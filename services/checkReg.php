<?php
    require_once "../services/conn.php";
    $type = $_GET["type"];
    $conn = MySQLDatabase::connect();
    if($type==1){
        $username = $_GET["username"];
        $str = "select COUNT(*) from user WHERE UserName = ".getEscapeStr($conn,$username);
        outputResult($conn,$str);
    }
    else if($type==2){
        $phone = $_GET["phone"];
        $str = "select COUNT(*)  from user WHERE MobilePhone = ".getEscapeStr($conn,$phone);
        outputResult($conn,$str);
    }
    else if($type==3){
        $email = $_GET["email"];
        $str = "select COUNT(*)  from user WHERE EmailAddress = ".getEscapeStr($conn,$email);
        outputResult($conn,$str);
    }else if($type==4){
        $vcode = $_GET["vcode"];
        $mvcode = $_SESSION["captcha"];
        if($vcode==$mvcode)
            echo true;
        else
            echo false;
    }

    function getEscapeStr($connection,$field){
        return "'" . mysqli_real_escape_string($connection,$field) . "'";
    }

    function outputResult($connection,$sql){
        if($s_result = mysqli_query($connection,$sql)){
            $s_result->data_seek(0);
            $row = $s_result->fetch_row();
            if($row[0]==0){
                // the field value is proved to be available
                $result = true;
                echo json_encode($result);
            }else{
                // the field value is proved to be unavailable
                $result = false;
                echo json_encode($result);
            }
            $s_result->free();
        }
    }
?>