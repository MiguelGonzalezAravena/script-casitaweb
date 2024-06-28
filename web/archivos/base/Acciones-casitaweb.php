<?php
function template_intro()
{
  exit();
  die();
}

function template_tyc24()
{
  global $context, $user_settings, $modSettings, $tranfer1;

  if ($user_settings['ID_GROUP'] == '7' || $user_settings['ID_GROUP'] == '11') {
    if (empty($user_settings['dar_dia'])) {
      $ss = '<span style="color:red;">A las <span style="font-size:9px;" title="Horario Argentino">(' . $modSettings['horap'] . ')</span> se recargar&aacute;n las recargas.</span>';
    } else {
      $ss = '<span style="color:green;">' . $user_settings['dar_dia'] . ' recargas disponibles.</span>';
    }

    if ($user_settings['ID_GROUP'] == '7') {
      $pts = '5';
      $title = 'Heredero';
      $texto = '<b>&#191;Que es ser Heredero?</b><br/>- Los usuarios con rango Heredero son usuarios destacados dentro de <b>CasitaWeb!</b> por su comportamiento (Posteos, Comentarios, Ayudas, etc).<br/>- El rango heredero lo obtendran siempre que este comportamiento continue estable o mejore.<br /><i>Muchas gracias.</i>';
    } elseif ($user_settings['ID_GROUP'] == '11') {
      $pts = '5';
      $title = 'Abastecedor';
      $texto = '<b>&#191;Que es ser Abastecedor?</b><br/>- Los usuarios con rango Abastecedor son usuarios destacados dentro de <b>CasitaWeb!</b> por haber ayudado a <b>CasitaWeb!</b> subiendo material para la web.<br/>- El rango abastecedor lo obtendran siempre que este comportamiento continue estable o mejore.<br /><i>Muchas gracias.</i>';
    }

    echo '<div style="width:922px;">
<strong class="size17">' . $title . '</strong><div class="hrs"></div> ' . $texto;

?>

<script type="text/javascript">
//Recargar puntos
function recargarPTS(){
if($('#user').val() == ''){$('#user').focus(); return false;}
$('#cargandoBoxyc').css('display','none');
$('#cargandoBoxy').css('display','block');
$.ajax({
    type: 'POST',
    url: '/web/cw-recargarPts.php',
    cache: false,
    data: 'user=' +  encodeURIComponent($('#user').val()),
    success: function(h){
      $('#cargandoBoxy').css('display','none');
          $('#cargandoBoxyc').css('display','block');
          $('#contenidoRE').remove();
        if(h.charAt(0)==0){ //Datos incorrectos
          $('#resultadoRE').addClass('noesta');
          $('#resultadoRE').html(h.substring(3)).fadeIn('fast');
        } else
        if(h.charAt(0)==1){ //OK				
          $('#resultadoRE').removeClass('noesta');
          $('#resultadoRE').addClass('noesta-ve');
          $('#resultadoRE').html(h.substring(3)).fadeIn('fast');}			
    },
    error: function(){
      Boxy.alert("Error, volver a intentar...", null, {title: 'Alerta'});
    }
  });
}

</script>

<?php
    echo '<br /><br /><strong class="size17">Recargar puntos</strong> <span id="cargandoBoxy" style="display: none;"><img alt="" src="' . $tranfer1 . '/icons/cargando.gif" style="width: 16px; height: 16px;" border="0"></span>
<div class="hrs"></div>';

    echo '<div id="resultadoRE" style="display:none;"></div>
<div style="padding:8px;background-color:#f4f4f4;border:1px solid #ccc;" id="contenidoRE">
<div style="float:right;display:block;">
<div style="float:left;">' . $ss . '<input type="text" onfocus="foco(this);" onblur="no_foco(this);" id="user" tabindex="1" style="width:155px;" maxlength="60" title="Introduci el NICK tal cual es." /></div><div><input onclick="return recargarPTS();" type="button" value="Recargar" tabindex="2" /></div>
</div>

<div class="clearfix"></div>

</div>

 </div>';
  }
}

function template_tyc14()
{
  global $tranfer1, $db_prefix;

  echo '<div class="box_buscador"><div class="box_title" style="width: 920px;"><div class="box_txt box_buscadort"><center>Nube de Tags</center></div><div class="box_rss"><img alt="" src="' . $tranfer1 . '/blank.gif" style="width:14px;height:12px;" border="0" /></div></div><div style="width: 912px;padding:4px;" class="windowbg"><center>En esta nube se reflejan los 100 tags m&aacute;s populares. Cuanto m&aacute;s grande es la palabra, mayor cantidad de veces fue utilizada.<br />
ordenar: <a href="/tags-alfa/" title="alfab&eacute;ticamente">alfab&eacute;ticamente</a> | <a href="/tags-importacia/" title="por importancia">por importancia</a><div class="hrs"></div>';
  $_GET['orden'] = str_replace('/', '', $_GET['orden']);
  if (empty($_GET['orden'])) {
    $orden = 'palabra ASC';
  } elseif ($_GET['orden'] == 'alfa') {
    $orden = 'palabra ASC';
  } elseif ($_GET['orden'] == 'importacia') {
    $orden = 'cantidad DESC';
  } else {
    $orden = 'palabra ASC';
  }

  $fontmax = 18;
  $fontmin = 10;
  $tagmax = 100;
  if ($tagmax <= 0)
    $tagmax = 10;
  $result3 = db_query("
SELECT cantidad
FROM {$db_prefix}tags
ORDER BY cantidad DESC
LIMIT 99,1", __FILE__, __LINE__);
  while ($row = mysqli_fetch_array($result3)) {
    $cantidad = $row['cantidad'];
  }

  $result = db_query("SELECT palabra as tag,count(palabra) as quantity, cantidad
FROM {$db_prefix}tags
WHERE cantidad >= '$cantidad'
GROUP BY palabra 
ORDER BY $orden
LIMIT 0,$tagmax", __FILE__, __LINE__);
  while ($row = mysqli_fetch_array($result)) {
    $tags[$row['tag']] = $row['cantidad'];
  }
  $max_qty = max(array_values($tags));
  $universo = array_sum(array_values($tags));
  $elemento_menor = min(array_values($tags));
  $hoja = max(array_values($tags)) - $elemento_menor;
  if ($hoja <= 0)
    $hoja = 1;
  $letra_hoja = $fontmax - $fontmin;
  if ($letra_hoja <= 0)
    $letra_hoja = 1;
  $font_step = $letra_hoja / $hoja;
  $asdas = 1;
  foreach ($tags as $key => $value) {
    $porcentaje = 0;
    $porcentaje = ($value / $universo) * 100;
    $tamanio = (int) ($fontmin + (($value - $elemento_menor) * $font_step));
    $asfff = $asdas++;
    $paltag = strtolower(str_replace('%', '', $key));
    echo '<a href="/tags/' . $paltag . '" style="font-size:' . $tamanio . 'pt;margin-right:2px;margin-bottom:5px;" title="' . $value . ' post con el tag ' . $paltag . '">' . $paltag . '</a> ';
    if ($asfff == 50)
      echo '<br />';
    if ($asfff == 100)
      echo '<br />';
    if ($asfff == 150)
      echo '<br />';
    if ($asfff == 200)
      echo '<br />';
  }
  echo '</center></div></div>';
}

function template_tyc999()
{
  global $tranfer1, $context, $settings, $db_prefix, $sourcedir, $ID_MEMBER, $options, $txt, $modSettings, $scripturl;

  require ($sourcedir . '/Hear-Buscador.php');
  hearBuscador('0', 't');

  $pasda = seguridad($_GET['palabra']);

  if ($pasda) {
    $request = db_query("SELECT b.ID_BOARD, b.name, b.childLevel FROM {$db_prefix}boards AS b", __FILE__, __LINE__);
    $context['boards'] = array();
    while ($row = mysqli_fetch_assoc($request))
      $context['boards'][] = array('id' => $row['ID_BOARD'], 'name' => $row['name']);
    mysqli_free_result($request);
    $RegistrosAMostrar = $modSettings['search_results_per_page'];
    $_GET['pag'] = isset($_GET['pag']) ? $_GET['pag'] : '';
    if ($_GET['pag'] < 1) {
      $dud = 1;
    } else {
      $dud = $_GET['pag'];
    }
    if (isset($dud)) {
      $RegistrosAEmpezar = ($dud - 1) * $RegistrosAMostrar;
      $PagAct = $dud;
    } else {
      $RegistrosAEmpezar = 0;
      $PagAct = 1;
    }

    $NroRegistros = mysqli_num_rows(db_query("SELECT t.palabra FROM ({$db_prefix}tags as t, {$db_prefix}messages AS p) WHERE t.palabra='$pasda' AND t.id_post=p.ID_TOPIC", __FILE__, __LINE__));
    $request = db_query("
SELECT p.puntos,t.palabra,p.subject,p.ID_TOPIC,b.description,p.hiddenOption,p.posterName,p.posterTime
FROM ({$db_prefix}tags AS t, {$db_prefix}messages AS p,{$db_prefix}boards AS b) 
WHERE t.palabra='$pasda' AND t.id_post=p.ID_TOPIC AND p.ID_BOARD=b.ID_BOARD
GROUP BY p.subject
ORDER BY p.ID_TOPIC ASC
LIMIT $RegistrosAEmpezar, $RegistrosAMostrar", __FILE__, __LINE__);
    $context['tags'] = array();
    while ($row = mysqli_fetch_assoc($request)) {
      $context['tags'][] = array(
        'subject' => $row['subject'],
        'id' => $row['ID_TOPIC'],
        'hiddenOption' => $row['hiddenOption'],
        'puntos' => $row['puntos'],
        'posterTime' => $row['posterTime'],
        'description' => $row['description']
      );
    }
    mysqli_free_result($request);
    // div grande

    if (!$NroRegistros) {
      echo '<div class="noesta-am" style="width:922px;">No se encontraron resultados.</div>';
    } else {
      echo '<table class="linksList" style="width:922px;">
<thead align="center"><tr><th style="text-align:left;">' . $NroRegistros . ' Posts con el tag: ' . $pasda . '</th><th>Fecha</th><th>Puntos</th></tr></thead><tbody>';

      foreach ($context['tags'] as $tag) {
        echo '<tr>
<td style="text-align: left;">';

        if ($tag['hiddenOption'] == '1' && $context['user']['is_guest'])
          echo '<div class="icon_img" style="float:left;margin-right:0px;"><img alt="" src="' . $tranfer1 . '/icons/cwbig-v1-iconos.gif?v3.2.3" style="margin-top:-578px;display:inline;" /></div>';

        echo '<a rel="dc:relation" target="_self" href="/post/' . $tag['id'] . '/' . $tag['description'] . '/' . urls(censorText($tag['subject'])) . '.html" title="' . censorText($tag['subject']) . '" class="categoriaPost ' . $tag['description'] . '">' . censorText($tag['subject']) . '</a></td><td title="' . timeformat($tag['posterTime']) . '">' . timeformat($tag['posterTime']) . '</td><td title="' . $tag['puntos'] . ' Puntos" style="color:green;">' . $tag['puntos'] . '</td>';
      }

      echo '</tbody></table>';
      $PagAnt = $PagAct - 1;
      $PagSig = $PagAct + 1;
      $PagUlt = $NroRegistros / $RegistrosAMostrar;
      $Res = $NroRegistros % $RegistrosAMostrar;
      if ($Res > 0)
        $PagUlt = floor($PagUlt) + 1;
      if ($PagAct < $PagUlt) {
        echo '<div class="windowbgpag" style="width:698px;">';
        if ($PagAct > 1)
          echo "<a href='/tags/buscar/&q=$pasda&orden=$order&categoria=$cat2&pag=$PagAnt'>&#171; anterior</a>";
        if ($PagAct < $PagUlt)
          echo "<a href='/tags/buscar/&q=$pasda&orden=$order&categoria=$cat2&pag=$PagSig'>siguiente &#187;</a>";
        echo '<div class="clearBoth"></div></div>';
      }
    }
  }
}

function template_tyc666()
{
  global $tranfer1, $context, $settings, $db_prefix, $options, $sourcedir, $ID_MEMBER, $txt, $modSettings, $scripturl;

  require ($sourcedir . '/Hear-Buscador.php');
  hearBuscador('0', 't');
  $ddcc = seguridad($_GET['q']);
  if ($ddcc) {
    if (empty($_GET['categoria'])) {
      $cat = 'p.ID_BOARD';
    } else {
      $cat = (int) $_GET['categoria'];
    }
    if (empty($_GET['categoria'])) {
      $cat2 = '0';
    } else {
      $cat2 = $cat;
    }

    if ($_GET['orden'] == 'fecha') {
      $order2 = ' p.ID_TOPIC DESC';
    } elseif ($_GET['orden'] == 'puntos') {
      $order2 = ' p.puntos DESC';
    } else {
      $order2 = ' p.ID_TOPIC DESC';
    }

    $RegistrosAMostrar = $modSettings['search_results_per_page'];
    $_GET['pag'] = isset($_GET['pag']) ? $_GET['pag'] : '';
    if ($_GET['pag'] < 1) {
      $dud = 1;
    } else {
      $dud = $_GET['pag'];
    }
    if (isset($dud)) {
      $RegistrosAEmpezar = ($dud - 1) * $RegistrosAMostrar;
      $PagAct = $dud;
    } else {
      $RegistrosAEmpezar = 0;
      $PagAct = 1;
    }

    if (!$context['user']['is_admin']) {
      $shas = ' AND p.ID_BOARD<>142 ';
    } else {
      $shas = '';
    }
    $ddcc = $_GET['q'];
    $pssads = trim(seguridad($ddcc));

    $NroRegistros = mysqli_num_rows(db_query("SELECT p.ID_TOPIC
FROM ({$db_prefix}tags AS t, {$db_prefix}messages AS p,{$db_prefix}boards AS b)
WHERE t.palabra LIKE '%$pssads%' AND t.id_post=p.ID_TOPIC$shas AND p.ID_BOARD={$cat} AND p.ID_BOARD=b.ID_BOARD 
GROUP BY p.subject", __FILE__, __LINE__));

    $request = db_query("SELECT p.puntos,p.subject,p.ID_TOPIC,b.description,p.hiddenOption,p.posterTime
FROM ({$db_prefix}tags AS t, {$db_prefix}messages AS p,{$db_prefix}boards AS b) 
WHERE t.palabra LIKE '%$pssads%' AND t.id_post=p.ID_TOPIC$shas AND p.ID_BOARD={$cat} AND p.ID_BOARD=b.ID_BOARD
GROUP BY p.subject
ORDER BY {$order2}
LIMIT $RegistrosAEmpezar, $RegistrosAMostrar", __FILE__, __LINE__);
    $context['tags'] = array();
    while ($row = mysqli_fetch_assoc($request)) {
      $context['tags'][] = array(
        'subject' => $row['subject'],
        'id' => $row['ID_TOPIC'],
        'hiddenOption' => $row['hiddenOption'],
        'puntos' => $row['puntos'],
        'posterTime' => $row['posterTime'],
        'description' => $row['description']
      );
    }
    mysqli_free_result($request);

    // div grande

    if (!$NroRegistros) {
      echo '<div class="noesta-am" style="width:922px;">No se encontraron resultados.</div>';
    } else {
      echo '<table class="linksList" style="width:922px;">
<thead align="center"><tr><th style="text-align:left;">' . $NroRegistros . ' Posts con el tag: ' . $pssads . '</th><th>Fecha</th><th>Puntos</th></tr></thead><tbody>';

      foreach ($context['tags'] as $tag) {
        echo '<tr><td style="text-align: left;">';

        if ($tag['hiddenOption'] == '1' && $context['user']['is_guest'])
          echo '<div class="icon_img" style="float:left;margin-right:0px;"><img alt="" src="' . $tranfer1 . '/icons/cwbig-v1-iconos.gif?v3.2.3" style="margin-top:-578px;display:inline;" /></div>';

        echo '<a rel="dc:relation" target="_self" href="/post/' . $tag['id'] . '/' . $tag['description'] . '/' . urls(censorText($tag['subject'])) . '.html" title="' . censorText($tag['subject']) . '" class="categoriaPost ' . $tag['description'] . '">' . censorText($tag['subject']) . '</a></td><td title="' . timeformat($tag['posterTime']) . '">' . timeformat($tag['posterTime']) . '</td><td title="' . $tag['puntos'] . ' Puntos" style="color:green;">' . $tag['puntos'] . '</td>';
      }

      echo '</tbody></table>';
      $PagAnt = $PagAct - 1;
      $PagSig = $PagAct + 1;
      $PagUlt = $NroRegistros / $RegistrosAMostrar;
      $Res = $NroRegistros % $RegistrosAMostrar;
      if ($Res > 0)
        $PagUlt = floor($PagUlt) + 1;

      if ($PagAct > $PagUlt) {
        echo '';
      } elseif ($PagAct > 1 || $PagAct < $PagUlt) {
        echo '<div class="windowbgpag" style="width:910px;">';
        if ($PagAct > 1) {
          echo "<a href='/tags/buscar/&q=$pssads&orden=$order&categoria=$cat2&pag=$PagAnt'>&#171; anterior</a>";
        }

        if ($PagAct < $PagUlt) {
          echo "<a href='/tags/buscar/&q=$pssads&orden=$order&categoria=$cat2&pag=$PagSig'>siguiente &#187;</a>";
        }
        echo '<div class="clearBoth"></div></div>';
      }

      // fin div grande
    }
  }
}

function template_tyc12()
{
  global $tranfer1, $txt, $db_prefix, $context, $scripturl, $sourcedir;

  $id = (int) $_GET['id'];
  if (empty($id)) {
    fatal_error('Debes seleccionar la im&aacute;gen.-', false);
  }
  $request = db_query("SELECT i.title
FROM ({$db_prefix}gallery_pic as i)
WHERE i.ID_PICTURE='{$id}'
LIMIT 1", __FILE__, __LINE__);
  $siesta = mysqli_num_rows($request);
  if (empty($siesta)) {
    fatal_error('Esta imagen no existe.-', false);
  }
  $row = mysqli_fetch_assoc($request);
  mysqli_free_result($request);

  echo '<div><div class="box_buscador"><div class="box_title" style="width: 920px;"><div class="box_txt box_buscadort"><center>Recomendar a tus amigos</center></div><div class="box_rss"><img alt="" src="' . $tranfer1 . '/blank.gif" style="width:14px;height:12px;" border="0" /></div></div><div style="width:912px;padding:4px;" class="windowbg"><center>
<form action="/web/cw-EnviarImgMail.php" method="post" accept-charset="' . $context['character_set'] . '"><br /><font class="size11"><b>Recomendarle esta imagen hasta a seis amigos:</b></font><br /><b class="size11">1 - </b><input type="text" onfocus="foco(this);" onblur="no_foco(this);" name="r_email" size="28" maxlength="60" /> <b class="size11">2 - </b><input type="text" onfocus="foco(this);" onblur="no_foco(this);" name="r_email1" size="28" maxlength="60" /><br /><b class="size11">3 - </b><input type="text" onfocus="foco(this);" onblur="no_foco(this);" name="r_email2" size="28" maxlength="60" /> <b class="size11">4 - </b><input type="text" onfocus="foco(this);" onblur="no_foco(this);" name="r_email3" size="28" maxlength="60" /><br /><b class="size11">5 - </b><input type="text" onfocus="foco(this);" onblur="no_foco(this);" name="r_email4" size="28" maxlength="60" /> <b class="size11">6 - </b><input type="text" onfocus="foco(this);" onblur="no_foco(this);" name="r_email5" size="28" maxlength="60" /><br /><br />
          <font class="size11"><b>Asunto:</b></font><br /><input size="40" name="titulo" value="' . $row['title'] . '" type="text" onfocus="foco(this);" onblur="no_foco(this);"><br /><br />
          <font class="size11"><b>Mensaje:</b></font><br />
          <textarea onfocus="foco(this);" onblur="no_foco(this);" cols="70" rows="8" wrap="hard" tabindex="6" name="comment">Hola! Te recomiendo que veas esta imagen! 

Saludos!

' . $context['user']['name'] . '</textarea>

<br /><br />';
  echo '<b style="font-size:11px;">C&oacute;digo de la im&aacute;gen</b><br />';
  captcha(1);
  echo '<br /><input onclick="return showr_email(this.form.comment.value);" type="submit" class="login" name="send" value="Recomendar imagen" /><br /><a href="/imagenes/ver/' . (int) $id . '" title="&#171; Volver a la im&aacute;gen" target="_self">&#171; Volver a la im&aacute;gen</a>
<input type="hidden" name="id" value="' . (int) $id . '" /></form></center></div></div></div>';
}

function template_tyc6()
{
  global $tranfer1, $context;

  $ok = isset($_GET['ok']) ? $_GET['ok'] : '';
  if ($ok == 'ok') {
    fatal_error('Muchas gracias, el mensaje se ha enviado correctamente.-', false, '&#161;Mensaje enviado!');
  } else {
    echo "<script>function error(nombre,email,comentario,code){
  if(nombre == ''){document.getElementById('nombre').innerHTML='<br /><font class=\"size10\" style=\"color: red;\">Debes agregar tu nombre y apellido.</font>'; return false;}
  if(email == ''){document.getElementById('errorr').innerHTML='<br /><font class=\"size10\" style=\"color: red;\">Debes agregar tu e-mail.</font>'; return false;}
  if(comentario == ''){document.getElementById('comentario').innerHTML='<font class=\"size10\" style=\"color: red;\"><br />Debes agregar el comentario.</font>'; return false;}
  if(code == ''){document.getElementById('visual_verification_code').innerHTML='<font class=\"size10\" style=\"color: red;\"><br />Debes insertar el codigo.</font>'; return false;}
  
  }</script>";

    echo '<div><form action="/web/cw-Contactar.php" method="post" accept-charset="' . $context['character_set'] . '"><div class="box_buscador">
<div class="box_title" style="width: 920px;"><div class="box_txt box_buscadort"><center>Contacto</center></div>
<div class="box_rss"><img alt="" src="' . $tranfer1 . "/blank.gif\" style=\"width: 14px; height: 12px;\" border=\"0\" /></div></div><div class=\"windowbg\" style=\"width:912px;padding:4px;\"><center>
           <b class=\"size11\">* Su nombre y apellido:</b>
      <br />
      <input style=\"width:184px;\" name=\"nombre\" tabindex=\"1\" type=\"text\" onfocus=\"foco(this);\" onblur=\"no_foco(this);\" /><label id=\"nombre\"></label>
      <br />
      <b class=\"size11\">* E-mail:</b>
      <br />
        <input style=\"width:184px;\" name=\"email\" tabindex=\"2\" type=\"text\" onfocus=\"foco(this);\" onblur=\"no_foco(this);\" /><label id=\"errorr\"></label>
      <br />
      <b class=\"size11\">Empresa:</b>
      <br />
      <input style=\"width:184px;\" name=\"empresa\" tabindex=\"3\" type=\"text\" onfocus=\"foco(this);\" onblur=\"no_foco(this);\" />
      <br />\t\t\t
      <b class=\"size11\">Tel&eacute;fono:</b>
      <br />
      <input style=\"width:184px;\" name=\"tel\" value=\"\" tabindex=\"4\" type=\"text\" onfocus=\"foco(this);\" onblur=\"no_foco(this);\" />
      <br />
          <b class=\"size11\">Motivo:</b>
      <br />
      <select tabindex=\"5\" style=\"width:85px;\" class=\"select\" name=\"motivo\">
      <option value=\"Publicidad\">Publicidad</option>
      <option value=\"Sugerencias\">Sugerencias</option>
      <option value=\"Peticiones\">Peticiones</option>
        <option value=\"Errores\">Errores</option>
        <option value=\"Otros\">Otros</option>
      </select>
        <br />
      <b class=\"size11\">Horarios de contacto:</b>
      <br />
      <input tabindex=\"6\" style=\"width:134px;\" name=\"hc\" type=\"text\" onfocus=\"foco(this);\" onblur=\"no_foco(this);\" />
      <br />
      <b class=\"size11\">* Comentarios:</b>
      <br />
      <textarea onfocus=\"foco(this);\" onblur=\"no_foco(this);\" name=\"comentario\" style=\"width:249px;\" cols=\"40\" rows=\"5\" tabindex=\"7\"></textarea><label id=\"comentario\"></label><br />";
    echo '<b class="size11">* C&oacute;digo de la im&aacute;gen</b><br />';
    captcha(1);
    echo '<label id="visual_verification_code"></label><br />';
    echo '<font class="size11" style="color: red;">* Campos obligatorios</font><br />
          <input class="login" onclick="return error(this.form.nombre.value,this.form.email.value,this.form.comentario.value,this.form.code);" name="enviar" value="Enviar" type="submit" />
<br /><span class="size9">- Su IP (' . $_SERVER['REMOTE_ADDR'] . ') Ser&aacute; almacenada en nuestra base de datos por razones de seguridad.</span>
</center></div></div></form></div>';
  }
}

// Widget
function template_tyc3() {
  global $tranfer1, $context, $settings, $options, $txt, $scripturl, $db_prefix, $modSettings, $boardurl, $mbname;

  echo '
    <script type="text/javascript">
      var ancho = new Array();
      var alto = new Array();

      ancho[\'0\'] = 350;
      alto[\'0\'] = 100;
      ancho[\'1\'] = 200;
      alto[\'1\'] = 200;
      ancho[\'2\'] = 200;
      alto[\'2\'] = 250; 
      ancho[\'3\'] = 285;
      alto[\'3\'] = 134;
      ancho[\'4\'] = 200;
      alto[\'4\'] = 300;
      ancho[\'5\'] = 320;
      alto[\'5\'] = 100;
      ancho[\'6\'] = 320;
      alto[\'6\'] = 200;
      ancho[\'7\'] = 320;
      alto[\'7\'] = 300;

      function actualizar_preview(noselect) {
        document.getElementById(\'cantidad\').value = parseInt(document.getElementById(\'cantidad\').value);
        if (isNaN(document.getElementById(\'cantidad\').value)) {
          document.getElementById(\'cantidad\').value=\'\';
          alert(\'Debes ingresar un valor num&eacute;rico en el campo cantidad de posts listados.\');
          return;
        }

        if (!document.getElementById(\'cantidad\').value){
          alert(\'Debes ingresar un valor en el campo cantidad de posts listados.\');
          document.getElementById(\'cantidad\').focus();
          return;
        }

        if (document.getElementById(\'cantidad\').value > 50){
          alert(\'La cantidad m&aacute;xima de posts listados es 50\t\');
          document.getElementById(\'cantidad\').focus();
          return;
        }

        if (document.getElementById(\'cantidad\').value < 5) {
          alert(\'La cantidad m&iacute;nima de posts listados es 5.\');
          document.getElementById(\'cantidad\').focus();
          return;
        }

        code = \'<div style="border: 1px solid rgb(213, 213, 213); padding: 2px 5px 5px; background: #D7D7D7 url(\\\'' . $boardurl . '/images/fondo2-widget.jpg\\\') repeat-x scroll center top; width: \' + ancho[document.getElementById(\'tamano\').value] + \'px; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; text-align: left;"><a href="' . $boardurl . '/"><img src="' . $boardurl . '/images/widget-logo.gif" alt="' . $mbname . '" style="border: 0pt none; margin: 0px 0px 5px 5px;" /></a><iframe src="' . $boardurl . '/web/cw-WidGet.php?cat=\' + document.getElementById(\'cat\').value + \';can=\' + document.getElementById(\'cantidad\').value + \';an=\' + ancho[document.getElementById(\'tamano\').value] + \'" style="border: 1px solid rgb(213, 213, 213); margin: 0pt; padding: 0pt; width: \' + ancho[document.getElementById(\'tamano\').value] + \'px; height: \' + alto[document.getElementById(\'tamano\').value] + \'px;" frameborder="0"></iframe></div>\';

        document.getElementById(\'widget-preview\').innerHTML = code;
        document.getElementById(\'codigo\').value = code;
        focus_code(noselect);
        return;
      }

      function focus_code(noselect) {
        if(!noselect) {
          document.getElementById(\'codigo\').focus();
        }

        document.getElementById(\'codigo\').select();
        return;
      }
    </script>';

  $request = db_query("
    SELECT name, ID_BOARD
    FROM {$db_prefix}boards
    WHERE ID_BOARD <> 142", __FILE__, __LINE__);

  echo '
    <div>
      <div class="box_buscador">
        <div class="box_title" style="width: 919px;">
          <div class="box_txt box_buscadort">
            <center>Widget</center>
          </div>
          <div class="box_rss">
            <img src="' . $tranfer1 . '/blank.gif" style="width: 14px; height: 12px;" alt="" border="0" /></div>
          </div>
          <div style="width: 911px; padding: 4px; margin-bottom: 8px;" class="windowbg">
            Integra los &uacute;ltimos posts de ' . $mbname . ' en tu web y mantente siempre actualizado.
            <br />
            En segundos podr&aacute;s tener un listado que estar&aacute; siempre 
            actualizado con los &uacute;ltimos posts publicados en ' . $mbname . '.
            <br />
            Puedes personalizar el listado para que se adapte al estilo de tu sitio. Puedes cambiar su tama&ntilde;o, color, cantidad de posts a listar y hasta puedes filtrar por categor&iacute;as.
            <br /><br />
            <b>¿C&oacute;mo implementarlo?</b>
            <br />
            <b>1.</b> Personal&iacute;zalo a tu gusto. C&aacute;mbiale color, y elige el tama&ntilde;o.
            <br />
            <b>2.</b> Copia el c&oacute;digo generado y p&eacute;galo en tu p&aacute;gina.
            <br />
            <b>3.</b> &iexcl;Listo!. Ya puedes disfrutar de ' . $mbname . ' widget.
            <br />
          </div>
        </div>
        <table style="width: 921px; margin: 0px;">
          <tr style="padding: 0px; margin: 0px;">
            <td style="20%; padding: 0px; margin: 0px;" height="25px" class="titlebg" align="center">
              <b>
                <font color="#EEE">Personalizaci&oacute;n</font>
              </b>
            </td>
            <td style="30%; padding: 0px; margin: 0px;" height="25px" class="titlebg" align="center">
              <b>
                <font color="#EEE">C&oacute;digo</font>
              </b>
            </td>
            <td style="50%; padding: 0px; margin: 0px;" height="25px" class="titlebg" align="center">
              <b>
                <font color="#EEE">Ejemplo</font>
              </b>
            </td>
          </tr>  
          <tr style="padding: 0px; margin: 0px;">
            <td align="center" style="padding: 0px; margin: 0px;" class="windowbg">
              <b>Categor&iacute;a:</b>
              <br />
              <select id="cat" onchange="actualizar_preview();">
                <option value="" selected="selected">Todas</option>';

  while ($row = mysqli_fetch_assoc($request)) {
    echo '<option value="' . $row['ID_BOARD'] . '">' . $row['name'] . '</option>';
  }

  mysqli_free_result($request);

  echo '
      </select>
      <br />
      <b>Cantidad:</b>
      <br />
      <input size="4" maxlength="2" id="cantidad" value="20" onchange="actualizar_preview();" type="text" onfocus="foco(this);" onblur="no_foco(this);" />
      <span class="smalltext">(m&aacute;x 50 - min 5)</span>
      <br />
      <b>Tama&ntilde;o:</b>
      <br />
      <select id="tamano" onchange="actualizar_preview();">
        <option value="0">350 x 100</option>
        <option value="2">200 x 250</option>
        <option value="1">200 x 200</option>
        <option value="3">285 x 134</option>
        <option value="4">200 x 300</option>
        <option value="5">320 x 100</option>
        <option value="6">320 x 200</option>
        <option value="7">320 x 300</option>
      </select>
    </td>
    <td align="center" style="padding: 0px; margin: 0px;" class="windowbg">
      <textarea onfocus="foco(this);" onblur="no_foco(this);" id="codigo" cols="47" rows="6" onClick="focus_code();"></textarea>
    </td>
    <td align="center" style="padding: 0px; margin: 0px;" class="windowbg">
      <input type="hidden" size="4" maxlength="2" id="cantidad" value="20" onchange="actualizar_preview();" />
      <div id="widget-preview"></div>
      <script type="text/javascript">actualizar_preview(1);</script>
    </td>
  </table>
  </div>';
}

function template_vr2965() {
  global $tranfer1, $context, $settings, $options, $txt, $scripturl, $modSettings, $db_prefix, $user_info, $board, $boardurl;

  $getid = isset($_GET['id']) ? (int) $_GET['id'] : 0;
  $_GET['inicio'] = isset($_GET['inicio']) ? $_GET['inicio'] : '';
  $_GET['post-agregar'] = isset($_GET['post-agregar']) ? $_GET['post-agregar'] : '';
  $page = isset($_GET['pag']) ? (int) $_GET['pag'] : 1;
  $width_column_1 = '461px';

  if ($context['allow_admin']) {
    $request = db_query("
      SELECT id_post
      FROM {$db_prefix}comentarios_mod
      WHERE id_post = $getid", __FILE__, __LINE__);

    $context['comentarios_mod'] = mysqli_num_rows($request);

    mysqli_free_result($request);

    $request = db_query("
      SELECT id_user, cerrado
      FROM {$db_prefix}comunicacion
      WHERE id_contenido = $getid
      LIMIT 1", __FILE__, __LINE__);

    while ($row = mysqli_fetch_array($request)) {
      $cerrar = $row['cerrado'];
      $id_user = $row['id_user'];
    }

    if ($_GET['inicio'] !== 1234) {
      echo '<div style="width: 912px; padding: 4px;" class="windowbg">';
    }

    if ($_GET['post-agregar'] == 1447) {
      echo '<form action="' . $boardurl . '/web/cw-ComunicacionAdm-AGR.php" method="post" accept-charset="' . $context['character_set'] . '" name="agregarp">';
      theme_quickreply_box();
      echo '</form>';
    }

    if ($getid) {
      $request = db_query("
        SELECT c.id_contenido, c.id_user, u.ID_MEMBER, c.id_contenido, u.realName, c.titulo, c.texto
        FROM {$db_prefix}comunicacion AS c, {$db_prefix}members AS u
        WHERE c.id_contenido = $getid
        AND c.id_user = u.ID_MEMBER
        LIMIT 1", __FILE__, __LINE__);

      while ($row = mysqli_fetch_array($request)) {
        echo '
          <b class="size11">Titulo:</b>
          <span title="' . censorText($row['titulo']) . '">' . censorText($row['titulo']) . '</span>
          -
          <span class="size11">
            ID:
            <a href="' . $boardurl . '/moderacion/comunicacion-mod/post/' . $row['id_contenido'] . '">' . $row['id_contenido'] . '</a>
          </span>
          -
          <span class="size11">
            COM:
            <a href="' . $boardurl . '/moderacion/comunicacion-mod/post/' . $row['id_contenido'] . '#comentarios">' . $context['comentarios_mod'] . '</a>
          </span>';

        if ($context['user']['is_admin']) {
          echo '
            -
            <span class="size11">
              [
                <b>
                  <a href="' . $boardurl . '/web/cw-ComunicacionAdm-EliPost.php?post=' . $row['id_contenido'] . '" onclick="if (!confirm(\'\xbfEstas seguro que desea eliminar este post?\')) return false;">X</a></b>]</span>';
        } elseif ($context['user']['id'] == $id_user) {
          echo ' - <span class="size11">[<b><a href="' . $boardurl . '/moderacion/comunicacion-mod/post/eliminar/' . $row['id_contenido'] . '" onclick="if (!confirm(\'\xbfEst&aacute;s seguro que deseas eliminar este post?\')) return false;">X</a>
                </b>
              ]
            </span>';
        }

        echo '
          <br />
          <b class="size11">
            Por:
          </b>
          <a href="' . $boardurl . '/perfil/' . $row['realName'] . '" title="' . $row['realName'] . '">' . $row['realName'] . '</a>
          <br />
          <div class="hrs"></div>
          <div style="width: 910px;">
            <div class="post-contenido">
              ' . str_replace('http://linkoculto.net/index.php?l=', '', parse_bbc($row['texto'])) . '
            </div>
          </div>
        </div>';
      }
    }

    if ($_GET['inicio'] == 1234) {
      $RegistrosAMostrar = 15;
      $dud = $page < 1 ? 1 : $page;

      if (isset($dud)) {
        $RegistrosAEmpezar = ($dud - 1) * $RegistrosAMostrar;
        $PagAct = $dud;
      } else {
        $RegistrosAEmpezar = 0;
        $PagAct = 1;
      }

      $request = db_query("
        SELECT c.titulo, c.id_contenido, u.realName
        FROM {$db_prefix}comunicacion AS c, {$db_prefix}members AS u
        WHERE c.id_user = u.ID_MEMBER
        ORDER BY c.id_contenido DESC
        LIMIT $RegistrosAEmpezar, $RegistrosAMostrar", __FILE__, __LINE__);

      echo '
        <div style="float: left; margin-right: 10px;">
          <table class="linksList" style="width: ' . $width_column_1 . ';">
            <thead align="center">
              <tr>
                <th>&nbsp</th>
                <th>Post</th>
                <th>Por</th>
              </tr>
            </thead>
            <tbody>';

      while ($row = mysqli_fetch_array($request)) {
        echo '
          <tr>
            <td>
              <div class="icon_img" style="float: left; margin-right: 2px;">
                <img alt="" src="' . $tranfer1 . '/icons/cwbig-v1-iconos.gif?v3.2.3" style="margin-top: -559px; display: inline;" />
              </div>
            </td>
            <td>
              <a href="' . $boardurl . '/moderacion/comunicacion-mod/post/' . $row['id_contenido'] . '">' . $row['titulo'] . '</a>
            </td>
            <td>
              <a href="' . $boardurl . '/perfil/' . $row['realName'] . '">' . $row['realName'] . '</a>
            </td>
          </tr>';
      }

      echo '
          </tbody>
        </table>';

      $request = db_query("
        SELECT id_contenido
        FROM {$db_prefix}comunicacion", __FILE__, __LINE__);

      $NroRegistros = mysqli_num_rows($request);

      $PagAnt = $PagAct - 1;
      $PagSig = $PagAct + 1;
      $PagUlt = $NroRegistros / $RegistrosAMostrar;
      $Res = $NroRegistros % $RegistrosAMostrar;

      if ($PagAct > 1 || $PagAct < $PagUlt) {
        echo '<div class="windowbgpag" style="width: ' . $width_column_1 . '; float: left;">';

        if ($Res > 0) {
          $PagUlt = floor($PagUlt) + 1;
        }

        if ($PagAct > 1) {
          echo '<a href="' . $boardurl . '/moderacion/comunicacion-mod/pag-' . $PagAnt . '" style="float: left;">&#171; anterior</a>';
        }

        if ($PagAct < $PagUlt) {
          echo '<a href="' . $boardurl . '/moderacion/comunicacion-mod/pag-' . $PagSig . '" style="float: left;">siguiente &#187;</a>';
        }

        echo '
          </div>
          <div class="clearBoth"></div>';
      }

      echo '
        </div>
        <div style="width: 200px; float: left;">
          <table class="linksList" style="width: 448px;">
            <thead align="center">
              <tr>
                <th>&nbsp</th>
                <th>Post Comentado</th>
                <th>Por</th>
              </tr>
            </thead>
            <tbody>';

      $request = db_query("
      SELECT u.realName, cm.id_post, c.titulo
      FROM {$db_prefix}comunicacion AS c, {$db_prefix}comentarios_mod AS cm, {$db_prefix}members AS u
      WHERE c.id_contenido = cm.id_post
      AND cm.id_user = u.ID_MEMBER
      ORDER BY cm.id DESC
      LIMIT 15", __FILE__, __LINE__);

      while ($row = mysqli_fetch_array($request)) {
        echo '
          <tr>
            <td><img src="' . $tranfer1 . '/comunidades/respuesta.png" alt="" /></td>
            <td><a href="' . $boardurl . '/moderacion/comunicacion-mod/post/' . $row['id_post'] . '">' . $row['titulo'] . '</a></td>
            <td><a href="' . $boardurl . '/perfil/' . $row['realName'] . '" title="' . $row['realName'] . '">' . $row['realName'] . '</a></td>
          </tr>';
      }

      echo '
            </tbody>
          </table>
        </div>';
    }

    if ($getid) {
      echo '
        <div style="margin-bottom: 8px; margin-top: 8px;">
          <b style="font-size: 14px;">Comentarios (<span id="nrocoment">' . $context['comentarios_mod'] . '</span>)</b>';

      $request = db_query("
        SELECT c.id, c.comentario, m.realName
        FROM {$db_prefix}comentarios_mod AS c, {$db_prefix}members AS m
        WHERE c.id_post = $getid
        AND c.id_user = m.ID_MEMBER
        ORDER BY c.id ASC", __FILE__, __LINE__);

      while ($row = mysqli_fetch_array($request)) {
        $row['comentario'] = censorText($row['comentario']);

        echo '
          <div id="cmt_' . $row['id'] . '" class="Coment" style="width: 922px;">
            <span class="size12">
              <div class="User-Coment">
                <div style="float: left;">
                  <b id="autor_cmnt_' . $row['id'] . '" user_comment="' . $row['realName'] . '" text_comment=\'' . $row['comentario'] . '\'>
                    <a href="' . $boardurl . '/perfil/' . $row['realName'] . '" style="color: #956100;">' . $row['realName'] . '</a>
                  </b>
                  dijo:
                </div>
                <div style="float: right;">
                  <a href="' . $boardurl . '/mensajes/a/' . $row['realName'] . '" title="Enviar MP a: ' . $row['realName'] . '">
                    <img alt="" src="' . $tranfer1 . '/icons/mensaje_para.gif" border="0" />
                  </a>';

        if (!$cerrar) {
          echo '<a onclick="citar_comment(' . $row['id'] . ')" href="javascript:void(0)" title="Citar Comentario"><img alt="" src="' . $tranfer1 . '/comunidades/respuesta.png" class="png" border="0" /></a>';
        }

        if ($id_user == $context['user']['id'] || $context['user']['is_admin']) {
          echo '<a href="' . $boardurl . '/web/cw-ComunicacionAdm-EliCom.php?id=' . $row['id'] . '&post=' . $getid . '" title="Eliminar Comentario"><img alt="" src="' . $tranfer1 . '/comunidades/eliminar.png" class="png" border="0" /></a>';
        }

        echo '
                </div>
              </div>
              <div class="cuerpo-Coment">
                <div style="white-space: pre-wrap; overflow: hidden; display: block;">
                  ' . str_replace('http://linkoculto.net/index.php?l=', '', parse_bbc($row['comentario'])) . '
                </div>
              </div> 
            </span>
          </div>';
      }

      if (!$context['comentarios_mod']) {
        echo '
          <div class="noesta" style="width: 922px;">Este post no tiene comentarios.</div>
          <div class="clearBoth"></div>';
      }

      if ($cerrar) {
        echo '
          <div class="noesta" style="width: 922px;">Este post est&aacute; cerrado, por lo tanto no se permiten nuevos comentarios.</div>
          <div class="clearBoth"></div>';
      }

      echo '</div>';
    }

    if ($getid && !$cerrar) {
      echo '
        <script type="text/javascript">
          function errorrojo(cuerpo_comment) {
            if (cuerpo_comment == \'\') {
              document.getElementById(\'error\').innerHTML = \'\';
              return false;
            }
          }
        </script>
        <div style="clear: left;"></div>
        <div style="margin-bottom: 3px;" id="comentar" name="comentar">
          <b style="font-size: 14px;">Agregar un nuevo comentario</b>
        </div>
        <div class="post-com coment-user" style="width: 922px;">
          <div style="clear: left; margin-bottom: 2px"></div>
          <form action="' . $boardurl . '/web/cw-ComunicacionAdm-Coment.php" method="post" accept-charset="' . $context['character_set'] . '" name="enviar">
            <textarea style="resize: none; height: 70px; width: 916px;" id="editorCW" name="cuerpo_comment" tabindex="1"></textarea>
            <p align="right" style="margin: 0px; padding: 0px;">';

      $request = db_query("
        SELECT description, filename, code
        FROM {$db_prefix}smileys
        WHERE hidden = 0
        ORDER BY ID_SMILEY ASC", __FILE__, __LINE__);

      while ($row = mysqli_fetch_assoc($request)) {
        echo '
          <span class="pointer" onclick="replaceText(\' ' . $row['code'] . '\', document.forms.enviar.cuerpo_comment); return false;">
            <img class="png" src="' . $tranfer1 . '/emoticones/' . $row['filename'] . '" align="bottom" alt="" title="' . $row['description'] . '" />
          </span> ';
      }

      mysqli_free_result($request);

      echo '<a href="javascript:moticonup()">[m&aacute;s]</a>
              <br />
              <input class="login" name="post" id="post" value="Enviar comentario" onclick="return errorrojo(this.form.cuerpo_comment.value);" tabindex="2" type="submit" />
              <input name="id_post" value="' . $getid . '" type="hidden" />
            </p>
            <div style="clear: left;"></div>
          </form>
        </div>
        <div style="clear: left; margin-bottom: 5px;"></div>';
    }
  } else {
    die('');
  }
}

function template_denuncias()
{
  global $tranfer1, $context, $db_prefix;

  if ($context['allow_admin']) {
    $NroRegistros = mysqli_num_rows(db_query("SELECT id_denuncia FROM {$db_prefix}denuncias", __FILE__, __LINE__));
?>
<script type="text/javascript">
function actuarDenuncia(a,id,den,ident){ $('#cargando_'+id).css('display','block');
$.ajax({
    type: 'GET',
    url: '/web/cw-denunciaAdm'+ a +'.php',
    cache: false,
    data: 'id=' + id + '&den=' + den + '&ident=' +  ident,
    success: function(h){
      $('#cargando_'+id).css('display','none');
          $('#contentv_'+id).remove();
          $('#resultado_'+id).css('display','block');
        if(h.charAt(0)==0){ //Datos incorrectos
          $('#resultado_'+id).addClass('noesta');
          $('#resultado_'+id).html(h.substring(3)).show();
        } else
        if(h.charAt(0)==1){ //OK				
          $('#resultado_'+id).removeClass('noesta');
          $('#resultado_'+id).addClass('noesta-ve');
          $('#resultado_'+id).html(h.substring(3)).show();}			
    },
    error: function(){
      Boxy.alert("Error, volver a intentar...", null, {title: 'Alerta'});
    }
  });
}
</script>
<?php echo '<div style="width: 922px;"><div class="box_title"><div class="box_txt box_buscadort"><center>Denuncias de posts, im&aacute;genes, usuarios y Comunidades</center></div></div></div>
<div style="width:912px;padding:4px;" class="windowbg">';

    if ($NroRegistros) {
      $RegistrosAMostrar = 25;
      $_GET['pag'] = isset($_GET['pag']) ? $_GET['pag'] : '';
      if ($_GET['pag'] < 1) {
        $dda = 1;
      } else {
        $dda = $_GET['pag'];
      }
      if (isset($dda)) {
        $RegistrosAEmpezar = ($dda - 1) * $RegistrosAMostrar;
        $PagAct = $dda;
      } else {
        $RegistrosAEmpezar = 0;
        $PagAct = 1;
      }

      $request = db_query("
SELECT den.borrado,den.id_post,den.name_post, c.description, den.id_user, den.tipo, den.atendido, den.id_denuncia, COUNT(den.id_post) as cont
FROM ({$db_prefix}denuncias AS den, {$db_prefix}boards c)
WHERE den.cat=c.ID_BOARD
GROUP BY den.id_post
ORDER BY den.borrado ASC
LIMIT $RegistrosAEmpezar, $RegistrosAMostrar", __FILE__, __LINE__);
      $context['denunciasss'] = array();
      while ($row = mysqli_fetch_assoc($request)) {
        $context['denunciasss'][] = array(
          'borrado' => $row['borrado'],
          'id_post' => $row['id_post'],
          'name_post' => $row['name_post'],
          'cont' => $row['cont'],
          'descripcion' => $row['description'],
          'id_user' => $row['id_user'],
          'tipo' => $row['tipo'],
          'mod' => $row['atendido'],
          'id_denuncia' => $row['id_denuncia']
        );
      }
      mysqli_free_result($request);

      foreach ($context['denunciasss'] as $den1) {
        if ($den1['borrado'] == '1') {
          $estilo = 'style="background-color:#FED2D2;border: 1px solid #FE2E2E; padding:4px;margin:8px;"';
        } elseif ($den1['borrado'] == '0') {
          $estilo = 'style="background-color:#F5F5F5;border: 1px solid #D5D5D5; padding:4px;margin:8px;"';
        } elseif ($den1['borrado'] == '2') {
          $estilo = 'style="background-color:#D7EFD2;border: 1px solid #47973A; padding:4px;margin:8px;"';
        }

        if ($den1['tipo'] == '1') {
          $link = '/imagenes/ver/' . $den1['id_post'];
          $imagen = 'class="categoriaPost imagenesNOCAT"';
        } elseif (empty($den1['tipo'])) {
          $link = '/post/' . $den1['id_post'] . '/' . $den1['descripcion'] . '/' . urls($den1['name_post']) . '.html';
          $imagen = 'class="categoriaPost ' . $den1['descripcion'] . '"';
        } elseif ($den1['tipo'] == '3') {
          $imagen = 'class="categoriaPost UsersNOCAT"';
          $link = '/perfil/' . nohtml(nohtml2($den1['name_post']));
        } elseif ($den1['tipo'] == '5') {
          $link = '/comunidades/' . nohtml(nohtml2($den1['name_post']));
          $imagen = 'class="categoriaPost comunidadesNOCAT"';
        }

        echo '<div style="display:none;margin:8px;width: 896px;" id="resultado_' . $den1['id_post'] . '"></div>
<img style="display:none;margin-left:8px;margin-top:8px;" id="cargando_' . $den1['id_post'] . '" src="' . $tranfer1 . '/icons/cargando.gif" width="16px" height="16px" alt="" /><div ' . $estilo . ' id="contentv_' . $den1['id_post'] . '">';

        echo '<div style="margin-right:4px;"><div style="float:left;">

<div style="float:left;"><a href="' . $link . '" ' . $imagen . ' title="' . nohtml(nohtml2($den1['name_post'])) . '">' . nohtml(nohtml2($den1['name_post'])) . '</a></div>
<div style="float:left;"> - (' . $den1['cont'] . ' denunucias)</div>

</div>';

        if (!$den1['borrado']) {
          echo '<div><div style="float:left;margin-right:5px;margin-left:5px;"><a onclick="actuarDenuncia(\'Acep\',\'' . $den1['id_post'] . "','" . $den1['id_user'] . "','" . $den1['id_denuncia'] . '\');" class="botN1" style="cursor:pointer;color:#fff;text-shadow: #005400 0px 1px 0px;">Aceptar</a></div><div><a onclick="actuarDenuncia(\'Rech\',\'' . $den1['id_post'] . "','" . $den1['id_user'] . "','" . $den1['id_denuncia'] . '\');" class="botN2" style="cursor:pointer;text-shadow: #CC0000 0px 1px 0px;color:#fff;">Rechazar</a></div></div>';
        } else {
          echo '<div> - Atendido por <a title="' . $den1['mod'] . '" href="/perfil/' . $den1['mod'] . '">' . $den1['mod'] . '</a></div>';
        }

        echo '</div><div class="clearfix"></div>';

        $reques2 = db_query("
SELECT den.comentario,m.realName,m.memberIP,den.razon
FROM ({$db_prefix}denuncias AS den, {$db_prefix}members AS m)
WHERE den.id_user=m.ID_MEMBER AND den.id_post='{$den1['id_post']}'
ORDER BY den.id_denuncia DESC", __FILE__, __LINE__);
        while ($den2 = mysqli_fetch_assoc($reques2)) {
          $comentario = nohtml(nohtml2($den2['comentario']));
          $den2['razon'] = str_replace('razon', 'raz&oacute;n', str_replace('contrasena', 'contrase&ntilde;a', str_replace('Esta', 'Esta&aacute;', str_replace('mayuscula', 'Esta&may&uacute;scula', str_replace('informacion', 'informaci&oacute;n', $den2['razon'])))));

          echo '<span class="size11"><b class="size11">Denunciante:</b> <a href="/perfil/' . $den2['realName'] . '" title="' . $den2['realName'] . '">' . $den2['realName'] . '</a> | <b class="size11">IP:</b> <a href="http://lacnic.net/cgi-bin/lacnic/whois?query=' . $den2['memberIP'] . '" title="' . $den2['memberIP'] . '">' . $den2['memberIP'] . '</a> | <b class="size11">Raz&oacute;n:</b> ' . $den2['razon'] . ' | <b class="size11">Comentario:</b> ' . str_replace("\n", '<br />', $comentario) . '</span><br />';
        }
        mysqli_free_result($reques2);
        echo '</div>';
      }

      $PagAnt = $PagAct - 1;
      $PagSig = $PagAct + 1;
      $PagUlt = $NroRegistros / $RegistrosAMostrar;
      $Res = $NroRegistros % $RegistrosAMostrar;
    } else {
      echo '<div class="noesta">No hay denuncias.</div>';
    }

    echo '<div class="hrs"></div><b style="color:#D5D5D5;" class="size13">Esperando moderaci&oacute;n</b> | <b style="color:#FE2E2E;" class="size13">Denuncia rechazada</b> | <b style="color:#47973A;" class="size13">Denuncia aceptada</b><br /><span class="size11"><b style="color:#008000;">Aceptar:</b> El denunciante denunci&oacute; <span style="color:#008000;">bien</span>, se le incrementar&aacute; 1 punto al denunciante - <b>ACTUAR SOBRE LA DENUNCIA</b><br /><b style="color:#FE0000;">Rechazar:</b> El denunciante denunci&oacute; <span style="color:#FE0000;">mal</span>.</span></span></div>';

    if ($PagAct > 1 || $PagAct < $PagUlt) {
      echo '<div class="windowbgpag" style="width:378px;">';
      if ($PagAct > 1)
        echo "<a href='/moderacion/denuncias/pag-$PagAnt'>&#171; anterior</a>";
      if ($PagAct < $PagUlt)
        echo "<a href='/moderacion/denuncias/pag-$PagSig'>siguiente &#187;</a>";
      echo '</div>';
    }
  } else {
    die('');
  }
}

function template_quickreply_box()
{
  global $tranfer1, $db_prefix, $context, $txt;

  echo '<b class="size11">Titulo:</b><br /><input name="titulo" tabindex="1" size="60" maxlength="60" type="text" onfocus="foco(this);" onblur="no_foco(this);" /><br /><b class="size11">Mensaje del post:</b><br />';
  echo '<textarea style="height:300px;width:615px;" id="editorCW" name="texto" cols="125" rows="25" tabindex="2"></textarea>';
  $existe = db_query("
SELECT hidden,ID_SMILEY,description,filename,code
FROM ({$db_prefix}smileys)
WHERE hidden=0
ORDER BY ID_SMILEY ASC", __FILE__, __LINE__);
  while ($row = mysqli_fetch_assoc($existe)) {
    echo '<span style="cursor:pointer;" onclick="replaceText(\' ', $row['code'], '\', document.forms.agregarp.texto); return false;"><img class="png" src="' . $tranfer1 . '/emoticones/' . $row['filename'] . '" align="bottom" alt="" title="', $row['description'], '" /></span> ';
  }
  mysqli_free_result($existe);
  if (!empty($context['smileys']['popup']))
    echo '<a href="javascript:moticonup()">[', $txt['more_smileys'], ']</a>';
  echo '<br /><label id="cerrado"><input class="check" tabindex="3" name="cerrado" value="1" type="checkbox"> No permitir comentarios</label><br /><center><input class="login" type="submit" value="Postear"></center>';
} ?>