<?php
//pagina de Rodrigo Zaupa (rigo@casitaweb.net)
if (!defined('CasitaWeb!-PorRigo'))die(base64_decode("d3d3LmNhc2l0YXdlYi5uZXQgLSByaWdv"));
function acciones(){global $db_prefix, $context, $user_settings,$user_info, $txt;

if(!$user_info['is_guest']){
$context['idgrup']=$user_settings['ID_POST_GROUP'];
$context['leecher']=$user_settings['ID_POST_GROUP'] == '4';
$context['novato']=$user_settings['ID_POST_GROUP'] == '5';
$context['buenus']=$user_settings['ID_POST_GROUP'] == '6';}

	loadTemplate('Acciones');	
	$context['all_pages'] = array(
    // 6  3  vr2965 denuncias quickreply_box
    'tyc24' => 'tyc24',
    'tyc14' => 'tyc14',
    'tyc999' => 'tyc999',
    'tyc666' => 'tyc666',
    'tyc12' => 'tyc12',
    'tyc6' => 'tyc6',
    'tyc3' => 'tyc3',
    'vr2965' => 'vr2965',
    'denuncias' => 'denuncias',
    'index' => 'intro');

if (!isset($_GET['m']) || !isset($context['all_pages'][$_GET['m']]))
		$_GET['m'] = 'index';

	$context['current_page'] = $_GET['m'];
	$context['sub_template'] = '' . $context['all_pages'][$context['current_page']];
	
if($_GET['m']=='tyc12'){
$id=(int)$_GET['id'];
$request = db_query("SELECT i.ID_PICTURE,i.title FROM ({$db_prefix}gallery_pic as i) WHERE i.ID_PICTURE='{$id}' LIMIT 1", __FILE__, __LINE__);
$row = mysql_fetch_assoc($request);
$context['page_title'] = censorText($row['title']);
}
//Nuve Tags
elseif($_GET['m']=='tyc14'){$context['page_title']='Nube de Tags';}
//Tags
elseif($_GET['m']=='tyc13' && seguridad($_GET['palabra']) ){
if(seguridad($_GET['palabra'])){$context['page_title']=seguridad($_GET['palabra']);}else{$context['page_title']='Buscador';}}
else{$context['page_title']=$txt[18];}

if(seguridad($_GET['m'])=='denuncias'){if($user_settings['ID_GROUP']=='1' || $user_settings['ID_GROUP']=='2'){adminIndex('denuncias');}else {die('');}}
if(seguridad($_GET['m'])=='vr2965'){
if (($user_info['is_admin'] || $user_info['is_mods'])){
adminIndex('vr2965');
$_GET['id']=isset($_GET['id']) ? (int) $_GET['id'] : '';
$_GET['post-agregar']=isset($_GET['post-agregar']) ? $_GET['post-agregar'] : '';
$_GET['inicio']=isset($_GET['inicio']) ? $_GET['inicio'] : '';

$context['admin_tabs'] = array(
		'title' =>'BatiCueva',
		'help' => 'comunicacion',
		'description' => 'Esto es una seccion donde los moderadores del sitio van a poder comunicarse entre ellos, atravez de posts y comentarios que son ocultos para todo p&uacute;blico.',
		'tabs' => array(),
	);
		$context['admin_tabs']['tabs'][] = array(
			'title' => 'Inicio' ,
			'description' => '',
			'href' => '/moderacion/comunicacion-mod/',
			'is_selected' => $_GET['inicio'] == '1234',
		);
		$context['admin_tabs']['tabs'][] = array(
			'title' => 'Agregar post' ,
			'description' => 'Esto es una secci&oacute;n donde los moderadores del sitio casitaweb.net van a poder comunicarse entre ellos, atravez de posts y comentarios que son ocultos para todo publico.',
			'href' => '/moderacion/comunicacion-mod/post/agregar/',
			'is_selected' => $_GET['post-agregar'] == '1447',
		);
if ($_GET['id'])
		$context['admin_tabs']['tabs'][] = array(
			'title' => 'post ID:'.$_GET['id'].'' ,
			'description' => 'Esto es una seccion donde los moderadores del sitio casitaweb.net van a poder comunicarse entre ellos, atravez de posts y comentarios que son ocultos para todo publico.',
			'href' => '/moderacion/comunicacion-mod/post/'.$_GET['id'].'',
			'is_selected' => $_GET['id'],
		);
        
}else {die('');}}
}



function theme_quickreply_box(){global $txt, $modSettings, $db_prefix;
global $context, $settings, $user_info;
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
		$context['smileys']['postform'][] = array(
		'last' => true,	);
	elseif ($user_info['smiley_set'] != 'none')
	{
		if (($temp = cache_get_data('posting_smileys', 480)) == null)
		{
			$request = db_query("
				SELECT code, filename, description, smileyRow, hidden
				FROM {$db_prefix}smileys
				WHERE hidden IN (0, 2)
				ORDER BY smileyRow, smileyOrder", __FILE__, __LINE__);
			while ($row = mysql_fetch_assoc($request))
			{
				$row['code'] = htmlspecialchars($row['code']);
				$row['filename'] = htmlspecialchars($row['filename']);
				$row['description'] = htmlspecialchars($row['description']);

				$context['smileys'][empty($row['hidden']) ? 'postform' : 'popup'][$row['smileyRow']]['smileys'][] = $row;
			}
			mysql_free_result($request);

			cache_put_data('posting_smileys', $context['smileys'], 480);
		}
		else
			$context['smileys'] = $temp;
	}

	// Clean house... add slashes to the code for javascript.
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
	$context['show_bbc'] = !empty($modSettings['enableBBC']) && !empty($settings['show_bbc']);
	if (!empty($modSettings['disabledBBC']))
	{
		$disabled_tags = explode(',', $modSettings['disabledBBC']);
		foreach ($disabled_tags as $tag)
			$context['disabled_tags'][trim($tag)] = true;
	}
	template_quickreply_box();
	if (isset($settings['use_default_images']) && $settings['use_default_images'] == 'defaults' && isset($settings['default_template']))
	{
		$settings['theme_url'] = $temp1;
		$settings['images_url'] = $temp2;
		$settings['theme_dir'] = $temp3;
	}}?>