<?php require("cw-conexion-seg-0011.php");
global $tranfer1, $context, $settings, $db_prefix, $options, $txt,$user_info,$user_settings, $scripturl;
if($user_info['is_guest']){Header("Location: /");exit();die();}else{
$ids=$_GET['user'];
if(empty($ids)){fatal_error('No seleccionastes a nadie, para agregar como amistad.');}else{
$datosmem=db_query("SELECT realName,ID_MEMBER FROM ({$db_prefix}members) WHERE realName='$ids'",__FILE__, __LINE__);
while($data=mysqli_fetch_assoc($datosmem)){$ser=$data['ID_MEMBER'];$name=$data['realName'];}

if($ser===$user_settings['ID_MEMBER']){fatal_error('No podes agregarte como amigo.');}else{
if(empty($ser)){fatal_error('El usuario seleccionado no existe.');}else{

$ser=seguridad($ser);

$yo=$user_settings['ID_MEMBER'];
if(empty($yo)){fatal_error('Debes estar logueado para realizar esta acci&oacute;n');}else{
$yo=seguridad($user_settings['ID_MEMBER']);

$errorr=db_query("SELECT user,amigo FROM {$db_prefix}amistad WHERE (user='$yo' AND amigo='$ser' OR user='$ser' AND amigo='$yo') AND acepto=1 LIMIT 1",__FILE__, __LINE__);

$yadio=mysqli_num_rows($errorr) != 0 ? true : false; mysqli_free_result($errorr);
if($yadio){fatal_error('Ya sos amigo de <b>'.$name.'</b>.');}else{
    
$errorrd=mysqli_num_rows(db_query("SELECT user,amigo FROM {$db_prefix}amistad WHERE (user='$yo' AND amigo='$ser' OR user='$ser' AND amigo='$yo') AND acepto=0 LIMIT 1",__FILE__, __LINE__));
if($errorrd){fatal_error('Esperando confirmaci&oacute;n de <b>'.$name.'</b>.');}else{
    
$fecha=time();
db_query("INSERT INTO {$db_prefix}amistad (user,amigo,fecha) VALUES ('$yo','$ser','$fecha')",__FILE__, __LINE__);

Header("Location: /perfil/$ids");exit();die();}}}}}}}
?>