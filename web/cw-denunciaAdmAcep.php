<?php require("cw-conexion-seg-0011.php");
global $db_prefix,$user_settings,$user_info;
if($user_info['is_admin'] || $user_info['is_mods']){    
$post=seguridad($_GET['id']);
$denunciante=(int) $_GET['den'];
$idden=(int) $_GET['ident'];

if(empty($post) || empty($denunciante) || empty($idden)){die('0: Faltan datos.');}

$request=db_query("
SELECT p.ID_MEMBER
FROM ({$db_prefix}messages as p)
WHERE p.ID_TOPIC='{$post}'
LIMIT 1",__FILE__, __LINE__);
while ($post = mysql_fetch_assoc($request)){$mem=$post['ID_MEMBER'];}
$mem=isset($mem) ? $mem : '';

    db_query("UPDATE {$db_prefix}members 
    SET posts=posts+1 
    WHERE ID_MEMBER='$denunciante' 
    LIMIT 1",__FILE__, __LINE__);
    
    
    db_query("UPDATE {$db_prefix}denuncias 
    SET borrado=2, atendido='{$user_settings['realName']}' 
    WHERE id_denuncia='$idden' 
    LIMIT 1",__FILE__, __LINE__);

if(!empty($mem)){pts_sumar_grup($mem);}
if(!empty($denunciante)){pts_sumar_grup($denunciante);}}
die('1: Okey'); ?>