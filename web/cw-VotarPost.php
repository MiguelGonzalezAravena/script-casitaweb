<?php require("cw-conexion-seg-0011.php"); global $context, $db_prefix, $txt, $modSettings, $user_info, $user_settings;

if($user_info['is_guest']){die('0: No tenes permisos para estar aca.-');}else{

	$amount=(int) $_GET['puntos'];
	$topic=(int) $_GET['post'];

	if(empty($amount)){die('0: Debes agregar la cantidad que desea dar.-');}
	if(empty($topic)){die('0: Debes seleccionar el post que le queres dar puntos.');}

	if($user_settings['posts'] < $amount)die('0: '.$txt['shop_dont_have_much'].'');
	elseif($amount < 1)die('0: '.$txt['shop_invalid_send_amount']);
    elseif($topic){
        
$lok=db_query("
SELECT m.ID_TOPIC,m.subject,m.ID_BOARD,m.ID_MEMBER,b.description
FROM ({$db_prefix}messages AS m, {$db_prefix}boards AS b)
WHERE m.ID_TOPIC='{$topic}' AND m.eliminado=0
LIMIT 1", __FILE__, __LINE__);
while($lokrow=mysql_fetch_assoc($lok)){
$ID_BOARD=$lokrow['ID_BOARD'];
$id_user=$lokrow['ID_MEMBER'];
$cat=$lokrow['description'];
$title=$lokrow['subject'];}
$id_user=isset($id_user) ? $id_user : '';
if(empty($id_user)){die('0: Este post no existe.');}

$errorr=db_query("SELECT id_member,id_post
FROM {$db_prefix}puntos
WHERE id_member='{$user_settings['ID_MEMBER']}' AND id_post='$topic'
LIMIT 1", __FILE__, __LINE__);
$yadio=mysql_num_rows($errorr); mysql_free_result($errorr);
if($yadio){die('0: Ya has dado puntos a este post.-');}else{

if($ID_BOARD===45 || $ID_BOARD===132){
die('0: No se permiten puntuar los post de esta categor&iacute;a.');}
if($context['leecher']){
die('0: Usuarios de rango Turistas no pueden dar puntos.');}
if($context['user']['is_guest']){
die('0: Usuarios no registrado no pueden dar puntos.');}

if($amount > $modSettings['puntos_por_post-img']){
die('0: Solo puedes dar '.$modSettings['puntos_por_post-img'].' puntos para cada post.');}
if($amount > $user_settings['puntos_dia']){
die('0: Solo tienes '.$user_settings['puntos_dia'].' puntos disponibles para dar.');}

if($user_settings['ID_MEMBER']===$id_user)
die('0: No puedes dar puntos a tus post.-');

		   $fecha=time();
			db_query("
				UPDATE {$db_prefix}members
				SET posts = posts + {$amount}
				WHERE ID_MEMBER = {$id_user}
				LIMIT 1", __FILE__, __LINE__);
				
			db_query("
				UPDATE {$db_prefix}members
				SET puntos_dia=puntos_dia-{$amount}, TiempoPuntos='$fecha'
				WHERE ID_MEMBER={$user_settings['ID_MEMBER']}
				LIMIT 1", __FILE__, __LINE__);
				
		    db_query("
				UPDATE {$db_prefix}messages
				SET puntos = puntos + {$amount}
				WHERE ID_TOPIC='$topic'
				LIMIT 1", __FILE__, __LINE__);

db_query("INSERT INTO {$db_prefix}puntos (id_post,id_member,fecha,cantidad)
				VALUES ('$topic', '{$user_settings['ID_MEMBER']}', '$fecha', '$amount')", __FILE__, __LINE__);

notificacionAGREGAR($id_user,'5',$amount);


pts_sumar_grup($id_user);

die('1: Puntos agregados!');
}}} ?>