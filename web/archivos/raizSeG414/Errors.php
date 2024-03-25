<?php
//Pagina de Rodrigo Zaupa (rigo@casitaweb.net)
if (!defined('CasitaWeb!-PorRigo'))die(base64_decode("d3d3LmNhc2l0YXdlYi5uZXQgLSByaWdv"));
function log_error(){}
function db_error($db_string, $file, $line)
{
	global $txt, $context, $sourcedir, $webmaster_email, $modSettings;
	global $forum_version, $db_connection, $db_last_error, $db_persist;
	global $db_server, $db_user, $db_passwd, $db_name, $db_show_debug;

	$query_error = mysql_error($db_connection);
	$query_errno = mysql_errno($db_connection);

	if (!isset($modSettings['autoFixDatabase']) || $modSettings['autoFixDatabase'] == '1')
	{
		$old_cache = @$modSettings['cache_enable'];
		$modSettings['cache_enable'] = '1';

		if (($temp = cache_get_data('db_last_error', 600)) !== null)
			$db_last_error = max(@$db_last_error, $temp);

		if (@$db_last_error < time() - 3600 * 24 * 3)
		{
			if ($query_errno == 1030 && strpos($query_error, ' 127 ') !== false)
			{
				preg_match_all('~(?:[\n\r]|^)[^\']+?(?:FROM|JOIN|UPDATE|TABLE) ((?:[^\n\r(]+?(?:, )?)*)~s', $db_string, $matches);

				$fix_tables = array();
				foreach ($matches[1] as $tables)
				{
					$tables = array_unique(explode(',', $tables));
					foreach ($tables as $table)
					{
						// Now, it's still theoretically possible this could be an injection.  So backtick it!
						if (trim($table) != '')
							$fix_tables[] = '`' . strtr(trim($table), array('`' => '')) . '`';
					}
				}

				$fix_tables = array_unique($fix_tables);
			}
			// Table crashed.  Let's try to fix it.
			elseif ($query_errno == 1016)
			{
				if (preg_match('~\'([^\.\']+)~', $query_error, $match) != 0)
					$fix_tables = array('`' . $match[1] . '`');
			}
			// Indexes crashed.  Should be easy to fix!
			elseif ($query_errno == 1034 || $query_errno == 1035)
			{
				preg_match('~\'([^\']+?)\'~', $query_error, $match);
				$fix_tables = array('`' . $match[1] . '`');
			}
		}
		if (!empty($fix_tables))
		{
			require_once($sourcedir . '/Admin.php');
			require_once($sourcedir . '/Subs-Post.php');

			cache_put_data('db_last_error', time(), 600);
			if (($temp = cache_get_data('db_last_error', 600)) === null)
				updateSettingsFile(array('db_last_error' => time()));
			foreach ($fix_tables as $table)
				db_query("
					REPAIR TABLE $table", false, false);
			$modSettings['cache_enable'] = $old_cache;
			$ret = db_query($db_string, false, false);
			if ($ret !== false)
				return $ret;
		}
		else
			$modSettings['cache_enable'] = $old_cache;
		if (in_array($query_errno, array(1205, 1213, 2006, 2013)))
		{
			if (in_array($query_errno, array(2006, 2013)))
			{
				if (empty($db_persist))
					$db_connection = @mysql_connect($db_server, $db_user, $db_passwd);
				else
					$db_connection = @mysql_pconnect($db_server, $db_user, $db_passwd);

				if (!$db_connection || !@mysql_select_db($db_name, $db_connection))
					$db_connection = false;
			}

			if ($db_connection)
			{
				// Try a deadlock more than once more.
				for ($n = 0; $n < 4; $n++)
				{
					$ret = db_query($db_string, false, false);

					$new_errno = mysql_errno($db_connection);
					if ($ret !== false || in_array($new_errno, array(1205, 1213)))
						break;
				}

				// If it failed again, shucks to be you... we're not trying it over and over.
				if ($ret !== false)
					return $ret;
			}
		}
		// Are they out of space, perhaps?
		elseif ($query_errno == 1030 && (strpos($query_error, ' -1 ') !== false || strpos($query_error, ' 28 ') !== false || strpos($query_error, ' 12 ') !== false))
		{
			if (!isset($txt))
				$query_error .= ' - Chekear espacio DB';
			else
			{
				if (!isset($txt['mysql_error_space']))
					loadLanguage('Errors');

				$query_error .= !isset($txt['mysql_error_space']) ? ' - Chekear espacio DB.' : $txt['mysql_error_space'];
			}
		}
	}

	// Nothing's defined yet... just die with it.
	if (empty($context) || empty($txt))
		die($query_error);

	$context['error_title'] = $txt[18];
	if (allowedTo('admin_forum'))
		$context['error_message'] = nl2br($query_error) . '<br />' . $txt[1003] . ': ' . $file . '<br />' . $txt[1004] . ': ' . $line;
	else
		$context['error_message'] = 'Hubo un error, Intentar nuevamente.';

	if (allowedTo('admin_forum') && isset($db_show_debug) && $db_show_debug === true)
	{
		$context['error_message'] .= '<br /><br />' . nl2br($db_string);
	}

	fatal_error($context['error_message'], false);}

function fatal_error($error, $log =false,$title=null,$botn=null){
global $txt, $context,$db_prefix, $modSettings,$tranfer1;

if(empty($title)){$title='Atenci&oacute;n!';}else{$title=$title;}
$context['page_title']=$title;
$context['error-web-page']=isset($context['error-web-page']) ? '1' : '0';
if($context['error-web-page']){cw_header(); loadTheme(0);}
echo template_main_above();
	
if(empty($botn) || $botn=='1'){$context['boton']='<input class="login" style="font-size: 11px;" type="submit" title="Ir a la P&aacute;gina principal" value="Ir a la P&aacute;gina principal" onclick="location.href=\'http://casitaweb.net/\'" />';}

else{
if($botn=='909'){$context['boton']='';}
if($botn=='900'){$context['boton']='<input class="login" style="font-size: 11px;" type="submit" title="Ir a la comunidad" value="Ir a la comunidad" onclick="location.href=\'/comunidades/'.nohtml($_GET['id']).'\'" /> <input class="login" style="font-size: 11px;" type="submit" title="Ir al centro de comunidades" value="Ir al centro de comunidades" onclick="location.href=\'/comunidades/\'" />';}
if($botn=='901'){$context['boton']='<input class="login" style="font-size: 11px;" type="submit" title="Ir a la P&aacute;gina principal" value="Ir a la P&aacute;gina principal" onclick="location.href=\'http://casitaweb.net/\'" /> <input class="login" style="font-size: 11px;" type="submit" title="Ir a centro de bans" value="Ir a centro de bans" onclick="location.href=\'/moderacion/edit-user/ban/lista/\'" />';}
if($botn=='2'){
$topic=(int)$_GET['topic'];
$datosmem2=db_query("
SELECT b.description,p.subject
FROM ({$db_prefix}messages as p,{$db_prefix}boards as b)
WHERE p.ID_TOPIC='$topic' AND b.ID_BOARD=p.ID_BOARD
LIMIT 1",__FILE__, __LINE__);
while($data3=mysql_fetch_assoc($datosmem2)){$cat=$data3['description'];$title=$data3['subject'];}
$context['boton']='<input class="login" style="font-size: 11px;" type="submit" title="Volver al post" value="Volver al post" onclick="location.href=\'/post/'.$_GET['topic'].'/'.$cat.'/'.urls(censorText($title)).'.html\'" /> <input class="login" style="font-size: 11px;" type="submit" title="Ir a la P&aacute;gina principal" value="Ir a la P&aacute;gina principal" onclick="location.href=\'/\'" />';}


//post editado o creado
if($botn=='3'){
$context['boton']='<input class="login" style="font-size: 11px;" type="submit" title="Ir al post" value="Ir al post" onclick="location.href=\'/post/'.$_REQUEST['id_top'].'/'.urls(censorText($_REQUEST['description'])).'/'.urls(censorText($_REQUEST['subject'])).'.html\'" /> <input class="login" style="font-size: 11px;" type="submit" title="Ir a la P&aacute;gina principal" value="Ir a la P&aacute;gina principal" onclick="location.href=\'/\'" />';}

if($botn=='4'){$context['boton']='<input class="login" style="font-size: 11px;" type="button" title="Volver atras" value="Volver atras" onclick="history.back()" /> <input class="login" style="font-size: 11px;" type="submit" title="Ir a la P&aacute;gina principal" value="Ir a la P&aacute;gina principal" onclick="location.href=\'/\'" />';}

if($botn=='5'){
$idpost=(int)$_GET['topic'];
$request=db_query("SELECT p.subject,p.ID_TOPIC,c.description
FROM ({$db_prefix}messages as p, {$db_prefix}boards as c)
WHERE p.ID_TOPIC='{$idpost}' AND p.ID_BOARD=c.ID_BOARD", __FILE__, __LINE__);
while ($row=mysql_fetch_assoc($request))
{$titulo=$row['subject'];
$ID_TOPIC=$row['ID_TOPIC'];
$description=$row['description'];}
mysql_free_result($request);

$context['boton']='<input class="login" style="font-size: 11px;" type="submit" title="Volver al post" value="Volver al post" onclick="location.href=\'/post/'.$ID_TOPIC.'/'.urls(censorText($description)).'/'.urls(censorText($titulo)).'.html\'" /> <input class="login" style="font-size: 11px;" type="submit" title="Ir a favoritos" value="Ir a favoritos" onclick="location.href=\'/favoritos/post/\'" /> <input class="login" style="font-size: 11px;" type="submit" title="Ir a la P&aacute;gina principal" value="Ir a la P&aacute;gina principal" onclick="location.href=\'/\'" />';}

if($botn=='6'){
$idpost=(int) $_GET['kjas'];
$context['boton']='<input class="login" style="font-size: 11px;" type="submit" title="Volver a la imagen" value="Volver a la imagen" onclick="location.href=\'/imagenes/ver/'.$idpost.'\'" /> <input class="login" style="font-size: 11px;" type="submit" title="Ir a favoritos" value="Ir a favoritos" onclick="location.href=\'/favoritos/imagen/\'" /> <input class="login" style="font-size: 11px;" type="submit" title="Ir a la P&aacute;gina principal" value="Ir a la P&aacute;gina principal" onclick="location.href=\'/\'" />';}

if($botn=='7'){
$id=(int)$_GET['id'];
$context['boton']='<input class="login" style="font-size: 11px;" type="submit" title="Volver a la imagen" value="Volver a la imagen" onclick="location.href=\'/imagenes/ver/'.$id.'/\'" /> <input class="login" style="font-size: 11px;" type="submit" title="Ir a la P&aacute;gina principal" value="Ir a la P&aacute;gina principal" onclick="location.href=\'/\'" />';}
if($botn=='9'){
$context['boton']='<input class="login" style="font-size: 11px;" type="submit" title="Volver atras" value="Volver atras" onclick="location.href=\'/ingresar/\'" /> <input class="login" style="font-size: 11px;" type="submit" title="Ir a la P&aacute;gina principal" value="Ir a la P&aacute;gina principal" onclick="location.href=\'/\'" />';}
}

if($context['boton']){
echo'<div align="center"><div class="box_errors"><div class="box_title" style="width: 388px"><div class="box_txt box_error" align="left">'.$title.'</div><div class="box_rss"><img alt="" src="'.$tranfer1.'/blank.gif" style="width:14px;height:12px;" border="0" /></div></div><div class="windowbg" style="width:380px;padding:4px;"><br />'.$error.'<br /><br />'.$context['boton'].'<br /><br /></div></div>

<br />
</div>
<div align="center"><p align="center" style="padding:0px;margin:0px;"><br />'; anuncio_728x90(); echo'</p></div>

';

}else{echo'<div align="center" class="noesta" style="width:922px;">'.$error.'</div>';
}


echo template_main_below();
die();}

function fatal_lang_error($error, $log = true, $sprintf = array()){global $txt;
loadLanguage('Errors'); if (empty($sprintf))fatal_error($txt[$error], $log);else fatal_error(vsprintf($txt[$error], $sprintf), $log);}


function error_handler($error_level, $error_string, $file, $line){global $settings, $modSettings, $db_show_debug;
if(error_reporting() == 0 || (defined('E_STRICT') && $error_level == E_STRICT && (empty($modSettings['enableErrorLogging']) || $modSettings['enableErrorLogging'] != 2)))return;
if(strpos($file, 'eval()') !== false && !empty($settings['current_include_filename'])){
if(function_exists('debug_backtrace')){
$array = debug_backtrace();
for ($i = 0; $i < count($array); $i++){
if ($array[$i]['function'] != 'loadSubTemplate')continue;
if (empty($array[$i]['args']))$i++;break;}
if (isset($array[$i]) && !empty($array[$i]['args']))
$file = realpath($settings['current_include_filename']) . ' - ' . $array[$i]['args'][0] . '';
else
$file = realpath($settings['current_include_filename']) . '';}
else
$file = realpath($settings['current_include_filename']) . '';}
if(isset($db_show_debug) && $db_show_debug === true){
if($error_level % 255 != E_ERROR){
$temporary = ob_get_contents();
if (substr($temporary, -2) == '="')echo '"';}
echo '<br /><b>', $error_level % 255 == E_ERROR ? 'Error' : ($error_level % 255 == E_WARNING ? '' : 'Notice'), '</b>: ', $error_string, ' archivo <b>', $file, '</b> en la linea <b>', $line, '</b><br />';}
$message = log_error($error_level . ': ' . $error_string, $file, $line);
if ($file == 'Unknown')return;
if ($error_level % 255 == E_ERROR)obExit(false);else return;
if ($error_level % 255 == E_ERROR || $error_level % 255 == E_WARNING)
fatal_error(allowedTo('admin_forum') ? $message : $error_string, false);


if ($error_level % 255 == E_ERROR)die(base64_decode("d3d3LmNhc2l0YXdlYi5uZXQgLSByaWdv"));}

function db_fatal_error($loadavg = false){die('Error.-');}

?>