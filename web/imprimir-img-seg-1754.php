<?php require("cw-conexion-seg-0011.php");
global $context,$db_prefix,$modSettings,$user_info;
$userdb=(int)$_GET['id487315'];
$valid=db_query("SELECT title,filename FROM {$db_prefix}gallery_pic WHERE ID_PICTURE='$userdb' LIMIT 1",__FILE__, __LINE__);
while ($udb = mysql_fetch_assoc($valid)){$title=$udb['title'];$filename=$udb['filename'];}
mysql_free_result($valid);
echo'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html version="XHTML+RDFa 1.0"  xmlns="http://www.w3.org/1999/xhtml" lang="es" xml:lang="es" >
<!--2009 casitaweb.net/por rigo-->
<head profile="http://purl.org/NET/erdf/profile">
  <link rel="schema.dc" href="http://purl.org/dc/elements/1.1/" />
  <link rel="schema.foaf" href="http://xmlns.com/foaf/0.1/" />
<meta name="verify-v1" content="HTXLHK/cBp/LYfs9+fLwj1UOxfq+/iFsv1DZjB6zWZU=" />
<meta http-equiv="Content-Type" content="text/html; charset='.$context['character_set'].'" />
<meta name="robots" content="all" />
<meta name="keywords" content="casitaweb, casita, web, rigo, cladj, caladj, rodri, zaupa, zaupita, nicolas, nicolaszaupita, elblogderigo, lawebderigo, elforoderigo, linksharing, enlaces, juegos, musica, links, noticias, imagenes, videos, animaciones, arte, tecnologia, celulares, argentina, comunidad, cw" />
<link rel="search" type="application/opensearchdescription+xml" title="CasitaWeb!" href="/cw-buscador-web.xml" />
<link rel="icon" href="/favicon.ico" type="image/x-icon" />
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
<link rel="apple-touch-icon" href="/web/imagenes/apple-touch-icon.png" />
<link rel="alternate" type="application/atom+xml" title="&Uacute;ltimos posts" href="http://casitaweb.net/rss/ultimos-post" />
<link rel="alternate" type="application/atom+xml" title="&Uacute;ltimos comentarios" href="http://casitaweb.net/rss/ultimos-comment" />
<title>Imprimir Imagen - '.$title.'</title>
<style type="text/css">
body{color:black;background-color:white;align:center;}
body, td, .normaltext{font-family: Arial, helvetica, serif;font-size:xx-small;}
*, a:link, a:visited, a:hover, a:active{color:black!important;}
table{empty-cells: show;}
.code{font-size: xxx-small;font-family: monospace;border: 1px solid black;margin: 1px;padding: 1px;}
.quote{font-size: xxx-small;border: 1px solid black;margin: 1px;padding: 1px;}
.smalltext, .quoteheader, .codeheader{font-size: xxx-small;}
hr{height: 1px;border: 0;color: black;background-color: black;}</style></head>';
echo'<body onload="javascript:window.print();"><center><h1 class="largetext">'.$title.'</h1>http://casitaweb.net/imagenes/ver/'.$userdb.'<br /><b></b><hr /><img alt="" onload="if(this.width > 750) {this.width=750}" src="'.$filename.'" title="'.$title.'" /></center>';
echo'<center><hr />&copy; ';año();echo' casitaweb.net</center></body></html>';
?>