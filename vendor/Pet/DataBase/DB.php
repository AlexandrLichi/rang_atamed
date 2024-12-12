<?php
namespace Pet\DataBase;

use Exception;
use mysqli;
use Pet\Cache\Cache;
use Pet\Logs\Logs;

class DB{

    public mysqli|null $Mysql = null;
    public $table;
    public $limit = null;
    public $NameBase =  DB_NAME;
    public $error = false;
    public $insert = false;
    public $joinfix = 'id';
    public $errorSQL = null;
    public $CACHE_DB = CACHE_DB;

    
    public function __construct()
    {
        $this->reload_cache_modification_time();
    }


    function reload_cache_modification_time() {
        if ($this->CACHE_DB == 1) {

            $time =  $this->update_time();

            if (Cache::factory()->time_section_exits($this->table, $time)) {
                Cache::factory()->clear_cache_section($this->table);
            }
        }
    }
    

    function select(array $column = [], $where = 1, $join = [])
    {
        $nameCache = $this->table.implode('-', array_merge($column, $join)).$where;

        $data = $this->CACHE_DB == 1 ? Cache::factory()->get($nameCache, $this->table) : false;

        if(gettype($data) == 'array') return $data;
        

        if(!$this->Mysql) $this->connect();
           
        $query = "`".$this->escape("` , `", $column, 'select')."`";
       
        $joinStr = '';

        if(count($column) == 0)  $query = "*";
           
        if(count($join) > 0){

            foreach($join as $key => $value){
                $joinStr  .= "JOIN `{$key}` ON `{$this->table}`.`{$value}` = `$key`.`{$this->joinfix}`";
            }

        }
            $querySelect = "SELECT $query FROM `{$this->table}` $joinStr WHERE $where";
            $querySelect .= $this->limite();
      
        return $this->CACHE_DB == 1? 
                Cache::factory()->set($this->query($querySelect),  $nameCache, $this->table):
                $this->query($querySelect);
    }

    


    


    function insert(array $column = []):bool
    {
       if($this->CACHE_DB == 1) Cache::factory()->clear_cache_section($this->table);

            if (!$this->insert) return false;
    
            if(!$this->connect()) return $this->error;
    
             $name = array_keys($column);
             $table = $this->table; 
             $name = '`' . $this->escape('` , `', $name) . '`';
             $value = "'" . $this->escape("' , '", $column) . "'";
           
             $query ="INSERT INTO `$table` ( $name ) VALUE ( $value );";
            return   $this->query($query);
      

    }

    function count(array|string $where = '', string $column = "id",){

        if (!$this->connect()) return $this->error;


        if($where != ''){
            if(gettype($where) == 'array'){
                $where = "WHERE ". $this->escapeAssos(" AND ", $where);
            }else{
                $where = "WHERE ".$where;
            }
        }

        $query = "SELECT COUNT(`$column`) FROM `{$this->table}` $where" ;
        $result = $this->query($query);
        
        if(gettype($result) == 'array')
        return $result[0]["COUNT(`$column`)"];
        
        return $result;
    }

    

    function update(array $column = [], $where = ''){
        // dm($column);
       if($this->CACHE_DB == 1) Cache::factory()->clear_cache_section($this->table);

        if(!$this->insert) return false;
        if(!$this->connect()) return $this->error;

        $Set = '';

        foreach($column as $key => $value){
            if($key == 'id') $where .= "`id`='{$this->escapeStr($column['id'])}'";
            $Set .= " `$key`='{$this->escapeStr($value)}',";
        }

        $Set = substr($Set, 0, -1);
        $query = "UPDATE `{$this->table}` SET $Set WHERE $where";

        return $this->query($query);

    }

    function delete(array $column = [], $where = "")
    {
      if($this->CACHE_DB == 1)  Cache::factory()->clear_cache_section($this->table);

        if(!$this->insert) return false;

        if (!$this->connect()) return $this->error;

        if(array_key_exists('id', $column)){
        
         $where = " `id`='{$this->escapeStr($column['id'])}' ";
        }else{
            return false;
        }
        
        $query = "DELETE FROM `{$this->table}` WHERE ". $where;

       
        return $this->query($query);
    }

    function max(string $id = 'id'):int{

        if (!$this->connect()) return $this->error;

      $array = $this->query("SELECT max(`$id`) as `$id` from `{$this->table}`");

      if(count($array) != 0){
        return $array[0][$id];
      }
        return 0;
    }


    private function query($query)
    {
        logs()->setSQL($query);
        
        if (!$this->error) {
           
            $sql = $this->Mysql->query($query);
            
            return gettype($sql) == 'object'? $sql->fetch_all(MYSQLI_ASSOC): $sql;
            
        } 
            return $this->error;
        
    }
    function __destruct()
    {
        if($this->Mysql){
            $this->Mysql->close();
        }
    }


    private function escapeAssos($separate , $column){
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $str = [];

        foreach($column as $key=>$value){
           $value = $this->Mysql->real_escape_string($value);
            $str[] = "`$key`='$value'";
        }

        return implode($separate, $str);
    }


    private function escape($separate , $column, $type = null){
        
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $str = '';

        foreach($column as $value){
            
            if(gettype($value) == 'string'){

                if(str_contains($value, ".") && $type == 'select'){
                     $tableColumn =  explode('.', $value);
                     $value = "{$tableColumn[0]}`.`{$tableColumn[1]}"; 
                }
                if(!$this->Mysql) $this->connect();
                $str .= $this->Mysql->real_escape_string($value).$separate;
            }else{
                $str .= $value . $separate;
            }
        }

         return substr($str, 0, -intval(strlen($separate)));
    }
    



     function escapeStr($value)
    {
        if (!$this->Mysql) $this->connect();
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        if (gettype($value) == 'string') {
            return $this->Mysql->real_escape_string($value) ;
        } else {
            return $value;
        }

    }


    public function connect():bool{

        $this->Mysql = new mysqli(DB_HOST, DB_LOGIN, DB_PASSWORD, $this->NameBase);

       if($this->Mysql->connect_error){

            $this->error = $this->Mysql->connect_error;
            return false;
       }

        return true;
    }



    private function limite():string{
        if($this->limit) return " LIMIT ".$this->limit;
        return "";
    }

    private function update_time(){
        if($this->connect()){
            $DB = $this->NameBase;
            $tb = $this->table; 
            $time =  $this->query("SELECT UPDATE_TIME FROM information_schema.tables WHERE TABLE_SCHEMA = '$DB' AND TABLE_NAME = '$tb';");
            return $time[0]['UPDATE_TIME']?strtotime($time[0]['UPDATE_TIME']):time();
        }
        return 0;
    }
}

?>