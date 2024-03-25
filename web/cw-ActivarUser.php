<?php require("cw-conexion-seg-0011.php"); global $user_info,$sourcedir,$db_prefix;
if($user_info['is_admin']){
$_GET['u']=isset($_GET['u']) ? (int) $_GET['u'] : '';
if(!empty($_GET['u'])){

$request = db_query("
		SELECT emailAddress,realName
		FROM {$db_prefix}members
		WHERE ID_MEMBER = '{$_GET['u']}'
		LIMIT 1", __FILE__, __LINE__);
        if (mysql_num_rows($request) == 0){die();}
        $row = mysql_fetch_assoc($request);
        mysql_free_result($request);
        
updateMemberData($_GET['u'], array('is_activated' => 1, 'validation_code' => '\'\''));
require($sourcedir . '/Subs-Post.php');
sendmail($row['emailAddress'],'Cuenta re-activada',
		"Le contamos que su cuenta en casitaweb.net fue reactivada.\n\n" .
			"Nick: ´{$row['emailAddress']} \n Password: ****** <span style='fontsize:8px;color:grey;'>(Oculta por seguridad)</span>\n\n" .
			"Si tiene problemas con su cuenta no dude en contactarnos: <a href='http://casitaweb.net/contactanos/'>http://casitaweb.net/contactanos/</a>");
}}
die();
?>