<?php require("cw-conexion-seg-0011.php");
global $db_prefix,$tranfer1,$user_settings,$user_info,$context;
if($user_info['is_guest']){fatal_error('No podes estar aca.-');}else{
$getid=(int)$_GET['id_sde'];
db_query("UPDATE {$db_prefix}mensaje_personal SET eliminado_de=1 WHERE id='{$getid}' AND id_de='{$user_settings['ID_MEMBER']}'", __FILE__, __LINE__);
actualizareliminados($getid);
Header("Location: /mensajes/enviados/");}
?>