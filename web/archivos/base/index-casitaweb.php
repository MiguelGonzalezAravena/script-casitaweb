<?php
function template_init()
{global $context, $settings, $options, $txt; 
$settings['use_default_images']='never';
$settings['doctype']='xhtml';
$settings['theme_version'] = '1';
$settings['use_tabs']=true;
$settings['use_buttons']=true;
$settings['seperate_sticky_lock']=true;}
function template_main_above(){
global $context,$boardurl,$txt,$modSettings,$user_info,$db_prefix,$tranfer1,$user_settings,$ie,$urlSep,$internetNO;  ?>
<link rel="stylesheet" type="text/css" href="<?php echo $tranfer1;?>/estilo.php" />
<script type="text/javascript">var urlWEb = "<?php echo $boardurl;?>";</script>
<script type="text/javascript" src="<?php echo $tranfer1;?>/js/index.php"></script>
<?php if($context['id-post']){
$context['page_title']=$context['titulo'];
echo'<meta property="dc:date" content="'.timeformat($context['fecha']).'"/>
<meta property="dc:creator" content="'.$context['posterName'].'" />
<link rel="canonical" href="http://casitaweb.net/post/'.$context['id-post'].'/'.$context['link_cat'].'/'.urls($context['titulo']).'.html" />
<link rel="prev" href="http://casitaweb.net/noestilo/post/'.(int)($context['id-post']-1).'" />
<link rel="next" href="http://casitaweb.net/noestilo/post/'.(int)($context['id-post']+1).'" />
<link rel="alternate" type="application/atom+xml" title="Comentarios del post" href="/rss/post-comment/'.$context['id-post'].'" />
<link rel="alternate" type="application/atom+xml" title="Post del usuario" href="/rss/post-user/'.$context['posterName'].'" />
<meta name="description" content="'.getMetaDescription($context['CsTNidO']).'" />';}
else{
if(empty($_GET[$urlSep])){echo'<meta http-equiv="refresh" content="600" />
';}
echo'<meta name="description" content="- Un sitio de distraccion, de descargas (Musica, Juegos, Programas, Peliculas,etc,etc).. Lo interesante que aca VOS sos el protagonita el que aporta sos vos, Todo eso y mucho m�s..." />
<link rel="alternate" type="application/atom+xml" title="&Uacute;ltimos posts" href="/rss/ultimos-post" />
<link rel="alternate" type="application/atom+xml" title="&Uacute;ltimos comentarios" href="/rss/ultimos-comment" />';}
if($context['page_title']==$txt[18]){$titlee=$context['forum_name'].' - '.$context['page_title'];}
else{$titlee=$context['page_title'].' - '.$context['forum_name'];}
?><meta name="keywords" content="<?php  echo getMetaKeywords($context['CsTNidO']); ?>,<?php echo $titlee; ?>,rapidshare,megaupload,mediafire,<?php echo ($context['id-post']-1);?>,<?php echo ($context['id-post']-2);?>,descarga,rapidshare,megaupload,mediafire,descarga-directa,bajar,mp3,casitaweb,rigo,caladj,elblogderigo,lawebderigo,elforoderigo,linksharing,enlaces,juegos,musica,links,noticias,imagenes,videos,animaciones,arte,tecnologia,celulares,argentina,comunidad,cw,infornes,2008,2009,warez,linksharing,web 2.0,directa <?php a�o();?>" />
<title><?php echo $titlee; ?></title>
<meta name="title" content="<?php echo $titlee; ?>" />
<meta name="generator" content="<?php echo $titlee; ?> / Para descargar / bajar / instalar gratis / Gratuito / rigo / casitaweb / 2.0 / linksaring / rapidshare / descargas / directas / megaupload / mediafire / software / freeware / serial / gratis / programas / musica / juegos / peliculas" />
<link rel="search" type="application/opensearchdescription+xml" title="CasitaWeb!" href="<?php echo $tranfer1;?>/buscador-cw.xml" />
<link rel="up" href="#top" title="Volver al principio de esta pagina" /> <?php
echo'<style rel="stylesheet" type="text/css">#logob{width:360px;height:95px;background: url(\'/logos/logo.gif\'); background-repeat:no-repeat;float:left;}</style>';  ?></head>
<body id="top">

<div id="cargando_boxy" style="display:none;" align="center"><div id="cargando_ajax">Cargando...</div></div>
<?php echo $internetNO;?>
<div id="maincontainer">
<div class="logon">
  <div id="logoa"><a href="/" title="<?php echo $context['forum_name'];?> - <?php echo $txt[18];?>" id="logob"><img src="<?php echo $tranfer1;?>/espacio.gif" width="360px" height="95px" alt="" align="top" border="0" /></a></div><div style="clear: both;"></div>
</div>
   
<div id="atri">
<div class="fixed"><div style="clear: both;"></div>
<ul><li class="clientarea" id="areaClient" >
<?php $accioncw241= isset($_GET[$urlSep]) ? $_GET[$urlSep] : '';
$m= isset($_GET['m']) ? $_GET['m'] : '';
if($accioncw241=='com'){$jj='2';$request=db_query("SELECT url,nombre FROM {$db_prefix}comunidades_categorias ORDER BY nombre ASC", __FILE__, __LINE__);}else{$jj='1';if(!$context['user']['is_admin']){$shas=' WHERE ID_BOARD<>142';}else{$shas='';} $request=db_query("SELECT description AS url,name AS nombre FROM {$db_prefix}boards$shas", __FILE__, __LINE__);} ?>
<span id="hdLoglink" class="hdLoglink2" onclick="javascript: AbrirCats();" ><img src="<?php echo $tranfer1;?>/arrow-cats.gif" width="20px" height="6px" alt="" />Ver Categor&iacute;as</span>
<div id="hd_cats" class="hd_loginbox2"><?php
while ($row = mysql_fetch_assoc($request)){
if($accioncw241=='com'){$ff='/comunidades/categoria/';}else{$ff='/categoria/';}
echo'<div><a href="'.$ff.$row['url'].'" >'.$row['nombre'].'</a></div>';} mysql_free_result($request); ?></div><?php  ?>
</li></ul>

<ul class="servicenav">
<li class="comunidadesc"><a href="/comunidades/" class="comuCC">Comunidades</a><?php $cincoMiN=time() - 600; $caadsasd=mysql_num_rows(db_query("SELECT id FROM ({$db_prefix}comunidades_articulos) WHERE creado > ".$cincoMiN." AND eliminado=0",__FILE__, __LINE__));if($caadsasd){if($caadsasd > 1){$plur='s';}else{$plur='';}echo'<div id="Sfvc" title="'.$caadsasd.' tema'.$plur.' nuevo'.$plur.' (&uacute;ltimos 10 Minutos)">'.$caadsasd.'</div>';}?></li>

<?php if(!empty($user_settings['ID_MEMBER'])){?>
<li class="sn"><a class="publicar" href="/agregar/">Publicar</a></li>
<li class="sn"><a href="/tops/">TOPs</a></li><?php } ?>
<li class="sn"><a href="/chat/">Chat</a></li>
<li class="sn"><a href="http://ayuda.casitaweb.net/" title="">Ayuda</a></li>
<li class="sn"><a href="/buscador/" title="">Buscar</a></li>
<li class="sn"><a href="/" title="">Inicio</a></li>
<?php 

if(empty($accioncw241) && $context['id-post']){$reg='0';}
elseif(empty($accioncw241) && empty($context['id-post'])){$reg='Home';}
else{$reg='0';}
if($accioncw241=='rz-seg55555658971' && $m=='tyc2'){$ch='1';}else{$ch='0';}
 if(empty($user_settings['ID_MEMBER'])){?><li><a href="/registrarse/" title="Registrate GRATIS!!!" class="registrarse">Registrate!!!</a></li>
<li class="clientarea" id="areaClient" ><span id="hdLoglink" class="hdLoglink" onclick="javascript: servicenavlogin();" >Iniciar sesi&oacute;n</span></li>

<div id="hd_loginbox"><div style="display: none;" id="login_cargando"><img alt="" src="<?php echo $tranfer1;?>/icons/cargando.gif" width="16px" height="16px" /></div><div style="display: none;" id="login_error"></div><div class="login_cuerpo"><form method="post" action="javascript:login_ajax('<?php echo $reg;?>')"><div><label onclick="$('#nickname').focus();" style="cursor:pointer;color:#444;">Nick: </label><input maxlength="64" name="nick" id="nickname" onfocus="foco(this);" onblur="no_foco(this);" class="loginuserid" type="text" /><br/><label onclick="$('#password').focus();" style="cursor:pointer;color:#444;">Contrase&ntilde;a: </label> <input maxlength="64" name="pass" id="password" onfocus="foco(this);" onblur="no_foco(this);" class="loginpasswd" type="password" /></div><p><input class="loginsubmit" type="submit" value="Conectarse" /></p></form><a href="/recuperar-pass/" class="loginforgotpass">&#191;Ha olvidado su password?</a><br/><a href="/registrarse/" class="loginforgotpass">&#191;Queres tu cuenta? Registrate!!</a><div class="clearfix"></div></div></div>

<?php }else{echo'<li class="clientarea" id="areaClient"><a id="hdLoglink" href="/perfil/'.$context['user']['name'].'" class="logged">'.$context['user']['name'].'</a></li>';
if($user_settings['puntos_dia'] > 0){$first="";echo'<li style="border-left:none;" class="puntosa"><a href="#" style="cursor:default;"><strong style="font-size:13px;color:#0B7F00;" title="Puntos disponibles, PARA VOTAR, posts e im&aacute;genes.">+<span id="puntosDD">'.$user_settings['puntos_dia'].'</span></strong></a></li>';}
else{$first='style="border-left:none;"';}
if($user_settings['topics'] > 0){echo'<li '.$first.'><a class="lcc2" href="/mensajes/" title="Mensajes Privados"><img alt="" src="'.$tranfer1.'/icons/mensaje_nuevo.gif" style="width: 16px;height: 15px;margin-right:5px;" /><span id="quitarMP"> (<strong><span title="Sin leer" id="cantidad-MP">'.$user_settings['topics'].'</strong>)</span></a></li>';}
else{echo'<li '.$first.'><a class="lcc2" href="/mensajes/" title="Mensajes Privados"><img alt="" src="'.$tranfer1.'/icons/mensaje.gif" style="width: 16px;height: 15px; " /></a></li>';}

echo'<li>';
if(!empty($ch)){echo'<a class="lcc2" target="_blank" title="Notificaciones" href="/notificaciones/"><span class="hdLoglink3"><img class="png" alt="" src="'.$tranfer1.'/icons/monitor.png" style="width: 16px;height: 15px; " /></span></a>';}
else{echo'<a class="lcc2" title="Notificaciones" onclick="notificaciones();"><span class="hdLoglink3"><img class="png" alt="" src="'.$tranfer1.'/icons/monitor.png" style="width: 16px;height: 15px; " /></span></a>';}

echo'<div id="hd_loginboxx3"><div id="hd_loginbox3"><div id="notificacionesES"><div style="display: none;" id="NOT_cargando"><center><br /><img alt="" src="'.$tranfer1.'/icons/cargando.gif" width="16px" height="16px" /></center></div><div id="notificaciones_cuerpo" style="display:none;"></div></div></div><div id="hd_masNOT" align="center"><a href="/notificaciones/">Ver m&aacute;s notificaciones.</a></div></div>';

 
 if($user_settings['notificacionMonitor']){if($user_settings['notificacionMonitor']){$plur='es';}echo'<div id="Sfvc" title="'.$user_settings['notificacionMonitor'].' notificacion'.$plur.'" class="Sfvc22">'.$user_settings['notificacionMonitor'].'</div>';}
 
 echo'</li>
 <li><a class="lcc2" href="/favoritos/" title="Mis Favoritos"><img class="png" alt="" src="'.$tranfer1.'/icons/favoritos.png" style="width:16px;height: 15px; " /></a></li>
 <li><a class="lcc2" href="/editar-perfil/" title="Editar mi perfil"><img class="png" alt="" src="'.$tranfer1.'/icons/editar-cuenta.png" style="width: 16px;height: 15px; " /></a></li>
 <li><a class="lcc" href="#" id="salir_cw"><strong>[x]</strong></a></li>
 <li><a class="lcc" href="/hist-mod/" title="Historial de moderaci&oacute;n"><img alt="" src="'.$tranfer1.'/icons/hmod.png" class="png" style="width:16px;height: 15px; " /></a></li>';
 if($user_settings['ID_GROUP']=='7' || $user_settings['ID_GROUP']=='11'){echo'<li><a  class="lcc" title="Men&uacute; especial" href="/men-especial/"><img class="png" alt="" src="'.$tranfer1.'/icons/especial.png" style="width:16px;height: 15px; " /></a></li>';}
 if(($user_info['is_admin'] || $user_info['is_mods'])){echo'<li><a class="lcc" title="Modreaci&oacute;n" href="/moderacion/"><img alt="" src="'.$tranfer1.'/icons/adm.png" class="png" style="width:16px;height: 15px; " /></a></li>';} }?> </ul><div style="clear: both;"></div></div>
 
 
<?php if($modSettings['news']){$texto1=nohtml($modSettings['news']);$paramostrar=str_replace('http://linkoculto.net/index.php?l=','',parse_bbc(str_replace('%','',$texto1))); echo'<div style="margin:0px;padding:0px;"><div id="mensaje-top">'.$paramostrar.'</div></div>';} ?><div class="clearBoth"></div><div id="bodyarea"><div id="bod"><?php }?> 
 
<?php function template_main_below(){global $tranfer1,$context, $txt; ?>
<div class="clearfix"></div> </div></div>
<div id="pie"> <?php echo' &copy; ';a�o();echo' <a href="/" title="casitaweb.net">casitaweb.net</a> | <a href="/protocolo/" title="Protocolo">Protocolo</a> | <a href="/enlazanos/" title="Enlazanos">Enlazanos</a> | <a href="/widget/" title="Widget">Widget</a> | <a href="/contactanos/" title="Contacto">Contacto</a> | <a href="/recomendar/" title="Recomendar CasitaWeb!">Recomendar CasitaWeb!</a> | <a href="/mapa-del-sitio/" title="Mapa del sitio">Mapa del sitio</a><div style="clear:both"></div></div></div></div>'; ?><script type="text/javascript">  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-2833411-5']);
  _gaq.push(['_setLocalRemoteServerMode']);
  _gaq.push(['_trackPageview']);
  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script><?php echo'<span id="flotadorUP" class="png" onclick="ira_CasitaWebNET(); return false;">&uarr;</span></body></html>';}

function template_menu(){}
function template_button_strip(){}
function theme_linktree2(){}
function theme_linktree3(){}
function theme_newestlink(){}
function theme_linktree(){}
?>