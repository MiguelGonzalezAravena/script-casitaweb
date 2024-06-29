<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $context, $db_prefix, $user_settings, $user_info, $boardurl, $ID_MEMBER;

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$post = isset($_GET['post']) ? (int) $_GET['post'] : 0;

if ($user_info['is_admin'] || $user_info['is_mods']) {
  if ($id <= 0) {
    fatal_error('Falta el comentario a eliminar.', false);
  }

  if ($post <= 0) {
    fatal_error('Falta el comentario a eliminar.', false);
  }

  $request = db_query("
    SELECT id
    FROM {$db_prefix}comentarios_mod
    WHERE id = $id
    LIMIT 1", __FILE__, __LINE__);

  $existe = mysqli_num_rows($request);

  if (!$existe) {
    fatal_error('Este comentario no se puede eliminar.');
  }

  $request = db_query("
    SELECT id_user
    FROM {$db_prefix}comunicacion
    WHERE id_contenido = $post
    LIMIT 1", __FILE__, __LINE__);

  while ($row = mysqli_fetch_array($request)) {
    $context['id_user'] = $row['id_user'];
  }

  if (!$context['id_user']) {
    fatal_error('Este comentario no se puede eliminar.');
  }

  if ($context['id_user'] == $ID_MEMBER || $user_info['is_admin']) {
    db_query("
      DELETE FROM {$db_prefix}comentarios_mod
      WHERE id = $id", __FILE__, __LINE__);
  }

  header(`Location: $boardurl/moderacion/comunicacion-mod/post/$post`);
} else {
  fatal_error('Este comentario no se puede eliminar.');
}

?>