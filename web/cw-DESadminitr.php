<?php require("cw-conexion-seg-0011.php");
global $tranfer1, $context,$user_settings,$user_info,$db_prefix;
if($user_info['is_guest']){die('0: No podes estar aca.-');}
$accion=(int)$_GET['acion'];

if($accion=='1' || $accion=='2'){
$quienid=(int)$_GET['user'];
if(empty($quienid)){die('0: Debes seleccionar alguien a quien desadmitir.-');}
if($user_settings['ID_MEMBER']==$quieid){die('0: No te podes desadmitir a vos mismo.-');}

$existeUser = mysqli_num_rows(db_query("SELECT ID_MEMBER FROM ({$db_prefix}members) WHERE ID_MEMBER='$quienid' LIMIT 1", __FILE__, __LINE__));
if(empty($existeUser)){die('0: El usuario seleccionado no existe.-');}

$fecha=time();
if($accion=='2'){
            db_query("INSERT INTO {$db_prefix}pm_admitir (id_user,quien,fecha) VALUES ('{$user_settings['ID_MEMBER']}', '$quienid','$fecha')", __FILE__, __LINE__);}

elseif($accion=='1'){
            db_query("DELETE FROM {$db_prefix}pm_admitir WHERE quien='$quienid' AND id_user='{$user_settings['ID_MEMBER']}'", __FILE__, __LINE__);}
die('1: si');}
die('0: no');?>