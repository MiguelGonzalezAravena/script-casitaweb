<?php
//Pagina de Rodrigo Zaupa (rigo@casitaweb.net)
if (!defined('CasitaWeb!-PorRigo'))die(base64_decode("d3d3LmNhc2l0YXdlYi5uZXQgLSByaWdv"));
function Comunidades(){global $context, $user_info, $user_settings, $urlSep,$txt,$db_prefix;
loadTemplate('ComunidadesAdmin');
if($user_info['is_admin'] || $user_info['is_mods']){
$_GET[$urlSep]=isset($_GET[$urlSep]) ? str_replace("/","",$_GET[$urlSep]) : '';
$context['ADMCOMtema']=isset($_GET['tema']) ? $_GET['tema'] : '';

if(empty($context['ADMCOMtema'])){adminIndex('ComunidadesAdm');}
else{adminIndex('ComunidadesAdm2');}

$context['all_pages'] = array('index' => 'intro');
		
if (!isset($_GET['m']) || !isset($context['all_pages'][$_GET['m']]))
{$_GET['m'] = 'index';}

$context['current_page']=$_GET['m'];
$context['sub_template']=$context['all_pages'][$context['current_page']];
$context['page_title'] ='Comunidades';
}else{die();}

}?>