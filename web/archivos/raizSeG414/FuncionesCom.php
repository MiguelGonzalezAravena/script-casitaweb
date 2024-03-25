<?php
//Pagina de Rodrigo Zaupa (rigo@casitaweb.net)f
if (!defined('CasitaWeb!-PorRigo'))die(base64_decode("d3d3LmNhc2l0YXdlYi5uZXQgLSByaWdv"));
$context['cc']='10';
function reglas_com($das){global $db_prefix; 
if($das=='crearc'){echo'Antes de crear una comunidad es importante que leas el <a href="/protocolo/">protocolo</a>.<br /><br />
Al crear la comunidad vas a ser due&ntilde;o/Administrador de tal por lo tanto tendr&aacute;s todos los permisos de un Administrador.<br /><br />
Podes crear tu propio protocolo para tu comunidad, pero siempre respetando el protocolo general.<br /><br />
Si tenes dudas sobre las comunidades visita <a href="http://ayuda.casitaweb.net/categoria/comunidades/">este enlace</a>.';}
elseif($das=='creart'){
echo'Antes de crear un nuevo tema es importante que leas el <a href="/protocolo/">protocolo</a>.<br /><br />
Al ser el creador del tema, tenes el permiso de editarlo, eliminarlo, eliminar comentarios, bloquear comentarios.<br /><br />
Si desea que su tema este fijado en la comunidad debe comunicarse con lo(s) Administrador(es) o Moderador(es) de la comunidad ya que ellos son los &uacute;nicos capaces de fijarlo.<br /><br />
Si tenes dudas sobre las comunidades visita <a href="http://ayuda.casitaweb.net/categoria/comunidades/">este enlace</a>.';}}

function arriba($da='',$a1='',$a2='',$a3='',$a4='',$a5='',$a6=''){global $db_prefix;
$comunidades='<li><a href="/comunidades/" title="Comunidades">Comunidades</a></li>';

$_GET['miembros']=isset($_GET['miembros']) ? $_GET['miembros'] : ''; 
$_GET['cat']=isset($_GET['cat']) ? $_GET['cat'] : ''; 
$_GET['id']=isset($_GET['id']) ? $_GET['id'] : ''; 

if(empty($da)){$resultado=$comunidades;}
elseif($da=='tema'){$resultado=$comunidades. '<li><a href="'.$a1.'" title="'.$a2.'">'.$a2.'</a></li> <li><a href="'.$a3.'" title="'.$a4.'">'.$a4.'</a></li> <li id="activer">'.$a5.'</li>';}
elseif($da=='CrearCom'){$resultado=$comunidades.'<li id="activer">Crear Comunidad</li>';}
elseif($da=='CrearTema'){$resultado=$comunidades.'<li><a href="'.$a1.'" title="'.$a2.'">'.$a2.'</a></li> <li><a href="'.$a3.'" title="'.$a4.'">'.$a4.'</a></li> <li id="activer">Crear Tema</li>';}
elseif($da=='EditarTema'){$resultado=$comunidades.'<li><a href="'.$a1.'" title="'.$a2.'">'.$a2.'</a></li> <li><a href="'.$a3.'" title="'.$a4.'">'.$a4.'</a></li> <li><a href="'.$a5.'" title="'.$a6.'">'.$a6.'</a></li> <li id="activer">Editar Tema</li>';}
elseif($da=='EditarCom'){$resultado=$comunidades.'<li><a href="'.$a1.'" title="'.$a2.'">'.$a2.'</a></li> <li><a href="'.$a3.'" title="'.$a4.'">'.$a4.'</a></li>  <li id="activer">Editar Comunidad</li>';}
elseif($da=='buscar'){$resultado=$comunidades.'<li id="activer">Buscar</li>';}
elseif($da=='buscar'){$resultado=$comunidades.'<li id="activer">Buscar</li>';}
elseif($da=='directorios'){$resultado=$comunidades.'<li id="activer">Directorios</li>';}

$cat=str_replace("/","",seguridad($_GET['cat']));
$id=str_replace("/","",seguridad($_GET['id']));
if($cat){
$rs=db_query("SELECT b.nombre FROM ({$db_prefix}comunidades_categorias AS b) WHERE b.url='$cat' LIMIT 1",__FILE__, __LINE__);while($row=mysql_fetch_assoc($rs)){$categoria= $row['nombre'];
if($da=='directorios'){$resultado=$comunidades.' <li><a href="/comunidades/dir/" title="Directorios">Directorios</a></li><li id="activer">'.$categoria.'</li>';}else{$resultado=$comunidades.' <li id="activer">'.$categoria.'</li>';}}
if(!$categoria){fatal_error('Esta categor&iacute;a no existe.-');}}

elseif($id){
$rs=db_query("
SELECT c.nombre,b.url,b.nombre as cnam,c.url AS url2,c.id
FROM ({$db_prefix}comunidades_categorias AS b, {$db_prefix}comunidades AS c)
WHERE c.url='$id' AND c.categoria=b.url
LIMIT 1",__FILE__, __LINE__);
while ($row=mysql_fetch_assoc($rs)){
$categoria=$row['cnam'];
$url=$row['url'];
$urlIDDD=$row['id'];
$row['nombre']=nohtml($row['nombre']);

if(!$_GET['miembros']){$resultado=$comunidades.' <li><a href="/comunidades/categoria/'.$row['url'].'" title="'.$row['cnam'].'">'.$row['cnam'].'</a></li> <li id="activer">'.$row['nombre'].'</li>';}

elseif($_GET['miembros']=='3'){$resultado=$comunidades.' <li><a href="/comunidades/categoria/'.$row['url'].'" title="'.$row['cnam'].'">'.$row['cnam'].'</a></li> <li><a href="/comunidades/'.$row['url2'].'" title="'.$row['nombre'].'">'.$row['nombre'].'</a></li> <li id="activer">Administrar comunidad</li>';}

elseif($_GET['miembros']=='8'){$resultado=$comunidades.' <li><a href="/comunidades/categoria/'.$row['url'].'" title="'.$row['cnam'].'">'.$row['cnam'].'</a></li> <li><a href="/comunidades/'.$row['url2'].'" title="'.$row['nombre'].'">'.$row['nombre'].'</a></li> <li id="activer">Denunciar comunidad</li>';}

elseif($_GET['miembros']=='9'){$resultado=$comunidades.' <li><a href="/comunidades/categoria/'.$row['url'].'" title="'.$row['cnam'].'">'.$row['cnam'].'</a></li> <li><a href="/comunidades/'.$row['url2'].'" title="'.$row['nombre'].'">'.$row['nombre'].'</a></li> <li id="activer">Publicitar</li>';}
}if(!$urlIDDD){fatal_error('Esta comunidad no existe.-');}

}


echo'<div style="clear: both;margin-bottom:8px;width:922px;"><div class="tagacom2"><ul>'.$resultado.'<div style="clear: both;"></div></ul></div><div style="clear: both;"></div></div>';}

function paginacion($total,$pp,$st,$url) {
if($total>$pp) {
$resto=$total%$pp;
if($resto==0) {
$pages=$total/$pp;
} else {$pages=(($total-$resto)/$pp)+1;}

if($pages>10) {
$current_page=($st/$pp)+1;
if($st==0) {
$first_page=0;
$last_page=10;
} elseif($current_page>=5 && $current_page <= ($pages-5)) {
$first_page=$current_page-5;
$last_page=$current_page+5;}
elseif($current_page<5){$first_page=0;
$last_page=$current_page+5+(5-$current_page);
}else{
$first_page=$current_page-5-(($current_page+5)-$pages);
$last_page=$pages;}}else{$first_page=0; $last_page=$pages;}
 
for($i=$first_page;$i < $last_page;$i++) {
$pge=$i+1;
$nextst=$i*$pp;+
$page_nav=isset($page_nav) ? $page_nav : ''; 
if($st==$nextst){$page_nav .= '<em>'.$pge.'</em>';}else{$page_nav .= '<a href="'.$url.$nextst.'">'.$pge.'</a>';}}
if($st==0){$current_page=1;} else { $current_page = ($st/$pp)+1; }
if($current_page < $pages){$page_next = '<a href="'.$url.$current_page*$pp.'">&#187;</a>';}
 
if($st>0) {
if($st>10){$page_first = '<a href="'.$url.'0">&#171;&#171;&#171;</a>';}else $page_first='';
$page_previous = '<a href="'.$url.''.($current_page-2)*$pp.'">&#171;</a>';
}else {$page_first='';$page_previous='';}

} 
$page_first=isset($page_first) ? $page_first : ''; 
$page_previous=isset($page_previous) ? $page_previous : ''; 
$page_next=isset($page_next) ? $page_next : ''; 
$page_last=isset($page_last) ? $page_last : ''; 
$page_nav=isset($page_nav) ? $page_nav : ''; 
return "<div align='right' class='paginacion2' style='float:right;'>$page_first $page_previous $page_nav $page_next $page_last</div>";}




// permiso 1 ADMIN COMU permiso 2 mods cw /*/ permiso 0 Sin permisos
function permisios($id){
global $context,$user_info,$ID_MEMBER,$db_prefix;
if($user_info['is_guest']){$context['permisoCom']='0';}else{
    
$rs44=db_query("
SELECT a.rango
FROM ({$db_prefix}comunidades_miembros as a)
WHERE a.id_user='$ID_MEMBER' AND a.id_com='$id'
LIMIT 1",__FILE__, __LINE__);
while ($row=mysql_fetch_assoc($rs44)){$dasfffd=$row['rango'];}

$dasfffd=isset($dasfffd) ? $dasfffd : ''; 

if($dasfffd=='1'){$context['permisoCom']='1';}
elseif($dasfffd=='5'){$context['permisoCom']='3';}
elseif(($user_info['is_admin'] || $user_info['is_mods'])){$context['permisoCom']='2';}
else{$context['permisoCom']='0';}
}
return true;}



function miembro($id){global $context,$ID_MEMBER,$db_prefix;
if(!$context['user']['is_guest']){
$soy=mysql_num_rows(db_query("SELECT c.id FROM ({$db_prefix}comunidades as c,{$db_prefix}comunidades_miembros as a) WHERE a.id_user='$ID_MEMBER' AND a.id_com='$id' AND a.aprobado=1 LIMIT 1",__FILE__, __LINE__));

if($soy){$context['miembro']='1';}
else{$context['miembro']='0';}

}else{$context['miembro']='0';}

return true;}

function eaprobacion($id_com){ global  $context,$db_prefix,$user_settings;

if(!$context['allow_admin'] && !$context['user']['is_guest']){
$enespera=mysql_num_rows(db_query("SELECT c.id_user FROM ({$db_prefix}comunidades_miembros AS c) 
WHERE c.id_com='$id_com' AND c.id_user='{$user_settings['ID_MEMBER']}' AND c.aprobado=0 LIMIT 1",__FILE__, __LINE__));
if($enespera){$dde=true;}else{$dde=false;}
return $dde;}

}

//para comentar postear o nadam, 3(admins PUEEN TOOD) / 1: puee comentar y postear // 2: solo comentar // 0: NADA
function acces($id){
global $context,$user_info,$settings,$db_prefix,$ID_MEMBER, $txt, $modSettings;
if($user_info['is_guest']){$context['puedo']='0';}else{
$rs44=db_query("
SELECT a.rango,c.permiso
FROM ({$db_prefix}comunidades as c,{$db_prefix}comunidades_miembros as a)
WHERE a.id_user='$ID_MEMBER' AND a.id_com='$id' AND a.id_com=c.id
LIMIT 1",__FILE__, __LINE__);
while ($row=mysql_fetch_assoc($rs44)){
if($row['rango']=='1'){$context['puedo']='3';}
elseif($row['rango']=='2'){$context['puedo']='1';}
elseif($row['rango']=='5'){$context['puedo']='1';}
elseif($row['rango']=='3'){$context['puedo']='1';}
elseif($row['rango']=='4'){$context['puedo']='0';}
elseif(!$row['rango']){
if($row['permiso']=='3'){$context['puedo']='1';}
elseif($row['permiso']=='2'){$context['puedo']='2';}
else{$context['puedo']='0';}}
$da=$row['permiso'];}
$da=isset($da) ? $da : ''; 
if(!$da){$context['puedo']='0';}}
return true;}

function entrar($id,$t=''){global $user_info,$db_prefix,$ID_MEMBER;
$rs44=db_query("
SELECT c.acceso,c.url
FROM ({$db_prefix}comunidades as c)
WHERE c.id='$id'
LIMIT 1",__FILE__, __LINE__);
while ($row=mysql_fetch_assoc($rs44)){$sdas223d=$row['acceso'];$sdaddd=$row['url'];}
if($user_info['is_guest'] && ($sdas223d=='2' || $sdas223d=='3')){is_not_guest('','header');}
else{
$rs4ee4=db_query("
SELECT c.id
FROM ({$db_prefix}comunidades_miembros as c)
WHERE c.id_com='$id' AND c.id_user='$ID_MEMBER' AND c.aprobado=1
LIMIT 1",__FILE__, __LINE__);
while ($row=mysql_fetch_assoc($rs4ee4)){$ddas224d=$row['id'];}
if(eaprobacion($id) || (($sdas223d=='3' || $sdas223d=='4') && !$ddas224d)){
if($user_info['is_admin'] || $user_info['is_mods']){$estan='';}else{
if(eaprobacion($id)){$estan='<div class="noesta-am" style="width:922px;">Esperando aprobaci&oacute;n de Administrador.</div>';}

else{$estan='<div class="noesta-am" style="width:922px;">Solo miembros de esta comunidad pueden acceder.<br/><a href="#"  onclick="if (!confirm(\'\xbfEstas seguro que desea unirse a esta comunidad?\')) return false; javascript:window.location.href=\'/web/cw-comunidadesUnirCom.php?id='.$sdaddd.'\'" >UNITE a esta comunidad</a>
</div>';}}
}else{$estan='';}

}
return $estan;}

function baneadoo($id){global $user_info,$ID_MEMBER,$db_prefix;
$rs44=db_query("
SELECT m.ban_por,m.ban_razon,m.ban_expirate,m.id
FROM ({$db_prefix}comunidades_miembros as m)
WHERE m.id_com='$id' AND m.id_user='$ID_MEMBER' AND m.ban=1 AND m.aprobado=1
LIMIT 1",__FILE__, __LINE__);
while($row=mysql_fetch_assoc($rs44)){
    $ban_razon=$row['ban_razon'];
    $ban_por=$row['ban_por'];
    $idor=$row['id'];
    $ban_expirate=$row['ban_expirate'] === null ? 'Nunca' : ($row['ban_expirate'] < time() ? 'Ya termino' : (int)ceil(($row['ban_expirate'] - time()) / (60 * 60 * 24)) . '&nbsp;d&iacute;a(s)');
    $ban_expirat_eliminar=$row['ban_expirate'] === null ? '0' : ($row['ban_expirate'] < time() ? '1' : '0');
    
if($ban_expirat_eliminar){
db_query("UPDATE {$db_prefix}comunidades_miembros
			SET ban=0, ban_razon='',ban_expirate ='' , ban_por=''
			WHERE id='$idor'
			LIMIT 1", __FILE__, __LINE__);}else{
arriba();
fatal_error('<center><b style="color:red;">Tu cuenta en esta comunidad se encuentra baneada.</b><br/>
<b>Raz&oacute;n:</b> '.nohtml(nohtml2($ban_razon)).'<br/>
<b>Por:</b> '.$ban_por.'<br/>
<b>Expira:</b> '.$ban_expirate.'</center>',false);}

 }
return true;}

function textaer($dd=''){
global $tranfer1, $context,$user_info, $settings,$db_prefix,$options, $txt, $modSettings;

	loadLanguage('Post');
	if (empty($modSettings['smiley_enable']) && $user_info['smiley_set'] != 'none')
		$context['smileys']['postform'][] = array();
	elseif ($user_info['smiley_set'] != 'none')
	{
		if (($temp = cache_get_data('posting_smileys', 480)) == null)
		{
			$request = db_query("
				SELECT code, filename, description, smileyRow, hidden
				FROM {$db_prefix}smileys
				WHERE hidden IN (0, 2)
				ORDER BY smileyRow, smileyOrder", __FILE__, __LINE__);
			while ($row = mysql_fetch_assoc($request))
			{
				$row['code'] = htmlspecialchars($row['code']);
				$row['filename'] = htmlspecialchars($row['filename']);
				$row['description'] = htmlspecialchars($row['description']);

				$context['smileys'][empty($row['hidden']) ? 'postform' : 'popup'][$row['smileyRow']]['smileys'][] = $row;
			}
			mysql_free_result($request);

			cache_put_data('posting_smileys', $context['smileys'], 480);
		}
		else
			$context['smileys'] = $temp;
	}

	foreach (array_keys($context['smileys']) as $location)
	{
		foreach ($context['smileys'][$location] as $j => $row)
		{
			$n = count($context['smileys'][$location][$j]['smileys']);
			for ($i = 0; $i < $n; $i++)
			{
				$context['smileys'][$location][$j]['smileys'][$i]['code'] = addslashes($context['smileys'][$location][$j]['smileys'][$i]['code']);
				$context['smileys'][$location][$j]['smileys'][$i]['js_description'] = addslashes($context['smileys'][$location][$j]['smileys'][$i]['description']);
			}

			$context['smileys'][$location][$j]['smileys'][$n - 1]['last'] = true;
		}
		if (!empty($context['smileys'][$location]))
			$context['smileys'][$location][count($context['smileys'][$location]) - 1]['last'] = true;
	}
	$context['show_bbc'] = !empty($modSettings['enableBBC']) && !empty($settings['show_bbc']);
if(!$dd){echo'<textarea style="resize:none;height:70px;width:746px;" name="cuerpo_comment" id="editorCW" tabindex="1"></textarea><p align="right" style="padding:0px;margin:0px;">';}
if(!empty($context['smileys']['postform']))
{foreach ($context['smileys']['postform'] as $smiley_row){
foreach ($smiley_row['smileys'] as $smiley)
echo'<span style="cursor:pointer;" onclick="replaceText(\' ', $smiley['code'], '\', document.forms.nuevocoment.editorCW); return false;"><img class="png" src="'.$tranfer1.'/emoticones/'.$smiley['filename'].'" align="bottom" alt="" title="'.$smiley['description'].'"/></span> ';
if (empty($smiley_row['last']))
echo'<br />';}
if (!empty($context['smileys']['popup']))
echo'<a href="javascript:moticonup()">[', $txt['more_smileys'], ']</a>';}}


function sidebar($id){
global $tranfer1, $func,$ID_MEMBER,$no_avatar, $context,$db_prefix;
$rs=db_query("
SELECT c.nombre,c.url,c.imagen,c.id,c.articulos,c.usuarios,c.paprobar
FROM ({$db_prefix}comunidades_categorias AS b, {$db_prefix}comunidades AS c)
WHERE c.url='$id' AND c.categoria=b.url",__FILE__, __LINE__);
while ($row=mysql_fetch_assoc($rs)){
$cat=nohtml2(nohtml($row['nombre'])); 
$img=nohtml($row['imagen']); 
$caturl=nohtml($row['url']); 
$temas=$row['articulos']; 
$sas=$row['id'];}

if ($img){$img2=$img;}else{$img2=$no_avatar;}



$miembrose=mysql_num_rows(db_query("SELECT c.id FROM ({$db_prefix}comunidades_miembros as c) WHERE c.id_com='$sas' AND c.aprobado=1",__FILE__, __LINE__));
$paprobare=mysql_num_rows(db_query("SELECT c.id FROM ({$db_prefix}comunidades_miembros as c) WHERE c.id_com='$sas' AND c.aprobado=0",__FILE__, __LINE__));

//temas
echo'<div style="margin-bottom:10px;width:160px;margin-right:8px;float:left;">
<div class="box_title" style="width:160px;"><div class="box_txt box_perfil-36">Comunidad</div>
<div class="box_rss"><img alt="" src="'.$tranfer1.'/blank.gif" style="width:16px;height:16px;" border="0" /></div></div><div class="windowbg" style="width:152px;padding:4px;"><center><a href="/comunidades/'.$caturl.'/">';

echo'<img src="'.$img2.'" width="120px" height="120px" alt="" class="avatar" title="Logo de la comunidad" onerror="error_avatar(this)" />';
echo'</a></center><br/>
<a href="/comunidades/'.$caturl.'/" title="'.$cat.'"><b class="size15">'.$cat.'</b></a>
<br/><br/>
<div class="hrs"></div>

<a href="/web/cw-TEMPcomMIEMBROS.php?c='.$sas.'" class="boxy" title="Miembros">'.$miembrose.' Miembros</a><br/>
'.$temas.' Temas<br/>';
if($context['permisoCom']=='1' && $paprobare){echo'<span class="pointer" style="color:#267F00;" onclick="Boxy.load(\'/web/cw-TEMPcomMIEMBROSaDm.php?c='.$sas.'\', { title:\'Miembros en lista de aprobaci&oacute;n\'})" title="Miembros en lista de aprobaci&oacute;n">'.$paprobare.' Esperando aprobaci&oacute;n</span><br/>';}

if($context['allow_admin']){echo'<div class="hrs"></div><center><a href="/comunidades/'.$caturl.'/administrar/" style="color:red;">Administrar Comunidad</a></center>';}

echo'<div class="hrs"></div><br /><center>';

if(!$context['miembro']){
if(!$context['user']['is_guest'] && !$context['permisoCom']){
echo'<input onclick="javascript:window.location.href=\'/comunidades/'.$caturl.'/denunciar\'" alt="" class="DenCom" title="" value=" " align="top" type="submit" /><br /><br />';}
if(!eaprobacion($sas)){
echo'<input onclick="if (!confirm(\'\xbfEstas seguro que desea unirse a esta comunidad?\')) return false; javascript:window.location.href=\'/web/cw-comunidadesUnirCom.php?id='.$caturl.'\'" alt="" class="unirCom" title="" value=" " align="top" type="submit" /><br /><br />';}else{echo'<div class="noesta-am">Esperando aprobaci&oacute;n de Administrador.</div>';}
}else{


if($context['permisoCom']=='1'){
echo'<input onclick="javascript:window.location.href=\'/comunidades/'.$caturl.'/publicitar\'" alt="" class="PublCom" title="" value=" " align="top" type="submit" /><br /><br />

<input onclick="javascript:window.location.href=\'/comunidades/'.$caturl.'/editar\'" alt="" class="EdiCom" title="" value=" " align="top" type="submit" /><br /><br />

<input onclick="if (!confirm(\'\xbfEstas seguro que desea ELIMINAR esta comunidad?\')) return false; javascript:window.location.href=\'/web/cw-comunidadesEliComu.php?id='.$caturl.'\'" alt="" class="EliCom" title="" value=" " align="top" type="submit" /><br /><br />';}

echo'<input onclick="if (!confirm(\'\xbfEstas seguro que desea abandonar esta comunidad?\')) return false; javascript:window.location.href=\'/web/cw-comunidadesAbanCom.php?id='.$caturl.'\'" alt="" class="AbandCom" title="" value=" " align="top" type="submit" /><br /><br /><div class="hrs"></div> ';

$rs444=db_query("SELECT rango
FROM ({$db_prefix}comunidades_miembros)
WHERE id_user='$ID_MEMBER' AND id_com='$sas'
LIMIT 1",__FILE__, __LINE__);
while ($row3=mysql_fetch_assoc($rs444)){$dsdass=$row3['rango'];}
echo ranguearIMG($dsdass,$sas).' '.ranguear($dsdass,$sas);}

echo'</center></div>';
anuncio1_120x240();

echo'</div>';}
function ranguear($id,$com){ global $db_prefix;
if($id=='1'){$sccb='Administrador';}
elseif($id=='2'){$sccb='Comentador';}
elseif($id=='3'){$sccb='Posteador';}
elseif($id=='4'){$sccb='Visitante';}
elseif($id=='5'){$sccb='Moderador';}
elseif(!$id){
$rs44=db_query("
SELECT permiso
FROM ({$db_prefix}comunidades)
WHERE id='$com'
LIMIT 1",__FILE__, __LINE__);
while ($row=mysql_fetch_assoc($rs44)){
if($row['permiso']=='2'){$sccb='Comentador';}
elseif($row['permiso']=='3'){$sccb='Posteador';}
elseif($row['permiso']=='4'){$sccb='Visitante';}}}
return $sccb;}

function ranguearIMG($id,$com){ global $db_prefix,$tranfer1;
if($id=='1'){$sccb='<img src="'.$tranfer1.'/comunidades/admin.png" alt="" title="Administrador" />';}
if($id=='5'){$sccb='<img src="'.$tranfer1.'/comunidades/mod.png" alt="" title="Moderador" />';}
elseif($id=='2'){$sccb='<img src="'.$tranfer1.'/comunidades/comentador.png" alt="" title="Comentador" />';}
elseif($id=='3'){$sccb='<img src="'.$tranfer1.'/comunidades/posteador.png" alt="" title="Posteador" />';}
elseif($id=='4'){$sccb='<img src="'.$tranfer1.'/comunidades/comentador.png" alt="" title="Visitante" />';}
elseif(!$id){
$rs44=db_query("
SELECT permiso
FROM ({$db_prefix}comunidades)
WHERE id='$com'
LIMIT 1",__FILE__, __LINE__);
while ($row=mysql_fetch_assoc($rs44)){
if($row['permiso']=='2'){$sccb='<img src="'.$tranfer1.'/comunidades/comentador.png" alt="" title="Comentador" />';}
elseif($row['permiso']=='3'){$sccb='<img src="'.$tranfer1.'/comunidades/posteador.png" alt="" title="Posteador" />';}
elseif($row['permiso']=='4'){$sccb='<img src="'.$tranfer1.'/comunidades/comentador.png" alt="" title="Visitante" />';}
}}
return $sccb;}

function sex($valor,$d=''){global $tranfer1;
if(!$d){
$valor = str_replace("1", "<img src=\"".$tranfer1."/Male.gif\" alt=\"\" title=\"Masculino\" border=\"0\"> Masculino", $valor);
$valor = str_replace("2", "<img src=\"".$tranfer1."/Female.gif\" alt=\"\" title=\"Femenino\" border=\"0\"> Femenino", $valor);}else{
$valor = str_replace("1", "Masculino", $valor);
$valor = str_replace("2", "Femenino", $valor);    
}
;return $valor;}


function bloqueado($id){ global $modSettings,$db_prefix,$user_info,$context,$tranfer1;
$dvasad=db_query("SELECT c.bloquear,c.bloquear_razon ,c.bloquear_por
FROM ({$db_prefix}comunidades as c) 
WHERE c.id='$id' AND c.bloquear=1 
LIMIT 1", __FILE__, __LINE__);
while($row=mysql_fetch_assoc($dvasad)){
$razon=$row['bloquear_razon'];
$bloquear_porr=$row['bloquear_por'];

if(($user_info['is_admin'] || $user_info['is_mods'])){$context['ComUeliminado']='<div class="noesta" style="margin-bottom:8px;width:922px;">Esta comunidad esta eliminada.</div>';}

elseif(!$bloquear_porr){fatal_error('<b style="color:red;">Esta comunidad esta eliminada.</b><br/><b>Raz&oacute;n:</b> '.nohtml(nohtml2($razon)).'<br/><b>Por:</b> '.$bloquear_porr);}else{fatal_error('<b style="color:red;">Esta comunidad esta eliminada.</b>');}

}

return;}













function resultados($tipo){ global $modSettings,$db_prefix,$context,$ID_MEMBER,$tranfer1;
//COMUNIDADES


if($tipo=='c'){
$busqueda=trim(decodeurl($_GET['q']));
$usuario=trim(decodeurl($_GET['autor']));
$usas=decodeurl($_GET['autor']);

hearBuscador('c','c');
$busqueda=isset($busqueda) ? $busqueda : '';
$usuario=isset($usuario) ? $usuario : '';
if($busqueda or $usuario){
if($busqueda){$bccb="c.nombre LIKE '%$busqueda%' AND ";}else{$bccb="";}
$cat=$_GET['categoria'];
if(!$cat){$cats='';}else{$cats="c.categoria='$cat' AND ";}
if(!$usuario){$ssdeeesss='';}else{
$r=db_query("
SELECT m.id_com
FROM ({$db_prefix}members as c,{$db_prefix}comunidades_miembros as m)
WHERE c.realName='$usuario' AND m.id_user=c.ID_MEMBER
ORDER BY m.id_com DESC", __FILE__, __LINE__);
while($row=mysql_fetch_assoc($r)){$ddeee[]=$row['id_com'];} 
$dd=join(',',$ddeee);
if($dd){
$ssdeeesss="c.id IN (".$dd.") AND ";}
else{$ssdeeesss="";}}

$sort=trim($_GET['orden']);
if($sort=='fecha' || $sort=='relevancia' || $sort=='puntos'){$orden=$sort;}else{$orden='fecha';}
if($orden=='fecha'){$dbor='c.id DESC';}
if($orden=='puntos'){$dbor='c.articulos DESC';}

$RegistrosAMostrar=$modSettings['search_results_per_page'];
if(isset($_GET['pag'])){$RegistrosAEmpezar=($_GET['pag']-1)*$RegistrosAMostrar;
$PagAct=$_GET['pag'];}else{$RegistrosAEmpezar=0;$PagAct=1;}

$NroRegistros=mysql_num_rows(db_query("
SELECT c.id
FROM {$db_prefix}comunidades AS c
WHERE $bccb $cats $ssdeeesss c.bloquear=0", __FILE__, __LINE__));


 $PagAnt=$PagAct-1;
 $PagSig=$PagAct+1;
 $PagUlt=$NroRegistros/$RegistrosAMostrar;
 $Res=$NroRegistros%$RegistrosAMostrar;
 if($Res>0) $PagUlt=floor($PagUlt)+1;

$result=db_query("SELECT c.UserName,c.nombre,ca.nombre AS nombCat,c.categoria,c.fecha_inicio,c.id,c.url,c.articulos,c.acceso
FROM ({$db_prefix}comunidades as c, {$db_prefix}comunidades_categorias AS ca)
WHERE $bccb $cats $ssdeeesss c.categoria=ca.url AND c.bloquear=0
ORDER BY $dbor
LIMIT $RegistrosAEmpezar, $RegistrosAMostrar", __FILE__, __LINE__);

if(!$NroRegistros){echo'<div class="noesta-am" style="width:922px;">No se encontraron resultados.</div>';}else{
    
$daasdasda=$RegistrosAEmpezar ? ($RegistrosAEmpezar+1) : '1';
$daasdasda2=$RegistrosAEmpezar ? ($RegistrosAEmpezar+50) : '50';
if($daasdasda2>$NroRegistros){$daasdasda4=$NroRegistros;}else{$daasdasda4=$daasdasda2;}
echo'<table class="linksList" style="width:922px;"><thead><tr>
					<th>&nbsp;</th>
					<th style="text-align: left;">Mostrando <strong>'.($daasdasda).' de '.($daasdasda4).'</strong> resultados de <strong>'.$NroRegistros.'</strong></th>
					<th>Fecha</th>
					<th>Temas</th>
				</tr></thead><tbody>';

while($row=mysql_fetch_assoc($result)){
$nombCat=$row['nombCat'];
$categoria=$row['categoria'];
$id=$row['id'];
$nombre=$row['nombre'];
$url=$row['url'];
$articulos=$row['articulos'];
$hiddenOptios=$row['acceso'];
$UserName=$row['UserName'];

if($usuario==$UserName){$back=' style="background-color: #F8FFCD; "';}else{$back='';}
$fecha_inicio=timeformat($row['fecha_inicio']);

echo'<tr id="div_'.$id.'"'.$back.'>
<td title="'.$nombCat.'"><img title="'.$nombCat.'" src="'.$tranfer1.'/comunidades/categorias/'.$categoria.'.png" alt="" /></td>
					<td style="text-align: left;">';
					
if($hiddenOptios=='2' && $context['user']['is_guest']){echo'<img alt="" src="'.$tranfer1.'/comunidades/registrado.png" /> ';}
echo'<a title="'.nohtml(nohtml2($nombre)).'" href="/comunidades/'.$url.'" class="titlePost">'.nohtml(nohtml2($nombre)).'</a></td>
<td title="'.$fecha_inicio.'">'.$fecha_inicio.'</td>
<td>'.$articulos.'</td>
</tr>';}
				echo'</tbody></table>';
if($PagAct>$PagUlt){echo'';}elseif($PagAct>1 || $PagAct<$PagUlt){echo'<div class="windowbgpag" style="width:700px;">';
if($PagAct>1) echo "<a href='/comunidades/buscar/&q=$enl&autor=$usuario&orden=$orden&buscador_tipo=c&categoria=$cat&pag=$PagAnt'>&#171; anterior</a>";
if($PagAct<$PagUlt)  echo "<a href='/comunidades/buscar/&q=$enl&autor=$usuario&orden=$orden&buscador_tipo=c&categoria=$cat&pag=$PagSig'>siguiente &#187;</a>";
echo'<div class="clearBoth"></div></div>';}}
}
}
//TEMAS3
/////////////////////////////////////////
else{
hearBuscador('c','t');
$busqueda=trim(decodeurl($_GET['q']));
$usuario=trim(decodeurl($_GET['autor']));
if($busqueda or $usuario){
$cat=$_GET['categoria'];
if(!$busqueda){$ssdeeesss2="";$score="a.UserName LIKE '%$usuario%'";
if($usuario){$ssdeeesss="a.UserName='$usuario'";}}
else{$ssdeeesss2="MATCH (a.titulo, a.cuerpo) AGAINST ('$busqueda')";
$score="MATCH (a.titulo, a.cuerpo) AGAINST ('$busqueda')";
if($usuario){$ssdeeesss="AND a.UserName='$usuario'";}}
if(!$cat){$cats='';}else{$cats=" AND a.categoria='$cat'";}
$sort=trim($_GET['orden']);
if($sort=='fecha' || $sort=='relevancia' || $sort=='puntos'){$orden=$sort;}else{$orden='fecha';}
if($orden=='fecha'){$dbor='a.id DESC';}
if($orden=='relevancia'){$dbor='Score DESC';}
if($orden=='puntos'){$dbor='a.calificacion DESC';}

$RegistrosAMostrar=$modSettings['search_results_per_page'];
if(isset($_GET['pag'])){$RegistrosAEmpezar=($_GET['pag']-1)*$RegistrosAMostrar;
$PagAct=$_GET['pag'];}else{$RegistrosAEmpezar=0;$PagAct=1;}

$NroRegistros=mysql_num_rows(db_query("
SELECT a.id
FROM ({$db_prefix}comunidades_articulos AS a,{$db_prefix}comunidades AS co)
WHERE $ssdeeesss2 $cats $ssdeeesss AND a.id_com=co.id AND co.bloquear=0 AND a.eliminado=0", __FILE__, __LINE__));

 $PagAnt=$PagAct-1;
 $PagSig=$PagAct+1;
 $PagUlt=$NroRegistros/$RegistrosAMostrar;
 $Res=$NroRegistros%$RegistrosAMostrar;
 if($Res>0) $PagUlt=floor($PagUlt)+1;

$result=db_query("SELECT $score AS Score, a.titulo,a.categoria, c.nombre,a.creado,a.calificacion,a.id,co.url
FROM ({$db_prefix}comunidades_articulos AS a,{$db_prefix}comunidades_categorias AS c,{$db_prefix}comunidades AS co)
WHERE $ssdeeesss2 $cats $ssdeeesss AND a.categoria=c.url AND a.id_com=co.id AND co.bloquear=0 AND a.eliminado=0
ORDER BY $dbor
LIMIT $RegistrosAEmpezar, $RegistrosAMostrar", __FILE__, __LINE__);

if(!$NroRegistros){echo'<div class="noesta-am" style="width:922px;">No se encontraron resultados.</div>';}else{
    
$daasdasda=$RegistrosAEmpezar ? ($RegistrosAEmpezar+1) : '1';
$daasdasda2=$RegistrosAEmpezar ? ($RegistrosAEmpezar+50) : '50';
if($daasdasda2>$NroRegistros){$daasdasda4=$NroRegistros;}else{$daasdasda4=$daasdasda2;}
echo'<table class="linksList" style="width:922px;"><thead><tr>
					<th>&nbsp;</th>
					<th style="text-align: left;">Mostrando <strong>'.($daasdasda).' de '.($daasdasda4).'</strong> resultados de <strong>'.$NroRegistros.'</strong></th>
					<th>Fecha</th>
					<th>Calificaci&oacute;n</th>
					<th>Relevancia</th>
				</tr></thead><tbody>';
while($row=mysql_fetch_assoc($result)){
$nombCat=$row['nombre'];
$categoria=$row['categoria'];
$id=$row['id'];
$nombre=$row['titulo'];
$url=$row['url'];
$relevancia=$row['Score'];
$articulos=$row['calificacion'];
$fecha_inicio=timeformat($row['creado']);

echo'<tr id="div_'.$id.'"><td title="'.$nombCat.'"><img title="'.$nombCat.'" src="'.$tranfer1.'/comunidades/categorias/'.$categoria.'.png" alt="" /></td><td style="text-align: left;">';

echo'<a title="'.nohtml(nohtml2($nombre)).'" href="/comunidades/'.$url.'/'.$id.'/'.urls($nombre).'.html" class="titlePost">'.nohtml(nohtml2($nombre)).'</a></td>
<td title="'.$fecha_inicio.'">'.$fecha_inicio.'</td>
<td style="color:green;">'.$articulos.'</td>
<td title="'.$relevancia.'%">'.relevancia($relevancia).'</td></tr>';}

echo'</tbody></table>';

if($PagAct>$PagUlt){echo'';}elseif($PagAct>1 || $PagAct<$PagUlt){echo'<div class="windowbgpag" style="width:700px;">';
if($PagAct>1) echo "<a href='/comunidades/buscar/&q=$enl&autor=$usuario&orden=$orden&buscador_tipo=t&categoria=$cat&pag=$PagAnt'>&#171; anterior</a>";
if($PagAct<$PagUlt)  echo "<a href='/comunidades/buscar/&q=$enl&autor=$usuario&orden=$orden&buscador_tipo=t&categoria=$cat&pag=$PagSig'>siguiente &#187;</a>";
echo'<div class="clearBoth"></div></div>';}}}}} ?>