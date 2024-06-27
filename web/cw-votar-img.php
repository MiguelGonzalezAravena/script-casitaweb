<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $context, $user_settings, $user_info, $db_prefix, $modSettings, $ID_MEMBER;


$id = isset($_GET['imagen']) ? (int) $_GET['imagen'] : 0;
$cantidad = isset($_GET['cantidad']) ? (int) $_GET['cantidad'] : 0;

if ($user_info['is_guest']) {
  die('0: Funcionalidad exclusiva de usuarios registrados.');
}

if (empty($id)) {
  die('0: Debes seleccionar la imagen a la cual le quieres dar puntos.');
}


$request = db_query("
  SELECT ID_MEMBER, title
  FROM {$db_prefix}gallery_pic
  WHERE ID_PICTURE = $id
  LIMIT 1", __FILE__, __LINE__);

$row = mysqli_fetch_assoc($request);
$user = $row['ID_MEMBER'];
$title = $row['title'];

mysqli_free_result($request);

$user = isset($user) ? $user : '';

if (empty($user)) {
  die('0: La imagen no existe o fue eliminada.');
}

$request = db_query("
  SELECT id_user
  FROM {$db_prefix}gallery_cat
  WHERE id_user = $ID_MEMBER
  AND id_img = $id
  LIMIT 1", __FILE__, __LINE__);

$rows = mysqli_num_rows($request) != 0 ? true : false;

mysqli_free_result($request);

if ($user_settings['posts'] < $cantidad) {
  die('0: No tienes esa cantidad de puntos.');
}

if ($cantidad < 1) {
  die('0: Debes seleccionar una cantidad v&aacute;lida.');
}

if (empty($cantidad)) {
  die('0: Debes especificar una cantidad.');
}

if (empty($user)) {
  die('0: El usuario que es due&ntilde;o del post no existe, o el post fue eliminado.');
}

if ($user == $ID_MEMBER) {
  die('0: No puedes dar puntos a tus im&aacute;genes.');
}

if ($rows) {
  die('0: Ya has dado puntos a esta imagen.');
}

if ($cantidad > $modSettings['puntos_por_post-img']) {
  die('0: S&oacute;lo puedes dar ' . $modSettings['puntos_por_post-img'] . ' puntos para cada imagen.');
}

if ($cantidad > $user_settings['puntos_dia']) {
  die('0: S&oacute;lo tienes ' . $user_settings['puntos_dia'] . ' puntos disponibles para dar.');
}

$fecha = time();

db_query("
  UPDATE {$db_prefix}members
  SET posts = posts + $cantidad
  WHERE ID_MEMBER = $user
  LIMIT 1", __FILE__, __LINE__);

db_query("
  UPDATE {$db_prefix}members
  SET puntos_dia = puntos_dia - $cantidad, TiempoPuntos = $fecha
  WHERE ID_MEMBER = $ID_MEMBER
  LIMIT 1", __FILE__, __LINE__);

db_query("
  UPDATE {$db_prefix}gallery_pic
  SET puntos = puntos + $cantidad
  WHERE ID_PICTURE = $id
  LIMIT 1", __FILE__, __LINE__);

db_query("
  INSERT INTO {$db_prefix}gallery_cat (id_img, id_user, cantidad, fecha)
  VALUES ($id, $ID_MEMBER, $cantidad, $fecha)", __FILE__, __LINE__);

notificacionAGREGAR($user, '4', $cantidad);

pts_sumar_grup($user);

die('1: &iexcl;Puntos agregados satisfactoriamente!');

?>