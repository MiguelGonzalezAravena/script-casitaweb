<?php require("cw-conexion-seg-0011.php");
global $func, $context, $db_prefix,$modSettings,$user_settings,$user_info;
if($user_info['is_guest']){die();}
ignore_user_abort(true);
@set_time_limit(300);
$url='http://fmcasita.net/lyrics.php';
$nick1='bu10q';//teclado222
$nick2='MathiaJ_';//teclado222
$nick3='blazer696';//teclado222
$nick4='charlesssss';//teclado222
$nick5='akuma48461';//teclado222

$nicksARRAYS = array($nick1, $nick2, $nick3, $nick4, $nick5);
$nikcs=rand(0,sizeof($nicksARRAYS)-1);
$nick=$nicksARRAYS[$nikcs];

if($nick==$nick1){$idUSER='149792';}
elseif($nick==$nick2){$idUSER='149793';}
elseif($nick==$nick3){$idUSER='149795';}
elseif($nick==$nick4){$idUSER='149796';}
elseif($nick==$nick5){$idUSER='149797';}
else{Header("Location: $url");exit();die();}

$tituloedit=strtr($func['htmlspecialchars']($_POST['titulo']), array("\r" => '', "\n" => '', "\t" => ''));
$titulo=addcslashes($tituloedit, '"');
$titulo=trim($tituloedit);

$postedit=$func['htmlspecialchars']($_POST['contenido']);
$post=str_replace(array('"', '<', '>', '  ', "'", "�", "�"), array('&quot;', '&lt;', '&gt;', ' &nbsp;', '&#39;', '&#8217;', '&#8216;'), $postedit);
$post= preg_replace('~<br(?: /)?' . '>~i', "\n", $postedit);
$post=trim($postedit);

$categorias=(int)$_POST['categorias'];
$tags=trim(strtolower($_POST['tags']));
$privado=(int)$_POST['privado'];


if(empty($titulo)){Header("Location: $url");exit();die();}
if(empty($post)){Header("Location: $url");exit();die();}
if(empty($categorias)){Header("Location: $url");exit();die();}
if(empty($tags)){Header("Location: $url");exit();die();}
    


if(strlen($_POST['titulo'])<3){Header("Location: $url");exit();die();}
if(strlen($_POST['titulo'])>=61){Header("Location: $url");exit();die();}
if(strlen($_POST['contenido'])<=60){Header("Location: $url");exit();die();}
if(strlen($_POST['contenido'])>$modSettings['max_messageLength']){Header("Location: $url");exit();die();}


$context['contadorsss']=mysqli_num_rows(db_query("
SELECT ID_BOARD
FROM {$db_prefix}boards
WHERE ID_BOARD='$categorias'
LIMIT 1", __FILE__, __LINE__));
if(empty($context['contadorsss'])){Header("Location: $url");exit();die();}

// TAGS
$ak=explode(',',$tags);
$Nn=implode(',', array_diff($ak, array_values(array(''))));
$a=explode(',',$Nn);
$c=sizeof($a);
 if($c < 4){Header("Location: $url");exit();die();}
 
if($c > 5){$c=5;}

if($user_settings['posts']>='500'){$dddderrr=(int)$_POST['nocom'];
if($dddderrr=='0' || $dddderrr=='1'){$nocom=$dddderrr;}else{$nocom='0';}
}else{$nocom='0';}


db_query("INSERT INTO {$db_prefix}messages (ID_BOARD, ID_MEMBER, subject, body, posterName, posterEmail, posterTime, hiddenOption, color, anuncio, posterIP, smileysEnabled, sticky, visitas) 

VALUES ('$categorias', '{$idUSER}', SUBSTRING('$titulo', 1,70), SUBSTRING('$post', 1, 65534), SUBSTRING('{$nick}', 1, 255), SUBSTRING('blazer696@gmail.com', 1, 255), ".time().", '$privado', '$colorsticky','$anuncio', SUBSTRING('66.249.71.18', 1, 255), '$nocom', '$principal', 1)", __FILE__, __LINE__);

$ID_TOPICTA=db_insert_id();


//tags
for($i=0;$i<$c;++$i){    
$lvccct=db_query("SELECT id FROM ({$db_prefix}tags) WHERE palabra='$a[$i]' AND rango=1 LIMIT 1", __FILE__, __LINE__); while($asserr=mysqli_fetch_assoc($lvccct)){$idse=$asserr['id'];}
$idse=isset($idse) ? $idse : '';
$a[$i]=nohtml($a[$i]);
if(!empty($idse)){db_query("UPDATE {$db_prefix}tags SET cantidad=cantidad+1 WHERE id='$idse' AND rango=1 LIMIT 1", __FILE__, __LINE__); $rg='0';}else{$rg='1';}

db_query("INSERT INTO {$db_prefix}tags (id_post,palabra,cantidad,rango) VALUES ('$ID_TOPICTA',SUBSTRING('$a[$i]', 1,65),1,'$rg')", __FILE__, __LINE__);}
//fin tags

estadisticastopic();

Header("Location: ".$url);exit();die();

?>