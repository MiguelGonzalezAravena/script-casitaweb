<?php
//Pagina de Rodrigo Zaupa (rigo@casitaweb.net)
if (!defined('CasitaWeb!-PorRigo')) {
  die(base64_decode('d3d3LmNhc2l0YXdlYi5uZXQgLSByaWdv'));
}

function Admin() {
  global $sourcedir, $db_prefix, $forum_version, $txt, $scripturl, $context, $modSettings;
  global $user_info, $_PHPA, $boardurl, $urlSep;

  isAllowedTo(array('admin_forum', 'manage_permissions', 'moderate_forum', 'manage_membergroups', 'manage_bans', 'send_mail', 'edit_news', 'manage_boards', 'manage_smileys', 'manage_attachments'));
  adminIndex('index');
    
  $request = db_query("
    SELECT ID_MEMBER, realName
    FROM {$db_prefix}members
    WHERE ID_GROUP = 1
    LIMIT 33", __FILE__, __LINE__);

  $context['administrators'] = array();

  while ($row = mysqli_fetch_assoc($request)) {
    $context['administrators'][] = '<a href="' . $boardurl . '/perfil/'.$row['realName'].'" title="'.$row['realName'].'">'.$row['realName'].'</a>';
  }

  mysqli_free_result($request);

  $request2 = db_query("
    SELECT realName
    FROM {$db_prefix}members
    WHERE ID_GROUP = 2", __FILE__, __LINE__);

  $context['moderadores'] = array();
  while ($row = mysqli_fetch_assoc($request2)) {
    $context['moderadores'][] = array(
      'realName' => $row['realName']
    );
  }

  mysqli_free_result($request2);

  $context['time_format'] = urlencode($user_info['time_format']);
  $context['can_admin'] = allowedTo('admin_forum');

  $context['sub_template'] = 'admin';
  $context['page_title'] = $txt[208];

  $quick_admin_tasks = array(
    array('admin_forum', 'featuresettings', 'modSettings_title', 'modSettings_info'),
    array('manage_permissions', 'permissions', 'edit_permissions', 'edit_permissions_info'),
    array('manage_smileys', 'smileys', 'smileys_manage', 'smileys_manage_info'),
    array('moderate_forum', 'viewmembers', '5', 'member_center_info'),
  );

  $context['quick_admin_tasks'] = array();

  foreach ($quick_admin_tasks as $task) {
    if (!empty($task[0]) && !allowedTo($task[0])) {
      continue;
    }

    $context['quick_admin_tasks'][] = array(
      'href' => $scripturl . '?' . $urlSep . '=' . $task[1],
      'link' => '<a href="' . $scripturl . '?' . $urlSep . '=' . $task[1] . '">' . $txt[$task[2]] . '</a>',
      'title' => $txt[$task[2]],
      'description' => $txt[$task[3]],
      'is_last' => false
    );
  }

  if (count($context['quick_admin_tasks']) % 2 == 1) {
    $context['quick_admin_tasks'][] = array(
      'href' => '',
      'link' => '',
      'title' => '',
      'description' => '',
      'is_last' => true
    );

    $context['quick_admin_tasks'][count($context['quick_admin_tasks']) - 2]['is_last'] = true;
  } else if (count($context['quick_admin_tasks']) != 0) {
    $context['quick_admin_tasks'][count($context['quick_admin_tasks']) - 1]['is_last'] = true;
    $context['quick_admin_tasks'][count($context['quick_admin_tasks']) - 2]['is_last'] = true;
  }
}

function AdminBoardRecount() {}
function VersionDetail() {}
function ManageCopyright() {}
function CleanupPermissions() {}
function updateSettingsFile($config_vars) {}

?>