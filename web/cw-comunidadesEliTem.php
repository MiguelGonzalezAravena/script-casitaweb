<?php require("cw-conexion-seg-0011.php"); global $db_prefix,$context,$sourcedir,$user_settings;
include($sourcedir.'/FuncionesCom.php');
if($user_info['is_guest']){fatal_error('Faltan datos.-');}

$id=(int)$_GET['id'];
if(!$id){fatal_error('Faltan datos.-');}
$rs44=db_query("
SELECT a.id,a.id_com,co.url,a.titulo,a.id_user
FROM ({$db_prefix}comunidades_articulos as a, {$db_prefix}comunidades AS co)
WHERE a.id='$id' AND a.id_com=co.id AND a.eliminado=0
LIMIT 1",__FILE__, __LINE__);
while ($row=mysqli_fetch_assoc($rs44)){
    $dasdasd=$row['id'];
    $url=$row['url'];
    $vbvbvki=$row['id_user'];
    $id_com=$row['id_com'];
    $titulo=$row['titulo'];}
$dasdasd=isset($dasdasd) ? $dasdasd : '';
if(empty($dasdasd)){fatal_error('Faltan datos.');}

baneadoo($id_com);
permisios($id_com);

if($context['permisoCom']=='1' || $context['permisoCom']=='2' || $vbvbvki==$user_settings['ID_MEMBER']){

db_query("UPDATE {$db_prefix}comunidades_articulos
			SET eliminado=1
			WHERE id='$dasdasd'
			LIMIT 1", __FILE__, __LINE__);


db_query("UPDATE {$db_prefix}comunidades
			SET articulos=articulos-1
			WHERE id='$id_com'
			LIMIT 1", __FILE__, __LINE__);

Header("Location: /comunidades/$url/$dasdasd/$titulo.html");
die();exit();}else{Header("Location: /comunidades/");die();exit();}
?>