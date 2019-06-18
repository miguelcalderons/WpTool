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
require_once plugin_dir_path( __FILE__ ) . '/includes/login.php';
require_once plugin_dir_path( __FILE__ ) . '/includes/menu.php';

function enqueueAssets() { 
  wp_register_script( 'custom-js', plugins_url( '/js/custom.js' , __FILE__), array( 'jquery' )); 
  wp_register_style( 'custom-css', plugins_url( '/css/style.css' , __FILE__ ),'','', 'screen' );
  wp_enqueue_script( 'custom-js' );
  wp_enqueue_style( 'custom-css' );
}

add_action( 'wp_enqueue_scripts', 'enqueueAssets' );

class WpTool {

    /**
     * Initializes the plugin.
     *
     * To keep the initialization fast, only add filter and action
     * hooks in the constructor.
     */


    public function __construct() {
        //$login = new login();
        //$login->add_dependencies();
        $register = new register();
        $register->add_dependencies();
        $login = new login();
        $login->add_dependencies();
        $menu = new menu();
        $menu->add_dependencies();
        
        add_filter('query_vars', [$this, 'addQueryVars']);
        add_action( 'template_redirect', [$this, 'actionIntercept'] );
    }

    public static function addQueryVars($qvars)
    {
        $qvars[] = 'tool_form';
        return $qvars;
    }

public static function plugin_activated() {
    // Information needed for creating the plugin's pages
    $page_definitions = array(
      'user-profile' => array(
        'title' => __( 'Your Account', 'wptool' ),
        'content' => '[account-info]'
      ),
      'register' => array(
        'title' => __( 'Register', 'wptool' ),
        'content' => '[custom-register-form]'
      ),
      'profile' => array(
        'title' => __( 'Profile Information', 'wptool' ),
        'content' => '[custom-profile-form]'
      ),
      'member-password-lost' => array(
        'title' => __( 'Forgot Your Password?', 'wptool' ),
        'content' => '[custom-password-lost-form]'
      ),
      'member-password-reset' => array(
        'title' => __( 'Pick a New Password', 'wptool' ),
        'content' => '[custom-password-reset-form]'
      )
    );

    foreach ( $page_definitions as $slug => $page ) {
            // Check that the page doesn't exist already
            $query = new WP_Query( 'pagename=' . $slug );
            if ( ! $query->have_posts() ) {
                // Add the page using the data from the array above
                wp_insert_post(
                array(
                    'post_content'   => $page['content'],
                    'post_name'      => $slug,
                    'post_title'     => $page['title'],
                    'post_status'    => 'publish',
                    'post_type'      => 'page',
                    'ping_status'    => 'closed',
                    'comment_status' => 'closed',
                )
                );
            }
        }
    }
}

// Initialize the plugin
$WPTool = new WpTool();

require_once plugin_dir_path( __FILE__ ) . '/vendor/autoload.php';