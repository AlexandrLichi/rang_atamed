<?php


namespace Pet\Middleware;

use Pet\FrontClasses\FrontClasses;
use Pet\Request\Request;
use Pet\Router\Router;

class Middleware{

    /**
     *  Объект прослоки запросов 
     *  Хранит array(
     *               middleware =>[class, metchod?]||funtion   
     *               group?=> string || all
     *               name?=> string
     *              )
     *          
     *            
     */
    
   private static  array  $Middleware = [];


    public function __construct()
    {

    }

    public function name($name): Middleware
    {
        $len = count(Middleware::$Middleware) - 1;
        Middleware::$Middleware[$len]['name'] = $name;
        return  $this;
    }



    public function group($name):Middleware
    {
        $len = count(Middleware::$Middleware) - 1;
        Middleware::$Middleware[$len]['group'] = $name;
       
        return  $this;
    }



    public function setRout($Routers)
    {

        $Roters = func_get_args();
        $len = count(Middleware::$Middleware) - 1;
        foreach($Roters as $Prefix)

            Router::$Route[$Prefix->id]['group'] =  Middleware::$Middleware[$len]['group'];
    }


    private function set($middleware = [])
    {
        Middleware::$Middleware[] = ['middleware'=> $middleware];
        return $this;
    }
    



    // проверяет аттрибуты в middleware
    private function isAttr($connect)
    {
        if(!array_key_exists('middleware', $connect) ) return false;

        if(count($connect['middleware']) == 0) return false;
        return true;

    }



    public function reconciliation($Rout, Request &$Request )
    {
       
        foreach(Middleware::$Middleware as $key => $connect){
            
            if(!$this->isAttr($connect)) continue;

            $FrontClasses = new FrontClasses();

            if($this->separate('group', $Rout, $connect)){
              $FrontClasses->classStarted($connect['middleware'], $Request);
            }

            if($this->separate('name', $Rout, $connect)){
              $FrontClasses->classStarted($connect['middleware'], $Request);
            }

        }
    }



    private function separate($name , $Rout, $connect):bool{

        if(array_key_exists($name, $connect)){

            if($name == 'group' && strtolower($connect[$name]) == 'all')
                return true;

            if (array_key_exists($name, $Rout) && 
                $Rout[$name] == $connect[$name])

                return true;
            }

        return false;
    }



     static function middleware($middleware): Middleware{

           $class = new Middleware();
           $class->set($middleware);
           return $class;
     }
}

?>