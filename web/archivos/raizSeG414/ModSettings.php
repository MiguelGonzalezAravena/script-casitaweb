<?php
//Pagina de Rodrigo Zaupa (rigo@casitaweb.net)
if (!defined('CasitaWeb!-PorRigo'))die(base64_decode("d3d3LmNhc2l0YXdlYi5uZXQgLSByaWdv"));
function ModifyFeatureSettings(){global $context, $txt, $scripturl, $modSettings, $sourcedir;
	isAllowedTo('admin_forum');
	adminIndex('edit_mods_settings');
	loadLanguage('Help');
	loadLanguage('ModSettings');
	require_once($sourcedir . '/ManageServer.php');
	$context['page_title'] = $txt['modSettings_title'];
	$context['sub_template'] = 'show_settings';
	$subActions = array(
		'basic' => 'ModifyBasicSettings',
		'layout' => 'ModifyLayoutSettings',
		'karma' => 'ModifyKarmaSettings',
	);

	$_REQUEST['sa'] = isset($_REQUEST['sa']) && isset($subActions[$_REQUEST['sa']]) ? $_REQUEST['sa'] : 'basic';
	$context['sub_action'] = $_REQUEST['sa'];

	$context['admin_tabs'] = array(
		'title' => &$txt['modSettings_title'],
		'help' => 'modsettings',
		'description' => $txt['smf3'],
		'tabs' => array(
			'basic' => array(
				'title' => $txt['mods_cat_features'],
				'href' => $scripturl . '?action=featuresettings;sa=basic;sesc=' . $context['session_id'],
			),
			'layout' => array(
				'title' => $txt['mods_cat_layout'],
				'href' => $scripturl . '?action=featuresettings;sa=layout;sesc=' . $context['session_id'],
			),
			'karma' => array(
				'title' => $txt['smf293'],
				'href' => $scripturl . '?action=featuresettings;sa=karma;sesc=' . $context['session_id'],
				'is_last' => true,
			),
		),
	);

	// Select the right tab based on the sub action.
	if (isset($context['admin_tabs']['tabs'][$context['sub_action']]))
		$context['admin_tabs']['tabs'][$context['sub_action']]['is_selected'] = true;

	// Call the right function for this sub-acton.
	$subActions[$_REQUEST['sa']]();
}

// This function basically just redirects to the right save function.
function ModifyFeatureSettings2()
{
	global $context, $txt, $scripturl, $modSettings, $sourcedir;

	isAllowedTo('admin_forum');
	loadLanguage('ModSettings');

	// Quick session check...
	checkSession();

	require_once($sourcedir . '/ManageServer.php');

	$subActions = array(
		'basic' => 'ModifyBasicSettings',
		'layout' => 'ModifyLayoutSettings',
		'karma' => 'ModifyKarmaSettings',
	);

	// Default to core (I assume)
	$_REQUEST['sa'] = isset($_REQUEST['sa']) && isset($subActions[$_REQUEST['sa']]) ? $_REQUEST['sa'] : 'basic';

	// Actually call the saving function.
	$subActions[$_REQUEST['sa']]();
}

function ModifyBasicSettings()
{
	global $txt, $scripturl, $context, $settings, $sc, $modSettings;

	$config_vars = array(
			// Big Options... polls, sticky, bbc....
			array('select', 'pollMode', array(&$txt['smf34'], &$txt['smf32'], &$txt['smf33'])),
		'',
			// Basic stuff, user languages, titles, flash, permissions...
			array('check', 'allow_guestAccess'),
			array('check', 'userLanguage'),
			array('check', 'allow_editDisplayName'),
			array('check', 'allow_hideOnline'),
			array('check', 'allow_hideEmail'),
			array('check', 'guest_hideContacts'),
			array('check', 'titlesEnable'),
			array('check', 'enable_buddylist'),
			array('text', 'default_personalText'),
			array('int', 'max_signatureLength'),
		'',
			// Stats, compression, cookies.... server type stuff.
			array('text', 'time_format'),
			array('select', 'number_format', array('1234.00' => '1234.00', '1,234.00' => '1,234.00', '1.234,00' => '1.234,00', '1 234,00' => '1 234,00', '1234,00' => '1234,00')),
			array('float', 'time_offset'),
			array('int', 'failed_login_threshold'),
			array('int', 'lastActive'),
			array('check', 'trackStats'),
			array('check', 'hitStats'),
			array('check', 'enableErrorLogging'),
			array('check', 'securityDisable'),
		'',
			// Reactive on email, and approve on delete
			array('check', 'send_validation_onChange'),
			array('check', 'approveAccountDeletion'),
		'',
			// Option-ish things... miscellaneous sorta.
			array('check', 'allow_disableAnnounce'),
			array('check', 'disallow_sendBody'),
			array('check', 'modlog_enabled'),
			array('check', 'queryless_urls'),
		'',
			// Width/Height image reduction.
			array('int', 'max_image_width'),
			array('int', 'max_image_height'),
		'',
			// Reporting of personal messages?
			array('check', 'enableReportPM'),
	);

	// Saving?
	if (isset($_GET['save']))
	{
		// Fix PM settings.
		$_POST['pm_spam_settings'] = (int) $_POST['max_pm_recipients'] . ',' . (int) $_POST['pm_posts_verification'] . ',' . (int) $_POST['pm_posts_per_hour'];
		$save_vars = $config_vars;
		$save_vars[] = array('text', 'pm_spam_settings');

		saveDBSettings($save_vars);

		writeLog();
		redirectexit('action=featuresettings;sa=basic');
	}

	// Hack for PM spam settings.
	list ($modSettings['max_pm_recipients'], $modSettings['pm_posts_verification'], $modSettings['pm_posts_per_hour']) = explode(',', $modSettings['pm_spam_settings']);
	$config_vars[] = array('int', 'max_pm_recipients');
	$config_vars[] = array('int', 'pm_posts_verification');
	$config_vars[] = array('int', 'pm_posts_per_hour');

	$context['post_url'] = $scripturl . '?action=featuresettings2;save;sa=basic';
	$context['settings_title'] = $txt['mods_cat_features'];

	prepareDBSettingContext($config_vars);
}

function ModifyLayoutSettings()
{
	global $txt, $scripturl, $context, $settings, $sc;

	$config_vars = array(
			// Compact pages?
			array('check', 'compactTopicPagesEnable'),
			array('int', 'compactTopicPagesContiguous', null, $txt['smf235'] . '<div class="smalltext">' . str_replace(' ', '&nbsp;', '"3" ' . $txt['smf236'] . ': <b>1 ... 4 [5] 6 ... 9</b>') . '<br />' . str_replace(' ', '&nbsp;', '"5" ' . $txt['smf236'] . ': <b>1 ... 3 4 [5] 6 7 ... 9</b>') . '</div>'),
		'',
			// Stuff that just is everywhere - today, search, online, etc.
			array('select', 'todayMod', array(&$txt['smf290'], &$txt['smf291'], &$txt['smf292'])),
			array('check', 'topbottomEnable'),
			array('check', 'onlineEnable'),
			array('check', 'enableVBStyleLogin'),
		'',
			// Pagination stuff.
			array('int', 'defaultMaxMembers'),
		'',
			// This is like debugging sorta.
			array('check', 'timeLoadPageEnable'),
			array('check', 'disableHostnameLookup'),
		'',
			// Who's online.
			array('check', 'who_enabled'),
	);

	// Saving?
	if (isset($_GET['save']))
	{
		saveDBSettings($config_vars);
		redirectexit('action=featuresettings;sa=layout');

		loadUserSettings();
		writeLog();
	}

	$context['post_url'] = $scripturl . '?action=featuresettings2;save;sa=layout';
	$context['settings_title'] = $txt['mods_cat_layout'];

	prepareDBSettingContext($config_vars);
}

function ModifyKarmaSettings()
{
	global $txt, $scripturl, $context, $settings, $sc;

	$config_vars = array(
			// Karma - On or off?
			array('select', 'karmaMode', explode('|', $txt['smf64'])),
		'',
			// Who can do it.... and who is restricted by time limits?
			array('int', 'karmaMinPosts'),
			array('float', 'karmaWaitTime'),
			array('check', 'karmaTimeRestrictAdmins'),
		'',
			// What does it look like?  [smite]?
			array('text', 'karmaLabel'),
			array('text', 'karmaApplaudLabel'),
			array('text', 'karmaSmiteLabel'),
	);

	// Saving?
	if (isset($_GET['save']))
	{
		saveDBSettings($config_vars);
		redirectexit('action=featuresettings;sa=karma');
	}

	$context['post_url'] = $scripturl . '?action=featuresettings2;save;sa=karma';
	$context['settings_title'] = $txt['smf293'];

	prepareDBSettingContext($config_vars);
}

?>