<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $user_info, $db_prefix, $user_settings, $ID_MEMBER;

if ($user_info['is_guest']) {
  fatal_error('Faltan datos.');
}

ignore_user_abort(true);
@set_time_limit(300);

$id = isset($_POST['comun']) ? seguridad($_POST['comun']) : '';

if (!$id) {
  fatal_error('Debes seleccionar una comunidad.');
}

$request = db_query("
  SELECT c.nombre, b.rango, c.id, c.categoria, c.url
  FROM {$db_prefix}comunidades_miembros AS b, {$db_prefix}comunidades AS c
  WHERE c.url = '$id'
  AND c.id = b.id_com
  AND b.id_user = {$user_settings['ID_MEMBER']}
  AND c.bloquear = 0
  LIMIT 1", __FILE__, __LINE__);

$row = mysqli_fetch_assoc($request);
$cat = seguridad(nohtml($row['nombre']));
$rango = $row['rango'];
$url = $row['url'];
$id_comunidad = $row['id'];
$id_categoria = $row['categoria'];

mysqli_free_result($request);

if (!$cat) {
  fatal_error('No eres miembro de esta comunidad.' . $ID_MEMBER);
}

require_once($sourcedir . '/FuncionesCom.php');
baneadoo($id_comunidad);
acces($id_comunidad);

if ($context['puedo'] == 1 || $context['puedo'] == 3) {
  // Tiempo agregar post
  $request = db_query("
    SELECT creado
    FROM {$db_prefix}comunidades_articulos
    WHERE id_user = $ID_MEMBER
    AND id_com = $id_comunidad
    ORDER BY id DESC
    LIMIT 1", __FILE__, __LINE__);

  $row = mysqli_fetch_assoc($request);
  $creado = $row['creado'];

  mysqli_free_result($request);

  if ($creado > time() - 30) {
    fatal_error('No es posible agregar temas con tan poca diferencia de tiempo.<br />Vuelva a intentar en segundos.');
  }

  if (!$rango) {
    $request = db_query("
      SELECT permiso
      FROM {$db_prefix}comunidades
      WHERE url = '$id'
      LIMIT 1", __FILE__, __LINE__);

    $row = mysqli_fetch_assoc($request);
    $rangofinal = $row['permiso'];

    mysqli_free_result($request);

    if ($rangofinal != 3) {
      fatal_error('No tienes permiso para postear en esta comunidad.');
    }
  } else {
    $rangofinal = 4;
  }

  if ($rangofinal == 4) {
    $stiky = $_POST['sticky'] ? 1 : 0;
  } else {
    $stiky = 0;
  }

  $nocoment = $_POST['nocoment'] ? 1 : 0;
  $titulo = isset($_POST['titulo']) ? seguridad($_POST['titulo']) : '';
  $cuerpo = isset($_POST['cuerpo_comment']) ? seguridad($_POST['cuerpo_comment']) : '';

  if (!$titulo) {
    fatal_error('Debes agregar un t&iacute;tulo.');
  }

  if (strlen($titulo) < 3) {
    fatal_error('El t&iacute;tulo no puede tener menos de <b>3 letras</b>.');
  }

  if (strlen($titulo) >= 61) {
    fatal_error('El t&iacute;tulo no puede tener m&aacute;s de <b>60 letras</b>.');
  }

  if (!$cuerpo) {
    fatal_error('Debes agregar un contenido.');
  }

  if (strlen($cuerpo) <= 15) {
    fatal_error('El contenido del tema no puede tener menos de <b>15 letras</b>.');
  }

  if (strlen($cuerpo) > $modSettings['max_messageLength']) {
    fatal_error('El contenido del tema no puede tener m&aacute;s de <b>' . $modSettings['max_messageLength'] . ' letras</b>.');
  }

  db_query("
    INSERT INTO {$db_prefix}comunidades_articulos (id_user, id_com, titulo, cuerpo, nocoment, stiky, UserName, categoria) 
    VALUES ($ID_MEMBER, $id_comunidad, SUBSTRING('$titulo', 1, 100), SUBSTRING('$cuerpo', 1, 60000), $nocoment, $stiky, '{$user_settings['realName']}', $id_categoria)", __FILE__, __LINE__);
      
  db_query("
    UPDATE {$db_prefix}comunidades
    SET articulos = articulos + 1
    WHERE id = $id_comunidad
    LIMIT 1", __FILE__, __LINE__);

  header('Location: ' . $boardurl . '/comunidades/' . $url . '/');
} else {
 fatal_error('No tienes permisos suficientes.');
}

?>