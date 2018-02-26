<?php
/**
 * Created by PhpStorm.
 * User: Charles
 * Date: 4/12/2017
 * Time: 7:12 PM
 */
    require_once 'error.php';
    require_once 'conn.php';
    $connection = MySQLDatabase::connect();
    echo phpinfo();
?>