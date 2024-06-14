<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $db_prefix, $user_info, $user_settings;

if ($user_info['is_guest']) {
  fatal_error('Faltan datos.');
}

ignore_user_abort(true);
@set_time_limit(300);

$nombre = isset($_POST['nombre']) ? seguridad($_POST['nombre']) : '';
$descripcion = isset($_POST['descripcion']) ? seguridad($_POST['descripcion']) : '';
$acceso = isset($_POST['privada']) ? (int) $_POST['privada'] : 0;
$aprobar = isset($_POST['aprobar']) ? (int) $_POST['aprobar'] : 0;
$permiso = isset($_POST['rango_default']) ? (int) $_POST['rango_default'] : 0;
$url = isset($_POST['shortname']) ? seguridad($_POST['shortname']) : '';
$imagen = isset($_POST['imagen']) ? seguridad($_POST['imagen']) : '';
$cat = isset($_POST['categoria']) ? seguridad($_POST['categoria']) : '';

if (!$user_settings['ID_GROUP']) {
  if ($user_settings['ID_POST_GROUP'] == 4) {
    $cantidadcom = 1;
  } else if ($user_settings['ID_POST_GROUP'] == 5) {
    $cantidadcom = 2;
  } else if ($user_settings['ID_POST_GROUP'] == 9) {
    $cantidadcom = 4;
  } else if ($user_settings['ID_POST_GROUP'] == 10) {
    $cantidadcom = 6;
  } else if ($user_settings['ID_POST_GROUP'] == 6) {
    $cantidadcom = 8;
  } else if ($user_settings['ID_POST_GROUP'] == 8) {
    $cantidadcom = 10;
  }
} else if ($user_settings['ID_GROUP']) {
  $cantidadcom = 15;
}

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

if (!preg_match('~[^a-zA-Z0-9\-]~', stripslashes($url)) == 0) {
  fatal_error('S&oacute;lo se permiten letras, n&uacute;meros y guiones medios (-).');
} else {
  $request = db_query("
    SELECT bloquear, id
    FROM {$db_prefix}comunidades
    WHERE url = '$url'
    ORDER BY id DESC 
    LIMIT 1", __FILE__, __LINE__);

  while ($row = mysqli_fetch_assoc($request)) {
    $id = $row['id'];
    $bloquear = $row['bloquear'];
  }
      
  if ($id && $bloquear) {
    $letras = '0x1o2m3b4r5a6H7b8c9dZ';

    srand((double) microtime() * 1000000);

    $i = 1;
    $largo_clave = 6;
    $largo = strlen($letras);
    $clave_usuario = '';

    while ($i <= $largo_clave) {
      $lee = rand(1, $largo);
      $clave_usuario .= substr($letras, $lee, 1);
      $i++;
    }

    $clave_usuario = trim($clave_usuario);
    $nombre_editado = $url . '-ELI-' . $clave_usuario;

    db_query("
      UPDATE {$db_prefix}comunidades
      SET url = '$nombre_editado'
      WHERE id = $id
      LIMIT 1", __FILE__, __LINE__);
  } else if ($id && !$bloquear) {
    fatal_error('El nombre seleccionado ya est&aacute; en uso.');
  } else if (strlen($url) < 5 || strlen($url) > 32) {
    fatal_error('El nombre debe tener entre 5 y 32 caracteres.');
  }
}

if (!$cat || $cat == '-1') {
  fatal_error('Debes elegir una categor&iacute;a');
}

$request = db_query("
  SELECT id, url
  FROM {$db_prefix}comunidades_categorias
  WHERE url = '$cat'
  LIMIT 1", __FILE__, __LINE__);

$comunidades_categorias = mysqli_num_rows($request);
$category = mysqli_fetch_assoc($request);
$id_category  = $category['id'];

if ($comunidades_categorias == 0) {
  fatal_error('Esta categor&iacuet;a no existe.');
}

$request = db_query("
  SELECT id_user
  FROM {$db_prefix}comunidades
  WHERE id_user = {$user_settings['ID_MEMBER']}
  AND bloquear = 0", __FILE__, __LINE__);

$rows = mysqli_num_rows($request);

if ($rows > $cantidadcom) {
  fatal_error('Tu rango no te permite tener m&aacute;s de ' . $cantidadcom . ' comunidades.', false);
}

db_query("
  INSERT INTO {$db_prefix}comunidades (id_user, nombre, descripcion, acceso, permiso, aprobar, url, imagen, categoria, UserName, cred_fecha, credito)
  VALUES ({$user_settings['ID_MEMBER']}, SUBSTRING('$nombre', 1, 100), SUBSTRING('$descripcion', 1, 2500), $acceso, $permiso, $aprobar, '$url', '$imagen', $id_category, '{$user_settings['realName']}', " . time() . ", 0)", __FILE__, __LINE__);

$id_community = db_insert_id();

db_query("
  INSERT INTO {$db_prefix}comunidades_miembros (id_user, id_com, rango, aprobado) 
  VALUES ({$user_settings['ID_MEMBER']}, $id_community, 1, 1)", __FILE__, __LINE__);

db_query("
  UPDATE {$db_prefix}comunidades_categorias
  SET comunidades = comunidades + 1
  WHERE id = $id_category
  LIMIT 1", __FILE__, __LINE__);

db_query("
  UPDATE {$db_prefix}comunidades
  SET usuarios = usuarios + 1
  WHERE id = $id_community
  LIMIT 1", __FILE__, __LINE__);  
        
header('Location: ' . $boardurl . '/comunidades/' . $url . '/');

?>