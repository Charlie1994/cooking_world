<?php
/**
 * Created by PhpStorm.
 * User: Charles
 * Date: 30/05/2017
 * Time: 9:30 AM
 */
    include_once "conn.php";
    include "error.php";
    $num = $_GET["pagenum"];
    $pagenum = (((int)$num)-1) * 5;
    $str = "select * from activity limit $pagenum,5 ";
    $conn = MySQLDatabase::connect();
    $result = $conn->query($str);
    $myArray = array();
    while($row = $result->fetch_array(MYSQL_ASSOC)) {
        $myArray[] = $row;
    }
    echo json_encode($myArray);
?>