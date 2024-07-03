<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $context, $modSettings, $user_settings, $user_info, $db_prefix, $ID_MEMBER;

$title = isset($_POST['title']) ? seguridad($_POST['title']) : '';
$url = isset($_POST['url']) ? seguridad($_POST['url']) : '';

if ($user_info['is_guest']) {
  die('Funcionalidad exclusiva de usuarios registrados.');
}

$context['leecher'] = $user_settings['ID_POST_GROUP'] == 4;

if ($context['leecher']) {
  die('0: Los usuarios de rango Turistas no pueden agregar im&aacute;genes.');
}

$request = db_query("
  SELECT date
  FROM {$db_prefix}gallery_pic
  WHERE ID_MEMBER = $ID_MEMBER
  ORDER BY date DESC
  LIMIT 1", __FILE__, __LINE__);

$lim2 = mysqli_fetch_assoc($request);
$modifiedTime = $lim2['date'];

if ($modifiedTime > time() - 60) {
  die('0: No es posible agregar im&aacute;genes con tan poca diferencia de tiempo.<br />Vuelva a intentar en segundos.');
}

if (empty($title)) {
  die('0: Debes agregar un t&iacute;tulo.');
}

if (strlen($title) <= 3) {
  die('0: El t&iacute;tulo debe tener m&aacute;s de 3 letras.');
}

if (strlen($title) > 55) {
  die('0: El t&iacute;tulo es muy largo.');
}

if (valida_url($url) == null) {
  die('0: La URL indicada no contiene imagen.');
}

if (empty($url)) {
  die('0: Debes agregar el enlace de la imagen.');
}

if (strlen($url) > 110) {
  die('0: El enlace no puede ser mayor de <b>110 letras</b><br/>Sube la imagen a <a href="' . $modSettings['host_imagen'] . '">' . $modSettings['host_imagen'] . '</a> que los enlaces son cortos.');
}

$date = time();

db_query("
  INSERT INTO {$db_prefix}gallery_pic (ID_CAT, filename, title, ID_MEMBER, date)
  VALUES (1, SUBSTRING('$url', 1, 110), '$title', $ID_MEMBER, $date)", __FILE__, __LINE__);

$id = db_insert_id();

pts_sumar_grup($ID_MEMBER);

unset($_POST);

die('1: Tu imagen fue agregada correctamente. (<a href="' . $boardurl . '/imagenes/ver/' . $id . '">Ver im&aacute;gen</a>).');

?>