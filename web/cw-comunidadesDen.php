<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $db_prefix, $sourcedir, $user_info, $user_settings;

$idCom = isset( $_POST['comu']) ? (int) $_POST['comu'] : 0;
$razon = isset($_POST['razon']) ? seguridad($_POST['razon']) : '';
$comentario = isset($_POST['comentario']) ? seguridad($_POST['comentario']) : '';

if (!$idCom) {
  fatal_error('Debes seleccionar una comunidad.');
}

$request = db_query("
  SELECT id, nombre
  FROM {$db_prefix}comunidades
  WHERE id = $idCom
  LIMIT 1", __FILE__, __LINE__);

$row = mysqli_fetch_assoc($request);
$url = $row['nombre'];
$Esta = $row['id'];

mysqli_free_result($request);

if (!$Esta) {
  fatal_error('Debes seleccionar una comunidad.');
}

require_once($sourcedir . '/FuncionesCom.php');

permisios($Esta);

if (!$context['user']['is_guest'] && !$context['permisoCom']) {
  if (!$razon || !$comentario) {
    fatal_error('Todos los campos son obligatorios.');
  }

  if (strlen($comentario) > 300) {
    fatal_error('El comentario es demasiado extenso, abr&eacute;vialo.');
  }

  if (strlen($razon) > 100) {
    fatal_error('La raz&oacuten es demasiada extensa, abr&eacute;viala.');
  }

  $request = db_query("
    SELECT id_denuncia
    FROM {$db_prefix}denuncias
    WHERE id_user = $ID_MEMBER
    AND id_post = $Esta
    AND tipo = 5
    LIMIT 1", __FILE__, __LINE__);

  $rows = mysqli_num_rows($request);

  mysqli_free_result($request);

  if ($rows) {
    fatal_error('Ya denunciaste a esta comunidad.');
  }

  db_query("
    INSERT INTO {$db_prefix}denuncias (comentario, razon, name_post, id_post, id_user, tipo, tiempo)
    VALUES ('$comentario', '$razon', '$url', $Esta, $ID_MEMBER, 5, " . time() . ')', __FILE__, __LINE__);
}

fatal_error('Tu denuncia fue enviada correctamente<br />Un moderador del sitio la verificar&aacute; en breve, Gracias', false, 'Denuncia enviada');

?>