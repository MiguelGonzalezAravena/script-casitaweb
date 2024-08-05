<?php
require(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $tranfer1, $db_prefix, $context, $settings, $ajaxError, $options, $ID_MEMBER, $scripturl, $modSettings, $sourcedir, $boardurl;

if (empty($context['ajax'])) {
  echo $ajaxError;
  die();
}

$_GET['c'] = isset($_GET['c']) ? (int) $_GET['c'] : '';

if (empty($_GET['c'])) {
  echo '<div class="noesta" style="width: 541px; margin-bottom: 8px; float: left;">Acci&oacute;n no conocida.</div>';
}

require_once($sourcedir . '/FuncionesCom.php');
permisios($_GET['c']);

if ($context['permisoCom'] == 1) {
  $RegistrosAMostrar = 8;
  $_GET['pag'] = isset($_GET['pag']) ? $_GET['pag'] : '';

  if ($_GET['pag'] < 1) {
    $per = 1;
  } else {
    $per = $_GET['pag'];
  }

  if (isset($per)) {
    $RegistrosAEmpezar = ($per - 1) * $RegistrosAMostrar;
    $PagAct = $per;
  } else {
    $RegistrosAEmpezar = 0;
    $PagAct = 1;
  }

  $PagAnt = $PagAct - 1;
  $PagSig = $PagAct + 1;
  $PagUlt = $NroRegistros / $RegistrosAMostrar;
  $Res = $NroRegistros % $RegistrosAMostrar;

  if ($Res > 0) {
    $PagUlt = floor($PagUlt) + 1;
  }

  $request = db_query("
    SELECT c.id
    FROM {$db_prefix}comunidades_miembros AS c, {$db_prefix}members AS m
    WHERE c.id_com = '{$_GET['c']}'
    AND c.id_user = m.ID_MEMBER
    AND c.aprobado = 0", __FILE__, __LINE__);

  $NroRegistros = mysqli_num_rows($request);

  echo '
    <div>
      <div id="contenidoPG" style="width: 510px; _width: 515px; height: 340px; overflow-y: auto; overflow-x: hidden;">';

  if (empty($NroRegistros)) {
    echo '<div class="noesta">No hay miembros.</div>';
  } else {
    if (($PagAct > 1 || $PagAct < $PagUlt) && $PagAct > 1) {
      echo '<div class="panador" onclick="pagComunidad2(\'' . $_GET['c'] . '\', \'' . $PagAnt . '\')">&#171; anterior</div>';
    }

    echo '<div id="miem-com">';

    $request = db_query("
      SELECT m.realName, m.avatar, c.rango, m.usertitle, m.gender, c.id
      FROM {$db_prefix}comunidades_miembros AS c, {$db_prefix}members AS m
      WHERE c.id_com = '{$_GET['c']}'
      AND c.id_user = m.ID_MEMBER
      AND c.aprobado = 0
      ORDER BY c.id DESC
      LIMIT $RegistrosAEmpezar, $RegistrosAMostrar", __FILE__, __LINE__);

    while ($row = mysqli_fetch_assoc($request)) {
      $img = $row['avatar'];
      $gender = $row['gender'];
      $usertitle = $row['usertitle'];
      $img2 = isset($img) ? $img : $no_avatar;

      echo '
        <div>
          <h4>
            <a href="' . $boardurl . '/perfil/' . $row['realName'] . '/" title="Perfil de ' . $row['realName'] . '">' . $row['realName'] . '</a>
          </h4>
          <div style="float: left;">
            <a href="' . $boardurl . '/perfil/' . $row['realName'] . '/" title="Perfil de ' . $row['realName'] . '">
              <img src="' . $img2 . '" onerror="error_avatar(this)" width="75px" height="75px" />
            </a>
          </div>
          <div style="float: right; width: 400px;">
            <ul>
              <li style="margin-bottom: 2px;"><b>Rango:</b> ' . ranguear($row['rango'], $_GET['c']) . '</li>
              <li style="margin-bottom: 2px;"><b>Pa&iacute;s:</b> ' . pais($usertitle) . '</li>
              <li style="margin-bottom: 2px;"><b>Sexo:</b> ' . sex($gender, 1) . ' </li>
              <li style="margin-bottom: 2px;">
                <div>
                  <div style="float: left; margin-right: 5px; margin-left: 5px;">
                    <a href="' . $boardurl . '/web/cw-ComunidadesAprobarmem.php?m=' . $row['id'] . '" title="Aprobar" class="botN1" style="cursor: pointer; color: #fff; text-shadow: #005400 0px 1px 0px;">Aprobar</a>
                  </div>
                  <div>
                    <a href="' . $boardurl . '/web/cw-ComunidadesDesaprobarmem.php?m=' . $row['id'] . '" title="No aprobar" class="botN2" style="cursor: pointer; text-shadow: #CC0000 0px 1px 0px; color: #fff;">No aprobar</a>
                  </div>
                </div>
              </li>
            </ul>
          </div>
          <div class="clearfix"></div>
        </div>';
    }

    echo '</div>';

    if (($PagAct > 1 || $PagAct < $PagUlt) && $PagAct < $PagUlt) {
      echo '
        <div class="panador" onclick="pagComunidad2(\'' . $_GET['c'] . '\', \'' . $PagSig . '\')">
          <div class="clearfix"></div>
          siguiente &#187;
        </div>';
    }
  }

echo '
    </div>
  </div>';
} else {
  echo'<div class="noesta" style="width: 510px;">Acci&oacute;nn no conocida.</div>';
}

?>