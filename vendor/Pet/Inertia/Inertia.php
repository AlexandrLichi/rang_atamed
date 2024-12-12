<?php
namespace Pet\Inertia;

use Pet\Middleware\MiddlewareCSRF;
use Pet\Request\Request;
use Pet\View\View;


class Inertia{


  static public $name = 'home';
  static public $request = 'home';
  public $pages;
  public $data = '';
  public $push = '/';
  public $typeHTML = ["text/html", "application/xhtml+xml", "application/xml"];
  public $typeJSON = ["text/json", "application/json", "text/x-json"];


  public function __construct($name, Request|null $request) {

    $this->pages = $name;
    $this->is_type_request($request);

  }



 private function renderHTML(Request|null $request){

   self::$name = $this->pages;
   self::$request = json_encode($request->attribute , JSON_UNESCAPED_UNICODE);
  //  include_once('http://localhost:8000/dist/view/index.php');
    include_once(str_replace('//',"/", dirname(__DIR__, 3)."/".FOLDER_PROJECT."/view/index.php"));
 }

private function getOriginalPath(Request|null $request){
  return $request->RedirectPath == ''? $request->path: $request->RedirectPath;
}

 private function returnJSON(Request|null $request){

    header("Inertia: {$this->pages}");
    header("Inertia-path: {$this->getOriginalPath($request)}");

    $token = MiddlewareCSRF::getCSFR();
    header("x-csrf-token: $token");
    echo json_encode($request->attribute, JSON_UNESCAPED_UNICODE);
 }



 private function is_type_request(Request $request){

      $headerControl = false;

      if(array_key_exists('Accept',$request->header)|| array_key_exists('Content-type', $request->header)){

        foreach($this->typeHTML as $value){

          if(array_key_exists('Accept', $request->header) && 
          str_contains($request->header['Accept'], $value)) $headerControl = true;

          if(array_key_exists('Content-type', $request->header) && 
          str_contains($request->header['Content-type'], $value)) $headerControl = true;
        }
      

      if($headerControl) return $this->renderHTML($request);


      foreach ($this->typeJSON as $value) {

        if (array_key_exists('Accept', $request->header) && 
        str_contains($request->header['Accept'], $value)) $headerControl = true;
        
        if (array_key_exists('Content-type', $request->header) && 
        str_contains($request->header['Content-type'], $value)) $headerControl = true;

      }
    }
    

    if($headerControl) return $this->returnJSON($request);

    // Возвращаем заголовки при непонятном ответе;
    echo '<h3>No type heder request in Inertia </h3>';
    return dd($request->header);
 }



  /*
   * Стартуем Инертия
   * 
   */ 
  static function render(string $name, Request $request){
        return new Inertia($name , $request);
  }
}
?>