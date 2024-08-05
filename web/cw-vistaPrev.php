<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $db_prefix, $scripturl, $context, $modSettings, $user_settings, $user_info, $tranfer1;

$databasetit = isset($_POST['subject']) ? seguridad($_POST['subject']) : '';
$databasepost = isset($_POST['message']) ? seguridad($_POST['message']) : '';
$databasepost = str_replace(array('"', '<', '>', '  '), array('&quot;', '&lt;', '&gt;', ' &nbsp;'), $databasepost);
$databasepost = preg_replace('~\[hide\](.+?)\[\/hide\]~i', '&nbsp;', $databasepost);
$databasepost = preg_replace(array('~\n?\[hide.*?\].+?\[/hide\]\n?~is', '~^\n~', '~\[/hide\]~'), '&nbsp;', $databasepost);
$databasepost = preg_replace('~<br(?: /)?' . '>~i', "\n", $databasepost);
$contenido = hides($databasepost);
$contenido = parse_bbc($contenido);

if (empty($ID_MEMBER)) {
  die('<div class="noesta" style="width: 922px; margin-bottom: 4px;">Funcionalidad exclusiva de usuarios registrados.</div>');
}

if (strlen($_POST['subject']) > 60) {
  die('<div class="noesta" style="width: 922px; margin-bottom: 4px;">El t&iacute;tulo no puede tener m&aacute;s de 60 letras.</div>');
}

if (strlen($_POST['subject']) < 3) {
  die('<div class="noesta" style="width: 922px; margin-bottom: 4px;">El t&iacute;tulo no puede tener menos de 3 letras.</div>');
}

if (strlen($_POST['message']) <= 60) {
  die('<div class="noesta" style="width: 922px; margin-bottom: 4px;">El post no puede tener menos de 60 letras.</div>');
}

if (strlen($_POST['message']) > $modSettings['max_messageLength']) {
  die('<div class="noesta" style="width: 922px; margin-bottom: 4px;">El post no puede tener m&aacute;s de ' . $modSettings['max_messageLength'] . ' letras.</div>');
}

if (empty($_POST['subject'])) {
  die('<div class="noesta" style="width: 922px; margin-bottom: 4px;">Falta el t&iacute;tulo.</div>');
}

if (empty($_POST['message'])) {
  die('<div class="noesta" style="width: 922px; margin-bottom: 4px;">Falta el mensaje del post.</div>');
}

echo '<div>';

menuser($ID_MEMBER);

echo '
  <div style="float: left; width: 774px;">
    <div class="box_780">
      <div class="box_title" style="width: 772px;">
        <div class="box_txt box_780-34">
          <center>' . $databasetit . '</center>
        </div>
        <div class="box_rss">
          <img alt="" src="' . $tranfer1 . '/blank.gif" style="width: 16px; height: 16px;" border="0" />
        </div>
      </div>
      <div class="windowbg" style="width: 772px;" id="vista_previa">
        <div class="post-contenido" property="dc:content">' . $contenido . '</div>
      </div>
      <div align="center" style="margin-top: 4px;">
        <input onclick="cerrar_vprevia()" class="button" style="font-size: 13px;" value="Cerrar la previsualizaci&oacute;n" title="Cerrar la previsualizaci&oacute;n" type="button" />
        <input onclick="confirm = false;" class="button" style="font-size: 13px;" value="OK, &iexcl;est&aacute; perfecto!" title="OK, &iexcl;est&aacute; perfecto!" type="submit" />
      </div>
    </div>
  </div>';

echo '</div>';
echo '<div style="clear: both; margin-bottom: 4px;"></div>';

?>