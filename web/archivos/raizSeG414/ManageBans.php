<?php
//Pagina de Rodrigo Zaupa (rigo@casitaweb.net)
if (!defined('CasitaWeb!-PorRigo'))die(base64_decode("d3d3LmNhc2l0YXdlYi5uZXQgLSByaWdv"));
function Ban(){global $context, $txt, $user_info,$scripturl;
if(($user_info['is_admin'] || $user_info['is_mods'])){
	adminIndex('ban_members');
	loadTemplate('ManageBans');
	$subActions = array(
		'add' => 'BanEdit',
		'edittrigger' => 'BanEditTrigger',
  'edit' => 'BanEdit',
  'buscar' => 'buscar',
		'list' => 'BanList',);
	$_REQUEST['sa'] = isset($_GET['sa']) && isset($subActions[$_GET['sa']]) ? $_GET['sa'] : 'list';
	$context['page_title'] = 'Historial de baneados';
	$context['sub_action'] = isset($_GET['sa']) ? str_replace('/','',$_GET['sa']) : '';
	$context['admin_tabs'] = array(
		'title' => 'Historial de baneados',
		'help' => 'ban_members',
        'description' => '',
		'tabs' => array(
			'list' => array(
				'title' => 'Lista de usuarios' ,
				'href' =>'/moderacion/edit-user/ban/',
				'is_selected' => empty($context['sub_action']),
			),
				'buscar' => array(
				'title' => 'Buscar usuario' ,
				'href' =>'/moderacion/edit-user/ban/buscar/',
				'is_selected' => $context['sub_action'] == 'buscar',
			),
			
		),
	);
	$subActions[$_REQUEST['sa']]();
}}

function buscar(){}
function BanList(){global $txt, $db_prefix, $context, $ban_request, $scripturl, $user_info;

	$context['get_ban'] = 'getBanEntry';
	if (preg_match('~%[AaBbCcDdeGghjmuYy](?:[^%]*%[AaBbCcDdeGghjmuYy])*~', $user_info['time_format'], $matches) == 0 || empty($matches[0]))
		$context['ban_time_format'] = $user_info['time_format'];
	else
		$context['ban_time_format'] = $matches[0];
}
 ?>