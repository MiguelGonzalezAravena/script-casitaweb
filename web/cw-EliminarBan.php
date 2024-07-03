<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $context, $sourcedir, $db_prefix, $user_settings, $user_info, $ID_MEMBER;

$id = isset($_POST['is']) ? (int) $_POST['is'] : 0;

if ($user_info['is_admin'] || $user_info['is_mods']) {
  if (empty($id)) {
    die('0: Debes seleccionar el ban a eliminar.');
  }

  $request = db_query("
    SELECT p.notes,p.clave
    FROM ({$db_prefix}ban_groups AS p)
    WHERE p.ID_BAN_GROUP='$id'
    LIMIT 1", __FILE__, __LINE__);

  $row = mysqli_fetch_array($request);
  $context['ussdee'] = isset($row['notes']) ? $row['notes'] : '';
  $context['clave'] = $row['clave'];

  if (empty($context['ussdee'])) {
    die('0: El Ban no existe.');
  }

  require_once($sourcedir . '/ManageBans.php');

  if ($context['ussdee'] == $ID_MEMBER) {
    db_query("
      DELETE FROM {$db_prefix}ban_groups
      WHERE ID_BAN_GROUP = $id
      LIMIT 1", __FILE__, __LINE__);

    updateSettings(array('banLastUpdated' => time()));

    die('1: Desbaneado correctamente.');
  } else {
    $_POST['clave'] = seguridad($_POST['clave']);

    if ($_POST['clave'] == $context['clave']) {
      db_query("
        DELETE FROM {$db_prefix}ban_groups
        WHERE ID_BAN_GROUP = $id
        LIMIT 1", __FILE__, __LINE__);

      updateSettings(array('banLastUpdated' => time()));

      die('1: Desbaneado correctamente.');
    } else {
      die('0: Debes ingresar la clave correctamente.');
    }
  }
} else {
  die('0: No tienes los privilegios necesarios para realizar esta acci&oacute;n.');
}

?>