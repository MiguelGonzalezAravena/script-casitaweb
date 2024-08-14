<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');

global $context, $db_prefix, $modSettings, $user_settings, $user_info;

if ($user_info['is_guest']) {
  die();
}

ignore_user_abort(true);
@set_time_limit(300);

$url = 'http://fmcasita.net/lyrics.php';
$nick1 = 'bu10q';  // teclado222
$nick2 = 'MathiaJ_';  // teclado222
$nick3 = 'blazer696';  // teclado222
$nick4 = 'charlesssss';  // teclado222
$nick5 = 'akuma48461';  // teclado222

$nicksARRAYS = array($nick1, $nick2, $nick3, $nick4, $nick5);
$nikcs = rand(0, sizeof($nicksARRAYS) - 1);
$nick = $nicksARRAYS[$nikcs];

if ($nick == $nick1) {
  $idUSER = '149792';
} else if ($nick == $nick2) {
  $idUSER = '149793';
} else if ($nick == $nick3) {
  $idUSER = '149795';
} else if ($nick == $nick4) {
  $idUSER = '149796';
} else if ($nick == $nick5) {
  $idUSER = '149797';
} else {
  header('Location: ' . $url);
}

$tituloedit = strtr(htmlspecialchars($_POST['titulo']), array("\r" => '', "\n" => '', "\t" => ''));
$titulo = addcslashes($tituloedit, '"');
$titulo = trim($tituloedit);

$postedit = htmlspecialchars($_POST['contenido']);
$post = str_replace(array('"', '<', '>', '  ', "'", '�', '�'), array('&quot;', '&lt;', '&gt;', ' &nbsp;', '&#39;', '&#8217;', '&#8216;'), $postedit);
$post = preg_replace('~<br(?: /)?' . '>~i', "\n", $postedit);
$post = trim($postedit);

$categorias = isset($_POST['categorias']) ? (int) $_POST['categorias'] : 0;
$tags = isset($_POST['tags']) ? trim(strtolower($_POST['tags'])) : '';
$privado = isset($_POST['privado']) ? (int) $_POST['privado'] : 0;
$anuncio = 0;
$principal = 0;

if (empty($titulo)) {
  header('Location: ' . $url);
}

if (empty($post)) {
  header('Location: ' . $url);
}

if (empty($categorias)) {
  header('Location: ' . $url);
}

if (empty($tags)) {
  header('Location: ' . $url);
}

if (strlen($_POST['titulo']) < 3) {
  header('Location: ' . $url);
}

if (strlen($_POST['titulo']) >= 61) {
  header('Location: ' . $url);
}

if (strlen($_POST['contenido']) <= 60) {
  header('Location: ' . $url);
}

if (strlen($_POST['contenido']) > $modSettings['max_messageLength']) {
  header('Location: ' . $url);
}

$context['contadorsss'] = mysqli_num_rows(db_query("
  SELECT ID_BOARD
  FROM {$db_prefix}boards
  WHERE ID_BOARD = $categorias
  LIMIT 1", __FILE__, __LINE__));

if (empty($context['contadorsss'])) {
  header('Location: ' . $url);
}

// Tags
$ak = explode(',', $tags);
$Nn = implode(',', array_diff($ak, array_values(array(''))));
$a = explode(',', $Nn);
$c = sizeof($a);

if ($c < 4) {
  header('Location: ' . $url);
}

if ($c > 5) {
  $c = 5;
}

if ($user_settings['posts'] >= '500') {
  $dddderrr = isset($_POST['nocom']) ? (int) $_POST['nocom'] : 0;

  if ($dddderrr == 0 || $dddderrr == 1) {
    $nocom = $dddderrr;
  } else {
    $nocom = 0;
  }
} else {
  $nocom = 0;
}

db_query("
  INSERT INTO {$db_prefix}messages (ID_BOARD, ID_MEMBER, subject, body, posterName, posterEmail, posterTime, hiddenOption, color, anuncio, posterIP, smileysEnabled, sticky, visitas) 
  VALUES ($categorias, $idUSER, SUBSTRING('$titulo', 1,70), SUBSTRING('$post', 1, 65534), SUBSTRING('$nick', 1, 255), SUBSTRING('blazer696@gmail.com', 1, 255), " . time() . ", $privado, '$colorsticky', $anuncio, SUBSTRING('66.249.71.18', 1, 255), $nocom, $principal, 1)", __FILE__, __LINE__);

$ID_TOPICTA = db_insert_id();

// Tags
for ($i = 0; $i < $c; ++$i) {
  $lvccct = db_query("
    SELECT id
    FROM {$db_prefix}tags
    WHERE palabra = '$a[$i]'
    AND rango = 1
    LIMIT 1", __FILE__, __LINE__);

  $asserr = mysqli_fetch_assoc($lvccct);
  $idse = $asserr['id'];
  $idse = isset($idse) ? $idse : '';

  if (!empty($idse)) {
    db_query("
      UPDATE {$db_prefix}tags
      SET cantidad = cantidad + 1
      WHERE id = $idse
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

header('Location: ' . $url);

?>