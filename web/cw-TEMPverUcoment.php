<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $context, $db_prefix, $user_info, $ajaxError, $boardurl;

if (empty($context['ajax'])) {
  echo $ajaxError;
  die('Error de ajax.');
}

echo '
  <div>
    <div style="width: 700px; height: 500px; overflow: auto;">';

if (($user_info['is_admin'] || $user_info['is_mods'])) {
  $request = db_query("
    SELECT id_post, id_coment
    FROM {$db_prefix}comentarios
    ORDER BY id_coment DESC
    LIMIT 30", __FILE__, __LINE__);

  while ($row = mysqli_fetch_array($request)) {
    $request2 = db_query("
      SELECT m.ID_TOPIC, c.description, m.subject, com.id_coment, com.comentario, mem.realName
      FROM {$db_prefix}messages AS m, {$db_prefix}boards AS c, {$db_prefix}comentarios AS com,{$db_prefix}members AS mem
      WHERE com.id_coment = {$row['id_coment']}
      AND m.ID_TOPIC = {$row['id_post']}
      AND m.ID_BOARD = c.ID_BOARD
      AND com.id_user = mem.ID_MEMBER
      LIMIT 1", __FILE__, __LINE__);

    while ($row2 = mysqli_fetch_array($request2)) {
      echo '
        <a href="' . $boardurl . '/post/' . $row2['ID_TOPIC'] . '/' . $row2['description'] . '/' . urls(censorText($row2['subject'])) . '.html" title="' . censorText($row2['subject']) . '" class="categoriaPost ' . $row2['description'] . '">' . censorText($row2['subject']) . '</a>
        <a href="' . $boardurl . '/post/' . $row2['ID_TOPIC'] . '/' . $row2['description'] . '/' . urls(censorText($row2['subject'])) . '.html#cmt_' . $row2['id_coment'] . '" title="' . censorText($row2['subject']) . '" style="color: green;">Ir al comentario</a>
        -
        <i>
          Escrito por:
          <a href="' . $boardurl . '/perfil/' . $row2['realName'] . '" title="' . $row2['realName'] . '">' . $row2['realName'] . '</a>
        </i>
        <br />
        ' . str_replace('if(this.width >720) {this.width=720}', 'if(this.width >698) {this.width=680}', censorText(parse_bbc($row2['comentario']))) . '
        <div class="hrs"></div>';
    }

    mysqli_free_result($request2);
  }

  mysqli_free_result($request);
}

echo '
    </div>
  </div>';

?>