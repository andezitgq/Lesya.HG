<?php
    
    session_start();
    
    $string = rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9);
     
    $_SESSION['rand_code'] = $string;
     
    $image = imagecreatetruecolor(300, 50);
    $black = imagecolorallocate($image, 0, 0, 0);
    $color = imagecolorallocate($image, 27, 70, 111); // red
    $white = imagecolorallocate($image, 255, 255, 255);
     
    imagefilledrectangle($image,0,0,399,99,$white);
    imagettftext ($image, 40, 0, 10, 40, $color, "./captcha1.ttf", $_SESSION['rand_code']);
     
    header("Content-type: image/jpeg");
    imagepng($image);
    
?>