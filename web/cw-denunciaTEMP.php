<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $context, $tranfer1, $db_prefix;

$_GET['t'] = isset($_GET['t']) ? (int) $_GET['t'] : 0;
$_GET['d'] = isset($_GET['d']) ? seguridad($_GET['d']) : 0;

if ($context['user']['is_guest']) {
  die('<div style="width: 400px;" class="noesta">Funcionalidad exclusiva de usuarios registrados.</div>');
}

// 0: Usuarios
// 1: Post
// 2: Imágenes
if (empty($_GET['t'])) {
  $request = db_query("
    SELECT realName
    FROM {$db_prefix}members
    WHERE realName = '{$_GET['d']}'", __FILE__, __LINE__);

  $esta = mysqli_num_rows($request);

  if (empty($esta)) {
    die('<div style="width: 400px;" class="noesta">El usuario que deseas denunciar no existe.</div>');
  }

  if ($context['user']['name'] == $_GET['d']) {
    die('<div style="width: 400px;" class="noesta">No te puedes denunciar a ti mismo.</div>');
  }

  echo '
    <div style="width: 400px;">
      <div id="resultado" style="display: none; width: 400px; margin: 0px;"></div>
      <div id="contentv">
        <p align="center" style="margin: 0px; padding: 0px;" class="size11">
          <strong>Raz&oacute;n de la denuncia:</strong>
          <br />
          <select name="razon" id="razon">
            <option value="Hace spam">Hace spam</option>
            <option value="Es racista o irrespetuoso">Es racista o irrespetuoso</option>
            <option value="Publica informacion personal">Publica informaci&oacute;n personal</option>
            <option value="Publica pornografia">Publica pornograf&iacute;a</option>
            <option value="No cumple con el protocolo">No cumple con el protocolo</option>
            <option value="Otra razon (especificar)">Otra raz&oacute;n (especificar)</option>
          </select>
          <br />
          <br />
          <strong>Aclaraci&oacute;n y comentarios:</strong>
          <br />
          <textarea name="comentario" id="cDen" onfocus="foco(this);" onblur="no_foco(this);" style="width: 380px;"  wrap="hard" tabindex="2"></textarea>
          <br />
          <br />
          <input class="login" onclick="enviarDen(\'user\', \'' . $_GET['d'] . '\');" type="button" value="Denunciar" />
        </p>
      </div>
    </div>';
} else if ($_GET['t'] == 1) {
  $request = db_query("
    SELECT ID_MEMBER
    FROM {$db_prefix}messages
    WHERE ID_TOPIC = {$_GET['d']}
    LIMIT 1", __FILE__, __LINE__);

  $row = mysqli_fetch_assoc($request);
  $idmember = isset($row['ID_MEMBER']) ? $row['ID_MEMBER'] : '';

  mysqli_free_result($request);

  if (empty($idmember)) {
    die('<div style="width: 400px;" class="noesta">El post que deseas denunciar no existe.</div>');
  }

  if ($context['user']['id'] == $idmember) {
    die('<div style="width: 400px;" class="noesta">No puedes denunciar tus post. Si tienes alg&uacute;n problema, b&oacute;rralo o ed&iacute;talo tu mismo.</div>');
  }

  echo '
    <div style="width: 400px;">
      <div id="resultado" style="display: none; width: 400px; margin: 0px;"></div>
        <div id="contentv">
          <p align="center" style="margin: 0px; padding: 0px;" class="size11">
            <strong>Raz&oacute;n de la denuncia:</strong>
            <br />
            <select name="razon" id="razon">
              <option value="Re-post">Re-post</option>
              <option value="Se hace spam">Se hace spam</option>
              <option value="Tiene enlaces muertos">Tiene enlaces muertos</option>
              <option value="Es racista o irrespetuoso">Es racista o irrespetuoso</option>
              <option value="Contiene informacion personal">Contiene informaci&oacute;n personal</option>
              <option value="El titulo esta en mayuscula">El t&iacute;tulo est&aacute; en may&uacute;scula</option>
              <option value="Contiene pornografia">Contiene pornograf&iacute;a</option>
              <option value="Es gore o asqueroso">Es gore o asqueroso</option>
              <option value="Esta mal la fuente">Est&aacute; mal la fuente</option>
              <option value="Post demasiado pobre">Post demasiado pobre</option>
              <option value="Pide contrasena y no esta">Pide contrase&ntilde;a y no est&aacute;</option>
              <option value="No cumple con el protocolo">No cumple con el protocolo</option>
              <option value="Otra razon (especificar)">Otra raz&oacute;n (especificar)</option>
            </select>
            <br />
            <br />
            <strong>Aclaraci&oacute;n y comentarios:</strong>
            <br />
            <textarea name="comentario" id="cDen" onfocus="foco(this);" onblur="no_foco(this);" style="width: 380px;"  wrap="hard" tabindex="2"></textarea>
            <br />
            <br />
            <input class="login" onclick="enviarDen(\'post\', \'' . $_GET['d'] . '\');" type="button" value="Denunciar" />
          </p>
        </div>
      </div>';
} else if ($_GET['t'] == 2) {
  $request = db_query("
    SELECT ID_MEMBER
    FROM {$db_prefix}gallery_pic
    WHERE ID_PICTURE = {$_GET['d']}
    LIMIT 1", __FILE__, __LINE__);

  $row = mysqli_fetch_assoc($request);
  $idmember = isset($row['ID_MEMBER']) ? $row['ID_MEMBER'] : '';

  mysqli_free_result($request);

  if (empty($idmember)) {
    die('<div style="width: 400px;" class="noesta">El post que deseas denunciar no existe.</div>');
  }

  if ($context['user']['id'] == $idmember) {
    die('<div style="width: 400px;" class="noesta">No puedes denunciar tus im&aacute;genes. Si tienes alg&uacute;n problema, b&oacute;rralo o ed&iacute;tala tu mismo.</div>');
  }

  echo '
    <div style="width: 400px;">
      <div id="resultado" style="display: none; width: 400px; margin: 0px;"></div>
      <div id="contentv">
        <p align="center" style="margin: 0px; padding: 0px;" class="size11">
          <strong>Raz&oacute;n de la denuncia:</strong>
          <br />
          <select name="razon" id="razon">
            <option value="Imagen ya agregada">Imagen ya agregada</option>
            <option value="Se hace spam">Se hace spam</option>
            <option value="Contiene pornografia">Contiene pornograf&iacute;a</option>
            <option value="Es gore o asqueroso">Es gore o asqueroso</option>
            <option value="No cumple con el protocolo">No cumple con el protocolo</option>
            <option value="Otra razon (especificar)">Otra raz&oacute;n (especificar)</option>
          </select>
          <br />
          <br />
          <strong>Aclaraci&oacute;n y comentarios:</strong>
          <br />
          <textarea name="comentario" id="cDen" onfocus="foco(this);" onblur="no_foco(this);" style="width: 380px;"  wrap="hard" tabindex="2"></textarea>
          <br />
          <br />
          <input class="login" onclick="enviarDen(\'imagen\', \'' . $_GET['d'] . '\');" type="button" value="Denunciar" />
        </p>
      </div>
    </div>';
}

?>