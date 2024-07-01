<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $context, $db_prefix, $user_settings, $modSettings, $ID_MEMBER;

$id_topics = isset($_POST['id_post']) ? (int) $_POST['id_post'] : 0;
$aa45s1dsasd = (int) $_POST['anuncio'];
$dddderrr = (int) $_POST['nocom'];
$adasdeeea = (int) $_POST['principal'];
$color = isset($_POST['colorsticky']) ? seguridad($_POST['colorsticky']) : '';
$causa = isset($_POST['causa']) ? seguridad($_POST['causa']) : '';

if ($user_info['is_guest']) {
  die('Funcionalidad exclusiva de usuarios registrados.');
}

ignore_user_abort(true);
@set_time_limit(300);
timeforComent(1);

$datos = db_query("
  SELECT ID_BOARD, ID_TOPIC, ID_MEMBER, sticky
  FROM {$db_prefix}messages
  WHERE ID_TOPIC = $id_topics
  AND eliminado = 0
  " . (!empty($user_info['is_mods']) || !empty($user_info['is_admin']) ? '' : " AND ID_MEMBER = $ID_MEMBER") . "
  LIMIT 1", __FILE__, __LINE__);

$row = mysqli_fetch_assoc($datos);
$id_cat = $row['ID_BOARD'];
$stikccss = $row['sticky'];
$id_post = $row['ID_TOPIC'];
$id_user = $row['ID_MEMBER'];
$error = mysqli_num_rows($datos);

if (empty($error)) {
  fatal_error('No tienes permisos para editar este post.');
}

$titulo = isset($_POST['titulo']) ? seguridad($_POST['titulo']) : '';
$post = isset($_POST['contenido']) ? seguridad($_POST['contenido']) : '';
$tags = isset($_POST['tags']) ? trim(strtolower($_POST['tags'])) : '';
$categorias = isset($_POST['categorias']) ? (int) $_POST['categorias'] : 0;
$privado = isset($_POST['privado']) ? (int) $_POST['privado'] : 0;

if (empty($titulo)) {
  fatal_error('Debes escribir un t&iacute;tulo.');
}

if (empty($post)) {
  fatal_error('Debes escribir el post.');
}

if (empty($categorias)) {
  fatal_error('Debes especificar la categor&iacute;a.');
}

if (empty($tags)) {
  fatal_error('Debes escribir los tags.');
}

if (strlen($_POST['titulo']) < 3) {
  fatal_error('El t&iacute;tulo no puede tener menos de <b>3 letras</b>.');
}

if (strlen($_POST['titulo']) >= 61) {
  fatal_error('El t&iacute;tulo no puede tener m&aacute;s de <b>60 letras</b>.');
}

if (strlen($_POST['contenido']) <= 60) {
  fatal_error('El post no puede tener menos de <b>60 letras</b>.');
}

if (strlen($_POST['contenido']) > $modSettings['max_messageLength']) {
  fatal_error('El post no puede tener m&aacute;s de <b>' . $modSettings['max_messageLength'] . ' letras</b>.');
}

$resquest = db_query("
  SELECT description
  FROM {$db_prefix}boards
  WHERE ID_BOARD = $categorias
  LIMIT 1", __FILE__, __LINE__);

$row = mysqli_fetch_assoc($resquest);
$descript = isset($row['description']) ? $row['description'] : '';

if (empty($descript)) {
  fatal_error('La categor&iacute;a especificada no existe.');
}

mysqli_free_result($resquest);

$ak = explode(',', $tags);
$Nn = implode(',', array_diff($ak, array_values(array(''))));
$a = explode(',', $Nn);
$c = sizeof($a);

if ($c < 4) {
  fatal_error('No se permiten menos de 4 tags.');
}

if ($c > 5) {
  $c = 5;
}

if ($user_info['is_admin']) {
  $anuncio = $aa45s1dsasd == 0 || $aa45s1dsasd == 1 ? $aa45s1dsasd : 0;
}

if ($user_settings['posts'] >= 500) {
  $nocom = ($dddderrr == 0 || $dddderrr == 1 ? $dddderrr : 0);
} else {
  $nocom = 0;
}

if ($user_info['is_admin'] || $user_info['is_mods']) {
  $principal = !$adasdeeea || $adasdeeea ? $adasdeeea : 0;

  if ($principal == 1) {
    if ($color == '#000000') {
      $colorsticky = '';
    } else {
      if (strlen($color) >= 1) {
        if (strlen($color) != 7) {
          fatal_error('El color ingresado est&aacute; mal escrito.');
        }

        $colorsticky = $color;
      } else {
        $colorsticky = '';
      }
    }
  } else {
    $colorsticky = '';
  }
} else {
  $principal = $stikccss;
}

$tiempo = time();

db_query("
  UPDATE {$db_prefix}messages
  SET
    ID_BOARD = $categorias,
    subject = '$titulo',
    body = '$post',
    modifiedTime = $tiempo,
    modifiedName = '{$user_settings['realName']}',
    hiddenOption = $privado,
    color = '$colorsticky',
    anuncio = $anuncio,
    smileysEnabled = $nocom,
    sticky = $principal
  WHERE ID_TOPIC = $id_topics
  LIMIT 1", __FILE__, __LINE__);

if (($user_info['is_admin'] || $user_info['is_mods']) && $id_user != $ID_MEMBER) {
  if (empty($causa)) {
    fatal_error('Debes especificar la causa de la eliminaci&oacute;n.');
  }

  if (strlen($causa) < 5) {
    fatal_error('Debes especificar una causa m&aacute;s detallada.');
  }

  logAction('modify', array('topic' => $titulo . ' (ID: ' . $id_topics . ')', 'member' => $id_user, 'causa' => $causa));
}

// Tags
db_query("
  DELETE FROM {$db_prefix}tags
  WHERE id_post = $id_topics
  AND rango = 0", __FILE__, __LINE__);

for ($i = 0; $i < $c; ++$i) {
  $lvccct = db_query("
    SELECT ID_TAG
    FROM {$db_prefix}tags
    WHERE palabra = '$a[$i]'
    AND rango = 1
    LIMIT 1", __FILE__, __LINE__);

  $asserr = mysqli_fetch_assoc($lvccct);
  $idse = isset($asserr['ID_TAG']) ? $asserr['ID_TAG'] : '';
  $a[$i] = nohtml($a[$i]);

  if (!empty($idse)) {
    db_query("
      UPDATE {$db_prefix}tags
      SET cantidad = cantidad + 1
      WHERE ID_TAG = $idse
      AND rango = 1
      LIMIT 1", __FILE__, __LINE__);

    $rg = 0;
  } else {
    $rg = 1;
  }

  db_query("
    INSERT INTO {$db_prefix}tags (id_post, palabra, cantidad, rango)
    VALUES ($id_topics, SUBSTRING('$a[$i]',  1, 65), 1, $rg)", __FILE__, __LINE__);
}
// Fin tags

$_SESSION['edit'] = 1;
$_SESSION['ultima_accionTIME'] = time();
$urls = $boardurl . '/post/' . $id_topics . '/' . $descript . '/' . urls($titulo) . '.html';

PostAccionado('Post editado', 'Tu post "<strong>' . censorText($titulo) . '</strong>" ha sido editado correctamente.', $urls, 'Ir al post');

?>