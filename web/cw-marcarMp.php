<?php require("cw-conexion-seg-0011.php"); global $context,$db_prefix,$user_settings,$user_info;
if($user_info['is_guest']){die();}else{
$getid=isset($_GET['id']) ? (int)$_GET['id'] : '';
$leer=db_query("
SELECT p.leido
FROM ({$db_prefix}mensaje_personal AS p)
WHERE p.id='{$getid}' AND p.id_para='{$user_settings['ID_MEMBER']}' AND p.eliminado_para=0
LIMIT 1", __FILE__, __LINE__);
while($row=mysqli_fetch_array($leer)){
    
if(!empty($row['leido'])){
db_query("
UPDATE {$db_prefix}mensaje_personal
SET leido=0 WHERE id='{$getid}' AND id_para='{$user_settings['ID_MEMBER']}'
LIMIT 1", __FILE__, __LINE__);

db_query("
UPDATE {$db_prefix}members
SET topics=topics+1
WHERE ID_MEMBER='{$user_settings['ID_MEMBER']}'
LIMIT 1", __FILE__, __LINE__);
}

}mysqli_free_result($leer);

Header("Location: /mensajes/");exit();die();}
?>