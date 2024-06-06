<?php require("cw-conexion-seg-0011.php"); global $db_prefix,$context,$sourcedir,$user_settings;

if($user_info['is_guest']){die();}
$id=isset($_GET['id']) ? (int)$_GET['id'] : '';

if(empty($id)){die('0: El tema no existe.');}
$rs44=db_query("
SELECT a.id_com
FROM ({$db_prefix}comunidades_articulos as a)
WHERE a.id='$id' AND a.eliminado=1
LIMIT 1",__FILE__, __LINE__);
while ($row=mysqli_fetch_assoc($rs44)){$id_com=$row['id_com'];}
$id_com=isset($id_com) ? $id_com : '';
if(empty($id_com)){die('0: El tema no existe.');}

include($sourcedir.'/FuncionesCom.php');
baneadoo($id_com);
permisios($id_com);
if($context['permisoCom']=='1' || $context['permisoCom']=='3' || $context['permisoCom']=='2'){

db_query("UPDATE {$db_prefix}comunidades_articulos
			SET eliminado=0
			WHERE id='$id'
			LIMIT 1", __FILE__, __LINE__);


db_query("UPDATE {$db_prefix}comunidades
			SET articulos=articulos+1
			WHERE id='$id_com'
			LIMIT 1", __FILE__, __LINE__);

die('1: Ok');exit();}else{die();}
?>