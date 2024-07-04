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
    SELECT ID_COMMENT, ID_PICTURE
    FROM {$db_prefix}gallery_comment
    ORDER BY ID_COMMENT DESC
    LIMIT 30", __FILE__, __LINE__);

  while ($row = mysqli_fetch_array($request)) {
    $request2 = db_query("
      SELECT m.ID_PICTURE, com.ID_COMMENT, m.title, mem.realName, com.comment
      FROM {$db_prefix}gallery_pic AS m, {$db_prefix}gallery_comment AS com,{$db_prefix}members AS mem
      WHERE com.ID_COMMENT = {$row['ID_COMMENT']}
      AND m.ID_PICTURE = {$row['ID_PICTURE']}
      AND com.ID_MEMBER = mem.ID_MEMBER
      LIMIT 1", __FILE__, __LINE__);

    while ($row2 = mysqli_fetch_array($request2)) {
      echo '
        <a href="' . $boardurl . '/imagenes/ver/' . $row2['ID_PICTURE'] . '#cmt_' . $row2['ID_COMMENT'] . '" title="' . censorText($row2['title']) . '">' . censorText($row2['title']) . '</a>
        <br />
        <a href="' . $boardurl . '/imagenes/ver/' . $row2['ID_PICTURE'] . '#cmt_' . $row2['ID_COMMENT'] . '" title="' . censorText($row2['title']) . '" style="color: green;">Ir al comentario</a>
        -
        <i>
          Escrito por:
          <a href="' . $boardurl . '/perfil/' . $row2['realName'] . '" title="' . $row2['realName'] . '">' . $row2['realName'] . '</a>
        </i>
        <br />
        ' . str_replace('if(this.width >720) {this.width=720}', 'if(this.width >698) {this.width=680}', censorText(parse_bbc($row2['comment']))) . '
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