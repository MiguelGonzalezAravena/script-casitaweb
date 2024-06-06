<?php require("cw-conexion-seg-0011.php");
global $context, $db_prefix, $user_settings, $user_info;
if($user_settings['ID_GROUP']=='7' || $user_settings['ID_GROUP']=='11' || ($user_info['is_admin'] || $user_info['is_mods'])){
    
$user=seguridad($_POST['user']);
if(empty($user)){die('0: Debes seleccionar a un usuario.');}

$requestr= db_query("
SELECT ID_POST_GROUP,ID_MEMBER,ID_GROUP
FROM {$db_prefix}members
WHERE realName='$user'
LIMIT 1",__FILE__, __LINE__);
while ($grupsa = mysqli_fetch_assoc($requestr)){
	$id_post_grup=$grupsa['ID_POST_GROUP'];
	$id_grup=$grupsa['ID_GROUP'];
	$id_user=$grupsa['ID_MEMBER'];}
	mysqli_free_result($requestr);
    
    
$id_user=isset($id_user) ? $id_user : '';
if(empty($id_user)){die('0: El usuario no existe.');}

if(empty($user_settings['dar_dia'])){die('0: No tienes recargas disponibles.');}

if($user_settings['ID_GROUP']=='7'){$pts='5';}
if($user_settings['ID_GROUP']=='11'){$pts='5';}
if($user_info['is_admin'] || $user_info['is_mods']){$pts='10';}

if($user_settings['dar_dia']>$pts){die('0: No puedes dar m&aacute;s de '.$pts.' recargos diarios.');}
if($context['ID_MEMBER']==$user_settings['ID_MEMBER']){die('0: No te podes recargar puntos a vos.');}

if(empty($id_grup)){$grupoo=$id_post_grup;}else{$grupoo=$id_grup;}

$cantidadDDD= db_query("
SELECT CantidadDePuntos
FROM {$db_prefix}membergroups
WHERE ID_GROUP='$grupoo'
LIMIT 1",__FILE__, __LINE__);
while ($row = mysqli_fetch_assoc($cantidadDDD)){$das=$row['CantidadDePuntos'];}
mysqli_free_result($cantidadDDD);
if(empty($das)){die('0: El rango de este usuario no contiene puntos.');}


db_query("UPDATE {$db_prefix}members SET dar_dia=dar_dia-1 WHERE ID_MEMBER='{$user_settings['ID_MEMBER']}' LIMIT 1",__FILE__, __LINE__);
db_query("UPDATE {$db_prefix}members SET puntos_dia='$das' WHERE ID_MEMBER='$id_user' LIMIT 1",__FILE__, __LINE__);

die('1: Puntos recargados correctamente');

}else{die();}
?>