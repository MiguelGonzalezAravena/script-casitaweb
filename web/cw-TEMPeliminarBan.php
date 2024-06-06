<?php require("cw-conexion-seg-0011.php");
global $context,$db_prefix,$user_info,$ajaxError,$user_settings;
if(empty($context['ajax'])){echo $ajaxError; die();}
?>
<script type="text/javascript" >
//EliminarBan
function EliminarBan(id,a){
if(a=='1'){ if($('#clave').val() == ''){$('#clave').focus(); return false;}}
$('#cargandoBoxyc').css('display','none');
$('#cargandoBoxy').css('display','block');
$.ajax({
		type: 'POST',
		url: '/web/cw-EliminarBan.php',
		cache: false,
		data: 'is=' + id + '&clave='+  encodeURIComponent($('#clave').val()),
		success: function(h){
		  $('#cargandoBoxy').css('display','none');
          $('#cargandoBoxyc').css('display','block');
          $('#contentv').remove();
          $('#resultado').css('display','block');
				if(h.charAt(0)==0){ //Datos incorrectos
					$('#resultado').addClass('noesta');
					$('#resultado').html(h.substring(3)).fadeIn('fast');
				} else
				if(h.charAt(0)==1){ //OK				
					$('#resultado').removeClass('noesta');
					$('#ban_'+id).remove();
					$('#resultado').addClass('noesta-ve');
					$('#resultado').html(h.substring(3)).fadeIn('fast');}			
		},
		error: function(){
		  Boxy.alert("Error, volver a intentar...", null, {title: 'Alerta'});
		}
	});
}</script>

<?php echo'<div style="width:300px;" align="center"><div id="resultado" style="display:none;"></div><div id="contentv">';
if(($user_info['is_admin'] || $user_info['is_mods'])){
$id=isset($_GET['id']) ? (int)$_GET['id']:'';
if(empty($id)){die('<div class="noesta">Debes seleccionar el ban a eliminar.</div>');}

$request=db_query("
SELECT p.notes,p.clave
FROM ({$db_prefix}ban_groups AS p)
WHERE p.ID_BAN_GROUP='$id'
LIMIT 1", __FILE__, __LINE__);
while($row=mysqli_fetch_array($request)){
	$context['ussdee']=$row['notes'];
	$context['clave']=$row['clave'];}
$context['ussdee']=isset($context['ussdee']) ? $context['ussdee'] : '';
if(empty($context['ussdee'])){die('<div class="noesta">El Ban no existe.</div>');}

if($context['ussdee']==$user_settings['ID_MEMBER']){
echo'<form onsubmit="EliminarBan(\''.$id.'\',\'0\'); return false;" method="post" accept-charset="'.$context['character_set'].'">
<strong>&#191;Estas seguro que desea desbanear a este usuario?</strong><br />
<input class="login" value="Si, estoy seguro" type="submit" />';
}else{
echo'<form onsubmit="EliminarBan(\''.$id.'\',\'1\'); return false;" method="post" accept-charset="'.$context['character_set'].'">Para poder desbanear este usuario es necesario una clave. La clave la sabe solo el moderador que lo baneo.<br/><br/><strong>Clave:</strong><div style="margin-bottom:5px"><input value="" type="text" onfocus="foco(this);" onblur="no_foco(this);" name="clave" id="clave" /><div id="MostrarError1" class="capsprotBAJO" style="width: 290px;margin-bottom:0px">Falta la clave.</div></div>
<input class="login" value="Desbanear usuario" type="submit" />';}
echo'</form>';

}
echo'</div></div>'; ?>