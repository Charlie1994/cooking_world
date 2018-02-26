<?php

/**
 * Class MySQLDatabase
 * @f
 */
    class MySQLDatabase{
        // use static
        static $link;
        static function connect(){
            if(!isset($link)){
                $config = parse_ini_file('../cooking_world_dbConfig.ini');
                $link = mysqli_connect('65.49.216.128', $config['username'],$config['password']);
                if(!$link){
                    die('Not connected : '.mysqli_error($link));
                }
                $db = mysqli_select_db($link, $config['dbname']);
                if(!$db){
                    die('Cannot use : '.mysqli_error($link));
                }
                mysqli_query( $link,"set character set 'UTF-8'");
            }
            return $link;
        }
    }
?>