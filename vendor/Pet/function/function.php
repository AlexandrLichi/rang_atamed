<?php
function search_file($path, $class = '')
{

    foreach (scandir($path) as $dir) {

        if ($dir == ".." || $dir == '.') continue;
        $dir = $path . "/" . $dir;
       
        if (is_dir($dir)) {

            search_file($dir, $class);
        } else {

            if (is_readable($dir)) {

                $file = file($dir);

                foreach ($file as $row)
                    if (str_contains($row, "class " . $class))
                        require_once($dir);
            }
        }
    }
}




function  include_dir($path, $prefix):array{
    $include = [];
    foreach (scandir($path) as $dir) {

        if ($dir == ".." || $dir == '.') continue;

        $dir = $path . "/" . $dir;
        if (is_dir($dir)) {
            include_dir($dir, $prefix);
        }else{
            if (is_readable($dir)) {
                preg_match("/([_0-9a-zA-Z]{1,})$prefix/", $dir, $matches);
                if(count($matches) != 0){
                    $include[] = $matches[0];
                    include_once($dir);
                }
            }
        }
    }

    return $include;
}
function  include_file($dirname, $file): array
{
    $include = [];
    foreach (scandir($dirname) as $dir) {

        if ($dir == ".." || $dir == '.') continue;
        $dir = $dirname . "/" . $dir;
        if (is_dir($dir)) {
            include_file($dir, $file);
        } else {
            if (is_readable($dir)  && $dir == $file)
                    include_once($dir);
                
            }
        }
    

    return $include;
}
?>