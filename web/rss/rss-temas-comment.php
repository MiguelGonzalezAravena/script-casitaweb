<?php
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if (!$id) {
  die('Debes seleccionar el tema.');
}

header('Content-type: application/xml');

echo '<?xml version="1.0" encoding="UTF-8" ?>
<rss version="0.92" xml:lang="spanish">';

require_once(dirname(dirname(dirname(__FILE__))) . '/funcion-seg-1547.php');
global $tranfer1, $db_prefix, $context, $mbname, $boardurl;

$comment_pic = db_query("
  SELECT com.comentario, m.realName, com.id, ar.titulo, ar.id AS ids
  FROM {$db_prefix}members AS m, {$db_prefix}comunidades_comentarios AS com, {$db_prefix}comunidades_articulos AS ar
  WHERE com.id_tema = $id
  AND com.id_user = m.ID_MEMBER
  AND com.id_tema = ar.id
  AND ar.eliminado = 0 
  ORDER BY com.id DESC
  LIMIT 25", __FILE__, __LINE__);

$context['comment-img'] = array();

while ($row = mysqli_fetch_assoc($comment_pic)) {
  $row['comentario'] = strlen($row['comentario']) > 400 ? substr($row['comentario'], 0, 397) . '...' : $row['comentario'];
  $row['comentario'] = parse_bbc($row['comentario']);
  $titulo = html_entity_decode($row['titulo']);

  $context['comment-img'][] = array(
    'comentario' => html_entity_decode($row['comentario']),
    'nom-user' => $row['realName'],
    'id_comment' => $row['id'],
    'id' => $row['ids']
  );
}

mysqli_free_result($comment_pic);

if (!$titulo) {
  die('Este tema no tiene comentarios.');
}

echo '
  <channel>
    <image>
      <url>' . $tranfer1 . '/rss.png</url>
      <title>' . $mbname . ' - Comentarios para el tema: ' . $titulo . '</title>
      <link>' . $boardurl . '/</link>
      <width>111</width>
      <height>32</height>
      <description>Comentarios para el tema ' . $titulo . '</description>
    </image>
    <title>' . $mbname . ' - Comentarios para el tema: ' . $titulo . '</title>
    <link>' . $boardurl . '/</link>
    <description>Comentarios para el tema ' . $titulo . '</description>';

$contando = 1;

foreach ($context['comment-img'] as $comment_img) {
  echo '
    <item>
      <title><![CDATA[#' . $contando++ . ' Comentario de ' . $comment_img['nom-user'] . ']]></title>
      <link>' . $boardurl . '/comunidades/ver/' . $comment_img['id'] . '#comentarios</link>
      <description><![CDATA[' . $comment_img['comentario'] . ']]></description>
      <comments>' . $boardurl . '/comunidades/ver/' . $comment_img['id'] . '#comentar</comments>
    </item>';
}

echo '
  </channel>
</rss>';

?>