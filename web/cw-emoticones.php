<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $db_prefix, $tranfer1;

echo '
  <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
  <html xmlns="http://www.w3.org/1999/xhtml" lang="es" xml:lang="es">
    <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
      <title>' . $mbname . ' - Emoticones</title>
    </head>
    <body onload="javascript:resizeTo(225, 500)" style="width: 200px; font-size: 12px; background: #B3A496; font-family: Arial;">
      <div align="center">
        <table width="190px" style="border: 1px solid #584434; background: #fff;">
          <tbody>
            <tr align="center">
              <td width="40">
                <strong>Emotic&oacute;n:</strong>
              </td>
              <td width="80">
                <strong>C&oacute;digo:</strong>
              </td>
            </tr>';

$request = db_query("
  SELECT hidden, ID_SMILEY, description, code, filename
  FROM {$db_prefix}smileys
  WHERE hidden = 2
  ORDER BY ID_SMILEY ASC", __FILE__, __LINE__);

while ($row = mysqli_fetch_assoc($request)) {
  echo '
    <tr align="center">
      <td>
        <img alt="" style="border: medium none;" src="' . $tranfer1 . '/emoticones/' . $row['filename'] . '" title="' . $row['description'] . '" />
      </td>
      <td>' . $row['code'] . '</td>
    </tr>';
}

mysqli_free_result($request);

echo '
          </tbody>
        </table>
      </div>
    </body>
  </html>';

?>