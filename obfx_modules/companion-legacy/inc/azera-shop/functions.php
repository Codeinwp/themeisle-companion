<?php
/*
 * Azera Shop Companion
 */

if ( ! function_exists( 'add_action' ) ) {
	die( 'Nothing to do...' );
}

/* Important constants */
define( 'AZERA_SHOP_COMPANION_VERSION', '1.0.7' ); /* trebuie inlocuit cu constanta de OBFX version */
define( 'AZERA_SHOP_COMPANION_URL', plugin_dir_url( __FILE__ ) ); /* de scos */
define( 'AZERA_SHOP_COMPANION_PATH', plugin_dir_path( __FILE__ ) ); /* de scos */

/* TODO: de verificat text-doamin si la ShopIsle */

/**
 * Require section translations
 */
require AZERA_SHOP_COMPANION_PATH . 'inc/translations/general.php';

/* Required helper functions */
include_once( dirname( __FILE__ ) . '/inc/settings.php' );

/* Add new sections in Azera Shop */
function azera_shop_companion_sections() {
	return array(
	
			'sections/azera_shop_logos_section',
			'azera_shop_our_services_section',
			'sections/azera_shop_shop_section',
			'azera_shop_our_team_section',
			'azera_shop_happy_customers_section',
			'sections/azera_shop_shortcodes_section',
			'sections/azera_shop_ribbon_section',
			'sections/azera_shop_contact_info_section',
			'sections/azera_shop_map_section'
			
			);
}

///* Check if Azera Shop theme is activated */
//
//if ( ! empty ( $GLOBALS['pagenow'] ) && 'plugins.php' === $GLOBALS['pagenow'] ) {
//    add_action( 'admin_notices', 'azera_shop_companion_admin_notices', 0 );
//}
//
//function azera_shop_companion_requirements() {
//
//	$azera_shop_companion_errors = array();
//	$theme = wp_get_theme();
//
//	if ( ('Azera Shop' != $theme->name) && ('Azera Shop' != $theme->parent_theme) ) {
//
//		$azera_shop_companion_errors[] = __( 'You need to have <a href="https://wordpress.org/themes/azera-shop/" target="_blank">Azera Shop</a> theme in order to use Azera Shop Companion plugin.','azera-shop-companion' );
//	}
//
//	if( defined('AZERA_SHOP_PLUS_PATH') ){
//		$azera_shop_companion_errors[] = __( 'There is no need for activating Azera Shop Companion. You already have the Plus version of Azera Shop which includes this plugin.','azera-shop-companion');
//	}
//
//	return $azera_shop_companion_errors;
//
//}
//
//function azera_shop_companion_admin_notices() {
//
//    $azera_shop_companion_errors = azera_shop_companion_requirements();
//
//    if ( empty ( $azera_shop_companion_errors ) )
//        return;
//
//    /* Suppress "Plugin activated" notice. */
//    unset( $_GET['activate'] );
//
//	echo '<div class="notice error my-acf-notice is-dismissible">';
//		echo '<p>'.join($azera_shop_companion_errors).'</p>';
//        echo '<p>'.__( '<i>Azera Shop Companion</i> has been deactivated.', 'azera-shop-companion' ).'</p>';
//    echo '</div>';
//
//    deactivate_plugins( plugin_basename( __FILE__ ) );
//}

/* Register style sheet. */
add_action( 'wp_enqueue_scripts', 'azera_shop_companion_register_plugin_styles' );

function azera_shop_companion_register_plugin_styles() {
	
	wp_enqueue_style( 'azera-shop-companion-style', trailingslashit( AZERA_SHOP_COMPANION_URL ) . 'css/style.css' );
	
}
