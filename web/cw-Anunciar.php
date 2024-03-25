<?php require("cw-conexion-seg-0011.php");
global $db_prefix,$user_info;

if(($user_info['is_admin'] || $user_info['is_mods'])){
$dat=seguridad($_POST['anuncio']);

db_query("UPDATE {$db_prefix}settings SET value='$dat' WHERE variable='news' LIMIT 1",__FILE__, __LINE__);

die('1: Anuncio guardado correctamente.');

}

?>