<?php

namespace Pet\Router;

use Pet\FrontClasses\FrontClasses;
use Pet\Middleware\Middleware;
 
use Pet\Request\Request;
use Pet\Router\Prefix as RouterPrefix;

use Pet\Router\methods;

class Router extends Middleware{

    public Request $Request;
    static  $Route = [];


    public function __construct()
    {
        $this->Request = new Request();
    }

    
    static public function options($path,  $collback): RouterPrefix
    {
        Router::$Route[] = ["path" => $path, "method" => 'OPTIONS', "callback" => $collback];
        return new RouterPrefix(count(Router::$Route) - 1);
    }

    static public function get($path,  $collback ):RouterPrefix
    {
        Router::$Route[] = ["path" => $path, "method" => 'GET' , "callback" => $collback];
        return new RouterPrefix(count(Router::$Route) - 1);
    }


    static public function post($path,  $collback): RouterPrefix
    {
        Router::$Route[] = ["path" => $path, "method" => 'POST', "callback" => $collback];
        return new RouterPrefix(count(Router::$Route) - 1);
    }


    static public function delete($path,  $collback): RouterPrefix
    {
        Router::$Route[] = ["path" => $path, "method" => 'DELETE', "callback" => $collback];
        return new RouterPrefix(count(Router::$Route) - 1);
    }


    static public function put($path,  $collback): RouterPrefix
    {
        Router::$Route[] = ["path" => $path, "method" => 'PUT', "callback" => $collback];
        return new RouterPrefix(count(Router::$Route) - 1);
    }

    
   

    function getFile($path):bool{
        
        $control = false;
        if(preg_match('/\.(?:js|css)$/', $path, $matches)){

            header('Cache-Control: public, max-age=31536000');
            header('Pragma: cache');
            header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + 31536000));
            $control= true;
            header("Content-type: text/".str_replace('.',"", $matches[0])); 
        }
            
       if(preg_match('/\.(?:png|jpg|jpeg|webp)$/', $path, $matches)){
            $control= true;
            header('Cache-Control: public, max-age=31536000');
            header('Pragma: cache');
            header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + 31536000));
            header("Content-type: image/" . str_replace('.', "", $matches[0]));
       }

        if (preg_match('/\.(?:svg)$/', $path, $matches)) {
            $control = true;
            header('Cache-Control: public, max-age=31536000');
            header('Pragma: cache');
            header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + 31536000));
            header("Content-type: image/" . str_replace('.', "", $matches[0])."+ xml");
        }
     
    
    if($control){
       
        $path =  __DIR__. "/../../../".FOLDER_PROJECT .$path;

        $path = urldecode($path);

        if(file_exists($path)){

            echo file_get_contents($path);

        }else{
            header("Content-type: text/html");
            echo $path;
            $this->error_code(404);
        }
        
    }
    
    return $control;
    
    }

    public function query(){
    //    если запрашивают файл
       if($this->getFile($this->Request->path)) return;

        foreach(Router::$Route as $Rout){
            
            if($Rout["method"] == $this->Request->method ){


                if($Rout["path"] == $this->Request->path || $this->Request->path ==  $this->mutable_path($Rout["path"])){
                    $this->Request->Router = $this;
                    
                    parent::reconciliation($Rout, $this->Request);
                    
                    $FrontClasses = new FrontClasses();
                    
                    $FrontClasses->classStarted($Rout['callback'], $this->Request);

                    return;
                }

            }
          }

        //   http_response_code(404);
          $this->error_code(404);
         return;
        
    }


    protected function mutable_path($mutable):bool|string{

        if(preg_match_all("|{([a-z]{1,})}|", $mutable, $matches)){

             $regular = $mutable;

            foreach($matches[0] as $name){

                $regular = str_replace($name,"([a-zA-Z0-9?_-]{1,})", $regular);
            }

        if(preg_match("|$regular|", $this->Request->path, $result, PREG_UNMATCHED_AS_NULL)){
        
            foreach($matches[1] as $key=> $value){
                $this->Request->parametr[$value] = $result[$key + 1];  
            }
                   return trim($result[0]);
            }
       }

      return false;
    }


   public function error_code($code = null){
    
        http_response_code($code);
        $code && file_exists(__DIR__ . "/../../../" . ERROR_PAGE)?
        include_once(__DIR__ . "/../../../" . ERROR_PAGE):
        die("Not faund Router");
        exit;

    }

   public function fileRouter(array|string $files = []){

        if(gettype($files) == 'array'){
            foreach($files as $file) include_once($file);
        }else{
            include_once($files);
        }
        
        return $this;
    }

}
?>