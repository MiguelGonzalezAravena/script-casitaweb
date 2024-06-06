<?php require("cw-conexion-seg-0011.php");
global $ID_MEMBER, $txt, $db_prefix, $scripturl,$modSettings, $boarddir,$sourcedir,$user_settings,$user_info;
if($user_info['is_guest']){fatal_error('No tienes permisos para estar aca.-',false);}
$context['idgrup'] = $user_settings['ID_POST_GROUP'];
$context['leecher'] = $user_settings['ID_POST_GROUP'] == '4';
$context['novato'] = $user_settings['ID_POST_GROUP'] == '5';
$context['buenus'] = $user_settings['ID_POST_GROUP'] == '6';
if($context['leecher']){fatal_error('Los usuarios de rango Turistas no pueden editar im&aacute;genes.-',false,'',4);}
$id=(int)$_POST['id'];
if(empty($id))fatal_error('Debes seleccionar una im&aacute;gen',false,'',4);
    $dbresult = db_query("
	SELECT p.ID_MEMBER,m.realName
	FROM ({$db_prefix}gallery_pic as p, {$db_prefix}members as m)
	WHERE p.ID_PICTURE='$id' AND p.ID_MEMBER=m.ID_MEMBER
	LIMIT 1", __FILE__, __LINE__);
	$row=mysqli_fetch_assoc($dbresult);
	$memID=$row['ID_MEMBER'];
	$realName=$row['realName'];
	mysqli_free_result($dbresult);
	
if(empty($memID))fatal_error('La im&aacute;gen seleccionada no existe.-',false,'',4);
if(($user_info['is_admin'] || $user_info['is_mods']) || $ID_MEMBER==$memID){
$title=seguridad(nohtml2($_POST['title']));
if(empty($title)){fatal_error('Debes agregar un titulo.-',false,'',4);}
if(strlen($title)<=3){fatal_error('El titulo debe tener m&aacute;s de 3 letras.-',false,'',4);}
if(strlen($title)>55){fatal_error('El titulo es muy largo.-',false,'',4);}
$filename=seguridad($_POST['filename']);
if(strlen($filename)>110){fatal_error('El enlace no puede ser mayor de <b>110 letras</b><br/>Subi la im&aacute;gen a <a href="'.$modSettings['host_imagen'].'">'.$modSettings['host_imagen'].'</a> que los enlaces son cortos.-');}
if(empty($filename)){fatal_error('Debes agregar el enlace de la im&aacute;gen.-',false,'',4);}

db_query("
UPDATE {$db_prefix}gallery_pic
SET ID_CAT='1', title='$title', filename=SUBSTRING('$filename', 1, 110)
WHERE ID_PICTURE='$id'
LIMIT 1", __FILE__, __LINE__);

Header("Location: /imagenes/{$realName}");exit();die();}
else {fatal_error('Usted no tiene permisos para editar esta im&aacute;gen.-',false,'',4);}
?>