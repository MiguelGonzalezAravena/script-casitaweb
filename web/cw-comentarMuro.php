<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $context, $db_prefix, $no_avatar, $user_info, $user_settings, $boardurl, $ID_MEMBER;

if ($user_info['is_guest']) {
  die('0: Funcionalidad exclusiva de usuarios registrados.');
}

$muro = isset($_POST['muro']) ? seguridad($_POST['muro']) : '';
$quehago = isset($_POST['quehago']) ? seguridad($_POST['quehago']) : '';
$idmem = isset($_POST['user']) ? (int) $_POST['user'] : 0;

if ($muro != '' || $quehago != '') {
  if ($muro != '') {
    if (empty($idmem)) {
      die('0: Debes especificar el usuario al cual deseas escribir en el muro.');
    }

    if (empty($muro) || $muro == 'Escribe algo...' || $muro == 'escribe algo...' || $muro == 'Escribe algo' || $muro == 'escribe algo') {
      die('0: Debes escribir algo en el muro.');
    } else {
      if (strlen($muro) > 10000) {
        die('0: No se aceptan escritos tan grandes.');
      } else {
        if (empty($ID_MEMBER)) {
          die('0: No puedes escribir en el muro alguien si no has iniciado sesi&oacute;n.');
        } else {
          $request = db_query("
            SELECT id_user
            FROM {$db_prefix}pm_admitir
            WHERE id_user = $idmem
            AND quien = $ID_MEMBER
            LIMIT 1", __FILE__, __LINE__);

          $ignorado = mysqli_num_rows($request);

          if ($ignorado) {
            die('0: No puedes comentar este muro.');
          }

          timeforComent();

          db_query("
            INSERT INTO {$db_prefix}muro (id_user, de, tipo, tipocc, muro)
            VALUES ($idmem, $ID_MEMBER, 0, 0, '$muro')", __FILE__, __LINE__);

          $ivvd = db_insert_id();

          // MOSTRARRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRR
          $dsddd = isset($_POST['datapagss']) ? (int) $_POST['datapagss'] : 0;
          if ($dsddd != 1) {
            echo '1: <div class="noesta-am">&iexcl;Comentario agregado correctamente!</div>';
          } else {
            $mensaje = censorText($muro);
            $yata = hace(time());
            $mensaje = nohtml2(moticon($mensaje, true));
            $filtrado = str_replace("\n", '<br />', $mensaje);
            $nombremem = getUsername($idmem);

            echo '1: ';
            echo '
              <div id="muro-' . $ivvd . '">
                <div id="muroEfectAV">
                  <table>
                    <tr>
                      <td valign="top">
                        <a href="' . $boardurl . '/perfil/' . $user_settings['realName'] . '">
                          <img src="' . (!empty($user_settings['avatar']) ? $user_settings['avatar'] : $no_avatar) . '" class="avatar-box" onerror="error_avatar(this)" width="50" height="50" alt="" />
                        </a>
                      </td>
                      <td valign="top" style="margin: 0px; font-size: 11px;">
                        <strong>
                          <a href="' . $boardurl . '/perfil/' . $user_settings['realName'] . '" title="' . $user_settings['realName'] . '" style="font-size: 14px; color: #D35F2C;">' . $user_settings['realName'] . '</a>
                        </strong>
                        <br />
                        ' . VideosMuro($filtrado) . '
                        <div style="margin-top: 6px;">
                          ' . hace(time()) . '
                          -
                          <span onclick="boxHablar(\'' . $ivvd . '\');" style="cursor: pointer; color: #424242;" id="c-' . $ivvd . '">Comentar</span>
                          <span style="display: none;" id="vmam_' . $ivvd . '">
                            -
                            <a href="' . $boardurl . '/perfil/' . $context['member']['name'] . '/muro;ccIDmuro=' . $ivvd . '">Ver muro a muro</a>
                          </span>
                          ' . ($user_settings['ID_MEMBER'] == $context['member']['id'] || ($user_info['is_admin'] || $user_info['is_mods']) ? ' - <span class="pointer" onclick="Boxy.confirm(\'&iquest;Est&aacute;s seguro que deseas borrar este mensaje?\', function() { del_coment_muro(\'' . $ivvd . '\'); }, {title: \'Eliminar mensaje\'}); " title="Eliminar mensaje">eliminar</span>' : '') . '
                        </div>
                      </td>
                    </tr>
                  </table>
                  ' . textarea2($ivvd) . '
                  <div class="hrs" style="margin: 0px; padding: 0px;"></div>
                </div>
              </div>';
            // FIN
          }

          $url = $boardurl . '/perfil/' . $nombremem . '/muro;ccIDmuro=' . $ivvd;
          notificacionAGREGAR($idmem, 3, 0, $url);

          $_SESSION['ultima_accionTIME'] = time();
          die();
        }
      }
    }
  } else if ($quehago) {
    if ($quehago == '') {
      fatal_error('Debes escribir algo.');
    }

    if ($quehago == '¿Qué estás haciendo ahora?' || $quehago == '&#191;Qu&eacute; est&aacute;s haciendo ahora&#63;') {
      fatal_error('Debes escribir algo.');
    }

    if (!empty($quehago)) {
      if (strlen($quehago) > 70) {
        fatal_error('No se aceptan escritos mayor a 70 letras.');
      }

      if (empty($ID_MEMBER)) {
        fatal_error('Funcionalidad exclusiva de usuarios registrados');
      } else {
        db_query("
          INSERT INTO {$db_prefix}muro (id_user, de, tipo, tipocc, muro)
          VALUES ($ID_MEMBER, $ID_MEMBER, 1, 0, '$quehago')", __FILE__, __LINE__);

        header('Location: ' . $boardurl . '/perfil');
      }
    }
  }
} else {
  header('Location: ' . $boardurl . '/');
}

?>