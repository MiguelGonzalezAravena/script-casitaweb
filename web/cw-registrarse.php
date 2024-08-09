<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $txt, $modSettings, $db_prefix, $user_info, $no_avatar, $sourcedir;

if (session_id() == '') {
  session_start();
}

$nombre = isset($_POST['nombre']) ? seguridad($_POST['nombre']) : '';
$nick = isset($_POST['user']) ? seguridad($_POST['user']) : '';
$passwrd1 = isset($_POST['passwrd1']) ? seguridad($_POST['passwrd1']) : '';
$passwrd2 = isset($_POST['passwrd2']) ? seguridad($_POST['passwrd2']) : '';
$email = isset($_POST['email']) ? seguridad($_POST['email']) : '';
$pais = isset($_POST['pais']) ? seguridad($_POST['pais']) : '';
$ciudad = isset($_POST['ciudad']) ? seguridad($_POST['ciudad']) : '';
$gh = isset($_POST['sexo']) ? (int) $_POST['sexo'] : 0;
$avatar = str_replace($no_avatar, '', $_POST['avatar']);
$avatar = isset($_POST['avatar']) ? seguridad($_POST['avatar']) : '';
$MP = isset($_POST['personalText']) ? seguridad(censorText($_POST['personalText'])) : '';
$URL = isset($_POST['url']) ? seguridad(censorText(str_replace('http://', '', $_POST['url']))) : '';

if (!empty($modSettings['registration_method']) && $modSettings['registration_method'] == 3) {
  fatal_error('El registro de usuarios se encuentra desactivado por el momento.');
}

if (empty($nombre)) {
  fatal_error('Debes agregar tu nombre y apellido.');
}

if (!preg_match('[^a-zA-Z\s_]', stripslashes($nombre)) == 0) {
  fatal_error('El nombre y apellido tiene caracteres no v&aacute;lidos.');
}

if (empty($nick)) {
  fatal_error('Debes poner tu nick.');
}

if (strlen($nick) > 20) {
  fatal_error('El nick se excede de los 20 caracteres.');
}

if (!preg_match('~[^a-zA-Z0-9_\-\s]~', stripslashes($nick)) == 0) {
  if (!preg_match('~[\s]~', stripslashes($nick)) == 0) {
    fatal_error('El nick no puede tener espacios, Puedes utilizar: Gui&oacute;n medio (-) o Gui&oacute;n bajo (_) en lugar de espacio.');
  }

  fatal_error('El nick no puede tener espacios, Puedes utilizar: Gui&oacute;n medio (-) o Gui&oacute;n bajo (_) en lugar de espacio.');
}

$request = db_query("
  SELECT ID_MEMBER
  FROM {$db_prefix}members
  WHERE realName = '$nick'
  LIMIT 1", __FILE__, __LINE__);

$rows = mysqli_num_rows($request);

if ($rows != 0) {
  fatal_error('El nick que intentas utilizar ya est&aacute; en uso.');
}

mysqli_free_result($request);


if (empty($passwrd1)) {
  fatal_error('Lo sentimos, debes agregar la contrase&ntilde;a.');
}

if (strlen($passwrd1) < 8) {
  fatal_error('Lo sentimos, la contrase&ntilde;a debe ser mayor a 8 caracteres.');
}

if ($passwrd1 != $passwrd2) {
  fatal_error('Lo sentimos, las contrase&ntilde;as no coinciden.');
}


require_once($sourcedir . '/Subs-Auth.php');
$passwordError = validatePassword($passwrd1, $nick, array($email));

if ($passwordError != null) {
  fatal_error('profile_error_password_' . $passwordError, false);
}

if (empty($email) || preg_match('~^[0-9A-Za-z=_+\-/][0-9A-Za-z=_\'+\-/\.]*@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$~', stripslashes($email)) === 0 || strlen(stripslashes($email)) > 255) {
  fatal_error(sprintf($txt[500], $nick));
}

$request = db_query("
  SELECT ID_MEMBER
  FROM {$db_prefix}members
  WHERE emailAddress = '$email'
  LIMIT 1", __FILE__, __LINE__);

$rows = mysqli_num_rows($request);

if ($rows != 0) {
  fatal_error('El correo electr&oacute;nico que intentas utilizar ya est&aacute; en uso.');
}

mysqli_free_result($request);

if ($pais == -1) {
  fatal_error('Debes seleccionar el pa&iacute;s donde vives.');
} else if ($pais == 'ar' || $pais == 'bo' || $pais == 'br' || $pais == 'cl' || $pais == 'co' || $pais == 'cr' || $pais == 'cu' || $pais == 'ec' || $pais == 'es' || $pais == 'gt' || $pais == 'it' || $pais == 'mx' || $pais == 'py' || $pais == 'pe' || $pais == 'pt' || $pais == 'pr' || $pais == 'uy' || $pais == 've' || $pais == 'ot') {
  // TO-DO: ¿Qué se hace en este caso?
  // TO-DO:  Validar que país exista
} else {
  fatal_error('El pa&iacute;s seleccinado no est&aacute; en la lista.');
}

if ($gh != 1 &&  $gh != 2) {
  fatal_error('S&oacute;lo se permite sexo Masculino y Femenino.');
}

if (isset($_POST['birthdate']) && !empty($_POST['birthdate'])) {
  $_POST['birthdate'] = strftime('%Y-%m-%d', strtotime($_POST['birthdate']));
} else if (!empty($_POST['bday1']) && !empty($_POST['bday2'])) {
  $_POST['birthdate'] = sprintf('%04d-%02d-%02d', empty($_POST['bday3']) ? 0 : (int) $_POST['bday3'], (int) $_POST['bday1'], (int) $_POST['bday2']);
}

$cumple = seguridad($_POST['birthdate']);

// reCaptcha challenge
$recaptcha_response = isset($_POST['g-recaptcha-response']) ? seguridad($_POST['g-recaptcha-response']) : '';
$challenge = recaptcha_validation($recaptcha_response);

if (!$challenge) {
  // var_dump($recaptcha_response);
  // var_dump($challenge);
  fatal_error('Lo sentimos, no pudimos verificar que eres un humano. Por favor, int&eacute;ntalo de nuevo.');
}
// var_dump($_SESSION['numeroxxx']);
// captcha(2);

if (empty($_POST['regagree']) || $_POST['regagree'] !== 'on') {
  fatal_error('Debes aceptar los T&eacute;rminos de uso y condiciones.');
}

$pw = sha1(strtolower($nick) . $passwrd1);
$pws = substr(md5(rand()), 0, 4);

db_query("
  INSERT INTO {$db_prefix}members (nombre, memberName, realName, emailAddress, passwd, passwordSalt, dateRegistered, memberIP, memberIP2, personalText, websiteTitle, avatar, gender, location, usertitle, birthdate)
  VALUES ('$nombre', '$nick', '$nick', '$email', '$pw', '$pws', " . time() . ", '{$user_info['ip']}', '{$_SERVER['BAN_CHECK_IP']}', '$MP', '$URL', '$avatar', $gh, '$ciudad', '$pais', '$cumple')", __FILE__, __LINE__);

$memberID = db_insert_id();
$realName = substr($nick, 1, -1);

updateStats('member', $memberID, $realName);
estadisticastopic(1);

$_SESSION['just_registered'] = 1;

unset($_POST);

if ($modSettings['registration_method'] == 1) {
  fatal_error('Su cuenta fue creada exitosamente.<br/>Se envi&oacute; un mensaje a la direcci&oacute;n de email especificada.<br/>Por favor, leer su contenido.', false, '&iexcl;&iexcl;Felicitaciones!!');
} else {
  fatal_error('Su cuenta fue creada exitosamente, ya puede ingresar al sitio web con su cuenta.', false, '&iexcl;&iexcl;Felicitaciones!!');
}

?>