<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $db_prefix, $user_settings, $user_info, $context, $sourcedir, $boardurl, $ID_MEMBER;

$causa = isset($_POST['causa']) ? seguridad($_POST['causa']) : '';

if ($user_info['is_guest']) {
  die('Funcionalidad exclusiva de usuarios registrados.');
}

$topic = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if (empty($topic)) {
  post_error();
}

$request = db_query("
  SELECT ID_MEMBER, subject, puntos
  FROM {$db_prefix}messages
  WHERE ID_TOPIC = $topic
  AND eliminado = 0
  LIMIT 1", __FILE__, __LINE__);

list($starter, $subject, $puntosdados) = mysqli_fetch_row($request);
mysqli_free_result($request);

if (empty($subject)) {
  post_error();
}

if ($starter === $ID_MEMBER || ($user_info['is_admin'] || $user_info['is_mods'])) {
  $subject = seguridad($subject);

  if ($user_info['is_admin'] || $user_info['is_mods']) {
    if ($starter != $ID_MEMBER) {
      if (empty($causa)) {
        fatal_error('Debes especificar la causa de la eliminaci&oacute;n.');
      }

      if (strlen($causa) <= 5) {
        fatal_error('Debes especificar una causa m&aacute;s detallada.');
      }
    }
  }

  db_query("
    UPDATE {$db_prefix}messages
    SET 
      eliminado = 1,
      body = SUBSTRING(body, 1, 255),
      modifiedName = '{$user_settings['realName']}'
    WHERE ID_TOPIC = $topic
    LIMIT 1", __FILE__, __LINE__);

  db_query("
    DELETE FROM {$db_prefix}comentarios
    WHERE id_post = $topic", __FILE__, __LINE__);

  db_query("
    DELETE FROM {$db_prefix}puntos
    WHERE id_post = $topic", __FILE__, __LINE__);

  if ($puntosdados && $starter === $ID_MEMBER) {
    db_query("
      UPDATE {$db_prefix}members
      SET posts = posts - $puntosdados
      WHERE ID_MEMBER = $starter", __FILE__, __LINE__);
  }

  db_query("
    DELETE FROM {$db_prefix}favoritos
    WHERE ID_TOPIC = $topic", __FILE__, __LINE__);

  // Quitar tags
  $lvccct = db_query("
    SELECT palabra
    FROM {$db_prefix}tags
    WHERE id_post = $topic", __FILE__, __LINE__);

  while ($asserr = mysqli_fetch_assoc($lvccct)) {
    $idse = isset($asserr['palabra']) ? $asserr['palabra'] : '';

    db_query("
      UPDATE {$db_prefix}tags
      SET cantidad = cantidad - 1
      WHERE palabra = '$idse'
      AND rango = 1
      LIMIT 1", __FILE__, __LINE__);
  }

  db_query("
    DELETE FROM {$db_prefix}tags
    WHERE id_post = $topic
    AND rango = 0", __FILE__, __LINE__);
  // Fin quitar tags

  if (($user_info['is_admin'] || $user_info['is_mods']) && ($starter != $ID_MEMBER)) {
    logAction('remove', array('topic' => $subject . ' (ID: ' . $topic . ')', 'member' => $starter, 'causa' => $causa));

    $datosmem = db_query("
      SELECT recibirmail
      FROM {$db_prefix}members
      WHERE ID_MEMBER = $starter", __FILE__, __LINE__);

    while ($data = mysqli_fetch_assoc($datosmem)) {
      $remail = $data['recibirmail'];
    }

    if ($remail) {
      $pmfrom = array(
        'id' => $ID_MEMBER,
        'name' => $user_settings['realName'],
        'username' => $user_settings['realName']
      );

      $titulo = 'Post eliminado: ' . censorText($subject);
      $titulo2 = censorText($subject);
      $causa = censorText($causa);

      $message = 'Hola!

Lamento contarte que tu post titulado ' . $titulo2 . ' ha sido eliminado.

Causa: [b]' . $causa . '[/b]

Para acceder al protocolo, presiona [asd1256as4867cxc8c7a8xc7asd16a8e7a56s4da65s4d68as7da54d564dcv787v777v7v7v7v7v7as7eelprotocolopm=protocolo/][b]este enlace[/b][/asd1256as4867cxc8c7a8xc7asd16a8e7a56s4da65s4d68as7da54d564dcv787v777v7v7v7v7v7as7eelprotocolopm].

Muchas gracias por entender!';

      require_once($sourcedir . '/Subs-Post.php');
      sendpm($titulo, $message, $starter, 1);
    }
  }

  pts_sumar_grup($starter);
}

header(`Location: $boardurl/`);

?>