<?php require("cw-conexion-seg-0011.php");
global $db_prefix,$user_settings,$user_info;
if($user_info['is_admin'] || $user_info['is_mods']){
    
$denunciante=(int)$_GET['den'];
$idden=(int) $_GET['ident'];

if(empty($denunciante) || empty($idden)){die('0: Faltan datos.');}
    
    db_query("UPDATE {$db_prefix}denuncias 
    SET borrado=1, atendido='{$user_settings['realName']}' 
    WHERE id_denuncia='$idden'
    LIMIT 1",__FILE__, __LINE__);
    
pts_sumar_grup($denunciante);}

die('1: Okey'); ?>