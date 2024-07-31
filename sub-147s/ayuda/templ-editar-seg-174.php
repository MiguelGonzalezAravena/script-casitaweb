<?php
require_once(dirname(__FILE__) . '/header-seg-1as4d4a777.php');
global $db_prefix, $context, $user_info, $scripturl, $no_avatar, $modSettings, $tranfer1, $board, $prefijo;

if ($user_info['is_admin'] || $user_info['is_mods']) {
  $art = isset($_GET['art']) ? (int) $_GET['art'] : 0;

  if (empty($art)) {
    falta('Debe seleccionar un art&iacute;culo.');
  }

  $catlist = db("
    SELECT titulo, contenido, id, vieron, fecha, categoria
    FROM {$prefijo}articulos
    WHERE id = $art
    ORDER BY id ASC
    LIMIT 1", __FILE__, __LINE__);

  $dat = mysqli_fetch_assoc($catlist);
  $qid = isset($dat['id']) ? $dat['id'] : '';
  $categoria = isset($dat['categoria']) ? $dat['categoria'] : '';
  $texto = isset($dat['contenido']) ? censorText($dat['contenido']) : '';
  $titulo = isset($dat['titulo']) ? censorText($dat['titulo']) : '';

  if (empty($qid)) {
    falta('El art&iacute;culo no existe.');
  }

  echo '
    <form action="' . $helpurl . '/art-editando/" method="post" accept-charset="' . $context['character_set'] . '" name="editarArticulo" id="editarArticulo" enctype="multipart/form-data" style="margin: 0;">
      <div class="box_buscador">
        <div class="box_title" style="width: 922px;">
          <div class="box_txt box_buscadort">
            <center>Agregar articulo</center>
          </div>
          <div class="box_rss">
            <img alt="" src="' . $helpurl . '/imagenes/espacio.gif" style="width: 14px; height: 12px;" border="0" />
          </div>
        </div>
        <div style="width: 914px; padding: 4px;" class="windowbg">
          <b class="size11">T&iacute;tulo:</b>
          <br />
          <input type="text" onfocus="foco(this);" onblur="no_foco(this);" name="titulo" value="' . $titulo . '" tabindex="1" style="width: 907px;" maxlength="60" />
          <br />
          <b class="size11">Mensaje del art&iacute;culo:</b>
          <textarea onfocus="foco(this);" onblur="no_foco(this);" style="height: 300px; width: 907px;" id="markItUp" name="contenido" class="markItUpEditor" tabindex="2">' . $texto . '</textarea>
          <b class="size11">Categor&iacute;a:</b>
          <br />
          <select tabindex="3" name="categorias">';

  $catlist = db("
    SELECT catid, cat, enlace
    FROM {$prefijo}cats
    WHERE maincat = 0
    ORDER BY cat ASC", __FILE__, __LINE__);

  while ($cat = mysqli_fetch_assoc($catlist)) {
    echo '
      <option ' . ($categoria == $cat['catid'] ? 'selected="selected" ' : '') . 'value="' . $cat['catid'] . '">' . $cat['cat'] . '</option>';
  }

  echo '
          </select>
          <br />
          <br />
          <input class="button" style="font-size: 15px;" value="Editar" title="Editar" type="submit" tabindex="4" />
        </div>
      </div>
      <input type="hidden" value="' . $qid . '" name="id_articulo" />
    </form>';
} else {
  falta('Debes ser parte del staff para realizar esta acci&oacute;n.');
}

require_once(dirname(__FILE__) . '/footer-seg-145747dd.php');

?>