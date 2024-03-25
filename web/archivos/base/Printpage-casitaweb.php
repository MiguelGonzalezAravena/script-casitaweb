<?php
function template_print_above(){global $context, $settings, $options, $txt;
echo'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html version="XHTML+RDFa 1.0"  xmlns="http://www.w3.org/1999/xhtml" lang="es" xml:lang="es" ><!--2009 casitaweb.net/por rigo--><head profile="http://purl.org/NET/erdf/profile"> <link rel="schema.dc" href="http://purl.org/dc/elements/1.1/" />  <link rel="schema.foaf" href="http://xmlns.com/foaf/0.1/" /><meta name="verify-v1" content="HTXLHK/cBp/LYfs9+fLwj1UOxfq+/iFsv1DZjB6zWZU=" /><meta http-equiv="Content-Type" content="text/html; charset='.$context['character_set'].'" /><meta name="description" content="'.getMetaDescription($context['body']).''.$context['subject'].'" /><meta name="robots" content="all" /><meta name="keywords" content="'.getMetaKeywords($context['body']).',casitaweb, casita, web, rigo, cladj, caladj, rodri, zaupa, zaupita, nicolas, nicolaszaupita, elblogderigo, lawebderigo, elforoderigo, linksharing, enlaces, juegos, musica, links, noticias, imagenes, videos, animaciones, arte, tecnologia, celulares, argentina, comunidad, cw" /><link rel="search" type="application/opensearchdescription+xml" title="CasitaWeb!" href="/cw-buscador-web.xml" /><link rel="icon" href="/favicon.ico" type="image/x-icon" /><link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" /><link rel="apple-touch-icon" href="/web/imagenes/apple-touch-icon.png" /><link rel="alternate" type="application/atom+xml" title="&Uacute;ltimos posts" href="/rss/ultimos-post" /><link rel="alternate" type="application/atom+xml" title="&Uacute;ltimos comentarios" href="/rss/ultimos-comment" /><title>'.$context['subject'].' - '.$context['forum_name'].' \ POST Sin estilo</title>

<style type="text/css">
body{color: black;background-color:white;align:center;}
body,td,.normaltext{font-family:Arial, helvetica, serif;font-size: 12px;align:center;}
*, a:link, a:visited, a:hover, a:active{color: black !important;}
table{empty-cells: show;}
.code{font-size: 12px;font-family: monospace;border: 1px solid black;margin: 1px;padding: 1px;}
.quote{font-size: 12px;border: 1px solid black;margin: 1px;padding: 1px;}
.noesta-am{
	border-top: 2px solid #F5EAA4;
	border-bottom: 2px solid #F5EAA4;
	padding-top:10px;
	padding-bottom:10px;
	margin:0px;
	width:100%;
	color:#495461;
	text-align:center;
	background-color:#FEF7CD;
	font-size:11px;
	font-weight:bold;}

.smalltext, .quoteheader, .codeheader{font-size: 12px;}
hr{height: 1px;border: 0;color: black;background-color: black;}</style>';
echo'</head><body>';
if($context['hiddenOption']=='1'){die('Este post es privado, para verlo debes autentificarte.');}else{
echo'<div class="noesta-am" style="margin-bottom:10px;"><a href="http://www.casitaweb.net/post/'.$context['id'].'/'.$context['description'].'/'.urls($context['subject']).'.html">VER VERSION ORIGINAL</a></div>
<center><h1 class="largetext">'.$context['forum_name'].' - '.$context['subject'].'</h1>
http://www.casitaweb.net/post/'.$context['id'].'/'.$context['description'].'/'.urls($context['subject']).'.html</h2></center>
<div align="center"><div style="width:80%;"><center>
<br />
					<hr size="2" width="100%" />
					', $txt[196], ': <b>'.$context['subject'].'</b><br />
					', $txt[197], ': <b>'.$context['member'].'</b> ', $txt[176], ' <b>'.$context['post_time'].'</b>
					<hr /><div style="margin:0px 5ex;">'.$context['body'].'</div><br />
';
if($context['haycomentssss']){echo'<hr /><b>COMENTARIOS ('.$context['haycomentssss'].'):</b>';
//comentarios
foreach ($context['coment'] as $coment){echo'<div style="font-size:12px;margin-bottom:4px;">
<div style="background:#EEE;padding:2px;">Comentario por: '.$coment['realName'].' '.$coment['time'].' </div><div style="padding:2px;">'.$coment['comentario'].'</div></div>';}}

}echo'</center></div></div>
<div class="noesta-am"><a href="/registrarse/">REGISTRATE!!!, ES GRATIS</a></div>

<div align="center"><div style="width:80%;"><center><hr /><font size="1">&copy; ';año();echo' casitaweb.net</font></center></div></div><script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script><script type="text/javascript">try{_uacct="UA-2833411-5";urchinTracker();} catch(err) {}</script></body></html>';}

function template_main(){} function template_print_below(){} ?>