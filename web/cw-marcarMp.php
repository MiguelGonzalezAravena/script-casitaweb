<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $context, $db_prefix, $user_settings, $user_info, $boardurl;

if ($user_info['is_guest']) {
  die('Funcionalidad exclusiva de usuarios registrados.');
} else {
  $getid = isset($_GET['id']) ? (int) $_GET['id'] : 0;

  $leer = db_query("
    SELECT leido
    FROM {$db_prefix}mensaje_personal
    WHERE id = $getid
    AND id_para = $ID_MEMBER
    AND eliminado_para = 0
    LIMIT 1", __FILE__, __LINE__);

  while ($row = mysqli_fetch_array($leer)) {
    if (!empty($row['leido'])) {
      db_query("
        UPDATE {$db_prefix}mensaje_personal
        SET leido = 0
        WHERE id = $getid
        AND id_para = $ID_MEMBER
        LIMIT 1", __FILE__, __LINE__);

      db_query("
        UPDATE {$db_prefix}members
        SET topics = topics + 1
        WHERE ID_MEMBER = $ID_MEMBER
        LIMIT 1", __FILE__, __LINE__);
    }
  }

  mysqli_free_result($leer);

  header('Location: ' . $boardurl . '/mensajes/');
}

?>