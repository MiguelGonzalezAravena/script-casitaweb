<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $tranfer1, $context, $db_prefix, $options, $txt, $scripturl, $user_settings, $user_info, $modSettings, $boardurl;

$ser = isset($_GET['user']) ? seguridad($_GET['user']) : '';
$causa = isset($_POST['causa']) ? seguridad($_POST['causa']) : '';

if ($user_info['is_admin'] || $user_info['is_mods']) {
  require_once($sourcedir . '/RemoveTopic.php');

  if (empty($ser)) {
    fatal_error('Debes seleccionar el usuario.');
  }

  $request = db_query("
    SELECT realName
    FROM {$db_prefix}members
    WHERE realName = '$ser'
    LIMIT 1", __FILE__, __LINE__);

  $sadasd33 = mysqli_num_rows($request);

  if (!$sadasd33) {
    fatal_error('El usuario seleccionado no existe.');
  }

  $datosmem27 = db_query("
    SELECT ID_MEMBER
    FROM {$db_prefix}members
    WHERE realName = '$ser'
    LIMIT 1", __FILE__, __LINE__);

  $data37 = mysqli_fetch_assoc($datosmem27);
  $id_mem = $data37['ID_MEMBER'];

  if (empty($_POST['campos'])) {
    fatal_error('No seleccionaste los posts para eliminar.');
  }

  if (empty($causa)) {
    fatal_error('No especificaste la causa de la eliminaci&oacute;n.');
  }

  if (strlen($causa) <= 5) {
    fatal_error('Escribe una causa m&aacute;s detallada.');
  }

  $aLista = array_keys($_POST['campos']);
  $z = implode(',', $aLista);
  $a = explode(',', $z);
  $c = sizeof($a);

  if ($c > 50) {
    fatal_error('Solo de a 50 posts.');
  }

  if (!$c) {
    $lelo = 'Intento de eliminado';
  } else if ($c == 1) {
    $lelo = '1 post';
  } else if ($c > 1) {
    $lelo = $c . ' posts';
  } else {
    $lelo = 'Intento de eliminado';
  }

  for ($i = 0; $i < $c; ++$i) {
    $datosmem2 = db_query("
      SELECT t.puntos, t.ID_BOARD, m.subject
      FROM {$db_prefix}topics AS t, {$db_prefix}messages AS m
      WHERE t.ID_TOPIC = '$a[$i]'
      AND t.ID_TOPIC = m.ID_TOPIC", __FILE__, __LINE__);

    while ($data3 = mysqli_fetch_assoc($datosmem2)) {
      $puntosdados = $data3['puntos'];
      $subject = $data3['subject'];
      $icon = $data3['ID_BOARD'];
    }

    removeTopics($a[$i]);

    if ($user_info['is_admin'] && $icon == 142) {
      db_query("
        INSERT INTO {$db_prefix}eliminados (id_post, titulo)
        VALUES ('$a[$i]', '$subject')", __FILE__, __LINE__);

      db_query("
        DELETE FROM {$db_prefix}tags
        WHERE id_post = $a[$i]", __FILE__, __LINE__);

      db_query("
        DELETE FROM {$db_prefix}comentarios
        WHERE id_post = $a[$i]", __FILE__, __LINE__);

      db_query("
        DELETE FROM {$db_prefix}puntos
        WHERE id_post = $a[$i]", __FILE__, __LINE__);

      db_query("
        DELETE FROM {$db_prefix}favoritos
        WHERE ID_TOPIC = $a[$i]", __FILE__, __LINE__);

      db_query("
        UPDATE {$db_prefix}members
        SET posts = posts - $puntosdados
        WHERE ID_MEMBER = $id_mem", __FILE__, __LINE__);
    }
  }

  logAction('remove', array('pack' => $lelo, 'member' => $ser, 'causa' => $causa));
  updateStats('topic');
  updateStats('message');
  header('Location: ' . $boardurl . '/user-post/' . $ser);
} else {
  header('Location: ' . $boardurl . '/');
}

?>