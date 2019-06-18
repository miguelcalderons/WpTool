<div id="password-lost-form" class="widecolumn">
    <?php if ( $attributes['show_title'] ) : ?>
        <h3><?php _e( 'Forgot Your Password?', 'wptool' ); ?></h3>
    <?php endif; ?>
    <p>
        <?php
            _e(
                "Enter your email address and we'll send you a link you can use to pick a new password.",
                'wptool'
            );
        ?>
    </p>
    <form id="lostpasswordform" action="<?php echo wp_lostpassword_url(); ?>" method="post">
        <p class="form-row">
            <label for="user_login"><?php _e( 'Email', 'wptool' ); ?></label>
            <input type="text" name="user_login" id="user_login">
        </p>
        <p class="lostpassword-submit">
            <input type="submit" name="submit" class="lostpassword-button" 
            value="<?php _e( 'Reset Password', 'wptool' ); ?>"/>
        </p>
    </form>
</div>