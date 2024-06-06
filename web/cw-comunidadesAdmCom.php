<?php require("cw-conexion-seg-0011.php"); global $db_prefix,$context,$user_info,$sourcedir,$user_settings;
if($user_info['is_admin'] || $user_info['is_mods']){
$us=(int)$_POST['comun'];

if(empty($us)){fatal_error('faltan datos.-');}

$rs=db_query("SELECT c.id,c.bloquear,c.url
FROM ({$db_prefix}comunidades AS c)
WHERE c.id='$us'
LIMIT 1",__FILE__, __LINE__);
while($row=mysqli_fetch_assoc($rs)){
    $cdavvbv=$row['id'];
    $banccc=$row['bloquear'];
    $url=$row['url'];}
$cdavvbv=isset($cdavvbv) ? $cdavvbv : '';
if(empty($cdavvbv)){fatal_error('faltan datos.-');}
$banear=empty($_POST['eliminar']) ? '0' : '1';
$desbanear=empty($_POST['restaur']) ? '0' : '1';

if($banccc && $desbanear){
    
    db_query("UPDATE {$db_prefix}comunidades
			SET bloquear=0, bloquear_razon='', bloquear_por=''
			WHERE id='$cdavvbv'
			LIMIT 1", __FILE__, __LINE__);}
            
elseif($banear && !$banccc){
    
$razon=$_POST['razon'];    
if(empty($razon)){fatal_error('La raz&oacute;n del ban es obligatoria.');}
if(strlen($razon)>150){fatal_error('La raz&oacute;n es demasiada larga.');}

db_query("UPDATE {$db_prefix}comunidades
			SET bloquear=1, bloquear_razon='$razon', bloquear_por='{$user_settings['realName']}'
			WHERE id='$cdavvbv'
			LIMIT 1", __FILE__, __LINE__); }

Header("Location: /comunidades/$url/");die();exit();

}else{fatal_error('No tenes permisos para estar aca.');}
die();
?>