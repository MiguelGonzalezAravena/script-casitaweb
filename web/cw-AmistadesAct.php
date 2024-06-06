<?php require("cw-conexion-seg-0011.php");
global $tranfer1, $context, $db_prefix, $options, $txt,$no_avatar,$user_info, $ID_MEMBER;
if($user_info['is_guest']){return;}else{
    
$ccc=mysqli_num_rows(db_query("SELECT c.id FROM ({$db_prefix}amistad AS c) WHERE c.amigo='{$ID_MEMBER}' AND c.acepto=0 LIMIT 1", __FILE__, __LINE__));
if($ccc){
echo'<div id="amistadesACT" style="width:163px;margin-bottom:8px;">
<div style="background:#DADADA;color:#666666;font-size:14px;padding:4px;width:155px;"><b>Quiere ser tu amigo:</b></div>

<div style="background:#EEEEEE;padding:2px;width:159px;">
<center><img style="margin-top:4px;display:none;" id="cargandoAmistad src="'.$tranfer1.'/icons/cargando.gif" width="16px" height="16px" alt="" /></center>
<div style="display:none;" class="noesta" id="errorAmistad"></div>';

$redddbnn=db_query("
SELECT c.user,c.id
FROM ({$db_prefix}amistad AS c)
WHERE c.amigo='{$ID_MEMBER}' AND c.acepto=0
ORDER BY c.id DESC
LIMIT 5", __FILE__, __LINE__);
while($red=mysqli_fetch_array($redddbnn)){

if($red['user']){
$reddd3=db_query("
SELECT b.realName,b.avatar
FROM ({$db_prefix}members AS b)
WHERE b.ID_MEMBER='{$red['user']}'
LIMIT 1", __FILE__, __LINE__);
while($redc=mysqli_fetch_array($reddd3)){
if(empty($redc['avatar'])){$AVA=$no_avatar;}else{$AVA=$redc['avatar'];}

echo'<div id="ams_'.$red['id'].'" style="white-space: pre-wrap;overflow: hidden;display: block;"><div class="muroEfect" id="muroEfectAV" ><table><tr valign="top"><td valign="top"><a href="/perfil/'.$redc['realName'].'"><img src="'.$AVA.'" class="avatar-box" onerror="error_avatar(this)" width="50" height="50" alt="" /></a></td>
<td valign="top">';
echo'<a title="'.$redc['realName'].'" href="/perfil/'.$redc['realName'].'" style="color:#D35F2C;"><b>'.$redc['realName'].'</b></a><div class="clearfix" style="margin-top:4px;margin-bottom:4px;"><div style="margin-bottom:6px;"><a href="#" onclick="accionAmistad(\''.$red['id'].'\',\'1\'); return false;" class="botN1" style="color:#fff;text-shadow: #005400 0px 1px 0px;">Aceptar</a></div><div ><a href="#" onclick="accionAmistad(\''.$red['id'].'\',\'0\'); return false;" class="botN2" style="text-shadow: #CC0000 0px 1px 0px;color:#fff;">Rechazar</a></div></div></td></tr></table></div></div>';   
}

}

}
echo'</div></div>';}} ?>