<?php require("cw-conexion-seg-0011.php");
global $tranfer1,$db_prefix, $context, $settings,$ajaxError, $options,$ID_MEMBER, $scripturl,$modSettings, $sourcedir;
if(empty($context['ajax'])){echo $ajaxError; die();}

$_GET['c']=isset($_GET['c']) ? seguridad($_GET['c']) : '';
$_GET['r']=isset($_GET['c']) ? seguridad($_GET['r']) : '';

include($sourcedir.'/FuncionesCom.php');
permisios($_GET['c']);

if($context['permisoCom']=='1' || $context['permisoCom']=='3'){
echo'<div style="width:541px;">';
$rs=db_query("SELECT c.rango,c.ban,c.id,m.realName,c.rango,c.ban,c.ban_por,m.ID_MEMBER,c.ban_razon,c.ban_expirate
FROM ({$db_prefix}comunidades_miembros AS c, {$db_prefix}members AS m)
WHERE c.id_com='{$_GET['c']}' AND m.realName='{$_GET['r']}' AND m.ID_MEMBER=c.id_user AND c.aprobado=1
LIMIT 1",__FILE__, __LINE__);
while($row=mysql_fetch_assoc($rs)){
    $cdavvbv=$row['id'];
    $crngo=$row['rango'];
    $ban_por=$row['ban_por'];
    $ban_razon=$row['ban_razon'];
    $banccc=$row['ban'];
    $cdavddvbv=$row['realName'];
    $cID_MEMdBER=$row['ID_MEMBER'];
    $ban_expirate=$row['ban_expirate'] === null ? 'Nunca' : ($row['ban_expirate'] < time() ? 'Ya termino su ban' : (int)ceil(($row['ban_expirate'] - time()) / (60 * 60 * 24)) . '&nbsp;d&iacute;a(s)');}
    
if($context['permisoCom']=='3' && $crngo=='1'){echo'<div class="noesta" style="width:541px;margin-bottom:8px;float:left;">No podes modificar a los administradores.-</div>';}else{
if(!$cdavvbv){echo'<div class="noesta" style="width:541px;margin-bottom:8px;float:left;">Este miembro no esta en esta comunidad.</div>';}else{
if($cID_MEMdBER==$ID_MEMBER){echo'<div class="noesta" style="width:541px;margin-bottom:8px;float:left;">No podes administrarte vos mismo.-</div>';}else{
if($crngo=='1'){echo'<div class="noesta" style="width:541px;margin-bottom:8px;float:left;">No se puede administrar un miembro con rango Administrador.-</div>';}else{
    

echo'<form style="margin: 0px; padding: 0px;" action="/web/cw-comunidadesAdmMem.php" method="POST" accept-charset="'.$context['character_set'].'"><table>

<tr><td style="width:100px;"><b>Miembro:</b></td> <td>'.$cdavddvbv.' <span class="sep">|</span> <a href="/web/cw-ComunidadesDesaprobarmem.php?m='.$cdavvbv.'" style="color:red;">Eliminar Miembro</a></td></tr>
<tr>
<td style="width:100px;"><b>Rango:</b></td> 
<td><select name="rango" style="height:85px;" size="15">
<option value="0"'; if($crngo=='0'){echo' selected="selected"';}  echo'>Default</option>';

if($context['permisoCom']=='1'){
echo'<option value="1"'; if($crngo=='1'){echo' selected="selected"';}  echo'>Administrador</option>
<option value="5"'; if($crngo=='5'){echo' selected="selected"';}  echo'>Moderador</option>';}

echo'<option value="3"'; if($crngo=='3'){echo' selected="selected"';}  echo'>Posteador</option>
<option value="2"'; if($crngo=='2'){echo' selected="selected"';}  echo'>Comentador</option>
</select></td></tr></table><div class="hrs"></div>';

if(!$banccc){
echo'<table><tr><td style="width:100px;"><b>Banear:</b></td> <td><input name="banear" type="checkbox" /><br /></td></tr>

<tr><td style="width:100px;"><b>Raz&oacute;n:</b></td> <td><input onfocus="foco(this);" onblur="no_foco(this);" title="Raz&oacute;n" value="" type="text" name="razon" /></td></tr>

<tr><td style="width:100px;"><b>Expira:</b></td> <td><input onfocus="foco(this);" onblur="no_foco(this);" title="Expira" value="" type="text" name="expira" /> D&iacute;a(s) / Vacio: para siempre</td></tr></table>';}else{

echo'<div class="noesta">Usuario baneado<br />
Por: '.$ban_por.'<br />
Raz&oacute;n: '.nohtml(nohtml2($ban_razon)).'<br />
Expira: '.$ban_expirate.'</div>

<table><tr><td style="width:100px;"><b>Desbanear:</b></td> <td><input name="desbanear" type="checkbox" /><br /></td></tr></table>';}
echo'<div class="hrs"></div><p style="margin:0px;margin:0px;" align="right"><input alt="" class="login" title="Aceptar" value="Aceptar" type="submit" /></p>
<input name="miembro-cuestion" value="'.$cdavvbv.'" type="hidden" />

</form>';

}}}}
echo'</div>';
}else{die('<div class="noesta">No podes estar aca.</div>');} 


?>