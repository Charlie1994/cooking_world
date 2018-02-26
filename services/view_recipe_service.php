<?php
    session_start();
    include_once "conn.php";
    class ViewRecipe{
        function getRecentComments(){
            $sql = "select * from user as a join comment as b on a.`UserID`= b.`userid` order by commenttime limit 0,6";
            $conn = MySQLDatabase::connect();
            $result = $conn->query($sql);
            return $result;
        }
        function getContributors(){
            $sql = "select * from user order by SharingRecipeNum desc limit 0,8";
            $conn = MySQLDatabase::connect();
            $result = $conn->query($sql);
            return $result;
        }
        function getCarousel(){
            $sql = "select * from recipe as a join user as b on a.UserID = b.UserID order by CommentCount limit 0,5";
            $conn = MySQLDatabase::connect();
            $result = $conn->query($sql);
            return $result;
        }
        function getPopularRecipes(){
            $sql = "select * from recipe as a join user as b on a.UserID = b.UserID order by ViewCount limit 0,8";
            $conn = MySQLDatabase::connect();
            $result = $conn->query($sql);
            return $result;
        }
        function getRecentRecipes(){
            $sql = "select * from recipe as a join user as b on a.UserID = b.UserID where Level = 1 order by RecipeID desc limit 0,4";
            $conn = MySQLDatabase::connect();
            $result = $conn->query($sql);
            return $result;
        }
        function getBeginnerRecipes(){
            $sql = "select * from recipe as a join user as b on a.UserID = b.UserID where Level = 1 order by ViewCount limit 0,5";
            $conn = MySQLDatabase::connect();
            $result = $conn->query($sql);
            return $result;
        }
        function getRecipeProfile($recipeid){
            $conn = MySQLDatabase::connect();
            $sql = "select * from recipe as a join user as b on a.UserID = b.UserID where RecipeID = ".$recipeid." limit 0,1";
            $result = $conn->query($sql);
            return $result;
        }
        function getRecipeSteps($recipeid){
            $conn = MySQLDatabase::connect();
            $sql = "select * from recipe_step where RecipeID = ".$recipeid." order by StepNum";
            $sql1 = "update recipe set ViewCount = ViewCount + 1 where RecipeID = ".$recipeid;
            $result = $conn->query($sql);
            $conn->query($sql1);
            return $result;
        }
        function checkIsFavorited($recipeid){
            $conn = MySQLDatabase::connect();
            $sql = "select * from favorite where recipeid = ".$recipeid." and userid = ".$_SESSION["userid"];
            $result = $conn->query($sql);
            if($result->num_rows>0){
                $result->close();
                return true;
            }else{
                $result->close();
                return false;
            }
        }
        function getIngredients($recipeid){
            $conn = MySQLDatabase::connect();
            $sql = "select * from ingredient where RecipeID = ".$recipeid;
            $result = $conn->query($sql);
            return $result;
        }
        function getSimilarRecipes($recipeid){
            $conn = MySQLDatabase::connect();
            $sql = "select distinct * from recipe where Course = (select Course from recipe where recipeid = ".$recipeid.") or Level = (select Level from recipe where recipeid = ".$recipeid.") or CookTime = (select CookTime from recipe where recipeid = ".$recipeid.") order by ViewCount,FavoriteCount,CommentCount desc limit 0,5";
            $result = $conn->query($sql);
            return $result;
        }
        function getTags(){
            $sql = "select a.TagID,TagName from tag as a join (select TagID,count(*) as count from tag_recipe_assoc group by TagID order by count desc limit 1,7)as b on a.TagID = b.TagID";
            $conn = MySQLDatabase::connect();
            $result = $conn->query($sql);
            return $result;
        }
        function getComments($recipeid){
            $sql = "select * from comment as a join user as b on a.userid = b.`UserID` where recipeid = ".$recipeid;
            $conn = MySQLDatabase::connect();
            $result = $conn->query($sql);
            return $result;
        }
    }
/**
 * Created by PhpStorm.
 * User: Charles
 * Date: 19/05/2017
 * Time: 9:57 AM
 */
?>