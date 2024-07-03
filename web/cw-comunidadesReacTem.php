<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');

global $db_prefix, $context, $sourcedir, $user_settings;

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($user_info['is_guest']) {
  die('0: Funcionalidad exclusiva de usuarios registrados.');
}

if (empty($id)) {
  die('0: Debes especificar el tema a reactivar.');
}

$request = db_query("
  SELECT id_com
  FROM {$db_prefix}comunidades_articulos
  WHERE id = $id
  AND eliminado = 1
  LIMIT 1", __FILE__, __LINE__);

$row = mysqli_fetch_assoc($request);
$id_com = isset($row['id_com']) ? $row['id_com'] : '';

mysqli_free_result($request);

if (empty($id_com)) {
  die('0: El tema especificado no existe.');
}

require_once($sourcedir . '/FuncionesCom.php');

baneadoo($id_com);
permisios($id_com);

if ($context['permisoCom'] == 1 || $context['permisoCom'] == 3 || $context['permisoCom'] == 2) {
  db_query("
    UPDATE {$db_prefix}comunidades_articulos
    SET eliminado = 0
    WHERE id = $id
    LIMIT 1", __FILE__, __LINE__);

  db_query("
    UPDATE {$db_prefix}comunidades
    SET articulos = articulos + 1
    WHERE id = $id_com
    LIMIT 1", __FILE__, __LINE__);

  die('1: Tema reactivado satisfactoriamente.');
} else {
  die('0: No tienes los permisos necesarios para realizar esta acci&oacute;n.');
}

?>