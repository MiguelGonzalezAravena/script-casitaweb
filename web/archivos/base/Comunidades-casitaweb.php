<?php

function template_intro() {
  global $tranfer1, $sourcedir, $no_avatar, $ID_MEMBER, $modSettings, $context, $db_prefix, $boardurl;

  arriba();

  if (!$context['comuid']) {
    echo '
      <div style="text-align: left;">
        <div style="float: left; height: auto; margin-right: 6px;">
          <div class="ultimos_postsa" style="margin-bottom: 4px;">
            <div class="crear_comunidad">
              <a href="' . $boardurl . '/crear-comunidades/">
                <img src="' . $tranfer1 . '/comunidades/btn-crear_comunidad.png" alt="" class="png" title="Crear Comunidad" />
              </a>
            </div>
            <div class="box_title" style="width: 378px;">
              <div class="box_txt ultimos_posts">&Uacute;ltimos temas</div>
              <div class="box_rss">
                <div class="icon_img">
                  <a href="' . $boardurl . '/rss/ultimos-temas/">
                    <img alt="" src="' . $tranfer1 . '/icons/cwbig-v1-iconos.gif?v3.2.3" style="cursor: pointer; margin-top: -352px; display: inline;" height="895px" width="18px" />
                  </a>
                </div>
              </div>
            </div>
            <div class="windowbg" style="width: 370px; padding: 4px;">';

    $RegistrosAMostrar = 20;
    $padds = isset($_GET['pag']) ? (int) $_GET['pag'] : '';
    $per = $padds < 1 ? 1 : $padds;

    if (isset($per)) {
      $RegistrosAEmpezar = ($per - 1) * $RegistrosAMostrar;
      $PagAct = $per;
    } else {
      $RegistrosAEmpezar = 0;
      $PagAct = 1;
    }

    $_GET['cat'] = isset($_GET['cat']) ? (int) $_GET['cat'] : '';
    $cat = str_replace('/', '', $_GET['cat']);

    $request = db_query("
      SELECT a.titulo, c.nombre, c.url as url2, a.id, m.realName, b.url, b.nombre as nomb2
      FROM {$db_prefix}comunidades AS c
      INNER JOIN {$db_prefix}comunidades_articulos AS a ON a.id_com = c.id
      AND a.eliminado = 0
      INNER JOIN {$db_prefix}members AS m ON a.id_user = m.ID_MEMBER
      INNER JOIN {$db_prefix}comunidades_categorias AS b ON c.categoria = b.id
      AND c.acceso <> 4
      AND c.bloquear = 0
      " . (empty($cat) ? '' : " AND b.url = '$cat'") . "
      ORDER BY a.id DESC
      LIMIT $RegistrosAEmpezar, $RegistrosAMostrar", __FILE__, __LINE__);

    $context['posts'] = array();

    while ($row = mysqli_fetch_assoc($request)) {
      $context['posts'][] = array(
        'titulo' => $row['titulo'],
        'nombre' => $row['nombre'],
        'url2' => $row['url2'],
        'id' => $row['id'],
        'url' => $row['url'],
        'nomb2' => $row['nomb2'],
        'realName' => $row['realName']
      );
    }

    mysqli_free_result($request);

    foreach ($context['posts'] as $posts) {
      $tit = $posts['titulo'];
      $posts['nombre'] = $posts['nombre'];
      $tit2 = $posts['titulo'];
      $tit3 = strlen($tit) > 40 ? substr($tit, 0, 37) . '...' : $tit;

      echo '
        <div class="comunidad_tema">
          <div>
            <div style="float: left; margin-right: 5px;">
              <img src="' . $tranfer1 . '/comunidades/categorias/' . $posts['url'] . '.png" alt="" title="' . $posts['nomb2'] . '" class="png" />
            </div>
            <div>
              <a style="color: #D35F2C; font-weight: bold; font-size: 13px;" href="' . $boardurl . '/comunidades/' . $posts['url2'] . '/' . $posts['id'] . '/' . urls($tit2) . '.html" target="_self" title="' . $tit . '">' . $tit3 . '</a>
            </div>
          </div>
          <div class="size10">
            En
            <a href="' . $boardurl . '/comunidades/' . $posts['url2'] . '/" target="_self" title="' . $posts['nombre'] . '">' . $posts['nombre'] . '</a>
            por
            <a href="' . $boardurl . '/perfil/' . $posts['realName'] . '" target="_self" title="' . $posts['realName'] . '">' . $posts['realName'] . '</a>
          </div>
        </div>
        <div class="hrs"></div>';
    }

    $request = db_query("
      SELECT a.id
      FROM {$db_prefix}comunidades AS c, {$db_prefix}comunidades_articulos AS a, {$db_prefix}comunidades_categorias AS b
      WHERE a.id_com = c.id
      AND c.acceso <> 4
      AND a.eliminado = 0
      AND c.bloquear = 0
      AND c.categoria = b.id
      " . (empty($cat) ? '' : " AND b.url = '$cat'"), __FILE__, __LINE__);

    $NroRegistros = mysqli_num_rows($request);
    $PagAnt = $PagAct - 1;
    $PagSig = $PagAct + 1;
    $PagUlt = $NroRegistros / $RegistrosAMostrar;
    $Res = $NroRegistros % $RegistrosAMostrar;

    if ($Res > 0) {
      $PagUlt = floor($PagUlt) + 1;
    }

    if ($NroRegistros == 0) {
      echo '
        <div class="noesta">
          <br /><br /><br /><br />
          No se han creado temas recientemente.
          <br /><br /><br /><br /><br />
        </div>';
    }

    echo '</div>';

    $dadenlace = !$cat ? $boardurl . '/comunidades/pag-' : $boardurl . '/comunidades/categoria/' . $cat . '/pag-';

    if ($PagAct > $PagUlt) {
    } else {
      if ($PagAct > 1 || $PagAct < $PagUlt) {
        echo '<div class="windowbgpag" style="width: 378px;">';

        if ($PagAct > 1) {
          echo '<a href="' . $dadenlace . $PagAnt . '">&#171; anterior</a>';
        }

        if ($PagAct < $PagUlt) {
          echo '<a href="' . $dadenlace . $PagSig . '">siguiente &#187;</a>';
        }

        echo '</div>';
      }
    }

    echo '
          </div>
        </div>
      </div>
      <div style="float: left; margin-right: 8px;">
        <div style="margin-bottom: 8px; width: 363px;">
          <ul class="buscadorPlus">
            <li id="gb" class="activo" onclick="elegir(\'google\')">Temas</li>
            <li id="cwb" onclick="elegir(\'casitaweb\')">Comunidades</li>
          </ul>
          <div class="clearBoth"></div>
          <div style="margin-top: -1px; clear: both;">
            <form style="margin: 0px; padding: 0px;" action="' . $boardurl . '/buscar-com.php" method="get" accept-charset="' . $context['character_set'] . '">
              <input type="text" name="q" id="q" class="ibuscador" style="height: 32px;" />
              <input onclick="return errorrojos(this.form.q.value);" alt="" class="bbuscador png" title="Buscar" value=" " type="submit" align="top" style="height: 34px;" />
              <input name="buscador_tipo" value="g" checked="checked" type="hidden" />
            </form>
          </div>
        </div>
        <div class="act_comments">
          <div class="box_title" style="width: 361px;">
            <div class="box_txt ultimos_comments">&Uacute;ltimas comunidades creadas</div>
            <div class="box_rss">
              <img alt="" src="' . $tranfer1 . '/blank.gif" style="width: 16px; height: 16px;" border="0" />
            </div>
          </div>
          <div class="windowbg" style="padding: 4px; width: 353px; margin-bottom: 8px;">';

    $request = db_query("
      SELECT c.nombre, m.realName, c.url, ca.url AS categoria, ca.nombre AS nombre2, c.fecha_inicio
      FROM {$db_prefix}comunidades AS c, {$db_prefix}comunidades_categorias AS ca, {$db_prefix}members AS m
      WHERE c.id_user = m.ID_MEMBER
      AND c.categoria = ca.id
      AND c.bloquear = 0
      ORDER BY c.id DESC
      LIMIT 5", __FILE__, __LINE__);

    while ($row = mysqli_fetch_assoc($request)) {
      $row['nombre'] = nohtml(nohtml2($row['nombre']));

      echo '
        <div class="comunidad_tema">
          <div>
            <div style="float: left; margin-right: 5px;">
              <img src="' . $tranfer1 . '/comunidades/categorias/' . $row['categoria'] . '.png" alt="" title="' . $row['nombre2'] . '" />
            </div>
            <div>
              <a style="color: #D35F2C; font-weight: bold; font-size: 13px;" href="' . $boardurl . '/comunidades/' . $row['url'] . '/" target="_self" title="' . $row['nombre'] . '">' . $row['nombre'] . '</a>
            </div>
          </div>
          <div class="size10">
            Comunidad creada por
            <a href="' . $boardurl . '/perfil/' . $row['realName'] . '" target="_self" title="' . $row['realName'] . '">' . $row['realName'] . '</a>
            |
            ' . timeformat($row['fecha_inicio']) . '
          </div>
        </div>
        <div class="hrs"></div>';
    }

    mysqli_free_result($request);

    echo '
        </div>
      </div>';

    echo '
      <div class="act_comments">
        <div class="box_title" style="width: 361px;">
          <div class="box_txt ultimos_comments">&Uacute;ltimos comentarios</div>
          <div class="box_rss">
            <div style="height: 16px; width: 16px; cursor: pointer;" class="actualizarComents png">
              <img alt="Actualizar" onclick="actualizar_comentarios_com(); return false;" src="' . $tranfer1 . '/espacio.png" class="png" height="16px" width="16px" />
            </div>
          </div>
        </div>
        <div class="windowbg" style="padding: 4px; width: 353px; margin-bottom: 8px;">
          <span id="ult_comm">';

    $request = db_query("
      SELECT m.realName, t.titulo, t.id, co.url
      FROM {$db_prefix}comunidades_comentarios AS c, {$db_prefix}members AS m, {$db_prefix}comunidades_articulos AS t, {$db_prefix}comunidades AS co
      WHERE c.id_user = m.ID_MEMBER
      AND c.id_tema = t.id
      AND t.id_com = co.id
      AND co.bloquear = 0
      AND t.eliminado = 0
      AND co.acceso <> 4
      ORDER BY c.id DESC
      LIMIT 10", __FILE__, __LINE__);

    $rows = mysqli_num_rows($request);

    if ($rows == 0) {
      echo '<div class="noesta-am">No existen comentarios.</div>';
    } else {
      while ($row = mysqli_fetch_assoc($request)) {
        echo '
          <font class="size11">
            <b>
              <a href="' . $boardurl . '/perfil/' . $row['realName'] . '" target="_self" title="' . $row['realName'] . '">' . $row['realName'] . '</a>
            </b>
            -
            <a href="' . $boardurl . '/comunidades/' . $row['url'] . '/' . $row['id'] . '/' . urls($row['titulo']) . '.html" target="_self" title="' . $row['titulo'] . '">' . $row['titulo'] . '</a>
          </font>
          <br style="margin: 0px; padding: 0px;">';
      }
    }

    mysqli_free_result($request);

    echo '
          </span>
        </div>
      </div>
      <div class="act_comments">
        <div class="box_title" style="width: 361px;">
          <div class="box_txt ultimos_comments">Destacados</div>
          <div class="box_rss">
            <img alt="" src="' . $tranfer1 . '/blank.gif" style="width: 16px; height: 16px;" border="0" />
          </div>
        </div>
        <div class="windowbg" style="padding: 4px; width: 353px; margin-bottom: 8px;">
          <center>';

    destacado();

    echo '
              </center>
            </div>
          </div>
        </div>
      <div style="float: left;">';

    $request = db_query("
      SELECT url, imagen, id, nombre, cred_fecha
      FROM {$db_prefix}comunidades
      WHERE credito = 100
      AND bloquear = 0
      ORDER BY RAND()
      LIMIT 1", __FILE__, __LINE__);

    $row = mysqli_fetch_assoc($request);
    $id_destacado = isset($row['id']) ? $row['id'] : 0;
    $nombre_destacado = isset($row['nombre']) ? $row['nombre'] : '';
    $url_destacada = isset($row['url']) ? $row['url'] : '';
    $imagen_destacada = isset($row['imagen']) ? $row['imagen'] : '';
    $fecha_destacada = isset($row['cred_fecha']) ? $row['cred_fecha'] + 86400 : INF;

    if (time() > $fecha_destacada) {
      db_query("
        UPDATE {$db_prefix}comunidades
        SET credito = 0
        WHERE id = $id_destacado
        LIMIT 1", __FILE__, __LINE__);
    }

    mysqli_free_result($request);

    $imagen = $imagen_destacada ? $imagen_destacada : $no_avatar;

    if ($id_destacado) {
      echo '
        <div class="img_aletat">
          <div class="box_title" style="width: 163px;">
            <div class="box_txt img_aletat">Destacados</div>
            <div class="box_rss">
              <img alt="" src="' . $tranfer1 . '/blank.gif" style="width: 16pw; height: 16px;" border="0" />
            </div>
          </div>
          <div class="windowbg" style="padding: 4px; width: 155px; margin-bottom: 8px;">
            <center>
              <a href="' . $boardurl . '/comunidades/' . $url_destacada . '/" title="' . $nombre_destacado . '">
                <img src="' . $imagen . '" width="120px" height="120px" alt="" class="avatar" title="' . $nombre_destacado . '" onerror="error_avatar(this)" />
              </a>
              <br />
              <div class="hrs"></div>
              <a href="' . $boardurl . '/comunidades/' . $url_destacada . '/" title="' . $nombre_destacado . '">
                <b class="size15">' . $nombre_destacado . '</b>
              </a>
            </center>
          </div>
        </div>';
    }

    echo '
      <div class="MenuCascada" style="margin-bottom: 8px;">
        <div style="width: 165px;">
          <div>
            <a href="' . $boardurl . '/comunidades/dir">Directorios</a>
          </div>
        </div>
      </div>
      <div class="img_aletat">
        <div class="box_title" style="width: 163px;">
          <div class="box_txt img_aletat">Publicidad</div>
          <div class="box_rss">
            <img alt="" src="' . $tranfer1 . '/blank.gif" style="width: 16px; height: 16px;" border="0" />
          </div>
        </div>
        <div class="windowbg" style="padding: 4px; width: 155px; margin-bottom: 8px;">
          <center>';

    anuncio_160x600();

    echo '
            </center>
          </div>
        </div>
      </div>';
  } else {
    // Comunidad
    $entrar = entrar($context['ddddsaaat']);

    if ($entrar) {
      echo $entrar;
    } else {
      $context['ComUeliminado'] = isset($context['ComUeliminado']) ? $context['ComUeliminado'] : '';

      echo $context['ComUeliminado'];

      sidebar($context['url2222']);

      if (!$_GET['miembros']) {
        echo '
          <div style="margin-bottom: 8px; float: left;">
            <div class="box_title" style="width: 539px;">
              <div class="box_txt">' . $context['nombrecat'] . '</div>
              <div class="box_rss">
                <img alt="" src="' . $tranfer1 . '/blank.gif" style="width: 14px; height: 12px;" border="0" />
              </div>
            </div>
            <div class="windowbg" style="width: 531px; padding: 4px;">
              <table>
                <tr>
                  <td valign="top" style="padding: 4px; font-size: 13px;">
                    <b>Descripci&oacute;n:</b>
                  </td>
                  <td style="padding: 4px; width: 360px; white-space: pre-wrap; overflow: hidden; display: block; height: 100%; background-color: #FFF; border: solid 1px #D5CCC3;">' . $context['descecat'] . '</td>
                </tr>
                <tr>
                  <td valign="top" style="padding: 4px; font-size: 13px;">
                    <b>Categor&iacute;a:</b>
                  </td>
                  <td style="padding: 4px;">
                    <a href="' . $boardurl . '/comunidades/categoria/' . $context['caturl'] . '">' . $context['cat'] . '</a>
                  </td>
                </tr>
                <tr>
                  <td valign="top" style="padding: 4px; font-size: 13px;">
                    <b>Comunidad creada el:</b>
                  </td>
                  <td style="padding: 4px;" title="' . $context['fecha'] . '">' . $context['fecha'] . '</td>
                </tr>
                <tr>
                  <td valign="top" style="padding: 4px; font-size: 13px;">
                    <b>Due&ntilde;o:</b>
                  </td>
                  <td style="padding: 4px;" title="' . $context['UserName'] . '">
                    <a href="' . $boardurl . '/perfil/' . $context['UserName'] . '">' . $context['UserName'] . '</a>
                  </td>
                </tr>
              </table>
            </div>
            <div class="box_title" style="width: 539px; margin-top: 8px;">
              <div class="box_txt">Temas fijados</div>
              <div class="box_rss">
                <img alt="" src="' . $tranfer1 . '/blank.gif" style="width: 14px; height: 12px;" border="0" />
              </div>
            </div>
            <div class="windowbg" style="width: 531px; padding: 4px; margin-bottom: 4px;">';

        $rs = db_query("
          SELECT a.titulo, m.realName, c.url, a.id
          FROM {$db_prefix}comunidades_articulos AS a, {$db_prefix}comunidades AS c, {$db_prefix}members AS m
          WHERE c.url = '{$context['url2222']}'
          AND c.id = a.id_com
          AND a.stiky = 1
          AND m.ID_MEMBER = a.id_user
          AND a.eliminado = 0", __FILE__, __LINE__);

        $rows = mysqli_num_rows($rs);

        while ($row = mysqli_fetch_assoc($rs)) {
          echo '
            <div class="comunidad_tema">
              <div>
                <div style="float: left; margin-right: 5px;">
                  <img src="' . $tranfer1 . '/comunidades/temas_fijo.png" alt="" title="' . $row['titulo'] . '" />
                </div>
                <div>
                  <a style="color: #D35F2C; font-weight: bold; font-size: 13px;" href="' . $boardurl . '/comunidades/' . $row['url'] . '/' . $row['id'] . '/' . urls($row['titulo']) . '.html" target="_self" title="' . $row['titulo'] . '">' . $row['titulo'] . '</a>
                </div>
              </div>
              <div class="size10">
                Por
                <a href="' . $boardurl . '/perfil/' . $row['realName'] . '" target="_self" title="' . $row['realName'] . '">' . $row['realName'] . '</a>
              </div>
            </div>
            <div class="hrs"></div>';
        }

        if ($rows == 0) {
          echo '<div class="noesta">No hay temas fijados.</div>';
        }

        echo '</div>';

        if (!eaprobacion($context['ddddsaaat']) && ($context['puedo'] == 1 || $context['puedo'] == 3)) {
          echo '
            <p align="right" style="padding: 0px; margin: 0px;">
              <input onclick="javascript:window.location.href=\'' . $boardurl . '/comunidades/' . $context['url2222'] . '/crear-tema\'" alt="" class="comCrearTema" title="" value=" " type="submit" align="top" />
            </p>';
        }

        echo '
          <div class="box_title" style="width: 539px; margin-top: 4px;">
            <div class="box_txt">Temas</div>
            <div class="box_rss">
              <img alt="" src="' . $tranfer1 . '/blank.gif" style="width: 14px; height: 12px;" border="0" />
            </div>
          </div>
          <div class="windowbg" style="width: 531px; padding: 4px;">';

        $_GET['st'] = isset($_GET['st']) ? (int) $_GET['st'] : 0;
        $das = $_GET['st'] < 1 ? 0 : $_GET['st'];
        $st = isset($das) ? (int) $das : 0;
        $pp = 10;

        $request = db_query("
          SELECT a.titulo
          FROM {$db_prefix}comunidades_articulos AS a, {$db_prefix}comunidades AS c, {$db_prefix}members AS m
          WHERE c.url = '{$context['url2222']}'
          AND c.id = a.id_com
          AND m.ID_MEMBER = a.id_user
          AND a.eliminado = 0", __FILE__, __LINE__);

        $total = mysqli_num_rows($request);

        $rs44 = db_query("
          SELECT a.titulo, a.id, c.url, m.realName
          FROM {$db_prefix}comunidades_articulos AS a, {$db_prefix}comunidades AS c, {$db_prefix}members AS m
          WHERE c.url = '{$context['url2222']}'
          AND c.id = a.id_com
          AND m.ID_MEMBER = a.id_user
          AND a.eliminado = 0
          ORDER BY a.id DESC
          LIMIT $st, $pp", __FILE__, __LINE__);

        while ($row = mysqli_fetch_assoc($rs44)) {
          echo '
                <div class="comunidad_tema"><div>
                <div style="float: left; margin-right: 5px;">
                  <img src="' . $tranfer1 . '/comunidades/temas.png" alt="" title="' . $row['titulo'] . '" />
                </div>
                <div>
                  <a style="color: #D35F2C; font-weight: bold; font-size: 13px;" href="' . $boardurl . '/comunidades/' . $row['url'] . '/' . $row['id'] . '/' . urls($row['titulo']) . '.html" target="_self" title="' . $row['titulo'] . '">' . $row['titulo'] . '</a>
                </div>
              </div>
              <div class="size10">
                Por
                <a href="' . $boardurl . '/perfil/' . $row['realName'] . '" target="_self" title="' . $row['realName'] . '">' . $row['realName'] . '</a>
              </div>
            </div>
            <div class="hrs"></div>';
        }

        if ($total == 0) {
          echo '<div class="noesta">No hay temas creados.</div>';
        }

        echo '
            </div>
          <div style="width: 541px;">';

        echo paginacion($total, $pp, $st, $boardurl . '/comunidades/' . $context['url2222'] . '/pag-');

        echo '
            </div>
          </div>';
      } else if ($_GET['miembros'] == 3 && $context['allow_admin']) {
        if (!$context['ddddsaaat']) {
          echo '<div class="noesta" style="width: 541px; margin-bottom: 8px; float: left;">Esta comunidad no existe.</div>';
        } else {
          echo '
            <div style="margin-bottom:8px;float:left;">
              <div class="box_title" style="width: 539px;">
                <div class="box_txt">Administrar Comunidad</div>
                <div class="box_rss">
                  <img alt="" src="' . $tranfer1 . '/blank.gif" style="width: 14px; height: 12px;" border="0">
                </div>
              </div>
              <div class="windowbg" style="width: 531px; padding: 4px;">
                <form style="margin: 0px; padding: 0px;" action="' . $boardurl . '/web/cw-comunidadesAdmCom.php" method="POST" accept-charset="' . $context['character_set'] . '">
                  <table>
                    <tr>
                      <td style="width: 100px;">
                        <b>Comunidad:</b>
                      </td>
                      <td>' . $context['nombrecat'] . '</td>
                    </tr>
                  </table>
                  <div class="hrs"></div>';

          if (!$context['ivvvaar']) {
            echo '
              <table>
                <tr>
                  <td style="width: 100px;">
                    <b>Eliminar:</b>
                  </td>
                  <td>
                    <input name="eliminar" type="checkbox" />
                    <br />
                  </td>
                </tr>
                <tr>
                  <td style="width: 100px;">
                    <b>Raz&oacute;n:</b>
                  </td>
                  <td>
                    <input onfocus="foco(this);" onblur="no_foco(this);" title="Raz&oacute;n" value="" type="text" name="razon" />
                  </td>
                </tr>
              </table>';
          } else {
            echo '
              <table>
                <tr>
                  <td style="width:100px;">
                    <b>Restaurar comunidad:</b>
                  </td>
                  <td>
                    <input name="restaur" type="checkbox" />
                    <br />
                  </td>
                </tr>
              </table>';
          }

          echo '
                <div class="hrs"></div>
                    <p style="margin: 0px; margin: 0px;" align="right">
                      <input alt="" class="login" title="Aceptar" value="Aceptar" type="submit" />
                    </p>
                    <input name="comun" value="' . $context['ddddsaaat'] . '" type="hidden" />
                  </form>
                </center>
              </div>
              <div class="clearBoth"></div>
            </div>';
        }
      } else if ($_GET['miembros'] == 8) {
        if (!$context['user']['is_guest'] && !$context['permisoCom']) {
          echo '
            <div style="margin-bottom: 8px; float: left;">
              <div class="box_title" style="width: 539px;">
                <div class="box_txt">Denunciar comunidad</div>
                <div class="box_rss">
                  <img alt="" src="' . $tranfer1 . '/blank.gif" style="width: 14px; height: 12px;" border="0">
                </div>
              </div>
              <div class="windowbg" style="width: 531px; padding: 4px;">
                <form style="margin: 0px; padding: 0px;" action="' . $boardurl . '/web/cw-comunidadesDen.php" method="POST" accept-charset="' . $context['character_set'] . '">
                  <table>
                    <tr>
                      <td style="width: 100px;">
                        <b>Comunidad:</b>
                      </td>
                      <td>' . $context['nombrecat'] . '</td>
                    </tr>
                    <tr>
                      <td style="width: 100px;">
                        <b>Raz&oacute;n:</b>
                      </td>
                      <td>
                        <input value="" type="text" name="razon" onfocus="foco(this);" onblur="no_foco(this);" style="width: 300px;" /> 
                      </td>
                    </tr>
                    <tr>
                      <td style="width: 100px;">
                        <b>Comentario:</b>
                      </td>
                      <td>
                        <textarea onfocus="foco(this);" onblur="no_foco(this);" style="width: 300px;" name="comentario"></textarea>
                      </td>
                    </tr>
                  </table>
                  <input type="hidden" value="' . $context['ddddsaaat'] . '" name="comu" />
                  <p align="right" style="padding: 0px; margin: 0px;">
                    <input type="submit" class="login" value="Enviar" name="enviar" />
                  </p>
                </form>
              </div>
              <div class="clearBoth"></div>
            </div>';
        } else {
          echo '<div class="noesta" style="width: 541px; margin-bottom: 8px; float: left;">No puedes denunciar esta comunidad.</div>';
        }
      } else if ($_GET['miembros'] == 9) {
        if ($context['permisoCom'] == 1) {
          echo '
            <div style="margin-bottom: 8px; float: left;">
              <div class="box_title" style="width: 539px;">
                <div class="box_txt">Publicitar comunidad</div>
                <div class="box_rss">
                  <img alt="" src="' . $tranfer1 . '/blank.gif" style="width: 14px; height: 12px;" border="0" />
                </div>
              </div>
              <div class="windowbg" style="width: 531px; padding: 4px;">
                <ul>
                  <li>Para publicitar tu comunidadad tienes que tener 500 o m&aacute;s puntos.</li>
                  <li>La publicidad vale 100 puntos.</li>
                  <li>Estar&aacute; a la vista de todos durante 24HS.</li>
                  <form style="margin: 0px; padding: 0px;" action="' . $boardurl . '/web/cw-comunidadesPublicitar.php" method="POST" accept-charset="' . $context['character_set'] . '">
                    <input type="hidden" value="' . $context['ddddsaaat'] . '" name="id" />
                    <p align="right" style="padding: 0px; margin: 0px;">
                      <input type="submit" class="login" value="Publicitar" name="enviar" />
                    </p>
                  </form>
                </div>
                <div class="clearBoth"></div>
              </div>';
        } else {
          echo '<div class="noesta" style="width: 541px; margin-bottom: 8px; float: left;">No puedes publicitar esta comunidad.</div>';
        }
      } else {
        echo '<div class="noesta" style="width: 541px; margin-bottom: 8px; float: left;">Acci&oacute;n no conocida.</div>';
      }
      // ///////////////////////////////////////////////////////////////////////////////////
      // ///////////////////////////////////////////////////////////////////////////////////
      // ///////////////////////////////////////////////////////////////////////////////////
      // ///////////////////////////////////////////////////////////////////////////////////
      // ///////////////////////////////////////////////////////////////////////////////////
      // ///////////////////////////////////////////////////////////////////////////////////
      echo '
        <div style="float: left; margin-bottom: 8px; margin-left: 8px;">
          <div style="margin-bottom: 8px;">
            <div class="box_title" style="width: 201px;">
              <div class="box_txt box_perfil2-36">&Uacute;ltimos comentarios</div>
              <div class="box_rss">
                <img src="' . $tranfer1 . '/blank.gif" style="width: 16px; height: 16px;" border="0" alt="" />
              </div>
            </div>
            <div class="windowbg" style="width: 193px; padding: 4px;">
              <span id="ult_comm">';

      $rs44 = db_query("
        SELECT m.realName, a.titulo, c.id, c.id_tema, co.url
        FROM {$db_prefix}members AS m, {$db_prefix}comunidades_comentarios AS c, {$db_prefix}comunidades AS co, {$db_prefix}comunidades_articulos AS a
        WHERE c.id_com = {$context['ddddsaaat']}
        AND a.id = c.id_tema
        AND c.id_user = m.ID_MEMBER
        AND c.id_com = co.id
        AND a.eliminado = 0
        ORDER BY c.id DESC
        LIMIT 10", __FILE__, __LINE__);

      $rows = mysqli_num_rows($rs44);

      while ($row = mysqli_fetch_assoc($rs44)) {
        $title = strlen($row['titulo']) > 20 ? substr($row['titulo'], 0, 17) . '...' : $row['titulo'];

        echo '
          <font class="size11">
            <b>
              <a href="' . $boardurl . '/perfil/' . $row['realName'] . '" title="' . $row['realName'] . '">' . $row['realName'] . '</a>
            </b>
            -
            <a title="' . $row['titulo'] . '" href="' . $boardurl . '/comunidades/' . $row['url'] . '/' . $row['id_tema'] . '/' . urls($row['titulo']) . '.html#comentarios">' . $title . '</a>
          </font>
          <br style="margin: 0px; padding: 0px;">';
      }

      if ($rows == 0) {
        echo '<div class="noesta">No hay nuevos comentarios.</div>';
      }

      echo '
            </span>
          </div>
        </div>
        <div style="margin-bottom: 8px;">
          <div class="box_title" style="width: 201px;">
            <div class="box_txt box_perfil2-36">&Uacute;ltimos miembros</div>
            <div class="box_rss">
              <img alt="" src="' . $tranfer1 . '/blank.gif" style="width: 14px; height: 12px;" border="0" />
            </div>
          </div>
          <div class="windowbg" style="width: 193px; padding: 4px;">';

      $rs44 = db_query("
        SELECT m.realName, c.fecha
        FROM {$db_prefix}members AS m, {$db_prefix}comunidades_miembros AS c
        WHERE c.id_com = {$context['ddddsaaat']}
        AND c.id_user = m.ID_MEMBER
        AND c.aprobado = 1
        ORDER BY c.id DESC
        LIMIT 10", __FILE__, __LINE__);

      $rows = mysqli_num_rows($rs44);

      while ($row = mysqli_fetch_assoc($rs44)) {
        echo '
          <font class="size11">
            <b>
              <a href="' . $boardurl . '/perfil/' . $row['realName'] . '" title="' . $row['realName'] . '">' . $row['realName'] . '</a>
            </b>
            -
            ' . hace($row['fecha']) . '
          </font>
          <br style="margin: 0px; padding: 0px;">';
      }

      if ($rows == 0) {
        echo '<div class="noesta">No hay nuevos miembros.</div>';
      }

      echo '
          </div>
        </div>';

      $rs = db_query("
        SELECT url, imagen, id, nombre, cred_fecha
        FROM {$db_prefix}comunidades
        WHERE credito = 100
        AND bloquear = 0
        ORDER BY RAND()
        LIMIT 1", __FILE__, __LINE__);

      $row = mysqli_fetch_assoc($rs);
      $imagen_destacada = isset($row['imagen']) ? $row['imagen'] : '';
      $url_destacada = isset($row['url']) ? $row['url'] : '';
      $nombre_destacado = isset($row['nombre']) ? $row['nombre'] : '';
      $id_destacado = isset($row['id']) ? $row['id'] : '';
      $fecha_destacada = isset($row['cred_fecha']) ? $row['cred_fecha'] + 86400 : INF;

      if (time() > $fecha_destacada) {
        db_query("
          UPDATE {$db_prefix}comunidades
          SET credito = 0
          WHERE id = '$id_destacado'
          LIMIT 1", __FILE__, __LINE__);
      }

      mysqli_free_result($rs);

      $img2 = $imagen_destacada ? $imagen_destacada : $no_avatar;

      if ($id_destacado) {
        echo '
          <div style="margin-bottom: 8px;">
            <div class="box_title" style="width: 201px;">
              <div class="box_txt box_perfil2-36">Destacados</div>
              <div class="box_rss">
                <img alt="" src="' . $tranfer1 . '/blank.gif" style="width: 14px; height: 12px;" border="0" />
              </div>
            </div>
            <div class="windowbg" style="width: 193px; padding: 4px;">
              <center>
                <a href="' . $boardurl . '/comunidades/' . $url_destacada . '/" title="' . $nombre_destacado . '">
                  <img src="' . $img2 . '" width="120px" height="120px" alt="" class="avatar" title="' . $nombre_destacado . '" onerror="error_avatar(this)" />
                </a>
                <br />
                <div class="hrs"></div>
                <a href="' . $boardurl . '/comunidades/' . $url_destacada . '/" title="' . $nombre_destacado . '">
                  <b class="size15">' . $nombre_destacado . '</b>
                </a>
              </center>
            </div>
          </div>';
      }

      echo '</div>';
    }
  }
}

// Artículo o Tema
function template_articulo() {
  global $context, $ID_MEMBER, $db_prefix, $user_info, $tranfer1, $sourcedir, $no_avatar, $boardurl;

  if (entrar($context['coMdasdasd'])) {
    echo entrar($context['coMdasdasd']);
  } else {
    echo '<div style="margin-bottom: 8px; width: 922px;">';

    arriba('tema', $boardurl . '/comunidades/categoria/' . $context['coMurl2'], $context['coMcnam'], $boardurl . '/comunidades/' . $context['coMurl'], $context['coMnombre'], $context['coMtitulo']);

    $img2 = $context['coMimg'] ? $context['coMimg'] : $no_avatar;
    $stle = $context['coMcalificacion'] > -1 ? 'ok' : 'error';
    $sigs = $context['coMcalificacion'] > -1 ? '+' : '';
    $context['postEliAdm'] = isset($context['postEliAdm']) ? $context['postEliAdm'] : '';

    echo $context['postEliAdm'];

    sidebar($context['coMurl']);

    echo '<div style="float: left;">';

    // Inicio Post
    echo '
      <div class="post-com">
        <div class="post-user">
          <table style="padding: 0px; margin: 0px;">
            <tr style="padding: 0px; margin: 0px;">
              <td valign="top">
                <a href="' . $boardurl . '/perfil/' . $context['coMrealName'] . '" title="' . $context['coMrealName'] . '">
                  <img src="' . $img2 . '" width="100px" height="100px" alt="" class="avatar" onerror="error_avatar(this)" />
                </a>
              </td>
              <td valign="top" style="width: 160px;">';

    $rs444 = db_query("
      SELECT rango
      FROM {$db_prefix}comunidades_miembros
      WHERE id_user = {$context['coMvbvbvki']}
      AND id_com = {$context['coMdasdasd']}
      LIMIT 1", __FILE__, __LINE__);

    $row3 = mysqli_fetch_assoc($rs444);
    $context['rangoos'] = isset($row3['rango']) ? $row3['rango'] : '';
    $rango = ranguear($context['rangoos'], $context['coMdasdasd']);
    $rangoIMG = ranguearIMG($context['rangoos'], $context['coMdasdasd']);

    echo '
      <b class="size15">
        <a href="' . $boardurl . '/perfil/' . $context['coMrealName'] . '" title="' . $context['coMrealName'] . '">' . $context['coMrealName'] . '</a>
      </b>
      <br />
      ' . $rangoIMG . ' ' . $rango;

    if (!$user_info['is_guest']) {
      echo '
        <br />
        <a href="' . $boardurl . '/web/cw-TEMPenviarMP.php?user=' . $context['coMrealName'] . '" title="Enviar MP a ' . $context['coMrealName'] . '" class="boxy">
          <img src="' . $tranfer1 . '/icons/mensaje_para.gif" alt="" />
          Enviar mensaje privado
        </a>
        <br />';
    }

    echo '
      </td>
      <td>';

    echo anuncio_468x60();

    echo '
            </td>
          </tr>
        </table>
      </div>
      <div style="padding: 6px;">
        <div style="padding: 0px; margin:0px;">
          <div style="float: left; padding-left: 3px;">
            <img src="' . $tranfer1 . '/comunidades/temas.png" alt="" />
          </div>
          <div style="font-size: 15px; padding-left: 2px;">
            <a href="' . $boardurl . '/comunidades/' . $context['coMurl'] . '/' . $context['coMid'] . '/' . urls($context['coMtitulo2']) . '.html" title="' . $context['coMtitulo'] . '">' . $context['coMtitulo'] . '</a>
          </div>
        </div>
        <div style="border: 1px solid #D35F2C; background: #D35F2C; height: 1px;" class="hrs"></div>
      </div>
      <div class="post-contenido" property="dc:content" id="post_' . $context['coMid'] . '">
        ' . $context['coMcuerpo'] . '
        <div id="social"></div>
      </div>
      <div class="post-datos">
        <table align="center">
          <tr>
            <td style="width: 230px;">
              <b>Calificar:</b>
            </td>
            <td style="width: 200px;">
              <b>Creado:</b>
            </td>
            <td style="width: 100px;">
              <b>Visitas:</b>
            </td>
          </tr>
          <tr>
            <td>
              <span id="votos_total2">
                <a href="javascript:com.tema_votar(1,' . $context['coMid'] . ')" class="thumbs thumbsUp" title="Votar positivo"></a>
                <a href="javascript:com.tema_votar(-1,' . $context['coMid'] . ')" class="thumbs thumbsDown" title="Votar negativo"></a>
              </span>
              <span id="votos_total" class="' . $stle . '">' . $sigs . $context['coMcalificacion'] . '</span>
            </td>
            <td>
              <span style="font-size: 11px;" title="' . $context['coMcreado'] . '">' . $context['coMcreado'] . '</span>
            </td>
            <td>
              <span style="font-size: 11px;">' . $context['coMvisitas'] . '</span>
            </td>
            <div class="clearBoth"></div>
          </tr>
        </table>
      </div>
    </div>
    <div style="width: 752px; margin-bottom: 30px; margin-top: 3px;">
      <div style="float: left;">
        ' . ($context['coMnocoment'] ? '<img src="' . $tranfer1 . '/comunidades/cerrado.png" alt="" title="Tema cerrado" /> ' : '') . '
        ' . ($context['coMstiky'] ? '<img src="' . $tranfer1 . '/comunidades/fijado.png" alt="" title="Tema fijado" /> ' : '') . '
      </div>
      <div style="float: right;">
        ' . (in_array($context['permisoCom'], [1, 2, 3]) || $context['coMvbvbvki'] == $ID_MEMBER ? '<p align="right" style="margin: 0px; padding: 0px;">' . $context['botonesCom'] . '</p>' : '') . '
      </div>
      <div class="clearBoth"></div>
    </div>';
    // Fin Post

    $request = db_query("
      SELECT id
      FROM {$db_prefix}comunidades_comentarios
      WHERE id_tema = {$context['coMid']}", __FILE__, __LINE__);

    $cant = mysqli_num_rows($request);

    echo '
      <div style="margin-bottom: 5px;">
        <div style="float: left; margin-right: 5px;">
          <a href="' . $boardurl . '/rss/temas-comment/' . $context['coMid'] . '">
            <div style="height: 16px; width: 16px; cursor: pointer;" class="feed png">
              <img alt="" src="' . $tranfer1 . '/espacio.gif" class="png" height="16px" width="16px" />
            </div>
          </a>
        </div>
        <div>
          <b style="font-size: 14px;">Comentarios (<span id="nrocoment">' . $cant . '</span>)</b>
        </div>
      </div>
      <div id="comentarios">
        <div class="post-com">';

    $rs443 = db_query("
      SELECT com.comentario, m.realName, com.id, com.fecha, m.ID_MEMBER
      FROM {$db_prefix}members AS m, {$db_prefix}comunidades_comentarios AS com
      WHERE com.id_tema = {$context['coMid']}
      AND com.id_user = m.ID_MEMBER
      ORDER BY com.id ASC", __FILE__, __LINE__);

    while ($row = mysqli_fetch_assoc($rs443)) {
      $dasd = $row['id'];
      $comene = parse_bbc($row['comentario']);
      $comene2 = $row['comentario'];

      echo '
        <div class="User-Coment">
          <div style="float: left;">
            <span class="size11">
              <b id="autor_cmnt_' . $dasd . '" user_comment="' . $row['realName'] . '" text_comment="' . $comene2 . '">
                <a href="' . $boardurl . '/perfil/' . $row['realName'] . '" title="' . $row['realName'] . '" style="color: #956100;">' . $row['realName'] . '</a>
              </b>
              |
              ' . hace($row['fecha']) . '
              dijo:
            </span>
          </div>
          <div style="float: right;">
            ' . (!$user_info['is_guest'] ? '<span onclick="Boxy.load(\'' . $boardurl . '/web/cw-TEMPenviarMP.php?user=' . $row['realName'] . '\', { title: \'Enviar MP a ' . $row['realName'] . '\' })" title="Enviar MP a ' . $row['realName'] . '" class="pointer"><img src="' . $tranfer1 . '/icons/mensaje_para.gif" alt="" /></span>' : '') . '
            ' . (!$context['coMnocoment'] && in_array($context['puedo'], [1, 2, 3]) ? ' <span onclick="citar_comment(' . $dasd . ')" title="Citar comentario" class="pointer"><img src="' . $tranfer1 . '/comunidades/respuesta.png" class="png" alt="" /></span>' : '') . '
            ' . (in_array($context['permisoCom'], [1, 2, 3]) || $row['ID_MEMBER'] == $ID_MEMBER ? ' <a href="' . $boardurl . '/web/cw-comunidadesEliCom.php?id=' . $dasd . '" title="Eliminar comentario" onclick="if (!confirm(\'\xbfEst&aacute;s seguro que deseas eliminar este comentario?\')) return false;"><img src="' . $tranfer1 . '/comunidades/eliminar.png" class="png" alt="" /></a>' : '') . '
          </div>
          <div style="clear:both"></div>
        </div>
        <div class="post-comentCont">
          ' . $comene . '
          <div class="clearBoth"></div>
        </div>';
    }

    echo '<div id="nuevocrio" style="display: none;"></div>';

    $dasd = isset($dasd) ? $dasd : '';

    echo '
          <div class="coment-user"' . (!empty($dasd) ? ' style="display: none;" ' : '') . '>
            <div class="noesta">Este tema no tiene comentarios.</div>
          </div>
        </div>
      </div>';

    // Comentar
    if (!$context['coMnocoment']) {
      if (in_array($context['puedo'], [1, 2, 3])) {
        echo '
          <div style="margin-bottom: 5px; margin-top: 25px;" id="comentar">
            <b style="font-size: 14px;">Agregar un nuevo comentario</b>
          </div>
          <div style="margin-bottom: 5px;">
            <div class="msg_comentar" style="display: none; margin-bottom: 2px;"></div>
              <form name="nuevocoment">';

        textaer();

        echo '
                <br />
                <input class="login" type="button" id="button_comentar" value="Enviar comentario" onclick="ComComentar(' . $context['coMid'] . '); return false;" tabindex="2" />
              </p>
              <div style="display: none; text-align: right;" id="gif_cargando_add_comment">
                <img src="' . $tranfer1 . '/icons/cargando.gif" alt="" />
              </div>
            </form>
          </div>';
      }
    }

    echo '
        </div>
      </div>';
  }
}

// Crear comunidad
function template_crearcomunidad() {
  global $tranfer1, $mbname, $ID_MEMBER, $modSettings, $user_settings, $context, $sourcedir, $db_prefix, $boardurl;

  if (!$ID_MEMBER) {
    fatal_error('Funcionalidad exclusiva de usuarios registrados.');
  }

  require_once($sourcedir . '/FuncionesCom.php');

  arriba('CrearCom');

  if (!$user_settings['ID_GROUP']) {
    if ($user_settings['ID_POST_GROUP'] == 4) {
      $cantidadcom = 1;
    } else if ($user_settings['ID_POST_GROUP'] == 5) {
      $cantidadcom = 2;
    } else if ($user_settings['ID_POST_GROUP'] == 9) {
      $cantidadcom = 4;
    } else if ($user_settings['ID_POST_GROUP'] == 10) {
      $cantidadcom = 6;
    } else if ($user_settings['ID_POST_GROUP'] == 6) {
      $cantidadcom = 8;
    } else if ($user_settings['ID_POST_GROUP'] == 8) {
      $cantidadcom = 10;
    }
  } else {
    $cantidadcom = 15;
  }

  $request = db_query("
    SELECT id_user
    FROM {$db_prefix}comunidades
    WHERE id_user = $ID_MEMBER
    AND bloquear = 0", __FILE__, __LINE__);

  $cuantascom = mysqli_num_rows($request);

  echo '
    <div style="width: 354px; float: left; margin-right: 8px;">
      <div class="box_354" style="margin-bottom: 8px;">
        <div class="box_title" style="width: 352px;">
          <div class="box_txt box_354-34">Importante</div>
          <div class="box_rss">
            <div class="icon_img">
              <img src="' . $tranfer1 . '/blank.gif" style="width: 16px; height: 16px;" border="0" alt="" />
            </div>
          </div>
        </div>
        <div style="width: 344px; padding: 4px;" class="windowbg">';

  reglas_com('crearc');

  echo '
      </div>
    </div>
    <div class="noesta-am" style="margin-bottom: 8px;">Tienes ' . ($cantidadcom - $cuantascom) . ' comunidades disponibles para crear</div>
    <div class="box_354">
      <div class="box_title" style="width: 352px;">
        <div class="box_txt box_354-34">Destacados</div>
        <div class="box_rss">
          <div class="icon_img">
            <img src="' . $tranfer1 . '/blank.gif" style="width: 16px; height: 16px;" border="0" alt="" />
          </div>
        </div>
      </div>
      <div style="width: 344px; padding: 4px;" class="windowbg">';

  anuncio_300x250();

  echo '
      </div>
    </div>
  </div>
  <div style="width: 560px; float: left;">
    <div class="box_560">
      <div class="box_title" style="width: 558px;">
        <div class="box_txt box_560-34">Crear nueva comunidad</div>
        <div class="box_rss">
          <div class="icon_img">
            <img src="' . $tranfer1 . '/blank.gif" style="width: 16px; height: 16px;" border="0" alt="" />
          </div>
        </div>
      </div>
      <div class="windowbg" style="width: 550px; padding: 4px;">
        <form name="add_comunidad" method="post" action="' . $boardurl . '/web/cw-comunidadesCrear.php">
          <div class="form-container">
            <div class="dataL">
              <label for="uname">Nombre de la comunidad</label>
              <input onfocus="foco(this);" onblur="no_foco(this);" class="c_input" value="" name="nombre" tabindex="1" datatype="text" dataname="Nombre" type="text" />
            </div>
            <div class="dataR">
              <label for="uname" style="float: left;">Nombre corto</label>
              <span class="gif_cargando" id="shortname" style="top: 0px; float: right; display: none;">
                <img src="' . $tranfer1 . '/icons/cargando.gif" alt="" />
              </span>
              <input onfocus="foco(this);" class="c_input" value="" name="shortname" tabindex="2" onkeyup="com.crear_shortname_key(this.value)" onblur="no_foco(this);com.crear_shortname_check(this.value)" datatype="text" dataname="Nombre corto" style="width: 254px;" type="text" />
              <div class="desform">
                URL de la comunidad:
                <br />
                <strong>
                  ' . $boardurl . '/comunidades/<span id="preview_shortname"></span>
                </strong>
              </div>
              <span id="msg_crear_shortname"></span>
            </div>
            <div class="clearBoth"></div>
            <div class="dataL">
              <label for="uname">Imagen</label>
              <input onfocus="foco(this);" onblur="no_foco(this);" class="c_input" value="http://" name="imagen" tabindex="3" datatype="url" dataname="Imagen" type="text" />
            </div>';

  $request = db_query("
    SELECT url, nombre
    FROM {$db_prefix}comunidades_categorias
    ORDER BY nombre ASC", __FILE__, __LINE__);

  echo '
    <div class="dataR">
      <span class="gif_cargando floatR" id="subcategoria" style="top: 0px;"></span>
      <label for="fname">Categoria</label>
      <select style="width: 264px; margin-top: 5px; height: 25px; vertical-align: middle;" name="categoria">
        <option value="-1" selected="true">Elegir una categor&iacute;a</option>';

  while ($row = mysqli_fetch_assoc($request)) {
    echo '<option value="' . $row['url'] . '">' . $row['nombre'] . '</option>';
  }

  echo '
              </select>
              </div>
              <div class="clearBoth"></div>
              <div class="data">
                <label for="uname">Descripci&oacute;n</label>
                <textarea onfocus="foco(this);" onblur="no_foco(this);" class="c_input_desc autogrow" style="display: block; width: 540px;" name="descripcion" tabindex="7" datatype="text" dataname="Descripcion"></textarea>
              </div>
            </div>
            <div style="clear: both; " class="hrs"></div>
            <div style="margin-bottom:8px;">
              <div class="titlesCom" onclick="DesplComOps(\'acceso\', \'fname\', \'aprobar\'); return false;" id="dev_acceso">
                <label for="lname">
                  <b>Acceso</b>
                </label>
              </div>
              <div class="titlesCom2" onclick="DesplComOps2(\'acceso\'); return false;" id="dov_acceso" style="display: none;">
                <label for="lname">
                  <b>Acceso</b>
                </label>
              </div>
              <div class="postLabel" style="display: none;" id="div_acceso">
                <fieldset>
                  <legend>
                    <label for="privada_1" class="tit_lab">Todos</label>
                  </legend>
                  <table>
                    <tr>
                      <td style="width: 18px; padding: 0px; margin: 0px;">
                        <input name="privada" id="privada_1" value="1" checked="checked" tabindex="9" type="radio" />
                      </td>
                      <td>
                        <p class="descRadio">Toda persona que entra a ' . $mbname . ' tiene la posibilidad de entrar y ver el contenido de tu comunidad.</p>
                      </td>
                    </tr>
                  </table>
                </fieldset>
                <fieldset>
                  <legend>
                    <label for="privada_2" class="tit_lab">S&oacute;lo usuarios registrados</label>
                  </legend>
                  <table>
                    <tr>
                      <td style="width: 18px; padding: 0px; margin: 0px;">
                        <input name="privada" id="privada_2" value="2" type="radio" />
                      </td>
                      <td>
                        <p class="descRadio">Todo aquel que no est&eacute; registrado en ' . $mbname . ' no podr&aacute; ver el contenido de tu comunidad.</p>
                      </td>
                    </tr>
                  </table>
                </fieldset>
                <fieldset>
                  <legend>
                    <label for="privada_3" class="tit_lab">S&oacute;lo Miembros</label>
                  </legend>
                  <table>
                    <tr>
                      <td style="width: 18px; padding: 0px; margin: 0px;">
                        <input name="privada" id="privada_3" value="3" type="radio" />
                      </td>
                      <td>
                        <p class="descRadio">Todo aquel que no est&eacute; unido en la comunidad no podr&aacute; ver el contenido de tu comunidad.</p>
                      </td>
                    </tr>
                  </table>
                </fieldset> 
                <fieldset>
                  <legend>
                    <label for="privada_4" class="tit_lab">S&oacute;lo Miembros/Oculta</label>
                  </legend>
                  <table>
                    <tr>
                      <td style="width: 18px; padding: 0px; margin: 0px;">
                        <input name="privada" id="privada_4" value="4" type="radio" />
                      </td>
                      <td>
                        <p class="descRadio">Todo aquel que no est&eacute; unido en la comunidad no podr&aacute; ver el contenido de tu comunidad. No se mostrar&aacute;n los &uacute;ltimos temas creados en el centro de comunidades.</p>
                      </td>
                    </tr>
                  </table>
                </fieldset> 
              </div>
            </div>
            <div style="margin-bottom: 8px;">
              <div class="titlesCom" onclick="DesplComOps(\'aprobar\', \'acceso\', \'fname\'); return false;" id="dev_aprobar">
                <label for="aprobar">
                  <b>Aprobaci&oacute;n de miembros</b>
                </label>
              </div>
              <div class="titlesCom2" onclick="DesplComOps2(\'aprobar\'); return false;" id="dov_aprobar" style="display: none;">
                <label for="lname">
                  <b>Aprobaci&oacute;n de miembros</b>
                </label>
              </div>
              <div class="postLabel" style="display: none;" id="div_aprobar">
                <fieldset>
                  <legend>
                    <label for="automatica" class="tit_lab">Autom&aacute;tica</label>
                  </legend>
                  <table>
                    <tr>
                      <td style="width: 18px; padding: 0px; margin: 0px;">
                        <input name="aprobar" id="automatica" value="0" checked="checked" tabindex="9" type="radio" />
                      </td>
                      <td>
                        <p class="descRadio">Cuando una persona se une a la comunidad, nadie deber&aacute; aprobarlo.</p>
                      </td>
                    </tr>
                  </table>
                </fieldset>
                <fieldset>
                  <legend>
                    <label for="manual" class="tit_lab">Manual</label>
                  </legend>
                  <table>
                    <tr>
                      <td style="width: 18px; padding: 0px; margin: 0px;">
                        <input name="aprobar" id="manual" value="1" type="radio" />
                      </td>
                      <td>
                        <p class="descRadio">Cuando una persona se une a la comunidad, deber&aacute; esperar a que el administrador de tal lo apruebe.</p>
                      </td>
                    </tr>
                  </table>
                </fieldset>
              </div>
            </div>
            <div class="titlesCom" onclick="DesplComOps(\'fname\', \'aprobar\', \'acceso\'); return false;" id="dev_fname">
              <label for="fname">
                <b>Permisos</b>
              </label>
            </div>
            <div class="titlesCom2" onclick="DesplComOps2(\'fname\'); return false;" id="dov_fname" style="display: none;">
              <label for="lname">
                <b>Permisos</b>
              </label>
            </div>
            <div class="postLabel" style="display: none;" id="div_fname">
              <fieldset>
                <legend>
                  <label for="permisos_1" class="tit_lab">Posteador</label>
                </legend>
                <table>
                  <tr>
                    <td style="width: 18px; padding: 0px; margin: 0px;">
                      <input name="rango_default" id="permisos_1" value="3" checked="checked" tabindex="11" checked="checked" type="radio" />
                    </td>
                    <td>
                      <p class="descRadio">Los usuarios al ingresar en tu comunidad podr&aacute;n comentar y crear temas.</p>
                    </td>
                  </tr>
                </table>
              </fieldset>
              <fieldset>
                <legend>
                  <label for="permisos_2" class="tit_lab">Comentador</label>
                </legend>
                <table>
                  <tr>
                    <td style="width: 18px; padding: 0px; margin: 0px;">
                      <input name="rango_default" id="permisos_2" value="2" type="radio" />
                    </td>
                    <td>
                      <p class="descRadio">Los usuarios al participar en tu  comunidad s&oacute;lo podr&aacute;n comentar pero no estar&aacute;n habilitados para crear nuevos temas.</p>
                    </td>
                  </tr>
                </table>
              </fieldset>
              <fieldset>
                <legend>
                  <label for="permisos_3" class="tit_lab">Visitante</label>
                </legend>
                <table>
                  <tr>
                    <td style="width: 18px; padding: 0px; margin: 0px;">
                      <input name="rango_default" id="permisos_3" value="4" type="radio" />
                    </td>
                    <td>
                      <p class="descRadio">Los usuarios al participar en tu comunidad no podr&aacute;n comentar ni tampoco crear temas.</p>
                    </td>
                  </tr>
                </table>
              </fieldset>
              <fieldset>
                <legend class="tit_lab">Nota:</legend>
                <p class="descRadio">
                  El permiso seleccionado se le asignar&aacute; autom&aacute;ticamente al usuario que se haga miembro, sin embargo, podr&aacute;s modificar el permiso a cada usuario especifico.
                </p>
              </fieldset>
            </div>
            <div style="clear: both; margin-bottom: 15px; margin-top: 20px;" class="hrs"></div>
            <div id="buttons" align="right">
              <input tabindex="14" title="Crear comunidad" value="Crear comunidad" class="login" name="Enviar" type="submit" />
            </div>
          </form>
        </div>
      </div>
    </div>';
}

// Crear tema
function template_ctema() {
  global $tranfer1, $ID_MEMBER, $sourcedir, $modSettings, $context, $db_prefix, $boardurl;

  if (!$ID_MEMBER) {
    fatal_error('Funcionalidad exclusiva de usuarios registrados.');
  }

  require_once($sourcedir . '/FuncionesCom.php');

  $id = isset($_GET['comun']) ? seguridad($_GET['comun']) : '';

  if (!$id) {
    fatal_error('Debes seleccionar una comunidad.');
  }

  $request = db_query("
    SELECT c.nombre, b.rango, c.id, c.url, ca.url AS url_categoria, ca.nombre AS nombre_categoria
    FROM {$db_prefix}comunidades_miembros AS b, {$db_prefix}comunidades AS c, {$db_prefix}comunidades_categorias AS ca
    WHERE c.url = '$id'
    AND c.id = b.id_com
    AND b.id_user = $ID_MEMBER
    AND c.bloquear = 0
    AND c.categoria = ca.id
    LIMIT 1", __FILE__, __LINE__);

  $row = mysqli_fetch_assoc($request);
  $cat = seguridad(nohtml($row['nombre']));
  $rango = $row['rango'];
  $id_comunidad = $row['id'];

  arriba('CrearTema', $boardurl . '/comunidades/categoria/' . $row['url_categoria'], $row['nombre_categoria'], $boardurl . '/comunidades/' . $row['url'] . '/', $cat);

  if (!$cat) {
    fatal_error('No eres miembro de esta comunidad.');
  }

  acces($id_comunidad);

  if ($context['puedo'] == 1 || $context['puedo'] == 3) {
    echo '
      <div style="width: 354px; float: left; margin-right: 8px;">
        <div class="box_354" style="margin-bottom: 8px;">
          <div class="box_title" style="width: 352px;">
            <div class="box_txt box_354-34">Importante</div>
            <div class="box_rss">
              <div class="icon_img">
                <img src="' . $tranfer1 . '/blank.gif" style="width: 16px; height: 16px;" border="0" alt="" />
              </div>
            </div>
          </div>
          <div style="width: 344px; padding: 4px;" class="windowbg">';

    reglas_com('creart');

    echo '
        </div>
      </div>
      <div class="box_354">
        <div class="box_title" style="width: 352px;">
          <div class="box_txt box_354-34">Destacados</div>
          <div class="box_rss">
            <div class="icon_img">
              <img src="' . $tranfer1 . '/blank.gif" style="width: 16px; height: 16px;" border="0" alt="" />
            </div>
          </div>
        </div>
        <div style="width: 344px; padding: 4px;" class="windowbg">';

    anuncio_300x250();

    echo '
          </div>
        </div>
      </div>
      <div style="width: 560px; float: left;">
        <div class="box_560">
          <div class="box_title" style="width: 558px;">
            <div class="box_txt box_560-34">Crear nuevo tema</div>
            <div class="box_rss">
              <div class="icon_img">
                <img src="' . $tranfer1 . '/blank.gif" style="width: 16px; height: 16px;" border="0" alt="" />
              </div>
            </div>
          </div>
          <div class="windowbg" style="width: 550px; padding: 4px;">
            <form name="add_comunidad" id="nuevocoment" method="post" action="' . $boardurl . '/web/cw-comunidadesCrearTema.php">
              <div class="form-container">
                <label for="uname">Titulo:</label>
                <input style="width:540px;" onfocus="foco(this);" onblur="no_foco(this);" class="c_input" value="" name="titulo" tabindex="1" datatype="text" dataname="Titulo" type="text" />
                <div class="clearBoth"></div>
                <div class="data">
                  <label for="uname">Cuerpo:</label>
                  <textarea style="height: 300px; width: 544px;" id="editorCW" name="cuerpo_comment" tabindex="3"></textarea>';

    textaer(1);

    echo '
                </div>
                <div class="clearBoth"></div>
                <br />
                <fieldset style="width: 200px;">
                  <legend>
                    <span class="tit_lab">Opciones</span>
                  </legend>
                  ' . ($context['puedo'] == 3 ? '<label for="sticky"><input name="sticky" id="sticky" value="1" type="checkbox" /> Fijar</label>' : '') . '
                  <label for="nocoment">
                    <input name="nocoment" id="nocoment" value="1" type="checkbox" />
                    No permitir comentarios
                  </label>
                </fieldset> 
                <input name="comun" value="' . $id . '" type="hidden" />
              </div>
              <div style="clear: both; margin-bottom: 15px; margin-top: 20px;" class="hrs"></div>
              <div id="buttons" align="right">
                <input tabindex="14" title="Crear tema" value="Crear tema" class="login" name="Enviar" type="submit"/>
              </div>
            </form>
          </div>
        </div>
      </div>';
  } else {
    fatal_error('No puedes crear nuevos temas.');
  }
}

// Editar tema
function template_etema() {
  global $tranfer1, $ID_MEMBER, $sourcedir, $modSettings, $context, $db_prefix, $boardurl;

  if (!$ID_MEMBER) {
    fatal_error('Funcionalidad exclusiva de usuarios registrados.');
  }

  require_once($sourcedir . '/FuncionesCom.php');

  $id = isset($_GET['comun']) ? (int) $_GET['comun'] : 0;

  if (!$id) {
    fatal_error('Debes seleccionar el tema.');
  }

  $rs = db_query("
    SELECT c.nombre, a.titulo, a.id_com, a.id_user, a.cuerpo, a.id, a.nocoment, c.url, a.stiky, ca.url AS urlCat, ca.nombre AS nombreCat
    FROM {$db_prefix}comunidades_articulos AS a, {$db_prefix}comunidades AS c, {$db_prefix}comunidades_categorias AS ca
    WHERE a.id = $id
    AND a.eliminado = 0
    AND a.id_com = c.id
    AND c.categoria = ca.url
    LIMIT 1", __FILE__, __LINE__);

  while ($row = mysqli_fetch_assoc($rs)) {
    $titulo = nohtml(nohtml2($row['titulo']));
    $cuerpo = nohtml(nohtml2($row['cuerpo']));
    $idc = $row['id_com'];
    $stiky = $row['stiky'];
    $nocoment = $row['nocoment'];
    $id_user = $row['id_user'];

    arriba('EditarTema', $boardurl . '/comunidades/categoria/' . $row['urlCat'], $row['nombreCat'], $boardurl . '/comunidades/' . $row['url'] . '/', '' . nohtml(nohtml2($row['nombre'])), $boardurl . '/comunidades/' . $row['url'] . '/' . $row['id'] . '/' . urls($titulo) . '.html', $titulo);
  }

  if (!$titulo) {
    fatal_error('No puedes editar este tema.');
  }

  permisios($idc);
  acces($idc);

  if (in_array($context['permisoCom'], [1, 2, 3] || $id_user == $ID_MEMBER)) {
    if (in_array($context['puedo'], [1, 3])) {
      echo '
        <div style="width: 354px; float: left; margin-right: 8px;">
          <div class="box_354">
            <div class="box_title" style="width: 352px;">
              <div class="box_txt box_354-34">Destacados</div>
              <div class="box_rss">
                <div class="icon_img">
                  <img src="' . $tranfer1 . '/blank.gif" style="width: 16px; height: 16px;" border="0" alt="" />
                </div>
              </div>
            </div>
            <div style="width: 344px; padding: 4px;" class="windowbg">';

      anuncio_300x250();

      echo '
            </div>
          </div>
        </div>
        <div style="width: 560px; float: left;">
          <div class="box_560">
            <div class="box_title" style="width: 558px;">
              <div class="box_txt box_560-34">Editar tema</div>
              <div class="box_rss">
                <div class="icon_img">
                  <img src="' . $tranfer1 . '/blank.gif" style="width: 16px; height: 16px;" border="0" alt="" />
                </div>
              </div>
            </div>
            <div class="windowbg" style="width: 550px; padding: 4px;">
              <form name="add_comunidad" id="nuevocoment" method="post" action="' . $boardurl . '/web/cw-comunidadesEditarTema.php">
                <div class="form-container">
                  <label for="uname">Titulo:</label>
                  <input style="width: 540px;" onfocus="foco(this);" onblur="no_foco(this);" class="c_input" value="' . $titulo . '" name="titulo" tabindex="1" datatype="text" dataname="Titulo" type="text" />
                  <div class="clearBoth"></div>
                  <div class="data">
                    <label for="uname">Cuerpo:</label>
                    <textarea style="height: 300px; width: 544px;" id="editorCW" name="cuerpo_comment" tabindex="3">' . $cuerpo . '</textarea>';

      textaer(1);

      echo '
        </div>
        <div class="clearBoth"></div>
        <br />
        <fieldset style="width: 200px;">
          <legend>
            <span class="tit_lab">Opciones</span>
          </legend>';

      if (in_array($context['permisoCom'], [1, 3])) {
        echo '
          <label for="sticky">
            <input name="sticky" id="sticky" value="1" type="checkbox"' . ($stiky ? ' checked="checked"' : '') . ' />
            Fijar
          </label>';
      }

      echo '
                    <label for="nocoment">
                      <input name="nocoment" id="nocoment" value="1" type="checkbox"' . ($nocoment ? ' checked="checked"' : '') . ' />
                      No permitir comentarios
                    </label>
                  </fieldset> 
                  <input name="id_tema" value="' . $id . '" type="hidden" />
                </div>
                <div style="clear: both; margin-bottom: 15px; margin-top: 20px;" class="hrs"></div>
                <div id="buttons" align="right">
                  <input tabindex="14" title="Editar tema" value="Editar tema" class="login" name="Enviar" type="submit"/>
                </div>
              </form>
            </div>
          </div>
        </div>';
    } else {
      fatal_error('No puedes editar este tema.');
    }
  } else {
    fatal_error('No puedes editar este tema.');
  }
}

// Editar comunidad
function template_ecomunidad() {
  global $tranfer1, $context, $db_prefix, $boardurl, $mbname;

  arriba('EditarCom', $boardurl . '/comunidades/categoria/' . $context['COMediTurlCat'], $context['COMediTnombreCat'], $boardurl . '/comunidades/' . $context['COMediTurl'], $context['COMediTnombre']);

  echo '
    <div style="width: 354px; float: left; margin-right: 8px;">
      <div class="box_354">
        <div class="box_title" style="width: 352px;">
          <div class="box_txt box_354-34">Destacados</div>
          <div class="box_rss">
            <div class="icon_img">
              <img src="' . $tranfer1 . '/blank.gif" style="width: 16px; height: 16px;" border="0" alt="" />
            </div>
          </div>
        </div>
        <div style="width: 344px; padding: 4px;" class="windowbg">';

  anuncio_300x250();

  echo '
        </div>
      </div>
    </div>
    <div style="width: 560px; float: left;">
      <div class="box_560">
        <div class="box_title" style="width: 558px;">
          <div class="box_txt box_560-34">Editar Comunidad</div>
          <div class="box_rss">
            <div class="icon_img">
              <img src="' . $tranfer1 . '/blank.gif" style="width: 16px; height: 16px;" border="0" alt="" />
            </div>
          </div>
        </div>
        <div class="windowbg" style="width: 550px; padding: 4px;">
          <form name="add_comunidad" method="post" action="' . $boardurl . '/web/cw-comunidadesEditar.php">
            <div class="form-container">
              <div class="dataL">
                <label for="uname">Nombre de la comunidad</label>
                <input onfocus="foco(this);" onblur="no_foco(this);" class="c_input" value="' . $context['COMediTnombre'] . '" name="nombre" tabindex="1" datatype="text" dataname="Nombre" type="text" />
              </div>
              <div class="dataR">
                <label for="uname" style="float: left;">Nombre corto</label>
                <br />
                <div style="padding-top: 6px;">
                  <strong style="color: green;">' . $boardurl . '/comunidades/' . $context['COMediTurl'] . '</strong>
                </div>
              </div>
              <div class="clearBoth"></div>
              <div class="dataL">
                <label for="uname">Imagen</label>
                <input onfocus="foco(this);" onblur="no_foco(this);" class="c_input" value="' . $context['COMediTimagen'] . '" name="imagen" tabindex="3" datatype="url" dataname="Imagen" type="text" />  
              </div>';

  $request = db_query("
    SELECT id, url, nombre
    FROM {$db_prefix}comunidades_categorias
    ORDER BY nombre ASC", __FILE__, __LINE__);

  echo '
    <div class="dataR">
      <span class="gif_cargando floatR" id="subcategoria" style="top: 0px;"></span>
      <label for="fname">Categor&iacute;a</label>
      <select style="width: 264px; margin-top: 5px; height: 25px; vertical-align: middle;" name="categoria">
        <option value="-1">Elegir una categor&iacute;a</option>';
  while ($row = mysqli_fetch_assoc($request)) {
    echo '<option value="' . $row['url'] . '"' . ($context['COMediTcategoria'] == $row['id'] ? ' selected="selected"' : '') . '>' . $row['nombre'] . '</option>';
  }

  echo '
        </select>
      </div>
      <div class="clearBoth"></div>
      <div class="data">
        <label for="uname">Descripci&oacute;n</label>
        <textarea onfocus="foco(this);" onblur="no_foco(this);" class="c_input_desc autogrow" style="display: block; width: 540px;" name="descripcion" tabindex="7" datatype="text" dataname="Descripcion">' . $context['COMediTdescripcion'] . '</textarea>
      </div>
    </div>
    <div style="clear: both;" class="hrs"></div>
      <div style="margin-bottom: 8px;">
        <div class="titlesCom" onclick="DesplComOps(\'acceso\', \'fname\', \'aprobar\'); return false;" id="dev_acceso">
          <label for="lname">
            <b>Acceso</b>
          </label>
        </div>
        <div class="titlesCom2" onclick="DesplComOps2(\'acceso\'); return false;" id="dov_acceso" style="display: none;">
          <label for="lname">
            <b>Acceso</b>
          </label>
        </div>
        <div class="postLabel" style="display: none;" id="div_acceso">
          <fieldset>
            <legend>
              <label for="privada_1" class="tit_lab">Todos</label>
            </legend>
            <table>
              <tr>
                <td style="width: 18px; padding: 0px; margin: 0px;">
                  <input name="privada" id="privada_1" value="1" tabindex="9" type="radio" '. ($context['COMediTacceso'] == 1 ? 'checked="checked" ' : '') . '/>
                </td>
                <td>
                  <p class="descRadio">Toda persona que entra a ' . $mbname . ' tiene la posibilidad de entrar y ver el contenido de tu comunidad.</p>
                </td>
              </tr>
            </table>
          </fieldset>
          <fieldset>
            <legend>
              <label for="privada_2" class="tit_lab">S&oacute;lo usuarios registrados</label>
            </legend>
            <table>
              <tr>
                <td style="width: 18px; padding: 0px; margin: 0px;">
                  <input name="privada" id="privada_2" value="2" type="radio" ' . ($context['COMediTacceso'] == 2 ? 'checked="checked" ' : '') . '/>
                </td>
                <td>
                  <p class="descRadio">Todo aquel que no est&eacute; registrado en ' . $mbname . ' no podr&aacute; ver el contenido de tu comunidad.</p>
                </td>
              </tr>
            </table>
          </fieldset>
          <fieldset>
            <legend>
              <label for="privada_3" class="tit_lab">S&oacute;lo Miembros</label>
            </legend>
            <table>
              <tr>
                <td style="width: 18px; padding: 0px; margin: 0px;">
                  <input name="privada" id="privada_3" value="3" type="radio" ' . ($context['COMediTacceso'] == 3 ? 'checked="checked" ' : '') . '/>
                </td>
                <td>
                  <p class="descRadio">Todo aquel que no est&eacute; unido en la comunidad no podr&aacute; ver el contenido de tu comunidad.</p>
                </td>
              </tr>
            </table>
          </fieldset> 
          <fieldset>
            <legend>
              <label for="privada_4" class="tit_lab">S&oacute;lo Miembros/Oculta</label>
            </legend>
              <table>
                <tr>
                  <td style="width: 18px; padding: 0px; margin: 0px;">
                    <input name="privada" id="privada_4" value="4" type="radio" ' . ($context['COMediTacceso'] == 4 ? 'checked="checked" ' : '') . '/>
                  </td>
                  <td>
                    <p class="descRadio">Todo aquel que no est&eacute; unido en la comunidad no podr&aacute; ver el contenido de tu comunidad. No se mostrar&aacute;n los &uacute;ltimos temas creados en el centro de comunidades.</p>
                  </td>
                </tr>
              </table>
            </fieldset> 
          </div>
        </div>
        <div style="margin-bottom: 8px;">
          <div class="titlesCom" onclick="DesplComOps(\'aprobar\', \'acceso\', \'fname\'); return false;" id="dev_aprobar">
            <label for="aprobar">
              <b>Aprobaci&oacute;n de miembros</b>
            </label>
          </div>
          <div class="titlesCom2" onclick="DesplComOps2(\'aprobar\'); return false;" id="dov_aprobar" style="display: none;">
            <label for="lname">
              <b>Aprobaci&oacute;n de miembros</b>
            </label>
          </div>
          <div class="postLabel" style="display: none;" id="div_aprobar">
            <fieldset>
              <legend>
                <label for="automatica" class="tit_lab">Autom&aacute;tica</label>
              </legend>
              <table>
                <tr>
                  <td style="width: 18px; padding: 0px; margin: 0px;">
                    <input name="aprobar" id="automatica" value="0" tabindex="9" type="radio" ' . (!$context['COMediTaprobar'] ? 'checked="checked" ': '') . '/>
                  </td>
                  <td>
                    <p class="descRadio">Cuando una persona se une a la comunidad, nadie deber&aacute; aprobarlo.</p>
                  </td>
                </tr>
              </table>
            </fieldset>
            <fieldset>
              <legend>
                <label for="manual" class="tit_lab">Manual</label>
              </legend>
              <table>
                <tr>
                  <td style="width: 18px; padding: 0px; margin: 0px;">
                    <input name="aprobar" id="manual" value="1" type="radio" ' . ($context['COMediTaprobar'] ? 'checked="checked" ' : '') . '/>
                  </td>
                  <td>
                    <p class="descRadio">Cuando una persona se une a la comunidad, deber&aacute; esperar a que el administrador de tal lo apruebe.</p>
                  </td>
                </tr>
              </table>
            </fieldset>
          </div>
        </div>
        <div class="titlesCom" onclick="DesplComOps(\'fname\', \'aprobar\', \'acceso\'); return false;" id="dev_fname">
          <label for="fname">
            <b>Permisos</b></label>
          </div>
          <div class="titlesCom2" onclick="DesplComOps2(\'fname\'); return false;" id="dov_fname" style="display: none;">
            <label for="lname">
              <b>Permisos</b></label>
            </div>
            <div class="postLabel" style="display: none;" id="div_fname">
              <fieldset>
                <legend>
                  <label for="permisos_1" class="tit_lab">Posteador</label>
                </legend>
                <table>
                  <tr>
                    <td style="width: 18px; padding: 0px; margin: 0px;">
                      <input name="rango_default" id="permisos_1" value="3" checked="checked" tabindex="11" type="radio" ' . ($context['COMediTpermiso'] == 3 ? 'checked="checked" ' : '') . '/>
                    </td>
                    <td>
                      <p class="descRadio">Los usuarios al ingresar en tu comunidad podr&aacute;n comentar y crear temas.</p>
                    </td>
                  </tr>
                </table>
              </fieldset>
              <fieldset>
                <legend>
                  <label for="permisos_2" class="tit_lab">Comentador</label>
                </legend>
                <table>
                  <tr>
                    <td style="width: 18px; padding: 0px; margin: 0px;">
                      <input name="rango_default" id="permisos_2" value="2" type="radio" ' . ($context['COMediTpermiso'] == 2 ? 'checked="checked" ' : '') . '/>
                    </td>
                    <td>
                      <p class="descRadio">Los usuarios al participar en tu comunidad s&oacute;lo podr&aacute;n comentar pero no estar&aacute;n habilitados para crear nuevos temas.</p>
                    </td>
                  </tr>
                </table>
              </fieldset>
              <fieldset>
                <legend>
                  <label for="permisos_3" class="tit_lab">Visitante</label>
                </legend>
                <table>
                  <tr>
                    <td style="width: 18px; padding: 0px; margin: 0px;">
                      <input name="rango_default" id="permisos_3" value="4" type="radio" ' . ($context['COMediTpermiso'] == 4 ? 'checked="checked" ' : '') . '/>
                    </td>
                    <td>
                      <p class="descRadio">Los usuarios al participar en tu comunidad no podr&aacute;n comentar ni tampoco crear temas.</p>
                    </td>
                  </tr>
                </table>
              </fieldset>
              <fieldset>
                <legend class="tit_lab">Nota:</legend>
                <p class="descRadio">
                  El permiso seleccionado se le asignar&aacute; autom&aacute;ticamente al usuario que se haga miembro, sin embargo, podr&aacute;s modificar el permiso a cada usuario espec&iacute;fico.
                </p>
              </fieldset>
            </div>
            <div style="clear: both; margin-bottom: 15px; margin-top: 20px;" class="hrs"></div>
            <div id="buttons" align="right">
              <input value="' . $context['COMediTidvb'] . '" name="idcom" type="hidden" />
              <input tabindex="14" title="Editar comunidad" value="Editar comunidad" class="login" name="Enviar" type="submit" />
            </div>
          </form>
        </div>
      </div>
    </div>';
}

// Directorios
function template_directorios() {
  global $sourcedir, $context, $no_avatar, $db_prefix, $boardurl;

  require_once($sourcedir . '/FuncionesCom.php');

  arriba('directorios');

  $cat = str_replace('/', '', trim($_GET['cat']));

  if (!$cat) {
    $maxrowlevel = 2;
    $rowlevel = 0;

    $rs = db_query("
      SELECT nombre, comunidades, url
      FROM {$db_prefix}comunidades_categorias", __FILE__, __LINE__);

    echo '
      <table style="width: 922px;">
        <tr>';

    while ($row = mysqli_fetch_assoc($rs)) {
      if ($rowlevel < ($maxrowlevel + 1)) {
        $rowlevel++;
      } else {
        $rowlevel = 0;
      }

      echo '
        <td style="width: 230.5px;">
          <a href="' . $boardurl . '/comunidades/dir/' . $row['url'] . '" style="color: green; font-size: 17px; border-bottom: 1px dotted;">' . $row['nombre'] . '</a>
          <br />
          <strong style="color: orange; font-size: 13px;">Comunidades: ' . $row['comunidades'] . '</strong>
        </td>';

      if ($rowlevel < 1) {
        echo '
          </tr>
          <tr>';
      }
    }

    echo '
        </tr>
      </table>
      <div class="noesta-am" style="width: 922px; margin-top: 15px;">
        <a href="' . $boardurl . '/crear-comunidades/">Crea tu comunidad. Es GRATIS, R&Aacute;PIDO Y FACIL</a>
      </div>';
  } else {
    $RegistrosAMostrar = 8;
    $pag = isset($_GET['paeg']) ? (int) $_GET['paeg'] : 0;
    $per = $pag < 1 ? 1 : $pag;

    if (isset($per)) {
      $RegistrosAEmpezar = ($per - 1) * $RegistrosAMostrar;
      $PagAct = $per;
    } else {
      $RegistrosAEmpezar = 0;
      $PagAct = 1;
    }

    $request = db_query("
      SELECT nombre
      FROM {$db_prefix}comunidades_categorias
      WHERE url = '$cat'
      LIMIT 1", __FILE__, __LINE__);

    $ta = mysqli_num_rows($request);

    if (!$ta) {
      fatal_error('Esta categor&iacute;a no existe.');
    }

    echo '
      <div style="width: 922px;">
        <div class="dir">';

    // TO-DO: Cambiar $cat por la url de la categoria de la comunidad mediante INNER JOIN
    $rs = db_query("
      SELECT nombre, imagen, descripcion, url
      FROM {$db_prefix}comunidades
      WHERE categoria = '$cat'
      AND bloquear = 0
      ORDER BY id DESC
      LIMIT $RegistrosAEmpezar, $RegistrosAMostrar", __FILE__, __LINE__);

    while ($row = mysqli_fetch_assoc($rs)) {
      $img = isset($row['imagen']) ? $row['imagen'] : $no_avatar;

      echo '
        <div class="dir-ind" id="muroEfectAV">
          <div style="float: left; width: 75px;">
            <img class="avatar-box" onerror="error_avatar(this)" src="' . $img . '" alt="" width="75px" height="75px" />
          </div>
          <div style="float: left; margin-left: 15px; width: 822px;">
            <div style="margin-bottom: 5px; color: green; font-size: 17px; border-bottom: 1px dotted;">
              <a href="' . $boardurl . '/comunidades/' . $row['url'] . '" style="color: green;">' . $row['nombre'] . '</a>
            </div>
            <div style="color: grey; font-size: 11px;">' . $row['descripcion'] . '</div>
          </div>
          <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>';
    }

    echo '
        </div>
      </div>';

    $request = db_query("
      SELECT nombre
      FROM {$db_prefix}comunidades
      WHERE categoria = '$cat'
      AND bloquear = 0", __FILE__, __LINE__);

    $tda = mysqli_num_rows($request);
    $PagAnt = $PagAct - 1;
    $PagSig = $PagAct + 1;
    $PagUlt = $tda / $RegistrosAMostrar;
    $Res = $tda % $RegistrosAMostrar;

    if ($Res > 0) {
      $PagUlt = floor($PagUlt) + 1;
    }

    if ($PagAct > $PagUlt) {
      echo '<div class="noesta" style="width: 922px;">Esta p&aacute;gina no existe.</div>';
    }

    if ($PagAct > $PagUlt) {
      // TO-DO: ¿Aquí se hace algo?
    } else {
      if ($PagAct > 1 || $PagAct < $PagUlt) {
        echo '<div class="windowbgpag" style="width: 378px;">';

        if ($PagAct > 1) {
          echo '<a href="' . $boardurl . '/comunidades/dir/' . $cat . '/pag-' . $PagAnt . '">&#171; anterior</a>';
        }

        if ($PagAct < $PagUlt) {
          echo '<a href="' . $boardurl . '/comunidades/dir/' . $cat . '/pag-' . $PagSig . '">siguiente &#187;</a>';
        }
      }

      echo '</div>';
    }
  }
}

// TOPs
function template_tops() {
  global $sourcedir, $context, $no_avatar, $db_prefix;

  include ($sourcedir . '/FuncionesCom.php');
  arriba('tops');

  echo '<div>
    <div class="box_300" align="left" style="float:left; margin-right: 8px;">
    <div class="box_title" style="width: 300px;"><div class="box_txt box_300-34">10 temas con mayor calificaci&oacute;n</div>
    <div class="box_rss"><div class="icon_img"><a href="/rss/post-puntos/"><img alt="" src="' . $tranfer1 . '"/icons/cwbig-v1-iconos.gif?v3.2.3" style="cursor: pointer; margin-top: -352px; display: inline;" /></a></div></div></div><div class="windowbg" style="width: 292px; padding: 4px;">';

  $rs = db_query("
    SELECT ca.titulo,ca.calificacion,ca.id,c.url
    FROM ({$db_prefix}comunidades_articulos AS ca,{$db_prefix}comunidades AS c)
    WHERE ca.id_com=c.id
    ORDER BY ca.calificacion DESC
    LIMIT 10", __FILE__, __LINE__);

  $uno = 1;
  $row['titulo'] = nohtml2(nohtml($row['titulo']));

  while ($row = mysqli_fetch_assoc($rs)) {

    echo '<div class="size11"><strong>' . $uno++ . '</strong> - <a href="/comunidades/' . $row['url'] . '/' . $row['id'] . '/' . urls($row['titulo']) . '.html">' . $row['titulo'] . ' (' . $row['calificacion'] . ')</div>';
  }

  echo '</div></div>
    <div class="box_300" align="left" style="float:left; margin-right: 8px;">
    <div class="box_title" style="width: 300px;"><div class="box_txt box_300-34">10 Post con m&aacute;s puntos</div>
    <div class="box_rss"><div class="icon_img"><a href="/rss/post-puntos/"><img alt="" src="' . $tranfer1 . '"/icons/cwbig-v1-iconos.gif?v3.2.3" style="cursor: pointer; margin-top: -352px; display: inline;" /></a></div></div></div><div class="windowbg" style="width: 292px; padding: 4px;">';

  echo '</div></div>
    <div class="box_300" align="left" style="float:left;">
    <div class="box_title" style="width: 300px;"><div class="box_txt box_300-34">10 Post con m&aacute;s puntos</div>
    <div class="box_rss"><div class="icon_img"><a href="/rss/post-puntos/"><img alt="" src="' . $tranfer1 . '"/icons/cwbig-v1-iconos.gif?v3.2.3" style="cursor: pointer; margin-top: -352px; display: inline;" /></a></div></div></div><div class="windowbg" style="width: 292px; padding: 4px;">';

  echo '</div></div></div>
    <div style="clear: left;"></div>';
}

// Buscar
function template_buscar() {
  global $sourcedir;

  require_once($sourcedir . '/FuncionesCom.php');
  require_once($sourcedir . '/Hear-Buscador.php');
  echo resultados($_GET['buscador_tipo']);
}

?>