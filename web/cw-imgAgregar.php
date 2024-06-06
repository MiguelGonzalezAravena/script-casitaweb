<?php require("cw-conexion-seg-0011.php");
global $context, $modSettings, $user_settings, $user_info, $db_prefix;
if($user_info['is_guest']){die();}

$context['leecher'] = $user_settings['ID_POST_GROUP'] == '4';
if($context['leecher']){die('0: Los usuarios de rango Turistas no pueden agregar im&aacute;genes.');}

$limit3=db_query("
SELECT date
FROM ({$db_prefix}gallery_pic)
WHERE ID_MEMBER='{$user_settings['ID_MEMBER']}'
ORDER BY date DESC
LIMIT 1", __FILE__, __LINE__);
while($lim2=mysqli_fetch_assoc($limit3)){$modifiedTime=$lim2['date'];}
if($modifiedTime>time()-60){die('0: No es posible agregar im&aacute;gen con tan poca diferencia de tiempo.<br />Vuelva a intentar en segundos.');}

$title=seguridad(nohtml2($_POST['title']));
if(empty($title)){die('0: Debes agregar un titulo.');}
if(strlen($title)<=3){die('0: El titulo debe tener m&aacute;s de 3 letras.');}
if(strlen($title)>55){die('0: El titulo es muy largos.');}
$url=seguridad($_POST['url']);
if(valida_url($url)==null){die('0: La URL indicada no contiene im&aacute;gen.');}
if(empty($url)){die('0: Debes agregar el enlace de la im&aacute;gen.');}
if(strlen($url)>110){die('0: El enlace no puede ser mayor de <b>110 letras</b><br/>Subi la im&aacute;gen a <a href="'.$modSettings['host_imagen'].'">'.$modSettings['host_imagen'].'</a> que los enlaces son cortos.');}


$date=time();

db_query("INSERT INTO {$db_prefix}gallery_pic
		(ID_CAT,filename,title,ID_MEMBER,date)
		VALUES ('1',SUBSTRING('$url', 1, 110),'$title','{$user_settings['ID_MEMBER']}','$date')", __FILE__, __LINE__);
$id = db_insert_id();
pts_sumar_grup($user_settings['ID_MEMBER']);

unset($_POST);
die('1: Tu im&aacute;gen fue agregada correctamente. (<a href="/imagenes/ver/'.$id.'">Ver im&aacute;gen</a>).'); ?>