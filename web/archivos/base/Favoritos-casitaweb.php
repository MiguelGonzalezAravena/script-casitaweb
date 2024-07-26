<?php
// Página de Rodrigo Zaupa (rigo@casitaweb.net)
if (!defined('CasitaWeb!-PorRigo')) {
  die(base64_decode('d3d3LmNhc2l0YXdlYi5uZXQgLSByaWdv'));
}

function template_intro() {
  global $context, $settings, $db_prefix, $scripturl, $txt, $return, $tranfer1, $boardurl;

  $myser = $context['user']['id'];
  $request = db_query("
    SELECT f.ID_MEMBER
    FROM {$db_prefix}messages AS m, {$db_prefix}bookmarks AS f
    WHERE f.ID_MEMBER = $myser
    AND f.tipo = 0
    AND m.ID_TOPIC = f.ID_TOPIC", __FILE__, __LINE__);

  $NroRegistros = mysqli_num_rows($request);

  arsar();

  if ($NroRegistros) {
    $RegistrosAMostrar = 15;
    $_GET['pag'] = isset($_GET['pag']) ? $_GET['pag'] : '';
    $fuf = $_GET['pag'] < 1 ? 1 : $_GET['pag'];

    if (isset($fuf)) {
      $RegistrosAEmpezar = ($fuf - 1) * $RegistrosAMostrar;
      $PagAct = $fuf;
    } else {
      $RegistrosAEmpezar = 0;
      $PagAct = 1;
    }

    $request = db_query("
      SELECT f.id, m.subject, c.description, m.ID_TOPIC, m.posterName, m.puntos
      FROM {$db_prefix}messages AS m, {$db_prefix}bookmarks AS f, {$db_prefix}boards AS c
      WHERE f.ID_MEMBER='$myser' AND f.tipo=0 AND m.ID_TOPIC=f.ID_TOPIC AND c.ID_BOARD=m.ID_BOARD
      ORDER BY f.id DESC
      LIMIT $RegistrosAEmpezar, $RegistrosAMostrar", __FILE__, __LINE__);

    $context['tos'] = array();

    while ($row = mysqli_fetch_array($request)) {
      $context['sdasdrr'] = $row['ID_TOPIC'];
      $context['tos'][] = array(
        'id' => $row['id'],
        'ID_TOPIC' => $row['ID_TOPIC'],
        'subject' => censorText($row['subject']),
        'posterName' => $row['posterName'],
        'puntos' => $row['puntos'],
        'description' => $row['description']
      );
    }

    mysqli_free_result($request);

    $PagAnt = $PagAct - 1;
    $PagSig = $PagAct + 1;
    $PagUlt = $NroRegistros / $RegistrosAMostrar;
    $Res = $NroRegistros % $RegistrosAMostrar;

    if ($Res > 0) {
      $PagUlt = floor($PagUlt) + 1;
    }

    if ($PagAct > $PagUlt) {
      echo '
        </div>
        <div style="clear: left;"></div>
        <div class="noesta" style="width:922px;">No tienes ning&uacute;n post favorito.</div>';
    } else {
      echo '
        <table class="linksList" style="width: 757px;">
          <thead align="center">
            <tr>
              <th style="text-align: left;">Posts favoritos</th>
              <th>Creado por</th>
              <th>Puntos</th>
              <th>Recomendar</th>
              <th>Eliminar</th>
            </tr>
          </thead>
          <tbody>';

      foreach ($context['tos'] as $dat) {
        echo '
          <tr id="fav_' . $dat['id'] . '">
            <td style="text-align: left;">
              <a class="categoriaPost ' . $dat['description'] . '"  href="' . $boardurl . '/post/' . $dat['ID_TOPIC'] . '/' . $dat['description'] . '/' . urls($dat['subject']) . '.html" title="" title="' . $dat['subject'] . '">' . achicars($dat['subject']) . '</a>
            </td>
            <td title="' . $dat['posterName'] . '">
              <a href="' . $boardurl . '/perfil/' . $dat['posterName'] . '">' . $dat['posterName'] . '</a>
            </td>
            <td style="color: green;" title="' . $dat['puntos'] . '">' . $dat['puntos'] . '</td>
            <td>
              <span class="pointer" title="Recomendar post" onclick="Boxy.load(\'' . $boardurl . '/web/cw-TEMPenviarPost.php?id=' . $dat['ID_TOPIC'] . "', { title: 'Recomendar " . $dat['subject'] . '\' });">
                <img alt="" src="' . $tranfer1 . '/icons/icono-enviar-mensaje.gif" height="16px" width="16px" />
              </span>
            </td>
            <td>
              <span id="imgel_' . $dat['id'] . '">
                <span class="pointer" onclick="return del_favoritos(\'' . $dat['id'] . '\');" title="Eliminar favorito">
                  <img alt="" src="' . $tranfer1 . '/eliminar.gif" height="10px" width="10px" />
                </span>
                <span id="imgerr_' . $dat['id'] . '" style="display: none;"></span>
                <span id="imgerrs_' . $dat['id'] . '" style="display: none;">
                  <img alt="" src="' . $tranfer1 . '/eliminar.gif" height="10px" width="10px" />
                </span>
              </span>
            </td>
          </tr>';
      }

      echo '
          </tbody>
        </table>
        <div style="clear: left;"></div>
        <div class="windowbgpag" style="width: 300px;">';

      if ($PagAct > $PagUlt) {
        // ¿Aquí se hace algo?
      } else if ($PagAct > 1 || $PagAct < $PagUlt) {
        if ($PagAct > 1) {
          echo '<a href="' . $boardurl . '/favoritos/post/pag-' . $PagAnt . '">&#171; anterior</a>';
        }

        if ($PagAct < $PagUlt) {
          echo '<a href="' . $boardurl . '/favoritos/post/pag-' . $PagSig . '">siguiente &#187;</a>';
        }
      }

      echo '
          </div>
          <div style="clear: both;"></div>
        </div>
        <div width="160px" style="float: left; width: 155px;">
          <div style="float: left; margin-bottom: 8px;" class="img_aletat">
            <div class="box_title" style="width: 155px;">
              <div class="box_txt img_aletat">Publicidad</div>
              <div class="box_rss">
                <img src="' . $tranfer1 . '/blank.gif" style="width: 16px; height: 16px;" border="0" alt="" />
              </div>
            </div>
            <div class="windowbg" style="width: 147px; padding: 4px;" align="center">';

      echo anuncio_160x600();

      echo '
            </div>
          </div>
        </div>';
    }
  } else {
    echo '
      </div>
      <div style="clear: left;"></div>
      <div class="noesta" style="width: 922px;">No tienes ning&uacute;n post favorito.</div>';
  }
}

function template_imagen() {
  global $context, $settings, $db_prefix, $scripturl, $txt, $return, $tranfer1, $boardurl;

  $myser = $context['user']['id'];
  $request = db_query("
    SELECT f.ID_MEMBER, f.tipo, m.ID_PICTURE, f.ID_TOPIC
    FROM {$db_prefix}gallery_pic AS m, {$db_prefix}bookmarks AS f
    WHERE f.ID_MEMBER = $myser
    AND f.tipo = 1
    AND m.ID_PICTURE = f.ID_TOPIC", __FILE__, __LINE__);

  $NroRegistros = mysqli_num_rows($request);

  arsar();

  if ($NroRegistros) {
    $RegistrosAMostrar = 15;
    $_GET['pag'] = isset($_GET['pag']) ? $_GET['pag'] : '';
    $fuf = $_GET['pag'] < 1 ? 1 : $_GET['pag'];

    if (isset($fuf)) {
      $RegistrosAEmpezar = ($fuf - 1) * $RegistrosAMostrar;
      $PagAct = $fuf;
    } else {
      $RegistrosAEmpezar = 0;
      $PagAct = 1;
    }

    $request = db_query("
      SELECT f.id, m.ID_PICTURE, m.title
      FROM {$db_prefix}gallery_pic AS m, {$db_prefix}bookmarks AS f
      WHERE f.ID_MEMBER = $myser
      AND f.tipo = 1
      AND m.ID_PICTURE = f.ID_TOPIC
      ORDER BY f.id DESC
      LIMIT $RegistrosAEmpezar, $RegistrosAMostrar", __FILE__, __LINE__);

    $context['tos'] = array();

    while ($row = mysqli_fetch_array($request)) {
      $context['tos'][] = array(
        'id' => $row['id'],
        'ID_TOPIC' => $row['ID_PICTURE'],
        'subject' => $row['title']
      );
    }

    mysqli_free_result($request);

    $PagAnt = $PagAct - 1;
    $PagSig = $PagAct + 1;
    $PagUlt = $NroRegistros / $RegistrosAMostrar;
    $Res = $NroRegistros % $RegistrosAMostrar;

    if ($Res > 0) {
      $PagUlt = floor($PagUlt) + 1;
    }

    if ($PagAct > $PagUlt) {
      echo '
        </div>
        <div style="clear: left;"></div>
        <div class="noesta" style="width: 922px;">No tienes ninguna imagen favorita.</div>';
    } else {
      echo '
        <table class="linksList" style="width: 757px;">
          <thead align="center">
            <tr>
              <th style="text-align:left;">Im&aacute;genes favoritas</th>
              <th>Creada por</th>
              <th>Puntos</th>
              <th>Enviar</th>
              <th>Eliminar</th>
            </tr>
          </thead>
          <tbody>';

      foreach ($context['tos'] as $dat) {
        $request = db_query("
          SELECT mem.realName, p.puntos
          FROM {$db_prefix}gallery_pic AS p, {$db_prefix}members AS mem
          WHERE p.ID_PICTURE = {$dat['ID_TOPIC']}
          AND p.ID_MEMBER = mem.ID_MEMBER", __FILE__, __LINE__);

        while ($row = mysqli_fetch_assoc($request)) {
          $realname = $row['realName'];
          $puntos = $row['puntos'];
        }

        mysqli_free_result($request);

        echo '
          <tr id="fav_' . $dat['id'] . '">
            <td style="text-align: left;">
              <a class="categoriaPost imagenesNOCAT" href="' . $boardurl . '/imagenes/ver/' . $dat['ID_TOPIC'] . '/" title="" title="' . $dat['subject'] . '">' . achicars($dat['subject']) . '</a>
            </td>
            <td title="' . $realname . '">
              <a href="' . $boardurl . '/perfil/' . $realname . '" title="' . $realname . '">' . $realname . '</a>
            </td>
            <td style="color: green;" title="' . $puntos . '">' . $puntos . '</td>
            <td>
              <a title="Enviar a amigo" href="' . $boardurl . '/enviar-a-amigo/imagen-' . $dat['ID_TOPIC'] . '">
                <img alt="" src="' . $tranfer1 . '/icons/icono-enviar-mensaje.gif" height="16px" width="16px" />
              </a>
            </td>
            <td>
              <span id="imgel_' . $dat['id'] . '">
                <a href="#" onclick="return del_favoritos(\'' . $dat['id'] . '\');" title="Eliminar favorito">
                  <img alt="" src="' . $tranfer1 . '/eliminar.gif" height="10px" width="10px" />
                </a>
              </span>
              <span id="imgerr_' . $dat['id'] . '" style="display: none;"></span>
              <span id="imgerrs_' . $dat['id'] . '" style="display: none;">
                <img alt="" src="' . $tranfer1 . '/eliminar.gif" height="10px" width="10px" />
              </span>
            </td>
          </tr>';
      }

      echo '
          </tbody>
        </table>
        <div style="clear: left;"></div>
        <div class="windowbgpag" style="width: 300px;">';

      if ($PagAct > $PagUlt) {
        // ¿Acá de hace algo?
      } else if ($PagAct > 1 || $PagAct < $PagUlt) {
        if ($PagAct > 1) {
          echo '<a href="' . $boardurl . '/favoritos/imagen/pag-' . $PagAnt . '">&#171; anterior</a>';
        }

        if ($PagAct < $PagUlt) {
          echo '<a href="' . $boardurl . '/favoritos/imagen/pag-' . $PagSig . '">siguiente &#187;</a>';
        }
      }

      echo '
          </div>
        </div>
        <div width="160px" style="float: left; width: 155px;">
          <div style="float: left; margin-bottom: 8px;" class="img_aletat">
            <div class="box_title" style="width: 155px;">
              <div class="box_txt img_aletat">Publicidad</div>
              <div class="box_rss">
                <img src="' . $tranfer1 . '/blank.gif" style="width: 16px; height: 16px;" border="0" alt="" />
              </div>
            </div>
            <div class="windowbg" style="width: 147px; padding: 4px;" align="center">';

      anuncio_160x600();

      echo '
            </div>
          </div>
        </div>';
    }
  } else {
    echo '
      </div>
      <div style="clear: left;"></div>
      <div class="noesta" style="width: 922px;">No tienes ninguna imagen favorita.</div>';
  }
}

function arsar() {
  global $boardurl;

  echo '
    <div style="float: left; width: 757px; margin-right: 8px;">
      <div class="botnes">
        <a href="' . $boardurl . '/favoritos/post/" title="Posts">Posts</a>
        <a href="' . $boardurl . '/favoritos/imagen/" title="Im&aacute;genes">Im&aacute;genes</a>
        <div style="clear: both;"></div>
      </div>';
}

?>