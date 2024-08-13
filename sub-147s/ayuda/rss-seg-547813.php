<?php
header('Content-type: application/rss+xml');
echo '<?xml version="1.0" encoding="UTF-8" ?>
<rss version="0.92" xml:lang="spanish">';

require_once(dirname(__FILE__) . '/config-seg-16a5s4das.php');

global $prefijo, $tranfer1, $mbname, $helpurl;

echo '
  <channel>
    <image>
      <url>' . $tranfer1 . '/rss.png</url>
      <title>' . $mbname . ' - Ayuda - RSS</title>
      <link>' . $helpurl . '/</link>
      <width>111</width>
      <height>32</height>
      <description></description>
    </image>
    <title>' . $mbname . ' - Ayuda - RSS</title>
    <link>' . $helpurl . '/</link>
    <description></description>';

$casd = db("
  SELECT titulo, id, contenido
  FROM {$prefijo}articulos
  ORDER BY id DESC
  LIMIT 10", __FILE__, __LINE__);

while ($row = mysqli_fetch_assoc($casd)) {
  $row['contenido'] = censorText($row['contenido']);
  $row['titulo'] = censorText($row['titulo']);
  $row['contenido'] = str_replace('http://link.casitaweb.net/index.php?l=', '', $row['contenido']);
  echo '
    <item>
      <title><![CDATA[' . $row['titulo'] . ']]></title>
      <link>' . $helpurl . '/articulo/' . $row['id'] . '</link>
      <description><![CDATA[' . parse_bbc(achicar400($row['contenido'])) . ']]></description>
      <comments>' . $helpurl . '/articulo/' . $row['id'] . '</comments>
    </item>';
}

echo '
  </channel>
</rss>';

?>