<?php require("cw-conexion-seg-0011.php");
global $tranfer1, $context,$db_prefix, $user_info,$user_settings;
if($user_info['is_guest']){die();}

$aLista= (int)$_GET['id'];
if($aLista <= 0){die('0: Error.');}else{
    
$reddd=db_query("
SELECT c.amigo,c.user
FROM ({$db_prefix}amistad AS c)
WHERE c.id='{$aLista}' AND c.acepto=0
LIMIT 1", __FILE__, __LINE__);
while($red=mysqli_fetch_array($reddd)){$context['amigo']=$red['amigo'];$userxx=$red['user'];}

if($context['amigo']==$user_settings['ID_MEMBER']){
$tip=(int)$_GET['tipo'];
if(empty($tip)){db_query("DELETE FROM {$db_prefix}amistad WHERE id='$aLista'", __FILE__, __LINE__);}
else{db_query("UPDATE {$db_prefix}amistad SET acepto=1 WHERE id='$aLista' LIMIT 1", __FILE__, __LINE__);}

die('1: Ok');}
else{die('0: Sin permisos.');}} ?>