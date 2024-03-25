<?php require("cw-conexion-seg-0011.php");
global $func, $ID_MEMBER, $modSettings, $db_prefix;

if(empty($ID_MEMBER))die();
$jetid=(int)$_POST['id'];
if(empty($jetid))die();

$titulo=trim($_POST['titulo']);
if(empty($titulo)){fatal_error('Debes escribir un titulo a la nota.-',false);}
if(strlen($titulo)>=61){fatal_error('El titulo no puede tener m&aacute;s de 60 letras.-',false);}


$contenido=trim($_POST['contenido']);
if(empty($contenido)){fatal_error('Debes escribir la nota.-',false);}
if(strlen($contenido)>$modSettings['max_messageLength']){fatal_error('El post no puede tener m&aacute;s de '.$modSettings['max_messageLength'].' letras.-',false);}

$fecha=time(); 
db_query("UPDATE {$db_prefix}notas SET titulo='$titulo',contenido='$contenido', fecha_editado='$fecha' WHERE id='$jetid' AND id_user='$ID_MEMBER' LIMIT 1", __FILE__, __LINE__);

Header("Location: /mis-notas/");exit();die();
?>