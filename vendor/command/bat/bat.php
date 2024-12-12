<?php

function osInput(&$output){
    if (PHP_OS == 'Linux'){
        exec('./vendor/command/bat/input.sh', $out);
        $output= $out[0];
    }
    if (PHP_OS == 'WINNT'){
        exec(__DIR__.'/bat/input.bat', $out);
        $output= $out[2];
    }
}
?>