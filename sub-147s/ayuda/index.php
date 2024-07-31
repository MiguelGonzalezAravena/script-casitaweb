<?php
require_once(dirname(__FILE__) . '/header-seg-1as4d4a777.php');
global $db_prefix, $context, $user_info, $scripturl, $no_avatar, $modSettings, $board, $prefijo, $helpurl;

echo '
  <div class="box_buscador" style="margin-bottom: 8px;">
    <div class="box_title" style="width: 922px;">
      <div class="box_txt box_buscadort">Categor&iacute;as</div>
      <div class="box_rss">
        <img alt="" src="' . $helpurl . '/imagenes/espacio.gif" style="width: 14px; height: 12px;" border="0" />
      </div>
    </div>
    <div style="width: 914px; padding: 4px;" class="windowbg">';

$catlist = db("
  SELECT catid, cat, enlace
  FROM {$prefijo}cats
  WHERE maincat=0
  ORDER BY cat ASC", __FILE__, __LINE__);

$countt = 1;

echo '
  <table align="center">
    <tr>';

while ($cat = mysqli_fetch_assoc($catlist)) {
  $thiscat = stripslashes($cat['cat']);

  echo '
    <td style="padding-right: 150px;">
      <img alt="" src="' . $helpurl . '/imagenes/carpeta.png" title="' . $thiscat . '" />
      <a href="' . $helpurl . '/categoria/' . $cat['enlace'] . '">' . $thiscat . '</a>';

  $request = db("
    SELECT categoria
    FROM {$prefijo}articulos
    WHERE categoria = {$cat['catid']}", __FILE__, __LINE__);

  $qcount = mysqli_num_rows($request);

  echo ' (' . $qcount . ')';
  echo '</td>';

  $br = $countt++;

  if ($br == 3) {
    echo '
      </tr>
      <tr>';
  }

  if ($br == 6) {
    echo '
      </tr>
      <tr>';
  }

  if ($br == 9) {
    echo '</tr>';
  }
}

echo '
        </tr>
      </table>
    </div>
  </div>';

$qlist = db("
  SELECT id, titulo, fecha
  FROM {$prefijo}articulos
  ORDER BY fecha DESC
  LIMIT 5", __FILE__, __LINE__);

echo '
  <div class="box_460" style="float: left; margin-right: 4px;">
    <div class="box_title" style="width: 456px;">
      <div class="box_txt box_460-34">5 art&iacute;culos recientes</div>
      <div class="box_rss">
        <img alt="" src="' . $helpurl . '/imagenes/espacio.gif" style="width: 14px; height: 12px;" border="0" />
      </div>
    </div>
    <div style="width: 448px; padding: 4px;" class="windowbg">';

while ($questions = mysqli_fetch_assoc($qlist)) {
  $questions['titulo'] = strlen($questions['titulo']) > 45 ? substr($questions['titulo'], 0, 42) . '...' : $questions['titulo'];
  $question = censorText(nohtml2($questions['titulo']));
  $dateadded = $questions['fecha'];

  echo '
    <img alt="" src="' . $helpurl . '/imagenes/articulo.png" title="' . $question . '" />
    <a href="' . $helpurl . '/articulo/' . $questions['id'] . '">' . $question . '</a>
    (' . timeformat($dateadded) . ')
    <br />';
}

echo '
    </div>
  </div>';

$qlist = db("
  SELECT id, titulo, vieron
  FROM {$prefijo}articulos
  WHERE vieron > 0
  ORDER BY vieron DESC
  LIMIT 5", __FILE__, __LINE__);

echo '
  <div style="float: left;" class="box_460">
    <div class="box_title" style="width: 460px;">
      <div class="box_txt box_460-34">5 art&iacute;culos m&aacute;s populares (Por visitas)</div>
      <div class="box_rss">
        <img alt="" src="' . $helpurl . '/imagenes/espacio.gif" style="width: 14px; height: 12px;" border="0" />
      </div>
    </div>
    <div style="width: 452px; padding: 4px;" class="windowbg">';

while ($questions = mysqli_fetch_assoc($qlist)) {
  $questions['titulo'] = strlen($questions['titulo']) > 45 ? substr($questions['titulo'], 0, 42) . '...' : $questions['titulo'];
  $question = censorText(nohtml2($questions['titulo']));
  $viewed = $questions['vieron'] == 1 ? '1  visita' : $questions['vieron'] . ' visitas';

  echo '
    <img alt="" src="' . $helpurl . '/imagenes/articulo.png" title="' . $question . '" />
    <a href="' . $helpurl . '/articulo/' . $questions['id'] . '">' . $question . '</a>
    (' . $viewed . ')
    <br />';
}

echo '
    </div>
  </div>';

require_once(dirname(__FILE__) . '/footer-seg-145747dd.php');

?>