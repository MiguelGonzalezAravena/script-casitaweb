<?php require("cw-conexion-seg-0011.php");
global $tranfer1, $context,$db_prefix, $txt,$scripturl, $modSettings,$user_info,$user_settings;
if(empty($user_settings['ID_MEMBER'])){die('0: Debes estar registrado y conectado para poder comentar.');}

$aLista= (int)$_GET['id'];
if($aLista <= 0){die('0: No seleccionastes comentario.');}else{
$topic=(int) $_GET['img'];
if($topic <= 0){die('0: Este comentario no se puede eliminar.');}else{

$existe=mysqli_num_rows(db_query("SELECT c.ID_COMMENT
FROM ({$db_prefix}gallery_comment AS c)
WHERE c.ID_COMMENT='{$aLista}' 
LIMIT 1", __FILE__, __LINE__));
if(!$existe){die('0: Este comentario no se puede eliminar.');}else{
    
$reddd=db_query("
SELECT i.ID_MEMBER
FROM ({$db_prefix}gallery_pic AS i)
WHERE i.ID_PICTURE='$topic'
LIMIT 1", __FILE__, __LINE__);
while($red=mysqli_fetch_array($reddd)){$context['id_user']=$red['ID_MEMBER'];}

if($context['id_user']==$user_settings['ID_MEMBER'] || $user_info['is_admin'] || $user_info['is_mods']){

db_query("DELETE FROM {$db_prefix}gallery_comment WHERE ID_COMMENT = '$aLista'", __FILE__, __LINE__);

$ccc=mysqli_num_rows(db_query("SELECT c.ID_COMMENT FROM ({$db_prefix}gallery_comment AS c) WHERE c.ID_PICTURE='$topic' LIMIT 1", __FILE__, __LINE__));

die('1: '.$ccc);}
else{die('0: No tenes permisos para eliminar este comentario.');}}}} ?>