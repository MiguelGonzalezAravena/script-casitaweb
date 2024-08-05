<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $context, $txt, $modSettings, $mbname;
global $sourcedir, $scripturl, $boardurl, $db_prefix;
global $ID_MEMBER, $user_info, $user_settings, $user_profile;
global $cookiename, $newpassemail, $validationCode;

$realname = isset($_POST['memberName']) ? seguridad($_POST['memberName']) : '';
$puntos = isset($_POST['puntos']) ? (int) $_POST['puntos'] : 0;
$nombre = isset($_POST['nombre']) ? seguridad($_POST['nombre']) : '';
$recibir = isset($_POST['recibir']) ? (int) $_POST['recibir'] : 0;
$gender = isset($_POST['gender']) ? (int) $_POST['gender'] : 0;
$quienver = isset($_POST['quienve']) ? (int) $_POST['quienve'] : 0;
$dia = isset($_POST['bday2']) ? (int) $_POST['bday2'] : 0;
$mes = isset($_POST['bday1']) ? (int) $_POST['bday1'] : 0;
$ano = isset($_POST['bday3']) ? (int) $_POST['bday3'] : 0;

loadTheme(0);

if ($user_info['is_guest']) {
  fatal_error('Funcionalidad exclusiva de usuarios registrados.', false);
}

loadLanguage('Profile');

require_once($sourcedir . '/Profile.php');

$sa_allowed = array(
  'cuenta' => array(array('manage_membergroups', 'profile_identity_any', 'profile_identity_own'), array('manage_membergroups', 'profile_identity_any'), 'post', true),
  'perfil' => array(array('profile_extra_any', 'profile_extra_own'), array('profile_extra_any'), 'post'),
  'activar' => array(array(), array('moderate_forum'), 'get'),
);

if (empty($_REQUEST['sa']) || !isset($sa_allowed[$_REQUEST['sa']])) {
  fatal_lang_error(453, false);
}

$profile_vars = array();
$post_errors = array();
$newpassemail = false;
$_POST = htmltrim__recursive($_POST);
$_POST = stripslashes__recursive($_POST);
$_POST = htmlspecialchars__recursive($_POST);
$_POST = addslashes__recursive($_POST);
$memberResult = loadMemberData((int) $_REQUEST['userID'], false, 'profile');

if (!is_array($memberResult)) {
  fatal_lang_error(453, false);
}

list($memID) = $memberResult;

if (!($user_info['is_admin'] || $user_info['is_mods'])) {
  if ($memID == 1) {
    fatal_error('No tienes los permisos necesarios para realizar esta acci&oacute;n.', false);
  }
}

if ($ID_MEMBER == $memID) {
  $context['user']['is_owner'] = true;
} else {
  $context['user']['is_owner'] = false;
  validateSession();
}

isAllowedTo($sa_allowed[$_REQUEST['sa']][$context['user']['is_owner'] ? 0 : 1]);

if ($context['user']['is_owner'] && !empty($sa_allowed[$_REQUEST['sa']][3])) {
  if (trim($_POST['oldpasswrd']) == '') {
    fatal_error('Debes escribir la contrase&ntilde;a.', false);
  }

  $_POST['oldpasswrd'] = addslashes(un_htmlspecialchars(stripslashes($_POST['oldpasswrd'])));
  $good_password = false;

  if (isset($modSettings['integrate_verify_password']) && function_exists($modSettings['integrate_verify_password'])) {
    if (call_user_func($modSettings['integrate_verify_password'], $user_profile[$memID]['memberName'], $_POST['oldpasswrd'], false) === true) {
      $good_password = true;
    }
  }

  if (!$good_password && $user_info['passwd'] != sha1(strtolower($user_profile[$memID]['memberName']) . $_POST['oldpasswrd'])) {
    fatal_error('Las contrase&ntilde;as no coinciden.', false);
  }
}

unset($sa_allowed);

if ($user_info['is_admin'] && isset($_POST['memberName'])) {
  require_once($sourcedir . '/Subs-Auth.php');
  resetPassword($memID, $_POST['memberName']);
}

if ($context['user']['is_owner']) {
  $profile_vars['memberIP'] = "'$user_info[ip]'";
}

saveProfileChanges($profile_vars, $post_errors, $memID);

if (!empty($post_errors)) {
  loadLanguage('Errors');
  $context['post_errors'] = $post_errors;
  $_REQUEST['sa'] = $_POST['sa'];
  $_REQUEST['u'] = $memID;

  return ModifyProfile($post_errors);
}

if (!empty($profile_vars)) {
  if (isset($profile_vars['passwd']) && isset($modSettings['integrate_reset_pass']) && function_exists($modSettings['integrate_reset_pass'])) {
    call_user_func($modSettings['integrate_reset_pass'], $user_profile[$memID]['memberName'], $user_profile[$memID]['memberName'], $_POST['passwrd1']);
  }

  updateMemberData($memID, $profile_vars);
}

if ($modSettings['latestMember'] == $memID) {
  updateStats('member');
} else if (isset($profile_vars['realName'])) {
  updateSettings(array('memberlist_updated' => time()));
}

if ($_GET['sa'] !== 'activar') {
  // Datos y errores
  if ($_POST['usertitle'] === -1 || empty($_POST['usertitle'])) {
    fatal_error('Debes seleccionar el pa&iacute;s donde vives.', false, '', 4);
  }

  if ($_POST['usertitle'] === 'ar' || $_POST['usertitle'] === 'bo' || $_POST['usertitle'] === 'br' || $_POST['usertitle'] === 'cl' || $_POST['usertitle'] === 'co' || $_POST['usertitle'] === 'cr' || $_POST['usertitle'] === 'cu' || $_POST['usertitle'] === 'ec' || $_POST['usertitle'] === 'es' || $_POST['usertitle'] === 'gt' || $_POST['usertitle'] === 'it' || $_POST['usertitle'] === 'mx' || $_POST['usertitle'] === 'py' || $_POST['usertitle'] === 'pe' || $_POST['usertitle'] === 'pt' || $_POST['usertitle'] === 'pr' || $_POST['usertitle'] === 'uy' || $_POST['usertitle'] === 've' || $_POST['usertitle'] === 'ot') {

  } else {
    fatal_error('El pa&iacute;s seleccinado no est&aacute; en la lista.', false, '', 4);
  }

  if (empty($_POST['location'])) {
    fatal_error('Debes especificar la ciudad donde vives.', false, '', 4);
  }

  if (strlen($_POST['location']) >= 60) {
    fatal_error('Nombre de ciudad muy largo. Abrevia la ciudad.', false, '', 4);
  }

  $myuser = $ID_MEMBER;

  $eskiji = $puntos < 0 ? 0 : (int) $puntos;
  $sex = $gender == 2 ? 2 : 1;

  $personalText = censorText(str_replace(array('&quot;', '&lt;', '&gt;', ' &nbsp;'), array('"', '<', '>', '  '), $_POST['personalText']));
  $websiteTitle = censorText(str_replace(' ', '', $_POST['websiteTitle']));

  if (strlen($personalText) > 21) {
    fatal_error('El texto personal no debe tener m&aacute;s de 21 caracteres.');
  }

  $data2 = $recibir == 1 || empty($recibir) ? $recibir : 0;

  if ($dia < 1 || $dia > 31) {
    fatal_error('Hubo un problema con la fecha de nacimiento.');
  }

  if ($mes < 1 || $mes > 12) {
    fatal_error('Hubo un problema con la fecha de nacimiento.');
  }

  if ($ano < 1900 || $ano > 2005) {
    fatal_error('Hubo un problema con la fecha de nacimiento.');
  }

  $nac = sprintf('%04d-%02d-%02d', empty($_POST['bday3']) ? 0 : (int) $_POST['bday3'], (int) $_POST['bday1'], (int) $_POST['bday2']);

  if ($quienver == 1 || $quienver == 2 || $quienver == 3 || empty($quienver)) {
    if (empty($quienver)) {
      $quienver = 0;
    }

    $agrearorefrescar = mysqli_num_rows(db_query("SELECT id_user FROM ({$db_prefix}infop) WHERE id_user='$memID'", __FILE__, __LINE__));

    if ($agrearorefrescar == 1) {
      db_query("}
        UPDATE {$db_prefix}infop
        SET a_quien = $quienver
        WHERE id_user = $memID", __FILE__, __LINE__);
    } else if (empty($agrearorefrescar)) {
      db_query("
        INSERT INTO {$db_prefix}infop (id_user, a_quien)
        VALUES ($memID, $quienver)", __FILE__, __LINE__);
    }
  }

  if (!empty($realname) && $user_settings['ID_MEMBER'] == 1) {
    db_query("
      UPDATE {$db_prefix}members
      SET realName = '$realname'
      WHERE ID_MEMBER = $memID
      LIMIT 1", __FILE__, __LINE__);
  }

  if (!empty($eskiji) && $user_settings['ID_MEMBER'] == 1) {
    db_query("
      UPDATE {$db_prefix}members
      SET posts = $eskiji
      WHERE ID_MEMBER = $memID
      LIMIT 1", __FILE__, __LINE__);
  }

  db_query("
    UPDATE {$db_prefix}members
    SET
      nombre = '$nombre',
      usertitle = '{$_POST['usertitle']}',
      location = '{$_POST['location']}',
      birthdate = '$nac',
      gender = $sex,
      personalText = '$personalText',
      websiteTitle = SUBSTRING('$websiteTitle', 1, 70),
      recibirmail = $data2
    WHERE ID_MEMBER = $memID
    LIMIT 1", __FILE__, __LINE__);

  if ($_POST['MSN']) {
    if (preg_match('~^[0-9A-Za-z=_+\-/][0-9A-Za-z=_\'+\-/\.]*@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$~', stripslashes($_POST['MSN'])) == 0) {
      fatal_error('Mensajero no v&aacute;lido.', false);
    } else {
      if (isset($_POST['MSN']) && ($_POST['MSN'] == '' || preg_match('~^[0-9A-Za-z=_+\-/][0-9A-Za-z=_\'+\-/\.]*@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$~', $_POST['MSN']) != 0)) {
        db_query("
          UPDATE {$db_prefix}members
          SET MSN = '{$_POST['MSN']}'
          WHERE ID_MEMBER = $memID
          LIMIT 1", __FILE__, __LINE__);
      }
    }
  }

  pts_sumar_grup($memID);
}

if ($newpassemail) {
  require_once($sourcedir . '/Subs-Post.php');

  // TO-DO: Verificar correo que llega
  $enlace = $boardurl . '/activar-' . $memID . 'codigo-' . $validationCode;
  sendmail(
    $_POST['emailAddress'],
    'Confirmar correo',
    'Para volver a ingresar con tu cuenta en ' . $boardurl . ', la debes activar.<br />' .
    'Para eso debes ir al siguiente enlace:<br / ><br />' . 
    '<a href="' . $enlace . '">' . $enlace . '</a><br /><br />' .
    'Si tienes problemas con el enlace, no dudes en contactar con ' . $mbname . ' (<a href="' . $boardurl . '/contactanos/">' . $boardurl . '/contactanos/</a>) siempre recordando tu c&oacute;digo de activaci&oacute;n: ' . $validationCode);

  db_query("
    DELETE FROM {$db_prefix}log_online
    WHERE ID_MEMBER = $memID", __FILE__, __LINE__);

  $_SESSION['log_time'] = 0;
  $_SESSION['login_' . $cookiename] = serialize(array(0, '', 0));

  if (isset($_COOKIE[$cookiename])) {
    $_COOKIE[$cookiename] = '';
  }

  loadUserSettings();

  $context['user']['is_logged'] = false;
  $context['user']['is_guest'] = true;

  loadTemplate('Register');

  $context += array(
    'page_title' => &$txt[79],
    'sub_template' => 'after',
    'description' => &$txt['activate_changed_email']
  );

  header('Location: ' . $boardurl . '/');
}

$sffffe = !empty($_POST['passwrd1']) ? 1 : 0;

if ($_POST['llegaravatar']) {
  header('Location: ' . $boardurl . '/moderacion/edit-user/perfil/' . $_POST['llegaravatar']);
} else if ($sffffe == 1) {
  header('Location: ' . $boardurl . '/');
} else {
  header('Location: ' . $boardurl . '/editar-perfil/');
}

?>