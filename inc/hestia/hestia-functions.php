<?php
/**
 * Companion code for Hestia
 *
 * @author Themeisle
 * @package themeisle-companion
 */

/**
 * Include sections from Companion plugin
 */
function themeisle_hestia_require() {

	if ( function_exists( 'hestia_setup_theme' ) ) {

		$features_path = trailingslashit( THEMEISLE_COMPANION_PATH ) . 'inc/hestia/inc/features/feature-features-section.php';
		$customizer_features_path = trailingslashit( THEMEISLE_COMPANION_PATH ) . 'inc/hestia/inc/sections/hestia-features-section.php';
		if ( file_exists( $features_path ) ) {
			require_once( $features_path );
		}
		if ( file_exists( $customizer_features_path ) ) {
			require_once( $customizer_features_path );
		}

		$testimonials_path = trailingslashit( THEMEISLE_COMPANION_PATH ) . 'inc/hestia/inc/features/feature-testimonials-section.php';
		$customizer_testimonials_path = trailingslashit( THEMEISLE_COMPANION_PATH ) . 'inc/hestia/inc/sections/hestia-testimonials-section.php';
		if ( file_exists( $testimonials_path ) ) {
			require_once( $testimonials_path );
		}
		if ( file_exists( $customizer_testimonials_path ) ) {
			require_once( $customizer_testimonials_path );
		}

		$team_path = trailingslashit( THEMEISLE_COMPANION_PATH ) . 'inc/hestia/inc/features/feature-team-section.php';
		$customizer_team_path = trailingslashit( THEMEISLE_COMPANION_PATH ) . 'inc/hestia/inc/sections/hestia-team-section.php';
		if ( file_exists( $team_path ) ) {
			require_once( $team_path );
		}
		if ( file_exists( $customizer_team_path ) ) {
			require_once( $customizer_team_path );
		}

		$customizer_path = trailingslashit( THEMEISLE_COMPANION_PATH ) . 'inc/hestia/inc/customizer.php';
		if ( file_exists( $customizer_path ) ) {
			require_once( $customizer_path );
		}
	}
}

add_action( 'after_setup_theme', 'themeisle_hestia_require' );
