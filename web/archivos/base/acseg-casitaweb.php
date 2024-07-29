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
  global $tranfer1, $modSettings, $mbname, $boardurl;

  if (!$modSettings['requireAgreement'] || !$modSettings['terminos']) {
    fatal_error('Los T&eacute;rminos y Condiciones no est&aacute;n habilitados.', false);
  } else {
    $shorturl = str_replace('http://', '', $boardurl);
    // $modSettings['terminos']
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
          <div class="windowbg" style="width: 912px; padding: 4px; text-align: justify; font-size: 12px">
            <p>
              En forma previa a la utilizaci&oacute;n de cualquier servicio o contenido ofrecido en ' . $mbname . ', debe leerse completa y
              atentamente este documento.
              <br /><br />
              Las presentes Condiciones Generales constituyen las normas y reglas dispuestas por ' . $mbname . ', relativas a todos los
              servicios existentes actualmente o que resulten incluidos en el futuro dentro del sitio ' . $shorturl . ' (el Sitio). Dichos
              servicios si bien pueden ser gratuitos, no son de libre utilizaci&oacute;n, sino que est&aacute;n sujetos a un conjunto de pautas
              que regulan su uso. El aprovechamiento que un individuo haga de los servicios incluidos en el Sitio, s&oacute;lo se
              considerar&aacute; l&iacute;cito y autorizado cuando lo sea en cumplimiento de las obligaciones impuestas, con los l&iacute;mites y
              alcances aqu&iacute; delineados, as&iacute; como los que surjan de disposiciones complementarias o accesorias, y/o de las diferentes
              normativas legales de orden nacional e internacional cuya aplicaci&oacute;n corresponda.
              <br />
              <br />
              ' . $mbname . ' podr&aacute; en cualquier momento y sin necesidad de previo aviso modificar estas Condiciones Generales. Tales
              modificaciones ser&aacute;n operativas a partir de su fijaci&oacute;n en el sitio ' . $shorturl . '. Los usuarios deber&aacute;n mantenerse
              actualizados en cuanto al los t&eacute;rminos aqu&iacute; incluidos ingresando en forma peri&oacute;dica al apartado de legales de
              ' . $mbname . '.
              <br />
              <br />
              <strong>1. La aceptaci&oacute;n por parte de los Usuarios</strong>
              <br />
              ' . $mbname . ', se reserva el derecho a exigir que cada usuario, acepte y cumpla los t&eacute;rminos aqu&iacute; expresados como
              condici&oacute;n previa y necesaria para el acceso, y utilizaci&oacute;n de los servicios y/o contenidos brindados por el Sitio.
            </p>
            <p>
              Cuando un usuario accediere al Sitio y utilizare cualquiera de los servicios y/o contenidos existentes, har&aacute; presumir
              el conocimiento del presente texto y que ha manifestado su plena aceptaci&oacute;n con respecto a todas y cada una de las
              disposiciones que lo integran.
            </p>
            <p>
              El usuario que no acepte, se halle en desacuerdo, o incurriere en incumplimiento de las disposiciones fijadas por la
              ' . $mbname . ' en estas Condiciones Generales, no contar&aacute; con autorizaci&oacute;n para el uso de los servicios y contenidos que
              existen o puedan existir en el Sitio, debiendo retirarse del Sitio en forma inmediata, y abstenerse de ingresar
              nuevamente al mismo.
              <br />
              <br />
              <strong>2. Capacidad legal de los usuarios.</strong>
              <br />
              S&oacute;lo podr&aacute;n acceder y utilizar los servicios y/o contenidos de ' . $mbname . ', quienes a tenor de la legislaci&oacute;n vigente en
              su lugar de residencia puedan v&aacute;lidamente emitir su consentimiento para la celebraci&oacute;n de contratos. Quienes a
              tenor de la legislaci&oacute;n vigente no posean tal capacidad para acceder u obligarse v&aacute;lidamente a los t&eacute;rminos y
              condiciones aqu&iacute; establecidos, deber&aacute;n obtener inexcusablemente autorizaci&oacute;n previa de sus representantes legales,
              quienes ser&aacute;n considerados responsables de todos los actos realizados por los incapaces a su cargo.
              <br />
              <br />
              Cuando se trate de falta de capacidad por minor&iacute;a de edad, la responsabilidad en la determinaci&oacute;n de los servicios y
              contenidos a los que acceden los menores de edad corresponde a los mayores a cuyo cargo se encuentren, sin embargo en
              ning&uacute;n caso estar&aacute; permitido el acceso al sitio por parte de menores de 18 años de edad.
              <br />
              <br />
              <strong>3. Registraci&oacute;n de los Usuarios.</strong>
              <br />
              Para valerse de los servicios prestados en ' . $mbname . ', basta la sola aceptaci&oacute;n de estas Condiciones Generales. Sin
              embargo para la utilizaci&oacute;n de algunos servicios o el acceso a ciertos contenidos, podr&aacute; establecerse como requisito,
              el previo registro del usuario. Dicho registro tendr&aacute; por finalidad establecer la identidad e informaci&oacute;n de contacto
              del usuario.
              <br />
              <br />
              Toda vez que para la registraci&oacute;n de un usuario le sea requerida informaci&oacute;n, la misma deber&aacute; ser fidedigna, y poseer&aacute;
              el car&aacute;cter de declaraci&oacute;n jurada. Cuando la informaci&oacute;n suministrada no atienda a las circunstancias reales de quien
              la brinda, se considerara tal usuario incurso en incumplimiento de estas Condiciones Generales, siendo responsable por
              todos los perjuicios que derivaren para ' . $mbname . ' o terceros como consecuencia de tal falta de veracidad o
              exactitud.
              <br />
              <br />
              El usuario dispondr&aacute;, una vez registrado, de un nombre de usuario y una contrase&ntilde;a que le permitir&aacute; el acceso
              personalizado, confidencial y seguro a su cuenta personal dentro del Sitio. Los servicios sujetos a registraci&oacute;n han
              sido concebidos para el uso personal del usuario requirente, por tanto el nombre de usuario y la contrase&ntilde;a de acceso
              concedidos por ' . $mbname . ' solo podr&aacute;n ser usados por este, estando prohibida su utilizaci&oacute;n por otra persona distinta
              al mismo. El usuario registrado asumir&aacute; la obligaci&oacute;n de guarda y custodia de su nombre de usuario y contrase&ntilde;a de
              acceso, debiendo informar inmediatamente a ' . $mbname . ' cuando los mismos hubieren perdido su estado de confidencialidad,
              y/o cuando sean objeto de uso por un tercero.
              <br />
              <br />
              Ser&aacute; tambi&eacute;n responsabilidad de cada usuario mantener actualizada su informaci&oacute;n personal asentada en el registro
              conforme resulte necesario, debiendo comunicar a ' . $mbname . ' toda vez que se produzcan cambios en relaci&oacute;n a la
              misma.
              <br />
              <br />
              ' . $mbname . ' podr&aacute; rechazar cualquier solicitud de registraci&oacute;n o, cancelar una registraci&oacute;n previamente aceptada, sin
              que tal decisi&oacute;n deba ser justificada, y sin que ello genere derecho alguno en beneficio del Usuario.
              <br />
              <br />
              ' . $mbname . ' utilizar&aacute; la informaci&oacute;n suministrada por el usuario exclusivamente con el objeto expuesto, y en todo
              momento velar&aacute; por el razonable resguardo a la intimidad y confidencialidad de las comunicaciones del usuario, pero
              atento que ' . $mbname . ' hace uso de sistemas tecnol&oacute;gicos que bajo ciertas condiciones pueden resultar falibles, se pone
              en conocimiento de los usuarios que ' . $mbname . ' no garantiza la inviolabilidad de sus sistemas, motivo por el cual los
              usuarios deber&aacute;n tomar en consideraci&oacute;n esta circunstancia al momento de decidir su registraci&oacute;n.
              <br />
              <br />
              En todos los casos, y de acuerdo con la <a href="' . $boardurl . '/privacidad-de-datos/">Pol&iacute;tica de Privacidad</a> sostenida por
              ' . $mbname . ', la informaci&oacute;n de car&aacute;cter personal suministrada por los Usuarios ser&aacute; objeto de adecuado tratamiento y
              preservaci&oacute;n, en resguardo de la privacidad de la misma. Sin embargo, los servicios de ' . $mbname . ' fueron dise&ntilde;ados
              entre otros fines para permitir que los usuarios accedan a ciertos datos (no sensibles) de otros usuarios permitiendo
              la interacci&oacute;n entre los mismos dentro de un esquema de red social. Por consiguiente, haciendo entrega de cualquier
              informaci&oacute;n personal distinta de su nombre, el usuario renuncia a cualquier expectativa de privacidad que posea con
              respecto al uso de esa informaci&oacute;n personal proporcionada dentro del sitio. Los usuarios que no deseen que su
              fotograf&iacute;a o imagen, p&aacute;gina web, mensajero, ciudad de residencia, nacionalidad, o descripci&oacute;n personal ingresadas en
              el Sitio, puedan ser brindadas al p&uacute;blico no deber&aacute;n registrarse en ' . $mbname . '.
              <br />
              <br />
              <strong>4. Notificaciones y comunicaciones</strong>
              <br />
              A los fines que los usuarios puedan tomar contacto con ' . $mbname . ', se considerar&aacute;n v&aacute;lidas las comunicaciones dirigidas
              a:
              <br />
              <br />
              Email:
              denuncias[@]' . $shorturl . '
              <br />
              <br />
              Las notificaciones y comunicaciones cursadas por ' . $mbname . ' a la casilla de correo electr&oacute;nico que surja como direcci&oacute;n
              de correo del usuario o remitente se considerar&aacute;n eficaces y plenamente v&aacute;lidas. Asimismo se considerar&aacute;n eficaces las
              comunicaciones que consistan en avisos y mensajes insertos en el sitio, o que se env&iacute;en durante la prestaci&oacute;n de un
              servicio, que tengan por finalidad informar a los usuarios sobre determinada circunstancia.&nbsp;&nbsp;&nbsp;
            </p>
            <br />
            <p>
              <strong>5. Libre acceso a los Servicios</strong>
              <br />
              M&aacute;s all&aacute; de la obligaci&oacute;n de cumplimiento de todas y cada una de estas Condiciones Generales, todos los servicios y
              contenidos ofrecidos en el Sitio son libremente accesibles por parte de los usuarios. La libre accesibilidad incluye
              la gratuidad de los servicios, que no estar&aacute;n sujetos al pago de ning&uacute;n arancel o retribuci&oacute;n hacia ' . $mbname . '.
              <br />
              <br />
              Tal gratuidad no es de aplicaci&oacute;n sobre los servicios de terceros brindados a trav&eacute;s del sitio que podr&aacute;n no ser
              gratuitos, y en igual sentido aquellos servicios y/o contenidos, actuales o futuros sobre los que ' . $mbname . ' decida
              establecer un canon para su utilizaci&oacute;n por parte de los usuarios.
              <br />
              El libre acceso y gratuidad no comprenden las facilidades de conexi&oacute;n a Internet. En ning&uacute;n caso ' . $mbname . ', proveer&aacute; a
              los usuarios la conectividad necesaria para que estos accedan a Internet. Ser&aacute; por exclusiva cuenta, cargo y
              responsabilidad de cada usuario la disposici&oacute;n de los medios t&eacute;cnicos necesarios para acceder a Internet.
            </p>
            <p>
              <br />
              <strong>6. De los servicios y contenidos en particular</strong>
              <br />
              ' . $mbname . ' es un sitio de Internet basado en una herramienta de comunicaci&oacute;n, que permite poner en contacto a sus
              usuarios para que los mismos compartan opiniones, comentarios, y en general cualquier tipo de informaci&oacute;n que sea de
              su inter&eacute;s. El objetivo de ' . $mbname . ' es la creaci&oacute;n de un &aacute;mbito de comunicaci&oacute;n y esparcimiento tan amplio como sea
              posible, destinado al p&uacute;blico de Internet en general.
            </p>
            <p>
              <br />
              <strong>6.1. De los post </strong>
              <br />
              El principal servicio que ' . $mbname . ' pone a disposici&oacute;n de los usuarios es la posibilidad de conocer las
              manifestaciones expresadas por otros usuarios, publicadas en el Sitio en forma de mensajes o "posts". Conforme lo
              establecido en el punto 3.<strong> </strong>de las Condiciones Generales, para obtener &nbsp;acceso para la
              visualizaci&oacute;n y lectura de los post solo basta la aceptaci&oacute;n de las mismas; sin embargo la creaci&oacute;n y fijaci&oacute;n de
              post, al igual que el acceso a post determinados as&iacute; como a ciertas funcionalidades, solo estar&aacute; reservada a los
              usuarios registrados.
              <br />
              <br />
              <u>a) Creaci&oacute;n y fijaci&oacute;n</u>
              <br />
              Quienes se registren en ' . $mbname . ' podr&aacute;n publicar sus posts libremente, para ello ' . $mbname . ' pone a disposici&oacute;n de los
              usuarios registrados una herramienta para la creaci&oacute;n y edici&oacute;n de sus posts, junto con los medios necesarios para su
              almacenamiento y exhibici&oacute;n dentro del Sitio.
              <br />
              <br />
              <u>b) Contenido de los post</u>
              <br />
              Los post que los usuarios incorporen s&oacute;lo podr&aacute;n contener texto. Cuando el usuario pretendiere insertar en su post
              fotograf&iacute;as, im&aacute;genes, ilustraciones, videos,&nbsp; animaciones, o referencia a archivos o sitios ajenos a ' . $mbname . ',
              s&oacute;lo podr&aacute; hacerlo a trav&eacute;s de links, mediante la indicaci&oacute;n de la direcci&oacute;n URL (Uniform Resource Locator) en donde
              se encuentre alojado el archivo que pretenda asociar a su post.
              <br />
              <br />
              ' . $mbname . ' pone en conocimiento de los usuarios y terceros en general, que los archivos asociados a un post no forman
              parte de &eacute;ste y no se encuentran reproducidos en ning&uacute;n sistema o plataforma del Sitio. ' . $mbname . ' solo proceder&aacute; a la
              publicaci&oacute;n de la direcci&oacute;n URL del archivo asociado, pudiendo en determinados casos se efectuar un <strong>embedded
                link que permita la visualizaci&oacute;n del enlace dentro del Sitio.</strong><strong> Consecuentemente, e</strong>n ning&uacute;n
              caso los usuarios podr&aacute;n transferir archivos hacia el sitio con el objeto que los mismos sean incorporados a sus post,
              o en general realizar una carga o "upload" al propio Sitio, de tal forma que esos archivos ( o una copia de ellos)
              pasen a residir en los servidores de ' . $mbname . '. En igual sentido no existen en el sitio archivos destinados a su
              descarga por parte de los usuarios.
              <br />
              <br />
              ' . $mbname . ' es un sitio dedicado a la comunicaci&oacute;n entre personas, mediante una estructura de red social.
              <br />
              ' . $mbname . ' NO ACTUA COMO UN CENTRO DE ALMACENAMIENTO O CONSERVACI&oacute;N ARCHIVOS.
              <br />
              ' . $mbname . ' NO ACTUA COMO UN SITIO DE INTERCAMBIO DE ARCHIVOS.
              <br />
              ' . $mbname . ' NO ACTUA COMO UN tracker.
              <br />
              ' . $mbname . ' NO CONSTITUYE UNA RED P2P (peer to peer).
              <br />
              <br />
              &nbsp;<u>c) Sobre los links incorporados en los posts</u>
              <br />
              Un link dentro de una p&aacute;gina web (denominado tambi&eacute;n enlace, v&iacute;nculo, hiperv&iacute;nculo o, hiperenlace) es un elemento que
              hace referencia a otro recurso, por ejemplo, otra p&aacute;gina o sitio web. <br />
              As&iacute; los links a diversos archivos que los usuarios incorporan en los post publicados en ' . $mbname . ' permiten invocar a
              una p&aacute;gina web determinada, o a una posici&oacute;n determinada en una p&aacute;gina web, pero en todos los casos los links siempre
              har&aacute;n referencia a paginas web titularidad de terceros y ajenas al control de ' . $mbname . '.
              <br />
              <br />
              Los links son simples enlaces que direcciona hacia cierta informaci&oacute;n o activan determinados contenidos, pero que en
              ning&uacute;n caso constituyen reproducciones de los contenidos a los cuales enlaza.
              <br />
              <br />
              <u>d) Aspectos a tener en cuenta sobre la incorporaci&oacute;n de links:</u>
              <br />
              Uno de los principales derechos patrimoniales de un autor es el de reproducci&oacute;n de su obra, este derecho confiere la
              facultad de prohibir reproducciones de su obra sin autorizaci&oacute;n previa y expresa. Un link no vulnera el derecho de
              reproducci&oacute;n, las direcciones URL, son meros hechos que no est&aacute;n protegidos por el derecho de autor por no implicar la
              realizaci&oacute;n de una copia de una obra. Sin embargo cuando el autor o el titular de los derechos sobre una obra no la
              hubiere publicado, nadie sin autorizaci&oacute;n de &eacute;ste podr&iacute;a l&iacute;citamente hacerlo, por consiguiente LOS USUARIOS SOLO
              PODRAN ASOCIAR A SUS POSTS, LINKS QUE REFIERAN A OBRAS QUE HUBIEREN SIDO LICITAMENTE PUBLICADAS EN INTERNET POR SU
              TITULAR.
              <br />
              <br />
              <u>e) Calificar posts:</u>
              <br />
              Este funcionalidad consiste en la posibilidad que tienen los usuarios registrados luego de superar la etapa de
              "novatos", de efectuar una ponderaci&oacute;n o calificaci&oacute;n sobre los posts fijados por&nbsp; otros usuarios.
            </p>
            <p>
              La calificaci&oacute;n que reciba cada post ser&aacute; exhibida sobre el mismo, y el conjunto de calificaciones recibidas por un
              usuario en base a sus posts establecer&aacute; la puntuaci&oacute;n total del usuario, de esta forma el&nbsp; usuario podr&aacute; conocer
              la opini&oacute;n general de los dem&aacute;s usuarios expresada a trav&eacute;s de la calificaci&oacute;n de la que fueran merecedores sus post.
            </p>
            <p>
              <br />
              <strong>6.2. De los comentarios</strong>
              <br />
              Otro de los servicios brindados por ' . $mbname . ', reservado s&oacute;lo a usuarios registrados, es la posibilidad de incorporar
              comentarios en forma de mensajes sobre un post de otro usuario, de tal forma que permita un intercambio de opiniones o
              aportes sobre el post que viene a comentar.
            </p>
            <p>
              <br />
              <strong>6.3. Del contacto entre usuarios</strong>
              <br />
              Adicionalmente ' . $mbname . ' brinda la posibilidad a los usuarios de conocer y establecer una comunicaci&oacute;n directa con
              otros usuarios, estableciendo un sistema de contacto mediante sesiones de chat, o a trav&eacute;s de la informaci&oacute;n por ellos
              brindada para ser incorporada en su perfil.
            </p>
              <br />
            <p>
              <strong>6.4. Disposiciones comunes</strong>
              <br />
              a) Conforme se detalla en el punto 7; todo usuario ser&aacute; exclusivo responsable por los post y comentarios que
              fije.
            </p>
            <p>
              b) La enumeraci&oacute;n precedente es al solo efecto enunciativo y no taxativo. ' . $mbname . ' podr&aacute; agregar, modificar,
              suprimir total o parcialmente los servicios y contenidos, sin que para ello se requiera conformidad o notificaci&oacute;n
              previa de ning&uacute;n tipo. Salvo estipulaci&oacute;n en contrario, todo nuevo contenido o ampliaci&oacute;n de los existentes se regir&aacute;
              por estas Condiciones Generales.
            </p>
            <p>
              <br />
              <strong>7.&nbsp; Responsabilidades, direcci&oacute;n y control sobre los servicios.</strong>
            </p>
            <p>
              <br />
              <strong>7.1. Facultades reservadas</strong>
              <br />
              ' . $mbname . ' se reserva todas las facultades de control y direcci&oacute;n del Sitio, en particular de los servicios, contenidos
              y comunicaciones habidos dentro del mismo. Podr&aacute; en consecuencia ' . $mbname . ', introducir todos los cambios y
              modificaciones que estime convenientes a su solo criterio, podr&aacute; agregar, alterar, sustituir o suprimir cualquiera de
              los servicios o contenidos en todo momento.
              <br />
              <br />
              En especial ' . $mbname . ' se reserva la facultad de controlar, editar, suprimir parcial o totalmente, cualquier post o
              comentario fijado por un usuario. Dicha facultad reposa en las facultades de direcci&oacute;n que posee ' . $mbname . ' en cuanto
              titular del Sitio, y su ejercicio no estar&aacute; supeditado a justificaci&oacute;n o causa alguna, quedando en todos los casos
              dicho ejercicio reservado a la discreci&oacute;n y voluntad de ' . $mbname . '. Sin perjuicio de ello y al solo efecto de servir de
              gu&iacute;a orientativa para los usuarios, ' . $mbname . ' podr&aacute; establecer una serie de recomendaciones sobre los contenidos
              aceptados y aquellos que no lo fueren en relaci&oacute;n a los posts y comentarios. Esta gu&iacute;a ser&aacute; accesible a los usuarios
              desde el propio Sitio y se la mencionar&aacute; como "protocolo" o bajo alguna otra designaci&oacute;n similar.
            </p>
            <p>
              <br />
              <strong>7.2<em>. </em></strong>
              <strong>Responsabilidades en relaci&oacute;n a los servicios prestados:</strong>
              <br />
              Cada usuario ser&aacute; exclusivo responsable por las manifestaciones que vierta o las acciones que lleve adelante dentro
              del marco del sitio.&nbsp; Sin embargo cuando ' . $mbname . ' reciba a trav&eacute;s de su mecanismo de recepci&oacute;n de denuncias, la
              manifestaci&oacute;n de una persona, que hubiere sufrido en forma injustificada un menoscabo en cualquiera de sus derechos,
              tomar&aacute; en forma inmediata las medidas necesarias para evitar la continuaci&oacute;n de la situaci&oacute;n perjudicial, y pondr&aacute; en
              conocimiento de las autoridades competentes los acontecimientos del caso.
              <br />
              <br />
              Sin perjuicio de estas facultades reservadas, ' . $mbname . ' en respeto de la privacidad y confidencialidad de las
              comunicaciones de los usuarios, no ejercer&aacute; un control de legalidad directo sobre las manifestaciones y/o acciones
              llevadas adelante por los usuarios. Consecuentemente no ser&aacute; responsable por el uso contrario a derecho que de los
              contenidos y servicios, hagan los usuarios, ni garantiza que los datos proporcionados por estos, relativos a su
              identidad sean veraces y fidedignos.
              <br />
              <br />
              ' . $mbname . ' es una plataforma concebida para la comunicaci&oacute;n y difusi&oacute;n de informaci&oacute;n, la utilizaci&oacute;n del Sitio
              realizada por un usuario, que impliquen un desmedro o la lisa y llana violaci&oacute;n de derechos de terceros, en especial
              los relativos a la propiedad intelectual, har&aacute; plenamente responsable a ese usuario por los da&ntilde;os que tal conducta
              irrogare para los terceros y/o ' . $mbname . '.
              <br />
              <br />

              <strong>7.2<em>. </em></strong>
              <strong>Responsabilidades en relaci&oacute;n a los servicios prestados:</strong>
              <br />
              Cada usuario ser&aacute; exclusivo responsable por las manifestaciones que vierta o las acciones que lleve adelante dentro
              del marco del sitio.&nbsp; Sin embargo cuando ' . $mbname . ' reciba a trav&eacute;s de su mecanismo de recepci&oacute;n de denuncias, la
              manifestaci&oacute;n de una persona, que hubiere sufrido en forma injustificada un menoscabo en cualquiera de sus derechos,
              tomar&aacute; en forma inmediata las medidas necesarias para evitar la continuaci&oacute;n de la situaci&oacute;n perjudicial, y pondr&aacute; en
              conocimiento de las autoridades competentes los acontecimientos del caso.
              <br />
              <br />
              Sin perjuicio de estas facultades reservadas, ' . $mbname . ' en respeto de la privacidad y confidencialidad de las
              comunicaciones de los usuarios, no ejercer&aacute; un control de legalidad directo sobre las manifestaciones y/o acciones
              llevadas adelante por los usuarios. Consecuentemente no ser&aacute; responsable por el uso contrario a derecho que de los
              contenidos y servicios, hagan los usuarios, ni garantiza que los datos proporcionados por estos, relativos a su
              identidad sean veraces y fidedignos.
              <br />
              <br />
              ' . $mbname . ' es una plataforma concebida para la comunicaci&oacute;n y difusi&oacute;n de informaci&oacute;n, la utilizaci&oacute;n del Sitio
              realizada por un usuario, que impliquen un desmedro o la lisa y llana violaci&oacute;n de derechos de terceros, en especial
              los relativos a la propiedad intelectual, har&aacute; plenamente responsable a ese usuario por los da&ntilde;os que tal conducta
              irrogare para los terceros y/o ' . $mbname . '.
              <br />
              <br />
              ' . $mbname . ' o sus integrantes, NO ser&aacute;n responsables en modo alguno por el contenido de las manifestaciones, opiniones,
              comentarios, e informaci&oacute;n volcada en un post o comentario.
            </p>
            <p>
              <br />
              <strong>8. Utilizaci&oacute;n de los servicios y contenidos brindados por el Sitio</strong>
              <br />
              Los usuarios deber&aacute;n utilizar los servicios, y acceder a los contenidos del sitio de conformidad con las disposiciones
              establecidas en estas <b style="color: black; background-color: rgb(160, 255, 255);">Condiciones</b> Generales; con el
              ordenamiento jur&iacute;dico al que se encuentren sometidos en raz&oacute;n del lugar, de las personas, o de la materia de la cual
              se trate, considerado en su conjunto; y seg&uacute;n las pautas de conducta impuestas por la moral, las buenas costumbres y
              el debido respeto a los derechos de terceros.
            </p>
            <p>
              <br />
              <strong>8.1. USO PROHIBIDO de los servicios o contenidos</strong>
              <br />
              Cualquier uso de los servicios que tenga por objeto, lesionar los derechos de terceros, contravenir el orden jur&iacute;dico
              o constituya una pr&aacute;ctica ofensiva al pudor p&uacute;blico, se reputar&aacute; como USO PROHIBIDO de los servicios o contenidos, en
              tanto transgrede los fines para los que fue puesto a disposici&oacute;n de los usuarios.
            </p>
            <p>
              <br />
              Se considerar&aacute; como USO PROHIBIDO, entre otros, la fijaci&oacute;n de post, mensajes o comentarios, propagaci&oacute;n, as&iacute; como la
              indicaci&oacute;n de v&iacute;nculos a p&aacute;ginas web, que:
            </p>
            <ol>
              <li>Resulten ofensivos para los derechos personal&iacute;simos de los individuos, con especial referencia al derecho al
                honor, a la dignidad, a la intimidad, a no ser objeto de tratos discriminatorios, a la salud, a la imagen, y a la
                libre expresi&oacute;n de las ideas; con absoluta independencia del cuerpo legal donde tales derechos adquieran
                reconocimiento.</li>
              <li>Infrinjan los derechos de propiedad intelectual de terceros.</li>
              <li>Posea contenido inapropiado.</li>
              <li>Tenga por objeto vulnerar la seguridad, y o normal funcionamiento de los sistemas inform&aacute;ticos de ' . $mbname . ' o de
                terceros.</li>
              <li>Induzca, instigue o promueva acciones delictivas, il&iacute;citas, disfuncionales o moralmente reprochables, o constituya
                una violaci&oacute;n de derechos de propiedad intelectual de terceras personas.</li>
              <li>Incorporen alguna forma de publicidad o fin comercial no permitidos por ' . $mbname . '.</li>
              <li>Tenga por objeto recolectar informaci&oacute;n de terceros con el objeto de remitirles publicidad o propaganda de
                cualquier tipo o especie, sin que esta fuera expresamente solicitada.</li>
            </ol>
            <p>
              <strong>8.2. Medidas de control</strong>
              <strong></strong>
              <br />
              Sin perjuicio de las acciones legales nacidas en cabeza de ' . $mbname . ' o terceros, cuando el uso de los servicios,
              llevado adelante por parte de un usuario pueda ser reputado por ' . $mbname . ' como USO PROHIBIDO, ' . $mbname . ' tomar&aacute; las
              medidas que considere convenientes seg&uacute;n su exclusivo criterio, pudiendo suspender o impedir el acceso a los servicios
              o contenidos a aquellos usuarios incursos en el uso prohibido de los mismos, y sin que para ello deba mediar
              comunicaci&oacute;n previa alguna.
            </p>
            <p>
              <br />
              <strong>9. Aspectos relacionados con la Propiedad Intelectual</strong>
            </p>
            <p>
              <br />
              <strong>9.1. Contenido de terceros </strong>
              <br />
              En uso de los servicios ofrecidos en el Sitio, el usuario puede tener acceso a contenidos provistos por otros
              usuarios o terceros. ' . $mbname . ' realiza sus mejores esfuerzos para controlar el material que le es suministrado, sin
              embargo, el usuario acepta que eventualmente podr&aacute; ser expuesto a contenido de terceros que sea falso, ofensivo,
              indecente o de otra manera inaceptable. Bajo ninguna circunstancia podr&aacute; responsabilizar a ' . $mbname . ' por tal
              circunstancia.
            </p>
            <p>
              <br />
              <strong>9.2. Material titularidad de ' . $mbname . '</strong>
              <br />
              Todo el material existente en el sitio, que no corresponda a un usuario u otro tercero, constituye propiedad exclusiva
              de ' . $mbname . '. A t&iacute;tulo meramente enunciativo, se entender&aacute;n incluidos las im&aacute;genes, fotograf&iacute;as, dise&ntilde;os, gr&aacute;ficos,
              sonidos, compilaciones de datos, marcas, nombres, t&iacute;tulos, designaciones, signos distintivos, y&nbsp; todo otro
              material accesible a trav&eacute;s del sitio.
              <br />
              <br />
              La titularidad del conjunto o selecci&oacute;n de links incorporados por los usuarios al Sitio, corresponder&aacute; a ' . $mbname . ' en
              calidad de propiedad intelectual, en tanto obra de clasificaci&oacute;n y compilaci&oacute;n. Dicha propiedad esta constituida no
              por los v&iacute;nculos o links considerados en forma individual, sino por la selecci&oacute;n del conjunto de links. En ese orden
              los usuarios ceden y transfieren irrevocablemente a ' . $mbname . ' todos los derechos que pudieran corresponderles sobre la
              selecci&oacute;n de links que cada uno de ello individualmente hubiere realizado.
              <br />
              <br />
              ' . $mbname . ' se reserva todos los derechos sobre el mencionado material, no cede ni transfiere a favor del usuario ning&uacute;n
              derecho sobre su propiedad intelectual o la de terceros. En consecuencia, su reproducci&oacute;n, distribuci&oacute;n, y/o
              modificaci&oacute;n deber&aacute; ser expresamente autorizada por parte de ' . $mbname . ', so pena de considerarse una actividad il&iacute;cita
              violatoria de los derechos de propiedad intelectual de ' . $mbname . '.
              <br />
              <br />
              Los usuarios del sitio s&oacute;lo contar&aacute;n con autorizaci&oacute;n para la utilizaci&oacute;n del material propiedad de ' . $mbname . ', cuando
              las finalidades de tal utilizaci&oacute;n sean aquellas espec&iacute;ficamente previstas por ' . $mbname . '.
            </p>

            A t&iacute;tulo informativo, se pone en conocimiento de los usuarios y visitantes del sitio, que los derechos relativos a la
            propiedad intelectual de ' . $mbname . ', quedan resguardados internacionalmente bajo la protecci&oacute;n del Convenio de Berna; el
            tratado de la WIPO (World Intellectual Property Organization) sobre derechos de autor, y dem&aacute;s disposiciones
            coincidentes; el acuerdo TRIPs (Trade Related Aspects of Intellectual Property Rights); que en su conjunto aseguran la
            plena vigencia internacional de los derechos de ' . $mbname . '.
            <br />
            <br />
            <p>
              <strong>10. Operatividad del sitio </strong>
              <br />
              Correspondientemente con el car&aacute;cter gratuito de los servicios brindados, ' . $mbname . ' no garantiza la plena operatividad
              del sitio y el acceso a los servicios y contenidos del mismo. En ning&uacute;n caso ' . $mbname . ' responder&aacute; por la operatividad,
              eficacia y seguridad de los servicios y contenidos puestos a disposici&oacute;n de los usuarios.
              <br />
              <br />
              ' . $mbname . ' no garantiza la conservaci&oacute;n, integridad ni indemnidad de los post, mensajes o comentarios fijados por los
              usuarios.
            </p>
            <p>
              <br />
              <strong>11. LINKS hacia ' . $mbname . '</strong>
              <br />
              El establecimiento de cualquier link, hiperv&iacute;nculo o enlace, entre una p&aacute;gina web ajena al sitio
              "' . $boardurl . '" y cualquier p&aacute;gina de este &uacute;ltimo solo podr&aacute; realizarse con expresa autorizaci&oacute;n por parte de
              ' . $mbname . '.
              <br />
              <br />
              En ning&uacute;n caso ' . $mbname . ' ser&aacute; responsable por los contenidos o manifestaciones existentes en las p&aacute;ginas web desde
              donde se establezcan los hiperv&iacute;nculos hacia el sitio de ' . $mbname . '. El hecho que exista un link entre una p&aacute;gina web y
              el sitio de ' . $mbname . ' no implica que ' . $mbname . ' tenga conocimiento de ello, o que ' . $mbname . ' mantenga relaci&oacute;n alguna
              con los titulares de la p&aacute;gina web desde donde se establece el enlace.
              <br />
              <br />
              ' . $mbname . ', se reserva el derecho a solicitar la remoci&oacute;n o eliminaci&oacute;n de cualquier enlace desde una p&aacute;gina web ajena
              al Sitio, en cualquier momento, sin expresi&oacute;n de causa, y sin que sea necesario preaviso alguno. El responsable de la
              p&aacute;gina web desde la cual se efectuare el enlace tendr&aacute; un plazo de 48hs. contados a partir del pedido de ' . $mbname . '
              para proceder a la remoci&oacute;n o eliminaci&oacute;n del mismo.
            </p>
            <p>
              <br />
              <strong>12</strong>. <strong>LINKS desde ' . $mbname . '</strong>
            </p>
            <p>
              <br />
              <strong>12.1. Links provistos por ' . $mbname . '</strong>
              <br />
              Los hiperv&iacute;nculos o enlaces a p&aacute;ginas web de terceros provistos por ' . $mbname . ', tienen por finalidad mejorar la
              experiencia de navegaci&oacute;n del usuario por el sitio ' . $mbname . ', poniendo a su disposici&oacute;n canales de acceso a otros
              sitios.
            </p>
            <p>
              <br />
              <strong>12.2 Links provistos por los usuarios</strong>
              <br />
              De acuerdo a lo expresado en el punto 6; los usuarios podr&aacute;n incorporar en sus post links que remitan a distintos
              recursos alojados fuera del Sitio!. El objeto de estos links es incrementar las posibilidades de comunicaci&oacute;n de los
              usuarios, permitiendo as&iacute; la referencia a cualquier elemento que se encuentre en Internet.
            </p>
            <p>
              <br />
              <strong>12.3. Responsabilidad derivada de los links</strong>}
              <br />
              En ninguno de los casos precedentemente enunciados ' . $mbname . ' controla, respalda o garantiza la seguridad, calidad,
              licitud, veracidad e idoneidad de los servicios y contenidos a los cuales se acceda a trav&eacute;s de un hiperv&iacute;culo. La
              inclusi&oacute;n del link no significa que ' . $mbname . ' se encuentre en forma alguna relacionada con el sitio al que dirige el
              link, ni que apoye, este de acuerdo, facilite o colabore en las actividades que en ese sitio se desarrollen.
              <br />
              La responsabilidad por los servicios o contenidos en los sitios enlazados corresponder&aacute; exclusivamente a los titulares
              de dichos sitios. Bajo ning&uacute;n supuesto ' . $mbname . ' ser&aacute; responsable por las irregularidades, ilicitudes o infracciones
              que en dichos sitios se registren, no respondiendo en tal sentido por los da&ntilde;os que pudieren experimentar los usuarios
              o terceros a partir de los contenidos all&iacute; publicados.
              <br />
              El acceso y utilizaci&oacute;n de p&aacute;ginas web enlazadas desde el sitio de ' . $mbname . ' ser&aacute; exclusiva responsabilidad del
              usuario, quien deber&aacute; tomar todas las medidas de precauci&oacute;n necesarias de acuerdo al tipo de servicio, o contenido al
              que acceda.
              <br />
              El usuario que considere inadecuada una p&aacute;gina vinculada desde el Sitio, podr&aacute; elevar su queja o recomendaci&oacute;n a
              trav&eacute;s del mecanismo de denuncias puesto a disposici&oacute;n de los usuarios por parte de ' . $mbname . '.
            </p>
            <p>
              <br />
              <strong>13. Finalizaci&oacute;n del Servicio</strong>
              <br />
              ' . $mbname . ' podr&aacute; a su sola discreci&oacute;n suspender temporalmente o
              desactivar definitivamente la cuenta de un usuario, sin que medie previa notificaci&oacute;n al mismo, y sin que sea
              necesaria la invocaci&oacute;n de causa alguna, procedi&eacute;ndose en tal caso a la eliminaci&oacute;n de toda la informaci&oacute;n relacionada
              con la cuenta.
            </p>
            <p>
              <br />
              <strong>14. Legislaci&oacute;n aplicable y jurisdicci&oacute;n.</strong>
              <br />
              A todos los efectos legales en relaci&oacute;n a los servicios y contenidos brindados o que puedan brindarse en el Sitio,
              ser&aacute; aplicable la legislaci&oacute;n vigente en la Rep&uacute;blica de Chile, y ser&aacute; competente la justicia ordinaria con
              jurisdicci&oacute;n en la Regi&oacute;n Metropolitana de Santiago.
            </p>
            <br />
            <center>© ' . $mbname . ' 2010 - ' . date('Y') . '. Todos los derechos reservados. Prohibida su reproducci&oacute;n total o parcial.</center>
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