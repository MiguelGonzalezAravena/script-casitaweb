<?php

function template_manual_above() {}
function template_manual_below() {}

function template_intro() {
  exit();
  die();
}

// Editar firma
function template_tyc3() {
  global $tranfer1, $context, $db_prefix, $modSettings, $boardurl;

  if ($context['user']['is_guest']) {
    fatal_error('Funcionalidad exclusiva de usuarios registrados.');
  }

  $Activo = substr($modSettings['signature_settings'], 0, 1) == 1;

  if ($Activo) {
    $getid = isset($_GET['u']) ? (int) $_GET['u'] : '';

    ditaruser();

    echo '<div style="float: left; width: 776px;">';

    $usecc = $getid ? $getid : $context['user']['id'];

    $existe = db_query("
      SELECT signature
      FROM {$db_prefix}members
      WHERE ID_MEMBER = $usecc
      LIMIT 1", __FILE__, __LINE__);

    $row = mysqli_fetch_assoc($existe);
    $signature = $row['signature'];

    mysqli_free_result($existe);

    echo '
      <script type="text/javascript">
        function comprobar(firma) {
          if (firma.length > 400) {
            $(\'#MostrarError1\').show();
            return false;
          } else {
            $(\'#MostrarError1\').hide();
          }
        }
      </script>
      <form name="per" method="post" action="' . $boardurl . '/web/cw-firmaEditar.php">
        <div class="box_780" style="float: left;">
          <div class="box_title" style="width: 774px;">
            <div class="box_txt box_780-34">
              <center>' . ($getid ? 'Editar la firma' : 'Editar mi firma') . '</center>
            </div>
            <div class="box_rss">
              <img alt="" src="' . $tranfer1 . '/blank.gif" style="width: 16px; height: 16px;" />
            </div>
          </div>
          <div class="windowbg" style="width: 766px; padding: 4px; margin-bottom: 8px;">
            <textarea onfocus="foco(this);" onblur="no_foco(this);" name="firma" id="firma" style="width: 758px; height: 100px;">' . $signature . '</textarea>
            <div id="MostrarError1" class="capsprotBAJO" style="width: 758px;">La firma no debe tener m&aacute;s de 400 car&aacute;cteres.</div>
            <div class="hrs"></div>
            <div class="noesta">* Si la firma contiene pornograf&iacute;a o es morboso, se borrar&aacute;.</div>
            <br />';

    if ($getid) {
      echo '
        <input type="hidden" name="admin" value="1" />
        <input type="hidden" name="id_user" value="' . $getid . '" />';

      $titlbotte = 'Editar el perfil';
    } else {
      $titlbotte = 'Editar mi perfil';
    }

    echo '
              <center>
                <input onclick="return comprobar(this.form.firma.value);" type="submit" class="button" style="font-size: 15px" value="' . $titlbotte . '" title="' . $titlbotte . '" />
              </center>
            </div>
          </div>
        </div>
      </form>';
  } else {
    fatal_error('La firma se encuentra desactivada.');
  }
}

function template_tyc4() {}

// Moderación de muros
function template_tyc6() {
  global $tranfer1, $context, $settings, $sourcedir, $options, $txt, $scripturl, $db_prefix, $modSettings, $boardurl;

  if ($context['user']['name'] == 'rigo' || $context['user']['id'] == 1) {
    echo '
      <div class="box_757">
        <div class="box_title" style="width: 752px;">
          <div class="box_txt box_757-34">
            <center>Muros</center>
          </div>
          <div class="box_rss">
            <img alt="" src="' . $tranfer1 . '/blank.gif" style="width: 16px; height: 16px;" border="0" />
          </div>
        </div>
      </div>
      <div style="width: 744px; padding: 4px;" class="windowbg">';

    $RegistrosAMostrar = 25;
    $pag = isset($_GET['pag-seg-157']) ? (int) $_GET['pag-seg-157'] : 1;
    $dud = $pag < 0 ? 1 : $pag;

    if (isset($dud)) {
      $RegistrosAEmpezar = ($dud - 1) * $RegistrosAMostrar;
      $PagAct = $dud;
    } else {
      $RegistrosAEmpezar = 0;
      $PagAct = 1;
    }

    $request = db_query("
      SELECT id, id_user, de, muro, tipo
      FROM {$db_prefix}muro
      ORDER BY id DESC
      LIMIT $RegistrosAEmpezar, $RegistrosAMostrar", __FILE__, __LINE__);

    while ($row = mysqli_fetch_array($request)) {
      echo '<div id="muro-' . $row['id'] . '">';

      $nick = getUsername($row['id_user']);
      $nick3 = getUsername($row['de']);

      if ($row['tipo'] == 0) {
        echo '<img src="' . $tranfer1 . '/icons/si.png" alt="Escrito" title="Escrito" />';
      } else if ($row['tipo'] == 1) {
        echo '<img src="' . $tranfer1 . '/icons/no.png" alt="Est&aacute; haciendo..." title="Est&aacute; haciendo..." />';
      } else {
        echo '<b style="color: red;"><i>Tipo de muro no conocido ||| </i></b>';
      }

      echo "
          -
          <a onclick=\"if (!confirm('\\xbfEst&aacute;s seguro que deseas borrar este mensaje?')) return false; del_coment_muro('" . $row['id'] . '\'); return false;" href="#" title="Eliminar mensaje">
            <img alt="Eliminar mensaje" src="' . $tranfer1 . '/eliminar.gif" width="8px" height="8px" />
          </a>
          <br />
          <b>Por:</b>
          <a href="' . $boardurl . '/perfil/' . $nick3 . '" title="' . $nick3 . '">' . $nick3 . '</a>
          <br />
          <b>A:</b>
          <a href="' . $boardurl . '/perfil/' . $nick . '" title="' . $nick . '">' . $nick . '</a>
          <br />
          <b>Mensaje:</b>
          <br />
          ' . parse_bbc(str_replace('<br/>', "\n", $row['muro'])) . '
          <div class="hrs"></div>
        </div>';
    }

    mysqli_free_result($request);

    $request = db_query("
      SELECT id
      FROM {$db_prefix}muro", __FILE__, __LINE__);

    $NroRegistros = mysqli_num_rows($request);
    $PagAnt = $PagAct - 1;
    $PagSig = $PagAct + 1;
    $PagUlt = $NroRegistros / $RegistrosAMostrar;
    $Res = $NroRegistros % $RegistrosAMostrar;

    if ($Res > 0) {
      $PagUlt = floor($PagUlt) + 1;
    }

    if ($PagAct > $PagUlt) {
      // ¿Qué se hace aquí?
    } else {
      echo '
          <br />
          <b>Cantidad de escritos:</b>
          ' . $NroRegistros . '
          <br />
        </div>';
    }

    if ($PagAct > $PagUlt) {
      echo '
          <div class="noesta">
            <b class="size11">Esta p&aacute;gina no existe.</b>
          </div>
        </div>';
    } else {
      echo '
        <div class="windowbgpag" style="width: 747px; padding: 4px;">
          <center>
            <font size="2">';

      if ($PagAct > 1) {
        echo `<a href="$boardurl/moderacion/muro/pag-$PagAnt">< anterior</a>`;
      }

      if ($PagAct < $PagUlt) {
        echo `<a href="$boardurl/moderacion/muro/pag-$PagSig">siguiente ></a>`;
      }

      echo '
            </font>
          </center>
        </div>';
    }
  } else {
    fatal_error('No puedes estar ac&aacute;.');
  }
}

?>