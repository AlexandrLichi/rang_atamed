<?php


function env($constans = null, $default = null){
  
   $env = file(dirname(__DIR__, 2).'/.env');

    foreach($env as $str){
        if (str_contains(trim($str), '#') && strpos(trim($str),"#") == 0) continue;
        if(str_contains($str, '=')){

            $param = explode('=', $str);

            if(trim($param[0]) == trim($constans)){
                return trim(str_replace([';','"',"'",],'', $param[1] ));
            }
        }

    }
    return $default;
}

function env_set($constans, $value ,$comment = ''){

    if(env($constans, false) === false){
        $data = "#$comment \n";
        $data .= "$constans = $value \n\r";
        file_put_contents(dirname(__DIR__, 2).'/.env', $data, FILE_APPEND);
    }else{
        $env = file_get_contents(dirname(__DIR__, 2).'/.env');
        $repl = preg_replace("/$constans(\s?)=(\s?)(.*?)(\n?\r?)/i","$constans = $value", $env);
      
        file_put_contents(dirname(__DIR__, 2).'/.env', $repl);
    }
}
?>