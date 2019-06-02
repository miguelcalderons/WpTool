<?php
/**
 * Plugin Name:       WP Tools
 * Description:       A plugin to add register form with shortcode and edit user.
 * Version:           1.0.0
 * License:           GPL-2.0+
 * Author:            Miguel Calderón
 * Text Domain:       wptools
 */

require_once plugin_dir_path( __FILE__ ) . '/includes/register.php';


add_action('init', 'register_script');
function register_script() {
    wp_register_script( 'custom_jquery', plugins_url('/js/custom.js', __FILE__), array('jquery'), '2.5.1' );

    wp_register_style( 'style', plugins_url('/css/style.css', __FILE__), false, '1.0.0', 'all');
}

// use the registered jquery and style above
add_action('wp_enqueue_scripts', 'enqueue_style');

function enqueue_style(){
   wp_enqueue_script('custom_jquery');

   wp_enqueue_style( 'new_style' );
}