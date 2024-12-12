<?php

namespace api;

use Exception;

class parser{

    public $url;


    public function __construct() {
        $this->url = $this->config();
    }

    function parse($name, string $sett_json){

    // ini_set('max_execution_time', '300');
    set_time_limit(300);

     $data = [$name => $sett_json];
    //  dm($data);
    //  dm($this->url);
     $objectParser = $this->POST($this->url, $data);
 
      // return $objectParser;
       $result = json_decode($objectParser, true);
       if($result){
        return $result;
      }else{
        return $objectParser;
      }

      //  logs()->set($objectParser);
       return false;
      
    }


   private function GET($url, $param = null){
        return file_get_contents($url . "?" . http_build_query($param));
    }

    private function POST($url, $data){

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $response = curl_exec($ch);

        curl_close($ch);
        return $response;

    }

    function config($url = true){

      $conf =  file(__DIR__."/.config");
      $result = [];
        foreach($conf as $row){
          if($row == '') continue;
          $result[trim(str_replace(["\n","\r"],"",explode('=', $row)[0]))] = trim(str_replace(["\n","\r"],"",explode('=', $row)[1]));
        }
      return $url? $result['PROTOCOL'].'://'.$result['IP'].'/'.$result['PATH']: $result;
    }

    function updateConfig(array $config){
        $new = '';
        foreach($config as $key=>$value) $new .= "$key=$value \n";
        file_put_contents(__DIR__."/.config", $new);
    }
    

}