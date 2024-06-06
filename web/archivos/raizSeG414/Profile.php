<?php
//Pagina de Rodrigo Zaupa (rigo@casitaweb.net)
if (!defined('CasitaWeb!-PorRigo'))die(base64_decode("d3d3LmNhc2l0YXdlYi5uZXQgLSByaWdv"));
function ModifyProfile($post_errors = array()){global $txt, $scripturl, $user_info, $context, $ID_MEMBER, $sourcedir, $user_profile, $modSettings;

	if (empty($post_errors))loadLanguage('Profile');
	loadTemplate('Profile');

	$sa_allowed = array(
		'summary' => array(array('profile_view_any', 'profile_view_own'), array('profile_view_any')),
		'cuenta' => array(array('profile_extra_any', 'profile_extra_own'), array('profile_extra_any')),
		'perfil' => array(array('profile_extra_any', 'profile_extra_own'), array('profile_extra_any')),
	);

	$context['template_layers'][] = 'profile';
	if(isset($_REQUEST['user']))
	$memberResult=loadMemberData(str_replace('/','',$_GET['user']), true, 'profile');
	elseif (!empty($_REQUEST['u']))
	$memberResult=loadMemberData((int) $_REQUEST['u'], false, 'profile');
	else
	$memberResult=loadMemberData($ID_MEMBER, false, 'profile');

	if(!is_array($memberResult))fatal_lang_error(453, false);
	
	list ($memID)=$memberResult;
	$context['user']['is_owner'] = $memID == $ID_MEMBER;
	if (!isset($_REQUEST['sa']) || !isset($sa_allowed[$_REQUEST['sa']]))
	{
		if ((allowedTo('profile_view_own') && $context['user']['is_owner']) || allowedTo('profile_view_any'))
			$_REQUEST['sa'] = 'summary';
		elseif ((allowedTo('profile_identity_own') && $context['user']['is_owner']) || allowedTo('profile_identity_any') || allowedTo('manage_membergroups'))
			$_REQUEST['sa'] = 'cuenta';
		elseif ((allowedTo('profile_extra_own') && $context['user']['is_owner']) || allowedTo('profile_extra_any'))
			$_REQUEST['sa'] = 'perfil';
		else
			isAllowedTo('profile_view_' . ($context['user']['is_owner'] ? 'own' : 'any'));
	}

	isAllowedTo($sa_allowed[$_REQUEST['sa']][$context['user']['is_owner'] ? 0 : 1]);

	if (!empty($sa_allowed[$_REQUEST['sa']][2]))validateSession();
	unset($sa_allowed);

	$context['profile_areas'] = array();

	if (!$user_info['is_guest'] && (($context['user']['is_owner'] && allowedTo('profile_view_own')) || allowedTo(array('profile_view_any', 'moderate_forum', 'manage_permissions'))))
	{
		$context['profile_areas']['info'] = array(
			'title' => $txt['profileInfo'],
			'areas' => array()
		);


	}

	if (($context['user']['is_owner'] && (allowedTo(array('profile_identity_own', 'profile_extra_own')))) || allowedTo(array('profile_identity_any', 'profile_extra_any', 'manage_membergroups')))
	{
		$context['profile_areas']['edit_profile'] = array(
			'title' => $txt['profileEdit'],
			'areas' => array()
		);

		if (($context['user']['is_owner'] && allowedTo('profile_identity_own')) || allowedTo(array('profile_identity_any', 'manage_membergroups')))
			$context['profile_areas']['edit_profile']['areas']['cuenta'] = '';

		if (($context['user']['is_owner'] && allowedTo('profile_extra_own')) || allowedTo('profile_extra_any'))
		{
			$context['profile_areas']['edit_profile']['areas']['perfil'] = '';
		}
	}

	if (($context['user']['is_owner'] && allowedTo('profile_remove_own')) || allowedTo('profile_remove_any') || (!$context['user']['is_owner'] && allowedTo('pm_send')))
	{
		$context['profile_areas']['profile_action'] = array(
			'title' => $txt['profileAction'],
			'areas' => array()
		);

		if (!$context['user']['is_owner'] && allowedTo('pm_send'))
			$context['profile_areas']['profile_action']['areas']['send_pm'] = '';
		if (allowedTo('manage_bans') && $user_profile[$memID]['ID_GROUP'] != 1)
			$context['profile_areas']['profile_action']['areas']['banUser'] = '';

	}

	if (!isset($context['profile_areas']['edit_profile']) && !isset($context['profile_areas']['profile_action']['areas']['banUser']))
		$context['profile_areas'] = array();

	$context['menu_item_selected'] = $_REQUEST['sa'];
	$context['sub_template'] = $_REQUEST['sa'];

	$context['require_password'] = in_array($context['menu_item_selected'], array('account'));

	$context['member'] = array(
		'id' => $memID,
		'name' => !isset($user_profile[$memID]['realName']) || $user_profile[$memID]['realName'] == '' ? '' : $user_profile[$memID]['realName'],
		'email' => $user_profile[$memID]['emailAddress'],
		'posts' => empty($user_profile[$memID]['posts']) ? 0: (int) $user_profile[$memID]['posts'],
		'registered' => empty($user_profile[$memID]['dateRegistered']) ? $txt[470] : strftime('%Y-%m-%d', $user_profile[$memID]['dateRegistered'] + ($user_info['time_offset'] + $modSettings['time_offset']) * 3600),
		'group' => $user_profile[$memID]['ID_GROUP'],
		'gender' => array('name' => empty($user_profile[$memID]['gender']) ? '' : ($user_profile[$memID]['gender'] == 2 ? 'f' : 'm')),
		'avatar' => array(
			'name' => &$user_profile[$memID]['avatar'],	
            'image' => $user_profile[$memID]['avatar'] == '' ? '' : (stristr($user_profile[$memID]['avatar'], 'http://') ? '<img src="' . $user_profile[$memID]['avatar'] . '" alt="" class="avatar png" border="0" />' : ''),
		),
		'msn' => array('name' => empty($user_profile[$memID]['MSN']) ? '' : $user_profile[$memID]['MSN']),
		'validation_code' => &$user_profile[$memID]['validation_code'],	
		'is_activated' => &$user_profile[$memID]['is_activated'],	
		'website' => array(
			'title' => !isset($user_profile[$memID]['websiteTitle']) ? '' : $user_profile[$memID]['websiteTitle'],
			'url' => !isset($user_profile[$memID]['websiteUrl']) ? '' : $user_profile[$memID]['websiteUrl'],
		),

	);

	$_REQUEST['sa']($memID);

	if (!empty($post_errors))
	{
		foreach ($post_errors as $error_type)
		$context['modify_error'][$error_type] = true;
		rememberPostData();
	}
	if (!isset($context['page_title']))
		$context['page_title'] = $txt[$_REQUEST['sa']];
}

function ModifyProfile2(){}

function saveProfileChanges(&$profile_vars, &$post_errors, $memID){
	global $db_prefix, $user_info, $txt, $modSettings, $user_profile;
	global $newpassemail, $validationCode, $context, $settings, $sourcedir;
	global $func;
	$old_profile = &$user_profile[$memID];

	if ($context['user']['is_owner'])
	{
		$changeIdentity = allowedTo(array('profile_identity_any', 'profile_identity_own'));
		$changeOther = allowedTo(array('profile_extra_any', 'profile_extra_own'));
	}
	else
	{
		$changeIdentity = allowedTo('profile_identity_any');
		$changeOther = allowedTo('profile_extra_any');
	}

	$profile_bools = array(
		'notifyAnnouncements', 'notifyOnce', 'notifySendBody',
	);
	$profile_ints = array(
		'pm_email_notify',
		'notifyTypes',
		'ICQ',
		'gender',
	);
	$profile_floats = array(
		'timeOffset',
	);
	$profile_strings = array(
		'websiteUrl', 'websiteTitle',
		'location', 'birthdate',
		'timeFormat',
		'signature', 'personalText', 'avatar',
	);

	$fix_spaces = array('MSN');
	foreach ($fix_spaces as $var)
	{
		// !!! Why?
		if (isset($_POST[$var]))
			$_POST[$var] = strtr($_POST[$var], ' ', '+');
	}

	if (isset($_POST['MSN']) && ($_POST['MSN'] == '' || preg_match('~^[0-9A-Za-z=_+\-/][0-9A-Za-z=_\'+\-/\.]*@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$~', $_POST['MSN']) != 0))
		$profile_strings[] = 'MSN';

	if (!empty($modSettings['titlesEnable']) && (allowedTo('profile_title_any') || (allowedTo('profile_title_own') && $context['user']['is_owner'])))
		$profile_strings[] = 'usertitle';

	if (isset($_POST['timeOffset']))
	{
		$_POST['timeOffset'] = strtr($_POST['timeOffset'], ',', '.');

		if ($_POST['timeOffset'] < -23.5 || $_POST['timeOffset'] > 23.5)
			$post_errors[] = 'bad_offset';
	}

	if (isset($_POST['websiteUrl']))
	{
		if (strlen(trim($_POST['websiteUrl'])) > 0 && strpos($_POST['websiteUrl'], '://') === false)
			$_POST['websiteUrl'] = 'http://' . $_POST['websiteUrl'];
		if (strlen($_POST['websiteUrl']) < 8)
			$_POST['websiteUrl'] = '';
	}
	if (isset($_POST['birthdate']))
	{
		if (preg_match('/(\d{4})[\-\., ](\d{2})[\-\., ](\d{2})/', $_POST['birthdate'], $dates) === 1)
			$_POST['birthdate'] = checkdate($dates[2], $dates[3], $dates[1] < 4 ? 4 : $dates[1]) ? sprintf('%04d-%02d-%02d', $dates[1] < 4 ? 4 : $dates[1], $dates[2], $dates[3]) : '0001-01-01';
		else
			unset($_POST['birthdate']);
	}
	elseif (isset($_POST['bday1'], $_POST['bday2'], $_POST['bday3']) && $_POST['bday1'] > 0 && $_POST['bday2'] > 0)
		$_POST['birthdate'] = checkdate($_POST['bday1'], $_POST['bday2'], $_POST['bday3'] < 4 ? 4 : $_POST['bday3']) ? sprintf('%04d-%02d-%02d', $_POST['bday3'] < 4 ? 4 : $_POST['bday3'], $_POST['bday1'], $_POST['bday2']) : '0001-01-01';
	elseif (isset($_POST['bday1']) || isset($_POST['bday2']) || isset($_POST['bday3']))
		$_POST['birthdate'] = '0001-01-01';



	if ($changeIdentity){
	if (isset($_POST['realName']) && (!empty($modSettings['allow_editDisplayName']) || allowedTo('moderate_forum')) && trim($_POST['realName']) != $old_profile['realName'])
		{
			$_POST['realName'] = trim(preg_replace('~[\s]~' . ($context['utf8'] ? 'u' : ''), ' ', $_POST['realName']));
			if (trim($_POST['realName']) == '')
				$post_errors[] = 'no_name';
			elseif ($func['strlen']($_POST['realName']) > 60)
				$post_errors[] = 'name_too_long';
			else
			{
				require_once($sourcedir . '/Subs-Members.php');
				if (isReservedName($_POST['realName'], $memID))
					$post_errors[] = 'name_taken';
			}

			if (isset($_POST['realName']))
				$profile_vars['realName'] = '\'' . $_POST['realName'] . '\'';
		}

				if (!empty($_POST['dateRegistered']) && allowedTo('moderate_forum'))
		{
						if (($_POST['dateRegistered'] = strtotime($_POST['dateRegistered'])) === -1)
				fatal_error($txt['smf233'] . ' ' . strftime('%d %b %Y ' . (strpos($user_info['time_format'], '%H') !== false ? '%I:%M:%S %p' : '%H:%M:%S'), forum_time(false)), false);
				
			elseif ($_POST['dateRegistered'] != $txt[470] && $_POST['dateRegistered'] != strtotime(strftime('%Y-%m-%d', $user_profile[$memID]['dateRegistered'] + ($user_info['time_offset'] + $modSettings['time_offset']) * 3600)))
				$profile_vars['dateRegistered'] = $_POST['dateRegistered'] - ($user_info['time_offset'] + $modSettings['time_offset']) * 3600;
		}

		if (isset($_POST['posts']) && allowedTo('moderate_forum'))
			$profile_vars['posts'] = $_POST['posts'] != '' ? (int) strtr($_POST['posts'], array(',' => '', '.' => '', ' ' => '')) : '\'\'';;
		if (isset($_POST['topics']) && allowedTo('moderate_forum'))
			$profile_vars['topics'] = $_POST['topics'] != '' ? (int) strtr($_POST['topics'], array(',' => '', '.' => '', ' ' => '')) : '\'\'';

		if (isset($_POST['emailAddress']) && strtolower($_POST['emailAddress']) != strtolower($old_profile['emailAddress']))
		{
			$_POST['emailAddress'] = strtr($_POST['emailAddress'], array('&#039;' => '\\\''));

			if (!empty($modSettings['send_validation_onChange']) && !allowedTo('moderate_forum'))
			{
				$validationCode = substr(preg_replace('/\W/', '', md5(rand())), 0, 10);
				$profile_vars['validation_code'] = '\'' . $validationCode . '\'';
				$profile_vars['is_activated'] = '2';
				$newpassemail = true;
			}

			// Check the name and email for validity.
			if (trim($_POST['emailAddress']) == '')
			fatal_error('Debes agregar el E-mail.-',false);
			if (preg_match('~^[0-9A-Za-z=_+\-/][0-9A-Za-z=_\'+\-/\.]*@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$~', stripslashes($_POST['emailAddress'])) == 0)
			fatal_error('Car&aacute;cteres invalidos en el E-mail.-');
			
			$request = db_query("
				SELECT ID_MEMBER
				FROM {$db_prefix}members
				WHERE ID_MEMBER != $memID AND emailAddress = '$_POST[emailAddress]'
				LIMIT 1", __FILE__, __LINE__);
			if (mysqli_num_rows($request) > 0)
			fatal_error('Ya existe el E-mail.-',false);
			mysqli_free_result($request);

			$profile_vars['emailAddress'] = '\'' . $_POST['emailAddress'] . '\'';
		}

		if (isset($_POST['hideEmail']) && (!empty($modSettings['allow_hideEmail']) || allowedTo('moderate_forum')))
			$profile_vars['hideEmail'] = empty($_POST['hideEmail']) ? '0' : '1';

		if (isset($_POST['passwrd1']) && $_POST['passwrd1'] != '')
		{
			if ($_POST['passwrd1'] != $_POST['passwrd2'])
			fatal_error('Las contrase&ntilde;as no coinciden.-',false);

			require($sourcedir . '/Subs-Auth.php');
			$passwordErrors = validatePassword($_POST['passwrd1'], $user_info['username'], array($user_info['name'], $user_info['email']));

			if ($passwordErrors != null)
			$post_errors[] = 'password_' . $passwordErrors;

			$profile_vars['passwd'] = '\'' . sha1(strtolower($old_profile['memberName']) . un_htmlspecialchars(stripslashes($_POST['passwrd1']))) . '\'';
		}


	}
if ($context['user']['is_admin'])
	{
		if (($_REQUEST['sa'] == 'activar' || !empty($_POST['is_activated'])) && isset($old_profile['is_activated']) && $old_profile['is_activated'] != 1)
		{
			if (isset($modSettings['integrate_activate']) && function_exists($modSettings['integrate_activate']))
				call_user_func($modSettings['integrate_activate'], $old_profile['memberName']);
			updateMemberData($memID, array('is_activated' => $old_profile['is_activated'] >= 10 ? '11' : '1', 'validation_code' => '\'\''));
			if (in_array($old_profile['is_activated'], array(3, 4, 13, 14)))
				updateSettings(array('unapprovedMembers' => ($modSettings['unapprovedMembers'] > 1 ? $modSettings['unapprovedMembers'] - 1 : 0)));
			updateStats('member', false);
Header("Location: /perfil/{$old_profile['memberName']}");exit;die();		}
}

if(allowedTo('manage_membergroups'))
	{
		if (isset($_POST['ID_GROUP']) && (allowedTo('admin_forum') || ((int) $_POST['ID_GROUP'] != 1 && $old_profile['ID_GROUP'] != 1)))
			$profile_vars['ID_GROUP'] = (int) $_POST['ID_GROUP'];

		if ($old_profile['ID_GROUP'] == 1)
		{
			$stillAdmin = !isset($profile_vars['ID_GROUP']) || $profile_vars['ID_GROUP'] == 1;
			if (!$stillAdmin)
			{
				$request = db_query("
					SELECT ID_MEMBER
					FROM {$db_prefix}members
					WHERE (ID_GROUP = 1)
						AND ID_MEMBER != $memID
					LIMIT 1", __FILE__, __LINE__);
				list ($another) = mysqli_fetch_row($request);
				mysqli_free_result($request);

				if (empty($another))
					fatal_lang_error('at_least_one_admin');
			}
		}
	}
	
}

function summary($memID){
global $context, $memberContext, $txt, $modSettings, $user_info, $ID_MEMBER,$user_profile, $sourcedir, $db_prefix, $scripturl;

if (!loadMemberContext($memID) || !isset($memberContext[$memID]))
		fatal_error($txt[453] . ' - ' . $memID, false);

$looped = false;
$context['postuser']=mysqli_num_rows(db_query("SELECT m.ID_MEMBER FROM ({$db_prefix}messages AS m) WHERE m.ID_MEMBER='$memID' AND m.eliminado=0", __FILE__, __LINE__));

if(!$user_info['is_guest']){
$context['yadio']=mysqli_num_rows(db_query("SELECT user,amigo FROM {$db_prefix}amistad WHERE (user='{$ID_MEMBER}' AND amigo='{$memID}' OR user='{$memID}' AND amigo='{$ID_MEMBER}') AND acepto=1 LIMIT 1", __FILE__, __LINE__));

$context['yadio2']=mysqli_num_rows(db_query("SELECT user,amigo FROM {$db_prefix}amistad WHERE (user='{$ID_MEMBER}' AND amigo='{$memID}' OR user='{$memID}' AND amigo='{$ID_MEMBER}') AND acepto=0 LIMIT 1", __FILE__, __LINE__));

$context['mpno']=mysqli_num_rows(db_query("SELECT id_user,quien FROM {$db_prefix}pm_admitir WHERE id_user='{$ID_MEMBER}' AND quien='{$memID}' LIMIT 1", __FILE__, __LINE__));}


$request=db_query("
			SELECT b.name,b.description,b.ID_BOARD,m.ID_TOPIC,m.subject
			FROM ({$db_prefix}messages AS m)
			INNER JOIN {$db_prefix}boards AS b ON m.ID_MEMBER='$memID' AND m.ID_BOARD=b.ID_BOARD AND m.eliminado=0
			ORDER BY m.ID_TOPIC DESC
			LIMIT 10", __FILE__, __LINE__);
$context['posts'] = array();
while ($row = mysqli_fetch_assoc($request))
{     censorText($row['subject']);
		$context['posts'][] = array(
			'board' => array(
				'name' => $row['name'],
                'description' => $row['description'],
				'id' => $row['ID_BOARD']
			),
			'topic' => $row['ID_TOPIC'],
			'subject' => $row['subject']);}
mysqli_free_result($request);
	

if($ID_MEMBER = $memID){
$context['count'] =  mysqli_num_rows(db_query("
		SELECT p.ID_MEMBER
		FROM {$db_prefix}gallery_pic as p
        INNER JOIN {$db_prefix}members AS m ON p.ID_MEMBER='$memID' AND p.ID_MEMBER = m.ID_MEMBER", __FILE__, __LINE__));
$dbresult = db_query("
		SELECT p.ID_PICTURE,p.filename,p.title
		FROM {$db_prefix}gallery_pic as p
		INNER JOIN {$db_prefix}members AS m ON p.ID_MEMBER='$memID' AND p.ID_MEMBER = m.ID_MEMBER
		ORDER BY p.ID_PICTURE DESC
		LIMIT 8", __FILE__, __LINE__);
while($row = mysqli_fetch_assoc($dbresult)){
		$context['img'][] = array(
			'id' => $row['ID_PICTURE'],
			'title' => censorText(nohtml2(nohtml($row['title']))),
            'filename' => nohtml2(nohtml($row['filename']))
            );}
		mysqli_free_result($dbresult);
}

	$context += array(
		'allow_hide_email' => !empty($modSettings['allow_hideEmail']),
		'page_title' => ' ' . $memberContext[$memID]['name'],
		'can_send_pm' => allowedTo('pm_send'),
		'can_have_buddy' => allowedTo('profile_identity_own') && !empty($modSettings['enable_buddylist']),
	);
	$context['member'] = &$memberContext[$memID];

	$days_registered = (int) ((time() - $user_profile[$memID]['dateRegistered']) / (3600 * 24));
	if (empty($user_profile[$memID]['dateRegistered']) || $days_registered < 1)
		$context['member']['posts_per_day'] = $txt[470];
	else
		$context['member']['posts_per_day'] = comma_format($context['member']['real_posts'] / $days_registered, 3);

	if (empty($user_profile[$memID]['dateRegistered']) || $days_registered < 1)
		$context['member']['topics_per_day'] = $txt[470];
	else
		$context['member']['topics_per_day'] = comma_format($context['member']['real_topics'] / $days_registered, 3);

	// Set the age...
	if (empty($context['member']['birth_date']))
	{
		$context['member'] +=  array(
			'age' => &$txt[470],
			'today_is_birthday' => false
		);
	}
	else
	{
		list ($birth_year, $birth_month, $birth_day) = sscanf($context['member']['birth_date'], '%d-%d-%d');
		$datearray = getdate(forum_time());
		$context['member'] += array(
			'age' => $birth_year <= 4 ? $txt[470] : $datearray['year'] - $birth_year - (($datearray['mon'] > $birth_month || ($datearray['mon'] == $birth_month && $datearray['mday'] >= $birth_day)) ? 0 : 1),
			'today_is_birthday' => $datearray['mon'] == $birth_month && $datearray['mday'] == $birth_day
		);
	}

	if (allowedTo('moderate_forum'))
	{
		if (preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/', $memberContext[$memID]['ip']) == 1 && empty($modSettings['disableHostnameLookup']))
			$context['member']['hostname'] = host_from_ip($memberContext[$memID]['ip']);
		else
			$context['member']['hostname'] = '';

		$context['can_see_ip'] = true;
	}
	else
		$context['can_see_ip'] = false;
        
}










function cuenta($memID){global $context, $settings, $user_profile, $txt, $db_prefix;
	global $scripturl, $membergroups, $modSettings, $language, $user_info;
	global $ID_MEMBER;
    global $func;
	global $context, $user_profile;
	global $user_info, $txt, $ID_MEMBER, $modSettings;
    $context['page_title'] = 'Editar mi avatar';
	$context['avatar_url']=$user_profile[$memID]['avatar'];}

function perfil($memID){
	global $context, $user_profile, $db_prefix;
	global $user_info, $txt, $ID_MEMBER, $modSettings;

    $context['page_title'] = 'Editar mi perfil';
	$context['avatar_url'] = $modSettings['avatar_url'];
	$context['allow_edit_title'] = allowedTo('profile_title_any') || (allowedTo('profile_title_own') && $context['user']['is_owner']);
	$context['allow_edit_username'] = isset($_GET['changeusername']) && allowedTo('admin_forum');
	$context['allow_edit_membergroups'] = allowedTo('manage_membergroups');
	$context['allow_edit_account'] = ($context['user']['is_owner'] && allowedTo('profile_identity_own')) || allowedTo('profile_identity_any');
	$context['allow_hide_email'] = !empty($modSettings['allow_hideEmail']) || allowedTo('moderate_forum');
	$context['allow_hide_online'] = !empty($modSettings['allow_hideOnline']) || allowedTo('moderate_forum');
	$context['allow_edit_name'] = !empty($modSettings['allow_editDisplayName']) || allowedTo('moderate_forum');
	$context['member'] += array(
		'is_admin' => !empty($user_profile[$memID]['ID_GROUP']) && $user_profile[$memID]['ID_GROUP'] == 1,
		'secret_question' => !isset($user_profile[$memID]['secretQuestion']) ? '' : $user_profile[$memID]['secretQuestion'],
	);
	$context['member'] += array(
		'birth_date' => empty($user_profile[$memID]['birthdate']) || $user_profile[$memID]['birthdate'] === '0001-01-01' ? '0000-00-00' : (substr($user_profile[$memID]['birthdate'], 0, 4) === '0004' ? '0000' . substr($user_profile[$memID]['birthdate'], 4) : $user_profile[$memID]['birthdate']),
		'location' => !isset($user_profile[$memID]['location']) ? '' : $user_profile[$memID]['location'],
		'title' => !isset($user_profile[$memID]['usertitle']) || $user_profile[$memID]['usertitle'] == '' ? '' : $user_profile[$memID]['usertitle'],
		'blurb' => !isset($user_profile[$memID]['personalText']) ? '' : str_replace(array('<', '>', '&amp;#039;'), array('&lt;', '&gt;', '&#039;'), $user_profile[$memID]['personalText']),
		'signature' => !isset($user_profile[$memID]['signature']) ? '' : str_replace(array('<br />', '<', '>', '"', '\''), array("\n", '&lt;', '&gt;', '&quot;', '&#039;'), $user_profile[$memID]['signature']),
	);
	list ($uyear, $umonth, $uday) = explode('-', $context['member']['birth_date']);
	$context['member']['birth_date'] = array(
		'year' => $uyear,
		'month' => $umonth,
		'day' => $uday
	);
    
	if ($context['allow_edit_account'] && !empty($modSettings['userLanguage']))
	{
		$selectedLanguage = empty($user_profile[$memID]['lngfile']) ? $language : $user_profile[$memID]['lngfile'];

		$language_directories = array(
			$settings['default_theme_dir'] . '/languages',
			$settings['actual_theme_dir'] . '/languages',
		);
		if (!empty($settings['base_theme_dir']))
			$language_directories[] = $settings['base_theme_dir'] . '/languages';
		$language_directories = array_unique($language_directories);

		foreach ($language_directories as $language_dir)
		{
			if (!file_exists($language_dir))
				continue;

			$dir = dir($language_dir);
			while ($entry = $dir->read())
			{
				// Each language file must *at least* have a 'index.LANGUAGENAME.php' file.
				if (preg_match('~^index\.(.+)\.php$~', $entry, $matches) == 0)
					continue;

				$context['languages'][$matches[1]] = array(
					'name' => $func['ucwords'](strtr($matches[1], array('_' => ' ', '-utf8' => ''))),
					'selected' => $selectedLanguage == $matches[1],
					'filename' => $matches[1],
				);
			}
			$dir->close();
		}
	}
		if ($context['allow_edit_membergroups'])
	{
		$context['member_groups'] = array(
			0 => array(
				'id' => 0,
				'name' => &$txt['no_primary_membergroup'],
				'is_primary' => $user_profile[$memID]['ID_GROUP'] == 0,
				'can_be_additional' => false,
			)
		);
        
		$request = db_query("
			SELECT groupName, ID_GROUP
			FROM {$db_prefix}membergroups
			WHERE ID_GROUP != 3
				AND minPosts = -1
			ORDER BY minPosts, IF(ID_GROUP < 4, ID_GROUP, 4), groupName", __FILE__, __LINE__);
		while ($row = mysqli_fetch_assoc($request))
		{
			// We should skip the administrator group if they don't have the admin_forum permission!
			if ($row['ID_GROUP'] == 1 && !allowedTo('admin_forum'))
				continue;

			$context['member_groups'][$row['ID_GROUP']] = array(
				'id' => $row['ID_GROUP'],
				'name' => $row['groupName'],
				'is_primary' => $user_profile[$memID]['ID_GROUP'] == $row['ID_GROUP'],
				'can_be_additional' => true,
			);
		}
		mysqli_free_result($request);
	}
	
}

?>