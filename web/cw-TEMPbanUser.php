<?php require("cw-conexion-seg-0011.php");
global $tranfer1, $context, $settings, $options,$ajaxError,$user_settings, $db_prefix,$scripturl, $txt,$ID_MEMBER, $modSettings;
if(empty($context['ajax'])){echo $ajaxError; die();}

if(($user_info['is_admin'] || $user_info['is_mods'])){
$sa=isset($_GET['sa']) ? $_GET['sa'] : '';
$bg=isset($_GET['bg']) ? (int) $_GET['bg'] : '';
$user=isset($_GET['u']) ? (int) $_GET['u'] : '';
//agregar
if($sa=='add' && $user){
$usersta=db_query("
SELECT m.realName,m.ID_MEMBER
FROM ({$db_prefix}ban_groups AS b,{$db_prefix}members AS m)
WHERE m.ID_MEMBER='$user'", __FILE__, __LINE__);
while($ban=mysqli_fetch_array($usersta)){
$name=$ban['realName'];
}   

$haygentea=mysqli_num_rows(db_query("
SELECT b.name
FROM ({$db_prefix}ban_groups AS b)
WHERE b.name='$name'", __FILE__, __LINE__));


$estar='0';
}


//esditar
elseif($sa=='edit' && $bg){
$usersta=db_query("
SELECT m.realName,b.notes,b.expire_time,b.reason
FROM ({$db_prefix}ban_groups AS b,{$db_prefix}members AS m)
WHERE b.ID_BAN_GROUP ='$bg' AND b.name=m.realName", __FILE__, __LINE__);
while($ban=mysqli_fetch_array($usersta)){
$name=$ban['realName'];
$mod=$ban['notes'];
$reason=trim(nohtml1(nohtml($ban['reason'])));
$status=$ban['expire_time'] === null ? '' : ($ban['expire_time'] < time() ? '' : 'still_active_but_we_re_counting_the_days');
$dias=$ban['expire_time'] > time() ? ceil(($ban['expire_time'] - time()) / 86400) : 0;

}    
$estar='1';}

if(!$name){echo'<div class="noesta" style="width:754px;">El usuario especificado no existe.</div>';}else{
if($name=='rigo'){echo'<div class="noesta" style="width:754px;">No puedes banear a este usuario.</div>';}else{
if($name==$user_settings['realName']){echo'<div class="noesta" style="width:754px;">No puedes banearte a vos mismo.</div>';}else{
    
if(!empty($estar)){if($mod<>$ID_MEMBER){$claveeeennJS3='this.form.clave.value';$claveeeennJS1='clave,';$claveeeennJS2='if(clave == \'\'){ $(\'#MostrarError2\').show();  return false;} else $(\'#MostrarError2\').hide();';$claveeeenn='<tr><th align="right" class="size11">Clave:</th><td align="left"><input type="text" onfocus="foco(this);" onblur="no_foco(this);" maxlength="6" style="width:55px;" name="clave" value="" size="50" /><div id="MostrarError2" class="capsprotBAJO">Falta la clave.</div></td></tr>';}else{$claveeeennJS3='';$claveeeennJS1='';$claveeeennJS2='';$claveeeenn='';}}else{$claveeeennJS3='';$claveeeennJS1='';$claveeeennJS2='';$claveeeenn='';}
                        
echo'<script type="text/javascript">
function chekkk('.$claveeeennJS1.'reason) { '.$claveeeennJS2.' if(reason == \'\'){ $(\'#MostrarError1\').show();  return false;} else $(\'#MostrarError1\').hide(); }</script>
<form action="/web/cw-BanEditAgre-seg-4454.php" method="post" accept-charset="'.$context['character_set'].'">

<table border="0" align="center" cellspacing="1" cellpadding="4" class="citacuerpo" width="400px">';
if(!empty($haygentea)){
echo'<tr><td style="background:#FFC703;border:solid 2px #BF8A01;color:#424242;">';
if(empty($_POST['agregar']) || empty($_POST['modificar'])){
echo'Este usuario esta en la lista de baneados - <a href="/moderacion/edit-user/ban/buscar&usuario='.$name.'&si=bloke"><b>Buscarlo</b></a>.-';}else{echo'Usuario baneado correctamente.-';}

echo'</td></tr>';}

echo'<tr><td align="center">';
if($estar){
echo'<input type="hidden" name="id" value="'.$bg.'" />';}else{echo'<input type="hidden" name="id" value="'.$user.'" />';   }

echo'
<table cellpadding="4">';

$status=isset($status) ? $status : '';
$dias=isset($dias) ? (int) $dias : '0';
$reason=isset($reason) ? $reason : '';
echo'<tr>
							<th align="right" valign="top" class="size11">Nunca:</th>
							<td align="left"><input type="radio" name="expiration" value="" id="never_expires"', $status ? '' : ' checked="checked"', ' class="check" />&nbsp;&nbsp;<label for="never_expires">Nunca</label><br />
                            
							<input type="radio" name="expiration" value="one_day" id="expires_one_day"', $status == 'still_active_but_we_re_counting_the_days' ? ' checked="checked"' : '', ' class="check" />&nbsp;&nbsp;<label for="expires_one_day">En</label>: <input type="text" onfocus="foco(this);" onblur="no_foco(this);" name="expire_date" id="expire_date" size="3" value="'.$dias.'" /> D&iacute;as</td>
						</tr>

						<tr>
							<th align="right" valign="top" class="size11">Causa:</th>
							<td align="left"><input type="text" onfocus="foco(this);" onblur="no_foco(this);" name="reason" value="'.$reason.'" size="50" /><div id="MostrarError1" class="capsprotBAJO">Falto la causa.</div>
							</td>
						</tr>';
				
	echo '
						<tr>
							<td colspan="2" align="center"><input class="login" onclick="return chekkk('.$claveeeennJS3.'this.form.reason.value);" type="submit" name="', $estar ? 'modificar' : 'agregar', '" value="', $estar ? 'Editar ban' : 'Agregar ban', '" /></td>
						</tr>';
        echo'
					</table></td></tr></table>
					</form>';
}}}
}
?>