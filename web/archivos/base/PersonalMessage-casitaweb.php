<?php
function template_intro(){global $tranfer1, $txt,$context, $user_settings;

$accion=isset($_GET['sas']) ? $_GET['sas'] : '';
echo'<div style="width:160px;float:left;margin-right:8px;">

<div style="margin-bottom:8px;" class="img_aletat"><div class="box_title" style="width: 158px;"><div class="box_txt img_aletat">'.$txt['pm_messages'].'</div><div class="box_rss"><img alt="" src="'.$tranfer1.'/blank.gif" style="width: 16px; height: 16px;" border="0" /></div></div><div class="windowbg" style="font-size:13px;width:150px;padding:4px;">';
if($user_settings['topics']>=1){
$b='<b>';
$bc='</b>';
$cantidad='(<span id="cantidad-MP2">'.$user_settings['topics'].'</span>)';
}else{
$b='';
$bc='';
$cantidad='';}

echo'<img src="'.$tranfer1.'/icons/mensaje_enviar.gif" alt="" width="16px" height="16px" /> <span class="pointer" onclick="Boxy.load(\'/web/cw-TEMPenviarMP.php\', {title: \'Enviar mensaje privado\'});" title="Enviar mensaje privado"> Enviar mensaje</span><br />';
echo'<img src="'.$tranfer1.'/icons/mensaje.gif" alt="" width="16px" height="16px" /> <a href="/mensajes/recibidos/" title="'.$txt[318].'"> '.$b.''.$txt[316].' '.$cantidad.''.$bc.'</a><br />';
echo'<img src="'.$tranfer1.'/icons/mensaje_para.gif" alt="" width="16px" height="16px" /> <a href="/mensajes/enviados/" title="'.$txt[320].'"> '.$txt[320].'</a>'; echo'</div></div>';
		
echo'<div class="img_aletat"><div class="box_title" style="width: 158px;"><div class="box_txt img_aletat">Publicidad</div><div class="box_rss"><img alt="" src="'.$tranfer1.'/blank.gif" style="width: 16px; height: 16px;" border="0" /></div></div><div class="windowbg" style="font-size:13px;width:150px;padding:4px;">';anuncio1_120x240();
echo'</div></div>

</div>

<div style="float:left;width:754px;">';

if($accion==='enviados'){enviados();}
elseif($accion==='recibidos'){recibidos();}
else{recibidos();}
echo'</div>';}


function enviados(){global $db_prefix,$tranfer1,$context,$ID_MEMBER;
$RegistrosAMostrar=5;
if(isset($_GET['pag-seg-145a'])){$RegistrosAEmpezar=($_GET['pag-seg-145a']-1)*$RegistrosAMostrar;$PagAct=$_GET['pag-seg-145a'];}else{$RegistrosAEmpezar=0;$PagAct=1;}

$NroRegistros=mysql_num_rows(db_query("SELECT p.id_de FROM ({$db_prefix}mensaje_personal AS p) WHERE p.id_de='$ID_MEMBER' AND p.eliminado_de=0", __FILE__, __LINE__));

$mensajes=db_query("
SELECT p.id_para,p.titulo,p.id,p.fecha
FROM ({$db_prefix}mensaje_personal AS p)
WHERE p.id_de='$ID_MEMBER' AND p.eliminado_de=0 AND p.sistema=0
ORDER BY p.id DESC
LIMIT $RegistrosAEmpezar, $RegistrosAMostrar", __FILE__, __LINE__);
if(empty($NroRegistros)){echo'<div class="noesta" style="width:754px;">No hay mensajes enviados...</div>';}

else{
    
echo'<table class="linksList" style="width:754px;"><thead align="center"><tr><th>&nbsp;</th><th>Asunto</th><th>Destinatario</th><th>Enviado</th></tr></thead><tbody>';


while($row=mysql_fetch_array($mensajes)){
$dato=db_query("
SELECT p.realName
FROM ({$db_prefix}members AS p)
WHERE p.ID_MEMBER='{$row['id_para']}'
LIMIT 1", __FILE__, __LINE__);
while($drow=mysql_fetch_array($dato)){$nick_a=$drow['realName'];}mysql_free_result($dato);

echo'<tr><td><span title="Eliminar mensaje" class="pointer" onclick="Boxy.confirm(\'&iquest;Estas seguro que desea eliminar este mensaje?\', function() { location.href=\'/web/cw-eliminarMp2.php?id_sde='.$row['id'].'\'; }, {title: \'Eliminar Mensaje\'});"><img width="10px" src="'.$tranfer1.'/eliminar.gif"  alt="" /></span></td>
<td><span class="pointer" onclick="Boxy.load(\'/web/cw-TEMPleereMP.php?id='.$row['id'].'\', {title: \''.censorText($row['titulo']).'\'});" title="'.censorText($row['titulo']).'">'.censorText($row['titulo']).'</span></td><td><a href="/perfil/'.$nick_a.'" title="'.$nick_a.'">'.$nick_a.'</a></td><td><span class="size11">'.timeformat($row['fecha']).'</span></td></tr>';}mysql_free_result($mensajes);
echo'</tbody></table>';}

 $PagAnt=$PagAct-1;
 $PagSig=$PagAct+1;
 $PagUlt=$NroRegistros/$RegistrosAMostrar;
 $Res=$NroRegistros%$RegistrosAMostrar;
 if($Res>0) $PagUlt=floor($PagUlt)+1;
 
if(!empty($NroRegistros)){
if(($PagAct>1) || ($PagAct<$PagUlt)){
echo'<div class="windowbgpag" style="width:300px;">';
if($PagAct>1) echo "<a href='/mensajes/enviados/pag-$PagAnt'>&#171; anterior</a>";
if($PagAct<$PagUlt)  echo "<a href='/mensajes/enviados/pag-$PagSig'>siguiente &#187;</a>";
echo'</div><div class="clearBoth"></div>';}}
}


function recibidos(){	
global $db_prefix,$tranfer1,$context,$ID_MEMBER;
$RegistrosAMostrar=5;
if(isset($_GET['pag-seg-145a'])){$RegistrosAEmpezar=($_GET['pag-seg-145a']-1)*$RegistrosAMostrar;$PagAct=$_GET['pag-seg-145a'];}else{$RegistrosAEmpezar=0;$PagAct=1;}


$NroRegistros=mysql_num_rows(db_query("SELECT p.id_para,p.eliminado_para FROM ({$db_prefix}mensaje_personal AS p) WHERE p.id_para='{$ID_MEMBER}' AND p.eliminado_para=0", __FILE__, __LINE__));
 
if(empty($NroRegistros)){echo'<div class="noesta" style="width:754px;">No hay mensajes recibidos...</div>';}else{
$mensajes=db_query("
SELECT p.id,p.leido,p.titulo,p.name_de,p.fecha
FROM ({$db_prefix}mensaje_personal AS p)
WHERE p.id_para='{$ID_MEMBER}' AND p.eliminado_para=0
ORDER BY p.id DESC
LIMIT $RegistrosAEmpezar, $RegistrosAMostrar", __FILE__, __LINE__);
echo'<table class="linksList" style="width:754px;"><thead align="center"><tr><th>&nbsp;</th><th>Asunto</th><th>Por</th><th>Recibido</th></tr></thead><tbody>';
while($row=mysql_fetch_array($mensajes)){

echo'<tr'; if(!$row['leido']){echo' style="background-color:#FDFBE7;" ';} echo' id="mp_'.$row['id'].'">
<td><span id="imgel_'.$row['id'].'" class="pointer" onclick="Boxy.confirm(\'&iquest;Estas seguro que desea eliminar este mensaje?\', function() { del_mp_env(\''.$row['id'].'\'); }, {title: \'Eliminar Mensaje\'});" title="Eliminar mensaje"><img width="10px" src="'.$tranfer1.'/eliminar.gif"  alt="" /></span><span id="imgerr_'.$row['id'].'" style="display: none;"></span><span id="imgerrs_'.$row['id'].'" style="display: none;"><img width="10px" src="'.$tranfer1.'/eliminar.gif" alt="" /></span></td>
<td><span class="pointer" onclick="Boxy.load(\'/web/cw-TEMPleerMP.php?id='.$row['id'].'\', {title: \''.censorText($row['titulo']).'\'});" title="'.censorText($row['titulo']).'">'.censorText($row['titulo']).'</span></td>
<td><a href="/perfil/'.$row['name_de'].'" title="'.$row['name_de'].'">'.$row['name_de'].'</a></td>
<td><span class="size11">'.timeformat($row['fecha']).'</span></td>

</tr>';}mysql_free_result($mensajes);

echo'</tbody></table>';}

 $PagAnt=$PagAct-1;
 $PagSig=$PagAct+1;
 $PagUlt=$NroRegistros/$RegistrosAMostrar;
 $Res=$NroRegistros%$RegistrosAMostrar;
 if($Res>0) $PagUlt=floor($PagUlt)+1;

if(!empty($NroRegistros)){
if($PagAct>1 || $PagAct<$PagUlt){
echo'<div class="windowbgpag" style="width:300px;">';
if($PagAct>1) echo "<a href='/mensajes/pag-$PagAnt'>&#171; anterior</a>";
if($PagAct<$PagUlt)  echo "<a href='/mensajes/pag-$PagSig'>siguiente &#187;</a>";
echo'</div><div class="clearBoth"></div>';}}
}
 ?>