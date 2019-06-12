<?php

//require_once plugin_dir_path( __FILE__ ) . 'tools.php';


Class register {

    const WP_CUSTOM = 'wp_custom';
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

        // Intercept facebook leads email activation
        if (!empty($_REQUEST['utm_medium']) && $_REQUEST['utm_medium'] =='leads'
            && !empty($_REQUEST['utm_source']) && $_REQUEST['utm_source'] =='facebook')
            $attributes['prefill'] = [
                'email' => sanitize_text_field($_REQUEST['e'] ?? ''),
                'email_verify' => sanitize_text_field($_REQUEST['ev'] ?? ''),
                'password' => sanitize_text_field($_REQUEST['p'] ?? ''),
                'password_verify' => sanitize_text_field($_REQUEST['pv'] ?? ''),
                'first_name' => sanitize_text_field($_REQUEST['fn'] ?? ''),
                'last_name' => sanitize_text_field($_REQUEST['ln'] ?? ''),
                'custom' => sanitize_text_field($_REQUEST['c'] ?? ''),
            ];

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

    public function do_register_user() {
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
                $country = sanitize_text_field( $_POST['country'] );
                $custom = sanitize_text_field( $_POST['custom'] );
                $result = $this->register_user( $email, $first_name, $last_name, $password, $country);
                error_log(print_r($result, true));
                if ( is_wp_error( $result[0] ) ) {
                    // Parse errors into a string and append as parameter to redirect
                    $errors = join( ',', $result[0]->get_error_codes() );
                    $redirect_url = add_query_arg(array( 'register-errors' => $errors), $redirect_url );
                } else {
                    // Success, redirect to login or application form page
                    $redirect_url = home_url( 'register' );
                    $redirect_url = add_query_arg(array( 'registered' => $email), $redirect_url );
                    
                }
            }

            wp_redirect( $redirect_url );
            exit;
        }
    }

    public static function register_user($email, $first_name, $last_name, $password, $custom) {
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
            self::WP_CUSTOM . 'custom'=> $custom
        );
        $user_id = wp_insert_user( $user_data );

        if ($confirmed)
            AdeccoCandidateModel::activateUser($user_id);

        update_user_meta( $user_id, self::WP_CUSTOM . 'custom' , $custom );

        // if the user is confirmed already, don't send out notification email
        if (!$confirmed)
            wp_new_user_notification($user_id, null, 'user');
        else
            AdeccoCandidateModel::loginAs($user_id); // autologin if confirmed ;)


        return $user_id;
    }

    public static function crf_user_register( $user_id ) {
        if ( !current_user_can( 'edit_user', $user_id ) ) {
            return false;
        }
        if(!empty($_POST['custom'])) {
            update_user_meta( $user_id, self::WP_CUSTOM . 'custom', $_POST['custom'] );
        }

    }

    function crf_show_extra_profile_fields( $user ) {
        ?>
        <h3><?php esc_html_e( 'Application Information', 'Adecco_Login_Plugin' ); ?></h3>

        <table class="form-table">
            <tr>
                <th><label for="custom"><?php esc_html_e( 'custom', 'Adecco_Login_Plugin' ); ?></label></th>
                <td><input type="text" id="custom" name="custom"  value="<?php echo esc_html( get_the_author_meta( self::WP_CUSTOM . 'custom', $user->ID ) ); ?>" /></td>
            </tr>
        </table>
        <?php
    }
}