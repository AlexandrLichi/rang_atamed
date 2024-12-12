<?php 
namespace Pet\Cache;

class Cache{


    public int $timeout = 60*60*10;
    public string $folderCache = CACHE_FOLDER;
    public $acpu_exits_function = false;
    public $headerResponceBool = false;
    public $CACHE_SYSTEM = CACHE_SYSTEM;
    public $CACHE_DB = CACHE_DB;

    public function __construct() 
    {
     $this->acpu_exits_function = 
     function_exists('apcu_add') && 
     function_exists('apcu_delete')&&
     function_exists('apcu_fetch')&&
     function_exists('apcu_exists')&&
     function_exists('apcu_clear_cache')&& 
     $this->CACHE_SYSTEM == 1;
    $this->createFolder();
    }

    function createFolder(){
        // if($this->$CACHE_DB);
    }


    function acpu_set($data, $key){
        if($this->acpu_exits_function){
           if(apcu_exists($key)) apcu_delete($key);
          return apcu_add($key, $data, $this->timeout)?$key:false;
        }
        return false;
    }



    public function timoutcachefile($file)
    {
      
        if(!file_exists($file)) return false;
        return time() > filemtime($file)+$this->timeout? unlink($file): false;
    }


    function acpu_get($key){
       if($this->acpu_exits_function){
            $this->headerResponceBool = true;
            return apcu_fetch($key);
       }else{
            return false;
       }
    }



    static function factory():Cache
    {
            return new Cache();
    }



    public function set($data, string $key , string $section = "")
    {

        if($this->acpu_exits_function){
            $keyacpu = $section != ''? $section."_".$this->cacheId($key):$this->cacheId($key);
    
            if($this->acpu_set($data, $keyacpu)) return $data;
        }else{
            
            if($section != '') $section = "/$section";
        
            if(!is_dir($this->folderCache."$section")) mkdir($this->folderCache.'/'.$section);
           
            file_put_contents($this->folderCache.$section.'\\'.$this->cacheId($key).".txt", serialize($data));
            return $data;
        }

    }

    public function get(string $key, $section = ''):mixed
    {

        if($this->acpu_exits_function){
            
            $keyapcu = $section != '' ? $section."_".$this->cacheId($key): $this->cacheId($key);
            $data = $this->acpu_get($keyapcu);
            if($data) return $data;

        }else{

            if($section != '') $section = '/'.$section;
            if(!is_dir($this->folderCache."$section")) return false;
            
            $nameFile = $this->folderCache.$section.'/'.$this->cacheId($key).".txt";
            if($this->timoutcachefile($nameFile)) return false;
    
            if(file_exists($nameFile)){
                $this->headerResponceBool = true;
                return unserialize(file_get_contents($nameFile));
            }else{
                return false;
            } 
        }

        return false;
    }


    public function cacheId(array|string $argum = [] )
    {
       $str = gettype($argum) == 'array'? implode('-', $argum): $argum;

       return md5($str);
    }


    public function clear_cache_all()
    {

        if($this->acpu_exits_function){
            apcu_clear_cache();
        }else{
            if(is_dir($this->folderCache)){
                foreach(scandir($this->folderCache) as $file){
                    if($file == '.'|| $file == '..') continue;
                    if(is_dir($this->folderCache.'/'.$file)){
                         foreach(scandir($this->folderCache.'/'.$file) as $filetxt){
                            if($file == '.'|| $file == '..') continue;
                             if(!is_dir($this->folderCache.'/'.$file.'/'.$filetxt))
                              unlink($this->folderCache.'/'.$file.'/'.$filetxt);
                         }  
                    }else{
                        unlink($this->folderCache.'/'.$file);
                    }
                }
            }
        }
    }

    

    public function clear_cache_section(string $section)
    {
        if($this->acpu_exits_function){
            foreach(apcu_cache_info()['cache_list'] as $apcu_cache){
              if(str_contains($apcu_cache['info'], $section.'_')){
                apcu_delete($apcu_cache['info']);
                return true;
            }
          }

        }else{

            if(is_dir($this->folderCache.'/'.$section)){
                foreach(scandir($this->folderCache.'/'.$section) as $file){
                    if($file == '.'|| $file == '..') continue;
                    if(file_exists($this->folderCache.'/'.$section.'/'.$file))
                       unlink($this->folderCache.'/'.$section.'/'.$file);
                    return true;
                }
            }
        }
        return false;

    }


    public function clear_cache_key(string $key, string $section = "")
    {
         if($this->acpu_exits_function){
            $keyacpu = $this->cacheId($key);
            if($section != '') $keyacpu = $section."_".$keyacpu;
            apcu_delete($keyacpu);
         }
         $file = $this->folderCache."/".$this->cacheId($key).".txt";
         if($section != '') $file =  $this->folderCache."/".$section."/".$this->cacheId($key).'.txt';
        if(file_exists($file)) unlink($file);
    }

    function __destruct()
    {
        if($this->headerResponceBool){

            header('Cache-Control: public, max-age=31536000');
            header('Pragma: cache');
            header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + 31536000));
        }else{
           
            header('Cache-Control: no-cache, no-store, max-age=0');
        }
    }

    public function time_exits($key, $time, $section = ''):bool
    {
        // $this->clear_cache_all();

        if($this->acpu_exits_function){
            $cache_time = apcu_key_info($key);
      
            if($cache_time === NULL) return false;
            return $time > $cache_time['creation_time'];

        }else{

              if($section != '') $section = $section.'/';
              $filepath = $this->folderCache."/$section".$key;

              
              if(!file_exists($filepath)) return false;
              
              
             return $time > filemtime($filepath) ;
        }
    }

    public function time_section_exits($section, $time){
        $bool = false;
       
        if($this->acpu_exits_function){
             foreach(apcu_cache_info()['cache_list'] as $apcu_cache){
              if(str_contains($apcu_cache['info'], $section.'_')){
                if($this->time_exits($apcu_cache['info'], $time)){
                    $bool = true;
                }
            }
          }

        }else{
            
            foreach(scandir($this->folderCache."/$section") as $key){

                if($key == '.' || $key == '..') continue;
                
                if($this->time_exits($key, $time, $section)){
                    $bool = true;
                }
            }
        }

        return $bool;
    }

}