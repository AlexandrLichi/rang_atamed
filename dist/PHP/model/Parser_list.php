<?php
namespace Model\Parser_listModel;
use Pet\Model\Model;
use Pet\Request\Request;


class Parser_listModel extends Model{

    public $table = "parsers-list";
    public $insert = false;
    public $join = [];

    function getlist(){
        return $this->find([],['name', 'id', 'query', 'default_url']);
    }

    function setParser($column){
        $this->insert = true;
        $this->insert($column);
    }

    
}
?>