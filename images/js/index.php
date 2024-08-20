<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/config-seg-cw1983.php');

$_GET['a'] = isset($_GET['a']) ? (int) $_GET['a'] : '';

header('Content-type: application/x-javascript; charset: UTF-8');
header('Cache-Control: must-revalidate');
$offset = 60 * 60;
$ExpStr = 'Expires: ' . gmdate("D, d M Y H:i:s", time() + $offset) . ' GMT';
header($ExpStr);

function replaceUrl($fileContent) {
  global $boardurl;

  return str_replace('{{boardUrl}}', $boardurl, $fileContent);
}

if ($_GET['a'] == 3) {
  echo "\r\r" . replaceUrl(file_get_contents('colores.js'));
} else {
  echo 'if (top.location != location)top.location = self.location;'."\n" .
    file_get_contents('http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js') .
    "\r\r" .
    file_get_contents('editor.js') .
    "\r\r" .
    replaceUrl(file_get_contents('boxy.js')) .
    "\r\r" .
    replaceUrl(file_get_contents('general.js')) .
    "\r\r";
}

die();

?>