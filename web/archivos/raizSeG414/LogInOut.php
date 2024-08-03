<?php
// Página de Rodrigo Zaupa (rigo@casitaweb.net)
if (!defined('CasitaWeb!-PorRigo')) {
  die(base64_decode('d3d3LmNhc2l0YXdlYi5uZXQgLSByaWdv'));
}

function Login() {
  global $txt, $context;

  if (WIRELESS) {
    $context['sub_template'] = WIRELESS_PROTOCOL . '_login';
  } else {
    loadLanguage('Login');
    loadTemplate('Login');
    $context['sub_template'] = 'login';
  }

  $context['page_title'] = $txt[34];
  $context['default_username'] = &$_GET['u'];
  $context['default_password'] = '';
  $context['never_expire'] = true;

  if (isset($_SESSION['old_url']) && preg_match('~(board|topic)[=,]~', $_SESSION['old_url']) != 0) {
    $_SESSION['login_url'] = $_SESSION['old_url'];
  } else {
    unset($_SESSION['login_url']);
  }
}

function Login2() {}
function Logout($internal = false) {}

function md5_hmac($data, $key) {
  $key = str_pad(strlen($key) <= 64 ? $key : pack('H*', md5($key)), 64, chr(0x00));

  return md5(($key ^ str_repeat(chr(0x5C), 64)) . pack('H*', md5(($key ^ str_repeat(chr(0x36), 64)) . $data)));
}

?>