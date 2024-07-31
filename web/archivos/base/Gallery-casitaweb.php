<?php
function template_main() {
  global $tranfer1, $db_prefix, $scripturl, $txt, $context, $ID_MEMBER, $modSettings, $boarddir, $boardurl;

  $g_add = allowedTo('smfgallery_add');
  $g_manage = allowedTo('smfgallery_manage');
  $g_edit_own = allowedTo('smfgallery_edit');
  $g_delete_own = allowedTo('smfgallery_delete');
  $maxrowlevel = 4;
  $rowlevel = 0;
  $userid = $context['gallery_userid'];

  echo '
    <style type="text/css">
      .photo_small {
        width: 90px;
        margin: 6px;
        padding: 2px;
        text-align: left;
        background: #FFFFFF none repeat scroll 0%;
        border: 1px solid #000000;
      }
    </style>';

  if ($context['user']['id'] == $userid) {
    ditaruser();

    echo '
      <div style="float: left; width: 776px;">
        <div class="box_780">
          <div class="box_title" style="width: 774px;">
            <div class="box_txt box_780-34">
              <center>Mis im&aacute;genes</center>
            </div>
            <div class="box_rss">
              <img alt="" src="' . $tranfer1 . '/blank.gif" style="width: 16px; height: 16px;" border="0" />
            </div>
          </div>
          <div class="windowbg" style="width: 766px; padding: 4px;">';
  } else {
    echo '
      <div style="float: left; width: 922px;">
        <div class="box_buscador">
          <div class="box_title" style="width: 920px;">
            <div class="box_txt box_buscadort">
              <center>Im&aacute;genes de ' . $context['gallery_usergallery_name'] . '</center>
            </div>
            <div class="box_rss">
              <img alt="" src="' . $tranfer1 . '/blank.gif" style="width: 14px; height: 12px;" border="0" />
            </div>
          </div>
          <div style="width: 912px; padding: 4px;" class="windowbg">';
  }

  echo '<table border="0" width="100%">';

  $RegistrosAMostrar = 9;
  $pag = isset($_GET['pag-seg-154s87135']) ? (int) $_GET['pag-seg-154s87135'] : 0;
  $dev = $pag < 1 ? 1 : $pag;

  if (isset($dev)) {
    $RegistrosAEmpezar = ($dev - 1) * $RegistrosAMostrar;
    $PagAct = $dev;
  } else {
    $RegistrosAEmpezar = 0;
    $PagAct = 1;
  }

  // TO-DO: Verificar si esto tiene algún valor o no
  // if ($ID_MEMBER == $userid) {
    $dbresult = db_query("
      SELECT p.title, p.filename, p.ID_PICTURE
      FROM {$db_prefix}gallery_pic AS p, {$db_prefix}members AS m 
      WHERE p.ID_MEMBER = m.ID_MEMBER
      AND p.ID_MEMBER = $userid
      ORDER BY p.ID_PICTURE DESC
      LIMIT $RegistrosAEmpezar, $RegistrosAMostrar", __FILE__, __LINE__);
  // }

  while ($row = mysqli_fetch_assoc($dbresult)) {
    if ($rowlevel < ($maxrowlevel + 1)) {
      $rowlevel++;
    } else {
      echo '<tr>';
      $rowlevel = 0;
    }

    $row['title'] = str_replace('"', '&#34;', $row['title']);
    $row['title'] = str_replace("'", '&#39;', $row['title']);
    $row['title'] = str_replace('<', '&#60;', $row['title']);
    $row['title'] = str_replace('>', '&#62;', $row['title']);

    $request = db_query("
      SELECT ID_PICTURE
      FROM {$db_prefix}gallery_comment
      WHERE ID_PICTURE = {$row['ID_PICTURE']}", __FILE__, __LINE__);

    $context['dato'] = mysqli_num_rows($request);

    mysqli_free_result($request);

    echo '
      <td width="70px">
        <div style="width: 90px;">
          <div class="photo_small">
            <a href="' . $boardurl . '/imagenes/ver/' . $row['ID_PICTURE'] . '">
              <img src="' . $row['filename'] . '" title="' . $row['title'] . '" onload="if (this.height > 68) { this.height = 68 }" style="width: 90px;" border="0" />
            </a>
          </div>
          <div class="smalltext">
            <center>
              Comentarios: (<a href="' . $boardurl . '/imagenes/ver/' . $row['ID_PICTURE'] . '#comentarios">' . $context['dato'] . '</a>)
            </center>
          </div>
        </div>
      </td>';

    if ($rowlevel < ($maxrowlevel + 1)) {
      $rowlevel++;
    } else {
      echo '</tr>';
      $rowlevel = 0;
    }
  }

  mysqli_free_result($dbresult);

  echo '</table>';

  $request = db_query("
    SELECT p.ID_MEMBER, m.ID_MEMBER
    FROM {$db_prefix}gallery_pic AS p, {$db_prefix}members AS m 
    WHERE p.ID_MEMBER = m.ID_MEMBER
    AND p.ID_MEMBER = $userid", __FILE__, __LINE__);

  $NroRegistros = mysqli_num_rows($request);

  $PagAnt = $PagAct - 1;
  $PagSig = $PagAct + 1;
  $PagUlt = $NroRegistros / $RegistrosAMostrar;
  $Res = $NroRegistros % $RegistrosAMostrar;

  if ($Res > 0) {
    $PagUlt = floor($PagUlt) + 1;
  }

  if (empty($NroRegistros)) {
    // ¿Se hace algo aquí?
  } else if ($PagAct > $PagUlt) {
    echo '<div class="noesta">Esta p&aacute;gina no existe.</div>';
  }

  if ($context['user']['name'] == $context['gallery_usergallery_name']) {
    // Avisar de que no hay imagen en galería
    if (!$NroRegistros) {
      echo '<div class="noesta">No tienes ninguna imagen - <a href="' . $boardurl . '/imagenes/agregar/" style="color: red; text-decorative: none;">AGREGAR AQU&Iacute;</a></div>';
    }
  } else if (!$NroRegistros) {
    echo '<div class="noesta">' . $context['gallery_usergallery_name'] . ' no tiene ninguna imagen.</div>';
  }

  echo '</div>';

  if ($PagAct > $PagUlt) {
    // ¿Acá se hace algo?
  } else if ($PagAct > 1 || $PagAct < $PagUlt) {
    echo '<div class="windowbgpag" style="width: ' . ($context['user']['name'] == $context['gallery_usergallery_name'] ? '780' : '921') . 'px;">';

    if ($PagAct > 1) {
      echo '<a href="' . $boardurl . '/imagenes/' . $context['gallery_usergallery_name'] . '/pag-' . $PagAnt . '">&#171; anterior</a>';
    }

    if ($PagAct < $PagUlt) {
      echo '<a href="' . $boardurl . '/imagenes/' . $context['gallery_usergallery_name'] . '/pag-' . $PagSig . '">siguiente &#187;</a>';
    }

    echo '
      </div>
      <div class="clearBoth"></div>
      <div style="clear: both;"></div>';
  }

  echo '
      </div>
    </div>';
}

function template_view_picture() {
  global $tranfer1, $context, $db_prefix, $user_settings, $options, $ID_MEMBER, $modSettings, $ie, $boardurl;

  $request = db_query("
    SELECT id_post
    FROM {$db_prefix}denuncias
    WHERE id_post = {$context['gallery_pic']['ID_PICTURE']}
    AND tipo = 1
    AND borrado <> 1", __FILE__, __LINE__);

  $context['contando'] = mysqli_num_rows($request);

  if ($context['contando'] >= 3 && empty($context['allow_admin'])) {
    fatal_error('Imagen eliminada por acumulaci&oacute;n de denuncias, se encuentra en proceso de revisi&oacute;n.', false);
  }

  if ($context['contando'] >= 3 && $context['allow_admin']) {
    echo '
      <p align="center" style="color: red;">
        <b class="size12">Verificar imagen - Tiene ' . $context['contando'] . ' denuncias</b>
      </p>';
  }

  $request = db_query("
    SELECT ID_PICTURE
    FROM {$db_prefix}gallery_comment
    WHERE ID_PICTURE = {$context['gallery_pic']['ID_PICTURE']}", __FILE__, __LINE__);

  $context['sin_coment'] = mysqli_num_rows($request);

  $request = db_query("
    SELECT ID_TOPIC, tipo
    FROM {$db_prefix}bookmarks
    WHERE ID_TOPIC = {$context['gallery_pic']['ID_PICTURE']}
    AND tipo = 1", __FILE__, __LINE__);

  $context['fav2'] = mysqli_num_rows($request);

  $cantidad = 1;

  echo "
    <script type=\"text/javascript\">
      function errorrojo2(causa){ if(causa == ''){ document.getElementById('errors').innerHTML='<font class=\"size10\" style=\"color: red;\">Es necesaria la causa de la eliminaci&oacute;n.</font>'; return false;}}
      function errorrojo(cuerpo_comment){ if(cuerpo_comment == ''){ document.getElementById('error').innerHTML='<br /><font class=\"size10\" style=\"color: red;\">No has escrito ning&uacute;n comentario.</font>'; return false;}}
    </script>";

  echo '
      <a name="inicio"></a>
    <div>';

  menuser($context['gallery_pic']['ID_MEMBER']);

  echo '
    <div style="float: left; width: 774px;">
      <div>
        <div class="box_title" style="width: 772px;">
          <div class="box_txt box_780-34">
            <center>' . $context['gallery_pic']['title'] . '</center>
          </div>
          <div class="box_rss">
            <div class="icon_img">
              <a href="' . $boardurl . '/imprimir/imagen/' . $context['gallery_pic']['ID_PICTURE'] . '">
                <img alt="" src="' . $tranfer1 . '/icons/cwbig-v1-iconos.gif?v3.2.3" style="cursor: pointer; margin-top: -640px; display: inline;" />
              </a>
            </div>
          </div>
        </div>
        <div class="windowbg" style="width: 772px;" id="img_' . $context['gallery_pic']['ID_PICTURE'] . '">
          <div class="post-contenido" property="dc:content">';

  if ($context['user']['is_guest']) {
    echo '<div align="center" style="-moz-border-radius: 5px; -webkit-border-radius: 5px; display: block; margin-bottom: 25px; margin-top: 10px; padding: 2px; border: solid 1px #D5CCC3; background: #FFF;">';

    echo anuncio_728x90();

    echo '
        <br />
        <a href="' . $boardurl . '/registrarse/" style="font-size: 12px; color: #FFB600; margin-bottom: 3px;">
          <b>REG&Iacute;STRATE GRATIS Y ELIMINA ESTA PUBLICIDAD, ADEM&Aacute;S TENDR&Aacute;S ACCESO A TODOS LOS POSTS Y FUNCIONES</b>
        </a>
      </div>';
  }

  // TO-DO: ¿Está bien usar un @ para mitigar el error en localhost? ¿Funcionará bien en un servidor remoto sin el @?
  $imgc = @getimagesize($context['gallery_pic']['filename']);
  // var_dump($imgc);
  $w = is_array($imgc) && $imgc[0] > '748' ? 'width="748px" ' : '';

  echo '
    <center>
      <img alt="" ' . $w . 'title="' . $context['gallery_pic']['title'] . '" src="' . $context['gallery_pic']['filename'] . '" />
    </center>';

  if ($context['user']['is_guest']) {
    echo '
      <div align="center" style="-moz-border-radius: 5px; -webkit-border-radius: 5px; display: block; margin-bottom: 10px; margin-top: 25px; padding: 2px; border: solid 1px #D5CCC3; background: #FFF;">
        <a href="' . $boardurl . '/registrarse/" style="font-size: 12px; color: #FFB600; margin-bottom: 3px;">
          <b>REG&Iacute;STRATE GRATIS Y ELIMINA ESTA PUBLICIDAD, ADEM&Aacute;S TENDR&Aacute;S ACCESO A TODOS LOS POSTS Y FUNCIONES</b>
        </a>
        <br />';

    anuncio_728x90();

    echo '</div>';
  }

  echo '
      </div>
      <div id="social"></div>
    </div>
    <!-- info de la imagen -->
    <div style="margin-top: 8px;">
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
        <div class="windowbg" style="width: 370px; padding: 4px;">';

  if ($context['gallery_pic']['ID_MEMBER'] == $ID_MEMBER || $context['allow_admin']) {
    echo '
      <form action="' . $boardurl . '/web/cw-imgEliminar.php?id=' . $context['gallery_pic']['ID_PICTURE'] . '" method="post" accept-charset="' . $context['character_set'] . '" name="causaf" id="causaf">
        <input class="login" style="font-size: 11px;" value="Editar img" title="Editar img" onclick="location.href=\'' . $boardurl . '/editar-imagen/', $context['gallery_pic']['ID_PICTURE'], '\'" type="button" />
        <input class="login" style="font-size: 11px;" type="submit" value="Eliminar img" title="Eliminar img" onclick="if (!confirm(\'\xbfEst&aacute;s seguro que deseas eliminar esta imagen?\')) return false;' . ($context['gallery_pic']['ID_MEMBER'] != $ID_MEMBER ? ' return errorrojo2(this.form.causa.value);' : '') . '" />';

    if ($context['gallery_pic']['ID_MEMBER'] != $ID_MEMBER) {
      echo '
         <b>Causa:</b>
        <input type="text" onfocus="foco(this);" onblur="no_foco(this);" id="causa" name="causa" maxlength="70" style="width: 135px;" />
        <center>
          <label id="errors" class="size10" style="color: red;"></label>
        </center>';
    }

    echo '
      </form>
      <div class="hrs"></div>';
  }

  if (!empty($context['leecher'])) {
    echo '
      <b class="size11">
        <center>Usuarios no registrados y <span title="Primer rango">turistas</span> no puede calificar.</center>
      </b>
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
          <a href="#" onclick="votar_img(\'' . $context['gallery_pic']['ID_PICTURE'] . "','" . $i . '\'); return false;" class="botN3" style="width: 20px; color: #fff; text-shadow: #444 0px 1px 0px;" title="Dar ' . $i . ' puntos">' . $i . '</a>
        </div>';
    }

    echo '
      </div>
      <div class="hrs"></div>';
  }

  if ($context['user']['is_logged']) {
    echo '
      <center>
        <span id="span_opciones2" style="text-align: center; display: block;">
          <a class="Iagregar_favoritos png" href="#" onclick="add_favoritos_img(\'' . $context['gallery_pic']['ID_PICTURE'] . '\'); return false;">Agregar a Favoritos</a>
          &nbsp;&#124;&nbsp;
          <a class="Idenunciar_post boxy png" title="Denunciar ' . $context['gallery_pic']['title'] . '" href="' . $boardurl . '/web/cw-denunciaTEMP.php?t=2;d=' . $context['gallery_pic']['ID_PICTURE'] . '"/>Denunciar IMG</a>
          &nbsp;&#124;&nbsp;';
  }

  echo '<a class="Irecomendar_post png" href="' . $boardurl . '/enviar-a-amigo/imagen-' . $context['gallery_pic']['ID_PICTURE'] . '">Enviar a un amigo</a>';

  if ($context['user']['is_logged']) {
    echo '
        </span>
      </center>';
  }

  echo '
    <div class="hrs"></div>
    <b class="size13">Otras im&aacute;genes:</b>
    <br />';

  $r = db_query("
    SELECT COUNT(*)
    FROM {$db_prefix}gallery_pic", __FILE__, __LINE__);

  $d = mysqli_fetch_row($r);
  $rand = mt_rand(0, $d[0] - 1);

  $al_azar = db_query("
    SELECT title, puntos, ID_PICTURE
    FROM {$db_prefix}gallery_pic
    LIMIT $rand, 10", __FILE__, __LINE__);

  while ($row = mysqli_fetch_assoc($al_azar)) {
    $tiitulo = $row['title'];
    $idlo = $row['ID_PICTURE'];

    echo '
      <div class="postENTry">
        <a rel="dc:relation" href="' . $boardurl . '/imagenes/ver/' . $idlo . '" title="' . $tiitulo . '" class="categoriaPost imagenesNOCAT" target="_self">' . $tiitulo . '</a>
        <div style="clear: left;"></div>
      </div>';
  }

  mysqli_free_result($al_azar);

  echo '
          </div>
        </div>
      </div>
      <div style="width: 386px; float: left;">
        <div class="box_390">
          <div class="box_title" style="width: 384px;">
            <div class="box_txt box_390-34">Informaci&oacute;n de la imagen</div>
            <div class="box_rss">
              <img alt="" src="' . $tranfer1 . '/blank.gif" style="width: 16px; height: 16px;" border="0" />
            </div>
          </div>
          <div class="windowbg" style="width: 376px; padding: 4px;">
            <center>
              <span class="Ivisitas png">&nbsp;', $context['gallery_pic']['views'], '&nbsp;visitas</span>
              <span class="Ifavoritos png">&nbsp;<span id="cant_favs_post">' . $context['fav2'] . '</span>&nbsp;favoritos</span>
              <span class="Ipuntos png">&nbsp;<span id="cant_pts_post_dos">' . $context['gallery_pic']['puntos'] . '</span>&nbsp;puntos</span>
            </center>';

  if ($context['allow_admin']) {
    $request = db_query("
      SELECT p.id_user, p.fecha, p.cantidad, m.realName
      FROM {$db_prefix}gallery_cat AS p, {$db_prefix}members AS m
      WHERE p.id_img = {$context['gallery_pic']['ID_PICTURE']}
      AND p.cantidad <> 0
      AND p.fecha <> 0
      AND p.id_user = m.ID_MEMBER
      ORDER BY p.ID_CAT DESC", __FILE__, __LINE__);

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

    if (!empty($skasdasdbsddd)) {
      echo '
        <div class="hrs"></div>
        <b>Dieron puntos a esta imagen:</b> ';
      echo join(', ', $userdasd);
    }
  }

  echo '
    <div class="hrs"></div>
    <b>Creado el:</b>&nbsp;';

  if (empty($context['gallery_pic']['date'])) {
    echo 'Sin fecha de creaci&oacute;n';
  } else {
    echo '<span property="dc:date" content="' . timeformat($context['gallery_pic']['date']) . '">' . timeformat($context['gallery_pic']['date']) . '</span>';
  }

  echo '
        <div class="hrs"></div>
        <table>
          <tr>
            <td width="50px">
              <b>Enlace:</b>
            </td>
            <td width="290px">
              <input readonly="readonly" id="enlace" name="enlace" type="text" onfocus="foco(this);" onblur="no_foco(this);" value="' . $context['gallery_pic']['filename'] . '" onclick="selectycopy(getElementById(\'enlace\'));" style="width: 290px;">
            </td>
          </tr>
          <tr>
            <td width="50px">
              <b>Embed:</b>
            </td>
            <td width="290px">
              <input readonly="readonly" id="embed" name="embed" type="text" onfocus="foco(this);" onblur="no_foco(this);" value="&lt;a title=&quot;' . $context['gallery_pic']['title'] . ' - casitaweb.net&quot; href=&quot;' . $boardurl . '/imagenes/ver/' . $context['gallery_pic']['ID_PICTURE'] . '&quot; target=&quot;_blank&quot;&gt;' . $context['gallery_pic']['title'] . ' - casitaweb.net&lt;/a&gt;" onclick="selectycopy(getElementById(\'embed\')); " style="width: 290px;" />
            </td>
          </tr>
          <tr>
            <td width="50px">
              <b>BBCode:</b>
            </td>
            <td width="290px">
              <input readonly="readonly" id="bbcode" name="bbcode" type="text" onfocus="foco(this);" onblur="no_foco(this);" value="[IMG]' . $context['gallery_pic']['filename'] . '[/IMG]" onclick="selectycopy(getElementById(\'bbcode\'));" style="width: 290px;" />
            </td>
          </tr>
        </table>
      </div>
    </div>';

  $requests = db_query("
    SELECT signature
    FROM {$db_prefix}members
    WHERE ID_MEMBER = {$context['gallery_pic']['ID_MEMBER']}", __FILE__, __LINE__);

  while ($grups = mysqli_fetch_assoc($requests)) {
    $context['firma'] = $grups['signature'];
  }

  mysqli_free_result($requests);

  $nwesdas = $context['firma'];

  if (!empty($nwesdas) && empty($options['show_no_signatures'])) {
    echo '
      <div class="box_390" style="margin-top: 8px;">
        <div class="box_title" style="width: 384px;">
          <div class="box_txt box_390-34">Firma</div>
          <div class="box_rss">
            <img alt="" src="' . $tranfer1 . '/blank.gif" style="width: 16px; height: 16px;" border="0" />
          </div>
        </div>
        <div class="windowbg" style="width: 376px; padding: 4px;">
          <div class="fimaFIX">
            <b class="size11">' . str_replace('if(this.width >720) {this.width=720}', 'if(this.width > 375) {this.width=375}', str_replace('class="imagen"', 'class="imagen-firma"', parse_bbc($nwesdas))) . '</b>
          </div>
        </div>
      </div>';
  }

  echo '
      </div>
    </div>
    <!-- fin info del post -->
    <!-- comentarios -->
    <div style="clear: left;"></div>
    <div style="margin-bottom: 8px; margin-top: 8px;">';

  if ($context['sin_coment']) {
    echo '
      <div class="icon_img" style="float: left; margin-right: 5px;">
        <a href="' . $boardurl . '/rss/pic-comment/' . $context['gallery_pic']['ID_PICTURE'] . '">
          <img alt="" src="' . $tranfer1 . '/icons/cwbig-v1-iconos.gif?v3.2.3" style="cursor: pointer; margin-top: -352px; display: inline;" />
        </a>
      </div>';
  }

  echo '<b style="font-size: 14px;">Comentarios (<span id="nrocoment">' . $context['sin_coment'] . '</span>)</b>';

  $dbresult = db_query("
    SELECT c.ID_PICTURE, c.ID_COMMENT, c.date, c.comment, c.ID_MEMBER, m.memberName, m.realName
    FROM {$db_prefix}gallery_comment AS c, {$db_prefix}members AS m
    WHERE c.ID_PICTURE = {$context['gallery_pic']['ID_PICTURE']}
    AND c.ID_MEMBER = m.ID_MEMBER
    ORDER BY c.ID_COMMENT ASC", __FILE__, __LINE__);

  $context['pic_comment'] = array();

  while ($row = mysqli_fetch_assoc($dbresult)) {
    censorText($row['comment']);

    $context['pic_comment'][] = array(
      'id' => $row['ID_COMMENT'],
      'nomuser' => $row['realName'],
      'id-user' => $row['ID_MEMBER'],
      'comentario' => parse_bbc($row['comment']),
      'comentario2' => $row['comment'],
      'fecha' => $row['date'],
    );

    $context['id_img'] = $row['ID_PICTURE'];
  }

  mysqli_free_result($dbresult);

  foreach ($context['pic_comment'] as $coment) {
    echo '
      <div id="cmt_' . $coment['id'] . '" class="Coment">
        <span class="size12">
          <div class="User-Coment">
            <div style="float: left;">
              <b id="autor_cmnt_' . $coment['id'] . '" user_comment="' . $coment['nomuser'] . '" text_comment="' . $coment['comentario2'] . '">
                <a href="' . $boardurl . '/perfil/' . $coment['nomuser'] . '" style="color: #956100;">' . $coment['nomuser'] . '</a>
              </b>
              <span title="' . tiempo2($coment['fecha']) . '">' . hace($coment['fecha']) . '</span>
              dijo:
            </div>
            <div style="float: right;">';

    if ($context['user']['is_logged']) {
      echo '
        <a href="' . $boardurl . '/web/cw-TEMPenviarMP.php?user=' . $coment['nomuser'] . '" title="Enviar MP a ' . $coment['nomuser'] . '" class="boxy">
          <img alt="" src="' . $tranfer1 . '/icons/mensaje_para.gif" border="0" />
        </a>
        &#32;
        <a onclick="citar_comment(' . $coment['id'] . ')" href="javascript:void(0)" title="Citar comentario">
          <img alt="" src="' . $tranfer1 . '/comunidades/respuesta.png" class="png" border="0" />
        </a>';

      if ($context['gallery_pic']['ID_MEMBER'] == $ID_MEMBER || $context['allow_admin']) {
        echo '
          &#32;
          <a href="#" onclick="del_coment_img(' . $coment['id'] . ',' . $context['gallery_pic']['ID_PICTURE'] . '); return false;" title="Eliminar comentario">
            <img alt="" src="' . $tranfer1 . '/comunidades/eliminar.png" class="png" style="width: 16px; height: 16px;" border="0" />
          </a>';
      }
    }

    echo '
            </div>
          </div>
          <div class="cuerpo-Coment">
            <div style="white-space: pre-wrap; overflow: hidden; display: block;">' . $coment['comentario'] . '</div>
          </div> 
        </span>
      </div>';
  }

  if (!$context['sin_coment']) {
    echo '<div id="no_comentarios" class="noesta" style="width: 774px;">Esta imagen no tiene comentarios.</div>';
  } else {
    echo '<div id="no_comentarios" class="noesta" style="width: 774px; display: none;">Esta imagen no tiene comentarios.</div>';
  }

  echo '
      <div id="return_agregar_comentario" style="display: none;"></div>
      <div class="errorDelCom" style="display: hide; width: 774px;"></div>
    </div>
    <!-- fin comentarios -->';

  if ($context['user']['id']) {
    $request = db_query("
      SELECT id_user
      FROM {$db_prefix}pm_admitir
      WHERE id_user = {$context['gallery_pic']['ID_MEMBER']}
      AND quien = {$context['user']['id']}
      LIMIT 1", __FILE__, __LINE__);

    $ignorado = mysqli_num_rows($request);

    if (!$ignorado) {
      echo '
        <!-- comentar -->
        <div style="clear: left;"></div>
        <div style="margin-bottom: 3px;" id="comentar" name="comentar">
          <b style="font-size: 14px;">Agregar un nuevo comentario</b>
        </div>
        <div style="width: 774px;">
          <form name="nuevocoment">
            <center><div class="msg_add_comment"></div></center>
            <div style="clear: left; margin-bottom: 2px"></div>';

      sas();

      echo '
              <br />
              <input class="login" type="button" id="button_add_comment" value="Enviar comentario" onclick="add_comment_img(\'' . $context['gallery_pic_id'] . "', '" . ($context['sin_coment'] + 1) . '\'); return false;" tabindex="2" />
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
  } else {
    echo '
      <div style="clear: left;"></div>
      <div class="noesta-am" style="width: 774px; margin-top: 5px;">
        Para poder comentar necesitas estar <a href="' . $boardurl . '/registrarse/" style="color:#FFB600;" title="Registrarse">REGISTRADO</a>. Si ya tienes usuario <a href="javascript:irAconectarse();" style="color:#FFB600;" title="Conectarse">&iexcl;CON&Eacute;CTATE!</a>
      </div>';
  }

  echo '
        </div>
      </div>
    </div>';
}

function sas() {
  global $tranfer1, $context, $settings, $options, $txt, $modSettings;

  echo '
    <textarea id="editorCW" style="resize: none; height: 70px; width: 768px;" name="cuerpo_comment" tabindex="1"></textarea>
    <p align="right" style="margin: 0px; padding: 0px;">';

  if (!empty($context['smileys']['postform'])) {
    foreach ($context['smileys']['postform'] as $smiley_row) {
      foreach ($smiley_row['smileys'] as $smiley) {
        echo '<span style="cursor:pointer;" onclick="replaceText(\' ', $smiley['code'], '\', document.forms.nuevocoment.editorCW); return false;"><img class="png" src="' . $tranfer1 . '/emoticones/' . $smiley['filename'] . '" align="bottom" alt="', $smiley['description'], '" title="', $smiley['description'], '" /></span> ';
      }
    }

    if (!empty($context['smileys']['popup'])) {
      echo '<a href="javascript:moticonup()">[', $txt['more_smileys'], ']</a>';
    }
  }
}

function template_add_picture() {
  global $tranfer1, $scripturl, $modSettings, $db_prefix, $txt, $context, $settings, $boardurl;

  $cat = 1;
  echo '
    <script type="text/javascript">
      function requerido(title, filename) {
        if (title == \'\') {
          alert(\'No has escrito el t&iacute;tulo de la imagen.\');
          return false;
        }

        if(filename == \'\') {
          alert(\'No has agregado ning\\xfan enlace de imagen.\');
          return false;
        }
      }
    </script>';

  ditaruser();

  echo '
    <div style="float: left; width: 776px;">
      <div class="box_780">
        <div class="box_title" style="width: 774px;">
          <div class="box_txt box_780-34">
            <center>Agregar imagen</center>
          </div>
          <div class="box_rss">
            <img alt="" src="' . $tranfer1 . '/blank.gif" style="width: 16px; height: 16px;" border="0" />
          </div>
        </div>
        <div class="windowbg" border="0" style="width: 766px; padding: 4px;">
          <form method="POST" enctype="multipart/form-data" name="forma" id="forma" action="' . $boardurl . '/web/cw-imgAgregar.php">
            <center>
              <b class="size11">' . $txt['gallery_form_title'] . '</b>
              <br />
              <input onfocus="foco(this);" onblur="no_foco(this);"  tabindex="1" size="60" maxlength="54" type="text" onfocus="foco(this);" onblur="no_foco(this);" name="title" id="title" value="" />
              <br />
              <br />
              <b class="size11">URL de la imagen:</b>
              <br />
              <input onfocus="foco(this);" onblur="no_foco(this);" type="text" onfocus="foco(this);" onblur="no_foco(this);" tabindex="2" size="60" name="filename" value="" />
              <div class="hrs"></div>
              <div class="noesta">* Si la imagen contiene pornograf&iacute;a o es morboso, se borrar&aacute;.</div>
              <br />
              <input type="submit" class="button" style="font-size: 15px;" onclick="return requerido(this.form.title.value, this.form.filename.value);" tabindex="3" value="Agregar imagen" name="submit" />
            </center>
          </form>
        </div>
      </div>
    </div>';
}

function template_edit_picture() {
  global $tranfer1, $scripturl, $modSettings, $db_prefix, $txt, $context, $settings, $boardurl;

  $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
  $limit3 = db_query("
    SELECT ID_MEMBER
    FROM {$db_prefix}gallery_pic
    WHERE ID_PICTURE = $id
    LIMIT 1", __FILE__, __LINE__);

  while ($lim2 = mysqli_fetch_assoc($limit3)) {
    $ID_MEMBER23234 = $lim2['ID_MEMBER'];
  }

  if ($context['allow_admin'] || $ID_MEMBER23234 == $context['user']['id']) {
    echo '
      <script type="text/javascript">
        function requerido(title, filename) {
          if (title == \'\') {
            alert(\'No has escrito el t&iacute;tulo de la imagen.\');
            return false;
          }

          if (filename == \'\') {
            alert(\'No has agregado ning\\xfan enlace de imagen.\');
            return false;
          }
        }
      </script>';

    if ($ID_MEMBER23234 !== $context['user']['id']) {
      $_GET['u'] = (int) $ID_MEMBER23234;
    }

    ditaruser();

    echo '
      <div style="float: left; width: 776px;">
        <div class="box_780">
          <div class="box_title" style="width: 774px;">
            <div class="box_txt box_780-34">
              <center>Editar imagen</center>
            </div>
            <div class="box_rss">
              <img alt="" src="' . $tranfer1 . '/blank.gif" style="width: 16px; height: 16px;" border="0" />
            </div>
          </div>
          <div class="windowbg" border="0" style="width: 766px; padding: 4px;">
            <form method="POST" enctype="multipart/form-data" name="forma2" id="forma2" action="' . $boardurl . '/web/cw-imgEditar.php">
              <center>
                <b class="size11">' . $txt['gallery_form_title'] . '</b><br />
                <input onfocus="foco(this);" onblur="no_foco(this);" tabindex="1" size="60" maxlength="54" type="text" onfocus="foco(this);" onblur="no_foco(this);" name="title" id="title" value="' . nohtml2(nohtml($context['gallery_pic']['title'])) . '" />
                <br />
                <br />
                <b class="size11">URL de la imagen:</b>
                <br />
                <input onfocus="foco(this);" onblur="no_foco(this);" type="text" onfocus="foco(this);" onblur="no_foco(this);" tabindex="2" size="60" name="filename" value="' . nohtml2(nohtml($context['gallery_pic']['filename'])) . '" />
                <div class="hrs"></div>
                <div class="noesta">* Si la imagen contiene pornograf&iacute;a o es morboso, se borrar&aacute;.</div>
                <br />
                <input type="submit" tabindex="3" class="button" style="font-size: 15px;" onclick="return requerido(this.form.title.value, this.form.filename.value);" value="Editar imagen" name="submit" />
              </center>
              <input type="hidden" name="id" value="' . $id . '" />
            </form>
          </div>
        </div>
      </div>';
  } else {
    fatal_error('No tienes permisos para editar esta imagen.', false, '', 4);
  }
}

?>