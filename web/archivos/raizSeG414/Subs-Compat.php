<?php
// Página de Rodrigo Zaupa (rigo@casitaweb.net)
if (!defined('CasitaWeb!-PorRigo')) {
  die(base64_decode('d3d3LmNhc2l0YXdlYi5uZXQgLSByaWdv'));
}

function sha1_casitaweb($str) {
  $nblk = (strlen($str) + 8 >> 6) + 1;
  $blks = array_pad(array(), $nblk * 16, 0);

  for ($i = 0; $i < strlen($str); $i++) {
    $blks[$i >> 2] |= ord($str[$i]) << (24 - ($i % 4) * 8);
  }

  $blks[$i >> 2] |= 0x80 << (24 - ($i % 4) * 8);

  return sha1_core($blks, strlen($str) * 8);
}

function sha1_core($x, $len) {
  @$x[$len >> 5] |= 0x80 << (24 - $len % 32);
  $x[(($len + 64 >> 9) << 4) + 15] = $len;

  $w = array();
  $a = 1732584193;
  $b = -271733879;
  $c = -1732584194;
  $d = 271733878;
  $e = -1009589776;

  for ($i = 0, $n = count($x); $i < $n; $i += 16) {
    $olda = $a;
    $oldb = $b;
    $oldc = $c;
    $oldd = $d;
    $olde = $e;

    for ($j = 0; $j < 80; $j++) {
      if ($j < 16) {
        $w[$j] = isset($x[$i + $j]) ? $x[$i + $j] : 0;
      } else {
        $w[$j] = sha1_rol($w[$j - 3] ^ $w[$j - 8] ^ $w[$j - 14] ^ $w[$j - 16], 1);
      }

      $t = sha1_rol($a, 5) + sha1_ft($j, $b, $c, $d) + $e + $w[$j] + sha1_kt($j);
      $e = $d;
      $d = $c;
      $c = sha1_rol($b, 30);
      $b = $a;
      $a = $t;
    }

    $a += $olda;
    $b += $oldb;
    $c += $oldc;
    $d += $oldd;
    $e += $olde;
  }

  return sprintf('%08x%08x%08x%08x%08x', $a, $b, $c, $d, $e);
}

function sha1_ft($t, $b, $c, $d) {
  if ($t < 20) {
    return ($b & $c) | ((~$b) & $d);
  }

  if ($t < 40) {
    return $b ^ $c ^ $d;
  }

  if ($t < 60) {
    return ($b & $c) | ($b & $d) | ($c & $d);
  }

  return $b ^ $c ^ $d;
}

function sha1_kt($t) {
  return $t < 20 ? 1518500249 : ($t < 40 ? 1859775393 : ($t < 60 ? -1894007588 : -899497514));
}

function sha1_rol($num, $cnt) {
  if ($num & 0x80000000) {
    $a = ($num >> 1 & 0x7fffffff) >> (31 - $cnt);
  } else {
    $a = $num >> (32 - $cnt);
  }

  return ($num << $cnt) | $a;
}

?>