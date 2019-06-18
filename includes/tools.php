<?php

class tools {

    public static function get_template_html( $template_name, $attributes = null ) {
        if ( ! $attributes ) {
            $attributes = array();
        }

        ob_start();

        do_action( 'personalize_login_before_' . $template_name );

        require(plugin_dir_path( __DIR__ ) .  'templates/' . $template_name . '.php');

        do_action( 'personalize_login_after_' . $template_name );

        $html = ob_get_contents();
        ob_end_clean();

        return $html;
    }

    public static function get_error_message( $error_code ) {
        error_log($error_code);
        switch ( $error_code ) {
            case 'empty_username':
                return __( 'You need to enter your email address to continue.', 'wptool' );

            case 'invalid_email':
            case 'invalidcombo':
                return __( 'There are no users registered with this email address.', 'wptool' );

            case 'empty_password':
                return __( 'You need to enter a password to login.', 'wptool' );

            case 'invalid_username':
                return __(
                  "We don't have any users with that email address. Maybe you used a different one when signing up?",
                  'wptool'
                );
            case 'email':
                return __( 'The email address you entered is not valid.', 'wptool' );

            case 'email_exists':
                return __( 'An account exists with this email address.', 'wptool' );

            case 'apiError':
                return __( 'Verify your username or password or register', 'wptool' );

            case 'email':
                return __( 'The email address you entered is not valid.', 'wptool' );

            case 'email_exists':
                return __( 'An account exists with this email address.', 'wptool' );

            case 'existing_user_login':
                return __( 'An account exists with this email address.', 'wptool' );

            case 'closed':
                return __( 'Registering new users is currently not allowed.', 'wptool' );

            case 'not_registered':
                return __( 'You are not registered in detto fra noi, are you registered?.', 'wptool' );

            case 'captcha':
                return __( 'The Google reCAPTCHA check failed. Are you a robot?', 'wptool' );

            case 'expiredkey':
            case 'invalidkey':
                return __( 'The password reset link you used is not valid anymore.', 'wptool' );

            case 'password_reset_mismatch':
                return __( "The two passwords you entered don't match.", 'wptool' );

            case 'password_reset_empty':
                return __( "Sorry, we don't accept empty passwords.", 'wptool' );
            case 'finished_register':
                return __( "Sorry, we don't accept more registrations from that country.", 'wptool' );

            case 'incorrect_password':
                $err = __(
                  "The password you entered wasn't quite right. <a href='%s'>Did you forget your password</a>?",
                  'wptool'
                );
                return sprintf( $err, wp_lostpassword_url() );

            default:
                break;
        }

        return __( 'An unknown error occurred. Please try again later.', 'wptool' );
    }
}