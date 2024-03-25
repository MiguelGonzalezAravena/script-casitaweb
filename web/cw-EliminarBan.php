<?php require("cw-conexion-seg-0011.php"); 
global $context, $sourcedir, $db_prefix,$user_settings,$user_info;
    
if($user_info['is_admin'] || $user_info['is_mods']){
$id=(int)$_POST['is'];
if(empty($id)){die('0: Debes seleccionar el ban a eliminar.');}

$request=db_query("SELECT p.notes,p.clave
FROM ({$db_prefix}ban_groups AS p)
WHERE p.ID_BAN_GROUP='$id'
LIMIT 1", __FILE__, __LINE__);
while($row=mysql_fetch_array($request)){
$context['ussdee']=$row['notes'];
$context['clave']=$row['clave'];}
$context['ussdee']=isset($context['ussdee']) ? $context['ussdee'] : '';
if(empty($context['ussdee'])){die('0: El Ban no existe.');}

require($sourcedir.'/ManageBans.php');

if($context['ussdee']==$user_settings['ID_MEMBER']){
db_query("DELETE FROM {$db_prefix}ban_groups WHERE ID_BAN_GROUP='$id' LIMIT 1", __FILE__, __LINE__);
updateSettings(array('banLastUpdated' => time()));
die('1: Desbaneado correctamente.'); exit();
}else{
$_POST['clave']=seguridad($_POST['clave']);
if($_POST['clave']==$context['clave']){
db_query("DELETE FROM {$db_prefix}ban_groups WHERE ID_BAN_GROUP='$id' LIMIT 1", __FILE__, __LINE__);
updateSettings(array('banLastUpdated' => time()));

die('1: Desbaneado correctamente.'); exit();

}else{die('0: Debes ingresar la clave correctamente.');}}
}else{die('0: Disculpe, no puedes estar aqui.');} ?>