<?php
namespace Pet\Migration;

class Table{

    private $primary = "";
    public $attribute = [];
    private $ENGINE = "ENGINE = InnoDB";
    public $foreign = [];
    private $charset = 'CHARSET=utf8 COLLATE utf8_general_ci';
    private $tableComment = "COMMENT = 'new table create'";
    public $index = [];
    private $table = '';
    private $DB;
    public $indexName = [];

   
    public function __construct($name_table, $DB){

        $this->table = $name_table;
        $this->DB = $DB;
    }


    /**
     * Аттрибуты  
     * */
    public function id(string $name = 'id'):Table{

        $this->attribute[] = " `$name` INT(60) NOT NULL AUTO_INCREMENT, ";
        $this->primary = " PRIMARY KEY (`$name`)";
        return $this;

    }



    public function text(string $name ):Table
    {
        $this->attribute[] = " `$name` TEXT NOT NULL ,";
        return $this;
    }

    public function string(string $name, int $length = 255): Table
    {
        $this->attribute[] = " `$name` VARCHAR($length) NOT NULL ,";
        return $this;
    }

     public function date(string $name= "date"): Table
     {
        $this->attribute[] = " `$name` DATE NOT NULL ,";
        return $this;

     }

    



    public function integer(string $name, int $length = 60):Table
    {

        $this->attribute[] = " `$name` INT($length) NULL ,"; 
        return $this;
    }




    public function index( $nameIndex = null):Table
    {

        $attr =  $this->attribute[count($this->attribute) - 1];
        preg_match("/`([-_a-zA-Z0-9]{1,})`/", $attr, $matches);

        if(!$nameIndex)  $nameIndex = $matches[1]."_ind";
        $this->indexName[] = $matches[1];
        $this->index[] = "ALTER TABLE `{$this->DB}`.`{$this->table}` ADD INDEX `{$nameIndex}` (`{$matches[1]}`);";
        return $this;
    }



    public function boolean(string $name):Table
    {

        $this->attribute[] = " `$name` BOOLEAN NULL ,";
        return $this;

    }

    public function timestamp(string $name = "date-time"):Table
    {

        $this->attribute[] = " `$name` DATETIME NULL DEFAULT CURRENT_TIMESTAMP ,";
        return $this;
    }

    public function tableComment(string $comment):void
    {
        $this->tableComment = "COMMENT = '$comment'";
    }


    public function storage(string $typebase = "InnoDB"):void
    {

        $this->ENGINE = " ENGINE = $typebase ";
    }

    /** 
     * Параметры атрибутов
     */ 

    public function null():Table
    {

       $attr =  $this->attribute[count($this->attribute) - 1];
       $attr  = str_replace( "NOT", "", $attr);

        $this->attribute[count( $this->attribute) - 1]
        = str_replace("NULL", "NULL DEFAULT NULL", $attr,);
        return $this;
    }



     public function default($default){

        $attr =  $this->attribute[count($this->attribute) - 1];
        
        $attr  = str_replace(",", "", $attr);

        if(gettype($default) == 'boolean'){
            $default? $attr.= "DEFAULT TRUE ,"
            : $attr .= "DEFAULT FALSE, ";
        }
        if (gettype($default) == 'string') $attr.="DEFAULT '$default' ,";

        
        $this->attribute[count($this->attribute) - 1] = $attr;

        return $this;

     }




    public function foreign(string $table, string $refer = 'id' , $referBase = null):Table
    {
        if(!$referBase) $referBase = $this->DB;
        $attr =  $this->attribute[count( $this->attribute) - 1];

        preg_match("/`([-_a-zA-Z0-9]{1,})`/",$attr, $matches);
        
        $this->foreign[] = "ALTER TABLE `{$this->table}` ADD FOREIGN KEY (`$matches[1]`) REFERENCES `{$referBase}`.`$table`(`{$refer}`) ON DELETE RESTRICT ON UPDATE RESTRICT;";
        return $this;
    }

    /*
    * @build string request Mysqli
    ALTER TABLE `pet-bd`.`userк` ADD INDEX `company_id` (`company_id`(60));
    */  

    function build():string
    {

       $attr = implode(' ', $this->attribute);

    //    if(count($this->foreign)) $attr .= implode(" ",$this->foreign);
      
        $build = " CREATE TABLE  `{$this->DB}`.`{$this->table}` ($attr {$this->primary}) {$this->ENGINE} {$this->charset} {$this->tableComment};";
        return $build;
    }

   

}
?>