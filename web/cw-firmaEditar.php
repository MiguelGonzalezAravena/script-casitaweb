<?php require("cw-conexion-seg-0011.php");
global $db_prefix, $context, $user_info, $func, $user_settings;
if($user_info['is_guet']){fatal_error('Vos no podes estar aca.-');}

$signa11=$func['htmlspecialchars'](stripslashes($_POST['firma']), ENT_QUOTES);
$signa=str_replace(array('"', '<', '>', '  '), array('&quot;', '&lt;', '&gt;', ' &nbsp;'), $signa11);
$signa= preg_replace("~\[hide\](.+?)\[\/hide\]~i", "&nbsp;", $signa11);
$signa= preg_replace(array('~\n?\[hide.*?\].+?\[/hide\]\n?~is', '~^\n~', '~\[/hide\]~'), "&nbsp;", $signa11);
$signa= preg_replace('~<br(?: /)?' . '>~i', "\n", $signa11);
$signa=trim($signa);

$admin=(int)$_POST['admin'];
$id_user=(int)$_POST['id_user'];
if(strlen($_POST['firma'])>400){fatal_error('La firma no debe tener m&aacute;s de 400 car&aacute;cteres.-');}

if(!$user_info['is_admin']){
if($id_user=='1'){fatal_error('No es necesario estar aca.-',false);}}

if($admin && ($user_info['is_admin'] || $user_info['is_mods'])){
if(empty($id_user)){fatal_error('Debes seleccionar un usuario.-',false);}
db_query("UPDATE {$db_prefix}members SET signature='$signa' WHERE ID_MEMBER='$id_user' LIMIT 1", __FILE__, __LINE__);
Header("Location: /moderacion/edit-user/firma/$id_user");exit();die();}

elseif(!$admin){
db_query("UPDATE {$db_prefix}members SET signature='$signa' WHERE ID_MEMBER='{$user_settings['ID_MEMBER']}' LIMIT 1", __FILE__, __LINE__); 
Header("Location: /editar-perfil/firma/");exit();die();} ?>