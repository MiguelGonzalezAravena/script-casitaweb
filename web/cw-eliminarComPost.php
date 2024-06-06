<?php require("cw-conexion-seg-0011.php"); global $tranfer1, $context, $user_settings, $user_info,$db_prefix;
if($user_info['is_guest']){die('0: Solo usuarios registrados pueden eliminar comentarios.');}else{
$aLista= (int)$_GET['id'];
if($aLista <= 0){die('0: No seleccionastes comentario.');}else{
$topic=(int) $_GET['post'];
if($topic <= 0){die('0: Este comentario no se puede eliminar.');}else{

$existe=mysqli_num_rows(db_query("
SELECT c.id_user
FROM ({$db_prefix}comentarios AS c)
WHERE c.id_coment='$aLista' AND c.id_post='$topic'
LIMIT 1", __FILE__, __LINE__));
mysqli_free_result($existe);
if(!$existe){die('0: Este comentario no se puede eliminar.');}else{
    
$reddd=db_query("
SELECT p.ID_MEMBER
FROM ({$db_prefix}messages AS p)
WHERE p.ID_TOPIC='$topic'
LIMIT 1", __FILE__, __LINE__);
while($red=mysqli_fetch_array($reddd)){$context['id_user']=$red['ID_MEMBER'];}
mysqli_free_result($reddd);

if($context['id_user']==$user_settings['ID_MEMBER'] || $user_info['is_admin'] || $user_info['is_mods']){
db_query("DELETE FROM {$db_prefix}comentarios WHERE id_coment='$aLista'", __FILE__, __LINE__);
$cccS=mysqli_num_rows(db_query("SELECT c.id_user FROM ({$db_prefix}comentarios AS c) WHERE c.id_post='$topic' LIMIT 1", __FILE__, __LINE__));
mysqli_free_result($cccS);
die('1: '.$cccS);}
else{die('0: No tenes permisos para eliminar este comentario.');}}}} }
?>