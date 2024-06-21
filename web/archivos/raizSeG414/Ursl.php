<?php

function urls($url) {
  // Transformar todo a minúsculas
  $url = strtolower($url);

  // Reemplazar caracteres especiales latinos
  $url = str_replace('&quot;', '', $url);
  $find = array('á', 'é', 'í', 'ó', 'ú', 'ñ');
  $repl = array('a', 'e', 'i', 'o', 'u', 'n');
  $url = str_replace($find, $repl, $url);
  $finfg = array('<', '>');
  $repgf = array('', '');
  $url = str_replace($find, $repl, $url);

  // Añadir guiones
  $find = array(' ', '&', '\r\n', '\n', '+');
  $url = str_replace($find, '-', $url);

  // Eliminar y reemplazar otros caracteres especiales
  $find = array('/[^a-z0-9\-<>]/', '/[\-]+/', '/<[^>]*>/');
  $repl = array('', '-', '');
  $url = preg_replace($find, $repl, $url);
  $url = trim($url);

  return $url;
}

?>