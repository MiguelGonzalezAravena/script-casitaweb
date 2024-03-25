<?php require("cw-conexion-seg-0011.php");
global $context,$db_prefix, $tranfer1,$user_info,$user_settings,$no_avatar;
if($user_info['is_guest']){die();}

if($user_settings['notificacionMonitor'] > 10){db_query("UPDATE {$db_prefix}members SET notificacionMonitor=notificacionMonitor-10 WHERE ID_MEMBER='{$user_settings['ID_MEMBER']}' LIMIT 1", __FILE__, __LINE__); 
}elseif($user_settings['notificacionMonitor'] > 0){
db_query("UPDATE {$db_prefix}members SET notificacionMonitor=notificacionMonitor-'{$user_settings['notificacionMonitor']}' WHERE ID_MEMBER='{$user_settings['ID_MEMBER']}' LIMIT 1", __FILE__, __LINE__); }

$datosmem=db_query("
SELECT que,url,por_quien,leido,fecha,extra,id
FROM ({$db_prefix}notificaciones) 
WHERE a_quien='{$user_settings['ID_MEMBER']}'
ORDER BY id DESC
LIMIT 10", __FILE__, __LINE__); 
while ($data=mysql_fetch_assoc($datosmem)){
if(empty($data['leido'])){$backCOLOR='background-color: #FFEAA8;';}else{$backCOLOR='background-color: #F2F2F2;';}
$MIEMBRO=db_query("
SELECT realName
FROM ({$db_prefix}members) 
WHERE ID_MEMBER='{$data['por_quien']}' 
LIMIT 1", __FILE__, __LINE__); 
while ($dd=mysql_fetch_assoc($MIEMBRO)){$realName=$dd['realName'];$avatar=$dd['avatar'];}
mysql_free_result($MIEMBRO);

echo'<div id="NOTup" onclick="location.href=\''.$data['url'].'\'"><div style="padding:4px;'.$backCOLOR.'border-bottom: 1px dotted #C8C8C8;margin-bottom:2px;"><strong style="font-size:14px;color:#444;">'.$realName.'</strong> <span style="font-size:10px;color:#444;">'.hace($data['fecha']).'</span><br /><span style="color:#D35F2C;">'.notificacionQUE($data['que'],$data['url'],$data['extra'],'no').'</div></div>';

db_query("UPDATE {$db_prefix}notificaciones SET leido='1' WHERE id='{$data['id']}' LIMIT 1", __FILE__, __LINE__);
$dac='1';
}
mysql_free_result($datosmem);

$dac=isset($dac) ? $dac : '';
if(empty($dac)){echo'<div class="noesta">No tenes nuevas notificaciones.</div>';}

?>