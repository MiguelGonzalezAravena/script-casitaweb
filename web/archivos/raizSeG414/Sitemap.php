<?php
//Pagina de Rodrigo Zaupa (rigo@casitaweb.net)
if (!defined('CasitaWeb!-PorRigo'))die(base64_decode("d3d3LmNhc2l0YXdlYi5uZXQgLSByaWdv"));
function ShowSiteMap() {
global $context, $scripturl, $settings, $txt, $user_info, $db_prefix, $modSettings;
$context['page_title'] = $txt['sitemap'];
loadtemplate('Sitemap');
BoardDisplay();}
function BoardDisplay(){global $context, $db_prefix, $user_info;
	$context['sub_template'] = 'Boards';
	$context['sitemap']['collapsible'] = array();
	if(!$context['user']['is_admin']){$shas=' AND b.ID_BOARD<>142 ';}else{$shas='';}
	$request = db_query("
		SELECT b.ID_BOARD, b.ID_PARENT, b.childLevel, b.name, b.description, b.numTopics, b.numPosts
		FROM {$db_prefix}boards as b
		WHERE $user_info[query_see_board] $shas
		ORDER BY b.boardOrder", __FILE__, __LINE__);
	while ($row = mysql_fetch_assoc($request))
	{$context['sitemap']['board'][$row['ID_BOARD']] = array(
			'id' => $row['ID_BOARD'],
			'level' => $row['childLevel'],
			'has_children' => false,
			'name' => $row['name'],
			'description' => $row['description'],
			'numt' => $row['numTopics'],
			'nump' => $row['numPosts']);
	if(!empty($row['childLevel']) && $row['childLevel'] == '1') {
			$context['sitemap']['board'][$row['ID_PARENT']]['has_children'] = true;
			$context['sitemap']['collapsible'] = $context['sitemap']['collapsible'] + array($row['ID_PARENT'] => $row['ID_PARENT']);}}	
	$context['sitemap']['collapsible'] = '\'parent' . implode('\', \'parent', $context['sitemap']['collapsible']) . '\'';
	mysql_free_result($request);}

function TopicDisplay($start) {
	global $context, $db_prefix, $user_info, $scripturl, $modSettings;

	$context['sub_template'] = 'Topics';
	
	$end = $modSettings['sitemap_topic_count'] - $start < 100 ? $modSettings['sitemap_topic_count'] - $start : 100;

	$request = db_query("
		SELECT m.ID_MSG, m.ID_TOPIC, t.puntos, t.numViews, m.ID_BOARD,
		m.subject, m.posterName, m.posterTime,b.description, m.posterName, t.ID_FIRST_MSG, b.name,
		m.hiddenOption
		FROM {$db_prefix}messages as m, {$db_prefix}topics as t, {$db_prefix}boards as b
		WHERE m.ID_MSG=t.ID_FIRST_MSG
		AND b.ID_BOARD = m.ID_BOARD
		AND $user_info[query_see_board]
		ORDER BY m.ID_TOPIC DESC
		LIMIT $start,$end", __FILE__, __LINE__);

	while ($row = mysql_fetch_assoc($request))
	{
			$context['sitemap']['topic'][] = array(
			'privado' => $row['hiddenOption'],
			'subject' => $row['subject'],
			'poster' => $row['posterName'],
			'views' => $row['numViews'],
			'puntos' => $row['puntos'],
			'id' => $row['ID_TOPIC'],
			'fecha' => timeformat($row['posterTime']),		
			'href' => 'http://casitaweb.net/post/' . $row['ID_TOPIC'] . '',
			'board_name' => $row['name'],
			'ID_BOARD' => $row['ID_BOARD'],
			'board_href' => 'http://casitaweb.net/categoria/' . $row['description'] . '',
		);
	}

	// Free the result
	mysql_free_result($request);
}

function XMLDisplay() {}
function date_iso8601($timestamp = '') {}

?>