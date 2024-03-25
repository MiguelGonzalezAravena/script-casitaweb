<?php
$txt[130] = "Saludos,\nEl equipo " . $context['forum_name'] . '.';
$txt['smf15'] = 'No permitir respuestas.';
$txt['smf22'] = 'Opci&oacute;n';
$txt['smf41'] = 'Selecciona esto si deseas reinicializar todos los conteos de votos a 0.';
$txt['smf42'] = 'votos';
$txt['smf120'] = 'Tipos de archivos permitidos';
$txt['smf121'] = 'Tama&ntilde;o m&aacute;ximo del attachment';
$txt['smf123'] = 'No puedes subir ese tipo de archivo. Las &uacute;nicas extensiones permitidas son';
$txt['smf129'] = 'Para borrar tu attachment, deja esto vac&iacute;o.';
$txt['smf130'] = 'Deselecciona los archivos adjuntos que desees eliminar';
$txt['smf130b'] = 'Ese es un nombre de archivo restringido. Por favor intenta un nombre de archivo diferente.';
$txt['smf287'] = '';
$txt['rtm4'] = 'por';
$txt['rtm10'] = 'Enviar';

$txt['maxAttachPerPost'] = 'por mensaje';
$txt['sticky_after2'] = 'Fijar este post.';
$txt['move_after2'] = 'Mover este post.';
$txt['back_to_topic'] = 'Regresar a este post.';


$txt['poll_options'] = 'Opciones de la encuesta';
$txt['poll_options1a'] = 'Correr la encuesta por';
$txt['poll_options1b'] = 'd&iacute;as. (dejarlo en blanco, para ning&uacute;n l&iacute;mite)';
$txt['poll_options2'] = 'Mostrar los resultados de la encuesta a cualquiera.';
$txt['poll_options3'] = 'Solamente muestra los resultados despu&eacute;s de haber votado.';
$txt['poll_options4'] = 'Solamente muestra los resultados despu&eacute;s que la encuesta haya expirado.';
$txt['poll_options5'] = 'M&aacute;ximo de votos por usuario.';
$txt['poll_options7'] = 'Permitirle al usuario cambiar su voto.';
$txt['poll_error1'] = 'Seleccionaste demasiadas opciones - el m&aacute;ximo permitido es %s';
$txt['poll_add_option'] = 'Agregar Opci&oacute;n';

$txt['spellcheck_done'] = 'Revisi&oacute;n de ortograf&iacute;a completada.';
$txt['spellcheck_change_to'] = 'Cambiar a:';
$txt['spellcheck_suggest'] = 'Sugerencias:';
$txt['spellcheck_change'] = 'Cambiar';
$txt['spellcheck_change_all'] = 'Cambiar Todo';
$txt['spellcheck_ignore'] = 'Ignorar';
$txt['spellcheck_ignore_all'] = 'Ignorar Todo';

$txt['more_attachments'] = 'm&aacute;s archivos';
// Don't use entities in the below string.
$txt['more_attachments_error'] = 'Lo sentimos, no est&#225;sautorizado para adjuntar m&#225;s archivos.';

$txt['more_smileys'] = 'm&aacute;s';
$txt['more_smileys_title'] = 'Smileys adicionales';
$txt['more_smileys_pick'] = 'Selecciona un smiley';
$txt['more_smileys_close_window'] = 'Cerrar ventana';

$txt['error_new_reply'] = 'Advertencia - mientras estabas escribiendo, una nueva respuesta fue publicada. Probablemente desees revisar tu mensaje.';
$txt['error_new_replies'] = 'Advertencia - mientras estabas escribiendo, fueron publicadas %d respuestas. Probablemente desees revisar tu mensaje.';
$txt['error_new_reply_reading'] = 'Advertencia - mientras estabas leyendo, una nueva respuesta fue publicada. Probablemente desees revisar tu mensaje.';
$txt['error_new_replies_reading'] = 'Advertencia - mientras estabas leyendo, fueron publicadas %d respuestas. Probablemente desees revisar tu mensaje.';
$txt['error_old_topic'] = 'Advertencia: no se han publicado mensajes en este post por aproximadamente ' . $modSettings['oldTopicDays'] . ' d&iacute;as.<br />A menos que est&eacute;s seguro que realmente deseas responder, por favor considera mejor crear un nuevo post.';

// Use numeric entities in the below sixteen strings.
$txt['notification_reply_subject'] = 'Respuesta al post: %s';
$txt['notification_reply'] = 'Una respuesta por %s ha sido publicada en un post que est&#225;s monitoreando.' . "\n\n" . 'Puedes verla en ';
$txt['notification_sticky_subject'] = 'post fijado: %s';
$txt['notification_sticky'] = 'Un post que estabas monitoreando ha sido marcado como post fijado por %s.' . "\n\n" . 'Ver el post en: ';
$txt['notification_lock_subject'] = 'post bloqueado comentarios: %s';
$txt['notification_lock'] = 'Un post que estabas monitoreando se bloquearon los comentarios por %s.' . "\n\n" . 'Ver el post en: ';
$txt['notification_unlock_subject'] = 'post habilitado comentarios: %s';
$txt['notification_unlock'] = 'Un post que estabas monitoreando se habilito los comentarios por %s.' . "\n\n" . 'Ver el post en: ';
$txt['notification_remove_subject'] = 'post eliminado: %s';
$txt['notification_remove'] = 'Un post que estabas monitoreando ha sido eliminado por %s.';
$txt['notification_move_subject'] = 'post movido: %s';
$txt['notification_move'] = 'Un post que estabas monitoreando ha sido movido a otro foro por %s.' . "\n\n" . 'Ver el post en: ';
$txt['notification_merge_subject'] = 'post combinado: %s';
$txt['notification_merge'] = 'Un post que estabas monitoreando ha sido combinado con otro post por %s.' . "\n\n" . 'Ver el nuevo post combinado en: ';
$txt['notification_split_subject'] = 'post dividido: %s';
$txt['notification_split'] = 'Un post que estabas monitoreando ha sido dividido en dos o m&aacute;s posts por %s.' . "\n\n" . 'Ver lo que queda de este post en: ';

// Use numeric entities in the below two strings.
$txt['notification_reply_body'] = 'El texto de la respuesta se muestra debajo:';
$txt['notification_new_topic_body'] = 'El texto del post se muestra debajo:';

$txt['announce_this_topic'] = 'Enviar un anuncio sobre este post a los usuarios:';
$txt['announce_title'] = 'Enviar un anuncio';
$txt['announce_desc'] = 'Este formulario te permite enviar un anuncio sobre este post a los grupos de usuarios seleccionados.';
$txt['announce_sending'] = 'Enviando anuncio del post';
$txt['announce_done'] = 'hecho';
$txt['announce_continue'] = 'Continuar';
$txt['announce_topic'] = 'post Anunciado.';
$txt['announce_regular_members'] = 'Usuarios Habituales';

?>