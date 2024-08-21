<?php

function template_init() {
  global $settings;

  $settings['use_default_images'] = 'never';
  $settings['doctype'] = 'xhtml';
  $settings['theme_version'] = '1';
  $settings['use_tabs'] = true;
  $settings['use_buttons'] = true;
  $settings['seperate_sticky_lock'] = true;
}

function template_main_above() {
  global $context, $boardurl, $txt, $modSettings, $user_info, $db_prefix, $tranfer1, $user_settings, $ie, $urlSep, $internetNO, $mbname, $ID_MEMBER, $helpurl;

  echo '
    <link rel="stylesheet" type="text/css" href="' . $tranfer1 . '/estilo.php" />
    <script type="text/javascript" src="' . $tranfer1 . '/js/index.php"></script>';

  if (isset($context['id-post']) && intval($context['id-post'])) {
    $context['page_title'] = $context['titulo'];

    echo '
      <meta property="dc:date" content="' . timeformat($context['fecha']) . '"/>
      <meta property="dc:creator" content="' . $context['posterName'] . '" />
      <link rel="canonical" href="' . $boardurl . '/post/' . $context['id-post'] . '/' . $context['link_cat'] . '/' . urls($context['titulo']) . '.html" />
      <link rel="prev" href="' . $boardurl . '/noestilo/post/' . ($context['id-post'] - 1) . '" />
      <link rel="next" href="' . $boardurl . '/noestilo/post/' . ($context['id-post'] + 1) . '" />
      <link rel="alternate" type="application/atom+xml" title="Comentarios del post" href="' . $boardurl . '/rss/post-comment/' . $context['id-post'] . '" />
      <link rel="alternate" type="application/atom+xml" title="Post del usuario" href="' . $boardurl . '/rss/post-user/' . $context['posterName'] . '" />
      <meta name="description" content="' . getMetaDescription($context['CsTNidO']) . '" />';
  } else {
    if (empty($_GET[$urlSep])) {
      echo '<meta http-equiv="refresh" content="600" />';
    }

    echo '
      <meta name="description" content="' . $mbname . ' - Un sitio de distraccion, de descargas (Musica, Juegos, Programas, Peliculas,etc,etc).. Lo interesante que aca VOS sos el protagonita el que aporta sos vos, Todo eso y mucho más..." />
      <link rel="alternate" type="application/atom+xml" title="&Uacute;ltimos posts" href="' . $boardurl . '/rss/ultimos-post" />
      <link rel="alternate" type="application/atom+xml" title="&Uacute;ltimos comentarios" href="' . $boardurl . '/rss/ultimos-comment" />';
  }

  if ($context['page_title'] == $txt[18]) {
    $titlee = $context['forum_name'] . ' - ' . $context['page_title'];
  } else {
    $titlee = $context['page_title'] . ' - ' . $context['forum_name'];
  }

  echo '
      <meta name="keywords" content="' . (isset($context['CsTNidO']) ? getMetaKeywords($context['CsTNidO']) : '') . ',' . $titlee . ',rapidshare,megaupload,mediafire,descarga,rapidshare,megaupload,mediafire,descarga-directa,bajar,mp3,casitaweb,rigo,caladj,elblogderigo,lawebderigo,elforoderigo,linksharing,enlaces,juegos,musica,links,noticias,imagenes,videos,animaciones,arte,tecnologia,celulares,argentina,comunidad,cw,infornes,2008,2009,warez,linksharing,web 2.0,directa ' . date('Y') . '" />
      <title>' . $titlee . '</title>
      <meta name="title" content="' . $titlee . '" />
      <meta name="generator" content="' . $titlee . ' / Para descargar / bajar / instalar gratis / Gratuito / rigo / casitaweb / 2.0 / linksaring / rapidshare / descargas / directas / megaupload / mediafire / software / freeware / serial / gratis / programas / musica / juegos / peliculas" />
      <link rel="search" type="application/opensearchdescription+xml" title="' . $mbname . '" href="' . $tranfer1 . '/buscador-cw.xml" />
      <link rel="up" href="#top" title="Volver al principio de esta pagina" />
      <style rel="stylesheet" type="text/css">
        #logob {
          width: 360px;
          height: 95px;
          background: url(\'' . $boardurl . '/logos/logo.gif\');
          background-repeat: no-repeat;
          float: left;
        }
      </style>
    </head>
    <body id="top">
      <div id="cargando_boxy" style="display: none;" align="center">
        <div id="cargando_ajax">Cargando...</div>
      </div>
      ' . $internetNO . '
      <div id="maincontainer">
        <div class="logon">
          <div id="logoa">
            <a href="' . $boardurl . '/" title="' . $context['forum_name'] . ' - ' . $txt[18] . '" id="logob">
              <img src="' . $tranfer1 . '/espacio.gif" width="360px" height="95px" alt="" align="top" border="0" />
            </a>
          </div>
          <div style="clear: both;"></div>
        </div>
        <div id="atri">
          <div class="fixed">
            <div style="clear: both;"></div>
            <ul>
              <li class="clientarea" id="areaClient">';

  $accioncw241 = isset($_GET[$urlSep]) ? $_GET[$urlSep] : '';
  $m = isset($_GET['m']) ? $_GET['m'] : '';

  if ($accioncw241 == 'com') {
    $jj = 2;

    $request = db_query("
      SELECT url, nombre
      FROM {$db_prefix}comunidades_categorias
      ORDER BY nombre ASC", __FILE__, __LINE__);
  } else {
    $jj = 1;
    $shas = !$context['user']['is_admin'] ? ' WHERE ID_BOARD <> 142' : '';

    $request = db_query("
      SELECT description AS url, name AS nombre
      FROM {$db_prefix}boards
      $shas", __FILE__, __LINE__);
  }

  echo '
    <span id="hdLoglink" class="hdLoglink2" onclick="javascript:AbrirCats();">
      <img src="' . $tranfer1 . '/arrow-cats.gif" width="20px" height="6px" alt="" />
      Ver categor&iacute;as
    </span>
    <div id="hd_cats" class="hd_loginbox2">';

  while ($row = mysqli_fetch_assoc($request)) {
    $ff = ($accioncw241 == 'com' ? $boardurl . '/comunidades/categoria/' : $boardurl . '/categoria/');

    echo '
      <div>
        <a href="' . $ff . $row['url'] . '">' . $row['nombre'] . '</a>
      </div>';
  }

  mysqli_free_result($request);

  echo '
        </div>
      </li>
    </ul>
    <ul class="servicenav">
      <li class="comunidadesc">
        <a href="' . $boardurl . '/comunidades/" class="comuCC">Comunidades</a>';

  $cincoMiN = time() - 600;

  $request = db_query("
    SELECT id
    FROM {$db_prefix}comunidades_articulos
    WHERE creado > " . $cincoMiN . '
    AND eliminado = 0', __FILE__, __LINE__);

  $rows = mysqli_num_rows($request);
  $plur = $rows > 0 ? 's' : '';

  echo '
      <div id="Sfvc" title="' . $rows . ' tema' . $plur . ' nuevo' . $plur . ' (&uacute;ltimos 10 minutos)">' . $rows . '</div>
    </li>';

  if (!empty($ID_MEMBER)) {
    echo '
      <li class="sn">
        <a class="publicar" href="' . $boardurl . '/agregar/">Publicar</a>
      </li>
      <li class="sn">
        <a href="' . $boardurl . '/tops/">TOPs</a>
      </li>';
  }

  echo '
    <li class="sn">
      <a href="' . $boardurl . '/chat/">Chat</a>
    </li>
    <li class="sn">
      <a href="' . $helpurl . '/" title="">Ayuda</a>
    </li>
    <li class="sn">
      <a href="' . $boardurl . '/buscador/" title="">Buscar</a>
    </li>
    <li class="sn">
      <a href="' . $boardurl . '/" title="">Inicio</a>
    </li>';

  if (empty($accioncw241) && isset($context['id-post'])) {
    $reg = '0';
  } else if (empty($accioncw241) && empty($context['id-post'])) {
    $reg = 'Home';
  } else {
    $reg = '0';
  }

  if ($accioncw241 == 'rz-seg55555658971' && $m == 'tyc2') {
    $ch = '1';
  } else {
    $ch = '0';
  }

  if (empty($ID_MEMBER)) {
    echo '
      <li>
        <a href="' . $boardurl . '/registrarse/" title="&iexcl;&iexcl;&iexcl;Reg&iacute;strate GRATIS!!!" class="registrarse">&iexcl;&iexcl;&iexcl;Reg&iacute;strate!!!</a>
      </li>
      <li class="clientarea" id="areaClient">
        <span id="hdLoglink" class="hdLoglink" onclick="javascript:servicenavlogin();">Iniciar sesi&oacute;n</span>
      </li>
      <div id="hd_loginbox">
        <div style="display: none;" id="login_cargando">
          <img alt="" src="' . $tranfer1 . '/icons/cargando.gif" width="16px" height="16px" />
        </div>
        <div style="display: none;" id="login_error"></div>
        <div class="login_cuerpo">
          <form method="post" action="javascript:login_ajax(\'' . $reg . '\')">
            <div>
              <label onclick="$(\'#nickname\').focus();" style="cursor: pointer; color: #444;">Nick:</label>
              <input maxlength="64" name="nick" id="nickname" onfocus="foco(this);" onblur="no_foco(this);" class="loginuserid" type="text" />
              <br />
              <label onclick="$(\'#password\').focus();" style="cursor: pointer; color: #444;">Contrase&ntilde;a:</label>
              <input maxlength="64" name="pass" id="password" onfocus="foco(this);" onblur="no_foco(this);" class="loginpasswd" type="password" />
            </div>
            <p>
              <input class="loginsubmit" type="submit" value="Conectarse" />
            </p>
          </form>
          <a href="' . $boardurl . '/recuperar-pass/" class="loginforgotpass">&#191;Has olvidado tu contrase&ntilde;a?</a>
          <br />
          <a href="' . $boardurl . '/registrarse/" class="loginforgotpass">&#191;Quieres tu cuenta? &iexcl;&iexcl;Reg&iacute;strate!!</a>
          <div class="clearfix"></div>
        </div>
      </div>';
  } else {
    echo '
      <li class="clientarea" id="areaClient">
        <a id="hdLoglink" href="' . $boardurl . '/perfil/' . $context['user']['name'] . '" class="logged">' . $context['user']['name'] . '</a>
      </li>';

    if (isset($user_settings['puntos_dia']) && $user_settings['puntos_dia'] > 0) {
      $first = '';

      echo '
        <li style="border-left: none;" class="puntosa">
          <a href="#" style="cursor: default;">
            <strong style="font-size: 13px; color: #0B7F00;" title="Puntos disponibles, PARA VOTAR, posts e im&aacute;genes.">+<span id="puntosDD">' . $user_settings['puntos_dia'] . '</span></strong>
          </a>
        </li>';
    } else {
      $first = 'style="border-left: none;"';
    }

    if ($user_settings['topics'] > 0) {
      echo '
        <li ' . $first . '>
          <a class="lcc2" href="' . $boardurl . '/mensajes/" title="Mensajes Privados">
            <img alt="" src="' . $tranfer1 . '/icons/mensaje_nuevo.gif" style="width: 16px; height: 15px; margin-right: 5px;" />
            <span id="quitarMP"> (<strong><span title="Sin leer" id="cantidad-MP">' . $user_settings['topics'] . '</span></strong>)</span>
          </a>
        </li>';
    } else {
      echo '
        <li ' . $first . '>
          <a class="lcc2" href="' . $boardurl . '/mensajes/" title="Mensajes Privados">
            <img alt="" src="' . $tranfer1 . '/icons/mensaje.gif" style="width: 16px; height: 15px; " />
          </a>
        </li>';
    }

    echo '<li>';

    if (!empty($ch)) {
      echo '
        <a class="lcc2" target="_blank" title="Notificaciones" href="' . $boardurl . '/notificaciones/">
          <span class="hdLoglink3">
            <img class="png" alt="" src="' . $tranfer1 . '/icons/monitor.png" style="width: 16px; height: 15px; " />
          </span>
        </a>';
    } else {
      echo '
        <a class="lcc2" title="Notificaciones" onclick="notificaciones();">
          <span class="hdLoglink3">
            <img class="png" alt="" src="' . $tranfer1 . '/icons/monitor.png" style="width: 16px; height: 15px; " />
          </span>
        </a>';
    }

    echo '
      <div id="hd_loginboxx3">
        <div id="hd_loginbox3">
          <div id="notificacionesES">
            <div style="display: none;" id="NOT_cargando">
              <center>
                <br />
                <img alt="" src="' . $tranfer1 . '/icons/cargando.gif" width="16px" height="16px" />
              </center>
            </div>
            <div id="notificaciones_cuerpo" style="display: none;"></div>
          </div>
        </div>
        <div id="hd_masNOT" align="center">
          <a href="' . $boardurl . '/notificaciones/">Ver m&aacute;s notificaciones.</a>
        </div>
      </div>';

    if (isset($user_settings['notificacionMonitor']) && $user_settings['notificacionMonitor']) {
      // TO-DO: ¿Por qué hay doble lógica?
      if ($user_settings['notificacionMonitor']) {
        $plur = 'es';
      }

      echo '<div id="Sfvc" title="' . $user_settings['notificacionMonitor'] . ' notificacion' . $plur . '" class="Sfvc22">' . $user_settings['notificacionMonitor'] . '</div>';
    }

    echo '
      </li>
      <li>
        <a class="lcc2" href="' . $boardurl . '/favoritos/" title="Mis Favoritos">
          <img class="png" alt="" src="' . $tranfer1 . '/icons/favoritos.png" style="width: 16px; height: 15px;" />
        </a>
      </li>
      <li>
        <a class="lcc2" href="' . $boardurl . '/editar-perfil/" title="Editar mi perfil">
          <img class="png" alt="" src="' . $tranfer1 . '/icons/editar-cuenta.png" style="width: 16px; height: 15px;" />
        </a>
      </li>
      <li>
        <a class="lcc" href="#" id="salir_cw">
          <strong>[x]</strong>
        </a>
      </li>
      <li>
        <a class="lcc" href="' . $boardurl . '/hist-mod/" title="Historial de moderaci&oacute;n">
          <img alt="" src="' . $tranfer1 . '/icons/hmod.png" class="png" style="width: 16px; height: 15px;" />
        </a>
      </li>';

    if ($user_settings['ID_GROUP'] == 7 || $user_settings['ID_GROUP'] == 11) {
      echo '
        <li>
          <a  class="lcc" title="Men&uacute; especial" href="' . $boardurl . '/men-especial/">
            <img class="png" alt="" src="' . $tranfer1 . '/icons/especial.png" style="width: 16px; height: 15px;" />
          </a>
        </li>';
    }

    if (($user_info['is_admin'] || $user_info['is_mods'])) {
      echo '
        <li>
          <a class="lcc" title="Modreaci&oacute;n" href="' . $boardurl . '/moderacion/">
            <img alt="" src="' . $tranfer1 . '/icons/adm.png" class="png" style="width: 16px; height: 15px;" />
          </a>
        </li>';
    }
  }

  echo '
      </ul>
      <div style="clear: both;"></div>
    </div>';

    
  if ($modSettings['news']) {
    $texto1 = $modSettings['news'];
    $paramostrar = str_replace('http://linkoculto.net/index.php?l=', '', parse_bbc(str_replace('%', '', $texto1)));
 
    echo '<div style="margin: 0px; padding: 0px;"><div id="mensaje-top">' . $paramostrar . '</div></div>';
  }
  echo '
    <div class="clearBoth"></div>
    <div id="bodyarea">
      <div id="bod">';
}

function template_main_below() {
  global $tranfer1, $context, $txt, $boardurl, $mbname;

  echo '
                <div class="clearfix"></div>
              </div>
            </div>
            <div id="pie">
              &copy;
              ' . date('Y') . '
              <a href="' . $boardurl . '/" title="' . $mbname . '">' . $mbname . '</a>
              |
              <a href="' . $boardurl . '/protocolo/" title="Protocolo">Protocolo</a>
              |
              <a href="' . $boardurl . '/enlazanos/" title="Enl&aacute;zanos">Enl&aacute;zanos</a>
              |
              <a href="' . $boardurl . '/widget/" title="Widget">Widget</a>
              |
              <a href="' . $boardurl . '/contactanos/" title="Contacto">Contacto</a>
              |
              <a href="' . $boardurl . '/recomendar/" title="Recomendar ' . $mbname . '">Recomendar ' . $mbname . '</a>
              |
              <a href="' . $boardurl . '/mapa-del-sitio/" title="Mapa del sitio">Mapa del sitio</a>
              <div style="clear:both"></div>
            </div>
          </div>
        </div>
        <span id="flotadorUP" class="png" onclick="ira_CasitaWebNET(); return false;">&uarr;</span>
      </body>
    </html>';
}

function template_menu() {}
function template_button_strip() {}
function theme_linktree2() {}
function theme_linktree3() {}
function theme_newestlink() {}
function theme_linktree() {}

?>