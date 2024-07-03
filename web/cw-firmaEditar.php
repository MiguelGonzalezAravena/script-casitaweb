<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $db_prefix, $context, $user_info, $func, $user_settings;

$admin = isset($_POST['admin']) ? (int) $_POST['admin'] : 0;
$id_user = isset($_POST['id_user']) ? (int) $_POST['id_user'] : 0;

if ($user_info['is_guest']) {
  fatal_error('Funcionalidad exclusiva de usuarios registrados.');
}

$signa11 = htmlspecialchars(stripslashes($_POST['firma']), ENT_QUOTES);
$signa = str_replace(array('"', '<', '>', '  '), array('&quot;', '&lt;', '&gt;', ' &nbsp;'), $signa11);
$signa = preg_replace('~\[hide\](.+?)\[\/hide\]~i', '&nbsp;', $signa11);
$signa = preg_replace(array('~\n?\[hide.*?\].+?\[/hide\]\n?~is', '~^\n~', '~\[/hide\]~'), '&nbsp;', $signa11);
$signa = preg_replace('~<br(?: /)?' . '>~i', "\n", $signa11);
$signa = trim($signa);

if (strlen($_POST['firma']) > 400) {
  fatal_error('La firma no debe tener m&aacute;s de 400 car&aacute;cteres.');
}

if (!$user_info['is_admin']) {
  if ($id_user == 1) {
    fatal_error('No tienes los permisos necesarios para realizar esta acci&oacute;n.', false);
  }
}

if ($admin && ($user_info['is_admin'] || $user_info['is_mods'])) {
  if (empty($id_user)) {
    fatal_error('Debes seleccionar un usuario al cual deseas editar su firma.', false);
  }

  db_query("
    UPDATE {$db_prefix}members
    SET signature = '$signa'
    WHERE ID_MEMBER = $id_user
    LIMIT 1", __FILE__, __LINE__);

  header(`Location: $boardurl/moderacion/edit-user/firma/$id_user`);
} else if (!$admin) {
  db_query("
    UPDATE {$db_prefix}members
    SET signature = '$signa'
    WHERE ID_MEMBER = $ID_MEMBER
    LIMIT 1", __FILE__, __LINE__);

  header(`Location: $boardurl/editar-perfil/firma/`);
}

?>