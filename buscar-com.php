<?php
function encodeurl($texto){
$texto=str_replace('+','{ad12B}',$texto);
$texto=str_replace(' ','+',$texto);
$texto=str_replace('?','{vvE3F}',$texto);
$texto=str_replace('/','{Edv3A}',$texto);
$texto=str_replace('#','{4RE23}',$texto);
$texto=str_replace('=','{rpl3D}',$texto);
$texto=str_replace('$','{Rfe26}',$texto);
$texto=str_replace(',','{fce2C}',$texto);
$texto=str_replace('<','{fci3C}',$texto);
$texto=str_replace('>','{fco3E}',$texto);
$texto=str_replace(';','{vD13B}',$texto);
$texto=str_replace('&','{4rc24}',$texto);
$texto=str_replace('~','{jho7E}',$texto);
$texto=str_replace('%','{ds625}',$texto);
return $texto;}
$t=encodeurl($_GET['q']);

$sort=$_GET['orden'];
$categoria=$_GET['categoria'];
$usuario=encodeurl($_GET['autor']);

if($_GET['buscador_tipo']=='c'){Header("Location: /comunidades/buscar/&q=$t&autor=$usuario&orden=$sort&categoria=$categoria&buscador_tipo=c");
exit();}

else{Header("Location: /comunidades/buscar/&q=$t&autor=$usuario&orden=$sort&categoria=$categoria&buscador_tipo=t");exit();}


?>