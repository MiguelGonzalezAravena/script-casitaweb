<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $sourcedir, $user_settings, $mbname;

$_POST['nombre'] = trim($_POST['nombre']);
$_POST['email'] = trim($_POST['email']);
$_POST['motivo'] = trim($_POST['motivo']);
$_POST['comentario'] = trim($_POST['comentario']);

if (empty($_POST['nombre'])) {
  fatal_error('Debes agregar tu nombre y apellido.');
}

if (empty($_POST['email'])) {
  fatal_error('Debes agregar tu correo.');
}

if ($_POST['email']) {
  if (preg_match('~^[0-9A-Za-z=_+\-/][0-9A-Za-z=_\'+\-/\.]*@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$~', stripslashes($_POST['email'])) == 0)
    fatal_error('Tu correo electr&oacute;nico est&aacute; mal escrito, por favor rev&iacute;salo.', false);
}

if (empty($_POST['motivo'])) {
  fatal_error('Debes agregar el motivo.');
}

if (empty($_POST['comentario'])) {
  fatal_error('Debes agregar el comentario.');
}

captcha(2);

if (empty($_SERVER['REMOTE_ADDR'])) {
  fatal_error('S&oacute;lo personas con una direcci&oacute;n IP pueden contactarse con ' . $mbname . '.');
}

require_once($sourcedir . '/Subs-Post.php');

sendmail(
  'soporte@casitaweb.net',
  un_htmlspecialchars($_POST['nombre']) . ' te contact&oacute;',
  sprintf('Nombre: ' . un_htmlspecialchars($_POST['nombre']) . '') . "\n" .
  sprintf('Correo: ' . $_POST['email'] . '') . "\n" .
  sprintf('Empresa: ' . $_POST['empresa'] . '') . "\n" .
  sprintf('Tel&eacute;fono: ' . $_POST['tel'] . '') . "\n" .
  sprintf('Horario de contacto: ' . $_POST['hc'] . '') . "\n" .
  sprintf('Motivo: ' . $_POST['motivo'] . '') . "\n" .
  sprintf('IP: ' . $_SERVER['REMOTE_ADDR'] . '') . "\n\n" .
  sprintf('Comentario:') . "\n" .
  sprintf(nohtml($_POST['comentario'])) . "\n\n" .
  sprintf('----------') . "\n" .
  sprintf('Logueado como: ' . $user_settings['realName']));

fatal_error('Mensaje enviado correctamente', false, 'OK');

?>