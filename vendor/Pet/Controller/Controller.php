<?php
namespace Pet\Controller;


class Controller{

    public $sesion;

    public function __construct(){
        
    }

    public function json($data = null){
      if($data){echo json_encode($data, JSON_UNESCAPED_UNICODE); exit;}
      echo json_encode(attr(),JSON_UNESCAPED_UNICODE);
    }

    public function  saveFile($name, $path):bool{
      return move_uploaded_file(files($name)['tmp_name'], $path);
    }
    

       
}



?>