<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $db_prefix, $context, $user_info, $sourcedir, $user_settings;

$us = isset($_POST['comun']) ? (int) $_POST['comun'] : 0;
$banear = empty($_POST['eliminar']) ? 0 : 1;
$desbanear = empty($_POST['restaur']) ? 0 : 1;
$razon = isset($_POST['razon']) ? seguridad($_POST['razon']) : '';

if ($user_info['is_admin'] || $user_info['is_mods']) {
  if (empty($us)) {
    fatal_error('Debes seleccionar la comunidad.');
  }

  $request = db_query("
    SELECT id, bloquear, url
    FROM {$db_prefix}comunidades
    WHERE id = $us
    LIMIT 1", __FILE__, __LINE__);

  $row = mysqli_fetch_assoc($request);
  $cdavvbv = $row['id'];
  $banccc = $row['bloquear'];
  $url = $row['url'];
  $cdavvbv = isset($cdavvbv) ? $cdavvbv : '';

  if (empty($cdavvbv)) {
    fatal_error('Comunidad no existente.');
  }


  if ($banccc && $desbanear) {
    db_query("
      UPDATE {$db_prefix}comunidades
      SET
        bloquear = 0,
        bloquear_razon = '',
        bloquear_por = ''
      WHERE id = $cdavvbv
      LIMIT 1", __FILE__, __LINE__);
  } else if ($banear && !$banccc) {
    if (empty($razon)) {
      fatal_error('La raz&oacute;n del baneo es obligatoria.');
    }

    if (strlen($razon) > 150) {
      fatal_error('La raz&oacute;n es demasiada larga.');
    }

    db_query("
      UPDATE {$db_prefix}comunidades
      SET
        bloquear = 1,
        bloquear_razon = '$razon',
        bloquear_por = '{$user_settings['realName']}'
      WHERE id = $cdavvbv
      LIMIT 1", __FILE__, __LINE__);
  }

  header('Location: ' . $boardurl . '/comunidades/' . $url . '/');
} else {
  fatal_error('No tienes permisos para estar ac&aacute;.');
}

?>