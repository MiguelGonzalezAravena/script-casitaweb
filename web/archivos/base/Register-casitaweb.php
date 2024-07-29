<?php
function template_before() {
  global $context, $settings, $options, $scripturl, $txt, $modSettings, $no_avatar, $tranfer1, $boardurl, $mbname;

  $VarJS1 = '
    if (!document.forms.creator.regagree.checked) {
      $(\'#MostrarError13\').show();
      return false;
    } else {
      $(\'#MostrarError13\').hide();
    }';
  $VarJS2 = ', this.form.regagree.value';
  $VarJS3 = ', regagree';
  $VarDat = '
    <tr>
      <td align="right" width="40%"></td>
      <td>
        <label for="regagree">
          <input tabindex="15" type="checkbox" name="regagree" id="regagree" class="check" />
          Acepto los <a href="' . $boardurl . '/terminos-y-condiciones/" target="_blank">T&eacute;rminos de uso</a>.
        </label>
      </td>
    </tr>';

  $VarError = '
    <div id="MostrarError13" class="capsprot">
      Debes aceptar los <a href="' . $boardurl . '/terminos-y-condiciones/" target="_blank">T&eacute;rminos de uso</a>.
    </div>';

  echo '
    <script type="text/javascript">
      function nuevoAjax() {
        var a = false;

        try {
          a = new ActiveXObject(\'Msxml2.XMLHTTP\');
        } catch(e) {
          try {
            a = new ActiveXObject(\'Microsoft.XMLHTTP\');
          } catch(E) {
            a = false;
          }
        }

        if (!a && typeof XMLHttpRequest != \'undefined\') {
          a = new XMLHttpRequest();
        }

        return a;
      }

      function nuevoEvento(a) {
        var b = document.getElementById(\'error\');
        sconderuno = document.getElementById(\'esconderuno\');
        sconderdos = document.getElementById(\'esconderdos\');
        scondertres = document.getElementById(\'escondertres\');
        var c = document.getElementById(\'img\');

        if (a == \'verificacion\') {
          var d = document.getElementById(\'verificacion\');
          var e = d.value;
        }

        d.disabled = true;
        c.style.display = \'inline\';
        sconderuno.style.display = \'none\';
        sconderdos.style.display = \'none\';
        scondertres.style.display = \'none\';
        var f = nuevoAjax();

        f.open(\'POST\', \'' . $boardurl . '/web/cw-verificar.php?seg=001\', true);
        f.setRequestHeader(\'Content-Type\', \'application/x-www-form-urlencoded\');
        f.send(a + \'=\' + e);
        f.onreadystatechange = function() {
          if (f.readyState == 4) {
            d.disabled = false;
            c.style.display = \'none\';
            sconderuno.style.display = \'\';
            sconderdos.style.display = \'\';
            scondertres.style.display = \'\';
            b.innerHTML = f.responseText;
          }
        }
      }

      function mail(a) {
        var b = document.getElementById(\'errord\');
        sconderuno = document.getElementById(\'esconderunod\');
        sconderdos = document.getElementById(\'esconderdosd\');
        scondertres = document.getElementById(\'escondertresd\');
        var c = document.getElementById(\'imgd\');

        if (a == \'emailverificar\') {
          var d = document.getElementById(\'emailverificar\');
          var e = d.value
        }

        d.disabled = true;
        c.style.display = \'inline\';
        sconderuno.style.display = \'none\';
        sconderdos.style.display = \'none\';
        scondertres.style.display = \'none\';
        var f = nuevoAjax();

        f.open(\'POST\', \'' . $boardurl . '/web/cw-verificar.php?seg=002\', true);
        f.setRequestHeader(\'Content-Type\', \'application/x-www-form-urlencoded\');
        f.send(a + \'=\' + e);
        f.onreadystatechange = function() {
          if (f.readyState == 4) {
            d.disabled = false;
            c.style.display = \'none\';
            sconderuno.style.display = \'\';
            sconderdos.style.display = \'\';
            scondertres.style.display = \'\';
            b.innerHTML = f.responseText;
          }
        }
      }

      function showtags(nombre, user, passwrd1, passwrd2, email, f, ciudad, bday2, bday1, bday3, code' . $VarJS3 . ') {
        if (nombre == \'\') {
          $(\'#MostrarError1\').show();
          return false;
        } else {
          $(\'#MostrarError1\').hide();
        }

        if (user == \'\') {
          $(\'#MostrarError2\').show();
          return false;
        } else {
          $(\'#MostrarError2\').hide();
        }

        if (passwrd1 == \'\') {
          $(\'#MostrarError3\').show();
          return false;
        } else {
          $(\'#MostrarError3\').hide();
        }

        if (passwrd1.length < 8) {
          $(\'#MostrarError13\').show();
          return false;
        } else {
          $(\'#MostrarError13\').hide();
        }

        if (passwrd2 == \'\') {
          $(\'#MostrarError4\').show();
          return false;
        } else {
          $(\'#MostrarError4\').hide();
        }

        if (passwrd1 != passwrd2) {
          $(\'#MostrarError11\').show();
          return false;
        } else {
          $(\'#MostrarError11\').hide();
        }

        if (email == \'\') {
          $(\'#MostrarError5\').show();
          return false;
        } else {
          $(\'#MostrarError5\').hide();
        }

        if (f.pais.options.selectedIndex == -1 || f.pais.options[f.pais.options.selectedIndex].value == -1) {
          $(\'#MostrarError6\').show();
          return false;
        } else {
          $(\'#MostrarError6\').hide();
        }

        if (ciudad == \'\') {
          $(\'#MostrarError7\').show();
          return false;
        } else {
          $(\'#MostrarError7\').hide();
        }

        if (bday2 == \'\') {
          $(\'#MostrarError8\').show();
          return false;
        } else {
          $(\'#MostrarError8\').hide();
        }

        if (bday1 == \'\') {
          $(\'#MostrarError9\').show();
          return false;
        } else {
          $(\'#MostrarError9\').hide();
        }

        if (bday3 == \'\') {
          $(\'#MostrarError10\').show();
          return false;
        } else {
          $(\'#MostrarError10\').hide();
        }

        if (code == \'\') {
          $(\'#MostrarError12\').show();
          return false;
        } else {
          $(\'#MostrarError12\').hide();
        }

        ' . $VarJS1 . '
      }
    </script>
    <form action="' . $boardurl . '/web/cw-registrarse.php" method="post" accept-charset="' . $context['character_set'] . '" name="creator" id="creator">
      <div style="width: 354px; float: left; margin-right: 8px;">
        <div class="box_354" style="margin-bottom: 8px;">
          <div class="box_title" style="width: 352px;">
            <div class="box_txt box_354-34">&#161;Aclaraci&oacute;n del registro&#33;</div>
            <div class="box_rss">
              <div class="icon_img">
                <img src="' . $tranfer1 . '/blank.gif" style="width: 16px; height: 16px;" border="0" alt="" />
              </div>
            </div>
          </div>
          <div style="width: 344px; padding: 4px;" class="windowbg">
            <font class="size10">
              El registro de usuarios en ' . $mbname . ' es limitado. Al registrarte tendr&aacute;s acceso a la totalidad de los posts. Podr&aacute;s tambi&eacute;n crear tus propios posts, los cuales ser&aacute;n publicados y los podr&aacute;n ver todos los usuarios.
              <br />
              <br />
              Al tener tu propia cuenta, prodr&aacute;s gozar de rangos, en lo cual al ir ascendiendo se le suman los permisos en la web. Para llegar al rango m&aacute;ximo deben llegar a los 1500 puntos, y adem&aacute;s, la web le da a los usuarios m&aacute;s destacados un rango especial que es el rango "Heredero" o "Abastecedor" que estos rangos tienen m&aacute;s permisos que los dem&aacute;s usuarios.
              <br />
              <br />
              Muchas gracias.
              <br />
              <br />
            </font>
            <font class="size9">
              IMPORTANTE: Todos los casilleros con el asterisco (*) son obligatorios
            </font>
          </div>
        </div>
        <div class="box_354">
          <div class="box_title" style="width: 352px;">
            <div class="box_txt box_354-34">Destacados</div>
            <div class="box_rss">
              <div class="icon_img">
                <img src="' . $tranfer1 . '/blank.gif" style="width: 16px; height: 16px;" border="0" alt="" />
              </div>
            </div>
          </div>
          <div style="width: 344px; padding: 4px;" class="windowbg">';

  anuncio_300x250();

  echo '
        </div>
      </div>
    </div>
    <div style="width: 560px; float: left;">
      <div class="box_560">
        <div class="box_title" style="width: 558px;">
          <div class="box_txt box_560-34">Formulario de registro</div>
          <div class="box_rss">
            <div class="icon_img">
              <img src="' . $tranfer1 . '/blank.gif" style="width: 16px; height: 16px;" border="0" alt="" />
            </div>
          </div>
        </div>
        <div class="windowbg" style="padding: 4px; width: 550px;">
          <table align="center" cellpadding="3" cellspacing="0" border="0" width="100%">
            <tr>
              <td align="right" width="40%">
                <font class="size11">* <b>Nombre y Apellido:</b></font>
              </td>
              <td>
                <input type="text" onfocus="foco(this);" onblur="no_foco(this);" name="nombre" size="20" tabindex="1" maxlength="50" />
                <span id="MostrarError1" class="capsprot">Ingresa Nombre y apellido.</span>
              </td>
            </tr>
            <tr>
              <td align="right" width="40%">
                <font class="size11">* <b>Nick:</b></font>
              </td>
              <td>
                <input type="text" onfocus="foco(this);" onblur="no_foco(this); nuevoEvento(\'verificacion\');" name="user" size="20" tabindex="2" maxlength="20" id="verificacion" />
                <img alt="" src="' . $tranfer1 . '/icons/cargando.gif" style="display: none;" id="img" />
                <span id="MostrarError2" class="capsprot">Ingresa tu nick.</span>
              </td>
            </tr>
            <tr id="esconderuno" style="display: none;">
              <td id="esconderdos" style="display: none;" align="right" width="40%"></td>
              <td id="escondertres" style="display: none;">
                <div id="error"></div>
              </td>
            </tr>
            <tr>
              <td align="right" width="40%">
                <font class="size11">* <b>Contrase&ntilde;a:</b></font>
              </td>
              <td>
                <input maxlength="30" type="password" onfocus="foco(this);" onblur="no_foco(this);" name="passwrd1" size="20" tabindex="3" />
                <span id="MostrarError3" class="capsprot">Falta la contrase&ntilde;a.</span>
                <span id="MostrarError13" class="capsprot">Contrase&ntilde;a corta.</span>
              </td>
            </tr>
            <tr>
              <td align="right" width="40%">
                <font class="size11">* <b>Confirmar contrase&ntilde;a:</b></font>
              </td>
              <td>
                <input type="password" onfocus="foco(this);" onblur="no_foco(this);" maxlength="20" name="passwrd2" size="20" tabindex="4" />
                <span id="MostrarError4" class="capsprot">Confirma tu contrase&ntilde;a.</span>
              </td>
            </tr>
            <tr>
              <td align="right" width="40%"><font class="size11">* <b>E-mail:</b></font></td>
              <td>
                <input type="text" onfocus="foco(this);" onblur="no_foco(this); mail(\'emailverificar\')" name="email" id="emailverificar" size="20" tabindex="5" />
                <img alt="" src="' . $tranfer1 . '/icons/cargando.gif" style="display: none;" id="imgd" />
                <span id="MostrarError5" class="capsprot">Ingresa tu e-mail.</span>
              </td>
            </tr>
            <tr id="esconderunod" style="display: none;">
              <td id="esconderdosd" style="display: none;" align="right" width="40%"></td>
              <td id="escondertresd" style="display: none;">
                <div id="errord"></div>
              </td>
            </tr>
            <tr>
              <td align="right" width="40%">
                <font class="size11">* <b>Pa&iacute;s:</b></font>
              </td>
              <td>
                <select tabindex="6" name="pais" id="pais">
                  <option value="-1">Seleccionar pa&iacute;s</option>';

  $countries = $txt['countries'];
  $countries_keys = array_keys($countries);

  for ($i = 0; $i < count($countries_keys); $i++) {
    $value = $countries_keys[$i];
    echo '
      <option value="' . $value . '">' . $countries[$value] . '</option>';
  }

  echo '
                </select>
                <span id="MostrarError6" class="capsprot">Selecciona tu pa&iacute;s.</span></td>
            </tr>
            <tr>
              <td align="right" width="40%">
                <font class="size11">* <b>Ciudad:</b></font>
              </td>
              <td>
                <input tabindex="7" type="text" onfocus="foco(this);" onblur="no_foco(this);" name="ciudad" size="20" value="" />
                <span id="MostrarError7" class="capsprot">Ingresa tu ciudad.</span>
              </td>
            </tr>
            <tr>
              <td align="right" width="40%">
                <font class="size11">* <b>' . $txt[231] . ':</b></font>
              </td>
              <td>
                <select name="sexo" tabindex="8" class="select" size="1">
                  <option value="-1">Seleccionar sexo</option>
                  <option value="1">' . $txt[238] . '</option>
                  <option value="2">' . $txt[239] . '</option>
                </select>
              </td>
            </tr>
            <tr>
              <td align="right" width="40%">
                <font class="size11">* <b>Fecha de nacimiento:</b></font>
                <div class="smalltext">&#40;d&iacute;a&#47;mes&#47;a&ntilde;o&#41;</div>
              </td>
              <td>
                <select tabindex="9" name="bday2" id="bday2" autocomplete="off">
                  <option value="">D&iacute;a:</option>';

  for ($i = 1; $i < 32; $i++) {
    echo '
      <option value="' . $i . '">' . $i . '</option>';
  }

  echo '
                </select>
                <select tabindex="10" name="bday1" id="bday1" autocomplete="off">
                  <option value="">Mes:</option>';

  $months = $txt['months'];
  
  for ($i = 1; $i <= count($months); $i++) {
    echo '
      <option value="' . $i . '">' . strtolower($months[$i]) . '</option>';
  }

  echo '
                </select>
                <select tabindex="11" name="bday3" id="bday3" autocomplete="off">
                  <option value="">A&ntilde;o:</option>';

  for ($i = date('Y') - 18; $i > 1899; $i--) {
    echo '
      <option value="' . $i . '">' . $i . '</option>';
  }

  echo '
                </select>
                <span id="MostrarError8" class="capsprot">Selecciona el d&iacute;a.</span>
                <span id="MostrarError9" class="capsprot">Selecciona el mes.</span>
                <span id="MostrarError10" class="capsprot">Selecciona el a&ntilde;o.</span>
              </td>
            </tr>
            <tr>
              <td align="right" width="40%">
                <font class="size11"><b>Avatar:</b></font>
              </td>
              <td>
                <input tabindex="12" type="text" onfocus="foco(this);" onblur="no_foco(this);" name="avatar" size="30" value="' . $no_avatar . '" />
              </td>
            </tr>
            <tr>
              <td align="right" width="40%">
                <font class="size11">
                  <b>Sitio Web / Blog:</b>
                </font>
              </td>
              <td>
                <input tabindex="13" type="text" onfocus="foco(this);" onblur="no_foco(this);" name="url" size="30" value="http://" />
              </td>
            </tr>
            <tr>
              <td align="right" width="40%">
                <font class="size11">
                  <b>Mensaje personal:</b>
                </font>
              </td>
              <td>
                <input tabindex="14" type="text" onfocus="foco(this);" onblur="no_foco(this);" name="personalText" size="30" maxlength="21" value="" />
              </td>
            </tr>
            <tr>
              <td width="40%" align="right" valign="top">
                <font class="size11">* <b>C&oacute;digo de la imagen:</b>
              </td>
              <td>';

  captcha(1);

  echo '
                  <div id="MostrarError12" class="capsprotBAJO" style="width: 168px">Escribe el c&oacute;digo.</div>
                </td>
              </tr>
              ' . $VarDat . '
            </table>
            <div align="center">
              <font class="size11" style="color: red;">* Campos obligatorios</font>
              <br />
              <div id="MostrarError11" class="capsprot">No coinciden las contrase&ntilde;as.</div>
              ' . $VarError . '
              <input onclick="return showtags(this.form.nombre.value, this.form.user.value, this.form.passwrd1.value, this.form.passwrd2.value, this.form.email.value, this.form, this.form.ciudad.value, this.form.bday2.value, this.form.bday1.value, this.form.bday3.value, this.form.code.value' . $VarJS2 . ');" class="login" type="submit" name="regSubmit" value="' . $txt[97] . '" />
            </div>
          </form>
        </div>
      </div>
    </div>';
}

?>