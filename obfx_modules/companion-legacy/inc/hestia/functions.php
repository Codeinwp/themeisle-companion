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

		// Require Zerif > Hestia content importer if it exists.
		$zerif_importer_path      = THEMEISLE_COMPANION_PATH. 'hestia/inc/features/import-zerif-content.php';
		if ( file_exists( $zerif_importer_path ) ) {
			require_once( $zerif_importer_path );
		}

		// Require Hestia Features section and customizer settings.
		$features_path            = THEMEISLE_COMPANION_PATH . 'hestia/inc/features/feature-features-section.php';
		$customizer_features_path = THEMEISLE_COMPANION_PATH . 'hestia/inc/sections/hestia-features-section.php';
		if ( file_exists( $features_path ) ) {
			require_once( $features_path );
		}
		if ( file_exists( $customizer_features_path ) ) {
			require_once( $customizer_features_path );
		}

		// Require Hestia Testimonials section and customizer settings.
		$testimonials_path            = THEMEISLE_COMPANION_PATH . 'hestia/inc/features/feature-testimonials-section.php';
		$customizer_testimonials_path = THEMEISLE_COMPANION_PATH . 'hestia/inc/sections/hestia-testimonials-section.php';
		if ( file_exists( $testimonials_path ) ) {
			require_once( $testimonials_path );
		}
		if ( file_exists( $customizer_testimonials_path ) ) {
			require_once( $customizer_testimonials_path );
		}

		// Require Hestia Team section and customizer settings.
		$team_path            = THEMEISLE_COMPANION_PATH . 'hestia/inc/features/feature-team-section.php';
		$customizer_team_path = THEMEISLE_COMPANION_PATH . 'hestia/inc/sections/hestia-team-section.php';
		if ( file_exists( $team_path ) ) {
			require_once( $team_path );
		}
		if ( file_exists( $customizer_team_path ) ) {
			require_once( $customizer_team_path );
		}

		// Require Hestia Customizer extension.
		$customizer_path = THEMEISLE_COMPANION_PATH . 'hestia/inc/customizer.php';
		if ( file_exists( $customizer_path ) ) {
			require_once( $customizer_path );
		}
	}
}

/**
 * Set Front page displays option to A static page
 */
function themeisle_hestia_set_frontpage() {
	if ( function_exists( 'hestia_setup_theme' ) ) {
		$is_fresh_site = get_option('fresh_site');
		if ( (bool)$is_fresh_site === false ) {
			$frontpage_title = esc_html__( 'Front Page', 'themeisle-companion' );
			$front_id = themeisle_hestia_create_page( 'hestia-front', $frontpage_title );
			$blogpage_title = esc_html__( 'Blog', 'themeisle-companion' );
			$blog_id = themeisle_hestia_create_page( 'blog', $blogpage_title );
			set_theme_mod( 'show_on_front','page' );
			update_option( 'show_on_front', 'page' );
			if ( ! empty( $front_id ) ) {
				update_option( 'page_on_front', $front_id );
			}
			if ( ! empty( $blog_id ) ) {
				update_option( 'page_for_posts', $blog_id );
			}
		}
	}
}

/**
 * Function that checks if a page with a slug exists. If not, it create one.
 * @param string $slug Page slug.
 * @param string $page_title Page title.
 * @return int
 */
function themeisle_hestia_create_page( $slug, $page_title ){
	//Check if page exists
	$args = array(
		'name'        => $slug,
		'post_type'   => 'page',
		'post_status' => 'publish',
		'numberposts' => 1
	);
	$post = get_posts($args);
	if( !empty( $post ) ) {
		$page_id = $post[0]->ID;
	} else {
		// Page doesn't exist. Create one.
		$postargs = array(
			'post_type'     => 'page',
			'post_name'     => $slug,
			'post_title'    => $page_title,
			'post_status'   => 'publish',
			'post_author'   => 1,
		);
		// Insert the post into the database
		$page_id = wp_insert_post( $postargs );
	}
	return $page_id;
}
