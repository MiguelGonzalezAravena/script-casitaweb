<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $tranfer1, $context, $settings, $db_prefix, $options, $txt, $user_info, $user_settings, $scripturl, $ID_MEMBER;

if ($user_info['is_guest']) {
  header('Location: ' . $boardurl);
}

$user = isset($_GET['user']) ? seguridad($_GET['user']) : '';

if (empty($user)) {
  fatal_error('No has seleccionado al usuario que quieres agregar como amigo(a).');
}

$request = db_query("
  SELECT realName, ID_MEMBER
  FROM {$db_prefix}members
  WHERE realName = '$user'
  LIMIT 1", __FILE__, __LINE__);

$row = mysqli_fetch_assoc($request);
$friend = $row['ID_MEMBER'];
$name = $row['realName'];

if ($friend === $ID_MEMBER) {
  fatal_error('No puedes agregarte como amigo.');
} else if (empty($friend)) {
  fatal_error('El usuario seleccionado no existe.');
} else if (empty($ID_MEMBER)) {
  fatal_error('Debes estar logueado para realizar esta acci&oacute;n');
} else {
  $request = db_query("
    SELECT user, amigo
    FROM {$db_prefix}amistad
    WHERE user = $ID_MEMBER
    AND amigo = $friend
    OR user = $friend
    AND amigo = $ID_MEMBER
    AND acepto = 1
    LIMIT 1", __FILE__, __LINE__);

  $rows = mysqli_num_rows($request) != 0 ? true : false;

  mysqli_free_result($request);

  if ($rows) {
    fatal_error('Ya eres amigo de <b>' . $name . '</b>.');
  } else {
    $request = db_query("
      SELECT user, amigo
      FROM {$db_prefix}amistad
      WHERE user = $ID_MEMBER
      AND amigo = $friend
      OR user = $friend
      AND amigo = $ID_MEMBER
      AND acepto = 0
      LIMIT 1", __FILE__, __LINE__);

    $rows = mysqli_num_rows($request);

    if ($rows) {
      fatal_error('Esperando confirmaci&oacute;n de <b>' . $name . '</b>.');
    } else {
      db_query("
        INSERT INTO {$db_prefix}amistad (user, amigo)
        VALUES ($ID_MEMBER, $friend)", __FILE__, __LINE__);

      header('Location: ' . $boardurl . '/perfil/' . $user);
    }
  }
}

?>