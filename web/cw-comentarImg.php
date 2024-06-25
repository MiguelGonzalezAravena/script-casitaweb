<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $context, $db_prefix, $ID_MEMBER, $txt, $user_info, $user_settings, $modSettings, $boardurl;

if (empty($ID_MEMBER)) {
  die('0: Funcionalidad exclusiva de usuarios registrados.');
}

$request = db_query("
  SELECT date
  FROM {$db_prefix}gallery_comment
  WHERE ID_MEMBER = $ID_MEMBER
  ORDER BY ID_COMMENT DESC
  LIMIT 1", __FILE__, __LINE__);

$row = mysqli_fetch_assoc($request);
$posterTime = isset($row['date']) ? $row['date'] : 0;

mysqli_free_result($request);

if ($posterTime > time() - 25) {
  die('0: No es posible comentar imagen con tan poca diferencia de tiempo.');
}

$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
$comentario = isset($_POST['editorCW']) ? seguridad($_POST['editorCW']) : '';
$sdasd = isset($_POST['psecion']) ? (int) $_POST['psecion'] : '';
$comment = htmlspecialchars(stripslashes($comentario), ENT_QUOTES);
$comment = str_replace(array('"', '<', '>', '  '), array('&quot;', '&lt;', '&gt;', ' &nbsp;'), $comment);
$comment = preg_replace('~\[hide\](.+?)\[\/hide\]~i', '&nbsp;', $comment);
$comment = preg_replace(array('~\n?\[hide.*?\].+?\[/hide\]\n?~is', '~^\n~', '~\[/hide\]~'), '&nbsp;', $comment);
$comment = preg_replace('~<br(?: /)?' . '>~i', "\n", $comment);
$comment = censorText($comment);
$commentdate = time();
$realName = $user_settings['realName'];

if ($id < 1) {
  die('0: ' . $txt['gallery_error_no_pic_selected']);
}

$datos = db_query("
  SELECT ID_PICTURE, ID_MEMBER
  FROM {$db_prefix}gallery_pic
  WHERE ID_PICTURE = $id
  ORDER BY ID_PICTURE DESC
  LIMIT 1", __FILE__, __LINE__);

$row = mysqli_fetch_assoc($datos);
$id_pic = $row['ID_PICTURE'];
$lmemdsa = $row['ID_MEMBER'];

mysqli_free_result($datos);

if (empty($id_pic)) {
  die('0: La imagen seleccionada no existe.');
}

$request = db_query("
  SELECT id_user
  FROM {$db_prefix}pm_admitir
  WHERE id_user = $lmemdsa
  AND quien = $ID_MEMBER
  LIMIT 1", __FILE__, __LINE__);

$ignorado = mysqli_num_rows($request);
if ($ignorado) {
  die('0: No puedes comentar esta imagen.');
}

if (strlen($comentario) > 4500) {
  die('0: El comentario es demasiado extenso, abr&eacute;vialo.');
}

if (empty($comentario)) {
  die('0: ' . $txt['gallery_error_no_comment']);
}

db_query("
  INSERT INTO {$db_prefix}gallery_comment (ID_MEMBER, comment, date, ID_PICTURE)
  VALUES ($ID_MEMBER, '$comment',  $commentdate, $id_pic)", __FILE__, __LINE__);

// Comentario
echo '1: ';

$request = db_query("
  SELECT comment, date, ID_COMMENT
  FROM {$db_prefix}gallery_comment
  WHERE ID_MEMBER = $ID_MEMBER
  ORDER BY ID_COMMENT DESC
  LIMIT 1", __FILE__, __LINE__);

$row = mysqli_fetch_assoc($request);
$context['comentariods'] = censorText(parse_bbc($row['comment']));
$context['id_coment'] = $row['ID_COMMENT'];
$context['fecha'] = $row['date'];

mysqli_free_result($request);

if ($ID_MEMBER != $lmemdsa) {
  $url = $boardurl . '/imagenes/ver/' . $id_pic . '#cmt_' . $context['id_coment'];
  db_query("
    INSERT INTO {$db_prefix}notificaciones (url, que, a_quien, por_quien, extra)
    VALUES ('$url', 2, $lmemdsa, $ID_MEMBER, $id_pic)", __FILE__, __LINE__);

  db_query("
    UPDATE {$db_prefix}members
    SET notificacionMonitor = notificacionMonitor + 1
    WHERE ID_MEMBER = $lmemdsa
    LIMIT 1", __FILE__, __LINE__);
}

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
        <a href="' . $boardurl . '/web/cw-TEMPenviarMP.php?user=' . $realName . '" title="Enviar MP a ' . $realName . '" class="boxy">
          <img alt="" src="' . $tranfer1 . '/icons/mensaje_para.gif" border="0" />
        </a>
        <a onclick="citar_comment(' . $context['id_coment'] . ');" href="javascript:void(0)" title="Citar comentario">
          <img alt="" src="' . $tranfer1 . '/comunidades/respuesta.png" border="0" class="png" />
        </a>
        ' . ($lmemdsa == $ID_MEMBER || $user_info['is_admin'] || $user_info['is_mods'] ? '<a href="#" onclick="del_coment_img(' . $context['id_coment'] . ',' . $id . '); return false;" title="Eliminar comentario"><img alt="" src="' . $tranfer1 . '/comunidades/eliminar.png" class="png" style="width: 16px; height: 16px;" border="0" /></a>' : '') . '
      </div>
    </div>
    <div class="cuerpo-Coment">
      <div style="white-space: pre-wrap; overflow: hidden; display: block;">' . $context['comentariods'] . '</div>
    </div>
  </div>';

?>