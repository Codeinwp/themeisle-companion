<?php
/*
 * Plugin Name: ThemeIsle Companion
 * Plugin URI: https://github.com/Codeinwp/themeisle-companion
 * Description: Enhances ThemeIsle's themes with extra functionalities.
 * Version: 1.0.2
 * Author: Themeisle
 * Author URI: http://themeisle.com
 * Text Domain: themeisle-companion
 * Domain Path: /languages
 * License: GPLv2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

define( 'THEMEISLE_COMPANION_VERSION',  '1.0.2' );
define( 'THEMEISLE_COMPANION_PATH',  plugin_dir_path( __FILE__ ) );
define( 'THEMEISLE_COMPANION_URL',  plugin_dir_url( __FILE__ ) );


if ( ! function_exists( 'add_action' ) ) {
	die('Nothing to do...');
}
add_action( 'plugins_loaded', 'themeisle_companion_textdomain' );

/**
 * Load plugin textdomain.
 */
function themeisle_companion_textdomain() {
	load_plugin_textdomain( 'themeisle-companion', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}


function themeisle_companion_loader() {
	if ( function_exists( 'zerif_setup' ) ) {
		require_once( THEMEISLE_COMPANION_PATH . 'inc/zerif-lite/zerif-lite-functions.php' );
	}

	if ( function_exists( 'hestia_setup_theme' ) ) {
		require_once( THEMEISLE_COMPANION_PATH . 'inc/hestia/hestia-functions.php' );
	}
}

add_action( 'after_setup_theme', 'themeisle_companion_loader', 0 );
