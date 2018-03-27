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

			$widget_name                          = themeisle_hestia_generate_unique_widget_name('woocommerce_widget_cart');
			$widget_index                         = trim( substr( $widget_name, strrpos( $widget_name, '-' ) + 1 ) );
			$active_widgets['sidebar-top-bar'][] = $widget_name;
			$cart_widget[ $widget_index ]         = array( 'title' => 'Cart' );
			update_option( 'widget_woocommerce_widget_cart', $cart_widget );


			$widget_name                         = themeisle_hestia_generate_unique_widget_name('woocommerce_product_search');
			$widget_index                        = trim( substr( $widget_name, strrpos( $widget_name, '-' ) + 1 ) );
			$active_widgets['sidebar-top-bar'][] = $widget_name;
			$search_widget[ $widget_index ]      = array( 'title' => 'Search' );
			update_option( 'widget_woocommerce_product_search', $search_widget );
			break;
		case 'blog_top':
			$widget_name                          = themeisle_hestia_generate_unique_widget_name('search');
			$widget_index                         = trim( substr( $widget_name, strrpos( $widget_name, '-' ) + 1 ) );
			$active_widgets['sidebar-top-bar'][] = $widget_name;
			$search_widget[$widget_index]         = array( 'title' => 'Search' );
			update_option( 'widget_search', $search_widget );
			break;
		case 'page_top':
			$widget_name                          = themeisle_hestia_generate_unique_widget_name('nav_menu');
			$widget_index                         = trim( substr( $widget_name, strrpos( $widget_name, '-' ) + 1 ) );
			$menu_id                              = themeisle_hestia_create_menu( 'socials' );
			$active_widgets['sidebar-top-bar'][] = $widget_name;
			$menu_widget[$widget_index]           = array(
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

/**
 * Generate new unique widget name.
 *
 * @param string $widget_name Widget name.
 *
 * @since 2.4.5
 * @return string
 */
function themeisle_hestia_generate_unique_widget_name( $widget_name ) {
	$current_sidebars = get_option( 'sidebars_widgets' );
	$all_widget_array = array();
	foreach ( $current_sidebars as $sidebar => $widgets ) {
		if ( ! empty( $widgets ) && is_array( $widgets ) && $sidebar != 'wp_inactive_widgets' ) {
			foreach ( $widgets as $widget ) {
				$all_widget_array[] = $widget;
			}
		}
	}
	$widget_index = 1;
	while ( in_array( $widget_name . '-' . $widget_index, $all_widget_array ) ) {
		$widget_index ++;
	}
	$new_widget_name = $widget_name . '-' . $widget_index;
	return $new_widget_name;
}


/**
 * Execute this function once to check all widgets and see if there are any duplicates.
 * If there are duplicates, remove that widget and generate a new one with same
 * data but a new id.
 *
 * @since 2.4.5
 */
function themeisle_hestia_fix_duplicate_widgets() {

	$load_default = get_option( 'hestia_fix_duplicate_widgets' );
	if ( $load_default !== false ) {
		return;
	}

	global $wp_registered_widgets;
	$current_sidebars = get_option( 'sidebars_widgets' );

	$duplicates = themeisle_hestia_get_duplicate_widgets();
	if(empty($duplicates)){
		return;
	}
	foreach ($duplicates as $widget){
		$old_widget_id = $widget['widget_id'];
		$old_widget_sidebar = $widget['sidebar'];
		$old_widget_index = array_search($old_widget_id,$current_sidebars[$old_widget_sidebar]);
		if( empty($old_widget_index)){
			return;
		}

		/* Remove the widget id and obtain the widget name */
		$old_widget_name = explode( '-', $old_widget_id );
		array_pop( $old_widget_name );
		$widget_name = implode('-', $old_widget_name);

		/* Get the id of new widget */
		$new_widget_name = themeisle_hestia_generate_unique_widget_name($widget_name);
		$new_widget_index  = trim( substr( $new_widget_name, strrpos( $new_widget_name, '-' ) + 1 ) );


		/* Get the options of old widget and update its id */
		$old_widget_options = $wp_registered_widgets[$old_widget_id];
		if(!empty($old_widget_options)) {
			if ( ! empty( $old_widget_options['params'] ) ) {
				unset( $old_widget_options['params'] );
			}
			if ( ! empty( $old_widget_options['callback'] ) ) {
				unset( $old_widget_options['callback'] );
			}
			if ( ! empty( $old_widget_options['id'] ) ) {
				unset( $old_widget_options['id'] );
			}
		} else {
			$old_widget_options = array();
		}

		$current_sidebars[$old_widget_sidebar][$old_widget_index] = $new_widget_name;
		$new_widget[ $new_widget_index ] = $old_widget_options;

		update_option( 'widget_'.$widget_name, $new_widget );
	}
	update_option( 'sidebars_widgets', $current_sidebars );

	update_option( 'hestia_fix_duplicate_widgets', true );
}


/**
 * Get an array of duplicate widgets and their sidebars.
 *
 * @since 2.4.5
 */
function themeisle_hestia_get_duplicate_widgets() {

	$current_sidebars = get_option( 'sidebars_widgets' );
	$all_widget_array = array();
	$duplicate_widgets = array();
	foreach ( $current_sidebars as $sidebar => $widgets ) {
		if ( ! empty( $widgets ) && is_array( $widgets ) && $sidebar != 'wp_inactive_widgets' ) {
			foreach ( $widgets as $widget ) {
				if( in_array($widget,$all_widget_array)){
					$duplicate_widgets[] = array(
						'widget_id' => $widget,
						'sidebar' => $sidebar
					);
				} else{
					$all_widget_array[] = $widget;
				}
			}
		}
	}
	return $duplicate_widgets;
}