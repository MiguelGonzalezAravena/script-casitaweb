<?php

function template_main() {
  global $context, $db_prefix, $tranfer1, $user_settings, $no_avatar, $boardurl;

  echo '
    <div style="width: 794px; float: left;">
      <div class="title-w">
        <h3>Notificaciones</h3>
      </div>';

  $cant = $user_settings['notificacionMonitor'];

  if ($cant) {
    db_query("
      UPDATE {$db_prefix}members
      SET notificacionMonitor = 0
      WHERE ID_MEMBER = $ID_MEMBER
      LIMIT 1", __FILE__, __LINE__);
  }

  $datosmem = db_query("
    SELECT que, url, por_quien, leido, fecha, extra, id
    FROM {$db_prefix}notificaciones
    WHERE a_quien = $ID_MEMBER
    ORDER BY id DESC
    LIMIT 30", __FILE__, __LINE__);

  while ($data = mysqli_fetch_assoc($datosmem)) {
    $backCOLOR = empty($data['leido']) ? 'background-color: #FDFBE7; ' : '';

    $MIEMBRO = db_query("
      SELECT realName, avatar
      FROM {$db_prefix}members
      WHERE ID_MEMBER = {$data['por_quien']}
      LIMIT 1", __FILE__, __LINE__);

    while ($dd = mysqli_fetch_assoc($MIEMBRO)) {
      $realName = $dd['realName'];
      $avatar = $dd['avatar'];
    }

    mysqli_free_result($MIEMBRO);

    $AVA = empty($avatar) ? $no_avatar : $avatar;

    echo '
      <div style="' . $backCOLOR . 'display: inline-block; #display: inline-block; _display: inline; padding: 4px; border-bottom: 1px dotted #CCC; margin-bottom: 2px; width: 786px;">
        <div style="float: left; margin-right: 8px;">
          <a href="' . $boardurl . '/perfil/' . $realName . '">
            <img src="' . $AVA . '" class="avatar-box" onerror="error_avatar(this)" width="50" height="50" alt="" />
          </a>
        </div>
        <div style="width: 728px;">
          <strong style="font-size: 14px;">
            <a href="' . $boardurl . '/perfil/' . $realName . '">' . $realName . '</a>
          </strong>
          <br />
          ' . notificacionQUE($data['que'], $data['url'], $data['extra']) . '
          <br />
          ' . hace($data['fecha']) . '
        </div>
      </div>
      <div class="clearfix"></div>';

    db_query("
      UPDATE {$db_prefix}notificaciones
      SET leido = 1
      WHERE id = {$data['id']}
      LIMIT 1", __FILE__, __LINE__);

    $dac = 1;
  }

  mysqli_free_result($datosmem);

  $dac = isset($dac) ? $dac : '';

  if (empty($dac)) {
    echo '<div class="noesta">No tienes nuevas notificaciones.</div>';
  }

  $d = db_query("
    SELECT id
    FROM {$db_prefix}notificaciones
    WHERE a_quien = $ID_MEMBER
    ORDER BY id DESC
    LIMIT 31, 500", __FILE__, __LINE__);

  while ($dff = mysqli_fetch_assoc($d)) {
    db_query("
      DELETE FROM {$db_prefix}notificaciones
      WHERE id = {$dff['id']}", __FILE__, __LINE__);
  }

  mysqli_free_result($d);

  echo '
    </div>
    <div style="width: 120px; float: right;" align="center">
      ' . anuncio_160x600() . '
    </div>
    <div class="clearfix"></div>';
}

?>