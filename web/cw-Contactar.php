<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $sourcedir, $user_settings, $mbname, $webmaster_email;

$_POST['nombre'] = isset($_POST['nombre']) ? seguridad($_POST['nombre']) : '';
$_POST['email'] = isset($_POST['email']) ? seguridad($_POST['email']) : '';
$_POST['empresa'] = isset($_POST['empresa']) ? seguridad($_POST['empresa']) : '';
$_POST['tel'] = isset($_POST['tel']) ? seguridad($_POST['tel']) : '';
$_POST['hc'] = isset($_POST['hc']) ? seguridad($_POST['hc']) : '';
$_POST['motivo'] = isset($_POST['motivo']) ? seguridad($_POST['motivo']) : '';
$_POST['comentario'] = isset($_POST['comentario']) ? seguridad($_POST['comentario']) : '';

if (empty($_POST['nombre'])) {
  fatal_error('Debes agregar tu nombre y apellido.');
}

if (empty($_POST['email'])) {
  fatal_error('Debes agregar tu correo.');
}

if ($_POST['email']) {
  if (preg_match('~^[0-9A-Za-z=_+\-/][0-9A-Za-z=_\'+\-/\.]*@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$~', stripslashes($_POST['email'])) == 0) {
    fatal_error('Tu correo electr&oacute;nico est&aacute; mal escrito, por favor rev&iacute;salo.', false);
  }
}

if (empty($_POST['motivo'])) {
  fatal_error('Debes seleccionar el motivo.');
}

if (empty($_POST['comentario'])) {
  fatal_error('Debes agregar el comentario.');
}

// Validar recaptcha
$recaptcha_response = isset($_POST['g-recaptcha-response']) ? seguridad($_POST['g-recaptcha-response']) : '';
$challenge = recaptcha_validation($recaptcha_response);

if (!$challenge) {
  fatal_error('Lo sentimos, no pudimos verificar que eres un humano. Por favor, int&eacute;ntalo de nuevo.');
}

if (empty($_SERVER['REMOTE_ADDR'])) {
  fatal_error('S&oacute;lo personas con una direcci&oacute;n IP pueden contactarse con ' . $mbname . '.');
}

require_once($sourcedir . '/Subs-Post.php');

sendmail(
  $webmaster_email,
  $_POST['nombre'] . ' te contact&oacute;',
  sprintf('Nombre: ' . $_POST['nombre']) . "\n" .
  sprintf('Correo: ' . $_POST['email']) . "\n" .
  sprintf('Empresa: ' . $_POST['empresa']) . "\n" .
  sprintf('Tel&eacute;fono: ' . $_POST['tel']) . "\n" .
  sprintf('Horario de contacto: ' . $_POST['hc']) . "\n" .
  sprintf('Motivo: ' . $_POST['motivo']) . "\n" .
  sprintf('IP: ' . $_SERVER['REMOTE_ADDR']) . "\n\n" .
  sprintf('Comentario:') . "\n" .
  sprintf($_POST['comentario']) . "\n\n" .
  sprintf('----------') . "\n" .
  sprintf('Inici&oacute; sesi&oacute;n como: ' . $user_settings['realName'])
);

fatal_error('Mensaje enviado correctamente.', false, '&iexcl;Gracias por tu contacto!');

?>