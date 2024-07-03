<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $ID_MEMBER, $modSettings, $db_prefix, $boardurl;

$jetid = isset($_POST['id']) ? (int) $_POST['id'] : 0;
$titulo = isset($_POST['titulo']) ? seguridad($_POST['titulo']) : '';
$contenido = isset($_POST['contenido']) ? seguridad($_POST['contenido']) : '';

if (empty($ID_MEMBER)) {
  die('Funcionalidad exclusiva de usuarios registrados.');
}

if (empty($jetid)) {
  die('Debes especificar la nota a editar.');
}

if (empty($titulo)) {
  fatal_error('Debes escribir un t&iacute;tulo a la nota.', false);
}

if (strlen($titulo) >= 61) {
  fatal_error('El t&iacute;tulo no puede tener m&aacute;s de 60 letras.', false);
}

if (empty($contenido)) {
  fatal_error('Debes escribir la nota.', false);
}

if (strlen($contenido) > $modSettings['max_messageLength']) {
  fatal_error('El post no puede tener m&aacute;s de ' . $modSettings['max_messageLength'] . ' letras.', false);
}

$fecha = time();

db_query("
  UPDATE {$db_prefix}notas
  SET
    titulo = '$titulo',
    contenido = '$contenido',
    fecha_editado = $fecha
    WHERE id = $jetid
    AND id_user = $ID_MEMBER
    LIMIT 1", __FILE__, __LINE__);

header(`Location: $boardurl/mis-notas/`);

?>