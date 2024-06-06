<?php require("cw-conexion-seg-0011.php"); global $db_prefix,$user_info,$context,$sourcedir, $tranfer1,$user_settings;
if($user_info['is_guest']){die('0: Faltan datos.');}

ignore_user_abort(true);
@set_time_limit(300);

$id=(int)$_POST['id'];
if(empty($id)){die('0: Falto el tema.');}

$rs=db_query("SELECT a.nocoment,a.id_user,a.id_com
FROM ({$db_prefix}comunidades_articulos AS a, {$db_prefix}comunidades AS c)
WHERE a.id='$id' AND a.id_com=c.id AND c.bloquear=0 AND a.eliminado=0
LIMIT 1",__FILE__, __LINE__);
while ($row=mysqli_fetch_assoc($rs)){$iid_user=$row['id_user'];$id_com=$row['id_com']; $nocoment=$row['nocoment'];}
$iid_user=isset($iid_user) ? $iid_user : '';

if(empty($iid_user)){die('0: Falto el tema.');}
if($nocoment){die('0: Tema cerrado, no se permiten respuestas.');}

include($sourcedir.'/FuncionesCom.php');
baneadoo($id_com);
acces($id_com);
permisios($id_com);

if(eaprobacion($id_com)){die('0: Esperando aprobaci&oacute;n.');}

if($context['puedo']=='1' || $context['puedo']=='2' || $context['puedo']=='3'){
    
timeforComent();

$cuerpo=seguridad($_POST['comentario']);
if(!$cuerpo){die('0: Debes agregar un comentario.');}
if(strlen($cuerpo)>4500){die('0: El comentario es demasiado extenso, abrevi&aacute;.');}

db_query("INSERT INTO {$db_prefix}comunidades_comentarios (id_tema, id_user, fecha, comentario, id_com, leido) 
    VALUES ('$id', '{$user_settings['ID_MEMBER']}',".time().", '$cuerpo', '$id_com', '0')", __FILE__, __LINE__);
      
$idseCe = db_insert_id();

db_query("
			UPDATE {$db_prefix}comunidades_articulos
			SET respuestas=respuestas+1
			WHERE id='$id'
			LIMIT 1", __FILE__, __LINE__);
            
notificacionAGREGAR($iid_user,'12');

echo'1: ';
$comene=parse_bbc(nohtml(nohtml2($cuerpo)));
$comene2=nohtml(nohtml2($cuerpo));


echo'<div class="User-Coment">

<div style="float:left;"><span class="size11"><b id="autor_cmnt_'.$idseCe.'" user_comment="'.$user_settings['realName'].'" text_comment=\''.$comene2.'\'><a href="/perfil/'.$user_settings['realName'].'" title="'.$user_settings['realName'].'" style="color:#956100;">'.$user_settings['realName'].'</a></b> | '.hace(time()).' dijo:</span></div>

<div style="float:right;">';
if(!$user_info['is_guest']){echo'<span onclick="Boxy.load(\'/web/cw-TEMPenviarMP.php?user='.$user_settings['realName'].'\', {title: \'Enviar MP a '.$user_settings['realName'].'\'})" title="Enviar MP a '.$user_settings['realName'].'" class="pointer"><img src="'.$tranfer1.'/icons/mensaje_para.gif" alt="" /></span>';}

if(!$nocoment && ($context['puedo']=='1' || $context['puedo']=='2' || $context['puedo']=='3')){
echo' <span onclick="citar_comment('.$idseCe.')" title="Citar Comentario" class="pointer"><img src="'.$tranfer1.'/comunidades/respuesta.png" class="png" alt="" /></span>';}

if($context['permisoCom']=='1' || $context['permisoCom']=='3' || $context['permisoCom']=='2' || $iid_user==$user_settings['ID_MEMBER']){
echo' <a href="/web/cw-comunidadesEliCom.php?id='.$idseCe.'" title="Eliminar Comentario" onclick="if (!confirm(\'\xbfEstas seguro que desea eliminar este comentario?\')) return false;"><img src="'.$tranfer1.'/comunidades/eliminar.png" class="png" alt="" /></a>';}


echo'</div><div style="clear:both"></div></div>

<div class="post-comentCont">'.$comene.'<div class="clearBoth"></div></div>';
$_SESSION['ultima_accionTIME']=time();
die();}else{die('0: No tenes permiso para comentar.'); }
?>