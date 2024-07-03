<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $db_prefix, $context, $sourcedir, $user_settings, $boardurl, $ID_MEMBER;

require_once($sourcedir . '/FuncionesCom.php');

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($user_info['is_guest']) {
  fatal_error('Funcionalidad exclusiva de usuarios registrados.');
}

if (empty($id)) {
  fatal_error('Debes especificar el comentario a eliminar.');
}

$rs44 = db_query("
  SELECT a.id, a.id_com, co.url, a.titulo, a.id_user
  FROM {$db_prefix}comunidades_articulos AS a, {$db_prefix}comunidades_comentarios AS c, {$db_prefix}comunidades AS co
  WHERE c.id = $id
  AND c.id_tema = a.id
  AND a.eliminado = 0
  AND a.id_com = co.id
  LIMIT 1", __FILE__, __LINE__);

$row = mysqli_fetch_assoc($rs44);
$dasdasd = isset($row['id']) ? $row['id'] : '';
$url = $row['url'];
$id_com = $row['id_com'];
$vbvbvki = $row['id_user'];
$titulo = $row['titulo'];

if (empty($dasdasd)) {
  fatal_error('El comentario especificado no existe.');
}

baneadoo($id_com);
permisios($id_com);

if ($context['permisoCom'] == 1 || $context['permisoCom'] == 3 || $context['permisoCom'] == 2 || $vbvbvki == $ID_MEMBER) {
  db_query("
    DELETE FROM {$db_prefix}comunidades_comentarios
    WHERE id = $id
    LIMIT 1", __FILE__, __LINE__);

  db_query("
    UPDATE {$db_prefix}comunidades_articulos
    SET respuestas = respuestas - 1
    WHERE id = $dasdasd
    LIMIT 1", __FILE__, __LINE__);

  header(`Location: $boardurl/comunidades/$url/$dasdasd/$titulo.html`);
} else {
  header(`Location: $boardurl/comunidades/`);
}
?>