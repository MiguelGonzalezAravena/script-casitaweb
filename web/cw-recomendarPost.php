<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $db_prefix, $boardurl;

$posts = isset($_POST['post']) ? (int) $_POST['post'] : 0;
$titulo = isset($_POST['titulo']) ? seguridad($_POST['titulo']) : '';
$comentario = isset($_POST['comment']) ? seguridad($_POST['comment']) : '';

if (empty($posts)) {
  die('0: El post especificado no existe.');
}

$request = db_query("
  SELECT m.subject, b.description
  FROM {$db_prefix}messages AS m, {$db_prefix}boards AS b
  WHERE m.ID_TOPIC = $posts
  AND m.ID_BOARD = b.ID_BOARD
  LIMIT 1", __FILE__, __LINE__);

$rows = mysqli_num_rows($request);

if ($rows == 0) {
  die('0: Este post no existe.');
}

$row = mysqli_fetch_assoc($request);

mysqli_free_result($request);

if (empty($_POST['r_email'])) {
  die('0: Debes agregar el primer correo.');
}

if ($_POST['r_email']) {
  if (preg_match('~^[0-9A-Za-z=_+\-/][0-9A-Za-z=_\'+\-/\.]*@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$~', stripslashes($_POST['r_email'])) == 0) {
    die('0: Correo electr&oacute;nico mal escrito.');
  }
}

if ($_POST['r_email1']) {
  if (preg_match('~^[0-9A-Za-z=_+\-/][0-9A-Za-z=_\'+\-/\.]*@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$~', stripslashes($_POST['r_email1'])) == 0) {
    die('0: Correo electr&oacute;nico mal escrito.');
  }
}

if ($_POST['r_email2']) {
  if (preg_match('~^[0-9A-Za-z=_+\-/][0-9A-Za-z=_\'+\-/\.]*@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$~', stripslashes($_POST['r_email2'])) == 0) {
    die('0: Correo electr&oacute;nico mal escrito.');
  }
}

if ($_POST['r_email3']) {
  if (preg_match('~^[0-9A-Za-z=_+\-/][0-9A-Za-z=_\'+\-/\.]*@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$~', stripslashes($_POST['r_email3'])) == 0) {
    die('0: Correo electr&oacute;nico mal escrito.');
  }
}

if ($_POST['r_email4']) {
  if (preg_match('~^[0-9A-Za-z=_+\-/][0-9A-Za-z=_\'+\-/\.]*@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$~', stripslashes($_POST['r_email4'])) == 0) {
    die('0: Correo electr&oacute;nico mal escrito.');
  }
}

if ($_POST['r_email5']) {
  if (preg_match('~^[0-9A-Za-z=_+\-/][0-9A-Za-z=_\'+\-/\.]*@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$~', stripslashes($_POST['r_email5'])) == 0) {
    die('0: Correo electr&oacute;nico mal escrito.');
  }
}

if (!isset($titulo) || empty($titulo)) {
  die('0: Debes agregar un asunto.');
}

if (strlen($titulo) >= 61) {
  die('0: El asunto no puede tener m&aacute;s de 60 letras.');
}

if (empty($comentario)) {
  die('0: Debes escribir un comentario.');
}

if (strlen($comentario) >= 700) {
  die('0: El comentario no puede tener 700 o m&aacute; letras.');
}

captcha(3);

$emailse = array($_POST['r_email'], $_POST['r_email1'], $_POST['r_email2'], $_POST['r_email3'], $_POST['r_email4'], $_POST['r_email5']);

require_once($sourcedir . '/Subs-Post.php');

censorText($row['subject']);

sendmail(
  $emailse,
  $titulo,
  sprintf('Este mensaje ha sido enviado desde ' . $boardurl . ':') . "\n\n" .
  sprintf($comentario) . "\n\n" .
  'Enlace: <a href="' . $boardurl . '/post/' . $posts . '/' . $row['description'] . '/' . urls($row['subject']) . '.html">' . $boardurl . '/post/' . $posts . '/' . $row['description'] . '/' . urls($row['subject']) . '.html</a>'
);

die('1: &iexcl;Post recomendado exitosamente!');

?>