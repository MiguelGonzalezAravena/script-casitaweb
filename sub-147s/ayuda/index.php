<?php include("header-seg-1as4d4a777.php");
global $db_prefix,$context,$user_info,$scripturl,$no_avatar,$modSettings,$board,$prefijo;
echo'<div class="box_buscador" style="margin-bottom:8px;">
<div class="box_title" style="width:922px;"><div class="box_txt box_buscadort">Categor&iacute;as</div>
<div class="box_rss"><img alt="" src="/imagenes/espacio.gif" style="width:14px;height:12px;" border="0" /></div></div><div style="width:914px;padding:4px;" class="windowbg">';
$catlist=db("
SELECT catid,cat,enlace
FROM {$prefijo}cats
WHERE maincat=0
ORDER BY cat ASC", __FILE__, __LINE__);
$countt=1;
echo'<table align="center"><tr>';
while ($cat=mysqli_fetch_assoc($catlist)){
$thiscat=stripslashes($cat['cat']);

echo'<td style="padding-right:150px;"><img alt="" src="/imagenes/carpeta.png" title="'.$thiscat.'" /> <a href="'.$cfaqindex.'/categoria/'.$cat['enlace'].'">'.$thiscat.'</a>';
	$qcount=mysqli_num_rows(db("
	SELECT categoria
	FROM {$prefijo}articulos
	WHERE categoria='{$cat['catid']}'", __FILE__, __LINE__));
	echo' ('.$qcount.')';
	echo'</td>';
	$br=$countt++;
	if($br=='3'){echo'</tr><tr>';}
	if($br=='6'){echo'</tr><tr>';}
	if($br=='9'){echo'</tr>';}}
echo'</tr></table></div></div>';

$qlist=db("
SELECT id, titulo, fecha
FROM {$prefijo}articulos
ORDER BY fecha DESC
LIMIT 5", __FILE__, __LINE__);
echo'
<div class="box_460" style="float:left;margin-right:4px;"><div class="box_title" style="width: 456px;"><div class="box_txt box_460-34">5 articulos recientes</div><div class="box_rss"><img alt="" src="/imagenes/espacio.gif" style="width:14px;height:12px;" border="0" /></div></div>
<div style="width: 448px;padding:4px;" class="windowbg">';
while ($questions=mysqli_fetch_assoc($qlist)){
    if(strlen($questions['titulo'])>45){$questions['titulo']=substr($questions['titulo'],0,42)."...";}else{$questions['titulo']=$questions['titulo'];}    
	$question=censorText(nohtml2($questions['titulo']));
	$dateadded=$questions['fecha'];

echo'<img alt="" src="/imagenes/articulo.png" title="'.$question.'" />&nbsp;<a href="/articulo/'.$questions['id'].'">'.$question.'</a> ('.timeformat($dateadded).')<br />';}
echo'</div></div>';

$qlist=db("
SELECT id, titulo, vieron
FROM {$prefijo}articulos
WHERE vieron > 0
ORDER BY vieron DESC
LIMIT 5", __FILE__, __LINE__);

echo'<div style="float:left;" class="box_460"><div class="box_title" style="width: 460px;"><div class="box_txt box_460-34">5 articulos m&aacute;s populares (Por visitas)</div><div class="box_rss"><img alt="" src="/imagenes/espacio.gif" style="width:14px;height:12px;" border="0" /></div></div>
<div style="width: 452px;padding:4px;" class="windowbg">';

while($questions=mysqli_fetch_assoc($qlist)){
    if(strlen($questions['titulo'])>45){$questions['titulo']=substr($questions['titulo'],0,42)."...";}else{$questions['titulo']=$questions['titulo'];}
	$question=censorText(nohtml2($questions['titulo']));
	if($questions['vieron']==1) $viewed='1  visita'; else $viewed=$questions['vieron'].' visitas';

echo'<img alt="" src="/imagenes/articulo.png" title="'.$question.'" />&nbsp;<a href="/articulo/'.$questions['id'].'">'.$question.'</a> ('.$viewed.')<br />';}
echo'</div></div>';

include("footer-seg-145747dd.php"); ?>