<?php

use Pet\Application;
use Pet\Cookie\Cookie;
use Pet\Logs\Logs;
use Pet\Request\Request;
use Pet\Router\Router;
use Pet\Session\Session;

function app():Application{ return $GLOBALS['App'] ;};
function router():Router{ return app()->Router; };
function request():Request{ return router()->Request;};

function attr(string $str= null):array|string|null {  
        if($str){
            return array_key_exists($str, request()->attribute)? request()->attribute[$str] :null;
        }else{
            return request()->attribute ;
        }};
        
function logs():Logs { return new Logs();};

function cookie($str = null): Cookie|string|null
{
    // dm(var_dump($str));
    return $str !== null? (new Cookie())->get($str): new Cookie();
};

function session(): Session {return new Session();};
function supple(string $str = null):array|string {return $str? request()->parametr[$str]: request()->parametr;};
function files(string $name = null){ return request()->file($name) ;}
?>