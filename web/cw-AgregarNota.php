<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $context, $modSettings, $db_prefix, $user_settings, $ID_MEMBER, $boardurl;

$titulo = isset($_POST['titulo']) ? trim($_POST['titulo']) : '';

if (empty($ID_MEMBER)) {
  die('Funcionalidad exclusiva de usuarios registrados.');
}

timeforComent(1);


if (empty($titulo)) {
  fatal_error('Debes escribir un t&iacute;tulo a la nota.');
}

if (strlen($titulo) >= 61) {
  fatal_error('El t&iacute;tulo no puede tener m&aacute;s de 60 letras.');
}

$titulos = seguridad($titulo);
$contenido = isset($_POST['contenido']) ? trim($_POST['contenido']) : '';

if (empty($contenido)) {
  fatal_error('Debes escribir la nota.');
}

if (strlen($contenido) > $modSettings['max_messageLength']) {
  fatal_error('La nota no puede tener m&aacute;s de ' . $modSettings['max_messageLength'] . ' letras.');
}

$contenidos = seguridad($contenido);

db_query("
  INSERT INTO {$db_prefix}notas (id_user, titulo, contenido)
  VALUES ($ID_MEMBER, '$titulos', '$contenidos')", __FILE__, __LINE__);

$_SESSION['ultima_accionTIME'] = $fecha;

header('Location: ' . $boardurl . '/mis-notas/');

?>