<?php

declare(strict_types=1);

use Pet\Application;

require_once(__DIR__ . '/../vendor/autoload.php');

spl_autoload_register(function (string $class) {

       $classN  = array_reverse(explode("\\", $class))[0];
       $path = __DIR__ . "/PHP";
       search_file($path, $classN);
});


Application::started()->middleware()
       ->fileRouter(__DIR__ . '/PHP/router/router.php')
       ->query();
