<?php require("cw-conexion-seg-0011.php");
global $context,$tranfer1,$ajaxError,$db_prefix,$ID_MEMBER;
if(empty($context['ajax'])){echo $ajaxError; die();}

$id=(int) $_GET['id'];
echo'<div style="float:left;">';
if(empty($id))echo'<div class="noesta" style="width:774px;">Debes seleccionar una nota a editar.</div>';else{
$notas=db_query("
SELECT titulo,contenido,id
FROM {$db_prefix}notas
WHERE id_user='{$ID_MEMBER}' AND id='{$id}'", __FILE__, __LINE__);
while ($row = mysql_fetch_assoc($notas)){
$id2=$row['id'];
$titulo=nohtml($row['titulo']);
$contenido=nohtml($row['contenido']);
}
mysql_free_result($notas);
$id2=isset($id2) ? $id2 : '';
if(empty($id2))echo'<div class="noesta" style="width:776px;">La nota seleccionada no existe.</div>';else{

echo'<form action="/web/cw-EditarNota.php" method="post" accept-charset="'.$context['character_set'].'" enctype="multipart/form-data">

<input type="text" title="Escribe el Titulo..." onfocus="if(this.value==\'Escribe el Titulo...\') this.value=\'\'; foco(this);" onblur="if(this.value==\'\') this.value=\'Escribe el Titulo...\'; no_foco(this);" value="'.$titulo.'" style="width:758px;font-family:arial;font-size:12px;" name="titulo" id="titulo" maxlength="60" /><br/><textarea name="contenido" id="contenido" style="width:758px;height:185px;font-family:arial;font-size:12px;" title="Escribe el Contenido..." onfocus="if(this.value==\'Escribe el Contenido...\') this.value=\'\'; foco(this);" onblur="if(this.value==\'\') this.value=\'Escribe el Contenido...\'; no_foco(this);">'.$contenido.'</textarea><br/><p align="right" style="margin:0px;padding:0px;"><input type="button" value="Salir sin guardar" class="close login" />
<input type="submit" value="Salir y guardar" name="editar" class="login" />
<input type="hidden" value="'.$id.'" name="id" /></p></form>';

}} ?>