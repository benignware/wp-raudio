<?php

add_shortcode('raudio-player', function($atts = array()) {
  $atts = is_array($atts) ? $atts : [];
  $atts = shortcode_atts(array(
    'size' => 'medium',
    'template' => plugin_dir_path( __DIR__ ) . 'template/player.php' // Override like this: `get_theme_file_path() . '/raudio-player.php'`
  ), $atts);
     
  $output = raudio_render($atts['template'], array_merge(
    $atts
  ));

  return $output;
});

add_shortcode('raudio-history', function($atts = array()) {
  $atts = is_array($atts) ? $atts : [];
  $atts = shortcode_atts(array(
    'size' => 'medium',
    'template' => plugin_dir_path( __DIR__ ) . 'template/history.php', // Override like this: `get_theme_file_path() . '/raudio-player.php'`
    'offset' => 1,
    'max_items' => 10
  ), $atts);
     
  $output = raudio_render($atts['template'], array_merge(
    $atts
  ));

  return $output;
});