<div class="footerTool">
	<div class="container"> 
		<div class="row">
            <div class="col-lg-6 textFooter">
                <h2><?php _e('Wp Tool Title', 'wptool') ?></h2>
                <p><?php _e('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.') ?></p>
            </div>
            <div class="col-lg-6 menu">
            <?php wp_nav_menu(
				array(
					'theme_location'  => 'primary',
					'container_id'    => '',
					'menu_class'      => 'menuHeader',
					'fallback_cb'     => '',
					'menu_id'         => '',
					'depth'           => 2,
				)
            ); ?>
            </div>
		</div>
    </div>
</div>