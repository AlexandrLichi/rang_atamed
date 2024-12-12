<?php
namespace Controller\LoginController;

use Model\UserModel\UserModel;
use Pet\Controller\Controller;
use Pet\Cookie\Cookie;
use Pet\Request\Request;
use Pet\View\View;
use Pet\Inertia\Inertia;
use Pet\Session\Session;

class LoginController extends Controller{

    public $user;
    public $session;
    public $insert = true;
    public Cookie $cookie;

    public function __construct(){
        $this->user = new UserModel();
        $this->session = new Session();
        $this->cookie = new Cookie();
    }

    public function index(Request $request){
        Inertia::render('login', $request);
    }


    /**
     * Сверяем пароль
     * */ 
    public function check(Request $request)
    {
        $attribute = $request->attribute;
        $data = $this->user->getUserEmail($attribute['login']);

        if(count($data) == 1){

           
            if(password_verify($attribute['password'], $data[0]['password'])){

                $auth = ['auth' => uniqid()];
                cookie()->set(['enter'=>"1"]);
                
                $this->cookie->set(["auth"=>password_hash($auth['auth'], PASSWORD_DEFAULT)]);
                $this->user->findUpdate(['id'=>$data[0]['id']], $auth);
                $this->json(["enter"=>"Yes"]);
            }else{

                $this->json(['password' => 'Не верный пароль']);
            }
        }else{
            $this->json(['login' => 'Не верный логин/email']);
        }
    }
}
?>