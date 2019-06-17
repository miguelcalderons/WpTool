<div class="login-form-container">
    <?php if ( $attributes['show_title'] ) : ?>
        <h2><?php _e( 'Sign In', 'wptool' ); ?></h2>
    <?php endif; 
    if ( count( $attributes['errors'] ) > 0 ) : ?>
        <?php foreach ( $attributes['errors'] as $error ) : ?>
            <p class="login-error">
                <?php echo $error; ?>
            </p>
        <?php endforeach; ?>
    <?php endif; ?>
    <div class="row">    
        <div class="col-lg-12">
            <?php
                wp_login_form(
                    array(
                    'label_username' => __( 'Username', 'wptool' ),
                    'label_log_in' => __( 'Sign In', 'wptool' ),
                    'redirect' => $attributes['redirect'],
                    )
                );
                ?>
        </div>
    </div>


    <a class="forgot-password" href="<?php echo wp_lostpassword_url(); ?>">
        <?php _e( 'Forgot your password?', 'wptool' ); ?>
    </a>
</div>