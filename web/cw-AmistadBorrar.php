<?php require("cw-conexion-seg-0011.php");
global $user_info, $user_settings, $db_prefix;
if($user_info['is_guest']){die('0: No estas loguead.');}

$ids= isset($_GET['user']) ? (int)$_GET['user'] : '';
if(empty($ids)){die('0: No seleccionastes a nadie, para borrar como amistad.');}

$ser=mysql_num_rows(db_query("SELECT ID_MEMBER FROM {$db_prefix}members WHERE ID_MEMBER='$ids' LIMIT 1",__FILE__, __LINE__));

if(empty($ser)){die('0: El usuario no existe.');}
elseif($ids===$user_settings['ID_MEMBER']){die('0: No podes borrarte como amigo.');}

$datosmem=db_query("SELECT user,amigo,id FROM ({$db_prefix}amistad) WHERE (user='{$user_settings['ID_MEMBER']}' AND amigo='$ids' OR user='$ids' AND amigo='{$user_settings['ID_MEMBER']}') AND acepto=1 LIMIT 1",__FILE__, __LINE__);
while($data=mysql_fetch_assoc($datosmem)){$id=$data['id'];}
$yadio=mysql_num_rows($datosmem) != 0 ? true : false; mysql_free_result($datosmem);

if($yadio){
db_query("DELETE FROM {$db_prefix}amistad WHERE id='$id'",__FILE__, __LINE__);
die('1: Eliminado correctamente.');}
else{die('0: Este usuario no es amigo tuyo.');} ?>