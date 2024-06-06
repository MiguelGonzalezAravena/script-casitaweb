<?php require("cw-conexion-seg-0011.php");
global $db_prefix,$tranfer1,$user_settings,$user_info,$context;
if($user_info['is_guest']){die('0: ');}else{
$getid=(int)$_GET['eliminar'];
$leer=db_query("
SELECT p.leido
FROM ({$db_prefix}mensaje_personal AS p)
WHERE p.id='{$getid}' AND p.id_para='{$user_settings['ID_MEMBER']}' AND p.eliminado_para=0
LIMIT 1", __FILE__, __LINE__);
while($row=mysqli_fetch_array($leer)){
if(empty($row['leido'])){
db_query("UPDATE {$db_prefix}mensaje_personal SET leido=1 WHERE id='{$getid}' LIMIT 1", __FILE__, __LINE__);
db_query("UPDATE {$db_prefix}members SET topics=topics-1 WHERE ID_MEMBER='{$user_settings['ID_MEMBER']}' LIMIT 1", __FILE__, __LINE__);}

db_query("UPDATE {$db_prefix}mensaje_personal SET eliminado_para=1 WHERE id='{$getid}' AND id_para='{$user_settings['ID_MEMBER']}' LIMIT 1", __FILE__, __LINE__);

actualizareliminados($getid);}
mysqli_free_result($leer);

die('1: ');

}
?>