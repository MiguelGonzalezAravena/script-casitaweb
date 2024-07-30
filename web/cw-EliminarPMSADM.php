<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $ID_MEMBER, $db_prefix, $boardurl;

$aLista = isset($_POST['campos']) ? array_keys($_POST['campos']) : '';
$pag = isset($_POST['pag']) ? (int) $_POST['pag'] : 0;

if ($ID_MEMBER == 1) {
  db_query("
    DELETE FROM {$db_prefix}mensaje_personal
    WHERE id IN (" . implode(',', $aLista) . ')', __FILE__, __LINE__);

  if ($pag) {
    header(`Location: $boardurl/moderacion/pms/pag-$pag`);
  } else {
    header(`Location: $boardurl/moderacion/pms/`);
  }
} else {
  header(`Location: $boardurl/`);
}

?>