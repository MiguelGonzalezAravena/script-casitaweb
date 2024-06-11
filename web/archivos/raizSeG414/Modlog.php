<?php
//Pagina de Rodrigo Zaupa (rigo@casitaweb.net)
if (!defined('CasitaWeb!-PorRigo')) {
  die(base64_decode("d3d3LmNhc2l0YXdlYi5uZXQgLSByaWdv"));
}

function ViewModlog() {
  global $db_prefix, $txt, $modSettings, $context, $scripturl, $boardurl;

  loadTemplate('Modlog');

  $context['page_title'] = 'Historial de moderaci&oacute;n';

  if (!$context['user']['id']) {
    is_not_guest();
  }

  $descriptions = array(
    'modify' => '<font style="color: #00BA00;">Editado</font>',
    'remove' => '<font style="color: #FF0000;">Eliminado</font>',
    'causa' => '<b style="color: #FF0000;">Causa</b>',
  );

  $result = db_query("
    SELECT lm.ID_ACTION, lm.ID_MEMBER, lm.action, lm.extra, mem.realName
    FROM {$db_prefix}log_actions AS lm
    LEFT JOIN {$db_prefix}members AS mem ON mem.ID_MEMBER = lm.ID_MEMBER
    ORDER BY lm.ID_ACTION DESC
    LIMIT 20", __FILE__, __LINE__);

  $context['entries'] = array();

  while ($row = mysqli_fetch_assoc($result)) {
    $row['extra'] = unserialize($row['extra']);
    $row['extra'] = is_array($row['extra']) ? $row['extra'] : array();
    // var_dump($row['extra']);

    if (isset($row['extra']['topic'])) {
      $sis = 'topic';
    }

    if (isset($row['extra']['Imagen'])) {
      $sis = 'Imagen';
    }

    $context['entries'][$row['ID_ACTION']] = array(
      'id' => $row['ID_ACTION'],
      'que' => $sis,
      'moderator' => array('link' => '<a href="' . $boardurl . '/perfil/' . $row['realName'] . '">' . $row['realName'] . '</a>'),
      'extra' => $row['extra'],
      'action' => isset($descriptions[$row['action']]) ? $descriptions[$row['action']] : $row['action'],
    );
  }

  mysqli_free_result($result);
}

?>