<?php
namespace Pet;

use Pet\Logs\Logs;
use Pet\Middleware\MiddlewareCSRF;
use Pet\Router\Router;

class Application{

    public Router $Router;
    private $csrf;
    private $error;
    
    public function __construct() {
        date_default_timezone_set(env('TIME_ZONE', 'Europe/Moscow'));
        $this->error = new Logs();
        $this->Router = new Router();
        $this->csrf = [MiddlewareCSRF::class,'check'];
    }



    function middleware($Midd = 0){
        
        $Midd = func_get_args();

        // default
        $Midd[] = $this->csrf;

        foreach($Midd as $middleware){
           
             Router::middleware($middleware)->group('All');
          
        }

        return $this->Router;
    }

    

    static function started(){
        $GLOBALS['App'] = new Application();
        return $GLOBALS['App'];
    }
    
}
?>