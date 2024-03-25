<?php require("cw-conexion-seg-0011.php");
global $context,$tranfer1,$db_prefix;
if($context['user']['is_guest']){die('<div style="width:400px;" class="noesta">Solo usuarios conectados pueden denunciar.</div>');}
$_GET['t']=isset($_GET['t']) ? (int) $_GET['t'] : '0';
$_GET['d']=isset($_GET['d']) ? seguridad($_GET['d']) : '0';


//0: Usuarios // 1: Post // 2: Imagenes
if(empty($_GET['t'])){
$esta=mysql_num_rows(db_query("SELECT realName FROM {$db_prefix}members WHERE realName='{$_GET['d']}'",__file__, __line__));
if(empty($esta)){die('<div style="width:400px;" class="noesta">El usuario que deseas denunciar no existe.</div>');}

if($context['user']['name']==$_GET['d']){die('<div style="width:400px;" class="noesta">No te podes denunciar a vos.</div>');}
echo'<div style="width:400px;" >

<div id="resultado" style="display:none;width:400px;margin:0px;"></div>
<div id="contentv" >
<p align="center" style="margin:0px;padding:0px;" class="size11"><strong>Raz&oacute;n de la denuncia:</strong><br />
			<select name="razon" id="razon">
			<option value="Hace Spam">Hace Spam</option>
			<option value="Es Racista o irrespetuoso">Es Racista o irrespetuoso</option>
			<option value="Publica informacion personal">Publica informaci&oacute;n personal</option>
			<option value="Publica Pornografia">Publica Pornografia</option>
			<option value="No cumple con el protocolo">No cumple con el protocolo</option>
			<option value="Otra razon (especificar)">Otra raz&oacute;n (especificar)</option>
			</select><br /><br />
            <strong>Aclaraci&oacute;n y comentarios:</strong><br />
			<textarea name="comentario" id="cDen" onfocus="foco(this);" onblur="no_foco(this);" style="width:380px;"  wrap="hard" tabindex="2"></textarea>
<br /><br /><input class="login" onclick="enviarDen(\'user\',\''.$_GET['d'].'\');" type="button" value="Denunciar" /></p></div></div>';}

elseif($_GET['t']=='1'){

$request = db_query("SELECT m.ID_MEMBER FROM ({$db_prefix}messages AS m) WHERE m.ID_TOPIC='{$_GET['d']}' LIMIT 1", __FILE__, __LINE__); while ($row=mysql_fetch_assoc($request)){$idmember=$row['ID_MEMBER'];} mysql_free_result($request);
$idmember=isset($idmember) ? $idmember : '';
if(empty($idmember)){die('<div style="width:400px;" class="noesta">El post que deseas denunciar no existe.</div>');}


if($context['user']['id']==$idmember)
die('<div style="width:400px;" class="noesta">Disculpe, pero no puedes denunciar tus post, si tiene alg&uacute;n problema, borralo o editalo vos.</div>');

echo'<div style="width:400px;" >
<div id="resultado" style="display:none;width:400px;margin:0px;"></div>
<div id="contentv" >
<p align="center" style="margin:0px;padding:0px;" class="size11"><strong>Raz&oacute;n de la denuncia:</strong><br />
			<select name="razon" id="razon">
			<option value="Re-post">Re-post</option>
			<option value="Se hace Spam">Se hace Spam</option>
			<option value="Tiene enlaces muertos">Tiene enlaces muertos</option>
			<option value="Es Racista o irrespetuoso">Es Racista o irrespetuoso</option>
			<option value="Contiene informacion personal">Contiene informaci&oacute;n personal</option>
			<option value="El Titulo esta en mayuscula">El Titulo esta en may&uacute;scula</option>
			<option value="Contiene Pornografia">Contiene Pornografia</option>
			<option value="Es Gore o asqueroso">Es Gore o asqueroso</option>
			<option value="Esta mal la fuente">Est&aacute; mal la fuente</option>
			<option value="Post demasiado pobre">Post demasiado pobre</option>
			<option value="Pide contrasena y no esta">Pide contrase&ntilde;a y no est&aacute;</option>
			<option value="No cumple con el protocolo">No cumple con el protocolo</option>
			<option value="Otra razon (especificar)">Otra raz&oacute;n (especificar)</option>
			</select><br /><br />
            <strong>Aclaraci&oacute;n y comentarios:</strong><br />
			<textarea name="comentario" id="cDen" onfocus="foco(this);" onblur="no_foco(this);" style="width:380px;"  wrap="hard" tabindex="2"></textarea>
<br /><br /><input class="login" onclick="enviarDen(\'post\',\''.$_GET['d'].'\');" type="button" value="Denunciar" /></p></div></div>';}


elseif($_GET['t']=='2'){

$request=db_query("SELECT p.ID_MEMBER FROM ({$db_prefix}gallery_pic AS p) WHERE p.ID_PICTURE={$_GET['d']} LIMIT 1", __FILE__, __LINE__); while($row = mysql_fetch_assoc($request)){$idmember=$row['ID_MEMBER'];} mysql_free_result($request);
$idmember=isset($idmember) ? $idmember : '';
if(empty($idmember)){die('<div style="width:400px;" class="noesta">El post que deseas denunciar no existe.</div>');}

if($context['user']['id']==$idmember)
die('<div style="width:400px;" class="noesta">Disculpe, pero no puedes denunciar tus imagenes, si tiene alg&uacute;n problema, borrala o editala vos.</div>');

echo'<div style="width:400px;" ><div id="resultado" style="display:none;width:400px;margin:0px;"></div>
<div id="contentv" >
<p align="center" style="margin:0px;padding:0px;" class="size11"><strong>Raz&oacute;n de la denuncia:</strong><br />
			<select name="razon" id="razon">
			<option value="Imagen ya agregada">Imagen ya agregada</option>
			<option value="Se hace Spam">Se hace Spam</option>
			<option value="Contiene Pornografia">Contiene Pornografia</option>
			<option value="Es Gore o asqueroso">Es Gore o asqueroso</option>
			<option value="No cumple con el protocolo">No cumple con el protocolo</option>
			<option value="Otra razon (especificar)">Otra raz&oacute;n (especificar)</option>
			</select><br /><br />
            <strong>Aclaraci&oacute;n y comentarios:</strong><br />
			<textarea name="comentario" id="cDen" onfocus="foco(this);" onblur="no_foco(this);" style="width:380px;"  wrap="hard" tabindex="2"></textarea>
<br /><br /><input class="login" onclick="enviarDen(\'imagen\',\''.$_GET['d'].'\');" type="button" value="Denunciar" /></p>

</div>
</div>'; } ?>