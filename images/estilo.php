<?php
if (extension_loaded('zlib')) {
  ob_start('ob_gzhandler');
}

header('content-type: text/css; charset: UTF-8');
header('cache-control: must-revalidate');

$offset = 60 * 60;
$expire = 'expires: ' . gmdate('D, d M Y H:i:s', time() + $offset) . ' GMT';

header($expire);
ob_start('compress');

function compress($buffer) {
  $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
  $buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer);

  return $buffer;
}

require_once('js/Entera.css');
require_once('js/Comunidad.css');

if (extension_loaded('zlib')) {
  ob_end_flush();
}

?>  