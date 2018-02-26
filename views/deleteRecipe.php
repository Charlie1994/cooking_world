<?php
/**
 * Created by PhpStorm.
 * User: Charles
 * Date: 30/05/2017
 * Time: 5:38 PM
 */
    include_once "../services/conn.php";
    $recipeid = $_GET["recipeid"];
    $userid = $_GET["userid"];
    $str = "delete from recipe where RecipeID =".$recipeid;
    $conn = MySQLDatabase::connect();
    $conn->query($str);
    header( 'Location: my_recipes.php?userid='.$userid ) ;
?>