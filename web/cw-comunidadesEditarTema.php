<?php require("cw-conexion-seg-0011.php"); global $user_info,$db_prefix,$user_settings,$ID_MEMBER;

if($user_info['is_guest']){fatal_error('faltan datos.-');}

ignore_user_abort(true);
@set_time_limit(300);

$id=(int)$_POST['id_tema'];
if(!$id){fatal_error('Debe seleccionar un tema.-');}

$rs=db_query("SELECT t.id_com,t.id_user,t.stiky
FROM ({$db_prefix}comunidades_articulos AS t)
WHERE t.id='$id' AND t.eliminado=0",__FILE__, __LINE__);
while ($row=mysql_fetch_assoc($rs)){
$id_com=$row['id_com'];
$id_user=$row['id_user'];
$stikyd=$row['stiky'];}
if(!$id_user){fatal_error('Este tema no existe.-');}

include($sourcedir.'/FuncionesCom.php');
permisios($id_com);
baneadoo($id_com);
acces($id_com);

if($context['permisoCom']=='1' || $context['permisoCom']=='2' || $context['permisoCom']=='3' || $id_user==$ID_MEMBER){
if($context['puedo']=='1' || $context['puedo']=='3'){
    

if($context['permisoCom']=='1' || $context['permisoCom']=='3'){$stiky=$_POST['sticky'] ? '1' : '0';}
else{$stiky=$stikyd;}
$nocoment=$_POST['nocoment'] ? '1' : '0';

$titulo=trim(seguridad($_POST['titulo']));
if(!$titulo){fatal_error('Debes agregar un titulo.-');}
if(strlen($titulo)<3){fatal_error('El titulo no puede tener menos de <b>3 letras</b>.-');}
if(strlen($titulo)>=61){fatal_error('El titulo no puede tener m&aacute;s de <b>60 letras</b>.-');}

$cuerpo=seguridad($_POST['cuerpo_comment']);
if(!$cuerpo){fatal_error('Debes agregar un contenido.-');}
if(strlen($cuerpo)<=15){fatal_error('El contenido del tema no puede tener menos de <b>15 letras</b>.-');}
if(strlen($cuerpo)>$modSettings['max_messageLength']){fatal_error('El contenido del tema no puede tener m&aacute;s de <b>'.$modSettings['max_messageLength'].' letras</b>.-');}


db_query("UPDATE {$db_prefix}comunidades_articulos
 SET titulo=SUBSTRING('$titulo', 1,100),
 cuerpo=SUBSTRING('$cuerpo', 1, 60000),
 nocoment=$nocoment,
 stiky=$stiky
 WHERE id='$id'
 LIMIT 1", __FILE__, __LINE__);
 
$rs=db_query("SELECT c.url FROM ({$db_prefix}comunidades AS c) WHERE c.id='$id_com' LIMIT 1",__FILE__, __LINE__);
while ($row=mysql_fetch_assoc($rs)){$url=$row['url'];}

 
Header("Location: /comunidades/$url/");exit();die();}
else{fatal_error('No tenes permiso suficiente.-');}}
else{fatal_error('No tenes permiso suficiente.-');}
?>