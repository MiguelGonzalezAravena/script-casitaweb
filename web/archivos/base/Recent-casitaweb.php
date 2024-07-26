<?php
function template_main() {
  global $tranfer1, $context, $boarddir, $db_prefix, $modSettings, $scripturl, $ID_MEMBER, $boardurl, $mbname;

  echo '
    <div style="text-align:left;">
      <div style="float: left; height: auto; margin-right: 8px;">
        <div class="ultimos_postsa" style="margin-bottom: 4px;">
          <div class="box_title" style="width: 378px;">
            <div class="box_txt ultimos_posts">&Uacute;ltimos posts</div>
            <div class="box_rss">
              <a href="' . $boardurl . '/rss/ultimos-post/">
                <div style="height: 16px; width: 16px; cursor: pointer;" class="feed png">
                  <img alt="" src="' . $tranfer1 . '/espacio.gif" class="png" height="16px" width="16px" />
                </div>
              </a>
            </div>
          </div>
          <div class="windowbg" style="width: 370px; padding: 4px;">
            <!-- empiezan los post -->';

  foreach ($context['sticky'] as $sticky) {
    $pag = isset($_GET['pag']) ? (int) $_GET['pag'] : 0;

    if (empty($pag) || $pag == 1) {
      echo '
        <div class="postENTrysticky" style="background-color: ' . (empty($sticky['color']) || $sticky['color'] == '0' || $sticky['color'] == '#000000' ? '#FFFFCC' : $sticky['color']) . ';">
          <a href="' . $boardurl . '/post/' . $sticky['id'] . '/' . $sticky['description'] . '/' . urls($sticky['titulo']) . '.html" target="_self" title="' . $sticky['titulo'] . '" class="categoriaPost ' . $sticky['description'] . '">' . achicars($sticky['titulo']) . '</a>
        </div>';
    }
  }

  if ($context['PagAct'] > $context['PagUlt']) {
    echo '
      <div class="noesta">
        <br /><br /><br /><br />
        Esta p&aacute;gina no existe.
        <br /><br /><br /><br /><br />
      </div>';
  } else {
    foreach ($context['posts'] as $posts) {
      echo '
        <div class="postENTry">
          <a href="' . $boardurl . '/post/' . $posts['id'] . '/' . $posts['description'] . '/' . urls($posts['titulo']) . '.html" target="_self" title="' . $posts['titulo'] . '"  class="categoriaPost ' . $posts['description'] . '">' . achicars($posts['titulo']) . '</a>
        </div>';
    }
  }
  
  echo '
      <div class="clearBoth"></div>
    </div>';
      
  if ($context['PagAct'] > $context['PagUlt']) {

  } else {
    if ($context['PagAct'] > 1 || $context['PagAct'] < $context['PagUlt']) {
      echo '<div class="windowbgpag" style="width: 378px;">';
  
      if (empty($context['catccdd'])) {
        if ($context['PagAct'] > 1) {
          echo '<a href="' . $boardurl . '/pag-' . $context['PagAnt'] . '">&#171; anterior</a>';
        }

        if ($context['PagAct'] < $context['PagUlt']) {
          echo '<a href="' . $boardurl . '/pag-' . $context['PagSig'] . '">siguiente &#187;</a>';
        }
      } else {
        if ($context['PagAct'] > 1) {
          echo '<a href="' . $boardurl . '/categoria/' . $context['catccdd'] . '/pag-' . $context['PagAnt'] . '">&#171; anterior</a>';
        }

        if ($context['PagAct'] < $context['PagUlt']) {
          echo '<a href="' . $boardurl . '/categoria/' . $context['catccdd'] . '/pag-' . $context['PagSig'] . '">siguiente &#187;</a>';
        }
      }
      
      echo '
          <div class="clearBoth"></div>
        </div>';
    }
  }

  echo '
      </div>
      <div class="clearBoth"></div>
    </div>
    <div style="float: left; margin: 0px; padding: 0px; height: 90px; margin-bottom: 8px;" align="center">
      <a href="' . $boardurl . '/chat/" target="_blank">
        <img alt="" src="' . $tranfer1 . '/sala-chat.gif" width="534px" height="90px" />
      </a>
    </div>
    <div style="float: left; margin-right: 8px;">
      <div style="margin-bottom: 8px; width: 363px;">
        <ul class="buscadorPlus">
          <li id="gb" class="activo" onclick="elegir(\'google\')">Google</li>
          <li id="cwb" onclick="elegir(\'casitaweb\')">' . $mbname . '</li>
          <div class="clearBoth"></div>
        </ul>
        <div class="clearBoth"></div>
        <div style="margin-top: -1px;clear:both;">
          <form style="margin: 0px; padding: 0px;" action="' . $boardurl . '/buscar.php" method="get" accept-charset="' . $context['character_set'] . '">
            <input type="text" name="q" id="q" class="ibuscador" style="height: 32px;" />
            <input onclick="return errorrojos(this.form.q.value);" alt="" class="bbuscador png" title="Buscar" value=" " type="submit" align="top" style="height: 34px;" />
            <input name="buscador_tipo" value="g" checked="checked" type="hidden" />
          </form>
        </div>
      </div>';

  if (!empty($modSettings['radio'])) {
    if ($modSettings['radio'] == 1) {
      echo '
        <div class="act_comments">
          <div class="box_title" style="width: 361px;">
            <div class="box_txt ultimos_comments">' . $mbname . ' - Radio</div>
            <div class="box_rss">
              <img alt="" src="' . $tranfer1 . '/blank.gif" style="width: 16px; height: 16px;" border="0" />
            </div>
          </div>
          <div class="windowbg" style="padding: 4px; width: 353px; margin-bottom: 8px;">
            <center>
              <div class="stream">
                <script type="text/javascript">
                  window.onload = radio;

                  function radio() {
                    if (document.getElementById(\'cc_stream_info_song\').innerHTML == \'\') {
                      document.getElementById(\'enlinea\').className = \'error\';
                      document.getElementById(\'enlinea\').innerHTML = \'Fuera de l&iacute;nea\';
                      document.getElementById(\'imgmic\').style.display = \'inline\';
                      document.getElementById(\'imgcar\').style.display = \'none\';
                    } else {
                      document.getElementById(\'enlinea\').className = \'ok\';
                      document.getElementById(\'enlinea\').innerHTML = \'En l&iacute;nea\';
                      document.getElementById(\'imgmic\').style.display = \'inline\';
                      document.getElementById(\'imgcar\').style.display = \'none\';
                      document.getElementById(\'escuchando\').style.display = \'inline\';
                    }
                  }
                </script>
                <span id="escuchando" style="display: none;">
                  <img src="' . $tranfer1 . '/icons/microfono.png" alt="" />
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
                  <param name="FlashVars"
                    value="mp3=http%3A//77.92.68.221%3A15393/%3B&amp;showvolume=1&amp;width=266&amp;showloading=always&amp;bgcolor1=CDC3B8&amp;bgcolor2=CDC3B8&amp;slidercolor1=FFC703&amp;slidercolor2=FFC703" />
                </object>
              </div>
              <img alt="" src="' . $tranfer1 . '/icons/radio-cw.gif" />
              <b class="size11">Ir a <a target="_blank" href="http://fmcasita.net">FMcasita.net</a> - Radio oficial de ' . $mbname . '</b>
            </center>
          </div>
        </div>';
    } else if ($modSettings['radio'] == 2) {
      echo '
        <div class="act_comments">
          <div class="box_title" style="width: 361px;">
            <div class="box_txt ultimos_comments">Radio / Perdidos en babylon</div>
            <div class="box_rss">
              <img alt="" src="' . $tranfer1 . '/blank.gif" style="width: 16px; height: 16px;" border="0" />
            </div>
          </div>
          <div class="windowbg" style="padding: 4px; width: 353px; margin-bottom: 8px;">
            <center>
              <embed type="application/x-mplayer2"
                pluginspace="http://www.microsoft.com/isapi/redir.dll?prd=windows&amp;sbp=mediaplayer&amp;ar=Media&amp;sba=Plugin&amp;"
                wmode="transparent" filename="mms://201.212.0.128/horaprima" name="WMPlay" autostart="0" showcontrols="1"
                showdisplay="0" showstatusbar="0" autosize="0" displaysize="0" width="280" height="45"
              />
              <br />
              <img alt="" src="' . $tranfer1 . '/icons/radio-cw.gif" />
              <b class="size11">
                Ir a <a target="_blank" href="http://perdidosenbabylon.com">Perdidos en babylon!</a>
                -
                Web oficial
                <br />
                <img alt="" src="http://fmcasita.net/utilidades/2.png" />
                <a href="mms://201.212.0.128/horaprima">Escuchar en Windows Media Player</a>
              </b>
            </center>
          </div>
        </div>';
    }
  }
  
  echo '
    <div class="act_comments">
      <div class="box_title" style="width: 361px;">
        <div class="box_txt ultimos_comments">&Uacute;ltimos comentarios</div>
        <div class="box_rss">
          <div style="height: 16px; width: 16px; cursor: pointer;" class="actualizarComents png">
            <img alt="Actualizar" onclick="actualizar_comentarios(); return false;" src="' . $tranfer1 . '/espacio.gif" class="png" height="16px" width="16px" />
          </div>
        </div>
      </div>
      <div class="windowbg" style="width: 353px; padding: 4px; margin-bottom: 8px;">
        <span id="ult_comm">
          ' . mensajes() . '
        </span>
      </div>
    </div>
    <div class="act_comments">
      <div class="box_title" style="width: 361px;">
        <div class="box_txt ultimos_comments">TOPs posts de la semana</div>
        <div class="box_rss">
          <img alt="" src="' . $tranfer1 . '/blank.gif" style="width: 16px; height: 16px;" border="0" />
        </div>
      </div>
      <div class="windowbg" style="width: 353px; padding: 4px; margin-bottom: 8px; font-size: 11px;">';

  $conanto = 1;

  foreach ($context['post_semana'] as $row) {
    echo '
      <b>' . $conanto . '.</b>
      <a href="' . $boardurl . '/post/' . $row['ID_TOPIC'] . '/' . $row['description'] . '/' . urls($row['subject']) . '.html"
        title="' . $row['subject'] . '">' . achicars($row['subject']) . '</a>
      (<span title="' . $row['num_posts'] . ' pts">' . $row['num_posts'] . ' pts</span>)
      <br />';

    ++$conanto;
  }

  echo '
      </div>
    </div>
    <div class="act_comments">
      <div class="box_title" style="width: 361px;">
        <div class="box_txt ultimos_comments">
          TOPs Tags
          <span style="font-size: 9px;">
            (
              <a href="' . $boardurl . '/tags/" title="Nube de Tags">Nube de Tags</a>
            )
          </span>
        </div>
        <div class="box_rss">
          <img alt="" src="' . $tranfer1 . '/blank.gif" style="width: 16px; height: 16px;" border="0" />
        </div>
      </div>
      <div class="windowbg" style="width: 353px; padding: 4px; margin-bottom: 8px; font-size: 11px;">
        <center>';

  $fontmax = 20;
  $fontmin = 10;
  $tagmax = 50;

  if ($tagmax <= 0) {
    $tagmax = 10;
  }

  $result3 = db_query("
    SELECT cantidad
    FROM {$db_prefix}tags
    WHERE rango = 1
    ORDER BY cantidad DESC
    LIMIT 29, 1", __FILE__, __LINE__);

  while ($row = mysqli_fetch_array($result3)) {
    $cantidad = $row['cantidad'];
  }

  $result = db_query("
    SELECT palabra AS tag, count(palabra) AS quantity, cantidad
    FROM {$db_prefix}tags
    WHERE cantidad >= $cantidad
    AND rango = 1
    GROUP BY palabra
    ORDER BY palabra DESC
    LIMIT 0, $tagmax", __FILE__, __LINE__);

  while ($row = mysqli_fetch_array($result)) {
    $tags[$row['tag']] = $row['cantidad'];
  }

  $max_qty = max(array_values($tags));
  $universo = array_sum(array_values($tags));
  $elemento_menor = min(array_values($tags));
  $hoja = max(array_values($tags)) - $elemento_menor;

  if ($hoja <= 0) {
    $hoja = 1;
  }

  $letra_hoja = $fontmax - $fontmin;

  if ($letra_hoja <= 0) {
    $letra_hoja = 1;
  }

  $font_step = $letra_hoja / $hoja;
  $asdas = 1;

  foreach ($tags as $key => $value) {
    $porcentaje = 0;
    $porcentaje = ($value / $universo) * 100;
    $tamanio = (int) ($fontmin + (($value - $elemento_menor) * $font_step));
    $asfff = ++$asdas;
    $paltag = strtolower(str_replace('%', '', $key));

    echo '<a href="' . $boardurl . '/tags/' . $paltag . '" style="font-size: ' . $tamanio . 'pt; margin-right: 2px; margin-bottom: 2px;" title="' . $value . ' post con el tag ' . $paltag . '">' . $paltag . '</a>&nbsp;';

    // Contador es múltiplo de 5
    if ($asfff % 5 == 0) {
      echo '<br />';
    }
  }

  echo '
          </center>
        </div>
      </div>
      <div class="act_comments">
        <div class="box_title" style="width: 361px;">
          <div class="box_txt ultimos_comments">Destacados</div>
          <div class="box_rss">
            <img alt="" src="' . $tranfer1 . '/blank.gif" style="width: 16px; height: 16px;" border="0" />
          </div>
        </div>
        <div align="center" class="windowbg" style="width: 353px; padding: 4px; margin-bottom: 8px;">
          ' . destacado() . '
        </div>
      </div>
    </div>
    <div style="float: left; margin-right: 0px;">';

  require_once($boarddir . '/web/cw-AmistadesAct.php');

  echo '
      <div class="img_aletat">
        <div class="box_title" style="width: 161px;">
          <div class="box_txt img_aletat">&Uacute;ltimas im&aacute;genes</div>
          <div class="box_rss">
            <img alt="" src="' . $tranfer1 . '/blank.gif" style="width: 16px; height: 16px;" border="0" />
          </div>
        </div>
        <div class="windowbg" style="padding: 4px; width: 153px; margin-bottom: 8px; font-size: 11px;">';

  foreach ($context['ultimas_img'] as $ui) {
    $titulo = $ui['titulo'];
    $titulo3 = strlen($titulo) > 24 ? substr($titulo, 0, strrpos(substr($titulo, 0, 21), ' ')) . '...' : $titulo;

    echo '
      <div class="postENTry" style="background-color: #FFFFCC;">
        <a href="' . $boardurl . '/imagenes/ver/' . $ui['id'] . '" title="' . $titulo . '" class="categoriaPost imagenesNOCAT" target="_self">
          ' . $titulo3 . '
        </a>
      </div>';
  }

  if (!empty($context['user']['id'])) {
    echo '
      <div class="hrs"></div>
      <center>
        <a href="' . $boardurl . '/web/cw-TEMPAgregarIMG.php" class="boxy" title="Agrega tu imagen">Agrega tu imagen</a>
      </center>';
  }

  echo '
      </div>
    </div>';

  $dasda = 1;

  echo '
    <div class="img_aletat">
      <div class="box_title" style="width: 161px;">
        <div class="box_txt img_aletat">User de la semana ' . count($context['top_posters_week']) . '</div>
        <div class="box_rss">
          <img alt="" src="' . $tranfer1 . '/blank.gif" style="width: 16px; height: 16px;" border="0" />
        </div>
      </div>
      <div class="windowbg" style="padding: 4px; width: 153px; margin-bottom: 8px;">';

  $rows = count($context['top_posters_week']);

  if ($rows == 0) {
    echo '<div class="noesta">Nada por ac&aacute;...</div>';
  }

  foreach ($context['top_posters_week'] as $row) {
    echo '
      <font style="font-size: 11px">
        <b>' . $dasda++ . ' - </b>
        ' . $row['link'] . '
        (' . $row['num_posts'] . ')
      </font><br />';
  }

  echo '
      </div>
    </div>';

  $dasda4 = 1;

  echo '
    <div class="img_aletat">
      <div class="box_title" style="width: 161px;">
        <div class="box_txt img_aletat">User con m&aacute;s post</div>
        <div class="box_rss">
          <img alt="" src="' . $tranfer1 . '/blank.gif" style="width: 16px; height: 16px;" border="0" />
        </div>
      </div>
      <div class="windowbg" style="padding: 4px; width: 153px; margin-bottom: 8px;">';

  foreach ($context['top_starters'] as $poster) {
    echo '
      <font style="font-size: 11px">
        <b>' . $dasda4++ . ' - </b>
        <a href="' . $boardurl . '/perfil/' . $poster['realName'] . '" title="' . $poster['realName'] . '">' . $poster['realName'] . '</a>
        (' . $poster['cuenta'] . ')
      </font><br />';
  }

  echo '
      </div>
    </div>
    <div class="img_aletat">
      <div class="box_title" style="width: 161px;">
        <div class="box_txt img_aletat">Enlaces</div>
        <div class="box_rss">
          <img alt="" src="' . $tranfer1 . '/blank.gif" style="width: 16px; height: 16px;" border="0" />
        </div>
      </div>
      <div class="windowbg" style="padding: 4px; width: 153px; margin-bottom: 8px;">
        ' . enlaces() . '
      </div>
    </div>';

  if ($context['user']['name'] == 'rigo') {
    $request = db_query("
      SELECT ID_MEMBER
      FROM {$db_prefix}log_online
      WHERE ID_MEMBER = 0", __FILE__, __LINE__);

    $context['invitados'] = mysqli_num_rows($request);

    $request = db_query("
      SELECT ID_MEMBER
      FROM {$db_prefix}log_online
      WHERE ID_MEMBER <> 0", __FILE__, __LINE__);

    $context['usuarios'] = mysqli_num_rows($request);

    echo '
      <div class="img_aletat">
        <div class="box_title" style="width: 161px;">
          <div class="box_txt img_aletat">Estad&iacute;sticas</div>
          <div class="box_rss">
            <img alt="" src="' . $tranfer1 . '/blank.gif" style="width: 16px; height: 16px;" border="0" />
          </div>
        </div>
        <div class="windowbg" style="padding: 4px; font-size: 11px; width: 153px; margin-bottom: 8px;">
        <b style="color: green;">' . ($context['invitados'] + $context['usuarios']) . ' personas conectadas</b>
        <br />
        ' . $context['invitados'] . ' invitados conectados
        <br />
        <a href="' . $boardurl . '/conectados/" title="' . $context['usuarios'] . ' registrados conectados">' . $context['usuarios'] . ' registrados conectados</a>
        <br />';

    $request = db_query("
      SELECT id_coment
      FROM {$db_prefix}comentarios", __FILE__, __LINE__);

    $context['cantidadcoment'] = mysqli_num_rows($request);
    
    echo $context['cantidadcoment'] . ' comentarios
        </div>
    </div>';
  }

  echo '
      </div>
      <div style="clear: left;"></div>
    </div>';
}

function mensajes() {
  global $context, $db_prefix, $modSettings, $boardurl;

  if (!$context['user']['is_admin']) {
    $shas = ' AND m.ID_BOARD <> 142';
  } else {
    $shas = '';
  }

  // TO-DO: Verificar dónde se setea esto
  $limit = $modSettings['catcoment'];
  $limit = 25;
  $request = db_query("
    SELECT c.id_post, m.ID_TOPIC, c.id_user, mem.ID_MEMBER, m.ID_BOARD, b.ID_BOARD, c.id_coment, m.subject, b.description, memberName, realName
    FROM {$db_prefix}comentarios AS c
    INNER JOIN {$db_prefix}messages AS m ON c.id_post = m.ID_TOPIC
    INNER JOIN {$db_prefix}members AS mem ON c.id_user = mem.ID_MEMBER
    INNER JOIN {$db_prefix}boards as b ON m.ID_BOARD = b.ID_BOARD
    $shas
    ORDER BY c.id_coment DESC
    LIMIT $limit", __FILE__, __LINE__);

  $context['comentarios25'] = array();

  while ($row = mysqli_fetch_assoc($request)) {
    $context['comentarios25'][] = array(
      'id_coment' => $row['id_coment'],
      'titulo' => censorText($row['subject']),
      'ID_TOPIC' => $row['ID_TOPIC'],
      'description' => $row['description'],
      'memberName' => $row['memberName'],
      'realName' => $row['realName'],
    );
  }

  mysqli_free_result($request);

  $comments = '';

  foreach ($context['comentarios25'] as $row) {
    $comments .= '
      <font class="size11">
        <b>
          <a title="' . $row['realName'] . '" href="' . $boardurl . '/perfil/' . $row['realName'] . '">' . $row['realName'] . '</a>
        </b>
        -
        <a title="' . $row['titulo'] . '" href="' . $boardurl . '/post/' . $row['ID_TOPIC'] . '/' . $row['description'] . '/' . urls($row['titulo']) . '.html#cmt_' . $row['id_coment'] . '">' . achicars($row['titulo']) . '</a>
      </font>
      <br style="margin: 0px; padding: 0px;" />';
  }

  return $comments;
}

?>