<?php require("cw-conexion-seg-0011.php"); global $db_prefix,$context,$ID_MEMBER,$user_info,$sourcedir,$user_settings;

if($user_info['is_guest']){fatal_error('faltan datos.-');}

include($sourcedir.'/FuncionesCom.php');

$id=(int)$_POST['id'];
if(!$id){fatal_error('Debes seleccionar una comunidad.-');}
$rs44=db_query("
SELECT c.id, c.credito,c.url
FROM ({$db_prefix}comunidades as c)
WHERE c.id='$id' AND c.bloquear=0
LIMIT 1",__FILE__, __LINE__);
while ($row=mysqli_fetch_assoc($rs44)){$dasdasd=$row['id'];$credito=$row['credito'];$url=$row['url'];}
if(!$dasdasd){fatal_error('La comuniad no existe.-');}
baneadoo($dasdasd);
permisios($dasdasd);
if($context['permisoCom']==1){  
if($credito=='100'){fatal_error('Ya tienes credito en publicidad.-');}
elseif($user_settings['posts'] > 499){

$time=time();
db_query("UPDATE {$db_prefix}comunidades
			SET credito=100, cred_fecha='$time'
			WHERE id='$dasdasd'
			LIMIT 1", __FILE__, __LINE__);
db_query("UPDATE {$db_prefix}members
			SET posts=posts-100
			WHERE ID_MEMBER='$ID_MEMBER'
			LIMIT 1", __FILE__, __LINE__);}
else{fatal_error('Para publicitar tu comunidad debes tener m�s de 500 puntos');}}else{fatal_error('No tenes permisos para publicitar esta comunidad');}

Header("Location: /comunidades/$url/");
die();exit();
?>