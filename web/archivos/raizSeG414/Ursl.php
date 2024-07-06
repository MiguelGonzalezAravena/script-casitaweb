<?php

function urls($text) {
  return strtolower(preg_replace("/[^a-zA-Z0-9-]+/i", '', str_replace(' ', '-', iconv('UTF-8', 'ASCII//TRANSLIT', html_entity_decode($text)))));
}

?>