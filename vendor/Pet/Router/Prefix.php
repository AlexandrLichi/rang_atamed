<?php
namespace Pet\Router;

use Pet\Router\Router;

class Prefix{

    public int $id;

    public function __construct(int $id) {
        $this->id = $id;
    }


    function name($name){
        end(Router::$Route)['name'] = $name;
        return $this;
    }
    
    function group($name){
        end(Router::$Route)['group'] = $name;
        return $this;
    }

}
?>