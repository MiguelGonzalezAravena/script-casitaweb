<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php'); 
global $context, $user_info, $user_settings, $db_prefix, $boardurl;

$post = isset($_POST['id_post']) ? (int) $_POST['id_post'] : 0;
$comentario = isset($_POST['cuerpo_comment']) ? seguridad($_POST['cuerpo_comment']) : '';
$date = time();

if ($user_info['is_admin'] || $user_info['is_mods']) {
  ignore_user_abort(true);
  @set_time_limit(300);
  timeforComent();

  if (empty($post)) {
    fatal_error('Debes seleccionar el post.', false);
  }

  $request = db_query("
    SELECT id_contenido
    FROM {$db_prefix}comunicacion
    WHERE id_contenido = '$post'", __FILE__, __LINE__);

  $sas = mysqli_num_rows($request);

  if (empty($sas)) {
    fatal_error('Quieres comentar un post que no existe.', false);
  }

  if (strlen($comentario) > 4500) {
    fatal_error('El comentario es demasiado extenso, abr&eacute;vialo.');
  }

  if (empty($comentario)) {
    fatal_error('Debes escribir un comentario.', false);
  }

  db_query("
    INSERT INTO {$db_prefix}comentarios_mod (id_user, id_post, comentario)
    VALUES ({$user_settings['ID_MEMBER']}, $post, '$comentario')", __FILE__, __LINE__);

  // Notificaciones
  $request = db_query("
    SELECT ID_MEMBER
    FROM {$db_prefix}members
    WHERE ID_GROUP = 1
    OR ID_GROUP = 2", __FILE__, __LINE__);

  while ($row = mysqli_fetch_array($request)) {
    notificacionAGREGAR($row['ID_MEMBER'], '9');
  }

  mysqli_free_result($request);

  $_SESSION['ultima_accionTIME'] = time();

  header('Location: ' . $boardurl . '/moderacion/comunicacion-mod/post/' . $post);
} else {
  die();
}

?>