<?php require("cw-conexion-seg-0011.php");
	global $txt, $modSettings;
	global $cookiename, $context;
	global $sourcedir, $scripturl, $db_prefix;
	global $ID_MEMBER,$func, $user_info;
	global $context, $newpassemail, $user_settings, $user_profile, $validationCode;
    
loadTheme(0);

if($user_info['is_guest']){fatal_error('No puedes estar aca.-',false);}

loadLanguage('Profile');

require($sourcedir.'/Profile.php');

$sa_allowed = array(
		'cuenta' => array(array('manage_membergroups', 'profile_identity_any', 'profile_identity_own'), array('manage_membergroups', 'profile_identity_any'), 'post', true),
		'perfil' => array(array('profile_extra_any', 'profile_extra_own'), array('profile_extra_any'), 'post'),
		'activar' => array(array(), array('moderate_forum'), 'get'),
	);
    
if (empty($_REQUEST['sa']) || !isset($sa_allowed[$_REQUEST['sa']]))fatal_lang_error(453, false);

	$profile_vars = array();
	$post_errors = array();
	$newpassemail = false;
	$_POST=htmltrim__recursive($_POST);
	$_POST=stripslashes__recursive($_POST);
	$_POST=htmlspecialchars__recursive($_POST);
	$_POST=addslashes__recursive($_POST);
	$memberResult=loadMemberData((int) $_REQUEST['userID'], false, 'profile');

	if(!is_array($memberResult))fatal_lang_error(453, false);

	list ($memID) = $memberResult;
if(!($user_info['is_admin'] || $user_info['is_mods'])){
    if($memID=='1'){fatal_error('No es necesario estar aca.-',false);}}
	
    if ($ID_MEMBER == $memID)
		$context['user']['is_owner'] = true;
	else
	{
		$context['user']['is_owner'] = false;
		validateSession();}
		
isAllowedTo($sa_allowed[$_REQUEST['sa']][$context['user']['is_owner'] ? 0 : 1]);

if($context['user']['is_owner'] && !empty($sa_allowed[$_REQUEST['sa']][3])){
		if (trim($_POST['oldpasswrd']) == '')
		fatal_error('Debes escribir la contrase&ntilde;a.-',false);
		$_POST['oldpasswrd'] = addslashes(un_htmlspecialchars(stripslashes($_POST['oldpasswrd'])));
    	$good_password = false;
		if (isset($modSettings['integrate_verify_password']) && function_exists($modSettings['integrate_verify_password']))
			if (call_user_func($modSettings['integrate_verify_password'], $user_profile[$memID]['memberName'], $_POST['oldpasswrd'], false) === true)
				$good_password = true;
if (!$good_password && $user_info['passwd'] != sha1(strtolower($user_profile[$memID]['memberName']) . $_POST['oldpasswrd'])){fatal_error('Las contrase&ntilde;as no coinciden.-',false);}}
	unset($sa_allowed);
    
	if ($user_info['is_admin'] && isset($_POST['memberName']))
	{
		require($sourcedir . '/Subs-Auth.php');
		resetPassword($memID, $_POST['memberName']);
	}
	if ($context['user']['is_owner'])
		$profile_vars['memberIP'] = "'$user_info[ip]'";

		saveProfileChanges($profile_vars, $post_errors, $memID);
        
	if (!empty($post_errors))
	{
		loadLanguage('Errors');
		$context['post_errors'] = $post_errors;
		$_REQUEST['sa'] = $_POST['sa'];
		$_REQUEST['u'] = $memID;
		return ModifyProfile($post_errors);
	}
	if (!empty($profile_vars))
	{
		if (isset($profile_vars['passwd']) && isset($modSettings['integrate_reset_pass']) && function_exists($modSettings['integrate_reset_pass']))
			call_user_func($modSettings['integrate_reset_pass'], $user_profile[$memID]['memberName'], $user_profile[$memID]['memberName'], $_POST['passwrd1']);updateMemberData($memID, $profile_vars);}

if ($modSettings['latestMember'] == $memID)	updateStats('member');
elseif (isset($profile_vars['realName']))
updateSettings(array('memberlist_updated' => time()));

if($_GET['sa'] !== 'activar' ){

///datos y errores
if($_POST['usertitle']==='-1' || empty($_POST['usertitle'])){
fatal_error('Debes seleccionar el pa&iacute;s donde vives.-', false,'',4);}
if($_POST['usertitle']==='ar' || $_POST['usertitle']==='bo' || $_POST['usertitle']==='br' ||  $_POST['usertitle']==='cl' || $_POST['usertitle']==='co' || $_POST['usertitle']==='cr' || $_POST['usertitle']==='cu' || $_POST['usertitle']==='ec' || $_POST['usertitle']==='es' || $_POST['usertitle']==='gt' || $_POST['usertitle']==='it' || $_POST['usertitle']==='mx' || $_POST['usertitle']==='py' || $_POST['usertitle']==='pe' || $_POST['usertitle']==='pt' ||  $_POST['usertitle']==='pr' || $_POST['usertitle']==='uy' || $_POST['usertitle']==='ve' || $_POST['usertitle']==='ot'){}else{fatal_error('El pa&iacute;s seleccinado no est&aacute; en la lista.-', false,'',4);}
if(empty($_POST['location'])){fatal_error('Debes poner la ciudad donde vives.-', false,'',4);}
if(strlen($_POST['location'])>=60){
fatal_error('Nombre de ciudad muy largo, Abrevia la ciudad.-', false,'',4);}
$myuser=$user_settings['ID_MEMBER'];
$realname=seguridad(nohtml($_POST['memberName']));
$puntos=(int) $_POST['puntos'];
if($puntos < 0){$eskiji='0';}else{$eskiji=(int)$puntos;}
$nombre=seguridad(nohtml($_POST['nombre']));
$recibir=(int)$_POST['recibir'];
$gender=(int)$_POST['gender'];
if($gender=='2'){$sex='2';}else{$sex='1';}
$personalText=censorText(str_replace(array('&quot;', '&lt;', '&gt;', ' &nbsp;'), array('"', '<', '>', '  '), $_POST['personalText']));
$websiteTitle=censorText(str_replace(" ","",$_POST['websiteTitle']));

if(strlen($personalText)>21){fatal_error('El texto personar no debe tener m&aacute;s de 21 car&aacute;cteres.-');}


if($recibir=='1' || empty($recibir)){$data2=$recibir;}else{$data2='0';}

$quienver=(int)$_POST['quienve'];
$dia=(int)$_POST['bday2'];
$mes=(int)$_POST['bday1'];
$ano=(int)$_POST['bday3'];
if($dia<'1' || $dia>'31'){fatal_error('Hubo un problema con la fecha de nacimiento.-');}
if($mes<'1' || $mes>'12'){fatal_error('Hubo un problema con la fecha de nacimiento.-');}
if($ano<'1900' || $ano>'2005'){fatal_error('Hubo un problema con la fecha de nacimiento.-');}
$nac=sprintf('%04d-%02d-%02d', empty($_POST['bday3']) ? 0 : (int) $_POST['bday3'], (int) $_POST['bday1'], (int) $_POST['bday2']);

if($quienver=='1' || $quienver=='2' || $quienver=='3' || empty($quienver)){
if(empty($quienver)){$quienver='0';}
$agrearorefrescar=mysqli_num_rows(db_query("SELECT id_user FROM ({$db_prefix}infop) WHERE id_user='$memID'", __FILE__, __LINE__));

if($agrearorefrescar=='1'){
db_query("UPDATE {$db_prefix}infop SET a_quien='$quienver' WHERE id_user='$memID'", __FILE__, __LINE__);}
elseif(empty($agrearorefrescar)){
db_query("INSERT INTO {$db_prefix}infop (id_user,a_quien) VALUES ('$memID','$quienver')", __FILE__, __LINE__);}}

if(!empty($realname) && $user_settings['ID_MEMBER']=='1'){
db_query("
UPDATE {$db_prefix}members
SET realName='$realname'
WHERE ID_MEMBER='$memID'
LIMIT 1", __FILE__, __LINE__);}

if(!empty($eskiji) && $user_settings['ID_MEMBER']=='1'){
db_query("
UPDATE {$db_prefix}members
SET posts='$eskiji'
WHERE ID_MEMBER='$memID'
LIMIT 1", __FILE__, __LINE__);}

db_query("
UPDATE {$db_prefix}members
SET nombre='$nombre',usertitle='{$_POST['usertitle']}',location='{$_POST['location']}',birthdate='$nac',gender='$sex',personalText='$personalText',websiteTitle=SUBSTRING('$websiteTitle', 1, 70),recibirmail='$data2'
WHERE ID_MEMBER='$memID'
LIMIT 1", __FILE__, __LINE__);

if($_POST['MSN']){
if(preg_match('~^[0-9A-Za-z=_+\-/][0-9A-Za-z=_\'+\-/\.]*@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$~', stripslashes($_POST['MSN'])) == 0){fatal_error('Mensajero invalido.-', false);}else{
if (isset($_POST['MSN']) && ($_POST['MSN'] == '' || preg_match('~^[0-9A-Za-z=_+\-/][0-9A-Za-z=_\'+\-/\.]*@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$~', $_POST['MSN']) != 0)){
db_query("UPDATE {$db_prefix}members SET MSN='{$_POST['MSN']}' WHERE ID_MEMBER='$memID' LIMIT 1", __FILE__, __LINE__);}
}}

pts_sumar_grup($memID);}

if($newpassemail){
require($sourcedir.'/Subs-Post.php');

sendmail($_POST['emailAddress'], 'Confirmar e-mail',
			"Para volver a ingresar con su cuenta en casitaweb.net, la debe activar.<br/>Para eso debe ir al siguiente enlace, una vez dentro de el su cuenta estara activa:<br/><br/><a href='http://casitaweb.net/activar-{$memID}codigo-{$validationCode}'>http://casitaweb.net/activar-{$memID}codigo-{$validationCode}</a><br/><br/>Si tiene problemas con el enlace, no dude en contactar con CasitaWeb! (<a href='http://casitaweb.net/contactanos/'>http://casitaweb.net/contactanos/</a>) siempre recordando su codigo de activacion: {$validationCode}");            
		db_query("DELETE FROM {$db_prefix}log_online WHERE ID_MEMBER='$memID'", __FILE__, __LINE__);
		$_SESSION['log_time'] = 0;
		$_SESSION['login_'.$cookiename] = serialize(array(0, '', 0));
		if(isset($_COOKIE[$cookiename])){$_COOKIE[$cookiename] = '';}
        
		loadUserSettings();
		$context['user']['is_logged'] = false;
		$context['user']['is_guest'] = true;
		loadTemplate('Register');
		$context += array(
			'page_title' => &$txt[79],
			'sub_template' => 'after',
			'description' => &$txt['activate_changed_email']);
Header("Location: /");exit;die();}
	
if(!empty($_POST['passwrd1'])){$sffffe='1';}else{$sffffe='0';}

if($_POST['llegaravatar']){Header("Location: /moderacion/edit-user/perfil/{$_POST['llegaravatar']}");exit;die();}
elseif($sffffe==1){Header("Location: /");exit;die();}else{Header("Location: /editar-perfil/");exit;die();}
?>