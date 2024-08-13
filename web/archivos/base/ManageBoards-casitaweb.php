<?php
function template_main() {
  global $context, $tranfer1, $settings, $options, $scripturl, $txt, $modSettings, $urlSep;

  echo '
    <div class="box_757">
      <div class="box_title" style="width: 752px;">
        <div class="box_txt box_757-34">
          <center>Categor&iacute;as</center>
        </div>
        <div class="box_rss">
          <img alt="" src="' . $tranfer1 . '/blank.gif" style="width: 16px; height: 16px;" border="0" />
        </div>
      </div>
    </div>
    <div class="windowbg" style="width:752px">
      <table>';

  if (!empty($context['move_board'])) {
    echo '
      <tr height="30">
        <td>
          <center>
            <b class"size11" style="color: red;">
              ' . $context['move_title'] . '
              [
              <a href="' . $scripturl . '?' . $urlSep . '=manageboards">' . $txt['mboards_cancel_moving'] . '</a>
              ]
            </b>
          </center>
        </td>
      </tr>';
  }

  foreach ($context['categories'] as $category) {
    echo '
      <tr>
        <td valign="top">';

    $alternate = false;

    foreach ($category['boards'] as $board) {
      $alternate = !$alternate;

      echo '
        <tr>
          <td>
            ' . $board['name'] . '
            <div class="link_resultado_opc">
              <a href="' . $scripturl . '?' . $urlSep . '=manageboards;move=' . $board['id'] . '">' . $txt['mboards_move'] . '</a>
              |
              <a href="' . $scripturl . '?' . $urlSep . '=manageboards;sa=board;boardid=' . $board['id'] . '">' . $txt['mboards_modify'] . '</a>
            </div>
          </td>
        </tr>';

      if (!empty($board['move_links'])) {
        $alternate = !$alternate;

        echo '
          <tr>
            <td style="padding-left: ' . (5 + 30 * $board['move_links'][0]['child_level']) . 'px;" colspan="4">';

        foreach ($board['move_links'] as $link) {
          echo '<a href="' . $link['href'] . '" style="color: green; padding-right: 13px; padding-left: 0px;" title="' . $link['label'] . '" class="size10">' . $link['label'] . '</a>';
        }

        echo '
            </td>
          </tr>';
      }
    }

    echo '
        </td>
      </tr>';
  }

  echo '
      </table>
    </div>';
}

function template_modify_category() {}

function template_modify_board() {
  global $context, $settings, $options, $tranfer1, $scripturl, $txt, $modSettings, $urlSep;

  echo '
    <form action="' . $scripturl . '?' . $urlSep . '=manageboards;sa=board2" method="post" accept-charset="' . $context['character_set'] . '">
      <input type="hidden" name="boardid" value="' . $context['board']['id'] . '" />
      <table border="0" width="757px" cellspacing="0" cellpadding="0" align="center">
        <tr>
          <td>
            <div class="box_757">
              <div class="box_title" style="width: 757px;">
                <div class="box_txt box_757-34">
                  <center>Modificar categor&iacute;a</center>
                </div>
                <div class="box_rss">
                  <img src="' . $tranfer1 . '/blank.gif" style="width: 16px; height: 16px;" border="0">
                </div>
              </div>
            </div>
            <table border="0" width="100%" cellspacing="1" cellpadding="4" class="windowbg">
              <td valign="top">
                <table border="0" width="100%" cellspacing="0" cellpadding="2">';

  if (isset($context['board']['is_new'])) {
    echo '
      <td>
        <b class="size11">' . $txt[43] . '</b>
        <br />
        <br />
      </td>
      <td valign="top" align="right">
        <select id="order" name="placement" onchange="this.form.boardOrder.disabled = this.options[this.selectedIndex].value == \'\';">
          ' . (!isset($context['board']['is_new']) ? '<option value="">(' . $txt['mboards_unchanged'] . ')</option>' : '') . '
          <option value="before">' . $txt['mboards_order_before'] . '...</option>
          <option value="after">' . $txt['mboards_order_after'] . '...</option>
        </select>&nbsp;&nbsp;';

    // The second select box lists all the boards in the category.
    echo '
      <select id="boardOrder" name="board_order" ' . (isset($context['board']['is_new']) ? '' : 'disabled="disabled"') . '>
        ' . (!isset($context['board']['is_new']) ? '<option value="">(' . $txt['mboards_unchanged'] . ')</option>' : '');

    foreach ($context['board_order'] as $order) {
      echo '
        <option' . ($order['selected'] ? ' selected="selected"' : '') . ' value="' . $order['id'] . '">' . $order['name'] . '</option>';
    }

    echo '
          </select>
        </td>
      </tr>
      <tr>';
  }

  echo '
      <td>
        <b class="size11">' . $txt[44] . ':</b><br />
        <span class="smalltext">' . $txt[672] . '</span><br /><br />
      </td>
      <td valign="top" align="right">
        <input type="text" onfocus="foco(this);" onblur="no_foco(this);" onfocus="foco(this);" onblur="no_foco(this);" name="board_name" value="' . $context['board']['name'] . '" size="30" />
      </td>
    </tr>
    <tr>
      <td>
        <b class="size11">Enlace de categor&iacute;a:</b>
        <br />
        <br />
      </td>
      <td valign="top" align="right">
        <input name="desc" size="30" value="' . $context['board']['description'] . '">
      </td>
    </tr>
    <tr>
      <td valign="top">
        <b class="size11">' . $txt['mboards_groups'] . '</b>
        <br />
        <br />
      </td>
      <td valign="top" align="right">';

  foreach ($context['groups'] as $group) {
    echo '
      <label for="groups_' . $group['id'] . '">
        <span' . ($group['is_post_group'] ? ' style="border-bottom: 1px dotted;" title="' . $txt['mboards_groups_post_group'] . '"' : '') . '>' . $group['name'] . '</span>
        <input type="checkbox" name="groups[]" value="' . $group['id'] . '" id="groups_' . $group['id'] . '"' . ($group['checked'] ? ' checked="checked"' : '') . ' />
      </label>
      <br />';
  }

  echo '
        <i>' . $txt[737] . '</i>
        <input type="checkbox" onclick="invertAll(this, this.form, \'groups[]\');" />
        <br />
        <br />
      </td>
    </tr>
    <tr>
      <td colspan="2" align="right">
        <br />';

  if (isset($context['board']['is_new'])) {
    echo '
      <input type="hidden" name="cur_cat" value="' . $context['board']['category'] . '">
      <input class="login" type="submit" name="add" value="' . $txt['mboards_new_board'] . '" onclick="return !isEmptyText(this.form.board_name);" />';
  } else {
    echo '
      <input class="login" type="submit" name="edit" value="' . $txt[17] . ' categor&iacute;a" onclick="return !isEmptyText(this.form.board_name);" />
      <input class="login" type="submit" name="delete" value="Eliminar categor&iacute;a" onclick="return confirm(\'' . $txt['boardConfirm'] . '\');" />';
  }

  echo '
        </td>
      </tr>
    </table>
    <input type="hidden" name="sc" value="' . $context['session_id'] . '" />';

  // If this board has no children don't bother with the next confirmation screen.
  if ($context['board']['no_children']) {
    echo '<input type="hidden" name="no_children" value="1" />';
  }

  echo '
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </form>';
}

// A template used when a user is deleting a board with child boards in it - to see what they want to do with them.
function template_confirm_board_delete() {
  global $context, $settings, $options, $scripturl, $txt, $urlSep;

  echo '
    <form action="' . $scripturl . '?' . $urlSep . '=manageboards;sa=board2" method="post" accept-charset="' . $context['character_set'] . '">
      <input type="hidden" name="boardid" value="' . $context['board']['id'] . '" />
      <table width="600" cellpadding="4" cellspacing="0" border="0" align="center" class="tborder">
        <tr class="titlebg">
          <td>' . $txt['mboards_delete_board'] . '</td>
        </tr>
        <tr class="windowbg">
          <td class="windowbg" valign="top">
            ' . $txt['mboards_delete_board_contains'] . ':
            <ul>';

  foreach ($context['children'] as $child) {
    echo '<li>' . $child['node']['name'] . '</li>';
  }

  echo '
          </ul>
        </td>
      </tr>
    </table>
    <br />
    <table width="600" cellpadding="4" cellspacing="0" border="0" align="center" class="tborder">
      <tr class="titlebg">
        <td>' . $txt['mboards_delete_what_do'] . ':</td>
      </tr>
      <tr>
        <td class="windowbg2">
          <label for="delete_action0">
            <input type="radio" id="delete_action0" name="delete_action" value="0" class="check" checked="checked" />
            ' . $txt['mboards_delete_board_option1'] . '
          </label>
          <br />
          <label for="delete_action1">
            <input type="radio" id="delete_action1" name="delete_action" value="1" class="check"' . (empty($context['can_move_children']) ? ' disabled="disabled"' : '') . ' />
            ' . $txt['mboards_delete_board_option2'] . '
          </label>:
          <select name="board_to" ' . (empty($context['can_move_children']) ? 'disabled="disabled"' : '') . '>';

  foreach ($context['board_order'] as $board) {
    if ($board['id'] != $context['board']['id'] && empty($board['is_child'])) {
      echo '
        <option value="' . $board['id'] . '">' . $board['name'] . '</option>';
    }
  }

  echo '
            </select>
          </td>
        </tr>
        <tr>
          <td align="center" class="windowbg2">
            <input class="login" type="submit" name="delete" value="' . $txt['mboards_delete_confirm'] . '" />
            <input class="login" type="submit" name="cancel" value="' . $txt['mboards_delete_cancel'] . '" />
          </td>
        </tr>
      </table>
      <input type="hidden" name="confirmation" value="1" />
      <input type="hidden" name="sc" value="' . $context['session_id'] . '" />
    </form>';
}

function template_modify_general_settings() {
  global $context, $settings, $options, $scripturl, $txt, $modSettings, $tranfer1, $urlSep;

  echo '
  <form action="' . $scripturl . '?' . $urlSep . '=manageboards;sa=settings" method="post" accept-charset="' . $context['character_set'] . '">
    <div class="box_757">
      <div class="box_title" style="width: 757px;">
        <div class="box_txt box_757-34">
          <center>Configuraci&oacute;n</center>
        </div>
        <div class="box_rss">
          <img src="' . $tranfer1 . '/blank.gif" style="width: 16px; height: 16px;" border="0" />
        </div>
      </div>
    </div>
    <table border="0" cellspacing="0" cellpadding="4" align="center" width="757px;" class="windowbg">';

  if ($context['can_change_permissions']) {
    echo '
      <tr class="windowbg2">
        <td width="50%" align="right" valign="top">
          <b class="size11">Grupos autorizados para administrar categor&iacute;as:</b>
        </td>
        <td width="50%">';

    theme_inline_permissions('manage_boards');

    echo '
        </td>
      </tr>
      <tr class="windowbg2">
        <td colspan="2">
          <div class="hrs"></div>
        </td>
      </tr>';
  }

  echo '
    <tr>
      <th width="50%" align="right" class="size11">
        <label for="recycle_enable_check">
          ' . $txt['recycle_enable'] . '
        </label>
        <span style="font-weight: normal;"></span>:
      </th>
      <td>
        <input type="checkbox" name="recycle_enable" id="recycle_enable_check"' . (empty($modSettings['recycle_enable']) ? '' : ' checked="checked"') . ' class="check" onclick="document.getElementById(\'recycle_board_select\').disabled = !this.checked;" />
      </td>
    </tr><tr>
      <th class="size11" align="right">' . $txt['recycle_board'] . ':</th>
      <td>
        <input type="hidden" name="recycle_board" value="' . (empty($modSettings['recycle_board']) ? '0' : $modSettings['recycle_board']) . '" />
        <select name="recycle_board" id="recycle_board_select">
          <option></option>';

  // var_dump($context['boards']);
  foreach ($context['boards'] as $board) {
    echo '
      <option value="' . $board['id'] . '"' . ($board['is_recycle'] ? ' selected="selected"' : '') . '>' . $board['name'] . '</option>';
  }

  echo '
            </select>
            <script language="JavaScript" type="text/javascript">
              <!-- // --><![CDATA[
                document.getElementById(\'recycle_board_select\').disabled = !document.getElementById(\'recycle_enable_check\').checked;
              // ]]>
            </script>
          </td>
        </tr>
        <tr class="windowbg2">
          <td align="right" colspan="2">
            <input class="login" type="submit" name="save_settings" value="' . $txt['mboards_settings_submit'] . '" />
          </td>
        </tr>
      </table>
      <input type="hidden" name="sc" value="' . $context['session_id'] . '" />
    </form>';
}

?>