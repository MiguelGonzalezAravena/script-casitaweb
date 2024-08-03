<?php
require(dirname(dirname(dirname(__FILE__))) . '/funcion-seg-1547.php');
global $tranfer1, $mtitle, $mmessage;

$dbhost = $db_server;
$dbusername = $db_user;
$dbname = $db_name;
$dbpassword = $db_passwd;
$dbtype = "mySQL";
$faqname = 'Ayuda - ' . $mbname;
$prefijo = 'cfaq_';
$adminemail = $webmaster_email;

$link = mysqli_connect($dbhost, $dbusername, $dbpassword);

if (!$link || !@mysqli_select_db($link, $dbname)) {
  die($mtitle . $mmessage);
}

function db($query, $file, $line) {
  global $dbtype, $link;

  $result = mysqli_query($link, $query);

  return $result;
}

function falta($texto) {
  global $helpurl;

  echo '
    <div align="center">
      <div class="box_errors">
        <div class="box_title" style="width: 390px">
          <div class="box_txt box_error" align="left">&iexcl;Atenci&oacute;n!</div>
          <div class="box_rss">
            <img alt="" src="' . $helpurl . '/imagenes/espacio.gif" style="width: 14px; height: 12px;" border="0" />
          </div>
        </div>
        <div class="windowbg" style="width: 382px; padding: 4px;">
          <br />
          ' . $texto . '
          <br />
          <br />
          <input class="login" style="font-size: 11px;" title="Ir a la P&aacute;gina principal" value="Ir a la P&aacute;gina principal" onclick="location.href=\'' . $helpurl . '/\'" type="submit" />
          <br />
          <br />
        </div>
      </div>
      <br />
      <div align="center">
        <p align="center" style="padding: 0px; margin: 0px;">
          <br />';

          anuncio_728x90();
  echo '
        </p>
      </div>
    </div>';

  require_once(dirname(__FILE__) . '/footer-seg-145747dd.php');
  die();
  exit();
}

/*
function bbcode($message, $smileys = true, $cache_id = '') {
  global $txt, $scripturl,$tranfer1,$db_prefix, $context, $modSettings,$tranfer1, $user_info;
  static $bbc_codes = array(), $itemcodes = array(), $no_autolink_tags = array();
  static $disabled;

    $message = str_replace('[img ]', '[img]', $message);
    $message = str_replace('[size=7px]', '[size=9px]', $message);

  if (WIRELESS)$smileys = false;
  elseif ($smileys !== null && ($smileys == '1' || $smileys == '0'))
    $smileys = (bool) $smileys;

  if (empty($modSettings['enableBBC']) && $message !== false) {
        if ($smileys === true)parsesmileys($message);return $message;
      }

  if (!isset($context['utf8']))$context['utf8']='UTF-8';

  if (empty($bbc_codes) || $message === false)
  {
    if (!empty($modSettings['disabledBBC']))
    {
      $temp = explode(',', strtolower($modSettings['disabledBBC']));

      foreach ($temp as $tag)
        $disabled[trim($tag)] = true;
    }

    if (empty($modSettings['enableEmbeddedFlash']))
      $disabled['flash'] = true;

if(!empty($_GET['post']) && $user_info['is_guest']){$sasuser='1';}else{$sasuser='0';}

$youtubeEMBED='<embed src="http://www.youtube.com/v/$1&rel=0&autoplay=0&showsearch=0&hd=0&fs=1&showinfo=1&iv_load_policy=1&hl=0&eurl=http://casitaweb.net&fmt=22&color1=0xD3CAC0&color2=0xA89889&border=0" allowFullScreen="true" quality="high" type="application/x-shockwave-flash" allownetworking="internal" allowscriptaccess="never" wmode="transparent" width="640px" height="385px" /><br /><a href="http://www.youtube.com/watch?v=$1&fmt=22&eurl=http://casitaweb.net/" target="_blank" rel="nofollow">[enlace]</a>';
$erer=1;
$codes = array(
      array(
        'tag' => 'b',
        'before' => '<strong>',
        'after' => '</strong>',
      ),

      array(
        'tag' => 'code',
        'type' => 'unparsed_content',
        'content' => '<div class="code" id="code">' . ($context['browser']['is_gecko'] ? '<pre style="margin-top: 0; display:inline;">$1</pre>' : '$1') . '</div>',
        'validate' => isset($disabled['code']) ? null : create_function('&$tag, &$data, $disabled', '
          global $context;

          if (!isset($disabled[\'code\']))
          {
            $php_parts = preg_split(\'~(&lt;\?php|\?&gt;)~\', $data, -1, PREG_SPLIT_DELIM_CAPTURE);

            for ($php_i = 0, $php_n = count($php_parts); $php_i < $php_n; $php_i++)
            {
              if ($php_parts[$php_i] != \'&lt;?php\')
                continue;

              $php_string = \'\';
              while ($php_i + 1 < count($php_parts) && $php_parts[$php_i] != \'?&gt;\')
              {
                $php_string .= $php_parts[$php_i];
                $php_parts[$php_i++] = \'\';
              }
              $php_parts[$php_i] = highlight_php_code($php_string . $php_parts[$php_i]);
            }
            $data = str_replace("<pre style=\"display: inline;\">\t</pre>", "\t", implode(\'\', $php_parts));
            if ($context[\'browser\'][\'is_ie4\'] || $context[\'browser\'][\'is_ie5\'] || $context[\'browser\'][\'is_ie5.5\'])
              $data = str_replace("\t", "<pre style=\"display: inline;\">\t</pre>", $data);
            elseif (!$context[\'browser\'][\'is_gecko\'])
              $data = str_replace("\t", "<span style=\"white-space: pre;\">\t</span>", $data);
          }'),

      ),
      array(
        'tag' => 'code',
        'type' => 'unparsed_equals_content',
        'content' => '<div class="code">' . ($context['browser']['is_gecko'] ? '<pre style="margin-top: 0; display: inline;">$1</pre>' : '$1') . '</div>',
        'validate' => isset($disabled['code']) ? null : create_function('&$tag, &$data, $disabled', '
          global $context;

          if (!isset($disabled[\'code\']))
          {
            $php_parts = preg_split(\'~(&lt;\?php|\?&gt;)~\', $data[0], -1, PREG_SPLIT_DELIM_CAPTURE);

            for ($php_i = 0, $php_n = count($php_parts); $php_i < $php_n; $php_i++)
            {
              if ($php_parts[$php_i] != \'&lt;?php\')
                continue;

              $php_string = \'\';
              while ($php_i + 1 < count($php_parts) && $php_parts[$php_i] != \'?&gt;\')
              {
                $php_string .= $php_parts[$php_i];
                $php_parts[$php_i++] = \'\';
              }
              $php_parts[$php_i] = highlight_php_code($php_string . $php_parts[$php_i]);
            }
          $data[0] = str_replace("<pre style=\"display: inline;\">\t</pre>", "\t", implode(\'\', $php_parts));
            if ($context[\'browser\'][\'is_ie4\'] || $context[\'browser\'][\'is_ie5\'] || $context[\'browser\'][\'is_ie5.5\'])
              $data = str_replace("\t", "<pre style=\"display: inline;\">\t</pre>", $data);
            elseif (!$context[\'browser\'][\'is_gecko\'])
              $data = str_replace("\t", "<span style=\"white-space: pre;\">\t</span>", $data);
          }'),

      ),
      array(
        'tag' => 'center',
        'before' => '<p align="center">',
        'after' => '</p>',
      ),

            array(
        'tag' => 'color',
        'type' => 'unparsed_equals',
        'before' => '<span style="color: $1;">',
        'after' => '</span>',
      ),

        array(
        'tag' => 'font',
        'type' => 'unparsed_equals',
        'before' => '<span style="font-family: $1;">',
        'after' => '</span>',
      ),
      array(
        'tag' => 'swf',
        'type' => 'unparsed_content',
        'content' => '<embed src="$1" quality="high" type="application/x-shockwave-flash" allownetworking="internal" allowscriptaccess="never" wmode="transparent" width="425" height="350" /><br/><a id="alive_link" href="$1" target="_blank" rel="nofollow">[enlace]</a>',
        'validate' => create_function('&$tag, &$data, $disabled', '$data = strtr($data, array(\'<br />\' => \'\'));'),		),

      array(
        'tag' => 'hr',
        'type' => 'closed',
        'content' => '<div style="width:50%;" class="hrs"></div>',

      ),



        array(
        'tag' => 'iconocat',
        'type' => 'unparsed_content',
        'content' => '<img onload="if(this.width >720) {this.width=720}" alt="" src="'.$tranfer1.'/post/icono_$1.gif" border="0" />',

        'validate' => create_function('&$tag, &$data, $disabled', '$data = strtr($data, array(\'<br />\' => \'\'));'),
        'disabled_content' => '($1)',
      ),


            array('tag' => 'gvideo',
        'type' => 'unparsed_content',
        'content' => '<embed src="http://video.google.com/googleplayer.swf?docId=$1&hl=es" quality="high" type="application/x-shockwave-flash" allownetworking="internal" allowscriptaccess="never" wmode="transparent" width="641" height="385" /><br /><a id="alive_link" href="http://video.google.com/googleplayer.swf?docId=$1&hl=es" target="_blank" rel="nofollow">[enlace]</a>',
        'validate' => create_function('&$tag, &$data, $disabled', '$data = strtr($data, array(\'<br />\' => \'\'));'),
        'disabled_content' => 'Google Video: ($1)',),


      array(
        'tag' => 'img',
        'type' => 'unparsed_content',
        'content' => '<img class="imagen" onload="if(this.width >720) {this.width=720}" src="$1" alt="" />',
        'validate' => create_function('&$tag, &$data, $disabled', '$data = strtr($data, array(\'<br />\' => \'\'));'),
        'disabled_content' => '($1)',
      ),

      array(
        'tag' => 'i',
        'before' => '<i>',
        'after' => '</i>',
      ),
      array(
        'tag' => 'left',
        'before' => '<p align="left">',
        'after' => '</p>',
      ),
      array(
        'tag' => 'right',
        'before' => '<p align="right">',
        'after' => '</p>',
      ),

      array(
        'tag' => 'quote',
        'before' => '<blockquote><div class="cita">Cita: </div><div class="citacuerpo">',
                'after' => '</div></blockquote>',

      ),
          array(
        'tag' => 'quote',
        'parameters' => array(
        'author' => array('match' => '(.{1,192}?)', 'quoted' => true, 'validate' => 'parse_bbc'),
        ),
        'before' => '<blockquote><div class="cita">Cita {author}: </div><div class="citacuerpo">',
        'after' => '</div></blockquote>',

      ),
      array(
        'tag' => 'quote',
        'type' => 'parsed_equals',
        'before' => '<blockquote><div class="cita">Cita $1: </div><div class="citacuerpo">',
            'after' => '</div></blockquote>',
            'quoted' => 'optional',

      ),

            array(
        'tag' => 'align',
        'type' => 'unparsed_equals',
        'test' => '([1-9][\d]?|(?:x-)?right?|(?:x-)?left?|(?:x-)?center?)\]',
        'before' => '<p align="$1">',
        'after' => '</p>',
      ),
          array(
        'tag' => 'size',
        'type' => 'unparsed_equals',
        'test' => '([1-9][\d]?p[x]|(?:x-)?small(?:er)?|(?:x-)?large[r]?)\]',
        'before' => '<span style="font-size: $1; line-height: 1.3em;">',
        'after' => '</span>',
      ),
      array(
        'tag' => 'size',
        'type' => 'unparsed_equals',
        'before' => '<span style="font-size: $1px; line-height: 1.3em;">',
        'after' => '</span>',
      ),

   array(
        'tag' => 'ocultar',
        'type' => 'parsed_equals',
        'before' => '<div style="margin-bottom:5px;margin-top:5px;margin-left:20px;margin-right:20px;"><div><input type="button" value="Mostrar" onclick="if (this.parentNode.parentNode.getElementsByTagName(\'div\')[1].getElementsByTagName(\'div\')[0].style.display != \'\') { this.parentNode.parentNode.getElementsByTagName(\'div\')[1].getElementsByTagName(\'div\')[0].style.display = \'\'; this.innerText = \'\'; this.value = \'Ocultar\'; } else { this.parentNode.parentNode.getElementsByTagName(\'div\')[1].getElementsByTagName(\'div\')[0].style.display = \'none\'; this.innerText = \'\'; this.value = \'Mostrar\'; }" /></div><div style="margin: 0px; padding: 6px;"><div id="content" style="display:none;">',
        'after' => '</div></div></div>',

      ),
             array(
        'tag' => 'ocultar',
        'before' => '<div style="margin-bottom:5px;margin-top:5px;margin-left:20px;margin-right:20px;"><div><input type="button" value="Mostrar" onclick="if (this.parentNode.parentNode.getElementsByTagName(\'div\')[1].getElementsByTagName(\'div\')[0].style.display != \'\') { this.parentNode.parentNode.getElementsByTagName(\'div\')[1].getElementsByTagName(\'div\')[0].style.display = \'\'; this.innerText = \'\'; this.value = \'Ocultar\'; } else { this.parentNode.parentNode.getElementsByTagName(\'div\')[1].getElementsByTagName(\'div\')[0].style.display = \'none\'; this.innerText = \'\'; this.value = \'Mostrar\'; }" /></div><div style="margin: 0px; padding: 6px;"><div id="content" style="display:none;">',
        'after' => '</div></div></div>',

      ),
      array(
        'tag' => 'url',
        'type' => 'unparsed_content',
        'content' => '<a id="alive_link" href="$1" target="_blank" rel="nofollow">$1</a>',
        'validate' => create_function('&$tag, &$data, $disabled', '$data = strtr($data, array(\'<br />\' => \'\'));'),
      ),
      array(
        'tag' => 'url',
        'type' => 'unparsed_equals',
        'before' => '<a id="alive_link" href="$1" target="_blank" rel="nofollow">',
        'after' =>   '</a>',
        'disallow_children' => array('email', 'url', 'iurl'),
        'disabled_after' => $sasuser ?  '' : ' ($1)',
      ),

            array(
        'tag' => 'email',
        'type' => 'unparsed_content',
        'content' => '<a href="mailto:$1">$1</a>',
          'validate' => create_function('&$tag, &$data, $disabled', '$data = strtr($data, array(\'<br />\' => \'\'));'),
      ),
      array(
        'tag' => 'email',
        'type' => 'unparsed_equals',
        'before' => '<a href="mailto:$1">',
        'after' => '</a>',
        'disallow_children' => array('email', 'url', 'iurl'),
        'disabled_after' => ' ($1)',
      ),

      array(
        'tag' => 'asd1256as4867cxc8c7a8xc7asd16a8e7a56s4da65s4d68as7da54d564dcv787v777v7v7v7v7v7as7eelprotocolopm',
        'type' => 'unparsed_equals',
        'before' => '<a href="/$1">',
        'after' => '</a>',
        'disallow_children' => array('email', 'url', 'iurl'),
        'disabled_after' => ' ($1)',
      ),
      array(
        'tag' => 'u',
        'before' => '<span style="text-decoration: underline;">',
        'after' => '</span>',
      ),

            array(
        'tag' => 'youtube',
        'type' => 'unparsed_content',
        'content' => $youtubeEMBED,

                'validate' => create_function('&$tag, &$data, $disabled', '
          global $txt;
          $data = strtr($data, array(\'<br />\' => \'\'));
          $site = \'www.\';
          if (preg_match(\'#^([0-9A-Za-z-_]{11})$#i\', trim($data), $matches))
            $data = $matches[1];
          else
          {
            if (preg_match(\'#^http://((?:www|uk|fr|ie|it|jp|pl|es|nl|br|au|hk|mx|nz|de|ca)\.|)youtube\.com/(?:(?:watch|)\?v=|v/|jp\.swf\?video_id=)([0-9A-Za-z-_]{11})(?:.*?)#i\', trim($data), $matches))
            {
              $data = $matches[2];
              $site = !empty($matches[1]) ? strtolower($matches[1]) : $site;
              unset($matches);
            }

          }
                    '),
        'disabled_content' => 'Video YouTube.com: ($1)',
                    ),

            array(
        'tag' => 'youtube',
        'type' => 'unparsed_commas_content',
        'test' => '\d+,\d+\]',
        'content' => $youtubeEMBED,
           'validate' => create_function('&$tag, &$data, $disabled', '
          global $txt;
          $data[0] = strtr($data[0], array(\'<br />\' => \'\'));
          $site = \'www.\';
          if (preg_match(\'#^([0-9A-Za-z-_]{11})$#i\', trim($data[0]), $matches))
            $data[0] = $matches[1];
          else
          {
            if (preg_match(\'#^http://((?:www|uk|fr|ie|it|jp|pl|es|nl|br|au|hk|mx|nz|de|ca)\.|)youtube\.com/(?:(?:watch|)\?v=|v/|jp\.swf\?video_id=)([0-9A-Za-z-_]{11})(?:.*?)#i\', trim($data[0]), $matches))
            {
              $data[0] = $matches[2];
              $site = !empty($matches[1]) ? strtolower($matches[1]) : $site;
              unset($matches);
            }
            else
            {
              // Invalid link
              $tag[\'content\'] = $txt[\'youtube_invalid\'];
              return;
            }
          }

          if (isset($disabled[\'url\']) && isset($disabled[\'youtube\']))
          {
            $tag[\'content\'] = $txt[\'youtube\'].\': http://\'.$site.\'youtube.com/watch?v=\'.$data[0];
            return;
          }
          elseif(isset($disabled[\'youtube\']))
          {
            $tag[\'content\'] = \'<a id="alive_link" href="http://\'.$site.\'youtube.com/watch?v=\'.$data[0].\'" target="_blank" rel="nofollow">\'.$txt[\'youtube\'].\': http://\'.$site.\'youtube.com/watch?v=\'.$data[0].\'</a>\';
            return;
          }
          if($data[1] > 800 || $data[1] < 100 || $data[2] > 800 || $data[2] < 100)
          {
            $data[1] = 640;
            $data[2] = 385;
          }
        '),
                'disabled_content' => 'Video YouTube.com: ($1)',)
    );

    if ($message === false)
      return $codes;
    $itemcodes = array(
      '*' => '',
      '@' => 'disc',
      '+' => 'square',
      'x' => 'square',
      '#' => 'square',
      'o' => 'circle',
      'O' => 'circle',
      '0' => 'circle',
            );

    $no_autolink_tags = array(
      'url',
      'iurl',
    );

    foreach ($codes as $c)
      $bbc_codes[substr($c['tag'], 0, 1)][] = $c;
    $codes = null;
  }

if ($cache_id != '' && !empty($modSettings['cache_enable']) && (($modSettings['cache_enable'] >= 2 && strlen($message) > 1000) || strlen($message) > 2400))
  {
    $cache_key = 'parse:' . $cache_id . '-' . md5(md5($message) . '-' . $smileys . (empty($disabled) ? '' : implode(',', array_keys($disabled))) . serialize($context['browser']) . $txt['lang_locale'] . $user_info['time_offset'] . $user_info['time_format']);

    if (($temp = cache_get_data($cache_key, 240)) != null)
      return $temp;

    $cache_t = microtime();
  }

  if ($smileys === 'print')
  {

    $disabled['color'] = true;
    $disabled['url'] = true;
    $disabled['iurl'] = true;
    $disabled['swf'] = true;
    $disabled['youtube'] = true;


    if (!isset($_GET['images']))
      $disabled['img'] = true;

  }

  $open_tags = array();


  $message = strtr($message, array("\n" => '<br />'));

  $non_breaking_space = $context['utf8'] ? ($context['server']['complex_preg_chars'] ? '\x{C2A0}' : chr(0xC2) . chr(0xA0)) : '\xA0';

  $pos = -1;
  while ($pos !== false)
  {
    $last_pos = isset($last_pos) ? max($pos, $last_pos) : $pos;
    $pos = strpos($message, '[', $pos + 1);

    // Failsafe.
    if ($pos === false || $last_pos > $pos)
      $pos = strlen($message) + 1;

    // Can't have a one letter smiley, URL, or email! (sorry.)
    if ($last_pos < $pos - 1)
    {
      // We want to eat one less, and one more, character (for smileys.)
      $last_pos = max($last_pos - 1, 0);
      $data = substr($message, $last_pos, $pos - $last_pos + 1);

      // Take care of some HTML!
      if (!empty($modSettings['enablePostHTML']) && strpos($data, '&lt;') !== false)
      {
        $data = preg_replace('~&lt;a\s+href=(?:&quot;)?((?:http://|ftp://|https://|ftps://|mailto:).+?)(?:&quot;)?&gt;~i', '[url=$1]', $data);
        $data = preg_replace('~&lt;/a&gt;~i', '[/url]', $data);

        // <br /> should be empty.
        $empty_tags = array('br', 'hr');
        foreach ($empty_tags as $tag)
          $data = str_replace(array('&lt;' . $tag . '&gt;', '&lt;' . $tag . '/&gt;', '&lt;' . $tag . ' /&gt;'), '[' . $tag . ' /]', $data);

        // b, u, i, s, pre... basic tags.
        $closable_tags = array('b', 'u', 'i', 's', 'em', 'ins', 'del', 'pre', 'blockquote');
        foreach ($closable_tags as $tag)
        {
          $diff = substr_count($data, '&lt;' . $tag . '&gt;') - substr_count($data, '&lt;/' . $tag . '&gt;');
          $data = strtr($data, array('&lt;' . $tag . '&gt;' => '<' . $tag . '>', '&lt;/' . $tag . '&gt;' => '</' . $tag . '>'));

          if ($diff > 0)
            $data .= str_repeat('</' . $tag . '>', $diff);
        }

        // Do <img ... /> - with security... action= -> action-.
        preg_match_all('~&lt;img\s+src=(?:&quot;)?((?:http://|ftp://|https://|ftps://).+?)(?:&quot;)?(?:\s+alt=(?:&quot;)?(.*?)(?:&quot;)?)?(?:\s?/)?&gt;~i', $data, $matches, PREG_PATTERN_ORDER);
        if (!empty($matches[0]))
        {
          $replaces = array();
          foreach ($matches[1] as $match => $imgtag)
          {
            // No alt?
            if (!isset($matches[2][$match]))
              $matches[2][$match] = '';

            // Remove action= from the URL - no funny business, now.
            if (preg_match('~action(=|%3d)(?!dlattach)~i', $imgtag) != 0)
              $imgtag = preg_replace('~action(=|%3d)(?!dlattach)~i', 'action-', $imgtag);

            // Check if the image is larger than allowed.
            if (!empty($modSettings['max_image_width']) && !empty($modSettings['max_image_height']))
            {
              list ($width, $height) = url_image_size($imgtag);

              if (!empty($modSettings['max_image_width']) && $width > $modSettings['max_image_width'])
              {
                $height = (int) (($modSettings['max_image_width'] * $height) / $width);
                $width = $modSettings['max_image_width'];
              }

              if (!empty($modSettings['max_image_height']) && $height > $modSettings['max_image_height'])
              {
                $width = (int) (($modSettings['max_image_height'] * $width) / $height);
                $height = $modSettings['max_image_height'];
              }

              // Set the new image tag.
              $replaces[$matches[0][$match]] = '<img src="' . $imgtag . '" width="' . $width . '" height="' . $height . '" alt="' . $matches[2][$match] . '" border="0" />';
            }
            else
              $replaces[$matches[0][$match]] = '<img src="' . $imgtag . '" alt="' . $matches[2][$match] . '" border="0" />';
          }

          $data = strtr($data, $replaces);
        }
      }

      if (!empty($modSettings['autoLinkUrls']))
      {
        // Are we inside tags that should be auto linked?
        $no_autolink_area = false;
        if (!empty($open_tags))
        {
          foreach ($open_tags as $open_tag)
            if (in_array($open_tag['tag'], $no_autolink_tags))
              $no_autolink_area = true;
        }

        // Don't go backwards.
        //!!! Don't think is the real solution....
        $lastAutoPos = isset($lastAutoPos) ? $lastAutoPos : 0;
        if ($pos < $lastAutoPos)
          $no_autolink_area = true;
        $lastAutoPos = $pos;

        if (!$no_autolink_area)
        {
          // Parse any URLs.... have to get rid of the @ problems some things cause... stupid email addresses.
          if (!isset($disabled['url']) && (strpos($data, '://') !== false || strpos($data, 'www.') !== false))
          {
            // Switch out quotes really quick because they can cause problems.
            $data = strtr($data, array('&#039;' => '\'', '&nbsp;' => $context['utf8'] ? "\xC2\xA0" : "\xA0", '&quot;' => '>">', '"' => '<"<', '&lt;' => '<lt<'));
            $data = preg_replace(array('~(?<=[\s>\.(;\'"]|^)((?:http|https|ftp|ftps)://[\w\-_%@:|]+(?:\.[\w\-_%]+)*(?::\d+)?(?:/[\w\-_\~%\.@,\?&;=#+:\'\\\\]*|[\(\{][\w\-_\~%\.@,\?&;=#(){}+:\'\\\\]*)*[/\w\-_\~%@\?;=#}\\\\])~i', '~(?<=[\s>(\'<]|^)(www(?:\.[\w\-_]+)+(?::\d+)?(?:/[\w\-_\~%\.@,\?&;=#+:\'\\\\]*|[\(\{][\w\-_\~%\.@,\?&;=#(){}+:\'\\\\]*)*[/\w\-_\~%@\?;=#}\\\\])~i'), array('[url]$1[/url]', '[url=http://$1]$1[/url]'), $data);
            $data = strtr($data, array('\'' => '&#039;', $context['utf8'] ? "\xC2\xA0" : "\xA0" => '&nbsp;', '>">' => '&quot;', '<"<' => '"', '<lt<' => '&lt;'));
          }

          // Next, emails...
          if (!isset($disabled['email']) && strpos($data, '@') !== false)
          {
            $data = preg_replace('~(?<=[\?\s' . $non_breaking_space . '\[\]()*\\\;>]|^)([\w\-\.]{1,80}@[\w\-]+\.[\w\-\.]+[\w\-])(?=[?,\s' . $non_breaking_space . '\[\]()*\\\]|$|<br />|&nbsp;|&gt;|&lt;|&quot;|&#039;|\.(?:\.|;|&nbsp;|\s|$|<br />))~' . ($context['utf8'] ? 'u' : ''), '[email]$1[/email]', $data);
            $data = preg_replace('~(?<=<br />)([\w\-\.]{1,80}@[\w\-]+\.[\w\-\.]+[\w\-])(?=[?\.,;\s' . $non_breaking_space . '\[\]()*\\\]|$|<br />|&nbsp;|&gt;|&lt;|&quot;|&#039;)~' . ($context['utf8'] ? 'u' : ''), '[email]$1[/email]', $data);
          }
        }
      }

      $data = strtr($data, array("\t" => '&nbsp;&nbsp;&nbsp;'));

      if (!empty($modSettings['fixLongWords']) && $modSettings['fixLongWords'] > 5)
      {
        // This is SADLY and INCREDIBLY browser dependent.
        if ($context['browser']['is_gecko'] || $context['browser']['is_konqueror'])
          $breaker = '<span style="margin: 0 -0.5ex 0 0;"> </span>';
        // Opera...
        elseif ($context['browser']['is_opera'])
          $breaker = '<span style="margin: 0 -0.65ex 0 -1px;"> </span>';
        // Internet Explorer...
        else
          $breaker = '<span style="width: 0; margin: 0 -0.6ex 0 -1px;"> </span>';

        // PCRE will not be happy if we don't give it a short.
        $modSettings['fixLongWords'] = (int) min(65535, $modSettings['fixLongWords']);

        // The idea is, find words xx long, and then replace them with xx + space + more.
        if (strlen($data) > $modSettings['fixLongWords'])
        {
          // This is done in a roundabout way because $breaker has "long words" :P.
          $data = strtr($data, array($breaker => '< >', '&nbsp;' => $context['utf8'] ? "\xC2\xA0" : "\xA0"));
          $data = preg_replace(
            '~(?<=[>;:!? ' . $non_breaking_space . '\]()]|^)([\w\.]{' . $modSettings['fixLongWords'] . ',})~e' . ($context['utf8'] ? 'u' : ''),
            "preg_replace('/(.{" . ($modSettings['fixLongWords'] - 1) . '})/' . ($context['utf8'] ? 'u' : '') . "', '\\\$1< >', '\$1')",
            $data);
          $data = strtr($data, array('< >' => $breaker, $context['utf8'] ? "\xC2\xA0" : "\xA0" => '&nbsp;'));
        }
      }

      // Do any smileys!
      if ($smileys === true)
        parsesmileys($data);

      // If it wasn't changed, no copying or other boring stuff has to happen!
      if ($data != substr($message, $last_pos, $pos - $last_pos + 1))
      {
        $message = substr($message, 0, $last_pos) . $data . substr($message, $pos + 1);

        // Since we changed it, look again incase we added or removed a tag.  But we don't want to skip any.
        $old_pos = strlen($data) + $last_pos - 1;
        $pos = strpos($message, '[', $last_pos);
        $pos = $pos === false ? $old_pos : min($pos, $old_pos);
      }
    }

    // Are we there yet?  Are we there yet?
    if ($pos >= strlen($message) - 1)
      break;

    $tags = strtolower(substr($message, $pos + 1, 1));

    if ($tags == '/' && !empty($open_tags))
    {
      $pos2 = strpos($message, ']', $pos + 1);
      if ($pos2 == $pos + 2)
        continue;
      $look_for = strtolower(substr($message, $pos + 2, $pos2 - $pos - 2));

      $to_close = array();
      $block_level = null;
      do
      {
        $tag = array_pop($open_tags);
        if (!$tag)
          break;

        if (!empty($tag['block_level']))
        {
          // Only find out if we need to.
          if ($block_level === false)
          {
            array_push($open_tags, $tag);
            break;
          }

          // The idea is, if we are LOOKING for a block level tag, we can close them on the way.
          if (strlen($look_for) > 0 && isset($bbc_codes[$look_for{0}]))
          {
            foreach ($bbc_codes[$look_for{0}] as $temp)
              if ($temp['tag'] == $look_for)
              {
                $block_level = !empty($temp['block_level']);
                break;
              }
          }

          if ($block_level !== true)
          {
            $block_level = false;
            array_push($open_tags, $tag);
            break;
          }
        }

        $to_close[] = $tag;
      }
      while ($tag['tag'] != $look_for);

      // Did we just eat through everything and not find it?
      if ((empty($open_tags) && (empty($tag) || $tag['tag'] != $look_for)))
      {
        $open_tags = $to_close;
        continue;
      }
      elseif (!empty($to_close) && $tag['tag'] != $look_for)
      {
        if ($block_level === null && isset($look_for{0}, $bbc_codes[$look_for{0}]))
        {
          foreach ($bbc_codes[$look_for{0}] as $temp)
            if ($temp['tag'] == $look_for)
            {
              $block_level = !empty($temp['block_level']);
              break;
            }
        }

        // We're not looking for a block level tag (or maybe even a tag that exists...)
        if (!$block_level)
        {
          foreach ($to_close as $tag)
            array_push($open_tags, $tag);
          continue;
        }
      }

      foreach ($to_close as $tag)
      {
        $message = substr($message, 0, $pos) . $tag['after'] . substr($message, $pos2 + 1);
        $pos += strlen($tag['after']);
        $pos2 = $pos - 1;

        // See the comment at the end of the big loop - just eating whitespace ;).
        if (!empty($tag['block_level']) && substr($message, $pos, 6) == '<br />')
          $message = substr($message, 0, $pos) . substr($message, $pos + 6);
        if (!empty($tag['trim']) && $tag['trim'] != 'inside' && preg_match('~(<br />|&nbsp;|\s)*~', substr($message, $pos), $matches) != 0)
          $message = substr($message, 0, $pos) . substr($message, $pos + strlen($matches[0]));
      }

      if (!empty($to_close))
      {
        $to_close = array();
        $pos--;
      }

      continue;
    }

    // No tags for this character, so just keep going (fastest possible course.)
    if (!isset($bbc_codes[$tags]))
      continue;

    $inside = empty($open_tags) ? null : $open_tags[count($open_tags) - 1];
    $tag = null;
    foreach ($bbc_codes[$tags] as $possible)
    {
      // Not a match?
      if (strtolower(substr($message, $pos + 1, strlen($possible['tag']))) != $possible['tag'])
        continue;

      $next_c = substr($message, $pos + 1 + strlen($possible['tag']), 1);

      // A test validation?
      if (isset($possible['test']) && preg_match('~^' . $possible['test'] . '~', substr($message, $pos + 1 + strlen($possible['tag']) + 1)) == 0)
        continue;
      // Do we want parameters?
      elseif (!empty($possible['parameters']))
      {
        if ($next_c != ' ')
          continue;
      }
      elseif (isset($possible['type']))
      {
        // Do we need an equal sign?
        if (in_array($possible['type'], array('unparsed_equals', 'unparsed_commas', 'unparsed_commas_content', 'unparsed_equals_content', 'parsed_equals')) && $next_c != '=')
          continue;
        // Maybe we just want a /...
        if ($possible['type'] == 'closed' && $next_c != ']' && substr($message, $pos + 1 + strlen($possible['tag']), 2) != '/]' && substr($message, $pos + 1 + strlen($possible['tag']), 3) != ' /]')
          continue;
        // An immediate ]?
        if ($possible['type'] == 'unparsed_content' && $next_c != ']')
          continue;
      }
      // No type means 'parsed_content', which demands an immediate ] without parameters!
      elseif ($next_c != ']')
        continue;

      // Check allowed tree?
      if (isset($possible['require_parents']) && ($inside === null || !in_array($inside['tag'], $possible['require_parents'])))
        continue;
      elseif (isset($inside['require_children']) && !in_array($possible['tag'], $inside['require_children']))
        continue;
      elseif (isset($inside['disallow_children']) && in_array($possible['tag'], $inside['disallow_children']))
        continue;
      $pos1 = $pos + 1 + strlen($possible['tag']) + 1;
      if (!empty($possible['parameters']))
      {
        $preg = array();
        foreach ($possible['parameters'] as $p => $info)
          $preg[] = '(\s+' . $p . '=' . (empty($info['quoted']) ? '' : '&quot;') . (isset($info['match']) ? $info['match'] : '(.+?)') . (empty($info['quoted']) ? '' : '&quot;') . ')' . (empty($info['optional']) ? '' : '?');
        $match = false;
        $orders = permute($preg);
        foreach ($orders as $p)
          if (preg_match('~^' . implode('', $p) . '\]~i', substr($message, $pos1 - 1), $matches) != 0)
          {
            $match = true;
            break;
          }
        if (!$match)
          continue;

        $params = array();
        for ($i = 1, $n = count($matches); $i < $n; $i += 2)
        {
          $key = strtok(ltrim($matches[$i]), '=');
          if (isset($possible['parameters'][$key]['value']))
            $params['{' . $key . '}'] = strtr($possible['parameters'][$key]['value'], array('$1' => $matches[$i + 1]));
          elseif (isset($possible['parameters'][$key]['validate']))
            $params['{' . $key . '}'] = $possible['parameters'][$key]['validate']($matches[$i + 1]);
          else
            $params['{' . $key . '}'] = $matches[$i + 1];

          // Just to make sure: replace any $ or { so they can't interpolate wrongly.
          $params['{' . $key . '}'] = strtr($params['{' . $key . '}'], array('$' => '&#036;', '{' => '&#123;'));
        }

        foreach ($possible['parameters'] as $p => $info)
        {
          if (!isset($params['{' . $p . '}']))
            $params['{' . $p . '}'] = '';
        }

        $tag = $possible;

        // Put the parameters into the string.
        if (isset($tag['before']))
          $tag['before'] = strtr($tag['before'], $params);
        if (isset($tag['after']))
          $tag['after'] = strtr($tag['after'], $params);
        if (isset($tag['content']))
          $tag['content'] = strtr($tag['content'], $params);

        $pos1 += strlen($matches[0]) - 1;
      }
      else
        $tag = $possible;
      break;
    }

    if ($tag === null && $inside !== null && !empty($inside['require_children']))
    {
      array_pop($open_tags);

      $message = substr($message, 0, $pos) . $inside['after'] . substr($message, $pos);
      $pos += strlen($inside['after']) - 1;
    }

    // No tag?  Keep looking, then.  Silly people using brackets without actual tags.
    if ($tag === null)
      continue;

    // Propagate the list to the child (so wrapping the disallowed tag won't work either.)
    if (isset($inside['disallow_children']))
      $tag['disallow_children'] = isset($tag['disallow_children']) ? array_unique(array_merge($tag['disallow_children'], $inside['disallow_children'])) : $inside['disallow_children'];

    // Is this tag disabled?
    if (isset($disabled[$tag['tag']]))
    {
      if (!isset($tag['disabled_before']) && !isset($tag['disabled_after']) && !isset($tag['disabled_content']))
      {
        $tag['before'] = !empty($tag['block_level']) ? '<div>' : '';
        $tag['after'] = !empty($tag['block_level']) ? '</div>' : '';
        $tag['content'] = isset($tag['type']) && $tag['type'] == 'closed' ? '' : (!empty($tag['block_level']) ? '<div>$1</div>' : '$1');
      }
      elseif (isset($tag['disabled_before']) || isset($tag['disabled_after']))
      {
        $tag['before'] = isset($tag['disabled_before']) ? $tag['disabled_before'] : (!empty($tag['block_level']) ? '<div>' : '');
        $tag['after'] = isset($tag['disabled_after']) ? $tag['disabled_after'] : (!empty($tag['block_level']) ? '</div>' : '');
      }
      else
        $tag['content'] = $tag['disabled_content'];
    }

    // The only special case is 'html', which doesn't need to close things.
    if (!empty($tag['block_level']) && $tag['tag'] != 'html' && empty($inside['block_level']))
    {
      $n = count($open_tags) - 1;
      while (empty($open_tags[$n]['block_level']) && $n >= 0)
        $n--;

      // Close all the non block level tags so this tag isn't surrounded by them.
      for ($i = count($open_tags) - 1; $i > $n; $i--)
      {
        $message = substr($message, 0, $pos) . $open_tags[$i]['after'] . substr($message, $pos);
        $pos += strlen($open_tags[$i]['after']);
        $pos1 += strlen($open_tags[$i]['after']);

        // Trim or eat trailing stuff... see comment at the end of the big loop.
        if (!empty($open_tags[$i]['block_level']) && substr($message, $pos, 6) == '<br />')
          $message = substr($message, 0, $pos) . substr($message, $pos + 6);
        if (!empty($open_tags[$i]['trim']) && $tag['trim'] != 'inside' && preg_match('~(<br />|&nbsp;|\s)*~', substr($message, $pos), $matches) != 0)
          $message = substr($message, 0, $pos) . substr($message, $pos + strlen($matches[0]));

        array_pop($open_tags);
      }
    }

    // No type means 'parsed_content'.
    if (!isset($tag['type']))
    {
      // !!! Check for end tag first, so people can say "I like that [i] tag"?
      $open_tags[] = $tag;
      $message = substr($message, 0, $pos) . $tag['before'] . substr($message, $pos1);
      $pos += strlen($tag['before']) - 1;
    }
    // Don't parse the content, just skip it.
    elseif ($tag['type'] == 'unparsed_content')
    {
      $pos2 = stripos($message, '[/' . substr($message, $pos + 1, strlen($tag['tag'])) . ']', $pos1);
      if ($pos2 === false)
        continue;

      $data = substr($message, $pos1, $pos2 - $pos1);

      if (!empty($tag['block_level']) && substr($data, 0, 6) == '<br />')
        $data = substr($data, 6);

      if (isset($tag['validate']))
        $tag['validate']($tag, $data, $disabled);

      $code = strtr($tag['content'], array('$1' => $data));
      $message = substr($message, 0, $pos) . $code . substr($message, $pos2 + 3 + strlen($tag['tag']));
      $pos += strlen($code) - 1;
    }
    // Don't parse the content, just skip it.
    elseif ($tag['type'] == 'unparsed_equals_content')
    {
      // The value may be quoted for some tags - check.
      if (isset($tag['quoted']))
      {
        $quoted = substr($message, $pos1, 6) == '&quot;';
        if ($tag['quoted'] != 'optional' && !$quoted)
          continue;

        if ($quoted)
          $pos1 += 6;
      }
      else
        $quoted = false;

      $pos2 = strpos($message, $quoted == false ? ']' : '&quot;]', $pos1);
      if ($pos2 === false)
        continue;
      $pos3 = stripos($message, '[/' . substr($message, $pos + 1, strlen($tag['tag'])) . ']', $pos2);
      if ($pos3 === false)
        continue;

      $data = array(
        substr($message, $pos2 + ($quoted == false ? 1 : 7), $pos3 - ($pos2 + ($quoted == false ? 1 : 7))),
        substr($message, $pos1, $pos2 - $pos1)
      );

      if (!empty($tag['block_level']) && substr($data[0], 0, 6) == '<br />')
        $data[0] = substr($data[0], 6);

      // Validation for my parking, please!
      if (isset($tag['validate']))
        $tag['validate']($tag, $data, $disabled);

      $code = strtr($tag['content'], array('$1' => $data[0], '$2' => $data[1]));
      $message = substr($message, 0, $pos) . $code . substr($message, $pos3 + 3 + strlen($tag['tag']));
      $pos += strlen($code) - 1;
    }
    // A closed tag, with no content or value.
    elseif ($tag['type'] == 'closed')
    {
      $pos2 = strpos($message, ']', $pos);
      $message = substr($message, 0, $pos) . $tag['content'] . substr($message, $pos2 + 1);
      $pos += strlen($tag['content']) - 1;
    }
    // This one is sorta ugly... :/.  Unforunately, it's needed for flash.
    elseif ($tag['type'] == 'unparsed_commas_content')
    {
      $pos2 = strpos($message, ']', $pos1);
      if ($pos2 === false)
        continue;
      $pos3 = stripos($message, '[/' . substr($message, $pos + 1, strlen($tag['tag'])) . ']', $pos2);
      if ($pos3 === false)
        continue;

      // We want $1 to be the content, and the rest to be csv.
      $data = explode(',', ',' . substr($message, $pos1, $pos2 - $pos1));
      $data[0] = substr($message, $pos2 + 1, $pos3 - $pos2 - 1);

      if (isset($tag['validate']))
        $tag['validate']($tag, $data, $disabled);

      $code = $tag['content'];
      foreach ($data as $k => $d)
        $code = strtr($code, array('$' . ($k + 1) => trim($d)));
      $message = substr($message, 0, $pos) . $code . substr($message, $pos3 + 3 + strlen($tag['tag']));
      $pos += strlen($code) - 1;
    }
    // This has parsed content, and a csv value which is unparsed.
    elseif ($tag['type'] == 'unparsed_commas')
    {
      $pos2 = strpos($message, ']', $pos1);
      if ($pos2 === false)
        continue;

      $data = explode(',', substr($message, $pos1, $pos2 - $pos1));

      if (isset($tag['validate']))
        $tag['validate']($tag, $data, $disabled);

      // Fix after, for disabled code mainly.
      foreach ($data as $k => $d)
        $tag['after'] = strtr($tag['after'], array('$' . ($k + 1) => trim($d)));

      $open_tags[] = $tag;

      // Replace them out, $1, $2, $3, $4, etc.
      $code = $tag['before'];
      foreach ($data as $k => $d)
        $code = strtr($code, array('$' . ($k + 1) => trim($d)));
      $message = substr($message, 0, $pos) . $code . substr($message, $pos2 + 1);
      $pos += strlen($code) - 1;
    }
    // A tag set to a value, parsed or not.
    elseif ($tag['type'] == 'unparsed_equals' || $tag['type'] == 'parsed_equals')
    {
      // The value may be quoted for some tags - check.
      if (isset($tag['quoted']))
      {
        $quoted = substr($message, $pos1, 6) == '&quot;';
        if ($tag['quoted'] != 'optional' && !$quoted)
          continue;

        if ($quoted)
          $pos1 += 6;
      }
      else
        $quoted = false;

      $pos2 = strpos($message, $quoted == false ? ']' : '&quot;]', $pos1);
      if ($pos2 === false)
        continue;

      $data = substr($message, $pos1, $pos2 - $pos1);

      // Validation for my parking, please!
      if (isset($tag['validate']))
        $tag['validate']($tag, $data, $disabled);

      // For parsed content, we must recurse to avoid security problems.
      if ($tag['type'] != 'unparsed_equals')
        $data = parse_bbc($data);

      $tag['after'] = strtr($tag['after'], array('$1' => $data));

      $open_tags[] = $tag;

      $code = strtr($tag['before'], array('$1' => $data));
      $message = substr($message, 0, $pos) . $code . substr($message, $pos2 + ($quoted == false ? 1 : 7));
      $pos += strlen($code) - 1;
    }

    // If this is block level, eat any breaks after it.
    if (!empty($tag['block_level']) && substr($message, $pos + 1, 6) == '<br />')
      $message = substr($message, 0, $pos + 1) . substr($message, $pos + 7);

    // Are we trimming outside this tag?
    if (!empty($tag['trim']) && $tag['trim'] != 'outside' && preg_match('~(<br />|&nbsp;|\s)*~', substr($message, $pos + 1), $matches) != 0)
      $message = substr($message, 0, $pos + 1) . substr($message, $pos + 1 + strlen($matches[0]));
  }

  // Close any remaining tags.
  while ($tag = array_pop($open_tags))
    $message .= $tag['after'];

  if (substr($message, 0, 1) == ' ')
    $message = '&nbsp;' . substr($message, 1);

  // Cleanup whitespace.
  $message = strtr($message, array('  ' => ' &nbsp;', "\r" => '', "\n" => '<br />', '<br /> ' => '<br />&nbsp;', '&#13;' => "\n"));

  //Clean up some missing removed hide close tags...
  if(preg_match("/\[\/hide\]/i", $message) != 0)
    $message = preg_replace("/\[\/hide\]/i", '', $message);

  // Cache the output if it took some time...
  if (isset($cache_key, $cache_t) && array_sum(explode(' ', microtime())) - array_sum(explode(' ', $cache_t)) > 0.05)
    cache_put_data($cache_key, $message, 240);

return $message;}
*/

// Parse bulletin board code in a string, as well as smileys optionally.
function bbcode($message, $smileys = true, $cache_id = '', $parse_tags = array()) {
  global $txt, $scripturl, $context, $modSettings, $user_info, $smcFunc;
  static $bbc_codes = array(), $itemcodes = array(), $no_autolink_tags = array();
  static $disabled;

  // Don't waste cycles
  if ($message === '')
    return '';

  // Never show smileys for wireless clients.  More bytes, can't see it anyway :P.
  if (WIRELESS)
    $smileys = false;
  elseif ($smileys !== null && ($smileys == '1' || $smileys == '0'))
    $smileys = (bool) $smileys;

  if (empty($modSettings['enableBBC']) && $message !== false) {
    if ($smileys === true)
      parsesmileys($message);

    return $message;
  }

  // Just in case it wasn't determined yet whether UTF-8 is enabled.
  if (!isset($context['utf8']))
    $context['utf8'] = (empty($modSettings['global_character_set']) ? $txt['lang_character_set'] : $modSettings['global_character_set']) === 'UTF-8';

  // If we are not doing every tag then we don't cache this run.
  if (!empty($parse_tags) && !empty($bbc_codes)) {
    $temp_bbc = $bbc_codes;
    $bbc_codes = array();
  }

  // Sift out the bbc for a performance improvement.
  if (empty($bbc_codes) || $message === false || !empty($parse_tags)) {
    if (!empty($modSettings['disabledBBC'])) {
      $temp = explode(',', strtolower($modSettings['disabledBBC']));

      foreach ($temp as $tag)
        $disabled[trim($tag)] = true;
    }

    if (empty($modSettings['enableEmbeddedFlash']))
      $disabled['flash'] = true;

    /*
     * The following bbc are formatted as an array, with keys as follows:
     *
     * tag: the tag's name - should be lowercase!
     *
     * type: one of...
     * 	- (missing): [tag]parsed content[/tag]
     * 	- unparsed_equals: [tag=xyz]parsed content[/tag]
     * 	- parsed_equals: [tag=parsed data]parsed content[/tag]
     * 	- unparsed_content: [tag]unparsed content[/tag]
     * 	- closed: [tag], [tag/], [tag /]
     * 	- unparsed_commas: [tag=1,2,3]parsed content[/tag]
     * 	- unparsed_commas_content: [tag=1,2,3]unparsed content[/tag]
     * 	- unparsed_equals_content: [tag=...]unparsed content[/tag]
     *
     * parameters: an optional array of parameters, for the form
     * 	[tag abc=123]content[/tag].  The array is an associative array
     * 	where the keys are the parameter names, and the values are an
     * 	array which may contain the following:
     * 	- match: a regular expression to validate and match the value.
     * 	- quoted: true if the value should be quoted.
     * 	- validate: callback to evaluate on the data, which is $data.
     * 	- value: a string in which to replace $1 with the data.
     * 		either it or validate may be used, not both.
     * 	- optional: true if the parameter is optional.
     *
     * test: a regular expression to test immediately after the tag's
     * 	'=', ' ' or ']'.  Typically, should have a \] at the end.
     * 	Optional.
     *
     * content: only available for unparsed_content, closed,
     * 	unparsed_commas_content, and unparsed_equals_content.
     * 	$1 is replaced with the content of the tag.  Parameters
     * 	are replaced in the form {param}.  For unparsed_commas_content,
     * 	$2, $3, ..., $n are replaced.
     *
     * before: only when content is not used, to go before any
     * 	content.  For unparsed_equals, $1 is replaced with the value.
     * 	For unparsed_commas, $1, $2, ..., $n are replaced.
     *
     * after: similar to before in every way, except that it is used
     * 	when the tag is closed.
     *
     * disabled_content: used in place of content when the tag is
     * 	disabled.  For closed, default is '', otherwise it is '$1' if
     * 	block_level is false, '<div>$1</div>' elsewise.
     *
     * disabled_before: used in place of before when disabled.  Defaults
     * 	to '<div>' if block_level, '' if not.
     *
     * disabled_after: used in place of after when disabled.  Defaults
     * 	to '</div>' if block_level, '' if not.
     *
     * block_level: set to true the tag is a "block level" tag, similar
     * 	to HTML.  Block level tags cannot be nested inside tags that are
     * 	not block level, and will not be implicitly closed as easily.
     * 	One break following a block level tag may also be removed.
     *
     * trim: if set, and 'inside' whitespace after the begin tag will be
     * 	removed.  If set to 'outside', whitespace after the end tag will
     * 	meet the same fate.
     *
     * validate: except when type is missing or 'closed', a callback to
     * 	validate the data as $data.  Depending on the tag's type, $data
     * 	may be a string or an array of strings (corresponding to the
     * 	replacement.)
     *
     * quoted: when type is 'unparsed_equals' or 'parsed_equals' only,
     * 	may be not set, 'optional', or 'required' corresponding to if
     * 	the content may be quoted.  This allows the parser to read
     * 	[tag="abc]def[esdf]"] properly.
     *
     * require_parents: an array of tag names, or not set.  If set, the
     * 	enclosing tag *must* be one of the listed tags, or parsing won't
     * 	occur.
     *
     * require_children: similar to require_parents, if set children
     * 	won't be parsed if they are not in the list.
     *
     * disallow_children: similar to, but very different from,
     * 	require_children, if it is set the listed tags will not be
     * 	parsed inside the tag.
     *
     * parsed_tags_allowed: an array restricting what BBC can be in the
     * 	parsed_equals parameter, if desired.
     */

    $codes = array(
      array(
        'tag' => 'abbr',
        'type' => 'unparsed_equals',
        'before' => '<abbr title="$1">',
        'after' => '</abbr>',
        'quoted' => 'optional',
        'disabled_after' => ' ($1)',
      ),
      array(
        'tag' => 'acronym',
        'type' => 'unparsed_equals',
        'before' => '<acronym title="$1">',
        'after' => '</acronym>',
        'quoted' => 'optional',
        'disabled_after' => ' ($1)',
      ),
      array(
        'tag' => 'anchor',
        'type' => 'unparsed_equals',
        'test' => '[#]?([A-Za-z][A-Za-z0-9_\-]*)\]',
        'before' => '<span id="post_$1">',
        'after' => '</span>',
      ),
      array(
        'tag' => 'b',
        'before' => '<strong>',
        'after' => '</strong>',
      ),
      array(
        'tag' => 'bdo',
        'type' => 'unparsed_equals',
        'before' => '<bdo dir="$1">',
        'after' => '</bdo>',
        'test' => '(rtl|ltr)\]',
        'block_level' => true,
      ),
      array(
        'tag' => 'black',
        'before' => '<span style="color: black;" class="bbc_color">',
        'after' => '</span>',
      ),
      array(
        'tag' => 'blue',
        'before' => '<span style="color: blue;" class="bbc_color">',
        'after' => '</span>',
      ),
      array(
        'tag' => 'br',
        'type' => 'closed',
        'content' => '<br />',
      ),
      array(
        'tag' => 'center',
        'before' => '<div align="center">',
        'after' => '</div>',
        'block_level' => true,
      ),
      array(
        'tag' => 'code',
        'type' => 'unparsed_content',
        'content' => '<div class="code" id="code">$1</div>',
        // !!! Maybe this can be simplified?
        'validate' => isset($disabled['code']) ? null : function (&$tag, &$data, $disabled) use ($context) {
          if (!isset($disabled['code'])) {
            $php_parts = preg_split('~(&lt;\?php|\?&gt;)~', $data, -1, PREG_SPLIT_DELIM_CAPTURE);

            for ($php_i = 0, $php_n = count($php_parts); $php_i < $php_n; $php_i++) {
              // Do PHP code coloring?
              if ($php_parts[$php_i] != '&lt;?php')
                continue;

              $php_string = '';
              while ($php_i + 1 < count($php_parts) && $php_parts[$php_i] != '?&gt;') {
                $php_string .= $php_parts[$php_i];
                $php_parts[$php_i++] = '';
              }
              $php_parts[$php_i] = highlight_php_code($php_string . $php_parts[$php_i]);
            }

            // Fix the PHP code stuff...
            $data = str_replace("<pre style=\"display: inline;\">\t</pre>", "\t", implode('', $php_parts));
            $data = str_replace("\t", "<span style=\"white-space: pre;\">\t</span>", $data);

            // Recent Opera bug requiring temporary fix. &nsbp; is needed before </code> to avoid broken selection.
            if (!empty($context['browser']['is_opera']))
              $data .= '&nbsp;';
          }
        },
        'block_level' => true,
      ),
      array(
        'tag' => 'code',
        'type' => 'unparsed_equals_content',
        'content' => '<div class="code" id="code">$1</div>',
        // !!! Maybe this can be simplified?
        'validate' => isset($disabled['code']) ? null : function (&$tag, &$data, $disabled) use ($context) {
          if (!isset($disabled['code'])) {
            $php_parts = preg_split('~(&lt;\?php|\?&gt;)~', $data[0], -1, PREG_SPLIT_DELIM_CAPTURE);

            for ($php_i = 0, $php_n = count($php_parts); $php_i < $php_n; $php_i++) {
              // Do PHP code coloring?
              if ($php_parts[$php_i] != '&lt;?php')
                continue;

              $php_string = '';
              while ($php_i + 1 < count($php_parts) && $php_parts[$php_i] != '?&gt;') {
                $php_string .= $php_parts[$php_i];
                $php_parts[$php_i++] = '';
              }
              $php_parts[$php_i] = highlight_php_code($php_string . $php_parts[$php_i]);
            }

            // Fix the PHP code stuff...
            $data[0] = str_replace("<pre style=\"display: inline;\">\t</pre>", "\t", implode('', $php_parts));
            $data[0] = str_replace("\t", "<span style=\"white-space: pre;\">\t</span>", $data[0]);

            // Recent Opera bug requiring temporary fix. &nsbp; is needed before </code> to avoid broken selection.
            if (!empty($context['browser']['is_opera']))
              $data[0] .= '&nbsp;';
          }
        },
        'block_level' => true,
      ),
      array(
        'tag' => 'color',
        'type' => 'unparsed_equals',
        'test' => '(#[\da-fA-F]{3}|#[\da-fA-F]{6}|[A-Za-z]{1,20}|rgb\(\d{1,3}, ?\d{1,3}, ?\d{1,3}\))\]',
        'before' => '<span style="color: $1;" class="bbc_color">',
        'after' => '</span>',
      ),
      array(
        'tag' => 'email',
        'type' => 'unparsed_content',
        'content' => '<a href="mailto:$1" class="bbc_email">$1</a>',
        // !!! Should this respect guest_hideContacts?
        'validate' => function (&$tag, &$data, $disabled) {
          $data = strtr($data, array('<br>' => ''));
        },
      ),
      array(
        'tag' => 'email',
        'type' => 'unparsed_equals',
        'before' => '<a href="mailto:$1" class="bbc_email">',
        'after' => '</a>',
        // !!! Should this respect guest_hideContacts?
        'disallow_children' => array('email', 'ftp', 'url', 'iurl'),
        'disabled_after' => ' ($1)',
      ),

      /*
       * array(
       * 	'tag' => 'flash',
       * 	'type' => 'unparsed_commas_content',
       * 	'test' => '\d+,\d+\]',
       * 	'content' => ($context['browser']['is_ie'] && !$context['browser']['is_mac_ie'] ? '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="$2" height="$3"><param name="movie" value="$1" /><param name="play" value="true" /><param name="loop" value="true" /><param name="quality" value="high" /><param name="AllowScriptAccess" value="never" /><embed src="$1" width="$2" height="$3" play="true" loop="true" quality="high" AllowScriptAccess="never" /><noembed><a href="$1" target="_blank" rel="noopener noreferrer" class="bbc_link bbc_flash_disabled new_win">$1</a></noembed></object>' : '<embed type="application/x-shockwave-flash" src="$1" width="$2" height="$3" play="true" loop="true" quality="high" AllowScriptAccess="never" /noembed><a href="$1" target="_blank" rel="noopener noreferrer" class="bbc_link bbc_flash_disabled new_win">$1</a></noembed>'),
       * 	'validate' => function(&$tag, &$data, $disabled)
       * 	{
       * 		if (isset($disabled['url']))
       * 			$tag['content'] = '$1';
       * 		elseif (strpos($data[0], 'http://') !== 0 && strpos($data[0], 'https://') !== 0)
       * 			$data[0] = 'http://' . $data[0];
       * 	},
       * 	'disabled_content' => '<a href="$1" target="_blank" rel="noopener noreferrer" class="bbc_link bbc_flash_disabled new_win">$1</a>',
       * ),
       */
      array(
        'tag' => 'font',
        'type' => 'unparsed_equals',
        'test' => '[A-Za-z0-9_,\-\s]+?\]',
        'before' => '<span style="font-family: $1;" class="bbc_font">',
        'after' => '</span>',
      ),
      array(
        'tag' => 'ftp',
        'type' => 'unparsed_content',
        'content' => '<a href="$1" class="bbc_ftp new_win" target="_blank" rel="noopener noreferrer">$1</a>',
        'validate' => function (&$tag, &$data, $disabled) {
          $data = strtr($data, array('<br />' => ''));
          if (strpos($data, 'ftp://') !== 0 && strpos($data, 'ftps://') !== 0)
            $data = 'ftp://' . $data;
        },
      ),
      array(
        'tag' => 'ftp',
        'type' => 'unparsed_equals',
        'before' => '<a href="$1" class="bbc_ftp new_win" target="_blank" rel="noopener noreferrer">',
        'after' => '</a>',
        'validate' => function (&$tag, &$data, $disabled) {
          if (strpos($data, 'ftp://') !== 0 && strpos($data, 'ftps://') !== 0)
            $data = 'ftp://' . $data;
        },
        'disallow_children' => array('email', 'ftp', 'url', 'iurl'),
        'disabled_after' => ' ($1)',
      ),

      /*
       * array(
       * 	'tag' => 'glow',
       * 	'type' => 'unparsed_commas',
       * 	'test' => '[#0-9a-zA-Z\-]{3,12},([012]\d{1,2}|\d{1,2})(,[^]]+)?\]',
       * 	'before' => $context['browser']['is_ie'] ? '<table border="0" cellpadding="0" cellspacing="0" style="display: inline; vertical-align: middle; font: inherit;"><tr><td style="filter: Glow(color=$1, strength=$2); font: inherit;">' : '<span style="text-shadow: $1 1px 1px 1px">',
       * 	'after' => $context['browser']['is_ie'] ? '</td></tr></table> ' : '</span>',
       * ),
       */
      array(
        'tag' => 'green',
        'before' => '<span style="color: green;" class="bbc_color">',
        'after' => '</span>',
      ),
      array(
        'tag' => 'html',
        'type' => 'unparsed_content',
        'content' => '$1',
        'block_level' => true,
        'disabled_content' => '$1',
      ),
      array(
        'tag' => 'hr',
        'type' => 'closed',
        'content' => '<div class="hrs"></div>',
        'block_level' => true,
      ),
      array(
        'tag' => 'i',
        'before' => '<em>',
        'after' => '</em>',
      ),
      array(
        'tag' => 'img',
        'type' => 'unparsed_content',
        'parameters' => array(
          'alt' => array('optional' => true),
          'width' => array('optional' => true, 'value' => ' width="$1"', 'match' => '(\d+)'),
          'height' => array('optional' => true, 'value' => ' height="$1"', 'match' => '(\d+)'),
        ),
        'content' => '<img src="$1" alt="{alt}"{width}{height} class="bbc_img resized" />',
        'validate' => function (&$tag, &$data, $disabled) {
          $data = strtr($data, array('<br>' => ''));
          if (strpos($data, 'http://') !== 0 && strpos($data, 'https://') !== 0)
            $data = 'http://' . $data;

          $data = get_proxied_url($data);
        },
        'disabled_content' => '($1)',
      ),
      array(
        'tag' => 'img',
        'type' => 'unparsed_content',
        'content' => '<img src="$1" alt="" class="bbc_img" />',
        'validate' => function (&$tag, &$data, $disabled) {
          $data = strtr($data, array('<br>' => ''));
          if (strpos($data, 'http://') !== 0 && strpos($data, 'https://') !== 0)
            $data = 'http://' . $data;

          $data = get_proxied_url($data);
        },
        'disabled_content' => '($1)',
      ),
      array(
        'tag' => 'iurl',
        'type' => 'unparsed_content',
        'content' => '<a href="$1" class="bbc_link">$1</a>',
        'validate' => function (&$tag, &$data, $disabled) {
          $data = strtr($data, array('<br />' => ''));
          if (strpos($data, 'http://') !== 0 && strpos($data, 'https://') !== 0)
            $data = 'http://' . $data;
        },
      ),
      array(
        'tag' => 'iurl',
        'type' => 'unparsed_equals',
        'before' => '<a href="$1" class="bbc_link">',
        'after' => '</a>',
        'validate' => function (&$tag, &$data, $disabled) {
          if (substr($data, 0, 1) == '#')
            $data = '#post_' . substr($data, 1);
          elseif (strpos($data, 'http://') !== 0 && strpos($data, 'https://') !== 0)
            $data = 'http://' . $data;
        },
        'disallow_children' => array('email', 'ftp', 'url', 'iurl'),
        'disabled_after' => ' ($1)',
      ),
      array(
        'tag' => 'left',
        'before' => '<div style="text-align: left;">',
        'after' => '</div>',
        'block_level' => true,
      ),
      array(
        'tag' => 'li',
        'before' => '<li>',
        'after' => '</li>',
        'trim' => 'outside',
        'require_parents' => array('list'),
        'block_level' => true,
        'disabled_before' => '',
        'disabled_after' => '<br />',
      ),
      array(
        'tag' => 'list',
        'before' => '<ul class="bbc_list">',
        'after' => '</ul>',
        'trim' => 'inside',
        'require_children' => array('li', 'list'),
        'block_level' => true,
      ),
      array(
        'tag' => 'list',
        'parameters' => array(
          'type' => array('match' => '(none|disc|circle|square|decimal|decimal-leading-zero|lower-roman|upper-roman|lower-alpha|upper-alpha|lower-greek|lower-latin|upper-latin|hebrew|armenian|georgian|cjk-ideographic|hiragana|katakana|hiragana-iroha|katakana-iroha)'),
        ),
        'before' => '<ul class="bbc_list" style="list-style-type: {type};">',
        'after' => '</ul>',
        'trim' => 'inside',
        'require_children' => array('li'),
        'block_level' => true,
      ),
      array(
        'tag' => 'ltr',
        'before' => '<div dir="ltr">',
        'after' => '</div>',
        'block_level' => true,
      ),
      array(
        'tag' => 'me',
        'type' => 'unparsed_equals',
        'before' => '<div class="meaction">* $1 ',
        'after' => '</div>',
        'quoted' => 'optional',
        'block_level' => true,
        'disabled_before' => '/me ',
        'disabled_after' => '<br />',
      ),
      array(
        'tag' => 'move',
        'before' => '<marquee>',
        'after' => '</marquee>',
        'block_level' => true,
        'disallow_children' => array('move'),
      ),
      array(
        'tag' => 'nobbc',
        'type' => 'unparsed_content',
        'content' => '$1',
      ),
      array(
        'tag' => 'php',
        'type' => 'unparsed_content',
        'content' => '<span class="phpcode">$1</span>',
        'validate' => isset($disabled['php']) ? null : function (&$tag, &$data, $disabled) {
          if (!isset($disabled['php'])) {
            $add_begin = substr(trim($data), 0, 5) != '&lt;?';
            $data = highlight_php_code($add_begin ? '&lt;?php ' . $data . '?&gt;' : $data);
            if ($add_begin)
              $data = preg_replace(array('~^(.+?)&lt;\?.{0,40}?php(?:&nbsp;|\s)~', '~\?&gt;((?:</(font|span)>)*)$~'), '$1', $data, 2);
          }
        },
        'block_level' => false,
        'disabled_content' => '$1',
      ),
      array(
        'tag' => 'pre',
        'before' => '<pre>',
        'after' => '</pre>',
      ),
      array(
        'tag' => 'quote',
        'before' => '<blockquote><div class="cita">' . $txt['smf240'] . ': </div><div class="citacuerpo">',
        'after' => '</div></blockquote>',
        'block_level' => true,
      ),
      array(
        'tag' => 'quote',
        'parameters' => array(
          'author' => array('match' => '(.{1,192}?)', 'quoted' => true, 'validate' => 'parse_bbc'),
        ),
        'before' => '<blockquote><div class="cita">' . $txt['smf240'] . ' {author}: </div><div class="citacuerpo">',
        'after' => '</div></blockquote>',
        'block_level' => true,
      ),
      array(
        'tag' => 'quote',
        'type' => 'parsed_equals',
        'before' => '<blockquote><div class="cita">' . $txt['smf240'] . ' $1: </div><div class="citacuerpo">',
        'after' => '</div></blockquote>',
        'quoted' => 'optional',
        // Don't allow everything to be embedded with the author name.
        'parsed_tags_allowed' => array('url', 'iurl', 'ftp'),
        'block_level' => true,
      ),
      array(
        'tag' => 'red',
        'before' => '<span style="color: red;" class="bbc_color">',
        'after' => '</span>',
      ),
      array(
        'tag' => 'right',
        'before' => '<div style="text-align: right;">',
        'after' => '</div>',
        'block_level' => true,
      ),
      array(
        'tag' => 'rtl',
        'before' => '<div dir="rtl">',
        'after' => '</div>',
        'block_level' => true,
      ),
      array(
        'tag' => 's',
        'before' => '<del>',
        'after' => '</del>',
      ),

      /*
       * array(
       * 	'tag' => 'shadow',
       * 	'type' => 'unparsed_commas',
       * 	'test' => '[#0-9a-zA-Z\-]{3,12},(left|right|top|bottom|[0123]\d{0,2})\]',
       * 	'before' => $context['browser']['is_ie'] ? '<span style="display: inline-block; filter: Shadow(color=$1, direction=$2); height: 1.2em;">' : '<span style="text-shadow: $1 $2">',
       * 	'after' => '</span>',
       * 	'validate' => $context['browser']['is_ie'] ? function(&$tag, &$data, $disabled)
       * 	{
       * 		if ($data[1] == 'left')
       * 			$data[1] = 270;
       * 		elseif ($data[1] == 'right')
       * 			$data[1] = 90;
       * 		elseif ($data[1] == 'top')
       * 			$data[1] = 0;
       * 		elseif ($data[1] == 'bottom')
       * 			$data[1] = 180;
       * 		else
       * 			$data[1] = (int) $data[1];
       * 	} : function(&$tag, &$data, $disabled)
       * 	{
       * 		if ($data[1] == 'top' || (is_numeric($data[1]) && $data[1] < 50))
       * 			$data[1] = '0 -2px 1px';
       * 		elseif ($data[1] == 'right' || (is_numeric($data[1]) && $data[1] < 100))
       * 			$data[1] = '2px 0 1px';
       * 		elseif ($data[1] == 'bottom' || (is_numeric($data[1]) && $data[1] < 190))
       * 			$data[1] = '0 2px 1px';
       * 		elseif ($data[1] == 'left' || (is_numeric($data[1]) && $data[1] < 280))
       * 			$data[1] = '-2px 0 1px';
       * 		else
       * 			$data[1] = '1px 1px 1px';
       * 	},
       * ),
       */
      array(
        'tag' => 'size',
        'type' => 'unparsed_equals',
        'test' => '([1-9][\d]?p[xt]|small(?:er)?|large[r]?|x[x]?-(?:small|large)|medium|(0\.[1-9]|[1-9](\.[\d][\d]?)?)?em)\]',
        'before' => '<span style="font-size: $1;" class="bbc_size">',
        'after' => '</span>',
      ),
      array(
        'tag' => 'size',
        'type' => 'unparsed_equals',
        'test' => '[1-7]\]',
        'before' => '<span style="font-size: $1;" class="bbc_size">',
        'after' => '</span>',
        'validate' => function (&$tag, &$data, $disabled) {
          $sizes = array(1 => 0.7, 2 => 1.0, 3 => 1.35, 4 => 1.45, 5 => 2.0, 6 => 2.65, 7 => 3.95);
          $data = $sizes[$data] . 'em';
        },
      ),
      array(
        'tag' => 'sub',
        'before' => '<sub>',
        'after' => '</sub>',
      ),
      array(
        'tag' => 'sup',
        'before' => '<sup>',
        'after' => '</sup>',
      ),
      array(
        'tag' => 'table',
        'before' => '<table class="bbc_table">',
        'after' => '</table>',
        'trim' => 'inside',
        'require_children' => array('tr'),
        'block_level' => true,
      ),
      array(
        'tag' => 'td',
        'before' => '<td>',
        'after' => '</td>',
        'require_parents' => array('tr'),
        'trim' => 'outside',
        'block_level' => true,
        'disabled_before' => '',
        'disabled_after' => '',
      ),
      array(
        'tag' => 'time',
        'type' => 'unparsed_content',
        'content' => '$1',
        'validate' => function (&$tag, &$data, $disabled) {
          if (is_numeric($data))
            $data = timeformat($data);
          else
            $tag['content'] = '[time]$1[/time]';
        },
      ),
      array(
        'tag' => 'tr',
        'before' => '<tr>',
        'after' => '</tr>',
        'require_parents' => array('table'),
        'require_children' => array('td'),
        'trim' => 'both',
        'block_level' => true,
        'disabled_before' => '',
        'disabled_after' => '',
      ),
      array(
        'tag' => 'tt',
        'before' => '<tt class="bbc_tt">',
        'after' => '</tt>',
      ),
      array(
        'tag' => 'u',
        'before' => '<span class="bbc_u">',
        'after' => '</span>',
      ),
      array(
        'tag' => 'url',
        'type' => 'unparsed_content',
        'content' => '<a href="$1" class="bbc_link" target="_blank" rel="noopener noreferrer">$1</a>',
        'validate' => function (&$tag, &$data, $disabled) {
          if (strpos($data, 'http://') !== 0 && strpos($data, 'https://') !== 0)
            $data = 'http://' . $data;
        },
      ),
      array(
        'tag' => 'url',
        'type' => 'unparsed_equals',
        'before' => '<a href="$1" class="bbc_link" target="_blank" rel="noopener noreferrer">',
        'after' => '</a>',
        'validate' => function (&$tag, &$data, $disabled) {
          if (strpos($data, 'http://') !== 0 && strpos($data, 'https://') !== 0)
            $data = 'http://' . $data;
        },
        'disallow_children' => array('email', 'ftp', 'url', 'iurl'),
        'disabled_after' => ' ($1)',
      ),
      array(
        'tag' => 'white',
        'before' => '<span style="color: white;" class="bbc_color">',
        'after' => '</span>',
      ),
    );

    // Let mods add new BBC without hassle.
    call_integration_hook('integrate_bbc_codes', array(&$codes));

    // This is mainly for the bbc manager, so it's easy to add tags above.  Custom BBC should be added above this line.
    if ($message === false) {
      if (isset($temp_bbc))
        $bbc_codes = $temp_bbc;
      return $codes;
    }

    // So the parser won't skip them.
    $itemcodes = array(
      '*' => 'disc',
      '@' => 'disc',
      '+' => 'square',
      'x' => 'square',
      '#' => 'square',
      'o' => 'circle',
      'O' => 'circle',
      '0' => 'circle',
    );
    if (!isset($disabled['li']) && !isset($disabled['list'])) {
      foreach ($itemcodes as $c => $dummy)
        $bbc_codes[$c] = array();
    }

    // Inside these tags autolink is not recommendable.
    $no_autolink_tags = array(
      'url',
      'iurl',
      'ftp',
      'email',
    );

    // Shhhh!
    if (!isset($disabled['color'])) {
      $codes[] = array(
        'tag' => 'chrissy',
        'before' => '<span style="color: #cc0099;">',
        'after' => ' :-*</span>',
      );
      $codes[] = array(
        'tag' => 'kissy',
        'before' => '<span style="color: #cc0099;">',
        'after' => ' :-*</span>',
      );
    }

    foreach ($codes as $code) {
      // If we are not doing every tag only do ones we are interested in.
      if (empty($parse_tags) || in_array($code['tag'], $parse_tags))
        $bbc_codes[substr($code['tag'], 0, 1)][] = $code;
    }
    $codes = null;
  }

  // Shall we take the time to cache this?
  if ($cache_id != '' && !empty($modSettings['cache_enable']) && (($modSettings['cache_enable'] >= 2 && strlen($message) > 1000) || strlen($message) > 2400) && empty($parse_tags)) {
    // It's likely this will change if the message is modified.
    $cache_key = 'parse:' . $cache_id . '-' . md5(md5($message) . '-' . $smileys . (empty($disabled) ? '' : implode(',', array_keys($disabled))) . serialize($context['browser']) . $txt['lang_locale'] . $user_info['time_offset'] . $user_info['time_format']);

    if (($temp = cache_get_data($cache_key, 240)) != null)
      return $temp;

    $cache_t = microtime();
  }

  if ($smileys === 'print') {
    // [glow], [shadow], and [move] can't really be printed.
    $disabled['glow'] = true;
    $disabled['shadow'] = true;
    $disabled['move'] = true;

    // Colors can't well be displayed... supposed to be black and white.
    $disabled['color'] = true;
    $disabled['black'] = true;
    $disabled['blue'] = true;
    $disabled['white'] = true;
    $disabled['red'] = true;
    $disabled['green'] = true;
    $disabled['me'] = true;

    // Color coding doesn't make sense.
    $disabled['php'] = true;

    // Links are useless on paper... just show the link.
    $disabled['ftp'] = true;
    $disabled['url'] = true;
    $disabled['iurl'] = true;
    $disabled['email'] = true;
    $disabled['flash'] = true;

    // !!! Change maybe?
    if (!isset($_GET['images']))
      $disabled['img'] = true;

    // !!! Interface/setting to add more?
  }

  $open_tags = array();
  $message = strtr($message, array("\n" => '<br />'));

  // The non-breaking-space looks a bit different each time.
  $non_breaking_space = $context['utf8'] ? ($context['server']['complex_preg_chars'] ? '\x{A0}' : "\u{00A0}") : '\xA0';

  // This saves time by doing our break long words checks here.
  if (!empty($modSettings['fixLongWords']) && $modSettings['fixLongWords'] > 5) {
    if ($context['browser']['is_gecko'] || $context['browser']['is_konqueror'])
      $breaker = '<span style="margin: 0 -0.5ex 0 0;"> </span>';
    // Opera...
    elseif ($context['browser']['is_opera'])
      $breaker = '<span style="margin: 0 -0.65ex 0 -1px;"> </span>';
    // Internet Explorer...
    else
      $breaker = '<span style="width: 0; margin: 0 -0.6ex 0 -1px;"> </span>';

    // PCRE will not be happy if we don't give it a short.
    $modSettings['fixLongWords'] = (int) min(65535, $modSettings['fixLongWords']);
  }

  $pos = -1;
  while ($pos !== false) {
    $last_pos = isset($last_pos) ? max($pos, $last_pos) : $pos;
    $pos = strpos($message, '[', $pos + 1);

    // Failsafe.
    if ($pos === false || $last_pos > $pos)
      $pos = strlen($message) + 1;

    // Can't have a one letter smiley, URL, or email! (sorry.)
    if ($last_pos < $pos - 1) {
      // Make sure the $last_pos is not negative.
      $last_pos = max($last_pos, 0);

      // Pick a block of data to do some raw fixing on.
      $data = substr($message, $last_pos, $pos - $last_pos);

      // Take care of some HTML!
      if (!empty($modSettings['enablePostHTML']) && strpos($data, '&lt;') !== false) {
        $data = preg_replace('~&lt;a\s+href=((?:&quot;)?)((?:https?://|ftps?://|mailto:)\S+?)\1&gt;~i', '[url=$2]', $data);
        $data = preg_replace('~&lt;/a&gt;~i', '[/url]', $data);

        // <br /> should be empty.
        $empty_tags = array('br', 'hr');
        foreach ($empty_tags as $tag)
          $data = str_replace(array('&lt;' . $tag . '&gt;', '&lt;' . $tag . '/&gt;', '&lt;' . $tag . ' /&gt;'), '[' . $tag . ' /]', $data);

        // b, u, i, s, pre... basic tags.
        $closable_tags = array('b', 'u', 'i', 's', 'em', 'ins', 'del', 'pre', 'blockquote');
        foreach ($closable_tags as $tag) {
          $diff = substr_count($data, '&lt;' . $tag . '&gt;') - substr_count($data, '&lt;/' . $tag . '&gt;');
          $data = strtr($data, array('&lt;' . $tag . '&gt;' => '<' . $tag . '>', '&lt;/' . $tag . '&gt;' => '</' . $tag . '>'));

          if ($diff > 0)
            $data = substr($data, 0, -1) . str_repeat('</' . $tag . '>', $diff) . substr($data, -1);
        }

        // Do <img ... /> - with security... action= -> action-.
        preg_match_all('~&lt;img\s+src=((?:&quot;)?)((?:https?://|ftps?://)\S+?)\1(?:\s+alt=(&quot;.*?&quot;|\S*?))?(?:\s?/)?&gt;~i', $data, $matches, PREG_PATTERN_ORDER);
        if (!empty($matches[0])) {
          $replaces = array();
          foreach ($matches[2] as $match => $imgtag) {
            $alt = empty($matches[3][$match]) ? '' : ' alt=' . preg_replace('~^&quot;|&quot;$~', '', $matches[3][$match]);

            // Remove action= from the URL - no funny business, now.
            if (preg_match('~action(=|%3d)(?!dlattach)~i', $imgtag) != 0)
              $imgtag = preg_replace('~action(?:=|%3d)(?!dlattach)~i', 'action-', $imgtag);

            // Check if the image is larger than allowed.
            if (!empty($modSettings['max_image_width']) && !empty($modSettings['max_image_height'])) {
              list($width, $height) = url_image_size($imgtag);

              if (!empty($modSettings['max_image_width']) && $width > $modSettings['max_image_width']) {
                $height = (int) (($modSettings['max_image_width'] * $height) / $width);
                $width = $modSettings['max_image_width'];
              }

              if (!empty($modSettings['max_image_height']) && $height > $modSettings['max_image_height']) {
                $width = (int) (($modSettings['max_image_height'] * $width) / $height);
                $height = $modSettings['max_image_height'];
              }

              // Set the new image tag.
              $replaces[$matches[0][$match]] = '[img width=' . $width . ' height=' . $height . $alt . ']' . $imgtag . '[/img]';
            }
            else
              $replaces[$matches[0][$match]] = '[img' . $alt . ']' . $imgtag . '[/img]';
          }

          $data = strtr($data, $replaces);
        }
      }

      if (!empty($modSettings['autoLinkUrls'])) {
        // Are we inside tags that should be auto linked?
        $no_autolink_area = false;
        if (!empty($open_tags)) {
          foreach ($open_tags as $open_tag)
            if (in_array($open_tag['tag'], $no_autolink_tags))
              $no_autolink_area = true;
        }

        // Don't go backwards.
        // !!! Don't think is the real solution....
        $lastAutoPos = isset($lastAutoPos) ? $lastAutoPos : 0;
        if ($pos < $lastAutoPos)
          $no_autolink_area = true;
        $lastAutoPos = $pos;

        if (!$no_autolink_area) {
          // Parse any URLs.... have to get rid of the @ problems some things cause... stupid email addresses.
          if (!isset($disabled['url']) && (strpos($data, '://') !== false || strpos($data, 'www.') !== false) && strpos($data, '[url') === false) {
            // Switch out quotes really quick because they can cause problems.
            $data = strtr($data, array('&#039;' => "'", '&nbsp;' => $context['utf8'] ? "\u{00A0}" : "\xa0", '&quot;' => '>">', '"' => '<"<', '&lt;' => '<lt<'));

            // Only do this if the preg survives.
            if (is_string($result = preg_replace(array(
              "~(?<=[\s>\.(;'\"]|^)((?:http|https)://[\w\-_%@:|]+(?:\.[\w\-_%]+)*(?::\d+)?(?:/[\w\-_\~%\.@!,\?&;=#(){}+:'\\\\]*)*[/\w\-_\~%@\?;=#}\\\\])~i",
              "~(?<=[\s>\.(;'\"]|^)((?:ftp|ftps)://[\w\-_%@:|]+(?:\.[\w\-_%]+)*(?::\d+)?(?:/[\w\-_\~%\.@,\?&;=#(){}+:'\\\\]*)*[/\w\-_\~%@\?;=#}\\\\])~i",
              "~(?<=[\s>('<]|^)(www(?:\.[\w\-_]+)+(?::\d+)?(?:/[\w\-_\~%\.@!,\?&;=#(){}+:'\\\\]*)*[/\w\-_\~%@\?;=#}\\\\])~i"
            ), array(
              '[url]$1[/url]',
              '[ftp]$1[/ftp]',
              '[url=http://$1]$1[/url]'
            ), $data)))
              $data = $result;

            $data = strtr($data, array("'" => '&#039;', $context['utf8'] ? "\u{00A0}" : "\xa0" => '&nbsp;', '>">' => '&quot;', '<"<' => '"', '<lt<' => '&lt;'));
          }

          // Next, emails...
          if (!isset($disabled['email']) && filter_var($data, FILTER_VALIDATE_EMAIL) !== false && strpos($data, '[email') === false) {
            $data = preg_replace('~(?<=[\?\s' . $non_breaking_space . '\[\]()*\\\\;>]|^)([\w\-\.]{1,80}@[\w\-]+\.[\w\-\.]+[\w\-])(?=[?,\s' . $non_breaking_space . '\[\]()*\\\\]|$|<br />|&nbsp;|&gt;|&lt;|&quot;|&#039;|\.(?:\.|;|&nbsp;|\s|$|<br />))~' . ($context['utf8'] ? 'u' : ''), '[email]$1[/email]', $data);
            $data = preg_replace('~(?<=<br />)([\w\-\.]{1,80}@[\w\-]+\.[\w\-\.]+[\w\-])(?=[?\.,;\s' . $non_breaking_space . '\[\]()*\\\\]|$|<br />|&nbsp;|&gt;|&lt;|&quot;|&#039;)~' . ($context['utf8'] ? 'u' : ''), '[email]$1[/email]', $data);
          }
        }
      }

      $data = strtr($data, array("\t" => '&nbsp;&nbsp;&nbsp;'));

      if (!empty($modSettings['fixLongWords']) && $modSettings['fixLongWords'] > 5) {
        // The idea is, find words xx long, and then replace them with xx + space + more.
        if ($smcFunc['strlen']($data) > $modSettings['fixLongWords']) {
          // This is done in a roundabout way because $breaker has "long words" :P.
          $data = strtr($data, array($breaker => '< >', '&nbsp;' => $context['utf8'] ? "\u{00A0}" : "\xa0"));
          $data = preg_replace_callback(
            '~(?<=[>;:!? ' . $non_breaking_space . '\]()]|^)([\w' . ($context['utf8'] ? '\pL' : '') . '\.]{' . $modSettings['fixLongWords'] . ',})~' . ($context['utf8'] ? 'u' : ''),
            'word_break__preg_callback',
            $data
          );
          $data = strtr($data, array('< >' => $breaker, $context['utf8'] ? "\u{00A0}" : "\xa0" => '&nbsp;'));
        }
      }

      // If it wasn't changed, no copying or other boring stuff has to happen!
      if ($data != substr($message, $last_pos, $pos - $last_pos)) {
        $message = substr($message, 0, $last_pos) . $data . substr($message, $pos);

        // Since we changed it, look again in case we added or removed a tag.  But we don't want to skip any.
        $old_pos = strlen($data) + $last_pos;
        $pos = strpos($message, '[', $last_pos);
        $pos = $pos === false ? $old_pos : min($pos, $old_pos);
      }
    }

    // Are we there yet?  Are we there yet?
    if ($pos >= strlen($message) - 1)
      break;

    $tags = strtolower(substr($message, $pos + 1, 1));

    if ($tags == '/' && !empty($open_tags)) {
      $pos2 = strpos($message, ']', $pos + 1);
      if ($pos2 == $pos + 2)
        continue;
      $look_for = strtolower(substr($message, $pos + 2, $pos2 - $pos - 2));

      $to_close = array();
      $block_level = null;
      do {
        $tag = array_pop($open_tags);
        if (!$tag)
          break;

        if (!empty($tag['block_level'])) {
          // Only find out if we need to.
          if ($block_level === false) {
            array_push($open_tags, $tag);
            break;
          }

          // The idea is, if we are LOOKING for a block level tag, we can close them on the way.
          if (strlen($look_for) > 0 && isset($bbc_codes[$look_for[0]])) {
            foreach ($bbc_codes[$look_for[0]] as $temp)
              if ($temp['tag'] == $look_for) {
                $block_level = !empty($temp['block_level']);
                break;
              }
          }

          if ($block_level !== true) {
            $block_level = false;
            array_push($open_tags, $tag);
            break;
          }
        }

        $to_close[] = $tag;
      } while ($tag['tag'] != $look_for);

      // Did we just eat through everything and not find it?
      if ((empty($open_tags) && (empty($tag) || $tag['tag'] != $look_for))) {
        $open_tags = $to_close;
        continue;
      } elseif (!empty($to_close) && $tag['tag'] != $look_for) {
        if ($block_level === null && isset($look_for[0], $bbc_codes[$look_for[0]])) {
          foreach ($bbc_codes[$look_for[0]] as $temp)
            if ($temp['tag'] == $look_for) {
              $block_level = !empty($temp['block_level']);
              break;
            }
        }

        // We're not looking for a block level tag (or maybe even a tag that exists...)
        if (!$block_level) {
          foreach ($to_close as $tag)
            array_push($open_tags, $tag);
          continue;
        }
      }

      foreach ($to_close as $tag) {
        $message = substr($message, 0, $pos) . "\n" . $tag['after'] . "\n" . substr($message, $pos2 + 1);
        $pos += strlen($tag['after']) + 2;
        $pos2 = $pos - 1;

        // See the comment at the end of the big loop - just eating whitespace ;).
        if (!empty($tag['block_level']) && substr($message, $pos, 6) == '<br />')
          $message = substr($message, 0, $pos) . substr($message, $pos + 6);
        if (!empty($tag['trim']) && $tag['trim'] != 'inside' && preg_match('~(<br />|&nbsp;|\s)*~', substr($message, $pos), $matches) != 0)
          $message = substr($message, 0, $pos) . substr($message, $pos + strlen($matches[0]));
      }

      if (!empty($to_close)) {
        $to_close = array();
        $pos--;
      }

      continue;
    }

    // No tags for this character, so just keep going (fastest possible course.)
    if (!isset($bbc_codes[$tags]))
      continue;

    $inside = empty($open_tags) ? null : $open_tags[count($open_tags) - 1];
    $tag = null;
    foreach ($bbc_codes[$tags] as $possible) {
      // Not a match?
      if (strtolower(substr($message, $pos + 1, strlen($possible['tag']))) != $possible['tag'])
        continue;

      $next_c = substr($message, $pos + 1 + strlen($possible['tag']), 1);

      // A test validation?
      if (isset($possible['test']) && preg_match('~^' . $possible['test'] . '~', substr($message, $pos + 1 + strlen($possible['tag']) + 1)) == 0)
        continue;
      // Do we want parameters?
      elseif (!empty($possible['parameters'])) {
        if ($next_c != ' ')
          continue;
      } elseif (isset($possible['type'])) {
        // Do we need an equal sign?
        if (in_array($possible['type'], array('unparsed_equals', 'unparsed_commas', 'unparsed_commas_content', 'unparsed_equals_content', 'parsed_equals')) && $next_c != '=')
          continue;
        // Maybe we just want a /...
        if ($possible['type'] == 'closed' && $next_c != ']' && substr($message, $pos + 1 + strlen($possible['tag']), 2) != '/]' && substr($message, $pos + 1 + strlen($possible['tag']), 3) != ' /]')
          continue;
        // An immediate ]?
        if ($possible['type'] == 'unparsed_content' && $next_c != ']')
          continue;
      }
      // No type means 'parsed_content', which demands an immediate ] without parameters!
      elseif ($next_c != ']')
        continue;

      // Check allowed tree?
      if (isset($possible['require_parents']) && ($inside === null || !in_array($inside['tag'], $possible['require_parents'])))
        continue;
      elseif (isset($inside['require_children']) && !in_array($possible['tag'], $inside['require_children']))
        continue;
      // If this is in the list of disallowed child tags, don't parse it.
      elseif (isset($inside['disallow_children']) && in_array($possible['tag'], $inside['disallow_children']))
        continue;

      $pos1 = $pos + 1 + strlen($possible['tag']) + 1;

      // Quotes can have alternate styling, we do this php-side due to all the permutations of quotes.
      if ($possible['tag'] == 'quote') {
        // Start with standard
        $quote_alt = false;
        foreach ($open_tags as $open_quote) {
          // Every parent quote this quote has flips the styling
          if ($open_quote['tag'] == 'quote')
            $quote_alt = !$quote_alt;
        }
        // Add a class to the quote to style alternating blockquotes
        $possible['before'] = strtr($possible['before'], array('<blockquote>' => '<blockquote class="bbc_' . ($quote_alt ? 'alternate' : 'standard') . '_quote">'));
      }

      // This is long, but it makes things much easier and cleaner.
      if (!empty($possible['parameters'])) {
        $preg = array();
        foreach ($possible['parameters'] as $p => $info)
          $preg[] = '(\s+' . $p . '=' . (empty($info['quoted']) ? '' : '&quot;') . (isset($info['match']) ? $info['match'] : '(.+?)') . (empty($info['quoted']) ? '' : '&quot;') . ')' . (empty($info['optional']) ? '' : '?');

        // Okay, this may look ugly and it is, but it's not going to happen much and it is the best way of allowing any order of parameters but still parsing them right.
        $match = false;
        $orders = permute($preg);
        foreach ($orders as $p)
          if (preg_match('~^' . implode('', $p) . '\]~i', substr($message, $pos1 - 1), $matches) != 0) {
            $match = true;
            break;
          }

        // Didn't match our parameter list, try the next possible.
        if (!$match)
          continue;

        $params = array();
        for ($i = 1, $n = count($matches); $i < $n; $i += 2) {
          $key = strtok(ltrim($matches[$i]), '=');
          if (isset($possible['parameters'][$key]['value']))
            $params['{' . $key . '}'] = strtr($possible['parameters'][$key]['value'], array('$1' => $matches[$i + 1]));
          elseif (isset($possible['parameters'][$key]['validate']))
            $params['{' . $key . '}'] = $possible['parameters'][$key]['validate']($matches[$i + 1]);
          else
            $params['{' . $key . '}'] = $matches[$i + 1];

          // Just to make sure: replace any $ or { so they can't interpolate wrongly.
          $params['{' . $key . '}'] = strtr($params['{' . $key . '}'], array('$' => '&#036;', '{' => '&#123;'));
        }

        foreach ($possible['parameters'] as $p => $info) {
          if (!isset($params['{' . $p . '}']))
            $params['{' . $p . '}'] = '';
        }

        $tag = $possible;

        // Put the parameters into the string.
        if (isset($tag['before']))
          $tag['before'] = strtr($tag['before'], $params);
        if (isset($tag['after']))
          $tag['after'] = strtr($tag['after'], $params);
        if (isset($tag['content']))
          $tag['content'] = strtr($tag['content'], $params);

        $pos1 += strlen($matches[0]) - 1;
      }
      else
        $tag = $possible;
      break;
    }

    // Item codes are complicated buggers... they are implicit [li]s and can make [list]s!
    if ($smileys !== false && $tag === null && isset($itemcodes[substr($message, $pos + 1, 1)]) && substr($message, $pos + 2, 1) == ']' && !isset($disabled['list']) && !isset($disabled['li'])) {
      if (substr($message, $pos + 1, 1) == '0' && !in_array(substr($message, $pos - 1, 1), array(';', ' ', "\t", '>')))
        continue;
      $tag = $itemcodes[substr($message, $pos + 1, 1)];

      // First let's set up the tree: it needs to be in a list, or after an li.
      if ($inside === null || ($inside['tag'] != 'list' && $inside['tag'] != 'li')) {
        $open_tags[] = array(
          'tag' => 'list',
          'after' => '</ul>',
          'block_level' => true,
          'require_children' => array('li'),
          'disallow_children' => isset($inside['disallow_children']) ? $inside['disallow_children'] : null,
        );
        $code = '<ul class="bbc_list">';
      }
      // We're in a list item already: another itemcode?  Close it first.
      elseif ($inside['tag'] == 'li') {
        array_pop($open_tags);
        $code = '</li>';
      }
      else
        $code = '';

      // Now we open a new tag.
      $open_tags[] = array(
        'tag' => 'li',
        'after' => '</li>',
        'trim' => 'outside',
        'block_level' => true,
        'disallow_children' => isset($inside['disallow_children']) ? $inside['disallow_children'] : null,
      );

      // First, open the tag...
      $code .= '<li' . ($tag == '' ? '' : ' type="' . $tag . '"') . '>';
      $message = substr($message, 0, $pos) . "\n" . $code . "\n" . substr($message, $pos + 3);
      $pos += strlen($code) - 1 + 2;

      // Next, find the next break (if any.)  If there's more itemcode after it, keep it going - otherwise close!
      $pos2 = strpos($message, '<br />', $pos);
      $pos3 = strpos($message, '[/', $pos);
      if ($pos2 !== false && ($pos2 <= $pos3 || $pos3 === false)) {
        preg_match('~^(<br />|&nbsp;|\s|\[)+~', substr($message, $pos2 + 6), $matches);
        $message = substr($message, 0, $pos2) . "\n" . (!empty($matches[0]) && substr($matches[0], -1) == '[' ? '[/li]' : '[/li][/list]') . "\n" . substr($message, $pos2);

        $open_tags[count($open_tags) - 2]['after'] = '</ul>';
      }
      // Tell the [list] that it needs to close specially.
      else {
        // Move the li over, because we're not sure what we'll hit.
        $open_tags[count($open_tags) - 1]['after'] = '';
        $open_tags[count($open_tags) - 2]['after'] = '</li></ul>';
      }

      continue;
    }

    // Implicitly close lists and tables if something other than what's required is in them.  This is needed for itemcode.
    if ($tag === null && $inside !== null && !empty($inside['require_children'])) {
      array_pop($open_tags);

      $message = substr($message, 0, $pos) . "\n" . $inside['after'] . "\n" . substr($message, $pos);
      $pos += strlen($inside['after']) - 1 + 2;
    }

    // No tag?  Keep looking, then.  Silly people using brackets without actual tags.
    if ($tag === null)
      continue;

    // Propagate the list to the child (so wrapping the disallowed tag won't work either.)
    if (isset($inside['disallow_children']))
      $tag['disallow_children'] = isset($tag['disallow_children']) ? array_unique(array_merge($tag['disallow_children'], $inside['disallow_children'])) : $inside['disallow_children'];

    // Is this tag disabled?
    if (isset($disabled[$tag['tag']])) {
      if (!isset($tag['disabled_before']) && !isset($tag['disabled_after']) && !isset($tag['disabled_content'])) {
        $tag['before'] = !empty($tag['block_level']) ? '<div>' : '';
        $tag['after'] = !empty($tag['block_level']) ? '</div>' : '';
        $tag['content'] = isset($tag['type']) && $tag['type'] == 'closed' ? '' : (!empty($tag['block_level']) ? '<div>$1</div>' : '$1');
      } elseif (isset($tag['disabled_before']) || isset($tag['disabled_after'])) {
        $tag['before'] = isset($tag['disabled_before']) ? $tag['disabled_before'] : (!empty($tag['block_level']) ? '<div>' : '');
        $tag['after'] = isset($tag['disabled_after']) ? $tag['disabled_after'] : (!empty($tag['block_level']) ? '</div>' : '');
      }
      else
        $tag['content'] = $tag['disabled_content'];
    }

    // The only special case is 'html', which doesn't need to close things.
    if (!empty($tag['block_level']) && $tag['tag'] != 'html' && empty($inside['block_level'])) {
      $n = count($open_tags) - 1;
      while (empty($open_tags[$n]['block_level']) && $n >= 0)
        $n--;

      // Close all the non block level tags so this tag isn't surrounded by them.
      for ($i = count($open_tags) - 1; $i > $n; $i--) {
        $message = substr($message, 0, $pos) . "\n" . $open_tags[$i]['after'] . "\n" . substr($message, $pos);
        $pos += strlen($open_tags[$i]['after']) + 2;
        $pos1 += strlen($open_tags[$i]['after']) + 2;

        // Trim or eat trailing stuff... see comment at the end of the big loop.
        if (!empty($open_tags[$i]['block_level']) && substr($message, $pos, 6) == '<br />')
          $message = substr($message, 0, $pos) . substr($message, $pos + 6);
        if (!empty($open_tags[$i]['trim']) && $tag['trim'] != 'inside' && preg_match('~(<br />|&nbsp;|\s)*~', substr($message, $pos), $matches) != 0)
          $message = substr($message, 0, $pos) . substr($message, $pos + strlen($matches[0]));

        array_pop($open_tags);
      }
    }

    // No type means 'parsed_content'.
    if (!isset($tag['type'])) {
      // !!! Check for end tag first, so people can say "I like that [i] tag"?
      $open_tags[] = $tag;
      $message = substr($message, 0, $pos) . "\n" . $tag['before'] . "\n" . substr($message, $pos1);
      $pos += strlen($tag['before']) - 1 + 2;
    }
    // Don't parse the content, just skip it.
    elseif ($tag['type'] == 'unparsed_content') {
      $pos2 = stripos($message, '[/' . substr($message, $pos + 1, strlen($tag['tag'])) . ']', $pos1);
      if ($pos2 === false)
        continue;

      $data = substr($message, $pos1, $pos2 - $pos1);

      if (!empty($tag['block_level']) && substr($data, 0, 6) == '<br />')
        $data = substr($data, 6);

      if (isset($tag['validate']))
        $tag['validate']($tag, $data, $disabled);

      $code = strtr($tag['content'], array('$1' => $data));
      $message = substr($message, 0, $pos) . "\n" . $code . "\n" . substr($message, $pos2 + 3 + strlen($tag['tag']));

      $pos += strlen($code) - 1 + 2;
      $last_pos = $pos + 1;
    }
    // Don't parse the content, just skip it.
    elseif ($tag['type'] == 'unparsed_equals_content') {
      // The value may be quoted for some tags - check.
      if (isset($tag['quoted'])) {
        $quoted = substr($message, $pos1, 6) == '&quot;';
        if ($tag['quoted'] != 'optional' && !$quoted)
          continue;

        if ($quoted)
          $pos1 += 6;
      }
      else
        $quoted = false;

      $pos2 = strpos($message, $quoted == false ? ']' : '&quot;]', $pos1);
      if ($pos2 === false)
        continue;
      $pos3 = stripos($message, '[/' . substr($message, $pos + 1, strlen($tag['tag'])) . ']', $pos2);
      if ($pos3 === false)
        continue;

      $data = array(
        substr($message, $pos2 + ($quoted == false ? 1 : 7), $pos3 - ($pos2 + ($quoted == false ? 1 : 7))),
        substr($message, $pos1, $pos2 - $pos1)
      );

      if (!empty($tag['block_level']) && substr($data[0], 0, 6) == '<br />')
        $data[0] = substr($data[0], 6);

      // Validation for my parking, please!
      if (isset($tag['validate']))
        $tag['validate']($tag, $data, $disabled);

      $code = strtr($tag['content'], array('$1' => $data[0], '$2' => $data[1]));
      $message = substr($message, 0, $pos) . "\n" . $code . "\n" . substr($message, $pos3 + 3 + strlen($tag['tag']));
      $pos += strlen($code) - 1 + 2;
    }
    // A closed tag, with no content or value.
    elseif ($tag['type'] == 'closed') {
      $pos2 = strpos($message, ']', $pos);
      $message = substr($message, 0, $pos) . "\n" . $tag['content'] . "\n" . substr($message, $pos2 + 1);
      $pos += strlen($tag['content']) - 1 + 2;
    }
    // This one is sorta ugly... :/.  Unfortunately, it's needed for flash.
    elseif ($tag['type'] == 'unparsed_commas_content') {
      $pos2 = strpos($message, ']', $pos1);
      if ($pos2 === false)
        continue;
      $pos3 = stripos($message, '[/' . substr($message, $pos + 1, strlen($tag['tag'])) . ']', $pos2);
      if ($pos3 === false)
        continue;

      // We want $1 to be the content, and the rest to be csv.
      $data = explode(',', ',' . substr($message, $pos1, $pos2 - $pos1));
      $data[0] = substr($message, $pos2 + 1, $pos3 - $pos2 - 1);

      if (isset($tag['validate']))
        $tag['validate']($tag, $data, $disabled);

      $code = $tag['content'];
      foreach ($data as $k => $d)
        $code = strtr($code, array('$' . ($k + 1) => trim($d)));
      $message = substr($message, 0, $pos) . "\n" . $code . "\n" . substr($message, $pos3 + 3 + strlen($tag['tag']));
      $pos += strlen($code) - 1 + 2;
    }
    // This has parsed content, and a csv value which is unparsed.
    elseif ($tag['type'] == 'unparsed_commas') {
      $pos2 = strpos($message, ']', $pos1);
      if ($pos2 === false)
        continue;

      $data = explode(',', substr($message, $pos1, $pos2 - $pos1));

      if (isset($tag['validate']))
        $tag['validate']($tag, $data, $disabled);

      // Fix after, for disabled code mainly.
      foreach ($data as $k => $d)
        $tag['after'] = strtr($tag['after'], array('$' . ($k + 1) => trim($d)));

      $open_tags[] = $tag;

      // Replace them out, $1, $2, $3, $4, etc.
      $code = $tag['before'];
      foreach ($data as $k => $d)
        $code = strtr($code, array('$' . ($k + 1) => trim($d)));
      $message = substr($message, 0, $pos) . "\n" . $code . "\n" . substr($message, $pos2 + 1);
      $pos += strlen($code) - 1 + 2;
    }
    // A tag set to a value, parsed or not.
    elseif ($tag['type'] == 'unparsed_equals' || $tag['type'] == 'parsed_equals') {
      // The value may be quoted for some tags - check.
      if (isset($tag['quoted'])) {
        $quoted = substr($message, $pos1, 6) == '&quot;';
        if ($tag['quoted'] != 'optional' && !$quoted)
          continue;

        if ($quoted)
          $pos1 += 6;
      }
      else
        $quoted = false;

      $pos2 = strpos($message, $quoted == false ? ']' : '&quot;]', $pos1);
      if ($pos2 === false)
        continue;

      $data = substr($message, $pos1, $pos2 - $pos1);

      // Validation for my parking, please!
      if (isset($tag['validate']))
        $tag['validate']($tag, $data, $disabled);

      // For parsed content, we must recurse to avoid security problems.
      if ($tag['type'] != 'unparsed_equals')
        $data = parse_bbc($data, !empty($tag['parsed_tags_allowed']) ? false : true, '', !empty($tag['parsed_tags_allowed']) ? $tag['parsed_tags_allowed'] : array());

      $tag['after'] = strtr($tag['after'], array('$1' => $data));

      $open_tags[] = $tag;

      $code = strtr($tag['before'], array('$1' => $data));
      $message = substr($message, 0, $pos) . "\n" . $code . "\n" . substr($message, $pos2 + ($quoted == false ? 1 : 7));
      $pos += strlen($code) - 1 + 2;
    }

    // If this is block level, eat any breaks after it.
    if (!empty($tag['block_level']) && substr($message, $pos + 1, 6) == '<br />')
      $message = substr($message, 0, $pos + 1) . substr($message, $pos + 7);

    // Are we trimming outside this tag?
    if (!empty($tag['trim']) && $tag['trim'] != 'outside' && preg_match('~(<br />|&nbsp;|\s)*~', substr($message, $pos + 1), $matches) != 0)
      $message = substr($message, 0, $pos + 1) . substr($message, $pos + 1 + strlen($matches[0]));
  }

  // Close any remaining tags.
  while ($tag = array_pop($open_tags))
    $message .= "\n" . $tag['after'] . "\n";

  // Parse the smileys within the parts where it can be done safely.
  if ($smileys === true) {
    $message_parts = explode("\n", $message);
    for ($i = 0, $n = count($message_parts); $i < $n; $i += 2)
      parsesmileys($message_parts[$i]);

    $message = implode('', $message_parts);
  }

  // No smileys, just get rid of the markers.
  else
    $message = strtr($message, array("\n" => ''));

  if (substr($message, 0, 1) == ' ')
    $message = '&nbsp;' . substr($message, 1);

  // Cleanup whitespace.
  $message = strtr($message, array('  ' => ' &nbsp;', "\r" => '', "\n" => '<br />', '<br /> ' => '<br />&nbsp;', '&#13;' => "\n"));

  // Cache the output if it took some time...
  if (isset($cache_key, $cache_t) && array_sum(explode(' ', microtime())) - array_sum(explode(' ', $cache_t)) > 0.05)
    cache_put_data($cache_key, $message, 240);

  // If this was a force parse revert if needed.
  if (!empty($parse_tags)) {
    if (empty($temp_bbc))
      $bbc_codes = array();
    else {
      $bbc_codes = $temp_bbc;
      unset($temp_bbc);
    }
  }

  return $message;
}

?>