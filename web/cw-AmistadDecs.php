<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $tranfer1, $context, $db_prefix, $user_info;

if ($user_info['is_guest']) {
  die('0: Funcionalidad exclusiva para usuarios registrados.');
}

$aLista = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($aLista <= 0) {
  die('0: Error.');
} else {
  $request = db_query("
    SELECT amigo, user
    FROM {$db_prefix}amistad
    WHERE id = {$aLista}
    AND acepto = 0
    LIMIT 1", __FILE__, __LINE__);

  $row = mysqli_fetch_assoc($request);
  $context['amigo'] = $row['amigo'];
  $userxx = $row['user'];

  if ($context['amigo'] == $ID_MEMBER) {
    $tip = isset($_GET['tipo']) ? (int) $_GET['tipo'] : 0;

    if (empty($tip)) {
      db_query("
        DELETE FROM {$db_prefix}amistad
        WHERE id = $aLista", __FILE__, __LINE__);
    } else {
      db_query("
        UPDATE {$db_prefix}amistad
        SET acepto = 1
        WHERE id = $aLista
        LIMIT 1", __FILE__, __LINE__);
    }

    die('1: OK');
  } else {
    die('0: Sin permisos.');
  }
}

?>