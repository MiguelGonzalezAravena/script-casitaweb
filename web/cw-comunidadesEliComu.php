<?php require("cw-conexion-seg-0011.php"); global $db_prefix,$context,$sourcedir,$user_settings;
include($sourcedir.'/FuncionesCom.php');
if($user_info['is_guest']){fatal_error('Faltan datos.-');}

$id=seguridad($_GET['id']);
if(!$id){fatal_error('Faltan datos.');}

$rs44=db_query("
SELECT co.id,co.categoria
FROM ({$db_prefix}comunidades AS co)
WHERE co.url='$id' AND co.bloquear=0
LIMIT 1",__FILE__, __LINE__);
while ($row=mysqli_fetch_assoc($rs44)){
    $id_com=$row['id'];
    $categ=$row['categoria'];}
$id_com=isset($id_com) ? $id_com : '';
if(empty($id_com)){fatal_error('Faltan datos.');}

baneadoo($id_com);
permisios($id_com);

if($context['permisoCom']=='1'){
db_query("UPDATE {$db_prefix}comunidades
			SET bloquear=1
			WHERE id='$id_com'
			LIMIT 1", __FILE__, __LINE__);
db_query("UPDATE {$db_prefix}comunidades_categorias
			SET comunidades=comunidades-1
			WHERE url='$categ'
			LIMIT 1", __FILE__, __LINE__);
}

Header("Location: /comunidades/");
die();exit();
?>