<?php header("Content-type: application/xml");
echo'<?xml version="1.0" encoding="UTF-8" ?>
<rss version="0.92" xml:lang="es-es">';
require("../../funcion-seg-1547.php");
global $tranfer1,$db_prefix,$context,$user_info;
echo'<channel><image>
<url>'.$tranfer1.'/rss.png</url>
<title>CasitaWeb! - Ultimos Post</title>
<link>http://casitaweb.net/</link>
<width>111</width>
<height>32</height>
<description>Ultimos 10 post de casitaweb.net</description>
</image>
<title>CasitaWeb! - Ultimos Post</title>
<link>http://casitaweb.net/</link>
<description>Ultimos 10 post de casitaweb.net</description>';
$existe=db_query("SELECT m.body,m.subject,m.ID_TOPIC,m.hiddenOption,b.description
FROM ({$db_prefix}messages AS m, {$db_prefix}boards as b)
WHERE b.ID_BOARD=m.ID_BOARD AND m.ID_BOARD<>142
ORDER BY m.ID_TOPIC DESC LIMIT 10",__FILE__, __LINE__);
$context['rssuser'] = array();
while ($row = mysqli_fetch_assoc($existe))
{$row['body'] = nohtml($row['body']);
$row['body'] = parse_bbc($row['body'], 1, $row['ID_MSG']);
$row['body'] = str_replace(' onload="if(this.width >720) {this.width=720}"','',$row['body']);
$row['body']= str_replace('&lt;br /&gt;', "<br />", $row['body']);
$row['body'] = achicar400($row['body']);
censorText($row['body']);
censorText($row['subject']);
$row['body']=str_replace('[img ]','[img]',$row['body']);
$context['rssuser'][] = array(
'id' => $row['ID_TOPIC'], 
'titulo' => $row['subject'], 
'description' => $row['description'], 
'body' => $row['body'], 
'postprivado' => $row['hiddenOption'],);}
mysqli_free_result($existe);
foreach($context['rssuser'] AS $rssuser){echo'<item>
<title><![CDATA['. $rssuser['titulo'] .']]></title>
<link>http://casitaweb.net/post/'. $rssuser['id'] .'/'.$rssuser['description'].'/'.censorText(urls($rssuser['titulo'])).'.html</link>
<description><![CDATA[';if($user_info['is_guest']){
if($rssuser['postprivado']==='1')echo'<center><i>Este es un post privado, para verlo debes autentificarte. - casitaweb.net</i></center><br />';
if($rssuser['postprivado']==='0')
echo $rssuser['body'];}if(!$user_info['is_guest']){echo $rssuser['body'];}
echo']]></description>
<comments>http://casitaweb.net/post/'. $rssuser['id'] .'/'.$rssuser['description'].'/'.censorText(urls($rssuser['titulo'])).'.html#comentarios</comments>
</item>';}
echo'</channel>
</rss>';?>