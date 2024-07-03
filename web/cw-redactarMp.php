<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $db_prefix, $user_settings, $user_info, $sourcedir, $context;

$para = isset($_POST['para']) ? seguridad($_POST['para']) : '';
$titulo = isset($_POST['titulo']) ? seguridad($_POST['titulo']) : '';
$mensaje = isset($_POST['mensaje']) ? seguridad($_POST['mensaje']) : '';

if ($user_info['is_guest']) {
  die('Funcionalidad exclusiva de usuarios registrados.');
}

if (!$user_info['is_guest']) {
  $limit3 = db_query("
    SELECT fecha
    FROM ({$db_prefix}mensaje_personal)
    WHERE id_de='{$user_settings['ID_MEMBER']}'
    ORDER BY fecha DESC
    LIMIT 1", __FILE__, __LINE__);
  while ($lim2 = mysqli_fetch_assoc($limit3)) {
    $modifiedTime = $lim2['fecha'];
  }

  if ($modifiedTime > time() - 25) {
    die('0: No es posible enviar mensajes con tan poca diferencia de tiempo.<br />Vuelva a intentar en segundos.');
  }

  $datosmem = db_query("
    SELECT ID_MEMBER
    FROM {$db_prefix}members
    WHERE realName = '$para'
    LIMIT 1", __FILE__, __LINE__);

  $data = mysqli_fetch_assoc($datosmem);
  $id_para = $data['ID_MEMBER'];

  $request = db_query("
    SELECT id_user, quien
    FROM {$db_prefix}pm_admitir
    WHERE id_user = $id_para
    AND quien = $ID_MEMBER
    LIMIT 1", __FILE__, __LINE__);

  $admitir = mysqli_num_rows($request);

  if (empty($admitir)) {
    require_once($sourcedir . '/Subs-Post.php');
    sendpm($titulo, $mensaje, $id_para, 0);
  } else {
    die('0: No puedes enviarle MP a este usuario.');
  }

  exit('1: &iexcl;Mensaje enviado correctamente!');
}

?>