<?php

function template_main() {
  global $context, $settings, $modSettings, $options, $no_avatar, $scripturl, $db_prefix, $tranfer1, $txt, $boardurl;

  if ($context['user']['name'] == 'rigo') {
    $rowlevel = 0;
    $maxrowlevel = 2;

    echo '
      <div class="box_r_buscador" style="float: left; margin-right: 8px; margin-bottom: 8px;">
        <div class="box_title" style="width: 700px;">
          <div class="box_txt box_r_buscadort">
            <center>Usuarios conectados</center>
          </div>
          <div class="box_rss">
            <img alt="" src="' . $tranfer1 . '/blank.gif" style="width: 14px; height: 12px;" border="0" />
          </div>
        </div>
        <div class="windowbg" style="width: 690px; padding: 4px;">
          <table align="center">';

    $request = db_query("
      SELECT ID_MEMBER
      FROM {$db_prefix}log_online
      WHERE ID_MEMBER <> 0", __FILE__, __LINE__);

    $conectados = mysqli_num_rows($request);

    $anuncio = db_query("
      SELECT ID_MEMBER
      FROM {$db_prefix}log_online
      WHERE ID_MEMBER <> 0", __FILE__, __LINE__);

    echo '
      <center>
        <b>Usuarios registrados online: ' . $conectados . '</b>
      </center>
      <div class="hrs"></div>';

    while ($row = mysqli_fetch_assoc($anuncio)) {
      $user = db_query("
        SELECT ID_MEMBER, avatar, realName, location, gender, personalText
        FROM {$db_prefix}members
        WHERE ID_MEMBER = {$row['ID_MEMBER']}", __FILE__, __LINE__);

      while ($datu = mysqli_fetch_assoc($user)) {
        $request = db_query("
          SELECT ID_MEMBER
          FROM {$db_prefix}gallery_pic
          WHERE ID_MEMBER = '{$datu['ID_MEMBER']}'", __FILE__, __LINE__);

        $imagenes = mysqli_num_rows($request);

        if ($rowlevel < ($maxrowlevel + 1)) {
          $rowlevel++;
        } else {
          echo '<tr valign="top">';
          $rowlevel = 0;
        }

        echo '
          <td  width="300px" valign="top">
            <table>
              <tr valign="top">';

        if ($modSettings['avatar_action_too_large'] == 'option_html_resize' || $modSettings['avatar_action_too_large'] == 'option_js_resize') {
          if (!empty($modSettings['avatar_max_width_external'])) {
            $context['user']['avatar']['width'] = $modSettings['avatar_max_width_external'];
          }

          if (!empty($modSettings['avatar_max_height_external'])) {
            $context['user']['avatar']['height'] = $modSettings['avatar_max_height_external'];
          }
        }

        if (!empty($datu['avatar'])) {
          $context['user']['avatar']['image'] = '<img src="' . $datu['avatar'] . '"' . (isset($context['user']['avatar']['width']) ? ' width="' . $context['user']['avatar']['width'] . '"' : '') . ' onload="if (this.height > 100) {this.height = 100}" alt="" class="avatar-box" onerror="error_avatar(this)" border="0" />';
        }

        echo '<td valign="top">';

        if ($datu['avatar']) {
          echo $context['user']['avatar']['image'];
        } else {
          echo '<img src="' . $no_avatar . '" class="avatar-box" border="0" alt="Sin Avatar" onerror="error_avatar(this)" />';
        }

        echo '
          </td>
          <td valign="top">
            <a href="' . $boardurl . '/perfil/' . $datu['realName'] . '" title="' . $datu['realName'] . '">' . $datu['realName'] . '</a>
            <br />';

        if (!$datu['personalText']) {
          echo censorText($datu['location']) . '<br />';
        }

        if ($datu['gender'] == 1 || $datu['gender'] == 2) {
          if ($datu['gender'] == 1) {
            $sexo = 'Hombre';
          }

          if ($datu['gender'] == 2) {
            $sexo = 'Mujer';
          }

          echo $sexo . '<br />';
        }

        echo '
          <div class="hrs"></div>
          <div style="margin-bottom: 2px;">
            <span>
              <img src="' . $tranfer1 . '/icons/mensaje_para.gif" />
              <a href="' . $boardurl . '/mensajes/a/' . $datu['realName'] . '" title="Enviar mensaje">Enviar mensaje</a>
            </span>
          </div>';

        if ($imagenes > 0) {
          echo '
            <div style="margin-bottom: 4px;">
              <span class="icons fot2">
                <a href="' . $boardurl . '/imagenes/' . $datu['realName'] . '" title="Sus im&aacute;genes"> Sus im&aacute;genes</a>
              </span>
            </div>';
        }

        echo '
                  <div style="margin-bottom: 2px;">
                    <span class="icons blog">
                      <a href="' . $boardurl . '/perfil/' . $datu['realName'] . '" title="Ver perfil">Ver perfil</a>
                    </span>
                  </div>
                </td>
              </tr>
            </table>
          </td>';

        if ($rowlevel < ($maxrowlevel + 1)) {
          $rowlevel++;
        } else {
          echo '</tr>';
          $rowlevel = 0;
        }
      }
    }

    echo '
          </table>
        </div>
      </div>
      <div class="publicidad" style="float: left; margin-bottom: 8px;">
      <div class="box_title" style="width: 212px;">
        <div class="box_txt publicidad_r">
          <center>Publicidad</center>
        </div>
        <div class="box_rss">
          <img alt="" src="' . $tranfer1 . '/blank.gif" style="width: 14px; height: 12px;" border="0" />
        </div>
      </div>
      <div class="windowbg" style="width: 210px;">
        <center>';

    anuncio_160x600();

    echo '
            </center>
          </div>
        </div>
      </div>';
  }
}

?>