<?php require("cw-conexion-seg-0011.php");
global $func, $context, $db_prefix, $user_settings, $modSettings;

if($user_info['is_guest']){die();}
ignore_user_abort(true);
@set_time_limit(300);

timeforComent(1);

$id_topics=(int)$_POST['id_post'];

$datos=db_query("
SELECT men.ID_BOARD,men.ID_TOPIC,men.ID_MEMBER,men.sticky
FROM ({$db_prefix}messages as men)
WHERE men.ID_TOPIC='{$id_topics}' AND men.eliminado=0".(!empty($user_info['is_mods']) || !empty($user_info['is_admin']) ? '' : " AND men.ID_MEMBER='{$user_settings['ID_MEMBER']}'")."
LIMIT 1",__FILE__, __LINE__);
while($data44=mysqli_fetch_assoc($datos)){
$id_cat=$data44['ID_BOARD'];
$stikccss=$data44['sticky'];
$id_post=$data44['ID_TOPIC'];
$id_user=$data44['ID_MEMBER'];}

$error=mysqli_num_rows($datos);
if(empty($error)){fatal_error('No tienes permisos para editar este post.-');}


$tituloedit=strtr($func['htmlspecialchars']($_POST['titulo']), array("\r" => '', "\n" => '', "\t" => ''));
$titulo=addcslashes($tituloedit, '"');
$titulo=trim($tituloedit);


$postedit=$func['htmlspecialchars']($_POST['contenido']);
$post=str_replace(array('"', '<', '>', '  ', "'", "�", "�"), array('&quot;', '&lt;', '&gt;', ' &nbsp;', '&#39;', '&#8217;', '&#8216;'), $postedit);
$post= preg_replace('~<br(?: /)?' . '>~i', "\n", $postedit);
$post=trim($postedit);

$categorias=(int)$_POST['categorias'];
$tags=trim(strtolower($_POST['tags']));
$privado=(int) $_POST['privado'];

if(empty($titulo)){fatal_error('Falto escribirle un titulo.');}
if(empty($post)){fatal_error('Falto escribir el post.');}
if(empty($categorias)){fatal_error('Falto asignarle la categor&iacute;a.');}
if(empty($tags)){fatal_error('Falto agregarle los tags.');}

if(strlen($_POST['titulo'])<3){fatal_error('El titulo no puede tener menos de <b>3 letras</b>.');}
if(strlen($_POST['titulo'])>=61){fatal_error('El titulo no puede tener m&aacute;s de <b>60 letras</b>.');}
if(strlen($_POST['contenido'])<=60){fatal_error('El post no puede tener menos de <b>60 letras</b>.');}
if(strlen($_POST['contenido'])>$modSettings['max_messageLength']){fatal_error('El post no puede tener m&aacute;s de <b>'.$modSettings['max_messageLength'].' letras</b>.');}

$resquest=db_query("
SELECT description
FROM {$db_prefix}boards
WHERE ID_BOARD='$categorias'
LIMIT 1", __FILE__, __LINE__);
while($row=mysqli_fetch_assoc($resquest)){$descript=$row['description'];}
$descript=isset($descript) ? $descript : '';
if(empty($descript)){fatal_error('La categor&iacute;a especificada no existe.');}
mysqli_free_result($resquest);

$ak=explode(',',$tags);
$Nn=implode(',', array_diff($ak, array_values(array(''))));
$a=explode(',',$Nn);
$c=sizeof($a);
if($c < 4){fatal_error('No se permiten menos de 4 tags.');}
if($c > 5){$c=5;}

if($user_info['is_admin']){
	$aa45s1dsasd=(int)$_POST['anuncio'];
	if($aa45s1dsasd=='0' || $aa45s1dsasd=='1'){$anuncio=$aa45s1dsasd;}else{$anuncio='0';}}


if($user_settings['posts']>='500'){$dddderrr=(int) $_POST['nocom'];
if($dddderrr=='0' || $dddderrr=='1'){$nocom=$dddderrr;}else{$nocom='0';}
	}else{$nocom='0';}


if($user_info['is_admin'] || $user_info['is_mods']){
$adasdeeea=(int)$_POST['principal'];
if(!$adasdeeea || $adasdeeea){$principal=$adasdeeea;}else{$principal='0';}

	if($principal=='1'){	
	if($_POST['colorsticky']=='#000000'){$colorsticky='';}else{
	if(strlen($_POST['colorsticky'])>=1){
	if(strlen($_POST['colorsticky'])<>7){fatal_error('El color ingresado est&aacute; mal escrito.-');}
	$colorsticky=$_POST['colorsticky'];}else{$colorsticky=='';}}
	}else{$colorsticky='';}}
else{ $principal=$stikccss; }

$tiempo=time();

db_query("UPDATE {$db_prefix}messages
SET ID_BOARD='$categorias',subject='$titulo',body='$post',modifiedTime='$tiempo',modifiedName='{$user_settings['realName']}',hiddenOption='$privado',color='$colorsticky',anuncio='$anuncio', smileysEnabled='$nocom' ,sticky='$principal'
WHERE ID_TOPIC='$id_topics'
LIMIT 1", __FILE__, __LINE__);

if(($user_info['is_admin'] || $user_info['is_mods']) && $id_user<>$user_settings['ID_MEMBER']){
$causa=nohtml($_POST['causa']);
if(empty($causa)){fatal_error('No agregastes la causa de la eliminaci&oacute;n.-');}
if(strlen($causa) < 5){fatal_error('Esmerate y da una causa m&aacute;s detallada.-');}
logAction('modify', array('topic' => $titulo. ' (ID: '.$id_topics.')', 'member' => $id_user,'causa' => $causa));}
	

//tags
db_query("DELETE FROM {$db_prefix}tags WHERE id_post='{$id_topics}' AND rango=0", __FILE__, __LINE__);
for($i=0;$i<$c;++$i){
$lvccct=db_query("SELECT id FROM ({$db_prefix}tags) WHERE palabra='$a[$i]' AND rango=1 LIMIT 1", __FILE__, __LINE__); while($asserr=mysqli_fetch_assoc($lvccct)){$idse=$asserr['id'];}
$idse=isset($idse) ? $idse : '';
$a[$i]=nohtml($a[$i]);
if(!empty($idse)){db_query("UPDATE {$db_prefix}tags SET cantidad=cantidad+1 WHERE id='$idse' AND rango=1 LIMIT 1", __FILE__, __LINE__); $rg='0';}else{$rg='1';}

db_query("INSERT INTO {$db_prefix}tags (id_post,palabra,cantidad,rango) VALUES ('$id_topics',SUBSTRING('$a[$i]', 1,65),1,'$rg')", __FILE__, __LINE__);}
//fin tags


$_SESSION['edit']='1';
$_SESSION['ultima_accionTIME']=time();


$urls='/post/'.$id_topics.'/'.$descript.'/'.urls($titulo).'.html';
PostAccionado('Post editado','Tu post "<strong>'.censorText(nohtml2(nohtml($titulo))).'</strong>" ha sido editado correctamente.', $urls, 'Ir al post'); ?>