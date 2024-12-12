<?php
namespace Model\FilialModel;
use Pet\Model\Model;
use Pet\Request\Request;


class FilialModel extends Model{

    public $table = "filial";
    public $insert = false;
    public $join = ['company_id'];

    function filial($company_id = null){
        
        return $company_id? $this->find(['company_id' =>$company_id], ['id', 'filial', 'address', 'api', 'active']):[];
    }

    function findApi($api){
        return $this->find(['api'=> $api]);
    }

    function filialCompany($company_id){
        return $this->find(["company_id"=> $company_id], ['api', 'filial', 'address', 'id', 'imap']);
    }
    
    function name($id):string{
        return $this->find(["id" => $id], ['filial'])[0]['filial'];
    }
   
    function toggleActiv(){
       
        $mark = $this->find(['id'=> attr('toggle')['id']], ['platform_marketing'])[0];
       
        $mark = explode('//', $mark['platform_marketing']);
        $mark = array_diff($mark, array(''));
       
        // unset($mark[array_search('', $mark)]);
       
        
        if(attr('toggle')['activ'] == 1){
            array_push($mark, attr('toggle')['platform']);
        }else{

            $mark = array_diff($mark, [attr('toggle')['platform']]);
        }
      
        
        $mark = ['platform_marketing'=>  implode('//', $mark)];
        $this->insert = true;
        $this->findUpdate(['id' => attr('toggle')['id']], $mark);
    }
    

}
?>