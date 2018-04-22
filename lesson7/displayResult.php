<?php

$image = imagecreatefrompng(__DIR__.'/certificate.png');

$blue = imagecolorallocate($image, 0, 0, 255);

$certHeader = 'User '.$_GET['userName']. ' has passed the test '.$_GET['testName'];
$certResult = 'Test result is '.$_GET['score']. ' correct answers out of '.$_GET['maxScore'];

imagettftext($image, 36, 0, 500, 300, $blue, __DIR__.'/vera.ttf', $certHeader);
imagettftext($image, 36, 0, 500, 500, $blue, __DIR__.'/vera.ttf', $certResult); 

header('Content-type: image/png');

imagepng($image, NULL, 6, PNG_ALL_FILTERS);
imagedestroy($image);