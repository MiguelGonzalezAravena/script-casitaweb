<?php
require_once(dirname(__FILE__) . '/config-seg-16a5s4das.php');
global $context, $user_info;

echo '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="es" xml:lang="es">
<head>
  <title>' . $faqname . '</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <link rel="icon" href="' . $tranfer1 . '/favicon.ico" type="image/x-icon" />
  <link rel="alternate" type="application/atom+xml" title="' . $mbname . ' Ayuda - RSS" href="' . $helpurl . '/rss/" />
  <link rel="shortcut icon" href="' . $tranfer1 . '/favicon.ico" type="image/x-icon" />
  <link rel="stylesheet" type="text/css" href="' . $helpurl . '/imagenes/css-cw-ayuda.css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js" type="text/javascript"></script>
  <script type="text/javascript" src="' . $helpurl . '/imagenes/js.js"></script>
</head>
<body>
  <div id="header">
    <div class="fixed">
      <div class="fixedblock" align="center">
        <div class="fixedcenter">
          <ul onclick="AbrirCats();" class="language">
            <li class="cats">
              <a href="#">Ver Categor&iacute;as</a>
            </li>';

$catlist = db("
  SELECT cat, enlace
  FROM {$prefijo}cats
  WHERE maincat = 0
  ORDER BY cat DESC", __FILE__, __LINE__);

while ($rows = mysqli_fetch_assoc($catlist)) {
  echo '
    <li class="cats otherlang">
      <a href="' . $helpurl . '/categoria/' . $rows['enlace'] . '">' . $rows['cat'] . '</a>
    </li>';
}

mysqli_free_result($catlist);

echo '

          </ul>
          <ul class="servicenav">
            ' . ($user_info['is_admin'] || $user_info['is_mods'] ? '<li><a href="' . $helpurl . '/agregar/">Agregar art&iacute;culo</a></li>' : '') . '
            <li>
              <a href="' . $boardurl . '/" title="' . $mbname . '">' . $mbname . '</a>
            </li>
            <li>
              <a href="' . $helpurl . '/" title="Inicio">Inicio</a>
            </li>
            <li class="clientarea" id="areaClient">
              <span id="hdLoglink" onclick="javascript: buscar();">Buscar</span>
              <div id="hd_loginbox">
                <div class="login_cuerpo">
                  <form name="form1" style="margin: 0px; padding-top: 5px;" method="GET" action="' . $helpurl . '/resultados.php">
                    <input name="palabra"' . (!$_GET['palabra'] ? ' onfocus="if (this.value == \'Buscar...\') { this.value = \'\'; } foco(this);" onblur="if (this.value == \'\') { this.value = \'Buscar...\'; } no_foco(this);" value="Buscar..." ' : ' onfocus="foco(this);" onblur="no_foco(this);" value="' . $_GET['palabra'] . '"') . ' size="30" style="height: 11px !important; font-size: 10px;" maxlength="100" type="text" />
                  </form>
                </div>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <b class="rtop">
    <b class="rtop1">
      <b></b>
    </b>
    <b class="rtop2">
      <b></b>
    </b>
    <b class="rtop3"></b>
    <b class="rtop4"></b>
    <b class="rtop5"></b>
  </b>
  <div id="maincontainer">
    <table id="widthControl" style="background-color:#fff;" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="100%" valign="top" style="padding:0;">
          <div id="head">
            <div id="logo">
              <a href="' . $helpurl . '/" title="' . $mbname . ' - Ayuda" id="logoi">
                <img src="' . $helpurl . '/imagenes/espacio.gif" alt="' . $mbname . ' - Ayuda" title="' . $mbname . ' - Ayuda" align="top" border="0" />
              </a>
            </div>
            <div align="right" id="banner" style="padding: 25px 0px 0px 0px;"></div>
          </div>
          <div id="bodyarea" style="background: url(' . $helpurl . '/imagenes/bg-cuerpo.gif) repeat-x; padding-top: 17px;">
            <table width="921px" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td valign="top">';

?>