<?php
namespace Pet\Migration;

use Exception;
use Pet\Migration\Table;
use mysqli;


class Schema{

    static $DB = DB_NAME;
    static $showQuery = false;

    static $commandAfter = [];
    static $join = [];


    static function create($name , callable $callable)
    {
        $table = new Table($name , Schema::$DB);

        $callable($table);

        Schema::$join[$name] = $table->indexName;

        foreach($table->foreign as $ind) Schema::$commandAfter[] = $ind;
        foreach($table->index as $ind) Schema::$commandAfter[] = $ind;

            if(count(Schema::query("SHOW TABLES LIKE '$name';")->fetch_all())== 0){
                
                Schema::query($table->build());
            }else{
                echo "таблица сушествует -- $name";
            }

    }

    static function drop($table)
    {
        $DB = Schema::$DB;
        Schema::query(" DROP TABLE `{$DB}`.`{$table}`;");
    }

    static function clear($table)
    {
   
        $DB = Schema::$DB;
        Schema::query("DELETE FROM `{$DB}`.`{$table}`;");
    }

    static function atler($tableName ,$nameAfter, callable $callable)
    {

        $DB = Schema::$DB;

        $table = new Table($tableName, Schema::$DB);

        $callable($table);

        $attr = implode(' ', $table->attribute);

        Schema::query("ALTER TABLE `{$DB}`.`{$tableName}` ADD {$attr} AFTER `$nameAfter`;");
    }

    static function deleteColumn( $table, $name)
    {
        $DB = Schema::$DB;
        Schema::query("ALTER TABLE `{$DB}`.`{$table}` DROP `$name`;");

    }


    static function setAfter(){
        try{
            foreach(Schema::$commandAfter as $query){
                Schema::query($query);
            }
        }catch(Exception $e){
            echo "Ключи и взаимосвязи созданы";
        }
    }

    static private function query(string $query){

        logs()->setSQL($query);

        $mysqli = new mysqli(DB_HOST, DB_LOGIN, DB_PASSWORD, Schema::$DB);


        if($mysqli->connect_error){

            echo "Error: ". $mysqli->connect_error;
        }else{

            if(Schema::$showQuery){
               echo $query;
               exit; 
            }
            
            $sql = $mysqli->query($query);
            $mysqli->close();
            return $sql;
        }
    }


}
?>