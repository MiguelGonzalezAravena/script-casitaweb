<?php require("cw-conexion-seg-0011.php");
global $tranfer1, $txt, $db_prefix, $context, $scripturl, $sourcedir;
$id=(int)$_POST['id'];
require($sourcedir . '/Subs-Post.php');

if(empty($id)){fatal_error('Debes seleccionar una imagen a enviar.-');}
	$request = db_query("
		SELECT i.ID_PICTURE
		FROM ({$db_prefix}gallery_pic as i)
		WHERE i.ID_PICTURE='{$id}'
		LIMIT 1", __FILE__, __LINE__);
	if (mysqli_num_rows($request) == 0){fatal_error('Esta imagen no existe.-', false);}
	$row=mysqli_fetch_assoc($request);
	mysqli_free_result($request);
    
$_POST['r_email']=trim($_POST['r_email']);
if($_POST['r_email']){
	if (preg_match('~^[0-9A-Za-z=_+\-/][0-9A-Za-z=_\'+\-/\.]*@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$~', stripslashes($_POST['r_email'])) == 0)
		fatal_lang_error(243, false);}
	if($_POST['r_email1']){
	if (preg_match('~^[0-9A-Za-z=_+\-/][0-9A-Za-z=_\'+\-/\.]*@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$~', stripslashes($_POST['r_email1'])) == 0)
		fatal_lang_error(243, false);}
	if($_POST['r_email2']){
	if (preg_match('~^[0-9A-Za-z=_+\-/][0-9A-Za-z=_\'+\-/\.]*@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$~', stripslashes($_POST['r_email2'])) == 0)
		fatal_lang_error(243, false);}
	if($_POST['r_email3']){
	if (preg_match('~^[0-9A-Za-z=_+\-/][0-9A-Za-z=_\'+\-/\.]*@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$~', stripslashes($_POST['r_email3'])) == 0)
		fatal_lang_error(243, false);}
	if($_POST['r_email4']){
	if (preg_match('~^[0-9A-Za-z=_+\-/][0-9A-Za-z=_\'+\-/\.]*@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$~', stripslashes($_POST['r_email4'])) == 0)
		fatal_lang_error(243, false);}
	if($_POST['r_email5']){
	if (preg_match('~^[0-9A-Za-z=_+\-/][0-9A-Za-z=_\'+\-/\.]*@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$~', stripslashes($_POST['r_email5'])) == 0)
		fatal_lang_error(243, false);}
if(empty($_POST['r_email'])){fatal_error('Debe agregar el primer e-mail.-');}

$emailse = array($_POST['r_email'],$_POST['r_email1'],$_POST['r_email2'],$_POST['r_email3'],$_POST['r_email4'],$_POST['r_email5']);

$_POST['comment']=trim(nohtml2(nohtml(censorText($_POST['comment']))));

captcha(2);
   
sendmail($emailse, $_POST['titulo'], sprintf('Este mensaje ha sido enviado desde casitaweb.net:') . "\n\n" . sprintf($_POST['comment']) . "\n\n" . sprintf('Enlace: <a href="http://casitaweb.net/imagenes/ver/'.$row['ID_PICTURE'].'">http://casitaweb.net/imagenes/ver/'.$row['ID_PICTURE'].'</a>'));
        
        
Header("Location: /imagenes/ver/{$row['ID_PICTURE']}"); exit();die(); ?>