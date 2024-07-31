<?php
require_once(dirname(__FILE__) . '/header-seg-1as4d4a777.php');
global $context;

$art = isset($_GET['art']) ? (int) $_GET['art'] : 0;

if (empty($art)) {
  falta('Debes seleccionar el art&iacute;culo.');
}

if (empty($_SESSION['topic' . $art . 'visita'])) {
  db("
    UPDATE {$prefijo}articulos
    SET vieron = vieron + 1
    WHERE id = $art", __FILE__, __LINE__);

  $_SESSION['topic' . $art . 'visita'] = 1;
}

$catlist = db("
  SELECT titulo, contenido, id, vieron, fecha
  FROM {$prefijo}articulos
  WHERE id = $art
  ORDER BY id ASC
  LIMIT 1", __FILE__, __LINE__);

$dat = mysqli_fetch_assoc($catlist);
$qid = isset($dat['id']) ? $dat['id'] : '';
$visitas = isset($dat['vieron']) ? $dat['vieron'] : '';
$fecha = isset($dat['fecha']) ? $dat['fecha'] : '';
$texto = isset($dat['contenido']) ? bbcode($dat['contenido']) : '';
$titulo = isset($dat['titulo']) ? $dat['titulo'] : '';
$texto = str_replace('http://link.casitaweb.net/index.php?l=', '', $texto);

echo '
  <div class="box_buscador">
    <div class="box_title" style="width: 922px;">
      <div class="box_txt box_buscadort">
        <center>' . $titulo . '</center>
      </div>
      <div class="box_rss">
        <img alt="" src="' . $helpurl . '/imagenes/espacio.gif" style="width: 14px; height: 12px;" border="0" />
      </div>
    </div>
    <div style="width: 914px; padding: 4px;" class="windowbg">
      <div class="post-contenido" property="dc:content">
      ' . $texto . '
      <hr class="divider" />
      <center>
        <b style="color: green;">
          ' . $visitas . ' visitas
          |
          ' . timeformat($fecha);
if ($context['allow_admin']) {
  echo '
    |
    <a href="' . $helpurl . '/editar/' . $qid . '">
      <u>Editar</u>
    </a>
    |
    <a onclick="if (!confirm(\'\xbfEst&aacute;s seguro que deseas eliminar este art&iacute;culo?\')) return false;" href="' . $helpurl . '/eliminar/' . $qid . '" >
      <u>Eliminar</u>
    </a>';
}

echo '
          </b>
        </center>
      </div>
    </div>
  </div>';

if (empty($qid)) {
  falta('El articulo no existe.');
}

require_once(dirname(__FILE__) . '/footer-seg-145747dd.php');

?>