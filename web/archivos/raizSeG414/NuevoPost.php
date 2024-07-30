<?php
// Página de Rodrigo Zaupa (rigo@casitaweb.net)
if (!defined('CasitaWeb!-PorRigo')) {
  die(base64_decode('d3d3LmNhc2l0YXdlYi5uZXQgLSByaWdv'));
}

function NuevoPost() {
  global $context;

  is_not_guest();
  LoadTemplate('NuevoPost');
  loadLanguage('Post');

  $context['page_title'] = 'Agregar nuevo post';
}

?>