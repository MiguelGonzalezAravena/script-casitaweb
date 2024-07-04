<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $context, $db_prefix, $user_settings, $user_info, $ID_MEMBER, $boardurl;

if ($user_info['is_guest']) {
  fatal_error('Funcionalidad exclusiva de usuarios registrados.');
}

$tipo = isset($_POST['tipo']) ? (int) $_POST['tipo'] : 1;
$profesion = isset($_POST['profesion']) ? seguridad($_POST['profesion']) : '';
$estudios = isset($_POST['estudios']) ? seguridad($_POST['estudios']) : '';
$empresa = isset($_POST['empresa']) ? seguridad($_POST['empresa']) : '';
$ingresos = isset($_POST['ingresos']) ? seguridad($_POST['ingresos']) : '';
$intereses_profesionales = isset($_POST['intereses_profesionales']) ? seguridad($_POST['intereses_profesionales']) : '';
$habilidades_profesionales = isset($_POST['habilidades_profesionales']) ? seguridad($_POST['habilidades_profesionales']) : '';
$me_gustaria = isset($_POST['me_gustaria']) ? seguridad($_POST['me_gustaria']) : '';
$hijos = isset($_POST['hijos']) ? seguridad($_POST['hijos']) : '';
$en_el_amor_estoy = isset($_POST['estado']) ? seguridad($_POST['estado']) : '';
$color_de_pelo = isset($_POST['pelo_color']) ? seguridad($_POST['pelo_color']) : '';
$color_de_ojos = isset($_POST['ojos_color']) ? seguridad($_POST['ojos_color']) : '';
$complexion = isset($_POST['fisico']) ? seguridad($_POST['fisico']) : '';
$mi_dieta_es = isset($_POST['dieta']) ? seguridad($_POST['dieta']) : '';
$tomo_alcohol = isset($_POST['tomo_alcohol']) ? seguridad($_POST['tomo_alcohol']) : '';
$fumo = isset($_POST['fumo']) ? seguridad($_POST['fumo']) : '';
$altura = isset($_POST['altura']) ? (int) $_POST['altura'] : null;
$peso = isset($_POST['peso']) ? (int) $_POST['peso'] : null;
$series_tv_favoritas = isset($_POST['series_tv_favoritas']) ? seguridad($_POST['series_tv_favoritas']) : '';
$musica_favorita = isset($_POST['musica_favorita']) ? seguridad($_POST['musica_favorita']) : '';
$deportes_y_equipos_favoritos = isset($_POST['deportes_y_equipos_favoritos']) ? seguridad($_POST['deportes_y_equipos_favoritos']) : '';
$libros_favoritos = isset($_POST['libros_favoritos']) ? seguridad($_POST['libros_favoritos']) : '';
$peliculas_favoritas = isset($_POST['peliculas_favoritas']) ? seguridad($_POST['peliculas_favoritas']) : '';
$comida_favorita = isset($_POST['comida_favorita']) ? seguridad($_POST['comida_favorita']) : '';
$mis_heroes_son = isset($_POST['mis_heroes_son']) ? seguridad($_POST['mis_heroes_son']) : '';
$mis_intereses = isset($_POST['mis_intereses']) ? seguridad($_POST['mis_intereses']) : '';
$hobbies = isset($_POST['hobbies']) ? seguridad($_POST['hobbies']) : '';

if (in_array($tipo, [1, 2, 3, 4])) {
  $request = db_query("
    SELECT id_user
    FROM {$db_prefix}infop
    WHERE id_user = $ID_MEMBER
    LIMIT 1", __FILE__, __LINE__);

  $rows = mysqli_num_rows($request);

  // Paso 1
  if ($tipo == 1) {
    // Registros existentes
    if ($rows > 0) {
      if (strlen($profesion) >= 50) {
        fatal_error('No puedes agregar una profesi&oacute;n con m&aacute;s de 50 letras.');
      }

      // Guardar profesión
      db_query("
        UPDATE {$db_prefix}infop
        SET profesion = '$profesion'
        WHERE id_user = $ID_MEMBER", __FILE__, __LINE__);

      // Guardar estudios
      if (in_array($estudios, getEstudios('keys'))) {
        db_query("
          UPDATE {$db_prefix}infop
          SET estudios = '$estudios'
          WHERE id_user = $ID_MEMBER", __FILE__, __LINE__);
      } else {
        fatal_error('Debes seleccionar un nivel de estudios que exista.');
      }

      if (strlen($empresa) >= 50) {
        fatal_error('No puedes agregar una empresa con m&aacute;s de 50 letras.');
      }

      // Guardar empresa
      db_query("
        UPDATE {$db_prefix}infop
        SET empresa = '$empresa'
        WHERE id_user = $ID_MEMBER", __FILE__, __LINE__);

      // Guardar ingresos
      if (in_array($ingresos, getIngresos('keys'))) {
        db_query("
          UPDATE {$db_prefix}infop
          SET nivel_de_ingresos = '$ingresos'
          WHERE id_user = $ID_MEMBER", __FILE__, __LINE__);
      } else {
        fatal_error('Debes seleccionar un nivel de ingresos que exista.');
      }

      // Guardar intereses profesionales
      db_query("
        UPDATE {$db_prefix}infop
        SET intereses_profesionales = '$intereses_profesionales'
        WHERE id_user = $ID_MEMBER", __FILE__, __LINE__);

      // Guardar habilidades profesionales
      db_query("
        UPDATE {$db_prefix}infop
        SET habilidades_profesionales = '$habilidades_profesionales'
        WHERE id_user = $ID_MEMBER", __FILE__, __LINE__);

      header('Location: ' . $boardurl . '/editar-apariencia/paso2/');
    } else {
      // Sin registros
      if (!in_array($estudios, getEstudios('keys'))) {
        fatal_error('Debes seleccionar un nivel de estudios que exista.');
      }

      if (!in_array($ingresos, getIngresos('keys'))) {
        fatal_error('Debes seleccionar un nivel de ingresos que exista.');
      }

      if (strlen($profesion) >= 50) {
        fatal_error('No puedes agrear una profesi&oacute;n con m&aacute;s de 50 letras.');
      }

      if (strlen($empresa) >= 50) {
        fatal_error('No puedes agrear una empresa con m&aacute;s de 50 letras.');
      }

      db_query("
        INSERT INTO {$db_prefix}infop (habilidades_profesionales, intereses_profesionales, nivel_de_ingresos, empresa, estudios, profesion, id_user)
        VALUES ('$habilidades_profesionales', '$intereses_profesionales', '$ingresos', '$empresa', '$estudios', '$profesion', $ID_MEMBER)", __FILE__, __LINE__);

      header('Location: ' . $boardurl . '/editar-apariencia/paso2/');
    }
  } else if ($tipo == 2) {
    // Paso 2
    // Registros existentes
    if ($rows > 0) {
      if (!in_array($me_gustaria, getMeGustarias('keys'))) {
        fatal_error('Debes seleccionar una opci&oacute;n de lo que te gustar&iacute;a hacer que exista.');
      }

      // Guardar me gustaría
      db_query("
        UPDATE {$db_prefix}infop
        SET me_gustaria = '$me_gustaria'
        WHERE id_user = $ID_MEMBER", __FILE__, __LINE__);

      if (!in_array($en_el_amor_estoy, getEstados('keys'))) {
        fatal_error('Debes seleccionar una opci&oacute;n sobre tu estado en el amor que exista.');
      }

      // Guardar estado en el amor
      db_query("
        UPDATE {$db_prefix}infop
        SET en_el_amor_estoy = '$en_el_amor_estoy'
        WHERE id_user = $ID_MEMBER", __FILE__, __LINE__);

      if (!in_array($hijos, getHijos('keys'))) {
        fatal_error('Debes seleccionar una opción sobre hijos que exista.');
      }

      // Guardar sobre hijos
      db_query("
        UPDATE {$db_prefix}infop
        SET hijos = '$hijos'
        WHERE id_user = $ID_MEMBER", __FILE__, __LINE__);

      header('Location: ' . $boardurl . '/editar-apariencia/paso3/');
    } else {
      // Sin registros
      if (!in_array($me_gustaria, getMeGustarias('keys'))) {
        fatal_error('Debes seleccionar una opci&oacute;n de lo que te gustar&iacute;a hacer que exista.');
      }

      if (!in_array($en_el_amor_estoy, getEstados('keys'))) {
        fatal_error('Debes seleccionar una opci&oacute;n sobre tu estado en el amor que exista.');
      }

      if (!in_array($hijos, getHijos('keys'))) {
        fatal_error('Debes seleccionar una opción sobre hijos que exista.');
      }
      
      db_query("
        INSERT INTO {$db_prefix}infop (en_el_amor_estoy, me_gustaria, hijos, id_user)
        VALUES ('$en_el_amor_estoy', '$me_gustaria', '$hijos', $ID_MEMBER)", __FILE__, __LINE__);

      header('Location: ' . $boardurl . '/editar-apariencia/paso3/');
    }
  } else if ($tipo == 3) {
    // Paso 3
    // Registro existente
    if ($rows > 0) {
      if (strlen($altura) >= 4) {
        fatal_error('No puede haber m&aacute;s de 3 n&uacute;meros en tu altura.');
      }

      db_query("
        UPDATE {$db_prefix}infop
        SET altura = '$altura'
        WHERE id_user = $ID_MEMBER", __FILE__, __LINE__);

      if (strlen($peso) >= 4) {
        fatal_error('No puede haber m&aacute;s de 3 n&uacute;meros en tu peso.');
      }

      db_query("
        UPDATE {$db_prefix}infop
        SET peso = '$peso'
        WHERE id_user = $ID_MEMBER", __FILE__, __LINE__);

      if (!in_array($fumo, getFumos('keys'))) {
        fatal_error('Debes seleccionar una opci&oacute;n de fumador que exista.');
      }

      db_query("
        UPDATE {$db_prefix}infop
        SET fumo = '$fumo'
        WHERE id_user = $ID_MEMBER", __FILE__, __LINE__);

      if (!in_array($tomo_alcohol, getAlcoholes('keys'))) {
        fatal_error('Debes seleccionar una opci&oacute;n de alcoholes que exista.');
      }

      db_query("
        UPDATE {$db_prefix}infop
        SET tomo_alcohol = '$tomo_alcohol'
        WHERE id_user = $ID_MEMBER", __FILE__, __LINE__);

      if (!in_array($color_de_pelo, getColoresPelo('keys'))) {
        fatal_error('Debes seleccionar una opci&oacute;n de color de pelo que exista.');
      }

      db_query("
        UPDATE {$db_prefix}infop
        SET color_de_pelo = '$color_de_pelo'
        WHERE id_user = $ID_MEMBER", __FILE__, __LINE__);

      if (!in_array($color_de_ojos, getColoresOjos('keys'))) {
        fatal_error('Debes seleccionar una opci&oacute;n de color de ojos que exista.');
      }

      db_query("
        UPDATE {$db_prefix}infop
        SET color_de_ojos = '$color_de_ojos'
        WHERE id_user = $ID_MEMBER", __FILE__, __LINE__);

      if (!in_array($mi_dieta_es, getDietas('keys'))) {
        fatal_error('Debes seleccionar una opci&oacute;n de dieta que exista.');
      }

      db_query("
        UPDATE {$db_prefix}infop
        SET mi_dieta_es = '$mi_dieta_es'
        WHERE id_user = $ID_MEMBER", __FILE__, __LINE__);

      if (!in_array($complexion, getComplexiones('keys'))) {
        fatal_error('Debes seleccionar una opci&oacute;n de complexi&oacute;n que exista.');
      }

      db_query("
        UPDATE {$db_prefix}infop
        SET complexion = '$complexion'
        WHERE id_user = $ID_MEMBER", __FILE__, __LINE__);

      header('Location: ' . $boardurl . '/editar-apariencia/paso4/');
    } else {
      // Sin registros
      if (strlen($altura) >= 4) {
        fatal_error('No puede haber m&aacute;s de 3 n&uacute;meros en tu altura.');
      }

      if (strlen($peso) >= 4) {
        fatal_error('No puede haber m&aacute;s de 3 n&uacute;meros en tu peso.-');
      }

      if (!in_array($fumo, getFumos('keys'))) {
        fatal_error('Debes seleccionar una opci&oacute;n de fumador que exista.');
      }

      if (!in_array($tomo_alcohol, getAlcoholes('keys'))) {
        fatal_error('Debes seleccionar una opci&oacute;n de alcoholes que exista.');
      }

      if (!in_array($color_de_pelo, getColoresPelo('keys'))) {
        fatal_error('Debes seleccionar una opci&oacute;n de color de pelo que exista.');
      }

      if (!in_array($color_de_ojos, getColoresOjos('keys'))) {
        fatal_error('Debes seleccionar una opci&oacute;n de color de ojos que exista.');
      }

      if (!in_array($mi_dieta_es, getDietas('keys'))) {
        fatal_error('Debes seleccionar una opci&oacute;n de dieta que exista.');
      }

      if (!in_array($complexion, getComplexiones('keys'))) {
        fatal_error('Debes seleccionar una opci&oacute;n de complexi&oacute;n que exista.');
      }

      db_query("
        INSERT INTO {$db_prefix}infop (altura, peso, mi_dieta_es, fumo, tomo_alcohol, complexion, color_de_pelo, color_de_ojos, id_user)
        VALUES ('$altura', '$peso', '$mi_dieta_es', '$fumo', '$tomo_alcohol', '$complexion', '$color_de_pelo', '$color_de_ojos', $ID_MEMBER)", __FILE__, __LINE__);

      header('Location: ' . $boardurl . '/editar-apariencia/paso4/');
    }
  } else if ($tipo == 4) {
    // Paso 4
    // Registro existente
    if ($rows > 0) {
      if (strlen($series_tv_favoritas) >= 501) {
        fatal_error('No puedes escribir m&aacute;s de 500 letras.');
      }

      if (strlen($musica_favorita) >= 501) {
        fatal_error('No puedes escribir m&aacute;s de 500 letras.');
      }

      if (strlen($deportes_y_equipos_favoritos) >= 501) {
        fatal_error('No puedes escribir m&aacute;s de 500 letras.');
      }

      if (strlen($libros_favoritos) >= 501) {
        fatal_error('No puedes escribir m&aacute;s de 500 letras.');
      }

      if (strlen($peliculas_favoritas) >= 501) {
        fatal_error('No puedes escribir m&aacute;s de 500 letras.');
      }

      if (strlen($comida_favorita) >= 501) {
        fatal_error('No puedes escribir m&aacute;s de 500 letras.');
      }

      if (strlen($mis_heroes_son) >= 501) {
        fatal_error('No puedes escribir m&aacute;s de 500 letras.');
      }

      if (strlen($mis_intereses) >= 501) {
        fatal_error('No puedes escribir m&aacute;s de 500 letras.');
      }

      if (strlen($hobbies) >= 501) {
        fatal_error('No puedes escribir m&aacute;s de 500 letras.');
      }

      db_query("
        UPDATE {$db_prefix}infop
        SET mis_intereses = '$mis_intereses'
        WHERE id_user = $ID_MEMBER", __FILE__, __LINE__);

      db_query("
        UPDATE {$db_prefix}infop
        SET hobbies = '$hobbies'
        WHERE id_user = $ID_MEMBER", __FILE__, __LINE__);

      db_query("
        UPDATE {$db_prefix}infop
        SET series_de_tv_favorita = '$series_tv_favoritas'
        WHERE id_user = $ID_MEMBER", __FILE__, __LINE__);

      db_query("
        UPDATE {$db_prefix}infop
        SET musica_favorita = '$musica_favorita'
        WHERE id_user = $ID_MEMBER", __FILE__, __LINE__);

      db_query("
        UPDATE {$db_prefix}infop
        SET deportes_y_equipos_favoritos = '$deportes_y_equipos_favoritos'
        WHERE id_user = $ID_MEMBER", __FILE__, __LINE__);

      db_query("
        UPDATE {$db_prefix}infop
        SET libros_favoritos = '$libros_favoritos'
        WHERE id_user = $ID_MEMBER", __FILE__, __LINE__);

      db_query("
        UPDATE {$db_prefix}infop
        SET peliculas_favoritas = '$peliculas_favoritas'
        WHERE id_user = $ID_MEMBER", __FILE__, __LINE__);

      db_query("
        UPDATE {$db_prefix}infop
        SET comida_favorita = '$comida_favorita'
        WHERE id_user = $ID_MEMBER", __FILE__, __LINE__);

      db_query("
        UPDATE {$db_prefix}infop
        SET mis_heroes_son = '$mis_heroes_son'
        WHERE id_user = $ID_MEMBER", __FILE__, __LINE__);


      header('Location: ' . $boardurl . '/editar-apariencia/');
    } else {
      if (strlen($series_tv_favoritas) >= 501) {
        fatal_error('No puedes escribir m&aacute;s de 500 letras.');
      }

      if (strlen($musica_favorita) >= 501) {
        fatal_error('No puedes escribir m&aacute;s de 500 letras.');
      }

      if (strlen($deportes_y_equipos_favoritos) >= 501) {
        fatal_error('No puedes escribir m&aacute;s de 500 letras.');
      }

      if (strlen($libros_favoritos) >= 501) {
        fatal_error('No puedes escribir m&aacute;s de 500 letras.');
      }

      if (strlen($peliculas_favoritas) >= 501) {
        fatal_error('No puedes escribir m&aacute;s de 500 letras.');
      }

      if (strlen($comida_favorita) >= 501) {
        fatal_error('No puedes escribir m&aacute;s de 500 letras.');
      }

      if (strlen($mis_heroes_son) >= 501) {
        fatal_error('No puedes escribir m&aacute;s de 500 letras.');
      }

      if (strlen($mis_intereses) >= 501) {
        fatal_error('No puedes escribir m&aacute;s de 500 letras.');
      }

      if (strlen($hobbies) >= 501) {
        fatal_error('No puedes escribir m&aacute;s de 500 letras.');
      }

      db_query("
        INSERT INTO {$db_prefix}infop (mis_intereses, hobbies, series_de_tv_favorita, musica_favorita, deportes_y_equipos_favoritos, libros_favoritos, peliculas_favoritas, comida_favorita, mis_heroes_son, id_user)
        VALUES ('$mis_intereses', '$hobbies', '$series_tv_favoritas', '$musica_favorita', '$deportes_y_equipos_favoritos', '$libros_favoritos', '$peliculas_favoritas', '$comida_favorita', '$mis_heroes_son', $ID_MEMBER)", __FILE__, __LINE__);

      header('Location: ' . $boardurl . '/editar-apariencia/');
    }
  }
} else {
  fatal_error('No puedes estar ac&aacute;');
}

?>