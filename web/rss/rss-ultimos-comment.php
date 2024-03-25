<?php header("Content-type: application/xml");
echo'<?xml version="1.0" encoding="UTF-8" ?>
<rss version="0.92" xml:lang="spanish">';
require("../../funcion-seg-1547.php");
global $tranfer1,$db_prefix,$context;
echo'<channel>
<image>
<url>'.$tranfer1.'/rss.png</url>
<title>CasitaWeb! - Comentarios de los post</title>
<link>http://casitaweb.net/</link>
<width>111</width>
<height>32</height>
<description>Ultimos 25 comentarios de los post en casitaweb.net</description>
</image>
<title>CasitaWeb! - Comentarios de los post</title>
<link>http://casitaweb.net/</link>
<description>Ultimos 25 comentarios de los post en casitaweb.net</description>';
$rs=db_query("SELECT c.id_coment,c.comentario,m.subject,m.ID_TOPIC,b.description,mem.realName
FROM ({$db_prefix}comentarios AS c, {$db_prefix}messages AS m, {$db_prefix}members AS mem, {$db_prefix}boards as b)
WHERE id_post=m.ID_TOPIC AND c.id_user=mem.ID_MEMBER AND m.ID_BOARD=b.ID_BOARD
ORDER BY c.id_coment DESC
LIMIT 25",__FILE__, __LINE__);
$context['comentarios25'] = array();
while ($row = mysql_fetch_assoc($rs)){
$row['comentario']=parse_bbc($row['comentario'], 1); 
censorText($row['subject']);
censorText($row['comentario']);
$row['comentario']=achicar400($row['comentario']);
$context['comentarios25'][] = array(
		'id_comment' => $row['id_coment'],
		'comentario' => $row['comentario'],
			'titulo' => $row['subject'],
			'id' => $row['ID_TOPIC'],
			'description' => $row['description'],
			'memberName' => $row['realName'],
			'nom-user' => $row['realName'],
		);}mysql_free_result($rs);
$contando=1;
foreach($context['comentarios25'] AS $comment){
echo'<item>
<title><![CDATA['. $comment['memberName'] .' - '. $comment['titulo'] .']]></title>
<link>http://casitaweb.net/post/'.$comment['id'].'/'.$comment['description'].'/'.urls(censorText($comment['titulo'])).'.html#cmt_'. $comment['id_comment'] .'</link>
<description><![CDATA['. $comment['comentario'] .']]></description>
<comments>http://casitaweb.net/post/'.$comment['id'].'/'.$comment['description'].'/'.urls(censorText($comment['titulo'])).'.html#comentarios</comments></item>';}
echo'</channel></rss>';?>