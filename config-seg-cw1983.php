<?php

$mbname = base64_decode('Q2FzaXRhV2ViIQ==');
$webmaster_email = 'soporte@casitaweb.net';
// TO-DO: Modificar ruta según corresponda
$helpurl = 'http://localhost/casitaweb/sub-147s/ayuda';

$maintenance = 0;

$mtitle = '
  <div style="border-top: 2px solid #DEB7B7; border-bottom: 2px solid #DEB7B7; padding-top: 10px; padding-bottom: 10px; margin: 0px; width: 100%; color: #495461; text-align: center; background-color: #F3DDDD; font-size: 11px; font-weight: bold;">
    <b style="color: red; font-size: 14px; font-family: Arial;">' . $mbname . ' se encuentra en mantenimiento.</b>
    <br />
    <b style="font-size: 13px;">
      <i>
        Disculpe las molestias. Si deseas contactarte con nosotros: <a href="mailto:' . $webmaster_email . '" title="e-mail">' . $webmaster_email . '</a>
      </i>
    </b>
  </div>';

$ajaxError = '
  <span style="color: #444; font: Arial 12px;">
    Este archivo s&oacute;lo se ejecuta desde casitaweb.net
    <br />
    Cualquier inquietud: ' . $webmaster_email . '
    <br/>
    &lt;&lt;
    <a href="/" style="color: #444;">Ir al inicio de ' . $mbname . '</a>
  </span>';

if ($maintenance == 1) {
  die($mtitle);
}

$urlSep = 'accioncw241';

$cookiename = 'casitaweb';
$language = 'english';
$errordb = $mtitle . '<br /><span style="font-size: 9px; color: #CCCCCC;">#01274</span>';
$db_error_send = base64_decode('MQ==');
// TO-DO: Modificar ruta según corresponda
$boardurl = 'http://localhost/casitaweb';
$no_avatar = $boardurl . '/avatar.gif';
$boarddir = 'C:\\wamp64\\www\\casitaweb';
$themedir = $boarddir . '\\web\\archivos\\base';
$sourcedir = $boarddir . '\\web\\archivos\\raizSeG414';
$tranfer1 = $boardurl . '/images';
$recaptcha_private = base64_decode('NkxkQjNTRXFBQUFBQUpja3RmcFR6bk9yc2tBU0NUaHEzUFRmc3dWZA==');
$recaptcha_public = base64_decode('NkxkQjNTRXFBQUFBQUVnV3RBU0ZHN1Y5V09FMHZnYmpVc2RBdW9CUw==');

$internetNO = '
  <!--[if lt IE 7.]>
  <style rel="stylesheet" type="text/css">
    .warningMessage {
      border-bottom: 6px solid #D35F2C;
      border-left: 6px solid #D35F2C;
      border-right: 6px solid #D35F2C;
      background: #FFF;
      width: 917px;
      text-align: center;
      margin-bottom: 5px;
    }
  </style>
  <div align="center">
    <div class="warningMessage">
      <div style="float: left;">
        <img src="'.$tranfer1.'/alerta.png" alt="" style="margin-bottom: 1px; margin-left: 1px;" />
      </div>
      <div style="padding-top: 10px;">
        Est&aacute;s usando una versi&oacute;n antigua de tu navegador, por favor actual&iacute;zalo a una de estas alternativas: <a href="http://www.microsoft.com/spain/windows/downloads/ie/getitnow.mspx" title="Internet Explorer 8" target="_blank">Internet Explorer 8</a> o <a href="http://www.mozilla-europe.org/es/firefox/" target="_blank" title="FireFox 3">FireFox 3</a>
      </div>
    </div>
  </div>
  <![endif]-->';

require_once($sourcedir . '/Ursl.php');
require_once($boarddir . '/web/cw-flood-protection.php');

global $mnc;

$mnc = isset($mnc) ? $mnc : '0';

if ($mnc) {
  die();
  exit();
} else {
  $db_server = 'localhost';
  $db_name = 'casitaweb';
  $db_user = 'root';
  $db_passwd = '';
  $db_prefix = 'cw_';
  $db_last_error = base64_decode('MA==');
  $db_character_set = base64_decode('dXRmOA==');
  $db_connection = @mysqli_connect($db_server, $db_user, $db_passwd);

  if (!$db_connection || !@mysqli_select_db($db_connection, $db_name)) {
    die($errordb);
  }
}

$navegador = getenv('HTTP_USER_AGENT');

if (preg_match("/MSIE/i", $navegador)) {
  $ie = true;
} else {
  $ie = false;
}

?>