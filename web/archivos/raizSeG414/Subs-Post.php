<?php
//Pagina de Rodrigo Zaupa (rigo@casitaweb.net)
if (!defined('CasitaWeb!-PorRigo'))die(base64_decode("d3d3LmNhc2l0YXdlYi5uZXQgLSByaWdv"));
function preparsecode(&$message, $previewing = false){}
function un_preparsecode($message){}

function fixTags(&$message)
{
  global $modSettings;
  $fixArray = array(
    array(
      'tag' => 'img',
      'protocols' => array('http', 'https'),
      'embeddedUrl' => false,
      'hasEqualSign' => false,
      'hasExtra' => true,
    ),
    array(
      'tag' => 'url',
      'protocols' => array('http', 'https'),
      'embeddedUrl' => true,
      'hasEqualSign' => false,
    ),
    array(
      'tag' => 'url',
      'protocols' => array('http', 'https'),
      'embeddedUrl' => true,
      'hasEqualSign' => true,
    ),
    array(
      'tag' => 'iurl',
      'protocols' => array('http', 'https'),
      'embeddedUrl' => true,
      'hasEqualSign' => false,
    ),
    array(
      'tag' => 'iurl',
      'protocols' => array('http', 'https'),
      'embeddedUrl' => true,
      'hasEqualSign' => true,
    ),
    // [ftp]ftp://...[/ftp]
    array(
      'tag' => 'ftp',
      'protocols' => array('ftp', 'ftps'),
      'embeddedUrl' => true,
      'hasEqualSign' => false,
    ),
    // [ftp=ftp://...]name[/ftp]
    // [flash]http://...[/flash]
    array(
      'tag' => 'swf',
      'protocols' => array('http', 'https'),
      'embeddedUrl' => false,
      'hasEqualSign' => false,
      'hasExtra' => true,
    ),
  );

  foreach ($fixArray as $param)
    fixTag($message, $param['tag'], $param['protocols'], $param['embeddedUrl'], $param['hasEqualSign'], !empty($param['hasExtra']));

  $message = preg_replace('~(\[img.*?\])(.+?)\[/img\]~eis', '\'$1\' . preg_replace(\'~action(=|%3d)(?!dlattach)~i\', \'action-\', \'$2\') . \'[/img]\'', $message);

  if (!empty($modSettings['max_image_width']) || !empty($modSettings['max_image_height']))
  {
    preg_match_all('~\[img(\s+width=\d+)?(\s+height=\d+)?(\s+width=\d+)?\](.+?)\[/img\]~is', $message, $matches, PREG_PATTERN_ORDER);

    $replaces = array();

    if (!empty($replaces))
      $message = strtr($message, $replaces);}
}

function fixTag(&$message, $myTag, $protocols, $embeddedUrl = false, $hasEqualSign = false, $hasExtra = false)
{
  global $boardurl, $scripturl;

  if (preg_match('~^([^:]+://[^/]+)~', $boardurl, $match) != 0)
    $domain_url = $match[1];
  else
    $domain_url = $boardurl . '/';

  $replaces = array();

  if ($hasEqualSign)
    preg_match_all('~\[(' . $myTag . ')=([^\]]*?)\](.+?)\[/(' . $myTag . ')\]~is', $message, $matches);
  else
    preg_match_all('~\[(' . $myTag . ($hasExtra ? '(?:[^\]]*?)' : '') . ')\](.+?)\[/(' . $myTag . ')\]~is', $message, $matches);

  foreach ($matches[0] as $k => $dummy)
  {
    // Remove all leading and trailing whitespace.
    $replace = trim($matches[2][$k]);
    $this_tag = $matches[1][$k];
    if (!$hasEqualSign)
      $this_close = $matches[3][$k];
    else
      $this_close = $matches[4][$k];

    $found = false;
    foreach ($protocols as $protocol)
    {
      $found = strncasecmp($replace, $protocol . '://', strlen($protocol) + 3) === 0;
      if ($found)
        break;
    }

    if (!$found && $protocols[0] == 'http')
    {
      if (substr($replace, 0, 1) == '/')
        $replace = $domain_url . $replace;
      elseif (substr($replace, 0, 1) == '?')
        $replace = $scripturl . $replace;
      elseif (substr($replace, 0, 1) == '#' && $embeddedUrl)
      {
        $replace = '#' . preg_replace('~[^A-Za-z0-9_\-#]~', '', substr($replace, 1));
        $this_tag = 'iurl';
        $this_close = 'iurl';
      }
      else
        $replace = $protocols[0] . '://' . $replace;
    }
    elseif (!$found)
      $replace = $protocols[0] . '://' . $replace;

    if ($hasEqualSign && $embeddedUrl)
      $replaces['[' . $matches[1][$k] . '=' . $matches[2][$k] . ']' . $matches[3][$k] . '[/' . $matches[4][$k] . ']'] = '[' . $this_tag . '=' . $replace . ']' . $matches[3][$k] . '[/' . $this_close . ']';
    elseif ($hasEqualSign)
      $replaces['[' . $matches[1][$k] . '=' . $matches[2][$k] . ']'] = '[' . $this_tag . '=' . $replace . ']';
    elseif ($embeddedUrl)
      $replaces['[' . $matches[1][$k] . ']' . $matches[2][$k] . '[/' . $matches[3][$k] . ']'] = '[' . $this_tag . '=' . $replace . ']' . $matches[2][$k] . '[/' . $this_close . ']';
    else
      $replaces['[' . $matches[1][$k] . ']' . $matches[2][$k] . '[/' . $matches[3][$k] . ']'] = '[' . $this_tag . ']' . $replace . '[/' . $this_close . ']';

  }

  foreach ($replaces as $k => $v)
  {
    if ($k == $v)
      unset($replaces[$k]);
  }

  if (!empty($replaces))
    $message = strtr($message, $replaces);
}

function sendmail($to, $subject, $message){global $webmaster_email, $mbname, $context, $modSettings, $txt, $scripturl;
$line_break = "\n";
$mail_result = true;
$to_array = is_array($to) ? $to : array($to);

$headers='From: "'.$mbname.'" <' . (empty($modSettings['mail_from']) ? $webmaster_email : $modSettings['mail_from']) . '>' . $line_break;
$headers .= 'Reply-To: <soporte@casitaweb.net>'. $line_break;
$headers .= 'Return-Path: ' . (empty($modSettings['mail_from']) ? $webmaster_email : $modSettings['mail_from']) . $line_break;
$headers .= 'Date: ' . gmdate('D, d M Y H:i:s') . ' -0000' . $line_break;

if (empty($modSettings['mail_no_message_id']))
$headers .= 'Message-ID: <' . md5($scripturl . microtime()) . '-' . strstr(empty($modSettings['mail_from']) ? $webmaster_email : $modSettings['mail_from'], '@') . '>' . $line_break;
$headers .= 'X-Mailer: CasitaWeb!' . $line_break;
$headers .= 'Mime-Version: 1.0' . $line_break;
$headers .= 'Content-Type: text/html; charset=iso-8859-1'. $line_break;
            
$message=str_replace("\n","<br />",trim($message));
if(!$message){fatal_error('Falta el mensaje.');}
if(strlen($message)>=700){fatal_error('El comentario no puede tener 700 o m&aacute; letras.-');}

$messagen='<div style="padding: 8px; border-top:3px solid rgb(255, 157, 3);background-color: rgb(211, 95, 44);width:100%;"><a href="http://casitaweb.net/" target="_blank"><img src="http://casitaweb.net/images/widget-logo.gif" style="border: 0px none;" /></a><div id="ecxbubble"><table style="background-color: rgb(246, 246, 246); color: rgb(34, 34, 34);padding:0pt;" width="100%"><tbody><tr><td style="padding: 8px;" width="100%"><h2 style="margin-bottom: 25px;padding:0pt;">Hola,</h2><p style="padding:0pt;">'.$message.'</p><p style="font-family: \'Lucida Grande\',Lucida Grande,Helvetica,Arial,sans-serif; font-style: normal; font-variant: normal; font-weight: normal; font-size: 13px; line-height: 18px; margin-bottom: 0pt; padding-top: 13px;"><span style="font-family: Georgia,serif; font-variant: normal; font-weight: normal; font-size: 13px; line-height: normal; font-style: italic; color: rgb(102, 102, 102);">El Equipo de '.$mbname.'</span></p>';

foreach ($to_array as $to){
$subject=trim($subject);
if(!$subject){fatal_error('Falta el asunto.');}
if(strlen($subject)>=61){fatal_error('El asunto no puede tener m&aacute;s de 60 letras.-');}
    
if (!mail($to, $subject, $messagen, $headers)){$mail_result = false;}
@set_time_limit(300);
if (function_exists('apache_reset_timeout'))apache_reset_timeout();}    

return $mail_result;}

function sendpm($titulo, $mensaje, $para, $sis) {
  global $db_prefix, $context, $scripturl, $txt, $user_info, $language,$user_settings, $modSettings;

  if ($user_info['is_guest']) {
    die();
  } else {
    $para = (int) $para;
    $sistema = empty($sis) ? 0 : 1;
    $eliminadoss = empty($sistema) ? 0 : 1;
    $titulo = seguridad($titulo);
    $htmlmessage = seguridad($mensaje);

    $request = db_query("
      SELECT ID_MEMBER
      FROM {$db_prefix}members
      WHERE ID_MEMBER = '$para'
      LIMIT 1", __FILE__, __LINE__);

    $verdad_para = mysqli_num_rows($request);

    if (empty($verdad_para)) {
      die('0: El destinario no existe.');
    }

    $id_de = $user_settings['ID_MEMBER'];
    $name_de = $user_settings['realName'];

    if ($id_de == $para) {
      die('0: No te puedes autoenviar mensaje.');
    }

    if (empty($titulo)) {
      die('0: El mensaje debe tener un asunto.');
    }

    if (empty($htmlmessage)) {
      die('0: Debes enviar un mensaje.');
    }

    if (strlen($titulo) >= 150) {
      die('0: El asunto es muy largo.');
    }

    if (strlen($htmlmessage) >= 5534) {
      die('0: El mensaje es muy largo.');
    }

    db_query("
      INSERT INTO {$db_prefix}mensaje_personal (name_de, id_de, id_para, eliminado_de, titulo, mensaje, sistema)
      VALUES ('$name_de', $id_de, $para, $eliminadoss, '$titulo', '$htmlmessage', $sistema)", __FILE__, __LINE__);

    db_query("
      UPDATE {$db_prefix}members
      SET topics = topics + 1
      WHERE ID_MEMBER = $para
      LIMIT 1", __FILE__, __LINE__);

    return true;
  }
}

function mimespecialchars($string, $with_charset = true, $hotmail_fix = false, $line_break = "\r\n"){global $context;

  $charset = $context['character_set'];
    if (preg_match_all('~&#(\d{3,8});~', $string, $matches) !== 0 && !$hotmail_fix){
  $simple = true;

    foreach ($matches[1] as $entity)
      if ($entity > 128)
        $simple = false;
    unset($matches);

    if ($simple)
      $string = preg_replace('~&#(\d{3,8});~e', 'chr(\'$1\')', $string);
    else
    {
      // Try to convert the string to UTF-8.
      if (!$context['utf8'] && function_exists('iconv'))
        $string = @iconv($context['character_set'], 'UTF-8', $string);

      $fixchar = create_function('$n', '
        if ($n < 128)
          return chr($n);
        elseif ($n < 2048)
          return chr(192 | $n >> 6) . chr(128 | $n & 63);
        elseif ($n < 65536)
          return chr(224 | $n >> 12) . chr(128 | $n >> 6 & 63) . chr(128 | $n & 63);
        else
          return chr(240 | $n >> 18) . chr(128 | $n >> 12 & 63) . chr(128 | $n >> 6 & 63) . chr(128 | $n & 63);');

      $string = preg_replace('~&#(\d{3,8});~e', '$fixchar(\'$1\')', $string);

      // Unicode, baby.
      $charset = 'UTF-8';
    }
  }

  // Convert all special characters to HTML entities...just for Hotmail :-\
  if ($hotmail_fix && ($context['utf8'] || function_exists('iconv') || $context['character_set'] === 'ISO-8859-1'))
  {
    if (!$context['utf8'] && function_exists('iconv'))
      $string = @iconv($context['character_set'], 'UTF-8', $string);

    $entityConvert = create_function('$c', '
      if (strlen($c) === 1 && ord($c{0}) <= 0x7F)
        return $c;
      elseif (strlen($c) === 2 && ord($c{0}) >= 0xC0 && ord($c{0}) <= 0xDF)
        return "&#" . (((ord($c{0}) ^ 0xC0) << 6) + (ord($c{1}) ^ 0x80)) . ";";
      elseif (strlen($c) === 3 && ord($c{0}) >= 0xE0 && ord($c{0}) <= 0xEF)
        return "&#" . (((ord($c{0}) ^ 0xE0) << 12) + ((ord($c{1}) ^ 0x80) << 6) + (ord($c{2}) ^ 0x80)) . ";";
      elseif (strlen($c) === 4 && ord($c{0}) >= 0xF0 && ord($c{0}) <= 0xF7)
        return "&#" . (((ord($c{0}) ^ 0xF0) << 18) + ((ord($c{1}) ^ 0x80) << 12) + ((ord($c{2}) ^ 0x80) << 6) + (ord($c{3}) ^ 0x80)) . ";";
      else
        return "";');

    // Convert all 'special' characters to HTML entities.
    return array($charset, preg_replace('~([\x80-' . ($context['server']['complex_preg_chars'] ? '\x{10FFFF}' : pack('C*', 0xF7, 0xBF, 0xBF, 0xBF)) . '])~eu', '$entityConvert(\'\1\')', $string), '7bit');
  }

  elseif (!$hotmail_fix && preg_match('~([^\x09\x0A\x0D\x20-\x7F])~', $string) === 1)
  {
    // Base64 encode.
    $string = base64_encode($string);

    if ($with_charset)
      $string = '=?' . $charset . '?B?' . $string . '?=';
    else
      $string = chunk_split($string, 76, $line_break);

    return array($charset, $string, 'base64');
  }

  else
    return array($charset, $string, '7bit');
}

function smtp_mail($mail_to_array, $subject, $message, $headers){}
function server_parse($message, $socket, $response){}
function calendarValidatePost(){}
function theme_postbox($msg){}
function SpellCheck(){}
function sendNotifications($ID_TOPIC, $type){}
function createPost(&$msgOptions, &$topicOptions, &$posterOptions){}
function modifyPost(&$msgOptions, &$topicOptions, &$posterOptions){}

?>