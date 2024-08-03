<?php
// Página de Rodrigo Zaupa (rigo@casitaweb.net)
if (!defined('CasitaWeb!-PorRigo')) {
  die(base64_decode('d3d3LmNhc2l0YXdlYi5uZXQgLSByaWdv'));
}

function db_query($db_string, $file, $line) {
  global $db_cache, $db_count, $db_connection, $errordb, $db_show_debug, $modSettings;

  $db_count = !isset($db_count) ? 1 : $db_count + 1;

  if (isset($db_show_debug) && $db_show_debug === true) {
    if (!isset($db_cache)) {
      $db_cache = array();
    }

    if (!empty($_SESSION['debug_redirect'])) {
      $db_cache = array_merge($_SESSION['debug_redirect'], $db_cache);
      $db_count = count($db_cache) + 1;
      $_SESSION['debug_redirect'] = array();
    }

    $db_cache[$db_count]['q'] = $db_string;
    $db_cache[$db_count]['f'] = $file;
    $db_cache[$db_count]['l'] = $line;
    $st = microtime();
  }

  if (empty($modSettings['disableQueryCheck'])) {
    $clean = '';
    $old_pos = 0;
    $pos = -1;

    while (true) {
      $pos = strpos($db_string, "'", $pos + 1);
      if ($pos === false) {
        break;
      }

      $clean .= substr($db_string, $old_pos, $pos - $old_pos);

      while (true) {
        $pos1 = strpos($db_string, "'", $pos + 1);
        $pos2 = strpos($db_string, '\\', $pos + 1);

        if ($pos1 === false) {
          break;
        } else if ($pos2 == false || $pos2 > $pos1) {
          $pos = $pos1;
          break;
        }

        $pos = $pos2 + 1;
      }

      $clean .= ' %s ';
      $old_pos = $pos + 1;
    }

    $clean .= substr($db_string, $old_pos);
    $clean = trim(strtolower(preg_replace(array('~\s+~s', '~/\*!40001 SQL_NO_CACHE \*/~', '~/\*!40000 USE INDEX \([A-Za-z\_]+?\) \*/~'), array(' ', '', ''), $clean)));

    if (strpos($clean, 'union') !== false && preg_match('~(^|[^a-z])union($|[^[a-z])~s', $clean) != 0) {
      $fail = true;
    } else if (strpos($clean, '/*') > 2 || strpos($clean, '--') !== false || strpos($clean, ';') !== false) {
      $fail = true;
    } else if (strpos($clean, 'sleep') !== false && preg_match('~(^|[^a-z])sleep($|[^[a-z])~s', $clean) != 0) {
      $fail = true;
    } else if (strpos($clean, 'benchmark') !== false && preg_match('~(^|[^a-z])benchmark($|[^[a-z])~s', $clean) != 0) {
      $fail = true;
    } else if (preg_match('~\([^)]*?select~s', $clean) != 0) {
      $fail = true;
    }

    if (!empty($fail)) {
      die($errordb);
    }
  }

  $ret = mysqli_query($db_connection, $db_string);

  if ($ret === false && $file !== false) {
    $ret = db_error($db_string, $file, $line);
  }

  if (isset($db_show_debug) && $db_show_debug === true) {
    $db_cache[$db_count]['t'] = array_sum(explode(' ', microtime())) - array_sum(explode(' ', $st));
  }

  return $ret;
}

function db_affected_rows() {
  global $db_connection;

  return mysqli_affected_rows($db_connection);
}

function db_insert_id() {
  global $db_connection;

  return mysqli_insert_id($db_connection);
}

function updateStats($type, $parameter1 = null, $parameter2 = null) {
  global $db_prefix, $sourcedir, $modSettings;

  switch ($type) {
    case 'member':
      $changes = array(
        'memberlist_updated' => time(),
      );
      if (!empty($modSettings['registration_method']) && $modSettings['registration_method'] == 2) {
        $result = db_query("SELECT COUNT(*), MAX(ID_MEMBER)
        FROM {$db_prefix}members
        WHERE is_activated = 1", __FILE__, __LINE__);
        list($changes['totalMembers'], $changes['latestMember']) = mysqli_fetch_row($result);
        mysqli_free_result($result);
        $result = db_query("
        SELECT realName
        FROM {$db_prefix}members
        WHERE ID_MEMBER = " . (int) $changes['latestMember'] . '
        LIMIT 1', __FILE__, __LINE__);
        list($changes['latestRealName']) = mysqli_fetch_row($result);
        mysqli_free_result($result);
        $result = db_query("
        SELECT COUNT(*)
        FROM {$db_prefix}members
        WHERE is_activated IN (3, 4)", __FILE__, __LINE__);
        list($changes['unapprovedMembers']) = mysqli_fetch_row($result);
        mysqli_free_result($result);
      } elseif ($parameter1 !== null && $parameter1 !== false) {
        $changes['latestMember'] = $parameter1;
        $changes['latestRealName'] = $parameter2;

        updateSettings(array('totalMembers' => true), true);
      } elseif ($parameter1 !== false) {
        $result = db_query("
        SELECT COUNT(*), MAX(ID_MEMBER)
        FROM {$db_prefix}members", __FILE__, __LINE__);
        list($changes['totalMembers'], $changes['latestMember']) = mysqli_fetch_row($result);
        mysqli_free_result($result);

        $result = db_query("
        SELECT realName
        FROM {$db_prefix}members
        WHERE ID_MEMBER = " . (int) $changes['latestMember'] . '
        LIMIT 1', __FILE__, __LINE__);
        list($changes['latestRealName']) = mysqli_fetch_row($result);
        mysqli_free_result($result);
      }

      updateSettings($changes);
      break;

    case 'message':
      if ($parameter1 === true && $parameter2 !== null)
        updateSettings(array('totalMessages' => true, 'maxMsgID' => $parameter2), true);
      else {
        // SUM and MAX on a smaller table is better for InnoDB tables.
        $result = db_query("
        SELECT SUM(numPosts) AS totalMessages, MAX(ID_LAST_MSG) AS maxMsgID
        FROM {$db_prefix}boards", __FILE__, __LINE__);
        $row = mysqli_fetch_assoc($result);
        mysqli_free_result($result);

        updateSettings(array(
          'totalMessages' => $row['totalMessages'],
          'maxMsgID' => $row['maxMsgID'] === null ? 0 : $row['maxMsgID']
        ));
      }
      break;

    case 'subject':
    case 'topic':
      if ($parameter1 === true)
        updateSettings(array('totalTopics' => true), true);
      else {
        $result = db_query("
        SELECT SUM(numTopics) AS totalTopics
        FROM {$db_prefix}boards" . (!empty($modSettings['recycle_enable']) && $modSettings['recycle_board'] > 0 ? "
        WHERE ID_BOARD != $modSettings[recycle_board]" : ''), __FILE__, __LINE__);
        $row = mysqli_fetch_assoc($result);
        mysqli_free_result($result);

        updateSettings(array('totalTopics' => $row['totalTopics']));
      }
      break;

    case 'postgroups':
      if ($parameter2 !== null && !in_array('posts', $parameter2))
        return;

      if (($postgroups = cache_get_data('updateStats:postgroups', 360)) == null) {
        $request = db_query("
        SELECT ID_GROUP, minPosts
        FROM {$db_prefix}membergroups
        WHERE minPosts != -1", __FILE__, __LINE__);
        $postgroups = array();
        while ($row = mysqli_fetch_assoc($request))
          $postgroups[$row['ID_GROUP']] = $row['minPosts'];
        mysqli_free_result($request);
        arsort($postgroups);

        cache_put_data('updateStats:postgroups', $postgroups, 360);
      }

      if (empty($postgroups))
        return;

      $conditions = '';
      foreach ($postgroups as $id => $minPosts) {
        $conditions .= '
          WHEN posts >= ' . $minPosts . (!empty($lastMin) ? ' AND posts <= ' . $lastMin : '') . ' THEN ' . $id;
        $lastMin = $minPosts;
      }

      // A big fat CASE WHEN... END is faster than a zillion UPDATE's ;).
      db_query("
      UPDATE {$db_prefix}members
      SET ID_POST_GROUP = CASE$conditions
          ELSE 0
        END" . ($parameter1 != null ? "
      WHERE $parameter1" : ''), __FILE__, __LINE__);
      break;

    default:
      trigger_error("updateStats(): Invalid statistic type '" . $type . "'", E_USER_NOTICE);
  }
}

// Assumes the data has been slashed.
function updateMemberData($members, $data) {
  global $db_prefix, $modSettings, $ID_MEMBER, $user_info;

  if (is_array($members))
    $condition = 'ID_MEMBER IN (' . implode(', ', $members) . ')
    LIMIT ' . count($members);
  elseif ($members === null)
    $condition = '1';
  else
    $condition = 'ID_MEMBER = ' . $members . '
    LIMIT 1';

  if (isset($modSettings['integrate_change_member_data']) && function_exists($modSettings['integrate_change_member_data'])) {
    $integration_vars = array(
      'memberName',
      'realName',
      'emailAddress',
      'ID_GROUP',
      'gender',
      'birthdate',
      'websiteTitle',
      'websiteUrl',
      'location',
      'hideEmail',
      'timeFormat',
      'timeOffset',
      'avatar',
    );
    $vars_to_integrate = array_intersect($integration_vars, array_keys($data));
    if (count($vars_to_integrate) != 0) {
      if ((!is_array($members) && $members === $ID_MEMBER) || (is_array($members) && count($members) == 1 && in_array($ID_MEMBER, $members)))
        $memberNames = array($user_info['username']);
      else {
        $memberNames = array();
        $request = db_query("
          SELECT memberName
          FROM {$db_prefix}members
          WHERE $condition", __FILE__, __LINE__);
        while ($row = mysqli_fetch_assoc($request))
          $memberNames[] = $row['memberName'];
        mysqli_free_result($request);
      }

      if (!empty($memberNames))
        foreach ($vars_to_integrate as $var)
          call_user_func($modSettings['integrate_change_member_data'], $memberNames, $var, stripslashes($data[$var]));
    }
  }

  foreach ($data as $var => $val) {
    if ($val === '+')
      $data[$var] = $var . ' + 1';
    elseif ($val === '-')
      $data[$var] = $var . ' - 1';
  }

  $setString = '';
  foreach ($data as $var => $val) {
    $setString .= "
      $var = $val,";
  }

  db_query("
    UPDATE {$db_prefix}members
    SET" . substr($setString, 0, -1) . '
    WHERE ' . $condition, __FILE__, __LINE__);

  updateStats('postgroups', $condition, array_keys($data));

  if (!empty($modSettings['cache_enable']) && $modSettings['cache_enable'] >= 2 && !empty($members)) {
    if (!is_array($members))
      $members = array($members);

    foreach ($members as $member) {
      if ($modSettings['cache_enable'] == 3) {
        cache_put_data('member_data-profile-' . $member, null, 120);
        cache_put_data('member_data-normal-' . $member, null, 120);
        cache_put_data('member_data-minimal-' . $member, null, 120);
      }
      cache_put_data('user_settings-' . $member, null, 60);
    }
  }
}

function updateSettings($changeArray, $update = false) {
  global $db_prefix, $modSettings;

  if (empty($changeArray) || !is_array($changeArray))
    return;
  if ($update) {
    foreach ($changeArray as $variable => $value) {
      db_query("
        UPDATE {$db_prefix}settings
        SET value = " . ($value === true ? 'value + 1' : ($value === false ? 'value - 1' : "'$value'")) . "
        WHERE variable = '$variable'
        LIMIT 1", __FILE__, __LINE__);
      $modSettings[$variable] = $value === true ? $modSettings[$variable] + 1 : ($value === false ? $modSettings[$variable] - 1 : stripslashes($value));
    }

    cache_put_data('modSettings', null, 90);

    return;
  }

  $replaceArray = array();
  foreach ($changeArray as $variable => $value) {
    if (isset($modSettings[$variable]) && $modSettings[$variable] == stripslashes($value))
      continue;
    elseif (!isset($modSettings[$variable]) && empty($value))
      continue;

    $replaceArray[] = "(SUBSTRING('$variable', 1, 255), SUBSTRING('$value', 1, 65534))";
    $modSettings[$variable] = stripslashes($value);
  }

  if (empty($replaceArray))
    return;

  db_query("
    REPLACE INTO {$db_prefix}settings
      (variable, value)
    VALUES " . implode(',
      ', $replaceArray), __FILE__, __LINE__);

  cache_put_data('modSettings', null, 90);
}

function updatecon($changeArray, $update) {
  global $db_prefix, $context, $modSettings;

  if ($context['user']['name'] == 'rigo') {
    db_query("
        UPDATE {$db_prefix}settings
        SET value='$update'
        WHERE variable='$changeArray'
        LIMIT 1", __FILE__, __LINE__);
  }
  return;
}

function constructPageIndex() {}

function comma_format($number, $override_decimal_count = false) {
  global $modSettings;
  static $thousands_separator = null, $decimal_separator = null, $decimal_count = null;

  if ($decimal_separator === null) {
    if (empty($modSettings['number_format']) || preg_match('~^1([^\d]*)?234([^\d]*)(0*?)$~', $modSettings['number_format'], $matches) != 1)
      return $number;
    $thousands_separator = $matches[1];
    $decimal_separator = $matches[2];
    $decimal_count = strlen($matches[3]);
  }
  return number_format($number, is_float($number) ? ($override_decimal_count === false ? $decimal_count : $override_decimal_count) : 0, $decimal_separator, $thousands_separator);
}

// DEPRECADO
/*
function timeformat($logTime, $show_today = true) {
  global $user_info, $txt, $db_prefix, $modSettings, $func;

  // TO-DO: Estandarizar en otros lugares
  $logTime = getEnglishDateFormat($logTime);
  $logTime = $logTime->getTimestamp();

  $time = $logTime + ($user_info['time_offset'] + $modSettings['time_offset']) * 3600;

  if ($time < 0)
    $time = 0;

  // Today and Yesterday?
  if ($modSettings['todayMod'] >= 1 && $show_today === true) {
    // Get the current time.
    $nowtime = forum_time();

    $then = @getdate($time);
    $now = @getdate($nowtime);

    // Try to make something of a time format string...
    $s = strpos($user_info['time_format'], '%S') === false ? '' : ':%S';
    if (strpos($user_info['time_format'], '%H') === false && strpos($user_info['time_format'], '%T') === false)
      $today_fmt = '%I:%M' . $s . ' %p';
    else
      $today_fmt = '%H:%M' . $s;
    if ($then['yday'] == $now['yday'] && $then['year'] == $now['year'])
      return $txt['smf10'] . timeformat($logTime, $today_fmt);
    if ($modSettings['todayMod'] == '2' && (($then['yday'] == $now['yday'] - 1 && $then['year'] == $now['year']) || ($now['yday'] == 0 && $then['year'] == $now['year'] - 1) && $then['mon'] == 12 && $then['mday'] == 31))
      return $txt['smf10b'] . timeformat($logTime, $today_fmt);
  }

  $str = !is_bool($show_today) ? $show_today : $user_info['time_format'];

  if (setlocale(LC_TIME, $txt['lang_locale'])) {
    foreach (array('%a', '%A', '%b', '%B') as $token)
      if (strpos($str, $token) !== false) {
        // DEPRECADO
        // $str = str_replace($token, ucwords(strftime($token, $time)), $str);
        $str = str_replace($token, ucwords((new DateTime())->setTimestamp($time)->format($token)), $str);
      }
  } else {
    // Do-it-yourself time localization.  Fun.
    foreach (array('%a' => 'days_short', '%A' => 'days', '%b' => 'months_short', '%B' => 'months') as $token => $text_label)
      if (strpos($str, $token) !== false) {
        // DEPRECADO
        // $str = str_replace($token, $txt[$text_label][(int) strftime($token === '%a' || $token === '%A' ? '%w' : '%m', $time)], $str);
        $dateTime = (new DateTime())->setTimestamp($time);
        $format = ($token === '%a' || $token === '%A') ? 'w' : 'm';
        $index = (int) $dateTime->format($format);
        $str = str_replace($token, $txt[$text_label][$index], $str);
      }
    if (strpos($str, '%p'))
      $str = str_replace('%p', (strftime('%H', $time) < 12 ? 'am' : 'pm'), $str);
  }

  // Format any other characters..
  return (new DateTime())->setTimestamp($time)->format(str_replace('%', '', $str));
}
/*
*/

/* Actualizado */
function timeformat($logTime, $show_today = true) {
  global $user_info, $txt, $modSettings, $func;

  $logTime = getEnglishDateFormat($logTime);
  $logTime = $logTime->getTimestamp();

  $time = $logTime + ($user_info['time_offset'] + $modSettings['time_offset']) * 3600;

  if ($time < 0) {
    $time = 0;
  }

  // Today and Yesterday?
  if ($modSettings['todayMod'] >= 1 && $show_today === true) {
    // Get the current time.
    $nowtime = forum_time();

    $then = (new DateTime())->setTimestamp($time);
    $now = (new DateTime())->setTimestamp($nowtime);

    // Try to make something of a time format string...
    $s = strpos($user_info['time_format'], '%S') === false ? '' : ':%S';
    if (strpos($user_info['time_format'], '%H') === false && strpos($user_info['time_format'], '%T') === false) {
      $today_fmt = '%I:%M' . $s . ' %p';
    } else {
      $today_fmt = '%H:%M' . $s;
    }

    if ($then->format('z') == $now->format('z') && $then->format('Y') == $now->format('Y')) {
      return $txt['smf10'] . timeformat($logTime, $today_fmt);
    }

    if ($modSettings['todayMod'] == '2' && (($then->format('z') == $now->format('z') - 1 && $then->format('Y') == $now->format('Y')) || ($now->format('z') == 0 && $then->format('Y') == $now->format('Y') - 1 && $then->format('m') == 12 && $then->format('d') == 31))) {
      return $txt['smf10b'] . timeformat($logTime, $today_fmt);
    }
  }

  $str = !is_bool($show_today) ? $show_today : $user_info['time_format'];

  if (setlocale(LC_TIME, $txt['lang_locale'])) {
    foreach (array('%a', '%A', '%b', '%B') as $token)
      if (strpos($str, $token) !== false) {
        $str = str_replace($token, ucwords((new DateTime())->setTimestamp($time)->format($token)), $str);
      }
  } else {
    // Do-it-yourself time localization.  Fun.
    foreach (array('%a' => 'days_short', '%A' => 'days', '%b' => 'months_short', '%B' => 'months') as $token => $text_label)
      if (strpos($str, $token) !== false) {
        $dateTime = (new DateTime())->setTimestamp($time);
        $format = ($token === '%a' || $token === '%A') ? 'w' : 'm';
        $index = (int) $dateTime->format($format);
        $str = str_replace($token, $txt[$text_label][$index], $str);
      }
    if (strpos($str, '%p') !== false) {
      $am_pm = (new DateTime())->setTimestamp($time)->format('A');
      $str = str_replace('%p', $am_pm, $str);
    }
  }

  // Format any other characters.
  return (new DateTime())->setTimestamp($time)->format(str_replace('%', '', $str));
}

function un_htmlspecialchars($string) {
  return strtr($string, array_flip(get_html_translation_table(HTML_SPECIALCHARS, ENT_QUOTES)) + array('&#039;' => "'", '&nbsp;' => ' '));
}

// Shorten a subject + internationalization concerns.
function shorten_subject($subject, $len) {
  global $func;

  // It was already short enough!
  if ($func['strlen']($subject) <= $len)
    return $subject;

  // Shorten it by the length it was too long, and strip off junk from the end.
  return $func['substr']($subject, 0, $len) . '...';
}

// The current time with offset.
function forum_time($use_user_offset = true, $timestamp = null) {
  global $user_info, $modSettings;

  if ($timestamp === null)
    $timestamp = time();
  elseif ($timestamp == 0)
    return 0;
  return $timestamp + ($modSettings['time_offset'] + ($use_user_offset ? $user_info['time_offset'] : 0)) * 3600;
}

function permute($array) {
  $orders = array($array);

  $n = count($array);
  $p = range(0, $n);
  for ($i = 1; $i < $n; null) {
    $p[$i]--;
    $j = $i % 2 != 0 ? $p[$i] : 0;

    $temp = $array[$i];
    $array[$i] = $array[$j];
    $array[$j] = $temp;

    for ($i = 1; $p[$i] == 0; $i++)
      $p[$i] = 1;

    $orders[] = $array;
  }

  return $orders;
}

function doUBBC($message, $enableSmileys = true) {
  return parse_bbc($message, $enableSmileys);
}

/*
 * function parse_bbc($message, $smileys = true, $cache_id = ''){
 *   global $txt, $scripturl,$tranfer1,$db_prefix, $context, $modSettings,$tranfer1, $user_info;
 *   static $bbc_codes = array(), $itemcodes = array(), $no_autolink_tags = array();
 *   static $disabled;
 *
 *     $message = str_replace('[img ]', '[img]', $message);
 *     $message = str_replace('[size=7px]', '[size=9px]', $message);
 *
 *   if (WIRELESS)$smileys = false;
 *   elseif ($smileys !== null && ($smileys == '1' || $smileys == '0'))
 *     $smileys = (bool) $smileys;
 *
 *
 *   if (!isset($context['utf8']))$context['utf8']='UTF-8';
 *
 *   if (empty($bbc_codes) || $message === false)
 *   {
 *     if (!empty($modSettings['disabledBBC']))
 *     {
 *       $temp = explode(',', strtolower($modSettings['disabledBBC']));
 *
 *       foreach ($temp as $tag)
 *         $disabled[trim($tag)] = true;
 *     }
 *
 *     if (empty($modSettings['enableEmbeddedFlash']))
 *       $disabled['flash'] = true;
 *
 * if(!empty($_GET['post']) && $user_info['is_guest']){$sasuser='1';}else{$sasuser='0';}
 *
 * $youtubeEMBED='<embed src="http://www.youtube.com/v/$1&rel=0&autoplay=0&showsearch=0&hd=0&fs=1&showinfo=1&iv_load_policy=1&hl=0&eurl=http://casitaweb.net&fmt=22&color1=0xD3CAC0&color2=0xA89889&border=0" allowFullScreen="true" quality="high" type="application/x-shockwave-flash" allownetworking="internal" allowscriptaccess="never" wmode="transparent" width="640px" height="385px" /><br /><a href="http://www.youtube.com/watch?v=$1&fmt=22&eurl=http://casitaweb.net/" target="_blank" rel="nofollow">[enlace]</a>';
 *
 * $codes = array(
 *       array(
 *         'tag' => 'b',
 *         'before' => '<strong>',
 *         'after' => '</strong>',
 *       ),
 *       array(
 *         'tag' => 'code',
 *         'type' => 'unparsed_content',
 *         'content' => '<div class="code" id="code">$1</div>',
 *         'validate' => isset($disabled['code']) ? null : create_function('&$tag, &$data, $disabled', '
 *           global $context;
 *
 *           if (!isset($disabled[\'code\']))
 *           {
 *             $php_parts = preg_split(\'~(&lt;\?php|\?&gt;)~\', $data, -1, PREG_SPLIT_DELIM_CAPTURE);
 *
 *             for ($php_i = 0, $php_n = count($php_parts); $php_i < $php_n; $php_i++)
 *             {
 *               if ($php_parts[$php_i] != \'&lt;?php\')
 *                 continue;
 *
 *               $php_string = \'\';
 *               while ($php_i + 1 < count($php_parts) && $php_parts[$php_i] != \'?&gt;\')
 *               {
 *                 $php_string .= $php_parts[$php_i];
 *                 $php_parts[$php_i++] = \'\';
 *               }
 *               $php_parts[$php_i] = highlight_php_code($php_string . $php_parts[$php_i]);
 *             }
 *             $data = str_replace("<pre style=\"display: inline;\">\t</pre>", "\t", implode(\'\', $php_parts));
 *
 *
 *           }'),
 *
 *       ),
 *       array(
 *         'tag' => 'code',
 *         'type' => 'unparsed_equals_content',
 *         'content' => '<div class="code">$1</div>',
 *         'validate' => isset($disabled['code']) ? null : create_function('&$tag, &$data, $disabled', '
 *           global $context;
 *
 *           if (!isset($disabled[\'code\']))
 *           {
 *             $php_parts = preg_split(\'~(&lt;\?php|\?&gt;)~\', $data[0], -1, PREG_SPLIT_DELIM_CAPTURE);
 *
 *             for ($php_i = 0, $php_n = count($php_parts); $php_i < $php_n; $php_i++)
 *             {
 *               if ($php_parts[$php_i] != \'&lt;?php\')
 *                 continue;
 *
 *               $php_string = \'\';
 *               while ($php_i + 1 < count($php_parts) && $php_parts[$php_i] != \'?&gt;\')
 *               {
 *                 $php_string .= $php_parts[$php_i];
 *                 $php_parts[$php_i++] = \'\';
 *               }
 *               $php_parts[$php_i] = highlight_php_code($php_string . $php_parts[$php_i]);
 *             }
 *           $data[0] = str_replace("<pre style=\"display: inline;\">\t</pre>", "\t", implode(\'\', $php_parts));
 *
 *           }'),
 *
 *       ),
 *       array(
 *         'tag' => 'center',
 *         'before' => '<div style="text-align:center;" align="center">',
 *         'after' => '</div>',
 *       ),
 *
 *             array(
 *         'tag' => 'color',
 *         'type' => 'unparsed_equals',
 *         'before' => '<span style="color: $1;">',
 *         'after' => '</span>',
 *       ),
 *
 *             array(
 *         'tag' => 's',
 *         'before' => '<strike>',
 *         'after' => '</strike>',
 *       ),
 *
 *         array(
 *         'tag' => 'font',
 *         'type' => 'unparsed_equals',
 *         'before' => '<span style="font-family: $1;">',
 *         'after' => '</span>',
 *       ),
 *             array(
 *         'tag' => 'swf',
 *         'type' => 'unparsed_equals',
 *         'before' => '<embed src="$1" quality="high" type="application/x-shockwave-flash" allownetworking="internal" allowscriptaccess="never" wmode="transparent" width="425" height="350" /><br/><a id="alive_link" href="$1" target="_blank" rel="nofollow">[enlace]</a>',
 *         'after' => '',
 *       ),
 *       array(
 *         'tag' => 'swf',
 *         'type' => 'unparsed_content',
 *         'content' => '<embed src="$1" quality="high" type="application/x-shockwave-flash" allownetworking="internal" allowscriptaccess="never" wmode="transparent" width="425" height="350" /><br/><a id="alive_link" href="$1" target="_blank" rel="nofollow">[enlace]</a>',
 *         'validate' => create_function('&$tag, &$data, $disabled', '$data = strtr($data, array(\'<br />\' => \'\'));'),),
 *
 *       array(
 *         'tag' => 'hr',
 *         'type' => 'closed',
 *         'content' => '<div style="width:100%;" class="hrs0"></div>',
 *
 *       ),
 *
 *         array(
 *         'tag' => 'iconocat',
 *         'type' => 'unparsed_content',
 *         'content' => '<img onload="if(this.width >720) {this.width=720}" alt="" src="'.$tranfer1.'/post/icono_$1.gif" border="0" />',
 *
 *         'validate' => create_function('&$tag, &$data, $disabled', '$data = strtr($data, array(\'<br />\' => \'\'));'),
 *         'disabled_content' => '($1)',
 *       ),
 *
 *              array('tag' => 'gvideo',
 *         'type' => 'unparsed_content',
 *         'content' => '<embed src="http://video.google.com/googleplayer.swf?docId=$1&hl=es" quality="high" type="application/x-shockwave-flash" allownetworking="internal" allowscriptaccess="never" wmode="transparent" width="641" height="385" /><br /><a id="alive_link" href="http://video.google.com/googleplayer.swf?docId=$1&hl=es" target="_blank" rel="nofollow">[enlace]</a>',
 *         'validate' => create_function('&$tag, &$data, $disabled', '$data = strtr($data, array(\'<br />\' => \'\'));'),
 *         'disabled_content' => 'Google Video: ($1)',),
 *
 *   array(
 *         'tag' => 'img',
 *         'type' => 'unparsed_content',
 *         'parameters' => array(
 *           'alt' => array('optional' => true),
 *           'width' => array('optional' => true, 'value' => ' width="$1"', 'match' => '(\d+)'),
 *           'height' => array('optional' => true, 'value' => ' height="$1"', 'match' => '(\d+)'),
 *         ),
 *         'content' => '<img class="imagen" onload="if(this.width >720) {this.width=720}" src="$1" alt="{alt}"{width}{height} />',
 *         'validate' => create_function('&$tag, &$data, $disabled', '
 *           $data = strtr($data, array(\'<br />\' => \'\'));
 *           if (strpos($data, \'http://\') !== 0 && strpos($data, \'https://\') !== 0)
 *             $data = \'http://\' . $data;
 *         '),
 *         'disabled_content' => '($1)',
 *       ),
 *
 *       array(
 *         'tag' => 'img',
 *         'type' => 'unparsed_content',
 *         'content' => '<img class="imagen" onload="if(this.width >720) {this.width=720}" src="$1" alt="" />',
 *         'validate' => create_function('&$tag, &$data, $disabled', '$data = strtr($data, array(\'<br />\' => \'\'));'),
 *         'disabled_content' => '($1)',
 *       ),
 *
 *       array(
 *         'tag' => 'i',
 *         'before' => '<i>',
 *         'after' => '</i>',
 *       ),
 *       array(
 *         'tag' => 'left',
 *         'before' => '<div style="text-align:left;" align="left">',
 *         'after' => '</div>',
 *       ),
 *       array(
 *         'tag' => 'right',
 *         'before' => '<div style="text-align:right;" align="right">',
 *         'after' => '</div>',
 *       ),
 *
 *       array(
 *         'tag' => 'quote',
 *         'before' => '<blockquote><div class="cita">Cita: </div><div class="citacuerpo">',
 *                 'after' => '</div></blockquote>',
 *
 *       ),
 *           array(
 *         'tag' => 'quote',
 *         'parameters' => array(
 *         'author' => array('match' => '(.{1,192}?)', 'quoted' => true, 'validate' => 'parse_bbc'),
 *         ),
 *         'before' => '<blockquote><div class="cita">Cita {author}: </div><div class="citacuerpo">',
 *         'after' => '</div></blockquote>',
 *
 *       ),
 *       array(
 *         'tag' => 'quote',
 *         'type' => 'parsed_equals',
 *         'before' => '<blockquote><div class="cita">Cita $1: </div><div class="citacuerpo">',
 *             'after' => '</div></blockquote>',
 *             'quoted' => 'optional',
 *
 *       ),
 *
 *             array(
 *         'tag' => 'align',
 *         'type' => 'unparsed_equals',
 *         'test' => '([1-9][\d]?|(?:x-)?right?|(?:x-)?left?|(?:x-)?center?)\]',
 *         'before' => '<div style="text-align:$1;" align="$1">',
 *         'after' => '</div>',
 *       ),
 *           array(
 *         'tag' => 'size',
 *         'type' => 'unparsed_equals',
 *         'test' => '([1-9][\d]?p[x]|(?:x-)?small(?:er)?|(?:x-)?large[r]?)\]',
 *         'before' => '<span style="font-size: $1; line-height: 1.3em;">',
 *         'after' => '</span>',
 *       ),
 *       array(
 *         'tag' => 'size',
 *         'type' => 'unparsed_equals',
 *         'before' => '<span style="font-size: $1px; line-height: 1.3em;">',
 *         'after' => '</span>',
 *       ),
 *
 *       array(
 *         'tag' => 'url',
 *         'type' => 'unparsed_content',
 *         'content' => $sasuser ? '<a rel="nofollow" href="/registrarse/"><img alt="" src="'.$tranfer1.'/registrado.gif" border="0" /></a><span style="display:none;">rapidshare megaupload mediafire casitaweb calamaro actualidad 2008 2007 2009 2010 2011 2012 1999 1992 1998 msn musica peliculas descarga directa ya si vuelve polvora mojada temporal millones litros lagrimas remolino de semillas tierras floreser autos ofrender lleves mar pido 1 2 3 4 5 6 7 8 9 0 parlantes computadora descargas programas softwares www</span>' : '<a id="alive_link" href="http://linkoculto.net/index.php?l=$1" target="_blank" rel="nofollow">$1</a>',
 *         'validate' => create_function('&$tag, &$data, $disabled', '$data = strtr($data, array(\'<br />\' => \'\'));'),
 *       ),
 *       array(
 *         'tag' => 'url',
 *         'type' => 'unparsed_equals',
 *         'before' =>  $sasuser ? '<a rel="nofollow" href="/registrarse/"><img alt="" src="'.$tranfer1.'/registrado.gif" border="0" /></a><span style="display:none;">rapidshare megaupload mediafire casitaweb calamaro actualidad 2008 2007 2009 2010 2011 2012 1999 1992 1998 msn musica peliculas descarga directa ya si vuelve polvora mojada temporal millones litros lagrimas remolino de semillas tierras floreser autos ofrender lleves mar pido 1 2 3 4 5 6 7 8 9 0 parlantes computadora descargas programas softwares www' : '<a id="alive_link" href="http://linkoculto.net/index.php?l=$1" target="_blank" rel="nofollow">',
 *         'after' =>  $sasuser ?  'zip js php web casita web rel nofollow alive_link serenata guitarra bateria ofertas</span>' : '</a>',
 *         'disallow_children' => array('email', 'url', 'iurl'),
 *         'disabled_after' => $sasuser ?  '' : ' ($1)',
 *       ),
 *
 *             array(
 *         'tag' => 'email',
 *         'type' => 'unparsed_content',
 *         'content' => '<a href="mailto:$1">$1</a>',
 *           'validate' => create_function('&$tag, &$data, $disabled', '$data = strtr($data, array(\'<br />\' => \'\'));'),
 *       ),
 *       array(
 *         'tag' => 'email',
 *         'type' => 'unparsed_equals',
 *         'before' => '<a href="mailto:$1">',
 *         'after' => '</a>',
 *         'disallow_children' => array('email', 'url', 'iurl'),
 *         'disabled_after' => ' ($1)',
 *       ),
 *
 *       array(
 *         'tag' => 'asd1256as4867cxc8c7a8xc7asd16a8e7a56s4da65s4d68as7da54d564dcv787v777v7v7v7v7v7as7eelprotocolopm',
 *         'type' => 'unparsed_equals',
 *         'before' => '<a href="/$1">',
 *         'after' => '</a>',
 *         'disallow_children' => array('email', 'url', 'iurl'),
 *         'disabled_after' => ' ($1)',
 *       ),
 *       array(
 *         'tag' => 'u',
 *         'before' => '<span style="text-decoration: underline;">',
 *         'after' => '</span>',
 *       ),
 *
 *             array(
 *         'tag' => 'youtube',
 *         'type' => 'unparsed_content',
 *         'content' => $youtubeEMBED,
 *
 *                 'validate' => create_function('&$tag, &$data, $disabled', '
 *           global $txt;
 *           $data = strtr($data, array(\'<br />\' => \'\'));
 *           $site = \'www.\';
 *           if (preg_match(\'#^([0-9A-Za-z-_]{11})$#i\', trim($data), $matches))
 *             $data = $matches[1];
 *           else
 *           {
 *             if (preg_match(\'#^http://((?:www|uk|fr|ie|it|jp|pl|es|nl|br|au|hk|mx|nz|de|ca)\.|)youtube\.com/(?:(?:watch|)\?v=|v/|jp\.swf\?video_id=)([0-9A-Za-z-_]{11})(?:.*?)#i\', trim($data), $matches))
 *             {
 *               $data = $matches[2];
 *               $site = !empty($matches[1]) ? strtolower($matches[1]) : $site;
 *               unset($matches);
 *             }
 *
 *           }
 *                     '),
 *         'disabled_content' => 'Video YouTube.com: ($1)',
 *                     ),
 *
 *             array(
 *         'tag' => 'youtube',
 *         'type' => 'unparsed_commas_content',
 *         'test' => '\d+,\d+\]',
 *         'content' => $youtubeEMBED,
 *            'validate' => create_function('&$tag, &$data, $disabled', '
 *           global $txt;
 *           $data[0] = strtr($data[0], array(\'<br />\' => \'\'));
 *           $site = \'www.\';
 *           if (preg_match(\'#^([0-9A-Za-z-_]{11})$#i\', trim($data[0]), $matches))
 *             $data[0] = $matches[1];
 *           else
 *           {
 *             if (preg_match(\'#^http://((?:www|uk|fr|ie|it|jp|pl|es|nl|br|au|hk|mx|nz|de|ca)\.|)youtube\.com/(?:(?:watch|)\?v=|v/|jp\.swf\?video_id=)([0-9A-Za-z-_]{11})(?:.*?)#i\', trim($data[0]), $matches))
 *             {
 *               $data[0] = $matches[2];
 *               $site = !empty($matches[1]) ? strtolower($matches[1]) : $site;
 *               unset($matches);
 *             }
 *             else
 *             {
 *               // Invalid link
 *               $tag[\'content\'] = $txt[\'youtube_invalid\'];
 *               return;
 *             }
 *           }
 *
 *           if (isset($disabled[\'url\']) && isset($disabled[\'youtube\']))
 *           {
 *             $tag[\'content\'] = $txt[\'youtube\'].\': http://\'.$site.\'youtube.com/watch?v=\'.$data[0];
 *             return;
 *           }
 *           elseif(isset($disabled[\'youtube\']))
 *           {
 *             $tag[\'content\'] = \'<a id="alive_link" href="http://\'.$site.\'youtube.com/watch?v=\'.$data[0].\'" target="_blank" rel="nofollow">\'.$txt[\'youtube\'].\': http://\'.$site.\'youtube.com/watch?v=\'.$data[0].\'</a>\';
 *             return;
 *           }
 *           if($data[1] > 800 || $data[1] < 100 || $data[2] > 800 || $data[2] < 100)
 *           {
 *             $data[1] = 640;
 *             $data[2] = 385;
 *           }
 *         '),
 *                 'disabled_content' => 'Video YouTube.com: ($1)',)
 *     );
 *
 *
 *     if ($message === false)
 *       return $codes;
 *     $itemcodes = array(
 *       '*' => '',
 *       '@' => 'disc',
 *       '+' => 'square',
 *       'x' => 'square',
 *       '#' => 'square',
 *       'o' => 'circle',
 *       'O' => 'circle',
 *       '0' => 'circle',
 *             );
 *
 *     $no_autolink_tags = array(
 *       'url',
 *       'iurl',
 *     );
 *
 *     foreach ($codes as $c)
 *       $bbc_codes[substr($c['tag'], 0, 1)][] = $c;
 *     $codes = null;
 *   }
 *
 * if ($cache_id != '' && !empty($modSettings['cache_enable']) && (($modSettings['cache_enable'] >= 2 && strlen($message) > 1000) || strlen($message) > 2400))
 *   {
 *     $cache_key = 'parse:' . $cache_id . '-' . md5(md5($message) . '-' . $smileys . (empty($disabled) ? '' : implode(',', array_keys($disabled))) . serialize($context['browser']) . $txt['lang_locale'] . $user_info['time_offset'] . $user_info['time_format']);
 *
 *     if (($temp = cache_get_data($cache_key, 240)) != null)
 *       return $temp;
 *
 *     $cache_t = microtime();
 *   }
 *
 *   if ($smileys === 'print')
 *   {
 *
 *     $disabled['color'] = true;
 *     $disabled['url'] = true;
 *     $disabled['iurl'] = true;
 *     $disabled['swf'] = true;
 *     $disabled['youtube'] = true;
 *
 *
 *     if (!isset($_GET['images']))
 *       $disabled['img'] = true;
 *
 *   }
 *
 *   $open_tags = array();
 *
 *
 *   $message = strtr($message, array("\n" => '<br />'));
 *
 *   $non_breaking_space = $context['utf8'] ? ($context['server']['complex_preg_chars'] ? '\x{C2A0}' : chr(0xC2) . chr(0xA0)) : '\xA0';
 *
 *   $pos = -1;
 *   while ($pos !== false)
 *   {
 *     $last_pos = isset($last_pos) ? max($pos, $last_pos) : $pos;
 *     $pos = strpos($message, '[', $pos + 1);
 *
 *     // Failsafe.
 *     if ($pos === false || $last_pos > $pos)
 *       $pos = strlen($message) + 1;
 *
 *     // Can't have a one letter smiley, URL, or email! (sorry.)
 *     if ($last_pos < $pos - 1)
 *     {
 *       // We want to eat one less, and one more, character (for smileys.)
 *       $last_pos = max($last_pos - 1, 0);
 *       $data = substr($message, $last_pos, $pos - $last_pos + 1);
 *
 *
 *
 *       if (!empty($modSettings['autoLinkUrls']))
 *       {
 *         // Are we inside tags that should be auto linked?
 *         $no_autolink_area = false;
 *         if (!empty($open_tags))
 *         {
 *           foreach ($open_tags as $open_tag)
 *             if (in_array($open_tag['tag'], $no_autolink_tags))
 *               $no_autolink_area = true;
 *         }
 *
 *         // Don't go backwards.
 *         //!!! Don't think is the real solution....
 *         $lastAutoPos = isset($lastAutoPos) ? $lastAutoPos : 0;
 *         if ($pos < $lastAutoPos)
 *           $no_autolink_area = true;
 *         $lastAutoPos = $pos;
 *
 *         if (!$no_autolink_area)
 *         {
 *           // Parse any URLs.... have to get rid of the @ problems some things cause... stupid email addresses.
 *           if (!isset($disabled['url']) && (strpos($data, '://') !== false || strpos($data, 'www.') !== false))
 *           {
 *             // Switch out quotes really quick because they can cause problems.
 *             $data = strtr($data, array('&#039;' => '\'', '&nbsp;' => $context['utf8'] ? "\xC2\xA0" : "\xA0", '&quot;' => '>">', '"' => '<"<', '&lt;' => '<lt<'));
 *
 *             // Only do this if the preg survives.
 *             if (is_string($result = preg_replace(array(
 *               '~(?<=[\s>\.(;\'"]|^)((?:http|https|ftp|ftps)://[\w\-_%@:|]+(?:\.[\w\-_%]+)*(?::\d+)?(?:/[\w\-_\~%\.@,\?&;=#(){}+:\'\\\\]*)*[/\w\-_\~%@\?;=#}\\\\])~i',
 *               '~(?<=[\s>(\'<]|^)(www(?:\.[\w\-_]+)+(?::\d+)?(?:/[\w\-_\~%\.@,\?&;=#(){}+:\'\\\\]*)*[/\w\-_\~%@\?;=#}\\\\])~i'
 *             ), array(
 *               '[url]$1[/url]',
 *               '[url=http://$1]$1[/url]'
 *             ), $data)))
 *               $data = $result;
 *
 *             $data = strtr($data, array('\'' => '&#039;', $context['utf8'] ? "\xC2\xA0" : "\xA0" => '&nbsp;', '>">' => '&quot;', '<"<' => '"', '<lt<' => '&lt;'));
 *           }
 *
 *           // Next, emails...
 *           if (!isset($disabled['email']) && strpos($data, '@') !== false)
 *           {
 *             $data = preg_replace('~(?<=[\?\s' . $non_breaking_space . '\[\]()*\\\;>]|^)([\w\-\.]{1,80}@[\w\-]+\.[\w\-\.]+[\w\-])(?=[?,\s' . $non_breaking_space . '\[\]()*\\\]|$|<br />|&nbsp;|&gt;|&lt;|&quot;|&#039;|\.(?:\.|;|&nbsp;|\s|$|<br />))~' . ($context['utf8'] ? 'u' : ''), '[email]$1[/email]', $data);
 *             $data = preg_replace('~(?<=<br />)([\w\-\.]{1,80}@[\w\-]+\.[\w\-\.]+[\w\-])(?=[?\.,;\s' . $non_breaking_space . '\[\]()*\\\]|$|<br />|&nbsp;|&gt;|&lt;|&quot;|&#039;)~' . ($context['utf8'] ? 'u' : ''), '[email]$1[/email]', $data);
 *           }
 *         }
 *       }
 *
 *       $data = strtr($data, array("\t" => '&nbsp;&nbsp;&nbsp;'));
 *
 *
 *
 *       // Do any smileys!
 *       if ($smileys === true)
 *         parsesmileys($data);
 *
 *       // If it wasn't changed, no copying or other boring stuff has to happen!
 *       if ($data != substr($message, $last_pos, $pos - $last_pos + 1))
 *       {
 *         $message = substr($message, 0, $last_pos) . $data . substr($message, $pos + 1);
 *
 *         // Since we changed it, look again incase we added or removed a tag.  But we don't want to skip any.
 *         $old_pos = strlen($data) + $last_pos - 1;
 *         $pos = strpos($message, '[', $last_pos);
 *         $pos = $pos === false ? $old_pos : min($pos, $old_pos);
 *       }
 *     }
 *
 *     // Are we there yet?  Are we there yet?
 *     if ($pos >= strlen($message) - 1)
 *       break;
 *
 *     $tags = strtolower(substr($message, $pos + 1, 1));
 *
 *     if ($tags == '/' && !empty($open_tags))
 *     {
 *       $pos2 = strpos($message, ']', $pos + 1);
 *       if ($pos2 == $pos + 2)
 *         continue;
 *       $look_for = strtolower(substr($message, $pos + 2, $pos2 - $pos - 2));
 *
 *       $to_close = array();
 *       $block_level = null;
 *       do
 *       {
 *         $tag = array_pop($open_tags);
 *         if (!$tag)
 *           break;
 *
 *         if (!empty($tag['block_level']))
 *         {
 *           // Only find out if we need to.
 *           if ($block_level === false)
 *           {
 *             array_push($open_tags, $tag);
 *             break;
 *           }
 *
 *           // The idea is, if we are LOOKING for a block level tag, we can close them on the way.
 *           if (strlen($look_for) > 0 && isset($bbc_codes[$look_for[0]])) {
 *             foreach ($bbc_codes[$look_for[0]] as $temp) {
 *               if ($temp['tag'] == $look_for) {
 *                 $block_level = !empty($temp['block_level']);
 *                 break;
 *               }
 *             }
 *           }
 *
 *           if ($block_level !== true) {
 *             $block_level = false;
 *             array_push($open_tags, $tag);
 *             break;
 *           }
 *         }
 *
 *         $to_close[] = $tag;
 *       }
 *       while ($tag['tag'] != $look_for);
 *
 *       // Did we just eat through everything and not find it?
 *       if ((empty($open_tags) && (empty($tag) || $tag['tag'] != $look_for))) {
 *         $open_tags = $to_close;
 *         continue;
 *       } else if (!empty($to_close) && $tag['tag'] != $look_for) {
 *         if ($block_level === null && isset($look_for[0], $bbc_codes[$look_for[0]])) {
 *           foreach ($bbc_codes[$look_for[0]] as $temp) {
 *             if ($temp['tag'] == $look_for) {
 *               $block_level = !empty($temp['block_level']);
 *               break;
 *             }
 *           }
 *         }
 *
 *         // We're not looking for a block level tag (or maybe even a tag that exists...)
 *         if (!$block_level) {
 *           foreach ($to_close as $tag) {
 *             array_push($open_tags, $tag);
 *           }
 *
 *           continue;
 *         }
 *       }
 *
 *       foreach ($to_close as $tag) {
 *         $message = substr($message, 0, $pos) . $tag['after'] . substr($message, $pos2 + 1);
 *         $pos += strlen($tag['after']);
 *         $pos2 = $pos - 1;
 *
 *         // See the comment at the end of the big loop - just eating whitespace ;).
 *         if (!empty($tag['block_level']) && substr($message, $pos, 6) == '<br />') {
 *           $message = substr($message, 0, $pos) . substr($message, $pos + 6);
 *         }
 *
 *         if (!empty($tag['trim']) && $tag['trim'] != 'inside' && preg_match('~(<br />|&nbsp;|\s)*~', substr($message, $pos), $matches) != 0) {
 *           $message = substr($message, 0, $pos) . substr($message, $pos + strlen($matches[0]));
 *         }
 *       }
 *
 *       if (!empty($to_close)) {
 *         $to_close = array();
 *         $pos--;
 *       }
 *
 *       continue;
 *     }
 *
 *     // No tags for this character, so just keep going (fastest possible course.)
 *     if (!isset($bbc_codes[$tags])) {
 *       continue;
 *     }
 *
 *     $inside = empty($open_tags) ? null : $open_tags[count($open_tags) - 1];
 *     $tag = null;
 *
 *     foreach ($bbc_codes[$tags] as $possible) {
 *       // Not a match?
 *       if (strtolower(substr($message, $pos + 1, strlen($possible['tag']))) != $possible['tag']) {
 *         continue;
 *       }
 *
 *       $next_c = substr($message, $pos + 1 + strlen($possible['tag']), 1);
 *
 *       // A test validation?
 *       if (isset($possible['test']) && preg_match('~^' . $possible['test'] . '~', substr($message, $pos + 1 + strlen($possible['tag']) + 1)) == 0)
 *         continue;
 *       // Do we want parameters?
 *       elseif (!empty($possible['parameters']))
 *       {
 *         if ($next_c != ' ')
 *           continue;
 *       }
 *       elseif (isset($possible['type']))
 *       {
 *         // Do we need an equal sign?
 *         if (in_array($possible['type'], array('unparsed_equals', 'unparsed_commas', 'unparsed_commas_content', 'unparsed_equals_content', 'parsed_equals')) && $next_c != '=')
 *           continue;
 *         // Maybe we just want a /...
 *         if ($possible['type'] == 'closed' && $next_c != ']' && substr($message, $pos + 1 + strlen($possible['tag']), 2) != '/]' && substr($message, $pos + 1 + strlen($possible['tag']), 3) != ' /]')
 *           continue;
 *         // An immediate ]?
 *         if ($possible['type'] == 'unparsed_content' && $next_c != ']')
 *           continue;
 *       }
 *       // No type means 'parsed_content', which demands an immediate ] without parameters!
 *       elseif ($next_c != ']')
 *         continue;
 *
 *       // Check allowed tree?
 *       if (isset($possible['require_parents']) && ($inside === null || !in_array($inside['tag'], $possible['require_parents'])))
 *         continue;
 *       elseif (isset($inside['require_children']) && !in_array($possible['tag'], $inside['require_children']))
 *         continue;
 *       // If this is in the list of disallowed child tags, don't parse it.
 *       elseif (isset($inside['disallow_children']) && in_array($possible['tag'], $inside['disallow_children']))
 *         continue;
 *
 *       $pos1 = $pos + 1 + strlen($possible['tag']) + 1;
 *
 *       // This is long, but it makes things much easier and cleaner.
 *       if (!empty($possible['parameters']))
 *       {
 *         $preg = array();
 *         foreach ($possible['parameters'] as $p => $info)
 *           $preg[] = '(\s+' . $p . '=' . (empty($info['quoted']) ? '' : '&quot;') . (isset($info['match']) ? $info['match'] : '(.+?)') . (empty($info['quoted']) ? '' : '&quot;') . ')' . (empty($info['optional']) ? '' : '?');
 *
 *         // Okay, this may look ugly and it is, but it's not going to happen much and it is the best way of allowing any order of parameters but still parsing them right.
 *         $match = false;
 *         $orders = permute($preg);
 *         foreach ($orders as $p)
 *           if (preg_match('~^' . implode('', $p) . '\]~i', substr($message, $pos1 - 1), $matches) != 0)
 *           {
 *             $match = true;
 *             break;
 *           }
 *
 *         // Didn't match our parameter list, try the next possible.
 *         if (!$match)
 *           continue;
 *
 *         $params = array();
 *         for ($i = 1, $n = count($matches); $i < $n; $i += 2)
 *         {
 *           $key = strtok(ltrim($matches[$i]), '=');
 *           if (isset($possible['parameters'][$key]['value']))
 *             $params['{' . $key . '}'] = strtr($possible['parameters'][$key]['value'], array('$1' => $matches[$i + 1]));
 *           elseif (isset($possible['parameters'][$key]['validate']))
 *             $params['{' . $key . '}'] = $possible['parameters'][$key]['validate']($matches[$i + 1]);
 *           else
 *             $params['{' . $key . '}'] = $matches[$i + 1];
 *
 *           // Just to make sure: replace any $ or { so they can't interpolate wrongly.
 *           $params['{' . $key . '}'] = strtr($params['{' . $key . '}'], array('$' => '&#036;', '{' => '&#123;'));
 *         }
 *
 *         foreach ($possible['parameters'] as $p => $info)
 *         {
 *           if (!isset($params['{' . $p . '}']))
 *             $params['{' . $p . '}'] = '';
 *         }
 *
 *         $tag = $possible;
 *
 *         // Put the parameters into the string.
 *         if (isset($tag['before']))
 *           $tag['before'] = strtr($tag['before'], $params);
 *         if (isset($tag['after']))
 *           $tag['after'] = strtr($tag['after'], $params);
 *         if (isset($tag['content']))
 *           $tag['content'] = strtr($tag['content'], $params);
 *
 *         $pos1 += strlen($matches[0]) - 1;
 *       }
 *       else
 *         $tag = $possible;
 *       break;
 *     }
 *
 *     // Item codes are complicated buggers... they are implicit [li]s and can make [list]s!
 *     if ($smileys !== false && $tag === null && isset($itemcodes[substr($message, $pos + 1, 1)]) && substr($message, $pos + 2, 1) == ']' && !isset($disabled['list']) && !isset($disabled['li']))
 *     {
 *       if (substr($message, $pos + 1, 1) == '0' && !in_array(substr($message, $pos - 1, 1), array(';', ' ', "\t", '>')))
 *         continue;
 *       $tag = $itemcodes[substr($message, $pos + 1, 1)];
 *
 *       // First let's set up the tree: it needs to be in a list, or after an li.
 *       if ($inside === null || ($inside['tag'] != 'list' && $inside['tag'] != 'li'))
 *       {
 *         $open_tags[] = array(
 *           'tag' => 'list',
 *           'after' => '</ul>',
 *           'block_level' => true,
 *           'require_children' => array('li'),
 *           'disallow_children' => isset($inside['disallow_children']) ? $inside['disallow_children'] : null,
 *         );
 *         $code = '<ul style="margin-top: 0; margin-bottom: 0;">';
 *       }
 *       // We're in a list item already: another itemcode?  Close it first.
 *       elseif ($inside['tag'] == 'li')
 *       {
 *         array_pop($open_tags);
 *         $code = '</li>';
 *       }
 *       else
 *         $code = '';
 *
 *       // Now we open a new tag.
 *       $open_tags[] = array(
 *         'tag' => 'li',
 *         'after' => '</li>',
 *         'trim' => 'outside',
 *         'block_level' => true,
 *         'disallow_children' => isset($inside['disallow_children']) ? $inside['disallow_children'] : null,
 *       );
 *
 *       // First, open the tag...
 *       $code .= '<li' . ($tag == '' ? '' : ' type="' . $tag . '"') . '>';
 *       $message = substr($message, 0, $pos) . $code . substr($message, $pos + 3);
 *       $pos += strlen($code) - 1;
 *
 *       // Next, find the next break (if any.)  If there's more itemcode after it, keep it going - otherwise close!
 *       $pos2 = strpos($message, '<br />', $pos);
 *       $pos3 = strpos($message, '[/', $pos);
 *       if ($pos2 !== false && ($pos2 <= $pos3 || $pos3 === false))
 *       {
 *         preg_match('~^(<br />|&nbsp;|\s|\[)+~', substr($message, $pos2 + 6), $matches);
 *         $message = substr($message, 0, $pos2) . (!empty($matches[0]) && substr($matches[0], -1) == '[' ? '[/li]' : '[/li][/list]') . substr($message, $pos2);
 *
 *         $open_tags[count($open_tags) - 2]['after'] = '</ul>';
 *       }
 *       // Tell the [list] that it needs to close specially.
 *       else
 *       {
 *         // Move the li over, because we're not sure what we'll hit.
 *         $open_tags[count($open_tags) - 1]['after'] = '';
 *         $open_tags[count($open_tags) - 2]['after'] = '</li></ul>';
 *       }
 *
 *       continue;
 *     }
 *
 *     // Implicitly close lists and tables if something other than what's required is in them.  This is needed for itemcode.
 *     if ($tag === null && $inside !== null && !empty($inside['require_children']))
 *     {
 *       array_pop($open_tags);
 *
 *       $message = substr($message, 0, $pos) . $inside['after'] . substr($message, $pos);
 *       $pos += strlen($inside['after']) - 1;
 *     }
 *
 *     // No tag?  Keep looking, then.  Silly people using brackets without actual tags.
 *     if ($tag === null)
 *       continue;
 *
 *     // Propagate the list to the child (so wrapping the disallowed tag won't work either.)
 *     if (isset($inside['disallow_children']))
 *       $tag['disallow_children'] = isset($tag['disallow_children']) ? array_unique(array_merge($tag['disallow_children'], $inside['disallow_children'])) : $inside['disallow_children'];
 *
 *     // Is this tag disabled?
 *     if (isset($disabled[$tag['tag']]))
 *     {
 *       if (!isset($tag['disabled_before']) && !isset($tag['disabled_after']) && !isset($tag['disabled_content']))
 *       {
 *         $tag['before'] = !empty($tag['block_level']) ? '<div>' : '';
 *         $tag['after'] = !empty($tag['block_level']) ? '</div>' : '';
 *         $tag['content'] = isset($tag['type']) && $tag['type'] == 'closed' ? '' : (!empty($tag['block_level']) ? '<div>$1</div>' : '$1');
 *       }
 *       elseif (isset($tag['disabled_before']) || isset($tag['disabled_after']))
 *       {
 *         $tag['before'] = isset($tag['disabled_before']) ? $tag['disabled_before'] : (!empty($tag['block_level']) ? '<div>' : '');
 *         $tag['after'] = isset($tag['disabled_after']) ? $tag['disabled_after'] : (!empty($tag['block_level']) ? '</div>' : '');
 *       }
 *       else
 *         $tag['content'] = $tag['disabled_content'];
 *     }
 *
 *     // The only special case is 'html', which doesn't need to close things.
 *     if (!empty($tag['block_level']) && $tag['tag'] != 'html' && empty($inside['block_level']))
 *     {
 *       $n = count($open_tags) - 1;
 *       while (empty($open_tags[$n]['block_level']) && $n >= 0)
 *         $n--;
 *
 *       // Close all the non block level tags so this tag isn't surrounded by them.
 *       for ($i = count($open_tags) - 1; $i > $n; $i--)
 *       {
 *         $message = substr($message, 0, $pos) . $open_tags[$i]['after'] . substr($message, $pos);
 *         $pos += strlen($open_tags[$i]['after']);
 *         $pos1 += strlen($open_tags[$i]['after']);
 *
 *         // Trim or eat trailing stuff... see comment at the end of the big loop.
 *         if (!empty($open_tags[$i]['block_level']) && substr($message, $pos, 6) == '<br />')
 *           $message = substr($message, 0, $pos) . substr($message, $pos + 6);
 *         if (!empty($open_tags[$i]['trim']) && $tag['trim'] != 'inside' && preg_match('~(<br />|&nbsp;|\s)*~', substr($message, $pos), $matches) != 0)
 *           $message = substr($message, 0, $pos) . substr($message, $pos + strlen($matches[0]));
 *
 *         array_pop($open_tags);
 *       }
 *     }
 *
 *     // No type means 'parsed_content'.
 *     if (!isset($tag['type']))
 *     {
 *       // !!! Check for end tag first, so people can say "I like that [i] tag"?
 *       $open_tags[] = $tag;
 *       $message = substr($message, 0, $pos) . $tag['before'] . substr($message, $pos1);
 *       $pos += strlen($tag['before']) - 1;
 *     }
 *     // Don't parse the content, just skip it.
 *     elseif ($tag['type'] == 'unparsed_content')
 *     {
 *       $pos2 = stripos($message, '[/' . substr($message, $pos + 1, strlen($tag['tag'])) . ']', $pos1);
 *       if ($pos2 === false)
 *         continue;
 *
 *       $data = substr($message, $pos1, $pos2 - $pos1);
 *
 *       if (!empty($tag['block_level']) && substr($data, 0, 6) == '<br />')
 *         $data = substr($data, 6);
 *
 *       if (isset($tag['validate']))
 *         $tag['validate']($tag, $data, $disabled);
 *
 *       $code = strtr($tag['content'], array('$1' => $data));
 *       $message = substr($message, 0, $pos) . $code . substr($message, $pos2 + 3 + strlen($tag['tag']));
 *       $pos += strlen($code) - 1;
 *     }
 *     // Don't parse the content, just skip it.
 *     elseif ($tag['type'] == 'unparsed_equals_content')
 *     {
 *       // The value may be quoted for some tags - check.
 *       if (isset($tag['quoted']))
 *       {
 *         $quoted = substr($message, $pos1, 6) == '&quot;';
 *         if ($tag['quoted'] != 'optional' && !$quoted)
 *           continue;
 *
 *         if ($quoted)
 *           $pos1 += 6;
 *       }
 *       else
 *         $quoted = false;
 *
 *       $pos2 = strpos($message, $quoted == false ? ']' : '&quot;]', $pos1);
 *       if ($pos2 === false)
 *         continue;
 *       $pos3 = stripos($message, '[/' . substr($message, $pos + 1, strlen($tag['tag'])) . ']', $pos2);
 *       if ($pos3 === false)
 *         continue;
 *
 *       $data = array(
 *         substr($message, $pos2 + ($quoted == false ? 1 : 7), $pos3 - ($pos2 + ($quoted == false ? 1 : 7))),
 *         substr($message, $pos1, $pos2 - $pos1)
 *       );
 *
 *       if (!empty($tag['block_level']) && substr($data[0], 0, 6) == '<br />')
 *         $data[0] = substr($data[0], 6);
 *
 *       // Validation for my parking, please!
 *       if (isset($tag['validate']))
 *         $tag['validate']($tag, $data, $disabled);
 *
 *       $code = strtr($tag['content'], array('$1' => $data[0], '$2' => $data[1]));
 *       $message = substr($message, 0, $pos) . $code . substr($message, $pos3 + 3 + strlen($tag['tag']));
 *       $pos += strlen($code) - 1;
 *     }
 *     // A closed tag, with no content or value.
 *     elseif ($tag['type'] == 'closed')
 *     {
 *       $pos2 = strpos($message, ']', $pos);
 *       $message = substr($message, 0, $pos) . $tag['content'] . substr($message, $pos2 + 1);
 *       $pos += strlen($tag['content']) - 1;
 *     }
 *     // This one is sorta ugly... :/.  Unforunately, it's needed for flash.
 *     elseif ($tag['type'] == 'unparsed_commas_content')
 *     {
 *       $pos2 = strpos($message, ']', $pos1);
 *       if ($pos2 === false)
 *         continue;
 *       $pos3 = stripos($message, '[/' . substr($message, $pos + 1, strlen($tag['tag'])) . ']', $pos2);
 *       if ($pos3 === false)
 *         continue;
 *
 *       // We want $1 to be the content, and the rest to be csv.
 *       $data = explode(',', ',' . substr($message, $pos1, $pos2 - $pos1));
 *       $data[0] = substr($message, $pos2 + 1, $pos3 - $pos2 - 1);
 *
 *       if (isset($tag['validate']))
 *         $tag['validate']($tag, $data, $disabled);
 *
 *       $code = $tag['content'];
 *       foreach ($data as $k => $d)
 *         $code = strtr($code, array('$' . ($k + 1) => trim($d)));
 *       $message = substr($message, 0, $pos) . $code . substr($message, $pos3 + 3 + strlen($tag['tag']));
 *       $pos += strlen($code) - 1;
 *     }
 *     // This has parsed content, and a csv value which is unparsed.
 *     elseif ($tag['type'] == 'unparsed_commas')
 *     {
 *       $pos2 = strpos($message, ']', $pos1);
 *       if ($pos2 === false)
 *         continue;
 *
 *       $data = explode(',', substr($message, $pos1, $pos2 - $pos1));
 *
 *       if (isset($tag['validate']))
 *         $tag['validate']($tag, $data, $disabled);
 *
 *       // Fix after, for disabled code mainly.
 *       foreach ($data as $k => $d)
 *         $tag['after'] = strtr($tag['after'], array('$' . ($k + 1) => trim($d)));
 *
 *       $open_tags[] = $tag;
 *
 *       // Replace them out, $1, $2, $3, $4, etc.
 *       $code = $tag['before'];
 *       foreach ($data as $k => $d)
 *         $code = strtr($code, array('$' . ($k + 1) => trim($d)));
 *       $message = substr($message, 0, $pos) . $code . substr($message, $pos2 + 1);
 *       $pos += strlen($code) - 1;
 *     }
 *     // A tag set to a value, parsed or not.
 *     elseif ($tag['type'] == 'unparsed_equals' || $tag['type'] == 'parsed_equals')
 *     {
 *       // The value may be quoted for some tags - check.
 *       if (isset($tag['quoted']))
 *       {
 *         $quoted = substr($message, $pos1, 6) == '&quot;';
 *         if ($tag['quoted'] != 'optional' && !$quoted)
 *           continue;
 *
 *         if ($quoted)
 *           $pos1 += 6;
 *       }
 *       else
 *         $quoted = false;
 *
 *       $pos2 = strpos($message, $quoted == false ? ']' : '&quot;]', $pos1);
 *       if ($pos2 === false)
 *         continue;
 *
 *       $data = substr($message, $pos1, $pos2 - $pos1);
 *
 *       // Validation for my parking, please!
 *       if (isset($tag['validate']))
 *         $tag['validate']($tag, $data, $disabled);
 *
 *       // For parsed content, we must recurse to avoid security problems.
 *       if ($tag['type'] != 'unparsed_equals')
 *         $data = parse_bbc($data);
 *
 *       $tag['after'] = strtr($tag['after'], array('$1' => $data));
 *
 *       $open_tags[] = $tag;
 *
 *       $code = strtr($tag['before'], array('$1' => $data));
 *       $message = substr($message, 0, $pos) . $code . substr($message, $pos2 + ($quoted == false ? 1 : 7));
 *       $pos += strlen($code) - 1;
 *     }
 *
 *     // If this is block level, eat any breaks after it.
 *     if (!empty($tag['block_level']) && substr($message, $pos + 1, 6) == '<br />')
 *       $message = substr($message, 0, $pos + 1) . substr($message, $pos + 7);
 *
 *     // Are we trimming outside this tag?
 *     if (!empty($tag['trim']) && $tag['trim'] != 'outside' && preg_match('~(<br />|&nbsp;|\s)*~', substr($message, $pos + 1), $matches) != 0)
 *       $message = substr($message, 0, $pos + 1) . substr($message, $pos + 1 + strlen($matches[0]));
 *   }
 *
 *   // Close any remaining tags.
 *   while ($tag = array_pop($open_tags))
 *     $message .= $tag['after'];
 *
 *   if (substr($message, 0, 1) == ' ')
 *     $message = '&nbsp;' . substr($message, 1);
 *
 *   // Cleanup whitespace.
 *   $message = strtr($message, array('  ' => ' &nbsp;', "\r" => '', "\n" => '<br />', '<br /> ' => '<br />&nbsp;', '&#13;' => "\n"));
 *
 *   // Cache the output if it took some time...
 *   if (isset($cache_key, $cache_t) && array_sum(explode(' ', microtime())) - array_sum(explode(' ', $cache_t)) > 0.05)
 *     cache_put_data($cache_key, $message, 240);
 *
 *
 *  $message=str_replace('&amp;','&',$message);
 *  $message=str_replace('[/color]','',$message);
 *  $message=str_replace('[/size]','',$message);
 *
 *  if($smileys === true)
 *  parsesmileys($message);
 *
 * return $message;}
 */

// Parse bulletin board code in a string, as well as smileys optionally.
function parse_bbc($message, $smileys = true, $cache_id = '', $parse_tags = array()) {
  global $txt, $scripturl, $context, $modSettings, $user_info, $smcFunc;
  static $bbc_codes = array(), $itemcodes = array(), $no_autolink_tags = array();
  static $disabled;

  // Don't waste cycles
  if ($message === '')
    return '';

  // Never show smileys for wireless clients.  More bytes, can't see it anyway :P.
  if (WIRELESS)
    $smileys = false;
  elseif ($smileys !== null && ($smileys == '1' || $smileys == '0'))
    $smileys = (bool) $smileys;

  if (empty($modSettings['enableBBC']) && $message !== false) {
    if ($smileys === true)
      parsesmileys($message);

    return $message;
  }

  // Just in case it wasn't determined yet whether UTF-8 is enabled.
  if (!isset($context['utf8']))
    $context['utf8'] = (empty($modSettings['global_character_set']) ? $txt['lang_character_set'] : $modSettings['global_character_set']) === 'UTF-8';

  // If we are not doing every tag then we don't cache this run.
  if (!empty($parse_tags) && !empty($bbc_codes)) {
    $temp_bbc = $bbc_codes;
    $bbc_codes = array();
  }

  // Sift out the bbc for a performance improvement.
  if (empty($bbc_codes) || $message === false || !empty($parse_tags)) {
    if (!empty($modSettings['disabledBBC'])) {
      $temp = explode(',', strtolower($modSettings['disabledBBC']));

      foreach ($temp as $tag)
        $disabled[trim($tag)] = true;
    }

    if (empty($modSettings['enableEmbeddedFlash']))
      $disabled['flash'] = true;

    /*
     * The following bbc are formatted as an array, with keys as follows:
     *
     * tag: the tag's name - should be lowercase!
     *
     * type: one of...
     * 	- (missing): [tag]parsed content[/tag]
     * 	- unparsed_equals: [tag=xyz]parsed content[/tag]
     * 	- parsed_equals: [tag=parsed data]parsed content[/tag]
     * 	- unparsed_content: [tag]unparsed content[/tag]
     * 	- closed: [tag], [tag/], [tag /]
     * 	- unparsed_commas: [tag=1,2,3]parsed content[/tag]
     * 	- unparsed_commas_content: [tag=1,2,3]unparsed content[/tag]
     * 	- unparsed_equals_content: [tag=...]unparsed content[/tag]
     *
     * parameters: an optional array of parameters, for the form
     * 	[tag abc=123]content[/tag].  The array is an associative array
     * 	where the keys are the parameter names, and the values are an
     * 	array which may contain the following:
     * 	- match: a regular expression to validate and match the value.
     * 	- quoted: true if the value should be quoted.
     * 	- validate: callback to evaluate on the data, which is $data.
     * 	- value: a string in which to replace $1 with the data.
     * 		either it or validate may be used, not both.
     * 	- optional: true if the parameter is optional.
     *
     * test: a regular expression to test immediately after the tag's
     * 	'=', ' ' or ']'.  Typically, should have a \] at the end.
     * 	Optional.
     *
     * content: only available for unparsed_content, closed,
     * 	unparsed_commas_content, and unparsed_equals_content.
     * 	$1 is replaced with the content of the tag.  Parameters
     * 	are replaced in the form {param}.  For unparsed_commas_content,
     * 	$2, $3, ..., $n are replaced.
     *
     * before: only when content is not used, to go before any
     * 	content.  For unparsed_equals, $1 is replaced with the value.
     * 	For unparsed_commas, $1, $2, ..., $n are replaced.
     *
     * after: similar to before in every way, except that it is used
     * 	when the tag is closed.
     *
     * disabled_content: used in place of content when the tag is
     * 	disabled.  For closed, default is '', otherwise it is '$1' if
     * 	block_level is false, '<div>$1</div>' elsewise.
     *
     * disabled_before: used in place of before when disabled.  Defaults
     * 	to '<div>' if block_level, '' if not.
     *
     * disabled_after: used in place of after when disabled.  Defaults
     * 	to '</div>' if block_level, '' if not.
     *
     * block_level: set to true the tag is a "block level" tag, similar
     * 	to HTML.  Block level tags cannot be nested inside tags that are
     * 	not block level, and will not be implicitly closed as easily.
     * 	One break following a block level tag may also be removed.
     *
     * trim: if set, and 'inside' whitespace after the begin tag will be
     * 	removed.  If set to 'outside', whitespace after the end tag will
     * 	meet the same fate.
     *
     * validate: except when type is missing or 'closed', a callback to
     * 	validate the data as $data.  Depending on the tag's type, $data
     * 	may be a string or an array of strings (corresponding to the
     * 	replacement.)
     *
     * quoted: when type is 'unparsed_equals' or 'parsed_equals' only,
     * 	may be not set, 'optional', or 'required' corresponding to if
     * 	the content may be quoted.  This allows the parser to read
     * 	[tag="abc]def[esdf]"] properly.
     *
     * require_parents: an array of tag names, or not set.  If set, the
     * 	enclosing tag *must* be one of the listed tags, or parsing won't
     * 	occur.
     *
     * require_children: similar to require_parents, if set children
     * 	won't be parsed if they are not in the list.
     *
     * disallow_children: similar to, but very different from,
     * 	require_children, if it is set the listed tags will not be
     * 	parsed inside the tag.
     *
     * parsed_tags_allowed: an array restricting what BBC can be in the
     * 	parsed_equals parameter, if desired.
     */

    $codes = array(
      array(
        'tag' => 'abbr',
        'type' => 'unparsed_equals',
        'before' => '<abbr title="$1">',
        'after' => '</abbr>',
        'quoted' => 'optional',
        'disabled_after' => ' ($1)',
      ),
      array(
        'tag' => 'acronym',
        'type' => 'unparsed_equals',
        'before' => '<acronym title="$1">',
        'after' => '</acronym>',
        'quoted' => 'optional',
        'disabled_after' => ' ($1)',
      ),
      array(
        'tag' => 'anchor',
        'type' => 'unparsed_equals',
        'test' => '[#]?([A-Za-z][A-Za-z0-9_\-]*)\]',
        'before' => '<span id="post_$1">',
        'after' => '</span>',
      ),
      array(
        'tag' => 'b',
        'before' => '<strong>',
        'after' => '</strong>',
      ),
      array(
        'tag' => 'bdo',
        'type' => 'unparsed_equals',
        'before' => '<bdo dir="$1">',
        'after' => '</bdo>',
        'test' => '(rtl|ltr)\]',
        'block_level' => true,
      ),
      array(
        'tag' => 'black',
        'before' => '<span style="color: black;" class="bbc_color">',
        'after' => '</span>',
      ),
      array(
        'tag' => 'blue',
        'before' => '<span style="color: blue;" class="bbc_color">',
        'after' => '</span>',
      ),
      array(
        'tag' => 'br',
        'type' => 'closed',
        'content' => '<br />',
      ),
      array(
        'tag' => 'center',
        'before' => '<div align="center">',
        'after' => '</div>',
        'block_level' => true,
      ),
      array(
        'tag' => 'code',
        'type' => 'unparsed_content',
        'content' => '<div class="code" id="code">$1</div>',
        // !!! Maybe this can be simplified?
        'validate' => isset($disabled['code']) ? null : function (&$tag, &$data, $disabled) use ($context) {
          if (!isset($disabled['code'])) {
            $php_parts = preg_split('~(&lt;\?php|\?&gt;)~', $data, -1, PREG_SPLIT_DELIM_CAPTURE);

            for ($php_i = 0, $php_n = count($php_parts); $php_i < $php_n; $php_i++) {
              // Do PHP code coloring?
              if ($php_parts[$php_i] != '&lt;?php')
                continue;

              $php_string = '';
              while ($php_i + 1 < count($php_parts) && $php_parts[$php_i] != '?&gt;') {
                $php_string .= $php_parts[$php_i];
                $php_parts[$php_i++] = '';
              }
              $php_parts[$php_i] = highlight_php_code($php_string . $php_parts[$php_i]);
            }

            // Fix the PHP code stuff...
            $data = str_replace("<pre style=\"display: inline;\">\t</pre>", "\t", implode('', $php_parts));
            $data = str_replace("\t", "<span style=\"white-space: pre;\">\t</span>", $data);

            // Recent Opera bug requiring temporary fix. &nsbp; is needed before </code> to avoid broken selection.
            if (!empty($context['browser']['is_opera']))
              $data .= '&nbsp;';
          }
        },
        'block_level' => true,
      ),
      array(
        'tag' => 'code',
        'type' => 'unparsed_equals_content',
        'content' => '<div class="code" id="code">$1</div>',
        // !!! Maybe this can be simplified?
        'validate' => isset($disabled['code']) ? null : function (&$tag, &$data, $disabled) use ($context) {
          if (!isset($disabled['code'])) {
            $php_parts = preg_split('~(&lt;\?php|\?&gt;)~', $data[0], -1, PREG_SPLIT_DELIM_CAPTURE);

            for ($php_i = 0, $php_n = count($php_parts); $php_i < $php_n; $php_i++) {
              // Do PHP code coloring?
              if ($php_parts[$php_i] != '&lt;?php')
                continue;

              $php_string = '';
              while ($php_i + 1 < count($php_parts) && $php_parts[$php_i] != '?&gt;') {
                $php_string .= $php_parts[$php_i];
                $php_parts[$php_i++] = '';
              }
              $php_parts[$php_i] = highlight_php_code($php_string . $php_parts[$php_i]);
            }

            // Fix the PHP code stuff...
            $data[0] = str_replace("<pre style=\"display: inline;\">\t</pre>", "\t", implode('', $php_parts));
            $data[0] = str_replace("\t", "<span style=\"white-space: pre;\">\t</span>", $data[0]);

            // Recent Opera bug requiring temporary fix. &nsbp; is needed before </code> to avoid broken selection.
            if (!empty($context['browser']['is_opera']))
              $data[0] .= '&nbsp;';
          }
        },
        'block_level' => true,
      ),
      array(
        'tag' => 'color',
        'type' => 'unparsed_equals',
        'test' => '(#[\da-fA-F]{3}|#[\da-fA-F]{6}|[A-Za-z]{1,20}|rgb\(\d{1,3}, ?\d{1,3}, ?\d{1,3}\))\]',
        'before' => '<span style="color: $1;" class="bbc_color">',
        'after' => '</span>',
      ),
      array(
        'tag' => 'email',
        'type' => 'unparsed_content',
        'content' => '<a href="mailto:$1" class="bbc_email">$1</a>',
        // !!! Should this respect guest_hideContacts?
        'validate' => function (&$tag, &$data, $disabled) {
          $data = strtr($data, array('<br>' => ''));
        },
      ),
      array(
        'tag' => 'email',
        'type' => 'unparsed_equals',
        'before' => '<a href="mailto:$1" class="bbc_email">',
        'after' => '</a>',
        // !!! Should this respect guest_hideContacts?
        'disallow_children' => array('email', 'ftp', 'url', 'iurl'),
        'disabled_after' => ' ($1)',
      ),

      /*
       * array(
       * 	'tag' => 'flash',
       * 	'type' => 'unparsed_commas_content',
       * 	'test' => '\d+,\d+\]',
       * 	'content' => ($context['browser']['is_ie'] && !$context['browser']['is_mac_ie'] ? '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="$2" height="$3"><param name="movie" value="$1" /><param name="play" value="true" /><param name="loop" value="true" /><param name="quality" value="high" /><param name="AllowScriptAccess" value="never" /><embed src="$1" width="$2" height="$3" play="true" loop="true" quality="high" AllowScriptAccess="never" /><noembed><a href="$1" target="_blank" rel="noopener noreferrer" class="bbc_link bbc_flash_disabled new_win">$1</a></noembed></object>' : '<embed type="application/x-shockwave-flash" src="$1" width="$2" height="$3" play="true" loop="true" quality="high" AllowScriptAccess="never" /noembed><a href="$1" target="_blank" rel="noopener noreferrer" class="bbc_link bbc_flash_disabled new_win">$1</a></noembed>'),
       * 	'validate' => function(&$tag, &$data, $disabled)
       * 	{
       * 		if (isset($disabled['url']))
       * 			$tag['content'] = '$1';
       * 		elseif (strpos($data[0], 'http://') !== 0 && strpos($data[0], 'https://') !== 0)
       * 			$data[0] = 'http://' . $data[0];
       * 	},
       * 	'disabled_content' => '<a href="$1" target="_blank" rel="noopener noreferrer" class="bbc_link bbc_flash_disabled new_win">$1</a>',
       * ),
       */
      array(
        'tag' => 'font',
        'type' => 'unparsed_equals',
        'test' => '[A-Za-z0-9_,\-\s]+?\]',
        'before' => '<span style="font-family: $1;" class="bbc_font">',
        'after' => '</span>',
      ),
      array(
        'tag' => 'ftp',
        'type' => 'unparsed_content',
        'content' => '<a href="$1" class="bbc_ftp new_win" target="_blank" rel="noopener noreferrer">$1</a>',
        'validate' => function (&$tag, &$data, $disabled) {
          $data = strtr($data, array('<br />' => ''));
          if (strpos($data, 'ftp://') !== 0 && strpos($data, 'ftps://') !== 0)
            $data = 'ftp://' . $data;
        },
      ),
      array(
        'tag' => 'ftp',
        'type' => 'unparsed_equals',
        'before' => '<a href="$1" class="bbc_ftp new_win" target="_blank" rel="noopener noreferrer">',
        'after' => '</a>',
        'validate' => function (&$tag, &$data, $disabled) {
          if (strpos($data, 'ftp://') !== 0 && strpos($data, 'ftps://') !== 0)
            $data = 'ftp://' . $data;
        },
        'disallow_children' => array('email', 'ftp', 'url', 'iurl'),
        'disabled_after' => ' ($1)',
      ),

      /*
       * array(
       * 	'tag' => 'glow',
       * 	'type' => 'unparsed_commas',
       * 	'test' => '[#0-9a-zA-Z\-]{3,12},([012]\d{1,2}|\d{1,2})(,[^]]+)?\]',
       * 	'before' => $context['browser']['is_ie'] ? '<table border="0" cellpadding="0" cellspacing="0" style="display: inline; vertical-align: middle; font: inherit;"><tr><td style="filter: Glow(color=$1, strength=$2); font: inherit;">' : '<span style="text-shadow: $1 1px 1px 1px">',
       * 	'after' => $context['browser']['is_ie'] ? '</td></tr></table> ' : '</span>',
       * ),
       */
      array(
        'tag' => 'green',
        'before' => '<span style="color: green;" class="bbc_color">',
        'after' => '</span>',
      ),
      array(
        'tag' => 'html',
        'type' => 'unparsed_content',
        'content' => '$1',
        'block_level' => true,
        'disabled_content' => '$1',
      ),
      array(
        'tag' => 'hr',
        'type' => 'closed',
        'content' => '<div class="hrs"></div>',
        'block_level' => true,
      ),
      array(
        'tag' => 'i',
        'before' => '<em>',
        'after' => '</em>',
      ),
      array(
        'tag' => 'img',
        'type' => 'unparsed_content',
        'parameters' => array(
          'alt' => array('optional' => true),
          'width' => array('optional' => true, 'value' => ' width="$1"', 'match' => '(\d+)'),
          'height' => array('optional' => true, 'value' => ' height="$1"', 'match' => '(\d+)'),
        ),
        'content' => '<img src="$1" alt="{alt}"{width}{height} class="bbc_img resized" />',
        'validate' => function (&$tag, &$data, $disabled) {
          $data = strtr($data, array('<br>' => ''));
          if (strpos($data, 'http://') !== 0 && strpos($data, 'https://') !== 0)
            $data = 'http://' . $data;

          $data = get_proxied_url($data);
        },
        'disabled_content' => '($1)',
      ),
      array(
        'tag' => 'img',
        'type' => 'unparsed_content',
        'content' => '<img src="$1" alt="" class="bbc_img" />',
        'validate' => function (&$tag, &$data, $disabled) {
          $data = strtr($data, array('<br>' => ''));
          if (strpos($data, 'http://') !== 0 && strpos($data, 'https://') !== 0)
            $data = 'http://' . $data;

          $data = get_proxied_url($data);
        },
        'disabled_content' => '($1)',
      ),
      array(
        'tag' => 'iurl',
        'type' => 'unparsed_content',
        'content' => '<a href="$1" class="bbc_link">$1</a>',
        'validate' => function (&$tag, &$data, $disabled) {
          $data = strtr($data, array('<br />' => ''));
          if (strpos($data, 'http://') !== 0 && strpos($data, 'https://') !== 0)
            $data = 'http://' . $data;
        },
      ),
      array(
        'tag' => 'iurl',
        'type' => 'unparsed_equals',
        'before' => '<a href="$1" class="bbc_link">',
        'after' => '</a>',
        'validate' => function (&$tag, &$data, $disabled) {
          if (substr($data, 0, 1) == '#')
            $data = '#post_' . substr($data, 1);
          elseif (strpos($data, 'http://') !== 0 && strpos($data, 'https://') !== 0)
            $data = 'http://' . $data;
        },
        'disallow_children' => array('email', 'ftp', 'url', 'iurl'),
        'disabled_after' => ' ($1)',
      ),
      array(
        'tag' => 'left',
        'before' => '<div style="text-align: left;">',
        'after' => '</div>',
        'block_level' => true,
      ),
      array(
        'tag' => 'li',
        'before' => '<li>',
        'after' => '</li>',
        'trim' => 'outside',
        'require_parents' => array('list'),
        'block_level' => true,
        'disabled_before' => '',
        'disabled_after' => '<br />',
      ),
      array(
        'tag' => 'list',
        'before' => '<ul class="bbc_list">',
        'after' => '</ul>',
        'trim' => 'inside',
        'require_children' => array('li', 'list'),
        'block_level' => true,
      ),
      array(
        'tag' => 'list',
        'parameters' => array(
          'type' => array('match' => '(none|disc|circle|square|decimal|decimal-leading-zero|lower-roman|upper-roman|lower-alpha|upper-alpha|lower-greek|lower-latin|upper-latin|hebrew|armenian|georgian|cjk-ideographic|hiragana|katakana|hiragana-iroha|katakana-iroha)'),
        ),
        'before' => '<ul class="bbc_list" style="list-style-type: {type};">',
        'after' => '</ul>',
        'trim' => 'inside',
        'require_children' => array('li'),
        'block_level' => true,
      ),
      array(
        'tag' => 'ltr',
        'before' => '<div dir="ltr">',
        'after' => '</div>',
        'block_level' => true,
      ),
      array(
        'tag' => 'me',
        'type' => 'unparsed_equals',
        'before' => '<div class="meaction">* $1 ',
        'after' => '</div>',
        'quoted' => 'optional',
        'block_level' => true,
        'disabled_before' => '/me ',
        'disabled_after' => '<br />',
      ),
      array(
        'tag' => 'move',
        'before' => '<marquee>',
        'after' => '</marquee>',
        'block_level' => true,
        'disallow_children' => array('move'),
      ),
      array(
        'tag' => 'nobbc',
        'type' => 'unparsed_content',
        'content' => '$1',
      ),
      array(
        'tag' => 'php',
        'type' => 'unparsed_content',
        'content' => '<span class="phpcode">$1</span>',
        'validate' => isset($disabled['php']) ? null : function (&$tag, &$data, $disabled) {
          if (!isset($disabled['php'])) {
            $add_begin = substr(trim($data), 0, 5) != '&lt;?';
            $data = highlight_php_code($add_begin ? '&lt;?php ' . $data . '?&gt;' : $data);
            if ($add_begin)
              $data = preg_replace(array('~^(.+?)&lt;\?.{0,40}?php(?:&nbsp;|\s)~', '~\?&gt;((?:</(font|span)>)*)$~'), '$1', $data, 2);
          }
        },
        'block_level' => false,
        'disabled_content' => '$1',
      ),
      array(
        'tag' => 'pre',
        'before' => '<pre>',
        'after' => '</pre>',
      ),
      array(
        'tag' => 'quote',
        'before' => '<blockquote><div class="cita">' . $txt['smf240'] . ': </div><div class="citacuerpo">',
        'after' => '</div></blockquote>',
        'block_level' => true,
      ),
      array(
        'tag' => 'quote',
        'parameters' => array(
          'author' => array('match' => '(.{1,192}?)', 'quoted' => true, 'validate' => 'parse_bbc'),
        ),
        'before' => '<blockquote><div class="cita">' . $txt['smf240'] . ' {author}: </div><div class="citacuerpo">',
        'after' => '</div></blockquote>',
        'block_level' => true,
      ),
      array(
        'tag' => 'quote',
        'type' => 'parsed_equals',
        'before' => '<blockquote><div class="cita">' . $txt['smf240'] . ' $1: </div><div class="citacuerpo">',
        'after' => '</div></blockquote>',
        'quoted' => 'optional',
        // Don't allow everything to be embedded with the author name.
        'parsed_tags_allowed' => array('url', 'iurl', 'ftp'),
        'block_level' => true,
      ),
      array(
        'tag' => 'red',
        'before' => '<span style="color: red;" class="bbc_color">',
        'after' => '</span>',
      ),
      array(
        'tag' => 'right',
        'before' => '<div style="text-align: right;">',
        'after' => '</div>',
        'block_level' => true,
      ),
      array(
        'tag' => 'rtl',
        'before' => '<div dir="rtl">',
        'after' => '</div>',
        'block_level' => true,
      ),
      array(
        'tag' => 's',
        'before' => '<del>',
        'after' => '</del>',
      ),

      /*
       * array(
       * 	'tag' => 'shadow',
       * 	'type' => 'unparsed_commas',
       * 	'test' => '[#0-9a-zA-Z\-]{3,12},(left|right|top|bottom|[0123]\d{0,2})\]',
       * 	'before' => $context['browser']['is_ie'] ? '<span style="display: inline-block; filter: Shadow(color=$1, direction=$2); height: 1.2em;">' : '<span style="text-shadow: $1 $2">',
       * 	'after' => '</span>',
       * 	'validate' => $context['browser']['is_ie'] ? function(&$tag, &$data, $disabled)
       * 	{
       * 		if ($data[1] == 'left')
       * 			$data[1] = 270;
       * 		elseif ($data[1] == 'right')
       * 			$data[1] = 90;
       * 		elseif ($data[1] == 'top')
       * 			$data[1] = 0;
       * 		elseif ($data[1] == 'bottom')
       * 			$data[1] = 180;
       * 		else
       * 			$data[1] = (int) $data[1];
       * 	} : function(&$tag, &$data, $disabled)
       * 	{
       * 		if ($data[1] == 'top' || (is_numeric($data[1]) && $data[1] < 50))
       * 			$data[1] = '0 -2px 1px';
       * 		elseif ($data[1] == 'right' || (is_numeric($data[1]) && $data[1] < 100))
       * 			$data[1] = '2px 0 1px';
       * 		elseif ($data[1] == 'bottom' || (is_numeric($data[1]) && $data[1] < 190))
       * 			$data[1] = '0 2px 1px';
       * 		elseif ($data[1] == 'left' || (is_numeric($data[1]) && $data[1] < 280))
       * 			$data[1] = '-2px 0 1px';
       * 		else
       * 			$data[1] = '1px 1px 1px';
       * 	},
       * ),
       */
      array(
        'tag' => 'size',
        'type' => 'unparsed_equals',
        'test' => '([1-9][\d]?p[xt]|small(?:er)?|large[r]?|x[x]?-(?:small|large)|medium|(0\.[1-9]|[1-9](\.[\d][\d]?)?)?em)\]',
        'before' => '<span style="font-size: $1;" class="bbc_size">',
        'after' => '</span>',
      ),
      array(
        'tag' => 'size',
        'type' => 'unparsed_equals',
        'test' => '[1-7]\]',
        'before' => '<span style="font-size: $1;" class="bbc_size">',
        'after' => '</span>',
        'validate' => function (&$tag, &$data, $disabled) {
          $sizes = array(1 => 0.7, 2 => 1.0, 3 => 1.35, 4 => 1.45, 5 => 2.0, 6 => 2.65, 7 => 3.95);
          $data = $sizes[$data] . 'em';
        },
      ),
      array(
        'tag' => 'sub',
        'before' => '<sub>',
        'after' => '</sub>',
      ),
      array(
        'tag' => 'sup',
        'before' => '<sup>',
        'after' => '</sup>',
      ),
      array(
        'tag' => 'table',
        'before' => '<table class="bbc_table">',
        'after' => '</table>',
        'trim' => 'inside',
        'require_children' => array('tr'),
        'block_level' => true,
      ),
      array(
        'tag' => 'td',
        'before' => '<td>',
        'after' => '</td>',
        'require_parents' => array('tr'),
        'trim' => 'outside',
        'block_level' => true,
        'disabled_before' => '',
        'disabled_after' => '',
      ),
      array(
        'tag' => 'time',
        'type' => 'unparsed_content',
        'content' => '$1',
        'validate' => function (&$tag, &$data, $disabled) {
          if (is_numeric($data))
            $data = timeformat($data);
          else
            $tag['content'] = '[time]$1[/time]';
        },
      ),
      array(
        'tag' => 'tr',
        'before' => '<tr>',
        'after' => '</tr>',
        'require_parents' => array('table'),
        'require_children' => array('td'),
        'trim' => 'both',
        'block_level' => true,
        'disabled_before' => '',
        'disabled_after' => '',
      ),
      array(
        'tag' => 'tt',
        'before' => '<tt class="bbc_tt">',
        'after' => '</tt>',
      ),
      array(
        'tag' => 'u',
        'before' => '<span class="bbc_u">',
        'after' => '</span>',
      ),
      array(
        'tag' => 'url',
        'type' => 'unparsed_content',
        'content' => '<a href="$1" class="bbc_link" target="_blank" rel="noopener noreferrer">$1</a>',
        'validate' => function (&$tag, &$data, $disabled) {
          if (strpos($data, 'http://') !== 0 && strpos($data, 'https://') !== 0)
            $data = 'http://' . $data;
        },
      ),
      array(
        'tag' => 'url',
        'type' => 'unparsed_equals',
        'before' => '<a href="$1" class="bbc_link" target="_blank" rel="noopener noreferrer">',
        'after' => '</a>',
        'validate' => function (&$tag, &$data, $disabled) {
          if (strpos($data, 'http://') !== 0 && strpos($data, 'https://') !== 0)
            $data = 'http://' . $data;
        },
        'disallow_children' => array('email', 'ftp', 'url', 'iurl'),
        'disabled_after' => ' ($1)',
      ),
      array(
        'tag' => 'white',
        'before' => '<span style="color: white;" class="bbc_color">',
        'after' => '</span>',
      ),
    );

    // Let mods add new BBC without hassle.
    call_integration_hook('integrate_bbc_codes', array(&$codes));

    // This is mainly for the bbc manager, so it's easy to add tags above.  Custom BBC should be added above this line.
    if ($message === false) {
      if (isset($temp_bbc))
        $bbc_codes = $temp_bbc;
      return $codes;
    }

    // So the parser won't skip them.
    $itemcodes = array(
      '*' => 'disc',
      '@' => 'disc',
      '+' => 'square',
      'x' => 'square',
      '#' => 'square',
      'o' => 'circle',
      'O' => 'circle',
      '0' => 'circle',
    );
    if (!isset($disabled['li']) && !isset($disabled['list'])) {
      foreach ($itemcodes as $c => $dummy)
        $bbc_codes[$c] = array();
    }

    // Inside these tags autolink is not recommendable.
    $no_autolink_tags = array(
      'url',
      'iurl',
      'ftp',
      'email',
    );

    // Shhhh!
    if (!isset($disabled['color'])) {
      $codes[] = array(
        'tag' => 'chrissy',
        'before' => '<span style="color: #cc0099;">',
        'after' => ' :-*</span>',
      );
      $codes[] = array(
        'tag' => 'kissy',
        'before' => '<span style="color: #cc0099;">',
        'after' => ' :-*</span>',
      );
    }

    foreach ($codes as $code) {
      // If we are not doing every tag only do ones we are interested in.
      if (empty($parse_tags) || in_array($code['tag'], $parse_tags))
        $bbc_codes[substr($code['tag'], 0, 1)][] = $code;
    }
    $codes = null;
  }

  // Shall we take the time to cache this?
  if ($cache_id != '' && !empty($modSettings['cache_enable']) && (($modSettings['cache_enable'] >= 2 && strlen($message) > 1000) || strlen($message) > 2400) && empty($parse_tags)) {
    // It's likely this will change if the message is modified.
    $cache_key = 'parse:' . $cache_id . '-' . md5(md5($message) . '-' . $smileys . (empty($disabled) ? '' : implode(',', array_keys($disabled))) . serialize($context['browser']) . $txt['lang_locale'] . $user_info['time_offset'] . $user_info['time_format']);

    if (($temp = cache_get_data($cache_key, 240)) != null)
      return $temp;

    $cache_t = microtime();
  }

  if ($smileys === 'print') {
    // [glow], [shadow], and [move] can't really be printed.
    $disabled['glow'] = true;
    $disabled['shadow'] = true;
    $disabled['move'] = true;

    // Colors can't well be displayed... supposed to be black and white.
    $disabled['color'] = true;
    $disabled['black'] = true;
    $disabled['blue'] = true;
    $disabled['white'] = true;
    $disabled['red'] = true;
    $disabled['green'] = true;
    $disabled['me'] = true;

    // Color coding doesn't make sense.
    $disabled['php'] = true;

    // Links are useless on paper... just show the link.
    $disabled['ftp'] = true;
    $disabled['url'] = true;
    $disabled['iurl'] = true;
    $disabled['email'] = true;
    $disabled['flash'] = true;

    // !!! Change maybe?
    if (!isset($_GET['images']))
      $disabled['img'] = true;

    // !!! Interface/setting to add more?
  }

  $open_tags = array();
  $message = strtr($message, array("\n" => '<br />'));

  // The non-breaking-space looks a bit different each time.
  $non_breaking_space = $context['utf8'] ? ($context['server']['complex_preg_chars'] ? '\x{A0}' : "\u{00A0}") : '\xA0';

  // This saves time by doing our break long words checks here.
  if (!empty($modSettings['fixLongWords']) && $modSettings['fixLongWords'] > 5) {
    if ($context['browser']['is_gecko'] || $context['browser']['is_konqueror'])
      $breaker = '<span style="margin: 0 -0.5ex 0 0;"> </span>';
    // Opera...
    elseif ($context['browser']['is_opera'])
      $breaker = '<span style="margin: 0 -0.65ex 0 -1px;"> </span>';
    // Internet Explorer...
    else
      $breaker = '<span style="width: 0; margin: 0 -0.6ex 0 -1px;"> </span>';

    // PCRE will not be happy if we don't give it a short.
    $modSettings['fixLongWords'] = (int) min(65535, $modSettings['fixLongWords']);
  }

  $pos = -1;
  while ($pos !== false) {
    $last_pos = isset($last_pos) ? max($pos, $last_pos) : $pos;
    $pos = strpos($message, '[', $pos + 1);

    // Failsafe.
    if ($pos === false || $last_pos > $pos)
      $pos = strlen($message) + 1;

    // Can't have a one letter smiley, URL, or email! (sorry.)
    if ($last_pos < $pos - 1) {
      // Make sure the $last_pos is not negative.
      $last_pos = max($last_pos, 0);

      // Pick a block of data to do some raw fixing on.
      $data = substr($message, $last_pos, $pos - $last_pos);

      // Take care of some HTML!
      if (!empty($modSettings['enablePostHTML']) && strpos($data, '&lt;') !== false) {
        $data = preg_replace('~&lt;a\s+href=((?:&quot;)?)((?:https?://|ftps?://|mailto:)\S+?)\1&gt;~i', '[url=$2]', $data);
        $data = preg_replace('~&lt;/a&gt;~i', '[/url]', $data);

        // <br /> should be empty.
        $empty_tags = array('br', 'hr');
        foreach ($empty_tags as $tag)
          $data = str_replace(array('&lt;' . $tag . '&gt;', '&lt;' . $tag . '/&gt;', '&lt;' . $tag . ' /&gt;'), '[' . $tag . ' /]', $data);

        // b, u, i, s, pre... basic tags.
        $closable_tags = array('b', 'u', 'i', 's', 'em', 'ins', 'del', 'pre', 'blockquote');
        foreach ($closable_tags as $tag) {
          $diff = substr_count($data, '&lt;' . $tag . '&gt;') - substr_count($data, '&lt;/' . $tag . '&gt;');
          $data = strtr($data, array('&lt;' . $tag . '&gt;' => '<' . $tag . '>', '&lt;/' . $tag . '&gt;' => '</' . $tag . '>'));

          if ($diff > 0)
            $data = substr($data, 0, -1) . str_repeat('</' . $tag . '>', $diff) . substr($data, -1);
        }

        // Do <img ... /> - with security... action= -> action-.
        preg_match_all('~&lt;img\s+src=((?:&quot;)?)((?:https?://|ftps?://)\S+?)\1(?:\s+alt=(&quot;.*?&quot;|\S*?))?(?:\s?/)?&gt;~i', $data, $matches, PREG_PATTERN_ORDER);
        if (!empty($matches[0])) {
          $replaces = array();
          foreach ($matches[2] as $match => $imgtag) {
            $alt = empty($matches[3][$match]) ? '' : ' alt=' . preg_replace('~^&quot;|&quot;$~', '', $matches[3][$match]);

            // Remove action= from the URL - no funny business, now.
            if (preg_match('~action(=|%3d)(?!dlattach)~i', $imgtag) != 0)
              $imgtag = preg_replace('~action(?:=|%3d)(?!dlattach)~i', 'action-', $imgtag);

            // Check if the image is larger than allowed.
            if (!empty($modSettings['max_image_width']) && !empty($modSettings['max_image_height'])) {
              list($width, $height) = url_image_size($imgtag);

              if (!empty($modSettings['max_image_width']) && $width > $modSettings['max_image_width']) {
                $height = (int) (($modSettings['max_image_width'] * $height) / $width);
                $width = $modSettings['max_image_width'];
              }

              if (!empty($modSettings['max_image_height']) && $height > $modSettings['max_image_height']) {
                $width = (int) (($modSettings['max_image_height'] * $width) / $height);
                $height = $modSettings['max_image_height'];
              }

              // Set the new image tag.
              $replaces[$matches[0][$match]] = '[img width=' . $width . ' height=' . $height . $alt . ']' . $imgtag . '[/img]';
            }
            else
              $replaces[$matches[0][$match]] = '[img' . $alt . ']' . $imgtag . '[/img]';
          }

          $data = strtr($data, $replaces);
        }
      }

      if (!empty($modSettings['autoLinkUrls'])) {
        // Are we inside tags that should be auto linked?
        $no_autolink_area = false;
        if (!empty($open_tags)) {
          foreach ($open_tags as $open_tag)
            if (in_array($open_tag['tag'], $no_autolink_tags))
              $no_autolink_area = true;
        }

        // Don't go backwards.
        // !!! Don't think is the real solution....
        $lastAutoPos = isset($lastAutoPos) ? $lastAutoPos : 0;
        if ($pos < $lastAutoPos)
          $no_autolink_area = true;
        $lastAutoPos = $pos;

        if (!$no_autolink_area) {
          // Parse any URLs.... have to get rid of the @ problems some things cause... stupid email addresses.
          if (!isset($disabled['url']) && (strpos($data, '://') !== false || strpos($data, 'www.') !== false) && strpos($data, '[url') === false) {
            // Switch out quotes really quick because they can cause problems.
            $data = strtr($data, array('&#039;' => "'", '&nbsp;' => $context['utf8'] ? "\u{00A0}" : "\xa0", '&quot;' => '>">', '"' => '<"<', '&lt;' => '<lt<'));

            // Only do this if the preg survives.
            if (is_string($result = preg_replace(array(
              "~(?<=[\s>\.(;'\"]|^)((?:http|https)://[\w\-_%@:|]+(?:\.[\w\-_%]+)*(?::\d+)?(?:/[\w\-_\~%\.@!,\?&;=#(){}+:'\\\\]*)*[/\w\-_\~%@\?;=#}\\\\])~i",
              "~(?<=[\s>\.(;'\"]|^)((?:ftp|ftps)://[\w\-_%@:|]+(?:\.[\w\-_%]+)*(?::\d+)?(?:/[\w\-_\~%\.@,\?&;=#(){}+:'\\\\]*)*[/\w\-_\~%@\?;=#}\\\\])~i",
              "~(?<=[\s>('<]|^)(www(?:\.[\w\-_]+)+(?::\d+)?(?:/[\w\-_\~%\.@!,\?&;=#(){}+:'\\\\]*)*[/\w\-_\~%@\?;=#}\\\\])~i"
            ), array(
              '[url]$1[/url]',
              '[ftp]$1[/ftp]',
              '[url=http://$1]$1[/url]'
            ), $data)))
              $data = $result;

            $data = strtr($data, array("'" => '&#039;', $context['utf8'] ? "\u{00A0}" : "\xa0" => '&nbsp;', '>">' => '&quot;', '<"<' => '"', '<lt<' => '&lt;'));
          }

          // Next, emails...
          if (!isset($disabled['email']) && filter_var($data, FILTER_VALIDATE_EMAIL) !== false && strpos($data, '[email') === false) {
            $data = preg_replace('~(?<=[\?\s' . $non_breaking_space . '\[\]()*\\\\;>]|^)([\w\-\.]{1,80}@[\w\-]+\.[\w\-\.]+[\w\-])(?=[?,\s' . $non_breaking_space . '\[\]()*\\\\]|$|<br />|&nbsp;|&gt;|&lt;|&quot;|&#039;|\.(?:\.|;|&nbsp;|\s|$|<br />))~' . ($context['utf8'] ? 'u' : ''), '[email]$1[/email]', $data);
            $data = preg_replace('~(?<=<br />)([\w\-\.]{1,80}@[\w\-]+\.[\w\-\.]+[\w\-])(?=[?\.,;\s' . $non_breaking_space . '\[\]()*\\\\]|$|<br />|&nbsp;|&gt;|&lt;|&quot;|&#039;)~' . ($context['utf8'] ? 'u' : ''), '[email]$1[/email]', $data);
          }
        }
      }

      $data = strtr($data, array("\t" => '&nbsp;&nbsp;&nbsp;'));

      if (!empty($modSettings['fixLongWords']) && $modSettings['fixLongWords'] > 5) {
        // The idea is, find words xx long, and then replace them with xx + space + more.
        if ($smcFunc['strlen']($data) > $modSettings['fixLongWords']) {
          // This is done in a roundabout way because $breaker has "long words" :P.
          $data = strtr($data, array($breaker => '< >', '&nbsp;' => $context['utf8'] ? "\u{00A0}" : "\xa0"));
          $data = preg_replace_callback(
            '~(?<=[>;:!? ' . $non_breaking_space . '\]()]|^)([\w' . ($context['utf8'] ? '\pL' : '') . '\.]{' . $modSettings['fixLongWords'] . ',})~' . ($context['utf8'] ? 'u' : ''),
            'word_break__preg_callback',
            $data
          );
          $data = strtr($data, array('< >' => $breaker, $context['utf8'] ? "\u{00A0}" : "\xa0" => '&nbsp;'));
        }
      }

      // If it wasn't changed, no copying or other boring stuff has to happen!
      if ($data != substr($message, $last_pos, $pos - $last_pos)) {
        $message = substr($message, 0, $last_pos) . $data . substr($message, $pos);

        // Since we changed it, look again in case we added or removed a tag.  But we don't want to skip any.
        $old_pos = strlen($data) + $last_pos;
        $pos = strpos($message, '[', $last_pos);
        $pos = $pos === false ? $old_pos : min($pos, $old_pos);
      }
    }

    // Are we there yet?  Are we there yet?
    if ($pos >= strlen($message) - 1)
      break;

    $tags = strtolower(substr($message, $pos + 1, 1));

    if ($tags == '/' && !empty($open_tags)) {
      $pos2 = strpos($message, ']', $pos + 1);
      if ($pos2 == $pos + 2)
        continue;
      $look_for = strtolower(substr($message, $pos + 2, $pos2 - $pos - 2));

      $to_close = array();
      $block_level = null;
      do {
        $tag = array_pop($open_tags);
        if (!$tag)
          break;

        if (!empty($tag['block_level'])) {
          // Only find out if we need to.
          if ($block_level === false) {
            array_push($open_tags, $tag);
            break;
          }

          // The idea is, if we are LOOKING for a block level tag, we can close them on the way.
          if (strlen($look_for) > 0 && isset($bbc_codes[$look_for[0]])) {
            foreach ($bbc_codes[$look_for[0]] as $temp)
              if ($temp['tag'] == $look_for) {
                $block_level = !empty($temp['block_level']);
                break;
              }
          }

          if ($block_level !== true) {
            $block_level = false;
            array_push($open_tags, $tag);
            break;
          }
        }

        $to_close[] = $tag;
      } while ($tag['tag'] != $look_for);

      // Did we just eat through everything and not find it?
      if ((empty($open_tags) && (empty($tag) || $tag['tag'] != $look_for))) {
        $open_tags = $to_close;
        continue;
      } elseif (!empty($to_close) && $tag['tag'] != $look_for) {
        if ($block_level === null && isset($look_for[0], $bbc_codes[$look_for[0]])) {
          foreach ($bbc_codes[$look_for[0]] as $temp)
            if ($temp['tag'] == $look_for) {
              $block_level = !empty($temp['block_level']);
              break;
            }
        }

        // We're not looking for a block level tag (or maybe even a tag that exists...)
        if (!$block_level) {
          foreach ($to_close as $tag)
            array_push($open_tags, $tag);
          continue;
        }
      }

      foreach ($to_close as $tag) {
        $message = substr($message, 0, $pos) . "\n" . $tag['after'] . "\n" . substr($message, $pos2 + 1);
        $pos += strlen($tag['after']) + 2;
        $pos2 = $pos - 1;

        // See the comment at the end of the big loop - just eating whitespace ;).
        if (!empty($tag['block_level']) && substr($message, $pos, 6) == '<br />')
          $message = substr($message, 0, $pos) . substr($message, $pos + 6);
        if (!empty($tag['trim']) && $tag['trim'] != 'inside' && preg_match('~(<br />|&nbsp;|\s)*~', substr($message, $pos), $matches) != 0)
          $message = substr($message, 0, $pos) . substr($message, $pos + strlen($matches[0]));
      }

      if (!empty($to_close)) {
        $to_close = array();
        $pos--;
      }

      continue;
    }

    // No tags for this character, so just keep going (fastest possible course.)
    if (!isset($bbc_codes[$tags]))
      continue;

    $inside = empty($open_tags) ? null : $open_tags[count($open_tags) - 1];
    $tag = null;
    foreach ($bbc_codes[$tags] as $possible) {
      // Not a match?
      if (strtolower(substr($message, $pos + 1, strlen($possible['tag']))) != $possible['tag'])
        continue;

      $next_c = substr($message, $pos + 1 + strlen($possible['tag']), 1);

      // A test validation?
      if (isset($possible['test']) && preg_match('~^' . $possible['test'] . '~', substr($message, $pos + 1 + strlen($possible['tag']) + 1)) == 0)
        continue;
      // Do we want parameters?
      elseif (!empty($possible['parameters'])) {
        if ($next_c != ' ')
          continue;
      } elseif (isset($possible['type'])) {
        // Do we need an equal sign?
        if (in_array($possible['type'], array('unparsed_equals', 'unparsed_commas', 'unparsed_commas_content', 'unparsed_equals_content', 'parsed_equals')) && $next_c != '=')
          continue;
        // Maybe we just want a /...
        if ($possible['type'] == 'closed' && $next_c != ']' && substr($message, $pos + 1 + strlen($possible['tag']), 2) != '/]' && substr($message, $pos + 1 + strlen($possible['tag']), 3) != ' /]')
          continue;
        // An immediate ]?
        if ($possible['type'] == 'unparsed_content' && $next_c != ']')
          continue;
      }
      // No type means 'parsed_content', which demands an immediate ] without parameters!
      elseif ($next_c != ']')
        continue;

      // Check allowed tree?
      if (isset($possible['require_parents']) && ($inside === null || !in_array($inside['tag'], $possible['require_parents'])))
        continue;
      elseif (isset($inside['require_children']) && !in_array($possible['tag'], $inside['require_children']))
        continue;
      // If this is in the list of disallowed child tags, don't parse it.
      elseif (isset($inside['disallow_children']) && in_array($possible['tag'], $inside['disallow_children']))
        continue;

      $pos1 = $pos + 1 + strlen($possible['tag']) + 1;

      // Quotes can have alternate styling, we do this php-side due to all the permutations of quotes.
      if ($possible['tag'] == 'quote') {
        // Start with standard
        $quote_alt = false;
        foreach ($open_tags as $open_quote) {
          // Every parent quote this quote has flips the styling
          if ($open_quote['tag'] == 'quote')
            $quote_alt = !$quote_alt;
        }
        // Add a class to the quote to style alternating blockquotes
        $possible['before'] = strtr($possible['before'], array('<blockquote>' => '<blockquote class="bbc_' . ($quote_alt ? 'alternate' : 'standard') . '_quote">'));
      }

      // This is long, but it makes things much easier and cleaner.
      if (!empty($possible['parameters'])) {
        $preg = array();
        foreach ($possible['parameters'] as $p => $info)
          $preg[] = '(\s+' . $p . '=' . (empty($info['quoted']) ? '' : '&quot;') . (isset($info['match']) ? $info['match'] : '(.+?)') . (empty($info['quoted']) ? '' : '&quot;') . ')' . (empty($info['optional']) ? '' : '?');

        // Okay, this may look ugly and it is, but it's not going to happen much and it is the best way of allowing any order of parameters but still parsing them right.
        $match = false;
        $orders = permute($preg);
        foreach ($orders as $p)
          if (preg_match('~^' . implode('', $p) . '\]~i', substr($message, $pos1 - 1), $matches) != 0) {
            $match = true;
            break;
          }

        // Didn't match our parameter list, try the next possible.
        if (!$match)
          continue;

        $params = array();
        for ($i = 1, $n = count($matches); $i < $n; $i += 2) {
          $key = strtok(ltrim($matches[$i]), '=');
          if (isset($possible['parameters'][$key]['value']))
            $params['{' . $key . '}'] = strtr($possible['parameters'][$key]['value'], array('$1' => $matches[$i + 1]));
          elseif (isset($possible['parameters'][$key]['validate']))
            $params['{' . $key . '}'] = $possible['parameters'][$key]['validate']($matches[$i + 1]);
          else
            $params['{' . $key . '}'] = $matches[$i + 1];

          // Just to make sure: replace any $ or { so they can't interpolate wrongly.
          $params['{' . $key . '}'] = strtr($params['{' . $key . '}'], array('$' => '&#036;', '{' => '&#123;'));
        }

        foreach ($possible['parameters'] as $p => $info) {
          if (!isset($params['{' . $p . '}']))
            $params['{' . $p . '}'] = '';
        }

        $tag = $possible;

        // Put the parameters into the string.
        if (isset($tag['before']))
          $tag['before'] = strtr($tag['before'], $params);
        if (isset($tag['after']))
          $tag['after'] = strtr($tag['after'], $params);
        if (isset($tag['content']))
          $tag['content'] = strtr($tag['content'], $params);

        $pos1 += strlen($matches[0]) - 1;
      }
      else
        $tag = $possible;
      break;
    }

    // Item codes are complicated buggers... they are implicit [li]s and can make [list]s!
    if ($smileys !== false && $tag === null && isset($itemcodes[substr($message, $pos + 1, 1)]) && substr($message, $pos + 2, 1) == ']' && !isset($disabled['list']) && !isset($disabled['li'])) {
      if (substr($message, $pos + 1, 1) == '0' && !in_array(substr($message, $pos - 1, 1), array(';', ' ', "\t", '>')))
        continue;
      $tag = $itemcodes[substr($message, $pos + 1, 1)];

      // First let's set up the tree: it needs to be in a list, or after an li.
      if ($inside === null || ($inside['tag'] != 'list' && $inside['tag'] != 'li')) {
        $open_tags[] = array(
          'tag' => 'list',
          'after' => '</ul>',
          'block_level' => true,
          'require_children' => array('li'),
          'disallow_children' => isset($inside['disallow_children']) ? $inside['disallow_children'] : null,
        );
        $code = '<ul class="bbc_list">';
      }
      // We're in a list item already: another itemcode?  Close it first.
      elseif ($inside['tag'] == 'li') {
        array_pop($open_tags);
        $code = '</li>';
      }
      else
        $code = '';

      // Now we open a new tag.
      $open_tags[] = array(
        'tag' => 'li',
        'after' => '</li>',
        'trim' => 'outside',
        'block_level' => true,
        'disallow_children' => isset($inside['disallow_children']) ? $inside['disallow_children'] : null,
      );

      // First, open the tag...
      $code .= '<li' . ($tag == '' ? '' : ' type="' . $tag . '"') . '>';
      $message = substr($message, 0, $pos) . "\n" . $code . "\n" . substr($message, $pos + 3);
      $pos += strlen($code) - 1 + 2;

      // Next, find the next break (if any.)  If there's more itemcode after it, keep it going - otherwise close!
      $pos2 = strpos($message, '<br />', $pos);
      $pos3 = strpos($message, '[/', $pos);
      if ($pos2 !== false && ($pos2 <= $pos3 || $pos3 === false)) {
        preg_match('~^(<br />|&nbsp;|\s|\[)+~', substr($message, $pos2 + 6), $matches);
        $message = substr($message, 0, $pos2) . "\n" . (!empty($matches[0]) && substr($matches[0], -1) == '[' ? '[/li]' : '[/li][/list]') . "\n" . substr($message, $pos2);

        $open_tags[count($open_tags) - 2]['after'] = '</ul>';
      }
      // Tell the [list] that it needs to close specially.
      else {
        // Move the li over, because we're not sure what we'll hit.
        $open_tags[count($open_tags) - 1]['after'] = '';
        $open_tags[count($open_tags) - 2]['after'] = '</li></ul>';
      }

      continue;
    }

    // Implicitly close lists and tables if something other than what's required is in them.  This is needed for itemcode.
    if ($tag === null && $inside !== null && !empty($inside['require_children'])) {
      array_pop($open_tags);

      $message = substr($message, 0, $pos) . "\n" . $inside['after'] . "\n" . substr($message, $pos);
      $pos += strlen($inside['after']) - 1 + 2;
    }

    // No tag?  Keep looking, then.  Silly people using brackets without actual tags.
    if ($tag === null)
      continue;

    // Propagate the list to the child (so wrapping the disallowed tag won't work either.)
    if (isset($inside['disallow_children']))
      $tag['disallow_children'] = isset($tag['disallow_children']) ? array_unique(array_merge($tag['disallow_children'], $inside['disallow_children'])) : $inside['disallow_children'];

    // Is this tag disabled?
    if (isset($disabled[$tag['tag']])) {
      if (!isset($tag['disabled_before']) && !isset($tag['disabled_after']) && !isset($tag['disabled_content'])) {
        $tag['before'] = !empty($tag['block_level']) ? '<div>' : '';
        $tag['after'] = !empty($tag['block_level']) ? '</div>' : '';
        $tag['content'] = isset($tag['type']) && $tag['type'] == 'closed' ? '' : (!empty($tag['block_level']) ? '<div>$1</div>' : '$1');
      } elseif (isset($tag['disabled_before']) || isset($tag['disabled_after'])) {
        $tag['before'] = isset($tag['disabled_before']) ? $tag['disabled_before'] : (!empty($tag['block_level']) ? '<div>' : '');
        $tag['after'] = isset($tag['disabled_after']) ? $tag['disabled_after'] : (!empty($tag['block_level']) ? '</div>' : '');
      }
      else
        $tag['content'] = $tag['disabled_content'];
    }

    // The only special case is 'html', which doesn't need to close things.
    if (!empty($tag['block_level']) && $tag['tag'] != 'html' && empty($inside['block_level'])) {
      $n = count($open_tags) - 1;
      while (empty($open_tags[$n]['block_level']) && $n >= 0)
        $n--;

      // Close all the non block level tags so this tag isn't surrounded by them.
      for ($i = count($open_tags) - 1; $i > $n; $i--) {
        $message = substr($message, 0, $pos) . "\n" . $open_tags[$i]['after'] . "\n" . substr($message, $pos);
        $pos += strlen($open_tags[$i]['after']) + 2;
        $pos1 += strlen($open_tags[$i]['after']) + 2;

        // Trim or eat trailing stuff... see comment at the end of the big loop.
        if (!empty($open_tags[$i]['block_level']) && substr($message, $pos, 6) == '<br />')
          $message = substr($message, 0, $pos) . substr($message, $pos + 6);
        if (!empty($open_tags[$i]['trim']) && $tag['trim'] != 'inside' && preg_match('~(<br />|&nbsp;|\s)*~', substr($message, $pos), $matches) != 0)
          $message = substr($message, 0, $pos) . substr($message, $pos + strlen($matches[0]));

        array_pop($open_tags);
      }
    }

    // No type means 'parsed_content'.
    if (!isset($tag['type'])) {
      // !!! Check for end tag first, so people can say "I like that [i] tag"?
      $open_tags[] = $tag;
      $message = substr($message, 0, $pos) . "\n" . $tag['before'] . "\n" . substr($message, $pos1);
      $pos += strlen($tag['before']) - 1 + 2;
    }
    // Don't parse the content, just skip it.
    elseif ($tag['type'] == 'unparsed_content') {
      $pos2 = stripos($message, '[/' . substr($message, $pos + 1, strlen($tag['tag'])) . ']', $pos1);
      if ($pos2 === false)
        continue;

      $data = substr($message, $pos1, $pos2 - $pos1);

      if (!empty($tag['block_level']) && substr($data, 0, 6) == '<br />')
        $data = substr($data, 6);

      if (isset($tag['validate']))
        $tag['validate']($tag, $data, $disabled);

      $code = strtr($tag['content'], array('$1' => $data));
      $message = substr($message, 0, $pos) . "\n" . $code . "\n" . substr($message, $pos2 + 3 + strlen($tag['tag']));

      $pos += strlen($code) - 1 + 2;
      $last_pos = $pos + 1;
    }
    // Don't parse the content, just skip it.
    elseif ($tag['type'] == 'unparsed_equals_content') {
      // The value may be quoted for some tags - check.
      if (isset($tag['quoted'])) {
        $quoted = substr($message, $pos1, 6) == '&quot;';
        if ($tag['quoted'] != 'optional' && !$quoted)
          continue;

        if ($quoted)
          $pos1 += 6;
      }
      else
        $quoted = false;

      $pos2 = strpos($message, $quoted == false ? ']' : '&quot;]', $pos1);
      if ($pos2 === false)
        continue;
      $pos3 = stripos($message, '[/' . substr($message, $pos + 1, strlen($tag['tag'])) . ']', $pos2);
      if ($pos3 === false)
        continue;

      $data = array(
        substr($message, $pos2 + ($quoted == false ? 1 : 7), $pos3 - ($pos2 + ($quoted == false ? 1 : 7))),
        substr($message, $pos1, $pos2 - $pos1)
      );

      if (!empty($tag['block_level']) && substr($data[0], 0, 6) == '<br />')
        $data[0] = substr($data[0], 6);

      // Validation for my parking, please!
      if (isset($tag['validate']))
        $tag['validate']($tag, $data, $disabled);

      $code = strtr($tag['content'], array('$1' => $data[0], '$2' => $data[1]));
      $message = substr($message, 0, $pos) . "\n" . $code . "\n" . substr($message, $pos3 + 3 + strlen($tag['tag']));
      $pos += strlen($code) - 1 + 2;
    }
    // A closed tag, with no content or value.
    elseif ($tag['type'] == 'closed') {
      $pos2 = strpos($message, ']', $pos);
      $message = substr($message, 0, $pos) . "\n" . $tag['content'] . "\n" . substr($message, $pos2 + 1);
      $pos += strlen($tag['content']) - 1 + 2;
    }
    // This one is sorta ugly... :/.  Unfortunately, it's needed for flash.
    elseif ($tag['type'] == 'unparsed_commas_content') {
      $pos2 = strpos($message, ']', $pos1);
      if ($pos2 === false)
        continue;
      $pos3 = stripos($message, '[/' . substr($message, $pos + 1, strlen($tag['tag'])) . ']', $pos2);
      if ($pos3 === false)
        continue;

      // We want $1 to be the content, and the rest to be csv.
      $data = explode(',', ',' . substr($message, $pos1, $pos2 - $pos1));
      $data[0] = substr($message, $pos2 + 1, $pos3 - $pos2 - 1);

      if (isset($tag['validate']))
        $tag['validate']($tag, $data, $disabled);

      $code = $tag['content'];
      foreach ($data as $k => $d)
        $code = strtr($code, array('$' . ($k + 1) => trim($d)));
      $message = substr($message, 0, $pos) . "\n" . $code . "\n" . substr($message, $pos3 + 3 + strlen($tag['tag']));
      $pos += strlen($code) - 1 + 2;
    }
    // This has parsed content, and a csv value which is unparsed.
    elseif ($tag['type'] == 'unparsed_commas') {
      $pos2 = strpos($message, ']', $pos1);
      if ($pos2 === false)
        continue;

      $data = explode(',', substr($message, $pos1, $pos2 - $pos1));

      if (isset($tag['validate']))
        $tag['validate']($tag, $data, $disabled);

      // Fix after, for disabled code mainly.
      foreach ($data as $k => $d)
        $tag['after'] = strtr($tag['after'], array('$' . ($k + 1) => trim($d)));

      $open_tags[] = $tag;

      // Replace them out, $1, $2, $3, $4, etc.
      $code = $tag['before'];
      foreach ($data as $k => $d)
        $code = strtr($code, array('$' . ($k + 1) => trim($d)));
      $message = substr($message, 0, $pos) . "\n" . $code . "\n" . substr($message, $pos2 + 1);
      $pos += strlen($code) - 1 + 2;
    }
    // A tag set to a value, parsed or not.
    elseif ($tag['type'] == 'unparsed_equals' || $tag['type'] == 'parsed_equals') {
      // The value may be quoted for some tags - check.
      if (isset($tag['quoted'])) {
        $quoted = substr($message, $pos1, 6) == '&quot;';
        if ($tag['quoted'] != 'optional' && !$quoted)
          continue;

        if ($quoted)
          $pos1 += 6;
      }
      else
        $quoted = false;

      $pos2 = strpos($message, $quoted == false ? ']' : '&quot;]', $pos1);
      if ($pos2 === false)
        continue;

      $data = substr($message, $pos1, $pos2 - $pos1);

      // Validation for my parking, please!
      if (isset($tag['validate']))
        $tag['validate']($tag, $data, $disabled);

      // For parsed content, we must recurse to avoid security problems.
      if ($tag['type'] != 'unparsed_equals')
        $data = parse_bbc($data, !empty($tag['parsed_tags_allowed']) ? false : true, '', !empty($tag['parsed_tags_allowed']) ? $tag['parsed_tags_allowed'] : array());

      $tag['after'] = strtr($tag['after'], array('$1' => $data));

      $open_tags[] = $tag;

      $code = strtr($tag['before'], array('$1' => $data));
      $message = substr($message, 0, $pos) . "\n" . $code . "\n" . substr($message, $pos2 + ($quoted == false ? 1 : 7));
      $pos += strlen($code) - 1 + 2;
    }

    // If this is block level, eat any breaks after it.
    if (!empty($tag['block_level']) && substr($message, $pos + 1, 6) == '<br />')
      $message = substr($message, 0, $pos + 1) . substr($message, $pos + 7);

    // Are we trimming outside this tag?
    if (!empty($tag['trim']) && $tag['trim'] != 'outside' && preg_match('~(<br />|&nbsp;|\s)*~', substr($message, $pos + 1), $matches) != 0)
      $message = substr($message, 0, $pos + 1) . substr($message, $pos + 1 + strlen($matches[0]));
  }

  // Close any remaining tags.
  while ($tag = array_pop($open_tags))
    $message .= "\n" . $tag['after'] . "\n";

  // Parse the smileys within the parts where it can be done safely.
  if ($smileys === true) {
    $message_parts = explode("\n", $message);
    for ($i = 0, $n = count($message_parts); $i < $n; $i += 2)
      parsesmileys($message_parts[$i]);

    $message = implode('', $message_parts);
  }

  // No smileys, just get rid of the markers.
  else
    $message = strtr($message, array("\n" => ''));

  if (substr($message, 0, 1) == ' ')
    $message = '&nbsp;' . substr($message, 1);

  // Cleanup whitespace.
  $message = strtr($message, array('  ' => ' &nbsp;', "\r" => '', "\n" => '<br />', '<br /> ' => '<br />&nbsp;', '&#13;' => "\n"));

  // Cache the output if it took some time...
  if (isset($cache_key, $cache_t) && array_sum(explode(' ', microtime())) - array_sum(explode(' ', $cache_t)) > 0.05)
    cache_put_data($cache_key, $message, 240);

  // If this was a force parse revert if needed.
  if (!empty($parse_tags)) {
    if (empty($temp_bbc))
      $bbc_codes = array();
    else {
      $bbc_codes = $temp_bbc;
      unset($temp_bbc);
    }
  }

  return $message;
}

// Process functions of an integration hook.
function call_integration_hook($hook, $parameters = array()) {
  global $modSettings;

  $results = array();
  if (empty($modSettings[$hook]))
    return $results;

  $functions = explode(',', $modSettings[$hook]);

  // Loop through each function.
  foreach ($functions as $function) {
    $function = trim($function);
    $call = strpos($function, '::') !== false ? explode('::', $function) : $function;

    // Is it valid?
    if (is_callable($call))
      $results[$function] = call_user_func_array($call, $parameters);
  }

  return $results;
}

function get_proxied_url($url) {
  global $boardurl, $image_proxy_enabled, $image_proxy_secret, $user_info;

  // Only use the proxy if enabled, and never for robots
  if (empty($image_proxy_enabled) || !empty($user_info['possibly_robot']))
    return $url;

  $parsedurl = parse_url($url);

  // Don't bother with HTTPS URLs, schemeless URLs, or obviously invalid URLs
  if (empty($parsedurl['scheme']) || empty($parsedurl['host']) || empty($parsedurl['path']) || $parsedurl['scheme'] === 'https')
    return $url;

  // We don't need to proxy our own resources
  if ($parsedurl['host'] === parse_url($boardurl, PHP_URL_HOST))
    return strtr($url, array('http://' => 'https://'));

  // By default, use SMF's own image proxy script
  $proxied_url = strtr($boardurl, array('http://' => 'https://')) . '/proxy.php?request=' . urlencode($url) . '&hash=' . hash_hmac('sha1', $url, $image_proxy_secret);

  // Allow mods to easily implement an alternative proxy
  call_integration_hook('integrate_proxy', array($url, &$proxied_url));

  return $proxied_url;
}

function parsesmileys(&$message) {
  global $modSettings, $db_prefix, $tranfer1, $txt, $user_info, $context;
  static $smileyfromcache = array(), $smileytocache = array();

  if ($user_info['smiley_set'] == 'none')
    return;

  if (empty($smileyfromcache)) {
    if (empty($modSettings['smiley_enable'])) {
      $smileysfrom = '';
      $smileysto = '';
      $smileysdescs = '';
    } else {
      if (($temp = cache_get_data('parsing_smileys', 480)) == null) {
        $result = db_query("
          SELECT code, filename, description
          FROM {$db_prefix}smileys", __FILE__, __LINE__);
        $smileysfrom = array();
        $smileysto = array();
        $smileysdescs = array();
        while ($row = mysqli_fetch_assoc($result)) {
          $smileysfrom[] = $row['code'];
          $smileysto[] = $row['filename'];
          $smileysdescs[] = $row['description'];
        }
        mysqli_free_result($result);

        cache_put_data('parsing_smileys', array($smileysfrom, $smileysto, $smileysdescs), 480);
      }
      else
        list($smileysfrom, $smileysto, $smileysdescs) = $temp;
    }

    $non_breaking_space = $context['utf8'] ? ($context['server']['complex_preg_chars'] ? '\x{A0}' : pack('C*', 0xC2, 0xA0)) : '\xA0';

    for ($i = 0, $n = count($smileysfrom); $i < $n; $i++) {
      $smileyfromcache[] = '/(?<=[>:\?\.\s' . $non_breaking_space . '[\]()*\\\\;]|^)(' . preg_quote($smileysfrom[$i], '/') . '|' . preg_quote(htmlspecialchars($smileysfrom[$i], ENT_QUOTES), '/') . ')(?=[^[:alpha:]0-9]|$)/' . ($context['utf8'] ? 'u' : '');
      // Escape a bunch of smiley-related characters in the description so it doesn't get a double dose :P.
      $smileytocache[] = '<img class="png" src="' . $tranfer1 . '/emoticones/' . $smileysto[$i] . '" alt="' . strtr(htmlspecialchars($smileysdescs[$i]), array(':' => '&#58;', '(' => '&#40;', ')' => '&#41;', '$' => '&#36;', '[' => '&#091;')) . '" border="0" />';
    }
  }
  $message = preg_replace($smileyfromcache, $smileytocache, $message);
}

function highlight_php_code($code) {
  global $context;

  $code = un_htmlspecialchars(strtr($code, array('<br />' => "\n", "\t" => 'SMF_TAB();', '&#91;' => '[')));
  $oldlevel = error_reporting(0);

  if (@version_compare(PHP_VERSION, '4.2.0') == -1) {
    ob_start();
    @highlight_string($code);
    $buffer = str_replace(array("\n", "\r"), '', ob_get_contents());
    ob_end_clean();
  }
  else
    $buffer = str_replace(array("\n", "\r"), '', @highlight_string($code, true));

  error_reporting($oldlevel);
  $buffer = preg_replace('~CW_TAB(</(font|span)><(font color|span style)="[^"]*?">)?\(\);~', "<pre style=\"display: inline;\">\t</pre>", $buffer);

  return strtr($buffer, array("'" => '&#039;', '<code>' => '', '</code>' => ''));
}

function writeLog($force = false) {
  global $db_prefix, $ID_MEMBER, $user_info, $user_settings, $sc, $modSettings, $settings, $topic, $board, $boardir, $sourcedir;

  if (!empty($settings['display_who_viewing']) && ($topic || $board)) {
    $force = true;
    if ($topic) {
      if (isset($_SESSION['last_topic_id']) && $_SESSION['last_topic_id'] == $topic)
        $force = false;
      $_SESSION['last_topic_id'] = $topic;
    }
  }
  if (!empty($_SESSION['log_time']) && $_SESSION['log_time'] >= (time() - 8) && !$force)
    return;

  if (!empty($modSettings['who_enabled'])) {
    $serialized = $_GET + array('USER_AGENT' => $_SERVER['HTTP_USER_AGENT']);
    unset($serialized['sesc']);
    $serialized = addslashes(serialize($serialized));
  }
  else
    $serialized = '';
  $session_id = $user_info['is_guest'] ? 'ip' . $user_info['ip'] : session_id();
  $do_delete = cache_get_data('log_online-update', 10) < time() - 10;
  $menos = time() - 120;

  if (!empty($_SESSION['log_time']) && $_SESSION['log_time'] >= time() - $modSettings['lastActive'] * 20) {
    if ($do_delete) {
      db_query("
      DELETE FROM {$db_prefix}log_online
      WHERE logTime < " . $menos . "
      AND session != '$session_id'", __FILE__, __LINE__);

      cache_put_data('log_online-update', time(), 10);
    }

    db_query("
    UPDATE {$db_prefix}log_online
    SET logTime = " . time() . ", ip = IFNULL(INET_ATON('$user_info[ip]'), 0), url = '$serialized'
    WHERE session = '$session_id'
    LIMIT 1", __FILE__, __LINE__);

    if (db_affected_rows() == 0) {
      $_SESSION['log_time'] = 0;
    }
  } else {
    $_SESSION['log_time'] = 0;
  }

  if (empty($_SESSION['log_time'])) {
    if ($do_delete || !empty($ID_MEMBER)) {
      // TO-DO: ¿Error en campo logTime? ¿Debe ser de tipo timestamp?

      /*
       * db_query("
       *   DELETE FROM {$db_prefix}log_online
       *   WHERE " . ($do_delete ? "logTime < $menos" : '') . ($do_delete && !empty($ID_MEMBER) ? ' OR ' : '') . (empty($ID_MEMBER) ? '' : "ID_MEMBER = $ID_MEMBER"), __FILE__, __LINE__);
       */
    }

    db_query('
    ' . ($do_delete ? 'INSERT IGNORE' : 'REPLACE') . " INTO {$db_prefix}log_online (session, ID_MEMBER, logTime, ip, url)
    VALUES ('$session_id', $ID_MEMBER, " . time() . ", IFNULL(INET_ATON('$user_info[ip]'), 0), '$serialized')", __FILE__, __LINE__);
  }

  $_SESSION['log_time'] = time();
  if (empty($_SESSION['timeOnlineUpdated']))
    $_SESSION['timeOnlineUpdated'] = time();

  if (!empty($user_info['last_login']) && $user_info['last_login'] < time() - 60) {
    if (time() - $_SESSION['timeOnlineUpdated'] > 60 * 15)
      $_SESSION['timeOnlineUpdated'] = time();

    $user_settings['totalTimeLoggedIn'] += time() - $_SESSION['timeOnlineUpdated'];
    updateMemberData($ID_MEMBER, array('lastLogin' => time(), 'memberIP' => "'" . $user_info['ip'] . "'", 'memberIP2' => "'" . $_SERVER['BAN_CHECK_IP'] . "'", 'totalTimeLoggedIn' => $user_settings['totalTimeLoggedIn']));

    if (!empty($modSettings['cache_enable']) && $modSettings['cache_enable'] >= 2)
      cache_put_data('user_settings-' . $ID_MEMBER, $user_settings, 60);

    $user_info['total_time_logged_in'] += time() - $_SESSION['timeOnlineUpdated'];
    $_SESSION['timeOnlineUpdated'] = time();
  }
}

function redirectexit($setLocation = '', $refresh = false) {
  global $scripturl, $context, $modSettings, $db_show_debug;

  $add = preg_match('~^(ftp|http)[s]?://~', $setLocation) == 0 && substr($setLocation, 0, 6) != 'about:';
  if (WIRELESS) {
    if ($add)
      $setLocation = $scripturl . '?' . $setLocation;
    $char = strpos($setLocation, '?') === false ? '?' : ';';
    if (strpos($setLocation, '#') == !false)
      $setLocation = strtr($setLocation, array('#' => $char . WIRELESS_PROTOCOL . '#'));
    else
      $setLocation .= $char . WIRELESS_PROTOCOL;
  }
  elseif ($add)
    $setLocation = $scripturl . ($setLocation != '' ? '?' . $setLocation : '');

  // Put the session ID in.
  if (defined('SID') && SID != '')
    $setLocation = preg_replace('/^' . preg_quote($scripturl, '/') . '(?!\?' . preg_quote(SID, '/') . ')(\?)?/', $scripturl . '?' . SID . ';', $setLocation);
  // Keep that debug in their for template debugging!
  elseif (isset($_GET['debug']))
    $setLocation = preg_replace('/^' . preg_quote($scripturl, '/') . '(\?)?/', $scripturl . '?debug;', $setLocation);

  if (!empty($modSettings['queryless_urls']) && (empty($context['server']['is_cgi']) || @ini_get('cgi.fix_pathinfo') == 1) && !empty($context['server']['is_apache'])) {
    if (defined('SID') && SID != '')
      $setLocation = preg_replace('/^' . preg_quote($scripturl, '/') . '\?(?:' . SID . ';)((?:board|topic)=[^#]+?)(#[^"]*?)?$/e', "\$scripturl . '/' . strtr('\$1', '&;=', '//,') . '.html\$2?' . SID", $setLocation);
    else
      $setLocation = preg_replace('/^' . preg_quote($scripturl, '/') . '\?((?:board|topic)=[^#"]+?)(#[^"]*?)?$/e', "\$scripturl . '/' . strtr('\$1', '&;=', '//,') . '.html\$2'", $setLocation);
  }

  if (isset($modSettings['integrate_redirect']) && function_exists($modSettings['integrate_redirect']))
    $modSettings['integrate_redirect']($setLocation, $refresh);
  if ($refresh && !WIRELESS)
    header('Refresh: 0; URL=' . strtr($setLocation, array(' ' => '%20', ';' => '%3b')));
  else
    header('Location: ' . str_replace(' ', '%20', $setLocation));

  // Debugging.
  if (isset($db_show_debug) && $db_show_debug === true)
    $_SESSION['debug_redirect'] = &$GLOBALS['db_cache'];

  obExit(false);
}

function obExit($header = null, $do_footer = null, $from_index = false) {
  global $context, $settings, $modSettings, $txt;
  static $header_done = false, $footer_done = false;

  trackStats();

  $do_header = $header === null ? !$header_done : $header;
  if ($do_footer === null)
    $do_footer = $do_header;

  if ($do_header) {
    ob_start('ob_sessrewrite');
    if ((isset($_REQUEST['debug']) || isset($_REQUEST['xml']) || (WIRELESS && WIRELESS_PROTOCOL == 'wap')) && in_array($txt['lang_locale'], array('UTF-8', 'ISO-8859-1')))
      ob_start('validate_unicode__recursive');

    if (!empty($settings['output_buffers']) && is_string($settings['output_buffers']))
      $buffers = explode(',', $settings['output_buffers']);
    elseif (!empty($settings['output_buffers']))
      $buffers = $settings['output_buffers'];
    else
      $buffers = array();

    if (isset($modSettings['integrate_buffer']))
      $buffers = array_merge(explode(',', $modSettings['integrate_buffer']), $buffers);

    if (!empty($buffers))
      foreach ($buffers as $buffer_function) {
        if (function_exists(trim($buffer_function)))
          ob_start(trim($buffer_function));
      }

    // Display the screen in the logical order.
    template_header();
    $header_done = true;
  }
  if ($do_footer) {
    if (WIRELESS && !isset($context['sub_template']))
      fatal_lang_error('wireless_error_notyet', false);

    loadSubTemplate(isset($context['sub_template']) ? $context['sub_template'] : 'main');

    if (!$footer_done) {
      $footer_done = true;
      template_footer();

      if (!isset($_REQUEST['xml']))
        db_debug_junk();
    }
  }

  $_SESSION['USER_AGENT'] = $_SERVER['HTTP_USER_AGENT'];

  if (isset($modSettings['integrate_exit'], $context['template_layers']) && in_array('main', $context['template_layers']) && function_exists($modSettings['integrate_exit']))
    call_user_func($modSettings['integrate_exit'], $do_footer && !WIRELESS);

  if (!$from_index || WIRELESS)
    exit;
}

function adminIndex($area) {
  global $txt, $context, $scripturl, $sc, $modSettings, $user_info, $urlSep, $settings, $boardurl;

  if (($user_info['is_admin'] || $user_info['is_mods'])) {
    loadLanguage('Admin');
    loadTemplate('Admin');

    $context['admin_areas']['forum'] = array(
      'title' => $txt[427],
      'areas' => array(
        'index' => '<a href="' . $boardurl . '/moderacion/">' . $txt[208] . '</a>',
        'denuncias' => '<a href="' . $boardurl . '/moderacion/denuncias/">Denuncias</a>',
        'Anuncios' => '<span onclick="Boxy.load(\'' . $boardurl . '/web/cw-TEMPanuncio.php\', {title: \'Anuncio\'});" class="pointer">Anuncio</span>',
        'recargar' => '<span onclick="Boxy.load(\'' . $boardurl . '/web/cw-TEMPrecargarPTS.php\', {title: \'Recargar puntos\'});" class="pointer">Recargar puntos</span>',
        'vr2965' => '<a href="' . $boardurl . '/moderacion/comunicacion-mod/">BatiCueva</a>',
        'tyc9' => '<a href="' . $boardurl . '/web/cw-TEMPverUcoment.php" title="&Uacute;ltimos 30 comentarios escritos" class="boxy">Comentarios en posts</a>',
        'tyc11' => '<a href="' . $boardurl . '/web/cw-TEMPverUcomentIMG.php" title="&Uacute;ltimos 30 comentarios escritos en im&aacute;genes" class="boxy">Comentarios en im&aacute;genes</a>',
        'ban_members' => '<a href="' . $boardurl . '/moderacion/edit-user/ban/">Historial de baneados</a>'
      )
    );

    $context['admin_areas']['Com'] = array(
      'title' => 'Comunidades',
      'areas' => array(
        'ComunidadesAdm' => '<a href="' . $boardurl . '/moderacion/comunidades/">Comunidades eliminadas</a>',
        'ComunidadesAdm2' => '<a href="' . $boardurl . '/moderacion/comunidades/temas/">Temas eliminados</a>',
      )
    );

    if ($context['user']['is_admin']) {
      $context['admin_areas']['config'] = array(
        'title' => 'Administraci&oacute;n',
        'areas' => array(
          'tyc200' => '<a href="' . $scripturl . '?' . $urlSep . '=manageboards">Categor&iacute;as</a>',
          'modify_settings' => '<a href="' . $boardurl . '/moderacion/web/config/">Configuraci&oacute;n de la web</a>',
          'tyc16' => '<a href="' . $boardurl . '/web/cw-TEMPeditarTags.php" class="boxy" title="Editor tags">Editor tags</a>',
          'tyc20' => '<a href="' . $boardurl . '/moderacion/emoticones/">Emoticones</a>',
          'tyc12' => '<a href="' . $boardurl . '/moderacion/pms/">Mensajes privados</a>',
          'edit_groups' => '<a href="' . $scripturl . '?' . $urlSep . '=membergroups;">' . $txt[8] . '</a>',
          'view_members' => '<a href="' . $scripturl . '?' . $urlSep . '=viewmembers">' . $txt[5] . '</a>',
          'edit_permissions' => '<a href="' . $scripturl . '?' . $urlSep . '=permissions">' . $txt['edit_permissions'] . '</a>',
          'tyc6' => '<a href="' . $boardurl . '/moderacion/muro/">Muros</a>'
        )
      );
    }

    foreach ($context['admin_areas'] as $id => $section)
      if (isset($section[$area]))
        $context['admin_section'] = $id;
    $context['admin_area'] = $area;

    $context['template_layers'][] = 'admin';
  } else {
    die();
  }
}

function trackStats($stats = array()) {
  global $db_prefix, $modSettings;
  static $cache_stats = array();

  if (empty($modSettings['trackStats']))
    return false;
  if (!empty($stats))
    return $cache_stats = array_merge($cache_stats, $stats);
  elseif (empty($cache_stats))
    return false;

  $setStringUpdate = '';
  foreach ($cache_stats as $field => $change) {
    $setStringUpdate .= '
      ' . $field . ' = ' . ($change === '+' ? $field . ' + 1' : $change) . ',';

    if ($change === '+')
      $cache_stats[$field] = 1;
  }

  // DEPRECADO
  // $date = strftime('%Y-%m-%d', forum_time(false));
  $date = (new DateTime())->setTimestamp(forum_time(false))->format('Y-m-d');

  db_query("
    UPDATE {$db_prefix}log_activity
    SET" . substr($setStringUpdate, 0, -1) . "
    WHERE date = '$date'
    LIMIT 1", __FILE__, __LINE__);
  if (db_affected_rows() == 0) {
    db_query("
      INSERT IGNORE INTO {$db_prefix}log_activity
        (date, " . implode(', ', array_keys($cache_stats)) . ")
      VALUES ('$date', " . implode(', ', $cache_stats) . ')', __FILE__, __LINE__);
  }

  $cache_stats = array();
  return true;
}

function estadisticastopic($d = '') {
  global $db_prefix, $modSettings;

  if (empty($modSettings['trackStats']))
    return false;

  $date = strftime('%Y-%m-%d', forum_time(false));

  $lvccct = db_query("
    SELECT date
    FROM ({$db_prefix}log_activity)
    WHERE date = '$date'
    ORDER BY date DESC
    LIMIT 1", __FILE__, __LINE__);

  while ($asserr = mysqli_fetch_assoc($lvccct)) {
    $topicsss = $asserr['date'];
  }

  if (!empty($topicsss)) {
    if (!$d) {
      db_query("
    UPDATE {$db_prefix}log_activity
    SET topics=topics+1 , posts=posts+1
    WHERE date = '$date'
    LIMIT 1", __FILE__, __LINE__);
    } else {
      db_query("
    UPDATE {$db_prefix}log_activity
    SET registers=registers+1
    WHERE date = '$date'
    LIMIT 1", __FILE__, __LINE__);
    }
  } elseif (empty($topicsss)) {
    if (!$d) {
      db_query("
      INSERT IGNORE INTO {$db_prefix}log_activity
      (date,hits, topics, posts,registers,mostOn)
      VALUES ('$date', 0,1,1,0,0)", __FILE__, __LINE__);
    } else {
      db_query("
      INSERT IGNORE INTO {$db_prefix}log_activity
      (date,hits, topics, posts,registers,mostOn)
      VALUES ('$date', 0,0,0,1,0)", __FILE__, __LINE__);
    }
  }

  return true;
}

function setupThemeContext() {
  global $modSettings, $user_info, $db_prefix, $ID_MEMBER, $context, $settings, $txt, $urlSep, $maintenance;

  $context['allow_admin'] = allowedTo(array('admin_forum', 'summary', 'manage_boards', 'manage_permissions', 'moderate_forum', 'manage_membergroups', 'manage_bans', 'send_mail', 'edit_news', 'manage_attachments', 'manage_smileys'));
  $context['allow_edit_profile'] = !$user_info['is_guest'] && allowedTo(array('profile_view_own', 'profile_view_any', 'profile_identity_own', 'profile_identity_any', 'profile_extra_own', 'profile_extra_any', 'profile_remove_own', 'profile_remove_any', 'moderate_forum', 'manage_membergroups'));
  $context['current_action'] = isset($_GET[$urlSep]) ? $_GET[$urlSep] : '';
  $context['show_news'] = !empty($settings['enable_news']);

  if (!isset($context['page_title']))
    $context['page_title'] = '';
}

function template_header() {
  global $context, $settings;

  setupThemeContext();

  foreach ($context['template_layers'] as $layer) {
    loadSubTemplate($layer . '_above', true);
  }

  if (isset($settings['use_default_images']) && $settings['use_default_images'] == 'defaults' && isset($settings['default_template'])) {
    $settings['theme_url'] = $settings['default_theme_url'];
    $settings['images_url'] = $settings['default_images_url'];
    $settings['theme_dir'] = $settings['default_theme_dir'];
  }
}

function text2words($text, $max_chars = 20, $encrypt = false) {
  global $func, $context;

  $words = preg_replace('~([\x0B\0' . ($context['utf8'] ? ($context['server']['complex_preg_chars'] ? '\x{A0}' : pack('C*', 0xC2, 0xA0)) : '\xA0') . '\t\r\s\n(){}\[\]<>!@$%^*.,:+=`\~\?/\\\\]|&(amp|lt|gt|quot);)+~' . ($context['utf8'] ? 'u' : ''), ' ', strtr($text, array('<br />' => ' ')));
  $words = un_htmlspecialchars($func['strtolower']($words));

  $words = explode(' ', $words);

  if ($encrypt) {
    $possible_chars = array_flip(array_merge(range(46, 57), range(65, 90), range(97, 122)));
    $returned_ints = array();
    foreach ($words as $word) {
      if (($word = trim($word, "-_'")) !== '') {
        $encrypted = substr(crypt($word, 'uk'), 2, $max_chars);
        $total = 0;
        for ($i = 0; $i < $max_chars; $i++)
          $total += $possible_chars[ord($encrypted[$i])] * pow(63, $i);
        $returned_ints[] = $max_chars == 4 ? min($total, 16777215) : $total;
      }
    }
    return array_unique($returned_ints);
  } else {
    // Trim characters before and after and add slashes for database insertion.
    $returned_words = array();
    foreach ($words as $word)
      if (($word = trim($word, "-_'")) !== '')
        $returned_words[] = addslashes($max_chars === null ? $word : substr($word, 0, $max_chars));

    // Filter out all words that occur more than once.
    return array_unique($returned_words);
  }
}

function logAction($action, $extra = array()) {
  global $db_prefix, $ID_MEMBER, $modSettings, $user_info;

  if (!is_array($extra))
    trigger_error("logAction(): data is not an array with action '" . $action . "'", E_USER_NOTICE);

  if (isset($extra['topic']) && !is_numeric($extra['topic']))
    trigger_error("logAction(): data's topic is not an number", E_USER_NOTICE);
  if (isset($extra['member']) && !is_numeric($extra['member']))
    trigger_error("logAction(): data's member is not an number", E_USER_NOTICE);

  if (!empty($modSettings['modlog_enabled'])) {
    db_query("
      INSERT INTO {$db_prefix}log_actions
        (logTime, ID_MEMBER, ip, action, extra)
      VALUES (" . time() . ", $ID_MEMBER, SUBSTRING('$user_info[ip]', 1, 16), SUBSTRING('$action', 1, 30),
        SUBSTRING('" . addslashes(serialize($extra)) . "', 1, 65534))", __FILE__, __LINE__);

    return db_insert_id();
  }

  return false;
}

function spamProtection($error_type) {
  return false;
}

function url_image_size($url) {
  return false;
}

function determineTopicClass(&$topic_context) {
  return false;
}

function template_rawdata() {}
function theme_copyright($get_it = false) {}

function template_footer() {
  global $context;

  foreach (array_reverse($context['template_layers']) as $layer) {
    loadSubTemplate($layer . '_below', true);
  }
}

function db_debug_junk() {}
function host_from_ip($ip) {}
function getAttachmentFilename($filename, $attachment_id, $new = false) {}
function create_button() {}

?>