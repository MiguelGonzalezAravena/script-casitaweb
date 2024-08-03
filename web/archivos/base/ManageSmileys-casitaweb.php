<?php
function template_settings(){}
function template_editsets(){}
function template_modifyset(){}

function template_editsmileys() {
  global $context, $settings, $options, $scripturl, $txt, $modSettings, $tranfer1;

  // TO-DO: Mejorar código
  echo '
  <script type="text/javascript"><!-- // --><![CDATA[
    function makeChanges(action)
    {
      if (action == \'-1\')
        return false;
      else if (action == \'delete\')
      {
        if (confirm(\'', $txt['smileys_confirm'], '\'))
          document.forms.smileyForm.submit();
      }
      else
        document.forms.smileyForm.submit();
    }
  // ]]></script>
  <form action="/moderacion/emoticones/editsmileys" method="post" accept-charset="', $context['character_set'], '" name="smileyForm" id="smileyForm">
    <table border="0" cellspacing="1" cellpadding="4" align="center" width="100%" class="tborder">
      <tr>
        <td colspan="7" align="right" class="titlebg">
          <select name="set" onchange="changeSet(this.options[this.selectedIndex].value);">';

    foreach ($context['smiley_sets'] as $smiley_set)
      echo '
            <option value="', $smiley_set['path'], '"', $context['selected_set'] == $smiley_set['path'] ? ' selected="selected"' : '', '>', $smiley_set['name'], '</option>';

    echo '
          </select>
        </td>
      </tr><tr class="catbg3">
        <td></td>
        <td>'.$txt['smileys_code'].'</td><td>
        '.$txt['smileys_filename'].'</a></td><td>
        '.$txt['smileys_location'].'</a></td><td>
        '.$txt['smileys_description'].'</a></td><td>
          ', $txt['smileys_modify'], '
        </td>
        <td width="4%"></td>
      </tr>';
    foreach ($context['smileys'] as $smiley)
      echo '
      <tr class="windowbg2">
        <td valign="top">
          <a href="/moderacion/emoticones/modifysmiley;smiley=', $smiley['id'], '"><img src="'.$tranfer1.'/emoticones/'.$smiley['filename'].'" alt="', $smiley['description'], '" style="padding: 2px;" id="smiley', $smiley['id'], '" /><input type="hidden" name="smileys[', $smiley['id'], '][filename]" value="', $smiley['filename'], '" /></a>
        </td><td valign="top" style="font-family: monospace;">
          ', $smiley['code'], '
        </td><td valign="top" class="windowbg">
          ', $smiley['filename'], '
        </td><td valign="top">
          ', $smiley['location'], '
        </td><td valign="top" class="windowbg">
          ', $smiley['description'], empty($smiley['sets_not_found']) ? '' : '<br />
          <span class="smalltext"><b>' . $txt['smileys_not_found_in_set'] . ':</b> ' . implode(', ', $smiley['sets_not_found']) . '</span>', '
        </td><td valign="top">
          <a href="/moderacion/emoticones/modifysmiley;smiley=', $smiley['id'], '">', $txt['smileys_modify'], '</a>
        </td><td valign="top" align="center" width="4%">
          <input type="checkbox" name="checked_smileys[]" value="', $smiley['id'], '" class="check" />
        </td>
      </tr>';
    echo '
      <tr class="windowbg">
        <td colspan="7" align="right">
          <select name="smiley_action" onchange="makeChanges(this.value);">
            <option value="-1">', $txt['smileys_with_selected'], ':</option>
            <option value="-1">--------------</option>
            <option value="hidden">', $txt['smileys_make_hidden'], '</option>
            <option value="post">', $txt['smileys_show_on_post'], '</option>
            <option value="popup">', $txt['smileys_show_on_popup'], '</option>
            <option value="delete">', $txt['smileys_remove'], '</option>
          </select>
          <noscript><input type="submit" name="perform_action" value="', $txt[161], '" /></noscript>
        </td>
      </tr>
    </table>
    <input type="hidden" name="sc" value="', $context['session_id'], '" />
  </form>
  <script language="JavaScript" type="text/javascript"><!-- // --><![CDATA[
    function changeSet(newSet)
    {
      var currentImage, i, knownSmileys = [';

    $knownSmileys = array();
    foreach ($context['smileys'] as $smiley)
      $knownSmileys[] = $smiley['id'];

    echo implode(', ', $knownSmileys), '];

      for (i = 0; i < knownSmileys.length; i++)
      {
        currentImage = document.getElementById("smiley" + knownSmileys[i]);
        currentImage.src = "', $modSettings['smileys_url'], '/" + newSet + "/" + document.forms.smileyForm["smileys[" + knownSmileys[i] + "][filename]"].value;
      }
    }
  // ]]></script>';
}
function template_modifysmiley()
{
  global $context, $settings, $options, $scripturl, $txt, $modSettings, $tranfer1;

  echo '
  <form action="/moderacion/emoticones/editsmileys" method="post" accept-charset="', $context['character_set'], '" name="smileyForm" id="smileyForm">
    <table border="0" cellspacing="0" cellpadding="4" align="center" width="80%" class="tborder">
      <tr class="titlebg">
        <td colspan="2">', $txt['smiley_modify_existing'], '</td>
      </tr>
      <tr class="windowbg2">
        <td align="right"><b>', $txt['smiley_preview'], ': </b></td>
        <td><img src="'.$tranfer1.'/'.$context['current_smiley']['filename'].'" id="preview" alt="" /> (', $txt['smiley_preview_using'], ': <select name="set" onchange="updatePreview();">';

    foreach ($context['smiley_sets'] as $smiley_set)
      echo '
            <option value="', $smiley_set['path'], '"', $context['selected_set'] == $smiley_set['path'] ? ' selected="selected"' : '', '>', $smiley_set['name'], '</option>';

    echo '
          </select>)
        </td>
      </tr>
      <tr class="windowbg2">
        <td align="right"><b><label for="smiley_code">', $txt['smileys_code'], '</label>: </b></td>
        <td><input type="text" onfocus="foco(this);" onblur="no_foco(this);" name="smiley_code" value="', $context['current_smiley']['code'], '" /></td>
      </tr>
      <tr class="windowbg2">
        <td align="right"><b><label for="smiley_filename">', $txt['smileys_filename'], '</label>: </b></td>
        <td>';
      if (empty($context['filenames']))
        echo '
          <input type="text" onfocus="foco(this);" onblur="no_foco(this);" name="smiley_filename" value="', $context['current_smiley']['filename'], '" />';
      else
      {
        echo '
          <select name="smiley_filename" onchange="updatePreview();">';
        foreach ($context['filenames'] as $filename)
          echo '
            <option value="', $filename['id'], '"', $filename['selected'] ? ' selected="selected"' : '', '>', $filename['id'], '</option>';
        echo '
          </select>';
      }
      echo '
        </td>
      </tr>
      <tr class="windowbg2">
        <td align="right"><b><label for="smiley_description">', $txt['smileys_description'], '</label>: </b></td>
        <td><input type="text" onfocus="foco(this);" onblur="no_foco(this);" name="smiley_description" value="', $context['current_smiley']['description'], '" /></td>
      </tr>
      <tr class="windowbg2">
        <td align="right"><b><label for="smiley_location">', $txt['smileys_location'], '</label>: </b></td>
        <td>
          <select name="smiley_location">
            <option value="0"', $context['current_smiley']['location'] == 0 ? ' selected="selected"' : '', '>
              ', $txt['smileys_location_form'], '
            </option>
            <option value="1"', $context['current_smiley']['location'] == 1 ? ' selected="selected"' : '', '>
              ', $txt['smileys_location_hidden'], '
            </option>
            <option value="2"', $context['current_smiley']['location'] == 2 ? ' selected="selected"' : '', '>
              ', $txt['smileys_location_popup'], '
            </option>
          </select>
        </td>
      </tr>
      <tr class="windowbg2">
        <td align="right" colspan="2">
          <input type="submit" value="', $txt['smileys_save'], '" />
        </td>
      </tr>
    </table>
    <input type="hidden" name="sc" value="', $context['session_id'], '" />
    <input type="hidden" name="smiley" value="', $context['current_smiley']['id'], '" />
  </form>
  <script language="JavaScript" type="text/javascript"><!-- // --><![CDATA[
    function updatePreview()
    {
      var currentImage = document.getElementById("preview");
      currentImage.src = "', $modSettings['smileys_url'], '/" + document.forms.smileyForm.set.value + "/" + document.forms.smileyForm.smiley_filename.value;
    }
  // ]]></script>';
}

function template_addsmiley() {
  global $context, $settings, $options, $scripturl, $txt,$tranfer1, $modSettings, $boardurl;
  echo'<form action="' . $boardurl . '/moderacion/emoticones/addsmiley" method="post" accept-charset="', $context['character_set'], '" name="smileyForm" id="smileyForm" enctype="multipart/form-data">
    <table border="0" cellspacing="0" cellpadding="4" align="center" width="80%" class="tborder">
      <tr class="titlebg">
        <td colspan="2">', $txt['smileys_add_method'], ':</td>
      </tr>
      <tr class="windowbg" valign="bottom">
        <td style="padding-bottom: 2ex;" align="right" width="40%">
          <b><label for="smiley_filename">', $txt['smileys_filename'], '</label>: </b>
        </td>
        <td style="padding-bottom: 2ex;" width="60%">';
  if (empty($context['filenames'])) {
    /* value="' . $context['current_smiley']['filename'] . '" */
    echo '<input type="text" onfocus="foco(this);" onblur="no_foco(this);" name="smiley_filename" value="" onchange="selectMethod(\'existing\');" />';
  }
  echo'</td></tr>';
  echo'</table><br />
    <table width="80%" cellpadding="4" cellspacing="0" border="0" align="center" class="tborder">
      <tr class="titlebg">
        <td colspan="2">', $txt['smiley_new'], '</td>
      </tr>
      <tr class="windowbg2">
        <td align="right" width="40%"><b><label for="smiley_code">', $txt['smileys_code'], '</label>: </b></td>
        <td width="60%"><input type="text" onfocus="foco(this);" onblur="no_foco(this);" name="smiley_code" value="" /></td>
      </tr>
      <tr class="windowbg2">
        <td align="right" width="40%"><b><label for="smiley_description">', $txt['smileys_description'], '</label>: </b></td>
        <td width="60%"><input type="text" onfocus="foco(this);" onblur="no_foco(this);" name="smiley_description" value="" /></td>
      </tr>
      <tr class="windowbg2">
        <td align="right" width="40%"><b><label for="smiley_location">', $txt['smileys_location'], '</label>: </b></td>
        <td width="60%">
          <select name="smiley_location">
            <option value="0" selected="selected">
              ', $txt['smileys_location_form'], '
            </option>
            <option value="1">
              ', $txt['smileys_location_hidden'], '
            </option>
            <option value="2">
              ', $txt['smileys_location_popup'], '
            </option>
          </select>
        </td>
      </tr>
      <tr class="windowbg">
        <td align="right" colspan="2"><input class="login" type="submit" value="', $txt['smileys_save'], '" /></td>
      </tr>
    </table>
    <input type="hidden" name="sc" value="', $context['session_id'], '" />
  </form>';
}

function template_setorder()
{
  global $context, $settings, $options, $scripturl,$tranfer1, $txt, $modSettings;

  foreach ($context['smileys'] as $location)
  {
    echo '
  <br />
  <form action="/moderacion/emoticones/editsmileys" method="post" accept-charset="', $context['character_set'], '">
  <table border="0" cellspacing="1" cellpadding="4" align="center" width="80%" class="tborder" style="padding: 1px;">
      <tr class="titlebg">
        <td>', $location['title'], '</td>
      </tr>
      <tr class="windowbg">
        <td class="smalltext">', $location['description'], '</td>
      </tr>
      <tr class="windowbg2">
        <td>
          <b>', empty($context['move_smiley']) ? $txt['smileys_move_select_smiley'] : $txt['smileys_move_select_destination'], '...</b><br />';
    foreach ($location['rows'] as $row)
    {
      if (!empty($context['move_smiley']))
        echo '
          <a href="/moderacion/emoticones/setorder;location=', $location['id'], ';source=', $context['move_smiley'], ';row=', $row[0]['row'], ';sesc=', $context['session_id'], '"><img src="'.$tranfer1.'/emoticon_select.gif" alt="', $txt['smileys_move_here'], '" /></a>';

      foreach ($row as $smiley)
      {
        if (empty($context['move_smiley']))
          echo '<a href="/moderacion/emoticones/setorder;move=', $smiley['id'], '"><img src="'.$tranfer1.'/emoticones/', $smiley['filename'], '" style="padding: 2px; border: 0px solid black;" alt="', $smiley['description'], '" /></a>';
        else
          echo '<img src="'.$tranfer1.'/emoticones/', $smiley['filename'], '" style="padding: 2px; border: ', $smiley['selected'] ? '2px solid red' : '0px solid black', ';" alt="', $smiley['description'], '" /><a href="/moderacion/emoticones/setorder;location=', $location['id'], ';source=', $context['move_smiley'], ';after=', $smiley['id'], ';sesc=', $context['session_id'], '" title="', $txt['smileys_move_here'], '"><img src="'.$tranfer1.'/emoticon_select.gif" alt="', $txt['smileys_move_here'], '" /></a>';
      }

      echo '
          <br />';
    }
    if (!empty($context['move_smiley']))
      echo '
          <a href="/moderacion/emoticones/setorder;location=', $location['id'], ';source=', $context['move_smiley'], ';row=', $location['last_row'], ';sesc=', $context['session_id'], '"><img src="'.$tranfer1.'/emoticon_select.gif" alt="', $txt['smileys_move_here'], '" /></a>';
    echo '
        </td>
      </tr>
    </table>
  </form>';
  }
}


function template_editicons(){}

function template_editicon(){}

?>