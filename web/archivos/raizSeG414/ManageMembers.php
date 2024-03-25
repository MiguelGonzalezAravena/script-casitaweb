<?php
//Pagina de Rodrigo Zaupa (rigo@casitaweb.net)
if (!defined('CasitaWeb!-PorRigo'))die(base64_decode("d3d3LmNhc2l0YXdlYi5uZXQgLSByaWdv"));
function ViewMembers(){global $txt, $scripturl, $user_info, $context, $modSettings, $db_prefix,$urlSep;
if(!$user_info['is_admin']){die();}
	$subActions = array(
		'all' => array('ViewMemberlist', 'moderate_forum'),
		'browse' => array('MembersAwaitingActivation', 'moderate_forum'),
		'search' => array('SearchMembers', 'moderate_forum'),
		'query' => array('ViewMemberlist', 'moderate_forum'),
	);
	$_REQUEST['sa'] = isset($_REQUEST['sa']) && isset($subActions[$_REQUEST['sa']]) ? $_REQUEST['sa'] : 'all';
	isAllowedTo($subActions[$_REQUEST['sa']][1]);

	adminIndex('view_members');
	loadLanguage('ManageMembers');
	loadTemplate('ManageMembers');
    

	$context['admin_tabs'] = array(
		'title' => $txt[9],
		'help' => 'view_members',
		'description' => $txt[11],
		'tabs' => array(),
	);
	if (allowedTo('moderate_forum'))
	{
		$context['admin_tabs']['tabs'] = array(
			'viewmembers' => array(
				'title' => $txt[303],
				'description' => $txt[11],
				'href' => '/index.php?'.$urlSep.'=viewmembers',
				'is_selected' => $_REQUEST['sa'] == 'all',
			),
			'search' => array(
				'title' => $txt['mlist_search'],
				'description' => $txt[11],
				'href' => '/index.php?'.$urlSep.'=viewmembers;sa=search',
				'is_selected' => $_REQUEST['sa'] == 'search' || $_REQUEST['sa'] == 'query',
			),
		);
	}
	$subActions[$_REQUEST['sa']][0]();
}

// View all members.
function ViewMemberlist()
{
	global $txt, $scripturl, $db_prefix, $context, $modSettings, $sourcedir;

	$context['sub_action'] = $_REQUEST['sa'];
    
	if ($context['sub_action'] == 'query' && empty($_REQUEST['params']))
	{
		// Retrieving the membergroups and postgroups.
		$context['membergroups'] = array(
			array(
				'id' => 0,
				'name' => $txt['membergroups_members'],
				'can_be_additional' => false
			)
		);
		$context['postgroups'] = array();

		$request = db_query("
			SELECT ID_GROUP, groupName, minPosts
			FROM {$db_prefix}membergroups
			WHERE ID_GROUP != 3
			ORDER BY minPosts, IF(ID_GROUP < 4, ID_GROUP, 4), groupName", __FILE__, __LINE__);
		while ($row = mysql_fetch_assoc($request))
		{
			if ($row['minPosts'] == -1)
				$context['membergroups'][] = array(
					'id' => $row['ID_GROUP'],
					'name' => $row['groupName'],
					'can_be_additional' => true
				);
			else
				$context['postgroups'][] = array(
					'id' => $row['ID_GROUP'],
					'name' => $row['groupName']
				);
		}
		mysql_free_result($request);

		$params = array(
			'mem_id' => array(
				'db_fields' => array('ID_MEMBER'),
				'type' => 'int',
				'range' => true
			),
			'age' => array(
				'db_fields' => array('birthdate'),
				'type' => 'age',
				'range' => true
			),
			'posts' => array(
				'db_fields' => array('posts'),
				'type' => 'int',
				'range' => true
			),
			'reg_date' => array(
				'db_fields' => array('dateRegistered'),
				'type' => 'date',
				'range' => true
			),
			'last_online' => array(
				'db_fields' => array('lastLogin'),
				'type' => 'date',
				'range' => true
			),
			'gender' => array(
				'db_fields' => array('gender'),
				'type' => 'checkbox',
				'values' => array('0', '1', '2'),
			),
			'activated' => array(
				'db_fields' => array('IF(is_activated IN (1, 11), 1, 0)'),
				'type' => 'checkbox',
				'values' => array('0', '1'),
			),
			'membername' => array(
				'db_fields' => array('memberName', 'realName'),
				'type' => 'string'
			),
			'email' => array(
				'db_fields' => array('emailAddress'),
				'type' => 'string'
			),
			'website' => array(
				'db_fields' => array('websiteTitle'),
				'type' => 'string'
			),
			'location' => array(
				'db_fields' => array('location'),
				'type' => 'string'
			),
			'ip' => array(
				'db_fields' => array('memberIP'),
				'type' => 'string'
			),
			'messenger' => array(
				'db_fields' => array('ICQ', 'AIM', 'YIM', 'MSN'),
				'type' => 'string'
			)
		);
		$range_trans = array(
			'--' => '<',
			'-' => '<=',
			'=' => '=',
			'+' => '>=',
			'++' => '>'
		);

		// !!! Validate a little more.

		// Loop through every field of the form.
		$query_parts = array();
		foreach ($params as $param_name => $param_info)
		{
			// Not filled in?
			if (!isset($_POST[$param_name]) || $_POST[$param_name] == '')
				continue;

			// Make sure numeric values are really numeric.
			if (in_array($param_info['type'], array('int', 'age')))
				$_POST[$param_name] = (int) $_POST[$param_name];
			// Date values have to match the specified format.
			elseif ($param_info['type'] == 'date')
			{
				// Check if this date format is valid.
				if (preg_match('/^\d{4}-\d{1,2}-\d{1,2}$/', $_POST[$param_name]) == 0)
					continue;

				$_POST[$param_name] = strtotime($_POST[$param_name]);
			}

			// Those values that are in some kind of range (<, <=, =, >=, >).
			if (!empty($param_info['range']))
			{
				// Default to '=', just in case...
				if (empty($range_trans[$_POST['types'][$param_name]]))
					$_POST['types'][$param_name] = '=';

				// Handle special case 'age'.
				if ($param_info['type'] == 'age')
				{
					// All people that were born between $lowerlimit and $upperlimit are currently the specified age.
					$datearray = getdate(forum_time());
					$upperlimit = sprintf('%04d-%02d-%02d', $datearray['year'] - $_POST[$param_name], $datearray['mon'], $datearray['mday']);
					$lowerlimit = sprintf('%04d-%02d-%02d', $datearray['year'] - $_POST[$param_name] - 1, $datearray['mon'], $datearray['mday']);
					if (in_array($_POST['types'][$param_name], array('-', '--', '=')))
						$query_parts[] = "{$param_info['db_fields'][0]} > '" . ($_POST['types'][$param_name] == '--' ? $upperlimit : $lowerlimit) . "'";
					if (in_array($_POST['types'][$param_name], array('+', '++', '=')))
					{
						$query_parts[] = "{$param_info['db_fields'][0]} <= '" . ($_POST['types'][$param_name] == '++' ? $lowerlimit : $upperlimit) . "'";

						// Make sure that members that didn't set their birth year are not queried.
						$query_parts[] = "{$param_info['db_fields'][0]} > '0000-12-31'";
					}
				}
				elseif ($param_info['type'] == 'date' && $_POST['types'][$param_name] == '=')
					$query_parts[] = $param_info['db_fields'][0] . ' > ' . $_POST[$param_name] . ' AND ' . $param_info['db_fields'][0] . ' < ' . ($_POST[$param_name] + 86400);
				else
					$query_parts[] = $param_info['db_fields'][0] . ' ' . $range_trans[$_POST['types'][$param_name]] . ' ' . $_POST[$param_name];
			}
			// Checkboxes.
			elseif ($param_info['type'] == 'checkbox')
			{
				// Each checkbox or no checkbox at all is checked -> ignore.
				if (!is_array($_POST[$param_name]) || count($_POST[$param_name]) == 0 || count($_POST[$param_name]) == count($param_info['values']))
					continue;

				$query_parts[] = "{$param_info['db_fields'][0]} IN ('" . implode("', '", $_POST[$param_name]) . "')";
			}
			else
			{
				// Replace the wildcard characters ('*' and '?') into MySQL ones.
				$_POST[$param_name] = strtolower(addslashes(strtr($_POST[$param_name], array('%' => '\%', '_' => '\_', '*' => '%', '?' => '_'))));

				$query_parts[] = '(' . implode(" LIKE '%{$_POST[$param_name]}%' OR ", $param_info['db_fields']) . " LIKE '%{$_POST[$param_name]}%')";
			}
		}

		// Set up the membergroup query part.
		$mg_query_parts = array();

		// Primary membergroups, but only if at least was was not selected.
		if (!empty($_POST['membergroups'][1]) && count($context['membergroups']) != count($_POST['membergroups'][1]))
			$mg_query_parts[] = "ID_GROUP IN (" . implode(", ", $_POST['membergroups'][1]) . ")";

		// Additional membergroups (these are only relevant if not all primary groups where selected!).
		if (!empty($_POST['membergroups'][2]) && (empty($_POST['membergroups'][1]) || count($context['membergroups']) != count($_POST['membergroups'][1])))
			foreach ($_POST['membergroups'][2] as $mg)
				$mg_query_parts[] = "FIND_IN_SET(" . (int) $mg . ", additionalGroups)";

		// Combine the one or two membergroup parts into one query part linked with an OR.
		if (!empty($mg_query_parts))
			$query_parts[] = '(' . implode(' OR ', $mg_query_parts) . ')';

		// Get all selected post count related membergroups.
		if (!empty($_POST['postgroups']) && count($_POST['postgroups']) != count($context['postgroups']))
			$query_parts[] = "ID_POST_GROUP IN (" . implode(", ", $_POST['postgroups']) . ")";

		// Construct the where part of the query.
		$where = empty($query_parts) ? '1' : implode('
			AND ', $query_parts);
	}
	// If the query information was already packed in the URL, decode it.
	// !!! Change this.
	elseif ($context['sub_action'] == 'query')
		$where = base64_decode(strtr($_REQUEST['params'], array(' ' => '+')));

	// Construct the additional URL part with the query info in it.
	$context['params_url'] = $context['sub_action'] == 'query' ? ';sa=query;params=' . base64_encode($where) : '';

	// Get the title and sub template ready..
	$context['page_title'] = $txt[9];
	$context['sub_template'] = 'view_members';

	// Determine whether to show the 'delete members' checkboxes.
	$context['can_delete_members'] = allowedTo('profile_remove_any');

	// All the columns they have to pick from...
	$context['columns'] = array(
		'ID_MEMBER' => array('label' => $txt['member_id']),
		'memberName' => array('label' => $txt[35]),
		'emailAddress' => array('label' => $txt['email_address']),
		'memberIP' => array('label' => $txt['ip_address']),
		'lastLogin' => array('label' => $txt['viewmembers_online']),
		'posts' => array('label' => $txt[26])
	);

	// Default sort column to 'memberName' if the current one is unknown or not set.
	if (!isset($_REQUEST['sort']) || !isset($context['columns'][$_REQUEST['sort']]))
		$_REQUEST['sort'] = 'memberName';
	$context['sort_by'] = $_REQUEST['sort'];
	$context['sort_direction'] = !isset($_REQUEST['desc']) ? 'down' : 'up';

	// Calculate the number of results.
	if (empty($where) or $where == '1')
		$num_members = $modSettings['totalMembers'];
	else
	{
		$request = db_query("
			SELECT COUNT(*)
			FROM {$db_prefix}members
			WHERE $where", __FILE__, __LINE__);
		list ($num_members) = mysql_fetch_row($request);
		mysql_free_result($request);
	}

	// Construct the page links.
	$context['page_index'] = constructPageIndex($scripturl . '?action=viewmembers' . $context['params_url'] . ';sort=' . $_REQUEST['sort'] . (isset($_REQUEST['desc']) ? ';desc' : ''), $_REQUEST['start'], $num_members, $modSettings['defaultMaxMembers']);
	$context['start'] = (int) $_REQUEST['start'];

	$request = db_query("
		SELECT ID_MEMBER, memberName, realName, emailAddress, memberIP, lastLogin, topics, is_activated
		FROM {$db_prefix}members" . ($context['sub_action'] == 'query' && !empty($where) ? "
		WHERE $where" : '') . "
		ORDER BY $_REQUEST[sort]" . (!isset($_REQUEST['desc']) ? '' : ' DESC') . "
		LIMIT $context[start], $modSettings[defaultMaxMembers]", __FILE__, __LINE__);
	while ($row = mysql_fetch_assoc($request))
	{
		// Calculate number of days since last online.
		if (empty($row['lastLogin']))
			$difference = $txt['never'];
		else
		{
			// Today or some time ago?
			$difference = jeffsdatediff($row['lastLogin']);
			if (empty($difference))
				$difference = $txt['viewmembers_today'];
			elseif ($difference == 1)
				$difference .= ' ' . $txt['viewmembers_day_ago'];
			else
				$difference .= ' ' . $txt['viewmembers_days_ago'];
		}
		if ($row['is_activated'] % 10 != 1)
			$difference = '<i title="' . $txt['not_activated'] . '">' . $difference . '</i>';

		$context['members'][] = array(
			'id' => $row['ID_MEMBER'],
			'username' => $row['memberName'],
			'name' => $row['realName'],
			'email' => $row['emailAddress'],
			'ip' => $row['memberIP'],
			'last_active' => $difference,
			'is_activated' => $row['is_activated'] % 10 == 1,
			'posts' => $row['topics'],
			'href' => '/perfil/'.$row['realName'],
			'link' => '<a href="/perfil/'.$row['realName'].'">'.$row['realName'].'</a>'
		);
	}
	mysql_free_result($request);
}

function SearchMembers()
{
	global $db_prefix, $context, $txt;

	// Get a list of all the membergroups and postgroups that can be selected.
	$context['membergroups'] = array(
		array(
			'id' => 0,
			'name' => $txt['membergroups_members'],
			'can_be_additional' => false
		)
	);
	$context['postgroups'] = array();

	$request = db_query("
		SELECT ID_GROUP, groupName, minPosts
		FROM {$db_prefix}membergroups
		WHERE ID_GROUP != 3
		ORDER BY minPosts, IF(ID_GROUP < 4, ID_GROUP, 4), groupName", __FILE__, __LINE__);
	while ($row = mysql_fetch_assoc($request))
	{
		if ($row['minPosts'] == -1)
			$context['membergroups'][] = array(
				'id' => $row['ID_GROUP'],
				'name' => $row['groupName'],
				'can_be_additional' => true
			);
		else
			$context['postgroups'][] = array(
				'id' => $row['ID_GROUP'],
				'name' => $row['groupName']
			);
	}
	mysql_free_result($request);

	$context['page_title'] = $txt[9];
	$context['sub_template'] = 'search_members';
}

function MembersAwaitingActivation(){}

function AdminApprove(){}
function jeffsdatediff($old){$forumTime = forum_time();$sinceMidnight = date('H', $forumTime) * 60 * 60 + date('i', $forumTime) * 60 + date('s', $forumTime);$dis = time() - $old; if($dis < $sinceMidnight)return 0;else$dis -= $sinceMidnight;return ceil($dis / (24 * 60 * 60));}

?>