<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $context, $txt, $sourcedir, $ID_MEMBER, $scripturl, $boarddir, $user_settings, $user_info, $db_prefix, $modSettings;

$id = isset($_REQUEST['id']) ? (int) $_REQUEST['id'] : 0;
$causa = isset($_POST['causa']) ? seguridad($_POST['causa']) : '';

if ($user_info['is_guest']) {
  fatal_error('Funcionalidad exclusiva de usuarios registrados.');
}

require_once($sourcedir . '/Subs-Post.php');

if (empty($id)) {
  fatal_error($txt['gallery_error_no_pic_selected']);
}

$dbresult = db_query("
  SELECT ID_PICTURE, filename, title, ID_MEMBER, puntos
  FROM {$db_prefix}gallery_pic
  WHERE ID_PICTURE = $id
  LIMIT 1", __FILE__, __LINE__);

$row = mysqli_fetch_assoc($dbresult);
$memID = $row['ID_MEMBER'];
$title = censorText($row['title']);
$puntosdados = $row['puntos'];

mysqli_free_result($dbresult);

if (empty($memID)) {
  fatal_error($txt['gallery_error_no_pic_selected']);
}

if (($user_info['is_admin'] || $user_info['is_mods']) || $ID_MEMBER == $memID) {
  if ($puntosdados) {
    db_query("
      UPDATE {$db_prefix}members
      SET posts = posts - $puntosdados
      WHERE ID_MEMBER = $memID", __FILE__, __LINE__);
  }

  db_query("
    DELETE FROM {$db_prefix}gallery_comment
    WHERE ID_PICTURE = $id
    LIMIT 1", __FILE__, __LINE__);

  db_query("
    DELETE FROM {$db_prefix}gallery_pic
    WHERE ID_PICTURE = $id
    LIMIT 1", __FILE__, __LINE__);

  $datosmem = db_query("
    SELECT realName, recibirmail
    FROM {$db_prefix}members
    WHERE ID_MEMBER = $memID
    LIMIT 1", __FILE__, __LINE__);

  $data = mysqli_fetch_assoc($datosmem);
  $ser = $data['realName'];
  $remail = $data['recibirmail'];

  if (($user_info['is_admin'] || $user_info['is_mods']) && $memID != $user_settings['ID_MEMBER']) {
    if (empty($causa)) {
      fatal_error('No agregaste la causa de la eliminaci&oacute;n.');
    }

    logAction('remove', array('Imagen' => $title . ' (ID: ' . $id . ')', 'member' => $memID, 'causa' => $causa));

    if ($remail == 1) {
      if (!empty($memID)) {
        $pmfrom = array(
          'id' => $ID_MEMBER,
          'name' => $user_settings['realName'],
          'username' => $user_settings['realName']
        );

        $titulo = 'Imagen eliminada: ' . censorText($title);
        $titulo2 = censorText($title);
        $causa = censorText($causa);
        $message = 'Hola!

Lamento contarte que tu imagen titulada [b]' . $titulo2 . '[/b] ha sido eliminada.

Causa: [b]' . $causa . '[/b]

Para acceder al protocolo, presiona [asd1256as4867cxc8c7a8xc7asd16a8e7a56s4da65s4d68as7da54d564dcv787v777v7v7v7v7v7as7eelprotocolopm=protocolo/][b]este enlace[/b][/asd1256as4867cxc8c7a8xc7asd16a8e7a56s4da65s4d68as7da54d564dcv787v777v7v7v7v7v7as7eelprotocolopm].

&iexcl;Muchas gracias por entender!';
        sendpm($titulo, $message, $memID, 1);
      }
    }
  }
}

header('Location: ' . $boardurl . '/imagenes/' . $ser);

?>