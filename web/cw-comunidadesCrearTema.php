<?php require("cw-conexion-seg-0011.php"); global $user_info,$db_prefix,$user_settings;

if($user_info['is_guest']){fatal_error('faltan datos.-');}

ignore_user_abort(true);
@set_time_limit(300);

$id=seguridad($_POST['comun']);
if(!$id){fatal_error('Debe seleccionar una comunidad.-');}
$rs=db_query("SELECT c.nombre,b.rango,c.id,c.categoria,c.url
FROM ({$db_prefix}comunidades_miembros AS b, {$db_prefix}comunidades AS c)
WHERE c.url='$id' AND c.id=b.id_com AND b.id_user='{$user_settings['ID_MEMBER']}' AND c.bloquear='0'
LIMIT 1",__FILE__, __LINE__);
while ($row=mysqli_fetch_assoc($rs)){
$cat=seguridad(nohtml($row['nombre']));
$rango=$row['rango'];
$url=$row['url'];
$dsds=$row['id'];
$dsdffs=$row['categoria'];}
if(!$cat){fatal_error('No sos miembro de esta comunidad.-');}

include($sourcedir.'/FuncionesCom.php');
baneadoo($dsds);
acces($dsds);

if($context['puedo']=='1' || $context['puedo']=='3'){
//tiempo aregar post
$rs=db_query("SELECT creado FROM ({$db_prefix}comunidades_articulos) WHERE id_user='{$user_settings['ID_MEMBER']}' AND id_com='$dsds'",__FILE__, __LINE__);while($row=mysqli_fetch_assoc($rs)){$creado=$row['creado'];}
if($creado>time()-30){fatal_error('No es posible agregar temas con tan poca diferencia de tiempo.<br />Vuelva a intentar en segundos.-');}
// FINN tiempo aregar post

if(!$rango){
$rkks=db_query("SELECT c.permiso FROM ({$db_prefix}comunidades AS c) WHERE c.url='$id'",__FILE__, __LINE__);
while($row=mysqli_fetch_assoc($rkks)){$rangofinal=$row['permiso'];}    

if($rangofinal != '3'){fatal_error('No tienes permiso para postear en esta comunidad.-');}}else{$rangofinal='4';}
if($rangofinal=='4'){$stiky=$_POST['sticky'] ? '1' : '0';}else{$stiky='0';}
$nocoment=$_POST['nocoment'] ? '1' : '0';


$titulo=trim(seguridad($_POST['titulo']));
if(!$titulo){fatal_error('Debes agregar un titulo.-');}
if(strlen($titulo)<3){fatal_error('El titulo no puede tener menos de <b>3 letras</b>.-');}
if(strlen($titulo)>=61){fatal_error('El titulo no puede tener m&aacute;s de <b>60 letras</b>.-');}

$cuerpo=seguridad($_POST['cuerpo_comment']);
if(!$cuerpo){fatal_error('Debes agregar un contenido.-');}
if(strlen($cuerpo)<=15){fatal_error('El contenido del tema no puede tener menos de <b>15 letras</b>.-');}
if(strlen($cuerpo)>$modSettings['max_messageLength']){fatal_error('El contenido del tema no puede tener m&aacute;s de <b>'.$modSettings['max_messageLength'].' letras</b>.-');}

db_query("INSERT INTO {$db_prefix}comunidades_articulos (id_user, id_com, titulo, cuerpo, creado, nocoment, stiky, UserName, categoria) 
    VALUES ('{$user_settings['ID_MEMBER']}', '$dsds', SUBSTRING('$titulo', 1,100), SUBSTRING('$cuerpo', 1, 60000), ".time().", '$nocoment', '$stiky', '{$user_settings['realName']}', '$dsdffs')", __FILE__, __LINE__);
    
    db_query("
			UPDATE {$db_prefix}comunidades
			SET articulos=articulos+1
			WHERE id='$dsds'
			LIMIT 1", __FILE__, __LINE__);
            
Header("Location: /comunidades/$url/");exit();die();}else{fatal_error('No tenes permiso suficiente.-');}
?>