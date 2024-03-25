<?php
if (defined('CasitaWeb!-PorRigo'))return true; define('CasitaWeb!-PorRigo', 'SSI');
if($_SERVER["REMOTE_ADDR"]=='190.51.177.88'){@ini_set('display_errors',true);}
$ssi_magic_quotes_runtime = @get_magic_quotes_runtime();
@set_magic_quotes_runtime(0);
$time_start = microtime();

require(dirname(__FILE__) . '/config-seg-cw1983.php');
require($sourcedir . '/QueryString.php');
require($sourcedir . '/Subs.php');
require($sourcedir . '/Errors.php');
require($sourcedir . '/Load.php');
require($sourcedir . '/Security.php');
require($sourcedir.'/Funciones.php');

reloadSettings();
cleanRequest();

if (isset($_REQUEST['GLOBALS']) || isset($_COOKIE['GLOBALS']))
	die('#474564');
elseif (isset($_REQUEST['ssi_theme']) && (int) $_REQUEST['ssi_theme'] == (int) $ssi_theme)
	die('#474564');
elseif (isset($_COOKIE['ssi_theme']) && (int) $_COOKIE['ssi_theme'] == (int) $ssi_theme)
	die('#474564');
elseif (isset($_REQUEST['ssi_layers'], $ssi_layers) && (@get_magic_quotes_gpc() ? stripslashes($_REQUEST['ssi_layers']) : $_REQUEST['ssi_layers']) == $ssi_layers)
	die('#474564');
if (isset($_REQUEST['context']))
	die('#474564');

define('WIRELESS', false);

if (isset($ssi_gzip) && $ssi_gzip === true && @ini_get('zlib.output_compression') != '1' && @ini_get('output_handler') != 'ob_gzhandler' && @version_compare(PHP_VERSION, '4.2.0') != -1)ob_start('ob_gzhandler');
else {$modSettings['enableCompressedOutput'] = '1';}

ob_start('ob_sessrewrite');

if (!headers_sent()){loadSession();}
else
{
	if (isset($_COOKIE[session_name()]) || isset($_REQUEST[session_name()]))
	{
		$temp = error_reporting(error_reporting() & !E_WARNING);
		loadSession();
		error_reporting($temp);
	}

if (!isset($_SESSION['rand_code']))$_SESSION['rand_code'] = '';$sc = &$_SESSION['rand_code'];}


unset($board);

loadUserSettings();
loadPermissions();
writeLog();

if (isset($ssi_layers)){$context['template_layers'] = $ssi_layers; template_header();}
else{setupThemeContext();}

is_not_banned();

error_reporting($ssi_error_reporting);
@set_magic_quotes_runtime($ssi_magic_quotes_runtime);

return true; ?>