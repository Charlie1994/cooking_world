<?php

namespace core;
use app\Config;

/**
 * Base model
 *
 * PHP version 7.0
 */
abstract class Model
{
    private $data = array();
    public $project_name = "/cooking_world";

    static $connection = null;
    /**
     * Get the Mysqli database connection
     *
     * @return \mysqli
     */
    protected static function getDB()
    {
        if(!isset($connection)){
            $connection = mysqli_connect(Config::DB_HOST, Config::DB_USER,Config::DB_PASSWORD);
            if(!$connection){
                die('Not connected : '.mysqli_error($connection));
            }
            $dbConnectResult = mysqli_select_db($connection, Config::DB_NAME);
            if(!$dbConnectResult){
                die('Cannot use : '.mysqli_error($connection));
            }
            mysqli_query( $connection,"set character set 'UTF-8'");
        }
        return $connection;
    }

    protected function upload_file($upload_path)
    {
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
    }

    /**
     * base64_to_img() changes the base64 strings to files and save it based on given path.
     * @param $base64_string: the base64 formatted string
     * @param $upload_path: project name + relative path of the file
     * @return integer|boolean $result: false for failing to write the file and positive number for the number of bytes that are written in file.
     */
    protected function base64_to_img($base64_string, $upload_path)
    {
        // split the string on commas
        // $data[ 0 ] == "data:image/png;base64"
        // $data[ 1 ] == <actual base64 string>
        $data = explode( ',', $base64_string );
        $temp = str_replace("data:","", $data[0]);
        $temp = str_replace(";base64","", $temp);
        $temp = explode( '/', $temp );
        if($temp[0] == "image")
            $extension = "." . $temp[1];
        else
            $extension = "." . $temp[0];

        // open the output file for writing
        $ifp = fopen( realpath($_SERVER['DOCUMENT_ROOT']) . $upload_path . $extension, 'wb' );

        // we could add validation here with ensuring count( $data ) > 1
        $result = fwrite( $ifp, base64_decode( $data[ 1 ] ) );

        // clean up the file resource
        fclose( $ifp );

        if(!$result)
            return $result;
        else
            return $upload_path . $extension;
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }
        $trace = debug_backtrace();
        trigger_error(
            'Undefined property via __get(): ' . $name .
            ' in ' . $trace[0]['file'] .
            ' on line ' . $trace[0]['line'],
            E_USER_NOTICE);
        return null;
    }

    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function get_property_assoc_array()
    {
        return $this->data;
    }

}