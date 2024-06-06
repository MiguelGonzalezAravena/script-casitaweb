<?php require("cw-conexion-seg-0011.php"); global $db_prefix,$user_settings;
if(!$user_settings['ID_MEMBER']){echo'0: <span class="error">Usuario no conectado</span>';}else{
    
$voto=trim($_POST['voto']);
$id=(int)$_POST['tema'];

if(!$id){echo'0: <span class="error">Error2</span>';}else{
$rs44=db_query("
SELECT a.calificacion,a.id,a.id_com
FROM ({$db_prefix}comunidades_articulos AS a)
WHERE a.id='$id'
LIMIT 1",__FILE__, __LINE__);
while($row=mysqli_fetch_assoc($rs44)){
$id2=$row['id'];
$id_com=$row['id_com'];
$def1=$row['calificacion'];}
include($sourcedir.'/FuncionesCom.php');
baneadoo($id_com);
if(!$id2){echo'0: <span class="error">Error</span>';}else{
$ya3=mysqli_num_rows(db_query("SELECT m.id_com FROM ({$db_prefix}comunidades_miembros AS m) WHERE m.id_com='$id_com' AND m.id_user='{$user_settings['ID_MEMBER']}' LIMIT 1",__FILE__, __LINE__));
if(!$ya3){echo'0: <span class="error">No sos miembro la comunidad</span>';}else{    
    
$ya=mysqli_num_rows(db_query("SELECT v.id_tema FROM ({$db_prefix}comunidades_votosArts AS v) WHERE v.id_tema='$id' AND v.id_user='{$user_settings['ID_MEMBER']}' LIMIT 1",__FILE__, __LINE__));
if($ya){echo'0: <span class="error">Ya puntuastes</span>';}else{
$ya2=mysqli_num_rows(db_query("SELECT a.id FROM ({$db_prefix}comunidades_articulos AS a) WHERE a.id='$id' AND a.id_user='{$user_settings['ID_MEMBER']}' LIMIT 1",__FILE__, __LINE__));
if($ya2){echo'0: <span class="error">No a tus temas</span>';}else{


if($voto<'-1'){echo'0: <span class="error">Error</span>';}
elseif($voto>'1'){echo'0: <span class="error">Error</span>';}
elseif($voto=='-0'){echo'0: <span class="error">Error</span>';}
elseif(!$voto){echo'0: <span class="error">Error</span>';}else{
if($voto=='-1'){$cali=$voto;
$def=$def1-1;
}elseif($voto=='1'){$cali='+1';
$def=$def1+1;}
else{$cali='-1';
$def=$def1-1;}

db_query("  UPDATE {$db_prefix}comunidades_articulos
			SET calificacion=calificacion$cali
			WHERE id='$id'
			LIMIT 1", __FILE__, __LINE__);
db_query("INSERT INTO {$db_prefix}comunidades_votosArts (id_tema, id_user, cant, fecha ) 
    VALUES ('$id', '{$user_settings['ID_MEMBER']}', '$cali', ".time().")", __FILE__, __LINE__);
if(!$def){echo'1: <span class="ok">'.$def.'</span>';}
elseif($def<0){echo'1: <span class="error">'.$def.'</span>';}
elseif($def>0){echo'1: <span class="ok">+'.$def.'</span>';}
}}}}}}}
?>