<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $db_prefix, $tranfer1, $context, $settings, $options, $no_avatar, $txt, $user_settings, $user_info, $scripturl, $modSettings;

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$myuser = $ID_MEMBER;

if (empty($myuser)) {
  fatal_error('Funcionalidad exclusiva de usuarios registrados.');
}

if (empty($id)) {
  fatal_error('Debes seleccionar una nota a eliminar.', false);
}

$request = db_query("
  SELECT id_user
  FROM {$db_prefix}notas
  WHERE id_user = $myuser
  AND id = $id", __FILE__, __LINE__);

$contador = mysqli_num_rows($request);

if (empty($contador)) {
  fatal_error('La nota que deseas eliminar no existe.', false);
} else {
  db_query("
    DELETE FROM {$db_prefix}notas
    WHERE id = $id
    AND id_user = $myuser", __FILE__, __LINE__);

  header(`Location: $boardurl/mis-notas/`);
}

?>