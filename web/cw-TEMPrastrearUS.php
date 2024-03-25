<?php require("cw-conexion-seg-0011.php");
global $db_prefix, $scripturl, $modSettings,$user_profile,$tranfer1,$ID_MEMBER,$context,$ajaxError;
if(empty($context['ajax'])){echo $ajaxError; die();}
if(!$user_info['is_admin']){die('<div class="noesta" style=";width:552px;">Error.</div>');}

$memID=isset($_GET['id']) ? (int)$_GET['id'] : '';
if(empty($memID)){die('<div class="noesta" style="width:552px;">Error.</div>');}
$request = db_query("
			SELECT memberIP, realName,memberIP2
			FROM {$db_prefix}members
			WHERE ID_MEMBER = '$memID'
            LIMIT 1", __FILE__, __LINE__);
		if (mysql_num_rows($request) > 0)
			while ($row = mysql_fetch_assoc($request)){
			 $context['last_ip'] = $row['memberIP'];
             $context['member']['name'] = $row['realName'];
             $ips = array(
             $row['memberIP'],
             $row['memberIP2'],
             ); }
		mysql_free_result($request);


$ips = array_unique($ips);
$context['members_in_range'] = array();
if(!empty($ips))
{
		$request = db_query("
			SELECT ID_MEMBER, realName
			FROM {$db_prefix}members
			WHERE ID_MEMBER != $memID
				AND memberIP IN ('" . implode("', '", $ips) . "')", __FILE__, __LINE__);
		if (mysql_num_rows($request) > 0)
			while ($row = mysql_fetch_assoc($request))
				$context['members_in_range'][$row['ID_MEMBER']] = '<a href="/perfil/'.$row['realName'].'">'.$row['realName'].'</a>';
		mysql_free_result($request);

		$request = db_query("
			SELECT mem.ID_MEMBER, mem.realName
			FROM ({$db_prefix}messages AS m, {$db_prefix}members AS mem)
			WHERE mem.ID_MEMBER = m.ID_MEMBER
				AND mem.ID_MEMBER != $memID
				AND m.posterIP IN ('" . implode("', '", $ips) . "')", __FILE__, __LINE__);
		if (mysql_num_rows($request) > 0)
			while ($row = mysql_fetch_assoc($request))
				$context['members_in_range'][$row['ID_MEMBER']] = '<a href="/perfil/'.$row['realName'].'">' . $row['realName'] . '</a>';
		mysql_free_result($request);}


echo'<div style="width:552px;">';


echo'<strong>IPs:</strong><br /><a href="http://lacnic.net/cgi-bin/lacnic/whois?query='.$ips[0].'">'.$ips[0].'</a> <a href="http://lacnic.net/cgi-bin/lacnic/whois?query='.$ips[1].'">'.$ips[1].'</a><br /><br />
<strong>Usuarios con la misma IP:</strong><br />
', (count($context['members_in_range']) > 0 ? implode(', ', $context['members_in_range']) : 'No hay'), '';


echo'</div>'; ?>