<?php require("cw-conexion-seg-0011.php"); global $context, $db_prefix,$no_avatar, $user_info, $user_settings;

if($user_info['is_guest']){die('0: Usuarios no logueados no pueden hacer esta acci&oacute;n.');}

$_POST['muro']=trim($_POST['muro']);
$_POST['quehago']=trim($_POST['quehago']);

if($_POST['muro'] || $_POST['quehago']){
$idmem=(int)$_POST['user'];

if($_POST['muro']){
if(empty($idmem)){die('0: Debes escribirle a alguien.-');}

$muro=trim(nohtml($_POST['muro']));
if(empty($muro) || $muro=='Escribe algo...' || $muro=='escribe algo...' || $muro=='Escribe algo' || $muro=='escribe algo'){die('0: Debes escribir algo en el muro.-');}
else{
    
if(strlen($muro)>10000){die('0: No se aceptan escritos tan grandes.-');}else {
$yo=$user_settings['ID_MEMBER'];
if(empty($yo)){die('0: Usuarios no logueados no pueden escribir en el muro de nadie.-');}else{
    
$ignorado=mysqli_num_rows(db_query("SELECT id_user FROM ({$db_prefix}pm_admitir) WHERE id_user='$idmem' AND quien='$yo' LIMIT 1", __FILE__, __LINE__));
if($ignorado){die('0: No podes comentar este muro.-');}

timeforComent();

$fecha=time();
db_query("INSERT INTO {$db_prefix}muro (id_user,de,tipo,fecha,muro) VALUES ('$idmem','$yo','0','$fecha','$muro')",__FILE__, __LINE__);
$ivvd = db_insert_id();


//MOSTRARRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRR
$dsddd=(int)$_POST['datapagss'];
if($dsddd <> '1'){echo'1: <div class="noesta-am">Comentario agregado correctamente!!!</div>';}else{



$mensaje=censorText($muro);
$yata=hace($fecha);
$mensaje=nohtml2(moticon($mensaje,true));
$filtrado=str_replace("\n","<br />",$mensaje);


$datosmem = db_query("SELECT ID_MEMBER,realName FROM ({$db_prefix}members) WHERE ID_MEMBER='$idmem' LIMIT 1", __FILE__, __LINE__);while ($data=mysqli_fetch_assoc($datosmem)){$nombremem=$data['realName'];}
mysqli_free_result($datosmem);

echo'1: ';
echo'<div id="muro-'.$ivvd.'"><div id="muroEfectAV">';

echo'<table><tr><td valign="top">';
if(empty($user_settings['avatar'])){$AVA=$no_avatar;}else{$AVA=$user_settings['avatar'];}
echo'<a href="/perfil/'.$user_settings['realName'].'"><img src="'.$AVA.'" class="avatar-box" onerror="error_avatar(this)" width="50" height="50" alt="" /></a>';

echo'</td><td valign="top" style="margin:0px;font-size:11px;"><strong><a href="/perfil/'.$user_settings['realName'].'" title="'.$user_settings['realName'].'" style="font-size:14px;color:#D35F2C;">'.$user_settings['realName'].'</a></strong><br />'.VideosMuro($filtrado);

echo'<div style="margin-top:6px;">';
echo $yata.'&#32;-&#32;<span onclick="boxHablar(\''.$ivvd.'\');" style="cursor:pointer;color:#424242;" id="c-'.$ivvd.'">Comentar</span>'; echo'<span style="display:none;" id="vmam_'.$ivvd.'">&#32;-&#32;<a href="/perfil/'.$context['member']['name'].'/muro;ccIDmuro='.$ivvd.'">Ver muro a muro</a></span>';
 if($user_settings['ID_MEMBER']==$context['member']['id'] || ($user_info['is_admin'] || $user_info['is_mods'])){echo'&#32;-&#32;<span class="pointer" onclick="Boxy.confirm(\'&iquest;Estas seguro que deseas borrar este mensaje?\', function() { del_coment_muro(\''.$ivvd.'\'); }, {title: \'Eliminar Mensaje\'}); " title="Eliminar Mensaje">eliminar</span>';} echo'</div></td></tr></table>';
 echo textarea2($ivvd);
 echo'<div class="hrs" style="margin:0px;padding:0px;"></div></div></div>';

//FINNNNNNNNNNNNNNNNNNNNNNNNNNNN
}
$url='/perfil/'.$nombremem.'/muro;ccIDmuro='.$ivvd;
notificacionAGREGAR($idmem,'3','',$url);

$_SESSION['ultima_accionTIME']=$fecha;
die();










}}}}elseif($_POST['quehago']){
$quehago=nohtml($_POST['quehago']);
if(!$_POST['quehago']){fatal_error('Debes escribir algo.');}

if($_POST['quehago']=='�Qu� est&aacute;s haciendo ahora?' || $_POST['quehago']=='&#191;Qu&eacute; est&aacute;s haciendo ahora&#63;'){fatal_error('Debes escribir algo.');}

if(!empty($quehago)){
if(strlen($quehago)>70){fatal_error('No se aceptan escritos mayor a 70 letras.');}
$yo=$user_settings['ID_MEMBER'];
if(empty($yo)){fatal_error('Usuarios no logueados no pueden no pueden hacer esta acci&oacute;n.');}
if(!empty($yo)){
$fecha=time();
db_query("INSERT INTO {$db_prefix}muro (id_user,de,tipo,fecha,muro) VALUES ($yo,'$yo','1','$fecha','$quehago')",__FILE__, __LINE__);

Header("Location: /perfil");exit();die();}}

}
}else{Header("Location:/");exit();die();} ?>