<?php

function template_intro(){global $ID_MEMBER,$context,$db_prefix,$tranfer1;
$_GET['pag']=isset($_GET['pag']) ? $_GET['pag'] : '';

if(empty($context['ADMCOMtema'])){
$RegistrosAMostrar=15;

if($_GET['pag'] < 1){$dud=1;}else{$dud=$_GET['pag'];}
if(isset($dud)){$RegistrosAEmpezar=($dud-1)*$RegistrosAMostrar;$PagAct=$dud;}else{$RegistrosAEmpezar=0;$PagAct=1;}

$NroRegistros=mysqli_num_rows(db_query("SELECT c.bloquear FROM ({$db_prefix}comunidades AS c) WHERE c.bloquear=1", __FILE__, __LINE__));

if($NroRegistros){
$datos=db_query("
SELECT c.bloquear,c.bloquear_razon,c.bloquear_por,c.nombre,c.url,c.id
FROM ({$db_prefix}comunidades AS c) 
WHERE c.bloquear=1 
ORDER BY c.id DESC 
LIMIT $RegistrosAEmpezar, $RegistrosAMostrar", __FILE__, __LINE__);

echo'<div class="clearBoth"></div>

<table class="linksList" style="width:922px;"><thead><tr>
					<th>&nbsp;</th>
					<th style="text-align: left;">Comunidades eliminadas</th>
					<th>Raz&oacute;n</th>
					<th>Por</th>
				</tr></thead><tbody>';
                
while($row=mysqli_fetch_assoc($datos)){
$row['nombre']=nohtml(nohtml2($row['nombre']));
if(strlen($row['nombre'])>50){$tipo=substr($row['nombre'],0,47)."...";}else{$tipo=$row['nombre'];}

echo'<tr id="ID_'.$row['id'].'">
<td title="Comunidades"><img src="'.$tranfer1.'/comunidades/comunidad.png" alt="" width="16px" height="16px" /></td>
<td style="text-align: left;"><a title="'.$row['nombre'].'" href="/comunidades/'.$row['url'].'" class="titlePost">'.$tipo.'</a></td>
<td>'; if(!$row['bloquear_razon']){echo' - ';}else{echo $row['bloquear_razon'];} echo'</td>
<td>'; if(!$row['bloquear_por']){echo' Creador ';}else{echo '<a href="/perfil/'.$row['bloquear_por'].'">'.$row['bloquear_por'].'</a>';} echo'</td>
</tr>';}


echo'</tbody></table>';

}

 $PagAnt=$PagAct-1;
 $PagSig=$PagAct+1;
 $PagUlt=$NroRegistros/$RegistrosAMostrar;
 $Res=$NroRegistros%$RegistrosAMostrar;
if($Res>0) $PagUlt=floor($PagUlt)+1;

if($PagAct>1 || $PagAct<$PagUlt){
echo'<div class="windowbgpag" style="width:300px;">';
 if($PagAct>1) echo "<a href='/moderacion/comunidades/pag-$PagAnt'>&#171; anterior</a>";
 if($PagAct<$PagUlt)  echo "<a href='/moderacion/comunidades/pag-$PagSig'>siguiente &#187;</a>";
echo'</div><div class="clearBoth"></div>';} 
    
    
    
    
    
    
}else{
    
$RegistrosAMostrar=15;
if($_GET['pag'] < 1){$dud=1;}else{$dud=$_GET['pag'];}
if(isset($dud)){$RegistrosAEmpezar=($dud-1)*$RegistrosAMostrar;$PagAct=$dud;}else{$RegistrosAEmpezar=0;$PagAct=1;}

$NroRegistros=mysqli_num_rows(db_query("SELECT c.eliminado FROM ({$db_prefix}comunidades_articulos AS c) WHERE c.eliminado=1", __FILE__, __LINE__));

if($NroRegistros){
$datos=db_query("
SELECT c.titulo,c.id,co.url,c.creado,c.UserName
FROM ({$db_prefix}comunidades_articulos AS c,{$db_prefix}comunidades AS co) 
WHERE c.eliminado=1 AND c.id_com=co.id
ORDER BY c.id DESC 
LIMIT $RegistrosAEmpezar, $RegistrosAMostrar", __FILE__, __LINE__);

echo'<div class="clearBoth"></div>
<table class="linksList" style="width:922px;"><thead><tr>
					<th>&nbsp;</th>
					<th style="text-align: left;">Tema eliminados</th>
					<th>Fecha</th>
					<th>Creado por</th>
				</tr></thead><tbody>';
                
while($row=mysqli_fetch_assoc($datos)){
$row['titulo']=nohtml(nohtml2($row['titulo']));
if(strlen($row['titulo'])>50){$tipo=substr($row['titulo'],0,47)."...";}else{$tipo=$row['titulo'];}

echo'<tr id="ID_'.$row['id'].'">
<td title="Comunidades"><img src="'.$tranfer1.'/comunidades/temas.png" alt="" width="16px" height="16px" /></td>
<td style="text-align: left;"><a title="'.$row['titulo'].'" href="/comunidades/'.$row['url'].'/'.$row['id'].'/'.urls($row['titulo']).'.html" class="titlePost">'.$tipo.'</a></td>
<td>'.timeformat($row['creado']).'</td>
<td><a href="/perfil/'.$row['UserName'].'">'.$row['UserName'].'</a></td>
</tr>';}


echo'</tbody></table>';

}

 $PagAnt=$PagAct-1;
 $PagSig=$PagAct+1;
 $PagUlt=$NroRegistros/$RegistrosAMostrar;
 $Res=$NroRegistros%$RegistrosAMostrar;
if($Res>0) $PagUlt=floor($PagUlt)+1;

if($PagAct>1 || $PagAct<$PagUlt){
echo'<div class="windowbgpag" style="width:300px;">';
 if($PagAct>1) echo "<a href='/moderacion/comunidades/temas/pag-$PagAnt'>&#171; anterior</a>";
 if($PagAct<$PagUlt)  echo "<a href='/moderacion/comunidades/temas/pag-$PagSig'>siguiente &#187;</a>";
echo'</div><div class="clearBoth"></div>';} 
    
    
}


} ?>