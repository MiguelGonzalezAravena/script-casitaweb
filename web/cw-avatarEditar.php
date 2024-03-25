<?php require("cw-conexion-seg-0011.php");
global $tranfer1, $context, $settings, $options,$no_avatar, $txt, $scripturl, $modSettings;
global $db_prefix, $user_info, $scripturl, $user_settings,$modSettings;
if($user_info['is_guet']){fatal_error('Vos no podes estar aca.-');}

if(!$user_info['is_guet']){

$avatars=seguridad($_POST['avatar']);
$avatars=nohtml($avatars);
$avatars2=valida_url($avatars);
if(!$avatars2){fatal_error('Imagen no reconocida.-');}

$admin=(int)$_POST['admin'];
$sina=(int)$_POST['sinavatar'];
$id_user=(int)$_POST['id_user'];

if(!$user_info['is_admin']){if($id_user=='1'){fatal_error('No es necesario estar aca.-',false);}}

if(empty($sina)){$sinavatar='0';}else{$sinavatar='1';}

if(strlen($avatars)>110){fatal_error('El enlace del avatar no puede ser mayor de <b>110 letras</b><br/>Subi la im&aacute;gen a <a href="'.$modSettings['host_imagen'].'">'.$modSettings['host_imagen'].'</a> que los enlaces son cortos.-');}
if(empty($avatars))fatal_error('Debes agregar el avatar.-');

if($admin){
if($user_info['is_admin'] || $user_info['is_mods']){
if(!$id_user){fatal_error('Debes seleccionar un usuario.-',false);}
if($sinavatar){
	db_query("UPDATE {$db_prefix}members SET avatar='' WHERE ID_MEMBER='$id_user' LIMIT 1", __FILE__, __LINE__);}
else{
    db_query("UPDATE {$db_prefix}members SET avatar='$avatars' WHERE ID_MEMBER='$id_user' LIMIT 1", __FILE__, __LINE__);}
    
Header("Location: /admin/edit-user/avatar/$id_user");exit();die();}
else{fatal_error('Vos no podes estar aca.');}}

elseif(!$admin){
if($sinavatar){
	db_query("UPDATE {$db_prefix}members SET avatar='' WHERE ID_MEMBER='{$user_settings['ID_MEMBER']}' LIMIT 1", __FILE__, __LINE__);}
	else{
db_query("UPDATE {$db_prefix}members SET avatar='$avatars' WHERE ID_MEMBER='{$user_settings['ID_MEMBER']}' LIMIT 1", __FILE__, __LINE__);}

Header("Location: /editar-perfil/avatar/");exit();die();}} ?>