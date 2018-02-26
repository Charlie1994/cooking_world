<?php
/**
 * Created by PhpStorm.
 * User: Charles
 * Date: 30/05/2017
 * Time: 3:57 PM
 */

    header('Content-type: app/json');
    echo json_encode(array("a"=>$_POST["test"]));
?>