<?php
/**
 * Created by PhpStorm.
 * User: Charles
 * Date: 7/12/2017
 * Time: 10:20 PM
 */
error_reporting(-1); // reports all errors
ini_set("display_errors", "1"); // shows all errors
ini_set("log_errors", 1);
ini_set("error_log", "/tmp/php-error.log");
date_default_timezone_set("Australia/Brisbane");
require_once 'Router.php';
$myrouter = new \Core\Router();
try{
    $myrouter->dispatch("localhost/sajdk/asd/indfex?ajsh=1&ajhskd=23");
}catch (Exception $exception){
    echo $exception;
}
