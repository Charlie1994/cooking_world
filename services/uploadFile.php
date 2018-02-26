<?php
$allowedExts = array("gif", "jpeg", "jpg", "png");
$temp = explode(".", $_FILES["file"]["name"]);
$extension = end($temp);     // get the suffix of the file
$extension = strtolower($extension);
if(isset($_POST["extension"])){
    $extension = $_POST["extension"];
    $extension = substr($extension,2,strlen($extension)-3);
    $extension = strtolower($extension);
}
$result = '';   // for returning the result message of upload
$status = 0;    // return 0(fail) or 1(success)
if ((($_FILES["file"]["type"] == "image/gif")
        || ($_FILES["file"]["type"] == "image/jpeg")
        || ($_FILES["file"]["type"] == "image/jpg")
        || ($_FILES["file"]["type"] == "image/pjpeg")
        || ($_FILES["file"]["type"] == "image/png")
        || ($_FILES["file"]["type"] == "image/*"))
    && ($_FILES["file"]["size"] < 40960000) // the size of the file should be smaller than 4MB
    && in_array($extension, $allowedExts)
    )
{
    if ($_FILES["file"]["error"] > 0)
    {
        $result = $_FILES["file"]["error"];
    }
    else
    {
        // make the file name as unique as possible
        $file_name = uniqid() .".". $extension;
        if(move_uploaded_file($_FILES["file"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'].
            "/cooking_world/resource/images/content/" . $file_name)){
            $result = "../resource/images/content/".$file_name;
            $status = 1;
        }else{
            $result = "Fail to upload";
        }
    }
}
else
{
    $result = "Invalid file";
}
if (isset($_POST["stepnum"])){
    echo json_encode(array("result"=>$result,"status"=>$status,"stepnum"=>$_POST["stepnum"]));
}else{
    echo json_encode(array("result"=>$result,"status"=>$status));
}