<?php
$t=str_replace('x','',$_GET['tamanio']); $tamanio=(int) $t;

echo'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml" lang="es" xml:lang="es"><head><title>Ads</title><style>body{margin:0px;padding:0px;background:none;border:none;line-height:0px;}</style></head><body>';
if($tamanio=='300250'){echo'<script type="text/javascript"><!--
smowtion_size = "300x250";
smowtion_section = "528437";
smowtion_iframe = 1;
//-->
</script>';}
elseif($tamanio=='120600'){echo'<script type="text/javascript"><!--
smowtion_size = "120x600";
smowtion_section = "528437";
smowtion_iframe = 1;
//-->
</script>';}
elseif($tamanio=='46860'){echo'<script type="text/javascript"><!--
smowtion_size = "468x60";
smowtion_section = "528437";
smowtion_iframe = 1;
//-->
</script>';}
elseif($tamanio=='72890'){echo'<script type="text/javascript"><!--
smowtion_size = "728x90";
smowtion_section = "528437";
smowtion_iframe = 1;
//-->
</script>';}
elseif($tamanio=='23460'){echo'<script type="text/javascript"><!--
smowtion_size = "234x60";
smowtion_section = "528437";
smowtion_iframe = 1;
//--></script>';}
elseif($tamanio=='160600'){echo'<script type="text/javascript"><!--
smowtion_size = "120x600";
smowtion_section = "528437";
smowtion_iframe = 1;
//-->
</script>';}  
echo'<noscript>Tu navegador no permite visualizar bien esta parte.</noscript><script type="text/javascript" src="http://ads.smowtion.com/ad.js"></script></body></html>'; ?>