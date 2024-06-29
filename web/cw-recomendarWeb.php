<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $tranfer1, $func, $ID_MEMBER, $context, $db_prefix;

$titulo = seguridad($_POST['titulo']);
$emailse = array($_POST['r_email'], $_POST['r_email1'], $_POST['r_email2'], $_POST['r_email3'], $_POST['r_email4'], $_POST['r_email5']);

if (empty($_POST['r_email'])) {
	fatal_error('Debe agregar el primer e-mail.', false);
}

if ($_POST['r_email']) {
	if (preg_match('~^[0-9A-Za-z=_+\-/][0-9A-Za-z=_\'+\-/\.]*@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$~', stripslashes($_POST['r_email'])) == 0) {
		fatal_error('Caracter no v&aacute;lido en el correo electr&oacute;nico.', false);
	}
}

if ($_POST['r_email1']) {
	if (preg_match('~^[0-9A-Za-z=_+\-/][0-9A-Za-z=_\'+\-/\.]*@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$~', stripslashes($_POST['r_email1'])) == 0) {
		fatal_error('Caracter no v&aacute;lido en el correo electr&oacute;nico.', false);
	}
}

if ($_POST['r_email2']) {
	if (preg_match('~^[0-9A-Za-z=_+\-/][0-9A-Za-z=_\'+\-/\.]*@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$~', stripslashes($_POST['r_email2'])) == 0) {
		fatal_error('Caracter no v&aacute;lido en el correo electr&oacute;nico.', false);
	}
}

if ($_POST['r_email3']) {
	if (preg_match('~^[0-9A-Za-z=_+\-/][0-9A-Za-z=_\'+\-/\.]*@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$~', stripslashes($_POST['r_email3'])) == 0) {
		fatal_error('Caracter no v&aacute;lido en el correo electr&oacute;nico.', false);
	}
}

if ($_POST['r_email4']) {
	if (preg_match('~^[0-9A-Za-z=_+\-/][0-9A-Za-z=_\'+\-/\.]*@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$~', stripslashes($_POST['r_email4'])) == 0) {
		fatal_error('Caracter no v&aacute;lido en el correo electr&oacute;nico.', false);
	}
}

if ($_POST['r_email5']) {
	if (preg_match('~^[0-9A-Za-z=_+\-/][0-9A-Za-z=_\'+\-/\.]*@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$~', stripslashes($_POST['r_email5'])) == 0) {
		fatal_error('Caracter no v&aacute;lido en el correo electr&oacute;nico.', false);
	}
}

if (strlen($titulo) >= 61) {
	fatal_error('El Asunto no puede tener m&aacute;s de 60 letras.', false);
}

if (!isset($titulo) || empty($titulo)) {
	fatal_error('Debes agregar un asunto.', false);
}

if (empty($_POST['comment'])) {
	fatal_error('Debes escribir un comentario.');
}

if (strlen($_POST['comment']) >= 700) {
	fatal_error('El comentario no puede tener 700 o m&aacute; letras.');
}

captcha(2);

require_once($sourcedir . '/Subs-Post.php');

sendmail(
	$emailse,
	$titulo,
	sprintf('Un persona te recomienda este sitio: ' . $boardurl . ', y dice:') . "\n\n" .
	sprintf($_POST['comment']) . "\n\n" .
	'Sitio Web: <a href="' . $boardurl . '/">' . $boardurl . '/</a>'
);

fatal_error('Muchas gracias por recomendar <b>' . $mbname . '</b>.', false, '&iexcl;&iexcl;&iexcl;Gracias!!!');

?>