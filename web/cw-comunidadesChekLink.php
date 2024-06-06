<?php require("cw-conexion-seg-0011.php"); global $db_prefix,$user_info;

if($user_info['is_guest']){echo'0: faltan datos.-';}else{

$_POST['shortname']=seguridad($_POST['shortname']);

$igual=mysqli_fetch_row(db_query("SELECT c.url
FROM ({$db_prefix}comunidades AS c)
WHERE c.url='{$_POST['shortname']}' AND c.bloquear=0 LIMIT 1",__FILE__, __LINE__));

if($igual){echo'0: El nombre seleccionado ya est&aacute; en uso.';}
elseif(!preg_match('~[^a-zA-Z0-9\-]~',stripslashes($_POST['shortname']))==0){echo'0: Solo se permiten letras, n&uacute;meros y guiones medios (-).';}
elseif(strlen($_POST['shortname'])<5 || strlen($_POST['shortname'])>32 ){echo'0: El nombre debe tener entre 5 y 32 caracteres.';}
else{echo'1: El nombre est&aacute; disponible!';}}
?>