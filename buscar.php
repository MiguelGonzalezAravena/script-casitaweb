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
$a=encodeurl($_GET['v']);

$sort=$_GET['orden'];
$categoria=(int)$_GET['categoria'];
if($categoria <= 0){$categoria='0';}else{$categoria=$categoria;}
$usuario=encodeurl($_GET['autor']);
if(empty($usuario)  || $usuario=='-1'){$usuario='';}else{$usuario=$usuario;}

if($_GET['buscador_tipo']=='g'){
Header("Location: /buscargoogle.php?cof=FORID%3A9&cx=015978274333592990658:r0qy7erzrbw&ie=UTF-8&sa=Buscar&q=$t");
exit();}

else{Header("Location: /buscador/&q=$t$a&autor=$usuario&orden=$sort&categoria=$categoria");exit();}


?>