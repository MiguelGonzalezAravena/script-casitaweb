<?php require("cw-conexion-seg-0011.php");
global $tranfer1,$db_prefix, $context, $settings,$ajaxError, $options,$ID_MEMBER, $scripturl,$modSettings, $sourcedir;
if(empty($context['ajax'])){echo $ajaxError; die();}

$_GET['c']=isset($_GET['c']) ? seguridad($_GET['c']) : '';

include($sourcedir.'/FuncionesCom.php');
permisios($_GET['c']);
$RegistrosAMostrar=8;
if($_GET['pag']<1){$per='1';}else{$per=$_GET['pag'];}
if(isset($per)){$RegistrosAEmpezar=($per-1)*$RegistrosAMostrar;
$PagAct=$per;}else{$RegistrosAEmpezar=0;$PagAct=1;}
$NroRegistros=mysql_num_rows(db_query("SELECT c.id FROM ({$db_prefix}comunidades_miembros AS c, {$db_prefix}members AS m) WHERE c.id_com='{$_GET['c']}' AND c.id_user=m.ID_MEMBER AND c.aprobado=1",__FILE__, __LINE__));
$PagAnt=$PagAct-1;
$PagSig=$PagAct+1;
$PagUlt=$NroRegistros/$RegistrosAMostrar;
$Res=$NroRegistros%$RegistrosAMostrar;
if($Res>0) $PagUlt=floor($PagUlt)+1;

echo'<div><div id="contenidoPG" style="width:510px;_width:515px;height: 340px;overflow-y:auto;overflow-x:hidden;">';



if(empty($NroRegistros)){echo'<div class="noesta" style="width:510px;">No hay miembros.</div>';}else{

if(($PagAct>1 || $PagAct<$PagUlt) && $PagAct>1){echo'<div class="panador" onclick="pagComunidad(\''.$_GET['c'].'\',\''.$PagAnt.'\')">&#171; anterior</div>';}

echo'<div id="miem-com">';
$rs=db_query("SELECT m.realName,m.avatar,c.rango,m.usertitle,m.gender
FROM ({$db_prefix}comunidades_miembros AS c, {$db_prefix}members AS m)
WHERE c.id_com='{$_GET['c']}' AND c.id_user=m.ID_MEMBER AND c.aprobado=1
ORDER BY c.id DESC
LIMIT $RegistrosAEmpezar, $RegistrosAMostrar",__FILE__, __LINE__);
while($row=mysql_fetch_assoc($rs)){
$img=$row['avatar'];
$gender=$row['gender'];
$usertitle=$row['usertitle'];
if($img){$img2=$img;}else{$img2=$no_avatar;}
echo'
<div>
<h4><a href="/perfil/'.$row['realName'].'/" title="Perfil de '.$row['realName'].'">'.$row['realName'].'</a></h4>

<div style="float:left;"><a href="/perfil/'.$row['realName'].'/" title="Perfil de '.$row['realName'].'"><img src="'.$img2.'" onerror="error_avatar(this)" width="75px" height="75px"></a></div>
<div style="float:right;width:400px;">
	<ul>
		<li style="margin-bottom:2px;"><b>Rango:</b> '.ranguear($row['rango'],$_GET['c']).'</li>
		<li style="margin-bottom:2px;"><b>Pa&iacute;s:</b> '.pais($usertitle).'</li>
        <li style="margin-bottom:2px;"><b>Sexo:</b> '.sex($gender,1).' </li>
        <li style="margin-bottom:2px;"><span onclick="Boxy.load(\'/web/cw-TEMPenviarMP.php?user='.$row['realName'].'\', { title:\'Enviar MP a '.$row['realName'].'\'})" title="Enviar MP a '.$row['realName'].'" class="pointer">Enviar mensaje privado</span></li>';
        if($context['permisoCom']=='1' || $context['permisoCom']=='3')
        {echo'
        <li style="margin-bottom:2px;"><span onclick="Boxy.load(\'/web/cw-TEMPcomADmMIEMBROS.php?c='.$_GET['c'].'&r='.$row['realName'].'\', { title:\'Administrar: '.$row['realName'].'\'})" title="Administrar: '.$row['realName'].'" class="pointer" style="color:green;">Administrar miembro</span></li>';}

echo'</ul></div><div class="clearfix"></div> </div>'; }


echo'</div>';

if(($PagAct>1 || $PagAct<$PagUlt) &&$PagAct<$PagUlt){echo'<div class="panador" onclick="pagComunidad(\''.$_GET['c'].'\',\''.$PagSig.'\')"><div class="clearfix"></div>siguiente &#187;</div>';}

}
echo'</div></div>';
?>