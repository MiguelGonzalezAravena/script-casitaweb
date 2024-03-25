<?php require("cw-conexion-seg-0011.php");
global $context,$tranfer1,$ajaxError;
if(empty($context['ajax'])){echo $ajaxError; die();}
if(!$user_info['is_admin']){die();}

echo'<div style="width:500px;" align="center">
<form action="/web/cw-eliminarTagsAdm.php" method="post" accept-charset="UTF-8" name="edtags" id="edtags" enctype="multipart/form-data" style="margin: 0;">
<b class="size11">Eliminar palabra:</b><br /><input type="text" onfocus="foco(this);" onblur="no_foco(this);" name="palabra" id="palabra" value="" style="width:150px;" /><br/><br/><input class="login" type="submit" value="Enviar" style="width:150px;" />
</form></div>'; ?>