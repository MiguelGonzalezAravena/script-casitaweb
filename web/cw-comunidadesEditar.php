<?php require("cw-conexion-seg-0011.php"); global $db_prefix,$sourcedir,$user_info,$user_settings;

if($user_info['is_guest']){fatal_error('faltan datos.-');}

$idCom=(int)$_POST['idcom'];

if(empty($idCom)){fatal_error('Debes seleccionar una comunidad.-');}
$rs=db_query("SELECT c.id,c.url
FROM ({$db_prefix}comunidades AS c)
WHERE c.id='$idCom'
LIMIT 1",__FILE__, __LINE__);
while ($row=mysqli_fetch_assoc($rs)){$url=$row['url'];$Esta=$row['id'];}
$Esta=isset($Esta) ? $Esta : '';
if(empty($Esta)){fatal_error('Debes seleccionar una comunidad.-');}

include($sourcedir.'/FuncionesCom.php');
permisios($Esta);
if($context['permisoCom']=='1'){
    
ignore_user_abort(true);
@set_time_limit(300);

$_POST=stripslashes__recursive($_POST);
$_POST=addslashes__recursive($_POST);
$nombre=trim($_POST['nombre']);

if(strlen($nombre) < 5 || strlen($nombre) > 55){fatal_error('El nombre debe tener entre 5 y 55 letras.-');}
$descripcion=trim($_POST['descripcion']);

if(strlen($descripcion) < 5 || strlen($nombre) > 2000){fatal_error('La descripci&oacute;n debe tener entre 5 y 2000 letras.-');}

$acceso=(int)$_POST['privada'];
if(!$acceso){fatal_error('Debes seleccionar un acceso.-');}

$aprobar=(int)$_POST['aprobar'];
   
$permiso=(int)$_POST['rango_default'];
if(!$permiso){fatal_error('Debes seleccionar un permiso.-');}

$imagen=trim($_POST['imagen']);
$cat=trim($_POST['categoria']);
if(!$cat || $cat=='-1'){fatal_error('Debes elegir una categor&iacute;a');}
$comunidades_categorias=mysqli_num_rows(db_query("SELECT c.url FROM ({$db_prefix}comunidades_categorias AS c) WHERE c.url='$cat' LIMIT 1",__FILE__, __LINE__));
if(!$comunidades_categorias){fatal_error('Esta categor&iacuet;a no existe.');}

if(!$aprobar){
    db_query("UPDATE {$db_prefix}comunidades_miembros
			SET aprobado='1'            
			WHERE id_com='$Esta'", __FILE__, __LINE__);}

db_query("UPDATE {$db_prefix}comunidades
			SET nombre=SUBSTRING('$nombre', 1,100), 
            descripcion=SUBSTRING('$descripcion', 1, 2500), 
            acceso='$acceso',
            permiso='$permiso', 
            aprobar='$aprobar',
            imagen='$imagen', 
            categoria='$cat'                        
			WHERE id='$Esta'                       
			LIMIT 1", __FILE__, __LINE__);}
            
Header("Location: /comunidades/$url/");exit();die(); 
?>