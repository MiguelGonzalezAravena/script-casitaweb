<?php
//Pagina de Rodrigo Zaupa (rigo@casitaweb.net)
if (!defined('CasitaWeb!-PorRigo'))die(base64_decode("d3d3LmNhc2l0YXdlYi5uZXQgLSByaWdv"));
function DisplayStats(){global $txt, $scripturl, $db_prefix, $modSettings, $user_info, $context;
	if (!empty($_REQUEST['expand']))
	{
		$month = (int) substr($_REQUEST['expand'], 4);
		$year = (int) substr($_REQUEST['expand'], 0, 4);
		if ($year > 1900 && $year < 2200 && $month >= 1 && $month <= 12)
			$_SESSION['expanded_stats'][$year][] = $month;
	}
	elseif (!empty($_REQUEST['collapse']))
	{
		$month = (int) substr($_REQUEST['collapse'], 4);
		$year = (int) substr($_REQUEST['collapse'], 0, 4);
		if (!empty($_SESSION['expanded_stats'][$year]))
			$_SESSION['expanded_stats'][$year] = array_diff($_SESSION['expanded_stats'][$year], array($month));
	}
	if (isset($_REQUEST['xml']))
	{
		if (!empty($_REQUEST['collapse']))
			obExit(false);

		$context['sub_template'] = 'stats';
		getDailyStats("YEAR(date) = $year AND MONTH(date) = $month");
		$context['monthly'][$year . sprintf('%02d', $month)]['date'] = array(
			'month' => sprintf('%02d', $month),
			'year' => $year,
		);
		return;
	}

    is_not_guest();
	loadLanguage('Stats');
	loadTemplate('Stats');
	$context['page_title'] ='TOPs';
	$context['show_member_list'] = allowedTo('view_mlist');
	
	
//imagenes
$request = db_query("
SELECT m.ID_MEMBER, i.ID_MEMBER, i.ID_PICTURE, i.title, m.memberName, m.realName
FROM ({$db_prefix}gallery_pic AS i, {$db_prefix}members AS m)
WHERE i.ID_MEMBER = m.ID_MEMBER
ORDER BY i.ID_PICTURE DESC
LIMIT 10", __FILE__, __LINE__);
$context['imagenestop'] = array();
while ($row = mysqli_fetch_assoc($request)){
$context['imagenestop'][] = array(
			'id' => $row['ID_PICTURE'],
			'titulo' => $row['title'],
			'idm' => $row['ID_MEMBER'],
			'nombrem' => $row['memberName'],
			'nombrem2' => $row['realName'],
			);}mysqli_free_result($request);
//imagenes
$request = db_query("
SELECT m.ID_MEMBER, i.ID_MEMBER, i.ID_PICTURE, i.title, m.memberName, m.realName, i.views
FROM ({$db_prefix}gallery_pic AS i, {$db_prefix}members AS m)
WHERE i.ID_MEMBER = m.ID_MEMBER
ORDER BY i.views DESC
LIMIT 0 , 10", __FILE__, __LINE__);

	$context['imgv'] = array();
	while ($row = mysqli_fetch_assoc($request))
	{
		
			$context['imgv'][] = array(
			'id' => $row['ID_PICTURE'],
			'titulo' => $row['title'],
			'idm' => $row['ID_MEMBER'],
            'v' => $row['views'],
			'nombrem' => $row['memberName'],
			'nombrem2' => $row['realName'],
			);
	}
	mysqli_free_result($request);

	$context['shop_richest'] = array();
		$result = db_query("
			SELECT ID_MEMBER, realName, posts
			FROM {$db_prefix}members
			ORDER BY posts DESC, realName
			LIMIT 10", __FILE__, __LINE__);
		while ($row = mysqli_fetch_array($result))
			// And add them to the list
			$context['shop_richest'][] = array(
				'ID_MEMBER' => $row['ID_MEMBER'],
				'realName' => $row['realName'],
				'money' => $row['posts']
			);
	if ($modSettings['totalMessages'] > 100000)
	{
		$request = db_query("
			SELECT ID_TOPIC
			FROM {$db_prefix}messages
			WHERE visitas != 0
			ORDER BY visitas DESC
			LIMIT 100", __FILE__, __LINE__);
		$topic_ids = array();
		while ($row = mysqli_fetch_assoc($request))
			$topic_ids[] = $row['ID_TOPIC'];
		mysqli_free_result($request);
	}
	else
		$topic_ids = array();


	$topic_view_result = db_query("
		SELECT m.subject, m.visitas, m.ID_BOARD, m.ID_TOPIC, b.name, b.description
		FROM ({$db_prefix}messages AS m, {$db_prefix}boards AS b)
		WHERE $user_info[query_see_board] AND b.ID_BOARD<>142 AND m.ID_BOARD = b.ID_BOARD" . (!empty($topic_ids) ? "	AND m.ID_TOPIC IN (" . implode(', ', $topic_ids) . ")" : '') . "
		ORDER BY m.visitas DESC
		LIMIT 10", __FILE__, __LINE__);
	$context['top_topics_views'] = array();
	$max_num_views = 1;
	while ($row_topic_views = mysqli_fetch_assoc($topic_view_result))
	{
censorText($row_topic_views['subject']);
  
		$context['top_topics_views'][] = array(
			'id' => $row_topic_views['ID_TOPIC'],
			'board' => array(
				'id' => $row_topic_views['ID_BOARD'],
				'name' => $row_topic_views['name'],
				'href' => '/categoria/' . $row_topic_views['ID_BOARD'] . '',
				'link' => '<a href="/categoria/' . $row_topic_views['ID_BOARD'] . '">' . $row_topic_views['name'] . '</a>'
			),
			'subject' => $row_topic_views['subject'],
			'num_views' => $row_topic_views['visitas'],
			'href' => '/post/'.$row_topic_views['ID_TOPIC'].'/'.censorText(urls($row_topic_views['description'])).'/'.censorText(urls($row_topic_views['subject'])).'.html',
			'link' => '<a href="/post/' . $row_topic_views['ID_TOPIC'] . '/'.censorText(urls($row_topic_views['description'])).'/'.censorText(urls($row_topic_views['subject'])).'.html">' . $row_topic_views['subject'] . '</a>'
		);

		if ($max_num_views < $row_topic_views['visitas'])
			$max_num_views = $row_topic_views['visitas'];}
	mysqli_free_result($topic_view_result);

	foreach ($context['top_topics_views'] as $i => $topic)
	$context['top_topics_views'][$i]['post_percent'] = round(($topic['num_views'] * 100) / $max_num_views);

$murosc=db_query("
SELECT COUNT(m.id_user) as Cuenta,u.realName,u.ID_MEMBER
From ({$db_prefix}muro as m, {$db_prefix}members as u)
WHERE m.id_user=u.ID_MEMBER
GROUP BY m.id_user
ORDER BY Cuenta DESC
LIMIT 10", __FILE__, __LINE__);
$context['muroc'] = array();
while ($row = mysqli_fetch_assoc($murosc))
$context['muroc'][] = array(
'realName' => $row['realName'],
'cuenta' => $row['Cuenta']);
mysqli_free_result($murosc);
$masi=db_query("
SELECT COUNT(m.ID_MEMBER) as Cuenta,u.realName,u.ID_MEMBER
From ({$db_prefix}gallery_pic as m, {$db_prefix}members as u)
WHERE m.ID_MEMBER=u.ID_MEMBER
GROUP BY m.ID_MEMBER
ORDER BY Cuenta DESC
LIMIT 10", __FILE__, __LINE__);
$context['masi'] = array();
while ($row = mysqli_fetch_assoc($masi))
$context['masi'][] = array(
'realName' => $row['realName'],
'cuenta' => $row['Cuenta']);
mysqli_free_result($masi);
$request = db_query("SELECT m.subject, m.ID_TOPIC, m.puntos, b.description
FROM ({$db_prefix}messages AS m,{$db_prefix}boards AS b)
WHERE m.ID_BOARD=b.ID_BOARD
ORDER BY m.puntos DESC
LIMIT 10 ", __FILE__, __LINE__);
$context['postporpuntos'] = array();
while ($row = mysqli_fetch_assoc($request))
$context['postporpuntos'][] = array(
'titulo' => $row['subject'],
'description' => $row['description'],
'puntos' => $row['puntos'],
'id' => $row['ID_TOPIC'],);
mysqli_free_result($request);
$requestq = db_query("
SELECT t.ID_TOPIC,COUNT(c.id_post) as Cuenta,t.subject,b.description
From ({$db_prefix}comentarios as c, {$db_prefix}messages as t,{$db_prefix}boards AS b)
WHERE t.ID_TOPIC = c.id_post AND t.ID_BOARD=b.ID_BOARD
GROUP BY c.id_post
ORDER BY Cuenta DESC
LIMIT 10", __FILE__, __LINE__);
$context['tcomentados'] = array();
while ($row = mysqli_fetch_assoc($requestq))
$context['tcomentados'][] = array(
'subject' => $row['subject'],
'description' => $row['description'],
'cuenta' => $row['Cuenta'],
'id' => $row['ID_TOPIC'],);
mysqli_free_result($requestq);
$requestq2= db_query("
SELECT t.ID_PICTURE,COUNT(c.ID_PICTURE) as Cuenta,t.title
From ({$db_prefix}gallery_comment as c, {$db_prefix}gallery_pic as t)
WHERE t.ID_PICTURE = c.ID_PICTURE
GROUP BY c.ID_PICTURE
ORDER BY Cuenta DESC
LIMIT 10", __FILE__, __LINE__);
$context['comment-img2']= array();
while ($row = mysqli_fetch_assoc($requestq2)){
$context['comment-img2'][] = array(
'title' => $row['title'],
'commenttotal' => $row['Cuenta'],
'id' => $row['ID_PICTURE'],);}
mysqli_free_result($requestq2);

$requestqs=db_query("
SELECT t.ID_MEMBER,COUNT(u.ID_MEMBER) as Cuenta,u.realName
From ({$db_prefix}members as u, {$db_prefix}messages as t)
WHERE t.ID_MEMBER = u.ID_MEMBER
GROUP BY u.ID_MEMBER
ORDER BY Cuenta DESC
LIMIT 10", __FILE__, __LINE__);
$context['tuser'] = array();
while ($row = mysqli_fetch_assoc($requestqs))
$context['tuser'][] = array(
'realName' => $row['realName'],
'cuenta' => $row['Cuenta'],
'id' => $row['ID_MEMBER'],);
mysqli_free_result($requestqs);

$comment_pic3=db_query("
SELECT title,puntos,ID_PICTURE
FROM {$db_prefix}gallery_pic
ORDER BY puntos DESC LIMIT 10", __FILE__, __LINE__);
$context['comment-img3']=array();
while ($row = mysqli_fetch_assoc($comment_pic3))
{$context['comment-img3'][] = array(
'title' => $row['title'],
'puntos' => $row['puntos'],
'id' => $row['ID_PICTURE']);}
mysqli_free_result($comment_pic3);




////////////////////////////////

	$months_result = db_query("
		SELECT
			YEAR(date) AS stats_year, MONTH(date) AS stats_month, SUM(hits) AS hits, SUM(registers) AS registers, SUM(topics) AS topics, SUM(posts) AS posts, MAX(mostOn) AS mostOn, COUNT(*) AS numDays
		FROM {$db_prefix}log_activity
		GROUP BY stats_year, stats_month", __FILE__, __LINE__);
	$context['monthly'] = array();
	while ($row_months = mysqli_fetch_assoc($months_result))
	{
		$ID_MONTH = $row_months['stats_year'] . sprintf('%02d', $row_months['stats_month']);
		$expanded = !empty($_SESSION['expanded_stats'][$row_months['stats_year']]) && in_array($row_months['stats_month'], $_SESSION['expanded_stats'][$row_months['stats_year']]);

		$context['monthly'][$ID_MONTH] = array(
			'id' => $ID_MONTH,
			'date' => array(
				'month' => sprintf('%02d', $row_months['stats_month']),
				'year' => $row_months['stats_year']
			),
			
			'href' => '/tops/' . ($expanded ? 'collapse' : 'expand') . '/' . $ID_MONTH . '#' . $ID_MONTH,
			'link' => '<a href="/tops/' . ($expanded ? 'collapse' : 'expand') . '/' . $ID_MONTH . '#' . $ID_MONTH . '">' . $txt['months'][$row_months['stats_month']] . ' ' . $row_months['stats_year'] . '</a>',
			'month' => $txt['months'][$row_months['stats_month']],
			'year' => $row_months['stats_year'],
			'new_topics' => $row_months['topics'],
			'new_posts' => $row_months['posts'],
			'new_members' => $row_months['registers'],
			'most_members_online' => $row_months['mostOn'],
			'hits' => $row_months['hits'],
			'num_days' => $row_months['numDays'],
			'days' => array(),
			'expanded' => $expanded
		);
	}
	krsort($context['monthly']);

	if (empty($_SESSION['expanded_stats']))
		return;

	$condition = array();
	foreach ($_SESSION['expanded_stats'] as $year => $months)
		if (!empty($months))
			$condition[] = "YEAR(date) = $year AND MONTH(date) IN (" . implode(', ', $months) . ')';

	// No daily stats to even look at?
	if (empty($condition))
		return;

	getDailyStats(implode(' OR ', $condition));
}

function getDailyStats($condition){global $context, $db_prefix;

	$days_result = db_query("
		SELECT YEAR(date) AS stats_year, MONTH(date) AS stats_month, DAYOFMONTH(date) AS stats_day, topics, posts, registers, mostOn, hits
		FROM {$db_prefix}log_activity
		WHERE $condition
		ORDER BY stats_day ASC", __FILE__, __LINE__);
	while ($row_days = mysqli_fetch_assoc($days_result))
		$context['monthly'][$row_days['stats_year'] . sprintf('%02d', $row_days['stats_month'])]['days'][] = array(
			'day' => sprintf('%02d', $row_days['stats_day']),
			'month' => sprintf('%02d', $row_days['stats_month']),
			'year' => $row_days['stats_year'],
			'new_topics' => $row_days['topics'],
			'new_posts' => $row_days['posts'],
			'new_members' => $row_days['registers'],
			'most_members_online' => $row_days['mostOn'],
			'hits' => $row_days['hits']
		);
	mysqli_free_result($days_result);
}
function SMStats(){}

?>