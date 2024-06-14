<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $tranfer1, $db_prefix, $context, $settings, $ajaxError, $options, $ID_MEMBER, $scripturl,$modSettings, $txt, $boardurl;

if (empty($context['ajax'])) {
  echo $ajaxError;
  die();
}

if (empty($ID_MEMBER)) {
  die();
}

loadLanguage('Post');

$responder = isset($_GET['responder']) ? (int) $_GET['responder'] : 0;

if (!empty($responder)) {
  $request = db_query("
    SELECT mensaje, fecha, titulo, name_de
    FROM {$db_prefix}mensaje_personal
    WHERE id = $responder
    AND id_para = $ID_MEMBER
    LIMIT 1", __FILE__, __LINE__);

  while ($row = mysqli_fetch_assoc($request)) {
    censorText($row['mensaje']);

    $row['mensaje'] = trim(un_htmlspecialchars(strip_tags(strtr(parse_bbc($row['mensaje'],false), array('<br />' => "\n", '</div>' => "\n", '</li>' => "\n", '&#91;' => '[', '&#93;' => ']')))));
    $comentario = $row['mensaje'];
    $fecha = $row['fecha'];
    $titulo = 'Re: ' . censorText($row['titulo']);
    $nombre = $row['name_de'];
  }

  mysqli_free_result($request);
}

$_GET['user'] = isset($_GET['user']) ? $_GET['user'] : '';

echo '
  <div>
    <img id="cargandoMP" alt="" src="' . $tranfer1 . '/icons/cargando.gif" style="width: 16px; height: 16px; display: none;" border="0" />
    <div id="resultadomp" style="display: none; width: 438px;"></div>
    <div id="contenidomp" style="width: 438px;">
      <form action="' . $boardurl . '/web/cw-redactarMp.php" method="post" accept-charset="' . $context['character_set'] . '" name="postmodify" id="postmodify">
        <div>';

if (empty($_GET['user'])) {
  $tip = 1;

  echo '
    <strong class="size12">Para:</strong>
    <br />
    <input type="text" name="para" id="para" onfocus="foco(this);"  tabindex="1" onblur="no_foco(this);" id="para" value="" size="40"/>
    <br />';
} else {
  $tip = 0;
}

echo '
  <strong class="size12">Asunto:</strong>
  <br />
  <input type="text" onfocus="foco(this);" onblur="no_foco(this);" name="titulo" id="titulo" value="' . (!empty($responder) ? $titulo : 'Sin asunto') . '" tabindex="2" size="40" maxlength="50"  />
  <br />
  <strong class="size12">Mensaje Privado:</strong>
  <br />';

$fecha2 = tiempo1($fecha);

echo '
  <form id="postmodify">
    <textarea style="height: 60px; width: 432px;" onfocus="foco(this);" onblur="no_foco(this);" id="editorCW" name="mensaje" class="mensaje" tabindex="3">';

if (!empty($responder)) {
  echo 'El ' . $fecha2 . ', ' . $nombre . ' escribi&oacute;:
> ' . str_replace("\n", "\n> ", $comentario) . '
';
}

echo '</textarea>
  <p align="right">';

if ($modSettings['smiley_enable']) {
  $existe = db_query("
    SELECT hidden, ID_SMILEY, description, filename, code
    FROM {$db_prefix}smileys
    WHERE hidden = 0
    ORDER BY ID_SMILEY ASC", __FILE__, __LINE__);

  while ($row = mysqli_fetch_assoc($existe)) {
    echo '<span style="cursor: pointer;" onclick="replaceText(\' ' . $row['code'] . '\', document.forms.postmodify.editorCW); return false;"><img src="' . $tranfer1 . '/emoticones/' . $row['filename'] . '" align="bottom" alt="" title="' . $row['description'] . '" class="png" /></span> ';
  }

  mysqli_free_result($existe);
}

echo '
        <br />
        <input class="login" type="button" value="Enviar" tabindex="3" onclick="enviarMP(\'' . $tip . '\', \'' . $_GET['user'] . '\');"/>
      </p>
      </form>
    </div>
  </div>';

?>