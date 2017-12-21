<?php
/**
 * Functions that are running in both Hestia Lite and Pro
 *
 * @author Themeisle
 * @package themeisle-companion
 */

/**
 * Change default alignment for top bar.
 */
function themeisle_hestia_top_bar_default_alignment(){
	return 'left';
}

/**
 * Add default content to clients section;
 */
function themeisle_hestia_clients_default_content(){
	$plugin_path = plugins_url( 'inc/img/', __FILE__ );
	return json_encode(
		array(
			array( 'image_url' => $plugin_path . 'clients1.png', 'link' => '#'),
			array( 'image_url' => $plugin_path . 'clients2.png', 'link' => '#'),
			array( 'image_url' => $plugin_path . 'clients3.png', 'link' => '#'),
			array( 'image_url' => $plugin_path . 'clients4.png', 'link' => '#'),
			array( 'image_url' => $plugin_path . 'clients5.png', 'link' => '#'),
		)
	);
}

/**
 * Function to load content in top bar.
 */
function themeisle_hestia_top_bar_default_content() {
	if ( class_exists( 'WooCommerce' ) ) {
		$top_bar_state = 'woo_top';
	} else {
		if ( 'page' == get_option('show_on_front') ) {
			$top_bar_state = 'page_top';
		} else {
			$top_bar_state = 'blog_top';
		}
	}

	$load_default = get_option( 'hestia_load_default' );
	if ( $load_default !== false ) {
		return;
	}

	switch ( $top_bar_state ) {
		case 'woo_top':
			themeisle_hestia_set_top_bar_menu( 'contact' );
			themeisle_hestia_set_top_bar_widgets( $top_bar_state );
			break;
		case 'blog_top':
			themeisle_hestia_set_top_bar_menu( 'socials' );
			themeisle_hestia_set_top_bar_widgets( $top_bar_state );
			break;
		case 'page_top':
			themeisle_hestia_set_top_bar_menu( 'contact' );
			themeisle_hestia_set_top_bar_widgets( $top_bar_state );
			break;
	}

	update_option( 'hestia_load_default', true );
}


/**
 * Set default widgets in top bar.
 *
 * @param string $type Top bar state.
 */
function themeisle_hestia_set_top_bar_widgets( $type ) {

	$active_widgets = get_option( 'sidebars_widgets' );

	if ( ! empty( $active_widgets['sidebar-top-bar'] ) ) :
		/* There is already some content. */
		return;
	endif;

	switch ( $type ) {
		case 'woo_top':
			$counter                              = 1;
			$active_widgets['sidebar-top-bar'][0] = 'woocommerce_widget_cart-' . $counter;
			$cart_widget[ $counter ]              = array( 'title' => 'Cart' );
			update_option( 'widget_woocommerce_widget_cart', $cart_widget );
			$counter++;

			$active_widgets['sidebar-top-bar'][] = 'woocommerce_product_search-' . $counter;
			$search_widget[ $counter ]           = array( 'title' => 'Search' );
			update_option( 'widget_woocommerce_product_search', $search_widget );
			break;
		case 'blog_top':
			$active_widgets['sidebar-top-bar'][0] = 'search-1';
			$search_widget[1]                     = array( 'title' => 'Search' );
			update_option( 'widget_search', $search_widget );
			break;
		case 'page_top':
			$menu_id                              = themeisle_hestia_create_menu( 'socials' );
			$active_widgets['sidebar-top-bar'][0] = 'nav_menu-1';
			$menu_widget[1]                       = array(
				'title'    => 'Socials',
				'nav_menu' => $menu_id,
			);
			update_option( 'widget_nav_menu', $menu_widget );
			break;
	}
	update_option( 'sidebars_widgets', $active_widgets );
}

/**
 * Set default menu in top bar.
 *
 * @param string $type Top bar state.
 */
function themeisle_hestia_set_top_bar_menu( $type ) {
	$theme_navs = get_theme_mod( 'nav_menu_locations' );
	if ( empty( $theme_navs['top-bar-menu'] ) ) {
		$menu_id                    = themeisle_hestia_create_menu( $type );
		$theme_navs['top-bar-menu'] = $menu_id;
		set_theme_mod( 'nav_menu_locations', $theme_navs );
	}
}

/**
 * Create default menu for top bar
 *
 * @param string $type Top bar state.
 */
function themeisle_hestia_create_menu( $type ) {

	$menu_name = 'Default Top Menu';
	if ( $type === 'socials' ) {
		$menu_name = 'Socials Top Menu';
	}

	$menu_exists = wp_get_nav_menu_object( $menu_name );
	if ( ! $menu_exists ) {

		$menu_id    = wp_create_nav_menu( $menu_name );
		$menu_items = array();
		switch ( $type ) {
			case 'contact':
				$menu_items = array(
					array(
						'title' => esc_html__('1-800-123-4567','themeisle-companion'),
						'url'   => esc_html__('tel:1-800-123-4567','themeisle-companion'),
					),
					array(
						'title' => esc_html__('friends@themeisle.com','themeisle-companion'),
						'url'   => esc_html__('mailto:friends@themeisle.com','themeisle-companion'),
					),
				);
				break;
			case 'socials':
				$menu_items = array(
					array(
						'title' => esc_html__('Facebook','themeisle-companion'),
						'url'   => esc_html__('www.facebook.com','themeisle-companion'),
					),
					array(
						'title' => esc_html__('Twitter','themeisle-companion'),
						'url'   => esc_html__('www.twitter.com','themeisle-companion'),
					),
					array(
						'title' => esc_html__('Google','themeisle-companion'),
						'url'   => esc_html__('www.google.com','themeisle-companion'),
					),
					array(
						'title' => esc_html__('Linkedin','themeisle-companion'),
						'url'   => esc_html__('www.linkedin.com','themeisle-companion'),
					),
					array(
						'title' => esc_html__('Instagram','themeisle-companion'),
						'url'   => esc_html__('www.instagram.com','themeisle-companion'),
					),
					array(
						'title' => esc_html__('Pinterest','themeisle-companion'),
						'url'   => esc_html__('www.pinterest.com','themeisle-companion'),
					),
					array(
						'title' => esc_html__('Youtube','themeisle-companion'),
						'url'   => esc_html__('www.youtube.com','themeisle-companion'),
					),
				);
				break;
		}
		foreach ( $menu_items as $menu_item ) {
			wp_update_nav_menu_item(
				$menu_id, 0, array(
					'menu-item-title'  => $menu_item['title'],
					'menu-item-url'    => $menu_item['url'],
					'menu-item-status' => 'publish',
				)
			);
		}
		return $menu_id;
	}
	return '';
}
