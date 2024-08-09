<?php
function template_admin_above() {
  global $tranfer1, $context, $settings, $options, $scripturl, $txt;

  echo '
    <div style="margin-bottom: 8px;">
      <div style="width: 920px; background-color: #555555; border: solid 1px #3F3F3F;">';

  foreach ($context['admin_areas'] as $section) {
    echo '
      <table>
        <tr>';

    foreach ($section['areas'] as $i => $area) {
      if ($i == $context['admin_area']) {
        echo '<td valign="top" class="maintab_active_back camptcha">', $area, '</td>';
      } else {
        echo '<td valign="top" class="maintab_back camptcha">', $area, '</td>';
      }
    }

    echo '
        </tr>
      </table>';
  }

  echo '</div>';

  $context['admin_tabs']['tabs'] = isset($context['admin_tabs']['tabs']) ? $context['admin_tabs']['tabs'] : '';

  if ($context['admin_tabs']['tabs']) {
    if (!empty($settings['use_tabs'])) {
      echo '
        <table>
          <tr>';

      foreach ($context['admin_tabs']['tabs'] as $tab) {
        if (!empty($tab['is_selected'])) {
          echo '
            <td valign="top" class="maintab_active_back">
              <a href="', $tab['href'], '">', $tab['title'], '</a>
            </td>';
        } else {
          echo '
            <td valign="top" class="maintab_back">
              <a href="', $tab['href'], '">', $tab['title'], '</a>
            </td>';
        }
      }

      echo '
          </tr>
        </table>';
    }
  }

  echo '</div>';
}

function template_admin_below() {}

function template_admin() {
  global $tranfer1, $context, $settings, $options, $scripturl, $sourcedir, $glee, $txt, $modSettings, $boardurl, $mbname;

  $_POST['a_radio'] = isset($_POST['a_radio']) ? $_POST['a_radio'] : '';

  if ($_POST['a_radio']) {
    updateSettings(array('radio' => (int) $_POST['radio']));
    header('Location: ' . $boardurl . '/moderacion/');
  }

  require_once($sourcedir . '/IpModLog.php');

  $dataMOstrar = '';

  if (in_array($context['user']['name'], $glee)) {
    $dat = explode(' : ', $glee[$context['user']['name']]);
    $dataMOstrar .= '
      <div class="noesta-am" style="width: 922px; margin-bottom: 8px;">
        &Uacute;ltima IP registrada en la moderaci&oacute;n fue ' . $dat[0] . ' y hace ' . hace($dat[1]) . ' que se registr&oacute;. Tu IP actual es: ' . $_SERVER['REMOTE_ADDR'] . '
      </div>';
  }

  $dataMOstrar2 = '
    <div class="noesta-am" style="width: 922px; margin-bottom: 8px;">
      Actualizando IP...
    </div>';

  if ($glee[$context['user']['name']]) {
    if (!$dat[1]) {
      logipADM();

      echo $dataMOstrar2;
    } else if (!$dat[0]) {
      logipADM();

      echo $dataMOstrar2;
    } else if (($dat[1] + 3600) > time()) {
      echo $dataMOstrar;
    } else {
      logipADM();

      echo $dataMOstrar2;
    }
  } else {
    logipADM();

    echo $dataMOstrar2;
  }

  echo '
    <div class="box_757">
      <div class="box_title" style="width: 920px;">
        <div class="box_txt box_757-34">
          <center>', $txt[208], '</center>
        </div>
        <div class="box_rss">
          <img alt="" src="' . $tranfer1 . '/blank.gif" style="width: 16px; height: 16px;" border="0" />
        </div>
      </div>
    </div>
    <div style="width: 912px; padding: 4px;" class="windowbg">
      <center>
        <form action="' . $boardurl . '/moderacion/" method="post" accept-charset="' . $context['character_set'] . '" name="actualizar_radio" id="actualizar_radio" enctype="multipart/form-data" style="margin: 0;">
          Radio:
          <select style="width: 150px;" tabindex="1" name="radio" class="select">
            <option value="0"' . ($modSettings['radio'] == 0 ? ' selected="selected"' : '') . '>Ninguna</option>
            <option value="1"' . ($modSettings['radio'] == 1 ? ' selected="selected"' : '') . '>' . $mbname . '</option>
            <option value="2"' . ($modSettings['radio'] == 2 ? ' selected="selected"' : '') . '>Perdidos en babylon!</option>
          </select>
          -
          <input class="login" style="font-size: 11px;" value="Guardar" title="Guardar" type="submit" tabindex="2" />
          <input type="hidden" name="sc" value="' . $context['session_id'] . '" />
          <input type="hidden" name="a_radio" value="1" />
        </form>
      </center>
      <div style="font-size: 0.85em; padding-top: 1ex;">
        <p align="center">
          <img alt="' . $mbname . '" border="0" title="' . $mbname . '" src="' . $boardurl . '/logos/casitaweb-banner-10.png" />
        </p>
        <p align="center">' . $txt[18] . '</p>
        <div class="hrs"></div>
        <center>
          <b class="size17">Importante para moderar</b>
          <div class="hrs"></div>
        </center>
        - Todo contenido que se comenta en <b>comunicaciones de moderador</b> o <b>las normas</b> que est&aacute;n en esta hoja, no decirlo en p&uacute;blico ni tampoco decir qui&eacute;n est&aacute; baneado y qui&eacute;n no. (<b>NO SACAR CAPTURAS Y DIFUNDIRLAS</b>)
        <div class="hrs"></div>
        <b class="size12">Sobre el ban:</b>
        <br />
        <table style="margin: 0px; padding: 0px;" width="100%" align="center" valign="top">
        <tr valign="top">
          <td valign="top" style="border: 1px solid #7C7C7C; background: #D0D0D0;" width="200px">Motivos</td>
          <td valign="top" style="border: 1px solid #7C7C7C; background:#D0D0D0;" width="200px">Cantidad de d&iacute;as</td>
          <td valign="top" style="border: 1px solid #7C7C7C; background: #D0D0D0;" width="200px">Captura de pantalla</td>
        </tr>
        <tr valign="top">
          <td valign="top" style="border: 1px solid #7C7C7C;">Spam en comentarios y MP.</td>
          <td valign="top" style="border: 1px solid #7C7C7C;">
            * 15 d&iacute;as p/usuario conocido. Caso contrario, de por vida.<br />
            * Usuario recurrente, de por vida.
          </td>
          <td valign="top" style="border: 1px solid #7C7C7C;">No es necesario.</td>
        </tr>
        <tr valign="top">
          <td valign="top" style="border: 1px solid #7C7C7C;">
            Insultos o Comentarios fuera de lugar (<span title="adj. y s. Que siente odio u hostilidad hacia los extranjeros." alt="adj. y s. Que siente odio u hostilidad hacia los extranjeros." style="color:#FF9400;">Xen&oacute;fobos</span>, Discriminatorios, etc).
          </td>
          <td valign="top" style="border: 1px solid #7C7C7C;">
            * 5 d&iacute;as p/usuario conocido. Caso contrario, de por vida.<br />
            * Usuario recurrente, de por vida.
          </td>
          <td valign="top" style="border: 1px solid #7C7C7C;">S&iacute; es necesario.</td>
        </tr>
        <tr valign="top">
          <td valign="top" style="border: 1px solid #7C7C7C;">Insultos/Ataques/Burlas entre Usuarios en comentarios, MP o Muro.</td><td style="border: 1px solid #7C7C7C;">* 10 d&iacute;as p/usuario<br />Caso contrario, de por vida.<br />* Usuario recurrente, de por vida.</td><td valign="top" style="border: 1px solid #7C7C7C;">Si es necesario.-</td></tr>
        <tr valign="top">
          <td valign="top" style="border: 1px solid #7C7C7C;">Suma dudosa de Puntos.</td>
          <td valign="top" style="border: 1px solid #7C7C7C;">
            * 30 d&iacute;as.<br />
            * Definitivo, en caso de haber sido suspendido y recurre en igual falta.
          </td>
          <td valign="top" style="border: 1px solid #7C7C7C;">S&iacute; es necesario.</td>
        </tr>
        <tr valign="top">
          <td valign="top" style="border: 1px solid #7C7C7C;">IP Clonada.</td>
          <td style="border: 1px solid #7C7C7C;">
            * 10 d&iacute;as.<br />
            * Definitivo, en caso de haber sido suspendido y recurre en igual falta.
          </td>
          <td style="border:1px solid #7C7C7C;">No es necesario.</td>
        </tr>
      </table>
      * Si un usuario es suspendido por un <b>MOD</b> ese usuario <b>DEBE</b> cumplir la pena.<br />
      * Si un <b>MOD</b> levanta la suspensi&oacute;n de un usuario sin consulta previa al que lo realiz&oacute;, se le deshabilitar&aacute; de <b>' . $mbname . '</b> tarde o temprano.<br />
      * <b>JAM&Aacute;S</b> deber&aacute;n banearse entre <b>MOD&#8217;s</b>, el que lo haga deber&aacute; atenerse a la decisi&oacute;n que tome el resto de sus compa&ntilde;eros.
      <div class="hrs"></div>
      <b class="size12">Puntos importantes:</b>
      <br />
      <span class="size11" style="color: green;">
        * Cuando se elimina un post y la causa es RePost, agregar el ID del post principal (No es necesario agregar enlace completo, con ID solo ya est&aacute;).<br />
        * Cuando en un post est&aacute; roto el enlace, fijarse si se puede reparar. Si no se puede, eliminar post.<br />
        * S&oacute;lo los posts en la categor&iacute;a Noticias requieren fuente.
      </span>
      <br />
      <div class="hrs"></div>
      <b class="size12">Protocolo para moderadores:</b>
      <br />
      <span class="size11">
        * Un moderador es un usuario com&uacute;n con mayores privilegios los cuales implican mayor responsabilidad.<br />
        * Un error de uno es un error de todos.<br />
        * Los usuarios no postean para nosotros, nosotros moderamos para ellos.<br />
        * Hacer un post puede llevar mucho tiempo y dedicaci&oacute;n y <b>DEBE SER</b> igualmente proporcional al tiempo para evaluar si un post debe ser borrado o editado.<br />
        * Un moderador <b>NO PUEDE</b> insultar, maltratar, trollear, ni burlarse de los dem&aacute;s usuarios. Si nosotros lo hacemos, lo estamos permitiendo.<br />
        * No se pueden desuspender usuarios que suspendi&oacute; otro moderador salvo que el moderador que suspendi&oacute; les de el permiso y llegen a un acuerdo en com&uacute;n.</span>
        <div class="hrs"></div>
        <center>
          <b class="size16">NOSOTROS DAMOS EL EJEMPLO, QUE SEA EL MEJOR.</b>
        </center>
        <br />
        <br />
        <center>
          <b class="size9">Los que hacemos esta hermosa web:</b>
          <br />
          <span class="size11">
            <img alt="" src="' . $tranfer1 . '/rangos/padre.gif" title="Administrador" /> ' . implode(' <img alt="" src="' . $tranfer1 . '/rangos/padre.gif" title="Administrador" /> ', $context['administrators']);
  foreach ($context['moderadores'] as $mod) {
    echo '
      <img lt="" src="' . $tranfer1 . '/rangos/hermano_mayor.gif" title="Moderador/a" />
      <a href="' . $boardurl . '/perfil/' . $mod['realName'] . '" title="' . $mod['realName'] . '">' . $mod['realName'] . '</a>';
  }

  echo '
        </center>
      </div>
    </div>';
}

function template_modify_settings() {
  global $tranfer1, $context, $settings, $options, $scripturl, $txt, $modSettings, $boardurl;

  echo '
    <form action="' . $boardurl . '/moderacion/web/config/" method="post" accept-charset="' . $context['character_set'] . '">
      <div class="box_757" style="float: left; margin-bottom: 8px;">
        <div class="box_title" style="width: 920px;">
          <div class="box_txt box_757-34">
            <center>Configuraci&oacute;n de la web</center>
          </div>
          <div class="box_rss">
            <img src="' . $tranfer1 . '/blank.gif" style="width: 16px; height: 16px;" border="0" alt="" />
          </div>
        </div>
        <div class="windowbg" style="width: 744px; padding: 4px;">
          <table border="0" cellspacing="0" cellpadding="4" align="center" width="100%">';

  if ($context['can_change_permissions']) {
    echo '
      <tr class="windowbg">
        <th width="50%" align="right" valign="top">
          <label for="search_posts_groups">Qui&eacute;n puede buscar:</label>
        </th>
        <td>';

    theme_inline_permissions('search_posts');

    echo '
        </td>
      </tr>';
  }

  echo '
            <tr class="windowbg">
              <th align="right">
                <label for="search_results_per_page_input">Cantidad de resultados por pagina:</label>
              </th>
              <td>
                <input type="text" onfocus="foco(this);" onblur="no_foco(this);" name="search_results_per_page" id="search_results_per_page_input" value="' . $modSettings['search_results_per_page'] . '" style="width: 20px;" />
              </td>
            </tr>
            <tr class="windowbg">
              <th align="right">
                <label for="timeLoadPageEnable">Mostrar tiempo de creado de p&aacute;gina:</label>
              </th>
              <td>
                <input name="timeLoadPageEnable" id="timeLoadPageEnable" value="1" type="checkbox" ' . ($modSettings['timeLoadPageEnable'] == 1 ? ' checked="checked" ' : '') . '/>
              </td>
            </tr>
            <tr class="windowbg">
              <th align="right">
                <label for="smiley_enable">Habilitar emoticones:</label>
              </th>
              <td>
                <input name="smiley_enable" id="smiley_enable" value="1" type="checkbox" ' . ($modSettings['smiley_enable'] == 1 ? ' checked="checked" ' : '') . '/>
              </td>
            </tr>
            <tr class="windowbg">
              <th align="right">
                <label for="enableStickyTopics">Habilitar sticky:</label>
              </th>
              <td>
                <input name="enableStickyTopics" id="enableStickyTopics" value="1" type="checkbox" ' . ($modSettings['enableStickyTopics'] == 1 ? ' checked="checked" ' : '') . '/>
              </td>
            </tr>
            <tr class="windowbg">
              <th align="right">
                <label for="puntos_por_post-img">Puntos a dar por post/imagen:</label>
              </th>
              <td>
                <input name="puntos_por_post-img" id="puntos_por_post-img" value="' . $modSettings['puntos_por_post-img'] . '" type="text" style="width: 20px;" />
              </td>
            </tr>
            <tr class="windowbg">
              <th align="right">
                <label for="titlog">Cantidad de "&Uacute;ltimos comentarios":</label>
                <br />
                <spam class="size9">En el inicio</span>
              </th>
              <td>
                <input type="text" onfocus="foco(this);" onblur="no_foco(this);" name="ccomentarios" id="ccomentarios" value="' . $modSettings['catcoment'] . '" style="width: 20px;" />
              </td>
            </tr>
            <tr class="windowbg">
              <td align="right" colspan="2">
                <input type="submit" name="save" class="login" value="Guardar cambios" />
              </td>
            </tr>
          </table>
          <input type="hidden" name="sc" value="', $context['session_id'], '" />
        </div>
      </div>
    </form>';
}

function template_edit_censored() {
  global $tranfer1, $context, $settings, $options, $scripturl, $txt, $modSettings, $boardurl;

  echo '
    <form action="' . $boardurl . '/moderacion/web/censor/" method="post" accept-charset="', $context['character_set'], '">
      <div class="box_757" style="float: left; margin-bottom: 8px;">
        <div class="box_title" style="width: 752px;">
          <div class="box_txt box_757-34">
            <center>Censor Text</center>
          </div>
          <div class="box_rss">
            <img src="' . $tranfer1 . '/blank.gif" style="width: 16px; height: 16px;" border="0" alt="" />
          </div>
        </div>
        <div class="windowbg" style="width: 744px; padding: 4px;">
          <table align="center">
            <tr class="windowbg2">
              <td align="center">
                <table width="100%">
                  <tr>
                    <td colspan="2" align="center">
                      ', $txt[136], '<br />';

  foreach ($context['censored_words'] as $vulgar => $proper) {
    echo '
      <div style="margin-top: 1ex;">
        <input type="text" onfocus="foco(this);" onblur="no_foco(this);" name="censor_vulgar[]" value="', $vulgar, '" size="20" />
        =>
        <input type="text" onfocus="foco(this);" onblur="no_foco(this);" name="censor_proper[]" value="', $proper, '" size="20" />
      </div>';
  }

  echo '
                <noscript>
                  <div style="margin-top: 1ex;">
                    <input type="text" onfocus="foco(this);" onblur="no_foco(this);" name="censor_vulgar[]" size="20" />
                    =>
                    <input type="text" onfocus="foco(this);" onblur="no_foco(this);" name="censor_proper[]" size="20" />
                  </div>
                </noscript>
                <div id="moreCensoredWords"></div>
                <div style="margin-top: 1ex; display: none;" id="moreCensoredWords_link">
                  <a href="#;" onclick="addNewWord(); return false;">', $txt['censor_clickadd'], '</a>
                </div>
                <script type="text/javascript">
                  <!-- // -->
                  <![CDATA[
                    document.getElementById("moreCensoredWords_link").style.display = \'\';

                    function addNewWord() {
                      setOuterHTML(document.getElementById(\'moreCensoredWords\'), \'<div style="margin-top: 1ex;"><input type="text" onfocus="foco(this);" onblur="no_foco(this);" name="censor_vulgar[]" size="20" /> => <input type="text" onfocus="foco(this);" onblur="no_foco(this);" name="censor_proper[]" size="20" /></div><div id="moreCensoredWords"></div>\');
                    }

                    function setOuterHTML(element, toValue) {
                      if (typeof(element.outerHTML) != \'undefined\') {
                        element.outerHTML = toValue;
                      } else {
                        var range = document.createRange();
                        range.setStartBefore(element);
                        element.parentNode.replaceChild(range.createContextualFragment(toValue), element);
                      }
                    }
                  // ]]>
                </script>
                <br />
              </td>
            </tr>
            <tr>
              <td colspan="2">
                <div class="hrs"></div>
              </td>
            </tr>
            <tr>
              <th width="50%" align="right">
                <label for="censorWholeWord_check">', $txt['smf231'], ':</label>
              </th>
              <td align="left">
                <input type="checkbox" name="censorWholeWord" value="1" id="censorWholeWord_check"', empty($modSettings['censorWholeWord']) ? '' : ' checked="checked"', ' class="check" />
              </td>
            </tr>
            <tr>
              <th align="right">
                <label for="censorIgnoreCase_check">', $txt['censor_case'], ':</label>
              </th>
              <td align="left">
                <input type="checkbox" name="censorIgnoreCase" value="1" id="censorIgnoreCase_check"', empty($modSettings['censorIgnoreCase']) ? '' : ' checked="checked"', ' class="check" />
              </td>
            </tr>
            <tr>
              <td colspan="2" align="right">
                <input class="login" type="submit" name="save_censor" value="Guardar" />
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
    <br />';

  echo '
          <input type="hidden" name="sc" value="', $context['session_id'], '" />
        </div>
      </div>
    </form>';
}

function template_edit_bbc_settings() {
  global $tranfer1, $context, $settings, $options, $txt, $scripturl, $modSettings, $boardurl;

  echo '
    <script type="text/javascript">
      <!-- // -->
      <![CDATA[
        function toggleBBCDisabled(disable) {
          for (var i = 0; i < document.forms.bbcForm.length; i++) {
            if (typeof(document.forms.bbcForm[i].name) == "undefined" || (document.forms.bbcForm[i].name.substr(0, 11) != "enabledTags")) {
              continue;
            }

          document.forms.bbcForm[i].disabled = disable;
        }

        document.getElementById("select_all").disabled = disable;
      }
      //]]>
    </script>

    <form action="' . $boardurl . '/moderacion/web/bbc/" method="post" accept-charset="', $context['character_set'], '" name="bbcForm" id="bbcForm" onsubmit="toggleBBCDisabled(false);">
      <table border="0" cellspacing="0" cellpadding="4" align="center" width="80%" class="tborder">
        <tr class="titlebg">
          <td colspan="2">', $txt['manageposts_bbc_settings_title'], '</td>
        </tr>
        <tr class="windowbg2">
          <th width="50%" align="right">
            <label for="enableBBC_check">', $txt['enableBBC'], '</label>
            <span style="font-weight: normal;"></span>:
          </th>
          <td>
            <input type="checkbox" name="enableBBC" id="enableBBC_check"', empty($modSettings['enableBBC']) ? '' : ' checked="checked"', ' onchange="toggleBBCDisabled(!this.checked);" class="check" />
          </td>
        </tr>
        <tr class="windowbg2">
          <th width="50%" align="right">
            <label for="enablePostHTML_check">', $txt['enablePostHTML'], '</label>
            <span style="font-weight: normal;"></span>:
          </th>
          <td>
            <input type="checkbox" name="enablePostHTML" id="enablePostHTML_check"', empty($modSettings['enablePostHTML']) ? '' : ' checked="checked"', ' class="check" />
          </td>
        </tr>
        <tr class="windowbg2">
          <th width="50%" align="right">
            <label for="autoLinkUrls_check">', $txt['autoLinkUrls'], '</label>:
          </th>
          <td>
            <input type="checkbox" name="autoLinkUrls" id="autoLinkUrls_check"', empty($modSettings['autoLinkUrls']) ? '' : ' checked="checked"', ' class="check" />
          </td>
        </tr>
        <tr class="windowbg2">
          <td align="right" colspan="2">
            <input class="login" type="submit" name="save_settings" value="', $txt['manageposts_settings_submit'], '" />
          </td>
        </tr>
      </table>
      <input type="hidden" name="sc" value="', $context['session_id'], '" />
    </form>';

  if (empty($modSettings['enableBBC'])) {
    echo '
      <script type="text/javascript">
        <!-- // -->
        <![CDATA[
          toggleBBCDisabled(true);
        // ]]>
      </script>';
  }
}

function logipADM() {
  global $context, $sourcedir;

  $linksc = $sourcedir . '/IpModLog.php';
  $contIMG = count(file($linksc));

  if ($contIMG > 100) {
    $file = fopen($linksc, 'w+');
    $contenido = '';

    fwrite($file, $contenido);
    fclose($file);
  } else {
    $file = fopen($linksc, 'a+');
    $contenido = '<?php $glee["' . $context['user']['name'] . '"]="' . $_SERVER['REMOTE_ADDR'] . ' : ' . time() . '"; ?>' . "\n\n";

    fwrite($file, $contenido);
    fclose($file);
  }
}

function template_edit_topic_settings() {}
function template_edit_signature_settings() {}
function template_maintain() {}
function template_manage_copyright() {}
function template_credits() {}
function template_view_versions() {}
function template_optimize() {}
function template_not_done() {}
function template_edit_post_settings() {}
function template_show_settings() {}
function template_convert_utf8() {}
function template_edit_hidetagspecial_settings() {}
function template_convert_entities() {}

?>