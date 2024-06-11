<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $db_prefix, $context, $sourcedir, $user_info, $user_settings, $boardurl;

if ($user_info['is_guest']) {
  fatal_error('S&oacute;lo usuarios conectados pueden unirse a las comunidades.');
}

require_once($sourcedir . '/FuncionesCom.php');

$id = isset($_GET['id']) ? seguridad($_GET['id']) : '';

if (empty($id)) {
  fatal_error('Debes seleccionar una comunidad.');
}

$rs44 = db_query("
  SELECT id, aprobar
  FROM {$db_prefix}comunidades
  WHERE url = '$id'
  AND bloquear = 0
  LIMIT 1", __FILE__, __LINE__);

while ($row = mysqli_fetch_assoc($rs44)) {
  $dasdasd = $row['id'];
  $aprobar = $row['aprobar'];
}

$dasdasd = isset($dasdasd) ? $dasdasd : '';

if (empty($dasdasd)) {
  fatal_error('La comuniad no existe.');
}

baneadoo($dasdasd);
miembro($dasdasd);

if ($context['miembro']) {
  fatal_error('Ya eres miembro de esta comunidad.');
}

$dascc = $aprobar ? 0 : 1;

if (eaprobacion($dasdasd)) {
  fatal_error('Esperando aprobaci&oacute;n de administrador.');
}

db_query("
  INSERT INTO {$db_prefix}comunidades_miembros (id_user, id_com, rango, aprobado, ban)
  VALUES ({$user_settings['ID_MEMBER']}, $dasdasd, 0, $dascc, 0)", __FILE__, __LINE__);

if ($aprobar) {
  db_query("
    UPDATE {$db_prefix}comunidades
    SET paprobar = paprobar + 1
    WHERE id = '$dasdasd'
    LIMIT 1", __FILE__, __LINE__);

  fatal_error('Te has unido a la comunidad correctamente. Ahora s&oacute;lo falta que un administrador te apruebe.', false, '¡¡¡Felicitaciones!!!', '900');
} else {
  db_query("
    UPDATE {$db_prefix}comunidades
    SET usuarios = usuarios + 1
    WHERE id = '$dasdasd'
    LIMIT 1", __FILE__, __LINE__);

  header('Location: ' . $boardurl . '/comunidades/' . $id . '/');
}

?>