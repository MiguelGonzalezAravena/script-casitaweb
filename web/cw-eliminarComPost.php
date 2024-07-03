<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $tranfer1, $context, $user_settings, $user_info, $db_prefix, $ID_MEMBER;

$aLista = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$topic = isset($_GET['post']) ? (int) $_GET['post'] : 0;

if ($user_info['is_guest']) {
  die('0: Funcionalidad exclusiva de usuarios registrados.');
}

if ($aLista <= 0) {
  die('0: Debes especificar el comentario.');
}

if ($topic <= 0) {
  die('0: Este comentario no se puede eliminar.');
}

$request = db_query("
  SELECT id_user
  FROM {$db_prefix}comentarios
  WHERE id_coment = $aLista
  AND id_post = $topic
  LIMIT 1", __FILE__, __LINE__);

$existe = mysqli_num_rows($request);

mysqli_free_result($existe);

if (!$existe) {
  die('0: El comentario especificado no se puede eliminar.');
}

$reddd = db_query("
  SELECT ID_MEMBER
  FROM {$db_prefix}messages
  WHERE ID_TOPIC = $topic
  LIMIT 1", __FILE__, __LINE__);

$red = mysqli_fetch_array($reddd);
$context['id_user'] = isset($red['ID_MEMBER']) ? $red['ID_MEMBER'] : '';

mysqli_free_result($reddd);

if ($context['id_user'] == $ID_MEMBER || $user_info['is_admin'] || $user_info['is_mods']) {
  db_query("
    DELETE FROM {$db_prefix}comentarios
    WHERE id_coment = $aLista", __FILE__, __LINE__);

  $request = db_query("
    SELECT id_user
    FROM {$db_prefix}comentarios
    WHERE id_post = $topic
    LIMIT 1", __FILE__, __LINE__);

  $cccS = mysqli_num_rows($request);

  mysqli_free_result($cccS);

  die('1: ' . $cccS);
} else {
  die('0: No tienes permisos para eliminar este comentario.');
}

?>