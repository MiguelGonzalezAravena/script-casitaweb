<?php
//Pagina de Rodrigo Zaupa (rigo@casitaweb.net)
if (!defined('CasitaWeb!-PorRigo'))die(base64_decode("d3d3LmNhc2l0YXdlYi5uZXQgLSByaWdv"));
function EditarPost(){ global $txt, $context, $scripturl, $db_prefix, $user_info, $ID_MEMBER, $return;
if($user_info['is_guest']){is_not_guest();die();}
LoadTemplate('EditarPost');
loadLanguage('Post');
$id=isset($_GET['post']) ? (int)$_GET['post'] : '';
$datos=db_query("
SELECT men.ID_BOARD,men.ID_TOPIC,men.hiddenOption,men.sticky,men.smileysEnabled,men.anuncio,men.color,men.subject,men.body,men.ID_MEMBER, men.eliminado
FROM ({$db_prefix}messages as men)
WHERE men.ID_TOPIC='{$id}'".(!empty($user_info['is_mods']) || !empty($user_info['is_admin']) ? '' : " AND men.ID_MEMBER='$ID_MEMBER'")."
LIMIT 1",__FILE__, __LINE__);
while($data44=mysql_fetch_assoc($datos)){
$data44['body']=censorText($data44['body']);
$data44['subject']=censorText($data44['subject']);
$context['id_cat']=$data44['ID_BOARD'];
$context['id_post']=$data44['ID_TOPIC'];
$context['id_user']=$data44['ID_MEMBER'];
$context['titulo']=censorText($data44['subject']);
$context['mensaje']=str_replace("<br />","\n",$data44['body']);
$context['privado']=$data44['hiddenOption'];
$context['sticky']=$data44['sticky'];
$context['locked']=$data44['smileysEnabled'];
$context['anuncio']=$data44['anuncio'];
$context['color']=$data44['color'];
$context['eliminado']=$data44['eliminado'];}
$context['id_post']=isset($context['id_post']) ? $context['id_post'] : '';
$context['eliminado']=isset($context['eliminado']) ? $context['eliminado'] : '';
if(empty($context['id_post']) || $context['eliminado']){fatal_error('No tienes permisos para editar este post.');}

$context['page_title']='Editar post';
} ?>