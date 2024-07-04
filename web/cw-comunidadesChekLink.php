<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $db_prefix, $user_info;

if ($user_info['is_guest']) {
  echo '0: Funcionalidad exclusiva de usuarios registrados.';
} else {

  $_POST['shortname'] = seguridad($_POST['shortname']);

  $request = db_query("
    SELECT url
    FROM {$db_prefix}comunidades
    WHERE url = '{$_POST['shortname']}'
    AND bloquear = 0
    LIMIT 1", __FILE__, __LINE__);

  $row = mysqli_fetch_row($request);

  if ($row) {
    echo '0: El nombre seleccionado ya est&aacute; en uso.';
  } else if (!preg_match('~[^a-zA-Z0-9\-]~', stripslashes($_POST['shortname'])) == 0) {
    echo '0: S&oacute;lo se permiten letras, n&uacute;meros y guiones medios (-).';
  } else if (strlen($_POST['shortname']) < 5 || strlen($_POST['shortname']) > 32) {
    echo '0: El nombre debe tener entre 5 y 32 caracteres.';
  } else {
    echo '1: &iexcl;El nombre est&aacute; disponible!';
  }
}

?>