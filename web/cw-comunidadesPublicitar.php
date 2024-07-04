<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $db_prefix, $context, $ID_MEMBER, $user_info, $sourcedir, $user_settings, $boardurl;

$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

if ($user_info['is_guest']) {
  fatal_error('Funcionalidad exclusiva de usuarios registrados.');
}

require_once($sourcedir . '/FuncionesCom.php');

if (!$id) {
  fatal_error('Debes especificar una comunidad.');
}

$request = db_query("
  SELECT id, credito, url
  FROM {$db_prefix}comunidades
  WHERE id = $id
  AND bloquear = 0
  LIMIT 1", __FILE__, __LINE__);

$row = mysqli_fetch_assoc($request);
$dasdasd = $row['id'];
$credito = $row['credito'];
$url = $row['url'];

mysqli_free_result($request);

if (!$dasdasd) {
  fatal_error('La comunidad especificada no existe.');
}

baneadoo($dasdasd);
permisios($dasdasd);

if ($context['permisoCom'] == 1) {
  if ($credito == '100') {
    fatal_error('Ya tienes cr&eacute;dito en publicidad.');
  } else if ($user_settings['posts'] > 499) {
    $time = time();

    db_query("
      UPDATE {$db_prefix}comunidades
      SET credito = 100, cred_fecha = $time
      WHERE id = $dasdasd
      LIMIT 1", __FILE__, __LINE__);

    db_query("
      UPDATE {$db_prefix}members
      SET posts = posts - 100
      WHERE ID_MEMBER = $ID_MEMBER
      LIMIT 1", __FILE__, __LINE__);
  } else {
    fatal_error('Para publicitar tu comunidad debes tener m&aacute;s de 500 puntos.');
  }
} else {
  fatal_error('No tenes permisos para publicitar esta comunidad.');
}

header(`Location: $boardurl/comunidades/$url/`);

?>