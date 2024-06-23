<?php
$t = str_replace('x', '', $_GET['tamanio']);
$tamanio = (int) $t;

echo '
  <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml" lang="es" xml:lang="es">
      <head>
        <title>Ads</title>
        <style>
          body {
            margin: 0px;
            padding: 0px;
            background: none;
            border: none;
            line-height: 0px;
          }
          </style>
        </head>
        <body>';

if ($tamanio == '300250') {
  echo '300x250';
} else if ($tamanio == '120600') {
  echo '120x600';
} else if ($tamanio == '46860') {
  echo '468x60';
} else if ($tamanio == '72890') {
  echo '728x90';
} else if ($tamanio == '23460') {
  echo '234x60';
} else if ($tamanio == '160600') {
  echo '160x600';
}

echo '
      <noscript>Tu navegador no permite visualizar bien esta parte.</noscript>
    </body>
  </html>';

?>