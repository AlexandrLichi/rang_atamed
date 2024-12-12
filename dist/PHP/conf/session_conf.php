<?php

/**
 * Где хранить сессию
 * file - создает папку session где хранит json
 */

 define("SESSION", env("SESION", "file"));

/**
 * Папка сессии в корне проекта
 * 
 */
define("SESSION_FOLDER", env("SESION_FOLDER", "session"));
?>