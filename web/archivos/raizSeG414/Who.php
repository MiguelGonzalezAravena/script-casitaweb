<?php
// Página de Rodrigo Zaupa (rigo@casitaweb.net)
if (!defined('CasitaWeb!-PorRigo')) {
  die(base64_decode('d3d3LmNhc2l0YXdlYi5uZXQgLSByaWdv'));
}

function Who() {
  global $db_prefix, $context, $scripturl, $user_info, $txt, $modSettings, $ID_MEMBER, $memberContext;

  isAllowedTo('who_view');

  if (empty($modSettings['who_enabled'])) {
    fatal_lang_error('who_off', false);
  }

  loadTemplate('Who');

  $context['page_title'] = 'Usuarios Conectados';
}

function determineActions($urls) {}

?>