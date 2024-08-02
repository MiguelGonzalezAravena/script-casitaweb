<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $db_prefix, $sourcedir, $user_info, $user_settings;

$idCom = isset($_POST['idcom']) ? (int) $_POST['idcom'] : 0;
$nombre = isset($_POST['nombre']) ? seguridad($_POST['nombre']) : '';
$descripcion = isset($_POST['descripcion']) ? seguridad($_POST['descripcion']) : '';
$acceso = isset($_POST['privada']) ? (int) $_POST['privada'] : 0;
$aprobar = isset($_POST['aprobar']) ? (int) $_POST['aprobar'] : 0;
$permiso = isset($_POST['rango_default']) ? (int) $_POST['rango_default'] : 0;
$imagen = isset($_POST['imagen']) ? seguridad($_POST['imagen']) : '';
$cat = isset($_POST['categoria']) ? seguridad($_POST['categoria']) : '';

if ($user_info['is_guest']) {
  fatal_error('Funcionalidad exclusiva de usuarios registrados.');
}

if (empty($idCom)) {
  fatal_error('Debes seleccionar una comunidad.');
}

$request = db_query("
  SELECT id, url
  FROM {$db_prefix}comunidades
  WHERE id = $idCom
  LIMIT 1", __FILE__, __LINE__);

$row = mysqli_fetch_assoc($request);
$url = $row['url'];
$Esta = isset($row['id']) ? $row['id'] : '';

mysqli_free_result($request);

if (empty($Esta)) {
  fatal_error('Debes seleccionar una comunidad.');
}

require_once($sourcedir . '/FuncionesCom.php');

permisios($Esta);

if ($context['permisoCom'] == 1) {
  ignore_user_abort(true);
  @set_time_limit(300);

  if (strlen($nombre) < 5 || strlen($nombre) > 55) {
    fatal_error('El nombre debe tener entre 5 y 55 letras.');
  }

  if (strlen($descripcion) < 5 || strlen($nombre) > 2000) {
    fatal_error('La descripci&oacute;n debe tener entre 5 y 2000 letras.');
  }

  if (!$acceso) {
    fatal_error('Debes seleccionar un acceso.');
  }

  if (!$permiso) {
    fatal_error('Debes seleccionar un permiso.');
  }

  if (!$cat || $cat == '-1') {
    fatal_error('Debes elegir una categor&iacute;a.');
  }

  $request = db_query("
    SELECT url
    FROM {$db_prefix}comunidades_categorias
    WHERE url = '$cat'
    LIMIT 1", __FILE__, __LINE__);

  $comunidades_categorias = mysqli_num_rows($request);

  if (!$comunidades_categorias) {
    fatal_error('Esta categor&iacuet;a no existe.');
  }

  if (!$aprobar) {
    db_query("
      UPDATE {$db_prefix}comunidades_miembros
      SET aprobado = 1
      WHERE id_com = $Esta", __FILE__, __LINE__);
  }

  db_query("
    UPDATE {$db_prefix}comunidades
    SET
      nombre = SUBSTRING('$nombre', 1, 100), 
      descripcion = SUBSTRING('$descripcion', 1, 2500),
      acceso = '$acceso',
      permiso = '$permiso',
      aprobar = '$aprobar',
      imagen = '$imagen',
      categoria = '$cat'
    WHERE id = '$Esta'
    LIMIT 1", __FILE__, __LINE__);
}

header('Location: ' . $boardurl . '/comunidades/' . $url . '/');

?>