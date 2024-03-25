<?php
function template_before(){global $context, $settings, $options, $scripturl, $txt, $modSettings, $no_avatar,$tranfer1;

$VarJS1='if (!document.forms.creator.regagree.checked){ $(\'#MostrarError13\').show();  return false;} else $(\'#MostrarError13\').hide();';
$VarJS2=', this.form.regagree.value';
$VarJS3=', regagree';
$VarDat='<tr>
<td align="right" width="40%"></td>
<td>
<label for="regagree"><input tabindex="15" type="checkbox" name="regagree" id="regagree" class="check" /> Acepto los</label> <a href="/terminos-y-condiciones/" target="_blank">T&eacute;rminos de uso</a></td>
</tr>';
$VarError='<div id="MostrarError13" class="capsprot">Debes aceptar los <a href="/terminos-y-condiciones/" target="_blank">T&eacute;rminos de uso</a>.</div>';
        
echo'<script language="JavaScript" type="text/javascript">'; ?>
eval(function(p,a,c,k,e,r){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('g l(){5 a=i;n{a=m o("R.p")}q(e){n{a=m o("S.p")}q(E){a=i}}h(!a&&T r!="U"){a=m r()}V a}g W(a){5 b=2.3("X");7=2.3("Y");8=2.3("Z");9=2.3("10");5 c=2.3("11");h(a=="s"){5 d=2.3("s");5 e=d.t}d.j=k;c.0.1="u";7.0.1="6";8.0.1="6";9.0.1="6";5 f=l();f.v("w","/y/z-A.B?C=12",k);f.D("F-G","H/x-I-J-K");f.L(a+"="+e);f.M=g(){h(f.N==4){d.j=i;c.0.1="6";7.0.1="";8.0.1="";9.0.1="";b.O=f.P}}}g 13(a){5 b=2.3("14");7=2.3("15");8=2.3("16");9=2.3("17");5 c=2.3("18");h(a=="Q"){5 d=2.3("Q");5 e=d.t}d.j=k;c.0.1="u";7.0.1="6";8.0.1="6";9.0.1="6";5 f=l();f.v("w","/y/z-A.B?C=19",k);f.D("F-G","H/x-I-J-K");f.L(a+"="+e);f.M=g(){h(f.N==4){d.j=i;c.0.1="6";7.0.1="";8.0.1="";9.0.1="";b.O=f.P}}}',62,72,'style|display|document|getElementById||var|none|sconderuno|sconderdos|scondertres|||||||function|if|false|disabled|true|nuevoAjax|new|try|ActiveXObject|XMLHTTP|catch|XMLHttpRequest|verificacion|value|inline|open|POST||web|cw|verificar|php|seg|setRequestHeader||Content|Type|application|www|form|urlencoded|send|onreadystatechange|readyState|innerHTML|responseText|emailverificar|Msxml2|Microsoft|typeof|undefined|return|nuevoEvento|error|esconderuno|esconderdos|escondertres|img|001|mail|errord|esconderunod|esconderdosd|escondertresd|imgd|002'.split('|'),0,{}))

<?php echo'
function showtags(nombre, user, passwrd1, passwrd2, email, f, ciudad, bday2, bday1, bday3,code'.$VarJS3.'){
    
if(nombre==\'\'){ $(\'#MostrarError1\').show();  return false;} else $(\'#MostrarError1\').hide();

if(user == \'\'){ $(\'#MostrarError2\').show();  return false;} else $(\'#MostrarError2\').hide();

if(passwrd1 == \'\'){ $(\'#MostrarError3\').show();  return false;} else $(\'#MostrarError3\').hide();

if(passwrd1.length < 8){ $(\'#MostrarError13\').show();  return false;} else $(\'#MostrarError13\').hide();

if(passwrd2 == \'\'){ $(\'#MostrarError4\').show();  return false;} else $(\'#MostrarError4\').hide();

if(passwrd1 != passwrd2){ $(\'#MostrarError11\').show();  return false;} else $(\'#MostrarError11\').hide();

if(email == \'\'){ $(\'#MostrarError5\').show();  return false;} else $(\'#MostrarError5\').hide();

if(f.pais.options.selectedIndex==-1 || f.pais.options[f.pais.options.selectedIndex].value==-1){ $(\'#MostrarError6\').show();  return false;} else $(\'#MostrarError6\').hide();

if(ciudad == \'\'){ $(\'#MostrarError7\').show();  return false;} else $(\'#MostrarError7\').hide();

if(bday2 == \'\'){ $(\'#MostrarError8\').show();  return false;} else $(\'#MostrarError8\').hide();

if(bday1 == \'\'){ $(\'#MostrarError9\').show();  return false;} else $(\'#MostrarError9\').hide();

if(bday3 == \'\'){ $(\'#MostrarError10\').show();  return false;} else $(\'#MostrarError10\').hide();

if(code == \'\'){ $(\'#MostrarError12\').show();  return false;} else $(\'#MostrarError12\').hide();


'.$VarJS1.'
} </script>

<form action="/web/cw-registrarse.php" method="post" accept-charset="'.$context['character_set'].'" name="creator" id="creator">
<div style="width:354px;float:left;margin-right:8px;">
<div class="box_354" style="margin-bottom:8px;">
<div class="box_title" style="width:352px;"><div class="box_txt box_354-34">&#161;Aclaraci&oacute;n del registro&#33;</div><div class="box_rss"><div class="icon_img"><img src="'.$tranfer1.'/blank.gif" style="width:16px;height:16px;" border="0" alt="" /></div></div></div>
	<div style="width:344px;padding:4px;" class="windowbg"><font class="size10">El registro de usuarios en CasitaWeb! es limitado. Al registrarte tendr&aacute;s acceso a la totalidad de los posts. Podr&aacute;s tambi&eacute;n crear tus propios posts, los cuales ser&aacute;n publicados y los podran ver todos los usuarios.<br /><br />
			Al tener su propia cuenta usted prodr&aacute; de gozar de rangos, en lo cual al ir ascendiendo se le suman los permisos en la Web, para llegar al rango maximo deben llegar a los 1500 puntos y ademas la Web le da a los usuarios m&aacute;s destacados un rango especial que es el rango "Heredero" o "Abastecedor" que estos rangos tienen m&aacute;s permisos que los demas usuarios.
			<br /><br />Muchas gracias.<br /><br /></font><font class="size9">IMPORTANTE: todos los casilleros con el asterisco (*) son obligatorios</font></div></div><div class="box_354"><div class="box_title" style="width:352px;"><div class="box_txt box_354-34">Destacados</div><div class="box_rss"><div class="icon_img"><img src="'.$tranfer1.'/blank.gif" style="width:16px;height:16px;" border="0" alt="" /></div></div></div><div style="width:344px;padding:4px;" class="windowbg">'; anuncio_300x250(); echo'</div></div></div>';


//Formulario de registro      
echo'<div style="width:560px;float:left;"><div class="box_560"><div class="box_title" style="width: 558px;"><div class="box_txt box_560-34">Formulario de registro</div><div class="box_rss"><div class="icon_img"><img src="'.$tranfer1.'/blank.gif" style="width:16px;height:16px;" border="0" alt="" /></div></div></div>
<div class="windowbg" style="padding:4px;width:550px;">

<table align="center" cellpadding="3" cellspacing="0" border="0" width="100%">
				
<tr>
<td align="right" width="40%"><font class="size11">* <b>Nombre y Apellido:</b></font></td>
<td><input type="text" onfocus="foco(this);" onblur="no_foco(this);" name="nombre" size="20" tabindex="1" maxlength="50" /><span id="MostrarError1" class="capsprot">Ingresa Nombre y apellido.</span></td>
</tr>  


<tr>
<td align="right" width="40%"><font class="size11">* <b>Nick:</b></font></td>
<td><input type="text" onfocus="foco(this);" onblur="no_foco(this);nuevoEvento(\'verificacion\');" name="user" size="20" tabindex="2" maxlength="20" id="verificacion" /> <img alt="" src="'.$tranfer1.'/icons/cargando.gif" style="display:none;" id="img"/><span id="MostrarError2" class="capsprot">Ingresa tu nick.</span></td>
</tr>	
<tr id="esconderuno" style="display:none;">
<td id="esconderdos" style="display:none;" align="right" width="40%"></td>
<td id="escondertres" style="display:none;"><div id="error"></div></td>
</tr>
                    

<tr>
<td align="right" width="40%"><font class="size11">* <b>Contrase&ntilde;a:</b></font></td>
<td><input maxlength="30" type="password" onfocus="foco(this);" onblur="no_foco(this);" name="passwrd1" size="20" tabindex="3" /><span id="MostrarError3" class="capsprot">Falta la contrase&ntilde;a.</span><span id="MostrarError13" class="capsprot">Contrase&ntilde;a corta.</span></td>
</tr>


<tr>
<td align="right" width="40%"><font class="size11">* <b>Confirmar contrase&ntilde;a:</b></font></td>
<td><input type="password" onfocus="foco(this);" onblur="no_foco(this);" maxlength="20" name="passwrd2" size="20" tabindex="4" /><span id="MostrarError4" class="capsprot">Confirma tu contrase&ntilde;a.</span></td>
</tr>


<tr>
<td align="right" width="40%"><font class="size11">* <b>E-mail:</b></font></td>
<td><input type="text" onfocus="foco(this);" onblur="no_foco(this);mail(\'emailverificar\')" name="email" id="emailverificar" size="20" tabindex="5" /> <img alt="" src="'.$tranfer1.'/icons/cargando.gif" style="display:none;" id="imgd" /><span id="MostrarError5" class="capsprot">Ingresa tu e-mail.</span></td>
</tr>
<tr id="esconderunod" style="display:none;">
<td id="esconderdosd" style="display:none;" align="right" width="40%"></td>
<td id="escondertresd" style="display:none;"><div id="errord"></div></td>
</tr>

<tr>
<td align="right" width="40%"><font class="size11">* <b>Pa&iacute;s: </b></font></td>
                        <td><select tabindex="6" name="pais" id="pais">
						<option value="-1">Seleccionar Pa&iacute;s</option>
						<option value="ar">Argentina</option>
						<option value="bo">Bolivia</option>
						<option value="br">Brasil</option>
						<option value="cl">Chile</option>
						<option value="co">Colombia</option>
						<option value="cr">Costa Rica</option>
						<option value="cu">Cuba</option>
						<option value="ec">Ecuador</option>
						<option value="es">Espa&ntilde;a</option>
						<option value="gt">Guatemala</option>
						<option value="it">Italia</option>
						<option value="mx">Mexico</option>
						<option value="py">Paraguay</option>
						<option value="pe">Peru</option>
						<option value="pt">Portugal</option>
						<option value="pr">Puerto Rico</option>
						<option value="uy">Uruguay</option>
						<option value="ve">Venezuela</option>
						<option value="ot">Otro</option>						
						</select><span id="MostrarError6" class="capsprot">Ingresa tu pa&iacute;s.</span></td>
</tr>


<tr>
<td align="right" width="40%"><font class="size11">* <b>Ciudad: </b></font></td>
<td><input tabindex="7" type="text" onfocus="foco(this);" onblur="no_foco(this);" name="ciudad" size="20" value="" /><span id="MostrarError7" class="capsprot">Ingresa tu ciudad.</span></td>
</tr>


<tr>
<td align="right" width="40%"><font class="size11">* <b>', $txt[231], ': </b></font></td>
<td><select name="sexo" tabindex="8" class="select" size="1">
<option value="1">', $txt[238], '</option>
<option value="2">', $txt[239], '</option>
</select></td>
</tr>

							
<tr>
<td align="right" width="40%"><font class="size11">* <b>Fecha de nacimiento:</b></font>
<div class="smalltext">&#40;d&iacute;a&#47;mes&#47;a&ntilde;o&#41;</div></td>
<td><select tabindex="9" name="bday2" id="bday2" autocomplete="off">
<option value="">D&iacute;a:</option>
<option value="1">1</option>
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>
<option value="5">5</option>
<option value="6">6</option>
<option value="7">7</option>
<option value="8">8</option>
<option value="9">9</option>
<option value="10">10</option>
<option value="11">11</option>
<option value="12">12</option>
<option value="13">13</option>
<option value="14">14</option>
<option value="15">15</option>
<option value="16">16</option>
<option value="17">17</option>
<option value="18">18</option>
<option value="19">19</option>
<option value="20">20</option>
<option value="21">21</option>
<option value="22">22</option>
<option value="23">23</option>
<option value="24">24</option>
<option value="25">25</option>
<option value="26">26</option>
<option value="27">27</option>
<option value="28">28</option>
<option value="29">29</option>
<option value="30">30</option>
<option value="31">31</option></select>



<select tabindex="10" name="bday1" id="bday1" autocomplete="off">
<option value="">Mes:</option>
<option value="1">enero</option>
<option value="2">febrero</option>
<option value="3">marzo</option>
<option value="4">abril</option>
<option value="5">mayo</option>
<option value="6">junio</option>
<option value="7">julio</option>
<option value="8">agosto</option>
<option value="9">septiembre</option>
<option value="10">octubre</option>
<option value="11">noviembre</option>
<option value="12">diciembre</option>
</select>

<select tabindex="11" name="bday3" id="bday3" autocomplete="off">
<option value="">A&ntilde;o:</option>
<option value="2003">2003</option>
<option value="2002">2002</option>
<option value="2001">2001</option>
<option value="2000">2000</option>
<option value="1999">1999</option>
<option value="1998">1998</option>
<option value="1997">1997</option>
<option value="1996">1996</option>
<option value="1995">1995</option>
<option value="1994">1994</option>

<option value="1993">1993</option>
<option value="1992">1992</option>
<option value="1991">1991</option>
<option value="1990">1990</option>
<option value="1989">1989</option>
<option value="1988">1988</option>
<option value="1987">1987</option>
<option value="1986">1986</option>
<option value="1985">1985</option>

<option value="1984">1984</option>
<option value="1983">1983</option>
<option value="1982">1982</option>
<option value="1981">1981</option>
<option value="1980">1980</option>
<option value="1979">1979</option>
<option value="1978">1978</option>
<option value="1977">1977</option>
<option value="1976">1976</option>

<option value="1975">1975</option>
<option value="1974">1974</option>
<option value="1973">1973</option>
<option value="1972">1972</option>
<option value="1971">1971</option>
<option value="1970">1970</option>
<option value="1969">1969</option>
<option value="1968">1968</option>
<option value="1967">1967</option>

<option value="1966">1966</option>
<option value="1965">1965</option>
<option value="1964">1964</option>
<option value="1963">1963</option>
<option value="1962">1962</option>
<option value="1961">1961</option>
<option value="1960">1960</option>
<option value="1959">1959</option>
<option value="1958">1958</option>

<option value="1957">1957</option>
<option value="1956">1956</option>
<option value="1955">1955</option>
<option value="1954">1954</option>
<option value="1953">1953</option>
<option value="1952">1952</option>
<option value="1951">1951</option>
<option value="1950">1950</option>
<option value="1949">1949</option>

<option value="1948">1948</option>
<option value="1947">1947</option>
<option value="1946">1946</option>
<option value="1945">1945</option>
<option value="1944">1944</option>
<option value="1943">1943</option>
<option value="1942">1942</option>
<option value="1941">1941</option>
<option value="1940">1940</option>

<option value="1939">1939</option>
<option value="1938">1938</option>
<option value="1937">1937</option>
<option value="1936">1936</option>
<option value="1935">1935</option>
<option value="1934">1934</option>
<option value="1933">1933</option>
<option value="1932">1932</option>
<option value="1931">1931</option>

<option value="1930">1930</option>
<option value="1929">1929</option>
<option value="1928">1928</option>
<option value="1927">1927</option>
<option value="1926">1926</option>
<option value="1925">1925</option>
<option value="1924">1924</option>
<option value="1923">1923</option>
<option value="1922">1922</option>

<option value="1921">1921</option>
<option value="1920">1920</option>
<option value="1919">1919</option>
<option value="1918">1918</option>
<option value="1917">1917</option>
<option value="1916">1916</option>
<option value="1915">1915</option>
<option value="1914">1914</option>
<option value="1913">1913</option>

<option value="1912">1912</option>
<option value="1911">1911</option>
<option value="1910">1910</option>
<option value="1909">1909</option>
<option value="1908">1908</option>
<option value="1907">1907</option>
<option value="1906">1906</option>
<option value="1905">1905</option>
<option value="1904">1904</option>

<option value="1903">1903</option>
<option value="1902">1902</option>
<option value="1901">1901</option>
<option value="1900">1900</option>
</select>
<span id="MostrarError8" class="capsprot">Ingresar el d&iacute;a.</span>
<span id="MostrarError9" class="capsprot">Ingresar el mes.</span>
<span id="MostrarError10" class="capsprot">Ingresar el a&ntilde;o.</span></td>
</tr>


<tr>
<td align="right" width="40%"><font class="size11"><b>Avatar: </b></font></td>
<td><input tabindex="12" type="text" onfocus="foco(this);" onblur="no_foco(this);" name="avatar" size="30" value="'.$no_avatar.'" /></td>
</tr>


<tr>
<td align="right" width="40%"><font class="size11"><b>Sitio Web / Blog: </b></font></td>
<td><input tabindex="13" type="text" onfocus="foco(this);" onblur="no_foco(this);" name="url" size="30" value="http://" /></td>
</tr>


<tr>
<td align="right" width="40%"><font class="size11"><b>Mensaje personal: </b></font></td>
<td><input tabindex="14" type="text" onfocus="foco(this);" onblur="no_foco(this);" name="personalText" size="30" maxlength="21" value="" /></td>
</tr>


<tr>
<td width="40%" align="right" valign="top"><font class="size11">* <b>C&oacute;digo de la im&aacute;gen:</b></td>
<td>';
captcha(1);

echo'<div id="MostrarError12" class="capsprotBAJO" style="width:168px">Escribi el codigo.</div></td>
</tr>';

echo $VarDat.'</table><div align="center"><font class="size11" style="color: red;">* Campos obligatorios</font><br />
<div id="MostrarError11" class="capsprot">No coinciden las contrase&ntilde;as.</div>
'.$VarError.'
<input onclick="return showtags(this.form.nombre.value,this.form.user.value, this.form.passwrd1.value, this.form.passwrd2.value, this.form.email.value, this.form, this.form.ciudad.value, this.form.bday2.value, this.form.bday1.value, this.form.bday3.value, this.form.code.value'.$VarJS2.');" class="login" type="submit" name="regSubmit" value="', $txt[97], '" /></div>
</form></div></div></div>';}

?>