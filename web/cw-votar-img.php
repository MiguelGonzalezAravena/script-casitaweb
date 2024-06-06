<?php require("cw-conexion-seg-0011.php"); 
global $context, $user_settings,$user_info,$db_prefix,$modSettings;

if($user_info['is_guest']){die('0: Debes estar conectado.-');}
$id=(int)$_GET['imagen'];
$cantidad=(int)$_GET['cantidad'];
if(empty($id)){die('0: Debes seleccionar una im&aacute;gen.');}

$dbs=db_query("
SELECT ID_MEMBER,title
FROM {$db_prefix}gallery_pic
WHERE ID_PICTURE='$id'
LIMIT 1", __FILE__, __LINE__);
while($grups = mysqli_fetch_assoc($dbs)){
$user=$grups['ID_MEMBER'];
$title=$grups['title'];}
mysqli_free_result($dbs);
$user=isset($user) ? $user : '';
if(empty($user)){die('0: La im&aacute;gen no existe o fue eliminada.');}

$errorr=db_query("
SELECT id_user
FROM {$db_prefix}gallery_cat
WHERE id_user='{$user_settings['ID_MEMBER']}' AND id_img='{$id}'
LIMIT 1", __FILE__, __LINE__);
$yadio=mysqli_num_rows($errorr) != 0 ? true : false; mysqli_free_result($errorr);
	if($user_settings['posts'] < $cantidad)
	die('0: No tienes esa cantidad de puntos.-');
	elseif ($cantidad < 1)
	die('0: Debes ingresar una cantidad valida.-');
	if(empty($cantidad))
	die('0: Debes especificar una candidad.-');
	if(empty($user))
	die('0: Hubo un error.-');
	if($user==$user_settings['ID_MEMBER'])
	die('0: No puedes dar puntos a tus im&aacute;genes.-');
	if($yadio)
    die('0: Ya has dado puntos a esta im&aacute;gen.-');
    if($cantidad > $modSettings['puntos_por_post-img']){
    die('0: Solo puedes dar '.$modSettings['puntos_por_post-img'].' puntos para cada im&aacute;gen.-');}
    if($cantidad > $user_settings['puntos_dia'])
    die('0: Solo tienes '.$user_settings['puntos_dia'].' puntos disponibles para dar.-');

	        $fecha=time();
            
	       db_query("
				UPDATE {$db_prefix}members
				SET posts=posts+{$cantidad}
				WHERE ID_MEMBER='{$user}'
				LIMIT 1", __FILE__, __LINE__);
		   db_query("
				UPDATE {$db_prefix}members
				SET puntos_dia=puntos_dia-{$cantidad}, TiempoPuntos='$fecha'
				WHERE ID_MEMBER='{$ID_MEMBER}'
				LIMIT 1", __FILE__, __LINE__);
		   db_query("
				UPDATE {$db_prefix}gallery_pic
				SET puntos=puntos+{$cantidad}
				WHERE ID_PICTURE='{$id}'
				LIMIT 1", __FILE__, __LINE__);
			db_query("
                INSERT INTO {$db_prefix}gallery_cat (id_img,id_user,cantidad,fecha)
                values('$id','{$user_settings['ID_MEMBER']}','$cantidad','$fecha')", __FILE__, __LINE__);


notificacionAGREGAR($user,'4',$cantidad);

pts_sumar_grup($user);
          
die('1: Puntos agregados!'); ?>