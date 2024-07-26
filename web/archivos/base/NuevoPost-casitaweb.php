<?php
function template_main() {
  global $context, $user_settings, $txt, $db_prefix, $modSettings, $tranfer1, $boardurl;

  if ($context['allow_admin']) {
    echo '
      <script type="text/javascript" src="' . $tranfer1 . '/js/?a=3"></script>
      <style type="text/css">
        #The_colorPicker {
          position: absolute;
          width: 224px;
          padding-bottom: 1px;
          background-color: #FFF;
          border: 1px solid #317082;
        }
      </style>';
  }

  echo "<script type=\"text/javascript\">
var confirm = true;
window.onbeforeunload = confirmleave;
function confirmleave() {
    if (confirm && (\$('input[name=titulo]').val() || \$('textarea[name=contenido]').val())) return \"Este post no fue publicado y se perdera lo que hecho.\";}

function scrollUp(){ var cs = (document.documentElement && document.documentElement.scrollTop)? document.documentElement : document.body; var step = Math.ceil(cs.scrollTop / 10); scrollBy(0, (step-(step*2)));
if(cs.scrollTop>0) setTimeout('scrollUp()', 40);}

function cerrar_vprevia(){\$('#preview').fadeOut(\"slow\");}
function vprevia(titulo,contenido,tags,f) {
if(titulo == ''){ \$('#MostrarError2').show();  return false;} else \$('#MostrarError2').hide();

if(contenido == ''){ \$('#MostrarError3').show();  return false;} else \$('#MostrarError3').hide();

if(contenido.length>63206){ \$('#MostrarError3').show();  return false;} else \$('#MostrarError3').hide();

if(tags == ''){ \$('#MostrarError6').show();  return false;} else \$('#MostrarError6').hide();

var separar_tags = tags.split(\",\");

if(separar_tags.length < 4){ \$('#MostrarError8').show();  return false;} else \$('#MostrarError8').hide();

if(f.categorias.options.selectedIndex==-1 || f.categorias.options[f.categorias.options.selectedIndex].value==-1){ \$('#MostrarError7').show();  return false;} else \$('#MostrarError7').hide();

var params = 'subject=' + encodeURIComponent(titulo) + '&message=' + encodeURIComponent(contenido) + '&accion=' + encodeURIComponent(1);

\$.ajax({
            type: \"POST\",
            url: '$boardurl/web/cw-vistaPrev.php',
\t\t\tdata: params,
          success: function(h){scrollUp();
          \$('#preview').html(h);
          \$('#preview').css('display','inline');}});}


        function _capsprot(s) {
            var len = s.length, strip = s.replace(/([A-Z])+/g, '').length, strip2 = s.replace(/([a-zA-Z])+/g, '').length,
            percent = (len  - strip) / (len - strip2) * 100;
            return percent;
        }
        \$(document).ready(function(){
            \$('input[name=titulo]').keyup(function(){
                if (\$(this).val().length >= 5 && _capsprot(\$(this).val()) > 90) \$('#MostrarError1').show();
                else \$('#MostrarError1').hide();
            });
        });
</script>";

  echo '<form action="' . $boardurl . '/web/cw-PostAgregar.php" method="post" accept-charset="' . $context['character_set'] . '" name="nuevoPost" id="nuevoPost" enctype="multipart/form-data">';
  // previsualizaci�n:
  echo '<div id="preview" style="display: none; width: 922px;"></div>';
  // fin previsualizaci�n

  echo '
    <div style="margin-bottom: 8px; margin-right: 8px; float: left; width: 235px;">
      <div class="box_235">
        <div class="box_title" style="width: 233px;">
          <div class="box_txt box_235-34">
            <center>&#161;Aclaraci&oacute;n!</center>
          </div>
          <div class="box_rss">
            <img alt="" src="' . $tranfer1 . '/blank.gif" style="width: 16px; height: 16px;" border="0" />
          </div>
        </div>
        <div class="windowbg" border="0" style="width: 225px; padding: 4px; font-family: arial;">
          <center class="size12">
            En esta secci&oacute;n puedes agregar una publicaci&oacute;n para compartirla con nuestra comunidad.
            <div class="hrs"></div>
            Para que esta publicaci&oacute;n no sea borrada por el staff de la web, debe estar de acuerdo con <a href="' . $boardurl . '/protocolo/" target="_blank" title="Protocolo"><b>las normas</b></a> establecidas en la web.
            <div class="hrs"></div>
            Tambi&eacute;n debe tener en cuenta los siguientes puntos:
          </center>
          <br />
          <img src="' . $tranfer1 . '/icons/si.png" class="png" alt="" width="16px" height="16px" />
          Contenido descriptivo.
          <br />
          <img src="' . $tranfer1 . '/icons/si.png" class="png" alt="" width="16px" height="16px" />
          T&iacute;tulo descriptivo.
          <br />
          <img src="' . $tranfer1 . '/icons/si.png" class="png" alt="" width="16px" height="16px" />
          Agregar im&aacute;genes sobre el post.
          <br />
          <img src="' . $tranfer1 . '/icons/si.png" class="png" alt="" width="16px" height="16px" />
          Noticias con fuente.
          <br />
          <img src="' . $tranfer1 . '/icons/si.png" class="png" alt="" width="16px" height="16px" />
          No excederse en may&uacute;sculas.
          <br />
          <img src="' . $tranfer1 . '/icons/si.png" class="png" alt="" width="16px" height="16px" />
          No t&iacute;tulo llamativo.
          <br />
          <img src="' . $tranfer1 . '/icons/si.png" class="png" alt="" width="16px" height="16px" />
          No spam.
          <br />
          <img src="' . $tranfer1 . '/icons/si.png" class="png" alt="" width="16px" height="16px" />
          No gore o asquerosos.
          <br />
          <img src="' . $tranfer1 . '/icons/si.png" class="png" alt="" width="16px" height="16px" />
          No insultos o malos tratos.
          <br />
          <img src="' . $tranfer1 . '/icons/si.png" class="png" alt="" width="16px" height="16px" />
          No pornograf&iacute;a.
          <br />
          <br />
          <center style="font-size: 11px;">
            <img src="' . $tranfer1 . '/icons/no.png" class="png" alt="" width="16px" height="16px" />
            Cumpliendo estos puntos m&aacute;s teniendo en cuenta el <a href="' . $boardurl . '/protocolo/" target="_blank" title="Protocolo">protocolo</a>, es probable que tu post no sea eliminado ni editado.
          </center>
          <p align="right" style="margin: 0px; padding: 0px; font-size: 11px;">Muchas gracias.</p>
        </div>
      </div>
    </div>
    <div class="ed-ag-post" style="float: left; margin-bottom: 8px; width: 679px;">
    <div class="box_title" style="width: 677px;">
      <div class="box_txt ed-ag-posts">
        <center>Agregar nuevo post</center>
      </div>
      <div class="box_rss">
        <img alt="" src="' . $tranfer1 . '/blank.gif" style="width: 16px; height: 16px;" border="0" />
      </div>
    </div>
    <div class="windowbg" border="0" style="width: 669px; padding: 4px;">
      <b class="size11">', $txt[70], ':</b>
      <br />
      <input type="text" onfocus="foco(this);" onblur="no_foco(this);" name="titulo" value="" tabindex="1" style="width: 415px;" maxlength="60" />
      <div id="MostrarError1" class="capsprotBAJO" style="margin-bottom:3px;">El t&iacute;tulo no debe estar en may&uacute;sculas</div>
      <div id="MostrarError2" class="capsprotBAJO">Falta el t&iacute;tulo del post.</div>
      <br />
      <b class="size11">Mensaje del post:</b>
      <br />
      <textarea style="height: 300px; width: 663px;" id="editorCW" name="contenido" tabindex="3"></textarea>';

  if ($modSettings['smiley_enable']) {
    $existe = db_query("
      SELECT hidden, ID_SMILEY, description, filename, code
      FROM {$db_prefix}smileys
      WHERE hidden = 0
      ORDER BY ID_SMILEY ASC", __FILE__, __LINE__);

    while ($row = mysqli_fetch_assoc($existe)) {
      echo '<span style="cursor: pointer;" onclick="replaceText(\' ', $row['code'], '\', document.forms.nuevoPost.editorCW); return false;"><img src="' . $tranfer1 . '/emoticones/' . $row['filename'] . '" align="bottom" alt="' . $row['description'] . '" title="' . $row['description'] . '" /></span> ';
    }

    mysqli_free_result($existe);

    echo '<a href="javascript:moticonup()">[' . $txt['more_smileys'] . ']</a>';
  }

  echo '
    <div id="MostrarError3" class="capsprotBAJO">Hace falta que escribas el contenido del post.</div>
    <div id="MostrarError4" class="capsprotBAJO">El contenido del post no se puede pasar de los 63206 caracteres.</div>
    <br />
    <b class="size11">Tags:</b>
    <br />
    <input style="width: 415px;" maxlength="128" value="" name="tags" tabindex="4" type="text" onfocus="foco(this);" onblur="no_foco(this);" />
    <br />
    <span class="size9">
      Una lista separada por comas, que describa el contenido. Ejemplo: <b>calmaro, musico, interprete, salmon</b>
    </span>
    <div id="MostrarError6" class="capsprotBAJO">Es necesario que pongas los tags del post.</div>
    <div id="MostrarError8" class="capsprotBAJO">Es necesario que ingreses por lo menos 4 tags separados por coma.</div>
    <br />
    <b class="size11">Categor&iacute;a:</b>
    <br />';

  categorias(2);

  echo '<div id="MostrarError7" class="capsprotBAJO">Debes agregar la categr&iacute;a del post.</div>';

  if ($context['allow_admin']) {
    echo '
      <br />
      <b class="size11">Color:</b>
      <br />
      <input type="text" value="" onfocus="foco(this);" onblur="no_foco(this);" name="colorsticky" size="15" onclick="startColorPicker(this)" id="colorsticky" onkeyup="maskedHex(this)" />
      <div id="coloraca" style="display: none; margin: 0px; padding: 0px;"></div>';
  }

  echo '
    <br />
    <font class="size11">
      <b>Opciones:</b>
    </font>';

  if ($context['allow_admin']) {
    echo '
      <br />
      <label for="principal">
        <input type="checkbox" name="principal" id="principal" value="1" />
        Agregar a Sticky.
      </label>';
  }

  if ($context['user']['is_admin']) {
    echo '
      <br />
      <label for="anuncio">
        <input type="checkbox" name="anuncio" id="anuncio" value="1" />
        Mostrar post como Anuncio.
      </label>';
  }

  if ($user_settings['posts'] >= '200') {
    echo '
      <br />
      <label for="nocom">
        <input type="checkbox" name="nocom" id="nocom" value="1" />
        No permitir comentarios.
      </label>';
  }

  echo '
        <br />
        <label for="privado">
          <input type="checkbox" name="privado" id="privado" value="1" />
          S&oacute;lo usuarios registrados.
        </label>
        <center>
          <input onclick="vprevia(this.form.titulo.value, this.form.contenido.value, this.form.tags.value, this.form);" class="button" style="font-size: 15px;" value="Previsualizar" title="Previsualizar" type="button" tabindex="8" />
        </center>
        <br />
        </div>
      </div>
    </form>';
}

?>