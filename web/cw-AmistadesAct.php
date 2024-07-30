<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $tranfer1, $context, $db_prefix, $options, $txt, $no_avatar, $user_info, $ID_MEMBER;

if ($user_info['is_guest']) {
  // TO-DO: ¿Está bien esto?
  return;
} else {
  $request = db_query("
    SELECT id
    FROM {$db_prefix}amistad
    WHERE amigo = $ID_MEMBER
    AND acepto = 0
    LIMIT 1", __FILE__, __LINE__);

  $rows = mysqli_num_rows($request);

  if ($rows > 0) {
    echo '
      <div id="amistadesACT" style="width: 163px; margin-bottom: 8px;">
        <div style="background: #DADADA; color: #666666; font-size: 14px; padding: 4px; width: 155px;">
          <b>Quiere ser tu amigo:</b>
        </div>
        <div style="background: #EEEEEE; padding: 2px; width: 159px;">
          <center>
            <img src="' . $tranfer1 . '/icons/cargando.gif" style="margin-top: 4px; display: none;" id="cargandoAmistad width="16px" height="16px" alt="" />
          </center>
          <div style="display: none;" class="noesta" id="errorAmistad"></div>';

    $request = db_query("
      SELECT user, id
      FROM {$db_prefix}amistad
      WHERE amigo = $ID_MEMBER
      AND acepto = 0
      ORDER BY id DESC
      LIMIT 5", __FILE__, __LINE__);

    $rows = mysqli_num_rows($request);

    if ($rows == 0) {
      die ('0: No se encuentran registros asociados.');
    }

    while ($row = mysqli_fetch_array($request)) {
      $id = $row['id'];
      $username = getUsername($row['user']);
      $avatar = getAvatar($row['user']);
      $url_profile = `$boardurl/perfil/$username`;

      echo '
        <div id="ams_' . $id . '" style="white-space: pre-wrap; overflow: hidden; display: block;">
          <div class="muroEfect" id="muroEfectAV">
            <table>
              <tr valign="top">
                <td valign="top">
                  <a href="' . $url_profile . '">
                    <img src="' . $avatar . '" class="avatar-box" onerror="error_avatar(this)" width="50" height="50" alt="" />
                  </a>
                </td>
                <td valign="top">
                  <a title="' . $username . '" href="' . $url_profile . '" style="color: #D35F2C;">
                    <b>' . $username . '</b>
                  </a>
                  <div class="clearfix" style="margin-top: 4px; margin-bottom: 4px;">
                    <div style="margin-bottom: 6px;">
                      <a href="#" onclick="accionAmistad(\'' . $id . '\', \'1\'); return false;" class="botN1" style="color: #fff; text-shadow: #005400 0px 1px 0px;">Aceptar</a>
                    </div>
                    <div>
                      <a href="#" onclick="accionAmistad(\'' . $id . '\', \'0\'); return false;" class="botN2" style="text-shadow: #CC0000 0px 1px 0px; color: #fff;">Rechazar</a>
                    </div>
                  </div>
                </td>
              </tr>
            </table>
          </div>
        </div>';
    }

    echo '
        </div>
      </div>';
  }
}

?>