<?php
$q=str_replace('%','{por}',str_replace('+','{mas}',$_GET['q']));
$t=str_replace(' ','+',$q);
$sort=$_GET['orden'];
$cat=$_GET['categoria']; 
Header("Location: /tags/buscar/&q={$t}&orden={$sort}&categoria={$cat}&nn=t"); ?>