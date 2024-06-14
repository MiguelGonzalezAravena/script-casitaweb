<?php
function template_intro() {
  global $tranfer1, $txt, $context, $user_settings, $boardurl;

  $accion = isset($_GET['sas']) ? $_GET['sas'] : '';

  echo '
    <div style="width: 160px; float: left; margin-right: 8px;">
      <div style="margin-bottom: 8px;" class="img_aletat">
        <div class="box_title" style="width: 158px;">
          <div class="box_txt img_aletat">' . $txt['pm_messages'] . '</div>
          <div class="box_rss">
            <img alt="" src="' . $tranfer1 . '/blank.gif" style="width: 16px; height: 16px;" border="0" />
          </div>
        </div>
        <div class="windowbg" style="font-size: 13px; width: 150px; padding: 4px;">';

  if ($user_settings['topics'] >= 1) {
    $b = '<b>';
    $bc = '</b>';
    $cantidad = '(<span id="cantidad-MP2">' . $user_settings['topics'] . '</span>)';
  } else {
    $b = '';
    $bc = '';
    $cantidad = '';
  }

  echo '
        <img src="' . $tranfer1 . '/icons/mensaje_enviar.gif" alt="" width="16px" height="16px" />
        <span class="pointer" onclick="Boxy.load(\'' . $boardurl . '/web/cw-TEMPenviarMP.php\', { title: \'Enviar mensaje privado\' });" title="Enviar mensaje privado"> Enviar mensaje</span>
        <br />
        <img src="' . $tranfer1 . '/icons/mensaje.gif" alt="" width="16px" height="16px" />
        <a href="' . $boardurl . '/mensajes/recibidos/" title="' . $txt[318] . '"> ' . $b . $txt[316] . ' ' . $cantidad . $bc . '</a>
        <br />
        <img src="' . $tranfer1 . '/icons/mensaje_para.gif" alt="" width="16px" height="16px" />
        <a href="' . $boardurl . '/mensajes/enviados/" title="' . $txt[320] . '"> ' . $txt[320] . '</a>
      </div>
    </div>
    <div class="img_aletat">
      <div class="box_title" style="width: 158px;">
        <div class="box_txt img_aletat">Publicidad</div>
        <div class="box_rss">
          <img alt="" src="' . $tranfer1 . '/blank.gif" style="width: 16px; height: 16px;" border="0" />
        </div>
      </div>
      <div class="windowbg" style="font-size: 13px; width: 150px; padding: 4px;">';
      
  anuncio1_120x240();

  echo '
        </div>
      </div>
    </div>
    <div style="float: left; width: 754px;">';

  switch($accion) {
    case 'enviados':
      enviados();
      break;
    case 'recibidos':
    default:
      recibidos();
      break;
  }

  echo '</div>';
}

function enviados() {
  global $db_prefix, $tranfer1, $context, $ID_MEMBER, $boardurl;

  $RegistrosAMostrar = 5;
  $pag = isset($_GET['pag-seg-145a']) ? (int) $_GET['pag-seg-145a'] : 1;

  if (isset($pag)) {
    $RegistrosAEmpezar = ($pag - 1) * $RegistrosAMostrar;
    $PagAct = $pag;
  } else {
    $RegistrosAEmpezar = 0;
    $PagAct = 1;
  }

  $request = db_query("
    SELECT id_de
    FROM {$db_prefix}mensaje_personal
    WHERE id_de = $ID_MEMBER
    AND eliminado_de = 0", __FILE__, __LINE__);

  $NroRegistros = mysqli_num_rows($request);

  $mensajes = db_query("
    SELECT id_para, titulo, id,fecha
    FROM {$db_prefix}mensaje_personal
    WHERE id_de = $ID_MEMBER
    AND eliminado_de = 0
    AND sistema = 0
    ORDER BY id DESC
    LIMIT $RegistrosAEmpezar, $RegistrosAMostrar", __FILE__, __LINE__);

  if (empty($NroRegistros)) {
    echo'<div class="noesta" style="width: 754px;">No hay mensajes enviados...</div>';
  } else {
    echo '
      <table class="linksList" style="width: 754px;">
        <thead align="center">
          <tr>
            <th>&nbsp;</th>
            <th>Asunto</th>
            <th>Destinatario</th>
            <th>Enviado</th>
          </tr>
        </thead>
        <tbody>';

    while ($row = mysqli_fetch_array($mensajes)) {
      $nick_a = getUsername($row['id_para']);

      echo '
        <tr>
          <td>
            <span title="Eliminar mensaje" class="pointer" onclick="Boxy.confirm(\'&iquest;Est&aacute;s seguro que deseas eliminar este mensaje?\', function() { location.href=\'' . $boardurl . '/web/cw-eliminarMp2.php?id_sde=' . $row['id'] . '\'; }, { title: \'Eliminar mensaje\' });">
              <img width="10px" src="' . $tranfer1 . '/eliminar.gif"  alt="" />
            </span>
          </td>
          <td>
            <span class="pointer" onclick="Boxy.load(\'' . $boardurl . '/web/cw-TEMPleereMP.php?id=' . $row['id'] . '\', { title: \'' . censorText($row['titulo']) . '\' });" title="' . censorText($row['titulo']) . '">' . censorText($row['titulo']) . '</span>
          </td>
          <td>
            <a href="' . $boardurl . '/perfil/' . $nick_a . '" title="' . $nick_a . '">' . $nick_a . '</a>
          </td>
          <td>
            <span class="size11">' . timeformat($row['fecha']) . '</span>
          </td>
        </tr>';
    }

    mysqli_free_result($mensajes);

    echo '
        </tbody>
      </table>';
  }

  $PagAnt = $PagAct - 1;
  $PagSig = $PagAct + 1;
  $PagUlt = $NroRegistros / $RegistrosAMostrar;
  $Res = $NroRegistros % $RegistrosAMostrar;

  if ($Res > 0) {
    $PagUlt = floor($PagUlt) + 1;
  }

  if (!empty($NroRegistros)) {
    if (($PagAct > 1) || ($PagAct < $PagUlt)) {
      echo '<div class="windowbgpag" style="width: 300px;">';

      if ($PagAct > 1) {
        echo '<a href="' . $boardurl . '/mensajes/enviados/pag-' . $PagAnt . '">&#171; anterior</a>';
      }

      if ($PagAct < $PagUlt) {
        echo '<a href="' . $boardurl . '/mensajes/enviados/pag-' . $PagSig . '">siguiente &#187;</a>';
      }

      echo '
        </div>
        <div class="clearBoth"></div>';
    }
  }
}

function recibidos() {
  global $db_prefix, $tranfer1, $context, $ID_MEMBER, $boardurl;

  $RegistrosAMostrar = 5;

  if (isset($_GET['pag-seg-145a'])) {
    $RegistrosAEmpezar = ($_GET['pag-seg-145a'] - 1) * $RegistrosAMostrar;
    $PagAct = $_GET['pag-seg-145a'];
  } else {
    $RegistrosAEmpezar = 0;
    $PagAct = 1;
  }

  $request = db_query("
    SELECT id_para, eliminado_para
    FROM {$db_prefix}mensaje_personal
    WHERE id_para = $ID_MEMBER
    AND eliminado_para = 0", __FILE__, __LINE__);

  $NroRegistros = mysqli_num_rows($request);

  if (empty($NroRegistros)) {
    echo'<div class="noesta" style="width:754px;">No hay mensajes recibidos...</div>';
  } else {
    $request = db_query("
      SELECT id, leido, titulo, name_de, fecha
      FROM {$db_prefix}mensaje_personal
      WHERE id_para = $ID_MEMBER
      AND eliminado_para = 0
      ORDER BY id DESC
      LIMIT $RegistrosAEmpezar, $RegistrosAMostrar", __FILE__, __LINE__);

    echo '
      <table class="linksList" style="width: 754px;">
        <thead align="center">
          <tr>
            <th>&nbsp;</th>
            <th>Asunto</th>
            <th>Por</th>
            <th>Recibido</th>
          </tr>
        </thead>
        <tbody>';

    while ($row = mysqli_fetch_array($request)) {
      echo '
        <tr' . (!$row['leido'] ? ' style="background-color: #FDFBE7;" ' : '') . ' id="mp_' . $row['id'] . '">
          <td>
            <span id="imgel_' . $row['id'] . '" class="pointer" onclick="Boxy.confirm(\'&iquest;Est&aacute;s seguro que deseas eliminar este mensaje?\', function() { del_mp_env(\'' . $row['id'] . '\'); }, { title: \'Eliminar mensaje\' });" title="Eliminar mensaje">
              <img width="10px" src="' . $tranfer1 . '/eliminar.gif"  alt="" />
            </span>
            <span id="imgerr_' . $row['id'] . '" style="display: none;"></span>
            <span id="imgerrs_' . $row['id'] . '" style="display: none;">
              <img width="10px" src="' . $tranfer1 . '/eliminar.gif" alt="" />
            </span>
          </td>
          <td>
            <span class="pointer" onclick="Boxy.load(\'' . $boardurl . '/web/cw-TEMPleerMP.php?id=' . $row['id'] . '\', {title: \'' . censorText($row['titulo']) . '\'});" title="' . censorText($row['titulo']) . '">' . censorText($row['titulo']) . '</span>
          </td>
          <td>
            <a href="' . $boardurl . '/perfil/' . $row['name_de'] . '" title="' . $row['name_de'] . '">' . $row['name_de'] . '</a>
          </td>
          <td>
            <span class="size11">' . timeformat($row['fecha']) . '</span>
          </td>
        </tr>';
    }
    
    mysqli_free_result($request);

    echo '
        </tbody>
      </table>';
  }

  $PagAnt = $PagAct - 1;
  $PagSig = $PagAct + 1;
  $PagUlt = $NroRegistros / $RegistrosAMostrar;
  $Res = $NroRegistros % $RegistrosAMostrar;

  if ($Res > 0) {
    $PagUlt = floor($PagUlt) + 1;
  }

  if (!empty($NroRegistros)) {
    if ($PagAct > 1 || $PagAct < $PagUlt) {
      echo '<div class="windowbgpag" style="width: 300px;">';

      if ($PagAct > 1) {
        echo '<a href="' . $boardurl . '/mensajes/pag-' . $PagAnt . '">&#171; anterior</a>';
      }

      if ($PagAct < $PagUlt) {
        echo '<a href="' . $boardurl . '/mensajes/pag-' . $PagSig . '">siguiente &#187;</a>';
      }

      echo '
        </div>
        <div class="clearBoth"></div>';
    }
  }
}

?>