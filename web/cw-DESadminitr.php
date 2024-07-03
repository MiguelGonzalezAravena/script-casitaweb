<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $tranfer1, $context, $user_settings, $user_info, $db_prefix, $ID_MEMBER;

$accion = isset($_GET['acion']) ? (int) $_GET['acion'] : 0;
$quienid = isset($_GET['user']) ? (int) $_GET['user'] : 0;

if ($user_info['is_guest']) {
  die('0: Funcionalidad exclusiva de usuarios registrados.');
}

if ($accion == 1 || $accion == 2) {
  if (empty($quienid)) {
    die('0: Debes seleccionar el usuario al cual deseas desadmitir.');
  }

  if ($ID_MEMBER == $quieid) {
    die('0: No te puedes desadmitir a ti mismo.');
  }

  $request = db_query("
    SELECT ID_MEMBER
    FROM {$db_prefix}members
    WHERE ID_MEMBER = $quienid
    LIMIT 1", __FILE__, __LINE__);

  $existeUser = mysqli_num_rows($request);

  if (empty($existeUser)) {
    die('0: El usuario seleccionado no existe.');
  }

  $fecha = time();

  if ($accion == 2) {
    db_query("
      INSERT INTO {$db_prefix}pm_admitir (id_user, quien, fecha)
      VALUES ($ID_MEMBER, $quienid, $fecha)", __FILE__, __LINE__);
  } else if ($accion == 1) {
    db_query("
      DELETE FROM {$db_prefix}pm_admitir
      WHERE quien = $quienid
      AND id_user = $ID_MEMBER", __FILE__, __LINE__);
  }

  die('1: OK');
}

die('0: ERROR');

?>