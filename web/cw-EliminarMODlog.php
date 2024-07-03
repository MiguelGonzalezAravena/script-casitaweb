<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $db_prefix, $user_info;

if ($user_info['is_admin']) {
  db_query("
    DELETE FROM {$db_prefix}log_actions", __FILE__, __LINE__);

  die('1: OK');
} else {
  die('0: No tienes permisos para realizar esta acci&oacute;n.');
}

?>