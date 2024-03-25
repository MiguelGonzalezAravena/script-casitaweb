<?php header('Content-type: application/rss+xml');
echo'<?xml version="1.0" encoding="UTF-8" ?>
<rss version="0.92" xml:lang="spanish">';
include("config-seg-16a5s4das.php");
global $prefijo,$tranfer1;
echo'<channel>
<image>
<url>'.$tranfer1.'/rss.png</url>
<title>CasitaWeb! - Ayuda - RSS</title>
<link>http://ayuda.casitaweb.net/</link>
<width>111</width>
<height>32</height>
<description></description>
</image>
<title>CasitaWeb! - Ayuda - RSS</title>
<link>http://ayuda.casitaweb.net/</link>
<description></description>';
$casd=db("SELECT titulo,id,contenido
FROM {$prefijo}articulos
ORDER BY id DESC
LIMIT 10", __FILE__, __LINE__);
while ($row = mysql_fetch_assoc($casd)){
$row['contenido']=censorText(nohtml2($row['contenido']));
$row['titulo']=censorText(nohtml2($row['titulo']));
$row['contenido']=str_replace('http://link.casitaweb.net/index.php?l=','',$row['contenido']);
echo'<item>
<title><![CDATA['.$row['titulo'].']]></title>
<link>http://ayuda.casitaweb.net/articulo/'.$row['id'].'</link>
<description><![CDATA[';echo''.parse_bbc(achicar400($row['contenido'])) .'';echo']]></description>
<comments>http://ayuda.casitaweb.net/articulo/'.$row['id'].'</comments></item>';}
echo'</channel></rss>'; ?>