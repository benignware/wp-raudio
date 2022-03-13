<?php

add_action('customize_register', function($wp_customize) {
  $wp_customize->add_section( 'raudio' , array(
    'title' => 'Raudio',
    'priority' => 100
  ));

  // Border width
  $wp_customize->add_setting( 'raudio_border_width', array(
    'capability' => 'edit_theme_options',
    'default' => '1px',
  ));
  
  $wp_customize->add_control( 'raudio_border_width', array(
    'type' => 'text',
    'section' => 'raudio',
    'label' => __( 'Border width' ),
    'description' => __( 'Specify border width' ),
  ));

  // Border radius
  $wp_customize->add_setting( 'raudio_border_radius', array(
    'capability' => 'edit_theme_options',
    'default' => '5px',
  ));
  
  $wp_customize->add_control( 'raudio_border_radius', array(
    'type' => 'text',
    'section' => 'raudio',
    'label' => __( 'Border radius' ),
    'description' => __( 'Specify border radius.' ),
  ));

  // Padding X
  $wp_customize->add_setting( 'raudio_padding_x', array(
    'capability' => 'edit_theme_options',
    'default' => '5px',
  ));
  
  $wp_customize->add_control( 'raudio_padding_x', array(
    'type' => 'text',
    'section' => 'raudio',
    'label' => __( 'Padding X' ),
    'description' => __( 'Specify horizontal padding.' ),
  ));
});

function raudio_enqueue_custom_css() {
  $border_width = get_theme_mod('raudio_border_width');
  $border_radius = get_theme_mod('raudio_border_radius');

  $css = <<< EOT
    :root {
      --raudio-border-width: $border_width;
      --raudio-border-radius: $border_radius;
    }
EOT;

  wp_register_style('raudio-custom-css', false, ['raudio'] );
  wp_enqueue_style('raudio-custom-css');
  wp_add_inline_style('raudio-custom-css', $css );
}

add_action( 'wp_enqueue_scripts', 'raudio_enqueue_custom_css', 100);
add_action( 'enqueue_block_editor_assets', 'raudio_enqueue_custom_css', 100);
