<?php

session_start();

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

$str1 = generateRandomString(4);
$str2 = generateRandomString(4);
$captcha = $str1 . $str2;

//NOW we put up the answer to the session variable
$_SESSION['cap'] = strtolower($captcha);
//This would change the content type, or in english this would tell the browser that
//what ever retuened by this script is an image
header('Content-Type: image/png');

//Following code is to generate the image from the test
$img = imagecreatetruecolor(250,80);

//In the above code, the image size is set to 250x30
$white = imagecolorallocate($img,255,255,255);
$grey = imagecolorallocate($img,128,128,128);
$black = imagecolorallocate($img,0,0,0);

//Filling the rectangle with white as we need black text on white
imagefilledrectangle($img,0,0,250,80, $white);

$text = $captcha;
//THE below code is CRITICAL. This is the palce where we tell which font to use.
//Choose any ttf you like and name it as font.ttf or change the following code, make sue
//you put the path to the file correctly [i used STENCIL so that parsers will find it hard to detect]
//$font = "./font.ttf";
$font = "fonts/ARIALN.ttf";

imagettftext($img,20,45,25,50,$grey,$font,$str1);
imagettftext($img,20,-45,80,20,$grey,$font,$str2);
//imagettftext($img,20,0,10,20,$black,$font,$text);
//Creating a PNG image, i use png cuz i <3 png [really its so small and efficient ;)]
imagepng($img);
//And then remove the memory parts once the output is given
imagedestroy($img);

?>