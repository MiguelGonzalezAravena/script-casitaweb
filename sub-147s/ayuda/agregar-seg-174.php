<?php
require_once(dirname(__FILE__) . '/header-seg-1as4d4a777.php');
global $context, $settings, $options, $txt, $con, $scripturl;
global $tranfer1, $user_settings, $ID_MEMBER;
global $prefijo, $user_info, $modSettings;

if ($user_info['is_admin'] || $user_info['is_mods']) {
  ignore_user_abort(true);
  @set_time_limit(300);

  $tituloedit = strtr(htmlspecialchars($_POST['titulo']), array("\r" => '', "\n" => '', "\t" => ''));
  $titulo = addcslashes($tituloedit, '"');
  $titulo = trim(censorText($tituloedit));
  $postedit = htmlspecialchars(stripslashes($_POST['contenido']), ENT_QUOTES);
  $post = str_replace(array('"', '<', '>', '  '), array('&quot;', '&lt;', '&gt;', ' &nbsp;'), $postedit);
  $post = preg_replace('~\[hide\](.+?)\[\/hide\]~i', '&nbsp;', $postedit);
  $post = preg_replace(array('~\n?\[hide.*?\].+?\[/hide\]\n?~is', '~^\n~', '~\[/hide\]~'), '&nbsp;', $postedit);
  $post = preg_replace('~<br(?: /)?' . '>~i', "\n", $postedit);
  $post = trim(censorText($postedit));
  $categorias = isset($_POST['categorias']) ? (int) $_POST['categorias'] : 0;

  if (empty($titulo)) {
    falta('Falt&oacute; escribirle un t&iacute;tulo.');
  }

  if (empty($post)) {
    falta('Falt&oacute; escribir el post.');
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
    INSERT INTO {$prefijo}articulos (titulo, contenido, categoria, fecha, vieron, idpor, namepor)
    VALUES (SUBSTRING('$titulo', 1, 70), SUBSTRING('$post', 1, 65534), $categorias, " . time() . ", 1, $ID_MEMBER, '{$user_settings['realName']}')", __FILE__, __LINE__);

  header('Location: ' . $helpurl . '/');
} else {
  falta('Debes ser parte del staff para realizar esta acci&oacute;n.');
}

require_once(dirname(__FILE__) . '/footer-seg-145747dd.php');

?>