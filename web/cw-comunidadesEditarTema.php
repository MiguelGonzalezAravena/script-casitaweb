<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $user_info, $db_prefix, $user_settings, $ID_MEMBER, $boardurl;


$id = isset($_POST['id_tema']) ? (int) $_POST['id_tema'] : 0;
$titulo = isset($_POST['titulo']) ? seguridad($_POST['titulo']) : '';
$cuerpo = isset($_POST['cuerpo_comment']) ? seguridad($_POST['cuerpo_comment']) : '';
$nocoment = $_POST['nocoment'] ? 1 : 0;

if ($user_info['is_guest']) {
  fatal_error('Funcionalidad exclusiva de usuarios registrados.');
}

ignore_user_abort(true);
@set_time_limit(300);

if (!$id) {
  fatal_error('Debe seleccionar un tema.');
}

$request = db_query("
  SELECT id_com, id_user, stiky
  FROM {$db_prefix}comunidades_articulos
  WHERE id = $id
  AND eliminado = 0
  LIMIT 1", __FILE__, __LINE__);

$row = mysqli_fetch_assoc($request);
$id_com = $row['id_com'];
$id_user = $row['id_user'];
$stikyd = $row['stiky'];

mysqli_free_result($request);

if (!$id_user) {
  fatal_error('Este tema no existe.');
}

require_once($sourcedir . '/FuncionesCom.php');

permisios($id_com);
baneadoo($id_com);
acces($id_com);

if ($context['permisoCom'] == 1 || $context['permisoCom'] == 2 || $context['permisoCom'] == 3 || $id_user == $ID_MEMBER) {
  if ($context['puedo'] == 1 || $context['puedo'] == 3) {
    $stiky = ($context['permisoCom'] == 1 || $context['permisoCom'] == 3 ? ($_POST['sticky'] ? 1 : 0) : $stikyd);

    if (!$titulo) {
      fatal_error('Debes agregar un t&iacute;tulo.');
    }

    if (strlen($titulo) < 3) {
      fatal_error('El t&iacute;tulo no puede tener menos de <b>3 letras</b>.');
    }

    if (strlen($titulo) >= 61) {
      fatal_error('El t&iacute;tulo no puede tener m&aacute;s de <b>60 letras</b>.');
    }

    if (!$cuerpo) {
      fatal_error('Debes agregar un contenido.');
    }

    if (strlen($cuerpo) <= 15) {
      fatal_error('El contenido del tema no puede tener menos de <b>15 letras</b>.');
    }

    if (strlen($cuerpo) > $modSettings['max_messageLength']) {
      fatal_error('El contenido del tema no puede tener m&aacute;s de <b>' . $modSettings['max_messageLength'] . ' letras</b>.');
    }

    db_query("
      UPDATE {$db_prefix}comunidades_articulos
      SET
        titulo = SUBSTRING('$titulo', 1, 100),
        cuerpo = SUBSTRING('$cuerpo', 1, 60000),
        nocoment = $nocoment,
        stiky = $stiky
      WHERE id = $id
      LIMIT 1", __FILE__, __LINE__);

    $request = db_query("
      SELECT url
      FROM {$db_prefix}comunidades
      WHERE id = $id_com
      LIMIT 1", __FILE__, __LINE__);

    $row = mysqli_fetch_assoc($request);
    $url = $row['url'];

    mysqli_free_result($request);

    header('Location: ' . $boardurl . '/comunidades/' . $url . '/');
  } else {
    fatal_error('No tienes los permisos para realizar esta acci&oacute;n.');
  }
} else {
  fatal_error('No tienes los permisos para realizar esta acci&oacute;n.');
}

?>