<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $user_info, $sourcedir, $db_prefix, $boardurl;

if ($user_info['is_admin']) {
  $_GET['u'] = isset($_GET['u']) ? (int) $_GET['u'] : '';

  if (!empty($_GET['u'])) {
    $request = db_query("
      SELECT emailAddress, realName
      FROM {$db_prefix}members
      WHERE ID_MEMBER = '{$_GET['u']}'
      LIMIT 1", __FILE__, __LINE__);

    if (mysqli_num_rows($request) == 0) {
      die();
    }

    $row = mysqli_fetch_assoc($request);
    $email = $row['emailAddress'];

    mysqli_free_result($request);

    updateMemberData($_GET['u'], array('is_activated' => 1, 'validation_code' => "''"));
    require_once($sourcedir . '/Subs-Post.php');
    sendmail(
      $email, 'Cuenta re-activada',
      "Su cuenta en ' . $boardurl . ' fue reactivada.\n\n" . 
      "Nombre de usuario: $email\nContrase&ntilde;a: ****** <span style=\"font-size: 8px; color: grey;\">(Oculta por seguridad)</span>\n\n" . 
      "Si tiene problemas con su cuenta, no dude en contactarnos: <a href=\"$boardurl/contactanos/\">$boardurl/contactanos/</a>"
    );
  }
}

die();

?>