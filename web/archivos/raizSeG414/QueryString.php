<?php
// Página de Rodrigo Zaupa (rigo@casitaweb.net)
if (!defined('CasitaWeb!-PorRigo')) {
  die(base64_decode('d3d3LmNhc2l0YXdlYi5uZXQgLSByaWdv'));
}

function cleanRequest() {
  global $board, $topic, $boardurl, $scripturl, $modSettings;

  $scripturl = $boardurl . '/index.php';

  unset($GLOBALS['HTTP_POST_VARS'], $GLOBALS['HTTP_POST_VARS']);
  unset($GLOBALS['HTTP_POST_FILES'], $GLOBALS['HTTP_POST_FILES']);
        
  if (isset($_REQUEST['GLOBALS']) || isset($_COOKIE['GLOBALS']))
    die('Variable Invalida.');
  foreach (array_merge(array_keys($_POST), array_keys($_GET), array_keys($_FILES)) as $key)
    if (is_numeric($key))die('Variable Invalida.');
                
  foreach ($_COOKIE as $key => $value)
    if (is_numeric($key))
      unset($_COOKIE[$key]);
  if (!isset($_SERVER['QUERY_STRING']))
    $_SERVER['QUERY_STRING'] = getenv('QUERY_STRING');
  if ((strpos(@ini_get('arg_separator.input'), ';') === false || @version_compare(PHP_VERSION, '4.2.0') == -1) && !empty($_SERVER['QUERY_STRING'])) {
    $_GET = array();
    $_SERVER['QUERY_STRING'] = urldecode(substr($_SERVER['QUERY_STRING'], 0, 5) == 'url=/' ? $_SERVER['REDIRECT_QUERY_STRING'] : $_SERVER['QUERY_STRING']);

    parse_str(preg_replace('/&(\w+)(?=&|$)/', '&$1=', strtr($_SERVER['QUERY_STRING'], array(';?' => '&', ';' => '&'))), $_GET);
  }
  elseif (strpos(@ini_get('arg_separator.input'), ';') !== false)
  {
    $_GET = urldecode__recursive($_GET);

    if (empty($modSettings['integrate_magic_quotes'])) {
      $_GET = stripslashes__recursive($_GET);
    }

    foreach ($_GET as $k => $v)
    {
      if (is_string($v) && strpos($k, ';') !== false)
      {
        $temp = explode(';', $v);
        $_GET[$k] = $temp[0];

        for ($i = 1, $n = count($temp); $i < $n; $i++)
        {
          @list ($key, $val) = @explode('=', $temp[$i], 2);
          if (!isset($_GET[$key]))
            $_GET[$key] = $val;
        }
      }

      // This helps a lot with integration!
      if (strpos($k, '?') === 0)
      {
        $_GET[substr($k, 1)] = $v;
        unset($_GET[$k]);
      }
    }
  }

  // There's no query string, but there is a URL... try to get the data from there.
  if (!empty($_SERVER['REQUEST_URI']))
  {
    // Remove the .html, assuming there is one.
    if (substr($_SERVER['REQUEST_URI'], strrpos($_SERVER['REQUEST_URI'], '.'), 4) == '.htm')
      $request = substr($_SERVER['REQUEST_URI'], 0, strrpos($_SERVER['REQUEST_URI'], '.'));
    else
      $request = $_SERVER['REQUEST_URI'];

    // Replace 'index.php/a,b,c/d/e,f' with 'a=b,c&d=&e=f' and parse it into $_GET.
    parse_str(substr(preg_replace('/&(\w+)(?=&|$)/', '&$1=', strtr(preg_replace('~/([^,/]+),~', '/$1=', substr($request, strpos($request, basename($scripturl)) + strlen(basename($scripturl)))), '/', '&')), 1), $temp);
    $_GET += $temp;
  }

  // Add entities to GET.  This is kinda like the slashes on everything else.
  $_GET = addslashes__recursive(htmlspecialchars__recursive($_GET));

  // Clean up after annoying ini settings.  (magic_quotes_gpc might be off...)
  if (empty($modSettings['integrate_magic_quotes']))
  {
    // E(G)PCS: ENV, (GET was already done), POST, COOKIE.
    $_ENV = addslashes__recursive($_ENV);
    $_POST = addslashes__recursive($_POST);
    $_COOKIE = addslashes__recursive($_COOKIE);

    // FILES work like this: k -> name -> array.  So be careful.
    foreach ($_FILES as $k => $dummy)
      $_FILES[$k]['name'] = addslashes__recursive($_FILES[$k]['name']);
  }

  $_SERVER = addslashes__recursive($_SERVER);
  $_REQUEST = $_POST + $_GET;


  if (empty($_REQUEST['start']) || $_REQUEST['start'] < 0)
    $_REQUEST['start'] = 0;


  if (isset($_REQUEST['action']))
    $_REQUEST['action'] = (string) $_REQUEST['action'];
  if (isset($_GET['action']))
    $_GET['action'] = (string) $_GET['action'];


  $_SERVER['BAN_CHECK_IP'] = isset($_SERVER['REMOTE_ADDR']) && preg_match('~^((([1]?\d)?\d|2[0-4]\d|25[0-5])\.){3}(([1]?\d)?\d|2[0-4]\d|25[0-5])$~', $_SERVER['REMOTE_ADDR']) === 1 ? $_SERVER['REMOTE_ADDR'] : 'unknown';

  // Find the user's IP address. (but don't let it give you 'unknown'!)
  if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_CLIENT_IP']) && (preg_match('~^((0|10|172\.16|192\.168|255|127\.0)\.|unknown)~', $_SERVER['HTTP_CLIENT_IP']) == 0 || preg_match('~^((0|10|172\.16|192\.168|255|127\.0)\.|unknown)~', $_SERVER['REMOTE_ADDR']) != 0))
  {
    // We have both forwarded for AND client IP... check the first forwarded for as the block - only switch if it's better that way.
    if (strtok($_SERVER['HTTP_X_FORWARDED_FOR'], '.') != strtok($_SERVER['HTTP_CLIENT_IP'], '.') && '.' . strtok($_SERVER['HTTP_X_FORWARDED_FOR'], '.') == strrchr($_SERVER['HTTP_CLIENT_IP'], '.') && (preg_match('~^((0|10|172\.16|192\.168|255|127\.0)\.|unknown)~', $_SERVER['HTTP_X_FORWARDED_FOR']) == 0 || preg_match('~^((0|10|172\.16|192\.168|255|127\.0)\.|unknown)~', $_SERVER['REMOTE_ADDR']) != 0))
      $_SERVER['REMOTE_ADDR'] = implode('.', array_reverse(explode('.', $_SERVER['HTTP_CLIENT_IP'])));
    else
      $_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_CLIENT_IP'];
  }
  if (!empty($_SERVER['HTTP_CLIENT_IP']) && (preg_match('~^((0|10|172\.16|192\.168|255|127\.0)\.|unknown)~', $_SERVER['HTTP_CLIENT_IP']) == 0 || preg_match('~^((0|10|172\.16|192\.168|255|127\.0)\.|unknown)~', $_SERVER['REMOTE_ADDR']) != 0))
  {
    // Since they are in different blocks, it's probably reversed.
    if (strtok($_SERVER['REMOTE_ADDR'], '.') != strtok($_SERVER['HTTP_CLIENT_IP'], '.'))
      $_SERVER['REMOTE_ADDR'] = implode('.', array_reverse(explode('.', $_SERVER['HTTP_CLIENT_IP'])));
    else
      $_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_CLIENT_IP'];
  }
  elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
  {
    if (strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',') !== false)
    {
      $ips = array_reverse(explode(', ', $_SERVER['HTTP_X_FORWARDED_FOR']));

      // Go through each IP...
      foreach ($ips as $i => $ip)
      {
        // Make sure it's in a valid range...
        if (preg_match('~^((0|10|172\.16|192\.168|255|127\.0)\.|unknown)~', $ip) != 0 && preg_match('~^((0|10|172\.16|192\.168|255|127\.0)\.|unknown)~', $_SERVER['REMOTE_ADDR']) == 0)
          continue;

        // Otherwise, we've got an IP!
        $_SERVER['REMOTE_ADDR'] = trim($ip);
        break;
      }
    }
    // Otherwise just use the only one.
    elseif (preg_match('~^((0|10|172\.16|192\.168|255|127\.0)\.|unknown)~', $_SERVER['HTTP_X_FORWARDED_FOR']) == 0 || preg_match('~^((0|10|172\.16|192\.168|255|127\.0)\.|unknown)~', $_SERVER['REMOTE_ADDR']) != 0)
      $_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_X_FORWARDED_FOR'];
  }
  elseif (!isset($_SERVER['REMOTE_ADDR']))
  {
    $_SERVER['REMOTE_ADDR'] = '';
    // A new magic variable to indicate we think this is command line.
    $_SERVER['is_cli'] = true;
  }

  // Make sure we know the URL of the current request.
  if (empty($_SERVER['REQUEST_URI']))
    $_SERVER['REQUEST_URL'] = $scripturl . (!empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '');
  elseif (preg_match('~^([^/]+//[^/]+)~', $scripturl, $match) == 1)
    $_SERVER['REQUEST_URL'] = $match[1] . $_SERVER['REQUEST_URI'];
  else
    $_SERVER['REQUEST_URL'] = $_SERVER['REQUEST_URI'];

  // And make sure HTTP_USER_AGENT is set.
  $_SERVER['HTTP_USER_AGENT'] = isset($_SERVER['HTTP_USER_AGENT']) ? htmlspecialchars(stripslashes($_SERVER['HTTP_USER_AGENT']), ENT_QUOTES) : '';

  // Some final checking.
  if (preg_match('~^((([1]?\d)?\d|2[0-4]\d|25[0-5])\.){3}(([1]?\d)?\d|2[0-4]\d|25[0-5])$~', $_SERVER['REMOTE_ADDR']) === 0)
    $_SERVER['REMOTE_ADDR'] = '';
}
function addslashes__recursive($var, $level = 0)
{
  if (!is_array($var))
    return addslashes($var);

  // Reindex the array with slashes.
  $new_var = array();

  // Add slashes to every element, even the indexes!
  foreach ($var as $k => $v)
    $new_var[addslashes($k)] = $level > 25 ? null : addslashes__recursive($v, $level + 1);

  return $new_var;
}

// Adds html entities to the array/variable.  Uses two underscores to guard against overloading.
function htmlspecialchars__recursive($var, $level = 0)
{
  /*
  global $func;

  return isset($func) ? $func['htmlspecialchars']($var, ENT_QUOTES) : htmlspecialchars($var, ENT_QUOTES);

  // Add the htmlspecialchars to every element.
  foreach ($var as $k => $v)
    $var[$k] = $level > 25 ? null : htmlspecialchars__recursive($v, $level + 1);
    */

  return $var;
}

// Removes url stuff from the array/variable.  Uses two underscores to guard against overloading.
function urldecode__recursive($var, $level = 0)
{
  if (!is_array($var))
    return urldecode($var);

  // Reindex the array...
  $new_var = array();

  // Add the htmlspecialchars to every element.
  foreach ($var as $k => $v)
    $new_var[urldecode($k)] = $level > 25 ? null : urldecode__recursive($v, $level + 1);

  return $new_var;
}
// Strips the slashes off any array or variable.  Two underscores for the normal reason.
function stripslashes__recursive($var, $level = 0)
{
  if (!is_array($var))
    return stripslashes($var);

  // Reindex the array without slashes, this time.
  $new_var = array();

  // Strip the slashes from every element.
  foreach ($var as $k => $v)
    $var[stripslashes($k)] = $level > 25 ? null : stripslashes__recursive($v, $level + 1);

  return $var;
}

// Trim a string including the HTML space, character 160.
function htmltrim__recursive($var, $level = 0)
{
  global $func;

  // Remove spaces (32), tabs (9), returns (13, 10, and 11), nulls (0), and hard spaces. (160)
  if (!is_array($var))
    return isset($func) ? $func['htmltrim']($var) : trim($var, " \t\n\r\x0B\0\xA0");

  // Go through all the elements and remove the whitespace.
  foreach ($var as $k => $v)
    $new_var[$k] = $level > 25 ? null : htmltrim__recursive($v, $level + 1);

  return $new_var;
}

// !!!
function validate_unicode__recursive($var)
{
  if (is_array($var))
    return array_map('validate_unicode__recursive', $var);

  $cleanup = array_merge(range(0, 8), range(11, 12), range(14, 31));

  // Assuming unicode for now - won't really hurt if we're wrong.
  for ($i = 0; $i < strlen($var); $i++)
  {
    $c = ord($var[$i]);
    if (in_array($c, $cleanup))
    {
      $var = substr($var, 0, $i) . substr($var, $i + 1);
      $i--;
      continue;
    }

    if ($c < 192)
      continue;
    elseif ($c < 224)
      $i++;
    elseif ($c < 240)
      $i += 2;
    elseif ($c < 248)
      $i += 3;
    elseif ($c < 252)
      $i += 4;
    elseif ($c < 254)
      $i += 5;
  }

  return $var;
}
function ob_sessrewrite($buffer)
{
  global $scripturl, $modSettings, $user_info, $context;
  if ($scripturl == '' || !defined('SID'))
    return $buffer;
  if (empty($_COOKIE) && SID != '' && empty($context['browser']['possibly_robot']) && @version_compare(PHP_VERSION, '4.3.0') != -1)
    $buffer = preg_replace('/"' . preg_quote($scripturl, '/') . '(?!\?' . preg_quote(SID, '/') . ')(\?)?/', '"' . $scripturl . '?' . SID . '&amp;', $buffer);
  elseif (isset($_GET['debug']))
    $buffer = preg_replace('/"' . preg_quote($scripturl, '/') . '(\?)?/', '"' . $scripturl . '?debug;', $buffer);
  if (!empty($modSettings['queryless_urls']) && (!$context['server']['is_cgi'] || @ini_get('cgi.fix_pathinfo') == 1) && $context['server']['is_apache'])
  {
    if (defined('SID') && SID != '')
      $buffer = preg_replace('/"' . preg_quote($scripturl, '/') . '\?(?:' . SID . ';)((?:board|topic)=[^#"]+?)(#[^"]*?)?"/e', "'\"' . \$scripturl . '/' . strtr('\$1', '&;=', '//,') . '.html?' . SID . '\$2\"'", $buffer);
    else
    $buffer = preg_replace('/"' . preg_quote($scripturl, '/') . '\?((?:board|topic)=[^#"]+?)(#[^"]*?)?"/e', "'\"' . \$scripturl . '/' . strtr('\$1', '&;=', '//,') . '.html\$2\"'", $buffer);
  }
  return $buffer;
}

?>
