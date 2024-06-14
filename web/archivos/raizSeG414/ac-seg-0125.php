<?php
// Página de Rodrigo Zaupa (rigo@casitaweb.net)
if (!defined('CasitaWeb!-PorRigo')) {
  die(base64_decode('d3d3LmNhc2l0YXdlYi5uZXQgLSByaWdv'));
}

function acsegsd() {
  global $context, $settings, $user_settings, $options, $txt, $scripturl, $modSettings;
  global $db_prefix, $user_info, $modSettings, $board;
  global $query_this_board;

  loadTemplate('acsegsd');

  $context['all_pages'] = array(
    'index' => 'intro',
    'tyc' => 'tyc',
    'tyc2' => 'tyc2',
    'tyc1' => 'tyc1',
    'tyc4' => 'tyc4',
    'tyc3' => 'tyc3',
    'tyc5' => 'tyc5',
    'tyc6' => 'tyc6',
    'tyc7' => 'tyc7',
    'tyc8' => 'tyc8',
    'tyc9' => 'tyc9',
    'tyc10' => 'tyc10',
    'tyc11' => 'tyc11',
    'tyc12' => 'tyc12',
    'tyc13' => 'tyc13',
    'tyc14' => 'tyc14',
    'tyc15' => 'tyc15',
    'tyc16' => 'tyc16',
    'tyc17' => 'tyc17'
  );

  if (!isset($_GET['m']) || !isset($context['all_pages'][$_GET['m']])) {
    $_GET['m'] = 'index';
  }

  $context['current_page'] = $_GET['m'];
  $context['sub_template'] = $context['all_pages'][$context['current_page']];
  $context['page_title'] = $txt[18];

  if (seguridad($_GET['m']) == 'tyc2') {
    if ($user_settings['ID_GROUP'] == 1 || $user_settings['ID_GROUP'] == 2) {
      adminIndex('tyc2');
    } else {
      fatal_error('No tienes permisos para estar ac&aacute;.');
    }
  }

  if (seguridad($_GET['m']) == 'tyc6') {
    if ($user_settings['ID_GROUP'] == 1) {
      adminIndex('tyc6');
    } else {
      fatal_error('No tienes permisos para estar ac&aacute;.');
    }
  }

  if (seguridad($_GET['m']) == 'tyc100' && $_GET['ldasdasdmkadmmm4'] == '2dasdasddwer23423425') {
    if ($user_settings['ID_GROUP'] == 1 || $user_settings['ID_GROUP'] == 2) {
      adminIndex('tyc9');
    } else {
      fatal_error('No tienes permisos para estar ac&aacute;.');
    }
  }
}

?>