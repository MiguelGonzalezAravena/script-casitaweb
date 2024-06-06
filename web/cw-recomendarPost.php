<?php require("cw-conexion-seg-0011.php"); global $tranfer1, $func,$ID_MEMBER, $context,$db_prefix;
$posts=isset($_POST['post']) ? (int)$_POST['post'] : '';
if(empty($posts))die('0: Este post no existe2.');
$request = db_query("
		SELECT m.subject,b.description
		FROM ({$db_prefix}messages AS m, {$db_prefix}boards AS b)
		WHERE m.ID_TOPIC='$posts' AND m.ID_BOARD=b.ID_BOARD
		LIMIT 1", __FILE__, __LINE__);
if(mysqli_num_rows($request)==0)die('0: Este post no existe.');
$row=mysqli_fetch_assoc($request);
mysqli_free_result($request);
if(empty($_POST['r_email'])){die('0: Debe agregar el primer e-mail.');}

if($_POST['r_email']){
	if (preg_match('~^[0-9A-Za-z=_+\-/][0-9A-Za-z=_\'+\-/\.]*@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$~', stripslashes($_POST['r_email'])) == 0)
		die('0: E-mail mal escrito.');}
	if($_POST['r_email1']){
	if (preg_match('~^[0-9A-Za-z=_+\-/][0-9A-Za-z=_\'+\-/\.]*@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$~', stripslashes($_POST['r_email1'])) == 0)
		die('0: E-mail mal escrito.');}
	if($_POST['r_email2']){
	if (preg_match('~^[0-9A-Za-z=_+\-/][0-9A-Za-z=_\'+\-/\.]*@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$~', stripslashes($_POST['r_email2'])) == 0)
		die('0: E-mail mal escrito.');}
	if($_POST['r_email3']){
	if (preg_match('~^[0-9A-Za-z=_+\-/][0-9A-Za-z=_\'+\-/\.]*@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$~', stripslashes($_POST['r_email3'])) == 0)
		die('0: E-mail mal escrito.');}
	if($_POST['r_email4']){
	if (preg_match('~^[0-9A-Za-z=_+\-/][0-9A-Za-z=_\'+\-/\.]*@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$~', stripslashes($_POST['r_email4'])) == 0)
		die('0: E-mail mal escrito.');}
	if($_POST['r_email5']){
	if (preg_match('~^[0-9A-Za-z=_+\-/][0-9A-Za-z=_\'+\-/\.]*@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$~', stripslashes($_POST['r_email5'])) == 0)
		die('0: E-mail mal escrito.');}

$_POST['comment']=trim($_POST['comment']);	
$titulo=seguridad($_POST['titulo']);
if(!isset($titulo) || empty($titulo))die('0: Debes agregar un asunto.');
if(strlen($titulo)>=61){die('0: El Asunto no puede tener m&aacute;s de 60 letras.');}
if(empty($_POST['comment'])){die('0: Debes escribir un comentario.-');}
if(strlen($_POST['comment'])>=700){die('0: El comentario no puede tener 700 o m&aacute; letras.-');}

captcha(3);

$emailse = array($_POST['r_email'],$_POST['r_email1'],$_POST['r_email2'],$_POST['r_email3'],$_POST['r_email4'],$_POST['r_email5']);
require($sourcedir.'/Subs-Post.php');

censorText($row['subject']);

sendmail($emailse, $titulo,	sprintf('Este mensaje ha sido enviado desde casitaweb.net:') . "\n\n" .	sprintf($_POST['comment']) . "\n\n" .	'Enlace: <a href="http://casitaweb.net/post/'.$posts.'/'.$row['description'].'/'.censorText(urls($row['subject'])).'.html">http://casitaweb.net/post/'.$posts.'/'.$row['description'].'/'.censorText(urls($row['subject'])).'.html</a>');
die('1: Post recomendado correctamente.');
exit;die();?>