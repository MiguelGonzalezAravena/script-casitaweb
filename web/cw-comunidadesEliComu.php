<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $db_prefix, $context, $sourcedir, $user_settings, $boardurl;

require_once($sourcedir . '/FuncionesCom.php');

if ($user_info['is_guest']) {
  fatal_error('Funcionalidad exclusiva de usuarios registrados.');
}

$id = isset($_GET['id']) ? seguridad($_GET['id']) : '';

if (!$id) {
  fatal_error('Debes especificar la comunidad que deseas eliminar.');
}

$rs44 = db_query("
  SELECT c.id, cc.url AS categoria
  FROM {$db_prefix}comunidades AS c
  INNER JOIN {$db_prefix}comunidades_categorias AS cc ON c.categoria = cc.id
  WHERE c.url = '$id'
  AND c.bloquear = 0
  LIMIT 1", __FILE__, __LINE__);

$row = mysqli_fetch_assoc($rs44);
$id_com = isset($row['id']) ? $row['id'] : '';
$categ = $row['categoria'];

if (empty($id_com)) {
  fatal_error('La comunidad especificada no existe.');
}

baneadoo($id_com);
permisios($id_com);

if ($context['permisoCom'] == 1) {
  db_query("
    UPDATE {$db_prefix}comunidades
    SET bloquear = 1
    WHERE id = $id_com
    LIMIT 1", __FILE__, __LINE__);

  db_query("
    UPDATE {$db_prefix}comunidades_categorias
    SET comunidades = comunidades - 1
    WHERE url = '$categ'
    LIMIT 1", __FILE__, __LINE__);
}

header('Location: ' . $boardurl . '/comunidades/');

?>