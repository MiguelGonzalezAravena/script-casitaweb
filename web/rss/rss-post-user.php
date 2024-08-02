<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/funcion-seg-1547.php');

$us = isset($_GET['id']) ? seguridad($_GET['id']) : '';

if (empty($us)) {
  die('Debes seleccionar un usuario.');
}

header('Content-type: application/xml');

echo '<?xml version="1.0" encoding="UTF-8" ?>
<rss version="0.92" xml:lang="spanish">';

global $tranfer1, $db_prefix, $context, $user_info, $mbname, $boardurl;

$shorturl = str_replace('http://', '', $boardurl);

$request = db_query("
  SELECT ID_MEMBER
  FROM {$db_prefix}members
  WHERE memberName = '$us'", __FILE__, __LINE__);

$row = mysqli_fetch_array($request);
$u = isset($row['ID_MEMBER']) ? $row['ID_MEMBER'] : '';

mysqli_free_result($request);

if (empty($u)) {
  die('El usuario seleccionado no existe.');
}

$request = db_query("
  SELECT realName
  FROM {$db_prefix}members
  WHERE ID_MEMBER = '$u'
  LIMIT 1", __FILE__, __LINE__);

$row = mysqli_fetch_assoc($request);
$userpost = isset($row['realName']) ? $row['realName'] : '';
$ID_MEMBER = isset($row['ID_MEMBER']) ? $row['ID_MEMBER'] : '';

mysqli_free_result($request);

echo '
  <channel>
    <image>
      <url>' . $tranfer1 . '/rss.png</url>
      <title>' . $mbname . ' - Post creados por el usuario: ' . $userpost . '</title>
      <link>' . $boardurl . '/</link>
      <width>111</width>
      <height>32</height>
      <description>Últimos 25 post creados por el usuario ' . $userpost . ' en ' . $shorturl . '</description>
    </image>
    <title>' . $mbname . ' - Post creados por el usuario: ' . $userpost . '</title>
    <link>' . $boardurl . '/</link>
    <description>Últimos 25 post creados por el usuario ' . $userpost . ' en ' . $shorturl . '</description>';

$existe = db_query("
  SELECT m.body, m.ID_TOPIC, c.description, m.subject, m.hiddenOption
  FROM {$db_prefix}messages AS m, {$db_prefix}boards AS c
  WHERE m.ID_MEMBER = $u
  AND m.ID_BOARD = c.ID_BOARD
  ORDER BY m.ID_TOPIC DESC
  LIMIT 25", __FILE__, __LINE__);

$context['rssuser'] = array();

while ($row = mysqli_fetch_assoc($existe)) {
  $row['body'] = parse_bbc($row['body']);
  $row['body'] = strtr(substr(str_replace('<br />', "\n", $row['body']), 0, 400 - 3), array("\n" => '<br />')) . '...';

  $context['rssuser'][] = array(
    'id' => $row['ID_TOPIC'],
    'description' => $row['description'],
    'titulo' => html_entity_decode($row['subject']),
    'body' => html_entity_decode($row['body']),
    'postprivado' => $row['hiddenOption'],
  );
}

mysqli_free_result($existe);

foreach ($context['rssuser'] as $rssuser) {
  echo '
    <item>
      <title><![CDATA[' . $rssuser['titulo'] . ']]></title>
      <link>' . $boardurl . '/post/' . $rssuser['id'] . '/' . $rssuser['description'] . '/' . urls($rssuser['titulo']) . '.html</link>
      <description><![CDATA[';

  if ($user_info['is_guest'] && $rssuser['postprivado'] == 1) {
    echo '<center><i>Este es un post privado, para verlo debes autentificarte - ' . $shorturl . '</i></center><br />';
  } else if ($rssuser['postprivado'] == 0 || !$user_info['is_guest']) {
    echo $rssuser['body'];
  }

  echo ']]></description>
      <comments>' . $boardurl . '/post/' . $rssuser['description'] . '/' . urls($rssuser['titulo']) . '.html</comments>
    </item>';
}

echo '
  </channel>
</rss>';

?>