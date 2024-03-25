<?php include("header-seg-1as4d4a777.php");
global $db_prefix;

$buscar=$_POST['palabra'] ? $_POST['palabra'] : $_GET['palabra'];
if(empty($buscar)){echo'<div class="noesta" style="width: 924px;">No se encontraron resultados.-</div>';}else{
       
$RegistrosAMostrar=15;
if($_GET['pag'] < 1){$dud=1;}else{$dud=$_GET['pag'];}
if(isset($dud)){$RegistrosAEmpezar=($dud-1)*$RegistrosAMostrar;
$PagAct=$dud;}else{$RegistrosAEmpezar=0;$PagAct=1;}

$sql=db("SELECT titulo,id,vieron,fecha
FROM {$prefijo}articulos 
WHERE MATCH (titulo, contenido) AGAINST ('$buscar')
ORDER BY id DESC
LIMIT $RegistrosAEmpezar, $RegistrosAMostrar");

$NroRegistros=mysql_num_rows(db("SELECT id
FROM {$prefijo}articulos 
WHERE MATCH (titulo, contenido) AGAINST ('$buscar')"));

if(!$NroRegistros){echo'<div class="noesta" style="width: 924px;">No se encontraron resultados.-</div>';}else{
echo'<table class="linksList" style="width: 924px;"><thead><tr>
					<th>&nbsp;</th>
					<th style="text-align: left;">Mostrando resultados</strong></th>
					<th>Fecha</th>
					<th>Visitan</th>
				</tr></thead><tbody>';
 $PagAnt=$PagAct-1;
 $PagSig=$PagAct+1;
 $PagUlt=$NroRegistros/$RegistrosAMostrar;
 $Res=$NroRegistros%$RegistrosAMostrar;
if($Res>0) $PagUlt=floor($PagUlt)+1;
while($row=mysql_fetch_array($sql)){
$daasdasda=$RegistrosAEmpezar ? ($RegistrosAEmpezar+1) : '1';
$daasdasda2=$RegistrosAEmpezar ? ($RegistrosAEmpezar+15) : '15';
if($daasdasda2>$NroRegistros){$daasdasda4=$NroRegistros;}else{$daasdasda4=$daasdasda2;}


echo'<tr id="div_'.$row['id'].'">
<td><img alt="" src="/imagenes/articulo.png" title="'.nohtml(nohtml2($row['titulo'])).'" /></td>
					<td style="text-align: left;"><a title="'.nohtml(nohtml2($row['titulo'])).'" href="/articulo/'.$row['id'].'" class="titlePost">'.nohtml(nohtml2($row['titulo'])).'</a></td>
					<td title="'.timeformat($row['fecha']).'">'.timeformat($row['fecha']).'</td>
					<td title="'.$row['vieron'].'">'.$row['vieron'].'</td>
				</tr>';}
                
echo'</tbody></table>';
}}

include("footer-seg-145747dd.php"); ?>