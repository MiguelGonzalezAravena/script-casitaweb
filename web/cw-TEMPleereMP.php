<?php require("cw-conexion-seg-0011.php");
global $db_prefix,$tranfer1,$ID_MEMBER,$ajaxError,$context;
if(empty($context['ajax'])){echo $ajaxError; die();}
$id=(int)$_GET['id'];
if(empty($id)){die('<div class="noesta" style=";width:552px;">Mensaje no econtrado.</div>');}
if(empty($ID_MEMBER)){die('<div class="noesta" style=";width:552px;">Mensaje no econtrado.</div>');}

$leer=db_query("
SELECT p.id,p.titulo,p.fecha,p.mensaje,p.id_para
FROM ({$db_prefix}mensaje_personal AS p)
WHERE p.id='$id' AND p.id_de='{$ID_MEMBER}' AND p.eliminado_para=0 AND p.sistema=0
LIMIT 1", __FILE__, __LINE__);
while($row=mysql_fetch_array($leer)){
$dato=db_query("
SELECT p.realName
FROM ({$db_prefix}members AS p)
WHERE p.ID_MEMBER='{$row['id_para']}'
LIMIT 1", __FILE__, __LINE__);
while($drow=mysql_fetch_array($dato)){$nick_a=$drow['realName'];}
mysql_free_result($dato);
    
echo'<div>

<div style="text-align:left;width:554px;">

<div>
<div style="padding:4px 0 4px 2px;background-color:#F6F6F6;"><strong>A:</strong></div>
<div style="padding:4px 0 4px 2px;"><a href="/perfil/'.$nick_a.'">'.$nick_a.'</a></div>
<div style="padding:4px 0 4px 2px;background-color:#F6F6F6;"><strong>Enviado:</strong></div>
<div style="padding:4px 0 4px 2px;">'.hace($row['fecha']).'</div>
</div>

<div style="padding:4px 0 4px 2px;background-color:#F6F6F6;"><strong>Mensaje:</strong></div>
<div style="height:140px;overflow:auto;padding:4px 0 4px 2px;">
'.censorText(str_replace('(this.width >720) {this.width=720}','(this.width >485) {this.width=485}',parse_bbc($row['mensaje']))).'
</div> 

</div></div>';
$d=$row['id'];
}mysql_free_result($leer);
$d=isset($d) ? $d : '';
if(empty($d)){die('<div class="noesta" style=";width:552px;">Mensaje no econtrado.</div>');}

?>