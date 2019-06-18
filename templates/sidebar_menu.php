<div id="menu-icon">
	<span></span>
	<span></span>
	<span></span>
</div>
<div id="mySidenav" class="sidenav menu">
			<!-- The WordPress Menu goes here -->
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