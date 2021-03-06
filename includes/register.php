<?php

require_once plugin_dir_path( __FILE__ ) . 'tools.php';

Class register {

    const WP_CUSTOM = 'wp_custom_';
    var $lastLang = 'en';
    public function add_dependencies()
    {
        add_shortcode( 'custom-register-form', array( $this, 'render_register_form' ) );
        add_action( 'login_form_register', array( $this, 'redirect_to_custom_register' ) );
        add_action( 'login_form_register', array( $this, 'do_register_user' ), 1 );
        add_action( 'edit_user_created_user',  array( $this, 'crf_user_register' ) );
        add_action( 'show_user_profile', array( $this, 'crf_show_extra_profile_fields') );
        add_action( 'edit_user_profile',array( $this, 'crf_show_extra_profile_fields') );
        add_action( 'personal_options_update', array( $this, 'crf_user_register') );
        add_action( 'edit_user_profile_update', array( $this, 'crf_user_register') );
    }

    public function render_register_form( $attributes, $content = null ) {
        
        // Parse shortcode attributes
        $default_attributes = array( 'show_title' => false );
        $attributes = shortcode_atts( $default_attributes, $attributes );
        $values = get_option('my_option_name');

        $attributes['errors'] = array();
        if ( isset( $_REQUEST['register-errors'] ) ) {
            $error_codes = explode( ',', $_REQUEST['register-errors'] );

            foreach ( $error_codes as $error_code ) {
                $attributes['errors'] []= tools::get_error_message( $error_code );
            }
        }

        // Retrieve possible errors from request parameters
        $attributes['errors'] = array();
        if ( isset( $_REQUEST['register-errors'] ) ) {
            $error_codes = explode( ',', $_REQUEST['register-errors'] );

            foreach ( $error_codes as $error_code ) {
                $attributes['errors'] []= tools::get_error_message( $error_code );
            }
        }

        return tools::get_template_html( 'register_form', $attributes );
    }

    public function redirect_to_custom_register() {
        if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
            if ( is_user_logged_in() ) {
                $this->redirect_logged_in_user();
            } else {
                //wp_redirect( home_url( 'register' ) );
            }
            exit;
        }
    }

    public static function do_register_user() {
        if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
            $redirect_url = home_url( 'register' );
            if ( ! get_option( 'users_can_register' ) ) {
                // Registration closed, display error
                $redirect_url = add_query_arg( 'register-errors', 'closed', $redirect_url );
            } else {
                $email = $_POST['email'];
                $first_name = sanitize_text_field( $_POST['first_name'] );
                $last_name = sanitize_text_field( $_POST['last_name'] );
                $password = sanitize_text_field( $_POST['password'] );
                $custom = sanitize_text_field( $_POST['custom'] );
                $languages = sanitize_text_field( $_POST['languages']);
                $result = Register::register_user( $email, $first_name, $last_name, $password, $custom, $languages);

                if ( is_wp_error( $result[0] ) ) {
                    // Parse errors into a string and append as parameter to redirect
                    $errors = join( ',', $result[0]->get_error_codes() );
                    $redirect_url = add_query_arg(array( 'register-errors' => $errors), $redirect_url );
                } else {
                    // Success, redirect to login or application form page
                    $redirect_url = home_url( 'login' );
                    $redirect_url = add_query_arg(array( 'registered' => $email), $redirect_url );
                    
                }
            }

            wp_redirect( $redirect_url );
            exit;
        }
    }

    public static function register_user($email, $first_name, $last_name, $password, $custom, $languages) {
        $errors = new WP_Error();
        // Email address is used as both username and email. It is also the only
        // parameter we need to validate

        if ( email_exists( $email ) ) {
            $errors->add( 'email_exists', tools::get_error_message( 'email_exists') );
            return array ($errors);
        }

        // Generate the password so that the subscriber will have to check email...

        $user_data = array(
            'user_email'    => $email,
            'user_login'    => $email,
            'user_pass'     => $password,
            'first_name'    => $first_name,
            'last_name'     => $last_name,
            'nickname'      => $first_name,
        );
        $user_id = wp_insert_user( $user_data );

        if(!empty($user_id)) {
            User::updateMetadata('custom', $user_id, $custom);
            User::updateMetadata('languages', $user_id, $languages);
        }
        return $user_id;
    }

    public static function crf_user_register( $user_id ) {
        printf("test: %s", $user_id);
        if ( !user_can(get_current_user_id(), 'administrator' ) ) {
            return false;
        }
        if(!empty($_POST['custom'])) {
            User::updateMetadata('custom', $user_id, $_POST['custom'] );
        }
        if(!empty($_POST['languages'])) {
            User::updateMetadata('languages', $user_id, $_POST['languages'] );
        }

        return true;

    }

    function crf_show_extra_profile_fields( $user ) {
        ?>
        <h3><?php esc_html_e( 'Application Information', 'wptool' ); ?></h3>

        <table class="form-table">
            <tr>
                <th><label for="custom"><?php esc_html_e( 'custom', 'wptool' ); ?></label></th>
                <td><input type="text" id="custom" name="custom"  value="<?php echo esc_html( User::getMetadata(  'custom', $user->ID ) ); ?>" /></td>
            </tr>
            <tr>
                <th><label for="languages"><?php esc_html_e( 'Languages', 'wptool' ); ?></label></th>
                <td><input type="text" id="languages" name="languages"  value="<?php echo esc_html( User::getMetadata( 'languages', $user->ID ) ); ?>" /></td>
            </tr>
        </table>
        <?php
    }
}