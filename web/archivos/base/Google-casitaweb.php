<?php
function template_intro(){
global $context,$sourcedir,$ID_MEMBER,$tranfer1;
require($sourcedir.'/Hear-Buscador.php');
hearBuscador('0','g');

echo'<div style="width: 922px;"><center>';
if($_GET['cx'] != '015978274333592990658:r0qy7erzrbw'){echo'<div class="noesta-am">No se encontraron resultados.</div>';}else{
if($_GET['ie'] != 'UTF-8'){echo'<div class="noesta-am">No se encontraron resultados.</div>';}else{
if($_GET['sa'] != 'Buscar'){echo'<div class="noesta-am">No se encontraron resultados.</div>';}else{
if(empty($_GET['q'])){echo'<div class="noesta-am">No se encontraron resultados.</div>';}else{

echo'<div id="resultados"></div>
<script type="text/javascript">
  var googleSearchIframeName = "resultados";
  var googleSearchFormName = "cse-search-box";
  var googleSearchFrameWidth = 911;
  var googleSearchDomain = "www.google.com";
  var googleSearchPath = "/cse";
</script>
<script type="text/javascript" src="http://www.google.com/afsonline/show_afs_search.js"></script>';}}}}
echo'</center></div>';} ?>