<?php
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if (empty($id)) {
  die('Debes seleccionar la imagen.');
}

header('Content-type: application/xml');

echo '<?xml version="1.0" encoding="UTF-8" ?>
<rss version="0.92" xml:lang="spanish">';

require_once(dirname(dirname(dirname(__FILE__))) . '/funcion-seg-1547.php');
global $tranfer1, $db_prefix, $context, $mbname, $boardurl;

$shorturl = str_replace('http://', '', $boardurl);

$comment_pic = db_query("
  SELECT c.comment, img.title, mem.realName, c.ID_COMMENT, img.ID_PICTURE
  FROM {$db_prefix}gallery_comment AS c, {$db_prefix}gallery_pic AS img, {$db_prefix}members AS mem
  WHERE c.ID_PICTURE = $id
  AND c.ID_PICTURE = img.ID_PICTURE
  AND c.ID_MEMBER = mem.ID_MEMBER
  ORDER BY c.ID_COMMENT DESC
  LIMIT 25", __FILE__, __LINE__);

$context['comment-img'] = array();

while ($row = mysqli_fetch_assoc($comment_pic)) {
  $row['comment'] = strlen($row['comment']) > 400 ? substr($row['comment'], 0, 397) . '...' : $row['comment'];
  $row['comment'] = parse_bbc($row['comment']);
  // censorText($row['comment']);
  $titulo = html_entity_decode($row['title']);

  $context['comment-img'][] = array(
    'comentario' => html_entity_decode($row['comment']),
    'nom-user' => $row['realName'],
    'id_comment' => $row['ID_COMMENT'],
    'id' => $row['ID_PICTURE']
  );
}

mysqli_free_result($comment_pic);

if (!$titulo) {
  die('La imagen no existe o no tiene comentarios.');
}

echo '
  <channel>
    <image>
      <url>' . $tranfer1 . '/rss.png</url>
      <title>' . $mbname . ' - Comentarios para la imagen: ' . $titulo . '</title>
      <link>' . $boardurl . '/</link>
      <width>111</width>
      <height>32</height>
      <description>Comentarios para la imagen ' . $titulo . ' de ' . $shorturl . '</description>
    </image>
    <title>' . $mbname . ' - Comentarios para la imagen: ' . $titulo . '</title>
    <link>' . $boardurl . '/</link>
    <description>Comentarios para la imagen ' . $titulo . ' de ' . $shorturl . '</description>';

$contando = 1;
foreach ($context['comment-img'] AS $comment_img) {
  echo '
    <item>
      <title><![CDATA[#' . $contando++ . ' Comentario de ' . $comment_img['nom-user'] . ']]></title>
      <link>' . $boardurl . '/imagenes/ver/' . $comment_img['id'] . '#cmt_' . $comment_img['id_comment'] . '</link>
      <description><![CDATA[' . $comment_img['comentario'] . ']]></description>
      <comments>' . $boardurl . '/imagenes/ver/' . $comment_img['id'] . '#comentar</comments>
    </item>';
}

echo '</channel>
</rss>';

2?>