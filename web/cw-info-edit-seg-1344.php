<?php require("cw-conexion-seg-0011.php"); global $context,  $db_prefix, $user_settings,$user_info;
if($user_info['is_guest']){fatal_error('No podes estar aca, Gracias.');}
$tipo=(int)$_POST['tipo'];


if($tipo=='1' || $tipo=='2' || $tipo=='3' || $tipo=='4'){
$refoagr=db_query("SELECT id_user FROM ({$db_prefix}infop) WHERE id_user='{$user_settings['ID_MEMBER']}' LIMIT 1", __FILE__, __LINE__); $agrearorefrescar=mysqli_num_rows($refoagr);

if($tipo=='1'){

if(!empty($agrearorefrescar)){
$profesion=nohtml($_POST['profesion']);
if(strlen($profesion)>=33){fatal_error('No puedes agrear una profesi&oacute;n con m&aacute;s de 32 letras.-');}
db_query("UPDATE {$db_prefix}infop SET profesion='$profesion' WHERE id_user='{$user_settings['ID_MEMBER']}'", __FILE__, __LINE__);
$estudios=nohtml($_POST['estudios']);
if(empty($estudios) || $estudios=='sin' || $estudios=='pri' || $estudios=='sec_curso' || $estudios=='sec_completo' || $estudios=='ter_curso' || $estudios=='univ_curso' || $estudios=='univ_completo' || $estudios=='ter_completo' || $estudios=='post_curso' || $estudios=='post_completo'){
if(empty($estudios)){$texto='';}
if($estudios=='sin'){$texto='Sin Estudios';}
if($estudios=='pri'){$texto='Primario completo';}
if($estudios=='sec_curso'){$texto='Secundario en curso';}
if($estudios=='sec_completo'){$texto='Secundario completo';}
if($estudios=='ter_curso'){$texto='Terciario en curso';}
if($estudios=='univ_curso'){$texto='Universitario en curso';}
if($estudios=='univ_completo'){$texto='Universitario completo';}
if($estudios=='ter_completo'){$texto='Terciario completo';}
if($estudios=='post_curso'){$texto='Post-grado en curso';}
if($estudios=='post_completo'){$texto='Post-grado completo';}
db_query("UPDATE {$db_prefix}infop SET estudios='$texto' WHERE id_user='{$user_settings['ID_MEMBER']}'", __FILE__, __LINE__);}
else{fatal_error('Hubo un error, intentar nuevamente.-');}

$empresa=nohtml($_POST['empresa']);
if(strlen($empresa)>=33){fatal_error('No puedes agrear una empresa con m&aacute;s de 32 letras.-');}
db_query("UPDATE {$db_prefix}infop SET empresa='$empresa' WHERE id_user='{$user_settings['ID_MEMBER']}'", __FILE__, __LINE__);

$ingresos=nohtml($_POST['ingresos']);
if(empty($ingresos) || $ingresos=='sin' || $ingresos=='bajos' || $ingresos=='intermedios' || $ingresos=='altos'){
if(empty($ingresos)){$texto2='';}
if($ingresos=='sin'){$texto2='Sin ingresos';}
if($ingresos=='bajos'){$texto2='Bajos';}
if($ingresos=='intermedios'){$texto2='Intermedios';}
if($ingresos=='altos'){$texto2='Altos';}
db_query("UPDATE {$db_prefix}infop SET nivel_de_ingresos='$texto2' WHERE id_user='{$user_settings['ID_MEMBER']}'", __FILE__, __LINE__);}
else{fatal_error('Hubo un error, intentar nuevamente.-');}

$intereses_profesionales=nohtml($_POST['intereses_profesionales']);
db_query("UPDATE {$db_prefix}infop SET intereses_profesionales='$intereses_profesionales' WHERE id_user='{$user_settings['ID_MEMBER']}'", __FILE__, __LINE__);
$habilidades_profesionales=nohtml($_POST['habilidades_profesionales']);
db_query("UPDATE {$db_prefix}infop SET habilidades_profesionales='$habilidades_profesionales' WHERE id_user='{$user_settings['ID_MEMBER']}'", __FILE__, __LINE__);

Header("Location: /editar-apariencia/paso2/");exit();die();}

else{
$profesion=nohtml($_POST['profesion']);
$estudios=nohtml($_POST['estudios']);
$empresa=nohtml($_POST['empresa']);
$intereses_profesionales=nohtml($_POST['intereses_profesionales']);
$habilidades_profesionales=nohtml($_POST['habilidades_profesionales']);
$ingresos=nohtml($_POST['ingresos']);
if(empty($estudios) || $estudios=='sin' || $estudios=='pri' || $estudios=='sec_curso' || $estudios=='sec_completo' || $estudios=='ter_curso' || $estudios=='univ_curso' || $estudios=='univ_completo' || $estudios=='ter_completo' || $estudios=='post_curso' || $estudios=='post_completo'){
if(empty($estudios)){$texto='';}
if($estudios=='sin'){$texto='Sin Estudios';}
if($estudios=='pri'){$texto='Primario completo';}
if($estudios=='sec_curso'){$texto='Secundario en curso';}
if($estudios=='sec_completo'){$texto='Secundario completo';}
if($estudios=='ter_curso'){$texto='Terciario en curso';}
if($estudios=='univ_curso'){$texto='Universitario en curso';}
if($estudios=='univ_completo'){$texto='Universitario completo';}
if($estudios=='ter_completo'){$texto='Terciario completo';}
if($estudios=='post_curso'){$texto='Post-grado en curso';}
if($estudios=='post_completo'){$texto='Post-grado completo';}}else{fatal_error('Hubo un error, intentar nuevamente.-');}
if(empty($ingresos) || $ingresos=='sin' || $ingresos=='bajos' || $ingresos=='intermedios' || $ingresos=='altos'){
if(empty($ingresos)){$texto2='';}
if($ingresos=='sin'){$texto2='Sin ingresos';}
if($ingresos=='bajos'){$texto2='Bajos';}
if($ingresos=='intermedios'){$texto2='Intermedios';}
if($ingresos=='altos'){$texto2='Altos';}}else{fatal_error('Hubo un error, intentar nuevamente.-');}
if(strlen($profesion)>=33){fatal_error('No puedes agrear una profesi&oacute;n con m&aacute;s de 32 letras.-');}
if(strlen($empresa)>=33){fatal_error('No puedes agrear una empresa con m&aacute;s de 32 letras.-');}
db_query("INSERT INTO {$db_prefix}infop
(habilidades_profesionales,intereses_profesionales,nivel_de_ingresos,empresa,estudios,profesion,id_user)
VALUES ('$habilidades_profesionales','$intereses_profesionales','$texto2','$empresa','$texto','$profesion','{$user_settings['ID_MEMBER']}')", __FILE__, __LINE__);
Header("Location: /editar-apariencia/paso2/");exit();die();}}


//paso 2 -------------------------------------------------------------------------------------------------------
if($tipo=='2'){
if(!empty($agrearorefrescar)){
$me_gustaria=$_POST['me_gustaria'];
$hijos=$_POST['hijos'];
$en_el_amor_estoy=$_POST['estado'];

if(empty($me_gustaria) || $me_gustaria=='hacer_amigos' || $me_gustaria=='conocer_gente_con_mis_intereses' || $me_gustaria=='conocer_gente_para_hacer_negocios' || $me_gustaria=='encontrar_pareja' || $me_gustaria=='de_todo'){
    
if(empty($me_gustaria)){$texto3='';}
elseif($me_gustaria=='hacer_amigos'){$texto3='Hacer Amigos';}
elseif($me_gustaria=='conocer_gente_con_mis_intereses'){$texto3='Conocer gente con mis intereses';}
elseif($me_gustaria=='conocer_gente_para_hacer_negocios'){$texto3='Conocer gente para hacer negocios';}
elseif($me_gustaria=='encontrar_pareja'){$texto3='Encontrar pareja';}
elseif($me_gustaria=='de_todo'){$texto3='De todo';}else{$texto3='';}}else{fatal_error('Hubo un error, intentar nuevamente.-');}
db_query("UPDATE {$db_prefix}infop SET me_gustaria='$texto3' WHERE id_user='{$user_settings['ID_MEMBER']}'", __FILE__, __LINE__);


if(empty($en_el_amor_estoy) || $en_el_amor_estoy=='soltero' || $en_el_amor_estoy=='novio' || $en_el_amor_estoy=='casado' || $en_el_amor_estoy=='divorciado' || $en_el_amor_estoy=='viudo' || $en_el_amor_estoy=='algo'){
if(empty($en_el_amor_esto)){$texto4='';}
elseif($en_el_amor_estoy=='soltero'){$texto4='Soltero/a';}
elseif($en_el_amor_estoy=='novio'){$texto4='De novio/a';}
elseif($en_el_amor_estoy=='casado'){$texto4='Casado/a';}
elseif($en_el_amor_estoy=='divorciado'){$texto4='Divorciado/a';}
elseif($en_el_amor_estoy=='viudo'){$texto4='Viudo/a';}
elseif($en_el_amor_estoy=='algo'){$texto4='En algo...';}else{$texto4='';}}else{fatal_error('Hubo un error, intentar nuevamente.-');}
db_query("UPDATE {$db_prefix}infop SET en_el_amor_estoy ='$texto4' WHERE id_user='{$user_settings['ID_MEMBER']}'", __FILE__, __LINE__);

if(empty($hijos) || $hijos=='no' || $hijos=='algun_dia' || $hijos=='no_quiero' || $hijos=='viven_conmigo' || $hijos=='no_viven_conmigo'){
if(empty($hijos)){$texto5='';}
elseif($hijos=='no'){$texto5='No tengo';}
elseif($hijos=='algun_dia'){$texto5='Alg&uacute;n d&iacute;a';}
elseif($hijos=='no_quiero'){$texto5='No son lo m&iacute;o';}
elseif($hijos=='viven_conmigo'){$texto5='Tengo, vivo con ellos';}
elseif($hijos=='no_viven_conmigo'){$texto5='Tengo, no vivo con ellos';}else{$texto5='';}

}else{fatal_error('Hubo un error, intentar nuevamente.-');}
db_query("UPDATE {$db_prefix}infop SET hijos='$texto5' WHERE id_user='{$user_settings['ID_MEMBER']}'", __FILE__, __LINE__);

Header("Location: /editar-apariencia/paso3/");exit();die();}

else{
$me_gustaria=$_POST['me_gustaria'];
$hijos=$_POST['hijos'];
$en_el_amor_estoy=$_POST['estado'];
if(empty($me_gustaria) || $me_gustaria=='hacer_amigos' || $me_gustaria=='conocer_gente_con_mis_intereses' || $me_gustaria=='conocer_gente_para_hacer_negocios' || $me_gustaria=='encontrar_pareja' || $me_gustaria=='de_todo'){
    
if(empty($me_gustaria)){$texto3='';}
elseif($me_gustaria=='hacer_amigos'){$texto3='Hacer Amigos';}
elseif($me_gustaria=='conocer_gente_con_mis_intereses'){$texto3='Conocer gente con mis intereses';}
elseif($me_gustaria=='conocer_gente_para_hacer_negocios'){$texto3='Conocer gente para hacer negocios';}
elseif($me_gustaria=='encontrar_pareja'){$texto3='Encontrar pareja';}
elseif($me_gustaria=='de_todo'){$texto3='De todo';}else{$texto3='';}}else{fatal_error('Hubo un error, intentar nuevamente.-');}

if(empty($en_el_amor_estoy) || $en_el_amor_estoy=='soltero' || $en_el_amor_estoy=='novio' || $en_el_amor_estoy=='casado' || $en_el_amor_estoy=='divorciado' || $en_el_amor_estoy=='viudo' || $en_el_amor_estoy=='algo'){
if(empty($en_el_amor_estoy)){$texto4='';}
elseif($en_el_amor_estoy=='soltero'){$texto4='Soltero/a';}
elseif($en_el_amor_estoy=='novio'){$texto4='De novio/a';}
elseif($en_el_amor_estoy=='casado'){$texto4='Casado/a';}
elseif($en_el_amor_estoy=='divorciado'){$texto4='Divorciado/a';}
elseif($en_el_amor_estoy=='viudo'){$texto4='Viudo/a';}
elseif($en_el_amor_estoy=='algo'){$texto4='En algo...';}else{$texto4='';}}else{fatal_error('Hubo un error, intentar nuevamente.-');}

if(empty($hijos) || $hijos=='no' || $hijos=='algun_dia' || $hijos=='no_quiero' || $hijos=='viven_conmigo' || $hijos=='no_viven_conmigo'){
if(empty($hijos)){$texto5='';}
elseif($hijos=='no'){$texto5='No tengo';}
elseif($hijos=='algun_dia'){$texto5='Alg&uacute;n d&iacute;a';}
elseif($hijos=='no_quiero'){$texto5='No son lo m&iacute;o';}
elseif($hijos=='viven_conmigo'){$texto5='Tengo, vivo con ellos';}
elseif($hijos=='no_viven_conmigo'){$texto5='Tengo, no vivo con ellos';}else{$texto5='';}}else{fatal_error('Hubo un error, intentar nuevamente.-');}

db_query("INSERT INTO {$db_prefix}infop
(en_el_amor_estoy,me_gustaria,hijos,id_user)
VALUES ('$texto4','$texto3','$texto5','{$user_settings['ID_MEMBER']}')", __FILE__, __LINE__);

Header("Location: /editar-apariencia/paso3/");exit();die();}}

//paso3 -------------------------------------------------------------------
elseif($tipo=='3'){

if(!empty($agrearorefrescar)){
$color_de_pelo=$_POST['pelo_color'];
$color_de_ojos=$_POST['ojos_color'];
$complexion=$_POST['fisico'];
$mi_dieta_es=$_POST['dieta'];
$tomo_alcohol=$_POST['tomo_alcohol'];
$fumo=$_POST['fumo'];
$altura=(int)$_POST['altura'];
$peso=(int)$_POST['peso'];


if(strlen($altura)>=4){fatal_error('No puede haber m&aacute;s de 3 numeros en tu altura.-');}
db_query("UPDATE {$db_prefix}infop SET altura='$altura' WHERE id_user='{$user_settings['ID_MEMBER']}'", __FILE__, __LINE__);

if(strlen($peso)>=4){fatal_error('No puede haber m&aacute;s de 3 numeros en tu peso.-');}
db_query("UPDATE {$db_prefix}infop SET peso='$peso' WHERE id_user='{$user_settings['ID_MEMBER']}'", __FILE__, __LINE__);


if(empty($fumo) || $fumo=='no' || $fumo=='casualmente' || $fumo=='socialmente' || $fumo=='regularmente' || $fumo=='mucho'){
if(empty($fumo)){$texto7='';}
elseif($fumo=='no'){$texto7='No';}
elseif($fumo=='casualmente'){$texto7='Casualmente';}
elseif($fumo=='socialmente'){$texto7='Socialmente';}
elseif($fumo=='regularmente'){$texto7='Regularmente';}
elseif($fumo=='mucho'){$texto7='Mucho';}else{$texto7='';}}else{fatal_error('Hubo un error, intentar nuevamente.-');}
db_query("UPDATE {$db_prefix}infop SET fumo='$texto7' WHERE id_user='{$user_settings['ID_MEMBER']}'", __FILE__, __LINE__);


if(empty($tomo_alcohol) || $tomo_alcohol=='no' || $tomo_alcohol=='casualmente' || $tomo_alcohol=='socialmente' || $tomo_alcohol=='regularmente' || $tomo_alcohol=='mucho'){
if(empty($tomo_alcohol)){$texto8='';}
elseif($tomo_alcohol=='no'){$texto8='No';}
elseif($tomo_alcohol=='casualmente'){$texto8='Casualmente';}
elseif($tomo_alcohol=='socialmente'){$texto8='Socialmente';}
elseif($tomo_alcohol=='regularmente'){$texto8='Regularmente';}
elseif($tomo_alcohol=='mucho'){$texto8='Mucho';}else{$texto8='';}}else{fatal_error('Hubo un error, intentar nuevamente.-');}
db_query("UPDATE {$db_prefix}infop SET tomo_alcohol='$texto8' WHERE id_user='{$user_settings['ID_MEMBER']}'", __FILE__, __LINE__);


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
elseif($color_de_pelo=='calvo'){$texto3='Calvo';}else{$texto3='';}}{fatal_error('Hubo un error, intentar nuevamente.-');}
db_query("UPDATE {$db_prefix}infop SET color_de_pelo='$texto3' WHERE id_user='{$user_settings['ID_MEMBER']}'", __FILE__, __LINE__);


if(empty($color_de_ojos) || $color_de_ojos=='negros' || $color_de_ojos=='marrones' || $color_de_ojos=='celestes' || $color_de_ojos=='verdes' || $color_de_ojos=='grises'){
if(empty($color_de_ojos)){$texto4='';}
elseif($color_de_ojos=='negros'){$texto4='Negros';}
elseif($color_de_ojos=='marrones'){$texto4='Marrones';}
elseif($color_de_ojos=='celestes'){$texto4='Celestes';}
elseif($color_de_ojos=='verdes'){$texto4='Verdes';}
elseif($color_de_ojos=='grises'){$texto4='Grises';}else{$texto4='';}}else{fatal_error('Hubo un error, intentar nuevamente.-');}
db_query("UPDATE {$db_prefix}infop SET color_de_ojos='$texto4' WHERE id_user='{$user_settings['ID_MEMBER']}'", __FILE__, __LINE__);



if(empty($mi_dieta_es) || $mi_dieta_es=='vegetariana' || $mi_dieta_es=='lacto_vegetariana' || $mi_dieta_es=='organica' || $mi_dieta_es=='de_todo' || $mi_dieta_es=='comida_basura'){
if(empty($mi_dieta_es)){$texto6='';}
elseif($mi_dieta_es=='vegetariana'){$texto6='Vegetariana';}
elseif($mi_dieta_es=='lacto_vegetariana'){$texto6='Lacto Vegetariana';}
elseif($mi_dieta_es=='organica'){$texto6='Org&aacute;nica';}
elseif($mi_dieta_es=='de_todo'){$texto6='De todo';}
elseif($mi_dieta_es=='comida_basura'){$texto6='Comida basura';}else{$texto6='';}}else{fatal_error('Hubo un error, intentar nuevamente.-');}
db_query("UPDATE {$db_prefix}infop SET mi_dieta_es='$texto6' WHERE id_user='{$user_settings['ID_MEMBER']}'", __FILE__, __LINE__);


if(empty($complexion) || $complexion=='delgado' || $complexion=='atletico' || $complexion=='normal' || $complexion=='kilos_de_mas' || $complexion=='corpulento'){
if(empty($complexion)){$texto5='';}
elseif($complexion=='delgado'){$texto5='Delgado/a';}
elseif($complexion=='atletico'){$texto5='tl&eacute;tico';}
elseif($complexion=='normal'){$texto5='Normal';}
elseif($complexion=='kilos_de_mas'){$texto5='Algunos kilos de m&aacute;s';}
elseif($complexion=='corpulento'){$texto5='Corpulento/a';}else{$texto5='';}}else{fatal_error('Hubo un error, intentar nuevamente.-');}
db_query("UPDATE {$db_prefix}infop SET complexion='$texto5' WHERE id_user='{$user_settings['ID_MEMBER']}'", __FILE__, __LINE__);

Header("Location: /editar-apariencia/paso4/");exit();die();}

else{
$color_de_pelo=$_POST['pelo_color'];
$color_de_ojos=$_POST['ojos_color'];
$complexion=$_POST['fisico'];
$mi_dieta_es=$_POST['dieta'];
$fumo=$_POST['fumo'];
$tomo_alcohol=$_POST['tomo_alcohol'];
$altura=(int)$_POST['altura'];
$peso=(int)$_POST['peso'];
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
VALUES ('$altura','$peso','$texto6','$texto7','$texto8','$texto5','$texto3','$texto4','{$user_settings['ID_MEMBER']}')", __FILE__, __LINE__);
Header("Location: /editar-apariencia/paso4/");exit();die();}}






elseif($tipo=='4'){
if(!empty($agrearorefrescar)){
if(strlen($_POST['series_tv_favoritas'])>=501){fatal_error('No puedes escribir m&aacute;s de 500 (Quinientas) letras.-');}
if(strlen($_POST['musica_favorita'])>=501){fatal_error('No puedes escribir m&aacute;s de 500 (Quinientas) letras.-');}
if(strlen($_POST['deportes_y_equipos_favoritos'])>=501){fatal_error('No puedes escribir m&aacute;s de 500 (Quinientas) letras.-');}
if(strlen($_POST['libros_favoritos'])>=501){fatal_error('No puedes escribir m&aacute;s de 500 (Quinientas) letras.-');}
if(strlen($_POST['peliculas_favoritas'])>=501){fatal_error('No puedes escribir m&aacute;s de 500 (Quinientas) letras.-');}
if(strlen($_POST['comida_favorita'])>=501){fatal_error('No puedes escribir m&aacute;s de 500 (Quinientas) letras.-');}
if(strlen($_POST['mis_heroes_son'])>=501){fatal_error('No puedes escribir m&aacute;s de 500 (Quinientas) letras.-');}
if(strlen($_POST['mis_intereses'])>=501){fatal_error('No puedes escribir m&aacute;s de 500 (Quinientas) letras.-');}
if(strlen($_POST['hobbies'])>=501){fatal_error('No puedes escribir m&aacute;s de 500 (Quinientas) letras.-');}

$series_tv_favoritas=nohtml($_POST['series_tv_favoritas']);
$musica_favorita=nohtml($_POST['musica_favorita']);
$deportes_y_equipos_favoritos=nohtml($_POST['deportes_y_equipos_favoritos']);
$libros_favoritos=nohtml($_POST['libros_favoritos']);
$peliculas_favoritas=nohtml($_POST['peliculas_favoritas']);
$comida_favorita=nohtml($_POST['comida_favorita']);
$mis_heroes_son=nohtml($_POST['mis_heroes_son']);
$mis_intereses=nohtml($_POST['mis_intereses']);
$hobbies=nohtml($_POST['hobbies']);
db_query("UPDATE {$db_prefix}infop SET mis_intereses='$mis_intereses' WHERE id_user='{$user_settings['ID_MEMBER']}'", __FILE__, __LINE__);
db_query("UPDATE {$db_prefix}infop SET hobbies='$hobbies' WHERE id_user='{$user_settings['ID_MEMBER']}'", __FILE__, __LINE__);
db_query("UPDATE {$db_prefix}infop SET series_de_tv_favorita='$series_tv_favoritas' WHERE id_user='{$user_settings['ID_MEMBER']}'", __FILE__, __LINE__);
db_query("UPDATE {$db_prefix}infop SET musica_favorita='$musica_favorita' WHERE id_user='{$user_settings['ID_MEMBER']}'", __FILE__, __LINE__);
db_query("UPDATE {$db_prefix}infop SET deportes_y_equipos_favoritos='$deportes_y_equipos_favoritos' WHERE id_user='{$user_settings['ID_MEMBER']}'", __FILE__, __LINE__);
db_query("UPDATE {$db_prefix}infop SET libros_favoritos='$libros_favoritos' WHERE id_user='{$user_settings['ID_MEMBER']}'", __FILE__, __LINE__);
db_query("UPDATE {$db_prefix}infop SET peliculas_favoritas='$peliculas_favoritas' WHERE id_user='{$user_settings['ID_MEMBER']}'", __FILE__, __LINE__);
db_query("UPDATE {$db_prefix}infop SET comida_favorita='$comida_favorita' WHERE id_user='{$user_settings['ID_MEMBER']}'", __FILE__, __LINE__);
db_query("UPDATE {$db_prefix}infop SET mis_heroes_son='$mis_heroes_son' WHERE id_user='{$user_settings['ID_MEMBER']}'", __FILE__, __LINE__);

Header("Location: /editar-apariencia/");exit();die();}

else{
if(strlen($_POST['series_tv_favoritas'])>=501){fatal_error('No puedes escribir m&aacute;s de 500 (Quinientas) letras.-');}
if(strlen($_POST['musica_favorita'])>=501){fatal_error('No puedes escribir m&aacute;s de 500 (Quinientas) letras.-');}
if(strlen($_POST['deportes_y_equipos_favoritos'])>=501){fatal_error('No puedes escribir m&aacute;s de 500 (Quinientas) letras.-');}
if(strlen($_POST['libros_favoritos'])>=501){fatal_error('No puedes escribir m&aacute;s de 500 (Quinientas) letras.-');}
if(strlen($_POST['peliculas_favoritas'])>=501){fatal_error('No puedes escribir m&aacute;s de 500 (Quinientas) letras.-');}
if(strlen($_POST['comida_favorita'])>=501){fatal_error('No puedes escribir m&aacute;s de 500 (Quinientas) letras.-');}
if(strlen($_POST['mis_heroes_son'])>=501){fatal_error('No puedes escribir m&aacute;s de 500 (Quinientas) letras.-');}
if(strlen($_POST['mis_intereses'])>=501){fatal_error('No puedes escribir m&aacute;s de 500 (Quinientas) letras.-');}
if(strlen($_POST['hobbies'])>=501){fatal_error('No puedes escribir m&aacute;s de 500 (Quinientas) letras.-');}
$series_tv_favoritas=nohtml($_POST['series_tv_favoritas']);
$musica_favorita=nohtml($_POST['musica_favorita']);
$deportes_y_equipos_favoritos=nohtml($_POST['deportes_y_equipos_favoritos']);
$libros_favoritos=nohtml($_POST['libros_favoritos']);
$peliculas_favoritas=nohtml($_POST['peliculas_favoritas']);
$comida_favorita=nohtml($_POST['comida_favorita']);
$mis_heroes_son=nohtml($_POST['mis_heroes_son']);
$mis_intereses=nohtml($_POST['mis_intereses']);
$hobbies=nohtml($_POST['hobbies']);
db_query("INSERT INTO {$db_prefix}infop
(mis_intereses,hobbies,series_de_tv_favorita,musica_favorita,deportes_y_equipos_favoritos,libros_favoritos,peliculas_favoritas,comida_favorita,mis_heroes_son,id_user)
VALUES ('$mis_intereses','$hobbies','$series_tv_favoritas','$musica_favorita','$deportes_y_equipos_favoritos','$libros_favoritos','$peliculas_favoritas','$comida_favorita','$mis_heroes_son','{$user_settings['ID_MEMBER']}')", __FILE__, __LINE__);
Header("Location: /editar-apariencia/");exit();die();}}

}else{fatal_error('No podes estar aca, Gracias.-');}?>