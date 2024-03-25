<?php  
define('CasitaWeb!-PorRigo',1);
if($_SERVER["REMOTE_ADDR"]=='190.51.178.172'){@ini_set('display_errors',true);}
error_reporting(E_ALL);
@set_magic_quotes_runtime(0);

$time_start=microtime();
require(dirname(__FILE__).'/config-seg-cw1983.php');
require($sourcedir.'/QueryString.php');
require($sourcedir.'/Subs.php');
require($sourcedir.'/Errors.php');
require($sourcedir.'/Load.php');
require($sourcedir.'/Security.php');
require($sourcedir.'/Funciones.php');

$context=array();
$context=array(); 

reloadSettings();
cleanRequest();
loadSession();
cw_header();

if(!defined('WIRELESS'))define('WIRELESS', isset($_REQUEST['wap']));

if (WIRELESS){define('WIRELESS_PROTOCOL',isset($_REQUEST['wap']));}

call_user_func(smf_main());

obExit(null, null, true);

function smf_main(){global $modSettings,  $user_info, $sourcedir, $urlSep;


    loadUserSettings();
	if (empty($_GET[$urlSep]))
	{
		writeLog();
		if (!empty($modSettings['hitStats']))
			trackStats(array('hits' => '+'));
	}
	loadTheme();
    is_not_banned();
	loadPermissions();

  if (empty($modSettings['allow_guestAccess']) && $user_info['is_guest'] && (!isset($_GET[$urlSep]) || !in_array($_GET[$urlSep], array('register', 'register2', 'reminder', 'activate', 'smstats'))))
	{
		require($sourcedir . 'Subs-Auth.php');
		return 'KickGuest';
	}
	elseif (empty($_GET[$urlSep]))
	{
$post=isset($_GET['post']) ? (int)$_GET['post'] : ''; 
if(empty($post)){
			require($sourcedir.'/Recent.php');
			return 'RecentPosts';
		}
		else
		{
			require($sourcedir.'/Posts.php');
			return 'Posts';
		}
	}
	$actionArray = array(
		'activate' => array('Register.php', 'Activate'),
		'admin' => array('Admin.php', 'Admin'),
		'ban' => array('ManageBans.php', 'Ban'),
        'favoritos' => array('Favoritos.php', 'Favoritos'),
		'bgoogle' => array('Google.php', 'Google'),
		'coppa' => array('Register.php', 'CoppaForm'),
		'imagenes' => array('Gallery.php', 'GalleryMain'),
        'com' => array('Comunidades.php', 'Comunidades'),
        'comA' => array('ComunidadesAdmin.php', 'Comunidades'),
		'deletemsg' => array('RemoveTopic.php', 'DeleteMessage'),
		'nuevoPost' => array('NuevoPost.php', 'NuevoPost'),
		'editarPost' => array('EditarPost.php', 'EditarPost'),
		'monitorUser' => array('MonitorUser.php', 'MonitorUser'),
		'posts' => array('Posts.php', 'Posts'),
		'featuresettings' => array('ModSettings.php', 'ModifyFeatureSettings'),
		'featuresettings2' => array('ModSettings.php', 'ModifyFeatureSettings2'),
		'manageboards' => array('ManageBoards.php', 'ManageBoards'),
		'managesearch' => array('ManageSearch.php', 'ManageSearch'),
		'membergroups' => array('ManageMembergroups.php', 'ModifyMembergroups'),
		'modifycat' => array('ManageBoards.php', 'ModifyCat'),
		'hist-mod' => array('Modlog.php', 'ViewModlog'),
		'permissions' => array('ManagePermissions.php', 'ModifyPermissions'),
		'mp' => array('PersonalMessage.php', 'MessageMain'),
		'agregar' => array('Agregar.php', 'Agregar'),
		'agregar2' => array('Agregar.php', 'Agregar2'),
		'postsettings' => array('ManagePosts.php', 'ManagePostSettings'),
		'printpage' => array('Printpage.php', 'PrintTopic'),
		'profile' => array('Profile.php', 'ModifyProfile'),
		'index' => array('Recent.php', 'RecentPosts'),
		'regcenter' => array('ManageRegistration.php', 'RegCenter'),
		'registrarse' => array('Register.php', 'Register'),
		'register2' => array('Register.php', 'Register2'),
		'reminder' => array('Reminder.php', 'RemindMe'),
		'requestmembers' => array('Subs-Auth.php', 'RequestMembers'),
		'search' => array('Search.php', 'PlushSearch1'),
		'mapadelsitio' => array('Sitemap.php', 'ShowSiteMap'),
		'smileys' => array('ManageSmileys.php', 'ManageSmileys'),
		'TOPs' => array('Stats.php', 'DisplayStats'),
		'rz' => array('Acciones.php', 'Acciones'),
		'rz-seg55555658971' => array('ac-seg-01114.php', 'acseg'),
		'rz-seg011' => array('ac-seg-0125.php', 'acsegsd'),
		'viewmembers' => array('ManageMembers.php', 'ViewMembers'),
		'xdas54d48as7d77' => array('Who.php', 'Who'),
	);
	if (!isset($_GET[$urlSep]) || !isset($actionArray[$_GET[$urlSep]]))
	{
		require($sourcedir . '/Recent.php');
		return 'RecentPosts';}
	require($sourcedir . '/' . $actionArray[$_GET[$urlSep]][0]);
	return $actionArray[$_GET[$urlSep]][1];}
            
?>