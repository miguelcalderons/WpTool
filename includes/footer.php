<?php 
    require_once plugin_dir_path( __FILE__ ) . 'tools.php';

    Class footer {

        public function add_dependencies()
        {
            add_action( 'widgets_init',array( $this, 'wptool_widgets_init' ));
            add_action( 'wp_footer', array( $this, 'render_sidebar_menu' ));
        }

        public function render_sidebar_menu() {    
            echo tools::get_template_html( 'footer', $attributes = null );
        }
        function wptool_widgets_init() {
            register_sidebar( array(
                'name'          => __( 'WpTool widget', 'wptool' ),
                'id'            => 'wptoolWidget',
                'description'   => __( 'Add widgets here to appear in your footer.', 'wptool' ),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            ) );
        }
    }
?>