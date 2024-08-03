<?php
// Página de Rodrigo Zaupa (rigo@casitaweb.net)
if (!defined('CasitaWeb!-PorRigo')) {
  die(base64_decode('d3d3LmNhc2l0YXdlYi5uZXQgLSByaWdv'));
}

function deleteMembers() {}

function deleteMembergroups($groups) {
  global $db_prefix;

  isAllowedTo('manage_membergroups');

  if (!is_array($groups)) {
    $groups = array((int) $groups);
  } else {
    $groups = array_unique($groups);

    // Make sure all groups are integer.
    foreach ($groups as $key => $value) {
      $groups[$key] = (int) $value;
    }
  }

  // Some groups are protected (guests, administrators, moderators, newbies).
  $groups = array_diff($groups, array(-1, 0, 1, 3, 4));

  if (empty($groups)) {
    return false;
  }

  // Remove the membergroups themselves.
  db_query("
    DELETE FROM {$db_prefix}membergroups
    WHERE ID_GROUP IN (" . implode(', ', $groups) . ')
    LIMIT ' . count($groups), __FILE__, __LINE__);

  // Remove the permissions of the membergroups.
  db_query("
    DELETE FROM {$db_prefix}permissions
    WHERE ID_GROUP IN (" . implode(', ', $groups) . ')', __FILE__, __LINE__);
  db_query("
    DELETE FROM {$db_prefix}board_permissions
    WHERE ID_GROUP IN (" . implode(', ', $groups) . ')', __FILE__, __LINE__);

  // Update the primary groups of members.
  db_query("
    UPDATE {$db_prefix}members
    SET ID_GROUP = 0
    WHERE ID_GROUP IN (" . implode(', ', $groups) . ')', __FILE__, __LINE__);

  // No boards can provide access to these membergroups anymore.
  $request = db_query("
    SELECT ID_BOARD, memberGroups
    FROM {$db_prefix}boards
    WHERE FIND_IN_SET(" . implode(', memberGroups) OR FIND_IN_SET(', $groups) . ', memberGroups)', __FILE__, __LINE__);

  $updates = array();

  while ($row = mysqli_fetch_assoc($request)) {
    $updates[$row['memberGroups']][] = $row['ID_BOARD'];
  }

  mysqli_free_result($request);

  foreach ($updates as $memberGroups => $boardArray) {
    db_query("
      UPDATE {$db_prefix}boards
      SET memberGroups = '" . implode(',', array_diff(explode(',', $memberGroups), $groups)) . "'
      WHERE ID_BOARD IN (" . implode(', ', $boardArray) . ')
      LIMIT 1', __FILE__, __LINE__);
  }

  // Recalculate the post groups, as they likely changed.
  updateStats('postgroups');

  // It was a success.
  return true;
}

function removeMembersFromGroups($members, $groups = null) {
  global $db_prefix;

  // You're getting nowhere without this permission.
  isAllowedTo('manage_membergroups');

  // Cleaning the input.
  if (!is_array($members)) {
    $members = array((int) $members);
  } else {
    $members = array_unique($members);

    // Cast the members to integer.
    foreach ($members as $key => $value) {
      $members[$key] = (int) $value;
    }
  }

  // Just incase.
  if (empty($members)) {
    return false;
  }

  $implicitGroups = array(-1, 0, 3);

  $request = db_query("
    SELECT ID_GROUP
    FROM {$db_prefix}membergroups
    WHERE minPosts != -1", __FILE__, __LINE__);

  while ($row = mysqli_fetch_assoc($request)) {
    $implicitGroups[] = $row['ID_GROUP'];
  }

  mysqli_free_result($request);

  // Now get rid of those groups.
  $groups = array_diff($groups, $implicitGroups);

  // If you're not an admin yourself, you can't de-admin others.
  if (!allowedTo('admin_forum')) {
    $groups = array_diff($groups, array(1));
  }

  // Only continue if there are still groups and members left.
  if (empty($groups) || empty($members)) {
    return false;
  }

  // First, reset those who have this as their primary group - this is the easy one.
  db_query("
    UPDATE {$db_prefix}members
    SET ID_GROUP = 0
    WHERE ID_GROUP IN (" . implode(', ', $groups) . ')
    AND ID_MEMBER IN (' . implode(', ', $members) . ')
    LIMIT ' . count($members), __FILE__, __LINE__);

  return true;
}

function addMembersToGroup($members, $group, $type = 'auto') {
  global $db_prefix;

  // Show your licence!
  isAllowedTo('manage_membergroups');

  if (!is_array($members)) {
    $members = array((int) $members);
  } else {
    $members = array_unique($members);

    // Make sure all members are integer.
    foreach ($members as $key => $value) {
      $members[$key] = (int) $value;
    }
  }

  $group = (int) $group;

  // Some groups just don't like explicitly having members.
  $request = db_query("
    SELECT ID_GROUP
    FROM {$db_prefix}membergroups
    WHERE minPosts != -1", __FILE__, __LINE__);

  $implicitGroups = array(-1, 0, 3);

  while ($row = mysqli_fetch_assoc($request)) {
    $implicitGroups[] = $row['ID_GROUP'];
  }

  mysqli_free_result($request);

  // Sorry, you can't join an implicit group.
  if (in_array($group, $implicitGroups) || empty($members)) {
    return false;
  }

  // Only admins can add admins.
  if ($group == 1 && !allowedTo('admin_forum')) {
    return false;
  }

  if ($type == 'only_primary' || $type == 'force_primary') {
    db_query("
      UPDATE {$db_prefix}members
      SET ID_GROUP = $group
      WHERE ID_MEMBER IN (" . implode(', ', $members) . ')' . ($type == 'force_primary' ? '' : "
        AND ID_GROUP = 0
        AND NOT FIND_IN_SET($group, additionalGroups)") . '
      LIMIT ' . count($members), __FILE__, __LINE__);
  } else if ($type == 'auto') {
    // ¿Qué se hace aquí?
  } else {
    // Ack!!?  What happened?
    trigger_error("addMembersToGroup(): Unknown type '" . $type . "'", E_USER_WARNING);
  }

  // Update their postgroup statistics.
  updateStats('postgroups', 'ID_MEMBER IN (' . implode(', ', $members) . ')');

  return true;
}

function groupsAllowedTo($permission, $board_id = null) {
  global $db_prefix, $modSettings, $board_info;

  // Admins are allowed to do anything.
  $membergroups = array(
    'allowed' => array(1),
    'denied' => array(),
  );

  // Assume we're dealing with regular permissions (like profile_view_own).
  if ($board_id === null) {
    $request = db_query("
      SELECT ID_GROUP, addDeny
      FROM {$db_prefix}permissions
      WHERE permission = '$permission'", __FILE__, __LINE__);

    while ($row = mysqli_fetch_assoc($request)) {
      $membergroups[$row['addDeny'] === '1' ? 'allowed' : 'denied'][] = $row['ID_GROUP'];
    }

    mysqli_free_result($request);
  } else {
    if (isset($board_info['id']) && $board_info['id'] == $board_id) {
      $permission_mode = $board_info['permission_mode'] == 'no_polls' ? 2 : ($board_info['permission_mode'] == 'reply_only' ? 3 : ($board_info['permission_mode'] == 'read_only' ? 4 : 0));
    } else if ($board_id !== 0) {
      $request = db_query("
        SELECT permission_mode
        FROM {$db_prefix}boards
        WHERE ID_BOARD = $board_id
        LIMIT 1", __FILE__, __LINE__);

      if (mysqli_num_rows($request) == 0) {
        fatal_lang_error('smf232');
      }

      list($permission_mode) = mysqli_fetch_row($request);
      mysqli_free_result($request);
    }

    $moderator_only = false;

    if ($board_id !== 0 && empty($modSettings['permission_enable_by_board']) && in_array($permission, array('post_reply_own', 'post_reply_any', 'post_new', 'poll_post'))) {
      $max_allowable_mode = 3;

      if ($permission == 'post_new') {
        $max_allowable_mode = 2;
      } else if ($permission == 'poll_post') {
        $max_allowable_mode = 0;
      }

      if ($permission_mode > $max_allowable_mode) {
        $moderator_only = true;
      }
    }

    $request = db_query("
      SELECT bp.ID_GROUP, bp.addDeny
      FROM ({$db_prefix}board_permissions AS bp" . ($moderator_only ? ", {$db_prefix}board_permissions AS modperm" : '') . ")
      WHERE bp.permission = '$permission'" . ($moderator_only ? "
      AND modperm.ID_GROUP = bp.ID_GROUP
      AND modperm.ID_BOARD = 0
      AND modperm.permission = 'moderate_board'
      AND modperm.addDeny = 1" : '') . '
      AND bp.ID_BOARD ' . (empty($modSettings['permission_enable_by_board']) || empty($permission_mode) || $board_id === 0 ? '= 0' : 'IN (0, ' . $board_id . ')'), __FILE__, __LINE__);

    while ($row = mysqli_fetch_assoc($request)) {
      $membergroups[$row['addDeny'] === '1' ? 'allowed' : 'denied'][] = $row['ID_GROUP'];
    }

    mysqli_free_result($request);
  }

  $membergroups['allowed'] = array_diff($membergroups['allowed'], $membergroups['denied']);

  return $membergroups;
}

function membersAllowedTo($permission, $board_id = null) {
  global $db_prefix;

  $membergroups = groupsAllowedTo($permission, $board_id);
  $include_moderators = in_array(3, $membergroups['allowed']) && $board_id !== null;
  $membergroups['allowed'] = array_diff($membergroups['allowed'], array(3));
  $exclude_moderators = in_array(3, $membergroups['denied']) && $board_id !== null;
  $membergroups['denied'] = array_diff($membergroups['denied'], array(3));

  $request = db_query("
    SELECT mem.ID_MEMBER
    FROM {$db_prefix}members AS mem" . ($include_moderators || $exclude_moderators ? "
    LEFT JOIN {$db_prefix}moderators AS mods ON (mods.ID_MEMBER = mem.ID_MEMBER AND ID_BOARD = $board_id)" : '') . '
    WHERE (' . ($include_moderators ? 'mods.ID_MEMBER IS NOT NULL OR ' : '') . 'ID_GROUP IN (' . implode(', ', $membergroups['allowed']) . ') ' . (empty($membergroups['denied']) ? '' : '
    AND NOT (' . ($exclude_moderators ? 'mods.ID_MEMBER IS NOT NULL OR ' : '') . 'ID_GROUP IN (' . implode(', ', $membergroups['denied']) . ')'), __FILE__, __LINE__);

  $members = array();

  while ($row = mysqli_fetch_assoc($request)) {
    $members[] = $row['ID_MEMBER'];
  }

  mysqli_free_result($request);

  return $members;
}

function reattributePosts($memID, $email = false, $post_count = false) {
  global $db_prefix;

  if ($email === false) {
    $request = db_query("
      SELECT emailAddress
      FROM {$db_prefix}members
      WHERE ID_MEMBER = $memID
      LIMIT 1", __FILE__, __LINE__);

    list($email) = mysqli_fetch_row($request);

    mysqli_free_result($request);
  }

  if ($post_count) {
    $request = db_query("
      SELECT COUNT(*)
      FROM ({$db_prefix}messages AS m, {$db_prefix}boards AS b)
      WHERE m.ID_MEMBER = 0
      AND m.posterEmail = '$email'
      AND m.icon != 'recycled'
      AND b.ID_BOARD = m.ID_BOARD
      AND b.countPosts = 1", __FILE__, __LINE__);

    list($messageCount) = mysqli_fetch_row($request);

    mysqli_free_result($request);
    updateMemberData($memID, array('posts' => 'posts + ' . $messageCount));
  }

  db_query("
    UPDATE {$db_prefix}messages
    SET ID_MEMBER = $memID
    WHERE posterEmail = '$email'", __FILE__, __LINE__);

  return db_affected_rows();
}

function BuddyListToggle() {}

?>
