<?php 
require_once(dirname(dirname(__FILE__)) . '/funcion-seg-1547.php');

$context['error-web-page'] = '1';
$context['ajax'] = isset($_GET['ajaxboxy']) ? ($_GET['ajaxboxy'] == 'si' ? '1' : '0') : '0';

?>