<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $db_prefix, $tranfer1, $ID_MEMBER, $ajaxError, $context;

if (empty($context['ajax'])) {
  echo $ajaxError;
  die();
}

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if (empty($id)) {
  die('<div class="noesta" style="width: 552px;">Debes especificar el mensaje que deseas leer.</div>');
}

if (empty($ID_MEMBER)) {
  die('<div class="noesta" style="width: 552px;">Funcionalidad exclusiva de usuarios registrados.</div>');
}

$request = db_query("
  SELECT id, titulo, fecha, mensaje, id_para
  FROM {$db_prefix}mensaje_personal
  WHERE id = $id
  AND id_de = $ID_MEMBER
  AND eliminado_para = 0
  AND sistema = 0
  LIMIT 1", __FILE__, __LINE__);

$rows = mysqli_num_rows($request);

if ($rows == 0) {
  die('<div class="noesta" style="width: 552px;">El mensaje que seleccionaste no existe.</div>');
}

while ($row = mysqli_fetch_array($request)) {
  $nick_a = getUsername($row['id_para']);

  echo '
    <div>
      <div style="text-align: left; width: 554px;">
        <div>
          <div style="padding: 4px 0 4px 2px; background-color: #F6F6F6;">
            <strong>A:</strong>
          </div>
          <div style="padding: 4px 0 4px 2px;">
            <a href="' . $boardurl . '/perfil/' . $nick_a . '">' . $nick_a . '</a>
          </div>
          <div style="padding: 4px 0 4px 2px; background-color: #F6F6F6;">
            <strong>Enviado:</strong>
          </div>
          <div style="padding: 4px 0 4px 2px;">' . hace($row['fecha']) . '</div>
        </div>
        <div style="padding: 4px 0 4px 2px; background-color: #F6F6F6;">
          <strong>Mensaje:</strong>
        </div>
        <div style="height: 140px; overflow: auto; padding: 4px 0 4px 2px;">
          ' . censorText(str_replace('(this.width > 720) {this.width=720}', '(this.width > 485) {this.width=485}', parse_bbc($row['mensaje']))) . '
        </div> 
      </div>
    </div>';
}

mysqli_free_result($request);

?>