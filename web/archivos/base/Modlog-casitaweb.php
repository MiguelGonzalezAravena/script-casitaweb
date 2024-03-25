<?php
function traduccion($valor){
$valor = str_replace("topic", "<span style='color:#B97CFF;'>Post:</span> ", $valor);
$valor = str_replace("Imagen", "<span style='color:#B97CFF;'>Imagen:</span> ", $valor);
return $valor;}


function template_main(){global $context,$db_prefix;
if($context['entries'] && $context['user']['is_admin']){
echo'
<script type="text/javascript">function EliminarHM(){
	$.ajax({
		type: \'GET\',
		url: \'/web/cw-EliminarMODlog.php\',
		success: function(h){location.reload();}
	});
}</script>';}
if($context['entries']){
echo'<table class="linksList size11" style="width:922px;" id="nohaynada3">
<thead><tr><th>&#191;Que?</th><th>Acci&oacute;n</th><th>Moderador</th><th>Causa</th></tr></thead>
<tbody>';

foreach ($context['entries'] as $entry){
echo'<tr>';

$request = db_query("SELECT realName FROM {$db_prefix}members WHERE ID_MEMBER = '{$entry['extra']['member']}' LIMIT 1", __FILE__, __LINE__); while ($row = mysql_fetch_assoc($request)){$iser=$row['realName'];}

echo'<td style="text-align:left;">';
echo traduccion($entry['que']).censorText($entry['extra'][$entry['que']]).'<br />Por: <a href="/perfil/'.$iser.'">'.$iser.'</a>';
echo'</td>';



echo'<td>'. $entry['action'] .'</td>
<td>'. $entry['moderator']['link'].'</td>';	

echo'<td>';
echo $entry['extra']['causa'];
echo'</td>';


echo'</tr>';}


echo'</tbody></table>';


$SYILEN='display:none;';}
else{$SYILEN='display:block;';}


echo'<div style="width: 922px;'.$SYILEN.'" id="nohaynada"><div class="noesta">No hay nada en el historial de moderaci&oacute;n.</div></div>';

if($context['entries'] && $context['user']['is_admin']){
echo'<div style="width: 922px;" id="nohaynada2">
<p align="right" style="margin-top:5px;"><input class="login" type="button" onclick="EliminarHM(); return false;" name="removeall" value="Borrar historial" title="Borrar historial" /></p></div>';}
}?>