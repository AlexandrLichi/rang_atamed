<?php
namespace Model\CompanyModel;
use Pet\Model\Model;

class CompanyModel extends Model{

    public $table = "company";
    public $insert = false;
    public $join = [];

    function company($id){
       return $this->find(['id'=>$id],['company', 'api', 'active', 'sample_bonus']);
    }

    function companyApi($api)
    {
        return $this->find(['api' => $api], ['company', 'api', 'active','id']);
    }

    
    function companyAll(){
        return $this->find();
    }
}
?>