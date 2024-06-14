<?php
//Pagina de Rodrigo Zaupa (rigo@casitaweb.net)
if (!defined('CasitaWeb!-PorRigo'))die(base64_decode("d3d3LmNhc2l0YXdlYi5uZXQgLSByaWdv"));
function markBoardsRead($boards, $unread = false){}
function MarkRead(){}
function getMsgMemberID(){}
function CollapseCategory(){}
function QuickModeration(){}
function QuickModeration2(){}
function modifyBoard($board_id, &$boardOptions){global $sourcedir, $cat_tree, $boards, $boardList, $modSettings, $db_prefix;global $func;

getBoardTree();

	if (!isset($boards[$board_id]) || (isset($boardOptions['target_board']) && !isset($boards[$boardOptions['target_board']])) || (isset($boardOptions['target_category']) && !isset($cat_tree[$boardOptions['target_category']])))
		fatal_lang_error('smf232');
	$boardUpdates = array();
	if (isset($boardOptions['move_to']))
	{
		if ($boardOptions['move_to'] == 'top')
		{
			$ID_CAT =1;
			$childLevel = 0;
			$ID_PARENT = 0;
			$after = $cat_tree[$ID_CAT]['last_board_order'];
		}
		
		
		elseif ($boardOptions['move_to'] == 'bottom')
		{
			$ID_CAT =1;
			$childLevel = 0;
			$ID_PARENT = 0;
			$after = 0;
			foreach ($cat_tree[$ID_CAT]['children'] as $id_board => $dummy)
				$after = max($after, $boards[$id_board]['order']);
		}

		
		elseif ($boardOptions['move_to'] == 'child')
		{
			$ID_CAT =1;
			$childLevel = $boards[$boardOptions['target_board']]['level'] + 1;
			$ID_PARENT = $boardOptions['target_board'];

			// !!! Change error message.
			if (isChildOf($ID_PARENT, $board_id))
				fatal_error('Unable to make a parent its own child');

			$after = $boards[$boardOptions['target_board']]['order'];

			// Check if there are already children and (if so) get the max board order.
			if (!empty($boards[$ID_PARENT]['tree']['children']) && empty($boardOptions['move_first_child']))
				foreach ($boards[$ID_PARENT]['tree']['children'] as $childBoard_id => $dummy)
					$after = max($after, $boards[$childBoard_id]['order']);
		}

		// Place a board before or after another board, on the same child level.
		elseif (in_array($boardOptions['move_to'], array('before', 'after')))
		{
			$ID_CAT =1;
			$childLevel = $boards[$boardOptions['target_board']]['level'];
			$ID_PARENT = $boards[$boardOptions['target_board']]['parent'];
			$after = $boards[$boardOptions['target_board']]['order'] - ($boardOptions['move_to'] == 'before' ? 1 : 0);
		}

		// Oops...?
		else
			trigger_error('modifyBoard(): The move_to value \'' . $boardOptions['move_to'] . '\' is incorrect', E_USER_ERROR);

		// Get a list of children of this board.
		$childList = array();
		recursiveBoards($childList, $boards[$board_id]['tree']);

		// See if there are changes that affect children.
		$childUpdates = array();
		$levelDiff = $childLevel - $boards[$board_id]['level'];
		if ($levelDiff != 0)
			$childUpdates[] = 'childLevel = childLevel ' . ($levelDiff > 0 ? '+ ' : '') . $levelDiff;


		// Fix the children of this board.
		if (!empty($childList) && !empty($childUpdates))
			db_query("
				UPDATE {$db_prefix}boards
				SET " . implode(',
					', $childUpdates) . "
				WHERE ID_BOARD IN (" . implode(', ', $childList) . ')', __FILE__, __LINE__);

		// Make some room for this spot.
		db_query("
			UPDATE {$db_prefix}boards
			SET boardOrder = boardOrder + " . (1 + count($childList)) . "
			WHERE boardOrder > $after
				AND ID_BOARD != $board_id", __FILE__, __LINE__);


		$boardUpdates[] = 'ID_PARENT = ' . $ID_PARENT;
		$boardUpdates[] = 'childLevel = ' . $childLevel;
		$boardUpdates[] = 'boardOrder = ' . ($after + 1);
	}
	if (isset($boardOptions['posts_count']))
	$boardUpdates[] = 'countPosts = ' . ($boardOptions['posts_count'] ? '0' : '1');
    if (isset($boardOptions['board_theme']))
	$boardUpdates[] = 'ID_THEME = ' . (int) $boardOptions['board_theme'];
	if (isset($boardOptions['override_theme']))
	$boardUpdates[] = 'override_theme = ' . ($boardOptions['override_theme'] ? '1' : '0');
    if (isset($boardOptions['countMoney']))
    $boardUpdates[] = 'countMoney = ' . ($boardOptions['countMoney'] ? '1' : '0');
   	if (isset($boardOptions['access_groups']))
	$boardUpdates[] = 'memberGroups = \'' . implode(',', $boardOptions['access_groups']) . '\'';
	if (isset($boardOptions['board_name']))
	$boardUpdates[] = 'name = \'' . $boardOptions['board_name'] . '\'';
	if (isset($boardOptions['board_description']))
	$boardUpdates[] = 'description = \'' . $boardOptions['board_description'] . '\'';
	if (isset($boardOptions['permission_mode']) && empty($modSettings['permission_enable_by_board']))
	$boardUpdates[] = 'permission_mode = ' . $boardOptions['permission_mode'];
	if (!empty($boardUpdates))
		$request = db_query("
			UPDATE {$db_prefix}boards
			SET
				" . implode(',
				', $boardUpdates) . "
			WHERE ID_BOARD = $board_id
			LIMIT 1", __FILE__, __LINE__);
	if (isset($boardOptions['move_to']))
		reorderBoards();
}

// Create a new board and set it's properties and position.
function createBoard($boardOptions)
{
	global $boards, $db_prefix, $modSettings;

	// Trigger an error if one of the required values is not set.
	if (!isset($boardOptions['board_name']) || trim($boardOptions['board_name']) == '' || !isset($boardOptions['move_to']) || !isset($boardOptions['target_category']))
		trigger_error('createBoard(): One or more of the required options is not set', E_USER_ERROR);

	if (in_array($boardOptions['move_to'], array('child', 'before', 'after')) && !isset($boardOptions['target_board']))
		trigger_error('createBoard(): Target board is not set', E_USER_ERROR);

	// Set every optional value to its default value.
	$boardOptions += array(
		'posts_count' => true,
		'override_theme' => false,'countMoney' => 1,
		'board_theme' => 0,
		'access_groups' => array(),
		'board_description' => '',
		'permission_mode' => 0,
		'inherit_permissions' => true,
	);

	// Insert a board, the settings are dealt with later.
	db_query("
		INSERT INTO {$db_prefix}boards
			( name, description, boardOrder, memberGroups)
		VALUES ( SUBSTRING('$boardOptions[board_name]', 1, 255), '', 0, '-1,0')", __FILE__, __LINE__);
	$board_id = db_insert_id();

	if (empty($board_id))
		return 0;

	// Change the board according to the given specifications.
	modifyBoard($board_id, $boardOptions);

	// Do we want the parent permissions to be inherited?
	if ($boardOptions['inherit_permissions'])
	{
		getBoardTree();

		if (empty($modSettings['permission_enable_by_board']) && !empty($boards[$board_id]['parent']) && empty($boards[$boards[$board_id]['parent']]['use_local_permissions']))
		{
			$request = db_query("
				SELECT permission_mode
				FROM {$db_prefix}boards
				WHERE ID_BOARD = " . (int) $boards[$board_id]['parent'] . "
				LIMIT 1", __FILE__, __LINE__);
			list ($boardOptions['permission_mode']) = mysqli_fetch_row($request);
			mysqli_free_result($request);

			db_query("
				UPDATE {$db_prefix}boards
				SET permission_mode = $boardOptions[permission_mode]
				WHERE ID_BOARD = $board_id", __FILE__, __LINE__);
		}
		elseif (!empty($modSettings['permission_enable_by_board']) && !empty($boards[$board_id]['parent']) && !empty($boards[$boards[$board_id]['parent']]['use_local_permissions']))
		{
			// Select all the parents permissions.
			$request = db_query("
				SELECT ID_GROUP, permission, addDeny
				FROM {$db_prefix}board_permissions
				WHERE ID_BOARD = " . (int) $boards[$board_id]['parent'], __FILE__, __LINE__);
			$boardPerms = array();
			while ($row = mysqli_fetch_assoc($request))
				$boardPerms[] = "$board_id, $row[ID_GROUP], '$row[permission]', $row[addDeny]";
			mysqli_free_result($request);

			// Do the insert!
			db_query("
				INSERT IGNORE INTO {$db_prefix}board_permissions
					(ID_BOARD, ID_GROUP, permission, addDeny)
				VALUES
					(" . implode('), (', $boardPerms) . ")", __FILE__, __LINE__);

			// Update the board.
			db_query("
				UPDATE {$db_prefix}boards
				SET permission_mode = 1
				WHERE ID_BOARD = $board_id", __FILE__, __LINE__);
		}
	}
	return $board_id;
}

function deleteBoards($boards_to_remove, $moveChildrenTo = null)
{
	global $db_prefix, $sourcedir, $boards, $modSettings;

	if (empty($boards_to_remove))
		return;

	getBoardTree();
	if ($moveChildrenTo === null)
	{
		// Get a list of the child boards that will also be removed.
		$child_boards_to_remove = array();
		foreach ($boards_to_remove as $board_to_remove)
			recursiveBoards($child_boards_to_remove, $boards[$board_to_remove]['tree']);

		// Merge the children with their parents.
		if (!empty($child_boards_to_remove))
			$boards_to_remove = array_unique(array_merge($boards_to_remove, $child_boards_to_remove));
	}
	// Move the children to a safe home.
	else
	{
		foreach ($boards_to_remove as $id_board)
		{
			// !!! Separate category?
			if ($moveChildrenTo === 0)
				fixChildren($id_board, 0, 0);
			else
				fixChildren($id_board, $boards[$moveChildrenTo]['level'] + 1, $moveChildrenTo);
		}
	}

	// Delete ALL topics in the selected boards (done first so topics can't be marooned.)
	$request = db_query("
		SELECT ID_TOPIC
		FROM {$db_prefix}topics
		WHERE ID_BOARD IN (" . implode(', ', $boards_to_remove) . ')', __FILE__, __LINE__);
	$topics = array();
	while ($row = mysqli_fetch_assoc($request))
		$topics[] = $row['ID_TOPIC'];
	mysqli_free_result($request);

	require_once($sourcedir . '/RemoveTopic.php');
	removeTopics($topics, false);

    db_query("
		DELETE FROM {$db_prefix}board_permissions
		WHERE ID_BOARD IN (" . implode(', ', $boards_to_remove) . ')', __FILE__, __LINE__);
	db_query("
		DELETE FROM {$db_prefix}boards
		WHERE ID_BOARD IN (" . implode(', ', $boards_to_remove) . ")
		LIMIT " . count($boards_to_remove), __FILE__, __LINE__);

	updateStats('message');
	updateStats('topic');

	if (!empty($modSettings['recycle_board']) && in_array($modSettings['recycle_board'], $boards_to_remove))
	updateSettings(array('recycle_board' => 0, 'recycle_enable' => 0));

	reorderBoards();
}

function modifyCategory($category_id, $catOptions){}
function createCategory($catOptions){}
function deleteCategories($categories, $moveBoardsTo = null){}

// Put all boards in the right order.
function reorderBoards()
{
	global $db_prefix, $cat_tree, $boardList, $boards;

	getBoardTree();

	// Set the board order for each category.
	$boardOrder = 0;
	foreach ($cat_tree as $catID => $dummy)
	{
		foreach ($boardList[$catID] as $boardID)
			if ($boards[$boardID]['order'] != ++$boardOrder)
				db_query("
					UPDATE {$db_prefix}boards
					SET boardOrder = $boardOrder
					WHERE ID_BOARD = $boardID
					LIMIT 1", __FILE__, __LINE__);
	}

	// Sort the records of the boards table on the boardOrder value.
	db_query("
		ALTER TABLE {$db_prefix}boards
		ORDER BY boardOrder", __FILE__, __LINE__);
}


// Fixes the children of a board by setting their childLevels to new values.
function fixChildren($parent, $newLevel, $newParent)
{
	global $db_prefix;

	// Grab all children of $parent...
	$result = db_query("
		SELECT ID_BOARD
		FROM {$db_prefix}boards
		WHERE ID_PARENT = $parent", __FILE__, __LINE__);
	$children = array();
	while ($row = mysqli_fetch_assoc($result))
		$children[] = $row['ID_BOARD'];
	mysqli_free_result($result);

	// ...and set it to a new parent and childLevel.
	db_query("
		UPDATE {$db_prefix}boards
		SET ID_PARENT = $newParent, childLevel = $newLevel
		WHERE ID_PARENT = $parent
		LIMIT " . count($children), __FILE__, __LINE__);

	// Recursively fix the children of the children.
	foreach ($children as $child)
		fixChildren($child, $newLevel + 1, $child);
}

// Load a lot of usefull information regarding the boards and categories.
function getBoardTree()
{
	global $db_prefix, $cat_tree, $boards, $boardList, $txt, $modSettings;

	// Getting all the board and category information you'd ever wanted.
  /* Antigua query
  	$request = db_query("
		SELECT
			b.ID_BOARD, b.ID_PARENT, b.name AS bName, b.description, b.childLevel,
			b.boardOrder, b.countPosts, b.memberGroups, b.ID_THEME, b.override_theme, b.countMoney, b.permission_mode
		FROM {$db_prefix}boards
		ORDER BY b.childLevel, b.boardOrder", __FILE__, __LINE__);

  */
  $request = db_query("
    SELECT
      ID_BOARD, ID_PARENT, name AS bName, description, childLevel,
      boardOrder, countPosts, memberGroups, ID_THEME, override_theme, countMoney, permission_mode
    FROM {$db_prefix}boards
    ORDER BY childLevel, boardOrder", __FILE__, __LINE__);

	$cat_tree = array();
	$boards = array();
	$last_board_order = 0;
	while ($row = mysqli_fetch_assoc($request))
	{
		if (!isset($cat_tree[1]))
		{
			$cat_tree[1] = array(
				'node' => array(
					'id' => 1,
					'name' => '',
					'order' => '',
					'canCollapse' => ''
				),
				'is_first' => empty($cat_tree),
				'last_board_order' => $last_board_order,
				'children' => array()
			);
			$prevBoard = 0;
			$curLevel = 0;
		}

		if (!empty($row['ID_BOARD']))
		{
			if ($row['childLevel'] != $curLevel)
				$prevBoard = 0;

			$boards[$row['ID_BOARD']] = array(
				'id' => $row['ID_BOARD'],
				'parent' => $row['ID_PARENT'],
				'level' => $row['childLevel'],
				'order' => $row['boardOrder'],
				'name' => $row['bName'],
				'memberGroups' => explode(',', $row['memberGroups']),
				'description' => $row['description'],
				'count_posts' => empty($row['countPosts']),
				'theme' => $row['ID_THEME'],
				'override_theme' => $row['override_theme'],'countMoney' => $row['countMoney'],
				'use_local_permissions' => !empty($modSettings['permission_enable_by_board']) && $row['permission_mode'] == 1,
				'permission_mode' => empty($modSettings['permission_enable_by_board']) ? (empty($row['permission_mode']) ? 'normal' : ($row['permission_mode'] == 2 ? 'no_polls' : ($row['permission_mode'] == 3 ? 'reply_only' : 'read_only'))) : 'normal',
				'prev_board' => $prevBoard
			);
			$prevBoard = $row['ID_BOARD'];
			$last_board_order = $row['boardOrder'];

			if (empty($row['childLevel']))
			{		$cat_tree[1]['children'][$row['ID_BOARD']] = array(
					'node' => &$boards[$row['ID_BOARD']],
					'is_first' => empty($cat_tree[1]['children']),
					'children' => array()
				);
				$boards[$row['ID_BOARD']]['tree'] = &$cat_tree[1]['children'][$row['ID_BOARD']];
			}
			else
			{
				// Parent doesn't exist!
				if (!isset($boards[$row['ID_PARENT']]['tree']))
					fatal_lang_error('no_valid_parent', false, array($row['bName']));

				// Wrong childlevel...we can silently fix this...
				if ($boards[$row['ID_PARENT']]['tree']['node']['level'] != $row['childLevel'] - 1)
					db_query("
						UPDATE {$db_prefix}boards
						SET childLevel = " . ($boards[$row['ID_PARENT']]['tree']['node']['level'] + 1) . "
						WHERE ID_BOARD = $row[ID_BOARD]", __FILE__, __LINE__);

				$boards[$row['ID_PARENT']]['tree']['children'][$row['ID_BOARD']] = array(
					'node' => &$boards[$row['ID_BOARD']],
					'is_first' => empty($boards[$row['ID_PARENT']]['tree']['children']),
					'children' => array()
				);
				$boards[$row['ID_BOARD']]['tree'] = &$boards[$row['ID_PARENT']]['tree']['children'][$row['ID_BOARD']];
			}
		}
	}
	mysqli_free_result($request);

	// Get a list of all the boards in each category (using recursion).
	$boardList = array();
	foreach ($cat_tree as $catID => $node)
	{
		$boardList[$catID] = array();
		recursiveBoards($boardList[$catID], $node);
	}
}

// Recursively get a list of boards.
function recursiveBoards(&$_boardList, &$_tree)
{
	if (empty($_tree['children']))
		return;

	foreach ($_tree['children'] as $id => $node)
	{
		$_boardList[] = $id;
		recursiveBoards($_boardList, $node);
	}}

function isChildOf($child, $parent){global $boards;
	if (empty($boards[$child]['parent']))
		return false;
	if ($boards[$child]['parent'] == $parent)
		return true;
	return isChildOf($boards[$child]['parent'], $parent);}
?>
