<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $context, $db_prefix, $user_info, $ajaxError, $modSettings, $boardurl;

if (empty($context['ajax'])) {
  echo $ajaxError;
  die();
}

if (($user_info['is_admin'] || $user_info['is_mods'])) {
?>
<script type="text/javascript" >
  // Enviar anuncio
  function EnviarAnuncio() {
    $('#cargandoBoxyc').css('display','none');
    $('#cargandoBoxy').css('display','block');
    $.ajax({
      type: 'POST',
      url: '<?php echo $boardurl; ?>/web/cw-Anunciar.php',
      cache: false,
      data: 'anuncio=' +  encodeURIComponent($('#anuncio').val()),
      success: function(h) {
        $('#cargandoBoxy').css('display','none');
        $('#cargandoBoxyc').css('display','block');
        $('#contentv').remove();
        $('#resultado').removeClass('noesta');
        $('#resultado').addClass('noesta-ve');
        $('#resultado').html(h.substring(3)).fadeIn('fast');	
        $('#mensaje-top').html('Cambios guardados...');		
      },
      error: function() {
        Boxy.alert("Error, volver a intentar...", null, { title: 'Alerta' });
      }
    });
  }
</script>
<?php 
echo '
  <div style="width: 300px;" align="center">
    <div id="resultado" style="display: none;"></div>
    <div id="contentv">
      <strong class="size9">S&oacute;lo acepta BBCode</strong><br />
      <input onfocus="foco(this);" onblur="no_foco(this);" maxlength="600" id="anuncio" style="width: 98%;" value="' . nohtml($modSettings['news']) . '" /><br />
      <input class="login" value="Guardar cambios" onclick="EnviarAnuncio();" type="button" />
    </div>
  </div>';
}

?>