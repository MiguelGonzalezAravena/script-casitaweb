<?php require("cw-conexion-seg-0011.php"); 
global $context,$func,$user_info, $user_settings,$db_prefix;

if($user_info['is_admin'] || $user_info['is_mods']){
ignore_user_abort(true);
@set_time_limit(300);
$post=(int)$_POST['id_post'];

timeforComent();

if(empty($post)){fatal_error("Debes seleccionar el post.-",false);}

$sas=mysqli_num_rows(db_query("SELECT id_contenido FROM {$db_prefix}comunicacion WHERE id_contenido='$post'",__FILE__, __LINE__));
if(empty($sas)){fatal_error("Quieres comentar un post que no existe.-",false);}

$comentario=seguridad($_POST['cuerpo_comment']);
if(strlen($comentario)>4500){fatal_error('El comentario es demasiado extenso, abrevi&aacute;.-');}
if(empty($comentario))fatal_error("Debes escribir un comentario.-",false);
$comentario=$func['htmlspecialchars'](stripslashes($comentario), ENT_QUOTES);
$comentario=str_replace(array('"', '<', '>', '  '), array('&quot;', '&lt;', '&gt;', ' &nbsp;'), $comentario);
$comentario= preg_replace('~<br(?: /)?' . '>~i', "\n", $comentario);
$date=time();

db_query("INSERT INTO {$db_prefix}comentarios_mod (id_user,id_post,data,comentario) VALUES ('{$user_settings['ID_MEMBER']}','$post','$date','$comentario')",__FILE__, __LINE__);


//NOTIFICACIONES
$getData=db_query("SELECT m.ID_MEMBER FROM ({$db_prefix}members as m) WHERE m.ID_GROUP=1 OR m.ID_GROUP=2", __FILE__, __LINE__);
while($celda=mysqli_fetch_array($getData)){notificacionAGREGAR($celda['ID_MEMBER'],'9');} 
mysqli_free_result($getData);

$_SESSION['ultima_accionTIME']=time();
Header("Location: /moderacion/comunicacion-mod/post/$post");exit();die();

}else{die();}
?>