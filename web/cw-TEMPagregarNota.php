
<div>
  <div style="display: none;" id="resultadoan"></div>
  <div id="contenidoan">
    <form action="<?php echo $boardurl; ?>/web/cw-AgregarNota.php" method="post" enctype="multipart/form-data">
      <input type="text" title="Escribe el t&iacute;tulo..." onfocus="if (this.value == 'Escribe el t&iacute;tulo...') this.value = ''; foco(this);" onblur="if (this.value == '') this.value = 'Escribe el t&iacute;tulo...'; no_foco(this);" value="Escribe el t&iacute;tulo..." style="width: 758px; font-family: arial; font-size: 12px;" name="titulo" maxlength="60" id="titulo" />
      <br />
      <textarea name="contenido" id="contenido" style="width: 758px; height: 185px; font-family: arial; font-size: 12px;" title="Escribe el contenido..." onfocus="if (this.value == 'Escribe el contenido...') this.value = ''; foco(this);" onblur="if (this.value == '') this.value = 'Escribe el contenido...'; no_foco(this);">Escribe el contenido...</textarea>
      <br />
      <p align="right" style="margin: 0px; padding: 0px;">
        <input type="submit" value="Crear nota" name="agregar" class="login" />
      </p>
    </form>
  </div>
</div>