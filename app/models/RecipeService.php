<?php
/**
 * Created by PhpStorm.
 * User: Charles
 * Date: 8/01/2018
 * Time: 11:05 AM
 */

namespace app\models;


use core\Model;

class RecipeService extends Model
{

    /**
     * @param Recipe $recipe
     */
    public function insert_recipe($recipe){
        $file_id = uniqid();
        $upload_path = $this->project_name . "/public/images/content/recipes/" . $file_id;
        $image_url = $this->base64_to_img($recipe->recipe_photo, $upload_path);
        $result = Array();
        if(!$image_url){
            $result['status'] = 409;
            $result['msg'] = "Fail to upload image.";
            return $result;
        }
        $recipename = $recipe->recipe_name;
        $course =  $recipe->course;
        $time = $recipe->cooking_time;
        $description = $recipe->description;
        $level = $recipe->level;
        $serving = $recipe->serving_number;
        $userid = $recipe->userid;
        $tags = $recipe->tags;
        $sql = "INSERT INTO recipes (`RecipeName`, `Image`, `Tags`, `Course`, `Description`, `EstimatedTime`, `UserID`, `Level`,`ServingNumber`, `CreatedTime`) VALUES (?,?,?,?,?,?,?,?,?,now());";
        $connection = self::getDB();
        $pstmt = $connection->prepare($sql);
        $pstmt->bind_param('sssssiisi', $recipename, $image_url, $tags, $course, $description, $time, $userid, $level, $serving);
        $pstmt->execute();
        if($pstmt->affected_rows==1){
            $result['status'] = 200;
            $result['msg'] = $pstmt->insert_id;
        }else {
            $result['status'] = 409;
            $result['msg'] = "Fail to upload.";
        }
        $pstmt->close();
        return $result;
    }

    /**
     * @param RecipeStep $step
     * @return array
     */
    public function insert_step($steps, $recipeid){
        $directory_path = $this->project_name . "/public/images/content/recipes/";
        $result = array();
        $fail_array = Array();
        $connection = self::getDB();
//            recipeid, stepnum, content, image
        $sql = "INSERT INTO `recipe_steps` VALUES(?,?,?,?);";
        $pstmt = $connection->prepare($sql);
        foreach ($steps as $step){
            $image_url = null;
            $upload_path = $directory_path . uniqid();
            if($step['hasphoto']){
                $image_url = $this->base64_to_img($step['image'], $upload_path);
                if(!$image_url){
                    $result['status'] = 409;
                    $result['msg'] = "Fail to upload image.";
                    return $result;
                }
            }
            else{
                $image_url = "";
            }
            $pstmt->bind_param('iiss', $recipeid, $step['stepnum'], $image_url, $step['content']);
            // this is wrong, because the value is pass-by-reference, but array elements are indirectly re-assigned
//        $pstmt->bind_param('iiss',  $step->recipe_id, $step->step_num, $image_url, $step->content);
            $pstmt->execute();
            if($pstmt->affected_rows==1){
                $result['status'] = 200;
                $result['msg'] = "Upload successfully.";
            }else {
                $result['status'] = 409;
                $result['msg'] = "Fail to upload.";
                array_push($fail_array, $step['stepnum']);
            }
        }
        $pstmt->close();
        if($result['status'] == 409)
            $result['msg'] = $fail_array;
        return $result;
    }

    public function insert_ingredient($ingredients, $recipeid)
    {
        $connection = self::getDB();
        $sql = "INSERT INTO recipe_ingredients VALUES(?,?,?)";
        $pstmt = $connection->prepare($sql);
        $count = 0;
        $successCount = 0;
        $result = Array();
        foreach ($ingredients as $item) {
            $pstmt->bind_param("iss", $recipeid, $item['name'], $item['quantity']);
            $pstmt->execute();
            $count += 1;
            if ($pstmt->affected_rows == 1)
                $successCount += 1;
        }
        $pstmt->close();
        if($count == $successCount){
            $result['status'] = 200;
            $result['msg'] = "Upload successfully.";
        }else {
            $result['status'] = 409;
            $result['msg'] = "Fail to upload.";
        }
        return $result;
    }

    public function getRecipe($recipeid)
    {
        $connection = self::getDB();

//        get recipe information
        $sql = "SELECT a.*, b.Avatar, b.`Username` FROM recipes AS a JOIN users AS b ON a.`UserID` = b.`UserID` WHERE RecipeID = ?";
        $pstmt = $connection->prepare($sql);
        $pstmt->bind_param('i', $recipeid);
        $pstmt->execute();
        $result = $pstmt->get_result();
        $row = $result->fetch_assoc();
        $recipe = new Recipe();
        $recipe->recipe_id = $recipeid;
        $recipe->recipe_name = $row['RecipeName'];
        $recipe->course = $row['Course'];
        $recipe->cooking_time = $row['EstimatedTime'];
        $recipe->create_time = $row['CreatedTime'];
        $recipe->level = str_replace("_", "", $row['Level']);
        $recipe->serving_number = $row['ServingNumber'];
        $recipe->tags = $row['Tags'];
        $recipe->recipe_photo = $row['Image'];
        $recipe->read_count = $row['ReadCount'];
        $recipe->comment_count = $row['CommentCount'];
        $recipe->like_count = $row['LikeCount'];
        $recipe->favorite_count = $row['FavoriteCount'];
        $recipe->made_count = $row['MadeCount'];
        $recipe->description = $row['Description'];
        $recipe->userid = $row['UserID'];
        $recipe->username = $row['Username'];
        $recipe->userphoto = $row['Avatar'];
        $pstmt->free_result();

//        get ingredients
        $ingredient_sql = "SELECT * FROM `recipe_ingredients` WHERE RecipeID = ?";
        $pstmt->prepare($ingredient_sql);
        $pstmt->bind_param('i', $recipeid);
        $pstmt->execute();
        $result = $pstmt->get_result();
        $ingredients = Array();
        while ($row = $result->fetch_assoc()){
            $ingredient = new RecipeIngredient($row['IngredientName'], $row['Quantity']);
            array_push($ingredients, $ingredient->get_property_assoc_array());
        }
        $pstmt->free_result();

//        get comments
        $comment_sql = "SELECT a.*, b.Username, b.Avatar FROM `recipe_comments` AS a JOIN `users` AS b ON a.UserID = b.UserID WHERE RecipeID = ?";
        $pstmt->prepare($comment_sql);
        $pstmt->bind_param('i', $recipeid);
        $pstmt->execute();
        $result = $pstmt->get_result();
        $comments = Array();
        while ($row = $result->fetch_assoc()) {
            $comment = new RecipeComment($row['CommentID'], $row['UserID'], $row['Username'], $row['CreatedTime'], $row['Content'], $row['Avatar']);
            array_push($comments, $comment->get_property_assoc_array());
        }
        $pstmt->free_result();

//        get steps
        $step_sql = "SELECT * FROM `recipe_steps` WHERE RecipeID = ? ORDER BY `StepNum`";
        $pstmt->prepare($step_sql);
        $pstmt->bind_param('i', $recipeid);
        $pstmt->execute();
        $result = $pstmt->get_result();
        $steps = Array();
        while ($row = $result->fetch_assoc()) {
            $step = new RecipeStep($row['RecipeID'], $row['StepNum'], $row['Content'], $row['Image']);
            array_push($steps, $step->get_property_assoc_array());
        }
        return array("recipe"=>$recipe->get_property_assoc_array(), "steps"=>$steps, "comments"=>$comments, "ingredients"=>$ingredients);
    }

    /**
     * @param RecipeComment $comment
     */
    public function insert_comment($userid, $content, $recipeid)
    {
        $sql = "INSERT INTO `recipe_comments`VALUES (NULL, ?, ?, NOW(), ?)";
        $connection = self::getDB();
        $pstmt = $connection->prepare($sql);
        $pstmt->bind_param('isi', $recipeid, $content, $userid);
        $pstmt->execute();
        $result = Array();
        if($pstmt->affected_rows==1){
            $result['status'] = 200;
            $result['msg'] = "Upload successfully.";
        }else {
            $result['status'] = 409;
            $result['msg'] = "Fail to upload.";
        }
        return $result;
    }

    public function get_popular_recipes()
    {
        $sql = "SELECT a.*, b.Username FROM recipes AS a JOIN users AS b ON a.UserID = b.UserID ORDER BY ReadCount LIMIT 0,8";
        $connection = self::getDB();
        $result = $connection->query($sql);
        $recipes = array();
        while ($row = $result->fetch_assoc())
        {
            $recipe = new Recipe();
            $recipe->recipe_id = $row['RecipeID'];
            $recipe->recipe_name = $row['RecipeName'];
            $recipe->create_time = $row['CreatedTime'];
            $recipe->recipe_photo = $row['Image'];
            $recipe->read_count = $row['ReadCount'];
            $recipe->comment_count = $row['CommentCount'];
            $recipe->like_count = $row['LikeCount'];
            $recipe->favorite_count = $row['FavoriteCount'];
            $recipe->username = $row['Username'];
            array_push($recipes, $recipe->get_property_assoc_array());
        }
        return $recipes;
    }

    public function get_recent_recipes()
    {
        $sql = "SELECT a.*, b.Username FROM recipes AS a JOIN users AS b ON a.UserID = b.UserID ORDER BY CreatedTime DESC LIMIT 0,8";
        $connection = self::getDB();
        $result = $connection->query($sql);
        $recipes = array();
        while ($row = $result->fetch_assoc())
        {
            $recipe = new Recipe();
            $recipe->recipe_id = $row['RecipeID'];
            $recipe->recipe_name = $row['RecipeName'];
            $recipe->create_time = $row['CreatedTime'];
            $recipe->recipe_photo = $row['Image'];
            $recipe->read_count = $row['ReadCount'];
            $recipe->comment_count = $row['CommentCount'];
            $recipe->like_count = $row['LikeCount'];
            $recipe->favorite_count = $row['FavoriteCount'];
            $recipe->username = $row['Username'];
            array_push($recipes, $recipe->get_property_assoc_array());
        }
        return $recipes;
    }

    //TODO similar
    public function get_similar_recipes()
    {
        $sql = "SELECT a.*, b.Username FROM recipes AS a JOIN users AS b ON a.UserID = b.UserID ORDER BY CreatedTime DESC LIMIT 0,5";
        $connection = self::getDB();
        $result = $connection->query($sql);
        $recipes = array();
        while ($row = $result->fetch_assoc())
        {
            $recipe = new Recipe();
            $recipe->recipe_id = $row['RecipeID'];
            $recipe->recipe_name = $row['RecipeName'];
            $recipe->create_time = $row['CreatedTime'];
            $recipe->recipe_photo = $row['Image'];
            $recipe->read_count = $row['ReadCount'];
            $recipe->comment_count = $row['CommentCount'];
            $recipe->like_count = $row['LikeCount'];
            $recipe->favorite_count = $row['FavoriteCount'];
            $recipe->username = $row['Username'];
            array_push($recipes, $recipe->get_property_assoc_array());
        }
        return $recipes;
    }

    public function get_carousel_recipes()
    {
        $sql = "SELECT a.*, b.Username FROM recipes AS a JOIN users AS b ON a.UserID = b.UserID ORDER BY ReadCount LIMIT 0,5";
        $connection = self::getDB();
        $result = $connection->query($sql);
        $recipes = array();
        while ($row = $result->fetch_assoc())
        {
            $recipe = new Recipe();
            $recipe->recipe_id = $row['RecipeID'];
            $recipe->recipe_name = $row['RecipeName'];
            $recipe->create_time = $row['CreatedTime'];
            $recipe->recipe_photo = $row['Image'];
            $recipe->read_count = $row['ReadCount'];
            $recipe->comment_count = $row['CommentCount'];
            $recipe->like_count = $row['LikeCount'];
            $recipe->favorite_count = $row['FavoriteCount'];
            $recipe->username = $row['Username'];
            array_push($recipes, $recipe->get_property_assoc_array());
        }
        return $recipes;
    }

    public function get_starter_left_recipe()
    {
        $sql = "SELECT a.*, b.Username FROM recipes AS a JOIN users AS b ON a.UserID = b.UserID WHERE Level = 'easy' LIMIT 0,1";
        $connection = self::getDB();
        $result = $connection->query($sql);
        $row = $result->fetch_assoc();
        $recipe = new Recipe();
        $recipe->recipe_id = $row['RecipeID'];
        $recipe->recipe_name = $row['RecipeName'];
        $recipe->create_time = $row['CreatedTime'];
        $recipe->recipe_photo = $row['Image'];
        $recipe->read_count = $row['ReadCount'];
        $recipe->comment_count = $row['CommentCount'];
        $recipe->like_count = $row['LikeCount'];
        $recipe->favorite_count = $row['FavoriteCount'];
        $recipe->username = $row['Username'];
        return $recipe->get_property_assoc_array();
    }

    public function get_starter_right_recipes()
    {
        $sql = "SELECT a.*, b.Username FROM recipes AS a JOIN users AS b ON a.UserID = b.UserID WHERE Level = 'easy' LIMIT 1,4";
        $connection = self::getDB();
        $result = $connection->query($sql);
        $recipes = array();
        while ($row = $result->fetch_assoc())
        {
            $recipe = new Recipe();
            $recipe->recipe_id = $row['RecipeID'];
            $recipe->recipe_name = $row['RecipeName'];
            $recipe->create_time = $row['CreatedTime'];
            $recipe->recipe_photo = $row['Image'];
            $recipe->read_count = $row['ReadCount'];
            $recipe->comment_count = $row['CommentCount'];
            $recipe->like_count = $row['LikeCount'];
            $recipe->favorite_count = $row['FavoriteCount'];
            $recipe->username = $row['Username'];
            array_push($recipes, $recipe->get_property_assoc_array());
        }
        return $recipes;
    }

    public function search_recipe($keyword, $pagenum)
    {
        $keyword = "%".$keyword."%";
        $sql = "SELECT a.*, b.Username FROM recipes AS a JOIN users AS b ON a.UserID = b.UserID WHERE RecipeName LIKE ? LIMIT ?,?";
        $start_page = ($pagenum-1)*10;
        $end_page = $pagenum*10;
        $connection = self::getDB();
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("sii", $keyword, $start_page, $end_page);
        $stmt->execute();
        $result = $stmt->get_result();
        $recipes = array();
        while ($row = $result->fetch_assoc())
        {
            $recipe = new Recipe();
            $recipe->recipe_id = $row['RecipeID'];
            $recipe->recipe_name = $row['RecipeName'];
            $recipe->create_time = $row['CreatedTime'];
            $recipe->recipe_photo = $row['Image'];
            $recipe->read_count = $row['ReadCount'];
            $recipe->comment_count = $row['CommentCount'];
            $recipe->like_count = $row['LikeCount'];
            $recipe->favorite_count = $row['FavoriteCount'];
            $recipe->username = $row['Username'];
            $recipe->description = $row['Description'];
            array_push($recipes, $recipe->get_property_assoc_array());
        }
        return $recipes;
    }
}