// TO-DO: Modificar ruta según corresponda
var boardUrl = 'http://localhost/casitaweb';

// Primer packer
var clientPC = navigator.userAgent.toLowerCase();
var clientVer = parseInt(navigator.appVersion);
var is_ie = (clientPC.indexOf('msie') != -1 && clientPC.indexOf('opera') == -1);
var is_nav = (clientPC.indexOf('mozilla') != -1 && clientPC.indexOf('spoofer') == -1 && clientPC.indexOf('compatible') == -1 && clientPC.indexOf('opera') == -1 && clientPC.indexOf('webtv') == -1 && clientPC.indexOf('hotjava') == -1);
var is_win = (clientPC.indexOf('win') != -1 || clientPC.indexOf('16bit') != -1);
var is_mac = (clientPC.indexOf('mac') != -1);
var is_moz = 0;

function el(a) {
  if (document.getElementById) {
    return document.getElementById(a);
  } else if (window[a]) {
    return window[a];
  }

  return null;
}

function selectycopy(a) {
  a.focus();
  a.select();
}

function createXMLHttpRequest() {
  var a = null;

  try {
    a = window.XMLHttpRequest ? new XMLHttpRequest(): new ActiveXObject('Microsoft.XMLHTTP');
  } catch(e) {
    alert('Tu explorador no soporta este sitema, CasitaWeb te recomienda que uses Firefox (http://www.mozilla-europe.org/es/firefox/)');
  }

  return a;
}

var xhr = createXMLHttpRequest();

function empty(a) {
  var b;

  if (a === '' || a === 0 || a === '0' || a === null || a === false || typeof a === 'undefined') {
    return true;
  }

  if (typeof a == 'object') {
    for (b in a) {
      return false;
    }

    return true;
  }

  return false;
};

// Segundo packer
function loadcargando(s) {
	if (s == 1) {
		$('#cargando_boxy').fadeIn('fast');
	} else {
		$('#cargando_boxy').hide();
	}
}

function cerrarBox() {
	$('.boxy-wrapper').remove();
	return;
}

function irAconectarse() {
	if (!$('.hdLoglink').hasClass('opened')) {
		servicenavlogin();
	}

	$('#nickname').focus();

	ira_CasitaWebNET();
}

// PopUp emoticones
function moticonup() {
  var a = window.open(boardUrl + '/web/cw-emoticones.php', '', 'width=225px,height=500px,scrollbars');
}

// Desplazamiento (scroll) por jQuery
;
(function(d) {
	var k = d.scrollTo = function(a, i, e) {
		d(window).scrollTo(a, i, e);
	};

	k.defaults =  {
		axis: 'xy',
    duration: parseFloat(d.fn.jquery) >= 1.3 ? 0 : 1
	};

	k.window = function(a) {
		return d(window)._scrollable();
	};

	d.fn._scrollable = function() {
		return this.map(function() {
      var a = this, i = !a.nodeName || d.inArray(a.nodeName.toLowerCase(), ['iframe', '#document', 'html', 'body']) != -1;

			if (!i) {
        return a;
      }

			var e = (a.contentWindow || a).document || a.ownerDocument || a;

			return d.browser.safari || e.compatMode == 'BackCompat' ? e.body : e.documentElement;
		});
	};

	d.fn.scrollTo = function(n, j, b) {
		if (typeof j=='object') {
			b = j;
			j = 0;
		}

		if (typeof b == 'function') {
      b = {
			  onAfter: b
		  };
    }

		if (n == 'max') {
      n = 9e9;
    }

		b = d.extend({}, k.defaults, b);
		j = j || b.speed || b.duration;
		b.queue = b.queue && b.axis.length > 1;

		if (b.queue) {
      j /= 2;
    }

		b.offset = p(b.offset);
		b.over = p(b.over);

		return this._scrollable().each(function() {
			var q = this, r = d(q), f = n, s, g = {}, u = r.is('html,body');

			switch(typeof f) {
				case 'number':
        case 'string':
          if (/^([+-]=)?\d+(\.\d+)?(px|%)?$/.test(f)) {
					  f = p(f);
					  break
				  }

  				f = d(f, this);
				case 'object':
          if (f.is || f.style) {
            s = (f = d(f)).offset()
          }
			}

			d.each(b.axis.split(''), function(a, i) {
				var e = i == 'x' ? 'Left' : 'Top', h = e.toLowerCase(), c = 'scroll' + e, l = q[c], m = k.max(q, i);

				if (s) {
					g[c] = s[h] + (u ? 0 : l - r.offset()[h]);

					if (b.margin) {
						g[c] -= parseInt(f.css('margin' + e)) || 0;
						g[c] -= parseInt(f.css('border' + e + 'Width')) || 0;
					}

					g[c] += b.offset[h] || 0;

					if (b.over[h]) {
            g[c] += f[i == 'x' ? 'width' : 'height']() * b.over[h];
          }
				} else {
					var o = f[h];
					g[c] = o.slice && o.slice(-1) == '%' ? parseFloat(o) / 100 * m : o;
				}

				if (/^\d+$/.test(g[c])) {
          g[c] = g[c] <= 0 ? 0 : Math.min(g[c], m);
        }

				if (!a && b.queue) {
					if (l != g[c]) {
            t(b.onAfterFirst);
          }

					delete g[c];
				}
			});

			t(b.onAfter);

			function t(a) {
				r.animate(g, j, b.easing, a && function() {
					a.call(this, n, b);
				});
			}
		}).end();
	};

	k.max = function(a, i) {
		var e = i == 'x' ? 'Width' : 'Height', h = 'scroll' + e;

		if (!d(a).is('html,body'))
      return a[h] - d(a)[e.toLowerCase()]();

		var c = 'client' + e, l = a.ownerDocument.documentElement, m = a.ownerDocument.body;

		return Math.max(l[h], m[h]) - Math.min(l[c], m[c]);
	};

	function p(a) {
		return typeof a == 'object' ? a : { top: a, left: a };
	}
})(jQuery);

function ira_CasitaWebNET() {
	$('#top').scrollTo(0, 800, { queue: true });
	return false;
}

// Replace emoticones
function replaceText(a, b) {
	if (typeof(b.caretPos) != 'undefined' && b.createTextRange) {
		var c = b.caretPos;
		c.text = c.text.charAt(c.text.length - 1) == ' ' ? a + ' ' : a;
		c.select();
	} else if (typeof(b.selectionStart) != 'undefined') {
		var d = b.value.substr(0, b.selectionStart);
		var e = b.value.substr(b.selectionEnd);
		var f = b.scrollTop;
		b.value = d + a + e;

		if (b.setSelectionRange) {
			b.focus();
			b.setSelectionRange(d.length + a.length, d.length + a.length);
		}

		b.scrollTop = f;
	} else {
		b.value += a;
		b.focus(b.value.length - 1);
	}
}

function crearVyoutube(u) {
  $('#v-' + u).html('<object type="application/x-shockwave-flash" width="450px" height="240px" data="http://www.youtube.com/v/' + u + '&rel=0&autoplay=1&showsearch=0&hd=0&fs=1&showinfo=1&iv_load_policy=1&hl=0&eurl=http://casitaweb.net"><param name="src" value="http://www.youtube.com/v/' + u + '&rel=0&autoplay=1&showsearch=0&hd=0&fs=1&showinfo=1&iv_load_policy=1&hl=0&eurl=http://casitaweb.net" /><param name="wmode" value="transparent" /><param name="allowscriptaccess" value="always" /><param name="allownetworking" value="internal" /><param name="allowFullScreen" value="true" /></object>');
}

function boxHablar(u) {
  $('#b-' + u).css('display', 'block');
  $('#textareaCC_' + u).focus();
}

drawSocialLinks = function(oContainers) {
  var l, i, socialList = [], socialHtm = '';
  var t = $('h1').eq(0).text();
  var u = window.location.href;
  var iconDirectory = boardUrl + '/images';
  var socialMedia = [
    { linkText: 'Del-icio-us', icon: 'del', href: 'http://del.icio.us/post?url=' + u + '&title=' + t },
    { linkText: 'Google', icon: 'google', href: 'http://www.google.com/bookmarks/mark?op=edit&bkmk=' + u },
    { linkText: 'Digg', icon: 'digg', href: 'http://digg.com/submit?phase=2&url=' + u + '&title=' + t },
    { linkText: 'Twitter', icon: 'twitter', href: 'http://twitter.com/home?status=' + u },
    { linkText: 'Facebook', icon: 'facebook', href: 'http://www.facebook.com/share.php?u=' + u}
  ];
 
  l = socialMedia.length;

  for (i = 0; i < l; i++) {
    socialList.push('<div style="float: right; margin-right: 5px;"><a href="' + socialMedia[i].href + '" title="' + socialMedia[i].linkText + '" target="_blank"><div style="height: 32px; width: 32px; cursor: pointer;" class="' + socialMedia[i].icon + ' png"><img alt="" src="' + iconDirectory + '/espacio.gif" class="png" height="32px" width="32px" /></div></a></div>');
  }

  socialHtm = socialList.join(' ');
  oContainers.append('<div style="margin-bottom: 5px; text-align: right;" align="right">' + socialHtm + '<div class="clearfix"></div></div>');
}

// Actualizar comentarios
function actualizar_comentarios() {
  $('#ult_comm').fadeOut('fast');

  $.ajax({
    type: 'POST',
    url: boardUrl + '/web/cw-ActComentarios.php',
    success: function(h) {
      $('#ult_comm').html(h);
      $('#ult_comm').fadeIn('fast');
    }
  });
}

function error_avatar(obj) {
  obj.src = boardUrl + '/avatar.gif';
}

// Citar comentarios
function citar_comment(id) {
  var user = el('autor_cmnt_' + id).getAttribute('user_comment');
  var cita = el('autor_cmnt_' + id).getAttribute('text_comment');
  var text = ($('#editorCW').val() != '') ? $('#editorCW').val() + '\n' : '';
  text += '[quote=' + user + ']' + cita + '[/quote]\n';
  $('#editorCW').val(text);
  $('#editorCW').focus();
}

function foco(elemento) {
  elemento.style.border = '1px solid #878787';
  elemento.style.color = '#333333';
}

function no_foco(elemento) {
  elemento.style.border = '1px solid #CCCCCC';
  elemento.style.color = '#6B6B6B';
}

// Abrir / Cerrar
function chgsec(obj) {
  $('div.aparence > h3').removeClass('titlesCom2');
  $('div.aparence > #contennnt').fadeOut('fast');
  if ($(obj).next().css('display') == 'none') {
    $(obj).addClass('titlesCom2');
    $(obj).next().slideDown('fast').addClass('active');
  }
}

// Reactivar temas comunidades
function reacTemas(id) {
  $.ajax({
    type: 'GET',
    url: boardUrl + '/web/cw-comunidadesReacTem.php',
    data: 'id=' + id,
    success: function(h) {
      if (h.charAt(0) == 0) {
        Boxy.alert(h.substring(3), null, { title: 'Alerta' });
      } else if (h.charAt(0) == 1) {
        $('#elt').fadeIn('fast');
        $('#edt').fadeIn('fast');
        $('#tel').remove();
        $('#rect').remove();
      }
    },
    error: function() {
      Boxy.alert('Error, volver a intentar...', null, { title: 'Alerta' });
    }
  });
}

// Votar posts
function votar_post(id, puntos) {
  $('#cargando_opciones').css('display', 'block');
  $('#cargando_opciones2').css('display', 'none');

  $.ajax({
    type: 'GET',
    url: boardUrl + '/web/cw-VotarPost.php',
    data: 'post=' + id + '&puntos=' + puntos,
    success: function(h) {
      $('#cargando_opciones').css('display', 'none');
      $('#span_opciones1').fadeOut('slow', function() {
        if (h.charAt(0) == 0) {
          // Error
          $('#span_opciones1').addClass('noesta');
        } else if (h.charAt(0) == 1) {
          // OK
          $('#span_opciones1').addClass('noesta-ve');
          $('#cant_pts_post_dos').html(parseInt($('#cant_pts_post_dos').html()) + parseInt(puntos));
          $('#puntosDD').html(parseInt($('#puntosDD').html()) - parseInt(puntos));
          $('#cant_pts_post').html(parseInt($('#cant_pts_post').html()) + parseInt(puntos));
        }

        $('#span_opciones1').css('text-align', 'center');
        $('#span_opciones1').removeClass('size10');
        $('#span_opciones1').html(h.substring(3));
        $('#span_opciones1').slideDown('slow');
      });
    },
    error: function() {
      Boxy.alert('Error, volver a intentar...', null, { title: 'Alerta' });
    }
  });
}

// Votar imagen
function votar_img(id, puntos) {
  $('#cargando_opciones').css('display', 'block');
  $('#cargando_opciones2').css('display', 'none');

  $.ajax({
    type: 'GET',
    url: boardUrl + '/web/cw-votar-img.php',
    data: 'imagen=' + id + '&cantidad=' + puntos,
    success: function(h) {
      $('#cargando_opciones').css('display', 'none');
      $('#span_opciones1').fadeOut('slow', function() {
        if (h.charAt(0) == 0) {
          // Error
          $('#span_opciones1').addClass('noesta');
        } else if (h.charAt(0) == 1) {
          // OK
          $('#span_opciones1').addClass('noesta-ve');
          $('#cant_pts_post_dos').html(parseInt($('#cant_pts_post_dos').html()) + parseInt(puntos));
          $('#puntosDD').html(parseInt($('#puntosDD').html()) - parseInt(puntos));
          $('#cant_pts_post').html(parseInt($('#cant_pts_post').html()) + parseInt(puntos));
        }

        $('#span_opciones1').css('text-align', 'center');
        $('#span_opciones1').removeClass('size10');
        $('#span_opciones1').html(h.substring(3));
        $('#span_opciones1').slideDown('slow');
      });
    },
    error: function() {
      Boxy.alert('Error, volver a intentar...', null, { title: 'Alerta' });
    }
  });
}

// Agregar post a favoritos
function add_favoritos(id) {
  $('#cargando_opciones').css('display', 'block');
  $('#cargando_opciones2').css('display', 'none');

  $.ajax({
    type: 'GET',
    url: boardUrl + '/web/cw-FavoritosACC.php',
    data: 'tipo=posts&post=' + id,
    success: function(h) {
      var original = $('#span_opciones2').html();

      $('#cargando_opciones').css('display', 'none');

      $('#span_opciones2').fadeOut('slow', function() {
        if (h.charAt(0) == 0) {
          $('#span_opciones2').addClass('status_error');
        } else if (h.charAt(0) == 1) {
          $('#cant_favs_post').html(parseInt($('#cant_favs_post').html()) + 1);
          $('#span_opciones2').addClass('status_ok');
        }

        $('#span_opciones2').html(h.substring(3));

        $('#span_opciones2').slideDown('slow', function() {
          if (h.charAt(0) == 1) {
            sleep(1000);
          } else {
            sleep(2500);
          }

          $('#span_opciones2').fadeOut('slow', function() {
            $('#span_opciones2').removeClass('status_error');
            $('#span_opciones2').removeClass('status_ok');
            $('#span_opciones2').html(original);
            $('#span_opciones2').slideDown('slow');
          });
        });
      });
    },
    error: function() {
      Boxy.alert('Error, volver a intentar...', null, { title: 'Alerta' });
    }
  });
}

// Agregar imagen a favoritos
function add_favoritos_img(id) {
  $('#cargando_opciones').css('display', 'block');
  $('#cargando_opciones2').css('display', 'none');

  $.ajax({
    type: 'GET',
    url: boardUrl + '/web/cw-FavoritosACC.php',
    data: 'tipo=imagen&kjas=' + id,
    success: function(h) {
      var original = $('#span_opciones2').html();

      $('#cargando_opciones').css('display', 'none');

      $('#span_opciones2').fadeOut('slow', function() {
        if (h.charAt(0) == 0) {
          $('#span_opciones2').addClass('status_error');
        } else if (h.charAt(0) == 1) {
          $('#cant_favs_post').html(parseInt($('#cant_favs_post').html()) + 1);
          $('#span_opciones2').addClass('status_ok');
        }

        $('#span_opciones2').html(h.substring(3));

        $('#span_opciones2').slideDown('slow', function() {
          if (h.charAt(0) == 1) {
            sleep(1000);
          } else {
            sleep(2500);
          }

          $('#span_opciones2').fadeOut('slow', function() {
            $('#span_opciones2').removeClass('status_error');
            $('#span_opciones2').removeClass('status_ok');
            $('#span_opciones2').html(original);
            $('#span_opciones2').slideDown('slow');
          });
        });
      });
    },
    error: function() {
      Boxy.alert('Error, volver a intentar...', null, { title: 'Alerta' });
    }
  });
}

// Enviar denuncia
function enviarDen(tipo, id) {
  if ($('#cDen').val() == '') {
    $('#cDen').focus();
    return false;
  }

  $('#cargandoBoxyc').css('display', 'none');
  $('#cargandoBoxy').css('display', 'block');

  $.ajax({
    type: 'POST',
    url: boardUrl + '/web/cw-denunciaEnviar.php',
    cache: false,
    data: 'tipo=' + tipo + '&id=' + id + '&razon=' +  encodeURIComponent($('#razon').val()) + '&comentario=' +  encodeURIComponent($('#cDen').val()),
    success: function(h) {
      $('#cargandoBoxy').css('display', 'none');
      $('#cargandoBoxyc').css('display', 'block');
      $('#contentv').remove();
      $('#resultado').css('display', 'block');

      if (h.charAt(0) == 0) {
        // Datos incorrectos
        $('#resultado').addClass('noesta');
        $('#resultado').html(h.substring(3)).fadeIn('fast');
      } else if (h.charAt(0) == 1) {
        // OK
        $('#resultado').removeClass('noesta');
        $('#resultado').addClass('noesta-ve');
        $('#resultado').html(h.substring(3)).fadeIn('fast');
      }
    },
    error: function() {
      Boxy.alert('Error, volver a intentar...', null, { title: 'Alerta' });
    }
  });
}

// Miembros comunidades página
function pagComunidad(id, pag) {
  $('#cargandoBoxyc').css('display', 'none');
  $('#cargandoBoxy').css('display', 'block');

  $.ajax({
    type: 'GET',
    url: boardUrl + '/web/cw-TEMPcomMIEMBROS.php',
    cache: false,
    data: 'c=' +  id + '&ajaxboxy=si&pag=' + pag,
    success: function(h) {
      $('#cargandoBoxy').css('display', 'none');
      $('#cargandoBoxyc').css('display', 'block');
      $('#contenidoPG').html(h.substring(0));	
    },
    error: function() {
      Boxy.alert('Error, volver a intentar...', null, { title: 'Alerta' });
    }
  });
}

// Miembros comunidades aprobados
function pagComunidad2(id, pag) {
  $('#cargandoBoxyc').css('display', 'none');
  $('#cargandoBoxy').css('display', 'block');

  $.ajax({
    type: 'GET',
    url: boardUrl + '/web/cw-TEMPcomMIEMBROSaDm.php',
    cache: false,
    data: 'c=' +  id + '&ajaxboxy=si&pag=' + pag,
    success: function(h) {
      $('#cargandoBoxy').css('display', 'none');
      $('#cargandoBoxyc').css('display', 'block');
      $('#contenidoPG').html(h.substring(0));	
    },
    error: function() {
      Boxy.alert('Error, volver a intentar...', null, { title: 'Alerta' });
    }
  });
}

// Enviar mensaje privado
function enviarMP(tipo, id) {
  if ($('#titulo').val() == '') {
    $('#titulo').focus();
    return false;
  }

  if ($('.mensaje').val() == '') {
    $('.mensaje').focus();
    return false;
  }

  if (tipo == 1) {
    var name = $('#para').val();
  } else {
    var name = id;
  }

  $('#cargandoBoxyc').css('display', 'none');
  $('#cargandoBoxy').css('display', 'block');

  $.ajax({
    type: 'POST',
    url: boardUrl + '/web/cw-redactarMp.php',
    cache: false,
    data: 'mensaje=' +  encodeURIComponent($('.mensaje').val()) + '&para=' + name + '&titulo=' +  encodeURIComponent($('#titulo').val()),
    success: function(h) {
      $('#cargandoBoxy').css('display', 'none');
      $('#cargandoBoxyc').css('display', 'block');
      $('#contenidomp').remove();
      $('#resultadomp').css('display', 'block');

      if (h.charAt(0) == 0) {
        // Datos incorrectos
        $('#resultadomp').addClass('noesta');
        $('#resultadomp').html(h.substring(3)).fadeIn('fast');
      } else if (h.charAt(0) == 1) {
        // OK
        $('#resultadomp').removeClass('noesta');
        $('#resultadomp').addClass('noesta-ve');
        $('#resultadomp').html(h.substring(3)).fadeIn('fast');
      }
    },
    error: function() {
      Boxy.alert('Error, volver a intentar...', null, { title: 'Alerta' });
    }
  });
}

// Recomendar post
function recomendarPost(id) {
  if ($('#titulo').val() == '') {
    $('#titulo').focus();
    return false;
  }

  if ($('#comment').val() == '') {
    $('#comment').focus();
    return false;
  }

  if ($('.r_email').val() == '') {
    $('.r_email').focus();
    return false;
  }

  $('#cargandoBoxyc').css('display', 'none');
  $('#cargandoBoxy').css('display', 'block');

  $.ajax({
    type: 'POST',
    url: boardUrl + '/web/cw-recomendarPost.php',
    cache: false,
    data: 'post=' + id + '&r_email=' + encodeURIComponent($('.r_email').val()) + '&r_email1=' + encodeURIComponent($('.r_email1').val()) + '&r_email2=' + encodeURIComponent($('.r_email2').val()) + '&r_email3=' + encodeURIComponent($('.r_email3').val()) + '&r_email4=' + encodeURIComponent($('.r_email4').val()) + '&r_email5=' + encodeURIComponent($('.r_email5').val()) + '&titulo=' + encodeURIComponent($('#titulo').val()) + '&comment=' + encodeURIComponent($('#comment').val()) + '&g-recaptcha-response=' + encodeURIComponent(grecaptcha.getResponse()),
    success: function(h) {
      $('#cargandoBoxy').css('display', 'none');
      $('#cargandoBoxyc').css('display', 'block');
      $('#contenidoEP').remove();
      $('#resultadoEP').css('display', 'block');

      if (h.charAt(0) == 0) {
        // Datos incorrectos
        $('#resultadoEP').addClass('noesta');
        $('#resultadoEP').html(h.substring(3)).fadeIn('fast');
      } else if (h.charAt(0) == 1) {
        // OK
        $('#resultadoEP').removeClass('noesta');
        $('#resultadoEP').addClass('noesta-ve');
        $('#resultadoEP').html(h.substring(3)).fadeIn('fast');
      }
    },
    error: function() {
      Boxy.alert('Error, volver a intentar...', null, { title: 'Alerta' });
    }
  });
}

// Agregar imagen
function addIMG(donde) {
  if ($('#title').val() == '') {
    $('#title').focus();
    return false;
  }

  if ($('#url').val() == '') {
    $('#url').focus();
    return false;
  }

  $('#cargandoBoxyc').css('display', 'none');
  $('#cargandoBoxy').css('display', 'block');

  $.ajax({
    type: 'POST',
    url: boardUrl + '/web/cw-imgAgregar.php',
    cache: false,
    data: 'url=' + encodeURIComponent($('#url').val()) + '&title=' + encodeURIComponent($('#title').val()),
    success: function(h) {
      $('#cargandoBoxy').css('display', 'none');
      $('#cargandoBoxyc').css('display', 'block');
      $('#contenidoAD').remove();
      $('#resultadoAD').css('display', 'block');

      if (h.charAt(0) == 0) {
        // Datos incorrectos
        $('#resultadoAD').addClass('noesta');
        $('#resultadoAD').html(h.substring(3)).fadeIn('fast');
      } else if (h.charAt(0) == 1) {
        // OK
        $('#resultadoAD').removeClass('noesta');
        $('#resultadoAD').addClass('noesta-ve');
        $('#resultadoAD').html(h.substring(3)).fadeIn('fast');
      }
    },
    error: function() {
      Boxy.alert('Error, volver a intentar...', null, { title: 'Alerta' });
    }
  });
}

// Agregar comentario post
function add_comment(id, nro) {
  if ($('#editorCW').val() == '') {
    $('#editorCW').focus();
    return;
  }

  $('.msg_add_comment').hide();
  $('#gif_cargando_add_comment').css('display', 'block');

  $.ajax({
    type: 'POST',
    url: boardUrl + '/web/cw-comentarPost.php',
    data: 'editorCW=' + encodeURIComponent($('#editorCW').val()) + '&id=' + id + '&psecion=' + nro,
    success: function(h) {
      $('#gif_cargando_add_comment').css('display', 'none');

      if (h.charAt(0) == 0) {
        $('.msg_add_comment').html(h.substring(3));
        $('.msg_add_comment').addClass('noesta');
        $('.msg_add_comment').fadeIn('fast');
      } else if (h.charAt(0) == 1) {
        $('#return_agregar_comentario').html(h.substring(3));
        $('#nrocoment').html(parseInt($('#nrocoment').html()) + 1);
        $('#return_agregar_comentario').fadeIn('fast');
        $('#editorCW').val('');

        if ($('#no_comentarios')) {
          $('#no_comentarios').fadeOut('fast');
        }
      }
    },
    error: function() {
      $('#gif_cargando_add_comment').css('display', 'none');
      Boxy.alert('Error, volver a intentar...', null, { title: 'Alerta' });
    }
  });
}

// Agregar comentario imagen
function add_comment_img(id, nro) {
  if ($('#editorCW').val() == '') {
    $('#editorCW').focus();
    return;
  }

  $('.msg_add_comment').hide();
  $('#gif_cargando_add_comment').css('display', 'block');

  $.ajax({
    type: 'POST',
    url: boardUrl + '/web/cw-comentarImg.php',
    data: 'editorCW=' + encodeURIComponent($('#editorCW').val()) + '&id=' + id + '&psecion=' + nro,
    success: function(h) {
      $('#gif_cargando_add_comment').css('display', 'none');
      if (h.charAt(0) == 0) {			
        $('.msg_add_comment').html(h.substring(3));
        $('.msg_add_comment').addClass('noesta');
        $('.msg_add_comment').fadeIn('fast');
      } else if (h.charAt(0) == 1) {
        $('#return_agregar_comentario').html(h.substring(3));
        $('#nrocoment').html(parseInt($('#nrocoment').html()) + 1);
        $('#editorCW').val('');
        $('#return_agregar_comentario').fadeIn('fast');

        if ($('#no_comentarios')) {
          $('#no_comentarios').fadeOut('fast');
        }
      }
    },
    error: function() {
      $('#gif_cargando_add_comment').css('display', 'none');
      Boxy.alert('Error, volver a intentar...', null, { title: 'Alerta' });
    }
  });
}

// Acciones amistad
function accionAmistad(id, q) {
  $('#cargandoAmistad').fadeIn('fast');

  $.ajax({
    type: 'GET',
    url: boardUrl + '/web/cw-AmistadDecs.php',
    data: 'id=' + id + '&tipo=' + q,
    success: function(h) {
      $('#cargandoAmistad').fadeOut('fast');

      if (h.charAt(0) == 0) {
        $('#errorAmistad').fadeIn('fast');
        $('#errorAmistad').html(h.substring(3));
      } else if (h.charAt(0) == 1) {
        $('#ams_' + id).remove();
        AmigosActs();
      }
    },
    error: function() {
      Boxy.alert('Error, volver a intentar...', null, { title: 'Alerta' });
    }
  });
}

 // Eliminar favorito
function del_favoritos(id) {
  $.ajax({
    type: 'GET',
    url: boardUrl + '/web/cw-FavoritosACC.php',
    data: 'eliminar=' + id,
    success: function(h) {
      if (h.charAt(0) == 0) {
        $('#imgerr_' + id).html(h.substring(3));
        $('#imgel_' + id).hide();
        $('#imgerr_' + id).fadeIn('fast');
      } else if (h.charAt(0) == 1) {
        $('#fav_' + id).addClass('eliminado');
        $('#imgel_' + id).hide();
        $('#imgerrs_' + id).fadeIn('fast');
      }
    },
    error: function() {
      Boxy.alert('Error, volver a intentar...', null, { title: 'Alerta' });
      $('#imgel_' + id).hide();
      $('#imgerr_' + id).fadeIn('fast');
    }
  });
}

// Eliminar mensaje privado
function del_mp_env(id) {
  $.ajax({
    type: 'GET',
    url: boardUrl + '/web/cw-eliminarMp.php',
    data: 'eliminar=' + id,
    success: function(h) {
      if (h.charAt(0) == 0) {
        $('#imgerr_' + id).html(h.substring(3));
        $('#imgel_' + id).hide();
        $('#imgerr_' + id).fadeIn('fast');
      } else if (h.charAt(0) == 1) {
        $('#mp_' + id).addClass('eliminado');
        $('#imgel_' + id).hide();

        if (parseInt($('#cantidad-MP2').html()) == '1') {
          $('#quitarMP').hide();
          $('#quitarMP2').hide();
        } else {
          $('#cantidad-MP').html(parseInt($('#cantidad-MP').html()) - 1);
          $('#cantidad-MP2').html(parseInt($('#cantidad-MP2').html()) - 1);
        }

        $('#imgerrs_' + id).fadeIn('fast');
      }
    },
    error: function() {
      Boxy.alert('Error, volver a intentar...', null, { title: 'Alerta' });
      $('#imgel_' + id).hide();
      $('#imgerr_' + id).fadeIn('fast');
    }
  });
}

// Eliminar comentario post
function del_coment_post(id, post) {
  $('.errorDelCom').hide();
  $('.msg_add_comment').hide();

  $.ajax({
    type: 'GET',
    url: boardUrl + '/web/cw-eliminarComPost.php',
    data: 'id=' + id + '&post=' + post,
    success: function(h) {
      if (h.charAt(0) == 0) {
        $('.errorDelCom').html(h.substring(3));
        $('.errorDelCom').addClass('noesta');
        $('.errorDelCom').fadeIn('fast');
      } else if (h.charAt(0) == 1) {
        if (h.substring(3) < 1) {
          $('#nrocoment').html('0');
          $('#no_comentarios').fadeIn('fast');
        } else {
          $('#nrocoment').html(parseInt($('#nrocoment').html()) - 1);
        }

        $('#cmt_' + id).hide();
      }					
    },
    error: function() {
      Boxy.alert('Error, volver a intentar...', null, { title: 'Alerta' });
    }
  });
}

// Comentar un comentario del muro
function comentarCcmuro(id) {
  $('#cargandoCC_' + id).css('display', 'block');

  $.ajax({
    type: 'POST',
    url: boardUrl + '/web/cw-comentarMurocc.php',
    data: 'id=' + id + '&quediche=' + $('#textareaCC_' + id).val(),
    success: function(h) {
      if (h.charAt(0) == 0) {
        $('#comentarCC_' + id).hide();
        $('#comentarCC2_' + id).html(h.substring(3));
        $('#comentarCC2_' + id).addClass('noestaGR');
        $('#comentarCC2_' + id).fadeIn('fast');
        $('#cargandoCC_' + id).hide();
      } else if (h.charAt(0) == 1) {
        $('#textareaCC_' + id).val('Escribe un comentario...');
        $('#comentarCC_' + id).hide();
        $('#vmam_' + id).fadeIn('fast');
        $('#comentarCC2_' + id).html(h.substring(3));
        $('#comentarCC2_' + id).fadeIn('fast');
        $('#cargandoCC_' + id).hide();
      }
    },
    error: function() {
      $('#comentarCC_' + id).remove();
      Boxy.alert('Error, volver a intentar...', null, { title: 'Alerta' });
    }
  });
}

// Eliminar comentario imagen
function del_coment_img(id, img) {
  $('.errorDelCom').hide();
  $('.msg_add_comment').hide();

  $.ajax({
    type: 'GET',
    url: boardUrl + '/web/cw-comentImgEli.php',
    data: 'id=' + id + '&img=' + img,
    success: function(h) {
      if (h.charAt(0) == 0) {
        $('.errorDelCom').html(h.substring(3));
        $('.errorDelCom').addClass('noesta');
        $('.errorDelCom').fadeIn('fast');
      } else if (h.charAt(0) == 1) {
        if (h.substring(3) < 1) {
          $('#nrocoment').html('0');
          $('#no_comentarios').fadeIn('fast');
        } else {
          $('#nrocoment').html(parseInt($('#nrocoment').html()) - 1);
        }

        $('#cmt_' + id ).hide();
      }
    },
    error: function() {
      Boxy.alert('Error, volver a intentar...', null, { title: 'Alerta' });
    }
  });
}

// Eliminar comentario muro
function del_coment_muro(id) {
  $('.msg_add_muro').hide();

  $.ajax({
    type: 'GET',
    url: boardUrl + '/web/cw-eliminarMuroComent.php',
    data: 'id=' + id,
    success: function(h) {
      if (h.charAt(0) == 0) {
        $('.msg_add_muro').html(h.substring(3));
        $('.msg_add_muro').addClass('noesta');
        $('.msg_add_muro').fadeIn('fast');
      } else if (h.charAt(0) == 1) {
        $('#cantmuro').html(parseInt($('#cantmuro').html()) - 1);
        $('#muro-' + id).remove();
      }
    },
    error: function() {
      Boxy.alert('Error, volver a intentar...', null, { title: 'Alerta' });
    }
  });
}

// Eliminar comentario muro
function del_comentCC_muro(id) {
  $.ajax({
    type: 'GET',
    url: boardUrl + '/web/cw-eliminarMuroComent.php',
    data: 'id=' + id,
    success: function(h) {
      if (h.charAt(0) == 0) {
        $('#SETcto_' + id).remove();
        $('#SETcto2_' + id).html(h.substring(3));
        $('#SETcto2_' + id).fadeIn('fast');
      } else if (h.charAt(0) == 1) {
        $('#SETcto_' + id).remove();
        $('#SETcto2_' + id).html('Comentario eliminado correctamente');
        $('#SETcto2_' + id).fadeIn('fast');
      }
    },
    error: function() {
      $('#SETcto_' + id).remove();
      Boxy.alert('Error, volver a intentar...', null, { title: 'Alerta' });
    }
  });
}

// Ignorar usuario
function ignorar(id, accion) {
  $.ajax({
    type: 'GET',
    url: boardUrl + '/web/cw-DESadminitr.php',
    data: 'acion=' + accion + '&user=' + id,
    success: function(h) {
      if (h.charAt(0) == 0) {
        alert(h.substring(3));
      } else {
        if (accion == 1) {
          $('#admitir').css('display', 'none');
          $('#des').css('display', 'block');
        } else if (accion == 2) {
          $('#admitir').css('display', 'block');
          $('#des').css('display', 'none');
        }
      }
    },
    error: function() {
      $('#gif_cargando_ign').hide();
      Boxy.alert('Error, volver a intentar...', null, { title: 'Alerta' });
    }
  });
}
    
// Agregar comentario muro
function add_muro(id) {
  if ($('#muro').val() == '') {
    $('#muro').focus();
    return;
  }

  $('.msg_add_muro').hide();
  $('#gif_cargando_add_muro').css('display', 'block');

  $.ajax({
    type: 'POST',
    url: boardUrl + '/web/cw-comentarMuro.php',
    data: 'muro=' + encodeURIComponent($('#muro').val()) + '&datapagss=' + encodeURIComponent($('#datapagss').val()) + '&user=' + id,
    success: function(h) {
      $('#gif_cargando_add_muro').css('display', 'none');

      if (h.charAt(0) == 0) {
        $('.msg_add_muro').html(h.substring(3));
        $('.msg_add_muro').addClass('noesta');
        $('.msg_add_muro').fadeIn('fast');
      } else {
        $('#return_agregar_muro').html(h.substring(3));
        $('#return_agregar_muro').fadeIn('fast');
        $('#cantmuro').html(parseInt($('#cantmuro').html()) + 1);
        $('#muro').val('Escribe algo...');

        if ($('#no_muro')) {
          $('#no_muro').fadeOut('fast');
        }

        if ($('#si_muro')) {
          $('#si_muro').fadeIn('fast');
        }
      }	
    },
    error: function() {
      $('#gif_cargando_add_muro').remove();
      Boxy.alert('Error, volver a intentar...', null, { title: 'Alerta' });
    }
  });
}

function AmigosActs() {
  $.ajax({
    type: 'GET',
    url: boardUrl + '/web/cw-AmistadesAct.php',
    success: function(h) {
      $('#amistadesACT').html(h.substring(3));
    },
    error: function() {
      Boxy.alert('Error, volver a intentar...', null, { title: 'Alerta' });
    }
  });
}

function elegir(tipo) {
  if (this.tipo == tipo) {
    return;
  }

  if (tipo == 'google') {
    $('input[name="buscador_tipo"]').val('g');
    $('#gb').addClass('activo');
    $('#cwb').removeClass('activo');
  } else if (tipo == 'casitaweb') {
    $('#cwb').addClass('activo');
    $('#gb').removeClass('activo');
    $('input[name="buscador_tipo"]').val('c');
  }
}
 
function errorrojos(q) {
  if (q == '') {
    $('input[name="q"]').focus();
    return false;
  }
}

// Sleep
function sleep(a) {
	var b = new Date().getTime();

	for (var i=0; i < 1e7; i++) {
		if ((new Date().getTime() - b) > a) {
			break;
		}
	}
}

var com = {
  // Crear shortnames
  crear_shortname_key: function(val) {
    $('#preview_shortname').html(val).removeClass('error').removeClass('ok');
    $('#msg_crear_shortname').html('');
  },
  crear_shortname_check_cache: new Array(),
  crear_shortname_check: function(val) {
    if (val == '') {
      return;
    }

    for (i = 0; i < this.crear_shortname_check_cache.length; i++) {
      // Verifico si ya lo busque
      if (this.crear_shortname_check_cache[i][0] === val) {
        // Lo tengo
        if (this.crear_shortname_check_cache[i][1] === '1') {
          // Disponible
          $('#preview_shortname').removeClass('error').addClass('ok');
          $('#msg_crear_shortname').html(this.crear_shortname_check_cache[i][2]).removeClass('error').addClass('ok');
        } else {
          // No disponible
          $('#preview_shortname').removeClass('ok').addClass('error');
          $('#msg_crear_shortname').html(this.crear_shortname_check_cache[i][2]).removeClass('ok').addClass('error');
        }

        return;			
      }
    }

    $('.gif_cargando#shortname').css('display', 'block');

    $.ajax({
      type: 'POST',
      url: boardUrl + '/web/cw-comunidadesChekLink.php',
      data: 'shortname=' + encodeURIComponent(val),
      success: function(h) {
        // Guardo los datos de verificacion
        com.crear_shortname_check_cache[com.crear_shortname_check_cache.length] = new Array(val, h.charAt(0), h.substring(3)); 

        $('.gif_cargando#shortname').css('display', 'none');

        switch(h.charAt(0)) {
          case '0': 
            // Error
            $('#preview_shortname').removeClass('ok').addClass('error');
            $('#msg_crear_shortname').html(h.substring(3)).removeClass('ok').addClass('error');
            break;
          case '1':
            // OK
            $('#preview_shortname').removeClass('error').addClass('ok');
            $('#msg_crear_shortname').html(h.substring(3)).removeClass('error').addClass('ok');
            break;
        }
      },
      error: function() {
        $('.gif_cargando#shortname').css('display', 'none');
        Boxy.alert('Error, volver a intentar...', null, { title: 'Alerta' });
      }
    });
  },
  tema_votar: function(voto, id) {
    $.ajax({
      type: 'POST',
      url: boardUrl + '/web/cw-comunidadesVotarTema.php',
      data: 'voto=' + voto + '&tema=' + id,
      success: function(h) {
        switch (h.charAt(0)) {
          case '0':
            // Error
            $('#votos_total2').html(h.substring(3)).removeClass('ok').addClass('error');
            break;
          case '1':
            // OK
            $('#votos_total').html(h.substring(3));
            break;
        }
      },
      error: function() {
        Boxy.alert('Error, volver a intentar...', null, { title: 'Alerta' });
      }
    });
  }
};
                
function actualizar_comentarios_com() {
  $('#ult_comm').fadeOut('fast');

  $.ajax({
    type: 'POST',
    url: boardUrl + '/web/cw-comunidadesActCom.php',
    success: function(h) {
      $('#ult_comm').html(h);
      $('#ult_comm').fadeIn('fast');
    }
  });
}

function DesplComOps2(id) {
  $('#dov_' + id).fadeOut('fast');
  $('#div_' + id).fadeOut('fast');
  $('#dev_' + id).fadeIn('fast');
}

function DesplComOps(id, id1, id2) {
  DesplComOps2(id2);
  DesplComOps2(id1);

  $('#dev_' + id).hide(1);
  $('#div_' + id).fadeIn('fast');
  $('#dov_' + id).fadeIn('fast');
}

// Eliminar amistad
function EliminarAmistad(user, id) {
  $.ajax({
    type: 'GET',
    url: boardUrl + '/web/cw-AmistadBorrar.php',
    data: 'user=' + user,
    success: function(h) {
      $('#amig_' + id).css('display', 'none');
      $('#error_' + id).html(h.substring(3));
      $('#error_' + id).css('display', 'block');
    },
    error: function() {
      Boxy.alert('Error, volver a intentar...', null, { title: 'Alerta' });
    }
  });
}

function ComComentar(id) {
  if ($('#editorCW').val() == '') {
    $('#editorCW').focus();
    return false;
  }

  $('#gif_cargando_add_comment').fadeIn('fast');

  $.ajax({
    type: 'POST',
    url: boardUrl + '/web/cw-comunidadesComentar.php',
    data: 'comentario=' + encodeURIComponent($('#editorCW').val()) + '&id=' + id,
    success: function(h) {
      $('#gif_cargando_add_comment').fadeOut('fast');

      if (h.charAt(0) == 0) {
        $('.msg_comentar').html(h.substring(3));
        $('.msg_comentar').addClass('noesta');
        $('.msg_comentar').fadeIn('fast');
      } else if (h.charAt(0) == 1) {
        $('.coment-user').hide();
        $('.msg_comentar').hide();
        $('#editorCW').val('');
        $('#nrocoment').html(parseInt($('#nrocoment').html()) + 1);
        $('#nuevocrio').html(h.substring(3));
        $('#nuevocrio').fadeIn('fast');
      }
    },
    error: function() {
      $('.msg_comentar').remove();
      $('#gif_cargando_add_comment').remove();
      Boxy.alert('Error, volver a intentar...', null, { title: 'Alerta' });
    }
  });
}

function login_ajax(donde) {
  var el = new Array();

  el['nick'] = $('#hd_loginbox #nickname');
  el['pass'] = $('#hd_loginbox #password');
  el['error'] = $('#hd_loginbox #login_error');
  el['cargando'] = $('#hd_loginbox #login_cargando');
  el['cuerpo'] = $('#hd_loginbox .login_cuerpo');
  el['button'] = $('#hd_loginbox input[type="submit"]');

  if (empty($(el['nick']).val())) {
    $(el['nick']).focus();
    return;
  } else if (empty($(el['pass']).val())) {
    $(el['pass']).focus();
    return;
  }

  $(el['error']).css('display', 'none');
  $(el['cargando']).css('display', 'block');
  $(el['button']).attr('disabled', 'disabled').addClass('disabled');

  $.ajax({
    type: 'POST',
    url: boardUrl + '/web/cw-Login.php',
    cache: false,
    data: 'nick=' + encodeURIComponent($(el['nick']).val()) + '&pass=' + encodeURIComponent($(el['pass']).val()),
    success: function(h) {
      if (h.charAt(0) == 0) {
        // Datos incorrectos
        $(el['error']).html(h.substring(3)).fadeIn('fast');
        $(el['nick']).focus();
        $(el['cargando']).css('display', 'none');
        $(el['button']).removeAttr('disabled').removeClass('disabled');
      } else if (h.charAt(0) == 1) {
        // OK
        if (donde == 'Home') {
          location.href = '/';
        } else {
          location.reload();
        }
      }

      // Suspendido
      if (h.charAt(0) == 2) {
        $(el['cargando']).css('display', 'none');
        $(el['cuerpo']).css('text-align', 'center').css('line-height', '150%').html(h.substring(3));
      }
    },
    error: function() {
      Boxy.alert('Error, volver a intentar...', null, { title: 'Alerta' });
    }
  });
}

function loginSeguridad() {
  var el = new Array();
  el['nick'] = $('#hd_loginbox2 #nickname');
  el['pass'] = $('#hd_loginbox2 #password');
  el['error'] = $('#hd_loginbox2 #login_error2');
  el['cargando'] = $('#hd_loginbox2 #login_cargando2');
  el['cuerpo'] = $('#hd_loginbox2 .login_cuerpo2');
  el['button'] = $('#hd_loginbox2 input[type="submit"]');
  
  if (empty($(el['nick']).val())) {
    $(el['nick']).focus();
    return;
  } else if (empty($(el['pass']).val())) {
    $(el['pass']).focus();
    return;
  }

  $(el['error']).css('display', 'none');
  $(el['cargando']).css('display', 'block');
  $(el['button']).attr('disabled', 'disabled').addClass('disabled');

  $.ajax({
    type: 'POST',
    url: boardUrl + '/web/cw-Login.php',
    cache: false,
    data: 'nick=' + encodeURIComponent($(el['nick']).val()) + '&pass=' + encodeURIComponent($(el['pass']).val()),
    success: function(h) {
      // Datos incorrectos
      if (h.charAt(0) == 0) {
        $(el['cargando']).css('display', 'none');
        $(el['error']).html(h.substring(3)).fadeIn('fast');
        $(el['nick']).focus();
        $(el['button']).removeAttr('disabled').removeClass('disabled');
      } else if (h.charAt(0) == 1) {
        // OK
        location.reload();
      }

      // Suspendido
      if (h.charAt(0) == 2) {
        $(el['cargando']).css('display', 'none');
        $(el['cuerpo']).css('text-align', 'center').css('line-height', '150%').html(h.substring(3));
      }
    },
    error: function() {
      Boxy.alert('Error, volver a intentar...', null, { title: 'Alerta' });
    }
  });
}

function AbrirCats() {
  if ($('.hdLoglink2').hasClass('opened')) {
    $('.hdLoglink2').removeClass('opened');
    $('.hd_loginbox2').css({'display': 'none'});
  } else {
    $('.hdLoglink2').addClass('opened');
    $('.hd_loginbox2').css({'display': 'block'});
  }
}

function servicenavlogin() {
  if ($('.hdLoglink').hasClass('opened')) {
    $('.hdLoglink').removeClass('opened');
    $('#hd_loginbox').css({'display': 'none'});
  } else {
    $('.hdLoglink').addClass('opened');
    $('#hd_loginbox').css({'display': 'block'});
  }
}

function notificaciones() {
  if ($('.hdLoglink3').hasClass('opened')) {
    $('.hdLoglink3').removeClass('opened');
    $('#hd_loginboxx3').css({'display': 'none'});
    $('.Sfvc22').css({'display': 'block'});
  } else {
    $('.hdLoglink3').addClass('opened');
    $('#hd_loginboxx3').css({'display': 'block'});
    $('.Sfvc22').css({'display': 'none'});
    NOTget();
  }
}

function NOTget() {
  if ($('#notificaciones_cuerpo').css('display') != 'block') {
    $('#NOT_cargando').css({'display': 'block'});
  }

  $.ajax({
    type: 'GET',
    url: boardUrl + '/web/cw-notificaciones.php',
    success: function(h) {		  
      $('#NOT_cargando').css({'display': 'none'});
      $('#notificaciones_cuerpo').css({'display': 'block'});
      $('#notificaciones_cuerpo').html(h.substring(0));
    }
  });
}

// Cerrar sesión
function salir() {
  $.ajax({
    type: 'GET',
    url: boardUrl + '/web/cw-salir.php',
    success: function(h) {
      location.reload();
    }
  });
}

// BBCode JSON
bbcode = {
  CasitaWSet: [
    { name: 'Negrita', key: 'B', openWith: '[b]', closeWith: '[/b]' },
    { name: 'Cursiva', key: 'I', openWith: '[i]', closeWith: '[/i]' },
    { name: 'Subrayado', key: 'U', openWith: '[u]', closeWith: '[/u]' },
    { name: 'Tachado', key: 'S', openWith: '[s]', closeWith: '[/s]' },
    { separator: '---------------' },
    { name: 'Texto alineado a la izquierda', openWith: '[left]', closeWith: '[/left]' }, 
    { name: 'Texto alineado al centro', openWith: '[center]', closeWith: '[/center]' }, 
    { name: 'Texto alineado a la derecha', openWith: '[right]', closeWith: '[/right]' }, 
    { separator: '---------------' },
    {
      name: 'Colores',
      dropMenu: [
        { openWith: '[color=#000000]', closeWith: '[/color]', className: 'c000000' },
        { openWith: '[color=#993300]', closeWith: '[/color]', className: 'c993300' },
        { openWith: '[color=#333300]', closeWith: '[/color]', className: 'c333300' },
        { openWith: '[color=#003300]', closeWith: '[/color]', className: 'c003300' },
        { openWith: '[color=#003366]', closeWith: '[/color]', className: 'c003366' },
        { openWith: '[color=#000080]', closeWith: '[/color]', className: 'c000080' },
        { openWith: '[color=#333399]', closeWith: '[/color]', className: 'c333399' },
        { openWith: '[color=#333333]', closeWith: '[/color]', className: 'c333333' },
        { openWith: '[color=#800000]', closeWith: '[/color]', className: 'c800000' },
        { openWith: '[color=#FF6600]', closeWith: '[/color]', className: 'cFF6600' },
        { openWith: '[color=#808000]', closeWith: '[/color]', className: 'c808000' },
        { openWith: '[color=#008000]', closeWith: '[/color]', className: 'c008000' },
        { openWith: '[color=#008080]', closeWith: '[/color]', className: 'c008080' },
        { openWith: '[color=#0000FF]', closeWith: '[/color]', className: 'c0000FF' },
        { openWith: '[color=#666699]', closeWith: '[/color]', className: 'c666699' },
        { openWith: '[color=#808080]', closeWith: '[/color]', className: 'c808080' },
        { openWith: '[color=#FF0000]', closeWith: '[/color]', className: 'cFF0000' },
        { openWith: '[color=#FF9900]', closeWith: '[/color]', className: 'cFF9900' },
        { openWith: '[color=#99CC00]', closeWith: '[/color]', className: 'c99CC00' },
        { openWith: '[color=#339966]', closeWith: '[/color]', className: 'c339966' },
        { openWith: '[color=#33CCCC]', closeWith: '[/color]', className: 'c33CCCC' },
        { openWith: '[color=#3366FF]', closeWith: '[/color]', className: 'c3366FF' },
        { openWith: '[color=#800080]', closeWith: '[/color]', className: 'c800080' },
        { openWith: '[color=#999999]', closeWith: '[/color]', className: 'c999999' },
        { openWith: '[color=#FF00FF]', closeWith: '[/color]', className: 'cFF00FF' },
        { openWith: '[color=#FFCC00]', closeWith: '[/color]', className: 'cFFCC00' },
        { openWith: '[color=#FFFF00]', closeWith: '[/color]', className: 'cFFFF00' },
        { openWith: '[color=#00FF00]', closeWith: '[/color]', className: 'c00FF00' },
        { openWith: '[color=#00FFFF]', closeWith: '[/color]', className: 'c00FFFF' },
        { openWith: '[color=#00CCFF]', closeWith: '[/color]', className: 'c00CCFF' },
        { openWith: '[color=#993366]', closeWith: '[/color]', className: 'c993366' },
        { openWith: '[color=#C0C0C0]', closeWith: '[/color]', className: 'cC0C0C0' },
        { openWith: '[color=#FF99CC]', closeWith: '[/color]', className: 'cFF99CC' },
        { openWith: '[color=#FFCC99]', closeWith: '[/color]', className: 'cFFCC99' },
        { openWith: '[color=#FFFF99]', closeWith: '[/color]', className: 'cFFFF99' },
        { openWith: '[color=#CCFFCC]', closeWith: '[/color]', className: 'cCCFFCC' },
        { openWith: '[color=#CCFFFF]', closeWith: '[/color]', className: 'cCCFFFF' },
        { openWith: '[color=#99CCFF]', closeWith: '[/color]', className: 'c99CCFF' },
        { openWith: '[color=#CC99FF]', closeWith: '[/color]', className: 'cCC99FF' },
        { openWith: '[color=#FFFFFF]', closeWith: '[/color]', className: 'cFFFFFF' }
      ]
    },
    {
      name: 'Medida de texto',
      dropMenu: [
        { name: 'Chiquita', openWith: '[size=8]', closeWith: '[/size]' },
        { name: 'Mediana', openWith: '[size=10]', closeWith: '[/size]' },
        { name: 'Normal', openWith: '[size=12]', closeWith: '[/size]' },
        { name: 'Gande', openWith: '[size=14]', closeWith: '[/size]' },
        { name: 'Muy grande', openWith: '[size=18]', closeWith: '[/size]' },
        { name: 'Gigante', openWith: '[size=24]', closeWith: '[/size]' }
      ]
    },
    {
      name: 'Fuente',
      dropMenu: [
        { name: 'Arial', stynn: ' style="font-family: Arial; "', openWith: '[font=arial]', closeWith: '[/font]' },
        { name: 'Arial Black', stynn: ' style="font-family: arial black; "', openWith: '[font=arial black]', closeWith: '[/font]' },
        { name: 'Comic Sans MS', stynn: ' style="font-family: comic sans ms; "', openWith: '[font=comic sans ms]', closeWith: '[/font]' },
        { name: 'Courier New', stynn: ' style="font-family: courier new; "', openWith: '[font=courier new]', closeWith: '[/font]' },
        { name: 'Georgia', stynn: ' style="font-family: georgia; "', openWith: '[font=georgia]', closeWith: '[/font]' },
        { name: 'Tahoma', stynn: ' style="font-family: tahoma; "', openWith: '[font=tahoma]', closeWith: '[/font]' },
        { name: 'Times New Roman', stynn: ' style="font-family: times new roman; "', openWith: '[font=times new roman]', closeWith: '[/font]' },
        { name: 'Verdana', stynn: ' style="font-family: verdana; "', openWith: '[font=verdana]', closeWith: '[/font]' }
      ]
    },
    { separator: '---------------' },
    { name: 'Enlace', beforeInsert: function(h) { cw_enlace(h); }},
    { name: 'Imagen', beforeInsert: function(h) { cw_img(h); }},
    { name: 'Video YouTube', beforeInsert: function(h) { cw_yt(h); }},
    { name: 'Video Google', beforeInsert: function(h) { cw_google(h); }},
    { name: 'Archivo SWF', beforeInsert: function(h) { cw_swf(h); }},
    { separator: '---------------' },
    { name: 'Code', openWith: '[code]', closeWith: '[/code]' },
    { name: 'Cita', openWith: '[quote]', closeWith: '[/quote]' }
  ]
};

$(document).ready(function() {
  $('#editorCW').editorCW(bbcode);
  $('.boxy').boxy();

  drawSocialLinks($('#social'));

  $('#salir_cw').click(function() {
    Boxy.confirm('&iquest;Est&aacute;s seguro que deseas salir de tu cuenta?', function() { salir() }, { title: 'Salir' });
    return false;
  });
});
