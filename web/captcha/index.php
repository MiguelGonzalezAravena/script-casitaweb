<?php
header('Content-type: image/gif');

$texto = isset($_GET['id']) ? (int) $_GET['id'] : '-';

$t = substr($texto, 0, 4);

$img = imagecreatefromgif('fondo.gif');
$text_color = imagecolorallocate($img, 211, 95, 94);

imagestring($img, 35, 5, 5, $t, $text_color);
imagegif($img);
imagedestroy($img);

?> 