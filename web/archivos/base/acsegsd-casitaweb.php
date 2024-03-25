<?php
function template_manual_above(){}
function template_manual_below(){}
function template_intro(){exit();die();}

function template_tyc3(){global $tranfer1, $context, $db_prefix, $modSettings;
if($context['user']['is_guest']){fatal_error('Vos no podes estar aca.');}
$Activo=substr($modSettings['signature_settings'], 0, 1) == 1;
if($Activo){    

$getid=isset($_GET['u']) ? (int)$_GET['u'] : '';
ditaruser();
echo'<div style="float:left;width: 776px;">';
if($getid){$usecc=$getid;}else{$usecc=$context['user']['id'];}

$existe=db_query("
SELECT signature
FROM ({$db_prefix}members)
WHERE ID_MEMBER='$usecc'
LIMIT 1", __FILE__, __LINE__);
while ($row = mysql_fetch_assoc($existe)){$signature=$row['signature'];}

echo'<script type="text/javascript">
function comprobar(firma) {
if(firma.length>400){ $(\'#MostrarError1\').show();  return false;} else $(\'#MostrarError1\').hide();}
</script>

<form name="per" method="post" action="/web/cw-firmaEditar.php">';
echo'<div class="box_780" style="float:left;">
<div class="box_title" style="width: 774px;"><div class="box_txt box_780-34"><center>';
if($getid){echo'Editar la firma';}
else{echo'Editar mi firma';}
echo'</center></div>';

echo'<div class="box_rss"><img alt="" src="'.$tranfer1.'/blank.gif" style="width:16px;height:16px;" /></div></div>
<div class="windowbg" style="width: 766px; padding: 4px;margin-bottom:8px;">

<textarea onfocus="foco(this);" onblur="no_foco(this);" name="firma" id="firma" style="width: 758px;height:100px;">'.$signature.'</textarea>
<div id="MostrarError1" class="capsprotBAJO" style="width: 758px;">La firma no debe tener m&aacute;s de 400 car&aacute;cteres.</div>
<div class="hrs"></div>
          
<div class="noesta">* Si la firma contiene pornografia, es morboso. Se borrar&aacute;.</div><br />'; 

if($getid){echo'
<input type="hidden" name="admin" value="1" />
<input type="hidden" name="id_user" value="'.$getid.'" />';
$titlbotte='Editar el perfil';}else{$titlbotte='Editar mi perfil';}

echo'<center><input onclick="return comprobar(this.form.firma.value);" type="submit" class="button" style="font-size:15px" value="'.$titlbotte.'" title="'.$titlbotte.'" /></center>
</div></div></div>
</form>';}else{fatal_error('La firma se encuentra desactivada.');} }


function template_tyc4(){}


function template_tyc6(){
global $tranfer1, $context, $settings,$sourcedir, $options, $txt, $scripturl, $db_prefix, $modSettings;
if($context['user']['name']=='rigo'||$context['user']['id']=='1'){
echo'<div class="box_757"><div class="box_title" style="width: 752px;"><div class="box_txt box_757-34"><center>Muros</center></div><div class="box_rss"><img alt="" src="'.$tranfer1.'/blank.gif" style="width: 16px; height: 16px;" border="0" /></div></div></div><div style="width:744px;padding:4px;" class="windowbg">';
$RegistrosAMostrar=25;

if($_GET['pag-seg-157'] < 0){$dud=1;}else{$dud=$_GET['pag-seg-157'];}
if(isset($dud)){$RegistrosAEmpezar=($dud-1)*$RegistrosAMostrar;
$PagAct=$dud;}else{$RegistrosAEmpezar=0;$PagAct=1;}
$Resultado=db_query("SELECT pm.id,pm.id_user,pm.de,pm.muro,pm.tipo FROM ({$db_prefix}muro as pm) ORDER BY pm.id DESC LIMIT $RegistrosAEmpezar, $RegistrosAMostrar", __FILE__, __LINE__);
while($MostrarFila2=mysql_fetch_array($Resultado)){
echo'<div id="muro-'.$MostrarFila2['id'].'">';

$datosmem=db_query("SELECT realName FROM ({$db_prefix}members) WHERE ID_MEMBER='{$MostrarFila2['id_user']}' LIMIT 1", __FILE__, __LINE__);while($data=mysql_fetch_assoc($datosmem)){$nick=$data['realName'];}
$datosmem2=db_query("SELECT realName FROM ({$db_prefix}members) WHERE ID_MEMBER='{$MostrarFila2['de']}' LIMIT 1", __FILE__, __LINE__);while($data4=mysql_fetch_assoc($datosmem2)){$nick3=$data4['realName'];}

if($MostrarFila2['tipo']=='0'){echo'<img src="'.$tranfer1.'/icons/bullet-verde.gif" alt="Escrito" title="Escrito" />';}elseif($MostrarFila2['tipo']=='1'){echo'<img src="'.$tranfer1.'/icons/bullet-rojo.gif" alt="Esta haciendo..." title="Esta haciendo..." />';}else{echo'<b style="color:red;"><i>Tipo de muro no conocido ||| </i></b>';}

echo' - <a onclick="if (!confirm(\'\xbfEstas seguro que deseas borrar este mensaje?\')) return false; del_coment_muro(\''.$MostrarFila2['id'].'\'); return false;" href="#" title="Eliminar Mensaje"><img alt="Eliminar Mensaje" src="'.$tranfer1.'/eliminar.gif" width="8px" height="8px"></a><br/><b>Por:</b> <a href="/perfil/'.$nick3.'" title="'.$nick3.'">'.$nick3.'</a><br/><b>A:</b> <a href="/perfil/'.$nick.'" title="'.$nick.'">'.$nick.'</a><br/><b>Mensaje:</b><br/>'.censorText(parse_bbc(str_replace("<br/>","\n",$MostrarFila2['muro']))).'<div class="hrs"></div></div>';}

$NroRegistros=mysql_num_rows(db_query("SELECT id FROM {$db_prefix}muro", __FILE__, __LINE__));
 $PagAnt=$PagAct-1;
 $PagSig=$PagAct+1;
 $PagUlt=$NroRegistros/$RegistrosAMostrar;
 $Res=$NroRegistros%$RegistrosAMostrar;
if($Res>0) $PagUlt=floor($PagUlt)+1;
if($PagAct>$PagUlt){}else{echo'<br/><b>Cantidad de escritos:</b> '.$NroRegistros.'<br/></div>';}

if($PagAct>$PagUlt){echo'<b class="size11">Est&aacute; p&aacute;gina no existe.</b><div class="hrs"></div></div>';}else{
echo'<div class="windowbgpag" style="width:747px;padding:4px;"><center><font size="2">';
if($PagAct>1) echo "<a href='/moderacion/muro/pag-$PagAnt'>< anterior</a>";
if($PagAct<$PagUlt)  echo "<a href='/moderacion/muro/pag-$PagSig'>siguiente ></a>";	
echo'</font></center></div>';}
}else{falta_error('No podes estar aca.');}}


?>