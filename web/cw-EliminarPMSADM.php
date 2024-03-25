<?php require("cw-conexion-seg-0011.php");
global $ID_MEMBER, $db_prefix;
if($ID_MEMBER=='1'){
$aLista=array_keys($_POST['campos']);
db_query("DELETE FROM {$db_prefix}mensaje_personal WHERE id IN (".implode(',',$aLista).")", __FILE__, __LINE__);
$pag=(int)$_POST['pag'];
if($pag){Header("Location: /moderacion/pms/pag-$pag");}else{Header("Location: /moderacion/pms/");}

exit();die();
}else{Header("Location: /");exit();die();}
?>