<?php
 namespace Pet\Migration;
use Pet\Migration\Schema;
class Migration {

    static $serilic;
    static $BD;

    public function __construct() {
     
    }

   public function setAfter(){
        Schema::setAfter();

        return Schema::$join;
    }




    function up(){

        Schema::create("sort", function($table){

            $table->id();
            $table->timestamp();
            $table->string("user")->null();

        });
    }

    function back(){
        Schema::drop("sort");
    }

}
?>