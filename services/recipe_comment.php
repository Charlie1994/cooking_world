<?php
/**
 * Created by PhpStorm.
 * User: Charles
 * Date: 21/05/2017
 * Time: 6:49 AM
 */
include "error.php";
    session_start();
    include "conn.php";
    $recipeid = $_GET["recipeid"];
    $userid = $_SESSION["userid"];
    $content = $_GET["content"];
    $sql = "insert into comment values(null,'".$content."',now(),".$recipeid.",".$userid.")";
    $conn = MySQLDatabase::connect();
    if($conn->query($sql))
        echo "success";
    else
        echo "fail";
?>