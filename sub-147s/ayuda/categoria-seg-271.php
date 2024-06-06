<?php include("header-seg-1as4d4a777.php");
global $db_prefix,$context,$user_info,$scripturl,$no_avatar,$modSettings,$con,$board;
$cat=str_replace('/','',seguridad($_GET['cat']));
if(empty($cat)){falta('Debes seleccionar la categor&iacute;a.-');}
$catlist=db("
SELECT catid,cat
FROM {$prefijo}cats
WHERE enlace='{$cat}'
ORDER BY cat ASC", __FILE__, __LINE__);
while ($dat=mysqli_fetch_assoc($catlist)){
$id=$dat['catid'];
$namec=$dat['cat'];}
if(empty($id)){falta('Esta categor&iacute;a no existe.-');}
echo'<div class="box_buscador">
<div class="box_title" style="width: 922px;"><div class="box_txt box_buscadort">'.$namec.'</div>
<div class="box_rss"><img alt="" src="/imagenes/espacio.gif" style="width:14px;height:12px;" border="0" /></div></div><div style="width:914px;padding:4px;" class="windowbg">';
$catss=db("
SELECT a.titulo,a.id
FROM ({$prefijo}articulos as a)
WHERE a.categoria='{$id}'
ORDER BY a.id DESC", __FILE__, __LINE__);
while ($yet=mysqli_fetch_assoc($catss)){
$titulo=$yet['titulo'];
$qid=$yet['id'];
echo'<img alt="" src="/imagenes/articulo.png" title="'.censorText($titulo).'" />&nbsp;<a href="/articulo/'.$qid.'">'.censorText($titulo).'</a><br />';}
if(empty($qid)){echo'<b class="noesta">Esta categor&iacute;a no tiene articulos.-</b><hr/>';}
echo'</div></div>';
include("footer-seg-145747dd.php"); ?>