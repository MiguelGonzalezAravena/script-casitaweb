<?php require("cw-conexion-seg-0011.php");
global $tranfer1, $context, $settings, $options,$no_avatar, $txt,$user_settings,$user_info, $scripturl, $modSettings;
global $db_prefix, $scripturl, $modSettings;
$myuser=$user_settings['ID_MEMBER'];
if(empty($myuser))fatal_error('Hubo un grabe error.-');
$id=(int) $_GET['id'];
if(empty($id)){fatal_error('Debes seleccionar una nota a eliminar.-',false);}else{
$contador=mysqli_num_rows(db_query("SELECT id_user FROM {$db_prefix}notas WHERE id_user='$myuser' AND id='$id'", __FILE__, __LINE__));
if(empty($contador)){fatal_error('La nota que deseas eliminar no existe.-',false);}

else{db_query("DELETE FROM {$db_prefix}notas WHERE id='$id' AND id_user='$myuser'", __FILE__, __LINE__);
Header("Location: /mis-notas/");exit();die();}}

?>