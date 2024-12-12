<?php
namespace Pet\Request;

use Exception;
use Pet\Router\Router;

class Request{

    public array $attribute = [];
    public array $header = [];
    public string $method = '';
    public string $path = '';
    public string $RedirectPath = '';
    public array $parametr = [];
    public Router $Router;
    public $Redirect = [];
    public $error_code;

    public $file;
    public function __construct() {

       $this->method = $_SERVER['REQUEST_METHOD'];
       $this->attribute = $this->input();
       $this->path = $this->getPath();
       $this->header = $this->HeadersAll();
       $this->file = $this->file();

    }


    public function file(string $name = null){
        if (!$name) return $_FILES;
        if(array_key_exists($name, $_FILES)) return $_FILES[$name];
        return null;
    }

    function HeadersAll(){
        $H = [];

        foreach(getallheaders() as $keyH=>$value){
            $H[ucfirst($keyH)] = $value;
        }
        return $H;
    }

    public function input(string|null $name = null):string|array|null{
        $REQUEST = $this->json_decode_request();

        if(!$name) return $REQUEST;
        return array_key_exists($name, $REQUEST) ? $REQUEST[$name] : null;
    }

    public function getPath(){
        return str_contains($_SERVER['REQUEST_URI'], '?')? explode('?', $_SERVER['REQUEST_URI'])[0]:
        $_SERVER['REQUEST_URI'];
    }

    /**
     * PHP не парсит значения application/json 
     * тут нужно различные мутации добавить
     * */ 
    private function json_decode_request():array{
      
        $attribute = [];
        if(array_key_exists('CONTENT_TYPE', $_SERVER) == 'application/json'){
            try{
                $attribute =  json_decode(file_get_contents('php://input'), true);
               
            }catch(Exception $e){
                $attribute =['error'=> "no json content"];
            }
        }else{
            $parametrGet = [];
            foreach($_GET as  $key =>$attr){
                try{
                    $parametrGet[$key] = json_decode($attr, true);
                }catch(Exception $e){
                     $parametrGet[$key] = $attr;
                }
            }
            return array_merge($parametrGet, $_POST);
        }
      return gettype($attribute) != 'array'? array_merge($_GET, $_POST): $attribute;
    }

   

    

    static function create():Request{
        return new Request();
    }
    

    // Редирект 
    public function redirect($path){

        if(!in_array($path, $this->Redirect)){
            $this->RedirectPath = $path;
            $this->Redirect = [$path];
            $this->path = $path;
            $this->Router->query();

        }
    }

    public function location($path){
      
        header("location: ".$path);
    }

    
    // Redirect на страницу ошибки 
    public function redirect_code($code = null){
        $this->Router->error_code($code);
    }


    public function header($header = []){
        foreach($header as $key => $value){
            header("$key: $value;");
        }
    }

    public function response($data = null){

        echo !$data? json_encode($this->attribute):json_encode($data);
    }

    public function set($data = [], $exclude = [] ){
       
        foreach($data as $key => $value){
            if(in_array($key, $exclude)) continue;

            array_key_exists($key, $this->attribute)?
                $this->attribute[$key."_"]= $value:
                $this->attribute[$key] = $value;
    
        }
      
    }
}
?>