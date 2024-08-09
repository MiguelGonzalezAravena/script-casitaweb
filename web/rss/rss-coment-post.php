<?php
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if (empty($id)) {
  die('Debes seleccionar el post.');
}

header('Content-type: application/xml');

echo '<?xml version="1.0" encoding="UTF-8" ?>
<rss version="0.92" xml:lang="spanish">';

require_once(dirname(dirname(dirname(__FILE__))) . '/funcion-seg-1547.php');
global $tranfer1, $db_prefix, $context, $mbname, $boardurl;

$shorturl = str_replace(array('http://', 'https://'), '', $boardurl);

$request = db_query("
  SELECT id_post
  FROM {$db_prefix}comentarios
  WHERE id_post = $id", __FILE__, __LINE__);

$contador = mysqli_num_rows($request);

if (empty($contador)) {
  die('El post no existe o no tiene comentarios.');
}

$comentpost = db_query("
  SELECT 
    c.id_post, c.comentario, c.id_coment, m.subject, m.ID_TOPIC, c.id_user,
    mem.ID_MEMBER, mem.realName, mem.memberName, b.description, m.ID_BOARD
  FROM {$db_prefix}comentarios AS c, {$db_prefix}messages AS m, {$db_prefix}members AS mem, {$db_prefix}boards AS b
  WHERE c.id_post = $id
  AND c.id_post = m.ID_TOPIC
  AND c.id_user = mem.ID_MEMBER
  AND m.ID_BOARD = b.ID_BOARD
  AND m.ID_BOARD <> 140
  ORDER BY c.id_coment ASC", __FILE__, __LINE__);

$context['rssuser'] = array();

while ($row = mysqli_fetch_assoc($comentpost)) {
  $row['comentario'] = parse_bbc($row['comentario'], 1, $row['ID_MSG']);

  $context['rssuser'][] = array(
    'id' => $row['id_coment'],
    'username' => $row['realName'],
    'body' => html_entity_decode($row['comentario']),
    'titulo' => html_entity_decode($row['subject']),
    'description' => $row['description'],
    'postprivado' => $row['hiddenOption'],
  );

  $titulo = html_entity_decode($row['subject']);
}

mysqli_free_result($comentpost);

echo '
  <channel>
    <image>
      <url>' . $tranfer1 . '/rss.png</url>
      <title>' . $mbname . ' - Comentarios para el post: ' . $titulo . '</title>
      <link>' . $boardurl . '/</link>
      <width>111</width>
      <height>32</height>
      <description>Comentarios para el post ' . $titulo . ' de ' . $shorturl . '</description>
    </image>
    <title>' . $mbname . ' - Comentarios para el post: ' . $titulo . '</title>
    <link>' . $boardurl . '/</link>
    <description>Comentarios para el post ' . $titulo . ' de ' . $shorturl . '</description>';

$contando = 1;

foreach ($context['rssuser'] as $rssuser) {
  echo '
    <item>
      <title><![CDATA[#' . $contando++ . ' Comentario de ' . $rssuser['username'] . ']]></title>
      <link>' . $boardurl . '/post/' . $rssuser['id'] . '/' . $rssuser['description'] . '/' . censorText(urls($rssuser['titulo'])) . '.html</link>
      <description><![CDATA[';

  // TO-DO: El $context['user'] no existe en este archivo
  // Usar $user_info['is_guest']
  if ($user_info['is_guest'] && $rssuser['postprivado'] == 1) {
    echo '<center><i>Este es un post privado, para verlo debes iniciar sesión - ' . $shorturl . '</i></center><br />';
  } else if (!$user_info['is_guest'] && $rssuser['postprivado'] == 1 || $rssuser['postprivado'] == 0) {
    echo achicar400($rssuser['body']);
  }

  echo ']]></description>
      <comments>' . $boardurl . '/post/' . $rssuser['id'] . '/' . $rssuser['description'] . '/' . censorText(urls($rssuser['titulo'])) . '.html#comentar</comments>
    </item>';
}

echo '
  </channel>
</rss>';

?>