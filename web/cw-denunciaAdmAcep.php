<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $db_prefix, $user_settings, $user_info;

$post = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$denunciante = isset($_GET['den']) ? (int) $_GET['den'] : 0;
$idden = isset($_GET['ident']) ? (int) $_GET['ident'] : 0;

if ($user_info['is_admin'] || $user_info['is_mods']) {
  if (empty($post)) {
    die('0: Debes especificar el post.');
  }

  if (empty($denunciante)) {
    die('0: Debes especificar el usuario denunciante.');
  }

  if (empty($idden)) {
    die('0: Debes especificar la denuncia.');
  }

  $request = db_query("
    SELECT ID_MEMBER
    FROM {$db_prefix}messages
    WHERE ID_TOPIC = $post
    LIMIT 1", __FILE__, __LINE__);

  $post = mysqli_fetch_assoc($request);
  $mem = isset($post['ID_MEMBER']) ? $post['ID_MEMBER'] : '';

  db_query("
    UPDATE {$db_prefix}members
    SET posts = posts + 1
    WHERE ID_MEMBER = $denunciante
    LIMIT 1", __FILE__, __LINE__);

  db_query("
    UPDATE {$db_prefix}denuncias
    SET
      borrado = 2,
      atendido = '{$user_settings['realName']}'
    WHERE id_denuncia = $idden
    LIMIT 1", __FILE__, __LINE__);

  if (!empty($mem)) {
    pts_sumar_grup($mem);
  }

  if (!empty($denunciante)) {
    pts_sumar_grup($denunciante);
  }
}

die('1: OK');

?>