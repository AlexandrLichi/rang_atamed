<?php
namespace Pet\Traits;

trait Assis{

    function isAssos(array $array):bool{
        return !in_array(0, array_keys($array));
    }

    function strescape($value)
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
         return gettype($value) == 'string'?$this->sql->real_escape_string($value): $value;
    }

    function equalKeys(array $array1,  array $array2):array{
        $result = [];
        $array2 = array_keys($array2);
        foreach($array1 as $key => $value)
          if(in_array($key, $array2)) $result[] = $key;
        return $result;
    }

}
?>