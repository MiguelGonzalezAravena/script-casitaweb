<?php
// Página de Rodrigo Zaupa (rigo@casitaweb.net)
if (!defined('CasitaWeb!-PorRigo')) {
  die(base64_decode('d3d3LmNhc2l0YXdlYi5uZXQgLSByaWdv'));
}

function RemindMe() {
  global $txt, $context, $scripturl;

  loadLanguage('Profile');
  loadTemplate('Reminder');

  $context['page_title'] = $txt[669];

  $subActions = array(
    'mail' => 'RemindMail',
    'setpassword' => 'setPassword',
    'setpassword2' => 'setPassword2'
  );

  if (isset($_REQUEST['sa']) && isset($subActions[$_REQUEST['sa']])) {
    $subActions[$_REQUEST['sa']]();
  }
}

function RemindMail() {
  global $db_prefix, $context, $txt, $scripturl, $sourcedir, $user_info, $webmaster_email;
  global $context, $mbname, $webmaster_email, $txt, $sourcedir, $modSettings, $scripturl;

  $userlimpio = seguridad($_POST['user']);

  if (!isset($userlimpio) || empty($userlimpio)) {
    fatal_error('Error con el Nick');
  }

  $request = db_query("
    SELECT ID_MEMBER, realName, memberName, emailAddress, is_activated, validation_code
    FROM {$db_prefix}members
    WHERE realName = '$userlimpio'
    LIMIT 1", __FILE__, __LINE__);

  if (mysqli_num_rows($request) == 0) {
    fatal_error('Error con el nick.');
  }

  $row = mysqli_fetch_assoc($request);
  mysqli_free_result($request);

  captcha(2);

  $row['emailAddress'] = trim($row['emailAddress']);

  if (empty($row['emailAddress'])) {
    fatal_error('Error con el nick.');
  }

  $password = substr(preg_replace('/\W/', '', md5(md5(md5(md5(rand()))))), 0, 10);

  updateMemberData($row['ID_MEMBER'], array('validation_code' => "'" . substr(md5(md5(md5(md5($password)))), 0, 10) . "'"));

  require_once ($sourcedir . '/Subs-Post.php');

  // TO-DO: Probar funcionalidad sendmail
  sendmail($row['emailAddress'], 'Recuperar mi contrase&ntilde;a',
    sprintf(
      "Se ha enviado este mensaje porque se ha aplicado la funci&oacute;n \"Recuperar mi contrase&ntilde;a\" en tu cuenta. Para establecer una nueva contrase&ntilde;a haz clic en el siguiente enlace:\n"
    ) .
    sprintf(
      "<a href='http://casitaweb.net/recuperar-pass/user-$row[ID_MEMBER]/id-$password'>http://casitaweb.net/recuperar-pass/user-$row[ID_MEMBER]/id-$password</a>"));

  $context += array(
    'page_title' => &$txt[194],
    'sub_template' => 'sent',
    'description' => &$txt['reminder_sent']
  );
}

function setPassword() {
  global $txt, $context;

  if (!isset($_REQUEST['code'])) {
    fatal_lang_error(1);
  }

  $context += array(
    'page_title' => 'Correo enviado',
    'sub_template' => 'set_password',
    'code' => $_REQUEST['code'],
    'memID' => (int) $_REQUEST['u']);
}

function setPassword2() {
  global $db_prefix, $context, $txt, $modSettings, $sourcedir;

  if (!empty($context['user']['name'])) {
    fatal_error('Ya iniciaste sesi&oacute;n como: <b>' . $context['user']['name'] . '</b>');
  }

  if (empty($_POST['u']) || !isset($_POST['passwrd1']) || !isset($_POST['passwrd2'])) {
    fatal_lang_error(1, false);
  }

  $_POST['u'] = isset($_POST['u']) ? (int) $_POST['u'] : 0;

  if ($_POST['passwrd1'] != $_POST['passwrd2']) {
    fatal_lang_error(213, false);
  }

  if (empty($_POST['passwrd1'])) {
    fatal_lang_error(91, false);
  }

  loadLanguage('Login');

  $request = db_query("
    SELECT validation_code, memberName, emailAddress
    FROM {$db_prefix}members
    WHERE ID_MEMBER = {$_POST['u']}
    AND is_activated = 1
    AND validation_code != ''
    LIMIT 1", __FILE__, __LINE__);

  if (mysqli_num_rows($request) == 0) {
    fatal_lang_error('invalid_userid', false);
  }

  list($realCode, $username, $email) = mysqli_fetch_row($request);
  mysqli_free_result($request);

  require ($sourcedir . '/Subs-Auth.php');

  $passwordError = validatePassword($_POST['passwrd1'], $username, array($email));

  if ($passwordError != null) {
    fatal_lang_error('profile_error_password_' . $passwordError, false);
  }

  if (empty($_POST['code']) || substr($realCode, 0, 10) != substr(md5(md5(md5(md5($_POST['code'])))), 0, 10)) {
    fatal_error('El c&oacute;digo no es v&aacute;lido.', false);
  }

  updateMemberData(
    $_POST['u'],
    array(
      'validation_code' => "''",
      'passwd' => "'" . sha1(strtolower($username) . $_POST['passwrd1']) . "'"
    )
  );

  if (isset($modSettings['integrate_reset_pass']) && function_exists($modSettings['integrate_reset_pass'])) {
    call_user_func($modSettings['integrate_reset_pass'], $username, $username, $_POST['passwrd1']);
  }

  fatal_error('Su contrase&ntilde;a ha cambiado correctamente.');
}

function secretAnswerInput() {}
function secretAnswer2() {}

?>