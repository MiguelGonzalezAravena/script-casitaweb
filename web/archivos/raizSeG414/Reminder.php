<?php
//Pagina de Rodrigo Zaupa (rigo@casitaweb.net)
if (!defined('CasitaWeb!-PorRigo'))die(base64_decode("d3d3LmNhc2l0YXdlYi5uZXQgLSByaWdv"));
function RemindMe(){global $txt, $context,$scripturl;
loadLanguage('Profile');
loadTemplate('Reminder');
$context['page_title'] = $txt[669];
$subActions = array(
'mail' => 'RemindMail',
'setpassword' =>'setPassword',
'setpassword2' =>'setPassword2');
if (isset($_REQUEST['sa']) && isset($subActions[$_REQUEST['sa']]))$subActions[$_REQUEST['sa']]();}

function RemindMail(){global $db_prefix, $context, $txt, $scripturl, $sourcedir, $user_info, $webmaster_email;global $context, $mbname, $webmaster_email, $txt, $sourcedir, $modSettings, $scripturl;

$userlimpio=seguridad($_POST['user']);

if (!isset($userlimpio) || empty($userlimpio))
fatal_error('Error con el Nick');
$request = db_query("SELECT ID_MEMBER, realName, memberName, emailAddress, is_activated, validation_code FROM {$db_prefix}members WHERE realName = '$userlimpio' LIMIT 1", __FILE__, __LINE__);
if (mysql_num_rows($request) == 0){
fatal_error('Error con el Nick');}

$row = mysql_fetch_assoc($request);
mysql_free_result($request);

captcha(2);
    
	$row['emailAddress'] = trim($row['emailAddress']);
	if (empty($row['emailAddress']))
	fatal_error('Error con el Nick');
    
	$password = substr(preg_replace('/\W/', '', md5(md5(md5(md5(rand()))))), 0, 10);
	updateMemberData($row['ID_MEMBER'], array('validation_code' => "'" . substr(md5(md5(md5(md5($password)))), 0, 10) . "'"));
    require_once($sourcedir . '/Subs-Post.php');
       sendmail($row['emailAddress'], 'Recuperar mi Password',
		sprintf("Se ha enviado este mensaje porque se ha aplicado la función \"Recuperar mi Password\" en tu cuenta. Para establecer un nuevo Password haz clic en el siguiente enlace:\n") .
		sprintf("<a href='http://casitaweb.net/recuperar-pass/user-$row[ID_MEMBER]/id-$password'>http://casitaweb.net/recuperar-pass/user-$row[ID_MEMBER]/id-$password</a>"));
        
	$context += array(
		'page_title' => &$txt[194],
		'sub_template' => 'sent',
		'description' => &$txt['reminder_sent']);}
        
function setPassword()
{global $txt, $context;
if (!isset($_REQUEST['code']))fatal_lang_error(1);
$context += array('page_title' => 'E-mail enviado',
'sub_template' => 'set_password',
'code' => $_REQUEST['code'],
'memID' => (int) $_REQUEST['u']	);}




function setPassword2()
{global $db_prefix, $context, $txt, $modSettings, $sourcedir;
if(!empty($context['user']['name'])){fatal_error('Ya estas conectado como: <b>'.$context['user']['name'].'</b>');}
if (empty($_POST['u']) || !isset($_POST['passwrd1']) || !isset($_POST['passwrd2']))fatal_lang_error(1, false);
$_POST['u'] = (int) $_POST['u'];
if ($_POST['passwrd1'] !=  $_POST['passwrd2'])fatal_lang_error(213, false);
if (empty($_POST['passwrd1']))fatal_lang_error(91, false);
loadLanguage('Login');

$request = db_query("SELECT validation_code, memberName, emailAddress FROM {$db_prefix}members WHERE ID_MEMBER ='$_POST[u]' AND is_activated = 1 AND validation_code != '' LIMIT 1", __FILE__, __LINE__);
if (mysql_num_rows($request) == 0) fatal_lang_error('invalid_userid', false);
list ($realCode, $username, $email) = mysql_fetch_row($request);
mysql_free_result($request);

require($sourcedir . '/Subs-Auth.php');
$passwordError = validatePassword($_POST['passwrd1'], $username, array($email));
if ($passwordError != null){fatal_lang_error('profile_error_password_' . $passwordError, false);}

if (empty($_POST['code']) || substr($realCode, 0, 10) != substr(md5(md5(md5(md5($_POST['code'])))), 0, 10))
fatal_error('El codigo es invalido.', false);

updateMemberData($_POST['u'], array('validation_code' => '\'\'', 'passwd' => '\'' . sha1(strtolower($username) . $_POST['passwrd1']) . '\''));
if (isset($modSettings['integrate_reset_pass']) && function_exists($modSettings['integrate_reset_pass']))
call_user_func($modSettings['integrate_reset_pass'], $username, $username, $_POST['passwrd1']);
fatal_error('Su password cambiado correctamente.');}
function secretAnswerInput(){}function secretAnswer2(){}?>