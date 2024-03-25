<?php require("cw-conexion-seg-0011.php"); global $context,$func,$db_prefix,$user_settings;
if($user_info['is_admin'] || $user_info['is_mods']){
    
ignore_user_abort(true);
@set_time_limit(300);
    
$titulo=seguridad($_POST['titulo']);
$texto=seguridad($_POST['texto']);
$cerrado=(int)$_POST['cerrado'];

timeforComent('1');

if(strlen($titulo)>70){fatal_error('El titulo no debe tener m&aacute;s de 70 letras.-');}
if(empty($titulo)){fatal_error("Debes escribir un titulo al post.-",false);}
if(strlen($titulo)>65536){fatal_error('El mensaje no debe tener m&aacute;s de 65536 letras.-');}
if(empty($texto))fatal_error("Debes escribir un mensaje.");
$titulo=strtr($func['htmlspecialchars']($titulo), array("\r" => '', "\n" => '', "\t" => ''));
$titulo=addcslashes($titulo, '"');
$titulo=censorText($titulo);
$texto=$func['htmlspecialchars'](stripslashes($texto), ENT_QUOTES);
$texto=str_replace(array('"', '<', '>', '  '), array('&quot;', '&lt;', '&gt;', ' &nbsp;'), $texto);
$texto= preg_replace('~<br(?: /)?' . '>~i', "\n", $texto);
$texto=censorText($texto);

if(empty($cerrado)){$look='0';}else{$look='1';}

$date=time();
db_query("INSERT INTO {$db_prefix}comunicacion (id_user,titulo,cerrado,fecha,texto) VALUES ('{$user_settings['ID_MEMBER']}','$titulo','$look','$date','$texto')",__FILE__, __LINE__);
$post = db_insert_id();

//NOTIFICACIONES
$getData=db_query("SELECT m.ID_MEMBER FROM ({$db_prefix}members as m) WHERE m.ID_GROUP=1 OR m.ID_GROUP=2", __FILE__, __LINE__);
while($celda=mysql_fetch_array($getData)){notificacionAGREGAR($celda['ID_MEMBER'],'10');} 
mysql_free_result($getData);

$_SESSION['ultima_accionTIME']=time();

Header("Location: $url");exit();}
else{die();}
?>