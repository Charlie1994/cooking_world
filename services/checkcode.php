<?php

session_start();
function random($len) {
    $srcstr = "1a2s3d4f5g6hj8k9qwertyupzxcvbnm";
    mt_srand();
    $strs = "";
    for ($i = 0; $i < $len; $i++) {
        $strs .= $srcstr[mt_rand(0, 30)];
    }
    return $strs;
}

// get random string
$str = random(4);

// the code pic width
$width  = 50;

// the code pic height
$height = 25;

// declare the format of the output picture
@ header("Content-Type:image/png");

// create a layer
$im = imagecreate($width, $height);

// background color
$back = imagecolorallocate($im, 0xFF, 0xFF, 0xFF);

// transition point
$pix  = imagecolorallocate($im, 187, 230, 247);

// font color
$font = imagecolorallocate($im, 41, 163, 238);

// set 1000 transition point on pic
mt_srand();
for ($i = 0; $i < 1000; $i++) {
    imagesetpixel($im, mt_rand(0, $width), mt_rand(0, $height), $pix);
}

// output string
imagestring($im, 5, 7, 5, $str, $font);

// output rectangle
imagerectangle($im, 0, 0, $width -1, $height -1, $font);

// output pic
imagepng($im);

imagedestroy($im);

$str = md5($str);

//选择 cookie
//SetCookie("verification", $str, time() + 7200, "/");

//选择 Session
$_SESSION["verification"] = $str;
?>