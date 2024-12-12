<?php
namespace Middleware\AuthMiddleware;

use Pet\Cookie\Cookie;
use Pet\Controller\Controller;
use Pet\Inertia\Inertia;
use Pet\Request\Request;
Use Pet\Session\Session;
Use Model\UserModel\UserModel;


class AuthMiddleware extends Controller {
      private $cookie;

      public function __construct(){
            $this->cookie = new Cookie();
      }


    function Auth(Request $request){
            
          $session =   new Session();

          $token = $this->cookie->get("auth");
      
          if(!$token || $token == '') {
                 
                  $request->location('\login');
            }

            $users = (new UserModel())->getAllUsers();
            $control = -1;
           
            foreach($users as $i => $user){
                  
                  if(password_verify($user['auth'], $token)){
                        $control = $i;
                  }
                  if($control >= 0) break;
            }

            if ($control >= 0){

                  if($users[$control]['active'] == 0){$request->location('/');}

                  $request->set(['user'=> (new UserModel())->user($users[$control]['id'])]);
              

            }else{
                  cookie()->delete('enter');
                  cookie()->delete('auth');
                  $request->location('/login');
            }
      

    }


 
}
?>