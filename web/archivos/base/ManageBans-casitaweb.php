<?php

function template_main() {
  global $tranfer1, $context, $settings, $db_prefix, $options, $scripturl, $txt, $boardurl;

  if ($context['sub_action'] == 'buscar') {
    echo '
      <script type="text/javascript">
        function enviarFORMbaneados() {
          if ($(\'#usuario\').val() == \'\') {
            $(\'#usuario\').focus();
            return false;
          }

          location.href = \'' . $boardurl . '/moderacion/edit-user/ban/buscar/&usuario=\' + $(\'#usuario\').val() + \'&si=Buscar\';
          return;
        }
      </script>';

    $usuario = isset($_GET['usuario']) ? seguridad($_GET['usuario']) : '';
    $dasdasd = isset($_GET['si']) ? seguridad($_GET['si']) : '';

    echo '
      <div style="padding: 8px; background-color: #F4F4F4; border: 1px solid #CCCCCC;">
        <div style="float: left;">
          <div style="float: left;">
            <input title="Busca usuario" onfocus="foco(this);" onblur="no_foco(this);" id="usuario" size="30" type="text" value="' . $usuario . '" />
          </div>
          <div style="float: left; padding-left: 2px">
            <input onclick="enviarFORMbaneados();" class="login" type="button" name="si" value="Buscar"/>
          </div>
        </div>
        <div class="clearfix"></div>
      </div>
      <div style="margin-top: 5px; width: 922px;">';

    if (!empty($dasdasd)) {
      if (!empty($usuario)) {
        $resultado = db_query("
          SELECT name, notes, ban_time, clave, ID_BAN_GROUP, reason, expire_time
          FROM {$db_prefix}ban_groups
          WHERE name = '$usuario'", __FILE__, __LINE__);

        $rows = mysqli_num_rows($resultado);

        if ($rows > 0) {
          echo '
            <table class="linksList size11" border="0" style="width: 922px; margin: 0px; padding: 0px;">
              <thead>
                <tr>
                  <th>Usuario</th>
                  <th>Moderador</th>
                  <th>Raz&oacute;n</th>
                  <th>Fecha</th>
                  <th>Expiraci&oacute;n</th>
                  <th>Clave</th>
                  <th>Opciones</th>
                </tr>
              </thead>
              <tbody>';

          while ($ban = mysqli_fetch_array($resultado)) {
            echo '
              ';

            $ban['ban_time'] = hace($ban['ban_time']);
            $idN = $ban['ID_BAN_GROUP'];
            $ban['expires'] = $ban['expire_time'] === null ? $txt['never'] : ($ban['expire_time'] < time() ? '<span style="color: red">' . $txt['ban_expired'] . '</span>' : (int) ceil(($ban['expire_time'] - time()) / (60 * 60 * 24)) . '&nbsp;d&iacute;a(s)');

            $request352 = db_query("
              SELECT realName
              FROM {$db_prefix}members
              WHERE ID_MEMBER = '{$ban['notes']}'
              LIMIT 1", __FILE__, __LINE__);

            $row = mysqli_fetch_assoc($request352);
            $moderator = isset($row['realName']) ? $row['realName'] : '';

            echo '
              <tr style="margin: 0px; padding: 0px;" class="fondoplano" id="ban_' . $idN . '">
                <td align="left" class="size11" valign="top">
                  <a href="' . $boardurl . '/perfil/' . $ban['name'] . '">' . $ban['name'] . '</a>
                </td>
                <td align="left" class="size11" valign="top">
                  ' . ($moderator == '' ? ' - ' : '<a href="' . $boardurl . '/perfil/' . $moderator . '" target="_blank">' . $moderator . '</a>') . '
                </td>
                <td align="left" class="size11" valign="top" style="text-align: left">
                  ' . $ban['reason'] . '
                </td>
                <td align="left" class="size8" valign="top">
                  ' . $ban['ban_time'] . '
                </td>
                <td align="left" class="size8" valign="top">
                  ' . $ban['expires'] . '
                </td>
                <td align="left" class="size11" valign="top">
                  ' . ($context['user']['id'] == $ban['notes'] || $context['user']['is_admin'] ? $ban['clave'] : 'Oculta') . '
                </td>
                <td align="left" class="size11" valign="top" style="width: 100px">
                  <input style="width: 30px; font-size: 10px" class="login" onclick="Boxy.load(\'' . $boardurl . '/web/cw-TEMPbanUser.php?sa=edit;bg=' . $idN . '\', { title : \'Editar ban de ' . $ban['name'] . '\' });" value="Editar" />
                  <input style="width: 38px; font-size: 10px" class="login" onclick="Boxy.load(\'' . $boardurl . '/web/cw-TEMPeliminarBan.php?id=' . $idN . '\', { title : \'Eliminar ban de ' . $ban['name'] . '\' });" value="Eliminar" />
                </td>
              </tr>';
          }

          echo '</table>';
        } else {
          echo '<div class="noesta">' . $usuario . ' no est&aacute; en la lista de baneados.</div>';
        }
      } else {
        echo '<div class="noesta">Debes escribir el nick del usuario a buscar.</div>';
      }
    }

    echo '</div>';
  } else {
    echo '
      <table class="linksList size11" border="0" style="width: 922px; margin: 0px; padding: 0px;">
        <thead>
          <tr>
            <th>Usuario</th>
            <th>Moderador</th>
            <th>Raz&oacute;n</th>
            <th>Fecha</th>
            <th>Expiraci&oacute;n</th>
            <th>Clave</th>
            <th>Opciones</th>
          </tr>
        </thead>
        <tbody>';

    $RegistrosAMostrar = 15;

    if (isset($_GET['pag-seg-15487135'])) {
      $RegistrosAEmpezar = ($_GET['pag-seg-15487135'] - 1) * $RegistrosAMostrar;
      $PagAct = $_GET['pag-seg-15487135'];
    } else {
      $RegistrosAEmpezar = 0;
      $PagAct = 1;
    }

    $request = db_query("
      SELECT ban_time
      FROM {$db_prefix}ban_groups", __FILE__, __LINE__);

    $NroRegistros = mysqli_num_rows($request);
    $PagAnt = $PagAct - 1;
    $PagSig = $PagAct + 1;
    $PagUlt = $NroRegistros / $RegistrosAMostrar;
    $Res = $NroRegistros % $RegistrosAMostrar;

    if ($Res > 0) {
      $PagUlt = floor($PagUlt) + 1;
    }

    $bdsee = db_query("
      SELECT name, notes, ban_time, clave, ID_BAN_GROUP, reason, expire_time
      FROM {$db_prefix}ban_groups
      ORDER BY ID_BAN_GROUP DESC
      LIMIT $RegistrosAEmpezar, $RegistrosAMostrar", __FILE__, __LINE__);

    while ($ban = mysqli_fetch_array($bdsee)) {
      $ban['ban_time'] = hace($ban['ban_time']);
      $ban['expires'] = $ban['expire_time'] === null ? $txt['never'] : ($ban['expire_time'] < time() ? '<span style="color: red">' . $txt['ban_expired'] . '</span>' : (int) ceil(($ban['expire_time'] - time()) / (60 * 60 * 24)) . '&nbsp;d&iacute;a(s)');
      $ban['notes'] = (int) $ban['notes'];

      $request352 = db_query("
        SELECT realName
        FROM {$db_prefix}members
        WHERE ID_MEMBER = {$ban['notes']}", __FILE__, __LINE__);

      $row = mysqli_fetch_assoc($request352);
      $moderator = isset($row['realName']) ? $row['realName'] : '';

      echo '
        <tr style="margin: 0px; padding: 0px; text-align: left;" class="fondoplano" id="ban_' . $ban['ID_BAN_GROUP'] . '">
          <td align="left" class="size11" valign="top">
            <a href="' . $boardurl . '/perfil/' . $ban['name'] . '">' . $ban['name'] . '</a>
          </td>
          <td align="left" class="size11" valign="top">
            ' . ($moderator == '' ? ' - ' : '<a href="' . $boardurl . '/perfil/' . $moderator . '" target="_blank">' . $moderator . '</a>') . '
          </td>
          <td align="left" class="size11" valign="top" style="text-align: left">
            ' . $ban['reason'] . '
          </td>
          <td align="left" class="size8" valign="top">
            ' . $ban['ban_time'] . '
          </td>
          <td align="left" class="size8" valign="top">
            ' . $ban['expires'] . '
          </td>
          <td align="left" class="size11" valign="top">
            ' . ($context['user']['id'] == $ban['notes'] || $context['user']['is_admin'] ? $ban['clave'] : 'Oculta') . '
          </td>
          <td align="left" class="size11" valign="top" style="width: 100px">
            <input style="width: 30px; font-size: 10px" class="login" onclick="Boxy.load(\'' . $boardurl . '/web/cw-TEMPbanUser.php?sa=edit;bg=' . $ban['ID_BAN_GROUP'] . '\', { title : \'Editar ban\' });" value="Editar" />
            <input style="width: 38px; font-size: 10px" class="login" onclick="Boxy.load(\'' . $boardurl . '/web/cw-TEMPeliminarBan.php?id=' . $ban['ID_BAN_GROUP'] . '\', { title : \'Eliminar ban\' });" value="Eliminar" />
          </td>
        </tr>';
    }

    echo '
        </tbody>
      </table>';

    if (!empty($NroRegistros)) {
      if ($PagAct > $PagUlt) {
        // TO-DO: ¿Aquí se valida algo?
      } else {
        if ($PagAct > 1 || $PagAct < $PagUlt) {
          echo '<div class="windowbgpag" style="padding: 4px; width: 745px;">';

          if ($PagAct > 1) {
            echo '<a href="' . $boardurl . '/moderacion/edit-user/ban/pag-' . $PagAnt . '">&lt; anterior</a>';
          }

          if ($PagAct < $PagUlt) {
            echo '<a href="' . $boardurl . '/moderacion/edit-user/ban/pag-' . $PagSig . '">siguiente &gt;</a>';
          }

          echo '</div>';
        }
      }
    }
  }
}

?>