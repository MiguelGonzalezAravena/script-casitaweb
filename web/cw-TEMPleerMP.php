<?php require("cw-conexion-seg-0011.php");
global $db_prefix,$tranfer1,$ID_MEMBER,$context,$ajaxError;
if(empty($context['ajax'])){echo $ajaxError; die();}
$id=(int)$_GET['id'];
if(empty($id)){die('<div class="noesta" style=";width:552px;">Mensaje no econtrado.</div>');}
if(empty($ID_MEMBER)){die('<div class="noesta" style=";width:552px;">Mensaje no econtrado.</div>');}
$leer=db_query("
SELECT p.name_de,p.fecha,p.mensaje,p.id,p.leido
FROM ({$db_prefix}mensaje_personal AS p)
WHERE p.id='$id' AND p.id_para='{$ID_MEMBER}' AND p.eliminado_para=0
LIMIT 1", __FILE__, __LINE__);
while($row=mysqli_fetch_array($leer)){
if(empty($row['leido'])){
db_query("UPDATE {$db_prefix}mensaje_personal SET leido=1 WHERE id='$id' AND id_para='{$ID_MEMBER}' LIMIT 1", __FILE__, __LINE__);
db_query("UPDATE {$db_prefix}members SET topics=topics-1 WHERE ID_MEMBER='{$ID_MEMBER}' LIMIT 1", __FILE__, __LINE__);}


echo'<div>

<div style="text-align:left;width:554px;">

<div>
<div style="padding:4px 0 4px 2px;background-color:#F6F6F6;"><strong>Por:</strong></div>
<div style="padding:4px 0 4px 2px;"><a href="/perfil/'.$row['name_de'].'">'.$row['name_de'].'</a></div>
<div style="padding:4px 0 4px 2px;background-color:#F6F6F6;"><strong>Recibido:</strong></div>
<div style="padding:4px 0 4px 2px;">'.hace($row['fecha']).'</div>
</div>

<div style="padding:4px 0 4px 2px;background-color:#F6F6F6;"><strong>Mensaje:</strong></div>
<div style="height:140px;overflow:auto;padding:4px 0 4px 2px;">
'.censorText(str_replace('(this.width >720) {this.width=720}','(this.width >485) {this.width=485}',parse_bbc($row['mensaje']))).'
</div> 

</div>';
					

echo '<p align="right" style="margin-top:5px;"><input class="login" style="font-size: 11px;" value="Marcar como no le&iacute;do" title="Marcar como no leiacute;do" onclick="if (!confirm(\'\xbfEstas seguro que desea marcar este mensaje como no le\xeddo?\')) return false; location.href=\'/web/cw-marcarMp.php?id='.$row['id'].'\'" type="button" />&nbsp;<input class="login" style="font-size: 11px;" value="Responder MP" title="Responder MP" onclick="Boxy.get(this).hide(); Boxy.load(\'/web/cw-TEMPenviarMP.php?user='.$row['name_de'].';responder='.$row['id'].'\', {title: \'Responder a '.$row['name_de'].'\'});" type="button" /></p>

</div>';
$d=$row['id'];
}mysqli_free_result($leer);
$d=isset($d) ? $d : '';
if(empty($d)){die('<div class="noesta" style=";width:552px;">Mensaje no econtrado.</div>');}

?>