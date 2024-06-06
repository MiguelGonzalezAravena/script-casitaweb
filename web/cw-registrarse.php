<?php require("cw-conexion-seg-0011.php");
global $txt, $modSettings, $db_prefix, $user_info, $no_avatar, $sourcedir;
if(!empty($modSettings['registration_method']) && $modSettings['registration_method'] == 3){fatal_error('El registro de usuarios se encuentra desactivado por el momento.-');}

$nombre=nohtml(seguridad($_POST['nombre']));
if(empty($nombre)){fatal_error('Debes agregar tu nombre y apellido.');}
elseif(!preg_match('[^a-zA-Z\s_]',stripslashes($nombre))==0){
fatal_error('El nombre y apellido tiene car&aacute;cteres inv&aacute;lidos.');}

$nick=seguridad($_POST['user']);
if(empty($nick)){fatal_error('Debes poner tu nick.');}
elseif(strlen($nick)>20){fatal_error('El nick se exede de los 20 car&aacute;cteres.');}
elseif(!preg_match('~[^a-zA-Z0-9_\-\s]~',stripslashes($nick))==0){
if(!preg_match('~[\s]~',stripslashes($nick))==0){
fatal_error('El nick no puede tener espacios, Puedes utilizar: Gui&oacute;n medio (-) o Gui&oacute;n bajo (_) en lugar de espacio.');}    
fatal_error('El nick no puede tener espacios, Puedes utilizar: Gui&oacute;n medio (-) o Gui&oacute;n bajo (_) en lugar de espacio.');}
$request = db_query("
		SELECT ID_MEMBER
		FROM {$db_prefix}members
		WHERE realName = '$nick'
		LIMIT 1", __FILE__, __LINE__);
if (mysqli_num_rows($request) != 0){fatal_error('El nick que intentas utilizar ya esta en uso.');}
mysqli_free_result($request);


$passwrd1=seguridad($_POST['passwrd1']);
$passwrd2=seguridad($_POST['passwrd2']);
$email=seguridad($_POST['email']);

if(empty($passwrd1)){fatal_error('Lo sentimos, debes agregar la contrase&ntilde;a.');}
elseif(strlen($passwrd1) < 8){fatal_error('Lo sentimos, la contrase&ntilde;a debe ser mayor a 8 caracteres.');}
if ($passwrd1 != $passwrd2)fatal_error('Lo sentimos, las contrase&ntilde;as no coinciden.');

$email=seguridad($_POST['email']);

require($sourcedir . '/Subs-Auth.php');
$passwordError = validatePassword($passwrd1, $nick, array($email));
if ($passwordError != null)fatal_error('profile_error_password_' . $passwordError, false);

if (empty($email) || preg_match('~^[0-9A-Za-z=_+\-/][0-9A-Za-z=_\'+\-/\.]*@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$~', stripslashes($email)) === 0 || strlen(stripslashes($email)) > 255)fatal_error(sprintf($txt[500], $nick));
$request = db_query("
		SELECT ID_MEMBER
		FROM {$db_prefix}members
		WHERE emailAddress = '$email'
		LIMIT 1", __FILE__, __LINE__);
if (mysqli_num_rows($request) != 0){fatal_error('El e-mail que intentas utilizar ya esta en uso.');}
mysqli_free_result($request);


//PAIS
$pais=seguridad($_POST['pais']);
if($pais=='-1'){fatal_error('Debes seleccionar el pa&iacute;s donde vives.');}
elseif($pais=='ar' || $pais=='bo' || $pais=='br' ||  $pais=='cl' || $pais=='co' || $pais=='cr' || $pais=='cu' || $pais=='ec' || $pais=='es' || $pais=='gt' || $pais=='it' || $pais=='mx' || $pais=='py' || $pais=='pe' || $pais=='pt' ||  $pais=='pr' || $pais=='uy' || $pais=='ve' || $pais=='ot'){}
else{fatal_error('El pa&iacute;s seleccinado no est&aacute; en la lista.');}
//FIN PAIS

//--------------------

//CIUDAD
$ciudad=seguridad($_POST['ciudad']);
//FIN CIUDAD

//--------------------

//SEXO
$gh=(int)$_POST['sexo'];
if($gh == '1' || $gh  == '2'){}
else{fatal_error('Solo se permite sexo Masculino y Femenino.');}
//FIN SEXO

if(isset($_POST['birthdate']) && !empty($_POST['birthdate'])){
$_POST['birthdate'] = strftime('%Y-%m-%d', strtotime($_POST['birthdate']));}
elseif(!empty($_POST['bday1']) && !empty($_POST['bday2'])){
$_POST['birthdate'] = sprintf('%04d-%02d-%02d', empty($_POST['bday3']) ? 0 : (int) $_POST['bday3'], (int) $_POST['bday1'], (int) $_POST['bday2']);}
$cumple=seguridad($_POST['birthdate']);


$avatar=str_replace("$no_avatar","",$_POST['avatar']);
$avatar=nohtml(seguridad($_POST['avatar']));

//URL
$URL=seguridad(censorText(str_replace('http://','',$_POST['url'])));
//FIN URL

//--------------------

//MP
$MP=seguridad(censorText($_POST['personalText']));
//FIN MP

//--------------------

captcha('2');

//--------------------

if(empty($_POST['regagree']) || $_POST['regagree']!=='on'){
fatal_error('Debes aceptar los T&eacute;rminos de uso y condiciones.');}
	
$pw=sha1(strtolower($nick) . $passwrd1);
$pws=substr(md5(rand()), 0, 4);

db_query("INSERT INTO {$db_prefix}members (nombre, memberName, realName, emailAddress, passwd, passwordSalt, dateRegistered, memberIP, memberIP2, personalText, websiteTitle, avatar, gender, location, usertitle, birthdate) 
VALUES ('$nombre', '$nick', '$nick', '$email', '$pw', '$pws', '".time()."', '$user_info[ip]', '$_SERVER[BAN_CHECK_IP]', '$MP', '$URL', '$avatar', '$gh', '$ciudad', '$pais', '$cumple')", __FILE__, __LINE__);
        
$memberID = db_insert_id();
$realName = substr($nick, 1, -1);
updateStats('member', $memberID, $realName);
estadisticastopic(1);

$_SESSION['just_registered'] = 1;
unset($_POST);
if($modSettings['registration_method']=='1'){
fatal_error('Su cuenta fue creada exitosamente.<br/>Se envi&oacute; un mensaje a la direcci&oacute;n de email especificada.<br/>Por favor, leer su contenido.-',false,'Felicitaciones!!');}
else{fatal_error('Su cuenta fue creada exitosamente, ya puede ingresar al sitio web con su cuenta.',false,'Felicitaciones!!');}
?>