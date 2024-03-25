<?php
//Pagina de Rodrigo Zaupa (rigo@casitaweb.net)
if (!defined('CasitaWeb!-PorRigo'))die(base64_decode("d3d3LmNhc2l0YXdlYi5uZXQgLSByaWdv"));
function ManageBoards(){global $context, $txt, $scripturl;
loadLanguage('ManageBoards');
$subActions = array(
		'board' => array('EditBoard', 'manage_boards'),
		'board2' => array('EditBoard2', 'manage_boards'),
		'main' => array('ManageBoardsMain', 'manage_boards'),
		'move' => array('ManageBoardsMain', 'manage_boards'),
		'newboard' => array('EditBoard', 'manage_boards'),
		'settings' => array('EditBoardSettings', 'admin_forum'),
	);

$_REQUEST['sa'] = isset($_REQUEST['sa']) && isset($subActions[$_REQUEST['sa']]) ? $_REQUEST['sa'] : (allowedTo('manage_boards') ? 'main' : 'settings');
	isAllowedTo($subActions[$_REQUEST['sa']][1]);
	adminIndex('manage_boards');
	$context['admin_tabs'] = array(
		'title' => $txt[41],
		'help' => 'manage_boards',
		'description' => $txt[677],
		'tabs' => array(),
	);
	if (allowedTo('manage_boards'))
	{
		$context['admin_tabs']['tabs']['modify_boards'] = array(
			'title' => $txt['boardsEdit'],
			'description' => $txt[677],
			'href' => $scripturl . '?cw1=manageboards',
			'is_selected' => $_REQUEST['sa'] != 'newcat' && $_REQUEST['sa'] != 'settings',
		);

	}
	if (allowedTo('admin_forum'))
		$context['admin_tabs']['tabs']['settings'] = array(
			'title' => $txt['settings'],
			'description' => $txt['mboards_settings_desc'],
			'href' => $scripturl . '?cw1=manageboards;sa=settings',
			'is_selected' => $_REQUEST['sa'] == 'settings',
			'is_last' => true,
		);
$subActions[$_REQUEST['sa']][0]();}

function ManageBoardsMain()
{
	global $txt, $context, $cat_tree, $boards, $boardList, $scripturl, $sourcedir, $txt;

	loadTemplate('ManageBoards');

	require_once($sourcedir . '/Subs-Boards.php');

	if (isset($_REQUEST['sa']) && $_REQUEST['sa'] == 'move' && in_array($_REQUEST['move_to'], array('child', 'before', 'after', 'top')))
	{
		checkSession('get');
		if ($_REQUEST['move_to'] === 'top')
			$boardOptions = array(
				'move_to' => $_REQUEST['move_to'],
				'target_category' => (int) $_REQUEST['target_cat'],
				'move_first_child' => true,
			);
		else
			$boardOptions = array(
				'move_to' => $_REQUEST['move_to'],
				'target_board' => (int) $_REQUEST['target_board'],
				'move_first_child' => true,
			);
		modifyBoard((int) $_REQUEST['src_board'], $boardOptions);
	}

	getBoardTree();

	$context['move_board'] = !empty($_REQUEST['move']) && isset($boards[(int) $_REQUEST['move']]) ? (int) $_REQUEST['move'] : 0;

	$context['categories'] = array();
	foreach ($cat_tree as $catid => $tree)
	{
		$context['categories'][$catid] = array(
			'name' => &$tree['node']['name'],
			'id' => &$tree['node']['id'],
			'boards' => array()
		);
		$move_cat = !empty($context['move_board']) && $boards[$context['move_board']]['category'] == $catid;
		foreach ($boardList[$catid] as $boardid)
		{
			$context['categories'][$catid]['boards'][$boardid] = array(
				'id' => &$boards[$boardid]['id'],
				'name' => &$boards[$boardid]['name'],
				'description' => &$boards[$boardid]['description'],
				'child_level' => &$boards[$boardid]['level'],
				'local_permissions' => &$boards[$boardid]['use_local_permissions'],
				'move' => $move_cat && ($boardid == $context['move_board'] || isChildOf($boardid, $context['move_board']))
			);
		}
	}

	if (!empty($context['move_board']))
	{
		$context['move_title'] = sprintf($txt['mboards_select_destination'], htmlspecialchars($boards[$context['move_board']]['name']));
		foreach ($cat_tree as $catid => $tree)
		{
			$prev_child_level = 0;
			$prev_board = 0;
			$stack = array();
			foreach ($boardList[$catid] as $boardid)
			{
				if (!isset($context['categories'][$catid]['move_link']))
					$context['categories'][$catid]['move_link'] = array(
						'child_level' => 0,
						'label' => $txt['mboards_order_before'] . ' \'' . htmlspecialchars($boards[$boardid]['name']) . '\'',
						'href' => $scripturl . '?cw1=manageboards;sa=move;src_board=' . $context['move_board'] . ';target_board='. $boardid . ';move_to=before;sesc=' . $context['session_id'],
					);
				
				if (!$context['categories'][$catid]['boards'][$boardid]['move'])
				$context['categories'][$catid]['boards'][$boardid]['move_links'] = array(
					array(
						'child_level' => $boards[$boardid]['level'],
						'label' => $txt['mboards_order_after'] . '\'' . htmlspecialchars($boards[$boardid]['name']) . '\'',
						'href' => $scripturl . '?cw1=manageboards;sa=move;src_board=' . $context['move_board'] . ';target_board='. $boardid . ';move_to=after;sesc=' . $context['session_id'],
					),
					array(
						'child_level' => $boards[$boardid]['level'] + 1,
						'label' => $txt['mboards_order_child_of'] . ' \'' . htmlspecialchars($boards[$boardid]['name']) . '\'',
						'href' => $scripturl . '?cw1=manageboards;sa=move;src_board=' . $context['move_board'] . ';target_board='. $boardid . ';move_to=child;sesc=' . $context['session_id'],
					),
				);

				$difference = $boards[$boardid]['level'] - $prev_child_level;
				if ($difference == 1 && !empty($context['categories'][$catid]['boards'][$prev_board]['move_links']))
					array_push($stack, array_shift($context['categories'][$catid]['boards'][$prev_board]['move_links']));
				elseif ($difference < 0)
				{
					if (empty($context['categories'][$catid]['boards'][$prev_board]['move_links']))
						$context['categories'][$catid]['boards'][$prev_board]['move_links'] = array();
					for ($i = 0; $i < -$difference; $i++)
						array_unshift($context['categories'][$catid]['boards'][$prev_board]['move_links'], array_pop($stack));
				}

				$prev_board = $boardid;
				$prev_child_level = $boards[$boardid]['level'];

			}
			if (!empty($stack) && !empty($context['categories'][$catid]['boards'][$prev_board]['move_links']))
				$context['categories'][$catid]['boards'][$prev_board]['move_links'] = array_merge($stack, $context['categories'][$catid]['boards'][$prev_board]['move_links']);
			elseif (!empty($stack))
				$context['categories'][$catid]['boards'][$prev_board]['move_links'] = $stack;

			if (empty($boardList[$catid]))
				$context['categories'][$catid]['move_link'] = array(
					'child_level' => 0,
					'label' => $txt['mboards_order_before'] . ' \'' . htmlspecialchars($tree['node']['name']) . '\'',
					'href' => $scripturl . '?cw1=manageboards;sa=move;src_board=' . $context['move_board'] . ';target_cat=' . $catid . ';move_to=top;sesc=' . $context['session_id'],
				);
		}
	}

	$context['page_title'] = $txt[41];
	$context['can_manage_permissions'] = allowedTo('manage_permissions');
}

function EditCategory(){}
function EditCategory2(){}
function EditBoard()
{
	global $txt, $db_prefix, $context, $cat_tree, $boards, $boardList, $sourcedir;

	loadTemplate('ManageBoards');
	require_once($sourcedir . '/Subs-Boards.php');
	getBoardTree();

	// ID_BOARD must be a number....
	$_REQUEST['boardid'] = isset($_REQUEST['boardid']) ? (int) $_REQUEST['boardid'] : 0;
	if (!isset($boards[$_REQUEST['boardid']]))
	{
		$_REQUEST['boardid'] = 0;
		$_REQUEST['sa'] = 'newboard';
	}

	if ($_REQUEST['sa'] == 'newboard')
	{
		// Some things that need to be setup for a new board.
		$curBoard = array(
			'memberGroups' => array(0, -1),
			'category' => (int) $_REQUEST['cat']
		);
		$context['board_order'] = array();
		$context['board'] = array(
			'is_new' => true,
			'id' => 0,
			'name' => $txt['mboards_new_board_name'],
			'description' => '',
			'count_posts' => 1,
			'theme' => 0,
			'override_theme' => 0,
			'category' => (int) $_REQUEST['cat'],
			'no_children' => true,
			'permission_mode' => 'normal',
			'thank_you_post_enable' => 0,
			'thank_you_post_enable' => 0,
		);
	}
	else
	{
		// Just some easy shortcuts.
		$curBoard = &$boards[$_REQUEST['boardid']];
		$context['board'] = $boards[$_REQUEST['boardid']];
		$context['board']['name'] = htmlspecialchars($context['board']['name']);
		$context['board']['description'] = htmlspecialchars($context['board']['description']);
		$context['board']['no_children'] = empty($boards[$_REQUEST['boardid']]['tree']['children']);
	}

	// Default membergroups.
	$context['groups'] = array(
		-1 => array(
			'id' => '-1',
			'name' => $txt['parent_guests_only'],
			'checked' => in_array('-1', $curBoard['memberGroups']),
			'is_post_group' => false,
		),
		0 => array(
			'id' => '0',
			'name' => $txt['parent_members_only'],
			'checked' => in_array('0', $curBoard['memberGroups']),
			'is_post_group' => false,
		)
	);

	// Load membergroups.
	$request = db_query("
		SELECT groupName, ID_GROUP, minPosts
		FROM {$db_prefix}membergroups
		WHERE ID_GROUP > 3 OR ID_GROUP = 2
		ORDER BY minPosts, ID_GROUP != 2, groupName", __FILE__, __LINE__);
	while ($row = mysql_fetch_assoc($request))
	{
		if ($_REQUEST['sa'] == 'newboard' && $row['minPosts'] == -1)
			$curBoard['memberGroups'][] = $row['ID_GROUP'];

		$context['groups'][(int) $row['ID_GROUP']] = array(
			'id' => $row['ID_GROUP'],
			'name' => trim($row['groupName']),
			'checked' => in_array($row['ID_GROUP'], $curBoard['memberGroups']),
			'is_post_group' => $row['minPosts'] != -1,
		);
	}
	mysql_free_result($request);

	foreach ($boardList[$curBoard['category']] as $boardid)
	{
		if ($boardid == $_REQUEST['boardid'])
		{
			$context['board_order'][] = array(
				'id' => $boardid,
				'name' => str_repeat('-', $boards[$boardid]['level']) . ' (' . $txt['mboards_current_position'] . ')',
				'children' => $boards[$boardid]['tree']['children'],
				'no_children' => empty($boards[$boardid]['tree']['children']),
				'is_child' => false,
				'selected' => true
			);
		}
		else
		{
			$context['board_order'][] = array(
				'id' => $boardid,
				'name' => str_repeat('-', $boards[$boardid]['level']) . ' ' . $boards[$boardid]['name'],
				'is_child' => empty($_REQUEST['boardid']) ? false : isChildOf($boardid, $_REQUEST['boardid']),
				'selected' => false
			);
		}
	}

	// Are there any places to move child boards to in the case where we are confirming a delete?
	if (!empty($_REQUEST['boardid']))
	{
		$context['can_move_children'] = false;
		$context['children'] = $boards[$_REQUEST['boardid']]['tree']['children'];
		foreach ($context['board_order'] as $board)
			if ($board['is_child'] == false && $board['selected'] == false)
				$context['can_move_children'] = true;
	}

	// Get other available categories.
	$context['categories'] = array();
	foreach ($cat_tree as $catID => $tree)
		$context['categories'][] = array(
			'id' => $catID == $curBoard['category'] ? 0 : $catID,
			'name' => $tree['node']['name'],
			'selected' => $catID == $curBoard['category']
		);
	$request = db_query("
		SELECT ID_THEME AS id, value AS name
		FROM {$db_prefix}themes
		WHERE variable = 'name'", __FILE__, __LINE__);
	$context['themes'] = array();
	while ($row = mysql_fetch_assoc($request))
		$context['themes'][] = $row;
	mysql_free_result($request);

	if (!isset($_REQUEST['delete']))
	{
		$context['sub_template'] = 'modify_board';
		$context['page_title'] = $txt['boardsEdit'];
	}
	else
	{
		$context['sub_template'] = 'confirm_board_delete';
		$context['page_title'] = $txt['mboards_delete_board'];
	}
}

// Make changes to/delete a board.
function EditBoard2()
{
	global $txt, $db_prefix, $sourcedir, $modSettings;

	checkSession();

	require_once($sourcedir . '/Subs-Boards.php');

	$_POST['boardid'] = (int) $_POST['boardid'];

	// Mode: modify aka. don't delete.
	if (isset($_POST['edit']) || isset($_POST['add']))
	{
		$boardOptions = array();

		// Move this board to a new category?
		if (!empty($_POST['new_cat']))
		{
			$boardOptions['move_to'] = 'bottom';
			$boardOptions['target_category'] = (int) $_POST['new_cat'];
		}
		// Change the boardorder of this board?
		elseif (!empty($_POST['placement']) && !empty($_POST['board_order']))
		{
			if (!in_array($_POST['placement'], array('before', 'after', 'child')))
				fatal_lang_error('mangled_post', false);

			$boardOptions['move_to'] = $_POST['placement'];
			$boardOptions['target_board'] =  (int) $_POST['board_order'];
		}

		// Checkboxes....
		$boardOptions['posts_count'] = isset($_POST['count']);
		$boardOptions['thank_you_post_enable'] = isset($_POST['thank_you_post_enable']);
		$boardOptions['thank_you_post_enable'] = isset($_POST['thank_you_post_enable']);
		$boardOptions['override_theme'] = isset($_POST['override_theme']);
		$boardOptions['board_theme'] = (int) $_POST['boardtheme'];
		$boardOptions['access_groups'] = array();
		if (!empty($_POST['groups']))
			foreach ($_POST['groups'] as $group)
				$boardOptions['access_groups'][] = (int) $group;

		// Change '1 & 2' to '1 &amp; 2', but not '&amp;' to '&amp;amp;'...
		$boardOptions['board_name'] = preg_replace('~[&]([^;]{8}|[^;]{0,8}$)~', '&amp;$1', $_POST['board_name']);
		$boardOptions['board_description'] = preg_replace('~[&]([^;]{8}|[^;]{0,8}$)~', '&amp;$1', $_POST['desc']);

		// With permission_enable_by_board disabled you can set some predefined permissions.
		if (empty($modSettings['permission_enable_by_board']))
		{
			$boardOptions['permission_mode'] = (int) $_POST['permission_mode'];
			$boardOptions['inherit_permissions'] = false;
		}

		// Create a new board...
		if (isset($_POST['add']))
		{
			// New boards by default go to the bottom of the category.
			if (empty($_POST['new_cat']))
				$boardOptions['target_category'] = (int) $_POST['cur_cat'];
			if (!isset($boardOptions['move_to']))
				$boardOptions['move_to'] = 'bottom';

			createBoard($boardOptions);
		}

		// ...or update an existing board.
		else
			modifyBoard($_POST['boardid'], $boardOptions);
	}
	elseif (isset($_POST['delete']) && !isset($_POST['confirmation']) && !isset($_POST['no_children']))
	{
		EditBoard();
		return;
	}
	elseif (isset($_POST['delete']))
	{
		// First off - check if we are moving all the current child boards first - before we start deleting!
		if (isset($_POST['delete_action']) && $_POST['delete_action'] == 1)
		{
			if (empty($_POST['board_to']))
				fatal_error($txt['mboards_delete_board_error']);

			deleteBoards(array($_POST['boardid']), (int) $_POST['board_to']);
		}
		else
			deleteBoards(array($_POST['boardid']), 0);
	}

	redirectexit('cw1=manageboards');
}

function ModifyCat(){}

function EditBoardSettings(){global $context, $txt, $db_prefix, $sourcedir, $modSettings;

	$context['page_title'] = $txt[41] . ' - ' . $txt['settings'];

	loadTemplate('ManageBoards');
	$context['sub_template'] = 'modify_general_settings';

	// Needed for the inline permission functions.
	require($sourcedir . '/ManagePermissions.php');

	if (!empty($_POST['save_settings']))
	{
		updateSettings(array(
			'countChildPosts' => empty($_POST['countChildPosts']) ? '0' : '1',
			'recycle_enable' => empty($_POST['recycle_enable']) ? '0' : '1',
			'recycle_board' => (int) $_POST['recycle_board'],
		));

		// Save the permissions.
		save_inline_permissions(array('manage_boards'));
	}

	// Get a list of boards.
	$context['boards'] = array();
	$request = db_query("
		SELECT b.ID_BOARD, b.name AS bName
		FROM {$db_prefix}boards AS b", __FILE__, __LINE__);
	while ($row = mysql_fetch_assoc($request))
		$context['boards'][] = array(
			'id' => $row['ID_BOARD'],
			'name' => $row['bName'],
			'is_recycle' => !empty($modSettings['recycle_board']) && $modSettings['recycle_board'] == $row['ID_BOARD'],

		);
	mysql_free_result($request);

	init_inline_permissions(array('manage_boards'), array(-1));
}

?>
