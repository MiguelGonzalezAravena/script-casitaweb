<?php require("cw-conexion-seg-0011.php"); global $db_prefix,$context,$user_info,$sourcedir,$user_settings;
if($user_info['is_guest']){fatal_error('faltan datos.-');}

$us=(int)$_POST['miembro-cuestion'];
if(!$us){fatal_error('faltan datos.-');}

$rs=db_query("SELECT c.rango,c.ban,c.id,c.rango,c.id_user,co.url,c.id_com
FROM ({$db_prefix}comunidades_miembros AS c, {$db_prefix}comunidades AS co)
WHERE c.id='$us' AND c.id_com=co.id AND c.aprobado=1
LIMIT 1",__FILE__, __LINE__);
while($row=mysql_fetch_assoc($rs)){
    $cdavvbv=$row['id'];
    $id_comvv=$row['id_com'];
    $url=$row['url'];
    $crngo=$row['rango'];
    $serid=$row['id_user'];
    $banccc=$row['ban'];}
$serid=isset($serid) ? $serid : '';

if($user_settings['ID_MEMBER']==$serid){fatal_error('No podes modificar a los administradores.');}
include($sourcedir.'/FuncionesCom.php');

if(eaprobacion($ddddsaaat)){fatal_error('Esperando aprobaci&oacute;n de Administrador.');}

permisios($id_comvv);
if($context['permisoCom']=='1' || $context['permisoCom']=='3'){
if($context['permisoCom']=='3' && $crngo=='1'){fatal_error('No podes modificar a los administradores.');}
$rangoCambiar=(int)$_POST['rango'];
$banear=!$_POST['banear'] ? '0' : '1';
$desbanear=!$_POST['desbanear'] ? '0' : '1';

if($context['permisoCom']=='3' &&  ($rangoCambiar=='0' || $rangoCambiar=='2' || $rangoCambiar=='3')){
db_query("UPDATE {$db_prefix}comunidades_miembros
			SET rango='$rangoCambiar'
			WHERE id='$cdavvbv'
			LIMIT 1", __FILE__, __LINE__);}
            
elseif($context['permisoCom']=='1' &&  ($rangoCambiar=='0' || $rangoCambiar=='1' || $rangoCambiar=='2' || $rangoCambiar=='3' || $rangoCambiar=='5')){
    db_query("UPDATE {$db_prefix}comunidades_miembros
			SET rango='$rangoCambiar'
			WHERE id='$cdavvbv'
			LIMIT 1", __FILE__, __LINE__);}
if($banccc && $desbanear){
    $nullo = 'NULL'; 
    db_query("UPDATE {$db_prefix}comunidades_miembros
			SET ban=0, ban_razon='', ban_expirate =$nullo, ban_por=''
			WHERE id='$cdavvbv'
			LIMIT 1", __FILE__, __LINE__);
}

elseif($banear && !$banccc){

$razon=$_POST['razon'];    
if(!$razon){fatal_error('La raz&oacute;n del ban es obligatoria.');}
if(strlen($razon)>150){fatal_error('La raz&oacute;n es demasiada larga.');}

$nullo = 'NULL'; 
$expirate=$_POST['expira'] ? 'ban_expirate = '.($_POST['expira']*86400+time()).',' : 'ban_expirate = '.$nullo.',' ;

db_query("UPDATE {$db_prefix}comunidades_miembros
			SET ban=1, ban_razon='$razon',$expirate ban_por='{$user_settings['realName']}'
			WHERE id='$cdavvbv'
			LIMIT 1", __FILE__, __LINE__);     }

Header("Location: /comunidades/$url/");die();exit();
}else{fatal_error('No tenes permisos para estar aca.');}

die();
?>