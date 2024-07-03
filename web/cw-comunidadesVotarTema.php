<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');

global $db_prefix, $user_settings, $ID_MEMBER;

$voto = isset($_POST['voto']) ? seguridad($_POST['voto']) : '';
$id = isset($_POST['tema']) ? (int) $_POST['tema'] : 0;

if (!$ID_MEMBER) {
  die('0: <span class="error">Funcionalidad exclusiva de usuarios registrados.</span>');
}

if (!$id) {
  die('0: <span class="error">Debes especificar el tema que deseas votar.</span>');
}

$request = db_query("
  SELECT calificacion, id, id_com
  FROM {$db_prefix}comunidades_articulos
  WHERE id = $id
  LIMIT 1", __FILE__, __LINE__);

$row = mysqli_fetch_assoc($request);
$id2 = isset($row['id']) ? $row['id'] : '';
$id_com = $row['id_com'];
$def1 = $row['calificacion'];

mysqli_free_result($request);

require_once($sourcedir . '/FuncionesCom.php');

baneadoo($id_com);

if (!$id2) {
  die('0: <span class="error">El tema especificado no existe.</span>');
} 

$request = db_query("
  SELECT id_com
  FROM {$db_prefix}comunidades_miembros
  WHERE id_com = $id_com
  AND id_user = $ID_MEMBER
  LIMIT 1", __FILE__, __LINE__);

$ya3 = mysqli_num_rows($request);

if (!$ya3) {
  die('0: <span class="error">No eres miembro de la comunidad.</span>');
}

$request = db_query("
  SELECT id_tema
  FROM {$db_prefix}comunidades_articulos_votos
  WHERE id_tema = $id
  AND id_user = $ID_MEMBER
  LIMIT 1", __FILE__, __LINE__);

$ya = mysqli_num_rows($request);

if ($ya) {
  die('0: <span class="error">Ya puntuaste este tema.</span>');
}

$request = db_query("
  SELECT id
  FROM {$db_prefix}comunidades_articulos
  WHERE id = $id
  AND id_user = $ID_MEMBER
  LIMIT 1", __FILE__, __LINE__);

$ya2 = mysqli_num_rows($request);

if ($ya2) {
  die('0: <span class="error">No puedes votar tus temas.</span>');
}

if ($voto < -1) {
  die('0: <span class="error">El voto no puede ser menor a -1.</span>');
}

if ($voto > 1) {
  die('0: <span class="error">El voto no puede ser mayor a 1.</span>');
}

if ($voto == -0) {
  die('0: <span class="error">El voto no puede ser igual a -0.</span>');
}

if (!$voto) {
  die('0: <span class="error">Debes especificar el voto que quieres dar.</span>');
}

/*
if ($voto == -1) {
  $cali = $voto;
  $def = $def1 - 1;
}
*/

if ($voto == 1) {
  $cali = '+1';
  $def = $def1 + 1;

  db_query("
    UPDATE {$db_prefix}comunidades_articulos
    SET calificacion = calificacion + 1
    WHERE id = $id
    LIMIT 1", __FILE__, __LINE__);

  db_query("
    INSERT INTO {$db_prefix}comunidades_articulos_votos (id_tema, id_user, cant, fecha)
    VALUES ($id, $ID_MEMBER, '+1', " . time() . ')', __FILE__, __LINE__);
}

if ($voto == -1) {
  $cali = '-1';
  $def = $def1 - 1;

  db_query("
    UPDATE {$db_prefix}comunidades_articulos
    SET calificacion = calificacion - 1
    WHERE id = $id
    LIMIT 1", __FILE__, __LINE__);

  db_query("
    INSERT INTO {$db_prefix}comunidades_articulos_votos (id_tema, id_user, cant, fecha)
    VALUES ($id, $ID_MEMBER, '-1', " . time() . ')', __FILE__, __LINE__);
}

if (!$def) {
  die('1: <span class="ok">' . $def . '</span>');
}

if ($def < 0) {
  die('1: <span class="error">' . $def . '</span>');
}

if ($def > 0) {
  die('1: <span class="ok">+' . $def . '</span>');
}

?>