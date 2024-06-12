<?php
//Pagina de Rodrigo Zaupa (rigo@casitaweb.net)
if (!defined('CasitaWeb!-PorRigo'))die(base64_decode("d3d3LmNhc2l0YXdlYi5uZXQgLSByaWdv"));

function Comunidades() {
  global $context, $user_settings, $sourcedir, $txt,$db_prefix,$urlSep;

  loadTemplate('Comunidades');

  $_GET[$urlSep] = str_replace('/', '', $_GET[$urlSep]);
  $context['comuid'] = isset($_GET['id']) ? str_replace('/', '', $_GET['id']) : '';

  $context['all_pages'] = array(
    'index' => 'intro',
    'articulo' => 'articulo',
    'ctema' => 'ctema',
    'etema' => 'etema',
    'ecomunidad' => 'ecomunidad',
    'denunciarCom' => 'denunciarCom',
    'crearcomunidad' => 'crearcomunidad',
    'dir' => 'directorios',
    'tops' => 'tops',
    'buscar' => 'buscar',
  );
    
  if (!isset($_GET['m']) || !isset($context['all_pages'][$_GET['m']])) {
    $_GET['m'] = 'index';
  }

  $context['current_page'] = $_GET['m'];
  $context['sub_template'] = $context['all_pages'][$context['current_page']];

  if ($context['current_page']== 'ecomunidad') {
    is_not_guest();

    $id = seguridad($_GET['comun']);

    if (empty($id)) {
      fatal_error('Debes seleccionar una comunidad.');
    }

    $rs = db_query("
      SELECT c.id, c.nombre, c.descripcion, c.acceso, c.permiso, c.url, c.imagen, c.categoria, c.aprobar, ca.url AS urlCat, ca.nombre AS nombreCat
      FROM {$db_prefix}comunidades AS c, {$db_prefix}comunidades_categorias AS ca
      WHERE c.url = '$id'
      AND c.categoria = ca.url
      LIMIT 1", __FILE__, __LINE__);

    while ($row = mysqli_fetch_assoc($rs)) {
      $context['COMediTidvb'] = $row['id'];
      $context['COMediTnombre'] = nohtml(nohtml2($row['nombre']));
      $context['COMediTdescripcion'] = nohtml(nohtml2($row['descripcion']));
      $context['COMediTacceso'] = $row['acceso'];
      $context['COMediTpermiso'] = $row['permiso'];
      $context['COMediTurl'] = $row['url'];
      $context['COMediTaprobar'] = $row['aprobar'];
      $context['COMediTimagen'] = nohtml(nohtml2($row['imagen']));
      $context['COMediTcategoria'] = $row['categoria'];
      $context['COMediTnombreCat'] = $row['nombreCat'];
      $context['COMediTurlCat'] = $row['urlCat'];
    }

    $context['page_title'] = 'Editar comunidad';
    $context['COMediTidvb'] = isset($context['COMediTidvb']) ?  $context['COMediTidvb'] : '';
    if (empty($context['COMediTidvb'])) {
      fatal_error('Debes seleccionar una comunidad.');
    }

    require_once($sourcedir . '/FuncionesCom.php');
    permisios($context['COMediTidvb']);

    if (empty($context['permisoCom'])) {
      fatal_error('No tenes permiso para editar esta comunidad.');
    }
  } else if($context['current_page']== 'articulo') {
    $context['coMid']= isset($_GET['tema']) ? (int) $_GET['tema'] : 0;

    if (empty($_SESSION['idddd'][$context['coMid']])) {
      db_query("
        UPDATE {$db_prefix}comunidades_articulos
        SET visitas = visitas+1
        WHERE id = '{$context['coMid']}'
        LIMIT 1", __FILE__, __LINE__);

      $_SESSION['idddd'][$context['coMid']] = '1';
   }

    $rs44=db_query("
    SELECT a.titulo,a.cuerpo,a.calificacion,a.visitas,c.nombre,c.url,a.nocoment,a.stiky, a.eliminado,b.url as url2,
    b.nombre as cnam, m.avatar,a.creado,c.id,m.realName,a.id_user
    FROM ({$db_prefix}members AS m)
    INNER JOIN {$db_prefix}comunidades_articulos AS a ON a.id='{$context['coMid']}' AND a.id_user=m.ID_MEMBER
    INNER JOIN {$db_prefix}comunidades AS c ON a.id_com=c.id
    INNER JOIN {$db_prefix}comunidades_categorias AS b ON c.categoria=b.url
    LIMIT 1",__FILE__, __LINE__);
    while ($row=mysqli_fetch_assoc($rs44)){
    $context['coMtitulo']=nohtml(nohtml2($row['titulo']));
    $context['coMtitulo2']=$row['titulo'];
    $context['coMcuerpo']=parse_bbc(nohtml(nohtml2($row['cuerpo'])));
    $context['coMdasdasd']=$row['id'];
    $context['coMeliminado']=$row['eliminado'];
    $context['coMurl']=$row['url'];
    $context['coMrealName']=$row['realName'];
    $context['coMimg']=nohtml($row['avatar']);
    $context['coMvbvbvki']=$row['id_user'];
    $context['coMvisitas']=$row['visitas'];
    $context['coMcreado']=hace($row['creado']);
    $context['coMstiky']=$row['stiky'];
    $context['coMnocoment']=$row['nocoment'];
    $context['coMcalificacion']=$row['calificacion'];
    $context['coMnombre']=$row['nombre'];
    $context['coMurl2']=$row['url2'];
    $context['coMcnam']=$row['cnam'];}
    mysqli_free_result($rs44);
    $context['coMdasdasd']=isset($context['coMdasdasd']) ? $context['coMdasdasd'] : '';
    $titulo=$context['coMtitulo'] ? $context['coMtitulo'] : $txt[18];
    $context['page_title'] = $titulo;

    include($sourcedir.'/FuncionesCom.php');
    baneadoo($context['coMdasdasd']);
    permisios($context['coMdasdasd']);
    acces($context['coMdasdasd']);
    bloqueado($context['coMdasdasd']);
    miembro($context['coMdasdasd']);

    if(empty($context['coMdasdasd'])){fatal_error('Este tema esta eliminado.');}

    if($context['coMeliminado']){
    if($context['permisoCom']=='1' || $context['permisoCom']=='3' || $context['permisoCom']=='2'){
    $styleboteditartema=' display:none; ';
    $styleboteliminartema=' display:none; ';
    $stylebotreactivartema=' display:inline; ';  
    $context['postEliAdm']='<div class="noesta" style="margin-bottom:8px;width:922px;" id="tel">Este tema esta eliminado.</div>';}
    else{fatal_error('Este tema esta eliminado.');}}
    else{$styleboteditartema=' display:inline; ';
    $styleboteliminartema=' display:inline; ';
    $stylebotreactivartema=' display:none; ';}
    $context['botonesCom']='<input class="login" style="font-size: 11px;'.$styleboteditartema.'" value="Editar tema" title="Editar tema" onclick="location.href=\'/comunidades/editar-tema/'.$context['coMid'].'\'" type="button" id="edt" /> <input class="login" style="font-size: 11px;'.$styleboteliminartema.'" value="Eliminar tema" title="Eliminar tema" onclick="if (!confirm(\'\xbfEstas seguro que desea eliminar este tema?\')) return false;location.href=\'/web/cw-comunidadesEliTem.php?id='.$context['coMid'].'\'" type="button" id="elt" /> <input class="login" style="font-size: 11px;'.$stylebotreactivartema.'" value="Reactivar tema" title="Reactivar tema" onclick="if (!confirm(\'\xbfEstas seguro que desea reactivar este tema?\')) return false; reacTemas(\''.$context['coMid'].'\');" type="button" id="rect" />';
  } else if($context['current_page']== 'index') {
    require_once($sourcedir . '/FuncionesCom.php');

    if ($context['comuid']) {
      // var_dump($context['comuid']);
      $rs = db_query("
        SELECT b.nombre, b.url, c.nombre AS nom2, c.url as url2, c.descripcion, c.fecha_inicio, c.imagen, c.id, c.UserName, c.bloquear
        FROM ({$db_prefix}comunidades_categorias AS b, {$db_prefix}comunidades AS c)
        WHERE c.url = '{$context['comuid']}'
        AND c.categoria = b.id
        LIMIT 1", __FILE__, __LINE__);

      while ($row = mysqli_fetch_assoc($rs)) {
        $context['nombrecat'] = nohtml(nohtml2($row['nom2']));
        $context['url2222'] = $row['url2'];
        $context['ivvvaar'] = $row['bloquear'];
        $context['ddddsaaat'] = $row['id'];
        $context['UserName'] = $row['UserName'];
        $context['descecat'] = nohtml($row['descripcion']);   
        $context['fecha'] = timeformat($row['fecha_inicio']);  
        $context['cat'] = nohtml($row['nombre']); 
        $context['caturl'] = nohtml($row['url']);
      }

      $context['ddddsaaat'] = isset($context['ddddsaaat']) ? $context['ddddsaaat'] : '';

      if (empty($context['ddddsaaat'])) {
        fatal_error('Esta comunidad no existe.');
      }

      bloqueado($context['ddddsaaat']);
      baneadoo($context['ddddsaaat']);
      miembro($context['ddddsaaat']);
      permisios($context['ddddsaaat']);
      acces($context['ddddsaaat']);
    }

    $titulo = isset($context['nombrecat']) ? $context['nombrecat'] : $txt[18];
    $context['page_title'] = $titulo;
  } else {
    $context['page_title'] = $txt[18];
  }
}

?>