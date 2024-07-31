<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $context, $func, $db_prefix, $user_settings, $user_info, $boardurl, $ID_MEMBER, $webmaster_email;

$posts = isset($_GET['post']) ? (int) $_GET['post'] : 0;

if ($user_info['is_admin'] || $user_info['is_mods']) {
  if (empty($posts)) {
    fatal_error('No est&aacute;s eliminando ning&uacute;n post.', false);
  }

  $opciones = db_query("
    SELECT id_contenido, id_user
    FROM {$db_prefix}comunicacion
    WHERE id_contenido = $posts
    ORDER BY id_contenido DESC
    LIMIT 1", __FILE__, __LINE__);

  $row = mysqli_fetch_assoc($opciones);
  $user = $row['id_user'];
  $id = $row['id_contenido'];

  if (empty($id)) {
    fatal_error('El post seleccionado no existe.', false);
  }

  if ($user == $ID_MEMBER || $user_info['is_admin'] || $user_info['is_mods']) {
    db_query("
      DELETE FROM {$db_prefix}comunicacion
      WHERE id_contenido = $id", __FILE__, __LINE__);

    db_query("
      DELETE FROM {$db_prefix}comentarios_mod
      WHERE id_post = $id", __FILE__, __LINE__);
  }

  header('Location: ' . $boardurl . '/moderacion/comunicacion-mod/');
} else {
  fatal_error('Debes estar conectado y ser de la moderaci&oacute;n. Si lo est&aacute;s, contactar con administrador (' . $webmaster_email . ').');
}

?>