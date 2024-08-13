<?php
require_once(dirname(__FILE__) . '/cw-conexion-seg-0011.php');
global $context, $tranfer1, $ajaxError, $db_prefix, $ID_MEMBER;

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if (empty($context['ajax'])) {
  echo $ajaxError;
  die('Error de ajax.');
}

echo '<div style="float: left;">';

if (empty($id)) {
  echo '<div class="noesta" style="width: 774px;">Debes seleccionar una nota a editar.</div>';
} else {
  $notas = db_query("
    SELECT titulo, contenido, id
    FROM {$db_prefix}notas
    WHERE id_user = $ID_MEMBER
    AND id = $id", __FILE__, __LINE__);

  $row = mysqli_fetch_assoc($notas);
  $id2 = isset($row['id']) ? $row['id'] : '';
  $titulo = $row['titulo'];
  $contenido = $row['contenido'];

  mysqli_free_result($notas);

  if (empty($id2)) {
    echo '<div class="noesta" style="width: 776px;">La nota seleccionada no existe.</div>';
  } else {
    echo '
      <form action="' . $boardurl . '/web/cw-EditarNota.php" method="post" accept-charset="' . $context['character_set'] . '" enctype="multipart/form-data">
        <input type="text" title="Escribe el t&iacute;tulo..." onfocus="if (this.value == \'Escribe el t&iacute;tulo...\') this.value = \'\'; foco(this);" onblur="if (this.value == \'\') this.value = \'Escribe el t&iacute;tulo...\'; no_foco(this);" value="' . $titulo . '" style="width: 758px; font-family: arial; font-size: 12px;" name="titulo" id="titulo" maxlength="60" />
        <br />
        <textarea name="contenido" id="contenido" style="width: 758px; height: 185px; font-family: arial; font-size: 12px;" title="Escribe el contenido..." onfocus="if (this.value == \'Escribe el contenido...\') this.value = \'\'; foco(this);" onblur="if (this.value == \'\') this.value = \'Escribe el contenido...\'; no_foco(this);">' . $contenido . '</textarea>
        <br />
        <p align="right" style="margin: 0px; padding: 0px;">
          <input type="button" value="Salir sin guardar" class="close login" />
          <input type="submit" value="Salir y guardar" name="editar" class="login" />
          <input type="hidden" value="' . $id . '" name="id" />
        </p>
      </form>';
  }
}

?>