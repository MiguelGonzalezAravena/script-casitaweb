<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $context, $db_prefix, $txt, $modSettings, $user_info, $user_settings, $ID_MEMBER;

$amount = isset($_GET['puntos']) ? (int) $_GET['puntos'] : 0;
$topic = isset($_GET['post']) ? (int) $_GET['post'] : 0;

if ($user_info['is_guest']) {
  die('0: Funcionalidad exclusiva de usuarios registrados.');
}

if (empty($amount)) {
  die('0: Debes seleccionar la cantidad de puntos que deseas dar.');
}

if (empty($topic)) {
  die('0: Debes seleccionar el post al cual le quieres dar puntos.');
}

if ($user_settings['posts'] < $amount) {
  die('0: No tienes los puntos necesarios.');
}

if ($amount < 1) {
  die('0: Selecciona una cantidad de puntos v&aacute;lida.');
}

if ($topic) {
  $request = db_query("
    SELECT m.ID_TOPIC, m.subject, m.ID_BOARD, m.ID_MEMBER, b.description
    FROM {$db_prefix}messages AS m, {$db_prefix}boards AS b
    WHERE m.ID_TOPIC = $topic
    AND m.eliminado = 0
    LIMIT 1", __FILE__, __LINE__);

  $row = mysqli_fetch_assoc($request);
  $ID_BOARD = $row['ID_BOARD'];
  $id_user = $row['ID_MEMBER'];
  $cat = $row['description'];
  $title = $row['subject'];

  mysqli_free_result($request);

  $id_user = isset($id_user) ? $id_user : '';

  if (empty($id_user)) {
    die('0: El post seleccionado no existe.');
  }

  $request = db_query("
    SELECT id_member, id_post
    FROM {$db_prefix}puntos
    WHERE id_member = $ID_MEMBER
    AND id_post = $topic
    LIMIT 1", __FILE__, __LINE__);

  $rows = mysqli_num_rows($request);

  mysqli_free_result($request);

  if ($rows) {
    die('0: Ya has dado puntos a este post.');
  }
  
  if ($ID_BOARD === 45 || $ID_BOARD === 132) {
    die('0: No se permiten puntuar los post de esta categor&iacute;a.');
  }

  if ($context['leecher']) {
    die('0: Usuarios de rango Turistas no pueden dar puntos.');
  }

  /* En teoría, no debería llegar aquí
  if ($context['user']['is_guest']) {
    die('0: Usuarios no registrado no pueden dar puntos.');
  }
  */

  if ($amount > $modSettings['puntos_por_post-img']) {
    die('0: S&oacute;lo puedes dar ' . $modSettings['puntos_por_post-img'] . ' puntos para cada post.');
  }

  if ($amount > $user_settings['puntos_dia']) {
    die('0: Solo tienes ' . $user_settings['puntos_dia'] . ' puntos disponibles para dar.');
  }

  if ($ID_MEMBER === $id_user) {
    die('0: No puedes dar puntos a tus post.');
  }

  $fecha = time();

  db_query("
    UPDATE {$db_prefix}members
    SET posts = posts + {$amount}
    WHERE ID_MEMBER = {$id_user}
    LIMIT 1", __FILE__, __LINE__);

  db_query("
    UPDATE {$db_prefix}members
    SET puntos_dia = puntos_dia - $amount, TiempoPuntos = $fecha
    WHERE ID_MEMBER = $ID_MEMBER
    LIMIT 1", __FILE__, __LINE__);

  db_query("
    UPDATE {$db_prefix}messages
    SET puntos = puntos + $amount
    WHERE ID_TOPIC = $topic
    LIMIT 1", __FILE__, __LINE__);

  db_query("
    INSERT INTO {$db_prefix}puntos (id_post, id_member, cantidad)
    VALUES ($topic, $ID_MEMBER, $amount)", __FILE__, __LINE__);

  notificacionAGREGAR($id_user, '5', $amount);

  pts_sumar_grup($id_user);

  die('1: &iexcl;Puntos agregados satisfactoriamente!');
}

?>