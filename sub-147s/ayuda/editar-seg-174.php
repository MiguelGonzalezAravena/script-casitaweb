<?php
require_once(dirname(__FILE__) . '/header-seg-1as4d4a777.php');

global $context, $settings, $options, $txt, $con, $scripturl;
global $tranfer1, $user_settings, $ID_MEMBER;
global $prefijo, $user_info, $modSettings;

if ($user_info['is_admin'] || $user_info['is_mods']) {
  ignore_user_abort(true);
  @set_time_limit(300);

  $id_articulo = isset($_POST['id_articulo']) ? (int) $_POST['id_articulo'] : 0;

  $catlist = db("
    SELECT id
    FROM {$prefijo}articulos
    WHERE id = $id_articulo
    ORDER BY id ASC
    LIMIT 1", __FILE__, __LINE__);

  $dat = mysqli_fetch_assoc($catlist);
  $qid = isset($dat['id']) ? $dat['id'] : '';

  if (empty($qid)) {
    falta('El art&iacute;culo no existe.');
  }

  if (empty($id_articulo)) {
    falta('Debe seleccionar un art&iacute;culo.');
  }

  $tituloedit = strtr(htmlspecialchars($_POST['titulo']), array("\r" => '', "\n" => '', "\t" => ''));
  $titulo = addcslashes($tituloedit, '"');
  $titulo = trim(censorText($tituloedit));
  $postedit = htmlspecialchars(stripslashes($_POST['contenido']), ENT_QUOTES);
  $post = str_replace(array('"', '<', '>', '  '), array('&quot;', '&lt;', '&gt;', ' &nbsp;'), $postedit);
  $post = preg_replace('~\[hide\](.+?)\[\/hide\]~i', '&nbsp;', $postedit);
  $post = preg_replace(array('~\n?\[hide.*?\].+?\[/hide\]\n?~is', '~^\n~', '~\[/hide\]~'), '&nbsp;', $postedit);
  $post = preg_replace('~<br(?: /)?' . '>~i', "\n", $postedit);
  $post = trim(censorText($postedit));
  $categorias = isset($_POST['categorias']) ? (int) $_POST['categorias'] : '';

  if (empty($titulo)) {
    falta('Falt&oacute; escribirle un t&iacute;tulo.');
  }

  if (empty($post)) {
    falta('Falt&oacute; escribir el art&iacute;culo.');
  }

  if (empty($categorias)) {
    falta('Falt&oacute; asignarle la categor&iacute;a.');
  }

  if (strlen($_POST['titulo']) < 3) {
    falta('El t&iacute;tulo no puede tener menos de <b>3 letras</b>.');
  }

  if (strlen($_POST['titulo']) >= 61) {
    falta('El t&iacute;tulo no puede tener m&aacute;s de <b>60 letras</b>.');
  }

  if (strlen($_POST['contenido']) <= 60) {
    falta('El post no puede tener menos de <b>60 letras</b>.');
  }

  if (strlen($_POST['contenido']) > $modSettings['max_messageLength']) {
    falta('El post no puede tener m&aacute;s de <b>' . $modSettings['max_messageLength'] . ' letras</b>.');
  }

  $request = db("
    SELECT catid
    FROM {$prefijo}cats
    WHERE catid = $categorias
    LIMIT 1", __FILE__, __LINE__);

  $context['contadorsss'] = mysqli_num_rows($request);

  if (empty($context['contadorsss'])) {
    falta('La categor&iacute;a especificada no existe.');
  }

  db("
    UPDATE {$prefijo}articulos
    SET
      titulo = '$titulo',
      contenido = '$post',
      categoria = $categorias,
      fechaedit = " . time() . "
      WHERE id = $id_articulo
      LIMIT 1", __FILE__, __LINE__);

  header('Location: ' . $helpurl . '/articulo/' . $id_articulo);
} else {
  falta('Debes ser parte del staff para realizar esta acci&oacute;n.');
}

require_once(dirname(__FILE__) . '/footer-seg-145747dd.php');
?>