<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $db_prefix, $user_info, $context, $sourcedir, $tranfer1, $user_settings, $boardurl, $ID_MEMBER;

$id_tema = isset($_POST['id']) ? (int) $_POST['id'] : 0;
$cuerpo = isset($_POST['comentario']) ? seguridad($_POST['comentario']) : '';

if ($user_info['is_guest']) {
  die('0: Funcionalidad exclusiva de usuarios registrados.');
}

ignore_user_abort(true);
@set_time_limit(300);

if (empty($id_tema)) {
  die('0: Debes especificar el tema a comentar.');
}

$rs = db_query("
  SELECT a.nocoment, a.id_user, a.id_com
  FROM {$db_prefix}comunidades_articulos AS a, {$db_prefix}comunidades AS c
  WHERE a.id = $id_tema
  AND a.id_com = c.id
  AND c.bloquear = 0
  AND a.eliminado = 0
  LIMIT 1", __FILE__, __LINE__);

$row = mysqli_fetch_assoc($rs);
$iid_user = $row['id_user'];
$id_com = $row['id_com'];
$nocoment = $row['nocoment'];

$iid_user = isset($iid_user) ? $iid_user : '';

if (empty($iid_user)) {
  die('0: Debes especificar el tema a comentar.');
}

if ($nocoment) {
  die('0: Tema cerrado. No se permiten respuestas.');
}

require_once($sourcedir . '/FuncionesCom.php');

baneadoo($id_com);
acces($id_com);
permisios($id_com);

if (eaprobacion($id_com)) {
  die('0: Esperando aprobaci&oacute;n.');
}

if ($context['puedo'] == 1 || $context['puedo'] == 2 || $context['puedo'] == 3) {
  timeforComent();

  
  if ($cuerpo == '') {
    die('0: Debes agregar un comentario.');
  }
  if (strlen($cuerpo) > 4500) {
    die('0: El comentario es demasiado extenso, abr&eacute;vialo.');
  }

  db_query("
    INSERT INTO {$db_prefix}comunidades_comentarios (id_com, id_tema, id_user, comentario, leido) 
    VALUES ($id_com, $id_tema, $ID_MEMBER, '$cuerpo', 0)", __FILE__, __LINE__);

  $idseCe = db_insert_id();

  db_query("
    UPDATE {$db_prefix}comunidades_articulos
    SET respuestas = respuestas + 1
    WHERE id = $id_tema", __FILE__, __LINE__);

  notificacionAGREGAR($iid_user, '12');

  echo '1: ';
  $comene = parse_bbc($cuerpo);
  $comene2 = $cuerpo;

  echo '
    <div class="User-Coment">
      <div style="float: left;">
        <span class="size11">
          <b id="autor_cmnt_' . $idseCe . '" user_comment="' . $user_settings['realName'] . '" text_comment=\'' . $comene2 . '\'>
            <a href="' . $boardurl . '/perfil/' . $user_settings['realName'] . '" title="' . $user_settings['realName'] . '" style="color: #956100;">' . $user_settings['realName'] . '</a>
          </b>
          |
          ' . hace(time()) . '
          dijo:
        </span>
      </div>
      <div style="float: right;">';

  if (!$user_info['is_guest']) {
    echo '
      <span onclick="Boxy.load(\'' . $boardurl . '/web/cw-TEMPenviarMP.php?user=' . $user_settings['realName'] . "', { title: 'Enviar MP a " . $user_settings['realName'] . '\' })" title="Enviar MP a ' . $user_settings['realName'] . '" class="pointer">
        <img src="' . $tranfer1 . '/icons/mensaje_para.gif" alt="" />
      </span>';
  }

  if (!$nocoment && ($context['puedo'] == 1 || $context['puedo'] == 2 || $context['puedo'] == 3)) {
    echo ' <span onclick="citar_comment(' . $idseCe . ')" title="Citar comentario" class="pointer"><img src="' . $tranfer1 . '/comunidades/respuesta.png" class="png" alt="" /></span>';
  }

  if ($context['permisoCom'] == 1 || $context['permisoCom'] == 3 || $context['permisoCom'] == 2 || $iid_user == $ID_MEMBER) {
    echo ' <a href="' . $boardurl . '/web/cw-comunidadesEliCom.php?id=' . $idseCe . '" title="Eliminar comentario" onclick="if (!confirm(\'\xbfEst&aacute;s seguro que deseas eliminar este comentario?\')) return false;"><img src="' . $tranfer1 . '/comunidades/eliminar.png" class="png" alt="" /></a>';
  }

  echo '
      </div>
      <div style="clear:both"></div>
    </div>
    <div class="post-comentCont">
      ' . $comene . '
      <div class="clearBoth"></div>
    </div>';

  $_SESSION['ultima_accionTIME'] = time();
  die();
} else {
  die('0: No tienes permisos para comentar.');
}

?>