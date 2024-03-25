<?php
//Pagina de Rodrigo Zaupa (rigo@casitaweb.net)
if (!defined('CasitaWeb!-PorRigo'))die(base64_decode("d3d3LmNhc2l0YXdlYi5uZXQgLSByaWdv"));
function acseg(){global $context, $txt, $user_settings;

loadTemplate('acseg');

$context['all_pages'] = array(
		'index' => 'intro',
		'tyc' => 'tyc',
		'tyc1' => 'tyc1',
        //CHAT
		'tyc2' => 'tyc2',
		'tyc3' => 'tyc3',
		'tyc5' => 'tyc5',
		'tyc12' => 'tyc12',
        //NotaS
        'tyc17' => 'tyc17',        
        'tyc23' => 'tyc23');
        
if (!isset($_GET['m']) || !isset($context['all_pages'][$_GET['m']]))$_GET['m'] = 'index';
$context['current_page'] = $_GET['m'];
$context['sub_template'] = $context['all_pages'][$context['current_page']];

//TITLES
if($context['current_page']=='tyc2')
$context['page_title'] = 'Chat';
elseif($context['current_page']=='tyc17')
$context['page_title'] = 'Mis notas';
else $context['page_title'] = $txt[18];

if($context['current_page']=='tyc17' && $context['user']['is_guest']){is_not_guest();}
if($context['current_page']=='tyc23' && $context['user']['is_guest']){is_not_guest();}
if($context['current_page']=='tyc12'){ if($user_settings['ID_GROUP']=='1')adminIndex('tyc12');else{die();}}

} ?>