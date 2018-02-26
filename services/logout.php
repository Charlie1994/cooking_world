<?php
/**
 * Created by PhpStorm.
 * User: Charles
 * Date: 19/05/2017
 * Time: 8:00 PM
 */
    session_start();
    session_destroy();
    header("location:../views/homepage.html.twig");
?>