<?php require("cw-conexion-seg-0011.php");
global $context, $txt,$sourcedir, $ID_MEMBER,$scripturl, $boarddir, $user_settings,$user_info,$db_prefix,$modSettings;
if($user_info['is_guest']){fatal_error('Debes estar logueado');}
require($sourcedir . '/Subs-Post.php');
$id=(int)$_REQUEST['id'];
if(empty($id))fatal_error($txt['gallery_error_no_pic_selected']);

	$dbresult = db_query("SELECT p.ID_PICTURE, p.filename,p.title, p.ID_MEMBER, p.puntos
	FROM {$db_prefix}gallery_pic as p
	WHERE p.ID_PICTURE='$id'
	LIMIT 1", __FILE__, __LINE__);
	$row = mysql_fetch_assoc($dbresult);
	$memID=$row['ID_MEMBER'];
	$title=censorText(nohtml($row['title']));
	$puntosdados=$row['puntos'];
	mysql_free_result($dbresult);
if(empty($memID))fatal_error($txt['gallery_error_no_pic_selected']);
    
if(($user_info['is_admin'] || $user_info['is_mods']) || $ID_MEMBER == $memID){
if($puntosdados){db_query("UPDATE {$db_prefix}members SET posts=posts-$puntosdados WHERE ID_MEMBER='$memID'",__FILE__, __LINE__);}
		db_query("DELETE FROM {$db_prefix}gallery_comment WHERE ID_PICTURE='$id' LIMIT 1",__FILE__, __LINE__);
		db_query("DELETE FROM {$db_prefix}gallery_pic WHERE ID_PICTURE='$id' LIMIT 1",__FILE__, __LINE__);

$datosmem=db_query("SELECT realName,recibirmail FROM ({$db_prefix}members) WHERE ID_MEMBER='$memID'",__FILE__, __LINE__);
while($data=mysql_fetch_assoc($datosmem)){$ser=$data['realName'];$remail=$data['recibirmail'];}

if(($user_info['is_admin'] || $user_info['is_mods']) && $memID != $user_settings['ID_MEMBER']){

$causa=seguridad($_POST['causa']);
if(empty($causa)){
fatal_error('No agregastes la causa de la eliminaci&oacute;n.-');}
logAction('remove', array('Imagen' => $title.' (ID: '.$id.')', 'member' => $memID, 'causa' => $causa));

if($remail=='1'){
if(!empty($memID)){
$pmfrom = array(
'id' => $user_settings['ID_MEMBER'],
'name' => $user_settings['realName'],
'username' => $user_settings['realName']);
$titulo='Imagen eliminada: '.censorText($title);
$titulo2=censorText($title);
$causa=censorText($causa);
$message='Hola!

Lamento contarte que tu imagen titulada [b]'.$titulo2.'[/b] ha sido eliminada.

Causa: [b]'.$causa.'[/b]

Para acceder al protocolo, presiona [asd1256as4867cxc8c7a8xc7asd16a8e7a56s4da65s4d68as7da54d564dcv787v777v7v7v7v7v7as7eelprotocolopm=protocolo/][b]este enlace[/b][/asd1256as4867cxc8c7a8xc7asd16a8e7a56s4da65s4d68as7da54d564dcv787v777v7v7v7v7v7as7eelprotocolopm].

Muchas gracias por entender!';
sendpm($titulo, $message,$memID,1);}}}}
Header("Location: /imagenes/$ser");exit(); ?>