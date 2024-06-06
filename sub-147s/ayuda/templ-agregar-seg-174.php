<?php include("header-seg-1as4d4a777.php");
global $db_prefix,$context,$user_info,$scripturl,$no_avatar,$modSettings,$tranfer1,$board,$prefijo;
if($user_info['is_admin'] || $user_info['is_mods']){
echo'<form action="/art-enviando/" method="post" accept-charset="'.$context['character_set'].'" name="nuevoArticulo" id="nuevoArticulo" enctype="multipart/form-data" style="margin: 0;">';

echo'<div class="box_buscador">
<div class="box_title" style="width: 922px;"><div class="box_txt box_buscadort"><center>Agregar articulo</center></div>
<div class="box_rss"><img alt="" src="/imagenes/espacio.gif" style="width:14px;height:12px;" border="0" /></div></div><div style="width:914px;padding:4px;" class="windowbg"><b class="size11">Titulo:</b><br />
<input type="text" onfocus="foco(this);" onblur="no_foco(this);" name="titulo" value="" tabindex="1" style="width:907px;" maxlength="60" /><br />
<b class="size11">Mensaje del articulo:</b><textarea onfocus="foco(this);" onblur="no_foco(this);" style="height:300px;width:907px;" id="markItUp" name="contenido" class="markItUpEditor" tabindex="2"></textarea>';
echo'<b class="size11">Categor&iacute;a:</b><br />
<select tabindex="3" name="categorias">';
$catlist=db("SELECT catid,cat,enlace
FROM {$prefijo}cats
WHERE maincat=0
ORDER BY cat ASC", __FILE__, __LINE__);
while($cat=mysqli_fetch_assoc($catlist)){echo'<option value="'.$cat['catid'].'">'.$cat['cat'].'</option>';}
echo'</select><br /><br />';

echo'<input class="button" style="font-size: 15px;" value="Publicar" title="Publicar" type="submit" tabindex="4" /></div></div></form>';

}else{
falta("Solo Staff.");}
include("footer-seg-145747dd.php");
?>