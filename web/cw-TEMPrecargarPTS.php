<?php require("cw-conexion-seg-0011.php");
global $context,$user_info,$ajaxError,$user_settings,$modSettings;
if(empty($context['ajax'])){echo $ajaxError; die();}

if(($user_info['is_admin'] || $user_info['is_mods'])){
?>
<script type="text/javascript">
//Recargar puntos
function recargarPTS(){
if($('#user').val() == ''){$('#user').focus(); return false;}
$('#cargandoBoxyc').css('display','none');
$('#cargandoBoxy').css('display','block');
$.ajax({
		type: 'POST',
		url: '/web/cw-recargarPts.php',
		cache: false,
		data: 'user=' +  encodeURIComponent($('#user').val()),
		success: function(h){
		  $('#cargandoBoxy').css('display','none');
          $('#cargandoBoxyc').css('display','block');
          $('#contenidoRE').remove();
				if(h.charAt(0)==0){ //Datos incorrectos
					$('#resultadoRE').addClass('noesta');
					$('#resultadoRE').html(h.substring(3)).fadeIn('fast');
				} else
				if(h.charAt(0)==1){ //OK				
					$('#resultadoRE').removeClass('noesta');
					$('#resultadoRE').addClass('noesta-ve');
					$('#resultadoRE').html(h.substring(3)).fadeIn('fast');}			
		},
		error: function(){
		  Boxy.alert("Error, volver a intentar...", null, {title: 'Alerta'});
		}
	});
}

</script>

<?php
  
if(empty($user_settings['dar_dia'])){$ss='<span style="color:red;">A las <span style="font-size:9px;" title="Horario Argentino">('.$modSettings['horap'].')</span> se recargar&aacute;n las recargas.</span>';}
else{$ss='<span style="color:green;">'.$user_settings['dar_dia'].' recargas disponibles.</span>';}

echo'<div style="width: 400px;">';

echo'<div id="resultadoRE" style="display:none;"></div>
<div align="center" id="contenidoRE">
'.$ss.'<br />
<input type="text" onfocus="foco(this);" onblur="no_foco(this);" id="user" tabindex="1" style="width:155px;" maxlength="60" title="Introduci el NICK tal cual es." /><br />
<input onclick="return recargarPTS();" type="button" value="Recargar" class="pointer" tabindex="2" />
</div>

</div>';
 } ?>