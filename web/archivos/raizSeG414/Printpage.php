<?php
//Pagina de Rodrigo Zaupa (rigo@casitaweb.net)
if (!defined('CasitaWeb!-PorRigo'))die(base64_decode("d3d3LmNhc2l0YXdlYi5uZXQgLSByaWdv"));
function PrintTopic(){global $db_prefix,$context;

$topics=(int)$_GET['post'];
if(empty($topics)){die('Debes seleccionar un post.-');}
	$request = db_query("
		SELECT m.posterTime, IFNULL(mem.realName, m.posterName) AS posterName,b.description,m.ID_TOPIC,b.name,m.subject, m.posterName, m.body, m.hiddenOption
		FROM ({$db_prefix}messages AS m,{$db_prefix}boards AS b)
			LEFT JOIN {$db_prefix}members AS mem ON (mem.ID_MEMBER = m.ID_MEMBER)
		WHERE m.ID_TOPIC='$topics' AND m.ID_BOARD<>142 AND m.ID_BOARD=b.ID_BOARD
		ORDER BY m.ID_TOPIC
		LIMIT 1", __FILE__, __LINE__);
	if(!mysqli_num_rows($request)){die('Este post no existe.-');}
	$row = mysqli_fetch_assoc($request);
	mysqli_free_result($request);

	loadTemplate('Printpage');
	$context['template_layers'] = array('print');
	$context['description'] = $row['description'];
	$context['board_name'] = $row['name'];
	$context['subject'] = censorText($row['subject']);
	$context['poster_name'] = $row['posterName'];
	$context['id'] = $row['ID_TOPIC'];
	$context['member'] = $row['posterName'];
	$context['body'] = parse_bbc($row['body'], 'print');
	$context['hiddenOption'] = $row['hiddenOption'];
	$context['post_time'] = timeformat($row['posterTime'], false);
    
$context['haycomentssss'] = mysqli_num_rows(db_query("
		SELECT c.fecha
		FROM {$db_prefix}comentarios AS c
		WHERE c.id_post='{$context['id']}'", __FILE__, __LINE__));
$request33 = db_query("
		SELECT c.fecha,c.comentario,mem.realName
		FROM {$db_prefix}comentarios AS c
			LEFT JOIN {$db_prefix}members AS mem ON (mem.ID_MEMBER = c.id_user)
		WHERE c.id_post='{$context['id']}'
		ORDER BY id_coment DESC", __FILE__, __LINE__);
	$context['coment'] = array();
	while ($row = mysqli_fetch_assoc($request33))
	{
		$context['coment'][] = array(
			'realName' => $row['realName'],
			'time' =>  hace($row['fecha']),
			'comentario' => parse_bbc($row['comentario'], 'print'),
		);
	}
	mysqli_free_result($request33);
}

?>