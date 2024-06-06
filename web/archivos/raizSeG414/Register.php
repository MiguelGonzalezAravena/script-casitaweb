<?php
//Pagina de Rodrigo Zaupa (rigo@casitaweb.net)
if (!defined('CasitaWeb!-PorRigo'))die(base64_decode("d3d3LmNhc2l0YXdlYi5uZXQgLSByaWdv"));
function Register(){global $context,$user_info,$modSettings;
if(!empty($modSettings['registration_method']) && $modSettings['registration_method'] == 3){fatal_error('El registro de usuarios se encuentra desactivado por el momento.-');}
if(empty($user_info['is_guest'])){ fatal_error('Ya estas logueado');}    
	loadLanguage('Login');
	loadTemplate('Register');
	$context['sub_template']='before';
	$context['page_title'] = 'Registrarse';}
    
function Activate(){global $db_prefix, $context, $txt, $modSettings, $sourcedir;
if (empty($_REQUEST['u']) && empty($_POST['user'])){
		if (empty($modSettings['registration_method']) || $modSettings['registration_method'] == 3)
		fatal_error('...1');

		$context['member_id'] = 0;
		$context['can_activate'] = empty($modSettings['registration_method']) || $modSettings['registration_method'] == 1;
		$context['default_username'] = isset($_GET['user']) ? $_GET['user'] : '';
  fatal_error('...2');
		return;
	}

	$request = db_query("
		SELECT ID_MEMBER, validation_code, memberName, realName, emailAddress, is_activated, passwd
		FROM {$db_prefix}members" . (empty($_REQUEST['u']) ? "
		WHERE memberName = '$_POST[user]' OR emailAddress = '$_POST[user]'" : "
		WHERE ID_MEMBER = " . (int) $_REQUEST['u']) . "
		LIMIT 1", __FILE__, __LINE__);

	// Does this user exist at all?
	if (mysqli_num_rows($request) == 0)
	{
		$context['sub_template'] = 'retry_activate';
		$context['page_title'] = isset($txt['invalid_userid']) ? $txt['invalid_userid'] : '';
		$context['member_id'] = 0;

		return;
	}

	$row = mysqli_fetch_assoc($request);
	mysqli_free_result($request);

	if (isset($_POST['new_email'], $_REQUEST['passwd']) && sha1(strtolower($row['memberName']) . $_REQUEST['passwd']) == $row['passwd'])
	{
		if (empty($modSettings['registration_method']) || $modSettings['registration_method'] == 3)
			fatal_lang_error(1);

		if (preg_match('~^[0-9A-Za-z=_+\-/][0-9A-Za-z=_\'+\-/\.]*@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$~', stripslashes($_POST['new_email'])) == 0)
			fatal_error(sprintf($txt[500], htmlspecialchars($_POST['new_email'])), false);
!
		$request = db_query("
			SELECT ID_MEMBER
			FROM {$db_prefix}members
			WHERE emailAddress = '$_POST[new_email]'
			LIMIT 1", __FILE__, __LINE__);
		if (mysqli_num_rows($request) != 0)
			fatal_error(sprintf($txt[730], htmlspecialchars($_POST['new_email'])), false);
		mysqli_free_result($request);

		updateMemberData($row['ID_MEMBER'], array('emailAddress' => "'$_POST[new_email]'"));
		$row['emailAddress'] = stripslashes($_POST['new_email']);

		$email_change = true;
	}
 elseif (!empty($_REQUEST['sa']) && $_REQUEST['sa'] == 'resend' && ($row['is_activated'] == 0 || $row['is_activated'] == 2) && (!isset($_REQUEST['code']) || $_REQUEST['code'] == '')){
		require($sourcedir . '/Subs-Post.php');
		sendmail($row['emailAddress'],'Confirmar e-mail',
		"Para volver a ingresar con su cuenta en casitaweb.net, la debe activar.
Para eso debe ir al siguiente enlace, una vez dentro de el su cuenta estara activa:\n\n" .
			"<a href='http://casitaweb.net/activar-{$row['ID_MEMBER']}codigo-{$row['validation_code']}'>http://casitaweb.net/activar-{$row['ID_MEMBER']}codigo-{$row['validation_code']}</a> \n\n" .
			"Si tiene problemas con el enlace, no dude en contactar con CasitaWeb! (<a href='http://casitaweb.net/contactanos/'>http://casitaweb.net/contactanos/</a>) siempre recordando su codigo de activacion: {$row['validation_code']}");
			
		$context['page_title'] = $txt['invalid_activation_resend'];
		fatal_error(!empty($email_change) ? '1' : 'Se envio nuevamente el e-mail de confirmaci&oacute;n' , false);	}

	if (empty($_REQUEST['code']) || $row['validation_code'] != $_REQUEST['code'])
	{
		if (!empty($row['is_activated']))
			fatal_lang_error('already_activated', false);
		elseif ($row['validation_code'] == '')
		{
			loadLanguage('Profile');
			fatal_error($txt['registration_not_approved'] . ' <a href="/recuperar-pass/activar-' . $row['memberName'] . '">' . $txt[662] . '</a>.', false);
		}

		$context['member_id'] = $row['ID_MEMBER'];
          fatal_error('...2');
		return;
	}

if (isset($modSettings['integrate_activate']) && function_exists($modSettings['integrate_activate']))
		call_user_func($modSettings['integrate_activate'], $row['memberName']);

updateMemberData($row['ID_MEMBER'], array('is_activated' => 1, 'validation_code' => '\'\''));
updateStats('member', false);

sendmail($row['emailAddress'],'Cuenta re-activada',
		"Le contamos que su cuenta en casitaweb.net fue reactivada.\n\n" .
			"Nick: {$row['emailAddress']} \n Password: ****** <span style='fontsize:8px;color:grey;'>(Oculta por seguridad)</span>\n\n" .
			"Si tiene problemas con su cuenta no dude en contactarnos: <a href='http://casitaweb.net/contactanos/'>http://casitaweb.net/contactanos/</a>");
fatal_error('Cuenta Reactivada correctamente.');}
?>