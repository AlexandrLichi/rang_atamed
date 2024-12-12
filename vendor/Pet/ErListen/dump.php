<?php

function dd($dump){
    include_once(__DIR__."/pageCode.php");
    exit;
}


function dm($dump)
{
   echo "<pre>".print_r($dump, true)."</pre>";
   exit;
}

?>