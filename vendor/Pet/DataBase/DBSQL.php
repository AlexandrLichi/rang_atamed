<?php

namespace Pet\DataBase;

use Exception;
use mysqli;
use Pet\Traits\Assis;

class DBSQL{

    use Assis;

    public $table = 'parsers';
    private mysqli $sql;
    private bool $isConnect;
    public $nameBase = 'rewievs-pet';
    public $error;
    private $host;
    private $login;
    private $password;
    private $bound_by_keys = [ 'filial' => 'filial_id', 'parsers-list'=>'parser_id', 'company'=>'filial.company_id' ];
    public array $column = [];
    public $latesQuery;

    /**
    *@param mixed $host
    *@param mixed $login
    *@param mixed $password
    *@return void
    */ 
    public function __construct($host = null, $login = null, $password= null) {

       $this->host = $host? $host: DB_HOST;
       $this->login = $login ? $login : DB_LOGIN;
       $this->password = $password? $password: DB_PASSWORD;
       
       $this->isConnect = $this->connect();
       $this->column = $this->isConnect? $this->buildColumn($this->table):[];
       register_shutdown_function(array($this, 'close'));

    }


    function find(array $search = null, array|string $argument = null ){

        if($argument && gettype($argument) == 'string') $argument = [$argument];

        $find = "1";

        if($search){
         $find = '';
            if($argument){
                $resArg = [];
                foreach($argument as $arg) $resArg[$arg] = $search[$arg];
                $search = $resArg;
            }

            $names = array_keys($search);
            $values = array_values($search);

            foreach($names as $key => $name){
                if(in_array($name, $this->column)){
                    $find .= "`".$this->table."`.`".$name."` = '".$this->strescape($values[$key])."' AND ";
                }
            }
            $find = substr($find, 0, -4);
        }

        if($this->bound_by_keys){

            
            $column = [];
            $JOIN = "";

            foreach($this->column as $col){
                $column[] = "{$this->table}`.`$col";
            }   

            foreach($this->bound_by_keys as $table => $field){

                if(str_contains($field, '.')){
                    $exp = explode('.', $field);
                    $JOIN .= "JOIN `$table` ON `{$exp[0]}`.`{$exp[1]}`= `$table`.`id` ";

                }else{

                    $JOIN .= "JOIN `$table` ON `{$this->table}`.`$field`= `$table`.`id` ";
                }


                $columnJOIN = $this->buildColumn($table);

                // unset($columnJOIN['id']);

                foreach($columnJOIN as $join){

                    if(in_array($join, $this->column)){
                        $column[] = "$table`.`$join` as `$table"."_".$join;
                    }else{
                        $column[] = "$table`.`$join";
                    }
                }
            }

            // $JOIN = substr($JOIN, 0, -2);
            $this->column = $column;
        }


        $strColumn  = "`".implode('` , `', $this->column)."`";

        // dm($strColumn);
        $query = "SELECT $strColumn FROM `{$this->table}` $JOIN WHERE $find ;";
        dm($query);
        $this->sql->query($query);
    }
    








    function buildColumn($table):array
    {
        $result = [];

        if($columns = $this->query("SHOW COLUMNS FROM {$table}"))
        
        foreach($columns as $column) $result[] = $column['Field'];

        return $result;
    }



    function query(string $query):array|false
    {

        try{

          $result =  $this->sql->query($query);
          return $result->fetch_all(MYSQLI_ASSOC);

        }catch( Exception $e){

            $this->error = $e->getMessage();
            return false;
        }

    }




    public function connect(): bool
    {

        $this->sql = new mysqli(DB_HOST, DB_LOGIN, DB_PASSWORD, $this->nameBase);
      
        $this->error = $this->sql->connect_error;


        return $this->error ? false: true;

    }

    


    public function close()
    {
        if($this->sql) $this->sql->close();
    }


}