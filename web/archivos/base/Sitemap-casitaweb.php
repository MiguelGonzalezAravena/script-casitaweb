<?php
function template_Begin(){}
function template_Boards(){global $context,$db_prefix, $scripturl, $txt, $modSettings, $settings,$tranfer1;
$request = db_query("
SELECT b.ID_BOARD,b.description, b.name, b.childLevel
FROM {$db_prefix}boards AS b", __FILE__, __LINE__);
$context['boards'] = array();
while ($row = mysqli_fetch_assoc($request))
$context['boards'][] = array(
'id' => $row['ID_BOARD'],
'name' => $row['name'],
'description' => $row['description'],
'child_level' => $row['childLevel']);
mysqli_free_result($request);
echo'<div class="box_300" style="float:left;margin-right:8px;" align="left">
<div class="box_title" style="width: 300px;"><div class="box_txt box_300-34">General</div>
<div class="box_rss"><img alt="" src="'.$tranfer1.'/blank.gif" style="width:16px;height:16px;" border="0" /></div></div><div class="windowbg" style="padding:4px;width:292px;"><span class="size11"><a href="http://ayuda.casitaweb.net/" title="Ayuda">Ayuda</a><br /><a href="/buscador/" title="Buscador">Buscador</a><br /><a href="/chat/" title="Chat">Chat</a><br /><a href="/contactanos/" title="Contacto">Contacto</a><br /><a href="/enlazanos/" title="Enlazanos">Enlazanos</a><br /><a href="/protocolo/" title="Protocolo">Protocolo</a><br /><a href="/widget/" title="Widget">Widget</a><br /><a href="/terminos-y-condiciones/" title="T&eacute;rminos y condiciones">T&eacute;rminos y condiciones</a><br /><a href="/tops/" title="Top">Top</a>

</span><br /></div></div>

<div class="box_300" style="float: left; margin-right:8px;" align="left">
<div class="box_title" style="width: 300px;"><div class="box_txt box_300-34">Categor&iacute;as</div>
<div class="box_rss"><img src="'.$tranfer1.'/blank.gif" style="width: 16px; height: 16px;" border="0" alt="" /></div></div><div class="windowbg" style="padding: 4px; width: 292px;"><span class="size11">';
foreach ($context['boards'] as $board){
echo'<a href="/categoria/'.$board['description'].'" title="'.$board['name'].'">'.$board['name'].'</a><br />';}
echo'</span></div></div>

<div class="box_300" style="float: left;" align="left">
<div class="box_title" style="width: 300px;"><div class="box_txt box_300-34">RSS</div>
<div class="box_rss"><img alt="" src="'.$tranfer1.'/blank.gif" style="width: 16px;height:16px;" border="0" /></div></div><div class="windowbg" style="padding: 4px; width: 292px;"><span class="size11">
<a href="/rss/ultimos-post/" title="&Uacute;ltimos posts">&Uacute;ltimos posts</a><br />
<a href="/rss/ultimos-comment/" title="&Uacute;ltimos comentarios">&Uacute;ltimos comentarios</a>

</span><br /></div></div>';


echo'<div style="clear: both;"></div><div style="display:none;">';
if(isset($context['sitemap']['board']))
$switch = false;
foreach($context['sitemap']['board'] as $board){if ($board['level'] == 0 && $switch){$switch = false;}
echo'<a title="',$board['name'],'" href="/categoria/', $board['id'], '" title="',$board['name'],'"><span title="',$board['name'],'">',$board['name'],'</span></a>';}
echo'</div>';}
function template_Topics(){} function template_End(){} function template_XMLDisplay(){} function getXMLLink(){} ?>