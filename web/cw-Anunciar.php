<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $db_prefix, $user_info;

$anuncio = isset($_POST['anuncio']) ? seguridad($_POST['anuncio']) : '';

if (($user_info['is_admin'] || $user_info['is_mods'])) {
  db_query("
    UPDATE {$db_prefix}settings
    SET value = '$anuncio'
    WHERE variable = 'news'
    LIMIT 1", __FILE__, __LINE__);

  die('1: Anuncio guardado correctamente.');
}

?>