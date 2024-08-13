<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $tranfer1, $db_prefix, $user_info, $user_settings;

if ($user_info['is_guest']) {
  die('0: Funcionalidad exclusiva de usuarios registrados.');
}

$_POST['quediche'] = trim($_POST['quediche']);
$_POST['id'] = (int) $_POST['id'];

if (empty($_POST['id'])) {
  die('0: Debes seleccionar un comentario del muro.');
}

if (empty($_POST['quediche']) || $_POST['quediche'] == 'Escribe un comentario...') {
  die('0: Te olvidaste del comentario.');
}
if (strlen($_POST['quediche']) > 10000) {
  die('0: No se aceptan escritos tan grandes.');
}

timeforComent();

$da = db_query("
  SELECT id_user
  FROM {$db_prefix}muro
  WHERE id = {$_POST['id']}
  AND tipocc = 0
  ORDER BY id DESC
  LIMIT 1", __FILE__, __LINE__);

while ($vdd = mysqli_fetch_assoc($da)) {
  $DeQuien = $vdd['id_user'];
}

mysqli_free_result($da);

$DeQuien = isset($DeQuien) ? $DeQuien : '';
if (empty($DeQuien)) {
  die('0: Debes seleccionar un comentario del muro.');
}

$request = db_query("
  SELECT id_user
  FROM {$db_prefix}pm_admitir
  WHERE id_user = '$DeQuien'
  AND quien = $ID_MEMBER
  LIMIT 1", __FILE__, __LINE__);

$ignorado = mysqli_num_rows($request);
if ($ignorado) {
  die('0: No puedes comentar este muro.');
}

db_query("
  INSERT INTO {$db_prefix}muro (id_user, de, tipo, muro, tipocc, id_cc)
  VALUES ($DeQuien, $ID_MEMBER, 0, '{$_POST['quediche']}', 1, {$_POST['id']})", __FILE__, __LINE__);

$dds = db_insert_id();

$da2 = db_query("
  SELECT de
  FROM {$db_prefix}muro
  WHERE id_cc = {$_POST['id']}
  OR id = {$_POST['id']}", __FILE__, __LINE__);

while ($vdds = mysqli_fetch_assoc($da2)) {
  $ts[] = $vdds['de'];
  $ts[] = $DeQuien;
}

mysqli_free_result($da2);

$sh = implode('|', array_unique($ts));
$iw = explode('|', $sh);
$c = count($iw) - 1;

for ($i = 0; $i <= $c; ++$i) {
  notificacionAGREGAR($iw[$i], '8');
}

db_query("
  UPDATE {$db_prefix}muro
  SET ccos = ccos + 1
  WHERE id='{$_POST['id']}'
  LIMIT 1", __FILE__, __LINE__);

echo '1: 
  <div id="SETcto_' . $dds . '">
    <div id="cto_' . $_POST['id'] . '" class="muroCcs" style="text-align: left; color: #666666; margin-bottom: 3px;">
      <strong>
        <a href="' . $boardurl . '/perfil/' . $user_settings['realName'] . '" style="color:#666666;" title="' . $user_settings['realName'] . '">' . $user_settings['realName'] . '</a>
      </strong> - ' . hace(time());

if ($user_settings['ID_MEMBER'] == $DeQuien || ($user_info['is_admin'] || $user_info['is_mods'])) {
  echo ' - <span class="pointer" onclick="Boxy.confirm(\'&iquest;Est&aacute;s seguro que deseas borrar este comentario?\', function() { del_comentCC_muro(\'' . $dds . '\'); }, {title: \'Eliminar cmentario\'});" title="Eliminar comentario">Eliminar</span>';
}

echo '
    <br />
    ' . moticon(censorText($_POST['quediche']), true) . '
    </div>
  </div>
  <div class="noestaGR" id="SETcto2_' . $dds . '" style="display: none; width: 416px; margin-bottom: 3px;"></div>';

$_SESSION['ultima_accionTIME'] = time();

die();
?>