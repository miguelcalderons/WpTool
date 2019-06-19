<?php 
    require_once plugin_dir_path( __FILE__ ) . 'tools.php';

    Class menu {

        public function add_dependencies()
        {
            add_action( 'init', array( $this, 'wptool_new_menu' ) );
            add_action( 'wp_head', array( $this, 'render_sidebar_menu' ));
        }

        public function render_sidebar_menu() {    
            echo tools::get_template_html( 'sidebar_menu', $attributes = null );
        }

        function wptool_new_menu() {
            register_nav_menu('primary',__( 'Primary Menu' ));
        }          
    }
?>