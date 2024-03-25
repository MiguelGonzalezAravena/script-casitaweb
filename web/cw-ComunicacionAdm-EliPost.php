<?php require("cw-conexion-seg-0011.php"); 
global $context,$func,$db_prefix,$user_settings, $user_info;
if($user_info['is_admin'] || $user_info['is_mods']){
$posts=(int)$_GET['post'];
if(empty($posts))fatal_error("No estas eliminando ning&uacute;n post.-",false);
$opciones=db_query("
SELECT c.id_contenido,c.id_user
FROM ({$db_prefix}comunicacion as c)
WHERE c.id_contenido='$posts'
ORDER BY c.id_contenido DESC
LIMIT 1",__FILE__, __LINE__);

while($row=mysql_fetch_array($opciones)){$user=$row['id_user'];$id=$row['id_contenido'];}
if(empty($id)){fatal_error("El post seleccionado no existe.-",false);}

if($user==$user_settings['ID_MEMBER'] || $user_info['is_admin'] || $user_info['is_mods']){
db_query("DELETE FROM {$db_prefix}comunicacion WHERE id_contenido='$id'",__FILE__, __LINE__);
db_query("DELETE FROM {$db_prefix}comentarios_mod WHERE id_post='$id'",__FILE__, __LINE__);}

Header("Location: /moderacion/comunicacion-mod/");}

else{fatal_error("Debes estar conectado y ser de la moderacion, si lo estas contactar con administrador (soporte@casitaweb.net).-");}
?>