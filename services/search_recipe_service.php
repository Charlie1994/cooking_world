<?php
    include "error.php";
    include_once "conn.php";
    class ViewRecipeKeyword{
    function getPageNum($keyword){
        $str = "SELECT count(*) FROM (
            SELECT distinct r.* from recipe r 
            LEFT JOIN tag_recipe_assoc tra on tra.RecipeID = r.RecipeID
            LEFT JOIN tag t on tra.TagID = t.TagID
            WHERE t.TagName like'%".$keyword."%' or recipeName like '%".$keyword."%' or Introduction like '%".$keyword."%' 
            UNION
            SELECT * from recipe
            WHERE RecipeID in (
		    SELECT RecipeID from ingredient
		    where IngredientName like '%".$keyword."%')) as tt";
        $conn = MySQLDatabase::connect();
        $number = (int)$conn->query($str)->fetch_row()[0];
        if ($number%5 == 0)
            $result = (int)($number/5);
        else
            $result = (int)($number/5+1);
        return $result;
    }

    function getCurrentPage($keyword,$num){
        $pagenum = (((int)$num)-1) * 5;
        $str = "SELECT DISTINCT * FROM (
            SELECT distinct r.* from recipe r 
            LEFT JOIN tag_recipe_assoc tra on tra.RecipeID = r.RecipeID
            LEFT JOIN tag t on tra.TagID = t.TagID
            WHERE t.TagName like'%".$keyword."%' or recipeName like '%".$keyword."%' or Introduction like '%".$keyword."%' 
            UNION
            SELECT * from recipe
            WHERE RecipeID in (
		    SELECT RecipeID from ingredient
		    where IngredientName like '%".$keyword."%')) as tt limit $pagenum,5";
        $conn = MySQLDatabase::connect();
        $result = $conn->query($str);
        return $result;
    }
    }


/**
 * Created by PhpStorm.
 * User: Edison
 * Date: 2017/5/21
 * Time: 12:44
 */
?>