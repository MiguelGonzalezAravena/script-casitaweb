<?php
//Pagina de Rodrigo Zaupa (rigo@casitaweb.net)
if (!defined('CasitaWeb!-PorRigo'))die(base64_decode("d3d3LmNhc2l0YXdlYi5uZXQgLSByaWdv"));
function ManageSmileys(){global $context, $txt, $scripturl, $modSettings;

	isAllowedTo('manage_smileys');
	adminIndex('manage_smileys');

	loadLanguage('ManageSmileys');
	loadTemplate('ManageSmileys');

	$subActions = array(
		'addsmiley' => 'AddSmiley',
		'editsmileys' => 'EditSmileys',
		'setorder' => 'EditSmileyOrder',
	);

	$_REQUEST['sa'] = isset($_REQUEST['sa']) && isset($subActions[$_REQUEST['sa']]) ? $_REQUEST['sa'] : 'addsmiley';

	$context['page_title'] = &$txt['smileys_manage'];
	$context['sub_action'] = $_REQUEST['sa'];
	$context['sub_template'] = &$context['sub_action'];

	$context['admin_tabs'] = array(
		'title' => &$txt['smileys_manage'],
		'help' => 'smileys',
		'description' => $txt['smiley_settings_explain'],
		'tabs' => array(
			'addsmiley' => array(
				'title' => $txt['smileys_add'],
				'description' => $txt['smiley_addsmiley_explain'],
				'href' => '/moderacion/emoticones/addsmiley',
			),
			'editsmileys' => array(
				'title' => $txt['smileys_edit'],
				'description' => $txt['smiley_editsmileys_explain'],
				'href' => '/moderacion/emoticones/editsmileys',
			),
			'setorder' => array(
				'title' => $txt['smileys_set_order'],
				'description' => $txt['smiley_setorder_explain'],
				'href' => '/moderacion/emoticones/setorder',
			),

		),
	);

	if (isset($context['admin_tabs']['tabs'][$context['sub_action']]))
		$context['admin_tabs']['tabs'][$context['sub_action']]['is_selected'] = true;
	if (empty($modSettings['messageIcons_enable']))
		unset($context['admin_tabs']['tabs']['editicons']);
	if (empty($modSettings['smiley_enable']))
	{
		unset($context['admin_tabs']['tabs']['addsmiley']);
		unset($context['admin_tabs']['tabs']['editsmileys']);
		unset($context['admin_tabs']['tabs']['setorder']);
	}

	$subActions[$_REQUEST['sa']]();
}

function EditSmileySettings(){}

function EditSmileySets()
{
	global $modSettings, $context, $settings, $db_prefix, $txt, $boarddir;

	// Set the right tab to be selected.
	$context['admin_tabs']['tabs']['editsets']['is_selected'] = true;

	// They must've been submitted a form.
	if (isset($_POST['sc']))
	{
		checkSession();

		// Delete selected smiley sets.
		if (!empty($_POST['delete']) && !empty($_POST['smiley_set']))
		{
			$set_paths = explode(',', $modSettings['smiley_sets_known']);
			$set_names = explode("\n", $modSettings['smiley_sets_names']);
			foreach ($_POST['smiley_set'] as $id => $val)
				if (isset($set_paths[$id], $set_names[$id]) && !empty($id))
					unset($set_paths[$id], $set_names[$id]);

			updateSettings(array(
				'smiley_sets_known' => addslashes(implode(',', $set_paths)),
				'smiley_sets_names' => addslashes(implode("\n", $set_names)),
				'smiley_sets_default' => addslashes(in_array($modSettings['smiley_sets_default'], $set_paths) ? $modSettings['smiley_sets_default'] : $set_paths[0]),
			));

			cache_put_data('parsing_smileys', null, 480);
			cache_put_data('posting_smileys', null, 480);
		}
		// Add a new smiley set.
		elseif (!empty($_POST['add']))
			$context['sub_action'] = 'modifyset';
		// Create or modify a smiley set.
		elseif (isset($_POST['set']))
		{
			$set_paths = explode(',', $modSettings['smiley_sets_known']);
			$set_names = explode("\n", $modSettings['smiley_sets_names']);

			// Create a new smiley set.
			if ($_POST['set'] == -1 && isset($_POST['smiley_sets_path']))
			{
				if (in_array($_POST['smiley_sets_path'], $set_paths))
					fatal_lang_error('smiley_set_already_exists');

				updateSettings(array(
					'smiley_sets_known' => addslashes($modSettings['smiley_sets_known']) . ',' . $_POST['smiley_sets_path'],
					'smiley_sets_names' => addslashes($modSettings['smiley_sets_names']) . "\n" . $_POST['smiley_sets_name'],
					'smiley_sets_default' => empty($_POST['smiley_sets_default']) ? addslashes($modSettings['smiley_sets_default']) : $_POST['smiley_sets_path'],
				));
			}
			// Modify an existing smiley set.
			else
			{
				// Make sure the smiley set exists.
				if (!isset($set_paths[$_POST['set']]) || !isset($set_names[$_POST['set']]))
					fatal_lang_error('smiley_set_not_found');

				// Make sure the path is not yet used by another smileyset.
				if (in_array($_POST['smiley_sets_path'], $set_paths) && $_POST['smiley_sets_path'] != $set_paths[$_POST['set']])
					fatal_lang_error('smiley_set_path_already_used');

				$set_paths[$_POST['set']] = stripslashes($_POST['smiley_sets_path']);
				$set_names[$_POST['set']] = stripslashes($_POST['smiley_sets_name']);
				updateSettings(array(
					'smiley_sets_known' => addslashes(implode(',', $set_paths)),
					'smiley_sets_names' => addslashes(implode("\n", $set_names)),
					'smiley_sets_default' => empty($_POST['smiley_sets_default']) ? addslashes($modSettings['smiley_sets_default']) : $_POST['smiley_sets_path']
				));
			}

			// The user might have checked to also import smileys.
			if (!empty($_POST['smiley_sets_import']))
				ImportSmileys($_POST['smiley_sets_path']);

			cache_put_data('parsing_smileys', null, 480);
			cache_put_data('posting_smileys', null, 480);
		}
	}

	// Load all available smileysets...
	$context['smiley_sets'] = explode(',', $modSettings['smiley_sets_known']);
	$set_names = explode("\n", $modSettings['smiley_sets_names']);
	foreach ($context['smiley_sets'] as $i => $set)
		$context['smiley_sets'][$i] = array(
			'id' => $i,
			'path' => $set,
			'name' => $set_names[$i],
			'selected' => $set == $modSettings['smiley_sets_default']
		);

	// Importing any smileys from an existing set?
	if ($context['sub_action'] == 'import')
	{
		checkSession('get');
		$_GET['set'] = (int) $_GET['set'];

		// Sanity check - then import.
		if (isset($context['smiley_sets'][$_GET['set']]))
			ImportSmileys($context['smiley_sets'][$_GET['set']]['path']);

		// Force the process to continue.
		$context['sub_action'] = 'modifyset';
	}
	// If we're modifying or adding a smileyset, some context info needs to be set.
	if ($context['sub_action'] == 'modifyset')
	{
		$_GET['set'] = !isset($_GET['set']) ? -1 : (int) $_GET['set'];
		if ($_GET['set'] == -1 || !isset($context['smiley_sets'][$_GET['set']]))
			$context['current_set'] = array(
				'id' => '-1',
				'path' => '',
				'name' => '',
				'selected' => false,
				'is_new' => true,
			);
		else
		{
			$context['current_set'] = &$context['smiley_sets'][$_GET['set']];
			$context['current_set']['is_new'] = false;

			// Calculate whether there are any smileys in the directory that can be imported.
			if (!empty($modSettings['smiley_enable']) && !empty($modSettings['smileys_dir']) && is_dir($modSettings['smileys_dir'] . '/' . $context['current_set']['path']))
			{
				$smileys = array();
				$dir = dir($modSettings['smileys_dir'] . '/' . $context['current_set']['path']);
				while ($entry = $dir->read())
				{
					if (in_array(strrchr($entry, '.'), array('.jpg', '.gif', '.jpeg', '.png')))
						$smileys[strtolower($entry)] = addslashes($entry);
				}
				$dir->close();

				// Exclude the smileys that are already in the database.
				$request = db_query("
					SELECT filename
					FROM {$db_prefix}smileys
					WHERE filename IN ('" . implode("', '", $smileys) . "')", __FILE__, __LINE__);
				while ($row = mysql_fetch_assoc($request))
					if (isset($smileys[strtolower($row['filename'])]))
						unset($smileys[strtolower($row['filename'])]);
				mysql_free_result($request);

				$context['current_set']['can_import'] = count($smileys);
				// Setup this string to look nice.
				$txt['smiley_set_import_multiple'] = sprintf($txt['smiley_set_import_multiple'], $context['current_set']['can_import']);
			}
		}

		// Retrieve all potential smiley set directories.
		$context['smiley_set_dirs'] = array();
		if (!empty($modSettings['smileys_dir']) && is_dir($modSettings['smileys_dir']))
		{
			$dir = dir($modSettings['smileys_dir']);
			while ($entry = $dir->read())
			{
				if (!in_array($entry, array('.', '..')) && is_dir($modSettings['smileys_dir'] . '/' . $entry))
					$context['smiley_set_dirs'][] = array(
						'id' => $entry,
						'path' => $modSettings['smileys_dir'] . '/' . $entry,
						'selectable' => $entry == $context['current_set']['path'] || !in_array($entry, explode(',', $modSettings['smiley_sets_known'])),
						'current' => $entry == $context['current_set']['path'],
					);
			}
			$dir->close();
		}
	}
}

function AddSmiley()
{
	global $modSettings, $context, $settings, $db_prefix, $txt, $boarddir;


	if (isset($_POST['sc'], $_POST['smiley_code']))
	{
		checkSession();

		$allowedTypes = array('jpeg', 'jpg', 'gif', 'png', 'bmp');
		$disabledFiles = array('con', 'com1', 'com2', 'com3', 'com4', 'prn', 'aux', 'lpt1', '.htaccess', 'index.php');

		$_POST['smiley_code'] = htmltrim__recursive($_POST['smiley_code']);
		$_POST['smiley_location'] = empty($_POST['smiley_location']) || $_POST['smiley_location'] > 2 || $_POST['smiley_location'] < 0 ? 0 : (int) $_POST['smiley_location'];
		$_POST['smiley_filename'] = htmltrim__recursive($_POST['smiley_filename']);

		// Make sure some code was entered.
		if (empty($_POST['smiley_code']))
			fatal_lang_error('smiley_has_no_code');


		$request = db_query("
			SELECT ID_SMILEY
			FROM {$db_prefix}smileys
			WHERE code = BINARY '$_POST[smiley_code]'", __FILE__, __LINE__);
		if (mysql_num_rows($request) > 0)
			fatal_lang_error('smiley_not_unique');
		mysql_free_result($request);



		if (empty($_POST['smiley_filename']))
			fatal_lang_error('smiley_has_no_filename');

		// Find the position on the right.
		$smileyOrder = '0';
		if ($_POST['smiley_location'] != 1)
		{
			$request = db_query("
				SELECT MAX(smileyOrder) + 1
				FROM {$db_prefix}smileys
				WHERE hidden = $_POST[smiley_location]
					AND smileyRow = 0", __FILE__, __LINE__);
			list ($smileyOrder) = mysql_fetch_row($request);
			mysql_free_result($request);

			if (empty($smileyOrder))
				$smileyOrder = '0';
		}
		db_query("
			INSERT INTO {$db_prefix}smileys
				(code, filename, description, hidden, smileyOrder)
			VALUES (SUBSTRING('$_POST[smiley_code]', 1, 30), SUBSTRING('$_POST[smiley_filename]', 1, 48), SUBSTRING('$_POST[smiley_description]', 1, 80), $_POST[smiley_location], $smileyOrder)", __FILE__, __LINE__);

		cache_put_data('parsing_smileys', null, 480);
		cache_put_data('posting_smileys', null, 480);


		redirectexit('http://casitaweb.net/moderacion/emoticones/editsmileys');
	}

	$context['selected_set'] = $modSettings['smiley_sets_default'];


}

function EditSmileys()
{
	global $modSettings, $context, $settings, $db_prefix, $txt, $boarddir;

	// Force the correct tab to be displayed.
	$context['admin_tabs']['tabs']['editsmileys']['is_selected'] = true;

	// Submitting a form?
	if (isset($_POST['sc']))
	{
		checkSession();

		// Changing the selected smileys?
		if (isset($_POST['smiley_action']) && !empty($_POST['checked_smileys']))
		{
			foreach ($_POST['checked_smileys'] as $id => $smiley_id)
				$_POST['checked_smileys'][$id] = (int) $smiley_id;

			if ($_POST['smiley_action'] == 'delete')
				db_query("
					DELETE FROM {$db_prefix}smileys
					WHERE ID_SMILEY IN (" . implode(', ', $_POST['checked_smileys']) . ')', __FILE__, __LINE__);
			// Changing the status of the smiley?
			else
			{
				// Check it's a valid type.
				$displayTypes = array(
					'post' => 0,
					'hidden' => 1,
					'popup' => 2
				);
				if (isset($displayTypes[$_POST['smiley_action']]))
					db_query("
						UPDATE {$db_prefix}smileys
						SET hidden = " . $displayTypes[$_POST['smiley_action']] . "
						WHERE ID_SMILEY IN (" . implode(', ', $_POST['checked_smileys']) . ')', __FILE__, __LINE__);
			}
		}
		// Create/modify a smiley.
		elseif (isset($_POST['smiley']))
		{
			$_POST['smiley'] = (int) $_POST['smiley'];
			$_POST['smiley_code'] = htmltrim__recursive($_POST['smiley_code']);
			$_POST['smiley_filename'] = htmltrim__recursive($_POST['smiley_filename']);
			$_POST['smiley_location'] = empty($_POST['smiley_location']) || $_POST['smiley_location'] > 2 || $_POST['smiley_location'] < 0 ? 0 : (int) $_POST['smiley_location'];

			// Make sure some code was entered.
			if (empty($_POST['smiley_code']))
				fatal_lang_error('smiley_has_no_code');

			// Also make sure a filename was given.
			if (empty($_POST['smiley_filename']))
				fatal_lang_error('smiley_has_no_filename');

			// Check whether the new code has duplicates. It should be unique.
			$request = db_query("
				SELECT ID_SMILEY
				FROM {$db_prefix}smileys
				WHERE code = BINARY '$_POST[smiley_code]'" . (empty($_POST['smiley']) ? '' : "
					AND ID_SMILEY != $_POST[smiley]"), __FILE__, __LINE__);
			if (mysql_num_rows($request) > 0)
				fatal_lang_error('smiley_not_unique');
			mysql_free_result($request);

			db_query("
				UPDATE {$db_prefix}smileys
				SET
					code = '$_POST[smiley_code]',
					filename = '$_POST[smiley_filename]',
					description = '$_POST[smiley_description]',
					hidden = $_POST[smiley_location]
				WHERE ID_SMILEY = $_POST[smiley]", __FILE__, __LINE__);

		}

		cache_put_data('parsing_smileys', null, 480);
		cache_put_data('posting_smileys', null, 480);
	}

	// Load all known smiley sets.
	$context['smiley_sets'] = explode(',', $modSettings['smiley_sets_known']);
	$set_names = explode("\n", $modSettings['smiley_sets_names']);
	foreach ($context['smiley_sets'] as $i => $set)
		$context['smiley_sets'][$i] = array(
			'id' => $i,
			'path' => $set,
			'name' => $set_names[$i],
			'selected' => $set == $modSettings['smiley_sets_default']
		);

	// Prepare overview of all (custom) smileys.
	if ($context['sub_action'] == 'editsmileys')
	{
		$sortColumns = array(
			'code',
			'filename',
			'description',
			'hidden',
		);

		// Default to 'order by filename'.
		$context['sort'] = empty($_REQUEST['sort']) || !in_array($_REQUEST['sort'], $sortColumns) ? 'filename' : $_REQUEST['sort'];

		$request = db_query("
			SELECT ID_SMILEY, code, filename, description, smileyRow, smileyOrder, hidden
			FROM {$db_prefix}smileys
			ORDER BY $context[sort]", __FILE__, __LINE__);
		$context['smileys'] = array();
		while ($row = mysql_fetch_assoc($request))
			$context['smileys'][] = array(
				'id' => $row['ID_SMILEY'],
				'code' => htmlspecialchars($row['code']),
				'filename' => htmlspecialchars($row['filename']),
				'description' => htmlspecialchars($row['description']),
				'row' => $row['smileyRow'],
				'order' => $row['smileyOrder'],
				'location' => empty($row['hidden']) ? $txt['smileys_location_form'] : ($row['hidden'] == 1 ? $txt['smileys_location_hidden'] : $txt['smileys_location_popup']),
				'sets_not_found' => array(),
			);
		mysql_free_result($request);

		if (!empty($modSettings['smileys_dir']) && is_dir($modSettings['smileys_dir']))
		{
			foreach ($context['smiley_sets'] as $smiley_set)
			{
				foreach ($context['smileys'] as $smiley_id => $smiley)
					if (!file_exists($modSettings['smileys_dir'] . '/' . $smiley_set['path'] . '/' . $smiley['filename']))
						$context['smileys'][$smiley_id]['sets_not_found'][] = $smiley_set['path'];
			}
		}

		$context['selected_set'] = $modSettings['smiley_sets_default'];
	}
	// Modifying smileys.
	elseif ($context['sub_action'] == 'modifysmiley')
	{


		$request = db_query("
			SELECT ID_SMILEY AS id, code, filename, description, hidden AS location, 0 AS is_new
			FROM {$db_prefix}smileys
			WHERE ID_SMILEY = " . (int) $_GET['smiley'], __FILE__, __LINE__);
		if (mysql_num_rows($request) != 1)
			fatal_lang_error('smiley_not_found');
		$context['current_smiley'] = mysql_fetch_assoc($request);
		mysql_free_result($request);

		$context['current_smiley']['code'] = htmlspecialchars($context['current_smiley']['code']);
		$context['current_smiley']['filename'] = htmlspecialchars($context['current_smiley']['filename']);
		$context['current_smiley']['description'] = htmlspecialchars($context['current_smiley']['description']);

		if (isset($context['filenames'][strtolower($context['current_smiley']['filename'])]))
			$context['filenames'][strtolower($context['current_smiley']['filename'])]['selected'] = true;
	}
}

function EditSmileyOrder()
{
	global $modSettings, $context, $settings, $db_prefix, $txt, $boarddir;

	// Move smileys to another position.
	if (isset($_GET['sesc']))
	{
		checkSession('get');

		$_GET['location'] = empty($_GET['location']) || $_GET['location'] != 'popup' ? 0 : 2;
		$_GET['source'] = empty($_GET['source']) ? 0 : (int) $_GET['source'];

		if (empty($_GET['source']))
			fatal_lang_error('smiley_not_found');

		if (!empty($_GET['after']))
		{
			$_GET['after'] = (int) $_GET['after'];

			$request = db_query("
				SELECT smileyRow, smileyOrder, hidden
				FROM {$db_prefix}smileys
				WHERE hidden = $_GET[location]
					AND ID_SMILEY = $_GET[after]", __FILE__, __LINE__);
			if (mysql_num_rows($request) != 1)
				fatal_lang_error('smiley_not_found');
			list ($smileyRow, $smileyOrder, $smileyLocation) = mysql_fetch_row($request);
			mysql_free_result($request);
		}
		else
		{
			$smileyRow = (int) $_GET['row'];
			$smileyOrder = -1;
			$smileyLocation = (int) $_GET['location'];
		}

		db_query("
			UPDATE {$db_prefix}smileys
			SET smileyOrder = smileyOrder + 1
			WHERE hidden = $_GET[location]
				AND smileyRow = $smileyRow
				AND smileyOrder > $smileyOrder", __FILE__, __LINE__);

		db_query("
			UPDATE {$db_prefix}smileys
			SET
				smileyOrder = $smileyOrder + 1,
				smileyRow = $smileyRow,
				hidden = $smileyLocation
			WHERE ID_SMILEY = $_GET[source]", __FILE__, __LINE__);

		cache_put_data('parsing_smileys', null, 480);
		cache_put_data('posting_smileys', null, 480);
	}

	$request = db_query("
		SELECT ID_SMILEY, code, filename, description, smileyRow, smileyOrder, hidden
		FROM {$db_prefix}smileys
		WHERE hidden != 1
		ORDER BY smileyOrder, smileyRow", __FILE__, __LINE__);
	$context['smileys'] = array(
		'postform' => array(
			'rows' => array(),
		),
		'popup' => array(
			'rows' => array(),
		),
	);
	while ($row = mysql_fetch_assoc($request))
	{
		$location = empty($row['hidden']) ? 'postform' : 'popup';
		$context['smileys'][$location]['rows'][$row['smileyRow']][] = array(
			'id' => $row['ID_SMILEY'],
			'code' => htmlspecialchars($row['code']),
			'filename' => htmlspecialchars($row['filename']),
			'description' => htmlspecialchars($row['description']),
			'row' => $row['smileyRow'],
			'order' => $row['smileyOrder'],
			'selected' => !empty($_REQUEST['move']) && $_REQUEST['move'] == $row['ID_SMILEY'],
		);
	}
	mysql_free_result($request);

	$context['move_smiley'] = empty($_REQUEST['move']) ? 0 : (int) $_REQUEST['move'];

	// Make sure all rows are sequential.
	foreach (array_keys($context['smileys']) as $location)
		$context['smileys'][$location] = array(
			'id' => $location,
			'title' => $location == 'postform' ? $txt['smileys_location_form'] : $txt['smileys_location_popup'],
			'description' => $location == 'postform' ? $txt['smileys_location_form_description'] : $txt['smileys_location_popup_description'],
			'last_row' => count($context['smileys'][$location]['rows']),
			'rows' => array_values($context['smileys'][$location]['rows']),
		);

	// Check & fix smileys that are not ordered properly in the database.
	foreach (array_keys($context['smileys']) as $location)
	{
		foreach ($context['smileys'][$location]['rows'] as $id => $smiley_row)
		{			if ($id != $smiley_row[0]['row'])
			{
				db_query("
					UPDATE {$db_prefix}smileys
					SET smileyRow = $id
					WHERE smileyRow = {$smiley_row[0]['row']}
						AND hidden = " . ($location == 'postform' ? '0' : '2'), __FILE__, __LINE__);

				$context['smileys'][$location]['rows'][$id][0]['row'] = $id;
			}
			foreach ($smiley_row as $order_id => $smiley)
				if ($order_id != $smiley['order'])
					db_query("
						UPDATE {$db_prefix}smileys
						SET smileyOrder = $order_id
						WHERE ID_SMILEY = $smiley[id]", __FILE__, __LINE__);
		}
	}

	cache_put_data('parsing_smileys', null, 480);
	cache_put_data('posting_smileys', null, 480);
}

function InstallSmileySet(){}

function ImportSmileys($smileyPath){}
function EditMessageIcons(){}

?>