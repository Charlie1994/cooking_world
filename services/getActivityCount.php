<?php
/**
 * Created by PhpStorm.
 * User: Charles
 * Date: 30/05/2017
 * Time: 9:29 AM
 */
    include_once "conn.php";
    $num = $_GET["page"];
    $pagenum = (((int)$num)-1) * 5;
    $str = "select COUNT(*)as count from activity";
    $conn = MySQLDatabase::connect();
    $result = $conn->query($str);
    $row = $result->fetch_assoc();
    $count = $row["count"];
    $result->close();
    if ($count%5==0)
        echo $count/5;
    else
        echo $count/5+1;
?>