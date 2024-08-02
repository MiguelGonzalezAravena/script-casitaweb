<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $db_prefix, $context, $sourcedir, $user_settings, $boardurl, $ID_MEMBER;

require_once($sourcedir . '/FuncionesCom.php');

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($user_info['is_guest']) {
  fatal_error('Funcionalidad exclusiva de usuarios registrados.');
}

if (!$id) {
  fatal_error('Debes especificar el tema que deseas eliminar.');
}

$request = db_query("
  SELECT a.id, a.id_com, co.url, a.titulo, a.id_user
  FROM {$db_prefix}comunidades_articulos AS a, {$db_prefix}comunidades AS co
  WHERE a.id = $id
  AND a.id_com = co.id
  AND a.eliminado = 0
  LIMIT 1", __FILE__, __LINE__);

$row = mysqli_fetch_assoc($request);
$dasdasd = isset($row['id']) ? $row['id'] : '';
$url = $row['url'];
$vbvbvki = $row['id_user'];
$id_com = $row['id_com'];
$titulo = $row['titulo'];

mysqli_free_result($request);

if (empty($dasdasd)) {
  fatal_error('El tema especificado no existe.');
}

baneadoo($id_com);
permisios($id_com);

if ($context['permisoCom'] == 1 || $context['permisoCom'] == 2 || $vbvbvki == $ID_MEMBER) {
  db_query("
    UPDATE {$db_prefix}comunidades_articulos
    SET eliminado = 1
    WHERE id = $dasdasd
    LIMIT 1", __FILE__, __LINE__);

  db_query("
    UPDATE {$db_prefix}comunidades
    SET articulos = articulos - 1
    WHERE id = $id_com
    LIMIT 1", __FILE__, __LINE__);

  header('Location: ' . $boardurl . '/comunidades/' . $url . '/' . $dasdasd . '/' . $titulo . '.html');
} else {
  header('Location: ' . $boardurl . '/comunidades/');
}

?>