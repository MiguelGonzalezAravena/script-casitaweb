<?php require("cw-conexion-seg-0011.php");
global $tranfer1, $context, $settings, $db_prefix, $options, $txt, $user_info, $user_settings,  $scripturl;
if($user_info['is_admin']){
$ids=(int)seguridad($_POST['id-seg-2451']);
$tipo=seguridad($_POST['tipo']);
if(empty($ids)){fatal_error('Debe seleccionar el id.-');}
$context['topicss']=mysql_num_rows(db_query("
SELECT ID_TOPIC
FROM {$db_prefix}messages
WHERE ID_TOPIC='$ids'
LIMIT 1", __FILE__, __LINE__));
if(empty($context['topicss'])){fatal_error('El post no existe.-');}

if($tipo=='Cambiar cat'){	
$cat=(int)seguridad($_POST['categorias']);
if(empty($cat)){fatal_error('Debe seleccionar la cat.-');}
$context['contadorsss']=mysql_num_rows(db_query("
SELECT ID_BOARD
FROM {$db_prefix}boards
WHERE ID_BOARD='$cat'
LIMIT 1", __FILE__, __LINE__));
if(empty($context['contadorsss'])){fatal_error('La categor&iacute;a especificada no existe.-');}

	db_query("
		UPDATE {$db_prefix}messages
		SET ID_BOARD='$cat'
		WHERE ID_TOPIC='$ids'
		LIMIT 1", __FILE__, __LINE__);
}elseif($tipo=='Regalar'){
$user=seguridad($_POST['useradar']);
if(empty($user)){fatal_error('Debes seleccionar el usuario.-');}
$lvccct=db_query("
SELECT memberIP,ID_MEMBER,emailAddress
FROM ({$db_prefix}members)
WHERE realName='{$user}'
ORDER BY ID_MEMBER DESC
LIMIT 1", __FILE__, __LINE__);
while($des=mysql_fetch_assoc($lvccct)){
$memberIP=$des['memberIP'];
$id_mem=$des['ID_MEMBER'];
$emailAddress=$des['emailAddress'];}

if(empty($id_mem)){fatal_error('El usuario no existe.-');}
	db_query("
		UPDATE {$db_prefix}messages
		SET posterName='$user',ID_MEMBER='$id_mem',posterEmail='$emailAddress',posterIP='$memberIP'
		WHERE ID_TOPIC='$ids'
		LIMIT 1", __FILE__, __LINE__);
}} Header("Location: /"); ?>