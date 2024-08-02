<?php
require_once(dirname(__FILE__) . '/config-seg-cw1983.php');

$q = isset($_GET['q']) ? $_GET['q'] : '';
$q = str_replace('%', '{por}', str_replace('+', '{mas}', $q));
$t = str_replace(' ', '+', $q);
$sort = $_GET['orden'];
$cat = $_GET['categoria'];

header('Location: ' . $boardurl . '/tags/buscar/&q=' . $t . '&orden=' . $sort . '&categoria=' . $cat . '&nn=t');

?>