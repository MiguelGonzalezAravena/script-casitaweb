<?php 
global $boarddir,$mbname,$tranfer1;
$IPBANS=$boarddir."/web/IPsBans.php";
$ip=$_SERVER["REMOTE_ADDR"];

$vvdd=count(file($IPBANS)); $blines=file($IPBANS);
for($i=1; $i<=$vvdd;++$i){ $blines[$i]=isset($blines[$i]) ? $blines[$i] : '';  if(trim($blines[$i]) == $ip){ die('-'); } }
$file=$boarddir."/web/IPsBlok.php";

$_SERVER['HTTP_USER_AGET']=isset($_SERVER['HTTP_USER_AGET'] ) ? $_SERVER['HTTP_USER_AGET'] : '';

if((strstr($_SERVER['HTTP_USER_AGET'] ,' googlebot' )) ||
         (strstr($_SERVER['HTTP_USER_AGET'], 'Googlebot')) ||
         (strstr($_SERVER['HTTP_USER_AGET'], 'Mediapartners-Google')) ||
         (strstr($_SERVER['HTTP_USER_AGET'], 'eBay Relevance Ad Crawler')) ||
         (strstr($_SERVER['HTTP_USER_AGET'], 'Yahoo! Slurp;' ))){ }else{
            
function escribir($c,$t,$r,$ca){
global $file,$ip;
$eltexto = $ca.'|'.$ip.'|'.$r.'|'.$c.'|'.$t."\n";
$archivo = fopen ($file, "a");
fwrite($archivo, $eltexto);
fclose($archivo);}

function escribir2(){
global $IPBANS,$ip;
$eltexto = "\n".$ip;
$vvdd=count(file($IPBANS)); $blines=file($IPBANS);
for($i=1; $i<=$vvdd;++$i){ if(trim($blines[$i]) == $ip){ die('-'); }else{
$archivo = fopen ($IPBANS, "a");
fwrite($archivo, $eltexto);
fclose($archivo);}}
}

$cantidad=count(file($file));

if($cantidad > 500){$archivo = fopen ($file, "w+");
fwrite($archivo, "");
fclose($archivo);}else{
    
$time=time();
$_SERVER["REQUEST_URI"]=trim(str_replace('/','',$_SERVER["REQUEST_URI"]));
$r=empty($_SERVER["REQUEST_URI"]) ?  'indexphp' : urls($_SERVER["REQUEST_URI"]);

if($r <> 'webcw-vistaprevphp'){
    
$lines = file($file);
ksort($lines);

foreach ($lines as $line_num => $line) {
$datos = explode("|", $line);
if($datos[0] > 0){$namber=($datos[0]-1);}else{$namber=0;}
$lines[$namber]=isset($lines[$namber]) ? $lines[$namber] : '';
$datos2 = explode("|", $lines[$namber]);
$datos2[1]=isset($datos2[1]) ? $datos2[1] : '';
$datos[1]=isset($datos[1]) ? $datos[1] : '';
if($datos2[1]==$ip){$rime2=$datos2[4];$rime32=$datos2[0];}
if($datos[1]==$ip){$rime=$datos[4];$rime3=$datos[0];$cat=$datos[3];$lug=$datos[2];}}

$rime2=isset($rime2) ? $rime2 : '';
$rime=isset($rime) ? $rime : '';
$lug=isset($lug) ? $lug : '';


if(($time < $rime + 4) && $lug==$r){
    
if($cat > 4){$mnc='1';} 
if($cat > 29){escribir2();}
$cat += 1; escribir($cat,$time,$r,$cantidad);}

else{escribir('1',$time,$r,$cantidad);return false;}

}else{return true;} }} ?>