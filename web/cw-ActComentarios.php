<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $context, $user_info, $db_prefix, $modSettings, $boardurl;

if ($user_info['is_guest']) {
  echo '
    <div class="noesta-am">
      S&oacute;lo Usuarios REGISTRADOS pueden actualizar los comentarios.
      <br />
      <a href="' . $boardurl . '/registrarse/">REG&Iacute;STRATE</a>
      -
      <a href="#" onclick="javascript: servicenavlogin();">CON&Eacute;CTATE</a>
    </div>';
} else {
  $shas = !$user_info['is_admin'] ? ' AND m.ID_BOARD <> 142' : '';

  $rs = db_query("
    SELECT c.id_post, m.ID_TOPIC, c.id_user, mem.ID_MEMBER, m.ID_BOARD, c.id_coment, m.subject, b.description, mem.memberName, mem.realName
    FROM {$db_prefix}comentarios AS c, {$db_prefix}messages AS m, {$db_prefix}members AS mem, {$db_prefix}boards as b
    WHERE c.id_post = m.ID_TOPIC
    AND c.id_user = mem.ID_MEMBER
    AND m.ID_BOARD = b.ID_BOARD
    $shas
    ORDER BY c.id_coment DESC
    LIMIT 25", __FILE__, __LINE__);

  $context['comentarios25'] = array();

  while ($row = mysqli_fetch_assoc($rs)) {
    $context['comentarios25'][] = array(
      'id_coment' => $row['id_coment'],
      'titulo' => censorText($row['subject']),
      'ID_TOPIC' => $row['ID_TOPIC'],
      'description' => $row['description'],
      'memberName' => $row['memberName'],
      'realName' => $row['realName']
    );
  }

  mysqli_free_result($rs);

  foreach ($context['comentarios25'] as $row) {
    echo '
      <font class="size11">
        <b>
          <a title="' . $row['realName'] . '" href="' . $boardurl . '/perfil/' . $row['realName'] . '">' . $row['realName'] . '</a>
        </b>
        -
        <a title="' . $row['titulo'] . '" href="' . $boardurl . '/post/' . $row['ID_TOPIC'] . '/' . $row['description'] . '/' . urls($row['titulo']) . '.html#cmt_' . $row['id_coment'] . '">' . achicars($row['titulo']) . '</a>
      </font>
      <br style="margin: 0px; padding: 0px;" />';
  }
}

?>