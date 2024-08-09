<?php
header('Content-type: application/xml');

echo '<?xml version="1.0" encoding="UTF-8" ?>
<rss version="0.92" xml:lang="es-es">';

require_once (dirname(dirname(dirname(__FILE__))) . '/funcion-seg-1547.php');
global $tranfer1, $db_prefix, $context, $user_info, $mbname, $boardurl;

$shorturl = str_replace(array('http://', 'https://'), '', $boardurl);

echo '
  <channel>
    <image>
      <url>' . $tranfer1 . '/rss.png</url>
      <title>' . $mbname . ' - Últimos temas</title>
      <link>' . $boardurl . '/</link>
      <width>111</width>
      <height>32</height>
      <description>Últimos 10 temas de las comunidades en ' . $shorturl . '</description>
    </image>
    <title>' . $mbname . ' - Últimos temas</title>
    <link>' . $boardurl . '/</link>
    <description>Últimos 10 temas de las comunidades en ' . $shorturl . '</description>';

$rs = db_query("
  SELECT a.titulo, c.nombre, c.url as url2, a.id, a.cuerpo, c.acceso
  FROM {$db_prefix}comunidades AS c, {$db_prefix}comunidades_articulos AS a
  WHERE a.id_com = c.id
  AND c.bloquear = 0
  AND a.eliminado = 0
  AND c.acceso <> 4
  ORDER BY a.id DESC
  LIMIT 10", __FILE__, __LINE__);

$context['posts'] = array();

while ($row = mysqli_fetch_assoc($rs)) {
  $row['cuerpo'] = parse_bbc($row['cuerpo']);
  $row['cuerpo'] = achicar400($row['cuerpo']);

  $context['posts'][] = array(
    'titulo' => html_entity_decode($row['titulo']),
    'nombre' => $row['nombre'],
    'id' => $row['id'],
    'url2' => $row['url2'],
    'acceso' => $row['acceso'],
    'cuerpo' => html_entity_decode($row['cuerpo'])
  );
}

foreach ($context['posts'] as $posts) {
  echo '
    <item>
      <title><![CDATA[' . $posts['titulo'] . ' - Comunidad: ' . $posts['nombre'] . ']]></title>
      <link>' . $boardurl . '/comunidades/' . $posts['url2'] . '/' . $posts['id'] . '/' . urls($posts['titulo']) . '.html</link>
      <description><![CDATA[';

  if ($user_info['is_guest'] && $posts['acceso'] == 2) {
    echo '<center><i>Este tema esta en una comunidad que solo tienen acceso los usuarios que han iniciado sesión</i></center><br />';
  } else if ($user_info['is_guest'] || $rssuser['postprivado'] == 1) {
    echo $posts['cuerpo'];
  }

  echo ']]></description>
      <comments>' . $boardurl . '/comunidades/' . $posts['url2'] . '/' . $posts['id'] . '/' . urls($posts['titulo']) . '.html#comentarios</comments>
    </item>';
}

echo '
  </channel>
</rss>';

?>