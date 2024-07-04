<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $context, $db_prefix, $user_settings, $user_info, $ID_MEMBER;

$user = isset($_POST['user']) ? seguridad($_POST['user']) : '';

if ($user_settings['ID_GROUP'] == 7 || $user_settings['ID_GROUP'] == 11 || ($user_info['is_admin'] || $user_info['is_mods'])) {
  if (empty($user)) {
    die('0: Debes seleccionar a un usuario.');
  }

  $request = db_query("
    SELECT ID_POST_GROUP, ID_MEMBER, ID_GROUP
    FROM {$db_prefix}members
    WHERE realName = '$user'
    LIMIT 1", __FILE__, __LINE__);

  $row = mysqli_fetch_assoc($request);
  $id_post_grup = $row['ID_POST_GROUP'];
  $id_grup = $row['ID_GROUP'];
  $id_user = isset($row['ID_MEMBER']) ? $row['ID_MEMBER'] : '';

  mysqli_free_result($request);

  if (empty($id_user)) {
    die('0: El usuario no existe.');
  }

  if (empty($user_settings['dar_dia'])) {
    die('0: No tienes recargas disponibles.');
  }

  if ($user_settings['ID_GROUP'] == 7) {
    $pts = 5;
  }

  if ($user_settings['ID_GROUP'] == 11) {
    $pts = 5;
  }

  if ($user_info['is_admin'] || $user_info['is_mods']) {
    $pts = 10;
  }

  if ($user_settings['dar_dia'] > $pts) {
    die('0: No puedes dar m&aacute;s de ' . $pts . ' recargos diarios.');
  }

  if ($context['ID_MEMBER'] == $ID_MEMBER) {
    die('0: No te puedes recargar puntos a ti mismo.');
  }

  $grupoo = empty($id_grup) ? $id_post_grup : $id_grup;

  $cantidadDDD = db_query("
    SELECT CantidadDePuntos
    FROM {$db_prefix}membergroups
    WHERE ID_GROUP = $grupoo
    LIMIT 1", __FILE__, __LINE__);

  $row = mysqli_fetch_assoc($cantidadDDD);
  $das = isset($row['CantidadDePuntos']) ? $row['CantidadDePuntos'] : '';

  mysqli_free_result($cantidadDDD);

  if (empty($das)) {
    die('0: El rango de este usuario no contiene puntos.');
  }

  db_query("
    UPDATE {$db_prefix}members
    SET dar_dia = dar_dia - 1
    WHERE ID_MEMBER = $ID_MEMBER
    LIMIT 1", __FILE__, __LINE__);

  db_query("
    UPDATE {$db_prefix}members
    SET puntos_dia = $das
    WHERE ID_MEMBER = $id_user
    LIMIT 1", __FILE__, __LINE__);

  die('1: &iexcl;Puntos recargados correctamente!');
} else {
  die('0: No tienes los privilegios necesarios para realizar esta acci&oacute;n.');
}

?>