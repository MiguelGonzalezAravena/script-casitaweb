<?php

function template_main(){
global $ID_MEMBER,$context,$modSettings, $settings, $options, $txt,$db_prefix, $scripturl,$sourcedir,$tranfer1;
require($sourcedir.'/Hear-Buscador.php');
hearBuscador('0','c');

$busqueda=trim(decodeurl($_GET['q']));
$usuario=trim(decodeurl($_GET['autor']));
if($busqueda or $usuario){
$cat=(int)$_GET['categoria'];
if($cat < 1){$cats='';}else{$cats=' AND p.ID_BOARD='.$cat;}


if(!$busqueda){$ssdeeesss2="";$score="p.posterName LIKE '%$usuario%'";
if($usuario){$ssdeeesss="p.posterName='$usuario'";}else{$ssdeeesss='';}}

else{$ssdeeesss2="MATCH (p.subject, p.body) AGAINST ('$busqueda')";$score="MATCH (p.subject, p.body) AGAINST ('$busqueda')";
if($usuario){$ssdeeesss="AND p.posterName='$usuario'";}else{$ssdeeesss='';}}



$sort=trim($_GET['orden']);

if($sort=='fecha' || $sort=='relevancia' || $sort=='puntos'){$orden=$sort;}else{$orden='fecha';}
if($orden=='fecha'){$dbor='p.ID_TOPIC DESC';}
if($orden=='relevancia'){$dbor='Score DESC';}
if($orden=='puntos'){$dbor='t.puntos DESC';}

$RegistrosAMostrar=$modSettings['search_results_per_page'];
$gd=isset($_GET['pag']) ? $_GET['pag'] : '';   
if($gd < 1){$dud=1;}else{$dud=$gd;}
if(isset($dud)){$RegistrosAEmpezar=($dud-1)*$RegistrosAMostrar;
$PagAct=$dud;}else{$RegistrosAEmpezar=0;$PagAct=1;}

if(!$context['user']['is_admin']){$shas=' AND p.ID_BOARD<>142 ';}else{$shas='';}

$NroRegistros=mysqli_num_rows(db_query("
SELECT p.subject 
FROM {$db_prefix}messages AS p
WHERE $ssdeeesss2 $cats $ssdeeesss $shas", __FILE__, __LINE__));

 $PagAnt=$PagAct-1;
 $PagSig=$PagAct+1;
 $PagUlt=$NroRegistros/$RegistrosAMostrar;
 $Res=$NroRegistros%$RegistrosAMostrar;
 if($Res>0) $PagUlt=floor($PagUlt)+1;

$result=db_query("SELECT $score AS Score,p.ID_TOPIC,p.puntos,p.subject,p.posterTime,c.description,p.hiddenOption
FROM ({$db_prefix}messages AS p,{$db_prefix}boards AS c, {$db_prefix}members AS u)
WHERE $ssdeeesss2 $cats $ssdeeesss AND p.ID_BOARD=c.ID_BOARD$shas AND p.ID_MEMBER=u.ID_MEMBER AND p.eliminado=0
ORDER BY $dbor
LIMIT $RegistrosAEmpezar, $RegistrosAMostrar", __FILE__, __LINE__);
$context['posts']=array();
while($row=mysqli_fetch_assoc($result)){
    $context['posts'][]=array(
		'puntos' => $row['puntos'],
		'description' => $row['description'],
		'posterTime' => timeformat($row['posterTime']),
		'hiddenOption' => $row['hiddenOption'],
		'id' => $row['ID_TOPIC'],
		'relevancia' => $row['Score'],
		'titulo' => censorText($row['subject']));
        }
mysqli_free_result($result);
if(!$NroRegistros){echo'<div class="noesta-am" style="width:922px;">No se encontraron resultados.</div>';}else{
    
$daasdasda=$RegistrosAEmpezar ? ($RegistrosAEmpezar+1) : '1';
$daasdasda2=$RegistrosAEmpezar ? ($RegistrosAEmpezar+50) : '50';
if($daasdasda2>$NroRegistros){$daasdasda4=$NroRegistros;}else{$daasdasda4=$daasdasda2;}
echo'<table class="linksList" style="width: 922px;"><thead><tr>
					<th style="text-align: left;">Mostrando <strong>'.($daasdasda).' de '.($daasdasda4).'</strong> resultados de <strong>'.$NroRegistros.'</strong></th>
					<th>Fecha</th>
					<th>Puntos</th>
					<th>Relevancia</th>
				</tr></thead><tbody>';
                
foreach ($context['posts'] as $sticky){

echo'<tr id="div_'.$sticky['id'].'">
					<td style="text-align: left;">';
					
if($sticky['hiddenOption'] && $context['user']['is_guest'])echo'<img alt="" src="'.$tranfer1.'/comunidades/registrado.png" /> ';

					echo'<a title="'.$sticky['titulo'].'" href="/post/'.$sticky['id'].'/'.$sticky['description'].'/'.urls($sticky['titulo']).'.html" class="categoriaPost '.$sticky['description'].'">'.$sticky['titulo'].'</a></td>
					<td title="'.$sticky['posterTime'].'">'.$sticky['posterTime'].'</td>
					<td><span style="color:green;">'.$sticky['puntos'].'</span></td>
					<td>'.relevancia($sticky['relevancia']).'</td>
				</tr>';
}

echo'</tbody></table>';

if($PagAct>$PagUlt){echo'';}elseif($PagAct>1 || $PagAct<$PagUlt){echo'<div class="windowbgpag" style="width: 910px;">';
if($PagAct>1) echo "<a href='/buscador/&q=$busqueda&autor=$usuario&orden=$orden&categoria=$cat&pag=$PagAnt'>&#171; anterior</a>";
if($PagAct<$PagUlt)  echo "<a href='/buscador/&q=$busqueda&autor=$usuario&orden=$orden&categoria=$cat&pag=$PagSig'>siguiente &#187;</a>";

echo'<div class="clearBoth"></div></div>';}}

}}


function template_results(){} ?>