<?php
// Página de Rodrigo Zaupa (rigo@casitaweb.net)
if (!defined('CasitaWeb!-PorRigo')) {
	die(base64_decode('d3d3LmNhc2l0YXdlYi5uZXQgLSByaWdv'));
}

function setLoginCookie($cookie_length, $id, $password = '')
{
	global $cookiename, $boardurl, $modSettings;

	$cookie_state = (empty($modSettings['localCookies']) ? 0 : 1) | (empty($modSettings['globalCookies']) ? 0 : 2);
	if (isset($_COOKIE[$cookiename]) && preg_match('~^a:[34]:\{i:0;(i:\d{1,6}|s:[1-8]:"\d{1,8}");i:1;s:(0|40):"([a-fA-F0-9]{40})?";i:2;[id]:\d{1,14};(i:3;i:\d;)?\}$~', $_COOKIE[$cookiename]) === 1) {
		$array = @unserialize($_COOKIE[$cookiename]);
		if (isset($array[3]) && $array[3] != $cookie_state) {
			$cookie_url = url_parts($array[3] & 1 > 0, $array[3] & 2 > 0);
			setcookie($cookiename, serialize(array(0, '', 0)), time() - 3600, $cookie_url[1], $cookie_url[0], 0);
		}
	}

	$data = serialize(empty($id) ? array(0, '', 0) : array($id, $password, time() + $cookie_length, $cookie_state));
	$cookie_url = url_parts(!empty($modSettings['localCookies']), !empty($modSettings['globalCookies']));
	setcookie($cookiename, $data, time() + $cookie_length, $cookie_url[1], $cookie_url[0], 0);
	if (empty($id) && !empty($modSettings['globalCookies']))
		setcookie($cookiename, $data, time() + $cookie_length, $cookie_url[1], '', 0);
	if (!empty($modSettings['forum_alias_urls'])) {
		$aliases = explode(',', $modSettings['forum_alias_urls']);

		$temp = $boardurl;
		foreach ($aliases as $alias) {
			// Fake the $boardurl so we can set a different cookie.
			$alias = strtr(trim($alias), array('http://' => '', 'https://' => ''));
			$boardurl = 'http://' . $alias;

			$cookie_url = url_parts(!empty($modSettings['localCookies']), !empty($modSettings['globalCookies']));

			if ($cookie_url[0] == '')
				$cookie_url[0] = strtok($alias, '/');

			setcookie($cookiename, $data, time() + $cookie_length, $cookie_url[1], $cookie_url[0], 0);
		}

		$boardurl = $temp;
	}

	$_COOKIE[$cookiename] = $data;

	// Make sure the user logs in with a new session ID.
	if (!isset($_SESSION['login_' . $cookiename]) || $_SESSION['login_' . $cookiename] !== $data) {
		// Backup and remove the old session.
		$oldSessionData = $_SESSION;
		$_SESSION = array();
		session_destroy();

		// Recreate and restore the new session.
		loadSession();
		session_regenerate_id();
		$_SESSION = $oldSessionData;

		// Version 4.3.2 didn't store the cookie of the new session.
		if (version_compare(PHP_VERSION, '4.3.2') === 0)
			setcookie(session_name(), session_id(), time() + $cookie_length, $cookie_url[1], '', 0);

		$_SESSION['login_' . $cookiename] = $data;
	}
}

// PHP < 4.3.2 doesn't have this function
if (!function_exists('session_regenerate_id')) {
	function session_regenerate_id()
	{
		// Too late to change the session now.
		if (headers_sent())
			return false;

		session_id(strtolower(md5(uniqid(rand(), true))));
		return true;
	}
}

// Get the domain and path for the cookie...
function url_parts($local, $global)
{
	global $boardurl;

	// Parse the URL with PHP to make life easier.
	$parsed_url = parse_url($boardurl);

	// Is local cookies off?
	if (empty($parsed_url['path']) || !$local)
		$parsed_url['path'] = '';

	// Globalize cookies across domains (filter out IP-addresses)?
	if ($global && preg_match('~^\d{1,3}(\.\d{1,3}){3}$~', $parsed_url['host']) == 0 && preg_match('~(?:[^\.]+\.)?([^\.]{2,}\..+)\z~i', $parsed_url['host'], $parts) == 1)
		$parsed_url['host'] = '.' . $parts[1];
	// We shouldn't use a host at all if both options are off.
	elseif (!$local && !$global)
		$parsed_url['host'] = '';
	// The host also shouldn't be set if there aren't any dots in it.
	elseif (!isset($parsed_url['host']) || strpos($parsed_url['host'], '.') === false)
		$parsed_url['host'] = '';

	return array($parsed_url['host'], $parsed_url['path'] . '/');
}

// Kick out a guest when guest access is off...
function KickGuest()
{
	echo is_not_guest();
}

function InMaintenance() {}
function adminLogin() {}
function adminLogin_outputPostVars($k, $v) {}
function show_db_error($loadavg = false) {}

function findMembers($names, $use_wildcards = false, $buddies_only = false, $max = null)
{
	global $db_prefix, $scripturl, $user_info, $modSettings, $func;

	if (!is_array($names))
		$names = explode(',', $names);

	$maybe_email = false;
	foreach ($names as $i => $name) {
		$names[$i] = addslashes(trim($func['strtolower']($name)));

		$maybe_email |= strpos($name, '@') !== false;

		// Make it so standard wildcards will work. (* and ?)
		if ($use_wildcards)
			$names[$i] = strtr($names[$i], array('%' => '\%', '_' => '\_', '*' => '%', '?' => '_', "\'" => '&#039;'));
		else
			$names[$i] = strtr($names[$i], array("\'" => '&#039;'));
	}

	// What are we using to compare?
	$comparison = $use_wildcards ? 'LIKE' : '=';

	// Nothing found yet.
	$results = array();

	// This ensures you can't search someones email address if you can't see it.
	$email_condition = $user_info['is_admin'] || empty($modSettings['allow_hideEmail']) ? '' : 'hideEmail = 0 AND ';

	if ($use_wildcards || $maybe_email)
		$email_condition = '
			OR (' . $email_condition . "emailAddress $comparison '" . implode("') OR ($email_condition emailAddress $comparison '", $names) . "')";
	else
		$email_condition = '';

	$request = db_query("
		SELECT ID_MEMBER, memberName, realName, emailAddress, hideEmail
		FROM {$db_prefix}members
		WHERE (memberName $comparison '" . implode("' OR memberName $comparison '", $names) . "'
			OR realName $comparison '" . implode("' OR realName $comparison '", $names) . "'$email_condition)
			" . ($buddies_only ? 'AND ID_MEMBER IN (' . implode(', ', $user_info['buddies']) . ')' : '') . '
			AND is_activated IN (1, 11)' . ($max == null ? '' : '
		LIMIT ' . (int) $max), __FILE__, __LINE__);
	while ($row = mysqli_fetch_assoc($request)) {
		$results[$row['ID_MEMBER']] = array(
			'id' => $row['ID_MEMBER'],
			'name' => $row['realName'],
			'username' => $row['memberName'],
			'email' => empty($row['hideEmail']) || empty($modSettings['allow_hideEmail']) || $user_info['is_admin'] ? $row['emailAddress'] : '',
			'href' => '/perfil/' . $row['realName'],
			'link' => '<a href="/perfil/' . $row['realName'] . '">' . $row['realName'] . '</a>'
		);
	}
	mysqli_free_result($request);

	// Return all the results.
	return $results;
}

function JSMembers() {}
function RequestMembers() {}

function resetPassword($memID, $username = null)
{
	global $db_prefix, $scripturl, $context, $txt, $sourcedir, $modSettings;

	loadLanguage('Login');
	is_admin();

	$request = db_query("
		SELECT realName, emailAddress
		FROM {$db_prefix}members
		WHERE ID_MEMBER = $memID", __FILE__, __LINE__);
	list($user, $email) = mysqli_fetch_row($request);
	mysqli_free_result($request);

	if ($username !== null) {
		$old_user = $user;
		$user = trim($username);
	}
	$newPassword = substr(preg_replace('/\W/', '', md5(rand())), 0, 10);
	$newPassword_sha1 = sha1(strtolower($user) . $newPassword);
	if ($username !== null) {
		if (empty($user))
			fatal_lang_error(37, false);

		if (in_array($user, array('_', '|')) || preg_match('~[<>&"\'=\\\\]~', $user) != 0 || strpos($user, '[code') !== false || strpos($user, '[/code') !== false)
			fatal_lang_error(240, false);

		if (stristr($user, $txt[28]) !== false)
			fatal_lang_error(244, true, array($txt[28]));

		require ($sourcedir . '/Subs-Members.php');
		if (isReservedName($user, $memID, false))
			fatal_error('(' . htmlspecialchars($user) . ') ' . $txt[473], false);

		db_query("UPDATE {$db_prefix}members 
        SET memberName='$user' 
        WHERE ID_MEMBER='$memID' 
        LIMIT 1", __FILE__, __LINE__);

		db_query("UPDATE {$db_prefix}ban_groups 
        SET name='$user' 
        WHERE name='$old_user' 
        LIMIT 1", __FILE__, __LINE__);

		db_query("UPDATE {$db_prefix}mensaje_personal 
        SET name_de='$user' 
        WHERE name_de='$old_user'", __FILE__, __LINE__);

		db_query("UPDATE {$db_prefix}denuncias 
        SET name_post='$user' 
        WHERE name_post='$old_user'", __FILE__, __LINE__);

		db_query("UPDATE {$db_prefix}messages 
        SET posterName='$user' 
        WHERE ID_MEMBER='$memID'", __FILE__, __LINE__);

		db_query("UPDATE {$db_prefix}comunidades
        SET UserName='$user' 
        WHERE id_user='$memID'", __FILE__, __LINE__);

		db_query("UPDATE {$db_prefix}comunidades_articulos
        SET UserName='$user' 
        WHERE id_user='$memID'", __FILE__, __LINE__);

		updateMemberData($memID, array('passwd' => "'" . $newPassword_sha1 . "'"));

		require ($sourcedir . '/Subs-Post.php');

		sendmail($email, 'Cambio de nick',
			"Le comentamos que se ha cambiado tu nick en CasitaWeb!<br />Por cuestiones de seguridad tambien se ha cambiado el password.\n\n"
				. "Nick: $user, Password: $newPassword\n\n"
				. "$txt[701]<br />"
				. "<a href='http://casitaweb.net/perfil/$user'>http://casitaweb.net/perfil/$user</a>");
	}

	if (isset($modSettings['integrate_reset_pass']) && function_exists($modSettings['integrate_reset_pass']))
		call_user_func($modSettings['integrate_reset_pass'], $old_user, $user, $newPassword);
}

function validatePassword($password, $username, $restrict_in = array())
{
	global $modSettings, $func;

	if (strlen($password) < (empty($modSettings['password_strength']) ? 4 : 8))
		return 'short';

	if (empty($modSettings['password_strength']))
		return null;
	if (preg_match('~\b' . preg_quote($password, '~') . '\b~', implode(' ', $restrict_in)) != 0)
		return 'restricted_words';
	elseif ($func['strpos']($password, $username) !== false)
		return 'restricted_words';

	if ($modSettings['password_strength'] == 1)
		return null;
	$good = preg_match('~(\D\d|\d\D)~', $password) != 0;
	$good &= $func['strtolower']($password) != $password;

	return $good ? null : 'chars';
}
?>