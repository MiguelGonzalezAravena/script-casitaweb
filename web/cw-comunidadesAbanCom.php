<?php require("cw-conexion-seg-0011.php"); global $db_prefix,$context,$user_info,$sourcedir,$user_settings;

if($user_info['is_guest']){fatal_error('faltan datos.-');}

include($sourcedir.'/FuncionesCom.php');
if(eaprobacion($ddddsaaat)){fatal_error('Esperando aprobaci&oacute;n de Administrador.');}

$id=seguridad($_GET['id']);
if(empty($id)){fatal_error('Debes seleccionar una comunidad.');}

$rs44=db_query("
SELECT c.id
FROM ({$db_prefix}comunidades as c)
WHERE c.url='$id' AND c.bloquear=0
LIMIT 1",__FILE__, __LINE__);
while ($row=mysql_fetch_assoc($rs44)){$dasdasd=$row['id'];}
$dasdasd=isset($dasdasd) ? $dasdasd : '';
if(empty($dasdasd)){fatal_error('Debes seleccionar una comunidad.');}

baneadoo($dasdasd);
permisios($id_comvv);

if($context['permisoCom']=='1'){
$dddasd=mysql_num_rows(db_query("SELECT id FROM ({$db_prefix}comunidades_miembros) WHERE id_com='$dasdasd' AND rango=1",__FILE__, __LINE__));
if($dddasd < 2){fatal_error('No podes dejar a la comunidad sin administrador.');}}

db_query("DELETE FROM {$db_prefix}comunidades_miembros
WHERE id_user='$user_settings[ID_MEMBER]' AND id_com='$dasdasd'
LIMIT 1", __FILE__, __LINE__);

db_query("UPDATE {$db_prefix}comunidades SET usuarios=usuarios-1 WHERE id='$dasdasd' LIMIT 1", __FILE__, __LINE__);

Header("Location: /comunidades/$id/");
die();exit(); ?>