<?php

require_once "conn.php";
$conn = MySQLDatabase::connect();

$activity_name = $_POST["activity"];
$holder = $_POST["name"];
$longitude = $_POST["longtitude"];
$latitude = $_POST["latitude"];
$contact = $_POST["contact"];
$introduction = $_POST["introduction"];
$time = $_POST["time"];
$address = $_POST["address"];
$str = "insert into activity(ActivityName,Holder,Contact,Longtitude,Latitude,Introduction,BeginTime,LocationName)
        VALUES 
        (?,?,?,?,?,?,?,?)";
$stmt = $conn->prepare($str);
$stmt->bind_param("sssddsss",$activity_name,$holder,$contact,floatval($longitude),floatval($latitude),$introduction,$time,$address);
if(!$stmt->execute()){
    die("error:".mysqli_error($conn));
}
else echo "Success";

mysqli_close($conn);
/**
 * Created by PhpStorm.
 * User: Edison
 * Date: 2017/5/20
 * Time: 12:32
 */