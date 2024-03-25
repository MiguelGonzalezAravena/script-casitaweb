<?php require("cw-conexion-seg-0011.php");
global $tranfer1, $context,$func, $db_prefix, $user_info, $user_settings,$ID_MEMBER;
ignore_user_abort(true);
@set_time_limit(300);
if($user_info['is_guest']){die('0: Solo usuarios registrados pueden comentar.-');}else{
$ID_TOPIC=isset($_POST['id']) ? (int)$_POST['id'] : '';
if($ID_TOPIC < 1){die('0: No has seleccionado el post a comentar.-');}else{
    
$hha=db_query("
SELECT m.ID_BOARD,m.smileysEnabled,m.ID_MEMBER,m.subject,b.description
FROM ({$db_prefix}messages as m)
INNER JOIN {$db_prefix}boards as b ON m.ID_BOARD=b.ID_BOARD AND m.ID_TOPIC='{$ID_TOPIC}' AND m.eliminado=0
LIMIT 1", __FILE__, __LINE__);
while($srhh=mysql_fetch_array($hha)){
    $ID_BOARD=$srhh['ID_BOARD'];
    $locked=$srhh['smileysEnabled'];
    $lmemdsa=$srhh['ID_MEMBER'];
    $description=$srhh['description'];
    $subject=$srhh['subject'];
    }
mysql_free_result($hha);

$ignorado=mysql_num_rows(db_query("SELECT id_user FROM ({$db_prefix}pm_admitir) WHERE id_user='$lmemdsa' AND quien='$ID_MEMBER' LIMIT 1", __FILE__, __LINE__));
if($ignorado){die('0: No podes comentar este post.-');}
if($locked){die('0: Este comentario est&aacute; cerrado.-');}else{
$comentario=isset($_POST['editorCW']) ? trim($_POST['editorCW']) : '';
if(empty($comentario)){die('0: Debes escribir un comentario.-');}else{
if(strlen($comentario)>4500){die('0: El comentario es demasiado extenso, abrevi&aacute;.-');}else{
$comentario=$func['htmlspecialchars'](stripslashes($comentario), ENT_QUOTES);
$comentario=str_replace(array('"', '<', '>', '  '), array('&quot;', '&lt;', '&gt;', ' &nbsp;'), $comentario);
$comentario= preg_replace('~<br(?: /)?' . '>~i', "\n", $comentario);
$comentario=censorText($comentario);
$d218=db_query("
SELECT id_user,fecha
FROM ({$db_prefix}comentarios)
WHERE id_user='$ID_MEMBER'
ORDER BY id_coment DESC
LIMIT 1", __FILE__, __LINE__);
while($lim2=mysql_fetch_assoc($d218)){$modifiedTime=$lim2['fecha'];}
mysql_free_result($d218);
if($modifiedTime>time()-25){die('0: No es posible comentar posts con tan poca diferencia de tiempo.-');}else{
if(empty($ID_BOARD)){die('0: Este post fue eliminado o no existe.-');}else{
$fecha=time();
db_query("INSERT INTO {$db_prefix}comentarios (id_post,id_cat,id_user,comentario,fecha) VALUES ('$ID_TOPIC', '$ID_BOARD', '$ID_MEMBER','$comentario','$fecha')", __FILE__, __LINE__);

echo'1: ';
$request = db_query("SELECT c.comentario, c.id_coment,c.fecha
FROM ({$db_prefix}comentarios AS c) 
WHERE c.id_user='$ID_MEMBER'
ORDER BY c.id_coment ASC", __FILE__, __LINE__);
while ($row = mysql_fetch_assoc($request)){
$context['comentariods']=censorText(parse_bbc($row['comentario']));
$context['id_coment']=$row['id_coment'];
$context['fecha']=$row['fecha'];
}
mysql_free_result($request);

if($ID_MEMBER<>$lmemdsa){
$url='/post/'.$ID_TOPIC.'/'.$description.'/'.urls($subject).'.html#cmt_'.$context['id_coment'];
db_query("INSERT INTO {$db_prefix}notificaciones (url,que,a_quien,por_quien,fecha) VALUES ('$url','1','$lmemdsa','$ID_MEMBER','$fecha')",__FILE__, __LINE__);
db_query("UPDATE {$db_prefix}members SET notificacionMonitor=notificacionMonitor+1 WHERE ID_MEMBER='$lmemdsa' LIMIT 1", __FILE__, __LINE__);}

//comentario
echo'<div id="cmt_'.$context['id_coment'].'" class="Coment">
<div class="User-Coment size12"><div style="float:left;">';

$mesesano2 = array("1","2","3","4","5","6","7","8","9","10","11","12");
$diames2 = date('j',$context['fecha']); $mesano2 = date('n',$context['fecha']) - 1 ; $ano2 = date('Y',$context['fecha']);
$seg2=date('s',$context['fecha']); $hora2=date('H',$context['fecha']); $min2=date('i',$context['fecha']);


$sdasd=isset($_POST['psecion']) ? (int)$_POST['psecion'] : '';
echo'<b id="autor_cmnt_'.$context['id_coment'].'" user_comment="'.$user_settings['realName'].'" text_comment=\''.$comentario.'\'><a href="/perfil/'.$user_settings['realName'].'" style="color:#956100;">'.$user_settings['realName'].'</a></b> <span title="'.$diames2.'.'.$mesesano2[$mesano2].'.'.$ano2.' '.$hora2.':'.$min2.':'.$seg2.'">'.hace($context['fecha']).'</span>  dijo:</div><div style="float:right;">';

if(!$user_info['is_guest']){echo'<a href="/web/cw-TEMPenviarMP.php?user='.$coment['nomuser'].'" title="Enviar MP a '.$coment['nomuser'].'" class="boxy"><img alt="" src="'.$tranfer1.'/icons/mensaje_para.gif" border="0" /></a>';

if(!$locked){echo'&#32;<a onclick="citar_comment('.$context['id_coment'].');" href="javascript:void(0)" title="Citar Comentario"><img alt="" src="'.$tranfer1.'/comunidades/respuesta.png" class="png" border="0" /></a>';}
if($lmemdsa==$user_settings['ID_MEMBER'] || $user_info['is_admin'] || $user_info['is_mods']){echo'&#32;<a href="#" onclick="del_coment_post('.$context['id_coment'].','.$ID_TOPIC.'); return false;" title="Eliminar Comentario"><img alt="" src="'.$tranfer1.'/comunidades/eliminar.png" class="png" style="width:16px;height:16px;" border="0" /></a>';}

}
echo'</div></div> <div class="cuerpo-Coment"><div style="white-space: pre-wrap; overflow: hidden; display: block;">'.$context['comentariods'].'</div></div></div>';

exit;die();}}}}}}}?>