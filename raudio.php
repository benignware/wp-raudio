<?php

/**
 Plugin Name: Raudio
 Plugin URI: http://github.com/benignware-labs/wp-raudio
 Description: Webradioplayer for Wordpress
 Version: 1.0.0-beta.2
 Author: Rafael Nowrotek, Benignware
 Author URI: http://benignware.com
 License: UNLICENSED
*/

require_once 'lib/utils.php';
require_once 'lib/settings.php';
require_once 'lib/shortcode.php';
require_once 'lib/nav-menu.php';
require_once 'lib/widget.php';
// require_once 'lib/customize.php';

add_action('wp_footer', function() {
  $raudio_options = get_option( 'raudio_options' );
  $stream_url = $raudio_options['stream_url'];
  $autoplay = $raudio_options['autoplay'] ? 'autoplay' : '';

  $arr = [
    'id' => 'raudio-stream',
    'src' => $stream_url,
  ];

  if ($autoplay) {
    $arr['data-raudio-autoplay'] = 'autoplay';
  }

  $attrs = raudio_array_to_attrs($arr);

  // https://streams.radio.co/sd1bcd1376/listen#.mp3

  // FCK.fm
  // https://streamer.radio.co/s23eb1dc4e/listen#.mp3

  if ($stream_url) {
    echo <<< EOT
  <audio
    $attrs
    data-raudio
    data-turbolinks-permanent
    data-turbo-permanent
  ></audio>
EOT;
  }
});

// Enqueue Scripts
add_action('wp_enqueue_scripts', function() {
  wp_register_script('raudio', plugin_dir_url( __FILE__ ) . 'raudio.js', [], '1.0', true);
  wp_enqueue_script('raudio');
});

add_action('wp_enqueue_scripts', function() {
  wp_enqueue_style('raudio', plugin_dir_url( __FILE__ ) . 'skins/native.css' );
}, 9999);

// add_shortcode('raudio-player', function($atts = array()) {
//   extract(shortcode_atts(array(
//     'size' => 'medium',
//     'template' => plugin_dir_path( __FILE__ ) . 'templates/player.php' // Override like this: `get_theme_file_path() . '/raudio-player.php'`
//   ), $atts));
     
//   $output = raudio_render($template, array_merge(
//     $atts
//   ));

//   return $output;
// });

// add_shortcode('raudio-history', function($atts = array()) {
//   $atts = shortcode_atts(array(
//     'size' => 'medium',
//     'template' => plugin_dir_path( __FILE__ ) . 'templates/history.php', // Override like this: `get_theme_file_path() . '/raudio-player.php'`
//     'max_items' => 20
//   ), $atts);

//   extract($atts);
     
//   $output = raudio_render($template, $atts);

//   return $output;
// });


