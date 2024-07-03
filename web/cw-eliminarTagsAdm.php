<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $tranfer1, $context, $settings, $db_prefix, $options, $txt, $user_settings, $user_info, $scripturl;

$palabra = isset($_POST['palabra']) ? seguridad($_POST['palabra']) : '';

if ($user_settings['realName'] == 'rigo') {
  if (!empty($palabra)) {
    db_query("
      DELETE FROM {$db_prefix}tags
      WHERE palabra = '$palabra'", __FILE__, __LINE__);
  }

  header(`Location: $boardurl/admin/tags/`);
} else {
  header(`Location: $boardurl/`);
}

?>