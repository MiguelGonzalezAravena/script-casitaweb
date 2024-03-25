<?php require("cw-conexion-seg-0011.php");
global $context,$tranfer1,$user_info,$db_prefix,$ajaxError;
if(empty($context['ajax'])){echo $ajaxError; die();}

$_GET['id']=isset($_GET['id']) ? (int) $_GET['id'] : $_GET['id'];

echo'<div><div style="width:500px;height:500px;overflow: auto;">';
if(($user_info['is_admin'] || $user_info['is_mods'])){
$datos=db_query("
SELECT m.ID_PICTURE,m.puntos,m.title
FROM ({$db_prefix}gallery_pic AS m)
WHERE m.ID_MEMBER = '{$_GET['id']}' AND m.puntos != 0 
ORDER BY m.ID_PICTURE DESC 
LIMIT 30", __FILE__, __LINE__);

while($row=mysql_fetch_assoc($datos)){
$d=$row['ID_PICTURE'];
echo'<strong><a title="'.censorText($row['title']).'" href="/imagenes/ver/'.$row['ID_PICTURE'].'" class="titlePost">'.censorText($row['title']).'</a> ('.$row['puntos'].' puntos)</strong><br /><i style="color:red;">Puntos otorgados por:';

$request=db_query("
SELECT p.cantidad,m.realName
FROM ({$db_prefix}gallery_cat AS p)
INNER JOIN {$db_prefix}members AS m ON p.id_img='{$row['ID_PICTURE']}' AND p.id_user=m.ID_MEMBER
ORDER BY p.ID_CAT DESC", __FILE__, __LINE__);
while($row2 = mysql_fetch_assoc($request)){echo'&#8226;&#32;<a href="/perfil/'.$row2['realName'].'" title="'.$row2['cantidad'].' puntos">'.$row2['realName'].'</a>&#32;';}mysql_free_result($request);
echo'</i><div class="hrs"></div>';}mysql_free_result($datos);
$d=isset($d) ? $d : '';
if(empty($d)){echo'<div class="noesta">No tiene imagenes creadas.</div>';}

echo'<div class="botnes" onclick="cerrarBox();" style="float:right;clear: both; height: 32px;"><a href="javascript:Boxy.load(\'/web/cw-TEMPquienDioptsPost.php?id='.$_GET['id'].'\', { title:\'30 posts\'})">Posts</a></div>';}
echo'</div></div>'; ?>