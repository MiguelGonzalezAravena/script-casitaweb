<?php
function tiempo1($fecha){$mesesano2 = array("1","2","3","4","5","6","7","8","9","10","11","12") ;
$diames2 = date('j',$fecha); $mesano2 = date('n',$fecha) - 1 ; $ano2 = date('Y',$fecha);
$seg2=date('s',$fecha); $hora2=date('H',$fecha); $min2=date('i',$fecha);
echo $diames2.'.'.$mesesano2[$mesano2].'.'.$ano2.' a las '.$hora2.':'.$min2.':'.$seg2;}

function tiempo2($fecha){$mesesano2 = array("1","2","3","4","5","6","7","8","9","10","11","12") ;
$diames2 = date('j',$fecha); $mesano2 = date('n',$fecha) - 1 ; $ano2 = date('Y',$fecha);
$seg2=date('s',$fecha); $hora2=date('H',$fecha); $min2=date('i',$fecha);
echo $diames2.'-'.$mesesano2[$mesano2].'-'.$ano2.' '.$hora2.':'.$min2.':'.$seg2.'';}

function PostAccionado($title='',$mje='',$url='',$btn=''){
global $tranfer1,$internetNO,$mbname;    
unset($_POST);
unset($_GET);

 if(empty($title)){$title='En mantenimiento';}  
 if(empty($mje)){$mje=$mbname.' se encuentra en mantenimiento, perdon por las molestias. Intente en segundos.';}  
 if(empty($btn)){$btnNUM='0';}else{$btnNUM='1';}  
 
?>
<style type="text/css">
body{margin:0px;width: 100%;color:#fff;font-family:Arial;padding:8% 0 0 0;_padding:15% 0 0 0;#padding:15% 0 0 0;background-color:#D35F2C;} a{color:#DBC7B6;} a:hover{color:#FFF;} .dborder{ background-image: url(<?php echo $tranfer1; ?>/dobleborder_Y.gif); background-repeat: repeat-y; padding: 1px;height: 275px;} .dborderX{clear: both; background-image: url(<?php echo $tranfer1; ?>/dobleborder_X.gif); background-repeat: repeat-x; padding: 1px; height: 2px; } input.inut {background: url('<?php echo $tranfer1; ?>/input_mje.png');outline: none;color: #fff;cursor: pointer;clear: left;width: 77px;text-shadow: #BC5427 0px 2px 2px;height: 29px; margin: 0 0 0 0; padding: 0; border:none;} input.inut:active{background-position: 0 -29px!important;outline:0px;} </style>
<?php $sastitle=''.$title.' - '.$mbname.'';
echo'<title>'.$sastitle.'</title></head><body>
<div style="width: 100%;" align="center"> <div style="width: 900px;">


<div style="margin-bottom:10%;">

<div style="float:left;width: 49%;text-align:right;">  
<div style="padding: 100px 0 0 0;"><img src="'.$tranfer1.'/logo-sin-casita.gif" title="'.$sastitle.'" alt="" /></div>
</div>
  
  
<div style="float:left;width: 1%;"><div class="dborder">&#32;</div></div>
    
<div style="float:left;width: 49%;text-align:left;">
<div style="padding: 5px 0 0 0;">

<div style="padding: 0 0 95px 0;color:#FFC6AF;text-shadow: #BC5427 0px 2px 2px;"><strong>'.$title.'</strong></div>
<div style="padding: 0 0 75px 0;">
<span style="color:#DBC7B6;">'.$mje.'</span></div>';
if($btnNUM){ echo'<input type="submit" class="inut" value="Principal" title="Principal" onclick="location.href=\'/\'" />&#32;&#32;<input type="submit" class="inut" value="'.$btn.'" title="'.$btn.'"  onclick="location.href=\''.$url.'\'"  />'; }
echo'</div> 
</div>


<div style="clear: both;"></div>
</div>


<div style="font-size:10px;font-family:Arial;">
<div class="dborderX">&#32;</div>

<div style="float:left;width: 50%;text-align:left;">&copy; <strong>'.$mbname.' 2010</strong></div>
 
<div style="float:left;width: 50%;text-align:right;">'.fotttter().'</div>


<div style="clear: both;"></div>
</div>



</div> </div>
</body></html>';
    die();
}

function fotttter(){
$res='<a href="/protocolo/" title="Protocolo">Protocolo</a> | <a href="/enlazanos/" title="Enlazanos">Enlazanos</a> | <a href="/widget/" title="Widget">Widget</a> | <a href="/contactanos/" title="Contacto">Contacto</a> | <a href="/recomendar/" title="Recomendar CasitaWeb!">Recomendar CasitaWeb!</a> | <a href="/mapa-del-sitio/" title="Mapa del sitio">Mapa del sitio</a>';

return $res;}

function notificacionQUE($que='', $url='', $extra='', $boton=''){global $tranfer1;
if(empty($boton)){
$ir='&nbsp;<a href="'.$url.'" target="_blank"><img style="display:inline-block;#display: inline-block;_display: inline;" src="'.$tranfer1.'/icons/application-resize.png" class="png" alt="" title=""/></a>';}else{$ir='';}

if($que == '1'){$resultado='coment&oacute;&nbsp;un&nbsp;post&nbsp;tuyo'.$ir.'';}
elseif($que == '2'){$resultado='coment&oacute;&nbsp;una&nbsp;imagen&nbsp;tuya'.$ir.'';}
elseif($que == '3'){$resultado='coment&oacute;&nbsp;tu&nbsp;muro'.$ir.'';}
elseif($que == '4'){$resultado='dej&oacute;&nbsp;<strong>'.$extra.'</strong>&nbsp;puntos&nbsp;en&nbsp;una&nbsp;imagen&nbsp;tuya'.$ir.'';}
elseif($que == '5'){$resultado='dej&oacute;&nbsp;<strong>'.$extra.'</strong>&nbsp;puntos&nbsp;en&nbsp;un&nbsp;post&nbsp;tuyo'.$ir.'';}
elseif($que == '6'){$resultado='agreg&oacute;&nbsp;a&nbsp;favoritos&nbsp;un&nbsp;post&nbsp;tuyo'.$ir.'';}
elseif($que == '7'){$resultado='agreg&oacute;&nbsp;a&nbsp;favoritos&nbsp;una&nbsp;imagen&nbsp;tuya'.$ir.'';}
elseif($que == '8'){$resultado='actualiz&oacute;&nbsp;una&nbsp;charla&nbsp;del&nbsp;muro'.$ir.'';}
elseif($que == '9'){$resultado='coment&oacute; un post en la baticueva'.$ir.'';}
elseif($que == '10'){$resultado='agreg&oacute; un post en la baticueva'.$ir.'';}
elseif($que == '11'){$resultado='se unio a tu comunidad'.$ir.'';}
elseif($que == '12'){$resultado='coment&oacute; un tema tuyo'.$ir.'';}
else{$resultado='';}

return $resultado;} 

function notificacionAGREGAR($us,$q,$f='',$urlExtra=''){
global $user_settings, $db_prefix,$boardurl;
if(empty($urlExtra)){
$aURL=str_replace('http://','',$boardurl);
$bURL=explode($aURL,censorText($_SERVER['HTTP_REFERER']));
$urlFinal=$bURL[1];}else{$urlFinal=$urlExtra;}

if($us <> $user_settings['ID_MEMBER']){
$date=time();
db_query("INSERT INTO {$db_prefix}notificaciones (url,que,a_quien,por_quien,fecha,extra) VALUES ('$urlFinal','$q','$us','{$user_settings['ID_MEMBER']}','$date','$f')",__FILE__, __LINE__);

db_query("UPDATE {$db_prefix}members 
SET notificacionMonitor=notificacionMonitor+1 
WHERE ID_MEMBER='{$us}' 
LIMIT 1", __FILE__, __LINE__);}
} 

function signosyletras($valor){					
$valor = str_replace("�", "&aacute;", $valor);
$valor = str_replace("�", "&eacute;", $valor);
$valor = str_replace("�", "&iacute;", $valor);
$valor = str_replace("�", "&oacute;", $valor);
$valor = str_replace("�", "&uacute;", $valor);
$valor = str_replace("�", "&ntilde;", $valor);
$valor = str_replace("�", "&Aacute;", $valor);
$valor = str_replace("�", "&Eacute;", $valor);
$valor = str_replace("�", "&Iacute;", $valor);
$valor = str_replace("�", "&Oacute;", $valor);
$valor = str_replace("�", "&Uacute;", $valor);
$valor = str_replace("�", "&�tilde;", $valor);
$valor = str_replace("!", "&#33;", $valor);
$valor = str_replace("�", "&iexcl;", $valor);
$valor = str_replace("�", "&iquest;", $valor);
$valor = str_replace("�", "&ouml;", $valor);
$valor = str_replace("�", "&Ouml;", $valor);
$valor = str_replace("�", "&ordm;", $valor);
$valor = str_replace("�", "&#176;", $valor);
$valor = str_replace("�", "&reg;", $valor);
$valor = str_replace("�", "&#169;", $valor);
return $valor;}

function seguridad($variable){$variable = addslashes(trim($variable));return $variable;}
//--------------------------

function anuncio_300x250() {
  global $boardurl;

  echo '
    <p align="center">
      <iframe src="' . $boardurl . '/web/cw-ads.php?tamanio=300x250" width="300px" height="250px" frameborder="0" scrolling="no" marginheight="0px" marginwidth="0px" marginheight="0px" marginwidth="0px">.</iframe>
    </p>';
}

function anuncio1_120x240() {
  global $boardurl;

  echo '
    <p align="center">
      <iframe src="' . $boardurl . '/web/cw-ads.php?tamanio=160x600" width="120px" height="600px" frameborder="0" scrolling="no" marginheight="0px" marginwidth="0px" marginheight="0px" marginwidth="0px">.</iframe>
    </p>';
}

function anuncio2_120x240() {
  global $boardurl;

  echo '
    <p align="center">
      <iframe src="' . $boardurl . '/web/cw-ads.php?tamanio=160x600" width="120px" height="600px" frameborder="0" scrolling="no" marginheight="0px" marginwidth="0px" marginheight="0px" marginwidth="0px">.</iframe>
    </p>';
}

function g_anuncio_160x600() {
  global $boardurl;

  echo '
    <p align="center">
      <iframe src="' . $boardurl . '/web/cw-ads.php?tamanio=160x600" width="120px" height="600px" frameborder="0" scrolling="no" marginheight="0px" marginwidth="0px" marginheight="0px" marginwidth="0px">.</iframe>
    </p>';
}

function anuncio_468x60() {
  global $boardurl;
  echo '
    <p align="center">
      <iframe src="' . $boardurl . '/web/cw-ads.php?tamanio=468x60" width="468px" height="60px" frameborder="0" scrolling="no" marginheight="0px" marginwidth="0px">.</iframe>
    </p>';
}

function anuncio_728x90() {
  global $boardurl;

  echo '
    <p align="center">
      <iframe src="' . $boardurl . '/web/cw-ads.php?tamanio=728x90" width="728px" height="90px" frameborder="0" scrolling="no" marginheight="0px" marginwidth="0px">.</iframe>
    </p>';
}

function g_anuncio_728x90() {
  global $boardurl;

  echo '
    <p align="center">
      <iframe src="' . $boardurl . '/web/cw-ads.php?tamanio=728x90" width="728px" height="90px" frameborder="0" scrolling="no" marginheight="0px" marginwidth="0px">.</iframe>
    </p>';
}

function g_anuncio_125x125() {
  echo'<p align="center">PUBLICIDAD</p>';
}

function anuncio_234x60() {
  global $boardurl;

  echo '
    <p align="center">
      <iframe src="' . $boardurl . '/web/cw-ads.php?tamanio=234x60" width="234px" height="60px" frameborder="0" scrolling="no" marginheight="0px" marginwidth="0px">.</iframe>
    </p>';
}

function anuncio_160x600() {
  global $boardurl;

  echo '
    <p align="center">
      <iframe src="' . $boardurl . '/web/cw-ads.php?tamanio=160x600" width="120px" height="600px" frameborder="0" scrolling="no" marginheight="0px" marginwidth="0px">.</iframe>
    </p>';
}

function pts_sumar_grup($valor) {
  global $db_prefix;

  $request = db_query("
    SELECT ID_MEMBER, posts
    FROM {$db_prefix}members
    WHERE ID_MEMBER = '{$valor}'", __FILE__, __LINE__);

  while ($grup = mysqli_fetch_assoc($request)) { 
    $grup['posts'] = $grup['posts'];

    if ($grup['posts'] < 15) {
      db_query("
        UPDATE {$db_prefix}members
        SET ID_POST_GROUP = 4
        WHERE ID_MEMBER = {$valor}
        LIMIT 1", __FILE__, __LINE__);
    } else if ($grup['posts'] >= 15 && $grup['posts'] < 250) {
      db_query("
        UPDATE {$db_prefix}members
        SET ID_POST_GROUP = 5
        WHERE ID_MEMBER = {$valor}
        LIMIT 1", __FILE__, __LINE__);
    } else if ($grup['posts'] >= 250 && $grup['posts'] < 500) {
      db_query("
        UPDATE {$db_prefix}members
        SET ID_POST_GROUP = 9
        WHERE ID_MEMBER = {$valor}
        LIMIT 1", __FILE__, __LINE__);
    } else if ($grup['posts'] >= 500 && $grup['posts'] < 1000) {
      db_query("
        UPDATE {$db_prefix}members
        SET ID_POST_GROUP = 10
        WHERE ID_MEMBER = {$valor}
        LIMIT 1", __FILE__, __LINE__);
    } else if ($grup['posts'] >= 1000 && $grup['posts'] < 1500) {
      db_query("
        UPDATE {$db_prefix}members
        SET ID_POST_GROUP = 6
        WHERE ID_MEMBER = {$valor}
        LIMIT 1", __FILE__, __LINE__);
    } else if ($grup['posts'] >= 1500) {
      db_query("
        UPDATE {$db_prefix}members
        SET ID_POST_GROUP = 8
        WHERE ID_MEMBER = {$valor}
        LIMIT 1", __FILE__, __LINE__);
    }
  }

  mysqli_free_result($request);
}

function pais($valor) {				
  $valor = str_replace('ar', 'Argentina', $valor);
  $valor = str_replace('bo', 'Bolivia', $valor);
  $valor = str_replace('br', 'Brasil', $valor);
  $valor = str_replace('cl', 'Chile', $valor);
  $valor = str_replace('co', 'Colombia', $valor);
  $valor = str_replace('cr', 'Costa Rica', $valor);
  $valor = str_replace('cu', 'Cuba', $valor);
  $valor = str_replace('ec', 'Ecuador', $valor);
  $valor = str_replace('es', 'Espa&ntilde;a', $valor);
  $valor = str_replace('gt', 'Guatemala', $valor);
  $valor = str_replace('it', 'Italia', $valor);
  $valor = str_replace('mx', 'M&eacute;xico', $valor);
  $valor = str_replace('py', 'Paraguay', $valor);
  $valor = str_replace('pe', 'Per&uacute;', $valor);
  $valor = str_replace('pt', 'Portugal', $valor);
  $valor = str_replace('pr', 'Puerto Rico', $valor);
  $valor = str_replace('uy', 'Uruguay', $valor);
  $valor = str_replace('ve', 'Venezuela', $valor);
  $valor = str_replace('ot', 'Otro', $valor);

  return $valor;
}

function menuser($user) {
global $tranfer1, $scripturl, $context, $txt, $no_firma, $no_avatar, $db_prefix, $options, $ID_MEMBER, $modSettings, $boardurl, $memberContext, $themeUser;

function sexo1($valor)
{$valor = str_replace('1', 'Masculino', $valor);
$valor = str_replace('2', 'Femenino', $valor);
return $valor;}

function sexo2($valor){
global $tranfer1;
$valor = str_replace('1', '<img alt="Hombre" title="Hombre" src="'.$tranfer1.'/Male.gif" />', $valor);
$valor = str_replace('2', '<img alt="Mujer" title="Mujer" src="'.$tranfer1.'/Female.gif" />', $valor);
return $valor;}

if(!$context['user']['is_admin']){$shas=' AND ID_BOARD<>142';}else{$shas='';}

$context['postuser']=mysqli_num_rows(db_query("SELECT ID_MEMBER FROM {$db_prefix}messages WHERE ID_MEMBER='{$user}'$shas", __FILE__, __LINE__));

$userse = db_query("
SELECT mem.ID_MEMBER,mem.memberName,mem.avatar,mem.personalText,mem.ID_POST_GROUP,mem.ID_GROUP,mem.realName,mem.usertitle,mem.gender,mem.topics,mem.signature,mem.posts,mem.memberIP
FROM ({$db_prefix}members as mem)
WHERE mem.ID_MEMBER='{$user}'", __FILE__, __LINE__);
while($row = mysqli_fetch_assoc($userse)){
  $context['memberName']=$row['memberName'];
  $context['avatar']=$row['avatar'];
  $context['personalText']=$row['personalText'];	
  $context['ID_POST_GROUP']=$row['ID_POST_GROUP'];
  $context['ID_GROUP']=$row['ID_GROUP'];
  $context['realName']=$row['realName'];
  $context['usertitle']=$row['usertitle'];
  $context['gender']=$row['gender'];
  $context['topics']=$row['topics'];
  $context['firma']=$row['signature'];
  $context['money']=$row['posts'];
  $context['ip']=$row['memberIP'];
  $context['ID_MEMBER']=$row['ID_MEMBER'];}
mysqli_free_result($userse);		
echo'<div class="box_140" style="float:left; margin-right:8px;width: 140px;">
<div class="box_title" style="width: 138px;"><div class="box_txt box_140-34">Publicado por:</div>
<div class="box_rss"><a href="/rss/post-user/'.$context['realName'].'"><div style="height: 16px; width: 16px; cursor: pointer;" class="feed png"><img alt="" src="'.$tranfer1.'/espacio.gif" class="png" height="16px" width="16px" /></div></a></div></div><div class="windowbg" style="width: 130px; padding: 4px;overflow: hidden; ">
<center>';
$idgrup=$context['ID_POST_GROUP'];
$idgrup2=$context['ID_GROUP'];
$userse2 = db_query("
SELECT g.groupName,g.ID_GROUP
FROM {$db_prefix}membergroups as g
WHERE g.ID_GROUP=$idgrup", __FILE__, __LINE__);
while($row2 = mysqli_fetch_assoc($userse2))
{$membergropu=$row2['groupName'];}
mysqli_free_result($userse2);		
$userse3 = db_query("SELECT g.groupName,g.ID_GROUP
FROM {$db_prefix}membergroups as g
WHERE g.ID_GROUP=$idgrup2", __FILE__, __LINE__);
while($row2 = mysqli_fetch_assoc($userse3))
{$membergropu2=$row2['groupName'];}
mysqli_free_result($userse3);

$medallavr=db_query("SELECT g.ID_GROUP,g.stars FROM {$db_prefix}membergroups as g WHERE g.ID_GROUP=".(!empty($idgrup2) ? $idgrup2 : $idgrup)."", __FILE__, __LINE__);
while($row7 = mysqli_fetch_assoc($medallavr)){$medalla=$row7['stars'];}
mysqli_free_result($medallavr);

      if ($modSettings['avatar_action_too_large'] == 'option_html_resize' || $modSettings['avatar_action_too_large'] == 'option_js_resize')
      {
        if (!empty($modSettings['avatar_max_width_external']))
          $context['user']['avatar']['width'] = $modSettings['avatar_max_width_external'];
        if (!empty($modSettings['avatar_max_height_external']))
           $context['user']['avatar']['height'] = $modSettings['avatar_max_height_external'];
      }
  
if (!empty($context['avatar']))
    $context['user']['avatar']['image'] = '<img src="'.$context['avatar'].'"' . (isset($context['user']['avatar']['width']) ? ' width="' . $context['user']['avatar']['width'] . '"' : '') . (isset($context['user']['avatar']['height']) ? ' height="' . $context['user']['avatar']['height'] . '"' : '') . ' alt="" class="avatar" border="0" onerror="error_avatar(this)" />';


if ($context['avatar']){
echo '<div class="fondoavatar" style="overflow:hidden;width:130px;" align="center"><a href="/perfil/', $context['memberName'], '" title="Ver Perfil">'.$context['user']['avatar']['image'].'</a><br /><span class="mp">'.censorText($context['personalText']).'</span></div>';
}
else
echo '<div class="fondoavatar" style="overflow: auto; width: 130px;" align="center"><a href="/perfil/', $context['memberName'], '" title="Ver Perfil"><img src="'.$no_avatar.'" border="0" alt="Sin Avatar"  onerror="error_avatar(this)" /></a><br /><span class="mp">'.censorText($context['personalText']).'</span></div>';

echo'</center><br/>';

  echo'<a href="/perfil/'.$context['memberName'].'" style="font-size:14px;color:#FF6600;"><strong>'.$context['realName'].'</strong></a><br />';
    
      echo '<strong style="font-size:12px;color:#747474;text-shadow: #6A5645 0px 1px 1px;">'.(!empty($membergropu2) ? $membergropu2 : $membergropu).'</strong><br />';
      
      echo '<span title="', (!empty($membergropu2) ? $membergropu2 : $membergropu), '"><img alt="" src="',str_replace("1#rangos", "$tranfer1/rangos", $medalla), '" /></span>';
      
       
      echo '&nbsp;<span title="'. sexo1($context['gender']) . '">'. sexo2($context['gender']) . '</span>';
      if($context['usertitle'])
      {echo'&nbsp;<img alt="" width="16px" height="11px" title="'. pais($context['usertitle'])  . '" src="'.$tranfer1.'/icons/banderas/'.$context['usertitle'].'.gif" />';}
            else{echo'&nbsp;<img alt="" width="16px" height="11px" src="'.$tranfer1.'/icons/banderas/ot.gif" />';}
if(!$context['user']['is_guest']){echo'&nbsp;<a href="/web/cw-TEMPenviarMP.php?user='.$context['memberName'].'" title="Enviar MP a '.$context['memberName'].'" class="boxy" ><img alt="" src="'.$tranfer1.'/icons/mensaje_para.gif" border="0" /></a>';}

echo'<br /><br /><div class="hrs"></div><br /><div class="fondoavatar" style="overflow:hidden;width:130px;">';

// aca marca los comentarios de los usuarios


if($context['allow_admin']){$d10='<a href="/perfil/'.$context['memberName'].'/puntosp">'; $d2='</a>';}
else{$d10=''; $d2='';}
echo '<b style="color:#FE8F47;text-shadow: #6A5645 0px 1px 1px;">PUNTOS:</b>&nbsp;'.$d10.'<b><span id="cant_pts_post">'.((int)$context['money']).'</span></b>'.$d2.'<br />

<b style="color:#FE8F47;text-shadow: #6A5645 0px 1px 1px;">POST:</b>&nbsp;<b><a href="/buscador/&q=&autor='.$context['memberName'].'&orden=fecha&categoria=0">'.usuarioPOST($user).'</a></b><br />
<b style="color:#FE8F47;text-shadow: #6A5645 0px 1px 1px;">COMENTARIOS:</b>&nbsp;<b>'.(usuarioComentariosPOST($user)+usuarioComentariosIMG($user)).'</b></div><br />';

if ($context['user']['is_guest']){
echo'<div class="hrs"></div><div class="size11"><br /><a href="/registrarse/" rel="nofollow" target="_blank" rel="nofollow">REGISTRATE!</a> es <b>GRATIS!!</b></div>';
anuncio2_120x240();}


if($context['ID_MEMBER']<>$context['user']['id']){
echo'<span class="size11">';
if($context['ID_MEMBER']<>'1'){
if($context['allow_admin']){echo'<div class="hrs"></div><b class="size11">Moderador:</b><br />IP: <a target="_blank" href="http://lacnic.net/cgi-bin/lacnic/whois?query='.$context['ip'].'" title="IP: '.$context['ip'].'" rel="nofollow">'.$context['ip'].'</a><br /><a href="/moderacion/edit-user/perfil/',$context['ID_MEMBER'],'" title="Administrar Usuario">ADMINISTRAR USUARIO</a><br />';}}
if ($context['user']['is_admin']){
$context['estaon']=mysqli_num_rows(db_query("SELECT ID_MEMBER FROM {$db_prefix}log_online WHERE ID_MEMBER='{$user}'", __FILE__, __LINE__));
if(empty($context['estaon'])){echo'<span style="color:red;">DESCONECTADO</span>';}
elseif(!empty($context['estaon'])){echo'<span style="color:green;">CONECTADO</span>';}}
echo'</span>';}
  
echo'</div></div>';}

function nohtml($html) {
  return htmlspecialchars(trim($html));
}

function nohtml1($html) {
  return stripslashes($html);
}

function nohtml2($html) {
  return stripslashes(stripslashes($html));
}

function moticon($mensaje,$smileys = true){if ($smileys === true){parsesmileys($mensaje);}else{$mensaje=$mensaje;} return $mensaje;}


function usuarioComentariosPOST($user){ global $db_prefix;
$u349jmdjfnsidufh8er=mysqli_num_rows(db_query("SELECT id_user FROM {$db_prefix}comentarios WHERE id_user='$user'", __FILE__, __LINE__));
return $u349jmdjfnsidufh8er;}

function usuarioComentariosIMG($user){ global $db_prefix;
$u349jmdjfnsidufh8er2=mysqli_num_rows(db_query("SELECT ID_MEMBER FROM {$db_prefix}gallery_comment WHERE ID_MEMBER='$user'", __FILE__, __LINE__));
return $u349jmdjfnsidufh8er2;}

function usuarioPOST($user){ global $db_prefix;
$u349jmdjfnsidufh8er3=mysqli_num_rows(db_query("SELECT ID_MEMBER FROM {$db_prefix}messages WHERE ID_MEMBER='$user'", __FILE__, __LINE__));
return $u349jmdjfnsidufh8er3;}



function ditaruser(){global $context, $db_prefix, $ID_MEMBER;
$getid=isset($_GET['u']) ? (int)$_GET['u'] : '';
$request=db_query("
SELECT ID_MEMBER,realName,memberIP,avatar
FROM ({$db_prefix}members)
WHERE ID_MEMBER='$getid'
LIMIT 1", __FILE__, __LINE__);
while ($row = mysqli_fetch_assoc($request)){
$context['membernames'] = $row['realName'];
$context['memberips'] = $row['memberIP'];
$context['avatar'] = $row['avatar'];}
mysqli_free_result($request);
if($getid==$ID_MEMBER){
if($_GET['sa']=='cuenta'){die();}
elseif($_GET['sa']=='perfil'){die();}
elseif($_GET['m']=='tyc8'){die();}
elseif($_GET['m']=='tyc3'){die();}}
if($getid=='1'){
if($_GET['sa']=='cuenta'){die();}
elseif($_GET['sa']=='perfil'){die();}
elseif($_GET['m']=='tyc8'){die();}
elseif($_GET['m']=='tyc3'){die();}}


echo'<div style="width: 138px;float:left;margin-right:8px;">
<div class="MenuCascada">
<div style="width: 138px;">';
if($getid){
echo'<div><a href="/moderacion/edit-user/perfil/'.$getid.'">Editar el perfil</a></div>
<div><a href="/moderacion/edit-user/avatar/'.$getid.'">Editar el avatar</a></div>
<div><a href="/moderacion/edit-user/firma/'.$getid.'">Editar la firmar</a></div>
<div><a href="/imagenes/'.$context['membernames'].'"> Ver im&aacute;genes</a></div>

';}else{
echo'<div><a href="/editar-perfil/">Editar mi perfil</a></div>
<div><a href="/editar-apariencia/">Editar mi apariencia</a></div>
<div><a href="/editar-perfil/avatar/">Editar mi avatar</a></div>
<div><a href="/editar-perfil/firma/">Editar mi firma</a></div>
<div><a href="/web/cw-TEMPAgregarIMG.php" class="boxy" title="Agregar im&aacute;gen">Agregar im&aacute;gen</a></div>
<div><a href="/mis-notas/">Mis notas</a></div>';
}


echo'</div></div><div class="clearfix"></div>';

if($getid){if($getid=='1'){die();}
elseif($getid==$ID_MEMBER){die();}
elseif($context['allow_admin']){
echo'<div class="hrs"></div><b>IP:</b>&nbsp;<a target="_blank" href="http://lacnic.net/cgi-bin/lacnic/whois?query='.$context['memberips'].'" title="IP: '.$context['memberips'].'" rel="nofollow">'.$context['memberips'].'</a><br /><a href="/web/cw-TEMPbanUser.php?sa=add;u='.$getid.'" class="boxy" title="Banear a '.$context['membernames'].'">BANEAR USUARIO</a><br />';}
if ($context['user']['is_admin']){echo'<a class="boxy" title="Rastrear Usuario" href="/web/cw-TEMPrastrearUS.php?id='.$getid.'">RASTREAR USUARIO</a><br />';}}
echo'</div>';


}



function vipmenu(){
global $tranfer1,$scripturl, $context, $txt, $no_firma, $no_avatar, $db_prefix, $options, $ID_MEMBER, $modSettings, $boardurl, $memberContext, $user_settings;
$getid=(int)$ID_MEMBER;

if($user_settings['ID_GROUP']=='7' || $user_settings['ID_GROUP']=='11'){
if($user_settings['ID_GROUP']=='7'){$title='Men&uacute; especial';}
if($user_settings['ID_GROUP']=='11'){$title='Men&uacute; especial';}
echo'<div class="box_140" style="float:left; margin-right:8px;margin-bottom:8px;">
<div class="box_title" style="width: 138px;"><div class="box_txt box_140-34">'.$title.'</div>
<div class="box_rss"><img alt="" src="'.$tranfer1.'/blank.gif" style="width:16px;height:16px;" border="0" /></div></div><div class="windowbg" style="font-size:11px;width: 130px; padding: 4px;">
<a href="/men-especial/">Inicio</a></span><br/><a href="/men-especial/recargar-pts">Recargar Puntos</a></span><br/>';

echo'</div></div>';}else{fatal_error('Solo para usuarios especiales.');}}

function a�o(){echo'2010';}

function hides($mje){$mje=str_replace('[img ]','[img]',$mje);return $mje;}

function decodeurl($texto){
$texto=str_replace('+',' ',$texto);
$texto=str_replace('{ad12B}','+',$texto);
$texto=str_replace('{vD13B}',';',$texto);
$texto=str_replace('{vvE3F}','?',$texto);
$texto=str_replace('{Edv3A}','/',$texto);
$texto=str_replace('{4RE23}','#',$texto);
$texto=str_replace('{4rc24}','&',$texto);
$texto=str_replace('{rpl3D}','=',$texto);
$texto=str_replace('{Rfe26}','$',$texto);
$texto=str_replace('{fce2C}',',',$texto);
$texto=str_replace('{fci3C}','<',$texto);
$texto=str_replace('{fco3E}','>',$texto);
$texto=str_replace('{jho7E}','~',$texto);
$texto=str_replace('{ds625}','%',$texto);
return $texto;}

function captcha($ds,$c=''){global $tranfer1;

if($ds=='1'){

$_SESSION['numeroxxx']=isset($_SESSION['numeroxxx']) ? $_SESSION['numeroxxx'] : '';      
if($_SESSION['numeroxxx'] < 1){    
$_SESSION['numeroxxx']='';
unset($_SESSION['numeroxxx']);
$aleatorio=mt_rand(1000,9999);
$_SESSION['numeroxxx']=''.(int)trim($aleatorio).'';}

if(empty($c)){echo'<table><tr><td class="camptcha"><img src="/web/captcha/index.php?id='.$_SESSION['numeroxxx'].'" alt="" /></td><td class="camptcha"><input size="10" type="text" onfocus="foco(this);" onblur="no_foco(this);" style="text-transform:uppercase;text-align:center;" maxlength="4" id="code" name="code" /></td></tr></table>';}}

else{
$sas1=(int) trim($_SESSION['numeroxxx']);
$sas2=(int) trim($_POST['code']);
if($sas1==$sas2){$_SESSION['numeroxxx']=''; unset($_SESSION['numeroxxx']);}
else{ if($ds=='2'){fatal_error('C&oacute;digo de im&aacute;gen invalido.');}else{die('0: C&oacute;digo de im&aacute;gen invalido.');}} }

return $_SESSION['numeroxxx'];
}

function categorias($tipo,$extra=null){
global $db_prefix,$context;
$request=db_query("SELECT ID_BOARD,description,name FROM {$db_prefix}boards", __FILE__, __LINE__);
$context['boards'] = array();
while ($row = mysqli_fetch_assoc($request)){
$context['boards'][] = array(
'id' => $row['ID_BOARD'],
'description' => $row['description'],
'name' => $row['name'],);}
mysqli_free_result($request);

if($tipo=='1'){echo'<select style="width:202px;" name="categoria" class="select"><option value="0" selected="selected">Todas</option>';
foreach ($context['boards'] as $board){
echo'<option value="'.$board['id'].'" '; if($_GET['categoria']==$board['id'])echo'selected="selected" '; echo'>'.$board['name'].'</option>';}}

elseif($tipo=='2'){echo'<select style="width:202px;" tabindex="5" name="categorias" class="select"><option value="-1" selected="selected">Elegir categor&iacute;a</option>';
foreach ($context['boards'] as $board){echo'<option value="'.$board['id'].'"  '; if($extra==$board['id'])echo'selected="true"'; echo' >'.$board['name'].'</option>';}}

echo'</select>';
return false;}

function enlaces()
{echo'<div align="left" style="margin-bottom:4px;"><a title="Anunciate aca" href="/contactanos/" target="_blank" rel="nofollow" class="anuncio">Anunciate aca</a></div>
         <div align="left" style="margin-bottom:4px;"><a title="Anunciate aca" href="/contactanos/" target="_blank" rel="nofollow" class="anuncio">Anunciate aca</a></div>
  <div class="hrs"></div><center><a class="size10" href="/enlazanos/" target="_blank" rel="nofollow">Enlazanos en tu Web</a></center>';}
  
function destacado() {
  global $tranfer1, $boardurl;

  $adsense = '<p align="center" style="margin:0px;padding:0px;"><iframe src="' . $boardurl . '/web/cw-ads.php?tamanio=300x250" width="300px" height="250px" frameborder="0" scrolling="no" marginheight="0px" marginwidth="0px" marginheight="0px" marginwidth="0px">.</iframe></p>';
  $adsense2a = '<p align="center" style="margin:0px;padding:0px;"><a href="http://www.apuntatelo.com.ar/" target="_blank" rel="nofollow"><img alt="" src="'.$tranfer1.'/publicidad/dest-apuntatelo.gif" border="0" title="Apuntatelo" /></a>&nbsp;<a href="/contactanos/"><img alt="" src="'.$tranfer1.'/publicidad/dest-anunciate.png" border="0" title="Anunciate Aqui" /></a></p>';
  $adsense1a = '<p align="center" style="margin:0px;padding:0px;"><iframe src="' . $boardurl . '/web/cw-ads.php?tamanio=300x250" width="300px" height="250px" frameborder="0" scrolling="no" marginheight="0px" marginwidth="0px" marginheight="0px" marginwidth="0px">.</iframe></p>';
  $destacado = '<p align="center" style="margin:0px;padding:0px;"><iframe src="' . $boardurl . '/web/cw-ads.php?tamanio=300x250" width="300px" height="250px" frameborder="0" scrolling="no" marginheight="0px" marginwidth="0px" marginheight="0px" marginwidth="0px">.</iframe></p>';
  $destacado02 = '<p align="center" style="margin:0px;padding:0px;"><a href="http://www.apuntatelo.com.ar/" target="_blank" rel="nofollow"><img alt="" src="'.$tranfer1.'/publicidad/dest-apuntatelo.gif" border="0" title="Apuntatelo" /></a>&nbsp;<a href="/contactanos/"><img alt="" src="'.$tranfer1.'/publicidad/dest-anunciate.png" border="0" title="Anunciate Aqui" /></a></p>';
  $destacado03 = '<p align="center"><a href="/contactanos/"><img alt="" src="' . $tranfer1 . '/publicidad/dest-anunciate.png" border="0" title="Anunciate Aqui" /></a>&nbsp;<a target="_blank" href="https://publisher.smowtion.com/users/signup/casitaweb-pay" style="border:none" rel="nofollow"><img width="125" height="125" border="0" style="border:none" src="http://ads.smowtion.com/affiliate/125_125_es.gif" alt="Rentabiliza tu sitio!" /></a></p>';
  $destacado04 = '<p align="center" style="margin:0px;padding:0px;"><iframe src="' . $boardurl . '/web/cw-ads.php?tamanio=300x250" width="300px" height="250px" frameborder="0" scrolling="no" marginheight="0px" marginwidth="0px" marginheight="0px" marginwidth="0px">.</iframe></p>';
  $destacados = array($destacado, $adsense, $adsense1a, $adsense2a, $destacado02, $destacado03, $destacado04);
  $destacados01 = rand(0, sizeof($destacados) - 1);

  echo $destacados[$destacados01];
}

function actualizareliminados($id = ''){global $db_prefix,$user_info;
if(!$user_info['is_guest']){
$lvccct=db_query("SELECT id FROM ({$db_prefix}mensaje_personal) WHERE eliminado_de=1 AND eliminado_para=1", __FILE__, __LINE__);while($asserr=mysqli_fetch_assoc($lvccct)){db_query("DELETE FROM {$db_prefix}mensaje_personal WHERE id='{$asserr['id']}'", __FILE__, __LINE__);} $dd=true;}else{$dd=false;} return $dd;}

function valida_url($url){
$direccion=@fopen($url,"r");
if($direccion){$resultado=true;}else{$resultado=false;}
return $resultado;
close($direccion);}

function achicar($tipo){censorText($tipo);
if(strlen($tipo)>33){$tipo=substr($tipo,0,30)."...";}else{$tipo=$tipo;}
return $tipo;}
function achicar400($tipo){
censorText($tipo);
if(strlen($tipo)>400){$tipo=substr($tipo,0,397)."...";}else{$tipo=$tipo;}
return $tipo;}

function achicars($valor){
if(strlen($valor)>47){$valor=substr($valor,0,44)."...";}else{$valor=$valor;}
return $valor;}


// tiempo 
function hace($valor){
$formato_defecto = "H:i:s j-n-Y";
$date = getEnglishDateFormat($valor);
$ht = time() - $date->getTimestamp();
if ($ht >= 2116800) {
$dia = $date->format('d');
$mes = $date->format('n');
$ano = $date->format('Y');
$hora = $date->format('H');
$minuto = $date->format('i');
$mesarray = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
$fecha = "el&nbsp;$dia&nbsp;de&nbsp;$mesarray[$mes]&nbsp;del&nbsp;$ano";}
if($ht<30242054.045){$hc=(int)round($ht/2629743.83);
if($hc>1){$s="es";}else{$s="";}$fecha="hace&nbsp;$hc&nbsp;mes".$s;}
if($ht<2116800){$hc=(int)round($ht/604800);
if($hc>1){$s="s";}else{$s="";}$fecha="hace&nbsp;$hc&nbsp;semana".$s;}
if($ht<561600){$hc=(int)round($ht/86400);
if($hc==1){$fecha="ayer";}
if($hc==2){$fecha="antes&nbsp;de&nbsp;ayer";}
if($hc>2)$fecha="hace&nbsp;$hc&nbsp;d&iacute;as";}
if($ht<84600){$hc=(int)round($ht/3600);if($hc>1){$s="s";}else{$s="";}$fecha="hace&nbsp;$hc&nbsp;hora".$s;
if($ht>4200 && $ht<5400){$fecha="hace m&aacute;s&nbsp;de&nbsp;una&nbsp;hora";}}
if($ht<3570){$hc=(int)round($ht/60);if($hc>1){$s="s";}else{$s="";}$fecha="hace&nbsp;$hc&nbsp;minuto".$s;}
if($ht<60){$fecha="hace&nbsp;$ht&nbsp;segundos";}
if($ht<=3){$fecha="hace&nbsp;segundos";}
return $fecha;}

//Falta
function faltan($valor){
$ht = time()-$valor;

if($ht<84600){
if($ht<84600){$hc=(int)round($ht/3600); }
elseif($ht<3570){$hc=(int)round($ht/60);}

$de=24-$hc;

if(empty($de)){$fecha="poco";}else{$fecha="$de hs";}}

else{$fecha='Recargando';}


return $fecha;}



function relevancia($data){
$resultado=$data; 
$resulPar1=$data*10;
$resulPar=(int)floor($resulPar1);
if($resulPar > 99){$mostrar=100;}elseif($resulPar < 1){$mostrar=0;}else{$mostrar=$resulPar;}
$resultado='<div class="relevancia png" title="'.$mostrar.'%"><div class="porcentajeRel png" style="width: '.$mostrar.'%;"></div></div>';
return $resultado;}

function CerrarSession(){}

function VideosMuro($data){global $tranfer1; 
$lineas=explode('<br />',$data);
$sinComas= str_replace(",","&cedil;",html_entity_decode($data));
$mensaje= str_replace("<br />",",",$sinComas);       
$nuLineas=count(explode(",", $mensaje));
$site = 'www.';
if (preg_match('#^([0-9A-Za-z-_]{11})$#i', trim($lineas[0]), $matches)){$data = $matches[1];}
else{
if (preg_match('#^http://((?:www|uk|fr|ie|it|jp|pl|es|nl|br|au|hk|mx|nz|de|ca)\.|)youtube\.com/(?:(?:watch|)\?v=|v/|jp\.swf\?video_id=)([0-9A-Za-z-_]{11})(?:.*?)(.*?)#i', trim($lineas[0]), $matches)){
$site = !empty($matches[1]) ? strtolower($matches[1]) : $site;
$xmlYT='http://gdata.youtube.com/feeds/api/videos/'.$matches[2]; 
$ch = curl_init();
curl_setopt ($ch, CURLOPT_URL,$xmlYT);
curl_setopt ($ch, CURLOPT_HEADER, 0);
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
$page = trim(curl_exec($ch));
$pos1=strpos($page,"<title type='text'>");
$pos2=strpos($page,"</title>",$pos1);
$pos1=$pos1+19;
$titulo=substr($page,$pos1,$pos2-$pos1);

unset($xmlYT);
for($i=1; $i<=$nuLineas;++$i){

$lineas[$i]=isset($lineas[$i]) ? $lineas[$i] : '';
$datad=$lineas[$i];
$pfff[]=$datad;}
$vas=join('<br />',$pfff);
$data='<div class="pyv-large-thumb" id="v-'.$matches[2].'"><a class="large-thumb" title="'.$titulo.'"><img onclick="crearVyoutube(\''.$matches[2].'\');" src="http://i1.ytimg.com/vi/'.$matches[2].'/hqdefault.jpg" alt="'.$titulo.'" /></a><h3><a title="'.$titulo.'"  onclick="crearVyoutube(\''.$matches[2].'\');"><span class="title-label">'.$titulo.'</span><span class="watch-video-label">Ver este v&iacute;deo</span></a></h3></div><br />'.($vas ? '<div class="codePro1" style="margin-top:10px;">'.$vas.'</div>' : '' );
unset($matches);}}               
return $data;}

function saberpais(){
$_SERVER["HTTP_CLIENT_IP"]!="" ? $ip=$_SERVER["HTTP_CLIENT_IP"]:$ip=$_SERVER["REMOTE_ADDR"];
function getCountry($ip_address){
$url = "http://ip-to-country.webhosting.info/node/view/36";
$inici = "src=/flag/?type=2&cc2=";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST,"POST");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, "ip_address=$ip_address"); 
ob_start();
curl_exec($ch);
curl_close($ch);
$cache = ob_get_contents();
ob_end_clean();
$resto = strstr($cache,$inici);
$pais = substr($resto,strlen($inici),2);
return $pais;}
$paism = strtolower(getCountry($ip));

return $paism;}

function getMetaDescription($text) {
$text = strip_tags($text);
$text = trim($text);
$text = substr($text, 0, 247);
return $text."...";}

function getMetaKeywords($text) {
// Limpiamos el texto
$text = strip_tags($text);
$text = strtolower($text);
$text = trim($text);
$text = preg_replace('/[^a-zA-Z0-9 -]/', ' ', $text);
// extraemos las palabras
$match = explode(" ", $text);
$count = array();
if(is_array($match)){
foreach ($match as $key => $val) {
if (strlen($val)> 3) {
if (isset($count[$val])) {
$count[$val]++;} else{$count[$val] = 1;}}}}
arsort($count);
$count = array_slice($count, 0, 10);
return implode(", ", array_keys($count));}


function textarea2($ivvd,$dd=0){
global $tranfer1,$user_info;
if(!$dd){$stylec='style="display:none;" ';}else{$stylec='';}
if(!$user_info['is_guest']){$resultado='<div align="center" '.$stylec.'id="b-'.$ivvd.'"><div class="muroCcs" id="comentarCC_'.$ivvd.'"><textarea title="Escribe un comentario..." onfocus="if(this.value==\'Escribe un comentario...\') this.value=\'\';foco(this);this.style.height=\'50px\';$(\'#ocultar_input_'.$ivvd.'\').css(\'display\', \'block\');" onblur="if(this.value==\'\'){this.style.height=\'15px\';$(\'#ocultar_input_'.$ivvd.'\').css(\'display\', \'none\');this.value=\'Escribe un comentario...\';}no_foco(this); " style="overflow:auto;height:15px;width:400px;font-size:11px;font-family:Arial,FreeSans;" id="textareaCC_'.$ivvd.'">Escribe un comentario...</textarea>
<p align="right" id="ocultar_input_'.$ivvd.'" style="display:none;padding:0px;margin:0px;"><label><input class="login" value="Comentar" onclick="comentarCcmuro(\''.$ivvd.'\'); return false;" type="button" /><span id="cargandoCC_'.$ivvd.'" style="display:none;" ><img src="'.$tranfer1.'/icons/cargando.gif" width="16px" height="16px" alt="" /></span></label></p></div><div id="comentarCC2_'.$ivvd.'" style="display:none;width:416px;"></div></div>';}else{$resultado='<div align="center" '.$stylec.'id="b-'.$ivvd.'"><div class="muroCcs" id="comentarCC_'.$ivvd.'">Solo usuarios conectados. <a href="/registrarse/">REGISTRARSE</a> - <a href="javascript:irAconectarse();">CONECTARSE</a></div></div>';}

return $resultado;}

function timeforComent($que=''){
$time=time();

$_SESSION['ultima_accionTIME']=isset($_SESSION['ultima_accionTIME']) ? $_SESSION['ultima_accionTIME'] : '0';
if($_SESSION['ultima_accionTIME'] > ($time-20) ){if(empty($que)){die('0: No es posible efectuar tantas acciones en poco tiempo.');}else{fatal_error('No es posible efectuar tantas acciones en poco tiempo.');}}
else{ unset($_SESSION['ultima_accionTIME']); }
}


function salir() {
  global $db_prefix, $sourcedir, $ID_MEMBER, $modSettings, $user_info, $user_settings;

  if (!$user_info['is_guest']) {
    if (isset($modSettings['integrate_logout']) && function_exists($modSettings['integrate_logout'])) {
      call_user_func($modSettings['integrate_logout'], $user_settings['memberName']);
    }

    db_query("
      DELETE FROM {$db_prefix}log_online
      WHERE ID_MEMBER = $ID_MEMBER
      LIMIT 1", __FILE__, __LINE__);

    require($sourcedir . '/Subs-Auth.php');

    $_SESSION['log_time'] = 0;

    setLoginCookie(-3600, 0);

    return true;
  } else {
    return false;
  }
}

function cw_header() {
    global $tranfer1;
//header("Cache-Control: must-revalidate");
//header("Expires: ".gmdate ("D, d M Y H:i:s", time() + 60*60*24*30)." GMT");
  echo '
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml" lang="es" xml:lang="es">
    <!--2008/2010 casitaweb.net/por rigo-->
    <head profile="http://purl.org/NET/erdf/profile">
      <link rel="schema.dc" href="http://purl.org/dc/elements/1.1/" />
      <link rel="schema.foaf" href="http://xmlns.com/foaf/0.1/" />
      <meta name="verify-v1" content="HTXLHK/cBp/LYfs9+fLwj1UOxfq+/iFsv1DZjB6zWZU=" />
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
      <link rel="icon" href="/favicon.ico" type="image/x-icon" />
      <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
      <link rel="apple-touch-icon" href="' . $tranfer1 . '/apple-touch-icon.png" />
      <meta name="robots" content="All" />
      <meta name="revisit-after" content="1 days" />';

  return true;
}

function getEnglishDateFormat($str_date) {
  $months = array(
    'Enero' => 'January',
    'Febrero' => 'February',
    'Marzo' => 'March',
    'Abril' => 'April',
    'Mayo' => 'May',
    'Junio' => 'June',
    'Julio' => 'July',
    'Agosto' => 'August',
    'Septiembre' => 'September',
    'Octubre' => 'October',
    'Noviembre' => 'November',
    'Diciembre' => 'December'
  );

  $date_text = trim($str_date);

  // Reemplazar el nombre del mes en español con su equivalente en inglés
  foreach ($months as $spanish => $english) {
    if (strpos($date_text, $spanish) !== false) {
        $date_text = str_replace($spanish, $english, $date_text);
        break;
    }
  }

  // Convertir la cadena en un objeto DateTime
  $fecha_datetime = new DateTime($date_text);

  // Usar la función date() para darle formato a la fecha
  /*
  $fecha_formateada = $fecha_datetime->format('Y-m-d'); // Puedes cambiar el formato a tu necesidad
  */

  return $fecha_datetime;
}

/*

function Flood(){}
function html2bbcode($mje) {
//ELIMINAR HTML          
        //Comentarios
        $bbcode [] = '/\<!--(.*?)--\>/is';
        $html [] = " ";
        //Script
        $bbcode [] = '/\<script(.*?)\>(.*?)\<\/script\>/is';
        $html [] = " ";
        //Style
        $bbcode [] = '/\<style(.*?)\>(.*?)\<\/style\>/is';
        $html [] = " ";        
        //Head
        $bbcode [] = '/\<head(.*?)\>(.*?)\<\/head\>/is';
        $html [] = " ";
//ELIMINAR HTML

        //Align
        $bbcode [] = '/\<p(.*?)style=\"text-align: (.*?);\"(.*?)\>(.*?)\<\/p\>/is';
        $html [] = '[$2]$4[/$2]';
        
        //P (Salto de linea)
        $bbcode [] = '/\<br\s*\/?\><\/p\>/is';
        $html [] = "\n";

        //BR (Salto de linea)
        $bbcode [] = '/\<br\s*\/?\>/is';
        $html [] = "\n";
        
        //P (Salto de linea)
        $bbcode [] = '/\<\/p\>/is';
        $html [] = "\n";
        
        //HR
        $bbcode [] = '/\<hr\s*\/?\>/is';
        $html [] = "[hr]";
                        
        //Negrita
        $bbcode [] = '/\<b\>/is';
        $html [] = '[b]';
        $bbcode [] = '/\<\/b\>/is';
        $html [] = '[/b]';
        
        //Negrita
        $bbcode [] = '/\<strong\>/is';
        $html [] = '[b]';
        $bbcode [] = '/\<\/strong\>/is';
        $html [] = '[/b]';
        
        //I
        $bbcode [] = '/\<i\>/is';
        $html [] = '[i]';
        $bbcode [] = '/\<\/i\>/is';
        $html [] = '[/i]';
        
        //I
        $bbcode [] = '/\<em\>/is';
        $html [] = '[i]';
        $bbcode [] = '/\<\/em\>/is';
        $html [] = '[/i]';
        
        //S
        $bbcode [] = '/\<strike\>/is';
        $html [] = '[s]';
        $bbcode [] = '/\<\/strike\>/is';
        $html [] = '[/s]';
        
        //S
        $bbcode [] = '/\<span(.*?)style=\"text-decoration: line-through;(.*?)\"(.*?)\>(.*?)\<\/span\>/is';
        $html [] = '[s]$4[/s]';
        
        //U
        $bbcode [] = '/\<u\>/is';
        $html [] = '[u]';
        $bbcode [] = '/\<\/u\>/is';
        $html [] = '[/u]';
        
        //U
        $bbcode [] = '/\<span(.*?)style=\"text-decoration: underline;(.*?)\"(.*?)\>(.*?)\<\/span\>/is';
        $html [] = '[u]$4[/u]';
        
        //FONT
        $bbcode [] = '/\<span(.*?)style=\"font-family: (.*?)\"(.*?)\>(.*?)\<\/span\>/is';
        $html [] = '[font=$2]$4[/font]';
        
        //Color
        $bbcode [] = '/\<span(.*?)style=\"color: (.*?);\"(.*?)\>(.*?)\<\/span\>/is';
        $html [] = '[color=$2]$4[/color]';
                
        //Color2
        $bbcode [] = '/\<font(.*?)_casitaw_style=\"color: (.*?)\"(.*?)\>(.*)\<\/font\>/is';
        $html [] = '[color=$2]$4[/color]';
               
        //Color3
        $bbcode [] = '/\<font(.*?)style=\"color: (.*?)\"(.*?)\>(.*?)\<\/font\>/is';
        $html [] = '[color=$2]$4[/color]';
        
        //Size
        $bbcode [] = '/\<span(.*?)style=\"font-size: (.*?)\"(.*?)\>(.*?)\<\/span\>/is';
        $html [] = '[size=$2]$4[/size]';

        //QUOTE
        $bbcode [] = '/\<blockquote(.*?)\>(.*?)\<\/blockquote\>/i';
        $html [] = '[quote]$2[/quote]';
        //IMG
        //IMG
            //IMG - TAMA�O
        $bbcode [] = '/\<img(.*?)src=\"(.*?)\"(.*?)width=\"(.*?)\"(.*?)height=\"(.*?)\"(.*?)\>/i';
        $html [] = '[img width=$4 height=$6]$2[/img]';
        
        $bbcode [] = '/\<img(.*?)src=\"(.*?)\"(.*?)\>/is';
        $html [] = '[img]$2[/img]';
        //SWF
        $bbcode [] = '/\<embed(.*?)src=\"(.*?)\"(.*?)\>/is';
        $html [] = '[swf]$2[/swf]';
        
        //URL
        $bbcode [] = '/\<a(.*?)href=\"(.*?)\"(.*?)\>(.*?)\<\/a\>/is';
        $html [] = '[url=$2]$4[/url]';
        
        //E-mail
        $bbcode [] = '/\<a(.*?)href=\"mailto:(.*?)\"(.*?)\>(.*?)\<\/a\>/is';
        $html [] = '[email=$2]$4[/email]';
        
        $cadena=stripslashes($mje);
        $ArmarTXT=preg_replace( $bbcode, $html, $cadena );
        $Fin=strip_tags(trim($ArmarTXT));
return $Fin;}


function tags(){
global  $db_prefix;
$result=db_query("
SELECT count(palabra) as quantity,id
FROM {$db_prefix}tags
GROUP BY palabra
ORDER BY id DESC",__FILE__, __LINE__);
while($row=mysqli_fetch_array($result)){$tag['cantidad']=$row['quantity'];$tag['id']=$row['id'];

db_query("UPDATE {$db_prefix}tags SET cantidad='{$tag['cantidad']}' WHERE id='{$tag['id']}'",__FILE__, __LINE__);}
mysqli_free_result($result);}
*/

?>