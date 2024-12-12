<?php
define("FOLDER_PROJECT", env("FOLDER_PROJECT", null));

/**
 * Настройка логов
 * */ 
define("LOG_CLEAR", env("LOG_CLEAR", "everyday"));

define("LOG_FOLDER", env("LOG_FOLDER", "/"));




define("CACHE_FOLDER", __DIR__.'/../../../'.env('CACHE_FOLDER', 'cache'));
/*
    Указать время хранения кеш в секундах 
*/ 

define("CACHE_TIMEOUT", env("CACHE_TIMEOUT", 60*60*24));

// Кеширование базы 1=да 2=нет

define("CACHE_DB", env("CACHE_DB", 1));
define("CACHE_SYSTEM", env("CACHE_SYSTEM", 1))


 ?>