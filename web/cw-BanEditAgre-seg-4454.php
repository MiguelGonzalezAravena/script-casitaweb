<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $ID_MEMBER, $db_prefix, $user_settings, $user_info;

$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
$razon = isset($_POST['reason']) ? seguridad($_POST['reason']) : '';
$letras = '0x1o2m3b4r5a6H7b8c9dZ';

if (($user_info['is_admin'] || $user_info['is_mods'])) {
  if ($_POST['modificar'] || $_POST['agregar']) {
    srand((float) microtime() * 1000000);
    $i = 1;
    $largo_clave = 6;
    $largo = strlen($letras);
    $clave_usuario = '';
    while ($i <= $largo_clave) {
      $lee = rand(1, $largo);
      $clave_usuario .= substr($letras, $lee, 1);
      $i++;
    }

    $clave_usuario = trim($clave_usuario);

    if (empty($razon)) {
      die('0: Debes agregar la raz&oacute;n del baneo.');
    }

    if ($_POST['modificar']) {
      $request352 = db_query("
        SELECT g.notes, g.clave, m.ID_MEMBER, m.memberIP, m.emailAddress
        FROM {$db_prefix}ban_groups AS g, {$db_prefix}members AS m
        WHERE g.ID_BAN_GROUP = $id
        AND g.name = m.realName
        LIMIT 1", __FILE__, __LINE__);

      while ($rows = mysqli_fetch_assoc($request352)) {
        $memberIP = $rows['memberIP'];
        $emailAddress = $rows['emailAddress'];
        $notes = $rows['notes'];
        $clavsss = $rows['clave'];
        $IssD_MEMBER = $rows['ID_MEMBER'];
      }

      if (empty($notes)) {
        die('0: El usuario especificado no existe.');
      }

      if ($IssD_MEMBER == $ID_MEMBER) {
        die('0: No puedes banearte a ti mismo.');
      }

      if ($ID_MEMBER <> $notes) {
        if ($_POST['clave'] !== $clavsss) {
          die('0: Debes ingresar la clave correctamente.');
        }
      }

      $nullo = 'NULL';
      $expirate = $_POST['expiration'] ? 'expire_time = ' . ($_POST['expire_date'] * 86400 + time()) . ',' : 'expire_time = ' . $nullo . ',';

      db_query("
        UPDATE {$db_prefix}ban_groups
        SET
          reason = '$razon',
          editado_por = $ID_MEMBER,
          ip = '$memberIP',
          email = '$emailAddress',
          $expirate
          clave = '$clave_usuario'
        WHERE ID_BAN_GROUP = '$id'
        LIMIT 1", __FILE__, __LINE__);

      updateSettings(array('banLastUpdated' => time()));

      db_query("
        DELETE FROM {$db_prefix}log_online
        WHERE ID_MEMBER ='$IssD_MEMBER'
        LIMIT 1", __FILE__, __LINE__);
      die('1: Baneo modificado correctamente.');
    } else {
      $request352 = db_query("
        SELECT realName, memberIP, emailAddress
        FROM {$db_prefix}members
        WHERE ID_MEMBER = '$id'
        LIMIT 1", __FILE__, __LINE__);

      while ($rows = mysqli_fetch_assoc($request352)) {
        $name = $rows['realName'];
        $memberIP = $rows['memberIP'];
        $resultado = db_query("
          SELECT expire_time, name
          FROM {$db_prefix}ban_groups
          WHERE name = '$name'
          LIMIT 1", __FILE__, __LINE__);

        while ($ban = mysqli_fetch_array($resultado)) {
          $expirate = $ban['expire_time'] === null ? 0 : ($ban['expire_time'] < time() ? 1 : 0);

          if ($expirate) {
            db_query("
              DELETE FROM {$db_prefix}ban_groups
              WHERE name = '$name'
              LIMIT 1", __FILE__, __LINE__);
          }
        }

        $emailAddress = $rows['emailAddress'];
      }

      mysqli_free_result($request352);

      if (!$name) {
        die('0: El usuario especificado no existe.');
      }
      if ($name == $user_settings['realName']) {
        die('0: No puedes banearte a ti mismo.');
      }
      if ($name == 'rigo') {
        die('0: No puedes banear a este usuario.');
      }

      $request = db_query("
        SELECT name
        FROM {$db_prefix}ban_groups
        WHERE name = '$name'
        LIMIT 1", __FILE__, __LINE__);

      $ddd = mysqli_num_rows($request);

      if ($ddd) {
        die('0: Este usuario ya est&aacute; en la lista de baneados.<br /><b><a href="' . $boardurl . '/moderacion/edit-user/ban/buscar&usuario=' . $name . '&si=bloke">B&uacute;scalo</a></b>');
      }

      $expirate = $_POST['expiration'] ? ($_POST['expire_date'] * 86400 + time()) . ',' : '';
      $Sexpirate = $_POST['expiration'] ? ' expire_time,' : '';

      db_query("
        INSERT INTO {$db_prefix}ban_groups (name, ban_time, $Sexpirate reason, notes, clave, ip, email)
        VALUES (SUBSTRING('$name', 1, 20), " . time() . ", $expirate SUBSTRING('$razon', 1, 255), SUBSTRING('$ID_MEMBER', 1, 150), '$clave_usuario', '$memberIP','$emailAddress')", __FILE__, __LINE__);

      db_query("
        DELETE FROM {$db_prefix}log_online
        WHERE ID_MEMBER = $id
        LIMIT 1", __FILE__, __LINE__);

      die('1: Baneado correctamente.');
    }
  } else {
    die('0: Error.');
  }
} else {
  die();
}

?>