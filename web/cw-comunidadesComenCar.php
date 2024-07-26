<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $db_prefix, $context, $user_info, $sourcedir, $user_settings, $boardurl;

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if (!$id) {
  die('Debes especificar el tema a comentar.');
}

$rs44 = db_query("
  SELECT c.id, a.nocoment
  FROM {$db_prefix}members AS m, {$db_prefix}comunidades AS c, {$db_prefix}comunidades_articulos AS a
  WHERE a.id = $id
  AND a.id_com = c.id
  AND a.id_user = m.ID_MEMBER
  AND c.bloquear = 0
  LIMIT 1", __FILE__, __LINE__);

$row = mysqli_fetch_assoc($rs44);
$dasdasd = $row['id'];
$nocoment = $row['nocoment'];

require_once($sourcedir . '/FuncionesCom.php');

baneadoo($dasdasd);
entrar($dasdasd);
permisios($dasdasd);
acces($dasdasd);

$pp = $context['cc'];
$per = $_GET['pag'] < 1 ? 1 : $_GET['pag'];
$st = isset($per) ? ($per - 1) * $pp : 1;

echo '
  <div class="post-com" id="carando" style="display: none; padding: 4px 0px 4px 0px; margin-bottom: 4px;">
    <center>
      <img alt="" src="' . $tranfer1 . '/comunidades/cargando.gif" />
    </center>
  </div>
  <div class="post-com">';

$request = db_query("
  SELECT id
  FROM {$db_prefix}comunidades_comentarios
  WHERE id_tema = $id", __FILE__, __LINE__);

$cant = mysqli_num_rows($request);

$request2 = db_query("
  SELECT com.comentario, m.realName, com.id, com.fecha, m.ID_MEMBER
  FROM {$db_prefix}members AS m, {$db_prefix}comunidades_comentarios AS com
  WHERE com.id_tema = $id
  AND com.id_user = m.ID_MEMBER
  ORDER BY com.id ASC
  LIMIT $st, $pp", __FILE__, __LINE__);

$emrr = $pp * ($per - 1);
$emrr = $emrr < 2 ? 1 : $emrr + 1;
$caste = $emrr;
$caste32 = $emrr;
$caste2 = $emrr;

while ($row = mysqli_fetch_assoc($request2)) {
  $mesesano2 = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12');
  $diames2 = date(j, $row['fecha']);
  $mesano2 = date(n, $row['fecha']) - 1;
  $ano2 = date(Y, $row['fecha']);
  $seg2 = date(s, $row['fecha']);
  $hora2 = date(H, $row['fecha']);
  $min2 = date(i, $row['fecha']);
  $dasd = $row['id'];
  $comene = parse_bbc(nohtml(nohtml2($row['comentario'])));
  $comene2 = nohtml(nohtml2($row['comentario']));

  echo '
    <div class="coment-user" id="' . $caste2++ . '">
      <div style="float: left;">
        <div class="com-com-info">
          <a href="#' . $caste32++ . '">#' . $caste++ . '</a>
        </div>
        <b id="autor_cmnt_' . $dasd . '" user_comment="' . $row['realName'] . '" text_comment="' . $comene2 . '">
          <a href="' . $boardurl . '/perfil/' . $row['realName'] . '" title="' . $row['realName'] . '">' . $row['realName'] . '</a>
        </b>
        |
        <span class="size10">' . tiempo2($row['fecha']) . '</span>
        dijo:
      </div>
      <div style="float: right;">';

  if (!$user_info['is_guest']) {
    echo '
      <a href="' . $boardurl . '/mensajes/a/' . $row['realName'] . '" title="Enviar MP a: ' . $row['realName'] . '">
        <img src="' . $tranfer1 . '/icons/mensaje_para.gif" alt="" />
      </a>';
  }

  if (!$nocoment && ($context['puedo'] == '1' || $context['puedo'] == '2' || $context['puedo'] == '3')) {
    echo '
      &nbsp;
      <a onclick="citar_comment(' . $dasd . ')" href="javascript:void(0)" title="Citar comentario">
        <img src="' . $tranfer1 . '/comunidades/respuesta.png" alt="" />
      </a>';
  }

  if (in_array($context['permisoCom'], [1, 2, 3]) || $row['ID_MEMBER'] == $ID_MEMBER) {
    echo '
      &nbsp;
      <a href="' . $boardurl . '/web/cw-comunidadesEliCom.php?id=' . $dasd . '" title="Eliminar comentario" onclick="if (!confirm(\'\xbfEst&aacute;s seguro que deseas eliminar este comentario?\')) return false;">
        <img src="' . $tranfer1 . '/comunidades/eliminar.png" alt="" />
      </a>';
  }

  echo '
      </div>
      <div style="clear:both"></div>
    </div>
    <div class="post-contenido">
      ' . $comene . '
      <div class="clearBoth"></div>
    </div>
    <div align="right" style="padding: 0px 4px 4px 0px;">
      <a href="#top" title="Ir arriba">
        <img src="' . $tranfer1 . '/comunidades/arriba-com.png" alt="" />
      </a>
    </div>';
}

if (!$dasd) {
  echo '
    <div class="coment-user">
      <div class="noesta">Este tema no tiene comentarios.</div>
    </div>';
}

echo '</div>';

echo ddsss($cant, $pp, $st, $id);

die();

?>