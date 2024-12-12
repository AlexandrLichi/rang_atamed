<?php
namespace Pet\Logs;
class Logs{


    private $date;

    protected $clear_str = LOG_CLEAR;
    private $pathLog = __DIR__."/../../..".LOG_FOLDER."Log.txt";
    private $pathLogSql = __DIR__."/../../..".LOG_FOLDER . "LogSql.txt";

    public function __construct() {
        $this->date = date("d.m.Y H:i:s");
        $this->register_fatal();
    }

    function set($str){
        $LOG = ">>> TIME: {$this->date} MESS: {$str}  \n\r";
        $this->savelog($this->pathLog, $LOG);
    }

    function setSQL($str)
    {
        $LOG = ">>> TIME: {$this->date} SQL: {$str} \n\r";
        $this->savelog($this->pathLogSql, $LOG);
    }

    function listen_logs(){
        if(($er = error_get_last())){
                $this->set( $er['message']. ' FILE '.$er['file'].' Line: '.$er['line']);
        }
    }

    function register_fatal(){
        register_shutdown_function( function(){ $this->listen_logs();});
    }

    private function savelog(string $path, $data){

       switch($this->clear_str){
        case "everyday":
            if(date("d", filemtime($path)) != date("d")) $this->clear(); break;
       }

        file_put_contents($path, $data, FILE_APPEND | LOCK_EX);
    }



    function clear(){
        file_put_contents($this->pathLog, "");
        file_put_contents($this->pathLogSql, "");
    }

}
?>