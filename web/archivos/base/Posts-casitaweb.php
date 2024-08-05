<?php
require_once(dirname(dirname(__FILE__)) . '/raizSeG414/Funciones.php');

function template_main() {
  global $tranfer1, $context, $user_settings, $options, $txt, $modSettings, $db_prefix, $boardurl;

  $cantidad = 1;
  $txt['comentarios'] = 'comentarios';
  $txt['mensajes'] = 'post';
  $post = (int) $context['id-post'];

  
  // var_dump($user_settings);
  // POSTS
  echo '
    <script type="text/javascript">
      function errorrojo2(causa) {
        if (causa == \'\') {
          document.getElementById(\'errors\').innerHTML = \'Es necesario especificar la causa de la eliminaci&oacute;n.\';
          return false;
        }
      }
    </script>
    <a name="arriba"></a>
    <div>';

  menuser($context['user_ID']);
  $message['body'] = hides($context['contenido']);

  echo '
    <div style="float: left; width: 774px;">
      <div class="box_780">
        <div class="box_title" style="width: 772px;">
          <div class="box_txt box_780-34">
            <center>' . $context['titulo'] . '</center>
          </div>
          <div class="box_rss">
            <img alt="" src="' . $tranfer1 . '/blank.gif" style="width: 16px; height: 16px;" border="0" />
          </div>
        </div>
        <div class="windowbg" style="width: 772px; overflow: hidden;" id="post_' . $context['id-post'] . '">
          <div class="post-contenido" property="dc:content">';

  if ($context['user']['is_guest']) {
    echo '
      <div align="center" style="-moz-border-radius: 5px; -webkit-border-radius: 5px; display: block; margin-bottom: 25px; margin-top: 10px; padding: 2px; border: solid 1px #D5CCC3; background: #FFF;">';

    anuncio_728x90();

    echo '
        <br />
        <a href="' . $boardurl . '/registrarse/" style="font-size: 12px; color: #FFB600; margin-bottom: 3px;"><b>REG&Iacute;STRATE GRATIS Y ELIMINA ESTA PUBLICIDAD, ADEM&Aacute;S TENDR&Aacute;S ACCESO A TODOS LOS POSTS Y FUNCIONES</b></a>
      </div>';
  }

  echo parse_bbc(censorText($message['body']));

  if ($context['user']['is_guest']) {
    echo '
      <div align="center" style="-moz-border-radius: 5px; -webkit-border-radius: 5px; display: block; margin-bottom: 10px; margin-top: 25px; padding: 2px; border: solid 1px #D5CCC3; background: #FFF;">
        <a href="' . $boardurl . '/registrarse/" style="font-size: 12px; color: #FFB600; margin-bottom: 3px;"><b>REG&Iacute;STRATE GRATIS Y ELIMINA ESTA PUBLICIDAD, ADEM&Aacute;S TENDR&Aacute;S ACCESO A TODOS LOS POSTS Y FUNCIONES</b></a>
        <br />';

    anuncio_728x90();

    echo '</div>';
  }

  echo '
      </div>
      <div id="social"></div>
    </div>';

  if ($context['anuncio'] == 1) {
    if ($context['user']['is_admin']) {
      echo '
        <form action="' . $boardurl . '/web/cw-postEliminar.php?id=' . $context['id-post'] . '" method="post" accept-charset="', $context['character_set'], '" name="causa" id="causa">
          <input class="login" style="font-size: 11px;" value="Editar post" title="Editar post" onclick="location.href=\'' . $boardurl . '/editar-post/id-' . $context['id-post'] . '\'" type="button" />
          <input class="login" style="font-size: 11px;" value="Eliminar post" title="Eliminar post" onclick="if (!confirm(\'\xbfEst&aacute;s seguro que deseas eliminar este post?\')) return false;' . ($context['id_cat'] != 142 && $context['user_ID'] != $context['user']['id'] ? ' return errorrojo2(this.form.causa.value);' : '') . '" type="submit" />
          ' . ($context['id_cat'] != 142 && $context['user_ID'] != $context['user']['id'] ? '<b>Causa:</b>&nbsp;<input type="text" onfocus="foco(this);" onblur="no_foco(this);" id="causa" name="causa" maxlength="70" style="width: 135px;" /><center><label id="errors" class="size10" style="color: red;"></label></center>' : '') . '
        </form>';
    }
  } else {
    echo '
      <!-- info del post -->
      <div style="width: 780px; margin-top: 8px;">
        <div style="width: 380px; float: left; margin-right: 8px; #margin-right: 8px; _margin-right: 6px;">
          <div class="box_390" style="width: 380px;">
            <div class="box_title" style="width: 378px;">
              <div class="box_txt box_390-34">Opciones</div>
              <div class="box_rss">
                <span id="cargando_opciones" style="display: none;">
                  <img alt="" src="' . $tranfer1 . '/icons/cargando.gif" style="width: 16px; height: 16px;" border="0" />
                </span>
                <span id="cargando_opciones2">
                  <img alt="" src="' . $tranfer1 . '/blank.gif" style="width: 16px; height: 16px;" border="0" />
                </span>
              </div>
            </div>
            <div class="windowbg" style="width: 370px; padding: 4px;">
              <form action="' . $boardurl . '/web/cw-postEliminar.php?id=' . $context['id-post'] . '" method="post" accept-charset="' . $context['character_set'] . '" style="margin: 0px; padding: 0px;" name="causa" id="causa">';

    if ($context['allow_admin']) {
      echo '
        <input class="login" style="font-size: 11px;" value="Editar post" title="Editar post" onclick="location.href=\'' . $boardurl . '/editar-post/id-' . $context['id-post'] . '\'" type="button" />&nbsp;<input class="login" style="font-size: 11px;" type="submit" value="Eliminar post" title="Eliminar post" onclick="if (!confirm(\'\xbfEst&aacute;s seguro que deseas eliminar este post?\')) return false; ' . ($context['id_cat'] != 142 && $context['user_ID'] != $context['user']['id'] ? ' return errorrojo2(this.form.causa.value);' : '') . '" />
        ' . ($context['id_cat'] != 142 && $context['user_ID'] != $context['user']['id'] ? '&nbsp;<b>Causa:</b>&nbsp;<input type="text" onfocus="foco(this);" onblur="no_foco(this);" id="causa" name="causa" maxlength="70" style="width: 135px;" /><center><label id="errors" class="size10" style="color: red;"></label></center>' : '') . '
        <div class="hrs"></div>';
    } else {
      if ($context['user_ID'] == $context['user']['id'] || $context['allow_admin']) {
        echo '
          <input class="login" style="font-size: 11px;" value="Editar post" title="Editar post" onclick="location.href=\'' . $boardurl . '/editar-post/id-' . $context['id-post'] . '\'" type="button" />
          <input class="login" style="font-size: 11px;" value="Eliminar post" title="Eliminar post" onclick="if (!confirm(\'\xbfEst&aacute;s seguro que deseas eliminar este post?\')) return false; location.href=\'' . $boardurl . '/web/cw-postEliminar.php?id=', $context['id-post'], '\'" type="button" />
          <div class="hrs"></div>';
      }
    }

    echo '</form>';

    if (!empty($context['leecher'])) {
      echo '
        <b class="size11">
          <center>Usuarios no registrados y <span title="Primer rango">turistas</span> no puede calificar.</center>
        </b>
        <div class="hrs"></div>';
    } else {
      if ($context['id_cat'] == 45 || $context['id_cat'] == 132) {
        echo '
          <center>
            <b class="size11">No se permite puntuar los post de esta categor&iacute;a.</b>
          </center>
          <div class="hrs"></div>';
      } else {
        if (empty($context['pdia'])) {
          $h = ' Faltan <u style="cursor: default;">' . faltan($user_settings['TiempoPuntos']) . '</u> para que se recargen tus puntos.';
          $f = '';
        } else {
          $h = '';
          $f = ' (<i>' . $context['pdia'] . ' puntos disponibles</i>)';
        }

        echo '
          <div id="span_opciones1" class="size10">
            <div style="margin-bottom: 2px;">
              <strong class="size11">Dar puntos</strong>' . $f . ':
            </div>';

        $pts = array();

        echo $h;

        for ($i = 1; $i <= $context['pdia']; ++$i) {
          echo '
            <div style="margin-left: 2px; margin-bottom: 5px; float: left;">
              <a href="#" onclick="votar_post(\'' . $context['id-post'] . "','" . $i . '\'); return false;" class="botN3" style="width: 20px; color: #fff; text-shadow: #444 0px 1px 0px;" title="Dar ' . $i . ' puntos">' . $i . '</a>
            </div>';
        }

        echo '
          </div>
          <div class="hrs"></div>';
      }
    }

    if ($context['user']['is_logged']) {
      echo '
        <center>
          <span id="span_opciones2" style="text-align: center; display: block;">
            <a class="Iagregar_favoritos png" href="#" onclick="add_favoritos(\'' . $context['id-post'] . '\'); return false;">Agregar a Favoritos</a>
            &#124;
            <a class="Idenunciar_post boxy png" title="Denunciar ' . $context['titulo'] . '" href="' . $boardurl . '/web/cw-denunciaTEMP.php?t=1;d=' . $context['id-post'] . '">Denunciar post</a>
            &#124;';
    }

    echo '<a class="Irecomendar_post png boxy" href="' . $boardurl . '/web/cw-TEMPenviarPost.php?id=' . $context['id-post'] . '" title="Recomendar ' . $context['titulo'] . '">Enviar a un amigo</a>';

    if ($context['user']['is_logged']) {
      echo '
          </span>
        </center>';
    }

    echo '
      <div class="hrs"></div>
      <b class="size13">Posts relacionados:</b>
      <br />';

    $dasdasd2 = db_query("
      SELECT id_post, palabra
      FROM {$db_prefix}tags
      WHERE id_post = '{$post}'
      ORDER BY palabra ASC", __FILE__, __LINE__);

    while ($row = mysqli_fetch_assoc($dasdasd2)) {
      $n[] = "palabra='" . str_replace("'", '', $row['palabra']) . "'";
      $ff = join(' OR ', $n);
    }

    mysqli_free_result($dasdasd2);

    $n = isset($n) ? $n : '';

    if ($n) {
      $select = db_query("
        SELECT id_post
        FROM {$db_prefix}tags
        WHERE $ff
        GROUP BY id_post
        LIMIT 10", __FILE__, __LINE__);

      while ($row24 = mysqli_fetch_assoc($select)) {
        $request = db_query("
          SELECT m.ID_TOPIC, m.subject, b.description
          FROM {$db_prefix}messages AS m
          INNER JOIN {$db_prefix}boards AS b ON m.ID_TOPIC = {$row24['id_post']}
          AND m.ID_TOPIC <> $post
          AND m.ID_BOARD = b.ID_BOARD
          AND m.eliminado = 0
          ORDER BY m.ID_TOPIC DESC
          LIMIT 1", __FILE__, __LINE__);

        $row = mysqli_fetch_assoc($request);
        $rows = mysqli_num_rows($request);
        $titulosssss = isset($row['subject']) ? censorText($row['subject']) : '';

        if ($rows > 0) {
          echo '
            <div class="postENTry">
              <a rel="dc:relation" href="' . $boardurl . '/post/' . $row['ID_TOPIC'] . '/' . $row['description'] . '/' . urls($titulosssss) . '.html" title="' . $titulosssss . '" target="_self" class="categoriaPost ' . $row['description'] . '">' . $titulosssss . '</a>
              <div style="clear: left;"></div>
            </div>';
        }

        mysqli_free_result($request);
      }

      if (!$titulosssss) {
        echo 'No hay posts relacionados.';
      }
    } else {
      echo 'No hay posts relacionados.';
    }

    echo '
          </div>
        </div>
      </div>
      <div style="width: 386px; float: left;">
        <div class="box_390">
          <div class="box_title" style="width: 384px;">
            <div class="box_txt box_390-34">Informaci&oacute;n del Post</div>
            <div class="box_rss">
              <img alt="" src="' . $tranfer1 . '/blank.gif" style="width: 16px; height: 16px;" border="0" />
            </div>
          </div>
          <div class="windowbg" style="width: 376px; padding: 4px;">
            <center>
              <span class="Ivisitas png">
                ' . $context['num_views'] . ' visitas
              </span>
              <span class="Ifavoritos png">
                <span id="cant_favs_post">' . $context['fav1'] . '</span>
                favoritos
              </span>
              <span class="Ipuntos png">
                <span id="cant_pts_post_dos">' . $context['puntos-post'] . '</span>
                puntos
              </span>
            </center>';

    if ($context['allow_admin']) {
      $request = db_query("
        SELECT p.id_post, p.id_member, p.fecha, p.cantidad, p.id, m.ID_MEMBER, m.realName
        FROM {$db_prefix}puntos AS p, {$db_prefix}members AS m
        WHERE p.id_post = $post
        AND p.cantidad <> 0
        AND p.fecha <> 0
        AND p.id_member = m.ID_MEMBER
        ORDER BY p.id DESC", __FILE__, __LINE__);

      while ($row = mysqli_fetch_assoc($request)) {
        if ($row['cantidad'] <= 0) {
          $asndbrbjweb = '';
        } else if ($row['cantidad'] == 1) {
          $asndbrbjweb = ' 1&nbsp;punto';
        } else if ($row['cantidad'] >= 2) {
          $asndbrbjweb = '' . $row['cantidad'] . '&nbsp;puntos';
        }

        $userdasd[] = '<a href="' . $boardurl . '/perfil/' . $row['realName'] . '" title="' . $asndbrbjweb . '">' . $row['realName'] . '</a>';
      }

      $skasdasdbsddd = mysqli_num_rows($request);

      mysqli_free_result($request);

      if (!empty($skasdasdbsddd)) {
        echo '
          <div class="hrs"></div>
          <b>Dieron puntos a este post:</b> ';

        echo join(', ', $userdasd);
      }
    }

    echo '
      <div class="hrs"></div>
      <b>Creado el:</b>
      <span property="dc:date" content="' . timeformat($context['fecha']) . '">' . timeformat($context['fecha']) . '</span>
      <div class="hrs"></div>
      <b>Categor&iacute;a:</b>
      <a href="' . $boardurl . '/categoria/' . $context['link_cat'] . '" title="' . $context['name_cat'] . '">' . $context['name_cat'] . '</a>
      <div class="hrs"></div>';

    $dasdasd = db_query("
      SELECT palabra, id_post
      FROM {$db_prefix}tags
      WHERE id_post = $post
      ORDER BY palabra ASC", __FILE__, __LINE__);

    $rows = mysqli_num_rows($dasdasd);

    echo '<b>Tags:</b>&nbsp;';

    if ($rows == 0) {
      echo 'Este post no cuenta con tags.';
    } else {
      while ($row = mysqli_fetch_assoc($dasdasd)) {
        if (trim($row['palabra']) != '') {
          $context['palabra'] = $row['palabra'];
          $palabra[] = '
            <a href="' . $boardurl . '/tags/' . $context['palabra'] . '" title="' . $context['palabra'] . '">' . $context['palabra'] . '</a>';
        }
      }

      mysqli_free_result($dasdasd);

      $palabra = isset($palabra) ? $palabra : '';

      if ($palabra != '') {
        echo join(' - ', $palabra);
      }
    }

    if ($context['user']['is_admin']) {
      echo '
        <div class="hrs"></div>
        <center>
          <strong>Moderaci&oacute;n</strong>
          <br />
          <form action="' . $boardurl . '/web/cw-cambio_cat-seg-684.php" method="post" accept-charset="' . $context['character_set'] . '" name="cat" id="cat">
            <table style="width: 100%">
              <tr style="margin: 10px">
                <td align="right">';

      categorias(2, $context['id_cat']);

      echo '
                </td>
                <td>
                  <input class="login" style="font-size: 10px;" value="Cambiar categor&iacute;a" title="Cambiar categor&iacute;a" name="tipo" type="submit" />
                </td>
              </tr>
              <tr>
                <td align="right">
                  <input size="10" value="" name="useradar" type="text" />
                </td>
                <td>
                  <input class="login" style="font-size: 10px;" value="Cambiar autor" title="Cambiar autor" name="tipo" type="submit" />
                  <input value="' . $context['id-post'] . '" name="id-seg-2451" type="hidden" />
                </td>
              </tr>
            </table>
          </form>
        </center>';
    }

    echo '
        </div>
      </div>';

    $requests = db_query("
      SELECT signature
      FROM {$db_prefix}members
      WHERE ID_MEMBER = {$context['user_ID']}", __FILE__, __LINE__);

    while ($grups = mysqli_fetch_assoc($requests)) {
      $context['firma'] = $grups['signature'];
    }
    mysqli_free_result($requests);
    $nwesdas = $context['firma'];
    if (!empty($nwesdas) && empty($options['show_no_signatures'])) {
      echo '
        <div class="box_390" style="margin-top: 8px; width: 386px;">
          <div class="box_title" style="width: 384px;">
            <div class="box_txt box_390-34">Firma</div>
            <div class="box_rss">
              <img alt="" src="' . $tranfer1 . '/blank.gif" style="width: 16px; height: 16px;" border="0" />
            </div>
          </div>
          <div class="windowbg" style="width: 384px; overflow: auto;">
            <div class="fimaFIX" style="padding: 4px;">
              <b class="size11">' . (str_replace('if(this.width >720) {this.width=720}', 'if(this.width > 375) {this.width=375}', str_replace('class="imagen"', 'class="imagen-firma"', parse_bbc($nwesdas)))) . '</b>
            </div>
          </div>
        </div>';
    }

    echo '
        </div>
      </div>
      <!-- fin info del post -->';
  }

  echo '
    <!-- comentarios -->
    <div style="clear: left;"></div>
    <div style="margin-bottom: 8px; margin-top: 8px; width: 774px;">';

  if ($context['numcom']) {
    echo '
      <div class="icon_img" style="float: left; margin-right: 5px;">
        <a href="' . $boardurl . '/rss/post-comment/' . $context['id-post'] . '">
          <img alt="" src="' . $tranfer1 . '/icons/cwbig-v1-iconos.gif?v3.2.3" style="cursor: pointer; margin-top: -352px; display: inline;" />
        </a>
      </div>';
  }

  echo '
    <b style="font-size: 14px;">
      Comentarios (<span id="nrocoment">' . $context['numcom'] . '</span>)
    </b>
    <div style="clear: both; margin-bottom: 3px;"></div>';

  if ($context['numcom']) {
    foreach ($context['comentarios'] as $coment) {
      echo '
        <div id="cmt_' . $coment['id'] . '" class="Coment">
          <span class="size12">
            <div class="User-Coment">
              <div style="float: left;">
                <b id="autor_cmnt_' . $coment['id'] . '" user_comment="' . $coment['nomuser'] . '" text_comment="' . $coment['comentario2'] . '">
                  <a href="' . $boardurl . '/perfil/' . $coment['nommem'] . '" style="color: #956100;">' . $coment['nomuser'] . '</a>
                </b>
                <span title="' . tiempo2($coment['fecha']) . '">' . hace($coment['fecha']) . '</span>
                dijo:
              </div>
              <div style="float: right;">';

      if ($context['user']['is_logged']) {
        echo '
          <a href="' . $boardurl . '/web/cw-TEMPenviarMP.php?user=' . $coment['nomuser'] . '" title="Enviar MP a ' . $coment['nomuser'] . '" class="boxy">
            <img alt="" src="' . $tranfer1 . '/icons/mensaje_para.gif" border="0" />
          </a>';
        
        if (!$context['is_locked']) {
          echo '
            &#32;
            <a onclick="citar_comment(' . $coment['id'] . ')" href="javascript:void(0)" title="Citar comentario">
              <img alt="" src="' . $tranfer1 . '/comunidades/respuesta.png" class="png" border="0" />
            </a>';
        }

        if ($context['user_ID'] == $context['user']['id'] || $context['allow_admin']) {
          echo '
            &#32;
            <a href="#" onclick="del_coment_post(' . $coment['id'] . ',' . $context['id-post'] . '); return false;" title="Eliminar comentario">
              <img alt="" src="' . $tranfer1 . '/comunidades/eliminar.png" class="png" style="width: 16px; height: 16px;" border="0" />
            </a>';
        }
      }

      echo '
              </div>
            </div>
            <div class="leccha"></div>
            <div class="cuerpo-Coment">
              <div class="coment-contenido">' . $coment['comentario'] . '</div>
            </div> 
          </span>
        </div>';
    }

    echo '<div id="no_comentarios" class="noesta" style="width: 774px; display: none;">Este post no tiene comentarios.</div>';
  } else {
    echo '<div id="no_comentarios" class="noesta" style="width: 774px;">Este post no tiene comentarios.</div>';
  }

  echo '<div id="return_agregar_comentario" style="display: none;"></div>';

  if ($context['is_locked'] && $context['user']['id']) {
    echo '<div id="post_cerrado" class="noesta-am" style="width: 774px; margin-top: 5px;">Este post est&aacute; cerrado, por lo tanto no se permiten nuevos comentarios.</div>';
  }

  echo '
      <div class="errorDelCom" style="display: hide; width: 774px;"></div>
    </div>
    <!-- fin comentarios -->';

  $request = db_query("
    SELECT id_user
    FROM {$db_prefix}pm_admitir
    WHERE id_user = {$context['user_ID']}
    AND quien = {$context['user']['id']}
    LIMIT 1", __FILE__, __LINE__);

  $ignorado = mysqli_num_rows($request);

  if ($context['user']['id'] && !$context['is_locked']) {
    if (!$ignorado) {
      echo '
        <!-- comentar -->
        <div style="clear: left;"></div>
        <div style="margin-bottom: 3px;" id="comentar" name="comentar">
          <b style="font-size: 14px;">Agregar un nuevo comentario</b>
        </div>
        <div style="width: 774px;">
          <form name="nuevocoment">
            <center>
              <div class="msg_add_comment"></div>
            </center>
            <div style="clear: left; margin-bottom: 2px"></div>';

      theme_quickreply_box();

      echo '
              <br />
              <input class="login" type="button" id="button_add_comment" value="Enviar comentario" onclick="add_comment(\'' . $context['id-post'] . "', '" . ($context['numcom'] + 1) . '\'); return false;" tabindex="2" />
              <div style="display: none; text-align: right;" id="gif_cargando_add_comment">
                <img alt="" src="' . $tranfer1 . '/icons/cargando.gif" alt="" />
              </div>
            </p>
            <div style="clear: left;"></div>
          </form>
        </div>
        <div style="clear: left;"></div>
        <!-- fin comentar -->';
    }
  }

  if (!$context['user']['id']) {
    echo '
      <div style="clear: left;"></div>
      <div class="noesta-am" style="width: 774px; margin-top: 5px;">
        Para poder comentar necesitas estar <a href="' . $boardurl . '/registrarse/" style="color: #FFB600;" title="Registrarse">REGISTRADO</a>. Si ya tienes usuario <a href="javascript:irAconectarse();" style="color:#FFB600;" title="Conectarse">&iexcl;CON&Eacute;CTATE!</a>
      </div>';
  }

  echo '
        </div>
      </div>
    </div>';

  if (!$context['user']['id']) {
    echo '
      <p align="right" style="font-text: 9px;">
        <a href="' . $boardurl . '/noestilo/post/' . $context['id-post'] . '">Sin estilo</a>
      </p>';
  }
}

function template_quickreply_box() {
  global $tranfer1, $context, $settings, $options, $txt, $modSettings;

  echo '
    <textarea id="editorCW" style="resize: none; height: 70px; width: 768px;" name="cuerpo_comment" tabindex="1"></textarea>
    <p align="right" style="margin: 0px; padding: 0px;">';

  if (!empty($context['smileys']['postform'])) {
    foreach ($context['smileys']['postform'] as $smiley_row) {
      foreach ($smiley_row['smileys'] as $smiley) {
        echo '<a href="javascript:void(0);" onclick="replaceText(\' ' . $smiley['code'] . '\', document.forms.nuevocoment.cuerpo_comment); return false;"><img src="' . $tranfer1 . '/emoticones/' . $smiley['filename'] . '" align="bottom" alt="' . $smiley['description'] . '" class="png" title="' . $smiley['description'] . '" /></a> ';
      }

      if (empty($smiley_row['last'])) {
        echo '<br />';
      }
    }

    if (!empty($context['smileys']['popup'])) {
      echo '<a href="javascript:moticonup()">[' . $txt['more_smileys'] . ']</a>';
    }
  }
}

?>