<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $context, $db_prefix, $user_settings;

if ($user_info['is_admin'] || $user_info['is_mods']) {
  ignore_user_abort(true);
  @set_time_limit(300);
      
  $titulo = isset($_POST['titulo']) ? seguridad($_POST['titulo']) : '';
  $texto = isset($_POST['texto']) ? seguridad($_POST['texto']) : '';
  $cerrado = isset($_POST['cerrado']) ? (int) $_POST['cerrado'] : 0;
  $look = empty($cerrado) ? 0 : 1;
  // $date = new DateTime();
  // $date = $date->format('Y-m-d H:i:s');

  timeforComent('1');

  if (strlen($titulo) > 70) {
    fatal_error('El t&iacute;tulo no debe tener m&aacute;s de 70 letras.');
  }

  if (empty($titulo)) {
    fatal_error('Debes escribir un t&iacute;tulo al post.', false);
  }

  if (strlen($titulo) > 65536) {
    fatal_error('El mensaje no debe tener m&aacute;s de 65536 letras.');
  }

  if (empty($texto)) {
    fatal_error('Debes escribir un mensaje.');
  }

  db_query("
    INSERT INTO {$db_prefix}comunicacion (id_user, titulo, cerrado, texto)
    VALUES ({$user_settings['ID_MEMBER']}, '$titulo', $look, '$texto')", __FILE__, __LINE__);

  $post = db_insert_id();

  // Notificaciones
  $request = db_query("
    SELECT ID_MEMBER
    FROM {$db_prefix}members
    WHERE ID_GROUP = 1
    OR ID_GROUP = 2", __FILE__, __LINE__);

  while ($row = mysqli_fetch_array($request)) {
    notificacionAGREGAR($row['ID_MEMBER'], '10');
  }

  mysqli_free_result($request);

  $_SESSION['ultima_accionTIME'] = time();

  header('Location: ' . $boardurl . '/moderacion/comunicacion-mod/post/' . $post);
} else {
  die();
}

?>