<?php require("cw-conexion-seg-0011.php");
global $context,$db_prefix,$user_info,$ajaxError;
if(empty($context['ajax'])){echo $ajaxError; die();}
echo'<div><div style="width:700px;height:500px;overflow: auto;">';
if(($user_info['is_admin'] || $user_info['is_mods'])){

$Resultado=db_query("
SELECT com.ID_COMMENT,com.ID_PICTURE
FROM ({$db_prefix}gallery_comment as com)
ORDER BY com.ID_COMMENT DESC
LIMIT 30", __FILE__, __LINE__);
while($MostrarFila2=mysql_fetch_array($Resultado)){
$datos=db_query("SELECT m.ID_PICTURE,com.ID_COMMENT,m.title,mem.realName,com.comment
FROM ({$db_prefix}gallery_pic as m, {$db_prefix}gallery_comment as com,{$db_prefix}members as mem)
WHERE com.ID_COMMENT='{$MostrarFila2['ID_COMMENT']}' AND m.ID_PICTURE='{$MostrarFila2['ID_PICTURE']}' AND com.ID_MEMBER=mem.ID_MEMBER
LIMIT 1", __FILE__, __LINE__);
while($row=mysql_fetch_array($datos)){
echo'<a href="/imagenes/ver/'.$row['ID_PICTURE'].'#cmt_'.$row['ID_COMMENT'].'" title="'.censorText($row['title']).'">'.censorText($row['title']).'</a><br /><a href="/imagenes/ver/'.$row['ID_PICTURE'].'#cmt_'.$row['ID_COMMENT'].'" title="'.censorText($row['title']).'" style="color:green;">Ir al comentario</a> - <i>Escrito por: <a href="/perfil/'.$row['realName'].'" title="'.$row['realName'].'">'.$row['realName'].'</a></i><br/>'.str_replace('if(this.width >720) {this.width=720}','if(this.width >698) {this.width=680}',censorText(parse_bbc($row['comment']))).'<div class="hrs"></div>';}
}
}
echo'</div></div>'; ?>


