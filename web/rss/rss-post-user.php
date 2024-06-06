<?php header("Content-type: application/xml");
echo'<?xml version="1.0" encoding="UTF-8" ?>
<rss version="0.92" xml:lang="spanish">';
require("../../funcion-seg-1547.php");
global $tranfer1,$db_prefix,$context;
$us=seguridad($_GET['id']);
if(empty($us)){die('Debes seleccionar un usuario.-');exit;}
$resp=db_query("select ID_MEMBER from {$db_prefix}members where memberName='$us'",__FILE__, __LINE__);
$datos=mysqli_fetch_array($resp) ;
$u=$datos['ID_MEMBER'];
if(empty($u)){die('El usuario seleccionado no existe.-');exit;}
$existesd=db_query("
SELECT mem.realName
FROM ({$db_prefix}members AS mem)
WHERE mem.ID_MEMBER='$u'",__FILE__, __LINE__);
while ($row = mysqli_fetch_assoc($existesd))
{$userpost = $row['realName'];
$ID_MEMBER = $row['ID_MEMBER'];}
mysqli_free_result($existe);
echo'<channel>
<image>
<url>'.$tranfer1.'/rss.png</url>
<title>CasitaWeb! - Post creados por el usuario: '.$userpost. '</title>
<link>http://casitaweb.net/</link>
<width>111</width>
<height>32</height>
<description>Ultimos 25 post creados por el usuario '.$userpost. ' en casitaweb.net</description></image>
<title>CasitaWeb! - Post creados por el usuario: '.$userpost. '</title>
<link>http://casitaweb.net/</link>
<description>Ultimos 25 post creados por el usuario '.$userpost. ' en casitaweb.net</description>';
$existe=db_query("SELECT m.body,m.ID_TOPIC,c.description,m.subject,m.hiddenOption
FROM ({$db_prefix}messages AS m, {$db_prefix}boards AS c)
WHERE m.ID_MEMBER='$u' AND m.ID_BOARD=c.ID_BOARD
ORDER BY m.ID_TOPIC DESC LIMIT 25",__FILE__, __LINE__);
$context['rssuser'] = array();
while ($row = mysqli_fetch_assoc($existe))
{$row['body'] = parse_bbc($row['body']); 
$row['body'] = strtr($func['substr'](str_replace('<br />', "\n", $row['body']), 0, 400 - 3), array("\n" => '<br />')) . '...';	
$context['rssuser'][] = array(
'id' => $row['ID_TOPIC'],
'description' => $row['description'],
'titulo' => $row['subject'],
'body' => $row['body'],
'postprivado' => $row['hiddenOption'],);}
mysqli_free_result($existe);	
foreach($context['rssuser'] AS $rssuser){echo'<item>
<title><![CDATA['. censorText($rssuser['titulo']) .']]></title>
<link>http://casitaweb.net/post/'.$rssuser['id'].'/'.$rssuser['description'].'/'.censorText(urls($rssuser['titulo'])).'.html</link><description><![CDATA[';
if($context['user']['is_guest']){if($rssuser['postprivado']=='1')
echo'<center><i>Este es un post privado, para verlo debes autentificarte. - casitaweb.net</i></center><br/>';
if($rssuser['postprivado']=='0')
echo censorText($rssuser['body']);}
if($context['user']['is_logged']){
echo censorText($rssuser['body']);}
echo']]></description>
<comments>http://casitaweb.net/post/'.$rssuser['description'].'/'.censorText(urls($rssuser['titulo'])).'.html</comments></item>';}
echo'</channel></rss>';?>