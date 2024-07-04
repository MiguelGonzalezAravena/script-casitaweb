<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $context, $tranfer1, $user_info, $db_prefix, $ajaxError, $boardurl;

$_GET['id'] = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if (empty($context['ajax'])) {
  echo $ajaxError;
  die('Error de ajax.');
}

echo '
  <div>
    <div style="width: 500px; height: 500px; overflow: auto;">';

if (($user_info['is_admin'] || $user_info['is_mods'])) {
  $datos = db_query("
    SELECT m.ID_TOPIC, m.puntos, b.description, m.subject
    FROM {$db_prefix}messages AS m
    INNER JOIN {$db_prefix}boards AS b ON m.ID_BOARD = b.ID_BOARD
    AND m.ID_MEMBER = {$_GET['id']}
    AND m.puntos != 0
    ORDER BY m.ID_TOPIC DESC
    LIMIT 30", __FILE__, __LINE__);

  while ($row = mysqli_fetch_assoc($datos)) {
    $d = $row['ID_TOPIC'];
    echo '
      <strong>
        <a title="' . censorText($row['subject']) . '" href="' . $boardurl . '/post/' . $row['ID_TOPIC'] . '/' . $row['description'] . '/' . censorText(urls($row['subject'])) . '.html" class="titlePost">' . censorText($row['subject']) . '</a>
        (' . $row['puntos'] . ' puntos)
      </strong>
      <br />
      <i style="color: red;">
        Puntos otorgados por:';

    $request = db_query("
      SELECT p.cantidad, m.realName
      FROM {$db_prefix}puntos AS p
      INNER JOIN {$db_prefix}members AS m ON p.id_post = {$row['ID_TOPIC']}
      AND p.id_member = m.ID_MEMBER
      ORDER BY p.id DESC", __FILE__, __LINE__);

    while ($row2 = mysqli_fetch_assoc($request)) {
      echo '&#8226;&#32;<a href="' . $boardurl . '/perfil/' . $row2['realName'] . '" title="' . $row2['cantidad'] . ' puntos">' . $row2['realName'] . '</a>&#32;';
    }

    mysqli_free_result($request);

    echo '
      </i>
      <div class="hrs"></div>';
  }

  mysqli_free_result($datos);

  $d = isset($d) ? $d : '';

  if (empty($d)) {
    echo '<div class="noesta">No tiene posts creados.</div>';
  }

  // TO-DO: Modificar el "30 imagenes" por un mysqli_num_rows()
  echo '
    <div class="botnes" onclick="cerrarBox();" style="float: right; clear: both; height: 32px;">
      <a href="javascript:Boxy.load(\'' . $boardurl . '/web/cw-TEMPquienDioptsImg.php?id=' . $_GET['id'] . "', { title: '30 imagenes' })\">Im&aacute;genes</a>
    </div>";
}

echo '
    </div>
  </div>';

?>