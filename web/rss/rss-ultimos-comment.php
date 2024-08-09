<?php
header('Content-type: application/xml');

echo '<?xml version="1.0" encoding="UTF-8" ?>
<rss version="0.92" xml:lang="spanish">';

require_once(dirname(dirname(dirname(__FILE__))) . '/funcion-seg-1547.php');
global $tranfer1, $db_prefix, $context, $mbname, $boardurl;

$shorturl = str_replace(array('http://', 'https://'), '', $boardurl);

echo '
  <channel>
    <image>
      <url>' . $tranfer1 . '/rss.png</url>
      <title>' . $mbname . ' - Comentarios de los post</title>
      <link>' . $boardurl . '/</link>
      <width>111</width>
      <height>32</height>
      <description>Últimos 25 comentarios de los post en ' . $shorturl . '</description>
    </image>
    <title>' . $mbname . ' - Comentarios de los post</title>
    <link>' . $boardurl . '/</link>
    <description>Últimos 25 comentarios de los post en ' . $shorturl . '</description>';

$rs = db_query("
  SELECT c.id_coment, c.comentario, m.subject, m.ID_TOPIC, b.description, mem.realName
  FROM {$db_prefix}comentarios AS c, {$db_prefix}messages AS m, {$db_prefix}members AS mem, {$db_prefix}boards AS b
  WHERE id_post = m.ID_TOPIC
  AND c.id_user = mem.ID_MEMBER
  AND m.ID_BOARD = b.ID_BOARD
  ORDER BY c.id_coment DESC
  LIMIT 25", __FILE__, __LINE__);

$context['comentarios25'] = array();

while ($row = mysqli_fetch_assoc($rs)) {
  $row['comentario'] = parse_bbc($row['comentario'], 1);
  // censorText($row['subject']);
  // censorText($row['comentario']);
  $row['comentario'] = achicar400($row['comentario']);

  $context['comentarios25'][] = array(
    'id_comment' => $row['id_coment'],
    'comentario' => html_entity_decode($row['comentario']),
    'titulo' => html_entity_decode($row['subject']),
    'id' => $row['ID_TOPIC'],
    'description' => $row['description'],
    'memberName' => $row['realName'],
    'nom-user' => $row['realName'],
  );
}

mysqli_free_result($rs);

$contando = 1;

foreach ($context['comentarios25'] as $comment) {
  echo '
    <item>
      <title><![CDATA[' . $comment['memberName'] . ' - ' . $comment['titulo'] . ']]></title>
      <link>' . $boardurl . '/post/' . $comment['id'] . '/' . $comment['description'] . '/' . urls($comment['titulo']) . '.html#cmt_' . $comment['id_comment'] . '</link>
      <description><![CDATA[' . $comment['comentario'] . ']]></description>
      <comments>' . $boardurl . '/post/' . $comment['id'] . '/' . $comment['description'] . '/' . urls($comment['titulo']) . '.html#comentarios</comments>
    </item>';
}

echo '
  </channel>
</rss>';

?>