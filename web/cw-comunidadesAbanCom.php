<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $db_prefix, $context, $user_info, $sourcedir, $user_settings, $boardurl, $ID_MEMBER;

if ($user_info['is_guest']) {
  fatal_error('Funcionalidad exclusiva de usuarios registrados.');
}

require_once($sourcedir . '/FuncionesCom.php');

if (eaprobacion($ddddsaaat)) {
  fatal_error('Esperando aprobaci&oacute;n de Administrador.');
}

$id = isset($_GET['id']) ? seguridad($_GET['id']) : '';

if (empty($id)) {
  fatal_error('Debes seleccionar una comunidad.');
}

$request = db_query("
  SELECT id
  FROM {$db_prefix}comunidades
  WHERE url = '$id'
  AND bloquear = 0
  LIMIT 1", __FILE__, __LINE__);

$row = mysqli_fetch_assoc($request);
$dasdasd = $row['id'];
$dasdasd = isset($dasdasd) ? $dasdasd : '';

if (empty($dasdasd)) {
  fatal_error('Debes seleccionar una comunidad.');
}

baneadoo($dasdasd);
permisios($id_comvv);

if ($context['permisoCom'] == 1) {
  $request = db_query("
    SELECT id
    FROM {$db_prefix}comunidades_miembros
    WHERE id_com = $dasdasd
    AND rango = 1", __FILE__, __LINE__);

  $dddasd = mysqli_num_rows($request);

  if ($dddasd < 2) {
    fatal_error('No puedes dejar a la comunidad sin administrador.');
  }
}

db_query("
  DELETE FROM {$db_prefix}comunidades_miembros
  WHERE id_user = $ID_MEMBER
  AND id_com = $dasdasd
  LIMIT 1", __FILE__, __LINE__);

db_query("
  UPDATE {$db_prefix}comunidades
  SET usuarios = usuarios - 1
  WHERE id = $dasdasd
  LIMIT 1", __FILE__, __LINE__);

header('Location: ' . $boardurl . '/comunidades/' . $id . '/');

?>