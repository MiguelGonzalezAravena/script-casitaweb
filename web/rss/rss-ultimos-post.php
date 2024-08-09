<?php
header('Content-type: application/xml');

echo '<?xml version="1.0" encoding="UTF-8" ?>
<rss version="0.92" xml:lang="es-es">';

require_once(dirname(dirname(dirname(__FILE__))) . '/funcion-seg-1547.php');
global $tranfer1, $db_prefix, $context, $user_info, $mbname, $boardurl;

$shorturl = str_replace(array('http://', 'https://'), '', $boardurl);

echo '
  <channel>
    <image>
      <url>' . $tranfer1 . '/rss.png</url>
      <title>' . $mbname . ' - Últimos Post</title>
      <link>' . $boardurl . '/</link>
      <width>111</width>
      <height>32</height>
      <description>Últimos 10 post de ' . $shorturl . '</description>
    </image>
    <title>' . $mbname . ' - Últimos Post</title>
    <link>' . $boardurl . '/</link>
    <description>Últimos 10 post de ' . $shorturl . '</description>';

$existe = db_query("
  SELECT m.body, m.subject, m.ID_TOPIC, m.hiddenOption, b.description
  FROM {$db_prefix}messages AS m, {$db_prefix}boards AS b
  WHERE b.ID_BOARD = m.ID_BOARD
  AND m.ID_BOARD <> 142
  ORDER BY m.ID_TOPIC DESC
  LIMIT 10", __FILE__, __LINE__);

$context['rssuser'] = array();

while ($row = mysqli_fetch_assoc($existe)) {
  $row['body'] = parse_bbc($row['body'], 1, $row['ID_MSG']);
  $row['body'] = str_replace(' onload="if(this.width >720) {this.width=720}"', '', $row['body']);
  $row['body'] = str_replace('&lt;br /&gt;', '<br />', $row['body']);
  $row['body'] = achicar400($row['body']);
  // censorText($row['body']);
  // censorText($row['subject']);
  $row['body'] = str_replace('[img ]', '[img]', $row['body']);

  $context['rssuser'][] = array(
    'id' => $row['ID_TOPIC'],
    'titulo' => html_entity_decode($row['subject']),
    'description' => $row['description'],
    'body' => html_entity_decode($row['body']),
    'postprivado' => $row['hiddenOption'],
  );
}

mysqli_free_result($existe);

foreach ($context['rssuser'] as $rssuser) {
  echo '
    <item>
      <title><![CDATA[' . $rssuser['titulo'] . ']]></title>
      <link>' . $boardurl . '/post/' . $rssuser['id'] . '/' . $rssuser['description'] . '/' . censorText(urls($rssuser['titulo'])) . '.html</link>
      <description><![CDATA[';

  if ($user_info['is_guest']) {
    if ($rssuser['postprivado'] === 1) {
      echo '<center><i>Este es un post privado, para verlo debes autentificarte. - ' . $shorturl . '</i></center><br />';
    }

    if ($rssuser['postprivado'] === '0') {
      echo $rssuser['body'];
    }
  }

  if (!$user_info['is_guest']) {
    echo $rssuser['body'];
  }

  echo ']]></description>
      <comments>' . $boardurl . '/post/' . $rssuser['id'] . '/' . $rssuser['description'] . '/' . censorText(urls($rssuser['titulo'])) . '.html#comentarios</comments>
    </item>';
}

echo '
  </channel>
</rss>';

?>