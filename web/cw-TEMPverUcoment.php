<?php require("cw-conexion-seg-0011.php");
global $context,$db_prefix,$user_info,$ajaxError;
if(empty($context['ajax'])){echo $ajaxError; die();}
echo'<div><div style="width:700px;height:500px;overflow: auto;">';
if(($user_info['is_admin'] || $user_info['is_mods'])){

$Resultado=db_query("
SELECT com.id_post,com.id_coment
FROM ({$db_prefix}comentarios as com)
ORDER BY com.id_coment DESC
LIMIT 30", __FILE__, __LINE__);
while($MostrarFila2=mysqli_fetch_array($Resultado)){
$datos=db_query("
SELECT m.ID_TOPIC,c.description,m.subject,com.id_coment,com.comentario,mem.realName
FROM ({$db_prefix}messages as m, {$db_prefix}boards as c, {$db_prefix}comentarios as com,{$db_prefix}members as mem)
WHERE com.id_coment='{$MostrarFila2['id_coment']}' AND m.ID_TOPIC='{$MostrarFila2['id_post']}' AND m.ID_BOARD=c.ID_BOARD AND com.id_user=mem.ID_MEMBER
LIMIT 1", __FILE__, __LINE__);
while($row=mysqli_fetch_array($datos)){
echo'<a href="/post/'.$row['ID_TOPIC'].'/'.$row['description'].'/'.urls(censorText($row['subject'])).'.html" title="'.censorText($row['subject']).'" class="categoriaPost '.$row['description'].'">'.censorText($row['subject']).'</a>
<a href="/post/'.$row['ID_TOPIC'].'/'.$row['description'].'/'.urls(censorText($row['subject'])).'.html#cmt_'.$row['id_coment'].'" title="'.censorText($row['subject']).'" style="color:green;">Ir al comentario</a> - <i>Escrito por: <a href="/perfil/'.$row['realName'].'" title="'.$row['realName'].'">'.$row['realName'].'</a></i><br/>'.str_replace('if(this.width >720) {this.width=720}','if(this.width >698) {this.width=680}',censorText(parse_bbc($row['comentario']))).'<div class="hrs"></div>';}
}
}
echo'</div></div>'; ?>