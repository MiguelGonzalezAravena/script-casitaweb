<?php

function urls($url) {// Tranformamos todo a minusculas

$url = strtolower($url);

//Rememplazamos caracteres especiales latinos

$url = str_replace ("&quot;", "", $url);
$find = array('�', '�', '�', '�', '�', '�');

$repl = array('a', 'e', 'i', 'o', 'u', 'n');

$url = str_replace ($find, $repl, $url);

$finfg = array('<', '>');

$repgf = array('', '');

$url = str_replace ($find, $repl, $url);

// A�aadimos los guiones

$find = array(' ', '&', '\r\n', '\n', '+');
$url = str_replace ($find, '-', $url);

// Eliminamos y Reemplazamos dem�s caracteres especiales

$find = array('/[^a-z0-9\-<>]/', '/[\-]+/', '/<[^>]*>/');

$repl = array('', '-', '');

$url = preg_replace ($find, $repl, $url);
$url=trim($url);
return $url; }

?>