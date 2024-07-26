<?php
function template_intro() {
  exit();
  die();
}

// Mis notas
function template_tyc17() {
  global $tranfer1, $func, $ID_MEMBER, $modSettings, $context, $db_prefix, $boardurl;

  ditaruser();

  $_GET['accion'] = isset($_GET['accion']) ? $_GET['accion'] : '';

  if ($_GET['accion'] == 'misnotas') {
    echo '<div style="float: left; width: 776px;">';

    $RegistrosAMostrar = 10;

    $request = db_query("
      SELECT id_user
      FROM {$db_prefix}notas
      WHERE id_user='{$ID_MEMBER}'", __FILE__, __LINE__);

    $NroRegistros = mysqli_num_rows($request);
    $_GET['pag'] = isset($_GET['pag']) ? $_GET['pag'] : '';
    $oagvv = $_GET['pag'] < 1 ? 1 : $_GET['pag'];

    if (isset($oagvv)) {
      $RegistrosAEmpezar = ($oagvv - 1) * $RegistrosAMostrar;
      $PagAct = $oagvv;
    } else {
      $RegistrosAEmpezar = 0;
      $PagAct = 1;
    }

    $PagAnt = $PagAct - 1;
    $PagSig = $PagAct + 1;
    $PagUlt = $NroRegistros / $RegistrosAMostrar;
    $Res = $NroRegistros % $RegistrosAMostrar;

    if ($Res > 0) {
      $PagUlt = floor($PagUlt) + 1;
    }

    echo '<div style="float: left; width: 776px;">';

    if (empty($NroRegistros)) {
      echo '<div class="noesta" style="width: 776px;">No tienes notas agregadas.</div>';
    } else if ($PagAct > $PagUlt) {
      echo '<div class="noesta" style="width: 776px;">Est&aacute; p&aacute;gina no existe.</div>';
    } else {
      echo '
        <table class="linksList" style="width: 776px;">
          <thead align="center">
            <tr>
              <th style="text-align: left;">Nota</th>
              <th>Fecha</th>
              <th>Eliminar</th>
            </tr>
          </thead>
          <tbody>';

      $notas = db_query("
        SELECT id, fecha_creado, titulo
        FROM {$db_prefix}notas
        WHERE id_user = $ID_MEMBER
        ORDER BY id DESC 
        LIMIT $RegistrosAEmpezar, $RegistrosAMostrar", __FILE__, __LINE__);

      $context['posts'] = array();

      while ($row = mysqli_fetch_assoc($notas)) {
        $context['posts'][] = array(
          'id' => $row['id'],
          'titulo' => nohtml($row['titulo']),
          'fechac' => timeformat($row['fecha_creado'])
        );
      }

      mysqli_free_result($notas);

      foreach ($context['posts'] as $post) {
        echo '
          <tr>
            <td style="text-align: left;">
              <a title="' . $post['titulo'] . '" href="#" onclick="Boxy.load("' . $boardurl . '/web/cw-TEMPeditarNota.php?id=' . $post['id'] . '", { title: "' . nohtml($post['titulo']) . '" });">' . $post['titulo'] . '</a>
            </td>
            <td title="' . $post['fechac'] . '">' . $post['fechac'] . '</td>
            <td>
              <img alt="" title="Eliminar nota" style="width: 16px; height: 16px; cursor: pointer;" class="png" src="' . $tranfer1 . '/comunidades/eliminar.png" onclick="Boxy.confirm(\'&iquest;Estas seguro que desea eliminar esta nota?\', function() { location.href = \'' . $boardurl . '/web/cw-EliminarNota.php?id=' . $post['id'] . '\' }, { title: \'Eliminar nota\' }); return false;" />
            </td>
          </tr>';
      }

      echo '
          </tbody>
        </table>';

      if ($PagAct > $PagUlt) {
        // ¿Se hace algo acá?
      } else if ($PagAct > 1 || $PagAct < $PagUlt) {
        echo '<div class="windowbgpag">';

        if ($PagAct > 1) {
          echo `<a href="$boardurl/mis-notas/pag-$PagAnt">&#171; anterior</a>`;
        }

        if ($PagAct < $PagUlt) {
          echo `<a href="$boardurl/mis-notas/pag-$PagSig">siguiente &#187;</a>`;
        }

        echo '</div>';
      }
    }

    echo '
          <div style="width: 776px; margin-top: 4px;">
            <p align="right" style="margin: 0px; padding: 0px;">
              <input type="button" value="Agregar nota" onclick="Boxy.load(\'' . $boardurl . '/web/cw-TEMPagregarNota.php\', { title: \'Agregar nota\' });" class="boxy login" />
            </p>
          </div>
          <div class="clearBoth"></div>
        </div>
      </div>';
  } else {
    die('Acci&oacute;n no reconocida.');
  }
}

// Moderación de mensajes privados
function template_tyc12() {
  global $tranfer1, $context, $db_prefix, $txt, $scripturl, $modSettings, $boardurl;

  if ($context['user']['name'] == 'rigo' || $context['user']['id'] == 1) {
    echo '
      <div class="box_757">
        <div class="box_title" style="width: 752px;">
          <div class="box_txt box_757-34">
            <center>Mensajes privados</center>
          </div>
          <div class="box_rss">
            <img alt="" src="' . $tranfer1 . '/blank.gif" style="width: 16px; height: 16px;" border="0" />
          </div>
        </div>
      </div>
      <div style="width: 744px; padding:4px;" class="windowbg">
        <form action="' . $boardurl . '/web/cw-EliminarPMSADM.php" method="post" accept-charset="' . $context['character_set'] . '" name="coments" id="coments">';

    $RegistrosAMostrar = 10;
    $pag = isset($_GET['pag-11sdasd']) ? (int) $_GET['pag-11sdasd'] : 0;
    $oagvv = $pag < 1 ? 1 : $pag;

    if (isset($oagvv)) {
      $RegistrosAEmpezar = ($oagvv - 1) * $RegistrosAMostrar;
      $PagAct = $oagvv;
    } else {
      $RegistrosAEmpezar = 0;
      $PagAct = 1;
    }

    $request = db_query("
      SELECT id, titulo, name_de, mensaje, id_para
      FROM {$db_prefix}mensaje_personal
      ORDER BY id DESC
      LIMIT $RegistrosAEmpezar, $RegistrosAMostrar", __FILE__, __LINE__);

    while ($row = mysqli_fetch_array($request)) {
      $datosmem = db_query("
        SELECT realName
        FROM {$db_prefix}members
        WHERE ID_MEMBER = {$row['id_para']}
        LIMIT 1", __FILE__, __LINE__);

      $data = mysqli_fetch_assoc($datosmem);
      $nick = $data['realName'];

      echo '
        <input type="checkbox" name="campos[' . $row['id'] . ']" />
        <br />
        <b>Por:</b>
        <a href="' . $boardurl . '/perfil/' . $row['name_de'] . '" title="' . $row['name_de'] . '">' . $row['name_de'] . '</a>
        <br />
        <b>A:</b>
        <a href="' . $boardurl . '/perfil/' . $nick . '" title="' . $nick . '">' . $nick . '</a>
        <br />
        <b>Asunto:</b>
        ' . censorText($row['titulo']) . '
        <br />
        <b>Mensaje:</b>
        <br />
        ' . parse_bbc(str_replace('<br/>', "\n", $row['mensaje'])) . '
        <div class="hrs"></div>';
    }

    $request = db_query("
      SELECT id
      FROM {$db_prefix}mensaje_personal", __FILE__, __LINE__);

    $NroRegistros = mysqli_num_rows($request);
    $PagAnt = $PagAct - 1;
    $PagSig = $PagAct + 1;
    $PagUlt = $NroRegistros / $RegistrosAMostrar;
    $Res = $NroRegistros % $RegistrosAMostrar;

    if ($Res > 0) {
      $PagUlt = floor($PagUlt) + 1;
    }

    if ($PagAct > $PagUlt) {
      echo '<div class="noesta">Est&aacute; p&aacute;gina no existe.</div>';
    }

    echo '
          <br />
          <b>Cantidad de mensajes:</b>
          ' . $NroRegistros . '
          <br />
          <span class="size10">Comentarios seleccionados:</span>
          <input class="login" style="font-size: 9px;" type="submit" value="Eliminar" />
          <input value="' . $PagAct . '" name="pag" type="hidden" />
        </form>
      </div>';

    if ($PagAct < $PagUlt) {
      echo '<div class="windowbgpag" style="width: 698px;">';

      if ($PagAct > 1) {
        echo '<a href="' . $boardurl . '/moderacion/pms/pag-' . $PagAnt . '">&#171; anterior</a>';
      }

      if ($PagAct < $PagUlt) {
        echo '<a href="' . $boardurl . '/moderacion/pms/pag-' . $PagSig . '">siguiente &#187;</a>';
      }

      echo '
        </div>
        <div class="clearBoth"></div>';
    }
  } else {
    fatal_error('No puedes estar ac&aacute;.');
  }
}

// Recomendar
function template_tyc() {
  global $tranfer1, $context, $mbname, $boardurl;

  echo '
    <script type="text/javascript">
      function showr_email(comment) {
        if (comment == \'\') {
          alert(\'No has escrito ning&uacute;n mensaje.\');
          return false;
        }
      }
    </script>
    <div>
      <div class="box_buscador">
        <div class="box_title" style="width: 920px;">
          <div class="box_txt box_buscadort">
            <center>Recomendar ' . $mbname . ' a tus amigos</center>
          </div>
          <div class="box_rss">
            <img alt="" src="' . $tranfer1 . '/blank.gif" style="width: 14px; height: 12px;" border="0" />
          </div>
        </div>
        <div style="width: 912px; padding: 4px;" class="windowbg">
          <center>
            <form action="' . $boardurl . '/web/cw-recomendarWeb.php" method="post" accept-charset="' . $context['character_set'] . '">
              <br />
              <font class="size11">
                <b>Recomendar ' . $mbname . ' hasta a seis amigos:</b>
              </font>
              <br />
              <b class="size11">1 - </b>
              <input type="text" onfocus="foco(this);" onblur="no_foco(this);" name="r_email" size="28" maxlength="60" />
              <b class="size11">2 - </b>
              <input type="text" onfocus="foco(this);" onblur="no_foco(this);" name="r_email1" size="28" maxlength="60" />
              <br />
              <b class="size11">3 - </b>
              <input type="text" onfocus="foco(this);" onblur="no_foco(this);" name="r_email2" size="28" maxlength="60" />
              <b class="size11">4 - </b>
              <input type="text" onfocus="foco(this);" onblur="no_foco(this);" name="r_email3" size="28" maxlength="60" />
              <br />
              <b class="size11">5 - </b>
              <input type="text" onfocus="foco(this);" onblur="no_foco(this);" name="r_email4" size="28" maxlength="60" />
              <b class="size11">6 - </b>
              <input type="text" onfocus="foco(this);" onblur="no_foco(this);" name="r_email5" size="28" maxlength="60" />
              <br /><br />
              <font class="size11">
                <b>Asunto:</b>
              </font>
              <br />
              <input size="40" name="titulo" value="Te recomiendo ' . $mbname . '" type="text" onfocus="foco(this);" onblur="no_foco(this);" />
              <br /><br />
              <font class="size11">
                <b>Mensaje:</b>
              </font>
              <br />
              <textarea cols="70" rows="8" wrap="hard" tabindex="6" name="comment">Hola! Te recomiendo que le des un vistazo a ' . $mbname . ' 

Saludos!

' . $context['user']['name'] . '</textarea>
                <br /><br />
                <font class="size11">
                  <strong>C&oacute;digo de la imagen:</strong>
                </font>
                <br />';

  captcha(1);

  echo '
            <br />
            <input onclick="return showr_email(this.form.comment.value);" type="submit" class="login" name="send" value="Recomendar ' . $mbname . '" />
          </form>
        </center>
        </div>
      </div>
    </div>';
}

// Enlázanos
function template_tyc1() {
  global $tranfer1, $context, $settings, $options, $txt, $scripturl, $modSettings, $db_prefix, $user_info, $con, $board, $boardurl, $mbname;

  echo '
    <div class="box_buscador">
      <div class="box_title" style="width: 920px;">
        <div class="box_txt box_buscadort">
          <center>Enl&aacute;zanos</center>
        </div>
        <div class="box_rss">
          <img alt="" src="' . $tranfer1 . '/blank.gif" style="width: 14px; height: 12px;" border="0" />
        </div>
      </div>
      <div class="windowbg" style="width: 912px; padding: 4px;">
        <table style="border-bottom: 1px solid #B3A496;">
          <tr>
            <td style="width: 125px; height: 62px; margin-top: 25px;">
              <center>
                <a title="' . $mbname . '" href="' . $boardurl . '/">
                  <img src="' . $boardurl . '/web/enlazanos/casitaweb-16x16.gif" alt="' . $mbname . '" width="16" border="0" height="16" />
                </a>
              </center>
            </td>
            <td style="width: 772px; height: 62px;">
              <textarea readonly="readonly" onmouseup="this.focus(); this.select()" wrap="off" style="border: 1px dashed rgb(192, 192, 192); background-color: rgb(249, 249, 249); width: 772px; height: 50px; font-family: arial; font-size: 11px;">&lt;a title="' . $mbname . '" href="' . $boardurl . '/"&gt;
&nbsp;&nbsp;&lt;img src="' . $boardurl . '/web/enlazanos/casitaweb-16x16.gif" alt="' . $mbname . '" width="16" border="0" height="16" /&gt;
&lt;/a&gt;</textarea>
            </td>
          </tr>
        </table>
        <table style="border-bottom: 1px solid #B3A496;">
          <tr>
            <td style="width: 125px; height: 62px; margin-top: 25px;">
              <center>
                <a title="' . $mbname . '" href="' . $boardurl . '/">
                  <img src="' . $boardurl . '/web/enlazanos/casitaweb-88x31.gif" alt="' . $mbname . '" width="88" border="0" height="31" />
                </a>
              </center>
            </td>
            <td style="width: 772px; height: 62px;">
              <textarea readonly="readonly" onmouseup="this.focus(); this.select()" wrap="off" style="border: 1px dashed rgb(192, 192, 192); background-color: rgb(249, 249, 249); width: 772px; height: 50px; font-family: arial; font-size: 11px;">&lt;a title="' . $mbname . '" href="' . $boardurl . '/"&gt;
&nbsp;&nbsp;&lt;img src="' . $boardurl . '/web/enlazanos/casitaweb-88x31.gif" alt="' . $mbname . '" width="88" border="0" height="31" /&gt;
&lt;/a&gt;</textarea>
            </td>
          </tr>
        </table>
        <table style="border-bottom: 1px solid #B3A496;">
          <tr>
            <td style="width: 125px; height: 62px; margin-top: 25px;">
              <center>
                <a title="' . $mbname . '" href="' . $boardurl . '/">
                  <img src="' . $boardurl . '/web/enlazanos/casitaweb-100x20.gif" alt="' . $mbname . '" width="100" border="0" height="20"/>
                </a>
              </center>
            </td>
            <td style="width: 772px; height: 62px;">
              <textarea readonly="readonly" onmouseup="this.focus(); this.select()" wrap="off" style="border: 1px dashed rgb(192, 192, 192); background-color: rgb(249, 249, 249); width: 772px; height: 50px; font-family: arial; font-size: 11px;">&lt;a title="' . $mbname . '" href="' . $boardurl . '/"&gt;
&nbsp;&nbsp;&lt;img src="' . $boardurl . '/web/enlazanos/casitaweb-100x20.gif" alt="' . $mbname . '" width="100" border="0" height="20" /&gt;
&lt;/a&gt;</textarea>
            </td>
          </tr>
        </table>
        <table>
          <tr>
            <td style="width: 125px; height: 62px; margin-top: 25px;">
              <center>
                <a title="' . $mbname . '" href="' . $boardurl . '/">
                  <img src="' . $boardurl . '/web/enlazanos/casitaweb-125x125.gif" alt="' . $mbname . '" width="125" border="0" height="125" />
                </a>
              </center>
            </td>
            <td style="width: 772px; height: 62px;">
              <textarea readonly="readonly" onmouseup="this.focus(); this.select()" wrap="off" style="border: 1px dashed rgb(192, 192, 192); background-color: rgb(249, 249, 249); width: 772px; height: 50px; font-family: arial; font-size: 11px;">&lt;a title="' . $mbname . '" href="' . $boardurl . '/"&gt;
&nbsp;&nbsp;&lt;img src="' . $boardurl . '/web/enlazanos/casitaweb-125x125.gif" alt="' . $mbname . '" width="125" border="0" height="125" /&gt;
&lt;/a&gt;</textarea>
            </td>
          </tr>
        </table>
      </div>
    </div>';
}

// Chat
function template_tyc2() {
  global $tranfer1, $modSettings, $boardurl, $mbname;

  echo '
    <div style="margin-bottom: 8px; width: 922px;">
      <div class="box_title">
        <div class="box_txt box_buscadort">Chat</div>
        <div class="box_rss">
          <img alt="" src="' . $tranfer1 . '/blank.gif" style="width: 14px; height: 12px;" border="0" />
        </div>
      </div>
      <div style="width: 912px; padding: 4px;" class="windowbg">
        <embed src="http://www.xatech.com/web_gear/chat/chat.swf" quality="high" name="chat" flashvars="id=124775015&amp;rl=Argentina" allowscriptaccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://xat.com/update_flash.shtml" align="middle" height="480px" width="912px" />';

  if (!empty($modSettings['radio'])) {
    if ($modSettings['radio'] == 1) {
      echo "
        <center>
          <div class=\"stream\">
            <script type=\"text/javascript\">
              window.onload = radio;

              function radio() {
                if (document.getElementById('cc_stream_info_song').innerHTML == '') {
                  document.getElementById('enlinea').innerHTML = '<span style=\"color:red;\">Fuera de linea</span>';
                  document.getElementById('imgmic').style.display = 'inline';
                  document.getElementById('imgcar').style.display = 'none';}
                  else{document.getElementById('enlinea').innerHTML = '<span style=\"color:green;\">En linea</span>';
                  document.getElementById('imgmic').style.display = 'inline';
                  document.getElementById('imgcar').style.display = 'none';
                  document.getElementById('escuchando').style.display = 'inline';
                }
              }
            </script>
            <span id=\"escuchando\" style=\"display: none;\">
              <img src=\"" . $tranfer1 . '/icons/microfono.png" alt="" />
              <a href="' . $boardurl . '/chat/" id="cc_stream_info_song"></a>
              <br />
            </span>
            <span id="linea">
              <img src="' . $tranfer1 . '/icons/microfono.png" alt="" style="display: none;" id="imgmic" />
              <img src="' . $tranfer1 . '/icons/cargando.gif" id="imgcar" alt="" />
              <span style="font-weight: bold;" id="enlinea"></span>
            </span>
            <script language="javascript" type="text/javascript" src="http://cp.internet-radio.org.uk/system/streaminfo.js"></script>
            <script language="javascript" type="text/javascript" src="http://cp.internet-radio.org.uk/js.php/camilo62/streaminfo/rnd0"></script>
            <object type="application/x-shockwave-flash" data="http://fmcasita.net/utilidades/player_mp3_maxi.swf" width="266" height="20">
              <param name="wmode" value="transparent" />
              <param name="movie" value="http://fmcasita.net/utilidades/player_mp3_maxi.swf" />
              <param name="bgcolor" value="#ffffff" />
              <param name="FlashVars" value="mp3=http%3A//77.92.68.221%3A15393/%3B&amp;showvolume=1&amp;width=266&amp;showloading=always&amp;bgcolor1=CDC3B8&amp;bgcolor2=CDC3B8&amp;slidercolor1=FFC703&amp;slidercolor2=FFC703" />
            </object>
          </div>
          <img alt="" src="' . $tranfer1 . '/icons/radio-cw.gif" />
          <b class="size11">
            Ir a
            <a target="_blank" href="http://fmcasita.net">FMcasita.net</a>
            -
            Radio oficial de ' . $mbname . '
          </b>
        </center>';
    } else if ($modSettings['radio'] == 2) {
      echo '
        <center>
          <embed type="application/x-mplayer2" pluginspace="http://www.microsoft.com/isapi/redir.dll?prd=windows&amp;sbp=mediaplayer&amp;ar=Media&amp;sba=Plugin&amp;" wmode="transparent" filename="mms://201.212.0.128/horaprima" name="WMPlay" autostart="0" showcontrols="1" showdisplay="0" showstatusbar="0" autosize="0" displaysize="0" width="280" height="45" />
          <br />
          <img alt="" src="' . $tranfer1 . '/icons/radio-cw.gif" />
          <b class="size11">
            Ir a
            <a target="_blank" href="http://perdidosenbabylon.com">Perdidos en babylon!</a>
            -
            Web oficial
            <br />
            <img alt="" src="http://fmcasita.net/utilidades/2.png" />
            <a href="mms://201.212.0.128/horaprima">Escuchar en Windows Media Player</a>
          </b>
        </center>';
    }
  }

  echo '
      </div>
    </div>
    <div class="aparence" style="width: 922px; margin: 0px;">
      <h3 class="titlesCom" onclick="chgsec(this)">Protocolo del chat</h3>
      <div class="active" id="contennnt" style="width: 922px;">
        <font class="size12">
          <b>1.</b> No se permite el uso de Nicks que contengan t&eacute;rminos insultantes, sexuales, apolog&iacute;as a la violencia o alg&uacute;n pedido de car&aacute;cter sexual, compa&ntilde;&iacute;a, parejas y/o a fines.
          <br /><br />
          <b>2.</b> Est&aacute; prohibido faltar el respeto, insultar, provocar, difamar, acosar, amenazar o hacer cualquier otra cosa no deseada, tanto directa como indirecta a otro usuario.
          <br /><br />
          <b>3.</b> No est&aacute; permitido el SPAM, publicidad o propaganda de p&aacute;ginas personales, chats, foros, mensajes comerciales destinados a vender productos o servicios, etc.
          <br /><br />
          <b>4.</b> No repetir o enviar varias l&iacute;neas de texto en un cierto tiempo, NO FLOOD.
          <br /><br />
          <b>5.</b> Recomendamos no abusar de las MAY&Uacute;SCULAS, s&oacute;lo utilizarlas por reglas ortogr&aacute;ficas (Comienzos de oraci&oacute;n, nombres propios o siglas), ya que el uso de &eacute;sta significa GRITAR.
        </font>
        <br />
        <p style="padding: 0px; margin: 0px;" align="right">
          <i>Este protocolo es s&oacute;lo para el chat, para la web en general existe otro <a href="' . $boardurl . '/protocolo/">protocolo</a>.</i>
        </p>
      </div>
    </div>';
}

// Términos y condiciones
function template_tyc3() {
  global $tranfer1, $modSettings;

  if (!$modSettings['requireAgreement'] || !$modSettings['terminos']) {
    fatal_error('Los T&eacute;rminos y Condiciones no est&aacute;n habilitados.', false);
  } else {
    echo '
      <div>
        <div class="box_buscador">
          <div class="box_title" style="width: 920px;">
            <div class="box_txt box_buscadort">
              <center>T&eacute;rminos y Condiciones</center>
            </div>
            <div class="box_rss">
              <img alt="" src="' . $tranfer1 . '/blank.gif" style="width: 14px; height: 12px;" border="0" />
            </div>
          </div>
          <div class="windowbg" style="width: 912px; padding: 4px;">
            <center>' . $modSettings['terminos'] . '</center>
          </div>
        </div>
      </div>';
  }
}

// Protocolo
function template_tyc5() {
  global $tranfer1, $mbname;

  echo '
    <div class="box_buscador">
      <div class="box_title" style="width: 920px;">
        <div class="box_txt box_buscadort">
          <center>Protocolo</center>
        </div>
        <div class="box_rss">
          <img alt="" src="' . $tranfer1 . '/blank.gif" style="width: 14px; height: 12px;" border="0" />
        </div>
      </div>
      <div class="windowbg" style="width: 912px; padding: 4px;">
        <div class="codePro">
          <b>Introducci&oacute;n:</b>
          <div class="codePro1">
            <b>' . $mbname . '</b> es un sitio de entretenimiento para usuarios de habla hispana en el cual los usuarios comparten informaci&oacute;n de diversas tem&aacute;ticas (enlaces, im&aacute;genes, noticias, videos, etc.) por medio de posts.
            <br />
            <b>' . $mbname . '</b> es una web, fue creada con la idea de responder a consultas o debatir temas como tal comunidad.
            <br />
            Los moderadores son los encargados de filtrar, eliminar o editar la informaci&oacute;n que se comparte, de esta forma se evita que el contenido se transforme en una gran cantidad de "nada" y siempre se mantenga con la mejor calidad posible. Existen reglas en las cuales se basa la filosof&iacute;a de administraci&oacute;n llamado protocolo y se detalla a continuaci&oacute;n.
          </div>
        </div>

        <div class="codePro"><b>Protocolo:</b>
        <div class="codePro1">
          <span class="size12" align="left">
            <p style="margin: 0px; padding: 0px; padding-left: 10px;">
              <img src="' . $tranfer1 . '/icons/si.png" width="16px" height="16px" class="png" alt="" />
              <b>Caracter&iacute;sticas para postear:</b>
            </p>
            <p style="margin: 0px; padding: 0px;">
              <img src="' . $tranfer1 . '/icons/no.png" width="16px" height="16px" class="png" alt="" />
              Asunto sin MAY&Uacute;SCULAS (ya que esto indica que se est&aacute; gritando).
            </p>
            <p style="margin: 0px; padding: 0px;">
              <img src="' . $tranfer1 . '/icons/no.png" width="16px" height="16px" class="png" alt="" />
              Ser lo m&aacute;s descriptivo o lo m&aacute;s claro posible.
            </p>
            <p style="margin: 0px; padding: 0px;">
              <img src="' . $tranfer1 . '/icons/no.png" width="16px" height="16px" class="png" alt="" />
              Postear temas en la categor&iacute;a correspondiente.
            </p>
            <p style="margin: 0px; padding: 0px;">
              <img src="' . $tranfer1 . '/icons/no.png" width="16px" height="16px" class="png" alt="" />
              Fijarse que los enlaces funcionen correctamente.
            </p>
            <p style="margin: 0px; padding: 0px;">
              <img src="' . $tranfer1 . '/icons/no.png" width="16px" height="16px" class="png" alt="" />
              No revelar informaci&oacute;n personal propia o de terceros tales como correo, MSN, nombres, apellidos, tel&eacute;fonos, etc. (' . $mbname . ' no se hace cargo de problemas al publicar tal contenido)
            </p>
            <br />
            <p style="margin: 0px; padding: 0px;">
              <i><b><u>Nota</u>:</b> Las caracter&iacute;sticas no representa que el post sea eliminado, o se bane&eacute; al usuario que lo cre&oacute;, lo que si representa es que a cualquier moderador le da el derecho de editar tal post.</i>
            </p>
            <br />

            <p style="margin: 0px; padding: 0px; padding-left: 10px;">
              <img src="' . $tranfer1 . '/icons/si.png" width="16px" height="16px" class="png" alt="" />
              <b> Se eliminan los post:</b>
            </p>
            <p style="margin: 0px; padding: 0px;">
              <img src="' . $tranfer1 . '/icons/no.png" width="16px" height="16px" class="png" alt="" />
              Que este considerado spam.
            </p>
            <p style="margin: 0px; padding: 0px;">
              <img src="' . $tranfer1 . '/icons/no.png" width="16px" height="16px" class="png" alt="" />
              Que sea repost.
            </p>
            <p style="margin: 0px; padding: 0px;">
              <img src="' . $tranfer1 . '/icons/no.png" width="16px" height="16px" class="png" alt="" />
              Que contenga un vocabulario vulgar.
            </p>
            <p style="margin: 0px; padding: 0px;">
              <img src="' . $tranfer1 . '/icons/no.png" width="16px" height="16px" class="png" alt="" />
              Que haga referencia a la violaci&oacute;n de los derechos humanos.
            </p>
            <p style="margin: 0px; padding: 0px;">
              <img src="' . $tranfer1 . '/icons/no.png" width="16px" height="16px" class="png" alt="" />
              Que tenga enlaces rotos.
            </p>
            <p style="margin: 0px; padding: 0px;">
              <img src="' . $tranfer1 . '/icons/no.png" width="16px" height="16px" class="png" alt="" />
              Que no contenga la fuente (s&oacute;lo para categor&iacute;a <i>Noticias</i>).
            </p>
            <p style="margin: 0px; padding: 0px;">
              <img src="' . $tranfer1 . '/icons/no.png" width="16px" height="16px" class="png" alt="" />
              Que contenga material pornogr&aacute;fico (im&aacute;genes, videos, enlaces, etc.).
            </p>
            <br />

            <p style="margin: 0px; padding: 0px; padding-left: 10px;"><img src="' . $tranfer1 . '/icons/si.png" width="16px" height="16px" class="png" alt="" /><b> Se eliminan los comentarios:</b></p>
            <p style="margin: 0px; padding: 0px;"><img src="' . $tranfer1 . '/icons/no.png" width="16px" height="16px" class="png" alt="" /> Que contenga tipograf&iacute;as muy grandes, abuso de may&uacute;sculas o con el claro efecto de llamar la atenci&oacute;n.</p>
            <p style="margin: 0px; padding: 0px;"><img src="' . $tranfer1 . '/icons/no.png" width="16px" height="16px" class="png" alt="" /> Genera discusiones (ForoBardo).</p>
            <p style="margin: 0px; padding: 0px;"><img src="' . $tranfer1 . '/icons/no.png" width="16px" height="16px" class="png" alt="" /> Que contengan insultos, ofensas, etc. (hacia otro usuario o de forma general).</p>
            <p style="margin: 0px; padding: 0px;"><img src="' . $tranfer1 . '/icons/no.png" width="16px" height="16px" class="png" alt="" /> Que sea un comentario racista.</p>
            <p style="margin: 0px; padding: 0px;"><img src="' . $tranfer1 . '/icons/no.png" width="16px" height="16px" class="png" alt="" /> Que contenga SPAM.</p>
            <br />

            <p style="margin: 0px; padding: 0px; padding-left: 10px;"><img src="' . $tranfer1 . '/icons/si.png" width="16px" height="16px" class="png" alt="" /><b> Se banea al usuario:</b></p>
            <p style="margin: 0px; padding: 0px;"><img src="' . $tranfer1 . '/icons/no.png" width="16px" height="16px" class="png" alt="" /> Que reiteradas veces hagan lo que no deben hacer.</p>
            <p style="margin: 0px; padding: 0px;"><img src="' . $tranfer1 . '/icons/no.png" width="16px" height="16px" class="png" alt="" /> Que genera SPAM</p>
            <p style="margin: 0px; padding: 0px;"><img src="' . $tranfer1 . '/icons/no.png" width="16px" height="16px" class="png" alt="" /> Que elimina sus comentarios o posts en totalidad (Para hacerlo <a href="/contactanos/" target="_blank" title="Contactar">contactar</a> y dar los motivos).</p>
            <p style="margin: 0px; padding: 0px;"><img src="' . $tranfer1 . '/icons/no.png" width="16px" height="16px" class="png" alt="" /> Que postea material pornogr&aacute;fico o material con morboso</p>
            <br />

            <p style="margin: 0px; padding: 0px; padding-left: 10px;"><img src="' . $tranfer1 . '/icons/si.png" width="16px" height="16px" class="png" alt="" /><b> Se eliminan o modifica las im&aacute;genes:</b></p>
            <p style="margin: 0px; padding: 0px;"><img src="' . $tranfer1 . '/icons/no.png" width="16px" height="16px" class="png" alt="" /> Que contenga SPAM (Imagen con enlace de un sitio)</p>
            <p style="margin: 0px; padding: 0px;"><img src="' . $tranfer1 . '/icons/no.png" width="16px" height="16px" class="png" alt="" /> Que contenga logos de Webs y tapas (Programas, CD de m&uacute;sica, etc.)</p>
            <p style="margin: 0px; padding: 0px;"><img src="' . $tranfer1 . '/icons/no.png" width="16px" height="16px" class="png" alt="" /> Que contenga pornograf&iacute;a.</p>
            <p style="margin: 0px; padding: 0px;"><img src="' . $tranfer1 . '/icons/no.png" width="16px" height="16px" class="png" alt="" /> Que sea morbosa.</p>
            <br />
 
            <p style="margin: 0px; padding: 0px; padding-left: 10px;"><img src="' . $tranfer1 . '/icons/si.png" width="16px" height="16px" class="png" alt="" /><b> Caracter&iacute;sticas para Crear una Comunidad:</b></p>
            <p style="margin: 0px; padding: 0px;"><img src="' . $tranfer1 . '/icons/no.png" width="16px" height="16px" class="png" alt="" /> No utilizar titulo entero en MAYUSCULAS (ya que &iacute;ndica que se est&aacute; gritando). </p>
            <p style="margin: 0px; padding: 0px;"><img src="' . $tranfer1 . '/icons/no.png" width="16px" height="16px" class="png" alt="" /> Ser lo m&aacute;s descriptivo o lo m&aacute;s claro posible.</p>
            <p style="margin: 0px; padding: 0px;"><img src="' . $tranfer1 . '/icons/no.png" width="16px" height="16px" class="png" alt="" /> No revelar Informaci&oacute;n personal propia o de terceros tales como e-mail, MSN, nombres, apellidos, tel&eacute;fonos, etc.</p>
            <p style="margin: 0px; padding: 0px;"><img src="' . $tranfer1 . '/icons/no.png" width="16px" height="16px" class="png" alt="" /> Cada usuario tiene limitado las cantidades de comunidades que puede crear.</p>
            <p style="margin: 0px; padding: 0px;"><i><b>Nota:</b> Las caracter&iacute;sticas no representa que la comunidad sea Eliminada, o suspenda al usuario que la creo, lo que representa es que a cualquier Moderador le da el derecho de editar tal comunidad.</i></p>
            <br />

            <p style="margin: 0px; padding: 0px; padding-left: 10px;"><img src="' . $tranfer1 . '/icons/si.png" width="16px" height="16px" class="png" alt="" /><b> Se eliminan las comunidades:</b></p>
            <p style="margin: 0px; padding: 0px;"><img src="' . $tranfer1 . '/icons/no.png" width="16px" height="16px" class="png" alt="" /> Que haya sido creada con el &uacute;nico objetivo de hacer  SPAM y/o REFERER.</p>
            <p style="margin: 0px; padding: 0px;"><img src="' . $tranfer1 . '/icons/no.png" width="16px" height="16px" class="png" alt="" /> Que contenga un vocabulario vulgar.</p>
            <p style="margin: 0px; padding: 0px;"><img src="' . $tranfer1 . '/icons/no.png" width="16px" height="16px" class="png" alt="" /> Que haga referencia a la violaci&oacute;n de los derechos humanos.</p>
            <p style="margin: 0px; padding: 0px;"><img src="' . $tranfer1 . '/icons/no.png" width="16px" height="16px" class="png" alt="" /> Que contenga material pornogr&aacute;fico (Im&aacute;genes, Videos, Enlaces, etc.) </p>
            <p style="margin: 0px; padding: 0px;"><img src="' . $tranfer1 . '/icons/no.png" width="16px" height="16px" class="png" alt="" /> De un mismo tema, creadas por el mismo usuario.</p>
            <div style="clear: both;"></div>
          </span>
        </div>
      </div>
    </div>';
}

// Editar apariencia
function template_tyc23() {
  global $context, $db_prefix, $boardurl;

  $allEstudios = getEstudios();
  $allIngresos = getIngresos();
  $allMeGustarias = getMeGustarias();
  $allEstados = getEstados();
  $allHijos = getHijos();
  $allColoresPelo = getColoresPelo();
  $allColoresOjos = getColoresOjos();
  $allComplexiones = getComplexiones();
  $allDietas = getDietas();
  $allFumos = getFumos();
  $allAlcoholes = getAlcoholes();

  $pasos = isset($_GET['paso']) ? (int) $_GET['paso'] : '';

  ditaruser();

  // var_dump($context['user']['id']);

  $request = db_query("
    SELECT *
    FROM {$db_prefix}infop
    WHERE id_user = {$context['user']['id']}
    LIMIT 1", __FILE__, __LINE__);

  $row = mysqli_fetch_assoc($request);

  $hp = isset($row['habilidades_profesionales']) ? $row['habilidades_profesionales'] : '';
  $ip = isset($row['intereses_profesionales']) ? $row['intereses_profesionales'] : '';
  $ingresos = isset($row['nivel_de_ingresos']) ? $row['nivel_de_ingresos'] : '';
  $emp = isset($row['empresa']) ? $row['empresa'] : '';
  $estudios = isset($row['estudios']) ? $row['estudios'] : '';
  $prof = isset($row['profesion']) ? $row['profesion'] : '';
  $me_gustaria = isset($row['me_gustaria']) ? $row['me_gustaria'] : '';
  $hijos = isset($row['hijos']) ? $row['hijos'] : '';
  $en_el_amor_estoy = isset($row['en_el_amor_estoy']) ? $row['en_el_amor_estoy'] : '';
  $altura = isset($row['altura']) ? $row['altura'] : '';
  $peso = isset($row['peso']) ? $row['peso'] : '';
  $color_de_pelo = isset($row['color_de_pelo']) ? $row['color_de_pelo'] : '';
  $color_de_ojos = isset($row['color_de_ojos']) ? $row['color_de_ojos'] : '';
  $complexion = isset($row['complexion']) ? $row['complexion'] : '';
  $mi_dieta_es = isset($row['mi_dieta_es']) ? $row['mi_dieta_es'] : '';
  $fumo = isset($row['fumo']) ? $row['fumo'] : '';
  $tomo_alcohol = isset($row['tomo_alcohol']) ? $row['tomo_alcohol'] : '';
  $hobbies = isset($row['hobbies']) ? censorText($row['hobbies']) : '';
  $series_de_tv_favorita = isset($row['series_de_tv_favorita']) ? censorText($row['series_de_tv_favorita']) : '';
  $musica_favorita = isset($row['musica_favorita']) ? censorText($row['musica_favorita']) : '';
  $deportes_y_equipos_favoritos = isset($row['deportes_y_equipos_favoritos']) ? censorText($row['deportes_y_equipos_favoritos']) : '';
  $libros_favoritos = isset($row['libros_favoritos']) ? censorText($row['libros_favoritos']) : '';
  $mis_intereses = isset($row['mis_intereses']) ? censorText($row['mis_intereses']) : '';
  $peliculas_favoritas = isset($row['peliculas_favoritas']) ? censorText($row['peliculas_favoritas']) : '';
  $comida_favorita = isset($row['comida_favorita']) ? censorText($row['comida_favorita']) : '';
  $mis_heroes_son = isset($row['mis_heroes_son']) ? censorText($row['mis_heroes_son']) : '';

  mysqli_free_result($request);

  if ($pasos == 1) {
    $pasoabierto2 = '';
    $pasoabierto2a = ' style="display: none;"';
    $pasoabierto3 = '';
    $pasoabierto3a = ' style="display: none;"';
    $pasoabierto4 = '';
    $pasoabierto4a = ' style="display: none;"';
    $pasoabierto1 = 'titlesCom2';
    $pasoabierto1a = '';
  } else if ($pasos == 2) {
    $pasoabierto1 = '';
    $pasoabierto1a = ' style="display: none;"';
    $pasoabierto3 = '';
    $pasoabierto3a = ' style="display: none;"';
    $pasoabierto4 = '';
    $pasoabierto4a = ' style="display: none;"';
    $pasoabierto2 = 'titlesCom2';
    $pasoabierto2a = '';
  } else if ($pasos == 3) {
    $pasoabierto1 = '';
    $pasoabierto1a = ' style="display: none;"';
    $pasoabierto2 = '';
    $pasoabierto2a = ' style="display: none;"';
    $pasoabierto4 = '';
    $pasoabierto4a = ' style="display: none;"';
    $pasoabierto3 = 'titlesCom2';
    $pasoabierto3a = '';
  } else if ($pasos == 4) {
    $pasoabierto1 = '';
    $pasoabierto1a = ' style="display: none;"';
    $pasoabierto2 = '';
    $pasoabierto2a = ' style="display: none;"';
    $pasoabierto3 = '';
    $pasoabierto3a = ' style="display: none;"';
    $pasoabierto4 = 'titlesCom2';
    $pasoabierto4a = '';
  } else {
    $pasoabierto1 = '';
    $pasoabierto1a = ' style="display: none;"';
    $pasoabierto2 = '';
    $pasoabierto2a = ' style="display: none;"';
    $pasoabierto3 = '';
    $pasoabierto3a = ' style="display: none;"';
    $pasoabierto4 = '';
    $pasoabierto4a = ' style="display: none;"';
  }

  echo '
    <div class="aparence" style="float: left; margin-bottom: 8px; width: 776px;">
      <div class="noesta-am">
        Al editar mi apariencia tambi&eacute;n acepto los
        <a href="' . $boardurl . '/terminos-y-condiciones/" target="_blank">T&eacute;rminos de uso</a>.
      </div>
      <h3 class="titlesCom ' . $pasoabierto1 . '" style="width: 762px;" onclick="chgsec(this)">1. Formaci&oacute;n y trabajo</h3>
      <div class="active" id="contennnt"' . $pasoabierto1a . '>';

  echo '
    <form action="' . $boardurl . '/accion-apariencia/paso1/" method="post" accept-charset="' . $context['character_set'] . '" enctype="multipart/form-data">
      <table cellpadding="4" width="100%">
        <tbody>
          <tr>
            <td align="right" valign="top" width="23%">
              <b>Estudios:</b>
            </td>
            <td width="40%">
              <select id="estudios" name="estudios">';

  foreach ($allEstudios as $key => $value) {
    echo '<option value="' . $key . '"' . ($key == $estudios ? ' selected="selected"' : '') . '>' . $value . '</option>';
  }

  echo '
            </select>
          </td>
        </tr>
        <tr>
          <td align="right" valign="top" width="23%">
            <b>Profesi&oacute;n:</b>
          </td>
          <td width="40%">
            <input size="30" maxlength="32" name="profesion" id="profesion" value="' . $prof . '" type="text" onfocus="foco(this);" onblur="no_foco(this);" />
          </td>
        </tr>
        <tr>
          <td align="right" valign="top">
            <b>Empresa:</b>
          </td>
          <td>
            <input size="30" maxlength="32" name="empresa" id="empresa" value="' . $emp . '" type="text" onfocus="foco(this);" onblur="no_foco(this);" />
          </td>
        </tr>
        <tr>
          <td align="right" valign="top">
            <b>Nivel de ingresos:</b>
          </td>
          <td>
            <select id="ingresos" name="ingresos">';

  foreach ($allIngresos as $key => $value) {
    echo '<option value="' . $key . '"' . ($key == $ingresos ? ' selected="selected"' : '') . '>' . $value . '</option>';
  }

  echo '
                </select>
              </td>
            </tr>
            <tr>
              <td align="right" valign="top">
                <b>Intereses Profesionales:</b>
              </td>
              <td>
                <textarea name="intereses_profesionales" cols="30" rows="5" id="intereses_profesionales" onfocus="foco(this);" onblur="no_foco(this);">' . $ip . '</textarea>
              </td>
            </tr>
            <tr>
              <td align="right" valign="top">
                <b>Habilidades Profesionales:</b>
              </td>
              <td>
                <textarea name="habilidades_profesionales" cols="30" rows="5" id="habilidades_profesionales" onfocus="foco(this);" onblur="no_foco(this);">' . $hp . '</textarea>
              </td>
            </tr>
            <tr>
              <td colspan="3" align="right">
                <div class="hrs"></div>
                <input class="button" style="font-size: 15px;" value="Editar mi apariencia" title="Editar mi apariencia" type="submit" name="enviar-265" />
                <input value="1" type="hidden" name="tipo" />
              </td>
            </tr>
          </tbody>
        </table>
      </form>
    </div>
    <h3 class="titlesCom ' . $pasoabierto2 . '" style="width: 762px;" onclick="chgsec(this)">2. M&aacute;s sobre mi</h3>
    <div class="active" id="contennnt" ' . $pasoabierto2a . '>
      <form action="' . $boardurl . '/accion-apariencia/paso2/" method="post" accept-charset="' . $context['character_set'] . '" enctype="multipart/form-data">
        <table width="100%" cellpadding="4">
          <tbody>
            <tr>
              <td valign="top" width="23%" align="right">
                <b>Me gustar&iacute;a:</b>
              </td>
              <td width="40%">
                <table width="100%" border="0">
                  <tbody>';

  foreach ($allMeGustarias as $key => $value) {
    echo '
      <tr>
        <td>
          <label for="me_gustaria">
            <input name="me_gustaria" id="me_gustaria" value="' . $key . '" type="radio" ' . ($key == $me_gustaria ? ' checked="checked" ' : '') . '/>
            ' . $value . '
          </label>
        </td>
      </tr>';
  }

  echo '
        </tbody>
      </table>
    </tr>
    <tr>
      <td valign="top" align="right">
        <b>En el amor estoy:</b>
      </td>
      <td>
        <table width="100%" border="0">
          <tbody>';

  foreach ($allEstados as $key => $value) {
    echo '
      <tr>
        <td>
          <label for="estado">
            <input name="estado" id="estado" value="' . $key . '" type="radio" ' . ($key == $en_el_amor_estoy ? ' checked="checked" ' : '') . '/>
            ' . $value . '
          </label>
        </td>
      </tr>';
  }

  echo '
        </tbody>
      </table>
    </tr>
    <tr>
      <td valign="top" width="23%" align="right">
        <b>Hijos:</b>
      </td>
      <td width="40%">
        <table width="100%" border="0">
          <tbody>';

  foreach ($allHijos as $key => $value) {
    echo '
      <tr>
        <td>
          <label for="hijos">
            <input name="hijos" id="hijos" value="' . $key . '" type="radio" ' . ($key == $hijos ? ' checked="checked" ' : '') . '/>
            ' . $value . '
          </label>
        </td>
      </tr>';
  }

  echo '
                  </tbody>
                </table>
              </td>
            </tr>
            <tr>
              <td colspan="3" align="right">
                <div class="hrs"></div>
                <input class="button" style="font-size: 15px;" value="Editar mi apariencia" title="Editar mi apariencia" type="submit" name="enviar-265" />
                <input value="2" type="hidden" name="tipo" />
              </td>
            </tr>
          </tbody>
        </table>
      </form>
    </div>
    <h3 class="titlesCom ' . $pasoabierto3 . '" style="width: 762px;" onclick="chgsec(this)">3. Como soy</h3>
    <div class="active" id="contennnt" ' . $pasoabierto3a . '>
      <form action="' . $boardurl . '/accion-apariencia/paso3/" method="post" accept-charset="' . $context['character_set'] . '" enctype="multipart/form-data" style="margin: 0px; padding: 0px;">
        <table width="100%" cellpadding="4">
          <tbody>
            <tr>
              <td align="right" width="23%">
                <b>Mi altura:</b>
              </td>
              <td width="40%">
                <input name="altura" id="altura" size="3" maxlength="3" type="text" onfocus="foco(this);" onblur="no_foco(this);" value="' . str_replace('0', '', $altura) . '" />
                cent&iacute;metros
              </td>
            </tr>
            <tr>
              <td align="right">
                <b>Mi peso:</b>
              </td>
              <td>
                <input name="peso" id="peso" size="3" maxlength="3" type="text" onfocus="foco(this);" onblur="no_foco(this);" value="' . str_replace('0', '', $peso) . '" />
                kilos
              </td>
            </tr>
            <tr>
              <td align="right" width="23%">
                <b>Color de pelo:</b>
              </td>
              <td width="40%">
                <select id="pelo_color" name="pelo_color">';

  foreach ($allColoresPelo as $key => $value) {
    echo '<option value="' . $key . '"' . ($key == $color_de_pelo ? ' selected="selected"' : '') . '>' . $value . '</option>';
  }

  echo '
        </select>
      </td>
    </tr>
    <tr>
      <td align="right">
        <b>Color de ojos:</b>
      </td>
      <td>
        <select id="ojos_color" name="ojos_color">';

  foreach ($allColoresOjos as $key => $value) {
    echo '<option value="' . $key . '"' . ($key == $color_de_ojos ? ' selected="selected"' : '') . '>' . $value . '</option>';
  }

  echo '
        </select>
      </td>
    </tr>
    <tr>
      <td align="right">
        <b>Complexi&oacute;n: ' . $complexion . '</b>
      </td>
      <td>
        <select id="fisico" name="fisico">';

  foreach ($allComplexiones as $key => $value) {
    echo '<option value="' . $key . '"' . ($key == $complexion ? ' selected="selected"' : '') . '>' . $value . '</option>';
  }

  echo '
        </select>
      </td>
    </tr>
    <tr>
      <td align="right" valign="top">
        <b>Mi dieta es:</b>
      </td>
      <td>
          <select id="dieta" name="dieta">';

  foreach ($allDietas as $key => $value) {
    echo '<option value="' . $key . '"' . ($key == $mi_dieta_es ? ' selected="selected"' : '') . '>' . $value . '</option>';
  }

  echo '
        </select>
      </td>
    </tr>
    <tr>
      <td align="right" valign="top">
        <b>Fumo:</b>
      </td>
      <td>
        <table border="0" width="100%">
          <tbody>';

  foreach ($allFumos as $key => $value) {
    echo '
      <tr>
        <td>
          <label for="fumo">
            <input name="fumo" id="fumo" value="' . $key . '" type="radio" ' . ($key == $fumo ? ' checked="checked" ' : '') . '/>
            ' . $value . '
          </label>
        </td>
      </tr>';
  }

  echo '
            </tbody>
          </table>
      </td>
    </tr>
    <tr>
      <td align="right" valign="top">
        <b>Tomo alcohol:</b>
      </td>
      <td>
        <table border="0" width="100%">
          <tbody>';

  foreach ($allAlcoholes as $key => $value) {
    echo '
      <tr>
        <td>
          <label for="tomo_alcohol">
            <input name="tomo_alcohol" id="tomo_alcohol" value="' . $key . '" type="radio" ' . ($key == $tomo_alcohol ? ' checked="checked" ' : '') . '/>
            ' . $value . '
          </label>
        </td>
      </tr>';
  }

  echo '
                  </tbody>
                </table>
              </td>
            </tr>
            <tr>
              <td colspan="3" align="right">
                <div class="hrs"></div>
                <input class="button" style="font-size: 15px;" value="Editar mi apariencia" title="Editar mi apariencia" type="submit" name="enviar-265" />
                <input value="3" type="hidden" name="tipo" />
              </td>
            </tr>
          </tbody>
          </table>
        </form>
      </div>
      <h3 class="titlesCom ' . $pasoabierto4 . '" style="width: 762px;" onclick="chgsec(this)">4. Intereses y preferencias</h3>
      <div class="active" id="contennnt" ' . $pasoabierto4a . '>
        <form action="' . $boardurl . '/accion-apariencia/paso4/" method="post" accept-charset="' . $context['character_set'] . '" enctype="multipart/form-data" style="margin: 0px; padding: 0px;">
          <table width="100%" cellpadding="4">
            <tbody>
              <tr>
                <td align="right" valign="top" width="23%">
                  <b>Mis intereses:</b>
                </td>
                <td width="40%">
                  <textarea style="width: 235px; height: 102px;" name="mis_intereses" cols="30" rows="5" id="mis_intereses" onfocus="foco(this);" onblur="no_foco(this);">' . $mis_intereses . '</textarea>
                </td>
              </tr>
              <tr>
                <td align="right" valign="top">
                  <b>Hobbies:</b>
                </td>
                <td>
                  <textarea style="width: 235px; height: 102px;" name="hobbies" cols="30" rows="5" id="hobbies" onfocus="foco(this);" onblur="no_foco(this);">' . $hobbies . '</textarea>
                </td>
              </tr>
              <tr>
                <td align="right" valign="top">
                  <b>Series de Tv favoritas:</b>
                </td>
                <td>
                  <textarea style="width: 235px; height: 102px;" name="series_tv_favoritas" cols="30" rows="5" id="series_tv_favoritas" onfocus="foco(this);" onblur="no_foco(this);">' . $series_de_tv_favorita . '</textarea>
                </td>
              </tr>
              <tr>
                <td align="right" valign="top" width="23%">
                  <b>M&uacute;sica favorita:</b>
                </td>
                <td width="40%">
                  <textarea style="width: 235px; height: 102px;" name="musica_favorita" cols="30" rows="5" id="musica_favorita" onfocus="foco(this);" onblur="no_foco(this);">' . $musica_favorita . '</textarea>
                </td>
              </tr>
              <tr>
                <td align="right" valign="top">
                  <b>Deportes y equipos favoritos:</b>
                </td>
                <td>
                  <textarea style="width: 235px; height: 102px;" name="deportes_y_equipos_favoritos" cols="30" rows="5" id="deportes_y_equipos_favoritos" onfocus="foco(this);" onblur="no_foco(this);">' . $deportes_y_equipos_favoritos . '</textarea>
                </td>
              </tr>
              <tr>
                <td align="right" valign="top">
                  <b>Libros Favoritos:</b>
                </td>
                <td>
                  <textarea style="width: 235px; height: 102px;" name="libros_favoritos" cols="30" rows="5" id="libros_favoritos" onfocus="foco(this);" onblur="no_foco(this);">' . $libros_favoritos . '</textarea>
                </td>
              </tr>
              <tr>
                <td align="right" valign="top" width="23%">
                  <b>Pel&iacute;culas favoritas:</b>
                </td>
                <td width="40%">
                  <textarea style="width: 235px; height: 102px;" name="peliculas_favoritas" cols="30" rows="5" id="peliculas_favoritas" onfocus="foco(this);" onblur="no_foco(this);">' . $peliculas_favoritas . '</textarea>
                </td>
              </tr>
              <tr>
                <td align="right" valign="top">
                  <b>Comida favor&iacute;ta:</b>
                </td>
                <td>
                  <textarea style="width: 235px; height: 102px;" name="comida_favorita" cols="30" rows="5" id="comida_favorita" onfocus="foco(this);" onblur="no_foco(this);">' . $comida_favorita . '</textarea>
                </td>
              </tr>
              <tr>
                <td align="right" valign="top">
                  <b>Mis h&eacute;roes son:</b>
                </td>
                <td>
                  <textarea style="width: 235px; height: 102px;" name="mis_heroes_son" cols="30" rows="5" id="mis_heroes_son" onfocus="foco(this);" onblur="no_foco(this);">' . $mis_heroes_son . '</textarea>
                </td>
              </tr>
              <tr>
                <td colspan="3" align="right">
                  <div class="hrs"></div>
                  <input class="button" style="font-size: 15px;" value="Editar mi apariencia" title="Editar mi apariencia" type="submit" name="enviar-265" />
                  <input value="4" type="hidden" name="tipo" />
                </td>
              </tr>
            </tbody>
          </table>
        </form>
      </div>
    </div>';
}

?>