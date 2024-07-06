<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
require_once($sourcedir . '/Funciones.php');
global $tranfer1, $context, $settings, $db_prefix, $options, $txt, $user_info, $user_settings, $scripturl, $boardurl;

$ids = isset($_POST['id-seg-2451']) ? (int) $_POST['id-seg-2451'] : 0;
$tipo = isset($_POST['tipo']) ? seguridad($_POST['tipo']) : '';
$cat = isset($_POST['categorias']) ? (int) $_POST['categorias'] : 0;
$user = isset($_POST['useradar']) ? seguridad($_POST['useradar']) : '';

if ($user_info['is_admin']) {
  if (empty($ids)) {
    fatal_error('Debe seleccionar el post.');
  }

  $request = db_query("
    SELECT ID_TOPIC
    FROM {$db_prefix}messages
    WHERE ID_TOPIC = $ids
    LIMIT 1", __FILE__, __LINE__);

  $context['topicss'] = mysqli_num_rows($request);

  if (empty($context['topicss'])) {
    fatal_error('El post no existe.');
  }

  if ($tipo == 'Cambiar categor&iacute;a') {
    if (empty($cat)) {
      fatal_error('Debe seleccionar la categor&iacute;a.');
    }

    $request = db_query("
      SELECT ID_BOARD
      FROM {$db_prefix}boards
      WHERE ID_BOARD = $cat
      LIMIT 1", __FILE__, __LINE__);

    $context['contadorsss'] = mysqli_num_rows($request);

    if (empty($context['contadorsss'])) {
      fatal_error('La categor&iacute;a especificada no existe.');
    }

    db_query("
      UPDATE {$db_prefix}messages
      SET ID_BOARD = $cat
      WHERE ID_TOPIC = $ids
      LIMIT 1", __FILE__, __LINE__);
  } else if ($tipo == 'Cambiar autor') {
    if (empty($user)) {
      fatal_error('Debes seleccionar el usuario.');
    }

    $lvccct = db_query("
      SELECT memberIP, ID_MEMBER, emailAddress
      FROM {$db_prefix}members
      WHERE realName = '$user'
      LIMIT 1", __FILE__, __LINE__);

    $des = mysqli_fetch_assoc($lvccct);
    $memberIP = $des['memberIP'];
    $id_mem = isset($des['ID_MEMBER']) ? $des['ID_MEMBER'] : '';
    $emailAddress = $des['emailAddress'];

    if (empty($id_mem)) {
      fatal_error('El usuario no existe.');
    }

    db_query("
      UPDATE {$db_prefix}messages
      SET posterName = '$user',
      ID_MEMBER = $id_mem,
      posterEmail = '$emailAddress',
      posterIP = '$memberIP'
      WHERE ID_TOPIC = $ids
      LIMIT 1", __FILE__, __LINE__);
  }
}
$url = generatePostURL($ids);

header('Location: ' . $url);

?>