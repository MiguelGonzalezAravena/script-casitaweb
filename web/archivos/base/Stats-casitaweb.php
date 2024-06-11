<?php
function template_main(){

global $context, $settings, $options, $txt,$db_prefix, $scripturl, $modSettings,$tranfer1, $boardurl;
$contar=1;
$saksdmpas2=1;
$contar2=1;
$contar3=1;
$contar4=1;
$contar5=1;
$contar22=1;
$contar6=1;
$contar7=1;
$contar25=1;
$contar9=1;
$contar8=1; ?>

<div>
<div class="box_300" align="left" style="float:left;margin-right: 8px;">
<div class="box_title" style="width: 300px;"><div class="box_txt box_300-34">10 Posts m&aacute;s comentados</div>
<div class="box_rss"><div class="icon_img"><img alt="" src="<?php echo $tranfer1;?>/blank.gif" style="width: 16px; height: 16px;" border="0" /></div></div></div><div class="windowbg" style="width: 292px; padding: 4px;">

<?php foreach($context['tcomentados'] as $total) {?>
<span class="size11"><b><?php echo $contar9++;?> - </b> <a title="<?php echo achicar($total['subject']);?>" href="/post/<?php echo $total['id'];?>/<?php echo urls($total['description']);?>/<?php echo urls($total['subject']);?>.html"><?php echo  achicar($total['subject']);?></a> (<?php echo $total['cuenta'];?> com)</span><br />
<?php } ?>

</div></div>


<div class="box_300" align="left" style="float:left; margin-right: 8px;">
<div class="box_title" style="width: 300px;"><div class="box_txt box_300-34">10 Posts m&aacute;s vistos</div>
<div class="box_rss"><div class="icon_img"><img alt="" src="<?php echo $tranfer1;?>/blank.gif" style="width: 16px; height: 16px;" border="0" /></div></div></div><div class="windowbg" style="width: 292px; padding: 4px;">
<?php foreach ($context['top_topics_views'] as $topic) { ?>
<span class="size11"><b><?php echo $contar3++;?> - </b><a title="<?php echo $topic['subject'];?>" href="<?php echo $topic['href'];?>"><?php echo achicar($topic['subject']);?></a> (<?php echo $topic['num_views'];?> vis)</span><br />
<?php } ?>

</div></div>

<div class="box_300" align="left" style="float:left;">
<div class="box_title" style="width: 300px;"><div class="box_txt box_300-34">10 Post con m&aacute;s puntos</div>
<div class="box_rss"><div class="icon_img"><img alt="" src="<?php echo $tranfer1;?>/blank.gif" style="width: 16px; height: 16px;" border="0" /></div></div></div><div class="windowbg" style="width: 292px; padding: 4px;">
<?php foreach ($context['postporpuntos'] as $ppp){ ?>

<span class="size11"><b><?php echo $contar6++;?> - </b><a title="<?php echo $ppp['titulo'];?>" href="/post/<?php echo $ppp['id'];?>/<?php echo urls($ppp['description']);?>/<?php echo urls($ppp['titulo']);?>.html"><?php echo achicar($ppp['titulo']);?></a> (<?php echo $ppp['puntos'];?> pts)</span><br />
<?php } ?>
</div></div>
</div>
<div style="clear: left;"></div>

<div style="margin-top:8px;">
<div class="box_300" align="left" style="float:left; margin-right: 8px;">
<div class="box_title" style="width: 300px;"><div class="box_txt box_300-34">10 Principales Posteadores</div>
<div class="box_rss"><div class="icon_img"><img alt="" src="<?php echo $tranfer1;?>/blank.gif" style="width: 16px; height: 16px;" border="0" /></div></div></div><div class="windowbg" style="width: 292px; padding: 4px;">
<?php foreach ($context['tuser'] as $poster){ ?>
<span class="size11"><b><?php echo $contar4++;?> - </b><a title="<?php echo censorText($poster['realName']);?>" href="/perfil/<?php echo $poster['realName'];?>"><?php echo censorText($poster['realName']);?></a> (<?php echo $poster['cuenta'];?> post)</span><br />
<?php } ?>
</div></div>


<div class="box_300" align="left" style="float:left; margin-right: 8px;">
<div class="box_title" style="width: 300px;"><div class="box_txt box_300-34">10 Usuarios con m&aacute;s puntos</div>
<div class="box_rss"><div class="icon_img"><img alt="" src="<?php echo $tranfer1;?>/blank.gif" style="width: 16px; height: 16px;" border="0" /></div></div></div><div class="windowbg" style="width: 292px; padding: 4px;">
<?php foreach ($context['shop_richest'] as $row){ ?>

<span class="size11"><b><?php echo $contar5++;?> - </b> <a title="<?php echo $row['realName'];?>" href="/perfil/<?php echo $row['realName'];?>"><?php echo $row['realName'];?></a> (<?php echo $row['money'];?> pts)</span><br />
<?php }?>
</div></div>

<div class="box_300" align="left" style="float:left;">
<div class="box_title" style="width: 300px;"><div class="box_txt box_300-34">10 Usuarios que m&aacute;s comentan</div>
<div class="box_rss"><div class="icon_img"><img alt="" src="<?php echo $tranfer1;?>/blank.gif" style="width: 16px; height: 16px;" border="0" /></div></div></div><div class="windowbg" style="width: 292px; padding: 4px;">
<?php $order=array();
$r=db_query("SELECT count(id_user) as Rows, id_user FROM {$db_prefix}comentarios GROUP BY id_user ORDER BY Rows DESC LIMIT 30", __FILE__, __LINE__);
while($row=mysqli_fetch_assoc($r)){
$r2=db_query("SELECT count(ID_MEMBER) as Rowsd, ID_MEMBER FROM {$db_prefix}gallery_comment WHERE ID_MEMBER={$row["id_user"]} 
GROUP BY ID_MEMBER ORDER BY Rowsd DESC LIMIT 10", __FILE__, __LINE__);
while($row2=mysqli_fetch_assoc($r2)){
$sers=db_query("SELECT ID_MEMBER,realName FROM {$db_prefix}members WHERE ID_MEMBER='{$row2["ID_MEMBER"]}' LIMIT 10", __FILE__, __LINE__);
while($grup=mysqli_fetch_assoc($sers)){
$order[$grup['realName']]=($row2["Rowsd"]+$row["Rows"]);}}}
arsort($order);
$e=1;
while ((list($i, $Valor) = each($order)) && $e <= 10) { ?>

<span class="size11"><b><?php echo$e++;?></b> - <a href="<?php echo $boardurl; ?>/perfil/<?php echo $i;?>" title="<?php echo $i;?>"><?php echo $i;?></a> (<?php echo $Valor;?> com)</span><br/>

<?php } ?>

</div></div>
</div>
<div style="clear: left;"></div>
<div style="margin-top:8px;">
<div class="box_300" align="left" style="float:left; margin-right: 8px;">
<div class="box_title" style="width: 300px;"><div class="box_txt box_300-34">10 Im&aacute;genes m&aacute;s comentadas</div>
<div class="box_rss"><div class="icon_img"><img alt="" src="<?php echo $tranfer1;?>/blank.gif" style="width: 16px; height: 16px;" border="0" /></div></div></div><div class="windowbg" style="width: 292px; padding: 4px;">
<?php foreach ($context['comment-img2'] as $poster){ ?>
<span class="size11"><b><?php echo $contar++;?> - </b><a title="<?php echo $poster['title'];?>" href="/imagenes/ver/<?php echo $poster['id'];?>"><?php echo achicar($poster['title']);?></a> (<?php echo $poster['commenttotal'];?> com)</span><br />
<?php } ?>

</div></div>

<div class="box_300" align="left" style="float:left;margin-right: 8px;">
<div class="box_title" style="width: 300px;"><div class="box_txt box_300-34">10 Im&aacute;genes m&aacute;s vistas</div>
<div class="box_rss"><div class="icon_img"><img alt="" src="<?php echo $tranfer1;?>/blank.gif" style="width: 16px; height: 16px;" border="0" /></div></div></div><div class="windowbg" style="width: 292px; padding: 4px;">
<?php foreach ($context['imgv'] as $imgv){ ?>

<span class="size11"><b><?php echo $contar7++;?> - </b><a title="<?php echo censorText($imgv['titulo']);?>" href="/imagenes/ver/<?php echo $imgv['id'];?>"><?php echo achicar($imgv['titulo']);?></a> (<?php echo $imgv['v'];?> vis)</span><br />

<?php } ?>

</div></div>

<div class="box_300" align="left" style="float:left;">
<div class="box_title" style="width: 300px;"><div class="box_txt box_300-34">10 Im&aacute;genes con m&aacute;s puntos</div>
<div class="box_rss"><div class="icon_img"><img alt="" src="<?php echo $tranfer1;?>/blank.gif" style="width: 16px; height: 16px;" border="0" /></div></div></div><div class="windowbg" style="width: 292px; padding: 4px;">
<?php foreach ($context['comment-img3'] as $topic) { ?>
<span class="size11"><b><?php echo $contar2++;?> - </b><a title="<?php echo censorText($topic['title']);?>" href="/imagenes/ver/<?php echo $topic['id'];?>"><?php echo achicar($topic['title']);?></a> (<?php echo $topic['puntos'];?> pts)</span><br />
<?php } ?>

</div></div>
</div>
<div style="clear: left;"></div>
<div style="margin-top:8px;">
<div class="box_300" align="left" style="float:left;margin-right: 8px;">
<div class="box_title" style="width: 300px;"><div class="box_txt box_300-34">10 Muros m&aacute;s comentados</div>
<div class="box_rss"><div class="icon_img"><img alt="" src="<?php echo $tranfer1;?>/blank.gif" style="width: 16px; height: 16px;" border="0" /></div></div></div>
<div class="windowbg" style="width: 292px; padding: 4px;">

<?php foreach ($context['muroc'] as $cmuro) { ?>
<span class="size11"><b><?php echo $contar25++;?> - </b><a title="<?php echo $cmuro['realName'];?>" href="/perfil/<?php echo $cmuro['realName'];?>"><?php echo $cmuro['realName'];?></a> (<?php echo $cmuro['cuenta'];?> mjs)</span><br />
<?php } ?>
</div></div>


<div class="box_300" align="left" style="float:left;margin-right: 8px;">
<div class="box_title" style="width: 300px;"><div class="box_txt box_300-34">10 Usuarios con m&aacute;s im&aacute;genes</div>
<div class="box_rss"><div class="icon_img"><img alt="" src="<?php echo $tranfer1;?>/blank.gif" style="width: 16px; height: 16px;" border="0" /></div></div></div><div class="windowbg" style="width: 292px; padding: 4px;">

<?php foreach ($context['masi'] as $imas){ ?>
<span class="size11"><b><?php echo $contar22++;?> - </b><a title="<?php echo $imas['realName'];?>" href="/perfil/<?php echo $imas['realName'];?>"><?php echo $imas['realName'];?></a> (<?php echo $imas['cuenta'];?> img)</span><br />
<?php } ?>
</div></div>

<div class="box_300" align="left" style="float:left;">
<div class="box_title" style="x"><div class="box_txt box_300-34">Publicidad</div>
<div class="box_rss"><div class="icon_img"><img alt="" src="<?php echo $tranfer1;?>/blank.gif" style="width: 16px; height: 16px;" border="0" /></div></div></div><div class="windowbg" style="width: 292px; padding: 4px;"><script type="text/javascript"><!--
google_ad_client = "pub-5583945616614902";
/* 300x250, creado 19/07/09 */
google_ad_slot = "3426331033";
google_ad_width = 285;
google_ad_height = 135;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script></div></div>

</div>
<div style="clear: left;"></div>

<?php if (!empty($context['monthly']) & ($context['user']['is_admin'])){ ?>
<div style="margin-top:8px;">
<div class="box_buscador">
<div class="box_title" style="width: 920px;"><div class="box_txt box_buscadort"><center>Historia del Foro (usando diferencia horaria del foro)</center></div>
<div class="box_rss"><img alt="" src="<?php echo $tranfer1;?>/blank.gif" style="width: 14px; height: 12px;" border="0" /></div></div>
<div style="width: 920px;" class="windowbg">
<table border="0" width="100%" cellspacing="1" cellpadding="4" style="margin-bottom: 1ex;" id="stats">
						<tr class="titlebg" valign="middle">
							<td width="25%">Mes</td>
							<td width="15%">Posts Nuevos</td>
							<td width="15%">Usuarios Nuevos</td>
							
<?php if (!empty($modSettings['hitStats'])) ?>

<td>P&aacute;gina vistas</td>
</tr>
<?php foreach ($context['monthly'] as $month){ ?>
<tr class="windowbg2" valign="middle" id="tr_<?php echo $month['id'];?>">
<th align="left" width="25%">
<a name="<?php echo $month['id'];?>" id="link_<?php echo $month['id'];?>" href="<?php echo $month['href'];?>" onclick="return doingExpandCollapse || expand_collapse('<?php echo $month['id'];?>', <?php echo $month['num_days'];?>);"> <?php echo $month['month'];?> <?php echo $month['year'];?></a>
							</th>
							<th width="15%"><?php echo $month['new_topics'];?></th>
							<th width="15%"><?php echo $month['new_members'];?></th>
<?php if (!empty($modSettings['hitStats'])) ?>

<th><?php echo $month['hits'];?></th></tr>

<?php if ($month['expanded']){
	foreach ($month['days'] as $day) { ?>

<tr class="windowbg2" valign="middle" align="left">
<td align="left" style="padding-left: 3ex;"><?php echo $day['year'];?>-<?php echo $day['month'];?>-<?php echo $day['day'];?></td>
<td><?php echo $day['new_topics'];?></td>
<td><?php echo $day['new_members'];?></td>

<?php if (!empty($modSettings['hitStats'])) ?>
<td><?php echo $day['hits'];?></td></tr>

<?php }}} ?>

</table></div></div>
</div>
<div style="clear: left;"></div>
<?php } ?>


<?php } ?>