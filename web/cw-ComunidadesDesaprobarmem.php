<?php require("cw-conexion-seg-0011.php"); global $db_prefix,$context,$user_info,$sourcedir,$ID_MEMBER;
if($user_info['is_guest']){die();}
$us=isset($_GET['m']) ? (int)$_GET['m'] : '';
if(!$us){die();}
$rss3=db_query("
SELECT c.id,co.id AS id_com,co.url
FROM ({$db_prefix}comunidades_miembros AS c, {$db_prefix}comunidades AS co) 
WHERE c.id='$us' AND c.id_com=co.id
LIMIT 1",__FILE__, __LINE__);
while ($r2ow=mysqli_fetch_assoc($rss3)){$dddd=$r2ow['id'];$url=$r2ow['url'];$id_com=$r2ow['id_com'];
include($sourcedir.'/FuncionesCom.php');
permisios($id_com);
if($context['permisoCom']==1){ 

db_query("DELETE FROM {$db_prefix}comunidades_miembros WHERE id='$dddd' LIMIT 1", __FILE__, __LINE__);
            
Header("Location: /comunidades/$url");die();exit();die();}
else{die();} }

?>