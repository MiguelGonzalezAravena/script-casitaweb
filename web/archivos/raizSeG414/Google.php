<?php
//Pagina de Rodrigo Zaupa (rigo@casitaweb.net)
if (!defined('CasitaWeb!-PorRigo'))die(base64_decode("d3d3LmNhc2l0YXdlYi5uZXQgLSByaWdv"));
function Google(){global $context, $settings,$user_settings, $options, $txt, $scripturl, $modSettings;

loadTemplate('Google');
$context['all_pages'] = array('index' => 'intro');
if (!isset($_GET['m']) || !isset($context['all_pages'][$_GET['m']]))$_GET['m'] = 'index';
$context['current_page'] = $_GET['m'];
$context['sub_template']=$context['all_pages'][$context['current_page']];
$context['page_title']='Buscador';} ?>