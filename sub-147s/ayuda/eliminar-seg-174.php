<?php include("header-seg-1as4d4a777.php");
global $func, $context, $settings, $db_prefix, $options, $txt,$con, $scripturl;
global $tranfer1,$user_settings,$func,$ID_MEMBER, $context,$db_prefix;
global $prefijo,$user_settings, $user_info, $ID_MEMBER,$context, $txt, $modSettings;
if($user_info['is_admin'] || $user_info['is_mods']){
$id=(int)$_GET['id'];
if(empty($id)){falta('Debes seleccionar un articulo.-');}
$catlist=db("
SELECT id
FROM {$prefijo}articulos
WHERE id='{$id}'
ORDER BY id ASC
LIMIT 1", __FILE__, __LINE__);
while($dat=mysql_fetch_assoc($catlist)){$qid=$dat['id'];}
if(empty($qid)){falta('El articulo no existe.-');}

db("DELETE FROM {$prefijo}articulos WHERE id='$id'",__FILE__, __LINE__);

Header("Location: /");}
else{falta('Debes ser de Staff.-');}
include("footer-seg-145747dd.php");
?>