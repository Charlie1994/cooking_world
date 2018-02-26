<?php
session_start();
require_once "conn.php";
$conn = MySQLDatabase::connect();

$postArray = $_POST["json"];
//$postArray = "{\"cookTime\":\"1\",\"course\":\"1\",\"ingredients\":[{\"amount\":\"this\",\"name\":\"this\"}],\"introduction\":\"this\",\"level\":\"1\",\"mainImg\":\"../resource/images/content/592920bebc30c.jpg\",\"steps\":[{\"direction\":\"../resource/images/content/592920bebc30c.jpg\",\"name\":\"this\",\"stepNum\":1}],\"tags\":\"this\",\"title\":\"this\"}";
$de_json = json_decode($postArray,TRUE);


$title = $de_json['title'];
$main_img = $de_json['mainImg'];
$cook_time =(int)$de_json['cookTime'];
$level = $de_json['level'];
$course = (int)$de_json['course'];
$introduction = $de_json['introduction'];
$tags = $de_json['tags'];
//Array
$steps = $de_json['steps'];
$ingredients = $de_json['ingredients'];

$user_id = null;
////USERID
if (isset($de_json["userid"]))
    $user_id = $de_json["userid"];
else
    $user_id = $_SESSION["userid"];
// result and message
$uploadResult = 1;
$message = "";

//Sum of steps
$num_steps = count($steps);
//echo $num_steps;

//Insert data into the table - Recipe;
//Get the ID
$str = "insert into recipe VALUES (null,?,?,?,?,?,?,?,?,0,0,0,now())";
$stmt = $conn->prepare($str);
$stmt->bind_param("isssisii",$user_id,$title,$level,$main_img,$num_steps,$introduction,$cook_time,$course);
$stmt->execute();
if($stmt->affected_rows==1){
    $uploadResult = 1;
}else{
    $uploadResult = 101;
    $message = $stmt->error;
}
$query = "select last_insert_id()";
$result = mysqli_query($conn,$query);
while($row = $result->fetch_row()){
     $RecipeID = $row[0];
     $message = $RecipeID;
}

//Insert data into the table - Ingredients
$num_ing = count($ingredients);
for ($i=0;$i<$num_ing;$i++){
    $str2 = "insert into ingredient VALUES (null,?,?,?)";
    $stmt2 = $conn->prepare($str2);
    $stmt2->bind_param("iss",$RecipeID,$ingredients[$i]["name"],$ingredients[$i]["amount"]);
    $stmt2->execute();
//    echo $stmt2->error;
        if($stmt2->affected_rows==1){
            $uploadResult = 1;
        }else {
            $uploadResult = 101;
            $message = $stmt->error;
        }
}
//Insert data into the table - recipe_steps
for ($k=0;$k<$num_steps;$k++){
    $str3 = "insert into recipe_step VALUES (null,?,?,?,?)";
    $stmt3 = $conn->prepare($str3);
    $stmt3->bind_param('iiss',$RecipeID,        $steps[$k]["stepNum"],$steps[$k]["name"],$steps[$k]["direction"]);
    $stmt3->execute();
//    echo $stmt3->error;
    if($stmt3->affected_rows==1){
        $uploadResult = 1;
    }else {
        $uploadResult = 101;
        $message = $stmt->error;
    }
}

//Split the tags
$tag = explode(",",$tags);
for($index=0;$index<count($tag);$index++){
    $query = "select TagID from tag where TagName='".trim($tag[$index])."' limit 0,1";
    $result = mysqli_query($conn,$query);
    $row = mysqli_fetch_assoc($result);
    $tagid = 1;
    if(empty($row)) {
        $str4 = "insert into tag VALUES (null,?)";
        $stmt4 = $conn->prepare($str4);
        $stmt4->bind_param('s', $tag[$index]);
        $stmt4->execute();

        $queryTagid = "select TagID from tag where TagName=\'".$tag[$index]."\' limit 0,1";
        $resultTagid = mysqli_query($conn,$query);
        $tagid = mysqli_fetch_row($resultTagid);
        $resultTagid->close();
    }else{
        $tagid = $row["TagID"];
    }
    $str5 = "insert into tag_recipe_assoc VALUES (?,?)";
    $stmt5 = $conn->prepare($str5);
    $stmt5->bind_param('ii', $tagid,$RecipeID);
    $stmt5->execute();

    if($stmt5->affected_rows==1){
        $uploadResult = 1;
    }else {
        $uploadResult = 101;
        $message = $stmt->error;
    }
}
if($uploadResult==1){
    $sql = "update user set SharingRecipeNum = SharingRecipeNum + 1 where UserID = ".$_SESSION["userid"];
    $conn->query($sql);
}

$result->close();
if ($postArray==null)
    $message = "nullstring";
echo json_encode(array("result"=>$uploadResult,"message"=>$message));
//echo $postArray;
/**
 * Created by PhpStorm.
 * User: Edison
 * Date: 2017/5/17
 * Time: 10:51
 *
 */
?>