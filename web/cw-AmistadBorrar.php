<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $user_info, $user_settings, $db_prefix;

$user = isset($_GET['user']) ? (int) $_GET['user'] : 0;

if ($user_info['is_guest']) {
  die('0: Funcionalidad exclusiva de usuarios registrados.');
}

if (empty($user)) {
  die('0: No seleccionaste a nadie, para borrar como amistad.');
}

$request = db_query("
  SELECT ID_MEMBER
  FROM {$db_prefix}members
  WHERE ID_MEMBER = $user
  LIMIT 1", __FILE__, __LINE__);

$rows = mysqli_num_rows($request);

if (empty($rows)) {
  die('0: El usuario no existe.');
} else if ($user === $ID_MEMBER) {
  die('0: No puedes borrarte como amigo.');
}

$datosmem = db_query("
  SELECT user, amigo, id
  FROM {$db_prefix}amistad
  WHERE user = $ID_MEMBER
  AND amigo = $user
  OR user = $user
  AND amigo = $ID_MEMBER
  AND acepto = 1
  LIMIT 1", __FILE__, __LINE__);

$data = mysqli_fetch_assoc($datosmem);
$id = $data['id'];
$exist = mysqli_num_rows($datosmem) != 0 ? true : false;

mysqli_free_result($datosmem);

if ($exist) {
  db_query("
    DELETE FROM {$db_prefix}amistad
    WHERE id = $id", __FILE__, __LINE__);

  die('1: Eliminado correctamente.');
} else {
  die('0: Este usuario no es amigo tuyo.');
}

?>