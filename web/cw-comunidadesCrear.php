<?php require("cw-conexion-seg-0011.php"); global $db_prefix,$user_info,$user_settings;

if($user_info['is_guest']){fatal_error('faltan datos.-');}

ignore_user_abort(true);
@set_time_limit(300);

$_POST=stripslashes__recursive($_POST);
$_POST=addslashes__recursive($_POST);
$nombre=trim($_POST['nombre']);

if(!$user_settings['ID_GROUP']){
if($user_settings['ID_POST_GROUP']=='4'){$cantidadcom='1';}
elseif($user_settings['ID_POST_GROUP']=='5'){$cantidadcom='2';}
elseif($user_settings['ID_POST_GROUP']=='9'){$cantidadcom='4';}
elseif($user_settings['ID_POST_GROUP']=='10'){$cantidadcom='6';}
elseif($user_settings['ID_POST_GROUP']=='6'){$cantidadcom='8';}
elseif($user_settings['ID_POST_GROUP']=='8'){$cantidadcom='10';}}
elseif($user_settings['ID_GROUP']){$cantidadcom='15';}

if(strlen($nombre)<5 || strlen($nombre)>55){fatal_error('El nombre debe tener entre 5 y 55 letras.-');}
$descripcion=trim($_POST['descripcion']);
if(strlen($descripcion)<5 || strlen($nombre)>2000){fatal_error('La descripci&oacute;n debe tener entre 5 y 2000 letras.-');}
$acceso=(int)$_POST['privada'];
if(!$acceso){fatal_error('Debes seleccionar un acceso.-');}
$aprobar=(int)$_POST['aprobar'];
$permiso=(int)$_POST['rango_default'];
if(!$permiso){fatal_error('Debes seleccionar un permiso.-');}
$url=trim($_POST['shortname']);
if(!preg_match('~[^a-zA-Z0-9\-]~',stripslashes($url))==0){fatal_error('Solo se permiten letras, n&uacute;meros y guiones medios (-).');}else{


$rss=db_query("
SELECT c.bloquear,c.id
FROM ({$db_prefix}comunidades as c) 
WHERE c.url='$url'
ORDER BY c.id DESC 
LIMIT 1",__FILE__, __LINE__);
while ($row=mysqli_fetch_assoc($rss)){
    $igual=$row['id'];
    $bloquear=$row['bloquear'];}
    
if($igual && $bloquear){
$letras = '0x1o2m3b4r5a6H7b8c9dZ';
srand((double)microtime()*1000000);
$i = 1;
$largo_clave = 6;
$largo = strlen($letras);
$clave_usuario='';
while ($i <= $largo_clave)
  { $lee = rand( 1,$largo);
     $clave_usuario .= substr($letras, $lee, 1); 
    $i++;} $clave_usuario=trim($clave_usuario);
$ddssNAMEEDITADO=$url.'-ELI-'.$clave_usuario;

db_query("UPDATE {$db_prefix}comunidades
			SET url='$ddssNAMEEDITADO'
			WHERE id='$igual'
			LIMIT 1", __FILE__, __LINE__);}
            
elseif($igual && !$bloquear){fatal_error('El nombre seleccionado ya est&aacute; en uso.');}elseif(strlen($url)<5 || strlen($url)>32 ){fatal_error('El nombre debe tener entre 5 y 32 caracteres.');}}
$imagen=trim($_POST['imagen']);
$cat=trim($_POST['categoria']);
if(!$cat || $cat=='-1'){fatal_error('Debes elegir una categor&iacute;a');}
$comunidades_categorias=mysqli_num_rows(db_query("SELECT c.url FROM ({$db_prefix}comunidades_categorias AS c) WHERE c.url='$cat' LIMIT 1",__FILE__, __LINE__));
if(!$comunidades_categorias){fatal_error('Esta categor&iacuet;a no existe.');}
$cuantascom=mysqli_num_rows(db_query("SELECT c.id_user FROM ({$db_prefix}comunidades AS c) WHERE c.id_user='{$user_settings['ID_MEMBER']}' AND c.bloquear=0",__FILE__, __LINE__));
if($cuantascom>$cantidadcom){
fatal_error('Tu rango no te permite tener m&aacute;s de '.$cantidadcom.' comunidades.',false);}

db_query("INSERT INTO {$db_prefix}comunidades (id_user, nombre, descripcion, acceso, permiso, aprobar, url, imagen, fecha_inicio, categoria,UserName) 
    VALUES ('{$user_settings['ID_MEMBER']}', SUBSTRING('$nombre', 1,100), SUBSTRING('$descripcion', 1, 2500), '$acceso', '$permiso', '$aprobar', '$url', '$imagen', ".time().", '$cat','{$user_settings['realName']}')", __FILE__, __LINE__);
    
$ddss = db_insert_id();

db_query("INSERT INTO {$db_prefix}comunidades_miembros (id_user, id_com, fecha, rango) 
    VALUES ('{$user_settings['ID_MEMBER']}', '$ddss', ".time().", '1')", __FILE__, __LINE__);
    

db_query("UPDATE {$db_prefix}comunidades_categorias
			SET comunidades=comunidades+1
			WHERE url='$cat'
			LIMIT 1", __FILE__, __LINE__);
db_query("UPDATE {$db_prefix}comunidades
			SET usuarios=usuarios+1
			WHERE id='$ddss'
			LIMIT 1", __FILE__, __LINE__);  
        
Header("Location: /comunidades/$url/");exit();die(); 
?>