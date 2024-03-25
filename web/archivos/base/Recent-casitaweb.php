<?php 
function template_main(){global $tranfer1, $context, $boarddir, $db_prefix, $modSettings, $scripturl, $ID_MEMBER; ?>
<div style="text-align:left;"><div style="float:left;height:auto;margin-right:8px;">
<div class="ultimos_postsa" style="margin-bottom:4px;"><div class="box_title" style="width:378px;"><div class="box_txt ultimos_posts">&Uacute;ltimos posts</div><div class="box_rss"><a href="/rss/ultimos-post/"><div style="height: 16px; width: 16px; cursor: pointer;" class="feed png"><img alt="" src="<?php echo $tranfer1; ?>/espacio.gif" class="png" height="16px" width="16px" /></div></a></div></div>
<div class="windowbg" style="width:370px;padding:4px;">
<!-- empiezan los post --> 
<?php

foreach ($context['sticky'] as $sticky){
if(empty($_GET['pag']) || $_GET['pag']==='1'){ ?>
<div class="postENTrysticky" style="background-color: <?php if(empty($sticky['color']) || $sticky['color']=="0" || $sticky['color']=="#000000"){?>#FFFFCC <?php }else{ echo $sticky['color']; } ?>;"><a href="/post/<?php echo $sticky['id']; ?>/<?php echo $sticky['description']; ?>/<?php echo urls($sticky['titulo']); ?>.html" target="_self" title="<?php echo $sticky['titulo']; ?>" class="categoriaPost <?php echo $sticky['description']; ?>"><?php echo achicars($sticky['titulo']); ?></a></div>
<?php } }

if($context['PagAct']>$context['PagUlt']){echo'<div class="noesta"><br /><br /><br /><br />Est&aacute; p&aacute;gina no existe.<br /><br /><br /><br /><br /></div>';}

else{
foreach ($context['posts'] as $posts){ ?> 
<div class="postENTry"><a href="/post/<?php echo $posts['id']; ?>/<?php echo $posts['description']; ?>/<?php echo urls($posts['titulo']); ?>.html" target="_self" title="<?php echo $posts['titulo']; ?>" class="categoriaPost <?php echo $posts['description']; ?>"><?php echo achicars($posts['titulo']); ?></a></div> <?php } } ?> <div class="clearBoth"></div></div> 

<?php if($context['PagAct']>$context['PagUlt']){}else{ ?> <?php if($context['PagAct']>1 || $context['PagAct']<$context['PagUlt']){ ?>
 <div class="windowbgpag" style="width:378px;"> 
<?php 
if(empty($context['catccdd'])){  if($context['PagAct']>1){ ?> <a href="/pag-<?php echo $context['PagAnt'];?>">&#171; anterior</a> <?php } 

if($context['PagAct']<$context['PagUlt']){ ?> <a href="/pag-<?php echo $context['PagSig'];?>">siguiente &#187;</a> <?php }}
else{ if($context['PagAct']>1){ ?> <a href="/categoria/<?php echo $context['catccdd'];?>/pag-<?php echo $context['PagAnt'];?>">&#171; anterior</a> <?php }
 if($context['PagAct']<$context['PagUlt']){ ?> <a href="/categoria/<?php echo $context['catccdd'];?>/pag-<?php echo $context['PagSig'];?>">siguiente &#187;</a> <?php } } ?><div class="clearBoth"></div></div> <?php }} ?> </div>
<div class="clearBoth"></div></div>

<div style="float:left;margin:0px;padding:0px;height:90px;margin-bottom:8px;" align="center"><a href="/chat/" target="_blank"><img alt="" src="<?php echo $tranfer1;?>/sala-chat.gif" width="534px" height="90px" /></a></div>

<div style="float:left;margin-right:8px;"> <div style="margin-bottom: 8px; width: 363px;">
<ul class="buscadorPlus"><li id="gb" class="activo" onclick="elegir('google')">Google</li><li id="cwb" onclick="elegir('casitaweb')">CasitaWeb!</li><div class="clearBoth"></div></ul><div class="clearBoth"></div><div style="margin-top: -1px;clear:both;"><form style="margin: 0px; padding: 0px;" action="/buscar.php" method="get" accept-charset="<?php echo $context['character_set'];?>"><input type="text" name="q" id="q" class="ibuscador" style="height:32px;" /><input onclick="return errorrojos(this.form.q.value);" alt="" class="bbuscador png" title="Buscar" value=" " type="submit" align="top" style="height:34px;" /><input name="buscador_tipo" value="g" checked="checked" type="hidden" /></form></div></div>

<?php if(!empty($modSettings['radio'])){ if($modSettings['radio']=='1'){ ?> <div class="act_comments"> <div class="box_title" style="width:361px;"><div class="box_txt ultimos_comments">CasitaWeb! - Radio</div> <div class="box_rss"><img alt="" src="<?php echo $tranfer1;?>/blank.gif" style="width:16px;height:16px;" border="0" /></div></div><div class="windowbg" style="padding:4px;width:353px;margin-bottom:8px;"><center> <div class="stream">
<script type="text/javascript" >window.onload=radio;
function radio(){
if(document.getElementById('cc_stream_info_song').innerHTML == ''){
document.getElementById('enlinea').className='error';
document.getElementById('enlinea').innerHTML='Fuera de linea';
document.getElementById('imgmic').style.display='inline';
document.getElementById('imgcar').style.display='none';}
else{document.getElementById('enlinea').className='ok';
document.getElementById('enlinea').innerHTML='En linea';
document.getElementById('imgmic').style.display='inline';
document.getElementById('imgcar').style.display='none';
document.getElementById('escuchando').style.display='inline';}}
</script>
<span id="escuchando" style="display:none;"><img src="<?php echo $tranfer1;?>/icons/microfono.png" alt="" /> <a  href="/chat/" id="cc_stream_info_song"></a><br /></span>
<span id="linea"><img src="<?php echo $tranfer1;?>/icons/microfono.png" alt="" style="display:none;" id="imgmic" /><img src="<?php echo $tranfer1;?>/icons/cargando.gif" id="imgcar" alt="" /> <span style="font-weight:bold;" id="enlinea"></span></span>
<script language="javascript" type="text/javascript" src="http://cp.internet-radio.org.uk/system/streaminfo.js"></script>
<script language="javascript" type="text/javascript" src="http://cp.internet-radio.org.uk/js.php/camilo62/streaminfo/rnd0"></script>
<object type="application/x-shockwave-flash" data="http://fmcasita.net/utilidades/player_mp3_maxi.swf" width="266" height="20">
    <param name="wmode" value="transparent" />
    <param name="movie" value="http://fmcasita.net/utilidades/player_mp3_maxi.swf" />
    <param name="bgcolor" value="#ffffff" />
    <param name="FlashVars" value="mp3=http%3A//77.92.68.221%3A15393/%3B&amp;showvolume=1&amp;width=266&amp;showloading=always&amp;bgcolor1=CDC3B8&amp;bgcolor2=CDC3B8&amp;slidercolor1=FFC703&amp;slidercolor2=FFC703" />
</object></div>
<img alt="" src="<?php echo $tranfer1;?>/icons/radio-cw.gif" /> <b class="size11">Ir a <a target="_blank" href="http://fmcasita.net">FMcasita.net</a> - Radio oficial de CasitaWeb!</b></center>
</div></div>

<?php } elseif($modSettings['radio']=='2'){ ?>
<div class="act_comments">
<div class="box_title" style="width:361px;"><div class="box_txt ultimos_comments">Radio / Perdidos en babylon</div>
<div class="box_rss"><img alt="" src="<?php echo $tranfer1;?>/blank.gif" style="width:16px;height:16px;" border="0" /></div></div><div class="windowbg" style="padding:4px;width:353px;margin-bottom:8px;"><center>
<embed type="application/x-mplayer2" pluginspace="http://www.microsoft.com/isapi/redir.dll?prd=windows&amp;sbp=mediaplayer&amp;ar=Media&amp;sba=Plugin&amp;" wmode="transparent" filename="mms://201.212.0.128/horaprima" name="WMPlay" autostart="0" showcontrols="1" showdisplay="0" showstatusbar="0" autosize="0" displaysize="0" width="280" height="45">
<br /><img alt="" src="<?php echo $tranfer1;?>/icons/radio-cw.gif" /> <b class="size11">Ir a <a target="_blank" href="http://perdidosenbabylon.com">Perdidos en babylon!</a> - Web oficial<br /><img alt="" src="http://fmcasita.net/utilidades/2.png" /> <a href="mms://201.212.0.128/horaprima">Escuchar en Windows media player</a></b></center></div></div>
<?php } } ?>

<div class="act_comments"><div class="box_title" style="width:361px;"><div class="box_txt ultimos_comments">&Uacute;ltimos comentarios</div><div class="box_rss"><div style="height: 16px; width: 16px; cursor: pointer;" class="actualizarComents png"><img alt="Actualizar" onclick="actualizar_comentarios(); return false;" src="<?php echo $tranfer1; ?>/espacio.gif" class="png" height="16px" width="16px" /></div></div></div>
<div class="windowbg" style="width:353px;padding:4px;margin-bottom:8px;">
<span id="ult_comm">

<?php mensajes();?>

</span></div></div>

<div class="act_comments"><div class="box_title" style="width:361px;"><div class="box_txt ultimos_comments">Tops posts de la semana</div><div class="box_rss"><img alt="" src="<?php echo $tranfer1;?>/blank.gif" style="width:16px;height:16px;" border="0" /></div></div><div class="windowbg" style="width: 353px; padding:4px;margin-bottom:8px;font-size:11px;">
<?php $conanto=1;
foreach ($context['post_semana'] as $postssssss){ ?>
<b><?php echo $conanto; ++$conanto;?>&nbsp;-</b>&nbsp;<a href="/post/<?php echo $postssssss['ID_TOPIC'];?>/<?php echo $postssssss['description'];?>/<?php echo urls($postssssss['subject']);?>.html" title="<?php echo $postssssss['subject'];?>"><?php echo achicars($postssssss['subject']);?></a>&nbsp;(<span title="<?php echo $postssssss['num_posts']; ?> pts"><?php echo $postssssss['num_posts'];?>&nbsp;pts</span>)<br />

<?php } ?>

</div></div>

<div class="act_comments"><div class="box_title" style="width:361px;"><div class="box_txt ultimos_comments">TOPs Tags <span style="font-size:9px;">(<a href="/tags/" title="Nube de Tags">Nube de Tags</a>)</span></div>
<div class="box_rss"><img alt="" src="<?php echo $tranfer1;?>/blank.gif" style="width:16px;height:16px;" border="0" /></div></div><div class="windowbg" style="width: 353px; padding:4px;margin-bottom:8px;font-size:11px;"><center>
<?php
$fontmax=20;
$fontmin=10;
$tagmax=50;
if($tagmax<=0)$tagmax=10;
$result3=db_query("
SELECT cantidad
FROM {$db_prefix}tags
WHERE rango=1
ORDER BY cantidad DESC
LIMIT 29,1", __FILE__, __LINE__);
while($row=mysql_fetch_array($result3)){$cantidad=$row['cantidad'];}
$result=db_query("
SELECT palabra as tag,count(palabra) as quantity, cantidad
FROM {$db_prefix}tags
WHERE cantidad >= $cantidad AND rango=1
GROUP BY palabra
ORDER BY palabra DESC
LIMIT 0,$tagmax", __FILE__, __LINE__);
while($row=mysql_fetch_array($result)){$tags[$row['tag']]=$row['cantidad'];}
$max_qty = max(array_values($tags));
$universo = array_sum(array_values($tags));
$elemento_menor = min(array_values($tags));
$hoja=max(array_values($tags))-$elemento_menor;
if($hoja<=0)$hoja=1;
$letra_hoja=$fontmax-$fontmin;
if($letra_hoja<= 0)$letra_hoja=1;
$font_step=$letra_hoja/$hoja;
$asdas=1;
foreach($tags as $key=>$value){$porcentaje=0;
$porcentaje=($value/$universo)*100;
$tamanio=(int)($fontmin+(($value-$elemento_menor)*$font_step));
$asfff=++$asdas;
$paltag=strtolower(str_replace('%','',$key));
echo'<a href="/tags/'.$paltag.'" style="font-size:'.$tamanio.'pt;margin-right:2px;margin-bottom:2px;" title="'.$value.' post con el tag '.$paltag.'">'.$paltag.'</a>&nbsp;';
if($asfff==5)echo'<br />';
if($asfff==10)echo'<br />';
if($asfff==15)echo'<br />';
if($asfff==20)echo'<br />';
if($asfff==25)echo'<br />';
if($asfff==30)echo'<br />';
if($asfff==35)echo'<br />';
if($asfff==40)echo'<br />';} ?>
</center></div></div>

<div class="act_comments">
<div class="box_title" style="width: 361px;"><div class="box_txt ultimos_comments">Destacados</div>
<div class="box_rss"><img alt="" src="<?php echo $tranfer1;?>/blank.gif" style="width:16px;height:16px;" border="0" /></div></div><div align="center" class="windowbg" style="width: 353px; padding:4px;margin-bottom:8px;"><?php destacado(); ?></div></div>


</div>

<div style="float:left;margin-right: 0px;">
<?php include($boarddir.'/web/cw-AmistadesAct.php'); ?>
<div class="img_aletat"><div class="box_title" style="width: 161px;"><div class="box_txt img_aletat">&Uacute;ltimas im&aacute;genes</div>
<div class="box_rss"><img alt="" src="<?php echo $tranfer1;?>/blank.gif" style="width:16px;height:16px;" border="0" /></div></div><div class="windowbg" style="padding:4px;width:153px;margin-bottom:8px;font-size:11px;">
<?php foreach($context['ultimas_img'] as $ui){
$titulo=censorText(nohtml2($ui['titulo'])); ?>
<div class="postENTry" style="background-color:#FFFFCC;"><a href="/imagenes/ver/<?php echo $ui['id']; ?>" title="<?php echo $titulo;?>" class="categoriaPost imagenesNOCAT" target="_self"><?php if(strlen($titulo)>24){$titulo3=substr($titulo,0,strrpos(substr($titulo,0,21)," "))."..."; echo $titulo3;}else{echo $titulo;}?></a></div>
<?php } 
if(!empty($context['user']['id'])){ ?> <center><a href="/web/cw-TEMPAgregarIMG.php" class="boxy" title="Agrega tu imagen">Agrega tu imagen</a></center> <?php } ?> </div></div>

<?php $dasda=1; ?>
<div class="img_aletat">
<div class="box_title" style="width: 161px;"><div class="box_txt img_aletat">User de la semana</div>
<div class="box_rss"><img alt="" src="<?php echo $tranfer1;?>/blank.gif" style="width:16px;height:16px;" border="0" /></div></div><div class="windowbg" style="padding:4px;width:153px;margin-bottom:8px;">
<?php foreach ($context['top_posters_week'] as $poster) {?>
<font style="font-size:11px"><b><?php echo $dasda++;?> - </b><?php echo $poster['link'];?> (<?php echo $poster['num_posts'];?>)</font><br />
<?php } ?>
</div></div>

<?php $dasda4=1; ?>
<div class="img_aletat">
<div class="box_title" style="width: 161px;"><div class="box_txt img_aletat">User con m&aacute;s post</div>
<div class="box_rss"><img alt="" src="<?php echo $tranfer1;?>/blank.gif" style="width:16px;height:16px;" border="0" /></div></div><div class="windowbg" style="padding:4px;width:153px;margin-bottom:8px;">
<?php foreach ($context['top_starters'] as $poster) {?>
<font style="font-size:11px"><b><?php echo $dasda4++;?> - </b><a href="/perfil/<?php echo $poster['realName'];?>" title="<?php echo $poster['realName'];?>"><?php echo $poster['realName'];?></a> (<?php echo $poster['cuenta'];?>)</font><br />
<?php }?>

</div></div>

<div class="img_aletat">
<div class="box_title" style="width: 161px;"><div class="box_txt img_aletat">Enlaces</div>
<div class="box_rss"><img alt="" src="<?php echo $tranfer1;?>/blank.gif" style="width:16px;height:16px;" border="0" /></div></div><div class="windowbg" style="padding:4px;width:153px;margin-bottom:8px;"><?php enlaces(); ?></div></div>

<?php if($context['user']['name']=='Miguel'){

$context['invitados']=mysql_num_rows(db_query("SELECT ID_MEMBER FROM {$db_prefix}log_online WHERE ID_MEMBER=0", __FILE__, __LINE__));
$context['usuarios']=mysql_num_rows(db_query("SELECT ID_MEMBER FROM {$db_prefix}log_online WHERE ID_MEMBER<>0", __FILE__, __LINE__));
    
?>

<div class="img_aletat"><div class="box_title" style="width: 161px;"><div class="box_txt img_aletat">Estadisticas</div>
<div class="box_rss"><img alt="" src="<?php echo $tranfer1;?>/blank.gif" style="width:16px;height:16px;" border="0" /></div></div><div class="windowbg" style="padding:4px;font-size:11px;width:153px;margin-bottom:8px;">


<b style="color:green;"><?php echo ($context['invitados']+$context['usuarios']);?> personas conectadas</b><br />
<?php echo $context['invitados'];?> invitados conectados<br />
<a href="/conectados/" title="<?php echo $context['usuarios'];?> registrados conectados"><?php echo $context['usuarios'];?> registrados conectados</a>
<?php $context['cantidadcoment']=mysql_num_rows(db_query("SELECT id_coment FROM {$db_prefix}comentarios", __FILE__, __LINE__)); ?>

<?php echo $context['cantidadcoment'];?> comentarios</div></div>

<?php } ?>

</div><div style="clear:left;"></div></div>

<?php }
function mensajes(){global $context,$db_prefix,$modSettings; 
if(!$context['user']['is_admin']){$shas=' AND m.ID_BOARD<>142';}else{$shas='';}
$rs=db_query("
SELECT c.id_post,m.ID_TOPIC,c.id_user,mem.ID_MEMBER,m.ID_BOARD,b.ID_BOARD,c.id_coment,m.subject,b.description,memberName,realName
FROM ({$db_prefix}comentarios AS c)
INNER JOIN {$db_prefix}messages AS m ON c.id_post=m.ID_TOPIC
INNER JOIN {$db_prefix}members AS mem ON c.id_user=mem.ID_MEMBER
INNER JOIN {$db_prefix}boards as b ON m.ID_BOARD=b.ID_BOARD$shas
ORDER BY c.id_coment DESC
LIMIT $modSettings[catcoment]",__FILE__, __LINE__);
$context['comentarios25']=array();
while($row=mysql_fetch_assoc($rs)){
censorText($row['subject']);
$context['comentarios25'][] = array(
		'id_coment' => $row['id_coment'],
			'titulo' => censorText($row['subject']),
			'ID_TOPIC' => $row['ID_TOPIC'],
			'description' => $row['description'],
			'memberName' => $row['memberName'],
			'realName' => $row['realName'],
		);}mysql_free_result($rs);
foreach ($context['comentarios25'] as $coment25){ ?>
<font class="size11" ><b><a title="<?php echo $coment25['realName']; ?>" href="/perfil/<?php echo $coment25['realName']; ?>"><?php echo $coment25['realName']; ?></a></b> - <a title="<?php echo $coment25['titulo'];?>"  href="/post/<?php echo $coment25['ID_TOPIC']; ?>/<?php echo $coment25['description'];?>/<?php echo urls($coment25['titulo']);?>.html#cmt_<?php echo $coment25['id_coment'];?>"><?php echo achicars($coment25['titulo']);?></a></font><br style="margin:0px;padding:0px;" />
<?php }} ?>