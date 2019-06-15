<div id="register-form" class="widecolumn">
    <?php if ( $attributes['show_title'] ) : ?>
    <h3><?php _e( 'Register', 'wptool' ); ?></h3>
    <?php endif; ?>

    <form id="signupform" action="<?php echo wp_registration_url(); ?>" method="post">
        <?php if ( count( $attributes['errors'] ) > 0 ) : ?>
        <?php
            foreach ( $attributes['errors'] as $error ) : ?>
        <p>
            <?php echo $error; ?>
        </p>
        <?php endforeach; ?>
        <?php endif; ?>
        <h2><?php _e('Application Form', 'wptool'); ?></h2>
        <div class="row">
            <div class="col-md-6">
                <p class="form-row">
                    <input type="text" class="form-control" name="first_name" id="first-name" value=""
                        placeholder="<?php _e('First name', 'wptool'); ?>" tabindex="1" required>
                </p>
            </div>
            <div class="col-md-6">
                <p class="form-row">
                    <input type="text" class="form-control" name="last_name" id="last-name" value=""
                        placeholder="<?php _e('Last name', 'wptool'); ?>" tabindex="2" required>
                </p>
            </div>
        </div>
        <div class="row <?php echo $hiddenClass ?>">
            <div class="col-md-6">
                <p class="form-row">
                    <input type="email" class="form-control" name="email" id="email" value=""
                        placeholder="<?php _e('Email', 'wptool'); ?>" tabindex="3" required>
                </p>
            </div>
            <div class="col-md-6">
                <p class="form-row">
                    <input type="email" class="form-control" name="email_verify" id="email_verify" value=""
                        placeholder="<?php _e('Verify Email', 'wptool'); ?>" tabindex="4" required>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <p class="form-row">
                    <input type="password" class="form-control" data-minlength="8" name="password" id="password" value=""
                        placeholder="<?php _e('Password', 'wptool'); ?>" tabindex="5" required>
                    <div id="validatePassword">
                        <p id="e1" style="display: none;color: red">Password must have 8 characters.</p>
                        <p id="e2" style="display: none;color: red">Password must have at least one letter.</p>
                        <p id="e3" style="display: none;color: red">Password must have at least one capital letter.</p>
                        <p id="e4" style="display: none;color: red">Password must have a number.</p>
                    </div>
                </p>
            </div>
            <div class="col-md-6">
                <p class="form-row">
                    <input type="password" class="form-control" name="verifyPassword" id="verifyPassword" value=""
                        data-match="#password" placeholder="<?php _e('Verify Password', 'wptool'); ?>"
                        tabindex="6" required>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <p class="form-row">
                <input type="text" class="form-control" name="custom" id="custom" placeholder="<?php _e('Custom', 'wptool'); ?>"
                        tabindex="6" required>
                </p>
            </div>

            <p class="signup-submit">
                <input type="submit" name="submit" class="register-button" value="<?php _e( 'Register now!', 'wptool' ); ?>" />    
            </p>
        </div>
    </form>
    <script>
    jQuery(function() {
        var password = document.getElementById("password"),
            confirm_password = document.getElementById("verifyPassword"),
            errorDiv = document.getElementById("validatePassword");
        var email = document.getElementById("email"),
            confirm_email = document.getElementById("email_verify");

        function validatePassword() {
            if (password.value.length < 8) {
                jQuery("#e1").show();
            } else {
                jQuery("#e1").hide();
            }

            if (!password.value.match(/[A-z]/)) {
                jQuery("#e2").show();
            } else {
                jQuery("#e2").hide();
            }

            if (!password.value.match(/[A-Z]/)) {
                jQuery("#e3").show();
            } else {
                jQuery("#e3").hide();
            }

            if (!password.value.match(/[0-9]/)) {
                jQuery("#e4").show();
            } else {
                jQuery("#e4").hide();
            }

            if (password.value != confirm_password.value) {
                confirm_password.setCustomValidity("<?php _e('Passwords Do Not Match') ?>");
                return;
            } else {
                password.setCustomValidity('');
                confirm_password.setCustomValidity('');
            }


        }

        function validateEmail() {

            if (email.value != confirm_email.value) {
                confirm_email.setCustomValidity("<?php _e('Emails Do Not Match') ?>");
                return;
            } else {
                confirm_email.setCustomValidity('');
            }
        }
        password.onchange = validatePassword;
        confirm_password.onkeyup = validatePassword;
        confirm_email.onkeyup = validateEmail;
    });
</div>