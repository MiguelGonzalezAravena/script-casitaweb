<?php include("header-seg-1as4d4a777.php");
global $func, $context, $settings, $db_prefix, $options, $txt,$con, $scripturl;
global $tranfer1,$user_settings,$func,$ID_MEMBER, $context,$db_prefix;
global $prefijo,$user_settings, $user_info, $ID_MEMBER,$context, $txt, $modSettings;
if($user_info['is_admin'] || $user_info['is_mods']){
ignore_user_abort(true);
@set_time_limit(300);
$id_articulo=(int)$_POST['id_articulo'];
$catlist=db("
SELECT id
FROM {$prefijo}articulos
WHERE id='{$id_articulo}'
ORDER BY id ASC
LIMIT 1", __FILE__, __LINE__);
while($dat=mysqli_fetch_assoc($catlist)){$qid=$dat['id'];}
if(empty($qid)){falta('El articulo no existe.-');}

if(empty($id_articulo)){falta('Debe seleccionar un articulo.-');}
$tituloedit=strtr($func['htmlspecialchars']($_POST['titulo']), array("\r" => '', "\n" => '', "\t" => ''));
$titulo=addcslashes($tituloedit, '"');
$titulo=trim(censorText($tituloedit));
$postedit=$func['htmlspecialchars'](stripslashes($_POST['contenido']), ENT_QUOTES);
$post=str_replace(array('"', '<', '>', '  '), array('&quot;', '&lt;', '&gt;', ' &nbsp;'), $postedit);
$post= preg_replace("~\[hide\](.+?)\[\/hide\]~i", "&nbsp;", $postedit);
$post= preg_replace(array('~\n?\[hide.*?\].+?\[/hide\]\n?~is', '~^\n~', '~\[/hide\]~'), "&nbsp;", $postedit);
$post= preg_replace('~<br(?: /)?' . '>~i', "\n", $postedit);
$post=trim(censorText($postedit));
$categorias=(int)$_POST['categorias'];

if(empty($titulo)){falta('Falto escribirle un titulo.-');}
if(empty($post)){falta('Falto escribir el articulo.-');}
if(empty($categorias)){falta('Falto asignarle la categor&iacute;a.-');}

if(strlen($_POST['titulo'])<3){falta('El titulo no puede tener menos de <b>3 letras</b>.-');}
if(strlen($_POST['titulo'])>=61){falta('El titulo no puede tener m&aacute;s de <b>60 letras</b>.-');}
if(strlen($_POST['contenido'])<=60){falta('El post no puede tener menos de <b>60 letras</b>.-');}
if(strlen($_POST['contenido'])>$modSettings['max_messageLength']){falta('El post no puede tener m&aacute;s de <b>'.$modSettings['max_messageLength'].' letras</b>.-');}

$context['contadorsss']=mysqli_num_rows(db("
SELECT catid
FROM {$prefijo}cats
WHERE catid='$categorias'
LIMIT 1", __FILE__, __LINE__));
if(empty($context['contadorsss'])){falta('La categor&iacute;a especificada no existe.-');}

db("UPDATE {$prefijo}articulos SET titulo='$titulo',contenido='$post',categoria='$categorias',fechaedit=".time()." WHERE id='$id_articulo' LIMIT 1", __FILE__, __LINE__);

Header("Location: /articulo/$id_articulo");}
else{falta('Debes ser de Staff.-');}

include("footer-seg-145747dd.php");
?>