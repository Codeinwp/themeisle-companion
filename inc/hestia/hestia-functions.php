<?php
/**
 * Plugin Name: Hestia Companion
 * Plugin URI: https://github.com/Codeinwp/
 * Description: Add three new sections to the front page.
 * Version: 1.1.17
 * Author: Themeisle
 * Author URI: http://themeisle.com
 * Text Domain: hestia-companion
 * Domain Path: /languages
 * License: GPLv2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package Hestia Companion
 */

define( 'HESTIA_COMPANION_PATH', plugin_dir_path( __FILE__ ) );

if ( ! function_exists( 'add_action' ) ) {
	die( 'Nothing to do...' );
}

add_action( 'plugins_loaded', 'hestia_companion_textdomain' );

/**
 * Load plugin textdomain.
 */
function hestia_companion_textdomain() {
	load_plugin_textdomain( 'hestia-companion', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}


/**
 * Include sections from Companion plugin
 */
function hestia_companion_require() {

	if ( function_exists( 'hestia_setup_theme' ) ) {

		$features_path = trailingslashit( HESTIA_COMPANION_PATH ) . 'inc/features/feature-features-section.php';
		$customizer_features_path = trailingslashit( HESTIA_COMPANION_PATH ) . 'inc/sections/hestia-features-section.php';
		if ( file_exists( $features_path ) ) {
			require_once( $features_path );
		}
		if ( file_exists( $customizer_features_path ) ) {
			require_once( $customizer_features_path );
		}

		$testimonials_path = trailingslashit( HESTIA_COMPANION_PATH ) . 'inc/features/feature-testimonials-section.php';
		$customizer_testimonials_path = trailingslashit( HESTIA_COMPANION_PATH ) . 'inc/sections/hestia-testimonials-section.php';
		if ( file_exists( $testimonials_path ) ) {
			require_once( $testimonials_path );
		}
		if ( file_exists( $customizer_testimonials_path ) ) {
			require_once( $customizer_testimonials_path );
		}

		$team_path = trailingslashit( HESTIA_COMPANION_PATH ) . 'inc/features/feature-team-section.php';
		$customizer_team_path = trailingslashit( HESTIA_COMPANION_PATH ) . 'inc/sections/hestia-team-section.php';
		if ( file_exists( $team_path ) ) {
			require_once( $team_path );
		}
		if ( file_exists( $customizer_team_path ) ) {
			require_once( $customizer_team_path );
		}

		$customizer_path = trailingslashit( HESTIA_COMPANION_PATH ) . 'inc/customizer.php';
		if ( file_exists( $customizer_path ) ) {
			require_once( $customizer_path );
		}
	}
}

add_action( 'after_setup_theme', 'hestia_companion_require' );
