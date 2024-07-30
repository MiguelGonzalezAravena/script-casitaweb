<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $context, $db_prefix, $tranfer1, $user_info;

$seg = isset($_GET['seg']) ? seguridad($_GET['seg']) : '';
$verificacion = isset($_POST['verificacion']) ? seguridad($_POST['verificacion']) : '';
$email = isset($_POST['emailverificar']) ? seguridad($_POST['emailverificar']) : '';

$no = '
  <div style="float: left;">
    <img alt="" src="' . $tranfer1 . '/icons/no.png" class="png" width="16px" height="16px" />
  </div>';

$si = '
  <div style="float: left;">
    <img alt="" src="' . $tranfer1 . '/icons/si.png" class="png" width="16px" height="16px" />
  </div>';

if (!$user_info['is_guest']) {
  die('<div style="height: 14px; width: 122px; border: solid 1px #C25B43; background-color: #F7ABA1; font-size: 11px; font-family: Arial; padding: 2px;">' . $no . '  Funcionalidad exclusiva de visitantes.</div>');
} else {
  switch ($seg) {
    case '001':
      if (empty($verificacion)) {
        die('<div style="height: 16px; width: 122px; border: solid 1px #C25B43; background-color: #F7ABA1; font-size: 11px; font-family: Arial; padding: 2px;">' . $no . ' <div>Debes agregar el nick</div></div>');
      }

      if (!preg_match('~[\s]~', stripslashes($verificacion)) == 0) {
        die('<div style="height: 16px; width: 122px; border: solid 1px #C25B43; background-color: #F7ABA1; font-size: 11px; font-family: Arial; padding: 2px;">' . $no . ' <div>Nick sin espacios</div></div>');
      }

      if (!preg_match('~[^a-zA-Z0-9_\-]~', stripslashes($verificacion)) == 0) {
        die('<div style="height: 16px; width: 122px; border: solid 1px #C25B43; background-color: #F7ABA1; font-size: 11px; font-family: Arial; padding: 2px;">' . $no . ' <div>Caracteres no v&aacute;lidos</div></div>');
      }

      if (!empty($verificacion) && checkNick($verificacion)) {
        die('<div style="height: 16px; width: 122px; border: solid 1px #C25B43; background-color: #F7ABA1; font-size: 11px; font-family: Arial; padding: 2px;">' . $no . ' <div>El nick no est&aacute; <span title="disponible">disp</span></div></div>');
      } else {
        die('<div style="height: 16px; width: 122px; font-family: Arial; border: solid 1px #2D832A; background-color: #B2DBA8; font-size: 11px; padding: 2px;">' . $si . ' <div>El nick est&aacute; <span title="disponible">disp</span></div></div>');
      }
      break;
    case '002':
      if (empty($email)) {
        die('<div style="height: 16px; width: 122px; border: solid 1px #C25B43; background-color: #F7ABA1; font-size: 11px; font-family: Arial; padding: 2px;">' . $no . ' <div>Debes agregar el correo</div></div>');
      }

      if (preg_match('~^[0-9A-Za-z=_+\-/][0-9A-Za-z=_\'+\-/\.]*@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$~', stripslashes($email)) == 0) {
        die('<div style="height: 16px; width: 122px; border: solid 1px #C25B43; background-color: #F7ABA1; font-size: 11px; font-family: Arial; padding: 2px;">' . $no . ' <div>Correo no v&aacute;lido</div></div>');
      }

      if (checkEmail($email)) {
        die('<div style="height: 16px; width: 122px; border: solid 1px #C25B43; background-color: #F7ABA1; font-size: 11px; font-family: Arial; padding: 2px;">' . $no . ' <div>Correo no disponible</div></div>');
      } else {
        die('<div style="height: 16px; width: 122px; font-family: Arial; border: solid 1px #2D832A; background-color: #B2DBA8; font-size: 11px; padding: 2px;">' . $si . ' <div>Correo disponible</div></div>');
      }
      break;
  }
}

function checkNick($nick) {
  global $db_prefix;

  $request = db_query("
    SELECT realName
    FROM {$db_prefix}members
    WHERE realName = '$nick'", __FILE__, __LINE__);

  $registro = mysqli_fetch_row($request);

  return (!empty($registro) ? true : false);
}

function checkEmail($email) {
  global $db_prefix;

  $request = db_query("
    SELECT emailAddress
    FROM {$db_prefix}members
    WHERE emailAddress = '$email'", __FILE__, __LINE__);

  $registro = mysqli_fetch_row($request);

  return (!empty($registro) ? true : false);
}

?>