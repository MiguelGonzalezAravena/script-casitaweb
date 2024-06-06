<?php header("Content-type: application/xml");
echo'<?xml version="1.0" encoding="UTF-8" ?>
<rss version="0.92" xml:lang="spanish">';
$id=(int)$_GET['id'];

if(!$id){die('Tema no existe.');exit;}
require("../../funcion-seg-1547.php");
global $tranfer1,$db_prefix,$context;
$comment_pic=db_query("
SELECT com.comentario,m.realName,com.id,ar.titulo,ar.id AS ids
FROM ({$db_prefix}members AS m,{$db_prefix}comunidades_comentarios AS com,{$db_prefix}comunidades_articulos AS ar)
WHERE com.id_tema='$id' AND com.id_user=m.ID_MEMBER AND com.id_tema=ar.id AND ar.eliminado=0 
ORDER BY com.id DESC
LIMIT 25",__FILE__, __LINE__);
$context['comment-img'] = array();
while ($row = mysqli_fetch_assoc($comment_pic)){
    
$row['comentario']=nohtml(nohtml2($row['comentario']));
if(strlen($row['comentario'])>400){$row['comentario']=substr($row['comentario'],0,397)."...";}
else{$row['comentario']=$row['comentario'];}
$row['comentario']=parse_bbc($row['comentario']);
$titulo=$row['titulo'];
$context['comment-img'][] = array(
'comentario' => $row['comentario'],
'nom-user' => $row['realName'],
'id_comment' => $row['id'],
'id' => $row['ids']);}
mysqli_free_result($comment_pic);

if(!$titulo){die('Este tema no tiene comentarios.-');exit;}

echo'<channel>
<image>
<url>'.$tranfer1.'/rss.png</url>
<title>CasitaWeb! - Comentarios para el tema: '.$titulo. '</title>
<link>http://casitaweb.net/</link>
<width>111</width>
<height>32</height>
<description>Comentarios para el tema '.$titulo. '</description>
</image>
<title>CasitaWeb! - Comentarios para el tema: '.$titulo. '</title>
<link>http://casitaweb.net/</link>
<description>Comentarios para el tema '.$titulo. '</description>';


$contando=1;
foreach($context['comment-img'] AS $comment_img){
echo'<item>
<title><![CDATA[#'.$contando++.' Comentario de '.$comment_img['nom-user'].']]></title>
<link>http://casitaweb.net/comunidades/ver/'.$comment_img['id'].'#comentarios</link>
<description><![CDATA['. $comment_img['comentario'] .']]>
</description>
<comments>http://casitaweb.net/comunidades/ver/'. $comment_img['id'] .'#comentar</comments>
</item>';}
echo'</channel></rss>';?>