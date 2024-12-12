<?php

namespace Pet\View;

class View{
    static $dir = FOLDER_PROJECT."/view/";

    static function open($name){
        include_once(dirname(__DIR__,3)."/".View::$dir.$name.".php");
    }

}