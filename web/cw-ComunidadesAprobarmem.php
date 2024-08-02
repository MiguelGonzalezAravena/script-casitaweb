<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $db_prefix, $context, $user_info, $sourcedir, $ID_MEMBER;

if ($user_info['is_guest']) {
  die('Funcionalidad exclusiva de usuarios registrados.');
}

$us = isset($_GET['m']) ? (int) $_GET['m'] : 0;

if (!$us) {
  die('Debes seleccionar el usuario a aprobar.');
}

$request = db_query("
  SELECT c.id, co.id AS id_com, co.url
  FROM {$db_prefix}comunidades_miembros AS c, {$db_prefix}comunidades AS co
  WHERE c.id = $us
  AND c.id_com = co.id
  AND c.aprobado = 0
  LIMIT 1", __FILE__, __LINE__);

$row = mysqli_fetch_assoc($request);
$dddd = $row['id'];
$url = $row['url'];
$id_com = $row['id_com'];

require_once($sourcedir . '/FuncionesCom.php');
permisios($id_com);

if ($context['permisoCom'] == 1) {
  db_query("
    UPDATE {$db_prefix}comunidades
    SET usuarios = usuarios + 1
    WHERE id = $id_com
    LIMIT 1", __FILE__, __LINE__);

  db_query("
    UPDATE {$db_prefix}comunidades_miembros
    SET aprobado = 1
    WHERE id = $dddd
    LIMIT 1", __FILE__, __LINE__);

  header('Location: ' . $boardurl . '/comunidades/' . $url . '/');
} else {
  die('No tienes permisos para realizar esta acci&oacute;n.');
}

?>