<?php
// Página de Rodrigo Zaupa (rigo@casitaweb.net)
if (!defined('CasitaWeb!-PorRigo')) {
  die(base64_decode('d3d3LmNhc2l0YXdlYi5uZXQgLSByaWdv'));
}

function GalleryMain() {
  global $context;

  loadtemplate('Gallery');

  if (loadlanguage('Gallery') == false) {
    loadLanguage('Gallery', 'english');
  }

  $subActions = array(
    'main' => 'main',
    '45844' => 'MyImages',
    'ver' => 'ViewPicture',
    'eliminar545' => 'DeletePicture',
    'eliminar' => 'DeletePicture2',
    'editar' => 'EditPicture',
    'edit2' => 'EditPicture2',
  );

  @$sa = seguridad($_GET['sa']);

  if (!empty($subActions[$sa])) {
    $subActions[$sa]();
  } else {
    main();
  }
}

function main() {
  global $context, $scripturl, $mbname, $txt, $db_prefix, $modSettings, $user_info;

  $us = isset($_GET['usuario']) ? seguridad($_GET['usuario']) : '';

  if (empty($us))
    fatal_error($txt['gallery_error_no_user_selected']);

  $dbresult = db_query("
    SELECT memberName, realName,ID_MEMBER
    FROM {$db_prefix}members
    WHERE realName = '$us'
    LIMIT 1", __FILE__, __LINE__);

  $row = mysqli_fetch_assoc($dbresult);
  $context['gallery_userid'] = $row['ID_MEMBER'];

  if (!$context['gallery_userid']) {
    fatal_error('Este usuario no existe.-', false);
  }

  $context['gallery_usergallery_name'] = $row['realName'];
  $context['page_title'] = $txt[18];
  $context['sub_template'] = 'galeria';
  $context['sub_template'] = 'main';
  $context['gallery_cat_name'] = ' ';

  mysqli_free_result($dbresult);
}

function ViewPicture() {
  global $context, $db_prefix, $user_info, $user_settings, $txt;

  loadlanguage('Post');

  $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

  if (empty($id)) {
    fatal_error('Esta imagen no existe.', false);
  }

  $context['gallery_pic_id'] = $id;

  if (empty($user_info['is_guest'])) {
    $context['pdia'] = $user_settings['puntos_dia'];
    $context['leecher'] = $user_settings['ID_POST_GROUP'] == 4;
  } else {
    $context['leecher'] = 1;
  }

  $_SESSION['imgVISTA_' . $id] = isset($_SESSION['imgVISTA_' . $id]) ? '1' : '0';

  if (empty($_SESSION['imgVISTA_' . $id])) {
    $dbresult = db_query("
      UPDATE {$db_prefix}gallery_pic 
      SET views = views + 1 
      WHERE ID_PICTURE = $id
      LIMIT 1", __FILE__, __LINE__);

      $_SESSION['imgVISTA_' . $id] = '1';
  }

  $dbresult = db_query("
    SELECT m.ID_MEMBER, p.ID_PICTURE, p.views, p.title, p.filename, p.date, p.puntos
    FROM {$db_prefix}gallery_pic AS p
    LEFT JOIN {$db_prefix}members AS m ON p.ID_MEMBER = m.ID_MEMBER
    WHERE p.ID_PICTURE = $id
    LIMIT 1", __FILE__, __LINE__);

  $row = mysqli_fetch_assoc($dbresult);
  $title = nohtml2(nohtml($row['title']));

  $context['gallery_pic'] = array(
    'ID_PICTURE' => $row['ID_PICTURE'],
    'ID_MEMBER' => $row['ID_MEMBER'],
    'views' => $row['views'],
    'puntos' => $row['puntos'],
    'title' => $title,
    'filename' => nohtml2(nohtml($row['filename'])),
    'date' => $row['date']
  );

  mysqli_free_result($dbresult);

  $title = isset($title) ? $title : '';
  $ds = isset($row['ID_PICTURE']) ? 1 : 0;

  if (empty($ds)) {
    fatal_error('Esta imagen no existe.', false);
  }

  if ($user_info['smiley_set'] != 'none') {
    $request = db_query("
      SELECT code, filename, description, smileyRow, hidden
      FROM {$db_prefix}smileys
      WHERE hidden IN (0, 2)
      ORDER BY smileyRow, smileyOrder", __FILE__, __LINE__);

    while ($row = mysqli_fetch_assoc($request)) {
      $context['smileys'][empty($row['hidden']) ? 'postform' : 'popup'][$row['smileyRow']]['smileys'][] = $row;
    }

    mysqli_free_result($request);
  }

  $context['sub_template'] = 'view_picture';

  if ($title) {
    $context['page_title'] = $title;
  } else {
    $context['page_title'] = $txt[18];
  }
}

function EditPicture() {
  global $context, $user_settings, $db_prefix, $txt;

  if ($context['user']['is_guest']) {
    fatal_error('Funcionalidad exclusiva de usuarios registrados.', false);
  }

  if (!empty($context['user']['is_guest'])) {
    $context['idgrup'] = $user_settings['ID_POST_GROUP'];
    $context['leecher'] = $user_settings['ID_POST_GROUP'] == 4;
    $context['novato'] = $user_settings['ID_POST_GROUP'] == 5;
    $context['buenus'] = $user_settings['ID_POST_GROUP'] == 6;
  }

  $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

  if (empty($id)) {
    fatal_error('Debes seleccionar una imagen', false);
  }

  $dbresult = db_query("
    SELECT filename, title, ID_PICTURE
    FROM {$db_prefix}gallery_pic
    WHERE ID_PICTURE = $id
    LIMIT 1", __FILE__, __LINE__);

  $row = mysqli_fetch_assoc($dbresult);

  $context['gallery_pic'] = array(
    'filename' => $row['filename'],
    'title' => $row['title']
  );

  mysqli_free_result($dbresult);

  if (empty($row['ID_PICTURE'])) {
    fatal_error('Esta imagen no existe o fue eliminada.', false);
  }

  $context['page_title'] = $txt[18];
  $context['sub_template'] = 'edit_picture';
}

?>