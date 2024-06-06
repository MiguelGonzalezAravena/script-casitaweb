<?php require("cw-conexion-seg-0011.php");global $db_prefix,$user_settings,$user_info,$sourcedir,$context;

if(!$user_info['is_guest']){
$limit3=db_query("
SELECT fecha
FROM ({$db_prefix}mensaje_personal)
WHERE id_de='{$user_settings['ID_MEMBER']}'
ORDER BY fecha DESC
LIMIT 1", __FILE__, __LINE__);
while($lim2=mysqli_fetch_assoc($limit3)){$modifiedTime=$lim2['fecha'];}
if($modifiedTime>time()-25){die('0: No es posible enviar mensajes con tan poca diferencia de tiempo.<br />Vuelva a intentar en segundos.');}


$para=str_replace('\"','',$_POST['para']);
$para=seguridad($para);
$datosmem=db_query("
SELECT ID_MEMBER
FROM ({$db_prefix}members)
WHERE realName='$para'
LIMIT 1", __FILE__, __LINE__);
while($data=mysqli_fetch_assoc($datosmem)){$id_para=$data['ID_MEMBER'];}

$admitir=mysqli_num_rows(db_query("SELECT id_user,quien FROM ({$db_prefix}pm_admitir) WHERE id_user='$id_para' AND quien='{$user_settings['ID_MEMBER']}' LIMIT 1", __FILE__, __LINE__));

if(empty($admitir)){
$titulo=$_POST['titulo'];
$mensaje=$_POST['mensaje'];
require($sourcedir.'/Subs-Post.php');
sendpm($titulo,$mensaje,$id_para,'0');}else{die('0: No podes enviarle MP a este user.');}
exit('1: Mensaje enviado correctamente.');die();}
?>