<?php

namespace Pet\Validator;

class Validator{

    /***
     * 79778888888
    */
   static function telefon(string &$telefon):bool
    {
        $telefon = str_replace(['(', ')', '+', '-', ' '], '', $telefon);
        
        preg_match("/^(?:7|8)\d{10}$/", $telefon, $matches);
        return count($matches) > 0;
    }
}
?>