<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php'); 

global $db_prefix, $user_info, $user_settings;
global $ID_MEMBER, $modSettings, $context;
global $sourcedir, $boardurl;

require_once($sourcedir . '/Subs-Auth.php');
require_once($sourcedir . '/LogInOut.php');

$userlimpio = seguridad($_POST['nick']);
$passrlimpio = seguridad($_POST['pass']);

if (!$user_info['is_guest']) {
  die('0: <div class="noesta">Ya iniciastes sesi&oacute;n.</div>');
}

if (isset($_SESSION['failed_login']) && $_SESSION['failed_login'] >= $modSettings['failed_login_threshold'] * 3) {
  die('0: <div class="noesta">Cuenta incorrecta.</div>');
}

$modSettings['cookieTime'] = 3153600;
$context['default_username'] = isset($_POST['nick']) ? htmlspecialchars(stripslashes($_POST['nick'])) : '';
$context['default_password'] = '';
$context['never_expire'] = $modSettings['cookieTime'] == 525600 || $modSettings['cookieTime'] == 3153600;

if (!isset($userlimpio) || empty($userlimpio)) {
  die('0: <div class="noesta">Cuenta incorrecta 1.</div>');
}

if ((!isset($passrlimpio) || empty($passrlimpio))) {
  die('0: <div class="noesta">Cuenta incorrecta 2.</div>');
}

if (preg_match('~[<>&"\'=\\\]~', $userlimpio) != 0) {
  die('0: <div class="noesta">Cuenta incorrecta 3.</div>');
}

$request = db_query("
  SELECT passwd, ID_MEMBER, ID_GROUP, is_activated, emailAddress, memberName, passwordSalt
  FROM {$db_prefix}members
  WHERE realName = '$userlimpio'
  LIMIT 1", __FILE__, __LINE__);

if (mysqli_num_rows($request) == 0) {
  die('0: <div class="noesta">Cuenta incorrecta 4.</div>');
}

$user_settings = mysqli_fetch_assoc($request);
mysqli_free_result($request);

$activation_status = $user_settings['is_activated'] > 10 ? $user_settings['is_activated'] - 10 : $user_settings['is_activated'];

if ($activation_status == 5) {
  die('0: <div class="noesta-am">Aceptacion: <a href="' . $boardurl . '/conectar/acepta-' . $user_settings['ID_MEMBER'] . '">A</a>.</div>');
} else if ($activation_status == 3) {
  die('0: <div class="noesta-am">Esperando activaci&oacute;.</div>');
} else if ($activation_status != 1) {
  die('0: <div class="noesta-am">Tu cuenta est&aacute; desactivada porque se le cambi&oacute; el correo.
 <br />Si no te lleg&oacute; el correo de confirmaci&oacute;n puedes reenviarlo entrando <a href="' . $boardurl . '/reactivar-' . $user_settings['ID_MEMBER'] . '">AC&Aacute;</a>.</div>');}

$sha_passwd = sha1(strtolower($user_settings['memberName']) . un_htmlspecialchars(stripslashes($passrlimpio)));

if ($user_settings['passwd'] != $sha_passwd) {
  $other_passwords = array();

  if ($user_settings['passwordSalt'] == '') {
    $other_passwords[] = crypt($passrlimpio, substr($passrlimpio, 0, 2));
    $other_passwords[] = crypt($passrlimpio, substr($user_settings['passwd'], 0, 2));
    $other_passwords[] = md5($passrlimpio);
    $other_passwords[] = sha1($passrlimpio);
    $other_passwords[] = md5_hmac($passrlimpio, strtolower($user_settings['memberName']));
    $other_passwords[] = md5($passrlimpio . strtolower($user_settings['memberName']));
    $other_passwords[] = $passrlimpio;
    $other_passwords[] = crypt(md5($passrlimpio), md5($passrlimpio));

    if (strlen($user_settings['passwd']) == 64 && function_exists('mhash') && defined('MHASH_SHA256')) {
      $other_passwords[] = bin2hex(mhash(MHASH_SHA256, $passrlimpio));
    }
  } else if (strlen($user_settings['passwd']) == 32) {
    $other_passwords[] = md5(md5($passrlimpio) . $user_settings['passwordSalt']);
    $other_passwords[] = md5(md5($user_settings['passwordSalt']) . md5($passrlimpio));
  }

  $other_passwords[] = sha1(strtolower($user_settings['memberName']) . addslashes(un_htmlspecialchars(stripslashes($passrlimpio))));

  require_once($sourcedir . '/Subs-Compat.php');

  $other_passwords[] = sha1_casitaweb(strtolower($user_settings['memberName']).un_htmlspecialchars(stripslashes($_REQUEST['passwrd'])));

  if (in_array($user_settings['passwd'], $other_passwords)) {
    $user_settings['passwd'] = $sha_passwd;
    $user_settings['passwordSalt'] = substr(md5(rand()), 0, 4);
    updateMemberData($user_settings['ID_MEMBER'], array('passwd' => '\'' . $user_settings['passwd'] . '\'', 'passwordSalt' => '\'' . $user_settings['passwordSalt'] . '\''));
  } else {
    var_dump($other_passwords);
    $_SESSION['failed_login'] = @$_SESSION['failed_login'] + 1;

    if ($_SESSION['failed_login'] >= 5) {
      echo '2: <div class="noesta">Intentaste demasiadas veces.<br /> Si te olvidaste de tu contrase&ntilde;a puedes recuperarla con esta funci&oacute;n <a href="' . $boardurl . '/recuperar-pass/">AC&Aacute;</a></div>';
      exit();
      die();
    } else {
      die('0: <div class="noesta">Cuenta incorrecta 5 / ' . $user_settings['passwd'] . '.</div>');
    }
  }
} else if (empty($user_settings['passwordSalt'])) {
  $user_settings['passwordSalt'] = substr(md5(rand()), 0, 4);
  updateMemberData($user_settings['ID_MEMBER'], array('passwordSalt' => '\'' . $user_settings['passwordSalt'] . '\''));
}

// ESTA BAN
$request = db_query("
  SELECT reason, expire_time, ip, ban_time
  FROM {$db_prefix}ban_groups
  WHERE name = '$userlimpio'
  LIMIT 1", __FILE__, __LINE__);

while ($row = mysqli_fetch_assoc($request)) {
  $rason = nohtml1(nohtml($row['reason']));
  $ban_time = hace($row['ban_time']);
  $Sxpirate = $row['expire_time'];
  $rehabilitacion = $row['expire_time'] === null ? 'Indefinido' : ($row['expire_time'] < time() ? '' : '' . (int) ceil(($row['expire_time'] - time()) / (60 * 60 * 24)) . '&nbsp;d&iacute;a(s)');

  if(!empty($rehabilitacion)) {
    die('2: <div class="noesta" style="font-size:11px;"><b class="error">Cuenta suspendida!!!</b><br/><b>Causa:</b> ' . $rason . '<br />' . $ban_time . '<br /><b>Su expiraci&oacute;n:</b> ' . $rehabilitacion . '</div>');
  }
}

mysqli_free_result($request);
// FIN EST BAN

$username = $user_settings['realName'];
$ID_MEMBER = $user_settings['ID_MEMBER'];

setLoginCookie(60 * $modSettings['cookieTime'], $user_settings['ID_MEMBER'], sha1($user_settings['passwd'] . $user_settings['passwordSalt']));

if (isset($_SESSION['failed_login'])) {
    unset($_SESSION['failed_login']);
}

$user_info['is_guest'] = false;
$user_info['is_admin'] = $user_settings['ID_GROUP'] == 1;
    
if ($user_info['is_admin']) {
  $_SESSION['admin_time'] = time();
  unset($_SESSION['just_registered']);
}

unset($_SESSION['language']);
unset($_SESSION['ID_THEME']);
updateMemberData($ID_MEMBER, array('lastLogin' => time(), 'memberIP' => '\'' . $user_info['ip'] . '\'', 'memberIP2' => '\'' . $_SERVER['BAN_CHECK_IP'] . '\''));

db_query("
  DELETE FROM {$db_prefix}log_online
  WHERE session = 'ip$user_info[ip]'
  LIMIT 1", __FILE__, __LINE__);

$_SESSION['log_time'] = 0;
unset($_POST); 

die('1: Ok.');

?>