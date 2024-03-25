<?php require("cw-conexion-seg-0011.php");
global $context, $settings,$db_prefix, $scripturl, $txt, $user_settings,$user_info, $tranfer1;
$tipo=$_GET['tipo'];
if($tipo=='imagen'){$tip='imagen';}else{$tip='posts';}
$myser=$user_settings['ID_MEMBER'];
if($tip=='posts'){$idcc='0';}elseif($tip=='imagen'){$idcc='1';}else{$idcc='0';}
if(!$user_info['is_admin']){$shas=' AND p.ID_BOARD<>142';}else{$shas='';}
if($idcc=='0'){$topicw=$_GET['post'];}
elseif($idcc=='1'){$topicw=$_GET['kjas'];}
if($topicw){$topic='topic';}else{$topic='';}

if($topic=='topic'){
if(empty($topicw)){die('0: Debes seleccionar el favorito a agregar.-');}
if($user_info['is_guest']){die('0: Solo usuarios registrados pueden agregar favoritos.-');}else{
$limpio=(int) $topicw;
if(!$idcc){
$sffss=mysql_num_rows(db_query("
SELECT p.ID_TOPIC,p.tipo,p.ID_MEMBER
FROM ({$db_prefix}favoritos AS p)
WHERE p.ID_TOPIC='$limpio' AND p.tipo=0 AND p.ID_MEMBER='$myser'", __FILE__, __LINE__));
if($sffss){die('0: Este post ya est&aacute; en tus favoritos.-');}

$dss3ss=mysql_num_rows(db_query("
SELECT p.ID_TOPIC,p.ID_MEMBER
FROM ({$db_prefix}messages AS p)
WHERE p.ID_TOPIC='$limpio'$shas AND p.ID_MEMBER='$myser'", __FILE__, __LINE__));
if($dss3ss){die('0: No puedes agregar a favoritos tus posts.-');}}

elseif($idcc){
$sffss=mysql_num_rows(db_query("
SELECT p.ID_TOPIC,p.tipo,p.ID_MEMBER
FROM ({$db_prefix}favoritos AS p)
WHERE p.ID_TOPIC='$limpio' AND p.tipo=1 AND p.ID_MEMBER='$myser'", __FILE__, __LINE__));
if($sffss){die('0: Esta imagen ya est&aacute; en tus favoritos.-');}

$dss3ss=mysql_num_rows(db_query("
SELECT p.ID_PICTURE,p.ID_MEMBER
FROM ({$db_prefix}gallery_pic AS p)
WHERE p.ID_PICTURE='$limpio' AND p.ID_MEMBER='$myser'", __FILE__, __LINE__));
if($dss3ss){die('0: No puedes agregar a favoritos tus im&aacute;genes.-');}}

if(!$idcc){
$sadasd33=db_query("
SELECT p.ID_TOPIC,p.ID_MEMBER,b.description,p.subject
FROM ({$db_prefix}messages AS p,{$db_prefix}boards AS b)
WHERE p.ID_TOPIC='$limpio' AND b.ID_BOARD=p.ID_BOARD$shas", __FILE__, __LINE__);
while($red=mysql_fetch_array($sadasd33)){$idss=$red['ID_TOPIC'];$idMen=$red['ID_MEMBER'];$subject=$red['subject'];$description=$red['description'];}
if(empty($idss)){die('0: El post seleccionado no existe.-');}
$fecha=time();
db_query("INSERT INTO {$db_prefix}favoritos (ID_MEMBER,ID_TOPIC,tipo,fecha) VALUES ('$myser','$idss','0','$fecha')",__FILE__, __LINE__);

if($myser<>$idMen){$url='/post/'.$idss.'/'.$description.'/'.urls($subject).'.html';
db_query("INSERT INTO {$db_prefix}notificaciones (url,que,a_quien,por_quien,fecha) VALUES ('$url','6','$idMen','$myser','$fecha')",__FILE__, __LINE__);
db_query("UPDATE {$db_prefix}members SET notificacionMonitor=notificacionMonitor+1 WHERE ID_MEMBER='$idMen' LIMIT 1", __FILE__, __LINE__);}

die('1: Agregado a favoritos!');

}elseif($idcc){

$sadasd33=db_query("
SELECT p.ID_PICTURE,p.ID_MEMBER
FROM ({$db_prefix}gallery_pic AS p)
WHERE p.ID_PICTURE='$limpio'
LIMIT 1", __FILE__, __LINE__);
while($red=mysql_fetch_array($sadasd33)){$idss=$red['ID_PICTURE'];$idMen=$red['ID_MEMBER'];}
if(empty($idss)){die('0: La imagen seleccionada no existe.-');}
$fecha=time();

db_query("INSERT INTO {$db_prefix}favoritos (ID_MEMBER,ID_TOPIC,tipo,fecha) VALUES ('$myser','$idss','1','$fecha')",__FILE__, __LINE__);
if($myser<>$idMen){$url='/imagenes/ver/'.$idss;
db_query("INSERT INTO {$db_prefix}notificaciones (url,que,a_quien,por_quien,fecha) VALUES ('$url','7','$idMen','$myser','$fecha')",__FILE__, __LINE__);
db_query("UPDATE {$db_prefix}members SET notificacionMonitor=notificacionMonitor+1 WHERE ID_MEMBER='$idMen' LIMIT 1", __FILE__, __LINE__);}
die('1: Agregado a favoritos!');


}}}else{
$sa=(int)$_GET['eliminar'];
if(!empty($sa)){
if($context['user']['is_guest']){die('Error.-');}else{
db_query("DELETE FROM {$db_prefix}favoritos WHERE id='$sa' AND ID_MEMBER='$myser'", __FILE__, __LINE__);
die('1: ');
}}} ?>