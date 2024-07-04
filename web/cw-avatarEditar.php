<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $tranfer1, $context, $settings, $options, $no_avatar, $txt, $scripturl, $modSettings;
global $db_prefix, $user_info, $user_settings;

$avatars = isset($_POST['avatar']) ? seguridad($_POST['avatar']) : '';
$avatars2 = valida_url($avatars);
$admin = isset($_POST['admin']) ? (int) $_POST['admin'] : 0;
$sina = isset($_POST['sinavatar']) ? (int) $_POST['sinavatar'] : 0;
$id_user = isset($_POST['id_user']) ? (int) $_POST['id_user'] : 0;
$sinavatar = $sina == 0 ? 0 : 1;

if ($user_info['is_guest']) {
  fatal_error('Funcionalidad exclusiva de usuarios registrados.');
}

if (!$avatars2) {
  fatal_error('Imagen no reconocida.');
}

// TO-DO: ¿Qué valida esto?
if (!$user_info['is_admin'] && $id_user == 1) {
  fatal_error('No puedes estar ac&aacute;.', false);
}

if (strlen($avatars) > 110) {
  fatal_error('El enlace del avatar no puede ser mayor de <b>110 letras</b><br/>Sube la imagen a <a href="' . $modSettings['host_imagen'] . '">' . $modSettings['host_imagen'] . '</a> que los enlaces son cortos.');
}

if (empty($avatars)) {
  fatal_error('Debes agregar el avatar.');
}

// Define si estás administrando a alguien o no
// También si tienes los permisos
if ($admin_mode) {
  if ($user_info['is_admin'] || $user_info['is_mods']) {
    if (!$id_user) {
      fatal_error('Debes seleccionar un usuario.', false);
    }

    if ($sinavatar) {
      db_query("
        UPDATE {$db_prefix}members
        SET avatar = ''
        WHERE ID_MEMBER = $id_user
        LIMIT 1", __FILE__, __LINE__);
    } else {
      db_query("
        UPDATE {$db_prefix}members
        SET avatar = '$avatars'
        WHERE ID_MEMBER = $id_user
        LIMIT 1", __FILE__, __LINE__);
    }

    header('Location: ' . $boardurl . '/admin/edit-user/avatar/' . $id_user);
  } else {
    fatal_error('No puedes estar ac&aacute;.');
  }
} else if (!$admin_mode) {
  if ($sinavatar) {
    db_query("
      UPDATE {$db_prefix}members
      SET avatar = ''
      WHERE ID_MEMBER = $ID_MEMBER
      LIMIT 1", __FILE__, __LINE__);
  } else {
    db_query("
      UPDATE {$db_prefix}members
      SET avatar = '$avatars'
      WHERE ID_MEMBER = $ID_MEMBER
      LIMIT 1", __FILE__, __LINE__);
  }

  header('Location: ' . $boardurl . '/editar-perfil/avatar/');
}

?>