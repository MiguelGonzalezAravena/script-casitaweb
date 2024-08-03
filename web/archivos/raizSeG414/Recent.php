<?php
// Página de Rodrigo Zaupa (rigo@casitaweb.net)
if (!defined('CasitaWeb!-PorRigo')) {
  die(base64_decode('d3d3LmNhc2l0YXdlYi5uZXQgLSByaWdv'));
}

function RecentPosts() {
  global $txt, $db_prefix, $context, $boardurl;

  loadTemplate('Recent');
  $context['page_title'] = $txt[214];

  //if($context['user']['id']=='1'){PostAccionado();}
  $context['catccdd'] = isset($_GET['catID']) ? seguridad(str_replace('/', '', $_GET['catID'])) : NULL;
  $RegistrosAMostrar = 50;
  $NroRegistros = 2500;
  $pags33 = isset($_GET['pag']) ? (int) $_GET['pag'] : '';
  $per = $pags33 < 1 ? 1 : $pags33;

  if (isset($per)) {
    $RegistrosAEmpezar = ($per - 1) * $RegistrosAMostrar;
    $context['PagAct'] = $per;
  } else {
    $RegistrosAEmpezar = 0;
    $context['PagAct'] = 1;
  }

  $context['PagAnt'] = $context['PagAct']-1;
  $context['PagSig'] = $context['PagAct']+1;
  $context['PagUlt'] = $NroRegistros / $RegistrosAMostrar;
  $Res = $NroRegistros % $RegistrosAMostrar;

  if ($Res > 0) {
    $context['PagUlt'] = floor($context['PagUlt']) + 1;
  }

  // Últimos posts
  $rs = db_query("
    SELECT m.subject, b.description, m.hiddenOption, b.ID_BOARD, m.ID_BOARD, m.ID_TOPIC, b.name
    FROM {$db_prefix}messages AS m
    INNER JOIN {$db_prefix}boards as b ON m.ID_BOARD = b.ID_BOARD
    AND m.eliminado = 0
    " . (empty($context['catccdd']) ? '' : " AND b.description='{$context['catccdd']}'") . "
    ORDER BY m.ID_TOPIC DESC
    LIMIT $RegistrosAEmpezar, $RegistrosAMostrar", __FILE__, __LINE__);

  // Posts
  $context['posts'] = array();

  while ($row = mysqli_fetch_assoc($rs)) {
    $context['posts'][] = array(
      'id' => $row['ID_TOPIC'],
      'titulo' => censorText($row['subject']),
      'ocultar' => $row['hiddenOption'],
      'id_cat' => $row['ID_BOARD'],
      'description' => $row['description'],
      'name_cat' => $row['name']
    );
  }

  mysqli_free_result($rs);

  // Stickys
  $rsdd = db_query("
    SELECT p.ID_TOPIC, p.subject, b.ID_BOARD, p.hiddenOption, p.color, b.name, b.description
    FROM {$db_prefix}messages AS p
    INNER JOIN {$db_prefix}boards AS b ON b.ID_BOARD = p.ID_BOARD
    AND p.eliminado = 0
    AND p.sticky = 1
    " . (empty($context['catccdd']) ? '' : " AND b.description = '{$context['catccdd']}'") . "
    ORDER BY p.ID_TOPIC DESC 
    LIMIT 7",__FILE__, __LINE__);

  $context['sticky'] = array();

  while ($row = mysqli_fetch_assoc($rsdd)) {
    $context['sticky'][] = array(
      'id' => $row['ID_TOPIC'],
      'titulo' => censorText($row['subject']),
      'description' => $row['description'],
      'ocultar' => $row['hiddenOption'],
      'id_cat' => $row['ID_BOARD'],
      'name' => $row['name'],
      'color' => $row['color']
    );
  }

  mysqli_free_result($rsdd);

  // Últimas imágenes
  $request = db_query("
    SELECT ID_PICTURE, title
    FROM {$db_prefix}gallery_pic
    ORDER BY ID_PICTURE DESC
    LIMIT 10", __FILE__, __LINE__);

  $context['ultimas_img'] = array();

  while ($row = mysqli_fetch_assoc($request)) {
    $context['ultimas_img'][] = array(
      'id' => $row['ID_PICTURE'],
      'titulo' => $row['title']
    );
  }

  mysqli_free_result($request);

  // Usuarios de la semana
  $starttime = mktime(0, 0, 0, date("n"), date("j"), date("Y")) - (date("N") * 3600 * 24);
  $starttime = forum_time(false, $starttime);
  $request = db_query("
    SELECT me.ID_MEMBER, me.memberName, me.realName, COUNT(*) AS count_posts
    FROM {$db_prefix}messages AS m
    LEFT JOIN {$db_prefix}members AS me ON me.ID_MEMBER = m.ID_MEMBER
    WHERE m.posterTime > " . $starttime . "
    AND m.ID_MEMBER != 0
    AND m.eliminado = 0
    GROUP BY me.ID_MEMBER
    ORDER BY count_posts DESC
    LIMIT 10", __FILE__, __LINE__);

  $context['top_posters_week'] = array();
  $max_num_posts = 1;

  while ($row_members = mysqli_fetch_assoc($request)) {
    $context['top_posters_week'][] = array(
      'name' => $row_members['realName'],
      'id' => $row_members['ID_MEMBER'],
      'num_posts' => $row_members['count_posts'],
      'href' => $boardurl . '/perfil/' . $row_members['memberName'],
      'link' => '<a href="' . $boardurl . '/perfil/' . $row_members['memberName'] . '">' . $row_members['realName'] . '</a>'
    );

    if ($max_num_posts < $row_members['count_posts']) {
      $max_num_posts = $row_members['count_posts'];
    }
  }

  mysqli_free_result($request);

  // Top posts de la semana
  $tiempo = mktime(0, 0, 0, date("n"), date("j"), date("Y")) - (date("N")*3600*24);
  $tiempo = forum_time(false, $tiempo);
  $request = db_query("
    SELECT m.id_post, me.ID_TOPIC, b.description, me.subject, m.fecha, SUM(m.cantidad) as count_posts
    FROM ({$db_prefix}puntos AS m, {$db_prefix}messages AS me, {$db_prefix}boards AS b)
    WHERE me.ID_TOPIC = m.id_post
    AND me.ID_BOARD = b.ID_BOARD
    AND m.fecha > " . $tiempo . "
    AND m.id_post != 0
    AND me.eliminado = 0
    GROUP BY me.ID_TOPIC
    ORDER BY count_posts DESC
    LIMIT 15", __FILE__, __LINE__);

  $context['post_semana'] = array();
  $max_num_posts = 1;

  while ($row_members = mysqli_fetch_assoc($request)) {
    $context['post_semana'][] = array(
      'ID_TOPIC' => $row_members['ID_TOPIC'],
      'description' => $row_members['description'],
      'subject' => censorText($row_members['subject']),
      'num_posts' => $row_members['count_posts'],
    );

    if ($max_num_posts < $row_members['count_posts']) {
      $max_num_posts = $row_members['count_posts'];
    }
  }

  mysqli_free_result($request);

  // Usuarios con más posts
  $members_result = db_query("
    SELECT t.ID_MEMBER,COUNT(u.ID_MEMBER) as Cuenta,u.realName
    From ({$db_prefix}members as u, {$db_prefix}messages as t)
    WHERE t.ID_MEMBER = u.ID_MEMBER AND t.eliminado=0
    GROUP BY u.ID_MEMBER
    ORDER BY Cuenta DESC 
    LIMIT 10", __FILE__, __LINE__);
  $context['top_starters'] = array();
  while ($row_members = mysqli_fetch_assoc($members_result)) {
    $context['top_starters'][] = array(
      'realName' => $row_members['realName'],
      'cuenta' => $row_members['Cuenta']
    );
  }

  mysqli_free_result($members_result);
}

?>