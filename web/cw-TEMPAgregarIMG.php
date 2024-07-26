<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $context, $tranfer1, $user_info, $db_prefix, $ajaxError;

if (empty($context['ajax'])) {
  echo $ajaxError;
  die();
}

echo '
  <div style="width: 400px;">
    <img id="cargandoAD" alt="" src="' . $tranfer1 . '/icons/cargando.gif" style="width: 16px; height: 16px; display: none;" border="0" />
    <div id="resultadoAD" style="display: none;"></div>
    <div id="contenidoAD" align="center">
      <form method="POST" action="javascript:addIMG();">
        <b class="size11">T&iacute;tulo:</b>
        <br />
        <input onfocus="foco(this);" onblur="no_foco(this);" tabindex="1" size="60" maxlength="54" type="text" name="title" id="title" value="" />
        <br />
        <br />
        <b class="size11">URL de la imagen:</b><br />
        <input onfocus="foco(this);" onblur="no_foco(this);" type="text" tabindex="2" size="60" name="url" id="url" value="" />
        <div class="hrs"></div>
        <div class="noesta">* Si la imagen contiene pornograf&iacute;a o es morboso, se borrar&aacute;.</div>
        <br />
        <input type="submit" class="login" style="font-size: 14px; padding: 5px; color: #FFFFFF" tabindex="3" value="Agregar imagen" />
      </form>
    </div>
  </div>';

?>