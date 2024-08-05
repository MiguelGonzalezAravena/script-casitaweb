<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $context, $db_prefix, $modSettings, $user_settings, $user_info;

$aa45s1dsasd = isset($_POST['anuncio']) ? (int) $_POST['anuncio'] : 0;
$categorias = isset($_POST['categorias']) ? (int) $_POST['categorias'] : 0;
$tags = trim(strtolower($_POST['tags']));
$privado = isset($_POST['privado']) ? (int) $_POST['privado'] : 0;
$dddderrr = isset($_POST['nocom']) ? (int) $_POST['nocom'] : 0;
$adasdeeea = isset($_POST['principal']) ? (int) $_POST['principal'] : 0;
$color = isset($_POST['colorsticky']) ? seguridad($_POST['colorsticky']) : '';
$titulo = isset($_POST['titulo']) ? seguridad($_POST['titulo']) : '';
$post = isset($_POST['contenido']) ? seguridad($_POST['contenido']) : '';
$anuncio = 0;
$nocom = 0;

// var_dump($user_settings);
if ($user_info['is_guest']) {
  die('Funcionalidad exclusiva de usuarios registrados.');
}

ignore_user_abort(true);
@set_time_limit(300);

timeforComent(1);

if (empty($titulo)) {
  fatal_error('Falta escribir el t&iacute;tulo.');
}

if (empty($post)) {
  fatal_error('Falto escribir el post.');
}

if (empty($categorias)) {
  fatal_error('Falto asignarle la categor&iacute;a.');
}

if (empty($tags)) {
  fatal_error('Falto agregarle los tags.');
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

$request = db_query("
  SELECT description
  FROM {$db_prefix}boards
  WHERE ID_BOARD = $categorias
  LIMIT 1", __FILE__, __LINE__);

$row = mysqli_fetch_assoc($request);
$descript = isset($row['description']) ? $row['description'] : '';

if (empty($descript)) {
  fatal_error('La categor&iacute;a especificada no existe.');
}

mysqli_free_result($request);

// Tags
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

if ($user_settings['ID_GROUP'] === 1) {
  $anuncio = $aa45s1dsasd == 0 || $aa45s1dsasd == 1 ? $aa45s1dsasd : 0;
}

if ($user_settings['posts'] >= 500) {
  $nocom = $dddderrr == 0 || $dddderrr == 1 ? $dddderrr : 0;
}

if ($user_info['is_admin'] || $user_info['is_mods']) {
  $principal = $adasdeeea == 0 || $adasdeeea == 1 ? $adasdeeea : 0;

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
        $colorsticky == '';
      }
    }
  } else {
    $colorsticky = '';
  }
}

// TO-DO: Mejorar incrementable, ya que tabla usa ID_MESSAGE como autoincrementable
$request = db_query("
  SELECT MAX(ID_TOPIC) AS max
  FROM {$db_prefix}messages", __FILE__, __LINE__);

$row = mysqli_fetch_assoc($request);

$ID_TOPIC = $row['max'] + 1;

$ip = !empty($user_settings['memberIP']) ? $user_settings['memberIP'] : '127.0.0.1';
$realName = $user_settings['realName'];
$emailAddress = $user_settings['emailAddress'];

$str = "
  INSERT INTO {$db_prefix}messages (ID_TOPIC, ID_BOARD, ID_MEMBER, subject, body, posterName, posterEmail, posterTime, hiddenOption, color, anuncio, posterIP, smileysEnabled, sticky, visitas)
  VALUES ($ID_TOPIC, $categorias, $ID_MEMBER, SUBSTRING('$titulo', 1, 70), SUBSTRING('$post', 1, 65534), SUBSTRING('$realName', 1, 255), SUBSTRING('$emailAddress', 1, 255), " . time() . ", $privado, '$colorsticky', $anuncio, SUBSTRING('$ip', 1, 255), $nocom, $principal, 1)";

  // var_dump($str);
db_query("
  INSERT INTO {$db_prefix}messages (ID_TOPIC, ID_BOARD, ID_MEMBER, subject, body, posterName, posterEmail, posterTime, hiddenOption, color, anuncio, posterIP, smileysEnabled, sticky, visitas)
  VALUES ($ID_TOPIC, $categorias, $ID_MEMBER, SUBSTRING('$titulo', 1, 70), SUBSTRING('$post', 1, 65534), SUBSTRING('$realName', 1, 255), SUBSTRING('$emailAddress', 1, 255), " . time() . ", $privado, '$colorsticky', $anuncio, SUBSTRING('$ip', 1, 255), $nocom, $principal, 1)", __FILE__, __LINE__);

// $ID_TOPICTA = db_insert_id();
$ID_TOPICTA = $ID_TOPIC;

// Tags
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
    VALUES ($ID_TOPICTA, SUBSTRING('$a[$i]', 1, 65), 1, $rg)", __FILE__, __LINE__);
}
// Fin tags

estadisticastopic();

$_SESSION['last_read_topic'] = 0;
$_SESSION['ultima_accionTIME'] = time();
$urls = $boardurl . '/post/' . $ID_TOPICTA . '/' . $descript . '/' . urls($titulo) . '.html';

PostAccionado('Post agregado correctamente', 'Tu post "<strong>' . censorText($titulo) . '</strong>" ha sido agregado correctamente.', $urls, 'Ir al post');

?>