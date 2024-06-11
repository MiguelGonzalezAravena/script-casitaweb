<?php
// Página de Rodrigo Zaupa (rigo@casitaweb.net)
if (!defined('CasitaWeb!-PorRigo')) {
  die(base64_decode('d3d3LmNhc2l0YXdlYi5uZXQgLSByaWdv'));
}

function EditarPost() {
  global $txt, $context, $scripturl, $db_prefix, $user_info, $ID_MEMBER;

  if ($user_info['is_guest']) {
    is_not_guest();
    die();
  }

  LoadTemplate('EditarPost');
  loadLanguage('Post');

  $id = isset($_GET['post']) ? (int) $_GET['post'] : 0;
  $request = db_query("
    SELECT 
      ID_BOARD, ID_TOPIC, hiddenOption, sticky, smileysEnabled,
      anuncio, color, subject, body, ID_MEMBER, eliminado
    FROM {$db_prefix}messages
    WHERE ID_TOPIC = $id
    " . (!empty($user_info['is_mods']) || !empty($user_info['is_admin']) ? '' : " AND ID_MEMBER = $ID_MEMBER") . "
    LIMIT 1", __FILE__, __LINE__);

  while ($row = mysqli_fetch_assoc($request)) {
    $row['body'] = censorText($row['body']);
    $row['subject'] = censorText($row['subject']);
    $context['id_cat'] = $row['ID_BOARD'];
    $context['id_post'] = $row['ID_TOPIC'];
    $context['id_user'] = $row['ID_MEMBER'];
    $context['titulo'] = censorText($row['subject']);
    $context['mensaje'] = str_replace("<br />", "\n", $row['body']);
    $context['privado'] = $row['hiddenOption'];
    $context['sticky'] = $row['sticky'];
    $context['locked'] = $row['smileysEnabled'];
    $context['anuncio'] = $row['anuncio'];
    $context['color'] = $row['color'];
    $context['eliminado'] = $row['eliminado'];
  }

  $context['id_post'] = isset($context['id_post']) ? $context['id_post'] : '';
  $context['eliminado'] = isset($context['eliminado']) ? $context['eliminado'] : '';

  if (empty($context['id_post']) || $context['eliminado']) {
    fatal_error('No tienes permisos para editar este post.');
  }

  $context['page_title'] = 'Editar post';
}

?>