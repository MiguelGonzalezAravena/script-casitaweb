<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $tranfer1, $context, $db_prefix, $txt, $scripturl, $modSettings, $user_info, $user_settings;

if (empty($ID_MEMBER)) {
  die('0: Funcionalidad exclusiva de usuarios registrados.');
}

$id_comentario = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$id_imagen = isset($_GET['img']) ? (int) $_GET['img'] : 0;

if (empty($id_comentario)) {
  die('0: Debes seleccionar el comentario a eliminar.');
} else if (empty($id_imagen)) {
  die('0: Debes seleccionar la imagen a la cual pertenece el comentario a eliminar.');
} else {
  $request = db_query("
    SELECT ID_COMMENT
    FROM {$db_prefix}gallery_comment
    WHERE ID_COMMENT = $id_comentario
    LIMIT 1", __FILE__, __LINE__);

  $rows = mysqli_num_rows($request);

  if ($rows == 0) {
    die('0: El comentario seleccionado no existe.');
  } else {
    $request = db_query("
      SELECT ID_MEMBER
      FROM {$db_prefix}gallery_pic
      WHERE ID_PICTURE = $id_imagen
      LIMIT 1", __FILE__, __LINE__);

    $row = mysqli_fetch_assoc($request);
    $context['id_user'] = $row['ID_MEMBER'];

    if ($context['id_user'] == $ID_MEMBER || $user_info['is_admin'] || $user_info['is_mods']) {
      db_query("
        DELETE FROM {$db_prefix}gallery_comment
        WHERE ID_COMMENT = $id_comentario", __FILE__, __LINE__);

      $request = db_query("
        SELECT ID_COMMENT
        FROM {$db_prefix}gallery_comment
        WHERE ID_PICTURE = $id_imagen
        LIMIT 1", __FILE__, __LINE__);

      $rows = mysqli_num_rows($request);

      die('1: ' . $rows);
    } else {
      die('0: No tienes permisos para eliminar este comentario.');
    }
  }
}

?>