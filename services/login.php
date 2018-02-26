<?php
//start session
session_start();
//Connect the database
require_once "conn.php";
$conn = MySQLDatabase::connect();

$result = 1;

$username = $_POST["username"];
$password = $_POST["password"];
$isrem = $_POST["isRem"];
$mUsername = "";
$mUserid = "";
$userurl = "";

if($username =="" || $password==""){
    $result = 104;
    exit;
}
else {
    $query = $conn->prepare("select UserID,Hash,Username,ImageURL from user where UserName = ? or MobilePhone = ? limit 0,1");
    $query ->bind_param("ss",$username,$username);
    $query ->execute();
    $query ->bind_result($mUserid, $Hash,$mUsername,$userurl);
    $query->fetch();
    if ($mUserid=="") {
        $result = 102;
    }
    else {
        if (password_verify($password, $Hash )==true) {
            $_SESSION['username'] = $mUsername;
            $_SESSION['userid'] = $mUserid;
            $_SESSION["userurl"] = $userurl;
            //Judge whether need to create cookie
            if($isrem==1){
                setcookie("username",$mUsername,time()+3600*24*30);
                setcookie("password",$password,time()+3600*24*30);
            }
            $result = 101;
        } else {
            //Wrong password
            echo "Wrong password";
            $result = 103;
        }
    }
    $query ->close();
}
if(isset($_POST["mobile"])){
    header('Content-type: app/json');
    echo json_encode(array("userName"=>$mUsername,"userPicUrl"=>$userurl,"userId"=>$mUserid,"loginStatus"=>$result));
}else{
    echo $result;
}
/**
 * Created by PhpStorm.
 * User: Edison, edited by Charles
 * Date: 2017/5/12
 * Time: 1:10
 */
?>