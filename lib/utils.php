<?php

function raudio_render($template, $data = array()) {
  foreach($data as $key => $value) {
    $$key = $data[$key];
  }

  ob_start();
  include $template;
  $output = ob_get_clean();

  return $output;
}

function raudio_dom_attrs($element) {
  $result = array();

  foreach ($element->attributes as $attrName => $attrNode) {
    $result[$attrName] = $attrNode->value;
  }

  return $result;
}

function raudio_dom_remove_children($element) {
  while ($element->hasChildNodes()) {
    $element->removeChild($element->firstChild);
  }
}

function raudio_array_to_attrs($array) {
  return join(" ", array_reduce(array_keys($array), function($as, $a) use ($array) {
    $as[] = sprintf('%s="%s"', $a, $array[$a]); return $as;
  }, array()));
}

