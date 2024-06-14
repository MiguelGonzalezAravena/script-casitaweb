<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $context, $db_prefix, $user_settings, $user_info, $ID_MEMBER, $boardurl;

if ($user_info['is_guest']) {
  fatal_error('No puedes estar acá.');
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
if($rows > 0) {



if(strlen($altura)>=4){fatal_error('No puede haber m&aacute;s de 3 numeros en tu altura.-');}
db_query("UPDATE {$db_prefix}infop SET altura='$altura' WHERE id_user=$ID_MEMBER", __FILE__, __LINE__);

if(strlen($peso)>=4){fatal_error('No puede haber m&aacute;s de 3 numeros en tu peso.-');}
db_query("UPDATE {$db_prefix}infop SET peso='$peso' WHERE id_user=$ID_MEMBER", __FILE__, __LINE__);


if(empty($fumo) || $fumo=='no' || $fumo=='casualmente' || $fumo=='socialmente' || $fumo=='regularmente' || $fumo=='mucho'){
if(empty($fumo)){$texto7='';}
elseif($fumo=='no'){$texto7='No';}
elseif($fumo=='casualmente'){$texto7='Casualmente';}
elseif($fumo=='socialmente'){$texto7='Socialmente';}
elseif($fumo=='regularmente'){$texto7='Regularmente';}
elseif($fumo=='mucho'){$texto7='Mucho';}else{$texto7='';}}else{fatal_error('Hubo un error, intentar nuevamente.');}
db_query("UPDATE {$db_prefix}infop SET fumo='$texto7' WHERE id_user = $ID_MEMBER", __FILE__, __LINE__);


if(empty($tomo_alcohol) || $tomo_alcohol=='no' || $tomo_alcohol=='casualmente' || $tomo_alcohol=='socialmente' || $tomo_alcohol=='regularmente' || $tomo_alcohol=='mucho'){
if(empty($tomo_alcohol)){$texto8='';}
elseif($tomo_alcohol=='no'){$texto8='No';}
elseif($tomo_alcohol=='casualmente'){$texto8='Casualmente';}
elseif($tomo_alcohol=='socialmente'){$texto8='Socialmente';}
elseif($tomo_alcohol=='regularmente'){$texto8='Regularmente';}
elseif($tomo_alcohol=='mucho'){$texto8='Mucho';}else{$texto8='';}}else{fatal_error('Hubo un error, intentar nuevamente.');}
db_query("UPDATE {$db_prefix}infop SET tomo_alcohol='$texto8' WHERE id_user=$ID_MEMBER", __FILE__, __LINE__);


if(empty($color_de_pelo) || $color_de_pelo=='negro' || $color_de_pelo=='castano_oscuro' || $color_de_pelo=='castano_claro' || $color_de_pelo=='rubio' || $color_de_pelo=='pelirrojo' || $color_de_pelo=='gris' || $color_de_pelo=='canoso' || $color_de_pelo=='tenido' || $color_de_pelo=='rapado' || $color_de_pelo=='calvo'){
if(empty($color_de_pelo)){$texto3='';}
elseif($color_de_pelo=='negro'){$texto3='Negro';}
elseif($color_de_pelo=='castano_oscuro'){$texto3='Casta&ntilde;o oscuro';}
elseif($color_de_pelo=='castano_claro'){$texto3='Casta&ntilde;o claro';}
elseif($color_de_pelo=='rubio'){$texto3='Rubio';}
elseif($color_de_pelo=='pelirrojo'){$texto3='Pelirrojo';}
elseif($color_de_pelo=='gris'){$texto3='Gris';}
elseif($color_de_pelo=='canoso'){$texto3='Canoso';}
elseif($color_de_pelo=='tenido'){$texto3='Te&ntilde;ido';}
elseif($color_de_pelo=='rapado'){$texto3='Rapado';}
elseif($color_de_pelo=='calvo'){$texto3='Calvo';}else{$texto3='';}} else {fatal_error('Hubo un error, intentar nuevamente.');}
db_query("UPDATE {$db_prefix}infop SET color_de_pelo='$texto3' WHERE id_user=$ID_MEMBER", __FILE__, __LINE__);


if(empty($color_de_ojos) || $color_de_ojos=='negros' || $color_de_ojos=='marrones' || $color_de_ojos=='celestes' || $color_de_ojos=='verdes' || $color_de_ojos=='grises'){
if(empty($color_de_ojos)){$texto4='';}
elseif($color_de_ojos=='negros'){$texto4='Negros';}
elseif($color_de_ojos=='marrones'){$texto4='Marrones';}
elseif($color_de_ojos=='celestes'){$texto4='Celestes';}
elseif($color_de_ojos=='verdes'){$texto4='Verdes';}
elseif($color_de_ojos=='grises'){$texto4='Grises';}else{$texto4='';}}else{fatal_error('Hubo un error, intentar nuevamente.-');}
db_query("UPDATE {$db_prefix}infop SET color_de_ojos='$texto4' WHERE id_user=$ID_MEMBER", __FILE__, __LINE__);



if(empty($mi_dieta_es) || $mi_dieta_es=='vegetariana' || $mi_dieta_es=='lacto_vegetariana' || $mi_dieta_es=='organica' || $mi_dieta_es=='de_todo' || $mi_dieta_es=='comida_basura'){
if(empty($mi_dieta_es)){$texto6='';}
elseif($mi_dieta_es=='vegetariana'){$texto6='Vegetariana';}
elseif($mi_dieta_es=='lacto_vegetariana'){$texto6='Lacto Vegetariana';}
elseif($mi_dieta_es=='organica'){$texto6='Org&aacute;nica';}
elseif($mi_dieta_es=='de_todo'){$texto6='De todo';}
elseif($mi_dieta_es=='comida_basura'){$texto6='Comida basura';}else{$texto6='';}}else{fatal_error('Hubo un error, intentar nuevamente.-');}
db_query("UPDATE {$db_prefix}infop SET mi_dieta_es='$texto6' WHERE id_user=$ID_MEMBER", __FILE__, __LINE__);


if(empty($complexion) || $complexion=='delgado' || $complexion=='atletico' || $complexion=='normal' || $complexion=='kilos_de_mas' || $complexion=='corpulento'){
if(empty($complexion)){$texto5='';}
elseif($complexion=='delgado'){$texto5='Delgado/a';}
elseif($complexion=='atletico'){$texto5='tl&eacute;tico';}
elseif($complexion=='normal'){$texto5='Normal';}
elseif($complexion=='kilos_de_mas'){$texto5='Algunos kilos de m&aacute;s';}
elseif($complexion=='corpulento'){$texto5='Corpulento/a';}else{$texto5='';}}else{fatal_error('Hubo un error, intentar nuevamente.-');}
db_query("UPDATE {$db_prefix}infop SET complexion='$texto5' WHERE id_user=$ID_MEMBER", __FILE__, __LINE__);

Header("Location: $boardurl/editar-apariencia/paso4/");exit();die();}

else{
if(strlen($altura)>=4){fatal_error('No puede haber m&aacute;s de 3 numeros en tu altura.-');}
if(strlen($peso)>=4){fatal_error('No puede haber m&aacute;s de 3 numeros en tu peso.-');}

if(empty($fumo) || $fumo=='no' || $fumo=='casualmente' || $fumo=='socialmente' || $fumo=='regularmente' || $fumo=='mucho'){
if(empty($fumo)){$texto7='';}
elseif($fumo=='no'){$texto7='No';}
elseif($fumo=='casualmente'){$texto7='Casualmente';}
elseif($fumo=='socialmente'){$texto7='Socialmente';}
elseif($fumo=='regularmente'){$texto7='Regularmente';}
elseif($fumo=='mucho'){$texto7='Mucho';}else{$texto7='';}}else{fatal_error('Hubo un error, intentar nuevamente.-');}

if(empty($tomo_alcohol) || $tomo_alcohol=='no' || $tomo_alcohol=='casualmente' || $tomo_alcohol=='socialmente' || $tomo_alcohol=='regularmente' || $tomo_alcohol=='mucho'){
if(empty($tomo_alcohol)){$texto8='';}
elseif($tomo_alcohol=='no'){$texto8='No';}
elseif($tomo_alcohol=='casualmente'){$texto8='Casualmente';}
elseif($tomo_alcohol=='socialmente'){$texto8='Socialmente';}
elseif($tomo_alcohol=='regularmente'){$texto8='Regularmente';}
elseif($tomo_alcohol=='mucho'){$texto8='Mucho';}else{$texto8='';}}else{fatal_error('Hubo un error, intentar nuevamente.-');}

if(empty($color_de_pelo) || $color_de_pelo=='negro' || $color_de_pelo=='castano_oscuro' || $color_de_pelo=='castano_claro' || $color_de_pelo=='rubio' || $color_de_pelo=='pelirrojo' || $color_de_pelo=='gris' || $color_de_pelo=='canoso' || $color_de_pelo=='tenido' || $color_de_pelo=='rapado' || $color_de_pelo=='calvo'){
if(empty($color_de_pelo)){$texto3='';}
elseif($color_de_pelo=='negro'){$texto3='Negro';}
elseif($color_de_pelo=='castano_oscuro'){$texto3='Casta&ntilde;o oscuro';}
elseif($color_de_pelo=='castano_claro'){$texto3='Casta&ntilde;o claro';}
elseif($color_de_pelo=='rubio'){$texto3='Rubio';}
elseif($color_de_pelo=='pelirrojo'){$texto3='Pelirrojo';}
elseif($color_de_pelo=='gris'){$texto3='Gris';}
elseif($color_de_pelo=='canoso'){$texto3='Canoso';}
elseif($color_de_pelo=='tenido'){$texto3='Te&ntilde;ido';}
elseif($color_de_pelo=='rapado'){$texto3='Rapado';}
elseif($color_de_pelo=='calvo'){$texto3='Calvo';}else{$texto3='';}}else{fatal_error('Hubo un error, intentar nuevamente.-');}

if(empty($color_de_ojos) || $color_de_ojos=='negros' || $color_de_ojos=='marrones' || $color_de_ojos=='celestes' || $color_de_ojos=='verdes' || $color_de_ojos=='grises'){
if(empty($color_de_ojos)){$texto4='';}
elseif($color_de_ojos=='negros'){$texto4='Negros';}
elseif($color_de_ojos=='marrones'){$texto4='Marrones';}
elseif($color_de_ojos=='celestes'){$texto4='Celestes';}
elseif($color_de_ojos=='verdes'){$texto4='Verdes';}
elseif($color_de_ojos=='grises'){$texto4='Grises';}else{$texto4='';}}else{fatal_error('Hubo un error, intentar nuevamente.-');}

if(empty($mi_dieta_es) || $mi_dieta_es=='vegetariana' || $mi_dieta_es=='lacto_vegetariana' || $mi_dieta_es=='organica' || $mi_dieta_es=='de_todo' || $mi_dieta_es=='comida_basura'){
if(empty($mi_dieta_es)){$texto6='';}
elseif($mi_dieta_es=='vegetariana'){$texto6='Vegetariana';}
elseif($mi_dieta_es=='lacto_vegetariana'){$texto6='Lacto Vegetariana';}
elseif($mi_dieta_es=='organica'){$texto6='Org&aacute;nica';}
elseif($mi_dieta_es=='de_todo'){$texto6='De todo';}
elseif($mi_dieta_es=='comida_basura'){$texto6='Comida basura';}else{$texto6='';}}else{fatal_error('Hubo un error, intentar nuevamente.-');}

if(empty($complexion) || $complexion=='delgado' || $complexion=='atletico' || $complexion=='normal' || $complexion=='kilos_de_mas' || $complexion=='corpulento'){
if(empty($complexion)){$texto5='';}
elseif($complexion=='delgado'){$texto5='Delgado/a';}
elseif($complexion=='atletico'){$texto5='tl&eacute;tico';}
elseif($complexion=='normal'){$texto5='Normal';}
elseif($complexion=='kilos_de_mas'){$texto5='Algunos kilos de m&aacute;s';}
elseif($complexion=='corpulento'){$texto5='Corpulento/a';}else{$texto5='';}}else{fatal_error('Hubo un error, intentar nuevamente.-');}

db_query("INSERT INTO {$db_prefix}infop
(altura,peso,mi_dieta_es,fumo,tomo_alcohol,complexion,color_de_pelo,color_de_ojos,id_user)
VALUES ('$altura','$peso','$texto6','$texto7','$texto8','$texto5','$texto3','$texto4',$ID_MEMBER)", __FILE__, __LINE__);
  header("Location: $boardurl/editar-apariencia/paso4/");
}
} else if ($tipo=='4') {
    if($rows > 0) {
      if(strlen($_POST['series_tv_favoritas'])>=501){fatal_error('No puedes escribir m&aacute;s de 500 (Quinientas) letras.-');}
      if(strlen($_POST['musica_favorita'])>=501){fatal_error('No puedes escribir m&aacute;s de 500 (Quinientas) letras.-');}
      if(strlen($_POST['deportes_y_equipos_favoritos'])>=501){fatal_error('No puedes escribir m&aacute;s de 500 (Quinientas) letras.-');}
      if(strlen($_POST['libros_favoritos'])>=501){fatal_error('No puedes escribir m&aacute;s de 500 (Quinientas) letras.-');}
      if(strlen($_POST['peliculas_favoritas'])>=501){fatal_error('No puedes escribir m&aacute;s de 500 (Quinientas) letras.-');}
      if(strlen($_POST['comida_favorita'])>=501){fatal_error('No puedes escribir m&aacute;s de 500 (Quinientas) letras.-');}
      if(strlen($_POST['mis_heroes_son'])>=501){fatal_error('No puedes escribir m&aacute;s de 500 (Quinientas) letras.-');}
      if(strlen($_POST['mis_intereses'])>=501){fatal_error('No puedes escribir m&aacute;s de 500 (Quinientas) letras.-');}
      if(strlen($_POST['hobbies'])>=501){fatal_error('No puedes escribir m&aacute;s de 500 (Quinientas) letras.-');}

      db_query("UPDATE {$db_prefix}infop SET mis_intereses='$mis_intereses' WHERE id_user = $ID_MEMBER", __FILE__, __LINE__);
      db_query("UPDATE {$db_prefix}infop SET hobbies='$hobbies' WHERE id_user = $ID_MEMBER", __FILE__, __LINE__);
      db_query("UPDATE {$db_prefix}infop SET series_de_tv_favorita='$series_tv_favoritas' WHERE id_user = $ID_MEMBER", __FILE__, __LINE__);
      db_query("UPDATE {$db_prefix}infop SET musica_favorita='$musica_favorita' WHERE id_user = $ID_MEMBER", __FILE__, __LINE__);
      db_query("UPDATE {$db_prefix}infop SET deportes_y_equipos_favoritos='$deportes_y_equipos_favoritos' WHERE id_user = $ID_MEMBER", __FILE__, __LINE__);
      db_query("UPDATE {$db_prefix}infop SET libros_favoritos='$libros_favoritos' WHERE id_user = $ID_MEMBER", __FILE__, __LINE__);
      db_query("UPDATE {$db_prefix}infop SET peliculas_favoritas='$peliculas_favoritas' WHERE id_user = $ID_MEMBER", __FILE__, __LINE__);
      db_query("UPDATE {$db_prefix}infop SET comida_favorita='$comida_favorita' WHERE id_user = $ID_MEMBER", __FILE__, __LINE__);
      db_query("UPDATE {$db_prefix}infop SET mis_heroes_son='$mis_heroes_son' WHERE id_user = $ID_MEMBER", __FILE__, __LINE__);

      header("Location: $boardurl/editar-apariencia/");
    } else {
      if(strlen($_POST['series_tv_favoritas'])>=501){fatal_error('No puedes escribir m&aacute;s de 500 (Quinientas) letras.-');}
      if(strlen($_POST['musica_favorita'])>=501){fatal_error('No puedes escribir m&aacute;s de 500 (Quinientas) letras.-');}
      if(strlen($_POST['deportes_y_equipos_favoritos'])>=501){fatal_error('No puedes escribir m&aacute;s de 500 (Quinientas) letras.-');}
      if(strlen($_POST['libros_favoritos'])>=501){fatal_error('No puedes escribir m&aacute;s de 500 (Quinientas) letras.-');}
      if(strlen($_POST['peliculas_favoritas'])>=501){fatal_error('No puedes escribir m&aacute;s de 500 (Quinientas) letras.-');}
      if(strlen($_POST['comida_favorita'])>=501){fatal_error('No puedes escribir m&aacute;s de 500 (Quinientas) letras.-');}
      if(strlen($_POST['mis_heroes_son'])>=501){fatal_error('No puedes escribir m&aacute;s de 500 (Quinientas) letras.-');}
      if(strlen($_POST['mis_intereses'])>=501){fatal_error('No puedes escribir m&aacute;s de 500 (Quinientas) letras.-');}
      if(strlen($_POST['hobbies'])>=501){fatal_error('No puedes escribir m&aacute;s de 500 (Quinientas) letras.-');}

      db_query("
        INSERT INTO {$db_prefix}infop (mis_intereses, hobbies, series_de_tv_favorita, musica_favorita, deportes_y_equipos_favoritos, libros_favoritos, peliculas_favoritas, comida_favorita, mis_heroes_son, id_user)
        VALUES ('$mis_intereses', '$hobbies', '$series_tv_favoritas', '$musica_favorita', '$deportes_y_equipos_favoritos', '$libros_favoritos', '$peliculas_favoritas', '$comida_favorita', '$mis_heroes_son', $ID_MEMBER)", __FILE__, __LINE__);

      header("Location: $boardurl/editar-apariencia/");
    }
  }

} else {
  fatal_error('No puedes estar ac&aacute;');
}

?>