<?php
require_once(dirname(__FILE__) . '/header-seg-1as4d4a777.php');
global $context, $settings, $options, $txt, $con, $scripturl;
global $tranfer1, $user_settings, $ID_MEMBER;
global $prefijo, $user_info, $modSettings;

if ($user_info['is_admin'] || $user_info['is_mods']) {
  $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

  if (empty($id)) {
    falta('Debes seleccionar un art&iacute;culo.');
  }

  $catlist = db("
    SELECT id
    FROM {$prefijo}articulos
    WHERE id = $id
    ORDER BY id ASC
    LIMIT 1", __FILE__, __LINE__);

  $dat = mysqli_fetch_assoc($catlist);
  $qid = isset($dat['id']) ? $dat['id'] : '';

  if (empty($qid)) {
    falta('El art&iacute;culo no existe.');
  }

  db("
    DELETE FROM {$prefijo}articulos
    WHERE id = $id", __FILE__, __LINE__);

  header('Location: /');
} else {
  falta('Debes ser parte del staff para realizar esta acci&oacute;n.');
}

require_once(dirname(__FILE__) . '/footer-seg-145747dd.php');

?>