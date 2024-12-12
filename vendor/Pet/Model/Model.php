<?php
namespace Pet\Model;
use Pet\DataBase\DB;
use Pet\FrontClasses\FrontClasses;

class Model extends DB{


    static private $search = [];
    static private $nameFunction = '';
    static private $valueFunction = null;
    public $insert = false;


    public $table = 'user';
    
    public $Latest = null;
    public $cross = "AND";
    public $BETWEEN = [];
    

    public function find(array $find = [] , $selects = [],  $join = [] , $order = ""):Array|string
    {
        if(!$this->connect()) return $this->error;
        
        
        $select = [];

        foreach($find as $name => $value){
            $table = $this->table;
            if(str_contains($name, '.')){
                $table = explode('.', $name)[0];
                $name = explode('.', $name)[1];
            }
            
                $select[] = "`{$table}`.`{$name}`='{$this->escapeStr($value)}'";
            
        }
        
        if($this->bettween() != '') $select[] = $this->bettween();

        $where = implode(" {$this->cross} ", $select);

        if($where =='') $where = 1;
        $where .= $order;
        $selects = $this->is_assos($selects)? array_keys($selects): $selects;
      
        $result =  $this->select($selects, $where, $join);

        if(gettype($result) != 'array'){
            logs()->set($result);
            return [ $result ];
        }
        
        $this->Latest = $result;
        return $result;
    }

    private function bettween():string{

        if(count($this->BETWEEN) !=0){
            $strBetween = '';
            foreach($this->BETWEEN as $key => $value){
                $strBetween .= "`$key` BETWEEN '".implode("' {$this->cross} '", $value)."' AND";
            }
            $strBetween = substr($strBetween, 0, -intval(strlen("AND")));
            return $strBetween;
        }else{
             return '';
        }
    }




    public function findJoin(array $column = [], $join = []){
        if (!$this->connect()) return $this->error;

        $select = [];

        foreach ($column as $name => $value) {
            $select[] = "`{$this->table}`.`{$name}`='{$this->escapeStr($value)}'";
        }

        return $this->select([], implode(" AND ", $select), $join);
    }



 

    /**
     * $condition return true Update else false insert;
     * **/ 
    public function findUpOrIn($find, $condition, $update):string
    {
        $finds = $this->find($find);
        
        if($condition($finds)){

           $update['id'] =  $finds[0]['id'];
            $this->update($update);
            if($this->errorSQL) return "ERROR";
            return "UPDATE";

        }else{
            $this->insert($update);
            if($this->errorSQL) return "ERROR";
            return "INSERT";
            
        }
       
        
    }



     public function findUpdate($find, $update)
     {

         $result = $this->find($find);
            
      if(count($result) != 0){

        foreach($result as $row){
            $update['id'] = $row['id'];
            $this->update($update);
        }

      }
        
    }


    public function findAndInset($findInsert):bool
    {

        $find = $this->find($findInsert);

        if(count($find) == 0){
            $this->insert($findInsert);
        }

        return count($find) == 0;
    }

    private function is_assos(array $array=[]):bool
    {


       return ! in_array(0, array_keys($array));
    }



}