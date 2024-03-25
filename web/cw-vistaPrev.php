<?php require("cw-conexion-seg-0011.php");
global $db_prefix, $scripturl, $func, $context, $modSettings,$user_settings, $user_info, $tranfer1;

$databasetit=$func['htmlspecialchars'](stripslashes($_POST['subject']), ENT_QUOTES);
$databasetit=addcslashes($databasetit, '"');
$databasetit=censorText($databasetit);

$databasepost=$func['htmlspecialchars'](stripslashes($_POST['message']), ENT_QUOTES);
$databasepost=str_replace(array('"', '<', '>', '  '), array('&quot;', '&lt;', '&gt;', ' &nbsp;'), $databasepost);
$databasepost= preg_replace("~\[hide\](.+?)\[\/hide\]~i", "&nbsp;", $databasepost);
$databasepost= preg_replace(array('~\n?\[hide.*?\].+?\[/hide\]\n?~is', '~^\n~', '~\[/hide\]~'), "&nbsp;", $databasepost);
$databasepost= preg_replace('~<br(?: /)?' . '>~i', "\n", $databasepost);
$databasepost=censorText($databasepost);
$contenido=hides($databasepost);
$contenido=parse_bbc($contenido);
$iduser=$user_settings['ID_MEMBER'];
if(empty($iduser)){die('<div class="noesta" style="width:922px;margin-bottom:4px;">Debes estar logueado para estar aca.-</div>');exit;}

if(strlen($_POST['subject'])>60){echo'<div class="noesta" style="width:922px;margin-bottom:4px;">El titulo no puede tener m&aacute;s de 60 letras.-</div>';}
else{
if(strlen($_POST['subject'])<3){echo'<div class="noesta" style="width:922px;margin-bottom:4px;">El titulo no puede tener menos de 3 letras.-</div>';}else{
if(strlen($_POST['message'])<=60){echo'<div class="noesta" style="width:922px;margin-bottom:4px;">El post no puede tener menos de 60 letras.-</div>';}else{
if(strlen($_POST['message'])>$modSettings['max_messageLength']){echo'<div class="noesta" style="width:922px;margin-bottom:4px;">El post no puede tener m&aacute;s de '.$modSettings['max_messageLength'].' letras.-</div>';}else{
		
        
if(empty($_POST['subject'])){echo'<div class="noesta" style="width:922px;margin-bottom:4px;">Falta el titulo.-</div>';}
elseif(empty($_POST['message'])){echo'<div class="noesta" style="width:922px;margin-bottom:4px;">Falta el mensaje del post.-</div>';}else{
echo'<div>';    
menuser($iduser);
echo'<div style="float:left;width: 774px;"><div class="box_780"><div class="box_title" style="width: 772px;"><div class="box_txt box_780-34"><center>'.$databasetit.'</center></div><div class="box_rss"><img alt="" src="'.$tranfer1.'/blank.gif" style="width:16px;height:16px;" border="0" /></div></div><div class="windowbg" style="width:772px;" id="vista_previa">
<div class="post-contenido" property="dc:content">'.$contenido.'</div></div>
<div align="center" style="margin-top:4px;"><input onclick="cerrar_vprevia()" class="button" style="font-size:13px;" value="Cerrar la previsualizaci&oacute;n" title="Cerrar la previsualizaci&oacute;n" type="button" /> <input onclick="confirm = false;" class="button" style="font-size:13px;" value="Ok, est&aacute; perfecto!" title="Ok, est&aacute; perfecto!" type="submit" />
</div></div></div>

</div>'; }}}}}

echo'<div style="clear: both;margin-bottom:4px;"></div>'; ?>