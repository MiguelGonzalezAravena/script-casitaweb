<?php header("Content-type: application/xml");
echo'<?xml version="1.0" encoding="UTF-8" ?>
<rss version="0.92" xml:lang="es-es">';
require("../../funcion-seg-1547.php");
global $tranfer1,$db_prefix,$context,$user_info;
echo'<channel><image>
<url>'.$tranfer1.'/rss.png</url>
<title>CasitaWeb! - Ultimos temas</title>
<link>http://casitaweb.net/</link>
<width>111</width>
<height>32</height>
<description>Ultimos 10 temas de las comunidades en casitaweb.net</description>
</image>
<title>CasitaWeb! - Ultimos temas</title>
<link>http://casitaweb.net/</link>
<description>Ultimos 10 temas de las comunidades en casitaweb.net</description>';

$rs=db_query("SELECT a.titulo,c.nombre,c.url as url2,a.id,a.cuerpo,c.acceso
FROM ({$db_prefix}comunidades AS c, {$db_prefix}comunidades_articulos AS a)
WHERE a.id_com=c.id AND c.bloquear=0 AND a.eliminado=0 AND a.acceso <> 4
ORDER BY a.id DESC
LIMIT 10",__FILE__, __LINE__);
$context['posts']=array();
while ($row=mysqli_fetch_assoc($rs)){
    
$row['cuerpo']=nohtml(nohtml2($row['cuerpo']));
$row['cuerpo']=parse_bbc($row['cuerpo']);
$row['cuerpo']=achicar400($row['cuerpo']);
    $context['posts'][]=array(
    'titulo' => nohtml2($row['titulo']),
    'nombre' => $row['nombre'],
    'id' => $row['id'],
    'url2' => $row['url2'],
    'acceso' => $row['acceso'],    
    'cuerpo' => $row['cuerpo']);}

foreach($context['posts'] AS $posts ){
echo'<item><title><![CDATA['. $posts['titulo'] .' - Comunidad: '. $posts['nombre'] .']]></title>
<link>http://casitaweb.net/comunidades/'.$posts['url2'].'/'.$posts['id'].'/'.urls($posts['titulo']).'.html</link>
<description><![CDATA[';  



if($user_info['is_guest']){
if($posts['cuerpo']=='2'){echo'<center><i>Este tema esta en una comunidad que solo tienen acceso los usuarios loueados</i></center><br />';}elseif($rssuser['postprivado']=='1'){echo $posts['cuerpo'];}}else{echo $posts['cuerpo'];}

echo']]></description>
<comments>http://casitaweb.net/comunidades/'.$posts['url2'].'/'.$posts['id'].'/'.urls($posts['titulo']).'.html#comentarios</comments>
</item>';}
echo'</channel>
</rss>';?>