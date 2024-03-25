<?php

function urls($url) {// Tranformamos todo a minusculas

$url = strtolower($url);

//Rememplazamos caracteres especiales latinos

$url = str_replace ("&quot;", "", $url);
$find = array('á', 'é', 'í', 'ó', 'ú', 'ñ');

$repl = array('a', 'e', 'i', 'o', 'u', 'n');

$url = str_replace ($find, $repl, $url);

$finfg = array('<', '>');

$repgf = array('', '');

$url = str_replace ($find, $repl, $url);

// Añaadimos los guiones

$find = array(' ', '&', '\r\n', '\n', '+');
$url = str_replace ($find, '-', $url);

// Eliminamos y Reemplazamos demás caracteres especiales

$find = array('/[^a-z0-9\-<>]/', '/[\-]+/', '/<[^>]*>/');

$repl = array('', '-', '');

$url = preg_replace ($find, $repl, $url);
$url=trim($url);
return $url; }

?>