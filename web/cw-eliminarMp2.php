<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $db_prefix, $tranfer1, $user_settings, $user_info, $context;

if ($user_info['is_guest']) {
  fatal_error('Funcionalidad exclusiva de usuarios registrados.');
} else {
  $getid = isset($_GET['id_sde']) ? (int) $_GET['id_sde'] : 0;

  db_query("
    UPDATE {$db_prefix}mensaje_personal
    SET eliminado_de = 1
    WHERE id = $getid
    AND id_de = $ID_MEMBER", __FILE__, __LINE__);

  updateMensajesEliminados($getid);

  header('Location: ' . $boardurl . '/mensajes/enviados/');
}

?>