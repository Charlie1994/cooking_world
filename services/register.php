<?php
    require_once "conn.php";
    include "Utils.php";
    $utils = new Utils();
    $conn = MySQLDatabase::connect();
    $username = $_POST['username'];
    $hash = $utils->encrptPassword($_POST['password']);
    $MobilePhone = $_POST['phone'];
    $Email = $_POST['email'];
    $gender = $_POST['gender'];
    $avatar = "../resource/images/content/default_avatar.png";
    $str = "insert into user VALUES (null,?,?,?,?,?,?,0)";
    $stmt = $conn->prepare($str);
    $stmt->bind_param("sssssi",$username,$hash,$avatar,$MobilePhone,$Email,$gender);
    $stmt->execute();
    echo $stmt->error;
    if($stmt->affected_rows==1){
        echo "success";
    }else{
        echo "fail";
    }
    $stmt->close();
?>
    
    

