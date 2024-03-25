<?php
//Pagina de Rodrigo Zaupa (rigo@casitaweb.net)
if (!defined('CasitaWeb!-PorRigo'))die(base64_decode("d3d3LmNhc2l0YXdlYi5uZXQgLSByaWdv"));
function ManagePostSettings(){global $context, $txt, $scripturl,$ID_MEMBER;
if($ID_MEMBER=='1'){
	adminIndex('posts_and_topics');
$_GET['sa']=str_replace("/","",$_GET['sa']);
	$subActions = array(    
        'config' => array('EditSearchSettings', 'admin_forum'),
		'bbc' => array('ModifyBBCSettings', 'admin_forum'),
		'censor' => array('SetCensor', 'admin_forum'),
	);

	$_GET['sa'] = isset($_GET['sa']) && isset($subActions[$_GET['sa']]) ? $_GET['sa'] : (allowedTo('admin_forum') ? 'config' : 'bbc');
	isAllowedTo($subActions[$_GET['sa']][1]);

	$context['page_title'] = 'Configuraci&oacute;n de la Web';

	$context['admin_tabs'] = array(
		'title' => 'Configuraci&oacute;n de la Web',
		'help' => 'posts_and_topics',
		'description' => $txt['manageposts_description'],
		'tabs' => array()
	);
		$context['admin_tabs']['tabs'][] = array(
			'title' => 'Configuraci&oacute;n general',
			'description' => $txt['manageposts_bbc_settings_description'],
			'href' => '/moderacion/web/config/',
			'is_selected' => $_GET['sa'] == 'config',
		);
        	$context['admin_tabs']['tabs'][] = array(
			'title' => $txt['manageposts_bbc_settings'],
			'description' => $txt['manageposts_bbc_settings_description'],
			'href' => '/moderacion/web/bbc/',
			'is_selected' => $_GET['sa'] == 'bbc',
		);
                
		$context['admin_tabs']['tabs'][] = array(
			'title' => $txt[135],
			'description' => $txt[141],
			'href' => '/moderacion/web/censor/',
			'is_selected' => $_GET['sa'] == 'censor',
			'is_last' => !allowedTo('admin_forum'),
		);

	$subActions[$_GET['sa']][0]();
}else{fatal_error('No podes estar aca.-');}}

function EditSearchSettings(){
global $txt, $context,$modSettings,$db_prefix,$ID_MEMBER, $sourcedir;
if($ID_MEMBER=='1'){
require($sourcedir.'/ManagePermissions.php');
if(isset($_POST['save'])){
		checkSession();
		updateSettings(array(
		'search_results_per_page' => (int) $_POST['search_results_per_page'],
		'catcoment' => (int) $_POST['ccomentarios'],
		'smiley_enable' => (int) $_POST['smiley_enable'],
		'enableStickyTopics' => (int) $_POST['enableStickyTopics'],
		'puntos_por_post-img' => (int) $_POST['puntos_por_post-img'],
		'timeLoadPageEnable' => (int) $_POST['timeLoadPageEnable'],));
		save_inline_permissions(array('search_posts'));};
	$context['page_title'] ='Configuraci&oacute;n general';
	$context['sub_template'] = 'modify_settings';
    $context['all_pages'] = array('modify_settings' => 'modify_settings');
	init_inline_permissions(array('search_posts'));}
    }
    
function SetCensor()
{
	global $txt, $modSettings, $context;

	if (!empty($_POST['save_censor']))
	{
		checkSession();

		$censored_vulgar = array();
		$censored_proper = array();
		if (isset($_POST['censortext']))
		{
			$_POST['censortext'] = explode("\n", strtr($_POST['censortext'], array("\r" => '')));

			foreach ($_POST['censortext'] as $c)
				list ($censored_vulgar[], $censored_proper[]) = array_pad(explode('=', trim($c)), 2, '');
		}
		elseif (isset($_POST['censor_vulgar'], $_POST['censor_proper']))
		{
			if (is_array($_POST['censor_vulgar']))
			{
				foreach ($_POST['censor_vulgar'] as $i => $value)
					if ($value == '')
					{
						unset($_POST['censor_vulgar'][$i]);
						unset($_POST['censor_proper'][$i]);
					}

				$censored_vulgar = $_POST['censor_vulgar'];
				$censored_proper = $_POST['censor_proper'];
			}
			else
			{
				$censored_vulgar = explode("\n", strtr($_POST['censor_vulgar'], array("\r" => '')));
				$censored_proper = explode("\n", strtr($_POST['censor_proper'], array("\r" => '')));
			}
		}

		// Set the new arrays and settings in the database.
		$updates = array(
			'censor_vulgar' => implode("\n", $censored_vulgar),
			'censor_proper' => implode("\n", $censored_proper),
			'censorWholeWord' => empty($_POST['censorWholeWord']) ? '0' : '1',
			'censorIgnoreCase' => empty($_POST['censorIgnoreCase']) ? '0' : '1',
		);

		updateSettings($updates);
	}

	if (isset($_POST['censortest']))
	{
		$censorText = htmlspecialchars(stripslashes($_POST['censortest']), ENT_QUOTES);
		$context['censor_test'] = strtr(censorText($censorText), array('"' => '&quot;'));
	}

	// Set everything up for the template to do its thang.
	$censor_vulgar = explode("\n", $modSettings['censor_vulgar']);
	$censor_proper = explode("\n", $modSettings['censor_proper']);

	$context['censored_words'] = array();
	for ($i = 0, $n = count($censor_vulgar); $i < $n; $i++)
	{
		if (empty($censor_vulgar[$i]))
			continue;

		// Skip it, it's either spaces or stars only.
		if (trim(strtr($censor_vulgar[$i], '*', ' ')) == '')
			continue;

		$context['censored_words'][htmlspecialchars(trim($censor_vulgar[$i]))] = isset($censor_proper[$i]) ? htmlspecialchars($censor_proper[$i]) : '';
	}

	$context['sub_template'] = 'edit_censored';
	$context['page_title'] = $txt[135];
}

function ModifyPostSettings(){}
function ModifyHideTagSpecialSettings(){}
function ModifyBBCSettings(){global $context, $txt, $modSettings, $helptxt;
	$context['sub_template'] = 'edit_bbc_settings';
	$context['page_title'] = $txt['manageposts_bbc_settings_title'];
	$temp = parse_bbc(false);
	$bbcTags = array();
	foreach ($temp as $tag)
		$bbcTags[] = $tag['tag'];

	$bbcTags = array_unique($bbcTags);
	$totalTags = count($bbcTags);

	// The number of columns we want to show the BBC tags in.
	$numColumns = 3;

	// In case we're saving.
	if (isset($_POST['save_settings']))
	{
		checkSession();

		if (!isset($_POST['enabledTags']))
			$_POST['enabledTags'] = array();
		elseif (!is_array($_POST['enabledTags']))
			$_POST['enabledTags'] = array($_POST['enabledTags']);

		// Update the actual settings.
		updateSettings(array(
			'minWordLen' => empty($_POST['minWordLen']) ? '0' : (int) $_POST['minWordLen'],
			'minChar' => empty($_POST['minChar']) ? '0' : (int) $_POST['minChar'],
			'enableBBC' => empty($_POST['enableBBC']) ? '0' : '1',
			'enablePostHTML' => empty($_POST['enablePostHTML']) ? '0' : '1',
			'autoLinkUrls'  => empty($_POST['autoLinkUrls']) ? '0' : '1',
			'disabledBBC' => implode(',', array_diff($bbcTags, $_POST['enabledTags'])),
		));
	}

	$context['bbc_columns'] = array();
	$tagsPerColumn = ceil($totalTags / $numColumns);
	$disabledTags = empty($modSettings['disabledBBC']) ? array() : explode(',', $modSettings['disabledBBC']);

	$col = 0;
	$i = 0;
	foreach ($bbcTags as $tag)
	{
		if ($i % $tagsPerColumn == 0 && $i != 0)
			$col++;

		$context['bbc_columns'][$col][] = array(
			'tag' => $tag,
			'is_enabled' => !in_array($tag, $disabledTags),
			// !!! 'tag_' . ?
			'show_help' => isset($helptxt[$tag]),
		);

		$i++;
	}

	$context['bbc_all_selected'] = empty($disabledTags);
}

function ModifyTopicSettings(){}

?>
