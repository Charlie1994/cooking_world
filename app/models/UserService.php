<?php
/**
 * Created by PhpStorm.
 * User: Charles
 * Date: 11/01/2018
 * Time: 6:56 PM
 */

namespace app\models;


use core\Model;

class UserService extends Model
{
    public function log_in($username, $password, $isrem)
    {
        $r = Array();
        $conn = self::getDB();
        $sql = "SELECT * FROM users WHERE Username = ?";
        $pstmt = $conn->prepare($sql);
        $pstmt->bind_param("s", $username);
        $pstmt->execute();
        $result = $pstmt->get_result();
        if ($result->num_rows<=0){
            $r['status'] = 401;
            $r['msg'] = "Your username or password is wrong.";
        }else{
            $row = $result->fetch_assoc();
            $hash = $row['Password'];
            if (!password_verify($password, $hash)){
                $r['status'] = 401;
                $r['msg'] = "Your username or password is wrong.";
            }else{
                $user = new User();
                $user->user_id = $row['UserID'];
                $user->username = $row['Username'];
                $user->photo = $row['Avatar'];
                $user->email = $row['Email'];
                $user->phone = $row['Phone'];
                $_SESSION['user'] = $user->get_property_assoc_array();
                //TODO is remembered password
//                if ($isrem)


                $r['status'] = 200;
                $r['msg'] = "Successfully log in.";
            }
        }
        return $r;
    }

    /**
     * @param User $user
     */
    public function insert_user($user, $hasphoto){
        $myuser = $user->get_property_assoc_array();
        $result = Array();
        $imgurl = "";
        if($hasphoto){
            $directory_path = $this->project_name . "/public/images/content/users/";
            $upload_path = $directory_path . uniqid();
            $imgurl = $this->base64_to_img($myuser['photo'], $upload_path);
            if(!$imgurl){
                $result['status'] = 409;
                $result['msg'] = "Fail to upload image.";
                return $result;
            }
        }
        $hash = $this->encrptPassword($myuser['password']);
        $connection = self::getDB();
        $sql = "INSERT INTO users VALUES(NULL, ?,?,?,?,?,?)";
        $pstmt = $connection->prepare($sql);
        $pstmt->bind_param("ssssss", $myuser['username'], $hash, $imgurl, $myuser['email'], $myuser['phone'], $myuser['paypal']);
        $pstmt->execute();
        if($pstmt->affected_rows==1){
            $result['status'] = 200;
            $result['msg'] = "Upload successfully.";
        }else {
            $result['status'] = 409;
            $result['msg'] = "Fail to upload.";
        }
        $pstmt->close();
        return $result;
    }

    protected function encrptPassword($pwd){
        // A higher "cost" is more secure but consumes more processing power
        $cost = 10;
        // Create a random salt
//        $salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');
//
//    // Prefix information about the hash so PHP knows how to verify it later.
//    // "$2a$" Means we're using the Blowfish algorithm. The following two digits are the cost parameter.
//        $salt = sprintf("$2y$%02d$", $cost) . $salt;

        $options = [
            'cost' => $cost ,
        ];
        // Hash the password with the salt
        $hash = password_hash($pwd,PASSWORD_BCRYPT,$options);
        return $hash;
    }

    public function sendVerifyMail($email)
    {
        $to = $email;
        $subject = "My subject";
        $txt = "Hello world!";
        $headers = "From: charles@cookingworld.com" . "\r\n";
        mail($to,$subject,$txt,$headers);
    }

    public function validateUsername($username)
    {
        $sql = "SELECT COUNT(*) as user_count FROM users WHERE LOWER (Username) = ?";
        $connection = self::getDB();
        $pstmt = $connection->prepare($sql);
        $pstmt->bind_param("s", $username);
        $pstmt->execute();
        $pstmt->store_result();
        $pstmt->bind_result($user_count);
        $pstmt->fetch();
        $pstmt->free_result();
        $pstmt->close();
        return ($user_count === 0) ? true : false;
    }

    public function validateEmail($email)
    {
        $sql = "SELECT COUNT(*) as user_count FROM users WHERE Email = ?";
        $connection = self::getDB();
        $pstmt = $connection->prepare($sql);
        $pstmt->bind_param("s", $email);
        $pstmt->execute();
        $pstmt->bind_result($user_count);
        $pstmt->fetch();
        $pstmt->free_result();
        $pstmt->close();
        return ($user_count === 0) ? true : false;
    }

    public function sendVerificationEmail()
    {

    }

    public function getActiveUsers()
    {
        $sql = "SELECT * FROM users ORDER BY PublishCount DESC LIMIT 0,8";
        $connection = self::getDB();
        $result = $connection->query($sql);
        $users = array();
        while($row = $result->fetch_assoc())
        {
            $user = new User();
            $user->username = $row['Username'];
            $user->photo = $row['Avatar'];
            $user->user_id = $row['UserID'];
            array_push($users, $user->get_property_assoc_array());
        }
        return $users;
    }
}