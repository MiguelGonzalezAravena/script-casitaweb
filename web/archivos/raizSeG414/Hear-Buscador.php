<?php
function hearBuscador($mm, $dd) {
  global $txt, $tranfer1, $scripturl, $db_prefix, $modSettings, $user_info, $context, $mbname, $boardurl;

  $_GET['q'] = isset($_GET['q']) ? $_GET['q'] : '';
  $_GET['autor'] = isset($_GET['autor']) ? $_GET['autor'] : '';
  $_GET['palabra'] = isset($_GET['palabra']) ? $_GET['palabra'] : '';
  $_GET['categoria'] = isset($_GET['categoria']) ? $_GET['categoria'] : '';
  $_GET['orden'] = isset($_GET['orden']) ? $_GET['orden'] : '';
  $stylen = '';

  if (!$mm) {
    $activo = ' class="here"';
    $activo2 = ' href=""';
    $activo3 = '';
    $activo4 = ' href="' . $boardurl . '/buscar-com.php?q=' . trim(decodeurl($_GET['q'])) . '&autor=' . trim(decodeurl($_GET['autor'])) . '&buscador_tipo=c&tipoc=1"';

    if ($dd == 'g') {
      $accion = $boardurl . '/buscargoogle.php';
      $quee = 'google';
      $hereg = 'class="here"';
      $herecw = '';
      $heret = '';
      $autor = '0';
      $cat = '0';
      $order = '0';
    } else if ($dd == 't') {
      $accion = $boardurl . '/buscartags.php';
      $quee = 'tags';
      $herecw = '';
      $hereg = '';
      $heret = 'class="here"';
      $autor = '0';
      $cat = '1';
      $order = '1';
    } else {
      $accion = $boardurl . '/buscar.php';
      $quee = 'cw';
      $herecw = 'class="here"';
      $hereg = '';
      $heret = '';
      $autor = '1';
      $cat = '1';
      $order = '1';
    }

    $links1 = '
      <a ' . $hereg . ' id="selen_google" href="javascript:buscador.select(\'google\')">Google</a>
      <span class="sep">|</span>
      <a ' . $herecw . ' id="selen_cw" href="javascript:buscador.select(\'cw\')">' . $mbname . '</a>
      <span class="sep">|</span>
      <a ' . $heret . ' id="selen_tags" href="javascript:buscador.select(\'tags\')">Tags</a>';

    echo '
      <script type="text/javascript">
        function check() {
          if ($(\'#sdq\').val() == \'\') {
            $(\'#sdq\').focus();
            return false;
          }
        }

        var buscador = {
          tipo: \'' . $quee . '\',
          buscadorLite: true,
          select: function(tipo) {
            if (this.tipo == tipo) {
              return;
            }

            // Cambio de action form
            if (tipo == \'cw\') {
              var enlace = \'\';
            } else {
              var enlace = tipo;
            } 
                    
            $(\'form[name="buscador"]\').attr(\'action\', \'' . $boardurl . '/buscar\' + enlace + \'.php\');

            // Sólo hago los cambios visuales si no envia consulta
            // Cambio here en <a />
            $(\'#selen_\' + this.tipo).removeClass(\'here\');
            $(\'#selen_\' + tipo).addClass(\'here\');

            // Mostrar / ocultar el input autor
            if (tipo == \'cw\') {
              $(\'span#filtro_autor\').show();
              $(\'#relevancia\').show();
            } else {
              $(\'span#filtro_autor\').hide();
              $(\'#relevancia\').hide();
            }

            // Mostrar / ocultar los input google
            if (tipo == \'google\') {
              $(\'#filtercat\').hide();
              $(\'#filterorder\').hide();
              $(\'input[name="buscador_tipo"]\').val(\'g\');
              $(\'#agregarG\').append(\'<input name="cof" value="FORID:10" type="hidden" /><input name="cx" value="015978274333592990658:r0qy7erzrbw" type="hidden" /><input name="ie" value="UTF-8" type="hidden" /><input name="sa" value="Buscar" type="hidden" />\');
            } else if (this.tipo == \'google\') {
              // El anterior fue Google
              $(\'input[name="cx"]\').remove();
              $(\'input[name="cof"]\').remove();
              $(\'input[name="sa"]\').remove();
              $(\'input[name="nn"]\').remove();
              $(\'input[name="buscador_tipo"]\').val(\'cw\');
              $(\'#filtercat\').show();
              $(\'#filterorder\').show();
              $(\'input[name="ie"]\').remove();
            }

            this.tipo = tipo;

            // En buscador lite, enviar consulta
            if ($(\'input[name="q"]\').val() != \'\') {
              $(\'form[name="buscador"]\').submit();
            } else {
              $(\'input[name="q"]\').focus();
            }
          }
        }
      </script>';
  } else if ($mm) {
    if ($dd == 't') {
      $accion = $boardurl . '/buscar-com.php';
      $quee = 'temas';
      $hereg = 'class="here"';
      $autor = '1';
      $cat = '1';
      $order = '1';
    } else if ($dd == 'c') {
      $hereg = '';
      $accion = $boardurl . '/buscar-com.php';
      $quee = 'comunidades';
      $heret = 'class="here"';
      $autor = '1';
      $cat = '1';
      $order = '1';
    }

    $links1 = '
      <a ' . $hereg . ' id="selen_temas" href="javascript:buscador.select(\'temas\')">Temas</a>
      <span class="sep">|</span>
      <a ' . $heret . ' id="selen_comunidades" href="javascript:buscador.select(\'comunidades\')">Comunidades</a>';

    $activo = '';
    $activo2 = ' href="' . $boardurl . '/buscar.php?q=' . trim(decodeurl($_GET['q'])) . '&buscador_tipo=c"';
    $activo3 = ' class="here"';
    $activo4 = ' href=""';

?>
<script type="text/javascript">
var buscador = {
  tipo: '<?php echo $quee; ?>',
  buscadorLite: true,
  select: function(tipo) {
    if (this.tipo == tipo) {
      return;
    }

    // Cambio de action form
    $('form[name="buscador"]').attr('action', '<?php echo $boardurl; ?>/buscar-com.php');
 
    if (tipo == 'temas') {
      $('#buscador_tipo').val('t');
      $('option[@id=puntos]').html('Calificaci&oacute;n');
      $('#relevancia').show();
    } else {
      $('#buscador_tipo').val('c');
      $('option[@id=puntos]').html('Temas');
      $('#relevancia').hide();
    }

    // Sólo hago los cambios visuales si no envia consulta
    // Cambio here en <a />
    $('#selen_' + this.tipo).removeClass('here');
    $('#selen_' + tipo).addClass('here');
    this.tipo = tipo;
      
    // En buscador lite envio consulta
    if ($('input[name="q"]').val() != '') {
      $('form[name="buscador"]').submit();
    } else {
      $('input[name="q"]').focus();
    }
  }
}
</script>

<?php
  }

  echo '
    <div id="buscadorLite" style="width: 922px;">
      <div class="clearBoth"></div>
      <ul class="searchTabs">
        <li' . $activo . '><a' . $activo2 . '>Posts</a></li>
        <li' . $activo3 . '><a' . $activo4 . '>Comunidades</a></li>
        <div class="clearBoth"></div>
      </ul>
      <div class="clearBoth"></div>
      <div class="searchCont">
      <form name="buscador" method="GET" action="' . $accion . '" onsubmit="window.buscador.onsubmit();">
          <div class="searchFil">
            <div style="margin-bottom: 5px;">
              <label class="searchWith" style="float: right;">
                ' . $links1 . '
              </label>
            <div class="clearfix"></div>
          </div>
          <div class="clearBoth"></div>
          <div class="boxBox">
            <div class="searchEngine">
              <span id="agregarG" style="display: none;"></span>
              <input name="buscador_tipo" id="buscador_tipo" value="' . $dd . '" type="hidden" />';

  if ($dd == 'g') {
    echo '<input name="cof" value="FORID:10" type="hidden" /><input name="cx" value="015978274333592990658:r0qy7erzrbw" type="hidden" /><input name="ie" value="UTF-8" type="hidden" /><input name="nn" value="g" type="hidden" /><input name="sa" value="Buscar" onclick="return check();" type="hidden" />';
  }

  $pal = $_GET['palabra'] ? $_GET['palabra'] : trim(decodeurl($_GET['q']));

  echo '
      <input onfocus="foco(this);" onblur="no_foco(this);" name="q" id="sdq" size="25" style="width: 450px; height: 22px; font-size: 18px;" class="searchBar" value="' . $pal . '" type="text" />
      <input onclick="return check();" class="login" value="Buscar" style="height: 32px; font-size: 18px;" title="Buscar" type="submit" />
    </div>
    <div class="filterSearch" style="margin-left: 0px; padding-left: 0px;">
      <div style="float: left;' . (!$cat ? ' display: none' : '') . '" id="filtercat">
        <label style="margin: 0px; padding: 0px;">Categor&iacute;a</label>
        <br />';

  if (!$context['user']['is_admin']) {
    $shas = ' WHERE ID_BOARD<>142';
  } else {
    $shas = '';
  }

  $request = db_query("
    SELECT ID_BOARD, description, name
    FROM {$db_prefix}boards
    $shas", __FILE__, __LINE__);

  $requess = db_query("
    SELECT url, nombre
    FROM {$db_prefix}comunidades_categorias
    ORDER BY nombre ASC", __FILE__, __LINE__);

  if ($mm) {
    echo '
      <select style="float: left; margin: 0px; padding: 0px; width: 150px; _width: 130px; #width: 130px; height: 20px;" name="categoria" id="categoria">
        <option value=""' . ($_GET['categoria'] == '' ? ' selected="selected" ' : '') . '>Todas</option>';

    while ($row = mysqli_fetch_assoc($requess)) {
      echo '<option value="' . $row['url'] . '"' . ($_GET['categoria'] == $row['url'] ? ' selected="selected"' : '') . '>' . $row['nombre'] . '</option>';
    }

    mysqli_free_result($requess);

    echo '</select>';
  } else {
    echo '
      <select style="float: left; margin: 0px; padding: 0px; width: 150px; _width: 130px; #width: 130px; height: 20px;" name="categoria" id="categoria">
        <option value=""' . ($_GET['categoria'] == '' ? ' selected="selected" ' : '') . '>Todas</option>';

    while ($row = mysqli_fetch_assoc($request)) {
      echo '<option value="' . $row['ID_BOARD'] . '"' . ($_GET['categoria'] == $row['ID_BOARD'] ? ' selected="selected"' : '') . '>' . $row['name'] . '</option>';
    }

    mysqli_free_result($request);

    echo '</select>';
  }

  echo '</div>';

  if (!$order) {
    $stylen = 'display: none;';
  }

  if (!$mm && $dd == 't' or $mm && $dd == 'c') {
    $stylend33 = 'display: none;';
  } else {
    $stylend33 = '';
  }

  if ($mm && $dd == 't') {
    $stylend3dd = 'Calificaci&oacute;n';
  } else if ($mm && $dd == 'c') {
    $stylend3dd = 'Temas';
  } else {
    $stylend3dd = 'Puntos';
  }

  echo '
    <div style="float: left; margin-left: 5px;' . $stylen . '" id="filterorder">
      <span>
        <label style="margin: 0px; padding: 0px;">Orden</label>
        <br />
        <select style="margin: 0px; padding: 0px; width: 70px; height: 20px;" name="orden" id="orden">
          <option value="fecha" id="fecha" style=""' . ($_GET['orden'] == 'fecha' ? ' selected="selected"' : '') . '>Fecha</option>
          <option value="puntos" id="puntos" style=""' . ($_GET['orden'] == 'puntos' ? ' selected="selected"' : '') . '>' . $stylend3dd . '</option>
          <option value="relevancia" id="relevancia" style="' . $stylend33 . '"' . ($_GET['orden'] == 'relevancia' ? ' selected="selected"' : '') . '>Relevancia</option>
        </select>
      </span>
    </div>';

  if (!$autor) {
    $stylen = ' style="display: none;" ';
  } else {
    $stylen = '';
  }

  echo '
                <div style="float: left; margin-left: 5px;">
                  <span id="filtro_autor"' . $stylen . '>
                    <label style="margin: 0px; padding: 0px;">Usuario</label>
                    <br />
                    <input onfocus="foco(this);" style="height: 15px; font-size: 11px; padding: 1px; width: 100px;" onblur="no_foco(this);" value="' . trim(decodeurl($_GET['autor'])) . '" name="autor" type="text" />
                  </span>
                </div>
                <div class="clearfix"></div>
              </div>
              <div class="clearfix"></div>
            </div>
            <div class="clearfix"></div>
          </div>
        </form>
      </div>
      <div class="clearBoth"></div>
    </div>';
}

?>