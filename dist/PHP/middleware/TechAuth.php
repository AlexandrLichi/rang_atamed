<?php
namespace Middleware\TechAuthMiddleware;

use Middleware\AuthMiddleware\AuthMiddleware;
use Pet\Controller\Controller;
use Pet\Inertia\Inertia;
use Pet\Request\Request;


class TechAuthMiddleware extends Controller{
    private $Auth;

    public function __construct() {
        $this->Auth = new AuthMiddleware();
    }
    
    function TechAuth(Request $request){
        $this->Auth->Auth($request);
        if(attr()['user']['job'] != 'system') $request->location('/admin');
 
        
    }
}
?>