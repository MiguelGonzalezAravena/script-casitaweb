<?php require("cw-conexion-seg-0011.php");
global $context,$db_prefix,$user_settings, $user_info;
if($user_info['is_admin'] || $user_info['is_mods']){
$id=(int)$_GET['id'];
if($id <= 0){fatal_error('Falta el comentario a eliminar.-',false);}
$post=(int)$_GET['post'];
if($post <= 0){fatal_error('Falta el comentario a eliminar.-',false);}

$existe=mysql_num_rows(db_query("SELECT c.id
FROM ({$db_prefix}comentarios_mod AS c)
WHERE c.id='$id' 
LIMIT 1", __FILE__, __LINE__));
if(!$existe){fatal_error('Este comentario no se puede eliminar.');}
    
$reddd=db_query("
SELECT c.id_user
FROM ({$db_prefix}comunicacion as c)
WHERE c.id_contenido='$post'
LIMIT 1", __FILE__, __LINE__);
while($red=mysql_fetch_array($reddd)){$context['id_user']=$red['id_user'];}
if(!$context['id_user']){fatal_error('Este comentario no se puede eliminar.');}


if($context['id_user']==$user_settings['ID_MEMBER'] || $user_info['is_admin']){

db_query("DELETE FROM {$db_prefix}comentarios_mod WHERE id='$id'",__FILE__, __LINE__);}

Header("Location: /moderacion/comunicacion-mod/post/$post");}
else{fatal_error('Este comentario no se puede eliminar.');}

?>