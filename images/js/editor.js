(function ($) {
  $.fn.editorCW = function (f, g) {
    var h, ctrlKey, shiftKey, altKey;
    ctrlKey = shiftKey = altKey = false;

    h = {
      id: '',
      nameSpace: 'bbcode',
      root: '',
      previewInWindow: '',
      previewAutoRefresh: false,
      previewPosition: '',
      previewTemplatePath: '',
      previewParserPath: '',
      previewParserVar: '',
      resizeHandle: false,
      beforeInsert: '',
      afterInsert: '',
      onEnter: {},
      onShiftEnter: {},
      onCtrlEnter: {},
      onTab: {},
      CasitaWSet: [{}]
    };

    $.extend(h, f, g);

    if (!h.root) {
      $('script').each(function (a, b) {
        miuScript = $(b).get(0).src.match(/(.*)jquery\.editorCW(\.pack)?\.js$/);

        if (miuScript !== null) {
          h.root = miuScript[1]
        }
      });
    }

    return this.each(function () {
      var d, textarea, levels, scrollPosition, caretPosition, caretOffset, clicked, hash, header, footer, previewWindow, template, iFrame, abort;
      d = $(this);

      textarea = this;
      levels = [];
      abort = false;
      scrollPosition = caretPosition = 0;
      caretOffset = -1;
      h.previewParserPath = localize(h.previewParserPath);
      h.previewTemplatePath = localize(h.previewTemplatePath);

      function localize(a, b) {
        if (b) {
          return a.replace(/("|')~\//g, "$1" + h.root)
        }
        return a.replace(/^~\//, h.root)
      }

      function init() {
        id = '';
        nameSpace = '';

        if (h.id) {
          id = 'id="' + h.id + '"'
        } else if (d.attr('id')) {
          id = 'id="editorCW' + (d.attr('id').substr(0, 1).toUpperCase()) + (d.attr('id').substr(1)) + '"'
        }

        if (h.nameSpace) {
          nameSpace = 'class="' + h.nameSpace + '"'
        }

        var b = parseInt($('textarea:#editorCW').css('width').replace('px', '')) + parseInt(6);

        d.wrap('<div ' + nameSpace + ' style="width: ' + b + 'px;"></div>');
        d.wrap('<div ' + id + ' class="editorCW png"></div>');
        d.wrap('<div class="editorCWContainer png"></div>');
        d.addClass("editorCWEditor png");

        header = $('<div class="editorCWHeader png"></div>').insertBefore(d);

        $(dropMenus(h.CasitaWSet)).appendTo(header);

        footer = $('<div class="editorCWFooter"></div>').insertAfter(d);

        d.keydown(keyPressed).keyup(keyPressed);
        d.bind('insertion', function (e, a) {
          if (a.target !== false) {
            get();
          }

          if (textarea === $.editorCW.focused) {
            CasitaW(a);
          }
        });

        d.focus(function () {
          $.editorCW.focused = this
        });
      }

      function dropMenus(b) {
        var c = $('<ul></ul>'),
          i = 0;

        $('li:hover > ul', c).css('display', 'block');

        $.each(b, function () {
          var a = this,
            t = '',
            title, li, j;

          title = (a.key) ? (a.name || '') + ' [Ctrl+' + a.key + ']' : (a.name || '');
          key = (a.key) ? 'accesskey="' + a.key + '"' : '';

          if (a.separator) {
            li = $('<li class="editorCWSeparator"></li>').appendTo(c);
          } else {
            i++;

            for (j = levels.length - 1; j >= 0; j--) {
              t += levels[j] + '-';
            }

            li = $('<li class="editorCWButton png editorCWButton' + t + (i) + ' ' + (a.className || '') + '"><a href="" ' + key + ' title="' + title + '"' + a.stynn + '>' + (a.name || '') + '</a></li>');
            li
            .bind('contextmenu', function () {
              return false
            })
            .click(function () {
              return false
            })
            .mousedown(function () {
              if (a.call) {
                eval(a.call)()
              }

              setTimeout(function () {
                CasitaW(a)
              }, 1);

              return false
            })
            .hover(function () {
              $('ul', this).show()
            }, function () {
              $('ul', this).hide()
            })
            .appendTo(c);

            if (a.dropMenu) {
              levels.push(i);
              $(li).addClass('editorCWDropMenu').append(dropMenus(a.dropMenu));
            }

            $('ul', this).click().hide();
          }
        });

        levels.pop();
        return c;
      }

      function magicCasitaWs(c) {
        if (c) {
          c = c.toString();

          c = c.replace(/\(\!\(([\s\S]*?)\)\!\)/g, function (x, a) {
            var b = a.split('|!|');

            if (altKey === true) {
              return (b[1] !== undefined) ? b[1] : b[0]
            } else {
              return (b[1] === undefined) ? "" : b[0]
            }
          });

          c = c.replace(/\[\!\[([\s\S]*?)\]\!\]/g, function (x, a) {
            var b = a.split(':!:');

            if (abort === true) {
              return false
            }

            value = prompt(b[0], (b[1]) ? b[1] : '');

            if (value === null) {
              abort = true
            }

            return value
          });

          return c;
        }

        return '';
      }

      function prepare(a) {
        if ($.isFunction(a)) {
          a = a(hash);
        }

        return magicCasitaWs(a);
      }

      function build(a) {
        openWith = prepare(clicked.openWith);
        placeHolder = prepare(clicked.placeHolder);
        replaceWith = prepare(clicked.replaceWith);
        closeWith = prepare(clicked.closeWith);

        if (replaceWith !== '') {
          block = openWith + replaceWith + closeWith
        } else if (selection === '' && placeHolder !== '') {
          block = openWith + placeHolder + closeWith
        } else {
          block = openWith + (a || selection) + closeWith
        }

        return {
          block: block,
          openWith: openWith,
          replaceWith: replaceWith,
          placeHolder: placeHolder,
          closeWith: closeWith
        }
      }

      function CasitaW(a) {
        var b, j, n, i;

        hash = clicked = a;

        get();

        $.extend(hash, {
          line: '',
          root: h.root,
          textarea: textarea,
          selection: (selection || ''),
          caretPosition: caretPosition,
          ctrlKey: ctrlKey,
          shiftKey: shiftKey,
          altKey: altKey
        });

        prepare(h.beforeInsert);
        prepare(clicked.beforeInsert);

        if (ctrlKey === true && shiftKey === true) {
          prepare(clicked.beforeMultiInsert)
        }

        $.extend(hash, {
          line: 1
        });

        if (ctrlKey === true && shiftKey === true) {
          lines = selection.split(/\r?\n/);

          for (j = 0, n = lines.length, i = 0; i < n; i++) {
            if ($.trim(lines[i]) !== '') {
              $.extend(hash, {
                line: ++j,
                selection: lines[i]
              });

              lines[i] = build(lines[i]).block;
            } else {
              lines[i] = '';
            }
          }

          string = {
            block: lines.join('\n')
          };

          start = caretPosition;
          b = string.block.length + (($.browser.opera) ? n : 0)
        } else if (ctrlKey === true) {
          string = build(selection);
          start = caretPosition + string.openWith.length;
          b = string.block.length - string.openWith.length - string.closeWith.length;
          b -= fixIeBug(string.block)
        } else if (shiftKey === true) {
          string = build(selection);
          start = caretPosition;
          b = string.block.length;
          b -= fixIeBug(string.block)
        } else {
          string = build(selection);
          start = caretPosition + string.block.length;
          b = 0;
          start -= fixIeBug(string.block)
        }

        if ((selection === '' && string.replaceWith === '')) {
          caretOffset += fixOperaBug(string.block);
          start = caretPosition + string.openWith.length;
          b = string.block.length - string.openWith.length - string.closeWith.length;
          caretOffset = d.val().substring(caretPosition, d.val().length).length;
          caretOffset -= fixOperaBug(d.val().substring(0, caretPosition))
        }

        $.extend(hash, {
          caretPosition: caretPosition,
          scrollPosition: scrollPosition
        });

        if (string.block !== selection && abort === false) {
          insert(string.block);
          set(start, b)
        } else {
          caretOffset = -1
        }

        get();

        $.extend(hash, {
          line: '',
          selection: selection
        });

        if (ctrlKey === true && shiftKey === true) {
          prepare(clicked.afterMultiInsert)
        }

        prepare(clicked.afterInsert);
        prepare(h.afterInsert);

        if (previewWindow && h.previewAutoRefresh) {
          refreshPreview();
        }

        shiftKey = altKey = ctrlKey = abort = false
      }

      function fixOperaBug(a) {
        if ($.browser.opera) {
          return a.length - a.replace(/\n*/g, '').length;
        }

        return 0;
      }

      function fixIeBug(a) {
        if ($.browser.msie) {
          return a.length - a.replace(/\r*/g, '').length;
        }

        return 0;
      }

      function insert(a) {
        if (document.selection) {
          var b = document.selection.createRange();
          b.text = a;
        } else {
          d.val(d.val().substring(0, caretPosition) + a + d.val().substring(caretPosition + selection.length, d.val().length));
        }
      }

      function set(a, b) {
        if (textarea.createTextRange) {
          if ($.browser.opera && $.browser.version >= 9.5 && b == 0) {
            return false;
          }

          range = textarea.createTextRange();
          range.collapse(true);
          range.moveStart('character', a);
          range.moveEnd('character', b);
          range.select()
        } else if (textarea.setSelectionRange) {
          textarea.setSelectionRange(a, a + b);
        }

        textarea.scrollTop = scrollPosition;
        textarea.focus();
      }

      function get() {
        textarea.focus();
        scrollPosition = textarea.scrollTop;

        if (document.selection) {
          selection = document.selection.createRange().text;

          if ($.browser.msie) {
            var a = document.selection.createRange(),
              rangeCopy = a.duplicate();

            rangeCopy.moveToElementText(textarea);
            caretPosition = -1;
            while (rangeCopy.inRange(a)) {
              rangeCopy.moveStart('character');
              caretPosition++
            }
          } else {
            caretPosition = textarea.selectionStart;
          }
        } else {
          caretPosition = textarea.selectionStart;
          selection = d.val().substring(caretPosition, textarea.selectionEnd);
        }

        return selection;
      }

      function keyPressed(e) {
        shiftKey = e.shiftKey;
        altKey = e.altKey;
        ctrlKey = (!(e.altKey && e.ctrlKey)) ? e.ctrlKey : false;

        if (e.type === 'keydown') {
          if (ctrlKey === true) {
            li = $('a[accesskey=' + String.fromCharCode(e.keyCode) + ']', header).parent('li');

            if (li.length !== 0) {
              ctrlKey = false;

              setTimeout(function () {
                li.triggerHandler('mousedown')
              }, 1);

              return false;
            }
          }

          if (e.keyCode === 13 || e.keyCode === 10) {
            if (ctrlKey === true) {
              ctrlKey = false;
              CasitaW(h.onCtrlEnter);
              return h.onCtrlEnter.keepDefault
            } else if (shiftKey === true) {
              shiftKey = false;
              CasitaW(h.onShiftEnter);
              return h.onShiftEnter.keepDefault
            } else {
              CasitaW(h.onEnter);
              return h.onEnter.keepDefault
            }
          }

          if (e.keyCode === 9) {
            if (shiftKey == true || ctrlKey == true || altKey == true) {
              return false;
            }

            if (caretOffset !== -1) {
              get();
              caretOffset = d.val().length - caretOffset;
              set(caretOffset, 0);
              caretOffset = -1;
              return false;
            } else {
              CasitaW(h.onTab);
              return h.onTab.keepDefault;
            }
          }
        }
      }

      init();
    })
  };

  $.fn.editorCWRemove = function () {
    return this.each(function () {
      var a = $(this).unbind().removeClass('editorCWEditor');
      a.parent('div').parent('div.editorCW').parent('div').replaceWith(a)
    })
  };

  $.editorCW = function (a) {
    var b = {
      target: false
    };

    $.extend(b, a);

    if (b.target) {
      return $(b.target).each(function () {
        $(this).focus();
        $(this).trigger('insertion', [b])
      })
    } else {
      $('textarea').trigger('insertion', [b])
    }
  }
})(jQuery);

function cw_img(h) {
  if (h.selection != '' && h.selection.substring(0, 7) == 'http://') {
    h.replaceWith = '[img]' + h.selection + '[/img]\n';
    h.openWith = '';
    h.closeWith = ''
  } else {
    var a = prompt('Ingresa la URL de la imagen', 'http://');
    if (a != null) {
      h.replaceWith = '[img]' + a + '[/img]\n';
      h.openWith = '';
      h.closeWith = ''
    } else {
      h.replaceWith = '';
      h.openWith = '';
      h.closeWith = ''
    }
  }
}

function cw_enlace(h) {
  if (h.selection == '') {
    var a = prompt('Ingresa la URL que deseas postear', 'http://');
    if (a != null) {
      h.replaceWith = '[url]' + a + '[/url]';
      h.openWith = '';
      h.closeWith = ''
    } else {
      h.replaceWith = '';
      h.openWith = '';
      h.closeWith = ''
    }
  } else if (h.selection.substring(0, 7) == 'http://' || h.selection.substring(0, 8) == 'https://' || h.selection.substring(0, 6) == 'ftp://') {
    h.replaceWith = '';
    h.openWith = '[url]';
    h.closeWith = '[/url]'
  } else {
    var a = prompt('Ingresa la URL que deseas postear', 'http://');
    if (a != null) {
      h.replaceWith = '';
      h.openWith = '[url=' + a + ']';
      h.closeWith = '[/url]'
    } else {
      h.replaceWith = '';
      h.openWith = '';
      h.closeWith = ''
    }
  }
}

function cw_yt(h) {
  if (h.selection != '' && h.selection.substring(0, 7) == 'http://') {
    h.replaceWith = '[youtube]' + h.selection + '[/youtube]\n';
    h.openWith = '';
    h.closeWith = ''
  } else {
    var a = prompt('Ingresa la URL o ID del video de YouTube', 'http://');
    if (a != null) {
      h.replaceWith = '[youtube]' + a + '[/youtube]\n';
      h.openWith = '';
      h.closeWith = ''
    } else {
      h.replaceWith = '';
      h.openWith = '';
      h.closeWith = ''
    }
  }
}

function cw_google(h) {
  if (h.selection != '' && h.selection.substring(0, 7) == 'http://') {
    h.replaceWith = '[gvideo]' + h.selection + '[/gvideo]\n';
    h.openWith = '';
    h.closeWith = ''
  } else {
    var a = prompt('Ingresa el ID del video de Google', 'http://');
    if (a != null) {
      h.replaceWith = '[gvideo]' + a + '[/gvideo]\n';
      h.openWith = '';
      h.closeWith = ''
    } else {
      h.replaceWith = '';
      h.openWith = '';
      h.closeWith = ''
    }
  }
}

function cw_swf(h) {
  if (h.selection != '' && h.selection.substring(0, 7) == 'http://') {
    h.replaceWith = '[swf]' + h.selection + '[/swf]\n';
    h.openWith = '';
    h.closeWith = ''
  } else {
    var a = prompt('Ingresa la URL del archivo SWF', 'http://');
    if (a != null) {
      h.replaceWith = '[swf]' + a + '[/swf]\n';
      h.openWith = '';
      h.closeWith = ''
    } else {
      h.replaceWith = '';
      h.openWith = '';
      h.closeWith = ''
    }
  }
}