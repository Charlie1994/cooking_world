<?php

/**
 * Created by PhpStorm.
 * User: Charles
 * Date: 14/05/2017
 * Time: 9:54 AM
 */
class Utils
{
    function encrptPassword($pwd){
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
}
?>