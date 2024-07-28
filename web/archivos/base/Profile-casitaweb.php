<?php
// Página echa por rigo (Rodrigo). CasitaWeb! - www.casitaweb.net.

function partearriba($sasdde, $lugar) {
  global $db_prefix, $context, $boardurl, $ID_MEMBER;

  $request = db_query("
  SELECT muro, fecha
  FROM {$db_prefix}muro
  WHERE id_user = '{$context['member']['id']}'
  AND tipocc = 0
  AND tipo = 1
  ORDER BY id DESC
  LIMIT 1", __FILE__, __LINE__);

  while ($row = mysqli_fetch_array($request)) {
    echo '
      <div style="border-bottom: #C8C8C8 solid 1px; width: 541px; padding-bottom: 8px; margin-bottom: 2px;">
        <strong style="font-size: 20px; color:#D35F2C;" title="' . $context['member']['name'] . '">' . $context['member']['name'] . '</strong>
        <span style="font-size: 13px;">
          ' . nohtml2($row['muro']) . '.
          <span style="color: #C0C0C0; font-size: 11px;">' . hace($row['fecha']) . '</span>
        </span>
      </div>';
  }

  mysqli_free_result($request);

  $yata = isset($yata) ? $yata : '';

  if (empty($yata)) {
    echo '
      <div style="border-bottom: #C8C8C8 solid 1px; width: 541px; padding-bottom: 8px; margin-bottom: 2px; float: left;">
        <strong style="font-size: 20px; color: #D35F2C;" title="' . $context['member']['name'] . '">' . $context['member']['name'] . '</strong>
      </div>';
  }

  if ($lugar == 'muro') {
    $muro = ' class="activado" ';
    $amist = '';
    $Comu = '';
    $apaa = '';
  } elseif ($lugar == 'apariencia') {
    $muro = '';
    $amist = '';
    $Comu = '';
    $apaa = ' class="activado" ';
  } elseif ($lugar == 'comunidades') {
    $muro = '';
    $amist = '';
    $Comu = ' class="activado" ';
    $apaa = '';
  } elseif ($lugar == 'lista-de-amigos') {
    $muro = '';
    $amist = ' class="activado" ';
    $Comu = '';
    $apaa = '';
  }

  echo '
    <div class="botnes" style="clear: both; height: 32px; width: 541px;">
      <a href="' . $boardurl . '/perfil/' . $sasdde . '/muro/" title="Muro"' . $muro . '>Muro</a></li>
      <a href="' . $boardurl . '/perfil/' . $sasdde . '/apariencia/" title="Apariencia"' . $apaa . '>Apariencia</a>
      <a href="' . $boardurl . '/perfil/' . $sasdde . '/comunidades/" title="Comunidades"' . $Comu . '>Comunidades</a>
      <a href="' . $boardurl . '/perfil/' . $sasdde . '/lista-de-amigos/" title="Amistades"' . $amist . '>Amistades</a>
      <div class="clearBoth"></div>
    </div>';
}

function template_summary() {
  global $context, $tranfer1, $user_info, $no_avatar, $db_prefix, $txt, $user_settings, $boardurl, $ID_MEMBER;

  $lugar = str_replace('/', '', $_GET['lugar']);

  if ($lugar === 1 || $lugar == 'apariencia') {
    $tipo = 1;
  } else if ($lugar === 2 || $lugar == 'muro') {
    $tipo = 0;
  } else if ($lugar === 5 || $lugar == 'comunidades') {
    $tipo = 5;
  } else {
    $tipo = $lugar;
  }

  $noimg = $context['member']['name'] . ' no tiene ninguna imagen';
  $nopost = $context['member']['name'] . ' no tiene ning&uacute;n post hecho';

  // CONSULTAS ------------------
  $shas = !$context['user']['is_admin'] ? ' AND ID_BOARD <> 142' : '';
  $pagq1 = isset($_GET['pag-seg-15487135']) ? $_GET['pag-seg-15487135'] : '';

  $request = db_query("
    SELECT id_user
    FROM {$db_prefix}muro
    WHERE id_user = {$context['member']['id']}", __FILE__, __LINE__);

  $cantidadmuro = mysqli_num_rows($request);

  $request = db_query("
    SELECT user
    FROM {$db_prefix}amistad
    WHERE user = {$context['member']['id']}
    OR amigo = {$context['member']['id']}
    AND acepto = 1", __FILE__, __LINE__);

  $bbvxc = mysqli_num_rows($request);

  if (!$user_info['is_guest']) {
    if ($user_settings['ID_MEMBER'] != $context['member']['id']) {
      $q = db_query("
        SELECT amigo, user
        FROM {$db_prefix}amistad
        WHERE user = $ID_MEMBER
        OR amigo = $ID_MEMBER
        AND acepto = 1", __FILE__, __LINE__);

      while ($r = mysqli_fetch_array($q)) {
        if ($r['amigo'] != $user_settings['ID_MEMBER']) {
          $sdasds = $r['amigo'];
        } else {
          $sdasds = $r['user'];
        }

        $amigos_en_comun[] = $sdasds;
        $sssdas = $sdasds;
      }

      if (!empty($sssdas)) {
        $request = db_query("
          SELECT user
          FROM {$db_prefix}amistad
          WHERE user='{$context['member']['id']}'
          AND amigo IN (" . join(',', $amigos_en_comun) . ')
          OR user IN (' . join(',', $amigos_en_comun) . ")
          AND amigo = {$context['member']['id']}
          AND acepto = 1", __FILE__, __LINE__);

        $cantidaddss = mysqli_num_rows($request);
      }
    }
  }

  echo '<div style="float:left;">';

  if ($context['user']['is_admin'] && $context['member']['is_activated'] == 2) {
    echo '
      <script type="text/javascript">
        function ActivarUS(id) {
          $.ajax({
            type: \'GET\',
            url: \'' . $boardurl . '/web/cw-ActivarUser.php\',
            data: \'u=\' + id,
            success: function(h) { $("#cdesact").remove(); }
          });
        }
      </script>
      <div class="noesta-am" style="margin-bottom: 8px;" id="cdesact">CUENTA DESACTIVADA!!! &#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&gt;&gt;&gt; <span onclick="ActivarUS(\'' . $context['member']['id'] . '\'); return false;" class="pointer">activar</span></div>';
  }

  if ($user_info['is_admin'] || $user_info['is_mods']) {
    $request52 = db_query("
      SELECT name, reason, ban_time, notes, expire_time
      FROM {$db_prefix}ban_groups
      WHERE name = '{$context['member']['name']}'", __FILE__, __LINE__);

    while ($row = mysqli_fetch_assoc($request52)) {
      $context['reason'] = $row['reason'];
      $context['fecha'] = $row['ban_time'];
      $context['notes'] = $row['notes'];
      $context['expira'] = $row['expire_time'] === null ? $txt['never'] : ($row['expire_time'] < time() ? '' : (int) ceil(($row['expire_time'] - time()) / (60 * 60 * 24)) . ' d&iacute;a(s)');
    }

    mysqli_free_result($request52);

    if (!empty($context['expira'])) {
      $request352 = db_query("
        SELECT realName
        FROM {$db_prefix}members
        WHERE ID_MEMBER = {$context['notes']}", __FILE__, __LINE__);

      $row = mysqli_fetch_assoc($request352);
      $moderator = isset($row['realName']) ? $row['realName'] : '';

      mysqli_free_result($request352);

      echo '
        <div class="noesta" style="margin-bottom: 8px;">
          &iexcl;&iexcl;&iexcl;CUENTA BANEADA!!!
          <br />
          <b>Raz&oacute;n:</b>
          ' . $context['reason'] . '
          |
          <b>El d&iacute;a:</b>
          ' . timeformat($context['fecha']) . '
          <br />
          <b>Por:</b>
          ' . ($moderator == '' ? ' - ' : '<a href="' . $boardurl . '/perfil/' . $moderator . '" title="' . $moderator . '">' . $moderator . '</a>') . '
          |
          <b>Expira:</b>
          ' . $context['expira'] . '
        </div>';
    }
  }

  if ($lugar == 'amigos-en-comun') {
    if ($user_settings['ID_MEMBER'] != $context['member']['id']) {
      echo '<div class="windowbg" style="border-top: 1px solid #C8C8C8; width: 523px; padding: 8px;">';

      $NroRegistros = $cantidaddss;

      if (!empty($cantidaddss) == 1) {
        echo '
          <p style="font-size: 10px; margin: 0px; padding: 0px; float: left; width: 250px;">
            <a href="' . $boardurl . '/perfil/' . $context['member']['name'] . '/amigos-en-comun/">1 amigo en com&uacute;n</a>
          </p>';
      } else if (!empty($cantidaddss) >= 2) {
        echo '
          <p style="font-size: 10px; margin: 0px; padding: 0px; float: left; width: 250px;">
            <a href="' . $boardurl . '/perfil/' . $context['member']['name'] . '/amigos-en-comun/">' . $cantidaddss . ' amigos en com&uacute;n</a>
          </p>';
      }

      if (!empty($bbvxc) == 1) {
        echo '
          <p align="right" style="font-size: 10px; margin: 0px; padding: 0px; width: 250px;">
            <a href="' . $boardurl . '/perfil/' . $context['member']['name'] . '/lista-de-amigos/">1 amigo</a>
          </p>';
      } elseif (!empty($bbvxc) >= 2) {
        echo '
          <p align="right" style="font-size: 10px; margin: 0px; padding: 0px;">
            <a href="' . $boardurl . '/perfil/' . $context['member']['name'] . '/lista-de-amigos/">' . $bbvxc . ' amigos</a>
          </p>';
      }

      if ((!empty($cantidaddss) == 1) || (!empty($cantidaddss) >= 2) || (!empty($bbvxc) == 1) || (!empty($bbvxc) >= 2)) {
        echo '<div class="hrs"></div>';
      }

      $RegistrosAMostrar = 15;
      $dud = $pagq1 < 1 ? 1 : $pagq1;

      if (isset($dud)) {
        $RegistrosAEmpezar = ($dud - 1) * $RegistrosAMostrar;
        $PagAct = $dud;
      } else {
        $RegistrosAEmpezar = 0;
        $PagAct = 1;
      }

      if (!empty($NroRegistros)) {
        $qd3 = db_query("
          SELECT user, amigo
          FROM {$db_prefix}amistad
          WHERE user = {$context['member']['id']}
          AND amigo IN (" . join(',', $amigos_en_comun) . ')
          OR user IN (' . join(',', $amigos_en_comun) . ")
          AND amigo = {$context['member']['id']}
          AND acepto = 1
          LIMIT $RegistrosAEmpezar, $RegistrosAMostrar", __FILE__, __LINE__);

        while ($rowd3 = mysqli_fetch_assoc($qd3)) {
          if ($rowd3['amigo'] != $context['member']['id']) {
            $sdasdsddvdv = $rowd3['amigo'];
          } else {
            $sdasdsddvdv = $rowd3['user'];
          }

          $datosmem = db_query("
            SELECT ID_MEMBER, personalText, avatar, realName
            FROM {$db_prefix}members
            WHERE ID_MEMBER = $sdasdsddvdv
            LIMIT 1", __FILE__, __LINE__);

          while ($data = mysqli_fetch_assoc($datosmem)) {
            $nombremem = $data['realName'];
            $avatar = $data['avatar'];
            $pt = $data['personalText'];
            $conejjssuu = $data['ID_MEMBER'];

            $dadd = db_query("
              SELECT fecha
              FROM {$db_prefix}amistad
              INNER JOIN {$db_prefix}members AS m ON amigo = $conejjssuu
              AND user = $ID_MEMBER
              OR amigo = $ID_MEMBER
              AND user = $conejjssuu
              AND acepto = 1
              LIMIT 1", __FILE__, __LINE__);

            while ($dataddf = mysqli_fetch_assoc($dadd)) {
              $yata = hace($dataddf['fecha']);
            }

            mysqli_free_result($dadd);

            $request = db_query("
              SELECT ID_MEMBER
              FROM {$db_prefix}log_online
              WHERE ID_MEMBER = $conejjssuu
              LIMIT 1", __FILE__, __LINE__);

            $esta = mysqli_num_rows($request);

            echo '
              <div class="muroEfect" id="muroEfectAV">
                <table>
                  <tr>
                    <td valign="top">
                      <img style="width: 50px; height: 50px;" class="avatar-box" alt="" src="' . (empty($avatar) ? $no_avatar : $avatar) . '" onerror="error_avatar(this)" />
                    </td>
                    <td valign="top" style="margin: 0px; padding: 4px;">
                      <a onclick="if (!confirm(\'\xbfEst&aacuteMs seguro que deseas eliminar a este usuario de tus amigos?\')) return false;" href="' . $boardurl . '/web/cw-AmistadBorrar.php?user=' . $nombremem . '" title="Eliminar usuario de mi lista de amigos">
                        <img alt="Eliminar usuario de mi lista de amigos" src="' . $tranfer1 . '/eliminar.gif" width="8px" height="8px" />
                      </a>
                      &#32;-&#32;
                      <b>
                        <span style="font-size: 12px;">
                          <a href="' . $boardurl . '/perfil/' . $nombremem . '" title="' . $nombremem . '">' . $nombremem . '</a>
                        </span>
                      </b>';

            if (!empty($pt)) {
              echo '&#32;-&#32;' . $pt;
            }

            if ($esta == 1) {
              echo '&#32;-&#32;<img src="' . $tranfer1 . '/icons/bullet-verde.gif" alt="Conectado/a" title="Conectado/a" />';
            }

            if (empty($esta)) {
              echo '&#32;-&#32;<img src="' . $tranfer1 . '/icons/bullet-rojo.gif" alt="Desconectado/a" title="Desconectado/a" />';
            }

            echo '
                      </span>
                      <br />
                      <span style="color: green; font-size: 10px;">
                        <b>Son amigos ' . $yata . '</b>
                      </span>
                    </td>
                  </tr>
                </table>
              </div>
              <div class="hrs"></div>';
          }
        }

        mysqli_free_result($qd3);
      }

      if (empty($conejjssuu)) {
        echo '<div class="noesta">No tienes ning&uacute;n amigo en com&uacute;n con ' . $context['member']['name'] . '</div>';
      }

      $PagAnt = $PagAct - 1;
      $PagSig = $PagAct + 1;
      $PagUlt = $NroRegistros / $RegistrosAMostrar;
      $Res = $NroRegistros % $RegistrosAMostrar;

      if ($Res > 0) {
        $PagUlt = floor($PagUlt) + 1;
      }

      echo '</div>';

      if ($PagAct > 1 || $PagAct < $PagUlt) {
        echo '<div class="windowbgpag" style="width: 200px;">';

        if ($PagAct > 1) {
          echo '<a href="' . $boardurl . '/perfil/' . $context['member']['name'] . '/amigos-en-comun-pag-' . $PagAnt . '">&#171; anterior</a>';
        }

        if ($PagAct < $PagUlt) {
          echo '<a href="' . $boardurl . '/perfil/' . $context['member']['name'] . '/amigos-en-comun-pag-' . $PagSig . '">siguiente &#187;</a>';
        }

        echo '
          </div>
          <div class="clearBoth"></div>
          <div style="clear: both;"></div>';
      }
    } else {
      echo '
        <div class="windowbg" style="border: 1px solid #B3A496; width: 523px; padding: 8px; font-size: 11px;">
          <b class="size11">Acci&oacute;n no reconocida.</b>
          <hr />
        </div>';
    }
  } else if ($lugar == 'lista-de-amigos') {
    partearriba($context['member']['name'], $lugar);

    $NroRegistros = $bbvxc;
    $RegistrosAMostrar = 15;
    $dud = $pagq1 < 1 ? 1 : $pagq1;

    if (isset($dud)) {
      $RegistrosAEmpezar = ($dud - 1) * $RegistrosAMostrar;
      $PagAct = $dud;
    } else {
      $RegistrosAEmpezar = 0;
      $PagAct = 1;
    }

    if ($NroRegistros) {
      echo '<div class="windowbg" style="border: 1px solid #C8C8C8; width: 523px; padding: 8px; font-size: 11px;">';

      if (!empty($cantidaddss) === 1) {
        echo '
          <p style="margin: 0px; padding: 0px; float: left; width: 250px;">
            <a href="' . $boardurl . '/perfil/' . $context['member']['name'] . '/amigos-en-comun/">1 amigo en com&uacute;n</a>
          </p>';
      } else if (!empty($cantidaddss) >= 2) {
        echo '
          <p style="margin: 0px; padding: 0px; float: left; width: 250px;">
            <a href="' . $boardurl . '/perfil/' . $context['member']['name'] . '/amigos-en-comun/">' . $cantidaddss . ' amigos en com&uacute;n</a>
          </p>';
      }

      if (!empty($bbvxc) === 1) {
        echo '
          <p align="right" style="margin: 0px; padding: 0px; width: 250px;">
            <a href="' . $boardurl . '/perfil/' . $context['member']['name'] . '/lista-de-amigos/">1 amigo</a>
          </p>';
      } else if (!empty($bbvxc) >= 2) {
        echo '
          <p align="right" style="margin: 0px; padding: 0px;">
            <a href="' . $boardurl . '/perfil/' . $context['member']['name'] . '/lista-de-amigos/">' . $bbvxc . ' amigos</a>
          </p>';
      }

      if ((!empty($cantidaddss) === 1) || (!empty($cantidaddss) >= 2) || (!empty($bbvxc) === 1) || (!empty($bbvxc) >= 2)) {
        echo '<div class="hrs"></div>';
      }

      $mostrarmuros = db_query("
        SELECT user, id, fecha, amigo
        FROM {$db_prefix}amistad
        WHERE user = {$context['member']['id']}
        OR amigo = {$context['member']['id']}
        AND acepto = 1
        ORDER BY fecha DESC
        LIMIT $RegistrosAEmpezar, $RegistrosAMostrar", __FILE__, __LINE__);

      while ($mostrarmuros1 = mysqli_fetch_array($mostrarmuros)) {
        $sdasdc = $mostrarmuros1['amigo'] != $context['member']['id'] ? $mostrarmuros1['amigo'] : $mostrarmuros1['user'];
        $ivvd = $mostrarmuros1['id'];
        $yata = timeformat($mostrarmuros1['fecha']);

        $datosmem = db_query("
          SELECT ID_MEMBER, personalText, avatar, realName
          FROM {$db_prefix}members
          WHERE ID_MEMBER = $sdasdc
          LIMIT 1", __FILE__, __LINE__);

        while ($data = mysqli_fetch_assoc($datosmem)) {
          $nombremem = $data['realName'];
          $avatar = $data['avatar'];
          $pt = $data['personalText'];
          $conejjssuu = $data['ID_MEMBER'];
        }

        mysqli_free_result($datosmem);

        $request = db_query("
          SELECT ID_MEMBER
          FROM {$db_prefix}log_online
          WHERE ID_MEMBER = $conejjssuu
          LIMIT 1", __FILE__, __LINE__);

        $esta = mysqli_num_rows($request);
        $predd = $ID_MEMBER == $context['member']['id'] ? 'Eres ' : 'Es ';

        echo '
          <div id="amig_' . $ivvd . '">
            <div class="muroEfect" id="muroEfectAV">
              <table>
                <tr>
                  <td valign="top">
                    <img style="width: 50px; height: 50px;" class="avatar-box" alt="" src="' . (empty($avatar) ? $no_avatar : $avatar) . '" onerror="error_avatar(this)" />
                  </td>
                  <td valign="top" style="margin: 0px; padding: 4px;">
                    ' . ($user_settings['ID_MEMBER'] == $context['member']['id'] ? '<img onclick="if (!confirm(\'\\xbfEst&aacute;s seguro que deseas eliminar a este usuario de tus amigos?\')) return false; EliminarAmistad(\'' . $conejjssuu . '\', \'' . $ivvd . '\'); return false;" style="cursor: pointer;" alt="" title="Eliminar usuario de mi lista de amigos" src="' . $tranfer1 . '/comunidades/eliminar.png" class="png" width="16px" height="16px" />&#32;-&#32;' : '') . '
                    <b>
                      <span style="font-size: 12px;">
                        <a href="' . $boardurl . '/perfil/' . $nombremem . '" title="' . $nombremem . '" style="font-size: 13px; color: #666666;">' . $nombremem . '</a>
                      </span>
                    </b>';
        if (!empty($pt)) {
          echo '<span style="font-size: 11px;">&#32;-&#32;' . $pt . '</span>';
        }

        if ($user_settings['ID_MEMBER'] == $context['member']['id']) {
          if ($esta == 1) {
            echo '&#32;-&#32;<img src="' . $tranfer1 . '/icons/si.png" alt="" class="png" width="16px" height="16px" title="Conectado/a" />';
          }
          if (empty($esta)) {
            echo '&#32;-&#32;<img src="' . $tranfer1 . '/icons/no.png" alt="" width="16px" height="16px" class="png" title="Desconectado/a" />';
          }
        }
        echo '
                    </span>
                    <br />
                    <span style="color: green; font-size: 11px;">
                      <b>' . $predd . ' amigo desde:</b>
                      ' . $yata . '
                    </span>
                  </td>
                </tr>
              </table>
            </div>
          </div>
          <div class="noesta" id="error_' . $ivvd . '" style="display: none; filter: alpha(opacity=65); -moz-opacity: .65; opacity: .65;"></div>
          <div class="hrs"></div>';
      }

      mysqli_free_result($mostrarmuros);

      echo '</div>';
    } else {
      echo '<div class="noestaGR" style="width: 541px;">' . $context['member']['name'] . ' no tiene ning&uacute;n amigo.</div>';
    }

    $PagAnt = $PagAct - 1;
    $PagSig = $PagAct + 1;
    $PagUlt = $NroRegistros / $RegistrosAMostrar;
    $Res = $NroRegistros % $RegistrosAMostrar;

    if ($Res > 0) {
      $PagUlt = floor($PagUlt) + 1;
    }

    if ($PagAct > 1 || $PagAct < $PagUlt) {
      echo '<div class="windowbgpag" style="width: 200px;">';

      if ($PagAct > 1) {
        echo '<a href="' . $boardurl . '/perfil/' . $context['member']['name'] . '/lista-de-amigos-pag-' . $PagAnt . '">&#171; anterior</a>';
      }

      if ($PagAct < $PagUlt) {
        echo '<a href="' . $boardurl . '/perfil/' . $context['member']['name'] . '/lista-de-amigos-pag-' . $PagSig . '">siguiente &#187;</a>';
      }

      echo '
          <div class="clearBoth"></div>
        </div>';
    }
  } else if ($tipo == 0) {
    // Muro
    partearriba($context['member']['name'], $lugar);

    $_GET['ccIDmuro'] = isset($_GET['ccIDmuro']) ? (int) $_GET['ccIDmuro'] : '';

    if (empty($_GET['ccIDmuro'])) {
      $RegistrosAMostrar = 15;
      $dud = $pagq1 < 1 ? 1 : $pagq1;

      if (isset($dud)) {
        $RegistrosAEmpezar = ($dud - 1) * $RegistrosAMostrar;
        $PagAct = $dud;
      } else {
        $RegistrosAEmpezar = 0;
        $PagAct = 1;
      }

      if ($context['user']['is_guest']) {
        echo '
          <div style="clear: left;">
            <div class="noesta-am" style="width: 541px; margin-bottom: 8px;">
              Para poder comentar este muro es necesario estar <a href="' . $boardurl . '/registrarse/" style="color: #FFB600;" title="Registrarse">REGISTRADO</a>.
              <br />
              Si ya tienes usuario <a href="javascript:irAconectarse();" style="color:#FFB600;" title="Conectarse">&iexcl;CON&Eacute;CTATE!</a>
            </div>
          </div>';
      } else {
        if ($user_settings['ID_MEMBER'] === $context['member']['id']) {
          echo '
            <form action="' . $boardurl . '/web/cw-comentarMuro.php" method="post" accept-charset="' . $context['character_set'] . '" style="margin: 0px; padding: 0px;">
              <div class="muroQh">
                <input title="&#191;Qu&eacute; est&aacute;s haciendo ahora&#63;" onfocus="if (this.value == \'&#191;Qu&eacute; est&aacute;s haciendo ahora&#63;\') this.value = \'\'; $(\'#qh_publicar_bottom\').css(\'display\', \'block\'); foco(this);" onblur="if (this.value == \'\') { $(\'#qh_publicar_bottom\').css(\'display\', \'none\'); this.value = \'&#191;Qu&eacute; est&aacute;s haciendo ahora&#63;\'; } no_foco(this);" style="width: 517px; font-size: 11px; font-family: Arial,FreeSans;" name="quehago" value="&#191;Qu&eacute; est&aacute;s haciendo ahora&#63;" type="text" />
                <p style="padding: 0px; margin: 0px; display: none;" id="qh_publicar_bottom" align="right">
                  <input class="login" style="margin-top: 2px;" value="Publicar" type="submit" />
                </p>
                <div class="clearBoth"></div>
              </div>
            </form>';
        }

        $request = db_query("
          SELECT id_user
          FROM {$db_prefix}pm_admitir
          WHERE id_user = {$context['member']['id']}
          AND quien = $ID_MEMBER
          LIMIT 1", __FILE__, __LINE__);

        $ignorado = mysqli_num_rows($request);

        if (!$ignorado) {
          echo '
            <div class="muroEscribir">
              <textarea title="Escribe algo..." onfocus="if (this.value == \'Escribe algo...\') this.value = \'\'; $(\'#muro_publicar_bottom\').css(\'display\', \'block\'); foco(this);" onblur="if (this.value == \'\') { $(\'#muro_publicar_bottom\').css(\'display\', \'none\'); this.value = \'Escribe algo...\'; } no_foco(this);" style="height: 30px; overflow: visible; width: 517px; font-size: 11px; font-family: Arial,FreeSans;" name="muro" id="muro">Escribe algo...</textarea>
              <p style="padding: 0px; margin: 0px; display: none;" id="muro_publicar_bottom" align="right">
                <input class="login" value="Publicar" style="margin-top: 2px;" onclick="add_muro(\'' . $context['member']['id'] . '\'); return false;" type="button" id="button_add_muro" />
                <input value="' . $PagAct . '" type="hidden" name="datapagss" id="datapagss" />
                <img alt="" src="' . $tranfer1 . '/icons/cargando.gif" style="width: 16px; height: 16px;display: none;" id="gif_cargando_add_muro" border="0" />
              </p>
              <div class="clearBoth"></div>
            </div>
            <div class="msg_add_muro" style="width: 541px; margin-bottom: 4px; display: none;"></div>';
        }
      }

      if ($cantidadmuro) {
        echo '
          <div class="clearBoth"></div>
            <div class="windowbg" style="border-top: #C8C8C8 solid 1px; width: 523px; padding: 8px; font-size: 11px;">
              <div class="muroBug">
                <div id="return_agregar_muro"></div>';

        $mostrarmuros = db_query("
          SELECT id_user, muro, id, fecha, de, tipo, ccos
          FROM {$db_prefix}muro
          WHERE id_user = {$context['member']['id']}
          AND tipocc = 0
          ORDER BY id DESC
          LIMIT $RegistrosAEmpezar, $RegistrosAMostrar", __FILE__, __LINE__);

        while ($mostrarmuros1 = mysqli_fetch_array($mostrarmuros)) {
          $mensaje = nohtml2($mostrarmuros1['muro']);
          $ivvd = $mostrarmuros1['id'];
          $yata = hace($mostrarmuros1['fecha']);
          $cmntarioss = $mostrarmuros1['ccos'];
          $mensaje = moticon($mostrarmuros1['muro'], true);
          $filtrado = str_replace("\n", '<br />', $mensaje);

          $datosmem = db_query("
            SELECT realName, avatar
            FROM {$db_prefix}members
            WHERE ID_MEMBER = {$mostrarmuros1['de']}
            LIMIT 1", __FILE__, __LINE__);

          while ($data = mysqli_fetch_assoc($datosmem)) {
            $nombremem = $data['realName'];
            $avatar = $data['avatar'];
          }

          mysqli_free_result($datosmem);

          echo '
            <div id="muro-' . $ivvd . '">
              <div id="muroEfectAV">
                <table>
                  <tr>
                    <td valign="top">
                      <a href="' . $boardurl . '/perfil/' . $nombremem . '">
                        <img src="' . (!empty($avatar) ? $avatar : $no_avatar) . '" class="avatar-box" onerror="error_avatar(this)" width="50" height="50" alt="" />
                      </a>
                    </td>
                    <td valign="top" style="margin: 0px; font-size: 11px;">
                      <strong>
                        <a href="' . $boardurl . '/perfil/' . $nombremem . '" title="' . $nombremem . '" style="font-size: 14px; color: #D35F2C;">' . $nombremem . '</a>
                      </strong>
                      <br />
                      ' . VideosMuro($filtrado) . '
                      <div style="margin-top: 6px;">
                        ' . $yata . '
                        -
                        <span onclick="boxHablar(\'' . $ivvd . '\');" style="cursor: pointer; color: #424242;" id="c-' . $ivvd . '">Comentar</span>
                        <span style="display: ' . ($cmntarioss ? 'inline' : 'none') . '" id="vmam_' . $ivvd . '">
                          -
                          <a href="' . $boardurl . '/perfil/' . $context['member']['name'] . '/muro;ccIDmuro=' . $ivvd . '">Ver muro a muro</a>
                        </span>
                        ' . ($user_settings['ID_MEMBER'] == $context['member']['id'] || ($user_info['is_admin'] || $user_info['is_mods']) ? ' - <span class="pointer" onclick="Boxy.confirm(\'&iquest;Est&aacute;s seguro que deseas borrar este mensaje?\', function() { del_coment_muro(\'' . $ivvd . '\'); }, { title: \'Eliminar mensaje\' }); " title="Eliminar mensaje">Eliminar</span>' : '') . '
                      </div>
                    </td>
                  </tr>
                </table>';

          if ($cmntarioss > 0) {
            $jsAP = isset($_GET['jsAP']) ? (int) $_GET['jsAP'] : '';

            if ($jsAP == $ivvd) {
              $limit = '';
            } else {
              $limit = ' LIMIT 2';
            }

            $mostrarcmtarios = db_query("
              SELECT id_user, muro, id, fecha, de
              FROM {$db_prefix}muro
              WHERE id_cc = '{$ivvd}'
              AND tipocc = 1
              ORDER BY id ASC
              $limit", __FILE__, __LINE__);

            echo '<div align="center">';

            $maxrowlevel = 2;
            $rowlevel = 0;

            while ($mostrarcmtarios1 = mysqli_fetch_array($mostrarcmtarios)) {
              $sdddd = $mostrarcmtarios1['id'];
              $haces = hace($mostrarcmtarios1['fecha']);
              $mensaje2 = moticon(nohtml2(nohtml($mostrarcmtarios1['muro'])), true);
              $nombremem = getUsername($mostrarcmtarios1['de']);
              $avatar = getAvatar($mostrarcmtarios1['de']);

              echo '
                <div id="SETcto_' . $sdddd . '">
                  <div id="cto_' . $ivvd . '" class="muroCcs" style="text-align: left; color: #666666; margin-bottom: 3px;">
                    <strong>
                      <a href="' . $boardurl . '/perfil/' . $nombremem . '" style="color: #666666;" title="' . $nombremem . '">' . $nombremem . '</a>
                    </strong>
                    -
                    ' . $haces . ($user_settings['ID_MEMBER'] == $context['member']['id'] || ($user_info['is_admin'] || $user_info['is_mods']) ? ' - <span class="pointer" onclick="Boxy.confirm(\'&iquest;Est&aacute;s seguro que deseas borrar este comentario?\', function() { del_comentCC_muro(\'' . $sdddd . '\'); }, { title: \'Eliminar comentario\' });" title="Eliminar comentario">Eliminar</span>' : '') . '
                    <br />
                    ' . $mensaje2 . '
                  </div>
                </div>
                <div class="noestaGR" id="SETcto2_' . $sdddd . '" style="display: none; width: 416px; margin-bottom: 3px;"></div>';
            }

            mysqli_free_result($mostrarcmtarios);

            if ($cmntarioss > 2 && $jsAP != $ivvd) {
              $leerTXT = 'Leer m&aacute;s (' . ($cmntarioss - 2) . ')';

              if (empty($PagAct)) {
                echo '<a style="color: #D35F2C;" href="' . $boardurl . '/perfil/' . $context['member']['name'] . '/muro;jsAP=' . $ivvd . '#cto_' . $ivvd . '">' . $leerTXT . '</a>';
              } elseif (!empty($PagAct)) {
                echo '<a style="color: #D35F2C;" href="' . $boardurl . '/perfil/' . $context['member']['name'] . '/muro-pag-' . $PagAct . ';jsAP=' . $ivvd . '#cto_' . $ivvd . '">' . $leerTXT . '</a>';
              }
            }

            echo '</div>' . textarea2($ivvd);
          } else {
            echo textarea2($ivvd);
          }

          echo '
                <div class="hrs" style="margin: 0px; padding: 0px;"></div>
              </div>
            </div>';
        }

        mysqli_free_result($mostrarmuros);

        echo '
            </div>
          </div>';
      } else {
        echo '
          <div class="clearBoth"></div>
          <div class="windowbg" id="si_muro" style="border-top: #C8C8C8 solid 1px; width: 523px; padding: 8px; font-size: 11px; display: none;">
            <div class="muroBug">
              <div id="return_agregar_muro"></div>
            </div>
          </div>
          <div id="no_muro" style="width: 541px" class="noestaGR">No hay mensajes en este muro.</div>';
      }

      $NroRegistros = $cantidadmuro;
      $PagAnt = $PagAct - 1;
      $PagSig = $PagAct + 1;
      $PagUlt = $NroRegistros / $RegistrosAMostrar;
      $Res = $NroRegistros % $RegistrosAMostrar;

      if ($Res > 0) {
        $PagUlt = floor($PagUlt) + 1;
      }

      if ($PagAct > 1 || $PagAct < $PagUlt) {
        echo '<div class="windowbgpag" style="width: 524px;">';

        if ($PagAct > 1) {
          echo '<a href="' . $boardurl . '/perfil/' . $context['member']['name'] . '/muro-pag-' . $PagAnt . '">&#171; anterior</a>';
        }

        if ($PagAct < $PagUlt) {
          echo '<a href="' . $boardurl . '/perfil/' . $context['member']['name'] . '/muro-pag-' . $PagSig . '">siguiente &#187;</a>';
        }

        echo '
            <div class="clearBoth"></div>
          </div>';
      }
    } else {
      // SOLO 1 comentario////////////////////////////
      // /////////////////////////////////////////////

      $mostrarmuros = db_query("
        SELECT id_user, muro, id, fecha, de, tipo, ccos
        FROM {$db_prefix}muro
        WHERE id = {$_GET['ccIDmuro']}
        AND id_user = {$context['member']['id']}
        LIMIT 1", __FILE__, __LINE__);

      while ($mostrarmuros1 = mysqli_fetch_array($mostrarmuros)) {
        $mensaje = nohtml2($mostrarmuros1['muro']);
        $ivvd = $mostrarmuros1['id'];
        $yata = timeformat($mostrarmuros1['fecha']);
        $cmntarioss = $mostrarmuros1['ccos'];
        $mensaje = moticon($mostrarmuros1['muro'], true);
        $filtrado = str_replace("\n", '<br />', $mensaje);

        echo '
          <div class="windowbg" style="border-top: #C8C8C8 solid 1px; width: 523px; padding: 8px; font-size: 11px;">
            <div class="muroBug">';

        $datosmem = db_query("
          SELECT realName, avatar
          FROM {$db_prefix}members
          WHERE ID_MEMBER = {$mostrarmuros1['de']}
          LIMIT 1", __FILE__, __LINE__);

        while ($data = mysqli_fetch_assoc($datosmem)) {
          $nombremem = $data['realName'];
          $avatar = $data['avatar'];
        }

        mysqli_free_result($datosmem);

        $AVA = empty($avatar) ? $no_avatar : $avatar;

        echo '
          <div id="muro-' . $ivvd . '">
            <div id="muroEfectAV">
              <table>
                <tr>
                  <td valign="top">
                    <a href="' . $boardurl . '/perfil/' . $nombremem . '">
                      <img src="' . $AVA . '" class="avatar-box" onerror="error_avatar(this)" width="50" height="50" alt="" />
                    </a>
                  </td>
                  <td valign="top" style="margin: 0px; font-size: 11px;">
                    <strong>
                      <a href="' . $boardurl . '/perfil/' . $nombremem . '" title="' . $nombremem . '" style="font-size: 14px; color: #D35F2C;">' . $nombremem . '</a>
                    </strong>
                    <br />
                    ' . VideosMuro($filtrado) . '
                    <div style="margin-top: 6px;">
                      ' . $yata . '
                      &#32;-&#32;
                      <span onclick="boxHablar(\'' . $ivvd . '\');" style="cursor: pointer; color: #424242;" id="c-' . $ivvd . '">Comentar</span>
                      ' . ($user_settings['ID_MEMBER'] == $context['member']['id'] || ($user_info['is_admin'] || $user_info['is_mods']) ? '&#32;-&#32;<a onclick="Boxy.confirm(\'&iquest;Est&aacute;s seguro que deseas borrar este mensaje?\', function() { del_coment_muro(\'' . $ivvd . '\'); }, { title: \'Eliminar mensaje\' }); " href="#" title="Eliminar mensaje">eliminar</a>' : '') . '
                    </div>
                  </td>
                </tr>
              </table>';

        $mostrarcmtarios = db_query("
          SELECT id_user, muro, id, fecha, de
          FROM {$db_prefix}muro
          WHERE id_cc = $ivvd
          AND tipocc = 1
          ORDER BY id ASC", __FILE__, __LINE__);

        while ($mostrarcmtarios1 = mysqli_fetch_array($mostrarcmtarios)) {
          echo '<div align="center">';

          $sdddd = $mostrarcmtarios1['id'];
          $haces = hace($mostrarcmtarios1['fecha']);
          $mensaje2 = moticon(nohtml2(nohtml($mostrarcmtarios1['muro'])), true);

          $datosmem = db_query("
            SELECT ID_MEMBER, realName, avatar
            FROM {$db_prefix}members
            WHERE ID_MEMBER = {$mostrarcmtarios1['de']}
            LIMIT 1", __FILE__, __LINE__);

          while ($data = mysqli_fetch_assoc($datosmem)) {
            $nombremem = $data['realName'];
            $avatar = $data['avatar'];
          }

          mysqli_free_result($datosmem);

          echo '
              <div id="SETcto_' . $sdddd . '">
                <div id="cto_' . $ivvd . '" class="muroCcs" style="text-align: left; color: #666666; margin-bottom: 3px;">
                  <strong>
                    <a href="' . $boardurl . '/perfil/' . $nombremem . '" style="color: #666666;" title="' . $nombremem . '">' . $nombremem . '</a>
                  </strong>
                  &#32;-&#32;
                  ' . $haces . '
                  ' . ($user_settings['ID_MEMBER'] == $context['member']['id'] || ($user_info['is_admin'] || $user_info['is_mods']) ? '&#32;-&#32;<a onclick="Boxy.confirm(\'&iquest;Est&aacute;s seguro que deseas borrar este comentario?\', function() { del_comentCC_muro(\'' . $sdddd . '\'); }, { title: \'Eliminar comentario\' });" href="#" title="Eliminar comentario">eliminar</a>' : '') . '
                  <br />
                  ' . $mensaje2 . '
                </div>
              </div>
              <div class="noestaGR" id="SETcto2_' . $sdddd . '" style="display: none; width: 416px; margin-bottom: 3px;"></div>
            </div>';
        }

        mysqli_free_result($mostrarcmtarios);

        echo textarea2($ivvd, 1);

        echo '
                </div>
              </div>
            </div>
          </div>';

        $dac = 1;
      }

      mysqli_free_result($mostrarmuros);

      $dac = isset($dac) ? $dac : '';

      if (empty($dac)) {
        echo '<div class="noestaGR">Este comentario no existe.</div>';
      }
    }
  } else if ($tipo == 1) {
    // Apariencia
    partearriba($context['member']['name'], $lugar);

    $dataquien = isset($dataquien) ? $dataquien : '';
    $refoagr2 = db_query("
      SELECT user
      FROM {$db_prefix}amistad
      WHERE user = {$context['member']['id']}
      AND amigo = $ID_MEMBER
      AND acepto = 1", __FILE__, __LINE__);

    while ($mddddd4 = mysqli_fetch_array($refoagr2)) {
      $esfeee = $mddddd4['user'];
    }

    mysqli_free_result($refoagr2);

    echo '
      <div style="width: 541px;">
        <div class="perfil-content general">
          <div class="widget big-info clearfix">
            <div class="title-w clearfix">
              <h3 style="width: 541px;">Apariencia de ' . $context['member']['name'] . '</h3>
            </div>
            <ul>';

    if (!$dataquien) {
      // A TODOS
      apariencia($context['member']['id']);
    } else if ($dataquien == 1 && ($user_settings['ID_MEMBER'] != $context['member']['id'])) {
      // A NADIE
      echo '<div class="noesta">No puedes ver la apariencia de ' . $context['member']['name'] . '.</div>';
    } else if ($dataquien == 2 && (!empty($esfeee) || $user_settings['ID_MEMBER'] == $context['member']['id'])) {
      // A AMIGOS
      echo '<div class="noesta">S&oacute;lo amigos de ' . $context['member']['name'] . ' pueden ver la apariencia.</div>';
    } else if ($dataquien == 3 && $context['user']['is_guest']) {
      // A REGISTRADOS
      echo '<div class="noestaGR">S&oacute;lo usuarios registrados pueden esta apariencia.</div>';
    } else {
      // A TODOS
      apariencia($context['member']['id']);
    }

    echo '
            </ul>
          </div>
        </div>
      </div>';
  } else if ($tipo == 5) {
    // COMUNIDADES
    partearriba($context['member']['name'], $lugar);

    $request = db_query("
      SELECT m.id
      FROM {$db_prefix}comunidades_articulos AS m, {$db_prefix}comunidades AS p
      WHERE m.id_user = {$context['member']['id']}
      AND m.eliminado = 0
      AND m.id_com = p.id
      AND p.bloquear = 0
      AND p.acceso <> 4", __FILE__, __LINE__);

    $NroRegistros = mysqli_num_rows($request);

    if ($NroRegistros) {
      $RegistrosAMostrar = 20;
      $dud = $pagq1 < 1 ? 1 : $pagq1;

      if (isset($dud)) {
        $RegistrosAEmpezar = ($dud - 1) * $RegistrosAMostrar;
        $PagAct = $dud;
      } else {
        $RegistrosAEmpezar = 0;
        $PagAct = 1;
      }

      $request = db_query("
        SELECT p.url, p.nombre, m.titulo, p.categoria, m.id
        FROM {$db_prefix}comunidades_articulos AS m
        INNER JOIN {$db_prefix}comunidades AS p ON m.id_user = {$context['member']['id']}
        AND m.eliminado = 0
        AND m.id_com = p.id
        AND p.bloquear = 0
        AND p.acceso <> 4
        ORDER BY m.id DESC
        LIMIT $RegistrosAEmpezar, $RegistrosAMostrar", __FILE__, __LINE__);

      echo '
        <div style="width: 541px;">
          <div class="clearBoth"></div>
          <div class="title-w clearfix">
            <h3>&Uacute;ltimos temas creados</h3>
          </div>
          <ul class="ultimos">';

      while ($row = mysqli_fetch_assoc($request)) {
        $context['rownombre999'] = $row['nombre'];
        $titulo = $row['titulo'];
        $cort2 = strlen($titulo) > 45 ? substr($titulo, 0, 42) . '...' : $titulo;

        echo '
          <li>
            <img title="Comunidades" src="' . $tranfer1 . '/comunidades/categorias/' . $row['categoria'] . '.png" class="png" alt="" />
            <a title="' . $titulo . '"  href="' . $boardurl . '/comunidades/' . $row['url'] . '/' . $row['id'] . '/' . urls($row['titulo']) . '.html">' . $cort2 . '</a>
            <div>
              <span>En <a title="' . $context['rownombre999'] . '" href="' . $boardurl . '/comunidades/' . $row['url'] . '">' . $context['rownombre999'] . '</a></span>
            </div>
          </li>';
      }

      mysqli_free_result($request);

      echo '
          </ul>
        </div>
        <div class="clearBoth"></div>';

      $PagAnt = $PagAct - 1;
      $PagSig = $PagAct + 1;
      $PagUlt = $NroRegistros / $RegistrosAMostrar;
      $Res = $NroRegistros % $RegistrosAMostrar;

      if ($Res > 0) {
        $PagUlt = floor($PagUlt) + 1;
      }

      if ($PagAct < $PagUlt) {
        echo '<div class="windowbgpag" style="width: 200px;">';

        if ($PagAct > 1) {
          echo '<a href="' . $boardurl . '/perfil/' . $context['member']['name'] . '/comunidades-pag-' . $PagAnt . '">&#171; anterior</a>';
        }

        if ($PagAct < $PagUlt) {
          echo '<a href="' . $boardurl . '/perfil/' . $context['member']['name'] . '/comunidades-pag-' . $PagSig . '">siguiente &#187</a>';
        }

        echo '</div>';
      }
    } else {
      echo '<div class="noestaGR" style="width: 541px;">Este usuario no tiene comunidades creadas.</div>';
    }
  } else {
    echo '<div class="noestaGR" style="width: 541px;">Acci&oacute;n no reconocida.</div>';
  }

  // parte abajo
  echo '
    </div>
    <div style="float: right; width: 373px;">
      <div style="margin-bottom: 10px; clear: Both;">';

  $context['yadio2'] = isset($context['yadio2']) ? $context['yadio2'] : '';

  if ($context['yadio2']) {
    echo '<div class="noesta-am" style="margin-bottom: 5px;">Esperando aceptaci&oacute;n de amistad.</div>';
  }

  echo '
    <div class="BoxUserMenu" align="center">
      <div style="height: auto; border-top: #fff solid 1px; border-bottom: none; border-right: #fff solid 1px; border-left: #fff solid 1px; padding: 4px;">
        <div class="AvatarBox">';

  if (!empty($context['member']['avatar']['image'])) {
    echo $context['member']['avatar']['image'];
  } else {
    echo '<img alt="" src="' . $no_avatar . '" border="0" alt="Sin Avatar" onerror="error_avatar(this)" />';
  }

  if ($user_info['is_admin'] || $user_info['is_mods']) {
    $d10 = '<a href="' . $boardurl . '/web/cw-TEMPquienDioptsPost.php?id=' . $context['member']['id'] . '" title="30 posts" class="boxy">';
    $d2 = '</a>';
  }

  echo '
      </div>
    </div>
    <div class="AccionMem2">';

  if (!$user_info['is_guest']) {
    if ($user_settings['ID_MEMBER'] != $context['member']['id']) {
      echo '
        <div class="AccionMem">
          <a href="' . $boardurl . '/web/cw-TEMPenviarMP.php?user=' . $context['member']['name'] . '" title="Enviar MP a ' . $context['member']['name'] . '" class="boxy">Enviarle MP</a>
        </div>';

      if (!$context['yadio2']) {
        echo '<div class="AccionMem">';

        if ($context['yadio']) {
          echo '<a href="' . $boardurl . '/web/cw-AmistadBorrar.php?user=' . $context['member']['name'] . '" title="Quitar amistad">Quitar amistad</a>';
        } else {
          echo '<a href="' . $boardurl . '/web/cw-AmistadNuevo.php?user=' . $context['member']['name'] . '" title="Agregar amistad">Agregar amistad</a>';
        }

        echo '</div>';
      }

      echo '
        <div class="AccionMem">
          <a  href="#" onclick="Boxy.load(\'' . $boardurl . '/web/cw-denunciaTEMP.php?t=0;d=' . $context['member']['name'] . "', { title: 'Denunciar a " . $context['member']['name'] . '\' });" title="Denunciar a ' . $context['member']['name'] . '">Denunciar usuario</a>
        </div>';

      if (!$context['mpno']) {
        $nostyle2 = ' style="display: none;"';
        $nostyle = '';
      } else {
        $nostyle2 = '';
        $nostyle = ' style="display: none;"';
      }

      echo '
        <div class="AccionMem" id="des"' . $nostyle . '>
          <a href="#" onclick="ignorar(\'' . $context['member']['id'] . '\', \'2\'); return false;" title="Desdmitir usuario">Desadmitir usuario</a>
        </div>
        <div class="AccionMem" id="admitir"' . $nostyle2 . '>
          <a  href="#" onclick="ignorar(\'' . $context['member']['id'] . '\', \'1\'); return false;" title="Admitir usuario">Admitir usuario</a>
        </div>';

      if (($user_info['is_admin'] || $user_info['is_mods']) && $context['member']['id'] != 1) {
        echo '
          <div class="AccionMem">
            <a href="' . $boardurl . '/moderacion/edit-user/perfil/' . $context['member']['id'] . '">Moderar usuario</a>
          </div>';
      }
    }
  }

  echo '
      </div>
    </div>
    <div class="clearBoth" style="float: left; padding-left: 4px; width: 227px;">
      <div class="title-w">
        <h3>Estad&iacute;sticas del usuario</h3>
      </div>
      <div class="estadisticas">
        <div class="Dd">
          <div style="float: left;">
            <h4>Rango:</h4>
          </div>
          <div style"float: right;">
            <span>' . (!empty($context['member']['group']) ? $context['member']['group'] : $context['member']['post_group']) . '</span>
          </div>
        </div>
        <div class="Dd">
          <div style="float: left;">
            <h4>Comentarios en su muro:</h4>
          </div>
          <div style"float: right;">
            <span id="cantmuro">' . $cantidadmuro . '</span>
          </div>
        </div>
        <div class="Dd">
          <div style="float: left;">
            <h4>Comentarios:</h4>
          </div>
          <div style"float: right;">
            <span>' . (usuarioComentariosPOST($context['member']['id']) + usuarioComentariosIMG($context['member']['id'])) . '</span>
          </div>
        </div>
        <div class="Dd">
          <div style="float: left;">
            <h4>Posts:</h4>
          </div>
          <div style"float: right;">
            <span>
              <a href="' . $boardurl . '/buscador/&q=&autor=' . $context['member']['name'] . '&orden=fecha&categoria=0">' . $context['postuser'] . '</a>
            </span>
          </div>
        </div>';

  $d10 = isset($d10) ? $d10 : '';
  $d2 = isset($d2) ? $d2 : '';

  echo '
          <div class="Dd">
            <div style="float: left;">
              <h4>Puntos:</h4>
            </div>
            <div style"float: right;">
              <span>' . $d10 . $context['member']['posts'] . $d2 . '</span>
            </div>
          </div>
          <div class="Dd">
            <div style="float: left;">
              <h4>Im&aacute;genes:</h4>
            </div>
            <div style"float: right;">
              <span>
                <a href="' . $boardurl . '/imagenes/' . $context['member']['name'] . '">' . $context['count'] . '</a>
              </span>
            </div>
          </div>
          <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
      </div>
      <div class="clearfix"></div>
    </div>
    <div style="margin-bottom: 10px;">
      <div class="title-w">
        <div class="clearfix"></div>
        <h3>&Uacute;ltimos posts creados</h3>
      </div>
      <ul class="ultimos">';

  foreach ($context['posts'] as $post) {
    $tit2 = strlen($post['subject']) > 45 ? substr($post['subject'], 0, 42) . '...' : $post['subject'];

    echo '
      <li>
        <a href="' . $boardurl . '/post/' . $post['topic'] . '/' . $post['board']['description'] . '/' . urls($post['subject']) . '.html" target="_self" title="' . $post['subject'] . '" class="categoriaPost ' . $post['board']['description'] . '">' . $tit2 . '</a>
      </li>';
  }

  echo '</ul>';

  if (!empty($context['posts'])) {
    echo '
      <div class="clearBoth" style="height: 21px;">
        <div class="windowbgpag" style="float: right;">
          <a href="' . $boardurl . '/buscador/&q=&autor=' . $context['member']['name'] . '&orden=fecha&categoria=0">ver m&aacute;s</a>
        </div>
      </div>';
  } else {
    echo '<div class="noestaGR">' . $nopost . '</div>';
  }

  echo '</div>';

  if ($lugar != 'comunidades') {
    echo '<div style="margin-bottom: 10px;">';

    $request = db_query("
      SELECT p.url, p.nombre, m.titulo, p.categoria, m.id
      FROM {$db_prefix}comunidades_articulos AS m
      INNER JOIN {$db_prefix}comunidades AS p ON m.id_user = {$context['member']['id']}
      AND m.eliminado = 0
      AND m.id_com = p.id
      AND p.bloquear = 0
      AND p.acceso <> 4
      ORDER BY m.id DESC
      LIMIT 10", __FILE__, __LINE__);

    echo '
      <div class="title-w">
        <h3>&Uacute;ltimos temas creados</h3>
      </div>
      <ul class="ultimos">';

    while ($row = mysqli_fetch_assoc($request)) {
      $context['rownombre2'] = $row['nombre'];
      $cort2 = strlen($row['titulo']) > 45 ? substr($row['titulo'], 0, 42) . '...' : $row['titulo'];

      echo '
        <li>
          <img title="Comunidades" src="' . $tranfer1 . '/comunidades/categorias/' . $row['categoria'] . '.png" class="png" alt="" />
          <a title="' . $row['titulo'] . '"  href="' . $boardurl . '/comunidades/' . $row['url'] . '/' . $row['id'] . '/' . urls($row['titulo']) . '.html">' . $cort2 . '</a>
          <div>
            <span>
              En
              <a title="' . $context['rownombre2'] . '" href="' . $boardurl . '/comunidades/' . $row['url'] . '">' . $context['rownombre2'] . '</a>
            </span>
          </div>
        </li>';
    }

    mysqli_free_result($request);

    echo '</ul>';

    $context['rownombre2'] = isset($context['rownombre2']) ? $context['rownombre2'] : '';

    if (!$context['rownombre2']) {
      echo '<div class="noestaGR">No ha creado temas.</div>';
    } else {
      echo '
        <div class="clearBoth" style="height: 21px;">
          <div class="windowbgpag" style="float: right;">
            <a href="' . $boardurl . '/perfil/' . $context['member']['name'] . '/comunidades">ver m&aacute;s</a>
          </div>
        </div>';
    }

    echo '</div>';
  }

  echo '
    <div style="margin-bottom: 10px;">
      <div class="title-w">
        <h3>Comunidades en las que participa</h3>
      </div>';

  $request = db_query("
    SELECT p.nombre, p.url, p.UserName
    FROM {$db_prefix}comunidades_miembros AS m
    INNER JOIN {$db_prefix}comunidades AS p ON m.id_user = {$context['member']['id']}
    AND m.id_com = p.id
    AND p.bloquear = 0
    AND p.acceso <> 4
    ORDER BY p.id DESC
    LIMIT 10", __FILE__, __LINE__);

  while ($row = mysqli_fetch_assoc($request)) {
    $context['rownombre'] = nohtml(nohtml2($row['nombre']));

    if ($row['UserName'] == $context['member']['name']) {
      $enlazar[] = '
        <a title="' . $context['rownombre'] . ' (Creador)" href="' . $boardurl . '/comunidades/' . $row['url'] . '" class="titlePost" style="color: #FFCF0F;">
          <strong>' . $context['rownombre'] . '</strong>
        </a>';
    } else {
      $enlazar[] = '<a title="' . $context['rownombre'] . '" href="' . $boardurl . '/comunidades/' . $row['url'] . '" class="titlePost">' . $context['rownombre'] . '</a>';
    }
  }

  mysqli_free_result($request);

  $context['rownombre'] = isset($context['rownombre']) ? $context['rownombre'] : '';

  if (!$context['rownombre']) {
    echo '<div class="noestaGR">No participa de comunidades.</div>';
  } else {
    echo '
      <div>
        ' . join(' - ', $enlazar) . '
        <div class="clearBoth"></div>
      </div>
      <div class="clearBoth" style="height: 21px;">
        <div class="windowbgpag" style="float: right;">
          <a href="' . $boardurl . '/comunidades/buscar/&q=&autor=' . $context['member']['name'] . '&orden=fecha&categoria=&buscador_tipo=c">ver m&aacute;s</a>
        </div>
      </div>';
  }

  echo '
    </div>
    <div style="margin-bottom: 10px;">
      <div class="title-w">
        <h3>&Uacute;ltimas im&aacute;genes</h3>
      </div>';

  if ($context['count']) {
    echo '<table align="center">';

    $maxrowlevel = 6;
    $rowlevel = 0;

    foreach ($context['img'] as $img) {
      if ($rowlevel < ($maxrowlevel + 1)) {
        $rowlevel++;
      } else {
        echo '<tr>';
        $rowlevel = 0;
      }

      echo '
        <td>
          <a href="' . $boardurl . '/imagenes/ver/' . $img['id'] . '">
            <img alt="" title="' . $img['title'] . '" style="width: 85px; height: 70px;" class="avatar-box" src="' . $img['filename'] . '" border="0" />
          </a>
        </td>';

      if ($rowlevel < ($maxrowlevel + 1)) {
        $rowlevel++;
      } else {
        echo '</tr>';
        $rowlevel = 0;
      }
    }

    echo '
      </table>
      <div class="clearBoth" style="height: 21px;">
        <div class="windowbgpag" style="float: right;">
          <a href="' . $boardurl . '/imagenes/' . $context['member']['name'] . '">ver m&aacute;s</a>
        </div>
      </div>';
  } else {
    echo '<div class="noestaGR">' . $noimg . '</div>';
  }

  echo '</div>';

  if (!$user_info['is_guest']) {
    if ($user_settings['ID_MEMBER'] != $context['member']['id']) {
      if (!empty($cantidaddss)) {
        echo '
          <div style="margin-bottom: 10px;">
            <div class="title-w">
              <h3>Amigos en com&uacute;n</h3>
            </div>
            <div style="float: left; font-size: 10px;">';

        if ($cantidaddss == 1) {
          echo '
            <p style="margin: 0px; padding: 0px;">
              <a href="#" title="1 amigo en com&uacute;n">1 amigo en com&uacute;n</a>
            </p>';
        } else if ($cantidaddss >= 2) {
          echo '
            <p style="margin: 0px; padding: 0px;">
              <a href="' . $boardurl . '/perfil/' . $context['member']['name'] . '/lista-de-amigos/" title="' . $cantidaddss . ' amigos en com&uacute;n">' . $cantidaddss . ' amigos en com&uacute;n</a>
            </p>';
        }

        echo '
          </div>
          <div style="font-size: 10px;">
            <p align="right" style="margin: 0px; padding: 0px;">
              <a href="' . $boardurl . '/perfil/' . $context['member']['name'] . '/amigos-en-comun/" title="Ver todos">Ver todos</a>
            </p>
          </div>
          <div class="hrs"></div>
          <center>
            <table>
               <tr>';

        $dasdasdasds = 1;

        $q3 = db_query("
          SELECT user, amigo
          FROM {$db_prefix}amistad
          WHERE user = {$context['member']['id']}
          AND amigo IN (" . join(',', $amigos_en_comun) . ")
          OR user IN (" . join(',', $amigos_en_comun) . ")
          AND amigo = {$context['member']['id']}
          AND acepto = 1
          ORDER BY RAND()
          LIMIT 16", __FILE__, __LINE__);

        while ($row3 = mysqli_fetch_assoc($q3)) {
          if ($row3['amigo'] != $context['member']['id']) {
            $sdasdsddvv = $row3['amigo'];
          } else {
            $sdasdsddvv = $row3['user'];
          }

          $q1 = db_query("
            SELECT m.avatar, m.realName
            FROM ({$db_prefix}members as m)
            WHERE m.ID_MEMBER='$sdasdsddvv'
            LIMIT 1", __FILE__, __LINE__);

          $row = mysqli_fetch_assoc($q1);
          $nombremem = isset($row['realName']) ? $row['realName'] : '';
          $avatar = isset($row['avatar']) ? $row['avatar'] : '';

          echo '
            <td align="center" style="font-size: 11px; font-family: arial; margin: 0px; padding: 0px;">
              <a href="' . $boardurl . '/perfil/' . $nombremem . '" title="' . $nombremem . '" style="text-decoration: none;">
                <img class="avatar-box" style="width: 40px; height: 40px;" alt="" src="' . (empty($avatar) ? $no_avatar : $avatar) . '" onerror="error_avatar(this)" title="' . $nombremem . '" />
              </a>
            </td>';

          if ($dasdasdasds++ == 8) {
            echo '
              </tr>
              <tr>';
          }
        }

        echo '
                </tr>
              </table>
            </center>
          </div>';
      }
    }
  }

  if ($lugar != 'lista-de-amigos' && !empty($bbvxc)) {
    echo '
      <div style="margin-bottom: 10px;">
        <div class="title-w">
          <h3>Amigos</h3>
        </div>
        <div style="float: left; font-size: 10px;">';

    if ($bbvxc == 1) {
      echo '
        <p style="margin: 0px; padding: 0px;">
          <a href="#">1 amigo</a>
        </p>';
    } else if ($bbvxc >= 2) {
      echo '
        <p style="margin: 0px; padding: 0px;">
          <a href="' . $boardurl . '/perfil/' . $context['member']['name'] . '/lista-de-amigos/" title="' . $bbvxc . ' amigos">' . $bbvxc . ' amigos</a>
        </p>';
    }

    echo '
      </div>
      <div style="font-size: 10px;">
        <p align="right" style="margin: 0px; padding: 0px;">
          <a href="' . $boardurl . '/perfil/' . $context['member']['name'] . '/lista-de-amigos/" title="Ver todos">Ver todos</a>
        </p>
      </div>
      <div class="hrs"></div>
      <center>
        <table>
          <tr>';

    $pds = 1;

    $mostrarmuros = db_query("
      SELECT user, id, amigo
      FROM {$db_prefix}amistad
      WHERE user = {$context['member']['id']}
      OR amigo = {$context['member']['id']}
      AND acepto = 1
      ORDER BY RAND()
      LIMIT 16", __FILE__, __LINE__);

    while ($mostrarmuros1 = mysqli_fetch_array($mostrarmuros)) {
      if ($mostrarmuros1['amigo'] != $context['member']['id']) {
        $sdasd = $mostrarmuros1['amigo'];
      } else {
        $sdasd = $mostrarmuros1['user'];
      }

      $datosmem = db_query("
        SELECT ID_MEMBER, avatar, realName, personalText
        FROM {$db_prefix}members
        WHERE ID_MEMBER = $sdasd
        LIMIT 1", __FILE__, __LINE__);

      $data = mysqli_fetch_assoc($datosmem);
      $nombremem = isset($data['realName']) ? $data['realName'] : '';
      $avatar = isset($data['avatar']) ? $data['avatar'] : '';
      $pt = isset($data['personalText']) ? $data['personalText'] : '';
      $cortadoname = strlen($nombremem) > 9 ? substr($nombremem, 0, 6) . '...' : $nombremem;

      echo '
        <td align="center" style="font-size: 11px; font-family: arial; margin: 0px; padding: 0px;">
          <a href="' . $boardurl . '/perfil/' . $nombremem . '" title="' . $nombremem . '" style="text-decoration: none;">
            <img style="width: 40px; height: 40px;" alt="" class="avatar-box" src="' . (empty($avatar) ? $no_avatar : $avatar) . '" onerror="error_avatar(this)" />
          </a>
        </td>';

      if ($pds++ == 8) {
        echo '
          </tr>
          <tr>';
      }
    }

    echo '
            </tr>
          </table>
        </center>
      </div>';
  }

  echo '</div>';
}

function template_trackIP() {}
function template_showPermissions() {}
function template_statPanel() {}

function template_cuenta() {
  global $tranfer1, $context, $no_avatar, $boardurl;

  if ($context['user']['is_guest']) {
    fatal_error('Funcionalidad exclusiva de usuarios registrados.');
  }

  echo '
    <script type="text/javascript">
      function load_new_avatar() {
        var f = document.forms.per;

        if (f.avatar.value.substring(0, 7) != \'http://\') {
          f.avatar.focus();
          alert(\'La direcci&oacute;n debe comenzar con http://\');
          return;
        }

        window.newAvatar = new Image();
        window.newAvatar.src = f.avatar.value;
        newAvatar.loadBeginTime = (new Date()).getTime();
        newAvatar.onerror = show_error;
        newAvatar.onload = show_new_avatar;
        avatar_check_timeout();
      }

      function avatar_check_timeout() {
        if (((new Date()).getTime() - newAvatar.loadBeginTime) > 15) {
          alert(\'Avatar no recomendable. Raz&oacute;n: Muy lento\');
          document.forms.per.avatar.focus();
        }
      }

      function show_error() {
        alert(\'Hubo un error al leer la imagen. Por favor, verifica que la direcci&oacute;n sea correcta.\');
        document.forms.per.avatar.focus();
      }

      function show_new_avatar() {
        document.getElementById(\'miAvatar\').src = newAvatar.src;
      }

      function errorrojos(avatar) {
        if (avatar == \'\') {
          document.getElementById(\'errorss\').innerHTML = \'<font class="size10" style="color: red;">Falta agregar el avatar.</font><br />\';
          return false;
        }
      }
    </script>
    <div>';

  $getid = isset($_GET['u']) ? (int) $_GET['u'] : '';
  ditaruser();

  echo '
    <div style="float: left; width: 776px;">
      <form name="per" method="post" onsubmit="return load_new_avatar();" action="' . $boardurl . '/web/cw-avatarEditar.php">
        <div class="box_780">
          <div class="box_title" style="width: 774px;">
            <div class="box_txt box_780-34">
              <center>' . ($getid ? 'Editar el avatar' : 'Editar mi avatar') . '</center>
            </div>
            <div class="box_rss">
              <img alt="" src="' . $tranfer1 . '/blank.gif" style="width: 16px; height: 16px;" border="0" />
            </div>
          </div>
          <div class="windowbg" style="width: 766px; padding: 4px; margin-bottom: 8px;">
            <table width="100%" cellpadding="4">
              <tr valign="top">
                <td width="130px" valign="top">
                  <div class="fondoavatar" style="overflow: auto; width: 130px;" align="right">
                    <img alt="" src="' . ($context['avatar_url'] ? $context['avatar_url'] : $no_avatar) . '" width="120" height="120" align="left" vspace="4" hspace="4" id="miAvatar" onerror="error_avatar(this)" />
                  </div>
                </td>
                <td width="640px" valign="top">
                  <br />
                  <br />
                  <center>
                    Escribe la direcci&oacute;n' . ($getid ? ' del ' : ' de tu ') . '<i>avatar</i>.
                    <br />
                    Ejemplo: <b>' . $no_avatar . '</b>
                    <br />
                    <br />
                    <input type="text" onfocus="foco(this);" onblur="no_foco(this);" size="64" maxlength="255" name="avatar" id="avatar" value="' . ($context['avatar_url'] ? $context['avatar_url'] : $no_avatar) . '" />
                    <input type="button" class="login" value="Previsualizar" onclick="load_new_avatar()" />
                    <br />
                    <label id="errorss"></label>
                    <label id="sinavatar">
                      <input name="sinavatar" id="sinavatar" value="1" type="checkbox" ' . (empty($context['avatar_url']) || $context['avatar_url'] == $no_avatar || $context['avatar_url'] == 'http://' ? 'checked="checked" ' : '') . '>
                      Sin avatar
                      <span style="font-size: 10px;">(avatar default)</span>.
                    </label>
                  </center>
                </td>
              </tr>
              <tr>
                <td colspan="3" align="center">
                  <div class="hrs"></div>
                  <div class="noesta">
                    * Si el avatar contiene pornograf&iacute;a o es morboso, se borrar&aacute;.
                  </div>
                  <br />';

  if ($getid) {
    echo '
      <input type="hidden" name="admin" value="1" />
      <input type="hidden" name="id_user" value="' . $getid . '" />';
  }
  echo '
                  <input onclick="return errorrojos(this.form.avatar.value); this.form.submit()" type="submit" class="button" style="font-size: 15px" value="Editar mi perfil" title="Editar mi perfil" />
                </td>
              </tr>
              </table>
            </div>
          </div>
        </div>
      </form>
    </div>';
}

// Editar perfil
function template_perfil() {
  global $tranfer1, $context, $settings, $db_prefix, $options, $scripturl, $modSettings, $txt, $boardurl;

  echo '
    <script type="text/javascript">
      <!-- // --><![CDATA[
        function checkProfileSubmit() {';

  if ($context['user']['is_owner'] && $context['require_password']) {
    echo '
          if (document.forms.creator.oldpasswrd.value == \'\') {
            alert(\'Por razones de seguridad, debes especificar tu contrase\xf1a actual para hacer cualquier cambio a tu perfil.\');
            return false;
          }';
  }

  echo '
          return true;
        }
      // ]]>
    </script>';

  $getid = isset($_GET['u']) ? (int) $_GET['u'] : '';
  ditaruser();

  echo '
    <div style="float: left; width: 776px;">
      <form action="' . $boardurl . '/web/cw-perfilEditar.php" method="post" accept-charset="' . $context['character_set'] . '" name="creator" id="creator" enctype="multipart/form-data">
        <div class="box_780">
          <div class="box_title" style="width: 774px;">
            <div class="box_txt box_780-34">
              <center>' . ($getid ? 'Editar el perfil' : 'Editar mi perfil') . '</center>
            </div>
            <div class="box_rss">
              <img alt="" src="' . $tranfer1 . '/blank.gif" style="width: 16px; height: 16px;" border="0" />
            </div>
          </div>
          <div class="windowbg" style="width: 766px; padding: 4px;">
            <table width="100%" style="padding: 4px; border: none;">';

  $nombre3 = db_query("
    SELECT nombre, MSN, recibirmail
    FROM {$db_prefix}members
    WHERE ID_MEMBER = {$context['member']['id']}
    LIMIT 1", __FILE__, __LINE__);

  $row = mysqli_fetch_assoc($nombre3);
  $nombre = isset($row['nombre']) ? $row['nombre'] : '';
  $MSN = isset($row['MSN']) ? $row['MSN'] : '';
  $recibirmail = isset($row['recibirmail']) ? $row['recibirmail'] : '';

  mysqli_free_result($nombre3);

  echo '
    <tr>
      <td width="20%">
        <b class="size11">Nombre y apellido</b>
      </td>
      <td>
        <input type="text" onfocus="foco(this);" onblur="no_foco(this);" name="nombre" size="30" value="' . $nombre . '" />
      </td>
    </tr>';

  if ($context['allow_edit_account']) {
    if ($context['user']['is_admin'] && !empty($_GET['changeusername'])) {
      echo '
        <tr>
          <td colspan="2" align="center" style="color: red">' . $txt['username_warning'] . '</td>
        </tr>
        <tr>
          <td width="40%">
            <b>' . $txt[35] . ':</b>
          </td>
          <td>
            <input type="text" onfocus="foco(this);" onblur="no_foco(this);" name="memberName" size="30" value="' . $context['member']['name'] . '" />
          </td>
        </tr>';
    } else {
      if ($context['user']['is_admin']) {
        echo '
          <tr>
            <td width="40%">
              <b class="size11">Nick:</b>
              <div class="smalltext">
                (
                <a href="' . $boardurl . '/moderacion/edit-user/nick/' . $context['member']['id'] . '/" style="font-style: italic;">' . $txt['username_change'] . '</a>
                )
              </div>
            </td>
            <td>
              ' . $context['member']['name'] . '
            </td>
          </tr>';
      }
    }
  }

  if (!$context['user']['is_admin']) {
    echo '
      <tr>
        <td width="40%">
          <b class="size11">Nick:</b>
        </td>
        <td>' . $context['member']['name'] . '</td>
      </tr>';
  }

  if ($context['allow_edit_membergroups']) {
    echo '
      <tr>
        <td valign="top">
          <b class="size11">' . $txt['primary_membergroup'] . ':</b>
          <div class="smalltext"></div>
        </td>
        <td>
          <select name="ID_GROUP">';

    foreach ($context['member_groups'] as $member_group) {
      echo '
        <option value="' . $member_group['id'] . '"' . ($member_group['is_primary'] ? ' selected="selected"' : '') . '>
          ' . $member_group['name'] . '
        </option>';
    }

    echo '
          </select>
        </td>
      </tr>';
  }

  echo '
    <tr>
      <td width="40%">
        <b class="size11">' . $txt[563] . ':</b>
        <div class="smalltext">&#40;' . $txt[565] . '&#47;' . $txt[564] . '&#47;' . $txt[566] . '&#41;</div>
      </td>
      <td>
      <select tabindex="' . $context['tabindex']++ . '" name="bday2" id="bday2" autocomplete="off">
        <option value="' . $context['member']['birth_date']['day'] . '">D&iacute;a:</option>';

  for ($i = 1; $i < 32; $i++) {
    echo '<option value="' . $i . '"' . ($context['member']['birth_date']['day'] == $i ? ' selected="selected"' : '') . '>' . $i . '</option>';
  }

  // Generar el arreglo de meses con posición corrida - 1 valores;
  $months = $txt['months'];

  echo '
    </select>
    <select tabindex="' . $context['tabindex']++ . '" name="bday1" id="bday1" autocomplete="off">
      <option value="' . $context['member']['birth_date']['month'] . '">Mes:</option>';

  for ($i = 1; $i <= count($months); $i++) {
    echo '<option value="' . $i . '"' . ($context['member']['birth_date']['month'] == $i ? ' selected="selected"' : '') . '>' . strtolower($months[$i]) . '</option>';
  }

  echo '
    </select>
    <select tabindex="' . $context['tabindex']++ . '" name="bday3" id="bday3" autocomplete="off">
      <option value="' . $context['member']['birth_date']['year'] . '">A&ntilde;o:</option>';

  for ($i = date('Y') - 18; $i > 1899; $i--) {
    echo '<option value="' . $i . '"' . ($context['member']['birth_date']['year'] == $i ? ' selected="selected"' : '') . '>' . $i . '</option>';
  }

  echo '
        </select>
      </td>
    </tr>
    <tr>
      <td width="40%">
        <b class="size11">Pa&iacute;s:</b>
      </td>
      <td>
        <select name="usertitle" id="usertitle">
          <option value="' . $context['member']['title'] . '">Pa&iacute;s</option>';

  $countries = $txt['countries'];
  $countries_keys = array_keys($countries);

  for ($i = 0; $i < count($countries_keys); $i++) {
    $value = $countries_keys[$i];
    echo '<option value="' . $value . '"' . ($context['member']['title'] == $value ? ' selected="selected"' : '') . '>' . $countries[$value] . '</option>';
  }

  echo '
        </select>
      </td>
    </tr>
    <tr>
      <td width="40%">
        <b class="size11">' . $txt[227] . ':</b>
      </td>
      <td>
        <input type="text" onfocus="foco(this);" onblur="no_foco(this);" name="location" size="30" value="' . $context['member']['location'] . '" />
      </td>
    </tr>
    <tr>
      <td width="40%">
        <b class="size11">' . $txt[231] . ':</b>
      </td>
      <td>
        <select name="gender" size="1">
          <option value="1"' . ($context['member']['gender']['name'] == 'm' ? ' selected="selected"' : '') . '>' . $txt[238] . '</option>
          <option value="2"' . ($context['member']['gender']['name'] == 'f' ? ' selected="selected"' : '') . '>' . $txt[239] . '</option>
        </select>
      </td>
    </tr>';

  $Dfa = db_query("
    SELECT a_quien
    FROM {$db_prefix}infop
    WHERE id_user = {$context['member']['id']}", __FILE__, __LINE__);

  while ($das343 = mysqli_fetch_array($Dfa)) {
    $quien = $das343['a_quien'];
  }

  $quien = isset($quien) ? $quien : 0;

  echo '
    <tr>
      <td width="40%">
        <b class="size11">Mostrar apariencia a:</b>
      </td>
      <td>
        <select name="quienve" size="1">';

  $privacy = $txt['privacy'];
  $privacy_keys = array_keys($privacy);

  for ($i = 0; $i < count($privacy_keys); $i++) {
    $value = $privacy_keys[$i];
    echo '<option value="' . $value . '"' . ($quien == $value ? ' selected="selected"' : '') . '>' . $privacy[$value] . '</option>';
  }

  echo '
        </select>
      </td>
    </tr>
    <tr>
      <td width="40%">
        <b class="size11">Avisar si me borran posts e im&aacute;genes:</b>
      </td>
      <td>
        <select name="recibir" id="recibir" size="1">
          <option value="0"' . (!$recibirmail ? ' selected="selected"' : '') . '>No</option>
          <option value="1"' . ($recibirmail == 1 ? ' selected="selected"' : '') . '>S&iacute;</option>
        </select>
      </td>
    </tr>
    <tr>
      <td width="20%">
        <b class="size11">' . $txt[228] . ':</b>
        <div class="smalltext">(aparecer&aacute; debajo del avatar)</div>
      </td>
      <td>
        <input type="text" onfocus="foco(this);" onblur="no_foco(this);" name="personalText" size="30" maxlength="21" value="' . $context['member']['blurb'] . '" />
      </td>
    </tr>
    <tr>
      <td width="20%">
        <b class="size11">' . $txt['MSN'] . ':</b>
        <div class="smalltext">' . $txt['smf237'] . '</div>
      </td>
      <td>
        <input type="text" onfocus="foco(this);" onblur="no_foco(this);" name="MSN" value="' . $MSN . '" size="30"/>
      </td>
    </tr>
    <tr>
      <td width="20%">
        <b class="size11">' . $txt[84] . ':</b>
        <div class="smalltext">' . $txt[599] . '</div>
      </td>
      <td>
        <input type="text" onfocus="foco(this);" onblur="no_foco(this);" name="websiteTitle" size="30" value="' . $context['member']['website']['title'] . '" />
      </td>
    </tr>';

  if ($context['user']['is_admin']) {
    echo '
      <tr>
        <td width="20%">
          <b class="size11">Puntos:</b>
        </td>
        <td>
          <input type="text" onfocus="foco(this);" onblur="no_foco(this);" name="puntos" style="width: 35px;" value="' . $context['member']['posts'] . '" />
        </td>
      </tr>';
  }

  if ($context['allow_edit_account']) {
    echo '
      <tr>
        <td width="40%">
          <b class="size11" ' . (isset($context['modify_error']['bad_email']) || isset($context['modify_error']['no_email']) || isset($context['modify_error']['email_taken']) ? ' style="color: red;"' : '') . '>' . $txt[69] . ':</b>
          <div class="smalltext">' . $txt[679] . '</div>
        </td>
        <td>
          <input type="text" onfocus="foco(this);" onblur="no_foco(this);" name="emailAddress" size="30" value="' . $context['member']['email'] . '" />
        </td>
      </tr>
      <tr>
        <td width="40%">
          <b class="size11">' . $txt[81] . ':</b>
        </td>
        <td>
          <input type="password" onfocus="foco(this);" onblur="no_foco(this);" name="passwrd1" size="20" />
        </td>
      </tr>
      <tr>
        <td width="40%">
          <b class="size11">' . $txt[82] . ':</b>
        </td>
        <td>
          <input type="password" onfocus="foco(this);" onblur="no_foco(this);" name="passwrd2" size="20" />
        </td>
      </tr>';
  }

  template_profile_save();

  echo '
            </table>
          </div>
        </div>
      </form>
    </div>';
}

function template_profile_save() {
  global $tranfer1, $context, $settings, $options, $txt;

  echo '<tr>';

  if ($context['user']['is_owner'] && $context['require_password']) {
    echo '
      <td width="40%">
        <b class="size11">Contrase&ntilde;a actual:</b>
      </td>
      <td>
        <input type="password" onfocus="foco(this);" onblur="no_foco(this);" name="oldpasswrd" size="20" onfocus="javascript:select();" style="margin-right: 4ex;" />';
  } else {
    echo '
      <td align="center" colspan="2">
      <br />
      <br />
      <input class="login" type="submit" value="Editar mi perfil" />
      <input type="hidden" name="sc" value="' . $context['session_id'] . '" />
      <input type="hidden" name="userID" value="' . $context['member']['id'] . '" />
      <input type="hidden" name="sa" value="' . $context['menu_item_selected'] . '" />';
  }

  $_GET['u'] = isset($_GET['u']) ? $_GET['u'] : '';

  if ($_GET['u']) {
    echo '<input type="hidden" name="llegaravatar" value="' . $_GET['u'] . '"/>';
  }

  echo '
      </td>
    </tr>';
}

function template_profile_above() {}
function template_profile_below() {}

function apariencia($user) {
  global $db_prefix, $context, $boardurl;

  $nombre3 = db_query("
    SELECT MSN
    FROM {$db_prefix}members
    WHERE ID_MEMBER = {$context['member']['id']}
    LIMIT 1", __FILE__, __LINE__);

  $row = mysqli_fetch_assoc($nombre3);
  $MSN = isset($row['MSN']) ? $row['MSN'] : '';

  mysqli_free_result($nombre3);

  echo '
    <li>
      <label>Edad:</label>
      <strong>' . $context['member']['age'] . '</strong>
    </li>
    <li>
      <label>Sexo:</label>
      <strong>' . $context['member']['gender']['name'] . '</strong>
    </li>
    <li>
      <label>Pa&iacute;s:</label>
      <strong>' . pais($context['member']['title']) . '</strong>
    </li>
    <li>
      <label>Ciudad:</label>
      <strong>' . $context['member']['location'] . '</strong>
    </li>';

  if ($MSN) {
    echo '
      <li>
        <label>Mensajer&iacute;a:</label>
        <strong>' . $MSN . '</strong>
      </li>';
  }

  if ($context['member']['website']['title']) {
    echo '
      <li>
        <label>Web:</label>
        <strong>' . censorText($context['member']['website']['title']) . '</strong>
      </li>';
  }

  echo '
    <li>
      <label>Es usuario desde:</label>
      <strong>' . $context['member']['registered'] . '</strong>
    </li>';

  $refoagr = db_query("
    SELECT *
    FROM {$db_prefix}infop
    WHERE id_user = '$user'
    LIMIT 1", __FILE__, __LINE__);

  $mddd = mysqli_fetch_array($refoagr);

  if (!empty($mddd['altura']) || !empty($mddd['tomo_alcohol']) || !empty($mddd['peso']) || !empty($mddd['color_de_pelo']) || !empty($mddd['complexion']) || !empty($mddd['color_de_ojos']) || !empty($mddd['mi_dieta_es']) || !empty($mddd['fumo'])) {
    echo '
      <li class="sep">
        <h4>C&oacute;mo es</h4>
      </li>';

    if (!empty($mddd['altura'])) {
      echo '
        <li>
          <label>Mide:</label>
          <strong>' . $mddd['altura'] . ' cent&iacute;metros</strong>
        </li>';
    }

    if (!empty($mddd['peso'])) {
      echo '
        <li>
          <label>Pesa:</label>
          <strong>' . $mddd['peso'] . ' kilos</strong>
        </li>';
    }

    if (!empty($mddd['color_de_pelo'])) {
      echo '
        <li>
          <label>Su color de pelo:</label>
          <strong>' . getColoresPelo('value', $mddd['color_de_pelo']) . '</strong>
        </li>';
    }

    if (!empty($mddd['color_de_ojos'])) {
      echo '
        <li>
          <label>Su color de ojos:</label>
          <strong>' . getColoresOjos('value', $mddd['color_de_ojos']) . '</strong>
        </li>';
    }

    if (!empty($mddd['complexion'])) {
      echo '
        <li>
          <label>Su f&iacute;sico:</label>
          <strong>' . getComplexiones('value', $mddd['complexion']) . '</strong>
        </li>';
    }

    if (!empty($mddd['mi_dieta_es'])) {
      echo '
        <li>
          <label>Su dieta es:</label>
          <strong>' . getDietas('value', $mddd['mi_dieta_es']) . '</strong>
        </li>';
    }

    if (!empty($mddd['fumo'])) {
      echo '
        <li>
          <label>Fuma:</label>
          <strong>' . getFumos('value', $mddd['fumo']) . '</strong>
        </li>';
    }

    if (!empty($mddd['tomo_alcohol'])) {
      echo '
        <li>
          <label>Toma alcohol:</label>
          <strong>' . getAlcoholes('value', $mddd['tomo_alcohol']) . '</strong>
        </li>';
    }
  }

  if (!empty($mddd['me_gustaria']) || !empty($mddd['en_el_amor_estoy']) || !empty($mddd['hijos'])) {
    echo '
      <li class="sep">
        <h4>M&aacute;s datos</h4>
      </li>';

    if (!empty($mddd['me_gustaria'])) {
      echo '
        <li>
          <label>Le gustar&iacute;a:</label>
          <strong>' . getMeGustarias('value', $mddd['me_gustaria']) . '</strong>
        </li>';
    }

    if (!empty($mddd['en_el_amor_estoy'])) {
      echo '
        <li>
          <label>En el amor est&aacute;:</label>
          <strong>' . getEstados('value', $mddd['en_el_amor_estoy']) . '</strong>
        </li>';
    }

    if (!empty($mddd['hijos'])) {
      echo '
        <li>
          <label>Hijos:</label>
          <strong>' . getHijos('value', $mddd['hijos']) . '</strong>
        </li>';
    }
  }

  if (!empty($mddd['estudios']) || !empty($mddd['profesion']) || !empty($mddd['empresa']) || !empty($mddd['nivel_de_ingresos']) || !empty($mddd['intereses_profesionales']) || !empty($mddd['habilidades_profesionales'])) {
    echo '
      <li class="sep">
        <h4>Formaci&oacute;n y trabajo</h4>
      </li>';

    if (!empty($mddd['estudios'])) {
      echo '
        <li>
          <label>Sus estudios:</label>
          <strong>' . getEstudios('value', $mddd['estudios']) . '</strong>
        </li>';
    }

    if (!empty($mddd['profesion'])) {
      echo '
        <li>
          <label>Profesi&oacute;n:</label>
          <strong>' . censorText($mddd['profesion']) . '</strong>
        </li>';
    }

    if (!empty($mddd['empresa'])) {
      echo '
        <li>
          <label>Empresa:</label>
          <strong>' . censorText($mddd['empresa']) . '</strong>
        </li>';
    }

    if (!empty($mddd['nivel_de_ingresos'])) {
      echo '
        <li>
          <label>Su nivel de ingresos:</label>
          <strong>' . getIngresos('value', $mddd['nivel_de_ingresos']) . '</strong>
        </li>';
    }

    if (!empty($mddd['intereses_profesionales'])) {
      echo '
        <li>
          <label>Intereses profesionales:</label>
          <strong>' . censorText($mddd['intereses_profesionales']) . '</strong>
        </li>';
    }

    if (!empty($mddd['habilidades_profesionales'])) {
      echo '
        <li>
          <label>Habilidades profesionales:</label>
          <strong>' . censorText($mddd['habilidades_profesionales']) . '</strong>
        </li>';
    }
  }

  if (!empty($mddd['mis_intereses']) || !empty($mddd['hobbies']) || !empty($mddd['series_de_tv_favorita']) || !empty($mddd['musica_favorita']) || !empty($mddd['deportes_y_equipos_favoritos']) || !empty($mddd['deportes_y_equipos_favoritos']) || !empty($mddd['libros_favoritos']) || !empty($mddd['peliculas_favoritas']) || !empty($mddd['comida_favorita']) || !empty($mddd['mis_heroes_son'])) {
    echo '
      <li class="sep">
        <h4>Intereses y preferencias</h4>
      </li>';

    if (!empty($mddd['mis_intereses'])) {
      echo '
        <li>
          <label>Intereses:</label>
          <strong>' . censorText($mddd['mis_intereses']) . '</strong>
        </li>';
    }

    if (!empty($mddd['hobbies'])) {
      echo '
        <li>
          <label>Hobbies:</label>
          <strong>' . censorText($mddd['hobbies']) . '</strong>
        </li>';
    }

    if (!empty($mddd['series_de_tv_favorita'])) {
      echo '
        <li>
          <label>Series de TV favoritas:</label>
          <strong>' . censorText($mddd['series_de_tv_favorita']) . '</strong>
        </li>';
    }

    if (!empty($mddd['musica_favorita'])) {
      echo '
        <li>
          <label>M&uacute;sica favorita:</label>
          <strong>' . censorText($mddd['musica_favorita']) . '</strong>
        </li>';
    }

    if (!empty($mddd['deportes_y_equipos_favoritos'])) {
      echo '
        <li>
          <label>Deportes y equipos:</label>
          <strong>' . censorText($mddd['deportes_y_equipos_favoritos']) . '</strong>
        </li>';
    }

    if (!empty($mddd['libros_favoritos'])) {
      echo '
        <li>
          <label>Libros favoritos:</label>
          <strong>' . censorText($mddd['libros_favoritos']) . '</strong>
        </li>';
    }

    if (!empty($mddd['peliculas_favoritas'])) {
      echo '
        <li>
          <label>Pel&iacute;culas favoritas:</label>
          <strong>' . censorText($mddd['peliculas_favoritas']) . '</strong>
        </li>';
    }

    if (!empty($mddd['comida_favorita'])) {
      echo '
        <li>
          <label>Comida favor&iacute;ta:</label>
          <strong>' . censorText($mddd['comida_favorita']) . '</strong>
        </li>';
    }

    if (!empty($mddd['mis_heroes_son'])) {
      echo '
        <li>
          <label>Sus h&eacute;roes son:</label>
          <strong>' . censorText($mddd['mis_heroes_son']) . '</strong>
        </li>';
    }
  }

  $dataquien = $mddd['a_quien'];

  mysqli_free_result($refoagr);
}

?>