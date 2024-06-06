<?php header("Content-type: text/xml");
function date_iso8601($timestamp=''){
	$timestamp = empty($timestamp) ? time() : $timestamp;
	$gmt =  substr(date("O", $timestamp), 0, 3).':00';
	return date('Y-m-d\TH:i:s',$timestamp).$gmt;}
function text($texto){
$texto=str_replace("'","&apos;",$texto);
$texto=str_replace('"','&quot;',$texto);
$texto=str_replace('>','&gt;',$texto);
$texto=str_replace('<','&lt;',$texto);
return $texto;}
$a=$_GET['a'];
$context['sitemap']['main'] = array('time' => date_iso8601());
$myurl='http://casitaweb.net/';

//empiezan:
echo'<?xml version="1.0" encoding="UTF-8" ?>
<urlset xmlns="http://www.google.com/schemas/sitemap/0.84" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.google.com/schemas/sitemap/0.84 http://www.google.com/schemas/sitemap/0.84/sitemap.xsd">';

echo'<url>
<loc>'.$myurl.'</loc>
<lastmod>'.$context['sitemap']['main']['time'].'</lastmod>
<changefreq>always</changefreq>
<priority>1.0</priority></url>';

require("cw-conexion-seg-0011.php");
global $context,$db_prefix,$modSettings,$user_info;

$request=db_query("
		SELECT m.ID_TOPIC, m.posterTime , b.description, m.subject
		FROM {$db_prefix}messages as m, {$db_prefix}boards as b
		WHERE b.ID_BOARD=m.ID_BOARD
		ORDER BY m.ID_TOPIC DESC
		LIMIT 100", __FILE__, __LINE__);
while ($row = mysqli_fetch_assoc($request))
{$context['sitemap']['topic'][] = array(
			'id' => $row['ID_TOPIC'] . '',
			'description' => $row['description'] . '',
			'subject' => $row['subject'] . '',
			'time' => date_iso8601($row['posterTime']),
		);}mysqli_free_result($request);
foreach ($context['sitemap']['topic'] as $topic){echo'<url>
<loc>'.text($myurl.'post/'.$topic['id'].'/'.$topic['description'].'/'.urls($topic['subject'])).'.html</loc>
<lastmod>'.$topic['time'].'</lastmod>
<changefreq>daily</changefreq>
<priority>0.80</priority></url>';}

echo'</urlset>';?>