<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $db_prefix, $tranfer1, $user_settings, $user_info, $context;

if ($user_info['is_guest']) {
  die('0: Funcionalidad exclusiva de usuarios registrados.');
} else {
  $getid = isset($_GET['eliminar']) ? (int) $_GET['eliminar'] : 0;
  $leer = db_query("
    SELECT leido
    FROM {$db_prefix}mensaje_personal
    WHERE id = $getid
    AND id_para = $ID_MEMBER
    AND eliminado_para = 0
    LIMIT 1", __FILE__, __LINE__);

  while ($row = mysqli_fetch_array($leer)) {
    if (empty($row['leido'])) {
      db_query("
        UPDATE {$db_prefix}mensaje_personal
        SET leido = 1
        WHERE id = $getid
        LIMIT 1", __FILE__, __LINE__);

      db_query("
        UPDATE {$db_prefix}members
        SET topics = topics - 1
        WHERE ID_MEMBER = $ID_MEMBER
        LIMIT 1", __FILE__, __LINE__);
    }

    db_query("
      UPDATE {$db_prefix}mensaje_personal
      SET eliminado_para = 1
      WHERE id = $getid
      AND id_para = $ID_MEMBER
      LIMIT 1", __FILE__, __LINE__);

    updateMensajesEliminados($getid);
  }

  mysqli_free_result($leer);

  die('1: Mensaje borrado satisfactoriamente.');
}

?>