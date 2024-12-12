<?php
namespace Model\ParsersModel;

use Model\CompanyModel\CompanyModel;
use Model\Parser_listModel\Parser_listModel;
use Pet\Model\Model;
use Pet\Request\Request;


class ParsersModel extends Model{

    public $table = "parsers";
    public $insert = false;
    public $join = ['filial_id'];

    function parsers($parserName){
      
       $column = ['parsers.id', 'filial.filial', 'parser_id', 'filial_id', 'company_id',  'filial.api', 'setting'];
       $id = (new Parser_listModel)->find(['name'=>$parserName], ['id', 'name'])[0];

      $parsers = $this->find(["parser_id"=>$id['id']], $column, ['filial'=>"filial_id"]);

      foreach($parsers as $key=>$filials){

            $parsers[$key]['company']['parser'] = $id['name'];
            $parsers[$key]['company'] = (new CompanyModel())->company($parsers[$key]['company_id'])[0]['company'];
      }

      return $parsers;
    }

    

    function updateSetting(Request $request){
   
      $this->insert = true;

      $setting = json_encode(attr()['setting']);
      $this->findUpdate(['id'=>attr()['id']], ['setting'=> $setting]);
      // dm(attr());
      echo json_encode($setting);
    }

    function listApiNameParser($api){
      $result = [];
      foreach($this->find(['api'=> $api], ['name'], ["parsers-list"=>"parser_id"]) as $name){
        if(!in_array($name['name'], $result)) $result[] = $name['name'];
      };
      return $result;
    }

    function getFilialApi($api, $f_id = null){

      $data = ['api' => $api];
      if($f_id) $data['filial_id'] = $f_id;

      $column = ['parsers.id', 'filial.filial', 'parser_id', 'filial_id', 'company_id',  'filial.api', 'setting', "parsers-list.name", "filial.platform_marketing"];
      $parsers = $this->find($data, $column, ['filial' => "filial_id", "parsers-list"=>"parser_id"]);
      return $parsers;

    }
    
    function toggleActiv(){
      $this->insert = true;
      $this->findUpdate(['id'=>attr('toggle')['id']], attr('toggle'));
    }



}
?>