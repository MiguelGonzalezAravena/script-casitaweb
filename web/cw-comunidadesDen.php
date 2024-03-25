<?php require("cw-conexion-seg-0011.php"); global $db_prefix,$sourcedir,$user_info,$user_settings;
$idCom=(int)$_POST['comu'];

if(!$idCom){fatal_error('Debes seleccionar una comunidad.-');}
$rs=db_query("SELECT c.id,c.nombre
FROM ({$db_prefix}comunidades AS c)
WHERE c.id='$idCom'
LIMIT 1",__FILE__, __LINE__);
while ($row=mysql_fetch_assoc($rs)){$url=$row['nombre'];$Esta=$row['id'];}
if(!$Esta){fatal_error('Debes seleccionar una comunidad.-');}

include($sourcedir.'/FuncionesCom.php');
permisios($Esta);
if(!$context['user']['is_guest'] && !$context['permisoCom']){
$_POST=stripslashes__recursive($_POST);
$_POST=addslashes__recursive($_POST);

$razon=trim($_POST['razon']);
$comentario=trim($_POST['comentario']);
if(!$razon || !$comentario){fatal_error('Todos los campos son obligatorios.-');}
if(strlen($comentario) > 300){fatal_error('El comentario es demasiado extenso, abrevi&aacute;.-');}
if(strlen($razon) > 100){fatal_error('La raz&oacuten es demasiada extensa, abrevi&aacute;.-');}

$ya_denuncie=mysql_num_rows(db_query("
SELECT d.id_denuncia 
FROM ({$db_prefix}denuncias AS d) 
WHERE d.id_user='{$user_settings['ID_MEMBER']}' AND d.id_post='$Esta' AND d.tipo=5 LIMIT 1",__FILE__, __LINE__));
if($ya_denuncie){fatal_error('Ya denunciastes esta comunidad.');}

db_query("INSERT INTO {$db_prefix}denuncias 
(comentario, razon, name_post, id_post, id_user, tipo, tiempo) 
VALUES ('$comentario', '$razon', '$url', '$Esta', '{$user_settings['ID_MEMBER']}', 5, ".time().")", __FILE__, __LINE__);
            
}
            
fatal_error('Tu denuncia fue enviada correctamente<br/>Un moderador del sitio la verificar&aacute; en breve, Gracias',false,'Denuncia enviada');
exit();die();
?>