<?php require("cw-conexion-seg-0011.php"); global $db_prefix,$user_info;
if($user_info['is_guest']){echo'<div class="noesta-am">Solo Usuarios REGISTRADOS pueden actualizar los comentarios.<br /><a href="/registrarse/">REGISTRATE</a> - <a href="/ingresar/">CONECTATE</a></div>';}else{
$rs2=db_query("SELECT m.realName,t.titulo,t.id,co.url
FROM ({$db_prefix}comunidades_comentarios AS c, {$db_prefix}members AS m, {$db_prefix}comunidades_articulos AS t, {$db_prefix}comunidades AS co)
WHERE c.id_user=m.ID_MEMBER AND c.id_tema=t.id AND t.id_com=co.id AND co.bloquear=0 AND t.eliminado=0 AND co.acceso <> 4
ORDER BY c.id DESC
LIMIT 10",__FILE__, __LINE__);
while ($row=mysql_fetch_assoc($rs2)){
$ddddsxx=nohtml(nohtml2($row['titulo']));
$ddaa=$row['titulo'];
echo'<font class="size11"><b><a href="/perfil/'.$row['realName'].'" target="_self" title="'.$row['realName'].'">'.$row['realName'].'</a></b> - <a href="/comunidades/'.$row['url'].'/'.$row['id'].'/'.urls($ddaa).'.html" target="_self" title="'.$ddddsxx.'">'.$ddddsxx.'</a></font><br style="margin: 0px; padding: 0px;">';}}
die();
?>