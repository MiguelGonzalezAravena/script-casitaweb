<?php
// Página de Rodrigo Zaupa (rigo@casitaweb.net)
if (!defined('CasitaWeb!-PorRigo')) {
  die(base64_decode('d3d3LmNhc2l0YXdlYi5uZXQgLSByaWdv'));
}

function MonitorUser() {
  global $context;

  is_not_guest();
  LoadTemplate('MonitorUser');

  $context['page_title'] = 'Notificaciones';
}

?>