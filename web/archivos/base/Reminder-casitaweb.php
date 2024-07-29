<?php
function template_main() {
  global $context, $settings, $tranfer1, $options, $txt, $scripturl, $boardurl;

  if ($context['user']['is_guest']) {
    echo '
      <div align="center">
        <form action="' . $boardurl . '/recuperar-pass/enviando/" method="post" accept-charset="' . $context['character_set'] . '">
          <div class="box_title" style="width: 361px;">
            <div class="box_txt box_363-34">Recuperar mi contrase&ntilde;a</div>
            <div class="box_rss">
              <div class="icon_img">
                <img src="' . $tranfer1 . '/blank.gif?v3.2.3" alt="" />
              </div>
            </div>
          </div>
          <table border="0" width="363" cellspacing="0" cellpadding="4" class="windowbg">
            <tr align="left">
              <td colspan="2" class="smalltext" style="padding: 2ex;">
                Con esta funci&oacute;n vas a poder recuperar la contrase&ntilde;a de tu cuenta, te llegar&aacute; un e-mail con los pasos a seguir.
              </td>
            </tr>
            <tr align="left">
              <td width="40%" align="center">
                <b class="size11">Nick:</b>
                <br />
                <input tabindex="1" type="text" onfocus="foco(this);" onblur="no_foco(this);" name="user" size="30" />
              </td>
            </tr>
            <tr align="left">
              <td width="40%" align="center">
                <b class="size11">C&oacute;digo de la imagen:</b>
                <br />';

    captcha(1);

    echo '
              </td>
            </tr>
            <tr>
              <td colspan="2" align="center">
                <input tabindex="3" class="login" type="submit" value="' . $txt['sendtopic_send'] . '" />
              </td>
            </tr>
          </table>
          <input type="hidden" name="sc" value="' . $context['session_id'] . '" />
        </form>
      </div>';
  } else {
    fatal_error('Ya iniciaste sesi&oacute;n con tu usuario.');
  }
}

function template_sent() {
  global $context, $settings, $options, $tranfer1, $scripturl;

  if ($context['user']['is_guest']) {
    fatal_error('Se te ha enviado un mensaje a tu e-mail. Haz clic en el enlace de dicho correo para establecer una nueva contrase&ntilde;a.', false, 'E-mail enviado');
  } else {
    fatal_error('Ya iniciaste sesi&oacute;n con tu usuario.');
  }
}

function template_set_password() {
  global $context, $settings, $options, $txt, $tranfer1, $boardurl;

  if ($context['user']['is_guest']) {
    echo '
      <div align="center">
        <form action="' . $boardurl . '/recuperar-pass/enviar/" method="post" accept-charset="' . $context['character_set'] . '">
          <div class="box_title" style="width: 361px;">
            <div class="box_txt box_363-34">' . $context['page_title'] . '</div>
            <div class="box_rss">
              <div class="icon_img">
                <img src="' . $tranfer1 . '/blank.gif?v3.2.3" />
              </div>
            </div>
          </div>
          <table border="0" width="363" cellspacing="0" cellpadding="4" class="windowbg" align="center">
            <tr align="left">
              <td width="40%" align="right">
                <b class="size11">' . $txt[81] . ':</b>
              </td>
              <td valign="top">
                <input onfocus="foco(this);" onblur="no_foco(this);" tabindex="1" type="password" name="passwrd1" size="22" />
              </td>
            </tr>
            <tr  align="left">
              <td width="40%" align="right">
                <b class="size11">' . $txt[82] . ':</b>
              </td>
              <td>
                <input onfocus="foco(this);" onblur="no_foco(this);" tabindex="2" type="password" name="passwrd2" size="22" />
              </td>
            </tr>
            <tr align="left">
              <td colspan="2" align="center">
                <input tabindex="3" class="login" type="submit" value="Aceptar" />
              </td>
            </tr>
          </table>
          <input type="hidden" name="code" value="' . $context['code'] . '" />
          <input type="hidden" name="u" value="' . $context['memID'] . '" />
          <input type="hidden" name="sc" value="' . $context['session_id'] . '" />
        </form>
      </div>';
  } else {
    fatal_error('Ya iniciaste sesi&oacute;n con tu usuario.');
  }
}

function template_ask() {}

?>