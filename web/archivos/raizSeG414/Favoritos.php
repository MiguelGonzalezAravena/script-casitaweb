<?php
// Pagina de Rodrigo Zaupa (rigo@casitaweb.net)
if (!defined('CasitaWeb!-PorRigo')) {
	die(base64_decode('d3d3LmNhc2l0YXdlYi5uZXQgLSByaWdv'));
}

function Favoritos() {
	global $txt, $context, $scripturl, $db_prefix, $user_info, $ID_MEMBER, $return;

	$_GET['m'] = str_replace('/', '', $_GET['m']);

	loadTemplate('Favoritos');

	if (!$context['user']['is_logged']) {
		fatal_error($txt['bookmark_not_for_guests'], false);
	}

	$context['all_pages'] = array(
		'post' => 'intro',
		'imagen' => 'imagen',
	);

	if (!isset($_GET['m']) || !isset($context['all_pages'][$_GET['m']])) {
		$_GET['m'] = 'post';
	}

	$context['current_page'] = $_GET['m'];
	$context['sub_template'] = $context['all_pages'][$context['current_page']];
	$context['page_title'] = $txt[18];
}

?>