<?php
//Pagina de Rodrigo Zaupa (rigo@casitaweb.net)
if (!defined('CasitaWeb!-PorRigo'))die(base64_decode("d3d3LmNhc2l0YXdlYi5uZXQgLSByaWdv"));
function MessageMain(){
global $tranfer1, $txt, $scripturl, $sourcedir, $context, $user_info, $user_settings, $db_prefix, $ID_MEMBER;
	is_not_guest();
	isAllowedTo('pm_read');
	loadTemplate('PersonalMessage');
	loadLanguage('PersonalMessage');
    
$context['all_pages'] = array('index' => 'intro');
if (!isset($_GET['m']) || !isset($context['all_pages'][$_GET['m']]))
$_GET['m'] = 'index';
$context['current_page'] = $_GET['m'];
$context['sub_template'] = $context['all_pages'][$context['current_page']];

$accion=isset($_GET['sas']) ? $_GET['sas'] : '';
if($accion=='redactar'){$context['page_title']='Enviar mensaje';}
elseif($accion=='enviar'){$context['page_title']=$txt[18];}
elseif($accion=='enviados'){$context['page_title']='Mensajes enviados';}
elseif($accion=='leer'){$context['page_title']=$txt[18];}
elseif($accion=='leere'){$context['page_title']=$txt[18];}
elseif($accion=='eliminar'){$context['page_title']=$txt[18];}
elseif($accion=='recibidos'){$context['page_title']='Mensajes recibidos';}
else{$context['page_title']=$txt[18];}}

function deleteMessages($id){
if($context['user']['is_admin']){
if(!empty($id)){
db_query("DELETE FROM {$db_prefix}mensaje_personal WHERE id_de='$id'", __FILE__, __LINE__);
db_query("DELETE FROM {$db_prefix}mensaje_personal WHERE id_para='$id'", __FILE__, __LINE__);
}}

return true;}
?>