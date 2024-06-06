<?php require("cw-conexion-seg-0011.php"); global $db_prefix,$context,$sourcedir,$user_settings;
include($sourcedir.'/FuncionesCom.php');
if($user_info['is_guest']){fatal_error('Faltan datos.-');}

$id=(int)$_GET['id'];
if(empty($id)){fatal_error('Faltan datos.');}
$rs44=db_query("
SELECT a.id,a.id_com,co.url,a.titulo,a.id_user
FROM ({$db_prefix}comunidades_articulos as a, {$db_prefix}comunidades_comentarios AS c, {$db_prefix}comunidades AS co)
WHERE c.id='$id' AND c.id_tema=a.id AND a.eliminado=0 AND a.id_com=co.id
LIMIT 1",__FILE__, __LINE__);
while ($row=mysqli_fetch_assoc($rs44)){
    $dasdasd=$row['id'];
    $url=$row['url'];
    $id_com=$row['id_com'];
    $vbvbvki=$row['id_user'];
    $titulo=$row['titulo'];}
$dasdasd=isset($dasdasd) ? $dasdasd : '';
if(empty($dasdasd)){fatal_error('Faltan datos.-');}

baneadoo($id_com);
permisios($id_com);
if($context['permisoCom']=='1'  || $context['permisoCom']=='3' || $context['permisoCom']=='2' || $vbvbvki==$user_settings['ID_MEMBER']){

db_query("
DELETE FROM {$db_prefix}comunidades_comentarios
WHERE id='$id' 
LIMIT 1", __FILE__, __LINE__);

db_query("UPDATE {$db_prefix}comunidades_articulos
			SET respuestas=respuestas-1
			WHERE id='$dasdasd'
			LIMIT 1", __FILE__, __LINE__);

Header("Location: /comunidades/$url/$dasdasd/$titulo.html");
die();exit();}
else{Header("Location: /comunidades/");die();exit();}
?>