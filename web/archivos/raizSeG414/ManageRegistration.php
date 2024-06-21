<?php
// Página de Rodrigo Zaupa (rigo@casitaweb.net)
if (!defined('CasitaWeb!-PorRigo')) {
  die(base64_decode('d3d3LmNhc2l0YXdlYi5uZXQgLSByaWdv'));
}

function RegCenter() {
  global $modSettings, $context, $txt, $db_prefix, $scripturl, $boardurl;

  $subActions = array(
    'register' => array('AdminRegister', 'moderate_forum'),
    'agreement' => array('EditAgreement', 'admin_forum'),
    'reservednames' => array('SetReserve', 'admin_forum'),
    'settings' => array('AdminSettings', 'admin_forum'),
  );

  if (!$context['user']['is_admin']) {
    fatal_error('No pudes estar ac&aacute;');
  }

  $context['sub_action'] = isset($_REQUEST['sa']) && isset($subActions[$_REQUEST['sa']]) ? $_REQUEST['sa'] : (allowedTo('moderate_forum') ? 'register' : 'settings');

  adminIndex('registration_center');
  loadLanguage('Login');
  loadTemplate('Register');

  $context['admin_tabs'] = array(
    'title' => &$txt['registration_center'],
    'help' => 'registrations',
    'description' => $txt['admin_settings_desc'],
    'tabs' => array(
      'register' => array(
        'title' => $txt['admin_browse_register_new'],
        'description' => $txt['admin_register_desc'],
        'href' => $boardurl . '/moderacion/registro/register/',
        'is_selected' => $context['sub_action'] == 'register',
        'is_last' => !allowedTo('admin_forum'),
      )
    )
  );

  if (allowedTo('admin_forum')) {
    $context['admin_tabs']['tabs']['agreement'] = array(
      'title' => $txt['smf11'],
      'description' => $txt['smf12'],
      'href' => $boardurl . '/moderacion/registro/agreement/',
      'is_selected' => $context['sub_action'] == 'agreement',
    );

    $context['admin_tabs']['tabs']['reservednames'] = array(
      'title' => $txt[341],
      'description' => $txt[699],
      'href' => $boardurl . '/moderacion/registro/reservednames/',
      'is_selected' => $context['sub_action'] == 'reservednames',
    );
    $context['admin_tabs']['tabs']['settings'] = array(
      'title' => $txt['settings'],
      'description' => $txt['admin_settings_desc'],
      'href' => $boardurl . '/moderacion/registro/settings/',
      'is_last' => true,
      'is_selected' => $context['sub_action'] == 'settings',
    );
  }

  $subActions[$context['sub_action']][0]();
}

function AdminRegister() {
  global $txt, $context, $db_prefix, $sourcedir, $scripturl, $boardurl;

  $context['admin_tabs']['tabs']['register']['is_selected'] = true;

  if (!empty($_POST['regSubmit'])) {
    checkSession();

    foreach ($_POST as $key => $value) {
      if (!is_array($_POST[$key])) {
        $_POST[$key] = htmltrim__recursive(str_replace(array("\n", "\r"), '', $_POST[$key]));
      }
    }

    $regOptions = array(
      'interface' => 'admin',
      'username' => $_POST['user'],
      'email' => $_POST['email'],
      'password' => $_POST['password'],
      'password_check' => $_POST['password'],
      'check_reserved_name' => true,
      'check_password_strength' => false,
      'check_email_ban' => false,
      'send_welcome_email' => isset($_POST['emailPassword']),
      'require' => isset($_POST['emailActivate']) ? 'activation' : 'nothing',
      'memberGroup' => empty($_POST['group']) ? 0 : (int) $_POST['group'],
    );

    require_once($sourcedir . '/Subs-Members.php');

    $memberID = registerMember($regOptions);

    if (!empty($memberID)) {
      $context['new_member'] = array(
        'id' => $memberID,
        'name' => $_POST['user'],
        'href' => $boardurl . '/perfil/' . $_POST['user'],
        'link' => '<a href="' . $boardurl . '/perfil/' . $_POST['user'] . '">' . $_POST['user'] . '</a>',
      );

      $context['registration_done'] = sprintf($txt['admin_register_done'], $context['new_member']['link']);
    }
  }

  $context['sub_template'] = 'admin_register';
  $context['page_title'] = $txt['registration_center'];

  $request = db_query("
    SELECT groupName, ID_GROUP
    FROM {$db_prefix}membergroups
    WHERE ID_GROUP != 3
    AND minPosts = -1" . (allowedTo('admin_forum') ? '' : '
    AND ID_GROUP != 1') . '
    ORDER BY minPosts, IF(ID_GROUP < 4, ID_GROUP, 4), groupName', __FILE__, __LINE__);

  $context['member_groups'] = array(0 => &$txt['admin_register_group_none']);

  while ($row = mysqli_fetch_assoc($request)) {
    $context['member_groups'][$row['ID_GROUP']] = $row['groupName'];
  }

  mysqli_free_result($request);
}

function EditAgreement() {
  global $txt, $boarddir, $context, $modSettings, $db_prefix;

  if (isset($_POST['agreement'])) {
    $_POST['agreement'] = trim($_POST['agreement']);

    if (empty($_POST['agreement'])) {
      $texto = '';
    } else {
      $texto = $_POST['agreement'];
    }

    updateSettings(array('requireAgreement' => !empty($_POST['requireAgreement'])));

    db_query("
      UPDATE {$db_prefix}settings
      SET value = '$texto'
      WHERE variable = 'terminos'
      LIMIT 1", __FILE__, __LINE__);

    fatal_error('Terminos y Condiciones editado correctamente.');
  }

  $context['agreement'] = $modSettings['terminos'];
  $context['require_agreement'] = !empty($modSettings['requireAgreement']);
  $context['sub_template'] = 'edit_agreement';
  $context['page_title'] = $txt['smf11'];
}

function SetReserve() {
  global $txt, $db_prefix, $context, $modSettings;

  // Submitting new reserved words.
  if (!empty($_POST['save_reserved_names'])) {
    checkSession();

    // Set all the options....
    updateSettings(array(
      'reserveWord' => (isset($_POST['matchword']) ? '1' : '0'),
      'reserveCase' => (isset($_POST['matchcase']) ? '1' : '0'),
      'reserveUser' => (isset($_POST['matchuser']) ? '1' : '0'),
      'reserveName' => (isset($_POST['matchname']) ? '1' : '0'),
      'reserveNames' => str_replace("\r", '', $_POST['reserved'])
    ));
  }

  // Get the reserved word options and words.
  $context['reserved_words'] = explode("\n", $modSettings['reserveNames']);
  $context['reserved_word_options'] = array();
  $context['reserved_word_options']['match_word'] = $modSettings['reserveWord'] == '1';
  $context['reserved_word_options']['match_case'] = $modSettings['reserveCase'] == '1';
  $context['reserved_word_options']['match_user'] = $modSettings['reserveUser'] == '1';
  $context['reserved_word_options']['match_name'] = $modSettings['reserveName'] == '1';

  // Ready the template......
  $context['sub_template'] = 'edit_reserved_words';
  $context['page_title'] = $txt[341];
}

// This function handles registration settings, and provides a few pretty stats too while it's at it.
function AdminSettings() {
  global $txt, $context, $db_prefix, $scripturl, $modSettings;

  global $sourcedir;

  // Setup the template
  $context['sub_template'] = 'admin_settings';
  $context['page_title'] = $txt['registration_center'];

  // Saving?
  if (isset($_POST['save'])) {
    checkSession();

    // Are there some contacts missing?
    if (!empty($_POST['coppaAge']) && !empty($_POST['coppaType']) && empty($_POST['coppaPost']) && empty($_POST['coppaFax'])) {
      fatal_error($txt['admin_setting_coppa_require_contact']);
    }

    // Post needs to take into account line breaks.
    $_POST['coppaPost'] = str_replace("\n", '<br />', empty($_POST['coppaPost']) ? '' : $_POST['coppaPost']);

    // PM Register User cleaning
    $_POST['pm_register_from'] = strtr(htmlspecialchars($_POST['pm_register_from'], ENT_QUOTES), array("\r" => '', "\n" => '', "\t" => ''));
    $_POST['pm_register_subject'] = strtr(htmlspecialchars($_POST['pm_register_subject'], ENT_QUOTES), array("\r" => '', "\n" => '', "\t" => ''));
    $_POST['pm_register_body'] = htmlspecialchars($_POST['pm_register_body'], ENT_QUOTES);

    require_once($sourcedir . '/Subs-Post.php');

    preparsecode($_POST['pm_register_body']);

    updateSettings(array(
      'registration_method' => (int) $_POST['registration_method'],
      'notify_new_registration' => isset($_POST['notify_new_registration']) ? 1 : 0,
      'password_strength' => (int) $_POST['password_strength'],
      'disable_visual_verification' => isset($_POST['visual_verification_type']) ? (int) $_POST['visual_verification_type'] : 0,
      'coppaAge' => (int) $_POST['coppaAge'],
      'coppaType' => empty($_POST['coppaType']) ? 0 : (int) $_POST['coppaType'],
      'coppaPost' => $_POST['coppaPost'],
      'coppaFax' => !empty($_POST['coppaFax']) ? $_POST['coppaFax'] : '',
      'coppaPhone' => !empty($_POST['coppaPhone']) ? $_POST['coppaPhone'] : '',
    ));

    // Reload the page, so the tabs are accurate.
    redirectexit($boardurl . '/moderacion/registro/settings/');
  }

  $context['coppaPost'] = !empty($modSettings['coppaPost']) ? preg_replace('~<br(?: /)?' . '>~', "\n", $modSettings['coppaPost']) : '';
  $context['use_graphic_library'] = in_array('gd', get_loaded_extensions());
}

?>