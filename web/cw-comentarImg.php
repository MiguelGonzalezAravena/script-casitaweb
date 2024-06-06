<?php require("cw-conexion-seg-0011.php");
global $context, $db_prefix, $ID_MEMBER, $txt,$user_info,$func, $user_settings, $modSettings;
if(empty($user_settings['ID_MEMBER'])){die('0: Debes estar registrado y conectado para poder comentar.-');}

$limit=db_query("SELECT m.date
FROM ({$db_prefix}gallery_comment AS m)
WHERE '{$user_settings['ID_MEMBER']}'=m.ID_MEMBER
ORDER BY m.ID_COMMENT DESC
LIMIT 1", __FILE__, __LINE__);
while($lim0=mysqli_fetch_assoc($limit)){$posterTime=$lim0['date'];}
if($posterTime>time()-25){die('0: No es posible comentar imagen con tan poca diferencia de tiempo.-');}

$id=isset($_POST['id']) ? (int)$_POST['id'] : '';
if($id < 1){die('0: '.$txt['gallery_error_no_pic_selected']);}

$datos=db_query("SELECT m.ID_PICTURE,m.ID_MEMBER
FROM ({$db_prefix}gallery_pic AS m)
WHERE m.ID_PICTURE='$id'
ORDER BY m.ID_PICTURE DESC
LIMIT 1", __FILE__, __LINE__);
while($row=mysqli_fetch_assoc($datos)){$id_pic=$row['ID_PICTURE'];$lmemdsa=$row['ID_MEMBER'];}mysqli_free_result($datos);

if(empty($id_pic)){die('0: La im&aacute;gen seleccionada no existe.-');}
$ignorado=mysqli_num_rows(db_query("SELECT id_user FROM ({$db_prefix}pm_admitir) WHERE id_user='$lmemdsa' AND quien='{$user_settings['ID_MEMBER']}' LIMIT 1", __FILE__, __LINE__));
if($ignorado){die('0: No podes comentar esta imagen.-');}
$comentario=isset($_POST['editorCW']) ? trim($_POST['editorCW']) : '';
if(strlen($comentario)>4500){die('0: El comentario es demasiado extenso, abrevi&aacute;.-');}
if(empty($comentario)){die('0: '.$txt['gallery_error_no_comment']);}

$commentdate=time();
$comment=$func['htmlspecialchars'](stripslashes($comentario), ENT_QUOTES);
$comment=str_replace(array('"', '<', '>', '  '), array('&quot;', '&lt;', '&gt;', ' &nbsp;'), $comment);
$comment= preg_replace("~\[hide\](.+?)\[\/hide\]~i", "&nbsp;",$comment);
$comment= preg_replace(array('~\n?\[hide.*?\].+?\[/hide\]\n?~is', '~^\n~', '~\[/hide\]~'), "&nbsp;", $comment);
$comment= preg_replace('~<br(?: /)?' . '>~i', "\n", $comment);
$comment=censorText($comment);

db_query("INSERT INTO {$db_prefix}gallery_comment (ID_MEMBER, comment, date, ID_PICTURE) VALUES ($ID_MEMBER,'$comment',' $commentdate','$id_pic')", __FILE__, __LINE__);


//comentario
echo'1: ';
$request = db_query("SELECT c.comment, c.date,c.ID_COMMENT
FROM ({$db_prefix}gallery_comment AS c) 
WHERE c.ID_MEMBER='{$user_settings['ID_MEMBER']}'
ORDER BY c.ID_COMMENT ASC", __FILE__, __LINE__);
while ($row = mysqli_fetch_assoc($request)){
$context['comentariods']=censorText(parse_bbc($row['comment']));
$context['id_coment']=$row['ID_COMMENT'];
$context['fecha']=$row['date'];}
mysqli_free_result($request);

if($ID_MEMBER<>$lmemdsa){
$url='/imagenes/ver/'.$id_pic.'#cmt_'.$context['id_coment'];
db_query("INSERT INTO {$db_prefix}notificaciones (url,que,a_quien,por_quien,fecha,extra) VALUES ('$url','2','$lmemdsa','$ID_MEMBER','$commentdate','$id_pic')",__FILE__, __LINE__);
db_query("UPDATE {$db_prefix}members SET notificacionMonitor=notificacionMonitor+1 WHERE ID_MEMBER='$lmemdsa' LIMIT 1", __FILE__, __LINE__);}

echo'<div id="cmt_'.$context['id_coment'].'" class="Coment">
<div class="User-Coment size12">
<div style="float:left;">';

$mesesano2 = array("1","2","3","4","5","6","7","8","9","10","11","12");
$diames2 = date('j',$context['fecha']); $mesano2 = date('n',$context['fecha']) - 1 ; $ano2 = date('Y',$context['fecha']);
$seg2=date('s',$context['fecha']); $hora2=date('H',$context['fecha']); $min2=date('i',$context['fecha']);
$sdasd=isset($_POST['psecion']) ? (int)$_POST['psecion'] : '';

echo'<b id="autor_cmnt_'.$context['id_coment'].'" user_comment="'.$user_settings['realName'].'" text_comment=\''.$comentario.'\'><a href="/perfil/'.$user_settings['realName'].'" style="color:#956100;">'.$user_settings['realName'].'</a></b> <span title="'.$diames2.'.'.$mesesano2[$mesano2].'.'.$ano2.' '.$hora2.':'.$min2.':'.$seg2.'">'.hace($context['fecha']).'</span> dijo:</div><div style="float:right;">';

echo'<a href="/web/cw-TEMPenviarMP.php?user='.$coment['nomuser'].'" title="Enviar MP a '.$coment['nomuser'].'" class="boxy"><img alt="" src="'.$tranfer1.'/icons/mensaje_para.gif" border="0" /></a>';
echo'&#32;<a onclick="citar_comment('.$context['id_coment'].');" href="javascript:void(0)" title="Citar Comentario"><img alt="" src="'.$tranfer1.'/comunidades/respuesta.png" border="0"  class="png" /></a>';
if($lmemdsa==$user_settings['ID_MEMBER'] || $user_info['is_admin'] || $user_info['is_mods']){echo'&#32;<a href="#" onclick="del_coment_img('.$context['id_coment'].','.$id.'); return false;" title="Eliminar Comentario"><img alt="" src="'.$tranfer1.'/comunidades/eliminar.png" class="png" style="width:16px;height:16px;" border="0" /></a>';}

echo'</div></div> <div class="cuerpo-Coment"><div style="white-space: pre-wrap; overflow: hidden; display: block;">'.$context['comentariods'].'</div></div></div>'; 
exit;die(); ?>