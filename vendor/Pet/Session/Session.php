<?php
namespace Pet\Session;

class Session{

    private $session;
    public string|null $id = null;
    private string $dir ;
    public $status;

    public function __construct() {

       $this->session = SESSION;
       $this->dir = __DIR__ . "/../../../" . SESSION_FOLDER;
       $this->status = $this->sessionStart();

    }

    function sessionStart(){
        if(session_status() != 2) session_start([ 'name' => "PETSESSION" ]);
        
        if(!$this->id = cookie()->get('device')){

            $this->id = session_id();

            cookie()->set(['device' => $this->id]);
        }

        return session_status();
    }

    public function set($data = []){

        foreach($data as $key => $value) $_SESSION[$key] = $value;
       

        $data['id'] = $this->id;
      
        if($this->session == 'file'){
            
            if(!$this->isId($this->id)){
               
              $new =  $this->getFile();
              $new['data'][] = $data;
              $this->setFile($new);

            }else{
                $this->replace($data);
            }
        }

        if ($this->session == 'base'){

        }
    }

    public function get($key):string|bool{

        $device = $this->getIdDevice($this->id);
    
        if(array_key_exists($key, $device)) return $device[$key];
        if(!array_key_exists($key, $_SESSION)) return false;

        return $_SESSION[$key];
    }


    private function getFile(){

        if(!is_dir($this->dir))mkdir($this->dir);
        if(!file_exists($this->dir ."/session.json")) file_put_contents($this->dir . "/session.json",'{ "data":[] } ');
        return json_decode(file_get_contents($this->dir . "/session.json"), true);

    }


    private function setFile($file){
        
        file_put_contents($this->dir . "/session.json", json_encode($file));
     }

    

    private  function isId($id):bool
    {
        $file = $this->getFile();

        foreach($file['data'] as $i => $session)
        if(array_key_exists('id', $session) && $session['id'] == $id) return true;
        
        return false;
    }



    private  function replace($data =[]){

        $dataFile = $this->getFile();

        foreach($dataFile['data'] as $i => $session){
            if($data['id'] == $session['id']){
                foreach($data as $key=>$value) $dataFile['data'][$i][$key] = $value;
            } 
        }
        
        $this->setFile($dataFile);
    }

    function getIdDevice($id){
        $dataFile = $this->getFile();
        $result = [];
    
        foreach ($dataFile['data'] as $i => $session){
            if ($id == $session['id']) $result = $session;
        }
      
        return $result;
    }
}
?>