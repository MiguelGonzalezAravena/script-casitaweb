<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $db_prefix, $user_settings, $user_info;

$denunciante = isset($_GET['den']) ? (int) $_GET['den'] : 0;
$idden = isset($_GET['ident']) ? (int) $_GET['ident'] : 0;

if ($user_info['is_admin'] || $user_info['is_mods']) {
  if (empty($denunciante)) {
    die('0: Debes especificar el denunciante.');
  }

  if (empty($idden)) {
    die('0: Debes especificar la denuncia.');
  }

  db_query("
    UPDATE {$db_prefix}denuncias
    SET
      borrado = 1,
      atendido = '{$user_settings['realName']}'
    WHERE id_denuncia = $idden
    LIMIT 1", __FILE__, __LINE__);

  pts_sumar_grup($denunciante);
}

die('1: OK');

?>