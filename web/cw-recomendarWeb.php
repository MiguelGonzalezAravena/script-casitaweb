<?php require("cw-conexion-seg-0011.php"); global $tranfer1, $func,$ID_MEMBER, $context,$db_prefix;

	if(empty($_POST['r_email'])){fatal_error('Debe agregar el primer e-mail.-',false);}
   	if($_POST['r_email']){
	if (preg_match('~^[0-9A-Za-z=_+\-/][0-9A-Za-z=_\'+\-/\.]*@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$~', stripslashes($_POST['r_email'])) == 0)
		fatal_error('Car&aacute;cter inválido en el Email.', false);}
	if($_POST['r_email1']){
	if (preg_match('~^[0-9A-Za-z=_+\-/][0-9A-Za-z=_\'+\-/\.]*@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$~', stripslashes($_POST['r_email1'])) == 0)
		fatal_error('Car&aacute;cter inválido en el Email.', false);}
	if($_POST['r_email2']){
	if (preg_match('~^[0-9A-Za-z=_+\-/][0-9A-Za-z=_\'+\-/\.]*@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$~', stripslashes($_POST['r_email2'])) == 0)
		fatal_error('Car&aacute;cter inválido en el Email.', false);}
	if($_POST['r_email3']){
	if (preg_match('~^[0-9A-Za-z=_+\-/][0-9A-Za-z=_\'+\-/\.]*@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$~', stripslashes($_POST['r_email3'])) == 0)
		fatal_error('Car&aacute;cter inválido en el Email.', false);}
	if($_POST['r_email4']){
	if (preg_match('~^[0-9A-Za-z=_+\-/][0-9A-Za-z=_\'+\-/\.]*@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$~', stripslashes($_POST['r_email4'])) == 0)
		fatal_error('Car&aacute;cter inválido en el Email.', false);}
	if($_POST['r_email5']){
	if (preg_match('~^[0-9A-Za-z=_+\-/][0-9A-Za-z=_\'+\-/\.]*@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$~', stripslashes($_POST['r_email5'])) == 0)
		fatal_error('Car&aacute;cter inválido en el Email.', false);}

   $titulo=seguridad($_POST['titulo']);
   if(strlen($titulo)>=61){fatal_error('El Asunto no puede tener m&aacute;s de 60 letras.-', false);}
   if (!isset($titulo) || empty($titulo))
   fatal_error('Debes agregar un asunto.-', false);
   $emailse = array($_POST['r_email'],$_POST['r_email1'],$_POST['r_email2'],$_POST['r_email3'],$_POST['r_email4'],$_POST['r_email5']);
   if(empty($_POST['comment'])){fatal_error('Debes escribir un comentario.-');}
   if(strlen($_POST['comment'])>=700){fatal_error('El comentario no puede tener 700 o m&aacute; letras.-');}
   captcha(2);
   require($sourcedir . '/Subs-Post.php');
   sendmail($emailse,$titulo,
		sprintf('Un persona te recomienda este sitio: www.casitaweb.net, y dice:') . "\n\n" .
		sprintf($_POST['comment']) . "\n\n" .
		'Sitio Web: <a href="http://casitaweb.net/">http://casitaweb.net/</a>');
fatal_error('Muchas gracias por recomendar <b>CasitaWeb!</b>.-',false,'Gracias!!!');
exit;die();?>