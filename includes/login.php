<?php

Class login {

    public function add_dependencies() {
        add_shortcode('custom-login-form', array ($this, 'render_login_form'));
        add_filter('authenticate', array ($this, 'maybe_redirect_at_authenticate'), 101, 3);
        add_action('wp_logout', array ($this, 'redirect_after_logout'));
        add_filter('login_redirect', array ($this, 'redirect_after_login'), 10, 3);
    }

    public function render_login_form( $attributes, $content = null ) {
        // Parse shortcode attributes

        $default_attributes = array( 'show_title' => false );
        $attributes = shortcode_atts( $default_attributes, $attributes );
        $show_title = $attributes['show_title'];

        if ( is_user_logged_in() ) {
            return __( 'You are already signed in.', 'wptool' );
        }

        // Check if user just updated password
        $attributes['password_updated'] = isset( $_REQUEST['password'] ) && $_REQUEST['password'] == 'changed';

        // Check if the user just requested a new password
        $attributes['lost_password_sent'] = isset( $_REQUEST['checkemail'] ) && $_REQUEST['checkemail'] == 'confirm';

        // Check if the user just registered
        $attributes['registered'] = isset( $_REQUEST['registered'] );

        // Pass the redirect parameter to the WordPress login functionality: by default,
        // don't specify a redirect, but if a valid redirect URL has been passed as
        // request parameter, use it.
        $attributes['redirect'] = '';
        if ( isset( $_REQUEST['redirect_to'] ) ) {
            $attributes['redirect'] = wp_validate_redirect( $_REQUEST['redirect_to'], $attributes['redirect'] );
        }

        // Check if user just logged out
        $attributes['logged_out'] = isset( $_REQUEST['logged_out'] ) && $_REQUEST['logged_out'] == true;

        $errors = array();
        if ( isset( $_REQUEST['login'] ) ) {
            $error_codes = explode( ',', $_REQUEST['login'] );

            foreach ( $error_codes as $code ) {
                $errors []= tools::get_error_message( $code );
            }
        }
        $attributes['errors'] = $errors;

        // Render the login form using an external template
        return tools::get_template_html( 'login_form', $attributes );

    }

    function redirect_to_custom_login() {
        if ( $_SERVER['REQUEST_METHOD'] == 'GET' ) {
            $redirect_to = isset( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : null;

            if ( is_user_logged_in() ) {
                $this->redirect_logged_in_user( $redirect_to );
                exit;
            }

            // The rest are redirected to the login page
            $login_url = home_url();
            if ( ! empty( $redirect_to ) ) {
                $login_url = add_query_arg( 'redirect_to', $redirect_to, $login_url );
            }

            wp_redirect( $login_url );
            exit;
        }
    }

    private function redirect_logged_in_user( $redirect_to = null ) {
        $user = wp_get_current_user();
        if ( user_can( $user, 'manage_options' ) ) {
            if ( $redirect_to ) {
                wp_safe_redirect( $redirect_to );
            } else {
                wp_redirect( admin_url() );
            }
        } else {
            wp_redirect( home_url( 'user-profile' ) );
        }
    }

    function maybe_redirect_at_authenticate( $user, $username, $password ) {
        if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
            if ( is_wp_error( $user ) ) {
                $error_codes = join( ',', $user->get_error_codes() );
     
                $login_url = home_url( 'login' );
                $login_url = add_query_arg( 'login', $error_codes, $login_url );
     
                wp_redirect( $login_url );
                exit;
            }
        }
     
        return $user;
    }

    public function redirect_after_logout() {
        wp_safe_redirect( home_url());
        exit;
    }

    public function redirect_after_login( $redirect_to, $requested_redirect_to, $user ) {

        $redirect_url = home_url();

        if ( ! isset( $user->ID ) ) {
            return $redirect_url;
        }

        if ( user_can( $user, 'manage_options' )) {
            // Use the redirect_to parameter if one is set, otherwise redirect to admin dashboard.
            $redirect_url = admin_url();
        } else {
            // Non-admin users always go to their account page after login
            $redirect_url = home_url();
        }

        return wp_validate_redirect( $redirect_url, home_url() );
    }

}