<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $context, $settings, $db_prefix, $scripturl, $txt, $user_settings, $user_info, $tranfer1, $ID_MEMBER, $boardurl;

$tipo = $_GET['tipo'];
$tip = $tipo == 'imagen' ? 'imagen' : 'posts';
$myser = $ID_MEMBER;
$idcc = ($tip == 'posts' ? 0 : ($tip == 'imagen' ? 1 : 0));

if (!$user_info['is_admin']) {
  $shas = ' AND p.ID_BOARD <> 142';
} else {
  $shas = '';
}

$topicw = ($idcc == 0 ? (int) $_GET['post'] : ($idcc == 1 ? (int) $_GET['kjas'] : 0));
$topic = $topicw != 0 ? 'topic' : '';

if ($user_info['is_guest']) {
  die('0: Funcionalidad exclusiva de usuarios registrados.');
}

if($topic != 'topic') {
  // Eliminar favorito
  $id = isset($_GET['eliminar']) ? (int) $_GET['eliminar'] : 0;

  if ($id > 0) {
    $request = db_query("
      SELECT *
      FROM {$db_prefix}bookmarks
      WHERE id = $id
      LIMIT 1", __FILE__, __LINE__);

    $rows = mysqli_num_rows($request);

    if ($rows == 0) {
      die('0: El favorito que quieres eliminar no existe.');
    }

    db_query("
      DELETE FROM {$db_prefix}bookmarks
      WHERE id = $id
      AND ID_MEMBER = $myser", __FILE__, __LINE__);

    die('1: Favorito eliminado exitosamente.');
  }
} else if ($topic == 'topic') {
  if ($topicw == 0) {
    die('0: Debes seleccionar el favorito a agregar.');
  }

  // Verificar si el post o imagen ya existe en tus favoritos y si es que eres dueño o no del mismo
  if (!$idcc) {
    // Post
    $request = db_query("
      SELECT ID_TOPIC, tipo, ID_MEMBER
      FROM {$db_prefix}bookmarks
      WHERE ID_TOPIC = $topicw
      AND tipo = 0
      AND ID_MEMBER = $myser", __FILE__, __LINE__);

    $rows = mysqli_num_rows($request);

    if ($rows) {
      die('0: Este post ya est&aacute; en tus favoritos.');
    }

    $request = db_query("
      SELECT ID_TOPIC, ID_MEMBER
      FROM {$db_prefix}messages
      WHERE ID_TOPIC = $topicw
      $shas
      AND ID_MEMBER = $myser", __FILE__, __LINE__);

    $rows = mysqli_num_rows($request);

    if ($rows) {
      die('0: No puedes agregar a favoritos tus posts.');
    }

  } else if ($idcc) {
    // Imagen
    $request = db_query("
      SELECT ID_TOPIC, tipo, ID_MEMBER
      FROM {$db_prefix}bookmarks
      WHERE ID_TOPIC = $topicw
      AND tipo = 1
      AND ID_MEMBER = $myser", __FILE__, __LINE__);

    $rows = mysqli_num_rows($request);

    if ($rows) {
      die('0: Esta imagen ya est&aacute; en tus favoritos.');
    }

    $request = db_query("
      SELECT ID_PICTURE, ID_MEMBER
      FROM {$db_prefix}gallery_pic
      WHERE ID_PICTURE = $topicw
      AND ID_MEMBER = $myser", __FILE__, __LINE__);

    $rows = mysqli_num_rows($request);

    if ($rows) {
      die('0: No puedes agregar a favoritos tus im&aacute;genes.');
    }
  }

  // Agregar a favoritos y enviar notificación a quien corresponda
  if (!$idcc) {
    // Post
    $request = db_query("
      SELECT p.ID_TOPIC, p.ID_MEMBER, b.description, p.subject
      FROM {$db_prefix}messages AS p, {$db_prefix}boards AS b
      WHERE p.ID_TOPIC = $topicw
      AND b.ID_BOARD = p.ID_BOARD
      $shas
      LIMIT 1", __FILE__, __LINE__);

    $row = mysqli_fetch_assoc($request);
    $idss = $row['ID_TOPIC'];
    $idMen = $row['ID_MEMBER'];
    $subject = $row['subject'];
    $description = $row['description'];

    if (empty($idss)) {
      die('0: El post seleccionado no existe.');
    }

    db_query("
      INSERT INTO {$db_prefix}bookmarks (ID_MEMBER, ID_TOPIC, tipo)
      VALUES ($myser, $idss, 0)", __FILE__, __LINE__);

    if ($myser != $idMen) {
      $url = $boardurl . '/post/' . $idss . '/' . $description . '/' . urls($subject) . '.html';

      db_query("
        INSERT INTO {$db_prefix}notificaciones (url, que, a_quien, por_quien)
        VALUES ('$url', 6, $idMen, $myser)", __FILE__, __LINE__);

      db_query("
        UPDATE {$db_prefix}members
        SET notificacionMonitor = notificacionMonitor + 1
        WHERE ID_MEMBER = $idMen
        LIMIT 1", __FILE__, __LINE__);
    }

    die('1: &iexcl;Agregado a favoritos!');
  } else if ($idcc) {
    // Imagen
    $request = db_query("
      SELECT ID_PICTURE, ID_MEMBER
      FROM {$db_prefix}gallery_pic
      WHERE ID_PICTURE = $topicw
      LIMIT 1", __FILE__, __LINE__);

    $row = mysqli_fetch_assoc($request);
    $idss = $row['ID_PICTURE'];
    $idMen = $row['ID_MEMBER'];

    if (empty($idss)) {
      die('0: La imagen seleccionada no existe.');
    }

    db_query("
      INSERT INTO {$db_prefix}bookmarks (ID_MEMBER, ID_TOPIC, tipo)
      VALUES ($myser, $idss, 1)", __FILE__, __LINE__);

    if ($myser != $idMen) {
      $url = $boardurl . '/imagenes/ver/' . $idss;

      db_query("
        INSERT INTO {$db_prefix}notificaciones (url, que, a_quien, por_quien)
        VALUES ('$url', 7, $idMen, $myser)", __FILE__, __LINE__);

      db_query("
        UPDATE {$db_prefix}members
        SET notificacionMonitor = notificacionMonitor + 1
        WHERE ID_MEMBER = $idMen
        LIMIT 1", __FILE__, __LINE__);
    }

    die('1: &iexcl;Agregado a favoritos!');
  }
}

?>