<?php require("cw-conexion-seg-0011.php");
global $db_prefix,  $user_settings,$user_info;
if($user_info['is_guest']){die('0: error');}else{
    
$ids=(int)$_GET['id'];
if(empty($ids)){die('0: Debe seleccionar el id.-');}else{

$datosmem=db_query("
SELECT m.id_user,u.realName,m.id_cc 
FROM ({$db_prefix}muro AS m)
INNER JOIN {$db_prefix}members AS u ON m.id='$ids' AND u.ID_MEMBER=m.id_user
LIMIT 1",__FILE__, __LINE__);
while($data=mysqli_fetch_assoc($datosmem)){
$ser=$data['id_user'];
$id_cc=$data['id_cc'];
$nomade=$data['realName'];}
mysqli_free_result($datosmem);
$id_cc= isset($id_cc) ? $id_cc : '0';
$ser= isset($ser) ? $ser : '0';
if(empty($ser)){die('0: Este comentario no existe.');}
    
if($user_settings['ID_MEMBER']===$ser || ($user_info['is_admin'] || $user_info['is_mods'])){
db_query("DELETE FROM {$db_prefix}muro WHERE id='$ids' LIMIT 1",__FILE__, __LINE__);
if(!empty($id_cc)){
    db_query("
			UPDATE {$db_prefix}muro
			SET ccos = ccos - 1
			WHERE id='$id_cc'
			LIMIT 1", __FILE__, __LINE__);
}else{db_query("DELETE FROM {$db_prefix}muro WHERE id_cc='$ids' LIMIT 1",__FILE__, __LINE__);}
die('1: Ok');}
else{die('0: No podes eliminar el mensaje de este muro.');} }}

?>