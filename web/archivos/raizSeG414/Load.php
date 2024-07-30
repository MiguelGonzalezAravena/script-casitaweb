<?php
// Página de Rodrigo Zaupa (rigo@casitaweb.net)
if (!defined('CasitaWeb!-PorRigo')) {
  die(base64_decode('d3d3LmNhc2l0YXdlYi5uZXQgLSByaWdv'));
}

function reloadSettings() {
  global $modSettings, $mysql_set_mode, $context, $db_prefix, $boarddir, $func, $txt, $db_character_set;

  if (isset($mysql_set_mode) && $mysql_set_mode === true) {
    db_query("SET sql_mode='', AUTOCOMMIT=1", false, false);
  }

  if (isset($db_character_set) && preg_match('~^\w+$~', $db_character_set) === 1) {
    db_query("SET NAMES $db_character_set", __FILE__, __LINE__);
  }

  if (($modSettings = cache_get_data('modSettings', 90)) == null) {
    $request = db_query("
      SELECT variable, value
      FROM {$db_prefix}settings", false, false);

    $modSettings = array();

    if (!$request) {
      db_fatal_error();
    }

    while ($row = mysqli_fetch_row($request)) {
      $modSettings[$row[0]] = $row[1];
    }

    mysqli_free_result($request);

    if (empty($modSettings['defaultMaxTopics']) || $modSettings['defaultMaxTopics'] <= 0 || $modSettings['defaultMaxTopics'] > 999) {
      $modSettings['defaultMaxTopics'] = 20;
    }

    if (empty($modSettings['defaultMaxMessages']) || $modSettings['defaultMaxMessages'] <= 0 || $modSettings['defaultMaxMessages'] > 999) {
      $modSettings['defaultMaxMessages'] = 15;
    }

    if (empty($modSettings['defaultMaxMembers']) || $modSettings['defaultMaxMembers'] <= 0 || $modSettings['defaultMaxMembers'] > 999) {
      $modSettings['defaultMaxMembers'] = 30;
    }

    if (!empty($modSettings['cache_enable'])) {
      cache_put_data('modSettings', $modSettings, 90);
    }
  }

  $utf8 = (empty($modSettings['global_character_set']) ? $txt['lang_character_set'] : $modSettings['global_character_set']) === 'UTF-8' && (strpos(strtolower(PHP_OS), 'win') === false || @version_compare(PHP_VERSION, '4.2.3') != -1);
  $ent_list = empty($modSettings['disableEntityCheck']) ? '&(#\d{1,7}|quot|amp|lt|gt|nbsp);' : '&(#021|quot|amp|lt|gt|nbsp);';
  $ent_check = empty($modSettings['disableEntityCheck']) ? array("preg_replace('~(&#(\d{1,7}|x[0-9a-fA-F]{1,6});)~e', '\$func[\'entity_fix\'](\'\\2\')', ", ')') : array('', '');

  $space_chars = $utf8 ? (@version_compare(PHP_VERSION, '4.3.3') != -1 ? '\x{A0}\x{2000}-\x{200F}\x{201F}\x{202F}\x{3000}\x{FEFF}' : pack('C*', 0xC2, 0xA0, 0xE2, 0x80, 0x80) . '-' . pack('C*', 0xE2, 0x80, 0x8F, 0xE2, 0x80, 0x9F, 0xE2, 0x80, 0xAF, 0xE2, 0x80, 0x9F, 0xE3, 0x80, 0x80, 0xEF, 0xBB, 0xBF)) : '\xA0';

  /*
   * $func = array(
   *   'entity_fix' => create_function('$string', '
   *     $num = substr($string, 0, 1) === \'x\' ? hexdec(substr($string, 1)) : (int) $string;
   *     return $num < 0x20 || $num > 0x10FFFF || ($num >= 0xD800 && $num <= 0xDFFF) ? \'\' : \'&#\' . $num . \';\';'),
   *   'substr' => create_function('$string, $start, $length = null', '
   *     global $func;
   *     $ent_arr = preg_split(\'~(&#' . (empty($modSettings['disableEntityCheck']) ? '\d{1,7}' : '021') . ';|&quot;|&amp;|&lt;|&gt;|&nbsp;|.)~' . ($utf8 ? 'u' : '') . '\', ' . implode('$string', $ent_check) . ', -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
   *     return $length === null ? implode(\'\', array_slice($ent_arr, $start)) : implode(\'\', array_slice($ent_arr, $start, $length));'),
   *   'strlen' => create_function('$string', '
   *     global $func;
   *     return strlen(preg_replace(\'~' . $ent_list . ($utf8 ? '|.~u' : '~') . '\', \'_\', ' . implode('$string', $ent_check) . '));'),
   *   'strpos' => create_function('$haystack, $needle, $offset = 0', '
   *     global $func;
   *     $haystack_arr = preg_split(\'~(&#' . (empty($modSettings['disableEntityCheck']) ? '\d{1,7}' : '021') . ';|&quot;|&amp;|&lt;|&gt;|&nbsp;|.)~' . ($utf8 ? 'u' : '') . '\', ' . implode('$haystack', $ent_check) . ', -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
   *     $haystack_size = count($haystack_arr);
   *     if (strlen($needle) === 1)
   *     {
   *       $result = array_search($needle, array_slice($haystack_arr, $offset));
   *       return is_int($result) ? $result + $offset : false;
   *     }
   *     else
   *     {
   *       $needle_arr = preg_split(\'~(&#' . (empty($modSettings['disableEntityCheck']) ? '\d{1,7}' : '021') . ';|&quot;|&amp;|&lt;|&gt;|&nbsp;|.)~' . ($utf8 ? 'u' : '') . '\',  ' . implode('$needle', $ent_check) . ', -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
   *       $needle_size = count($needle_arr);
   *
   *       $result = array_search($needle_arr[0], array_slice($haystack_arr, $offset));
   *       while (is_int($result))
   *       {
   *         $offset += $result;
   *         if (array_slice($haystack_arr, $offset, $needle_size) === $needle_arr)
   *           return $offset;
   *         $result = array_search($needle_arr[0], array_slice($haystack_arr, ++$offset));
   *       }
   *       return false;
   *     }'),
   *   'htmlspecialchars' => create_function('$string, $quote_style = ENT_COMPAT, $charset = \'ISO-8859-1\'', '
   *     global $func;
   *     return ' . strtr($ent_check[0], array('&' => '&amp;'))  . 'htmlspecialchars($string, $quote_style, ' . ($utf8 ? '\'UTF-8\'' : '$charset') . ')' . $ent_check[1] . ';'),
   *   'htmltrim' => create_function('$string', '
   *     global $func;
   *     return preg_replace(\'~^([ \t\n\r\x0B\x00' . $space_chars . ']|&nbsp;)+|([ \t\n\r\x0B\x00' . $space_chars . ']|&nbsp;)+$~' . ($utf8 ? 'u' : '') . '\', \'\', ' . implode('$string', $ent_check) . ');'),
   *   'truncate' => create_function('$string, $length', (empty($modSettings['disableEntityCheck']) ? '
   *     global $func;
   *     $string = ' . implode('$string', $ent_check) . ';' : '') . '
   *     preg_match(\'~^(' . $ent_list . '|.){\' . $func[\'strlen\'](substr($string, 0, $length)) . \'}~'.  ($utf8 ? 'u' : '') . '\', $string, $matches);
   *     $string = $matches[0];
   *     while (strlen($string) > $length)
   *       $string = preg_replace(\'~(' . $ent_list . '|.)$~'.  ($utf8 ? 'u' : '') . '\', \'\', $string);
   *     return $string;'),
   *   'strtolower' => $utf8 ? (function_exists('mb_strtolower') ? create_function('$string', '
   *     return mb_strtolower($string, \'UTF-8\');') : create_function('$string', '
   *     global $sourcedir;
   *     require_once($sourcedir . \'/Subs-Charset.php\');
   *     return utf8_strtolower($string);')) : 'strtolower',
   *   'strtoupper' => $utf8 ? (function_exists('mb_strtoupper') ? create_function('$string', '
   *     return mb_strtoupper($string, \'UTF-8\');') : create_function('$string', '
   *     global $sourcedir;
   *     require_once($sourcedir . \'/Subs-Charset.php\');
   *     return utf8_strtoupper($string);')) : 'strtoupper',
   *   'ucfirst' => $utf8 ? create_function('$string', '
   *     global $func;
   *     return $func[\'strtoupper\']($func[\'substr\']($string, 0, 1)) . $func[\'substr\']($string, 1);') : 'ucfirst',
   *   'ucwords' => $utf8 ? (function_exists('mb_convert_case') ? create_function('$string', '
   *     return mb_convert_case($string, MB_CASE_TITLE, \'UTF-8\');') : create_function('$string', '
   *     global $func;
   *     $words = preg_split(\'~([\s\r\n\t]+)~\', $string, -1, PREG_SPLIT_DELIM_CAPTURE);
   *     for ($i = 0, $n = count($words); $i < $n; $i += 2)
   *       $words[$i] = $func[\'ucfirst\']($words[$i]);
   *     return implode(\'\', $words);')) : 'ucwords',
   * );
   */
  $func = [];

  // Setting the timezone is a requirement for some functions in PHP >= 5.1.
  if (isset($modSettings['default_timezone']) && function_exists('date_default_timezone_set'))
    date_default_timezone_set($modSettings['default_timezone']);

  // Check the load averages?
  if (!empty($modSettings['loadavg_enable'])) {
    if (($modSettings['load_average'] = cache_get_data('loadavg', 90)) == null) {
      $modSettings['load_average'] = @file_get_contents('/proc/loadavg');
      if (!empty($modSettings['load_average']) && preg_match('~^([^ ]+?) ([^ ]+?) ([^ ]+)~', $modSettings['load_average'], $matches) != 0) {
        $modSettings['load_average'] = (float) $matches[1];
      } else if (($modSettings['load_average'] = @`uptime`) != null && preg_match('~load average[s]?: (\d+\.\d+), (\d+\.\d+), (\d+\.\d+)~i', $modSettings['load_average'], $matches) != 0) {
        $modSettings['load_average'] = (float) $matches[1];
      } else {
        unset($modSettings['load_average']);
      }

      if (!empty($modSettings['load_average'])) {
        cache_put_data('loadavg', $modSettings['load_average'], 90);
      }
    }

    if (!empty($modSettings['loadavg_forum']) && !empty($modSettings['load_average']) && $modSettings['load_average'] >= $modSettings['loadavg_forum']) {
      db_fatal_error(true);
    }
  }
}

function loadUserSettings() {
  global $modSettings, $user_settings;
  global $ID_MEMBER, $db_prefix, $cookiename, $user_info, $language;

  if (isset($modSettings['integrate_verify_user']) && function_exists($modSettings['integrate_verify_user'])) {
    $ID_MEMBER = (int) call_user_func($modSettings['integrate_verify_user']);
    $already_verified = $ID_MEMBER > 0;
  }
  else
    $ID_MEMBER = 0;

  if (empty($ID_MEMBER) && isset($_COOKIE[$cookiename])) {
    $_COOKIE[$cookiename] = stripslashes($_COOKIE[$cookiename]);
    if (preg_match('~^a:[34]:\{i:0;(i:\d{1,6}|s:[1-8]:"\d{1,8}");i:1;s:(0|40):"([a-fA-F0-9]{40})?";i:2;[id]:\d{1,14};(i:3;i:\d;)?\}$~', $_COOKIE[$cookiename]) == 1) {
      list($ID_MEMBER, $password) = @unserialize($_COOKIE[$cookiename]);
      $ID_MEMBER = !empty($ID_MEMBER) && strlen($password) > 0 ? (int) $ID_MEMBER : 0;
    }
    else
      $ID_MEMBER = 0;
  } elseif (empty($ID_MEMBER) && isset($_SESSION['login_' . $cookiename]) && ($_SESSION['USER_AGENT'] == $_SERVER['HTTP_USER_AGENT'] || !empty($modSettings['disableCheckUA']))) {
    list($ID_MEMBER, $password, $login_span) = @unserialize(stripslashes($_SESSION['login_' . $cookiename]));
    $ID_MEMBER = !empty($ID_MEMBER) && strlen($password) == 40 && $login_span > time() ? (int) $ID_MEMBER : 0;
  }
  if ($ID_MEMBER != 0) {
    if (empty($modSettings['cache_enable']) || $modSettings['cache_enable'] < 2 || ($user_settings = cache_get_data('user_settings-' . $ID_MEMBER, 60)) == null) {
      if (!empty($_GET['accioncw241'])) {
        $cw2 = $_GET['accioncw241'];
      } else {
        $cw2 = 'vacio';
      }
      if ($cw2 == 'monitorUser') {
        db_query("UPDATE {$db_prefix}members SET notificacionMonitor=0 WHERE ID_MEMBER='{$ID_MEMBER}'", __FILE__, __LINE__);
      }

      $request = db_query("
        SELECT mem.*
        FROM {$db_prefix}members AS mem
        WHERE mem.ID_MEMBER = $ID_MEMBER
        LIMIT 1", __FILE__, __LINE__);
      $user_settings = mysqli_fetch_assoc($request);
      mysqli_free_result($request);

      if (!empty($modSettings['cache_enable']) && $modSettings['cache_enable'] >= 2)
        cache_put_data('user_settings-' . $ID_MEMBER, $user_settings, 60);
    }
    if (!empty($user_settings)) {
      if (!empty($already_verified) && $already_verified === true)
        $check = true;
      elseif (strlen($password) == 40)
        $check = sha1($user_settings['passwd'] . $user_settings['passwordSalt']) == $password;
      else
        $check = false;
      $ID_MEMBER = $check && ($user_settings['is_activated'] == 1 || $user_settings['is_activated'] == 11) ? $user_settings['ID_MEMBER'] : 0;
    }
    else
      $ID_MEMBER = 0;
  }
  if ($ID_MEMBER != 0) {
    if (empty($_SESSION['ID_MSG_LAST_VISIT']))
      $_SESSION['ID_MSG_LAST_VISIT'] = $user_settings['ID_MSG_LAST_VISIT'];
    $username = $user_settings['memberName'];
    if (empty($user_settings['additionalGroups']))
      $user_info = array('groups' => array($user_settings['ID_GROUP'], $user_settings['ID_POST_GROUP']));
    else
      $user_info = array('groups' => array_merge(array($user_settings['ID_GROUP'], $user_settings['ID_POST_GROUP']),
        explode(',', $user_settings['additionalGroups'])));
  } else {
    $username = '';
    $user_info = array('groups' => array(-1));
    $user_settings = array();
    if (isset($_COOKIE[$cookiename]))
      $_COOKIE[$cookiename] = '';
  }
  $user_info += array(
    'username' => $username,
    'name' => isset($user_settings['realName']) ? $user_settings['realName'] : '',
    'email' => isset($user_settings['emailAddress']) ? $user_settings['emailAddress'] : '',
    'passwd' => isset($user_settings['passwd']) ? $user_settings['passwd'] : '',
    'is_guest' => $ID_MEMBER == 0,
    'is_admin' => in_array(1, $user_info['groups']),
    'is_mods' => in_array(2, $user_info['groups']),
    'theme' => 0,
    'last_login' => empty($user_settings['lastLogin']) ? 0 : $user_settings['lastLogin'],
    'ip' => $_SERVER['REMOTE_ADDR'],
    'ip2' => $_SERVER['BAN_CHECK_IP'],
    'posts' => empty($user_settings['posts']) ? 0 : $user_settings['posts'],
    'topics' => empty($user_settings['topics']) ? 0 : $user_settings['topics'],
    'time_format' => empty($user_settings['timeFormat']) ? $modSettings['time_format'] : $user_settings['timeFormat'],
    'time_offset' => empty($user_settings['timeOffset']) ? 0 : $user_settings['timeOffset'],
    'avatar' => array(
      'url' => isset($user_settings['avatar']) ? $user_settings['avatar'] : '',
    ),
    'smiley_set' => isset($user_settings['smileySet']) ? $user_settings['smileySet'] : '',
    'messages' => empty($user_settings['instantMessages']) ? 0 : $user_settings['instantMessages'],
    'unread_messages' => empty($user_settings['unreadMessages']) ? 0 : $user_settings['unreadMessages'],
    'total_time_logged_in' => empty($user_settings['totalTimeLoggedIn']) ? 0 : $user_settings['totalTimeLoggedIn'],
    'money' => isset($user_settings['posts']) ? $user_settings['posts'] : '',
    'permissions' => array()
  );
  $user_info['groups'] = array_unique($user_info['groups']);

  if (!empty($modSettings['userLanguage']) && !empty($_REQUEST['language'])) {
    $user_info['language'] = strtr($_REQUEST['language'], './\:', '____');
    $_SESSION['language'] = $user_info['language'];
  }
  elseif (!empty($modSettings['userLanguage']) && !empty($_SESSION['language']))
    $user_info['language'] = strtr($_SESSION['language'], './\:', '____');
  if ($user_info['is_guest'])
    $user_info['query_see_board'] = 'FIND_IN_SET(-1, b.memberGroups)';
  elseif ($user_info['is_admin'])
    $user_info['query_see_board'] = '1';
  else
    $user_info['query_see_board'] = '(FIND_IN_SET(' . implode(', b.memberGroups) OR FIND_IN_SET(', $user_info['groups']) . ', b.memberGroups))';
}

function loadBoard() {}

function loadPermissions() {
  global $user_info, $db_prefix, $board, $board_info, $modSettings;

  $user_info['permissions'] = array();

  if ($user_info['is_admin'])
    return;

  if (!empty($modSettings['cache_enable'])) {
    $cache_groups = $user_info['groups'];
    asort($cache_groups);
    $cache_groups = implode(',', $cache_groups);

    if ($modSettings['cache_enable'] >= 2 && !empty($board) && ($temp = cache_get_data('permissions:' . $cache_groups . ':' . $board, 240)) != null) {
      list($user_info['permissions']) = $temp;
      banPermissions();

      return;
    }
    elseif (($temp = cache_get_data('permissions:' . $cache_groups, 240)) != null)
      list($user_info['permissions'], $removals) = $temp;
  }

  if (empty($user_info['permissions'])) {
    // Get the general permissions.
    $request = db_query("
      SELECT permission, addDeny
      FROM {$db_prefix}permissions
      WHERE ID_GROUP IN (" . implode(', ', $user_info['groups']) . ')', __FILE__, __LINE__);
    $removals = array();
    while ($row = mysqli_fetch_assoc($request)) {
      if (empty($row['addDeny']))
        $removals[] = $row['permission'];
      else
        $user_info['permissions'][] = $row['permission'];
    }
    mysqli_free_result($request);

    if (isset($cache_groups))
      cache_put_data('permissions:' . $cache_groups, array($user_info['permissions'], $removals), 240);
  }

  // Get the board permissions.
  if (!empty($board)) {
    // Make sure the board (if any) has been loaded by loadBoard().
    if (!isset($board_info['use_local_permissions']))
      fatal_lang_error('smf232');

    $request = db_query("
      SELECT permission, addDeny
      FROM {$db_prefix}board_permissions
      WHERE ID_GROUP IN (" . implode(', ', $user_info['groups']) . ')
        AND ID_BOARD = ' . ($board_info['use_local_permissions'] ? $board : '0'), __FILE__, __LINE__);
    while ($row = mysqli_fetch_assoc($request)) {
      if (empty($row['addDeny']))
        $removals[] = $row['permission'];
      else
        $user_info['permissions'][] = $row['permission'];
    }
    mysqli_free_result($request);
  }

  // Remove all the permissions they shouldn't have ;).
  if (!empty($modSettings['permission_enable_deny']))
    $user_info['permissions'] = array_diff($user_info['permissions'], $removals);

  // Remove some board permissions if the board is read-only or reply-only.
  if (empty($modSettings['permission_enable_by_board']) && !empty($board) && $board_info['permission_mode'] != 'normal' && !allowedTo('moderate_board')) {
    $permission_mode = array(
      'read_only' => array(
        'post_new',
        'poll_post',
        'post_reply_own',
        'post_reply_any',
      ),
    );
    $user_info['permissions'] = array_diff($user_info['permissions'], $permission_mode[$board_info['permission_mode']]);
  }

  if (isset($cache_groups) && !empty($board) && $modSettings['cache_enable'] >= 2)
    cache_put_data('permissions:' . $cache_groups . ':' . $board, array($user_info['permissions'], null), 240);

  // Banned?  Watch, don't touch..
  banPermissions();
}

// Loads an array of users' data by ID or memberName.
function loadMemberData($users, $is_name = false, $set = 'normal')
{
  global $user_profile, $db_prefix, $modSettings, $board_info;

  // Can't just look for no users :P.
  if (empty($users))
    return false;

  // Make sure it's an array.
  $users = !is_array($users) ? array($users) : array_unique($users);
  $loaded_ids = array();

  if (!$is_name && !empty($modSettings['cache_enable']) && $modSettings['cache_enable'] == 3) {
    $users = array_values($users);
    for ($i = 0, $n = count($users); $i < $n; $i++) {
      $data = cache_get_data('member_data-' . $set . '-' . $users[$i], 240);
      if ($data == null)
        continue;

      $loaded_ids[] = $data['ID_MEMBER'];
      $user_profile[$data['ID_MEMBER']] = $data;
      unset($users[$i]);
    }
  }

  if ($set == 'normal') {
    $select_columns = "
      IFNULL(lo.logTime, 0) AS isOnline,
      mem.signature, mem.personalText, mem.location, mem.gender, mem.avatar, mem.ID_MEMBER, mem.memberName,
      mem.realName, mem.emailAddress, mem.dateRegistered, mem.websiteTitle, 
      mem.birthdate, mem.memberIP, mem.memberIP2, mem.MSN, mem.topics, mem.lastLogin,
      mem.ID_POST_GROUP, mem.ID_GROUP, mem.timeOffset,
      mg.onlineColor AS member_group_color, IFNULL(mg.groupName, '') AS member_group,
      pg.onlineColor AS post_group_color, IFNULL(pg.groupName, '') AS post_group, mem.is_activated, mem.posts,
      IF(mem.ID_GROUP = 0 OR mg.stars = '', pg.stars, mg.stars) AS stars" . (!empty($modSettings['titlesEnable']) ? ',
      mem.usertitle' : '');
    $select_tables = "
      LEFT JOIN {$db_prefix}log_online AS lo ON (lo.ID_MEMBER = mem.ID_MEMBER)
      LEFT JOIN {$db_prefix}membergroups AS pg ON (pg.ID_GROUP = mem.ID_POST_GROUP)
      LEFT JOIN {$db_prefix}membergroups AS mg ON (mg.ID_GROUP = mem.ID_GROUP)";
  } elseif ($set == 'profile') {
    $select_columns = '
      IFNULL(lo.logTime, 0) AS isOnline,
      mem.signature, mem.personalText, mem.location, mem.gender, mem.avatar, mem.ID_MEMBER, mem.memberName,
      mem.realName, mem.emailAddress, mem.dateRegistered, mem.websiteTitle, 
      mem.birthdate, mem.topics, mem.lastLogin, mem.memberIP, mem.memberIP2, mem.ID_GROUP, 
          mem.timeOffset' . (!empty($modSettings['titlesEnable']) ? ', mem.usertitle' : '') . ",
      mem.is_activated,
      mem.totalTimeLoggedIn, mem.ID_POST_GROUP, lo.url, mg.onlineColor AS member_group_color, IFNULL(mg.groupName, '') AS member_group,
      pg.onlineColor AS post_group_color, IFNULL(pg.groupName, '') AS post_group,
      IF(mem.ID_GROUP = 0 OR mg.stars = '', pg.stars, mg.stars) AS stars, mem.posts, mem.passwordSalt";
    $select_tables = "
      LEFT JOIN {$db_prefix}log_online AS lo ON (lo.ID_MEMBER = mem.ID_MEMBER)
      LEFT JOIN {$db_prefix}membergroups AS pg ON (pg.ID_GROUP = mem.ID_POST_GROUP)
      LEFT JOIN {$db_prefix}membergroups AS mg ON (mg.ID_GROUP = mem.ID_GROUP)";
  } elseif ($set == 'minimal') {
    $select_columns = '
      mem.ID_MEMBER, mem.memberName, mem.realName, mem.emailAddress, mem.dateRegistered,
      mem.topics, mem.lastLogin, mem.memberIP, mem.memberIP2, mem.ID_GROUP';
    $select_tables = '';
  }
  else
    trigger_error("loadMemberData(): Invalid member data set '" . $set . "'", E_USER_WARNING);

  if (!empty($users)) {
    // Load the member's data.
    $request = db_query("
      SELECT$select_columns
      FROM {$db_prefix}members AS mem$select_tables
      WHERE mem." . ($is_name ? 'memberName' : 'ID_MEMBER') . (count($users) == 1 ? " = '" . current($users) . "'" : " IN ('" . implode("', '", $users) . "')"), __FILE__, __LINE__);
    $new_loaded_ids = array();
    while ($row = mysqli_fetch_assoc($request)) {
      $new_loaded_ids[] = $row['ID_MEMBER'];
      $loaded_ids[] = $row['ID_MEMBER'];
      $row['options'] = array();
      $user_profile[$row['ID_MEMBER']] = $row;
    }
    mysqli_free_result($request);
  }

  if (!empty($new_loaded_ids) && $set !== 'minimal') {
    $request = db_query("
      SELECT *
      FROM {$db_prefix}themes
      WHERE ID_MEMBER" . (count($new_loaded_ids) == 1 ? ' = ' . $new_loaded_ids[0] : ' IN (' . implode(', ', $new_loaded_ids) . ')'), __FILE__, __LINE__);
    while ($row = mysqli_fetch_assoc($request))
      $user_profile[$row['ID_MEMBER']]['options'][$row['variable']] = $row['value'];
    mysqli_free_result($request);
  }

  if (!empty($new_loaded_ids) && !empty($modSettings['cache_enable']) && $modSettings['cache_enable'] == 3) {
    for ($i = 0, $n = count($new_loaded_ids); $i < $n; $i++)
      cache_put_data('member_data-' . $set . '-' . $new_loaded_ids[$i], $user_profile[$new_loaded_ids[$i]], 240);
  }

  // Are we loading any moderators?  If so, fix their group data...
  if (!empty($loaded_ids) && !empty($board_info['moderators']) && $set === 'normal' && count($temp_mods = array_intersect($loaded_ids, array_keys($board_info['moderators']))) !== 0) {
    if (($row = cache_get_data('moderator_group_info', 480)) == null) {
      $request = db_query("
        SELECT groupName AS member_group, onlineColor AS member_group_color, stars
        FROM {$db_prefix}membergroups
        WHERE ID_GROUP = 3
        LIMIT 1", __FILE__, __LINE__);
      $row = mysqli_fetch_assoc($request);
      mysqli_free_result($request);

      cache_put_data('moderator_group_info', $row, 480);
    }

    foreach ($temp_mods as $id) {
      // By popular demand, don't show admins or global moderators as moderators.
      if ($user_profile[$id]['ID_GROUP'] != 1 && $user_profile[$id]['ID_GROUP'] != 2)
        $user_profile[$id]['member_group'] = $row['member_group'];

      // If the Moderator group has no color or stars, but their group does... don't overwrite.
      if (!empty($row['stars']))
        $user_profile[$id]['stars'] = $row['stars'];
      if (!empty($row['member_group_color']))
        $user_profile[$id]['member_group_color'] = $row['member_group_color'];
    }
  }

  return empty($loaded_ids) ? false : $loaded_ids;
}

function loadMemberContext($user) {
  global $memberContext, $user_profile, $txt, $scripturl, $user_info;
  global $context, $modSettings, $tranfer1, $ID_MEMBER, $board_info, $settings;
  global $db_prefix, $func, $boardurl;
  static $dataLoaded = array();

  if (isset($dataLoaded[$user]))
    return true;
  if ($user == 0)
    return false;
  if (!isset($user_profile[$user])) {
    trigger_error('' . $user . '', E_USER_WARNING);
    return false;
  }

  // Well, it's loaded now anyhow.
  $dataLoaded[$user] = true;
  $profile = $user_profile[$user];

  // Censor everything.
  censorText($profile['signature']);
  censorText($profile['personalText']);
  censorText($profile['location']);

  // Set things up to be used before hand.
  $gendertxt = $profile['gender'] == 2 ? $txt[239] : ($profile['gender'] == 1 ? $txt[238] : '');
  $profile['signature'] = str_replace(array("\n", "\r"), array('<br />', ''), $profile['signature']);
  $profile['signature'] = parse_bbc($profile['signature'], true, 'sig' . $profile['ID_MEMBER']);

  $profile['is_online'] = (!empty($profile['showOnline']) || allowedTo('moderate_forum')) && $profile['isOnline'] > 0;
  $profile['stars'] = empty($profile['stars']) ? array('', '') : explode('#', $profile['stars']);

  // If we're always html resizing, assume it's too large.
  if ($modSettings['avatar_action_too_large'] == 'option_html_resize' || $modSettings['avatar_action_too_large'] == 'option_js_resize') {
    $avatar_width = !empty($modSettings['avatar_max_width_external']) ? ' width="' . $modSettings['avatar_max_width_external'] . '"' : '';
    $avatar_height = !empty($modSettings['avatar_max_height_external']) ? ' height="' . $modSettings['avatar_max_height_external'] . '"' : '';
  } else {
    $avatar_width = '';
    $avatar_height = '';
  }

  // What a monstrous array...
  $memberContext[$user] = array(
    'username' => &$profile['memberName'],
    'name' => &$profile['realName'],
    'id' => &$profile['ID_MEMBER'],
    'is_guest' => $profile['ID_MEMBER'] == 0,
    'title' => !empty($modSettings['titlesEnable']) ? $profile['usertitle'] : '',
    'href' => $boardurl . '/perfi/' . $profile['realName'],
    'link' => '<a href="/perfi/' . $profile['realName'] . '" title="' . $txt[92] . ' ' . $profile['realName'] . '">' . $profile['realName'] . '</a>',
    'email' => &$profile['emailAddress'],
    'hide_email' => $profile['emailAddress'] == '' || (!empty($modSettings['guest_hideContacts']) && $user_info['is_guest']) || (!empty($profile['hideEmail']) && !empty($modSettings['allow_hideEmail']) && !allowedTo('moderate_forum') && $ID_MEMBER != $profile['ID_MEMBER']),
    'email_public' => (empty($profile['hideEmail']) || empty($modSettings['allow_hideEmail'])) && (empty($modSettings['guest_hideContacts']) || !$user_info['is_guest']),
    'registered' => empty($profile['dateRegistered']) ? $txt[470] : timeformat($profile['dateRegistered']),
    'registered_timestamp' => empty($profile['dateRegistered']) ? 0 : forum_time(true, $profile['dateRegistered']),
    'blurb' => &$profile['personalText'],
    'gender' => array(
      'name' => $gendertxt,
      'image' => !empty($profile['gender']) ? '<img src="' . $tranfer1 . '/' . ($profile['gender'] == 1 ? 'Male' : 'Female') . '.gif" alt="" title="' . $gendertxt . '" border="0" />' : ''
    ),
    'website' => array(
      'title' => &$profile['websiteTitle'],
      'url' => &$profile['websiteUrl'],
    ),
    'birth_date' => empty($profile['birthdate']) || $profile['birthdate'] === '0001-01-01' ? '0000-00-00' : (substr($profile['birthdate'], 0, 4) === '0004' ? '0000' . substr($profile['birthdate'], 4) : $profile['birthdate']),
    'signature' => &$profile['signature'],
    'location' => &$profile['location'],
    array('name' => '', 'href' => '', 'link' => '', 'link_text' => ''),
    'real_posts' => $profile['posts'],
    'posts' => $profile['posts'] > 100000 ? $txt[683] : ($profile['posts'] == 1337 ? 'leet' : comma_format($profile['posts'])),
    'real_topics' => $profile['topics'],
    'topics' => $profile['topics'] > 100000 ? $txt[683] : ($profile['topics'] == 1337 ? 'leet' : comma_format($profile['topics'])),
    'avatar' => array(
      'name' => &$profile['avatar'],
      'image' => (stristr($profile['avatar'], 'http://') ? '<img src="' . $profile['avatar'] . '"' . $avatar_width . $avatar_height . ' alt="" class="avatar" border="0" />' : '<img src="' . $modSettings['avatar_url'] . '/' . htmlspecialchars($profile['avatar']) . '" alt="" class="avatar" border="0" />'),
      'href' => (stristr($profile['avatar'], 'http://') ? $profile['avatar'] : $modSettings['avatar_url'] . '/' . $profile['avatar']),
      'url' => $profile['avatar'] == '' ? '' : (stristr($profile['avatar'], 'http://') ? $profile['avatar'] : $modSettings['avatar_url'] . '/' . $profile['avatar'])
    ),
    'last_login' => empty($profile['lastLogin']) ? $txt['never'] : timeformat($profile['lastLogin']),
    'last_login_timestamp' => empty($profile['lastLogin']) ? 0 : forum_time(0, $profile['lastLogin']),
    'ip' => htmlspecialchars($profile['memberIP']),
    'ip2' => htmlspecialchars($profile['memberIP2']),
    'online' => array(
      'is_online' => $profile['is_online'],
      'text' => &$txt[$profile['is_online'] ? 'online2' : 'online3'],
      'href' => '/mensajes/a/' . $profile['realName'],
      'link' => '<a href="/mensajes/a/' . $profile['realName'] . '">' . $txt[$profile['is_online'] ? 'online2' : 'online3'] . '</a>',
    ),
    'is_activated' => isset($profile['is_activated']) ? $profile['is_activated'] : 1,
    'is_banned' => isset($profile['is_activated']) ? $profile['is_activated'] >= 10 : 0,
    'options' => $profile['options'],
    'is_guest' => false,
    'group' => $profile['member_group'],
    'group_color' => $profile['member_group_color'],
    'group_id' => $profile['ID_GROUP'],
    'post_group' => $profile['post_group'],
    'post_group_color' => $profile['post_group_color'],
    'group_stars' => '<img src="' . $tranfer1 . '/' . $profile['stars'][1] . '" alt="" title="' . $profile['post_group'] . '" border="0" />',
    'local_time' => timeformat(time() + ($profile['timeOffset'] - $user_info['time_offset']) * 3600, false),
  );

  return true;
}

function loadTheme($ID_THEME = 0, $initialize = true) {
  global $ID_MEMBER, $user_info, $board_info, $sc;
  global $db_prefix, $txt, $boardurl, $scripturl, $mbname, $user_settings, $modSettings;
  global $context, $settings, $options;

  $ID_THEME = 0;
  $member = empty($ID_MEMBER) ? -1 : $ID_MEMBER;

  // $settings ='';
  $options = '';

  // TO-DO: ¿Esto está bien?

  /*
   * if (isset($settings)) {
   *   $settings['theme_id'] = 1;
   *   $settings['actual_theme_url'] ='';
   *   $settings['actual_theme_dir'] ='';
   * }
   */

  if (!$initialize)
    return;
  if (isset($detected_url) && $detected_url != $boardurl) {
    if (!empty($modSettings['forum_alias_urls'])) {
      $aliases = explode(',', $modSettings['forum_alias_urls']);

      foreach ($aliases as $alias) {
        if ($detected_url == trim($alias) || strtr($detected_url, array('http://' => '', 'https://' => '')) == trim($alias))
          $do_fix = true;
      }
    }

    if (empty($do_fix) && strtr($detected_url, array('://' => '://www.')) == $boardurl && (empty($_GET) || count($_GET) == 1)) {
      if (empty($_GET))
        redirectexit('www');
      else {
        list($k, $v) = each($_GET);

        if ($k != 'www')
          redirectexit('www;' . $k . '=' . $v);
      }
    }

    if (strtr($detected_url, array('https://' => 'http://')) == $boardurl)
      $do_fix = true;
    if (!empty($do_fix) || preg_match('~^http[s]://[\d\.:]+($|/)~', $detected_url) == 1) {
      $oldurl = $boardurl;

      $boardurl = $detected_url;
      $scripturl = strtr($scripturl, array($oldurl => $boardurl));
      $_SERVER['REQUEST_URL'] = strtr($_SERVER['REQUEST_URL'], array($oldurl => $boardurl));
      $settings['theme_url'] = strtr($settings['theme_url'], array($oldurl => $boardurl));
      $settings['default_theme_url'] = strtr($settings['default_theme_url'], array($oldurl => $boardurl));
      $settings['actual_theme_url'] = strtr($settings['actual_theme_url'], array($oldurl => $boardurl));
      $settings['images_url'] = strtr($settings['images_url'], array($oldurl => $boardurl));
      $settings['default_images_url'] = strtr($settings['default_images_url'], array($oldurl => $boardurl));
      $settings['actual_images_url'] = strtr($settings['actual_images_url'], array($oldurl => $boardurl));
      $modSettings['smileys_url'] = strtr($modSettings['smileys_url'], array($oldurl => $boardurl));
      $modSettings['avatar_url'] = strtr($modSettings['avatar_url'], array($oldurl => $boardurl));
    }
  }
  $context['user'] = array(
    'id' => &$ID_MEMBER,
    'is_logged' => !$user_info['is_guest'],
    'is_guest' => &$user_info['is_guest'],
    'is_admin' => &$user_info['is_admin'],
    'is_mod' => false,
    'username' => &$user_info['username'],
    'language' => &$user_info['language'],
    'email' => &$user_info['email']
  );

  if ($context['user']['is_guest'])
    $context['user']['name'] = &$txt[28];
  else
    $context['user']['name'] = &$user_info['name'];

  $user_info['smiley_set'] = (!in_array($user_info['smiley_set'], explode(',', $modSettings['smiley_sets_known'])) && $user_info['smiley_set'] != 'none') || empty($modSettings['smiley_sets_enable']) ? (!empty($settings['smiley_sets_default']) ? $settings['smiley_sets_default'] : $modSettings['smiley_sets_default']) : $user_info['smiley_set'];
  $context['user']['smiley_set'] = &$user_info['smiley_set'];
  $context['session_id'] = &$sc;
  $context['forum_name'] = &$mbname;
  $context['current_action'] = isset($_GET['accioncw241']) ? $_GET['accioncw241'] : null;
  $context['current_subaction'] = isset($_REQUEST['sa']) ? $_REQUEST['sa'] : null;

  if (isset($modSettings['load_average']))
    $context['load_average'] = $modSettings['load_average'];

  $context['server'] = array(
    'is_iis' => isset($_SERVER['SERVER_SOFTWARE']) && strpos($_SERVER['SERVER_SOFTWARE'], 'Microsoft-IIS') !== false,
    'is_apache' => isset($_SERVER['SERVER_SOFTWARE']) && strpos($_SERVER['SERVER_SOFTWARE'], 'Apache') !== false,
    'is_cgi' => isset($_SERVER['SERVER_SOFTWARE']) && strpos(php_sapi_name(), 'cgi') !== false,
    'is_windows' => stristr(PHP_OS, 'WIN') !== false,
    'iso_case_folding' => ord(strtolower(chr(138))) === 154,
    'complex_preg_chars' => @version_compare(PHP_VERSION, '4.3.3') != -1,
  );

  $context['server']['needs_login_fix'] = $context['server']['is_cgi'];

  if ((isset($_GET['accioncw241']) && in_array($_GET['accioncw241'], array('login', 'login2', 'register'))) || !$context['user']['is_guest'])
    $context['browser']['possibly_robot'] = false;
  $txt = array();
  $simpleActions = array('printpage');
  if (WIRELESS) {
    $context['template_layers'] = array(WIRELESS_PROTOCOL);
    loadTemplate('Wireless');
    loadLanguage('Wireless');
    loadLanguage('index');
  } elseif (!empty($_GET['accioncw241']) && in_array($_GET['accioncw241'], $simpleActions)) {
    loadLanguage('index');
    $context['template_layers'] = array();
  } else {
    if (isset($settings['theme_templates']))
      $templates = explode(',', $settings['theme_templates']);
    else
      $templates = array('index');

    if (isset($settings['theme_layers']))
      $context['template_layers'] = explode(',', $settings['theme_layers']);
    else
      $context['template_layers'] = array('main');

    foreach ($templates as $template) {
      loadTemplate($template);
      loadLanguage($template, '', false);
    }
  }

  loadLanguage('Modifications', '', false);
  loadSubTemplate('init', 'ignore');

  if (isset($settings['use_default_images']) && $settings['use_default_images'] == 'always') {
    $settings['theme_url'] = $settings['default_theme_url'];
    $settings['images_url'] = $settings['default_images_url'];
    $settings['theme_dir'] = $settings['default_theme_dir'];
  }

  $context['character_set'] = empty($modSettings['global_character_set']) ? $txt['lang_character_set'] : $modSettings['global_character_set'];
  $context['utf8'] = $context['character_set'] === 'UTF-8' && (strpos(strtolower(PHP_OS), 'win') === false || @version_compare(PHP_VERSION, '4.2.3') != -1);
  $context['right_to_left'] = !empty($txt['lang_rtl']);
  $context['tabindex'] = 1;

  // Actualizar puntos
  if (empty($user_info['is_guest'])) {
    if (empty($user_settings['ID_GROUP'])) {
      $grupoi = $user_settings['ID_POST_GROUP'];
    } else {
      $grupoi = 0;
    }

    if (empty($user_settings['puntos_dia']) && $grupoi != 4) {
      if (isset($user_settings['TiempoPuntos']) && faltan($user_settings['TiempoPuntos']) == 'Recargando') {
        if (empty($user_settings['ID_GROUP'])) {
          $grupob = $user_settings['ID_POST_GROUP'];
        } else {
          $grupob = $user_settings['ID_GROUP'];
        }

        $request = db_query("
        SELECT CantidadDePuntos
        FROM {$db_prefix}membergroups
        WHERE ID_GROUP = $grupob
        LIMIT 1", __FILE__, __LINE__);

        while ($row = mysqli_fetch_assoc($request)) {
          $cp = $row['CantidadDePuntos'];
        }

        if (empty($grupoi)) {
          db_query("
          UPDATE {$db_prefix}members
          SET puntos_dia = '$cp'
          WHERE ID_MEMBER = '{$user_settings['ID_MEMBER']}'
          LIMIT 1", __FILE__, __LINE__);
        } else {
          db_query("
          UPDATE {$db_prefix}members
          SET puntos_dia = '$cp'
          WHERE ID_MEMBER = '{$user_settings['ID_MEMBER']}'
          LIMIT 1", __FILE__, __LINE__);
        }
      }
    }
  }
  // FIN ACTUALIZAR PUNTOS

  // DATOS POST
  $idtop = isset($_GET['post']) ? (int) $_GET['post'] : '';

  if ($idtop > 0) {
    $request = db_query("
    SELECT
      p.ID_BOARD, p.ID_TOPIC, p.puntos, p.subject, p.body, p.ID_MEMBER, p.hiddenOption, p.anuncio, b.ID_BOARD, b.name, b.description,
      p.posterTime, p.smileysEnabled, p.visitas, SUBSTRING(p.body, 1, 300) AS ACsgg, p.posterName, p.eliminado
    FROM {$db_prefix}messages AS p
    INNER JOIN {$db_prefix}boards as b ON p.ID_BOARD = b.ID_BOARD
    AND p.ID_TOPIC = $idtop
    LIMIT 1", __FILE__, __LINE__);

    while ($row = mysqli_fetch_assoc($request)) {
      $context['puntos-post'] = $row['puntos'];
      $context['titulo'] = censorText($row['subject']);
      $context['user_ID'] = $row['ID_MEMBER'];
      $context['id-post'] = $row['ID_TOPIC'];
      $context['contenido'] = $row['body'];
      $context['num_views'] = $row['visitas'];
      $context['oculto'] = $row['hiddenOption'];
      $context['anuncio'] = $row['anuncio'];
      $context['CsTNidO'] = parse_bbc($row['ACsgg']);
      $context['id_cat'] = $row['ID_BOARD'];
      $context['name_cat'] = $row['name'];
      $context['link_cat'] = $row['description'];
      $context['fecha'] = $row['posterTime'];
      $context['posterName'] = $row['posterName'];
      $context['is_locked'] = $row['smileysEnabled'];
      $context['eliminado'] = $row['eliminado'];
    }

    mysqli_free_result($request);
  }

  $context['id-post'] = isset($context['id-post']) ? $context['id-post'] : '';
}

function loadTemplate($template_name, $fatal = true) {
  global $context, $settings, $themedir, $scripturl, $boarddir, $db_show_debug;

  $settings['default_template'] = true;
  template_include($themedir . '/' . $template_name . '-casitaweb.php', true);
  $template_name .= ' (' . basename($themedir) . ')';

  if ($db_show_debug === true) {
    $context['debug']['templates'][] = $template_name;
  }
}

function loadSubTemplate($sub_template_name, $fatal = false) {
  global $context, $settings, $options, $txt, $db_show_debug;

  if ($db_show_debug === true)
    $context['debug']['sub_templates'][] = $sub_template_name;
  $theme_function = 'template_' . $sub_template_name;

  if (function_exists($theme_function))
    $theme_function();
  elseif ($fatal === false)
    die();
  elseif ($fatal !== 'ignore')
    die();

  if (allowedTo('admin_forum') && isset($_REQUEST['debug']) && !in_array($sub_template_name, array('init', 'main_below')) && ob_get_length() > 0 && !isset($_REQUEST['xml'])) {
  }
}

function loadLanguage($template_name, $lang = '', $fatal = true) {
  global $boarddir, $boardurl, $user_info, $language_dir, $language, $settings, $context, $txt, $db_show_debug;
  static $already_loaded = array();

  if (empty($lang))
    $lang = $user_info['language'];

  $attempts = array(
    array($boarddir . '/web/archivos', $template_name, $lang, 'http://casitaweb.net/web/archivos'),
    array($boarddir . '/web/archivos', $template_name, $language, 'http://casitaweb.net/web/archivos'),
  );

  if (isset($settings['base_theme_dir'])) {
    $attempts[] = array($boarddir . '/web/archivos', $template_name, $lang, 'http://casitaweb.net/web/archivos');
    $attempts[] = array($boarddir . '/web/archivos', $template_name, $language, 'http://casitaweb.net/web/archivos');
  }
  $attempts[] = array($boarddir . '/web/archivos', $template_name, $lang, 'http://casitaweb.net/web/archivos');
  $attempts[] = array($boarddir . '/web/archivos', $template_name, $language, 'http://casitaweb.net/web/archivos');
  foreach ($attempts as $k => $file)
    if (file_exists($file[0] . '/idioma/' . $file[1] . '.' . $file[2] . '.php')) {
      $language_dir = $file[0] . '/idioma';
      $lang = $file[2];
      $language_url = $file[3];
      template_include($file[0] . '/idioma/' . $file[1] . '.' . $file[2] . '.php');

      break;
    }
  if (!isset($language_url)) {
    if ($fatal)
      log_error(sprintf($txt['theme_language_error'], $template_name . '.' . $lang));
    return false;
  }

  if ($db_show_debug === true)
    $context['debug']['language_files'][] = $template_name . '.' . $lang . ' (' . basename($language_url) . ')';
  return $lang;
}

function getBoardParents($id_parent) {}

// Replace all vulgar words with respective proper words. (substring or whole words..)
function &censorText(&$text, $force = false) {
  global $modSettings, $options, $settings, $txt;
  static $censor_vulgar = null, $censor_proper = null;

  if ((!empty($options['show_no_censored']) && $settings['allow_no_censored'] && !$force) || empty($modSettings['censor_vulgar'])) {
    return $text;
  }

  // If they haven't yet been loaded, load them.
  if ($censor_vulgar == null) {
    $censor_vulgar = explode("\n", $modSettings['censor_vulgar']);
    $censor_proper = explode("\n", $modSettings['censor_proper']);

    // Quote them for use in regular expressions.
    for ($i = 0, $n = count($censor_vulgar); $i < $n; $i++) {
      $censor_vulgar[$i] = strtr(preg_quote($censor_vulgar[$i], '/'), array('\\\\\\*' => '[*]', '\*' => '[^\s]*?', '&' => '&amp;'));
      $censor_vulgar[$i] = (empty($modSettings['censorWholeWord']) ? '/' . $censor_vulgar[$i] . '/' : '/(?<=^|\W)' . $censor_vulgar[$i] . '(?=$|\W)/') . (empty($modSettings['censorIgnoreCase']) ? '' : 'i') . ((empty($modSettings['global_character_set']) ? $txt['lang_character_set'] : $modSettings['global_character_set']) === 'UTF-8' ? 'u' : '');

      if (strpos($censor_vulgar[$i], "'") !== false) {
        $censor_proper[count($censor_vulgar)] = $censor_proper[$i];
        $censor_vulgar[count($censor_vulgar)] = strtr($censor_vulgar[$i], array("'" => '&#039;'));
      }
    }
  }

  // Censoring isn't so very complicated :P.
  $text = preg_replace($censor_vulgar, $censor_proper, $text);
  return $text;
}

function loadJumpTo() {}

function template_include($filename, $once = false) {
  global $context, $mtitle, $modSettings, $boardurl, $txt;
  static $templates = array();

  ini_set('track_errors', '1');

  if ($once && in_array($filename, $templates)) {
    return;
  } else {
    $templates[] = $filename;
  }

  $file_found = file_exists($filename);

  if ($once && $file_found) {
    require_once ($filename);
  } else if ($file_found) {
    require ($filename);
  }

  if ($file_found !== true) {
    echo '
      <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
      <html xmlns="http://www.w3.org/1999/xhtml"' . (!empty($context['right_to_left']) ? ' dir="rtl"' : '') . '><head>';

    if (isset($context['character_set'])) {
      echo '<meta http-equiv="Content-Type" content="text/html; charset=' . $context['character_set'] . '" />';
      echo '</head><body>' . $mtitle . '</body></html>';
    }

    die;
  }
}

function loadSession() {
  global $HTTP_SESSION_VARS, $modSettings, $boardurl, $sc;

  // Attempt to change a few PHP settings.
  @ini_set('session.use_cookies', true);
  @ini_set('session.use_only_cookies', false);
  @ini_set('url_rewriter.tags', '');
  @ini_set('session.use_trans_sid', false);
  @ini_set('arg_separator.output', '&amp;');

  if (!empty($modSettings['globalCookies'])) {
    $parsed_url = parse_url($boardurl);

    if (preg_match('~^\d{1,3}(\.\d{1,3}){3}$~', $parsed_url['host']) == 0 && preg_match('~(?:[^\.]+\.)?([^\.]{2,}\..+)\z~i', $parsed_url['host'], $parts) == 1) {
      @ini_set('session.cookie_domain', '.' . $parts[1]);
    }
  }
  // !!! Set the session cookie path?

  // If it's already been started... probably best to skip this.
  if ((@ini_get('session.auto_start') == 1 && !empty($modSettings['databaseSession_enable'])) || session_id() == '') {
    // Attempt to end the already-started session.
    if (@ini_get('session.auto_start') == 1)
      @session_write_close();

    // This is here to stop people from using bad junky PHPSESSIDs.
    if (isset($_REQUEST[session_name()]) && preg_match('~^[A-Za-z0-9]{16,32}$~', $_REQUEST[session_name()]) == 0 && !isset($_COOKIE[session_name()])) {
      $_REQUEST[session_name()] = md5(md5('smf_sess_' . time()) . rand());
      $_GET[session_name()] = md5(md5('smf_sess_' . time()) . rand());
      $_POST[session_name()] = md5(md5('smf_sess_' . time()) . rand());
    }

    // Use database sessions? (they don't work in 4.1.x!)
    if (!empty($modSettings['databaseSession_enable']) && @version_compare(PHP_VERSION, '4.2.0') != -1)
      session_set_save_handler('sessionOpen', 'sessionClose', 'sessionRead', 'sessionWrite', 'sessionDestroy', 'sessionGC');
    elseif (@ini_get('session.gc_maxlifetime') <= 1440 && !empty($modSettings['databaseSession_lifetime']))
      @ini_set('session.gc_maxlifetime', max($modSettings['databaseSession_lifetime'], 60));

    // Use cache setting sessions?
    if (empty($modSettings['databaseSession_enable']) && !empty($modSettings['cache_enable']) && php_sapi_name() != 'cli') {
      if (function_exists('mmcache_set_session_handlers'))
        mmcache_set_session_handlers();
      elseif (function_exists('eaccelerator_set_session_handlers'))
        eaccelerator_set_session_handlers();
    }

    // TO-DO: ¿Cuándo volver a activar?
    // session_start();

    // Change it so the cache settings are a little looser than default.
    if (!empty($modSettings['databaseSession_loose']))
      header('Cache-Control: private');
  }

  // While PHP 4.1.x should use $_SESSION, it seems to need this to do it right.
  if (@version_compare(PHP_VERSION, '4.2.0') == -1)
    $HTTP_SESSION_VARS['php_412_bugfix'] = true;

  // Set the randomly generated code.
  if (!isset($_SESSION['rand_code']))
    $_SESSION['rand_code'] = md5(session_id() . rand());
  $sc = $_SESSION['rand_code'];
}

function sessionOpen($save_path, $session_name) {
  return true;
}

function sessionClose() {
  return true;
}

function sessionRead($session_id) {
  global $db_prefix;

  if (preg_match('~^[A-Za-z0-9]{16,32}$~', $session_id) == 0)
    return false;

  // Look for it in the database.
  $result = db_query("
    SELECT data
    FROM {$db_prefix}sessions
    WHERE session_id = '" . addslashes($session_id) . "'
    LIMIT 1", __FILE__, __LINE__);
  list($sess_data) = mysqli_fetch_row($result);
  mysqli_free_result($result);

  return $sess_data;
}

function sessionWrite($session_id, $data) {
  global $db_prefix;

  if (preg_match('~^[A-Za-z0-9]{16,32}$~', $session_id) == 0)
    return false;

  // First try to update an existing row...
  $result = db_query("
    UPDATE {$db_prefix}sessions
    SET data = '" . addslashes($data) . "', last_update = " . time() . "
    WHERE session_id = '" . addslashes($session_id) . "'
    LIMIT 1", __FILE__, __LINE__);

  // If that didn't work, try inserting a new one.
  if (db_affected_rows() == 0)
    $result = db_query("
      INSERT IGNORE INTO {$db_prefix}sessions
        (session_id, data, last_update)
      VALUES ('" . addslashes($session_id) . "', '" . addslashes($data) . "', " . time() . ')', __FILE__, __LINE__);

  return $result;
}

function sessionDestroy($session_id) {
  global $db_prefix;

  if (preg_match('~^[A-Za-z0-9]{16,32}$~', $session_id) == 0)
    return false;

  return db_query("
    DELETE FROM {$db_prefix}sessions
    WHERE session_id = '" . addslashes($session_id) . "'
    LIMIT 1", __FILE__, __LINE__);
}

function sessionGC($max_lifetime) {
  global $db_prefix, $modSettings;

  if (!empty($modSettings['databaseSession_lifetime']) && ($max_lifetime <= 1440 || $modSettings['databaseSession_lifetime'] > $max_lifetime))
    $max_lifetime = max($modSettings['databaseSession_lifetime'], 60);

  return db_query("
    DELETE FROM {$db_prefix}sessions
    WHERE last_update < " . (time() - $max_lifetime), __FILE__, __LINE__);
}

function cache_put_data($key, $value, $ttl = 120) {
  global $boardurl, $sourcedir, $modSettings, $memcached;
  global $cache_hits, $cache_count, $db_show_debug;

  if (empty($modSettings['cache_enable']) && !empty($modSettings))
    return;

  $cache_count = isset($cache_count) ? $cache_count + 1 : 1;
  if (isset($db_show_debug) && $db_show_debug === true) {
    $cache_hits[$cache_count] = array('k' => $key, 'd' => 'put', 's' => $value === null ? 0 : strlen(serialize($value)));
    $st = microtime();
  }
  $key = md5($boardurl . filemtime($sourcedir . '/Load.php')) . '-SMF-' . $key;
  $value = $value === null ? null : serialize($value);
  if (isset($modSettings['cache_memcached']) && trim($modSettings['cache_memcached']) != '') {
    if (!is_resource($memcached))
      get_memcached_server();
    if (!$memcached)
      return;
    if (!fwrite($memcached, 'set ' . $key . ' 0 ' . $ttl . ' ' . strlen($value) . "\r\n" . $value . "\r\n")) {
      $memcached = fclose($memcached);
      return;
    }

    fread($memcached, 128);
  } elseif (function_exists('eaccelerator_put')) {
    if (rand(0, 10) == 1)
      eaccelerator_gc();

    if ($value === null)
      @eaccelerator_rm($key);
    else
      eaccelerator_put($key, $value, $ttl);
  }
  // Turck MMCache?
  elseif (function_exists('mmcache_put')) {
    if (rand(0, 10) == 1)
      mmcache_gc();

    if ($value === null)
      @mmcache_rm($key);
    else
      mmcache_put($key, $value, $ttl);
  } elseif (function_exists('apc_store')) {
    if ($value === null)
      apc_delete($key . 'smf');
    else
      apc_store($key . 'smf', $value, $ttl);
  }
  elseif (function_exists('output_cache_put'))
    output_cache_put($key, $value);
  if (isset($db_show_debug) && $db_show_debug === true)
    $cache_hits[$cache_count]['t'] = array_sum(explode(' ', microtime())) - array_sum(explode(' ', $st));
}

function cache_get_data($key, $ttl = 120) {
  global $boardurl, $sourcedir, $modSettings, $memcached;
  global $cache_hits, $cache_count, $db_show_debug;

  if (empty($modSettings['cache_enable']) && !empty($modSettings))
    return;

  $cache_count = isset($cache_count) ? $cache_count + 1 : 1;
  if (isset($db_show_debug) && $db_show_debug === true) {
    $cache_hits[$cache_count] = array('k' => $key, 'd' => 'get');
    $st = microtime();
  }

  $key = md5($boardurl . filemtime($sourcedir . '/Load.php')) . '-SMF-' . $key;

  if (isset($modSettings['cache_memcached']) && trim($modSettings['cache_memcached']) != '') {
    // Grab the memcached server.
    if (!is_resource($memcached) && $memcached !== '0')
      get_memcached_server();
    if (!$memcached) {
      // '0' means ignore me for the rest of this page view.
      $memcached = '0';
      return null;
    }

    if (!fwrite($memcached, 'get ' . $key . "\r\n")) {
      $memcached = fclose($memcached);
      return null;
    }

    $response = fgets($memcached);
    if (substr($response, 0, 3) != 'END' && substr($response, 0, 5) != 'VALUE') {
      // Bad response, junk time.
      $memcached = fclose($memcached);
      return null;
    }

    if (substr($response, 0, 5) == 'VALUE' && preg_match('~(\d+)$~', trim($response), $match) != 0)
      $value = substr(fread($memcached, $match[1] + 2), 0, -2);

    fread($memcached, 5);
  }
  // Again, eAccelerator.
  elseif (function_exists('eaccelerator_get'))
    $value = eaccelerator_get($key);
  // The older, but ever-stable, Turck MMCache...
  elseif (function_exists('mmcache_get'))
    $value = mmcache_get($key);
  // This is the free APC from PECL.
  elseif (function_exists('apc_fetch'))
    $value = apc_fetch($key . 'smf');
  // Zend's pricey stuff.
  elseif (function_exists('output_cache_get'))
    $value = output_cache_get($key, $ttl);

  if (isset($db_show_debug) && $db_show_debug === true) {
    $cache_hits[$cache_count]['t'] = array_sum(explode(' ', microtime())) - array_sum(explode(' ', $st));
    $cache_hits[$cache_count]['s'] = isset($value) ? strlen($value) : 0;
  }

  if (empty($value))
    return null;
  // If it's broke, it's broke... so give up on it.
  else
    return @unserialize($value);
}

function get_memcached_server($level = 3) {
  global $modSettings, $memcached, $db_persist;

  $servers = explode(',', $modSettings['cache_memcached']);
  $server = explode(':', trim($servers[array_rand($servers)]));
  $level = min(count($servers), $level);
  if (empty($db_persist))
    $memcached = @fsockopen($server[0], empty($server[1]) ? 11211 : $server[1], $err, $err, 0.15);
  else
    $memcached = @pfsockopen($server[0], empty($server[1]) ? 11211 : $server[1], $err, $err, 0.15);
  if (!$memcached && $level > 0)
    get_memcached_server($level - 1);
  elseif ($memcached) {
    @socket_set_timeout($memcached, 1);
    @set_file_buffer($memcached, 0);
  }
}

?>