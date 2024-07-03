<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $ID_MEMBER, $txt, $db_prefix, $scripturl, $modSettings, $boarddir, $sourcedir, $user_settings, $user_info;

$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
$title = isset($_POST['title']) ? seguridad($_POST['title']) : '';
$filename = isset($_POST['filename']) ? seguridad($_POST['filename']) : '';

if ($user_info['is_guest']) {
  fatal_error('Funcionalidad exclusiva de usuarios registrados.', false);
}

$context['idgrup'] = $user_settings['ID_POST_GROUP'];
$context['leecher'] = $user_settings['ID_POST_GROUP'] == 4;
$context['novato'] = $user_settings['ID_POST_GROUP'] == 5;
$context['buenus'] = $user_settings['ID_POST_GROUP'] == 6;

if ($context['leecher']) {
  fatal_error('Los usuarios de rango Turistas no pueden editar im&aacute;genes.', false, '', 4);
}

if (empty($id)) {
  fatal_error('Debes especificar una imagen para editar.', false, '', 4);
}

$request = db_query("
  SELECT p.ID_MEMBER, m.realName
  FROM {$db_prefix}gallery_pic AS p, {$db_prefix}members AS m
  WHERE p.ID_PICTURE = $id
  AND p.ID_MEMBER = m.ID_MEMBER
  LIMIT 1", __FILE__, __LINE__);

$row = mysqli_fetch_assoc($request);
$memID = $row['ID_MEMBER'];
$realName = $row['realName'];

mysqli_free_result($request);

if (empty($memID)) {
  fatal_error('La imagen especificada no existe.', false, '', 4);
}

if (($user_info['is_admin'] || $user_info['is_mods']) || $ID_MEMBER == $memID) {
  if (empty($title)) {
    fatal_error('Debes agregar un t&iacute;tulo.', false, '', 4);
  }

  if (strlen($title) <= 3) {
    fatal_error('El t&iacute;tulo debe tener m&aacute;s de 3 letras.', false, '', 4);
  }

  if (strlen($title) > 55) {
    fatal_error('El t&iacute;tulo es muy largo.', false, '', 4);
  }

  if (strlen($filename) > 110) {
    fatal_error('El enlace no puede ser mayor de <b>110 letras</b><br/>Subi la imagen a <a href="' . $modSettings['host_imagen'] . '">' . $modSettings['host_imagen'] . '</a> que los enlaces son cortos.-');
  }

  if (empty($filename)) {
    fatal_error('Debes agregar el enlace de la imagen.', false, '', 4);
  }

  db_query("
    UPDATE {$db_prefix}gallery_pic
    SET
      ID_CAT = 1,
      title = '$title',
      filename = SUBSTRING('$filename', 1, 110)
    WHERE ID_PICTURE = $id
    LIMIT 1", __FILE__, __LINE__);

  header(`Location: $boardurl/imagenes/$realName`);
} else {
  fatal_error('No tienes los permisos necesarios para editar esta imagen.', false, '', 4);
}

?>