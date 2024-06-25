<?php
require_once(dirname(__FILE__) . '/config-seg-cw1983.php');
require_once($sourcedir . '/Funciones.php');

function encodeurl($texto) {
  $texto = str_replace('+', '{ad12B}', $texto);
  $texto = str_replace(' ', '+', $texto);
  $texto = str_replace('?', '{vvE3F}', $texto);
  $texto = str_replace('/', '{Edv3A}', $texto);
  $texto = str_replace('#', '{4RE23}', $texto);
  $texto = str_replace('=', '{rpl3D}', $texto);
  $texto = str_replace('$', '{Rfe26}', $texto);
  $texto = str_replace(',', '{fce2C}', $texto);
  $texto = str_replace('<', '{fci3C}', $texto);
  $texto = str_replace('>', '{fco3E}', $texto);
  $texto = str_replace(';', '{vD13B}', $texto);
  $texto = str_replace('&', '{4rc24}', $texto);
  $texto = str_replace('~', '{jho7E}', $texto);
  $texto = str_replace('%', '{ds625}', $texto);

  return $texto;
}

$t = isset($_GET['q']) ? encodeurl($_GET['q']) : '';
$a = isset($_GET['v']) ? encodeurl($_GET['v']) : '';
$sort = isset($_GET['orden']) ? seguridad($_GET['orden']) : '';
$categoria = isset($_GET['categoria']) ? (int) $_GET['categoria'] : 0;
$categoria = $categoria <= 0 ? 0 : $categoria;
$usuario = isset($_GET['autor']) ? encodeurl($_GET['autor']) : '';
$usuario = empty($usuario) || $usuario == '-1' ? '' : $usuario;

if ($_GET['buscador_tipo'] == 'g') {
  header("Location: $boardurl/buscargoogle.php?cof=FORID%3A9&cx=015978274333592990658:r0qy7erzrbw&ie=UTF-8&sa=Buscar&q=$t");
} else {
  header("Location: $boardurl/buscador/&q=$t$a&autor=$usuario&orden=$sort&categoria=$categoria");
}

?>