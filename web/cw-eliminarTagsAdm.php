<?php require("cw-conexion-seg-0011.php"); 
global $tranfer1, $context, $settings, $db_prefix, $options, $txt,$user_settings,$user_info, $scripturl;
if($user_settings['realName']=='rigo'){
$palabra=$_POST['palabra'];
if(!empty($palabra)){db_query("DELETE FROM {$db_prefix}tags WHERE palabra='$palabra'",__FILE__, __LINE__);}
Header("Location: /admin/tags/");}
else{Header("Location: /");}
?>