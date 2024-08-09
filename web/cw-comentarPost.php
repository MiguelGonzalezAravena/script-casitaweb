<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $tranfer1, $context, $db_prefix, $user_info, $user_settings, $ID_MEMBER;

ignore_user_abort(true);
@set_time_limit(300);

$ID_TOPIC = isset($_POST['id']) ? (int) $_POST['id'] : 0;
$comentario = isset($_POST['editorCW']) ? seguridad($_POST['editorCW']) : '';
$comentario = censorText($comentario);
$sdasd = isset($_POST['psecion']) ? (int) $_POST['psecion'] : '';
$realName = $user_settings['realName'];

if ($user_info['is_guest']) {
  die('0: Funcionalidad exclusiva de usuarios registrados.');
} else if ($ID_TOPIC == 0) {
  die('0: No has seleccionado el post a comentar.');
} else {
  $request = db_query("
    SELECT m.ID_BOARD, m.smileysEnabled, m.ID_MEMBER, m.subject, b.description
    FROM {$db_prefix}messages AS m
    INNER JOIN {$db_prefix}boards AS b ON m.ID_BOARD = b.ID_BOARD
    AND m.ID_TOPIC = $ID_TOPIC
    AND m.eliminado = 0
    LIMIT 1", __FILE__, __LINE__);

  $row = mysqli_fetch_assoc($request);
  $ID_BOARD = $row['ID_BOARD'];
  $locked = $row['smileysEnabled'];
  $lmemdsa = $row['ID_MEMBER'];
  $description = $row['description'];
  $subject = $row['subject'];

  mysqli_free_result($request);

  $request = db_query("
    SELECT id_user
    FROM {$db_prefix}pm_admitir
    WHERE id_user = '$lmemdsa'
    AND quien = $ID_MEMBER
    LIMIT 1", __FILE__, __LINE__);

  $rows = mysqli_num_rows($request);

  if ($rows) {
    die('0: No puedes comentar este post.');
  }

  if ($locked) {
    die('0: Este post est&aacute; cerrado.');
  } else if (empty($comentario)) {
    die('0: Debes escribir un comentario.');
  } else if (strlen($comentario) > 4500) {
    die('0: El comentario es demasiado extenso, abr&eacute;vialo.');
  } else {
    $request = db_query("
      SELECT id_user, fecha
      FROM {$db_prefix}comentarios
      WHERE id_user = $ID_MEMBER
      ORDER BY id_coment DESC
      LIMIT 1", __FILE__, __LINE__);

    $row = mysqli_fetch_assoc($request);
    $modifiedTime = $row['fecha'];

    mysqli_free_result($request);

    if ($modifiedTime > time() - 25) {
      die('0: No es posible comentar posts con tan poca diferencia de tiempo.');
    } else if (empty($ID_BOARD)) {
      die('0: Este post fue eliminado o no existe.');
    } else {
      $fecha = time();
      db_query("
        INSERT INTO {$db_prefix}comentarios (id_post, id_cat, id_user, comentario, fecha)
        VALUES ($ID_TOPIC, $ID_BOARD, $ID_MEMBER, '$comentario', $fecha)", __FILE__, __LINE__);

      echo '1: ';

      $request = db_query("
        SELECT comentario, id_coment, fecha
        FROM {$db_prefix}comentarios
        WHERE id_user = $ID_MEMBER
        ORDER BY id_coment DESC
        LIMIT 1", __FILE__, __LINE__);

      $row = mysqli_fetch_assoc($request);
      $context['comentariods'] = censorText(parse_bbc($row['comentario']));
      $context['id_coment'] = $row['id_coment'];
      $context['fecha'] = $row['fecha'];

      mysqli_free_result($request);

      if ($ID_MEMBER != $lmemdsa) {
        $url = $boardurl . '/post/' . $ID_TOPIC . '/' . $description . '/' . urls($subject) . '.html#cmt_' . $context['id_coment'];

        db_query("
          INSERT INTO {$db_prefix}notificaciones (url, que, a_quien, por_quien)
          VALUES ('$url', 1, $lmemdsa, $ID_MEMBER)", __FILE__, __LINE__);

        db_query("
          UPDATE {$db_prefix}members
          SET notificacionMonitor = notificacionMonitor + 1
          WHERE ID_MEMBER = $lmemdsa
          LIMIT 1", __FILE__, __LINE__);
      }

      // Comentario
      echo '
        <div id="cmt_' . $context['id_coment'] . '" class="Coment">
          <div class="User-Coment size12">
            <div style="float: left;">
              <b id="autor_cmnt_' . $context['id_coment'] . '" user_comment="' . $realName . '" text_comment="' . $comentario . '">
                <a href="' . $boardurl . '/perfil/' . $realName . '" style="color: #956100;">' . $realName . '</a>
              </b>
              <span title="' . tiempo2($context['fecha']) . '">' . hace($context['fecha']) . '</span>
              dijo:
            </div>
            <div style="float: right;">
              ' . (!$user_info['is_guest'] ? '<a href="' . $boardurl . '/web/cw-TEMPenviarMP.php?user=' . $realName . '" title="Enviar MP a ' . $realName . '" class="boxy"><img alt="" src="' . $tranfer1 . '/icons/mensaje_para.gif" border="0" /></a>' : '') . '
              ' . (!$locked ? ' <a onclick="citar_comment(' . $context['id_coment'] . ');" href="javascript:void(0)" title="Citar comentario"><img alt="" src="' . $tranfer1 . '/comunidades/respuesta.png" class="png" border="0" /></a>' : '') . '
              ' . ($lmemdsa == $ID_MEMBER || $user_info['is_admin'] || $user_info['is_mods'] ? ' <a href="#" onclick="del_coment_post(' . $context['id_coment'] . ', ' . $ID_TOPIC . '); return false;" title="Eliminar comentario"><img alt="" src="' . $tranfer1 . '/comunidades/eliminar.png" class="png" style="width: 16px; height: 16px;" border="0" /></a>' : '') . '
            </div>
          </div>
          <div class="cuerpo-Coment">
            <div style="white-space: pre-wrap; overflow: hidden; display: block;">' . $context['comentariods'] . '</div>
          </div>
        </div>';
    }
  }
}

?>