<?php
function traduccion($valor) {
  $valor = str_replace('topic', '<span style="color: #B97CFF;">Post:</span> ', $valor);
  $valor = str_replace('Imagen', '<span style="color: #B97CFF;">Imagen:</span> ', $valor);

  return $valor;
}

function template_main() {
  global $context, $db_prefix, $boardurl;

  if ($context['entries'] && $context['user']['is_admin']) {
    echo '
      <script type="text/javascript">
        function EliminarHM(){
          $.ajax({
            type: \'GET\',
            url: \'' . $boardurl . '/web/cw-EliminarMODlog.php\',
            success: function(h) {
              location.reload();
            }
          });
        }
      </script>';
  }

  if ($context['entries']) {
    echo '
      <table class="linksList size11" style="width: 922px;" id="nohaynada3">
        <thead>
          <tr>
            <th>&#191;Qu&eacute;?</th>
            <th>Acci&oacute;n</th>
            <th>Moderador</th>
            <th>Causa</th>
          </tr>
        </thead>
        <tbody>';

    foreach ($context['entries'] as $entry) {
      // var_dump($entry);
      echo '<tr>';

      if (isset($entry['extra']['member'])) {
        $request = db_query("
          SELECT realName
          FROM {$db_prefix}members
          WHERE ID_MEMBER = '{$entry['extra']['member']}'
          LIMIT 1", __FILE__, __LINE__);

        while ($row = mysqli_fetch_assoc($request)) {
          $iser = $row['realName'];
        }
      }

      echo '<td style="text-align: left;">';
      echo traduccion($entry['que']) . $entry['extra'][$entry['que']] . '<br />' . (isset($iser) ? 'Por: <a href="' . $boardurl . '/perfil/' . $iser . '">' . $iser . '</a>' : '');
      echo '</td>';
      echo '<td>' . $entry['action'] . '</td>
        <td>' . $entry['moderator']['link'] . '</td>';
      echo '<td>';
      echo isset($entry['extra']['causa']) ? $entry['extra']['causa'] : ' - ';
      echo '</td>';
      echo '</tr>';
    }

    echo '
        </tbody>
      </table>';

    $SYILEN = 'display: none;';
  } else {
    $SYILEN = 'display: block;';
  }

  echo '
    <div style="width: 922px;' . $SYILEN . '" id="nohaynada">
      <div class="noesta">No hay nada en el historial de moderaci&oacute;n.</div>
    </div>';

  if ($context['entries'] && $context['user']['is_admin']) {
    echo '
      <div style="width: 922px;" id="nohaynada2">
        <p align="right" style="margin-top: 5px;">
          <input class="login" type="button" onclick="EliminarHM(); return false;" name="removeall" value="Borrar historial" title="Borrar historial" />
        </p>
      </div>';
  }
}

?>