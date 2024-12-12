<?php
namespace Pet\Middleware;

use Pet\Middleware\Middleware;
use Pet\Request\Request;
use Pet\Session\Session;

class  MiddlewareCSRF extends Middleware{

    protected $csrf  = true;
    private Session $Session;
    

    protected $method = ['POST', 'PUT', "DELETE"];

        public function __construct() {
            $this->Session = new Session();
        }

        /**
         * Проверяме csrf token
         * 
         */ 
    function check(Request $Request){
               
        // dd($Request->attribute);
        if(in_array($Request->method, $this->method)){

            if(!array_key_exists('token-csrf', $Request->attribute))
             $Request->redirect_code(400);
              
            if(!password_verify($this->Session->get('origin'), $Request->attribute['token-csrf']))
            $Request->redirect_code(400);
              
            
                //  Удаляем токен чтобы не маячил в атрибутах
                 unset($Request->attribute['token-csrf']);

            }else{

              if(!$this->isToken()) $this->Session->set($this->generateCSRF());
              
            }

    }

    static public function getCSFR(){
        return password_hash(session()->get('origin'), PASSWORD_DEFAULT);
    }

    function isToken(){
        return session()->get('token-csrf')? true:false;
    }

    private function generateCSRF():array
    {
          $uniqid = uniqid().uniqid();
          $date = date("Y-m-d");
          $time = date('H:i:s');
          $mktime = time();
          return ['date' => $date, "sec" =>$mktime ,"time" => $time, 'token-csrf' => password_hash($uniqid, PASSWORD_DEFAULT) , 'origin'=> $uniqid];
    }
    
}