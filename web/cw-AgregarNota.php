<?php require("cw-conexion-seg-0011.php");
global $context, $func,  $modSettings, $db_prefix, $user_settings;
if(empty($user_settings['ID_MEMBER']))die();

timeforComent(1);

$titulo=trim($_POST['titulo']);
if(empty($titulo)){fatal_error('Debes escribir un titulo a la nota.');}
if(strlen($titulo)>=61){fatal_error('El titulo no puede tener m&aacute;s de 60 letras.');}

$titulos=strtr($func['htmlspecialchars']($titulo), array("\r" => '', "\n" => '', "\t" => ''));
$titulos=addcslashes($titulos, '"');
$$titulos=censorText($titulos);
$contenido=trim($_POST['contenido']);

if(empty($contenido)){fatal_error('Debes escribir la nota.');}
if(strlen($contenido)>$modSettings['max_messageLength']){fatal_error('La nota no puede tener m&aacute;s de '.$modSettings['max_messageLength'].' letras.');}

$contenidos=$func['htmlspecialchars'](stripslashes($contenido), ENT_QUOTES);
$contenidos=str_replace(array('"', '<', '>', '  '), array('&quot;', '&lt;', '&gt;', ' &nbsp;'), $contenidos);
$contenidos= preg_replace('~<br(?: /)?' . '>~i', "\n", $contenidos);
$contenidos=censorText($contenidos);
$fecha=time();

db_query("INSERT INTO {$db_prefix}notas (id_user,titulo,contenido,fecha_creado) VALUES ('{$user_settings['ID_MEMBER']}','$titulos', '$contenidos','$fecha')", __FILE__, __LINE__);

$_SESSION['ultima_accionTIME']=$fecha;

Header("Location: /mis-notas/"); die(); ?>