<?php
//Pagina de Rodrigo Zaupa (rigo@casitaweb.net)
if (!defined('CasitaWeb!-PorRigo'))die(base64_decode("d3d3LmNhc2l0YXdlYi5uZXQgLSByaWdv"));
function Posts(){global $db_prefix, $context, $user_info,$user_settings;
$post=isset($_GET['post']) ? (int)$_GET['post'] : '';  
loadTemplate('Posts');
if(empty($context['id-post'])){post_error();}
elseif($context['eliminado']){post_error($context['titulo']);}


if($context['user']['is_guest'] && $context['oculto']){fatal_error('Este post es privado, para verlo debes autentificarte.', false);}

if(empty($_SESSION['last_read_topic']) || $_SESSION['last_read_topic'] != $post)
	{		db_query("
			UPDATE {$db_prefix}messages
			SET visitas = visitas + 1
			WHERE ID_TOPIC='$post'
			LIMIT 1", __FILE__, __LINE__);
		$_SESSION['last_read_topic']=$post;
	}

// aca marca si hay comentarios
$context['numcom']=mysqli_num_rows(db_query("SELECT id_post FROM ({$db_prefix}comentarios) WHERE id_post='{$post}'", __FILE__, __LINE__));
// aca marca si hay favoritos
$context['fav1']=mysqli_num_rows(db_query("SELECT o.ID_TOPIC,o.tipo FROM ({$db_prefix}favoritos AS o) WHERE o.ID_TOPIC='{$post}' AND o.tipo=0", __FILE__, __LINE__));

// aca marca los comentarios
$request = db_query("SELECT c.comentario, c.comentario AS comentario2, c.id_post, c.id_user, mem.ID_MEMBER, mem.memberName, mem.realName, c.id_coment, c.fecha
FROM ({$db_prefix}comentarios AS c, {$db_prefix}members AS mem) 
WHERE c.id_post='$post' AND c.id_user=mem.ID_MEMBER
ORDER BY c.id_coment ASC", __FILE__, __LINE__);
$context['comentarios'] = array();
while ($row = mysqli_fetch_assoc($request))
{
        $row['comentario2']=$row['comentario2'];
		$row['comentario'] = parse_bbc($row['comentario'], '1');
		censorText($row['comentario']);
		censorText($row['comentario2']);
		$context['comentarios'][] = array(
		    'comentario2' => $row['comentario2'],
			'comentario' => $row['comentario'],
			'user' => $row['id_user'],
			'nomuser' => $row['realName'],
			'nommem' => $row['memberName'],
			'id' => $row['id_coment'],
			'fecha' => $row['fecha'],);}
mysqli_free_result($request);

if(empty($user_info['is_guest'])){
$context['idgrup'] =$user_settings['ID_POST_GROUP'];
$context['pdia'] =$user_settings['puntos_dia'];
$context['leecher'] =$user_settings['ID_POST_GROUP'] == '4';
$context['novato'] =$user_settings['ID_POST_GROUP'] == '5';
$context['buenus'] =$user_settings['ID_POST_GROUP'] == '6';}else{
$context['leecher'] = '1';}
}
function post_error($titulo = ''){
global $tranfer1, $context, $settings, $options, $txt, $db_prefix;
$titulo=trim($titulo);
if($titulo) $context['page_title']=$titulo; else $context['page_title']=$txt[18]; 

echo template_main_above();


echo'<div class="noesta" style="margin-bottom:8px;width:922px"><div class="post-deleted"><h3>Este post no existe o fue eliminado!</h3>Pero OJO no es el unico post en CasitaWeb!.</div></div>

<table class="linksList" style="width: 922px;">
<thead align="center">
<tr>
<th style="text-align:left;">Otros posts</th>
<th>Por</th>
<th>Fecha</th></tr>
</thead><tbody>';

if($titulo){
$tit=explode(' ',$titulo);
$tit=array_filter($tit);
$dc=(count($tit)-1);
for($i=1; $i<=$dc;++$i){$n[]="palabra='".str_replace("'","",$tit[$i])."'";}
$ff=join(" OR ",$n);
$select=db_query("SELECT id_post FROM {$db_prefix}tags WHERE $ff GROUP BY id_post ORDER BY id_post DESC LIMIT 10", __FILE__, __LINE__);
while($row24 = mysqli_fetch_assoc($select)){
$request=db_query("
SELECT m.ID_TOPIC,m.subject,b.description, m.posterTime, m.posterName
FROM ({$db_prefix}messages AS m)
INNER JOIN {$db_prefix}boards AS b ON m.ID_TOPIC='{$row24['id_post']}' AND m.ID_BOARD=b.ID_BOARD AND m.eliminado=0
ORDER BY m.ID_TOPIC DESC
LIMIT 1", __FILE__, __LINE__);
while($row=mysqli_fetch_assoc($request)){
echo'<tr><td style="text-align:left;" ><a rel="dc:relation" class="categoriaPost '.$row['description'].'" href="/post/'.$row['ID_TOPIC'].'/'.$row['description'].'/'.urls($row['subject']).'.html" title="'.$row['subject'].'">'.$row['subject'].'</a></td>
<td title="'.$row['posterName'].'"><a href="/perfil/'.$row['posterName'].'">'.$row['posterName'].'</a></td>
<td title="'.timeformat($row['posterTime']).'">'.hace($row['posterTime'],true).'</td></tr>';}
mysqli_free_result($request);}
mysqli_free_result($select);}



else{
$request3=db_query("
SELECT m.ID_TOPIC,m.subject,b.description, m.posterTime, m.posterName
FROM ({$db_prefix}messages as m,{$db_prefix}boards as b)
WHERE m.ID_BOARD=b.ID_BOARD
ORDER BY m.ID_TOPIC DESC
LIMIT 10", __FILE__, __LINE__);
while($row44=mysqli_fetch_assoc($request3)){
echo'<tr><td style="text-align:left;" ><a rel="dc:relation" class="categoriaPost '.$row44['description'].'" href="/post/'.$row44['ID_TOPIC'].'/'.$row44['description'].'/'.urls($row44['subject']).'.html" title="'.$row44['subject'].'">'.$row44['subject'].'</a></td>
<td title="'.$row44['posterName'].'"><a href="/perfil/'.$row44['posterName'].'">'.$row44['posterName'].'</a></td>
<td title="'.timeformat($row44['posterTime']).'">'.hace($row44['posterTime'],true).'</td></tr>';
}
mysqli_free_result($request3);

}
echo'</tbody></table>

<span style="display:none;font-size:0.5px;">'.$titulo.' rapidshare megaupload mediafire casitaweb calamaro actualidad 2008 2007 2009 2010 2011 2012 1999 1992 1998 msn musica peliculas descarga directa ya si vuelve polvora mojada temporal millones litros lagrimas remolino de semillas tierras floreser autos ofrender lleves mar pido 1 2 3 4 5 6 7 8 9 0 parlantes computadora descargas programas softwares www zip js php web casita web rel nofollow alive_link serenata guitarra bateria ofertas</span>';
echo template_main_below();
die();
}


function theme_quickreply_box()
{	global  $modSettings, $db_prefix,$settings, $user_info,$context;
	if (isset($settings['use_default_images']) && $settings['use_default_images'] == 'defaults' && isset($settings['default_template']))
	{
		$temp1 = $settings['theme_url'];
		$settings['theme_url'] = $settings['default_theme_url'];
		$temp2 = $settings['images_url'];
		$settings['images_url'] = $settings['default_images_url'];
		$temp3 = $settings['theme_dir'];
		$settings['theme_dir'] = $settings['default_theme_dir'];
	}
	$context['smileys'] = array(
		'postform' => array(),
		'popup' => array(),
	);
	loadLanguage('Post');
	if (empty($modSettings['smiley_enable']) && $user_info['smiley_set'] != 'none')
		$context['smileys']['postform'][] = array();
	elseif ($user_info['smiley_set'] != 'none')
	{
		if (($temp = cache_get_data('posting_smileys', 480)) == null)
		{
			$request = db_query("
				SELECT code, filename, description, smileyRow, hidden
				FROM {$db_prefix}smileys
				WHERE hidden IN (0, 2)
				ORDER BY smileyRow, smileyOrder", __FILE__, __LINE__);
			while ($row = mysqli_fetch_assoc($request))
			{
				$row['code'] = htmlspecialchars($row['code']);
				$row['filename'] = htmlspecialchars($row['filename']);
				$row['description'] = htmlspecialchars($row['description']);

				$context['smileys'][empty($row['hidden']) ? 'postform' : 'popup'][$row['smileyRow']]['smileys'][] = $row;
			}
			mysqli_free_result($request);

			cache_put_data('posting_smileys', $context['smileys'], 480);
		}
		else
			$context['smileys'] = $temp;
	}

	foreach (array_keys($context['smileys']) as $location)
	{
		foreach ($context['smileys'][$location] as $j => $row)
		{
			$n = count($context['smileys'][$location][$j]['smileys']);
			for ($i = 0; $i < $n; $i++)
			{
				$context['smileys'][$location][$j]['smileys'][$i]['code'] = addslashes($context['smileys'][$location][$j]['smileys'][$i]['code']);
				$context['smileys'][$location][$j]['smileys'][$i]['js_description'] = addslashes($context['smileys'][$location][$j]['smileys'][$i]['description']);
			}

			$context['smileys'][$location][$j]['smileys'][$n - 1]['last'] = true;
		}
		if (!empty($context['smileys'][$location]))
			$context['smileys'][$location][count($context['smileys'][$location]) - 1]['last'] = true;
	}

	template_quickreply_box();
	if (isset($settings['use_default_images']) && $settings['use_default_images'] == 'defaults' && isset($settings['default_template']))
	{
		$settings['theme_url'] = $temp1;
		$settings['images_url'] = $temp2;
		$settings['theme_dir'] = $temp3;
	}
}
?>