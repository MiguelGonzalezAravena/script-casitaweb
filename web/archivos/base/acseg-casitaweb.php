<?php
function template_intro() {
  exit();
  die();
}

function template_tyc17() {
  global $tranfer1, $func, $ID_MEMBER, $modSettings, $context, $db_prefix;

  ditaruser();

  $_GET['accion'] = isset($_GET['accion']) ? $_GET['accion'] : '';

if ($_GET['accion'] == 'misnotas') {

echo'<div style="float:left;width:776px;">';
$RegistrosAMostrar=10;
$NroRegistros=mysqli_num_rows(db_query("
SELECT id_user
FROM {$db_prefix}notas
WHERE id_user='{$ID_MEMBER}'", __FILE__, __LINE__)); 
$_GET['pag']=isset($_GET['pag']) ? $_GET['pag'] : '';
if($_GET['pag'] < 1){$oagvv=1;}else{$oagvv=$_GET['pag'];}
if(isset($oagvv)){$RegistrosAEmpezar=($oagvv-1)*$RegistrosAMostrar;
$PagAct=$oagvv;}else{$RegistrosAEmpezar=0;$PagAct=1;}
$PagAnt=$PagAct-1;
$PagSig=$PagAct+1;
$PagUlt=$NroRegistros/$RegistrosAMostrar;
 $Res=$NroRegistros%$RegistrosAMostrar;
 if($Res>0) $PagUlt=floor($PagUlt)+1;
 
echo'<div style="float:left;width:776px;">';
if(empty($NroRegistros)){echo'<div class="noesta" style="width:776px;">No tienes notas agregadas.</div>';}
elseif($PagAct>$PagUlt){echo'<div class="noesta" style="width:776px;">Est&aacute; p&aacute;gina no existe.</div>';}
else{echo'<table class="linksList" style="width:776px;"><thead align="center"><tr><th style="text-align:left;">Nota</th><th>Fecha</th><th>Eliminar</th></tr></thead><tbody>';

$notas=db_query("
SELECT id,fecha_creado,titulo
FROM {$db_prefix}notas
WHERE id_user='{$ID_MEMBER}'
ORDER BY id DESC 
LIMIT $RegistrosAEmpezar, $RegistrosAMostrar", __FILE__, __LINE__);
$context['posts']=array();
while($row=mysqli_fetch_assoc($notas)){
    $context['posts'][]=array(
    'id' => $row['id'],
    'titulo' => nohtml($row['titulo']),
    'fechac' => timeformat($row['fecha_creado']));}
mysqli_free_result($notas);


foreach($context['posts'] as $post){
echo'<tr><td style="text-align:left;"><a title="'.$post['titulo'].'" href="#" onclick=\'Boxy.load("/web/cw-TEMPeditarNota.php?id='.$post['id'].'", { title: "'.nohtml($post['titulo']).'"});\' >'.$post['titulo'].'</a></td>
<td title="'.$post['fechac'].'">'.$post['fechac'].'</td> <td><img alt="" title="Eliminar nota" style="width:16px;height:16px;cursor:pointer;" class="png" src="'.$tranfer1.'/comunidades/eliminar.png" onclick="Boxy.confirm(\'&iquest;Estas seguro que desea eliminar esta nota?\', function() { location.href=\'/web/cw-EliminarNota.php?id='.$post['id'].'\' }, {title: \'Eliminar nota\'}); return false;" /></td></tr>';}



echo'</tbody></table>';


if($PagAct>$PagUlt){}elseif($PagAct>1 || $PagAct<$PagUlt){
echo'<div class="windowbgpag" >';
if($PagAct>1) echo "<a href='/mis-notas/pag-$PagAnt'>&#171; anterior</a>";
if($PagAct<$PagUlt)  echo "<a href='/mis-notas/pag-$PagSig'>siguiente &#187;</a>";
echo'</div>';}}

echo'<div style="width:776px;margin-top:4px;"><p align="right" style="margin:0px;padding:0px;"><input type="button" value="Agregar nota" onclick="Boxy.load(\'/web/cw-TEMPagregarNota.php\', { title: \'Agregar nota\'});" class="boxy login" /></p></div>';
echo'<div class="clearBoth"></div></div>';
echo'</div>';
}else{die();}}

function template_tyc12(){global $tranfer1, $context,$db_prefix, $txt,$scripturl, $modSettings;
if($context['user']['name']=='rigo'||$context['user']['id']=='1'){

echo'<div class="box_757"><div class="box_title" style="width: 752px;"><div class="box_txt box_757-34"><center>Mp\'s</center></div><div class="box_rss"><img alt="" src="'.$tranfer1.'/blank.gif" style="width: 16px; height: 16px;" border="0" /></div></div></div><div style="width:744px;padding:4px;" class="windowbg"><form action="/web/cw-EliminarPMSADM.php" method="post" accept-charset="'.$context['character_set'].'" name="coments" id="coments">';

$RegistrosAMostrar=10;
if($_GET['pag-11sdasd'] < 1){$oagvv=1;}else{$oagvv=$_GET['pag-11sdasd'];}
if(isset($oagvv)){$RegistrosAEmpezar=($oagvv-1)*$RegistrosAMostrar;
$PagAct=$oagvv;}else{$RegistrosAEmpezar=0;$PagAct=1;}
$Resultado=db_query("
SELECT pm.id,pm.titulo,pm.name_de,pm.mensaje,pm.id_para
FROM ({$db_prefix}mensaje_personal as pm)
ORDER BY pm.id DESC
LIMIT $RegistrosAEmpezar, $RegistrosAMostrar", __FILE__, __LINE__);
while($MostrarFila2=mysqli_fetch_array($Resultado)){
$datosmem=db_query("
SELECT realName
FROM ({$db_prefix}members)
WHERE ID_MEMBER='{$MostrarFila2['id_para']}'
LIMIT 1", __FILE__, __LINE__);
while($data=mysqli_fetch_assoc($datosmem)){$nick=$data['realName'];}

echo'<input type="checkbox" name="campos['.$MostrarFila2['id'].']" /><br/><b>Por:</b> <a href="/perfil/'.$MostrarFila2['name_de'].'" title="'.$MostrarFila2['name_de'].'">'.$MostrarFila2['name_de'].'</a><br/><b>A:</b> <a href="/perfil/'.$nick.'" title="'.$nick.'">'.$nick.'</a><br/><b>Asunto:</b> '.censorText($MostrarFila2['titulo']).'<br/><b>Mensaje:</b><br/>'.censorText(parse_bbc(str_replace("<br/>","\n",$MostrarFila2['mensaje']))).'<div class="hrs"></div>';
}
$NroRegistros=mysqli_num_rows(db_query("SELECT id FROM {$db_prefix}mensaje_personal", __FILE__, __LINE__));
 $PagAnt=$PagAct-1;
 $PagSig=$PagAct+1;
 $PagUlt=$NroRegistros/$RegistrosAMostrar;
 $Res=$NroRegistros%$RegistrosAMostrar;
if($Res>0) $PagUlt=floor($PagUlt)+1;

if($PagAct>$PagUlt){echo'<div class="noesta">Est&aacute; p&aacute;gina no existe.</div>';}else{}	
echo'<br/><b>Cantidad de mensajes:</b> '.$NroRegistros.'<br/><span class="size10">Comentarios Seleccionados:</span> <input class="login" style="font-size: 9px;" type="submit" value="Eliminar" />
<input value="'.$PagAct.'" name="pag" type="hidden" /></form></div>';
if($PagAct<$PagUlt){echo'<div class="windowbgpag" style="width:698px;">';
if($PagAct>1)echo "<a href='/moderacion/pms/pag-$PagAnt'>&#171; anterior</a>";
 if($PagAct<$PagUlt)  echo "<a href='/moderacion/pms/pag-$PagSig'>siguiente &#187;</a>";
echo'</div><div class="clearBoth"></div>';}

}else{falta_error('No podes estar aca.');}}

function template_tyc(){global $tranfer1, $context;
echo'<script language="JavaScript" type="text/javascript">function showr_email(comment){if(comment == \'\'){alert(\'No has escrito ningun mensaje.\');return false;}}</script>';
echo'<div><div class="box_buscador"><div class="box_title" style="width: 920px;"><div class="box_txt box_buscadort"><center>Recomendar CasitaWeb! a tus amigos</center></div><div class="box_rss"><img alt="" src="'.$tranfer1.'/blank.gif" style="width: 14px;height:12px;" border="0" /></div></div><div style="width:912px;padding:4px;" class="windowbg"><center>
<form action="/web/cw-recomendarWeb.php" method="post" accept-charset="'.$context['character_set'].'">
          <br /><font class="size11"><b>Recomendar CasitaWeb! hasta a seis amigos:</b></font><br />
        <b class="size11">1 - </b><input type="text" onfocus="foco(this);" onblur="no_foco(this);" name="r_email" size="28" maxlength="60" /> <b class="size11">2 - </b><input type="text" onfocus="foco(this);" onblur="no_foco(this);" name="r_email1" size="28" maxlength="60" /><br /><b class="size11">3 - </b><input type="text" onfocus="foco(this);" onblur="no_foco(this);" name="r_email2" size="28" maxlength="60" /> <b class="size11">4 - </b><input type="text" onfocus="foco(this);" onblur="no_foco(this);" name="r_email3" size="28" maxlength="60" /><br /><b class="size11">5 - </b><input type="text" onfocus="foco(this);" onblur="no_foco(this);" name="r_email4" size="28" maxlength="60" /> <b class="size11">6 - </b><input type="text" onfocus="foco(this);" onblur="no_foco(this);" name="r_email5" size="28" maxlength="60" /><br /><br />
          <font class="size11"><b>Asunto:</b></font><br /><input size="40" name="titulo" value="Te recomiendo CasitaWeb!" type="text" onfocus="foco(this);" onblur="no_foco(this);"><br /><br />
          <font class="size11"><b>Mensaje:</b></font><br />
          <textarea cols="70" rows="8" wrap="hard" tabindex="6" name="comment">Hola! Te recomiendo que le des un vistazo a CasitaWeb! 

Saludos!

'.$context['user']['name'].'</textarea>

<br /><br /><font class="size11"><strong>C&oacute;digo de la im&aacute;gen:</strong></font><br />';
captcha(1);
echo'<br />';
echo'<input onclick="return showr_email(this.form.comment.value);" type="submit" class="login" name="send" value="Recomendar CasitaWeb!" /></form></center></div></div></div>';}
      
function template_tyc1(){global $tranfer1, $context, $settings, $options, $txt, $scripturl, $modSettings,$db_prefix,$user_info, $con, $board;
echo'<div class="box_buscador"><div class="box_title" style="width:920px;"><div class="box_txt box_buscadort"><center>Enlazanos</center></div><div class="box_rss"><img alt="" src="'.$tranfer1.'/blank.gif" style="width:14px;height:12px;" border="0"></div></div><div class="windowbg" style="width:912px;padding:4px;">

<table style="border-bottom:1px solid #B3A496;">
<tr><td style="width:125px;height:62px;margin-top:25px;"><center><a tile="CasitaWeb!" href="http://www.casitaweb.net/"><img src="/web/enlazanos/casitaweb-16x16.gif" alt="CasitaWeb!" width="16" border="0" height="16" /></a></center></td>
<td style="width:772px;height:62px;"><textarea readonly="readonly" onmouseup="this.focus(); this.select()" wrap="off" style="border:1px dashed rgb(192, 192, 192);background-color: rgb(249, 249, 249);width:772px;height:50px;font-family:arial;font-size:11px;">&lt;a title="CasitaWeb!" href="http://www.casitaweb.net/"&gt;
&lt;img src="http://casitaweb.net/web/enlazanos/casitaweb-16x16.gif" alt="CasitaWeb!" width="16" border="0" height="16" /&gt;
&lt;/a&gt;</textarea></td></tr>
</table>

<table style="border-bottom:1px solid #B3A496;">
<tr><td style="width:125px;height:62px;margin-top:25px;"><center><a tile="CasitaWeb!" href="http://www.casitaweb.net/"><img src="/web/enlazanos/casitaweb-88x31.gif" alt="CasitaWeb!" width="88" border="0" height="31" /></a></center></td>
<td style="width:772px;height:62px;"><textarea readonly="readonly" onmouseup="this.focus(); this.select()" wrap="off" style="border:1px dashed rgb(192, 192, 192);background-color: rgb(249, 249, 249);width:772px;height:50px;font-family:arial;font-size:11px;">&lt;a title="CasitaWeb!" href="http://www.casitaweb.net/"&gt;
&lt;img src="http://casitaweb.net/web/enlazanos/casitaweb-88x31.gif" alt="CasitaWeb!" width="88" border="0" height="31" /&gt;
&lt;/a&gt;</textarea></td></tr>
</table>

<table style="border-bottom:1px solid #B3A496;">
<tr><td style="width:125px;height:62px;margin-top:25px;"><center><a tile="CasitaWeb!" href="http://www.casitaweb.net/"><img src="/web/enlazanos/casitaweb-100x20.gif" alt="CasitaWeb!" width="100" border="0" height="20"/></a></center></td>
<td style="width:772px;height:62px;"><textarea readonly="readonly" onmouseup="this.focus(); this.select()" wrap="off" style="border:1px dashed rgb(192, 192, 192);background-color: rgb(249, 249, 249);width:772px;height:50px;font-family:arial;font-size:11px;">&lt;a title="CasitaWeb!" href="http://www.casitaweb.net/"&gt;
&lt;img src="http://casitaweb.net/web/enlazanos/casitaweb-100x20.gif" alt="CasitaWeb!" width="100" border="0" height="20" /&gt;
&lt;/a&gt;</textarea></td></tr>
</table>


<table>
<tr><td style="width:125px;height:62px;margin-top:25px;"><center><a tile="CasitaWeb!" href="http://www.casitaweb.net/"><img src="/web/enlazanos/casitaweb-125x125.gif" alt="CasitaWeb!" width="125" border="0" height="125" /></a></center></td>
<td style="width:772px;height:62px;"><textarea readonly="readonly" onmouseup="this.focus(); this.select()" wrap="off" style="border:1px dashed rgb(192, 192, 192);background-color: rgb(249, 249, 249);width:772px;height:50px;font-family:arial;font-size:11px;">&lt;a title="CasitaWeb!" href="http://www.casitaweb.net/"&gt;
&lt;img src="http://casitaweb.net/web/enlazanos/casitaweb-125x125.gif" alt="CasitaWeb!" width="125" border="0" height="125" /&gt;
&lt;/a&gt;</textarea></td></tr>
</table>

 </div></div>';}
 
function template_tyc2(){ global $tranfer1, $modSettings;
echo'<div style="margin-bottom:8px;width:922px;"><div class="box_title"><div class="box_txt box_buscadort">Chat</div><div class="box_rss"><img alt="" src="'.$tranfer1.'/blank.gif" style="width:14px;height:12px;" border="0" /></div></div><div style="width: 912px;padding:4px;" class="windowbg" ><embed src="http://www.xatech.com/web_gear/chat/chat.swf" quality="high" name="chat" flashvars="id=124775015&amp;rl=Argentina" allowscriptaccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://xat.com/update_flash.shtml" align="middle" height="480px" width="912px">';

if(!empty($modSettings['radio'])){
if($modSettings['radio']=='1'){echo'<center><div class="stream">
<script>window.onload=radio;
function radio(){
if(document.getElementById(\'cc_stream_info_song\').innerHTML == \'\'){
document.getElementById(\'enlinea\').innerHTML=\'<span style="color:red;">Fuera de linea</span>\';
document.getElementById(\'imgmic\').style.display=\'inline\';
document.getElementById(\'imgcar\').style.display=\'none\';}
else{document.getElementById(\'enlinea\').innerHTML=\'<span style="color:green;">En linea</span>\';
document.getElementById(\'imgmic\').style.display=\'inline\';
document.getElementById(\'imgcar\').style.display=\'none\';
document.getElementById(\'escuchando\').style.display=\'inline\';}}
</script>
<span id="escuchando" style="display:none;"><img src="'.$tranfer1.'/icons/microfono.png" alt="" /> <a  href="/chat/" id="cc_stream_info_song"></a><br /></span>
<span id="linea"><img src="'.$tranfer1.'/icons/microfono.png" alt="" style="display:none;" id="imgmic" /><img src="'.$tranfer1.'/icons/cargando.gif" id="imgcar" alt="" /> <span style="font-weight:bold;" id="enlinea"></span></span>
<script language="javascript" type="text/javascript" src="http://cp.internet-radio.org.uk/system/streaminfo.js"></script>
<script language="javascript" type="text/javascript" src="http://cp.internet-radio.org.uk/js.php/camilo62/streaminfo/rnd0"></script>
<object type="application/x-shockwave-flash" data="http://fmcasita.net/utilidades/player_mp3_maxi.swf" width="266" height="20">
    <param name="wmode" value="transparent" />
    <param name="movie" value="http://fmcasita.net/utilidades/player_mp3_maxi.swf" />
    <param name="bgcolor" value="#ffffff" />
    <param name="FlashVars" value="mp3=http%3A//77.92.68.221%3A15393/%3B&amp;showvolume=1&amp;width=266&amp;showloading=always&amp;bgcolor1=CDC3B8&amp;bgcolor2=CDC3B8&amp;slidercolor1=FFC703&amp;slidercolor2=FFC703" />
</object></div><img alt="" src="'.$tranfer1.'/icons/radio-cw.gif" /> <b class="size11">Ir a <a target="_blank" href="http://fmcasita.net">FMcasita.net</a> - Radio oficial de CasitaWeb!</b></center>';}
elseif($modSettings['radio']=='2'){
echo'<center>
<embed type="application/x-mplayer2" pluginspace="http://www.microsoft.com/isapi/redir.dll?prd=windows&amp;sbp=mediaplayer&amp;ar=Media&amp;sba=Plugin&amp;" wmode="transparent" filename="mms://201.212.0.128/horaprima" name="WMPlay" autostart="0" showcontrols="1" showdisplay="0" showstatusbar="0" autosize="0" displaysize="0" width="280" height="45">
<br /><img alt="" src="'.$tranfer1.'/icons/radio-cw.gif" /> <b class="size11">Ir a <a target="_blank" href="http://perdidosenbabylon.com">Perdidos en babylon!</a> - Web oficial<br /><img alt="" src="http://fmcasita.net/utilidades/2.png" /> <a href="mms://201.212.0.128/horaprima">Escuchar en Windows media player</a></b></center>';}}
echo'</div></div><div class="aparence" style="width: 922px;margin:0px;"><h3 class="titlesCom" onclick="chgsec(this)">Protocolo del chat</h3><div class="active" id="contennnt" style="width: 922px;" ><font class="size12"><b>1.</b> No se permite el uso de Nicks que contengan t&eacute;rminos insultantes, sexuales, apolog&iacute;as a la violencia o alg&uacute;n pedido de car&aacute;cter sexual, compa&ntilde;&iacute;a, parejas y/o a fines.<br /><br /><b>2.</b> Est&aacute; prohibido faltar el respeto, insultar, provocar, difamar, acosar, amenazar o hacer cualquier otra cosa no deseada, tanto directa como indirecta a otro usuario.<br /><br /><b>3.</b> No est&aacute; permitido el SPAM, publicidad o propaganda de p&aacute;ginas personales, chats, foros, mensajes comerciales destinados a vender productos o servicios, etc.<br /><br /><b>4.</b> No repetir o enviar varias l&iacute;neas de texto en un cierto tiempo, NO FLOOD.<br /><br /><b>5.</b> Recomendamos no abusar de las MAY&Uacute;SCULAS, solo utilizarlas por reglas ortograficas (Comienzos de oraci&oacute;n, nombres propios o siglas), ya que el uso de &eacute;sta significa GRITAR.</font><br />
<p style="padding:0px;margin:0px;" align="right"><i>Este protocolo es solo para el chat, para la Web en general existe otro <a href="/protocolo/">protocolo</a>.</i></p></div></div>';}


function template_tyc3() {
  global $tranfer1, $modSettings;

  if (!$modSettings['requireAgreement']) {
    fatal_error('Los T&eacute;rminos y Condiciones no est&aacute;n habilitados.-', false);
  } else {
    echo '
      <div>
        <div class="box_buscador">
          <div class="box_title" style="width: 920px;">
            <div class="box_txt box_buscadort"><center>T&eacute;rminos y Condiciones</center></div>
            <div class="box_rss"><img alt="" src="' . $tranfer1 . '/blank.gif" style="width: 14px; height: 12px;" border="0" /></div>
          </div>
          <div class="windowbg" style="width: 912px; padding: 4px;"><center>' . $modSettings['terminos'] . '</center></div>
        </div>
      </div>';
  }
}


function template_tyc5(){
global $tranfer1;
echo'<div class="box_buscador"><div class="box_title" style="width: 920px;"><div class="box_txt box_buscadort"><center>Protocolo</center></div><div class="box_rss"><img alt="" src="'.$tranfer1.'/blank.gif" style="width:14px;height:12px;" border="0" /></div></div><div class="windowbg" style="width:912px;padding:4px;">

<div class="codePro"><b>Introducci&oacute;n:</b>
<div class="codePro1">
<b>CasitaWeb!</b> es un sitio de entretenimiento para usuarios de habla hispana en el cual los usuarios comparten informaci&oacute;n de diversas tem&aacute;ticas (links, im&aacute;genes, noticias, videos, etc.) por medio de posts.<br /><b>CasitaWeb!</b> es una Web, fue creada con la idea de responder a consultas o debatir temas como tal comunidad.<br />Los moderadores son los encargados de filtrar, eliminar o editar la informaci&oacute;n que se comparte, de esta forma se evita que el contenido se transforme en una gran cantidad de "nada" y siempre se mantenga con la mejor calidad posible. Existen reglas en las cuales se basa la filosof&iacute;a de administraci&oacute;n llamado protocolo y se detalla a continuaci&oacute;n.</div></div>

<div class="codePro"><b>Protocolo:</b>
<div class="codePro1">
<span class="size12" align="left">
<p style="margin:0px;padding:0px;padding-left:10px;"><img src="'.$tranfer1.'/icons/si.png" width="16px" height="16px" class="png" alt="" /><b> Caracter&iacute;sticas para postear:</b></p>
<p style="margin:0px;padding:0px;"><img src="'.$tranfer1.'/icons/no.png" width="16px" height="16px" class="png" alt="" /> Asunto sin MAY&Uacute;SCULAS (ya que esto &iacute;ndica que se est&aacute; gritando).</p>
<p style="margin:0px;padding:0px;"><img src="'.$tranfer1.'/icons/no.png" width="16px" height="16px" class="png" alt="" /> Ser lo m&aacute;s descriptivo o lo m&aacute;s claro posible.</p>
<p style="margin:0px;padding:0px;"><img src="'.$tranfer1.'/icons/no.png" width="16px" height="16px" class="png" alt="" /> Postear temas en la categor&iacute;a correspondiente.</p>
<p style="margin:0px;padding:0px;"><img src="'.$tranfer1.'/icons/no.png" width="16px" height="16px" class="png" alt="" /> Fijarse que los links funcionen correctamente.</p>
<p style="margin:0px;padding:0px;"><img src="'.$tranfer1.'/icons/no.png" width="16px" height="16px" class="png" alt="" /> No revelar Informaci&oacute;n personal propia o de terceros tales como e-mail, MSN, nombres, apellidos, tel&eacute;fonos, etc. (CasitaWeb! no se hace cargo de problemas al publicar tal contenido)</p>

<p style="margin:0px;padding:0px;"><i><b>Nota:</b> Las caracter&iacute;sticas no representa que el post sea Eliminado, o se bane&eacute; al usuario que lo creo, lo que si representa es que a cualquier Moderador le da el derecho de editar tal post.</i></p>

<br />


<p style="margin:0px;padding:0px;padding-left:10px;"><img src="'.$tranfer1.'/icons/si.png" width="16px" height="16px" class="png" alt="" /><b> Se eliminan los post:</b></p>
<p style="margin:0px;padding:0px;"><img src="'.$tranfer1.'/icons/no.png" width="16px" height="16px" class="png" alt="" /> Que este considerado SPAM.</p>
<p style="margin:0px;padding:0px;"><img src="'.$tranfer1.'/icons/no.png" width="16px" height="16px" class="png" alt="" /> Que sea Re-Post.</p>
<p style="margin:0px;padding:0px;"><img src="'.$tranfer1.'/icons/no.png" width="16px" height="16px" class="png" alt="" /> Que contenga un vocabulario vulgar.</p>
<p style="margin:0px;padding:0px;"><img src="'.$tranfer1.'/icons/no.png" width="16px" height="16px" class="png" alt="" /> Que haga referencia a la violaci&oacute;n de los <a href="/post/44266/informes/derechos_humanos.html" target="_blank" title="Derechos Humanos">derechos humanos</a>.</p>
<p style="margin:0px;padding:0px;"><img src="'.$tranfer1.'/icons/no.png" width="16px" height="16px" class="png" alt="" /> Que tenga enlaces rotos.</p>
<p style="margin:0px;padding:0px;"><img src="'.$tranfer1.'/icons/no.png" width="16px" height="16px" class="png" alt="" /> Que no contenga la fuente (Solo para categor&iacute;a <i>Noticias</i>).</p>
<p style="margin:0px;padding:0px;"><img src="'.$tranfer1.'/icons/no.png" width="16px" height="16px" class="png" alt="" /> Que contenga material pornogr&aacute;fico (Im&aacute;genes, Videos, Enlaces, etc.).</p>
<br />


<p style="margin:0px;padding:0px;padding-left:10px;"><img src="'.$tranfer1.'/icons/si.png" width="16px" height="16px" class="png" alt="" /><b> Se eliminan los comentarios:</b></p>
<p style="margin:0px;padding:0px;"><img src="'.$tranfer1.'/icons/no.png" width="16px" height="16px" class="png" alt="" /> Que contenga tipograf&iacute;as muy grandes, abuso de may&uacute;sculas o con el claro efecto de llamar la atenci&oacute;n.</p>
<p style="margin:0px;padding:0px;"><img src="'.$tranfer1.'/icons/no.png" width="16px" height="16px" class="png" alt="" /> Genera discusiones (ForoBardo).</p>
<p style="margin:0px;padding:0px;"><img src="'.$tranfer1.'/icons/no.png" width="16px" height="16px" class="png" alt="" /> Que contengan insultos, ofensas, etc. (hacia otro usuario o de forma general).</p>
<p style="margin:0px;padding:0px;"><img src="'.$tranfer1.'/icons/no.png" width="16px" height="16px" class="png" alt="" /> Que sea un comentario racista.</p>
<p style="margin:0px;padding:0px;"><img src="'.$tranfer1.'/icons/no.png" width="16px" height="16px" class="png" alt="" /> Que contenga SPAM.</p>
<br />
<p style="margin:0px;padding:0px;padding-left:10px;"><img src="'.$tranfer1.'/icons/si.png" width="16px" height="16px" class="png" alt="" /><b> Se banea al usuario:</b></p>
<p style="margin:0px;padding:0px;"><img src="'.$tranfer1.'/icons/no.png" width="16px" height="16px" class="png" alt="" /> Que reiteradas veces hagan lo que no deben hacer.</p>
<p style="margin:0px;padding:0px;"><img src="'.$tranfer1.'/icons/no.png" width="16px" height="16px" class="png" alt="" /> Que genera SPAM</p>
<p style="margin:0px;padding:0px;"><img src="'.$tranfer1.'/icons/no.png" width="16px" height="16px" class="png" alt="" /> Que elimina sus comentarios o posts en totalidad (Para hacerlo <a href="/contactanos/" target="_blank" title="Contactar">contactar</a> y dar los motivos).</p>
<p style="margin:0px;padding:0px;"><img src="'.$tranfer1.'/icons/no.png" width="16px" height="16px" class="png" alt="" /> Que postea material pornogr&aacute;fico o material con morboso</p>
<br />


<p style="margin:0px;padding:0px;padding-left:10px;"><img src="'.$tranfer1.'/icons/si.png" width="16px" height="16px" class="png" alt="" /><b> Se eliminan o modifica las im&aacute;genes:</b></p>
<p style="margin:0px;padding:0px;"><img src="'.$tranfer1.'/icons/no.png" width="16px" height="16px" class="png" alt="" /> Que contenga SPAM (Imagen con enlace de un sitio)</p>
<p style="margin:0px;padding:0px;"><img src="'.$tranfer1.'/icons/no.png" width="16px" height="16px" class="png" alt="" /> Que contenga logos de Webs y tapas (Programas, CD de m&uacute;sica, etc.)</p>
<p style="margin:0px;padding:0px;"><img src="'.$tranfer1.'/icons/no.png" width="16px" height="16px" class="png" alt="" /> Que contenga pornograf&iacute;a.</p>
<p style="margin:0px;padding:0px;"><img src="'.$tranfer1.'/icons/no.png" width="16px" height="16px" class="png" alt="" /> Que sea morbosa.</p>

 <br />
 
<p style="margin:0px;padding:0px;padding-left:10px;"><img src="'.$tranfer1.'/icons/si.png" width="16px" height="16px" class="png" alt="" /><b> Caracter&iacute;sticas para Crear una Comunidad:</b></p>
<p style="margin:0px;padding:0px;"><img src="'.$tranfer1.'/icons/no.png" width="16px" height="16px" class="png" alt="" /> No utilizar titulo entero en MAYUSCULAS (ya que &iacute;ndica que se est&aacute; gritando). </p>
<p style="margin:0px;padding:0px;"><img src="'.$tranfer1.'/icons/no.png" width="16px" height="16px" class="png" alt="" /> Ser lo m&aacute;s descriptivo o lo m&aacute;s claro posible.</p>
<p style="margin:0px;padding:0px;"><img src="'.$tranfer1.'/icons/no.png" width="16px" height="16px" class="png" alt="" /> No revelar Informaci&oacute;n personal propia o de terceros tales como e-mail, MSN, nombres, apellidos, tel&eacute;fonos, etc.</p>
<p style="margin:0px;padding:0px;"><img src="'.$tranfer1.'/icons/no.png" width="16px" height="16px" class="png" alt="" /> Cada usuario tiene limitado las cantidades de comunidades que puede crear.</p>
<p style="margin:0px;padding:0px;"><i><b>Nota:</b> Las caracter&iacute;sticas no representa que la comunidad sea Eliminada, o suspenda al usuario que la creo, lo que representa es que a cualquier Moderador le da el derecho de editar tal comunidad.</i></p>
<br />

<p style="margin:0px;padding:0px;padding-left:10px;"><img src="'.$tranfer1.'/icons/si.png" width="16px" height="16px" class="png" alt="" /><b> Se eliminan las comunidades:</b></p>
<p style="margin:0px;padding:0px;"><img src="'.$tranfer1.'/icons/no.png" width="16px" height="16px" class="png" alt="" /> Que haya sido creada con el &uacute;nico objetivo de hacer  SPAM y/o REFERER.</p>
<p style="margin:0px;padding:0px;"><img src="'.$tranfer1.'/icons/no.png" width="16px" height="16px" class="png" alt="" /> Que contenga un vocabulario vulgar.</p>
<p style="margin:0px;padding:0px;"><img src="'.$tranfer1.'/icons/no.png" width="16px" height="16px" class="png" alt="" /> Que haga referencia a la violaci&oacute;n de los derechos humanos.</p>
<p style="margin:0px;padding:0px;"><img src="'.$tranfer1.'/icons/no.png" width="16px" height="16px" class="png" alt="" /> Que contenga material pornogr&aacute;fico (Im&aacute;genes, Videos, Enlaces, etc.) </p>
<p style="margin:0px;padding:0px;"><img src="'.$tranfer1.'/icons/no.png" width="16px" height="16px" class="png" alt="" /> De un mismo tema, creadas por el mismo usuario.</p>
<div style="clear: both;"></div></span></div></div>
</div></div>';}

















//////////////////

function template_tyc23() {
  global $context, $db_prefix, $boardurl;

  ditaruser();

  $refoagr = db_query("
    SELECT i.*
    FROM {$db_prefix}infop
    WHERE id_user = '{$context['user']['id']}'
    LIMIT 1", __FILE__, __LINE__);

while($mddd=mysqli_fetch_array($refoagr)){
$hp=$mddd['habilidades_profesionales'];
$ip=$mddd['intereses_profesionales'];
$ingresos=$mddd['nivel_de_ingresos'];
$emp=$mddd['empresa'];
$estudios=$mddd['estudios'];
$prof=$mddd['profesion'];
$me_gustaria=$mddd['me_gustaria'];
$hijos=$mddd['hijos'];
$en_el_amor_estoy=$mddd['en_el_amor_estoy'];
$altura=$mddd['altura'];
$peso=$mddd['peso'];
$color_de_pelo=$mddd['color_de_pelo'];
$color_de_ojos=$mddd['color_de_ojos'];
$complexion=$mddd['complexion'];
$mi_dieta_es=$mddd['mi_dieta_es'];
$fumo=$mddd['fumo'];
$tomo_alcohol=$mddd['tomo_alcohol'];


if(!$estudios){$texto='';}
if($estudios=='Sin Estudios'){$texto='sin';}
if($estudios=='Primario completo'){$texto='pri';}
if($estudios=='Secundario en curso'){$texto='sec_curso';}
if($estudios=='Secundario completo'){$texto='sec_completo';}
if($estudios=='Terciario en curso'){$texto='ter_curso';}
if($estudios=='Universitario en curso'){$texto='univ_curso';}
if($estudios=='Universitario completo'){$texto='univ_completo';}
if($estudios=='Terciario completo'){$texto='ter_completo';}
if($estudios=='Post-grado en curso'){$texto='post_curso';}
if($estudios=='Post-grado completo'){$texto='post_completo';}
if(!$ingresos){$texto2='';}
if($ingresos=='Sin ingresos'){$texto2='sin';}
if($ingresos=='Bajos'){$texto2='bajos';}
if($ingresos=='Intermedios'){$texto2='intermedios';}
if($ingresos=='Altos'){$texto2='altos';}

if(!$me_gustaria){$texto3='';}
elseif($me_gustaria=='Hacer Amigos'){$texto3='hacer_amigos';}
elseif($me_gustaria=='Conocer gente con mis intereses'){$texto3='conocer_gente_con_mis_intereses';}
elseif($me_gustaria=='Conocer gente para hacer negocios'){$texto3='conocer_gente_para_hacer_negocios';}
elseif($me_gustaria=='Encontrar pareja'){$texto3='encontrar_pareja';}
elseif($me_gustaria=='De todo'){$texto3='de_todo';}
else{$texto3='';}

if(!$en_el_amor_estoy){$texto4='';}
elseif($en_el_amor_estoy=='Soltero/a'){$texto4='soltero';}
elseif($en_el_amor_estoy=='De novio/a'){$texto4='novio';}
elseif($en_el_amor_estoy=='Casado/a'){$texto4='casado';}
elseif($en_el_amor_estoy=='Divorciado/a'){$texto4='divorciado';}
elseif($en_el_amor_estoy=='Viudo/a'){$texto4='viudo';}
elseif($en_el_amor_estoy=='En algo...'){$texto4='algo';}
else{$texto4='';}

if(!$hijos){$texto5='';}
elseif($hijos=='No tengo'){$texto5='no';}
elseif($hijos=='Alg&uacute;n d&iacute;a'){$texto5='algun_dia';}
elseif($hijos=='No son lo m&iacute;o'){$texto5='no_quiero';}
elseif($hijos=='Tengo, vivo con ellos'){$texto5='viven_conmigo';}
elseif($hijos=='Tengo, no vivo con ellos'){$texto5='no_viven_conmigo';}
else{$texto5='';}


if(!$fumo){$texto7='';}
elseif($fumo=='No'){$texto7='no';}
elseif($fumo=='Casualmente'){$texto7='casualmente';}
elseif($fumo=='Socialmente'){$texto7='socialmente';}
elseif($fumo=='Regularmente'){$texto7='regularmente';}
elseif($fumo=='Mucho'){$texto7='mucho';}else{$texto7='';}

if($tomo_alcohol==''){$texto8='';}
elseif($tomo_alcohol=='No'){$texto8='no';}
elseif($tomo_alcohol=='Casualmente'){$texto8='casualmente';}
elseif($tomo_alcohol=='Socialmente'){$texto8='socialmente';}
elseif($tomo_alcohol=='Regularmente'){$texto8='regularmente';}
elseif($tomo_alcohol=='Mucho'){$texto8='mucho';}else{$texto8='';}

if(!$color_de_pelo){$texto9='';}
elseif($color_de_pelo=='Negro'){$texto9='negro';}
elseif($color_de_pelo=='Casta&ntilde;o oscuro'){$texto9='castano_oscuro';}
elseif($color_de_pelo=='Casta&ntilde;o claro'){$texto9='castano_claro';}
elseif($color_de_pelo=='Rubio'){$texto9='rubio';}
elseif($color_de_pelo=='Pelirrojo'){$texto9='pelirrojo';}
elseif($color_de_pelo=='Gris'){$texto9='gris';}
elseif($color_de_pelo=='Canoso'){$texto9='canoso';}
elseif($color_de_pelo=='Te&ntilde;ido'){$texto9='tenido';}
elseif($color_de_pelo=='Rapado'){$texto9='rapado';}
elseif($color_de_pelo=='Calvo'){$texto9='calvo';}else{$texto9='';}

if(!$color_de_ojos){$texto10='';}
elseif($color_de_ojos=='Negros'){$texto10='negros';}
elseif($color_de_ojos=='Marrones'){$texto10='marrones';}
elseif($color_de_ojos=='Celestes'){$texto10='celestes';}
elseif($color_de_ojos=='Verdes'){$texto10='verdes';}
elseif($color_de_ojos=='Grises'){$texto10='grises';}else{$texto10='';}

if(!$mi_dieta_es){$texto6='';}
elseif($mi_dieta_es=='Vegetariana'){$texto6='vegetariana';}
elseif($mi_dieta_es=='Lacto Vegetariana'){$texto6='lacto_vegetariana';}
elseif($mi_dieta_es=='Org&aacute;nica'){$texto6='organica';}
elseif($mi_dieta_es=='De todo'){$texto6='de_todo';}
elseif($mi_dieta_es=='Comida basura'){$texto6='comida_basura';}else{$texto6='';}

if(!$complexion){$texto11='';}
elseif($complexion=='Delgado/a'){$texto11='delgado';}
elseif($complexion=='Atl&eacute;tico'){$texto11='atletico';}
elseif($complexion=='Normal'){$texto11='normal';}
elseif($complexion=='Algunos kilos de m&aacute;s'){$texto11='kilos_de_mas';}	
elseif($complexion=='Corpulento/a'){$texto11='corpulento';}else{$texto11='';}

$hobbies=censorText($mddd['hobbies']);
$series_de_tv_favorita=censorText($mddd['series_de_tv_favorita']);
$musica_favorita=censorText($mddd['musica_favorita']);
$deportes_y_equipos_favoritos=censorText($mddd['deportes_y_equipos_favoritos']);
$libros_favoritos=censorText($mddd['libros_favoritos']);
$mis_intereses=censorText($mddd['mis_intereses']);
$pel�culas_favoritas=censorText($mddd['peliculas_favoritas']);
$comida_favor�ta=censorText($mddd['comida_favorita']);
$mis_heroes_son=censorText($mddd['mis_heroes_son']);
}
mysqli_free_result($refoagr);

$pasos=isset($_GET['paso']) ? (int)$_GET['paso'] : '';
if($pasos=='1'){
$pasoabierto2='';$pasoabierto2a=' style="display: none;"';
$pasoabierto3='';$pasoabierto3a=' style="display: none;"';
$pasoabierto4='';$pasoabierto4a=' style="display: none;"';
$pasoabierto1='titlesCom2';$pasoabierto1a='';}
elseif($pasos=='2'){
$pasoabierto1='';$pasoabierto1a=' style="display: none;"';
$pasoabierto3='';$pasoabierto3a=' style="display: none;"';
$pasoabierto4='';$pasoabierto4a=' style="display: none;"';
$pasoabierto2='titlesCom2';$pasoabierto2a='';}
elseif($pasos=='3'){
$pasoabierto1='';$pasoabierto1a=' style="display: none;"';
$pasoabierto2='';$pasoabierto2a=' style="display: none;"';
$pasoabierto4='';$pasoabierto4a=' style="display: none;"';
$pasoabierto3='titlesCom2';$pasoabierto3a='';}
elseif($pasos=='4'){
$pasoabierto1='';$pasoabierto1a=' style="display: none;"';
$pasoabierto2='';$pasoabierto2a=' style="display: none;"';
$pasoabierto3='';$pasoabierto3a=' style="display: none;"';
$pasoabierto4='titlesCom2';$pasoabierto4a='';}
else{
$pasoabierto1='';$pasoabierto1a=' style="display: none;"';
$pasoabierto2='';$pasoabierto2a=' style="display: none;"';
$pasoabierto3='';$pasoabierto3a=' style="display: none;"';
$pasoabierto4='';$pasoabierto4a=' style="display: none;"';}

echo'<div class="aparence" style="float:left;margin-bottom:8px;width:776px;">
<div class="noesta-am">Al editar mi apariencia tambi&eacute;n acepto los <a href="' . $boardurl . '/terminos-y-condiciones/" target="_blank">T&eacute;rminos de uso</a>.</div>

<h3 class="titlesCom '.$pasoabierto1.'" style="width: 762px;" onclick="chgsec(this)">1. Formaci&oacute;n y trabajo</h3>
<div class="active" id="contennnt"'.$pasoabierto1a.'>';
echo'<form action="/accion-apariencia/paso1/" method="post" accept-charset="'.$context['character_set'].'" enctype="multipart/form-data"><table cellpadding="4" width="100%"><tbody><tr><td align="right" valign="top" width="23%">
<b>Estudios:</b></td><td width="40%"><select id="estudios" name="estudios"><option '; if(!$texto){echo'selected="selected"';} echo' value="">Sin Respuesta</option><option '; if($texto=='sin'){echo'selected="selected"';} echo' value="sin">Sin Estudios</option><option '; if($texto=='pri'){echo'selected="selected"';} echo' value="pri">Primario completo</option><option '; if($texto=='sec_curso'){echo'selected="selected"';} echo' value="sec_curso">Secundario en curso</option><option '; if($texto=='sec_completo'){echo'selected="selected"';} echo' value="sec_completo">Secundario completo</option><option '; if($texto=='ter_curso'){echo'selected="selected"';} echo' value="ter_curso">Terciario en curso</option><option '; if($texto=='ter_completo'){echo'selected="selected"';} echo' value="ter_completo">Terciario completo</option><option '; if($texto=='univ_curso'){echo'selected="selected"';} echo' value="univ_curso">Universitario en curso</option><option '; if($texto=='univ_completo'){echo'selected="selected"';} echo' value="univ_completo">Universitario completo</option><option '; if($texto=='post_curso'){echo'selected="selected"';} echo' value="post_curso">Post-grado en curso</option><option value="post_completo">Post-grado completo</option></select></td></tr><tr><td align="right" valign="top" width="23%"><b>Profesi&oacute;n:</b></td><td width="40%"><input size="30" maxlength="32" name="profesion" id="profesion" value="'.$prof.'" type="text" onfocus="foco(this);" onblur="no_foco(this);" /></td></tr><tr><td align="right" valign="top"><b>Empresa:</b></td><td><input size="30" maxlength="32" name="empresa" id="empresa" value="'.$emp.'" type="text" onfocus="foco(this);" onblur="no_foco(this);" /></td></tr><tr><td align="right" valign="top"><b>Nivel de ingresos:</b></td><td><select id="ingresos" name="ingresos"><option '; if($texto==''){echo'selected="selected"';} echo' value="">Sin Respuesta</option><option '; if($texto=='sin'){echo'selected="selected"';} echo' value="sin">Sin ingresos</option><option '; if($texto=='bajos'){echo'selected="selected"';} echo' value="bajos">Bajos</option><option '; if($texto=='intermedios'){echo'selected="selected"';} echo' value="intermedios">Intermedios</option><option '; if($texto=='altos'){echo'selected="selected"';} echo' value="altos">Altos</option></select></td></tr><tr><td align="right" valign="top"><b>Intereses Profesionales:</b></td><td><textarea name="intereses_profesionales" cols="30" rows="5" id="intereses_profesionales" onfocus="foco(this);" onblur="no_foco(this);">'.$ip.'</textarea></td></tr><tr><td align="right" valign="top"><b>Habilidades Profesionales:</b></td><td><textarea name="habilidades_profesionales" cols="30" rows="5" id="habilidades_profesionales" onfocus="foco(this);" onblur="no_foco(this);">'.$hp.'</textarea></td></tr><tr><td colspan="3" align="right"><div class="hrs"></div><input class="button" style="font-size: 15px;" value="Editar mi apariencia" title="Editar mi apariencia" type="submit" name="enviar-265" /><input value="1" type="hidden" name="tipo" /></td></tr></tbody></table></form></div>


<h3 class="titlesCom '.$pasoabierto2.'" style="width: 762px;" onclick="chgsec(this)">2. M&aacute;s sobre mi</h3>
<div class="active" id="contennnt" '.$pasoabierto2a.'>

<form action="/accion-apariencia/paso2/" method="post" accept-charset="'.$context['character_set'].'" enctype="multipart/form-data"><table width="100%" cellpadding="4"><tbody><tr><td valign="top" width="23%" align="right"><b>Me gustar&iacute;a:</b></td><td width="40%"><table width="100%" border="0"><tbody><tr><td><label for="me_gustaria"><input '; if(!$texto3){echo'checked="checked"';} echo' name="me_gustaria" id="me_gustaria" value="" type="radio" /> Sin Respuesta</label></td></tr>

<tr><td><label for="me_gustaria2"><input ';if($texto3=='hacer_amigos'){echo'checked="checked"';}echo' name="me_gustaria" id="me_gustaria2" value="hacer_amigos" type="radio" /> Hacer Amigos</td></tr><tr>

<td><label for="me_gustaria3"><input '; if($texto3=='conocer_gente_con_mis_intereses'){echo'checked="checked"';} echo' name="me_gustaria" id="me_gustaria3" value="conocer_gente_con_mis_intereses" type="radio" /> Conocer gente con mis intereses</label></td></tr>

<tr><td><label for="me_gustaria4"><input '; if($texto3=='conocer_gente_para_hacer_negocios'){echo'checked="checked"';} echo' name="me_gustaria" id="me_gustaria4" value="conocer_gente_para_hacer_negocios" type="radio" /> Conocer gente para hacer negocios</label></td></tr>

<tr><td><label for="me_gustaria5"><input '; if($texto3=='encontrar_pareja'){echo'checked="checked"';} echo' name="me_gustaria" id="me_gustaria5" value="encontrar_pareja" type="radio" /> Encontrar pareja</label></td></tr>

<tr><td><label for="me_gustaria6"><input '; if($texto3=='de_todo'){echo'checked="checked"';} echo' name="me_gustaria" id="me_gustaria6" value="de_todo" type="radio" /> De todo</label></td></tr></tbody></table></tr>


<tr><td valign="top" align="right"><b>En el amor estoy:</b></td><td><table width="100%" border="0"><tbody>
<tr><td><label for="estado"><input '; if(!$texto4){echo'checked="checked"';} echo' name="estado" id="estado" value="" type="radio" /> Sin Respuesta</label></td></tr>

<tr><td><label for="estado2"><input '; if($texto4=='soltero'){echo'checked="checked"';} echo' name="estado" id="estado2" value="soltero" type="radio" /> Soltero/a</label></td></tr>

<tr><td><label for="estado3"><input '; if($texto4=='novio'){echo'checked="checked"';} echo' name="estado" id="estado3" value="novio" type="radio" /> De novio/a</label></td></tr>

<tr><td><label for="estado4"><input '; if($texto4=='casado'){echo'checked="checked"';} echo' name="estado" id="estado4" value="casado" type="radio" /> Casado/a</label></td></tr>

<tr><td><label for="estado5"><input '; if($texto4=='divorciado'){echo'checked="checked"';} echo' name="estado" id="estado5" value="divorciado" type="radio" /> Divorciado/a</label></td></tr>

<tr><td><label for="estado6"><input '; if($texto4=='viudo'){echo'checked="checked"';} echo' name="estado" id="estado6" value="viudo" type="radio" /> Viudo/a</label></td></tr>

<tr><td><label for="estado7"><input '; if($texto4=='algo'){echo'checked="checked"';} echo' name="estado" id="estado7" value="algo" type="radio" /> En algo...</label></td></tr></tbody></table></tr>

<tr><td valign="top" width="23%" align="right"><b>Hijos:</b></td><td width="40%"><table width="100%" border="0"><tbody>
<tr><td><label for="hijos"><input '; if(!$texto5){echo'checked="checked"';} echo' name="hijos" id="hijos" value="" type="radio" /> Sin Respuesta</label></td></tr>

<tr><td><label for="hijos2"><input '; if($texto5=='no'){echo'checked="checked"';} echo' name="hijos" id="hijos2" value="no" type="radio" /> No tengo</label></td></tr>

<tr><td><label for="hijos3"><input '; if($texto5=='algun_dia'){echo'checked="checked"';} echo' name="hijos" id="hijos3" value="algun_dia" type="radio" /> Alg&uacute;n d&iacute;a</label></td></tr>

<tr><td><label for="hijos4"><input '; if($texto5=='no_quiero'){echo'checked="checked"';} echo' name="hijos" id="hijos4" value="no_quiero" type="radio" /> No son lo m&iacute;o</label></td></tr>

<tr><td><label for="hijos5"><input '; if($texto5=='viven_conmigo'){echo'checked="checked"';} echo' name="hijos" id="hijos5" value="viven_conmigo" type="radio" /> Tengo, vivo con ellos</label></td></tr>

<tr><td><label for="hijos6"><input '; if($texto5=='no_viven_conmigo'){echo'checked="checked"';} echo' name="hijos" id="hijos6" value="no_viven_conmigo" type="radio" /> Tengo, no vivo con ellos</label></td></tr></tbody></table>

</td></tr>
<tr><td colspan="3" align="right"><div class="hrs"></div><input class="button" style="font-size: 15px;" value="Editar mi apariencia" title="Editar mi apariencia" type="submit" name="enviar-265" /><input value="2" type="hidden" name="tipo" /></td></tr></tbody></table></form>
</div>


<h3 class="titlesCom '.$pasoabierto3.'" style="width: 762px;" onclick="chgsec(this)">3. Como soy</h3>
<div class="active" id="contennnt" '.$pasoabierto3a.'>
<form action="/accion-apariencia/paso3/" method="post" accept-charset="'.$context['character_set'].'" enctype="multipart/form-data" style="margin:0px;padding:0px;"><table width="100%" cellpadding="4"><tbody><tr><td align="right" width="23%"><b>Mi altura:</b></td><td width="40%"><input name="altura" id="altura" size="3" maxlength="3" type="text" onfocus="foco(this);" onblur="no_foco(this);" value="'.str_replace('0','',$altura).'" /> centimetros</td></tr><tr><td align="right"><b>Mi peso:</b></td><td><input name="peso" id="peso" size="3" maxlength="3" type="text" onfocus="foco(this);" onblur="no_foco(this);" value="'.str_replace('0','',$peso).'" /> kilos</td></tr><tr><td align="right" width="23%"><b>Color de pelo:</b></td><td width="40%"><select id="pelo_color" name="pelo_color"><option '; if(!$texto9){echo'selected="selected"';} echo' value="">Sin Respuesta</option><option '; if($texto9=='negro'){echo'selected="selected"';} echo' value="negro">Negro</option><option '; if($texto9=='castano_oscuro'){echo'selected="selected"';} echo' value="castano_oscuro">Casta&ntilde;o oscuro</option><option '; if($texto9=='castano_claro'){echo'selected="selected"';} echo' value="castano_claro">Casta&ntilde;o claro</option><option '; if($texto9=='rubio'){echo'selected="selected"';} echo' value="rubio">Rubio</option><option '; if($texto9=='pelirrojo'){echo'selected="selected"';} echo' value="pelirrojo">Pelirrojo</option><option '; if($texto9=='gris'){echo'selected="selected"';} echo' value="gris">Gris</option><option '; if($texto9=='canoso'){echo'selected="selected"';} echo' value="canoso">Canoso</option><option '; if($texto9=='tenido'){echo'selected="selected"';} echo' value="tenido">Te&ntilde;ido</option><option '; if($texto9=='rapado'){echo'selected="selected"';} echo' value="rapado">Rapado</option><option '; if($texto9=='calvo'){echo'selected="selected"';} echo' value="calvo">Calvo</option></select></td></tr><tr><td align="right"><b>Color de ojos:</b></td><td><select id="ojos_color" name="ojos_color"><option '; if(!$texto10){echo'selected="selected"';} echo' value="">Sin Respuesta</option><option '; if($texto10=='negros'){echo'selected="selected"';} echo' value="negros">Negros</option><option '; if($texto10=='marrones'){echo'selected="selected"';} echo' value="marrones">Marrones</option><option '; if($texto10=='celestes'){echo'selected="selected"';} echo' value="celestes">Celestes</option><option '; if($texto10=='verdes'){echo'selected="selected"';} echo' value="verdes">Verdes</option><option '; if($texto10=='grises'){echo'selected="selected"';} echo' value="grises">Grises</option></select></td></tr><tr><td align="right"><b>Complexi&oacute;n:</b></td><td><select id="fisico" name="fisico"><option '; if(!$texto11){echo'selected="selected"';} echo' value="">Sin Respuesta</option><option '; if($texto11=='delgado'){echo'selected="selected"';} echo' value="delgado">Delgado/a</option><option '; if($texto11=='atletico'){echo'selected="selected"';} echo' value="atletico">Atl&eacute;tico</option><option '; if($texto11=='normal'){echo'selected="selected"';} echo' value="normal">Normal</option><option '; if($texto11=='kilos_de_mas'){echo'selected="selected"';} echo' value="kilos_de_mas">Algunos kilos de m&aacute;s</option><option '; if($texto11=='corpulento'){echo'selected="selected"';} echo' value="corpulento">Corpulento/a</option></select></td></tr><td align="right" valign="top"><b>Mi dieta es:</b></td><td><select id="dieta" name="dieta"><option '; if($texto6==''){echo'selected="selected"';} echo' value="">Sin Respuesta</option><option '; if($texto6=='vegetariana'){echo'selected="selected"';} echo' value="vegetariana">Vegetariana</option><option '; if($texto6=='lacto_vegetariana'){echo'selected="selected"';} echo' value="lacto_vegetariana">Lacto Vegetariana</option><option '; if($texto6=='organica'){echo'selected="selected"';} echo' value="organica">Org&aacute;nica</option><option '; if($texto6=='de_todo'){echo'selected="selected"';} echo' value="de_todo">De todo</option><option '; if($texto6=='comida_basura'){echo'selected="selected"';} echo' value="comida_basura">Comida basura</option></select></td></tr>

<tr><td align="right" valign="top"><b>Fumo:</b></td><td><table border="0" width="100%"><tbody><tr><td><label for="fumo"><input '; if(!$texto7){echo'checked="checked"';} echo' name="fumo" id="fumo" value="" type="radio" /> Sin Respuesta</label></td></tr>

<tr><td><label for="fumo2"><input '; if($texto7=='no'){echo'checked="checked"';} echo' name="fumo" id="fumo2" value="no" type="radio" /> No</label></td></tr>

<tr><td><label for="fumo3"><input '; if($texto7=='casualmente'){echo'checked="checked"';} echo' name="fumo" id="fumo3" value="casualmente" type="radio" /> Casualmente</label></td></tr>

<tr><td><label for="fumo4"><input '; if($texto7=='socialmente'){echo'checked="checked"';} echo' name="fumo" id="fumo4" value="socialmente" type="radio" /> Socialmente</label></td></tr>

<tr><td><label for="fumo5"><input '; if($texto7=='regularmente'){echo'checked="checked"';} echo' name="fumo" id="fumo5" value="regularmente" type="radio" /> Regularmente</label></td></tr>

<tr><td><label for="fumo6"><input '; if($texto7=='mucho'){echo'checked="checked"';} echo' name="fumo" id="fumo6" value="mucho" type="radio"> Mucho</label></td></tr></tbody></table>

</td><tr><td align="right" valign="top"><b>Tomo alcohol:</b></td><td><table border="0" width="100%"><tbody>
<tr><td><label for="tomo_alcohol"><input '; if(!$texto8){echo'checked="checked"';} echo' name="tomo_alcohol" id="tomo_alcohol" value="" type="radio" /> Sin Respuesta</label></td></tr>

<tr><td><label for="tomo_alcohol2"><input '; if($texto8=='no'){echo'checked="checked"';} echo' name="tomo_alcohol" id="tomo_alcohol2" value="no" type="radio" /> No</label></td></tr>

<tr><td><label for="tomo_alcohol3"><input '; if($texto8=='casualmente'){echo'checked="checked"';} echo' name="tomo_alcohol" id="tomo_alcohol3" value="casualmente" type="radio" /> Casualmente</label></td></tr>

<tr><td><label for="tomo_alcohol4"><input '; if($texto8=='socialmente'){echo'checked="checked"';} echo' name="tomo_alcohol" id="tomo_alcohol4" value="socialmente" type="radio" /> Socialmente</label></td></tr>

<tr><td><label for="tomo_alcohol5"><input '; if($texto8=='regularmente'){echo'checked="checked"';} echo' name="tomo_alcohol" id="tomo_alcohol5" value="regularmente" type="radio" /> Regularmente</label></td></tr>

<tr><td><label for="tomo_alcohol6"><input '; if($texto8=='mucho'){echo'checked="checked"';} echo' name="tomo_alcohol" id="tomo_alcohol6" value="mucho" type="radio" /> Mucho</label></td></tr></tbody></table></td></tr>
<tr><td colspan="3" align="right"><div class="hrs"></div><input class="button" style="font-size: 15px;" value="Editar mi apariencia" title="Editar mi apariencia" type="submit" name="enviar-265" /><input value="3" type="hidden" name="tipo" /></td></tr></tbody></table></form></div>

<h3 class="titlesCom '.$pasoabierto4.'" style="width: 762px;" onclick="chgsec(this)">4. Intereses y preferencias</h3>
<div class="active" id="contennnt" '.$pasoabierto4a.'><form action="/accion-apariencia/paso4/" method="post" accept-charset="'.$context['character_set'].'" enctype="multipart/form-data" style="margin:0px;padding:0px;"><table width="100%" cellpadding="4"><tbody><tr><td align="right" valign="top" width="23%"><b>Mis intereses:</b></td><td width="40%"><textarea style="width:235px;height:102px;" name="mis_intereses" cols="30" rows="5" id="mis_intereses" onfocus="foco(this);" onblur="no_foco(this);">'.$mis_intereses.'</textarea></td></tr><tr><td align="right" valign="top"><b>Hobbies:</b></td><td><textarea style="width:235px;height:102px;" name="hobbies" cols="30" rows="5" id="hobbies" onfocus="foco(this);" onblur="no_foco(this);">'.$hobbies.'</textarea></td></tr><tr><td align="right" valign="top"><b>Series de Tv favoritas:</b></td><td><textarea style="width:235px;height:102px;" name="series_tv_favoritas" cols="30" rows="5" id="series_tv_favoritas" onfocus="foco(this);" onblur="no_foco(this);">'.$series_de_tv_favorita.'</textarea></td></tr><tr><td align="right" valign="top" width="23%"><b>M&uacute;sica favorita:</b></td><td width="40%"><textarea style="width:235px;height:102px;" name="musica_favorita" cols="30" rows="5" id="musica_favorita" onfocus="foco(this);" onblur="no_foco(this);">'.$musica_favorita.'</textarea></td></tr><tr><td align="right" valign="top"><b>Deportes y equipos favoritos:</b></td><td><textarea style="width:235px;height:102px;" name="deportes_y_equipos_favoritos" cols="30" rows="5" id="deportes_y_equipos_favoritos" onfocus="foco(this);" onblur="no_foco(this);">'.$deportes_y_equipos_favoritos.'</textarea></td></tr><tr><td align="right" valign="top"><b>Libros Favoritos:</b></td><td><textarea style="width:235px;height:102px;" name="libros_favoritos" cols="30" rows="5" id="libros_favoritos" onfocus="foco(this);" onblur="no_foco(this);">'.$libros_favoritos.'</textarea></td></tr><tr><td align="right" valign="top" width="23%"><b>Pel&iacute;culas favoritas:</b></td><td width="40%"><textarea style="width:235px;height:102px;" name="peliculas_favoritas" cols="30" rows="5" id="peliculas_favoritas" onfocus="foco(this);" onblur="no_foco(this);">'.$pel�culas_favoritas.'</textarea></td></tr><tr><td align="right" valign="top"><b>Comida favor&iacute;ta:</b></td><td><textarea style="width:235px;height:102px;" name="comida_favorita" cols="30" rows="5" id="comida_favorita" onfocus="foco(this);" onblur="no_foco(this);">'.$comida_favor�ta.'</textarea></td></tr><tr><td align="right" valign="top"><b>Mis h&eacute;roes son:</b></td><td><textarea style="width:235px;height:102px;" name="mis_heroes_son" cols="30" rows="5" id="mis_heroes_son" onfocus="foco(this);" onblur="no_foco(this);">'.$mis_heroes_son.'</textarea></td></tr><tr><td colspan="3" align="right"><div class="hrs"></div><input class="button" style="font-size: 15px;" value="Editar mi apariencia" title="Editar mi apariencia" type="submit" name="enviar-265" /><input value="4" type="hidden" name="tipo" /></td></tr></tbody></table></form></div>


</div>'; } ?>