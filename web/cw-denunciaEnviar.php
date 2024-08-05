<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $context, $ID_MEMBER, $db_prefix, $user_info;

$ID_TOPIC2 = isset($_POST['id']) ? seguridad($_POST['id']) : '';
$tipo = isset($_POST['tipo']) ? seguridad($_POST['tipo']) : '';
$comentario = isset($_POST['comentario']) ? seguridad($_POST['comentario']) : '';
$razon = isset($_POST['razon']) ? seguridad($_POST['razon']) : '';

if ($user_info['is_guest']) {
  die('0: Funcionalidad exclusiva de usuarios registrados.');
}

if (empty($comentario)) {
  die('0: Debes escribir un comentario.');
}

if (strlen($comentario) >= 300) {
  die('0: El comentario es demasiado extenso.');
}

timeforComent();

switch ($tipo) {
  case 'imagen':
    if ($razon == 'Imagen ya agregada' || $razon == 'Se hace Spam' || $razon == 'Contiene Pornografia' || $razon == 'Es Gore o asqueroso' || $razon == 'No cumple con el protocolo' || $razon == 'Otra razon (especificar)') {
      $request = db_query("
        SELECT ID_PICTURE, title, ID_MEMBER
        FROM {$db_prefix}gallery_pic
        WHERE ID_PICTURE = $ID_TOPIC2
        LIMIT 1", __FILE__, __LINE__);

      $row = mysqli_fetch_assoc($request);
      $cxxxs = isset($row['ID_PICTURE']) ? $row['ID_PICTURE'] : '';
      $rdasdasdasd2 = $row['title'];
      $rdasdasdaswdd2 = $row['ID_MEMBER'];

      mysqli_free_result($request);

      if (empty($cxxxs)) {
        die('0: La imagen que deseas denunciar no existe.');
      }

      $errorr = db_query("
        SELECT id_user
        FROM {$db_prefix}denuncias
        WHERE id_user = $ID_MEMBER
        AND id_post = $ID_TOPIC2
        AND tipo = 1
        LIMIT 1", __FILE__, __LINE__);

      $yadio = mysqli_num_rows($errorr);
  
      if ($yadio) {
        die('0: Ya has denunciado esta imagen');
      }

      $tiempo = time();

      db_query("
        INSERT INTO {$db_prefix}denuncias (id_post, id_user, razon, comentario, name_post, tipo, tiempo)
        VALUES ($ID_TOPIC2, $ID_MEMBER, '$razon', '$comentario', '$rdasdasdasd2', 1, $tiempo)", __FILE__, __LINE__);
    } else {
      die('0: Hubo un error con la raz&oacute;n');
    }
    break;
  case 'post':
    if ($razon == 'Re-post' || $razon == 'Se hace Spam' || $razon == 'Tiene enlaces muertos' || $razon == 'Es Racista o irrespetuoso' || $razon == 'Contiene informacion personal' || $razon == 'Contiene Pornografia' || $razon == 'Es Gore o asqueroso' || $razon == 'Esta mal la fuente' || $razon == 'Post demasiado pobre' || $razon == 'El Titulo esta en mayuscula' || $razon == 'Pide contrasena y no esta' || $razon == 'No cumple con el protocolo' || $razon == 'Otra razon (especificar)') {
      $request = db_query("
        SELECT subject, ID_TOPIC, ID_MEMBER, ID_BOARD
        FROM {$db_prefix}messages
        WHERE ID_TOPIC = $ID_TOPIC2
        LIMIT 1", __FILE__, __LINE__);

      $row = mysqli_fetch_assoc($request);
      $rdsasdasdasd = strtr(htmlspecialchars($row['subject']), array("\r" => '', "\n" => '', "\t" => ''));
      $rdasdasdasd = addcslashes($rdsasdasdasd, '"');
      $rdasdasdasd = addcslashes($rdsasdasdasd, "'");
      $rdasdasdasd = censorText($rdasdasdasd);
      $ddssss = isset($row['ID_TOPIC']) ? $row['ID_TOPIC'] : '';
      $ID_BOARD = $row['ID_BOARD'];
      $rdasdasdaswdd = $row['ID_MEMBER'];

      mysqli_free_result($request);

      if (empty($ddssss)) {
        die('0: El post que deseas denunciar no existe');
      }
  
      $errorr = db_query("
        SELECT id_user
        FROM {$db_prefix}denuncias
        WHERE id_user = $ID_MEMBER
        AND id_post = $ID_TOPIC2
        AND tipo = 0
        LIMIT 1", __FILE__, __LINE__);

      $yadio = mysqli_num_rows($errorr);

      if ($yadio) {
        die('0: Ya has denunciado este post.');
      }
  
      $tiempo = time();

      db_query("
        INSERT INTO {$db_prefix}denuncias (id_post, id_user, razon, comentario, name_post, cat, tipo, tiempo)
        VALUES ($ID_TOPIC2, $ID_MEMBER, '$razon', '$comentario', '$rdasdasdasd', $ID_BOARD, 0, $tiempo)", __FILE__, __LINE__);
    } else {
      die('0: Hubo un error con la raz&oacute;n.');
    }
    break;
  case 'user':
    if ($razon == 'Hace Spam' || $razon == 'Es Racista o irrespetuoso' || $razon == 'Publica informacion personal' || $razon == 'Publica Pornografia' || $razon == 'No cumple con el protocolo' || $razon == 'Otra razon (especificar)') {
      $request = db_query("
        SELECT realName, ID_MEMBER
        FROM {$db_prefix}members
        WHERE realName = '$ID_TOPIC2'
        LIMIT 1", __FILE__, __LINE__);

      $row = mysqli_fetch_assoc($request);
      $rdasdasdasd = $row['realName'];
      $ddssss = isset($row['ID_MEMBER']) ? $row['ID_MEMBER'] : '';

      mysqli_free_result($request);

      if (empty($ddssss)) {
        die('0: El usuario que deseas denunciar no existe.');
      }
  
      $errorr = db_query("
        SELECT id_user
        FROM {$db_prefix}denuncias
        WHERE id_user = $ID_MEMBER
        AND id_post = $ID_TOPIC2
        AND tipo = 3
        LIMIT 1", __FILE__, __LINE__);

      $yadio = mysqli_num_rows($errorr);

      if ($yadio) {
        die('0: Ya has denunciado a este usuario.');
      }

      $tiempo = time();

      db_query("
        INSERT INTO {$db_prefix}denuncias (id_post, id_user, razon, comentario, name_post, tipo, tiempo)
        VALUES ($ID_TOPIC2, $ID_MEMBER, '$razon', '$comentario', '$rdasdasdasd', 3, $tiempo)", __FILE__, __LINE__);
    } else {
      die('0: Hubo un error con la raz&oacute;n.');
    }
    break;
  default:
    die('0: Error');
    break;
}

$_SESSION['ultima_accionTIME'] = time();

die('1: Denuncia enviada satisfactoriamente.');

?>