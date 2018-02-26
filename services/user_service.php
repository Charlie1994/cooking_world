<?php
/**
 * Created by PhpStorm.
 * User: Charles
 * Date: 30/05/2017
 * Time: 3:53 AM
 */

    include_once "conn.php";
    class MyUser{

        function getUserInformation($userid){
            $conn = MySQLDatabase::connect();
            $str = "select ImageURL, UserName from user where UserID = ".$userid." limit 0,1";
            $result = $conn->query($str);
            $user = $result->fetch_assoc();
            return $user;
        }
        function getMyRecipes($userid){
            $conn = MySQLDatabase::connect();
            $str = "select * from recipe where UserID = ".$userid;
            $result = $conn->query($str);
            return $result;
        }
        function getMyFavorites($userid){
            $conn = MySQLDatabase::connect();
            $str = "select * from recipe as a join `favorite` as b on a.RecipeId=b.RecipeId where b.UserID = ".$userid;
            $result = $conn->query($str);
            return $result;
        }
        function uploadUserPhoto($photourl){

        }
    }
?>