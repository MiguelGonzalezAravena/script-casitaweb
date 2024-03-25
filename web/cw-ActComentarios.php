<?php require("cw-conexion-seg-0011.php");
global $context,$user_info,$db_prefix,$modSettings; ?>
<?php  if($user_info['is_guest']){ ?>
<div class="noesta-am">Solo Usuarios REGISTRADOS pueden actualizar los comentarios.<br /><a href="/registrarse/">REGISTRATE</a> - <a href="#" onclick="javascript: servicenavlogin();">CONECTATE</a></div>
<?php } else{ 
if(!$user_info['is_admin']){$shas=' AND m.ID_BOARD<>142';}else{$shas='';}
$rs=db_query("
SELECT c.id_post,m.ID_TOPIC,c.id_user,mem.ID_MEMBER,m.ID_BOARD,b.ID_BOARD,c.id_coment,m.subject,b.description,memberName,realName
FROM ({$db_prefix}comentarios AS c, {$db_prefix}messages AS m, {$db_prefix}members AS mem, {$db_prefix}boards as b)
WHERE c.id_post=m.ID_TOPIC AND c.id_user=mem.ID_MEMBER AND m.ID_BOARD=b.ID_BOARD$shas
ORDER BY c.id_coment DESC
LIMIT $modSettings[catcoment]",__FILE__, __LINE__);
$context['comentarios25']=array();
while($row=mysql_fetch_assoc($rs)){
censorText($row['subject']);
$context['comentarios25'][] = array(
		'id_coment' => $row['id_coment'],
			'titulo' => censorText($row['subject']),
			'ID_TOPIC' => $row['ID_TOPIC'],
			'description' => $row['description'],
			'memberName' => $row['memberName'],
			'realName' => $row['realName'],
		);}mysql_free_result($rs);

foreach ($context['comentarios25'] as $coment25){ ?>
<font class="size11" ><b><a title="<?php echo $coment25['realName']; ?>" href="/perfil/<?php echo $coment25['realName']; ?>"><?php echo $coment25['realName']; ?></a></b> - <a title="<?php echo $coment25['titulo'];?>"  href="/post/<?php echo $coment25['ID_TOPIC']; ?>/<?php echo $coment25['description'];?>/<?php echo urls($coment25['titulo']);?>.html#cmt_<?php echo $coment25['id_coment'];?>"><?php echo achicars($coment25['titulo']);?></a></font><br style="margin:0px;padding:0px;" />
<?php }} ?>