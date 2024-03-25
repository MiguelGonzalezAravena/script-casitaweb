<?php require("cw-conexion-seg-0011.php");
global $user_info; 
if ($user_info['is_admin']){
    
db_query("DELETE FROM {$db_prefix}log_actions", __FILE__, __LINE__);

die('1: Ok');

}else{die('0: No');}

?>