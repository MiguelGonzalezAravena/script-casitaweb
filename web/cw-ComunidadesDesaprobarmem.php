<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $db_prefix, $context, $user_info, $sourcedir, $ID_MEMBER, $boardurl;

$us = isset($_GET['m']) ? (int) $_GET['m'] : 0;

if ($user_info['is_guest']) {
  die('Funcionalidad exclusiva de usuarios registrados.');
}

if (!$us) {
  die('Debes especificar la solicitud.');
}

$request = db_query("
  SELECT c.id, co.id AS id_com, co.url
  FROM {$db_prefix}comunidades_miembros AS c, {$db_prefix}comunidades AS co
  WHERE c.id = $us
  AND c.id_com = co.id
  LIMIT 1", __FILE__, __LINE__);

$row = mysqli_fetch_assoc($request);
$id = $row['id'];
$url = $row['url'];
$id_com = $row['id_com'];

require_once($sourcedir . '/FuncionesCom.php');

permisios($id_com);

if ($context['permisoCom'] == 1) {
  db_query("
    DELETE FROM {$db_prefix}comunidades_miembros
    WHERE id = $id
    LIMIT 1", __FILE__, __LINE__);

  header('Location: ' . $boardurl . '/comunidades/' . $url);
} else {
  die('No tienes permisos para realizar esta acci&oacute;n.');
}

?>