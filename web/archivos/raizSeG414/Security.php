<?php
// Página de Rodrigo Zaupa (rigo@casitaweb.net)
if (!defined('CasitaWeb!-PorRigo')) {
  die(base64_decode('d3d3LmNhc2l0YXdlYi5uZXQgLSByaWdv'));
}

function validateSession() {}

function is_not_guest($q = '', $h = '') {
  global $user_info, $txt, $context, $tranfer1, $boardurl;

  if (!$user_info['is_guest']) {
    return;
  }

  unset($_GET['cw1']);
  unset($_GET['post']);

  writeLog(true);

  $context['page_title'] = isset($context['page_title']) ? $context['page_title'] : $txt[18];

  if (empty($h)) {
    echo template_main_above();
  }

  echo '
    <div style="width: 922px;">
      <div style="height: 330px; background: #fff url(\'' . $tranfer1 . '/backReg.gif\') repeat-x; padding: 1px; border: solid 1px #D5CCC3;">
        <div style="border: solid 1px #FFF; padding: 25px;">';

  if (!empty($q)) {
    echo '<div class="noesta">' . $q . '</div>';
  }

  echo '
        <div style="width: 600px; float: left;">
          <span style="font-size: 40px; color: #606060;">Reg&iacute;strate</span>
          <div style="padding-top: 25px; padding-left: 25px; color: #A5A5A5; height: 135px;">
            <b>
              <div style="margin-bottom: 3px;">*  Comparte tus Posts</div>
              <div style="margin-bottom: 3px;">*  Ve perfiles y agrega nuevos amigos</div>
              <div style="margin-bottom: 3px;">*  Comenta cualquier tipo de posts</div>
              <div style="margin-bottom: 3px;">*  Crea tu propia comunidad</div>
              <div style="margin-bottom: 3px;">*  &Uacute;nete a todas las comunidades</div>
              <div style="margin-bottom: 3px;">*  Comparte tus fotos</div>
              <div style="margin-bottom: 3px;">*  Califica posts e im&aacute;genes</div>
              <div style="margin-bottom: 3px;">*  Ten organizados tus posts e im&aacute;genes favoritas</div>
            </b>
            <div style="clear: both"></div>
          </div>
          <div style="padding-top: 20px; text-align: right; width: 400px;">
            <input class="login" style="font-size: 19px;" type="submit" onclick="location.href=\'' . $boardurl . '/registrarse/\'" value="&iexcl;&iexcl;&iexcl;Registrarse!!!" />
          </div>
        </div>
        <div style="width: 264px; float: left;">
          <span style="font-size: 40px; color: #606060;">Iniciar sesi&oacute;n</span>
          <form action="javascript:loginSeguridad();">
            <b>
              <div style="padding-top: 25px; padding-left: 10px; height: 135px;">
                <div id="hd_loginbox2">
                  <div style="display: none;" id="login_cargando2">
                    <img alt="" src="' . $tranfer1 . '/icons/cargando.gif" width="16px" height="16px" />
                  </div>
                  <div style="display: none;" id="login_error2"></div>
                  <div class="login_cuerpo2">
                    <div>
                      <label>Nick:</label>
                      <br />
                      <input style="width: 200px;" maxlength="64" name="nick" id="nickname" onfocus="foco(this);" onblur="no_foco(this);" class="loginuserid" type="text" />
                      <br />
                      <label>Contrase&ntilde;a:</label>
                      <br />
                      <input style="width: 200px;" maxlength="64" name="pass" id="password" onfocus="foco(this);" onblur="no_foco(this);" class="loginpasswd" type="password" />
                      <div style="clear: both"></div>
                    </div>
                    <div style="clear: both"></div>
                  </div>
                  <div style="clear: both"></div>
                </div>
                <div style="clear:both"></div>
                <div style="padding-top: 20px; text-align: right;">
                  <p>
                    <input style="font-size: 19px;" class="login" type="submit" value="Conectarse" />
                  </p>
                  <a href="' . $boardurl . '/recuperar-pass/" class="loginforgotpass">&#191;Has olvidado tu contrase&ntilde;a?</a>
                </div>
              </div>
            </b>
          </form>
        </div>  
      </div>
    </div>';

  echo template_main_below();

  die();
  exit();
}

function is_not_banned() {
  global $user_info, $user_settings, $db_prefix;

  if (!$user_info['is_guest']) {
    $request = db_query("
      SELECT reason, expire_time, ip, ban_time
      FROM {$db_prefix}ban_groups
      WHERE name = '{$user_settings['realName']}'
      LIMIT 1", __FILE__, __LINE__);

    while ($row = mysqli_fetch_assoc($request)) {
      $rason = nohtml1(nohtml($row['reason']));
      $ban_time = hace($row['ban_time']);
      $Sxpirate = $row['expire_time'];
      $rehabilitacion = $row['expire_time'] === null ? 'Indefinido' : ($row['expire_time'] < time() ? '' : '' . (int) ceil(($row['expire_time'] - time()) / (60 * 60 * 24)) . '&nbsp;d&iacute;a(s)');

      if (!empty($rehabilitacion)) {
        PostAccionado('Cuenta suspendida', '<b>Causa:</b> ' . $rason . '<br /><b>Su expiraci&oacute;n:</b> ' . $rehabilitacion . '<br />' . $ban_time);
        die();
      }
    }

    mysqli_free_result($request);
  }

  return true;
}

function banPermissions() {}
function log_ban($ban_ids = array(), $email = null) {}
function isBannedEmail($email, $restriction, $error) {}

function checkSession($type = 'post', $from_action = '', $is_fatal = true) {
  global $sc, $modSettings, $boardurl;

  if ($type == 'post' && (!isset($_POST['sc']) || $_POST['sc'] != $sc))
    $error = 'smf304';
  // How about $_GET['sesc']?
  elseif ($type == 'get' && (!isset($_GET['sesc']) || $_GET['sesc'] != $sc))
    $error = 'smf305';
  // Or can it be in either?
  elseif ($type == 'request' && (!isset($_GET['sesc']) || $_GET['sesc'] != $sc) && (!isset($_POST['sc']) || $_POST['sc'] != $sc))
    $error = 'smf305';

  // Verify that they aren't changing user agents on us - that could be bad.
  if ((!isset($_SESSION['USER_AGENT']) || $_SESSION['USER_AGENT'] != $_SERVER['HTTP_USER_AGENT']) && empty($modSettings['disableCheckUA']))
    $error = 'smf305';

  // Make sure a page with session check requirement is not being prefetched.
  if (isset($_SERVER['HTTP_X_MOZ']) && $_SERVER['HTTP_X_MOZ'] == 'prefetch') {
    ob_end_clean();
    header('HTTP/1.1 403 Forbidden');
    die;
  }

  // Check the referring site - it should be the same server at least!
  $referrer = isset($_SERVER['HTTP_REFERER']) ? @parse_url($_SERVER['HTTP_REFERER']) : array();
  if (!empty($referrer['host'])) {
    if (strpos($_SERVER['HTTP_HOST'], ':') !== false)
      $real_host = substr($_SERVER['HTTP_HOST'], 0, strpos($_SERVER['HTTP_HOST'], ':'));
    else
      $real_host = $_SERVER['HTTP_HOST'];

    $parsed_url = parse_url($boardurl);

    // Are global cookies on?  If so, let's check them ;).
    if (!empty($modSettings['globalCookies'])) {
      if (preg_match('~(?:[^\.]+\.)?([^\.]{3,}\..+)\z~i', $parsed_url['host'], $parts) == 1)
        $parsed_url['host'] = $parts[1];

      if (preg_match('~(?:[^\.]+\.)?([^\.]{3,}\..+)\z~i', $referrer['host'], $parts) == 1)
        $referrer['host'] = $parts[1];

      if (preg_match('~(?:[^\.]+\.)?([^\.]{3,}\..+)\z~i', $real_host, $parts) == 1)
        $real_host = $parts[1];
    }

    // Okay: referrer must either match parsed_url or real_host.
    if (isset($parsed_url['host']) && strtolower($referrer['host']) != strtolower($parsed_url['host']) && strtolower($referrer['host']) != strtolower($real_host)) {
      $error = 'smf306';
      $log_error = true;
    }
  }

  // Well, first of all, if a from_action is specified you'd better have an old_url.
  if (!empty($from_action) && (!isset($_SESSION['old_url']) || preg_match('~[?;&]action=' . $from_action . '([;&]|$)~', $_SESSION['old_url']) == 0)) {
    $error = 'smf306';
    $log_error = true;
  }

  if (strtolower($_SERVER['HTTP_USER_AGENT']) == 'hacker')
    fatal_error('No podes estar aca.', false);
  if (!isset($error))
    return '';
  elseif ($is_fatal)
    fatal_lang_error($error, isset($log_error));
  else
    return $error;
  trigger_error(base64_decode('d3d3LmNhc2l0YXdlYi5uZXQgLSByaWdv'), E_USER_ERROR);
}

function checkSubmitOnce($action, $is_fatal = true) {
  global $context;

  if (!isset($_SESSION['forms']))
    $_SESSION['forms'] = array();

  if ($action == 'register') {
    $context['form_sequence_number'] = 0;
    while (empty($context['form_sequence_number']) || in_array($context['form_sequence_number'], $_SESSION['forms']))
      $context['form_sequence_number'] = mt_rand(1, 16000000);
  }
  // Check whether the submitted number can be found in the session.
  elseif ($action == 'check') {
    if (!isset($_REQUEST['seqnum']))
      return true;
    elseif (!in_array($_REQUEST['seqnum'], $_SESSION['forms'])) {
      $_SESSION['forms'][] = (int) $_REQUEST['seqnum'];
      return true;
    }
    elseif ($is_fatal)
      fatal_lang_error('error_form_already_submitted', false);
    else
      return false;
  }
  // Don't check, just free the stack number.
  elseif ($action == 'free' && isset($_REQUEST['seqnum']) && in_array($_REQUEST['seqnum'], $_SESSION['forms']))
    $_SESSION['forms'] = array_diff($_SESSION['forms'], array($_REQUEST['seqnum']));
  elseif ($action != 'free')
    trigger_error("checkSubmitOnce(): Invalid action '" . $action . "'", E_USER_WARNING);
}

// Check the user's permissions.
function allowedTo($permission, $boards = null)
{
  global $user_info, $db_prefix, $modSettings, $ID_MEMBER;

  if (empty($permission))
    return true;

  // You're never allowed to do something if your data hasn't been loaded yet!
  if (empty($user_info))
    return false;

  // Administrators are supermen :P.
  if ($user_info['is_admin'])
    return true;

  // Are we checking the _current_ board, or some other boards?
  if ($boards === null) {
    // Check if they can do it.
    if (!is_array($permission) && in_array($permission, $user_info['permissions']))
      return true;
    // Search for any of a list of permissions.
    elseif (is_array($permission) && count(array_intersect($permission, $user_info['permissions'])) != 0)
      return true;
    // You aren't allowed, by default.
    else
      return false;
  }
  elseif (!is_array($boards))
    $boards = array($boards);

  // Determine which permission mode is still acceptable.
  if (empty($modSettings['permission_enable_by_board']) && !in_array('moderate_board', $user_info['permissions'])) {
    // Make an array of the permission.
    $temp = is_array($permission) ? $permission : array($permission);

    if (in_array('post_reply_own', $temp) || in_array('post_reply_any', $temp))
      $max_allowable_mode = 3;
    elseif (in_array('post_new', $temp))
      $max_allowable_mode = 2;
    elseif (in_array('poll_post', $temp))
      $max_allowable_mode = 0;
  }

  $request = db_query("
    SELECT MIN(bp.addDeny) AS addDeny
    FROM ({$db_prefix}boards AS b, {$db_prefix}board_permissions AS bp)
    WHERE b.ID_BOARD IN (" . implode(', ', $boards) . ')' . (isset($max_allowable_mode) ? "
      AND b.permission_mode <= $max_allowable_mode" : '') . '
      AND bp.ID_BOARD = ' . (empty($modSettings['permission_enable_by_board']) ? '0' : 'IF(b.permission_mode = 1, b.ID_BOARD, 0)') . '
      AND bp.ID_GROUP IN (' . implode(', ', $user_info['groups']) . ', 3)
      AND bp.permission ' . (is_array($permission) ? "IN ('" . implode("', '", $permission) . "')" : " = '$permission'") . '
    GROUP BY b.ID_BOARD', __FILE__, __LINE__);

  // Make sure they can do it on all of the boards.
  if (mysqli_num_rows($request) != count($boards))
    return false;

  $result = true;
  while ($row = mysqli_fetch_assoc($request))
    $result &= !empty($row['addDeny']);
  mysqli_free_result($request);

  // If the query returned 1, they can do it... otherwise, they can't.
  return $result;
}

// Fatal error if they cannot...
function isAllowedTo($permission, $boards = null)
{
  global $user_info, $txt;

  static $heavy_permissions = array(
    'admin_forum',
    'manage_attachments',
    'manage_smileys',
    'manage_boards',
    'edit_news',
    'moderate_forum',
    'manage_bans',
    'manage_membergroups',
    'manage_permissions',
  );

  // Make it an array, even if a string was passed.
  $permission = is_array($permission) ? $permission : array($permission);

  // Check the permission and return an error...
  if (!allowedTo($permission, $boards)) {
    $error_permission = array_shift($permission);
    $_GET['action'] = '';
    $_GET['board'] = '';
    $_GET['topic'] = '';
    writeLog(true);

    fatal_lang_error('cannot_' . $error_permission, false);

    trigger_error('...', E_USER_ERROR);
  }

  // If you're doing something on behalf of some "heavy" permissions, validate your session.
  // (take out the heavy permissions, and if you can't do anything but those, you need a validated session.)
  if (!allowedTo(array_diff($permission, $heavy_permissions), $boards))
    validateSession();
}

// Return the boards a user has a certain (board) permission on. (array(0) if all.)
function boardsAllowedTo($permission)
{
  global $db_prefix, $ID_MEMBER, $user_info, $modSettings;

  // Administrators are all powerful, sorry.
  if ($user_info['is_admin'])
    return array(0);

  // All groups the user is in except 'moderator'.
  $groups = array_diff($user_info['groups'], array(3));
  if (empty($modSettings['permission_enable_by_board']) && !in_array('moderate_board', $user_info['permissions'])) {
    $needed_level = array(
      'post_reply_own' => 3,
      'post_reply_any' => 3,
      'post_new' => 2,
      'poll_post' => 0,
    );
    if (isset($needed_level[$permission]))
      $max_allowable_mode = $needed_level[$permission];
  }

  $request = db_query("
    SELECT b.ID_BOARD, b.permission_mode, bp.addDeny
    FROM ({$db_prefix}boards AS b, {$db_prefix}board_permissions AS bp)
    WHERE bp.ID_BOARD = " . (empty($modSettings['permission_enable_by_board']) ? '0' : 'IF(b.permission_mode = 1, b.ID_BOARD, 0)') . '
      AND bp.ID_GROUP IN (' . implode(', ', $groups) . ", 3)
      AND bp.permission = '$permission'", __FILE__, __LINE__);
  $boards = array();
  $deny_boards = array();
  while ($row = mysqli_fetch_assoc($request)) {
    if (empty($row['addDeny']))
      $deny_boards[] = $row['ID_BOARD'];
    else
      $boards[] = $row['ID_BOARD'];
  }
  mysqli_free_result($request);
  $boards = array_values(array_diff($boards, $deny_boards));
  return $boards;
}

function is_admin() {
  isAllowedTo('admin_forum');
}

?>