<?php
/*
Plugin Name: ThemeIsle Companion
Plugin URI: https://github.com/Codeinwp/themeisle-companion
Description: Creates the widgets "Zerif - Testimonial widget" , "Zerif - Team member widget" , "Zerif - Our focus widget" and "Zerif - Clients widget" in the Zerif Lite theme.
Version: 1.0.0
Author: Themeisle
Author URI: http://themeisle.com
Text Domain: themeisle-companion
Domain Path: /languages
License: GPLv2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

define( 'THEMEISLE_COMPANION_VERSION',  '1.0.0' );
define( 'THEMEISLE_COMPANION_PATH',  plugin_dir_path( __FILE__ ) );
define( 'THEMEISLE_COMPANION_URL',  plugin_dir_url( __FILE__ ) );


if ( ! function_exists( 'add_action' ) ) {
	die('Nothing to do...');
}

/**
 * Populate Zerif frontpage widgets areas with default widgets
 */
function themeisle_populate_with_default_widgets() {

	$zerif_lite_sidebars = array ( 'sidebar-ourfocus' => 'sidebar-ourfocus', 'sidebar-testimonials' => 'sidebar-testimonials', 'sidebar-ourteam' => 'sidebar-ourteam' );

	$active_widgets = get_option( 'sidebars_widgets' );

	/**
	 * Populate the Our Focus sidebar
	 */
	if ( empty ( $active_widgets[ $zerif_lite_sidebars['sidebar-ourfocus'] ] ) ) {

		$zerif_lite_counter = 1;

		/* our focus widget #1 */

		$active_widgets['sidebar-ourfocus'][0] = 'ctup-ads-widget-' . $zerif_lite_counter;

		if ( file_exists( get_stylesheet_directory() . '/images/parallax.png' ) ) {
			$ourfocus_content[ $zerif_lite_counter ] = array(
				'title'     => 'PARALLAX EFFECT',
				'text'      => 'Create memorable pages with smooth parallax effects that everyone loves. Also, use our lightweight content slider offering you smooth and great-looking animations.',
				'link'      => '#',
				'image_uri' => get_stylesheet_directory_uri() . "/images/parallax.png"
			);
		} else {
			$ourfocus_content[ $zerif_lite_counter ] = array(
				'title'     => 'PARALLAX EFFECT',
				'text'      => 'Create memorable pages with smooth parallax effects that everyone loves. Also, use our lightweight content slider offering you smooth and great-looking animations.',
				'link'      => '#',
				'image_uri' => get_template_directory_uri() . "/images/parallax.png"
			);
		}

		update_option( 'widget_ctup-ads-widget', $ourfocus_content );

		$zerif_lite_counter ++;

		/* our focus widget #2 */

		$active_widgets['sidebar-ourfocus'][] = 'ctup-ads-widget-' . $zerif_lite_counter;

		if ( file_exists( get_stylesheet_directory() . '/images/woo.png' ) ) {
			$ourfocus_content[ $zerif_lite_counter ] = array(
				'title'     => 'WOOCOMMERCE',
				'text'      => 'Build a front page for your WooCommerce store in a matter of minutes. The neat and clean presentation will help your sales and make your store accessible to everyone.',
				'link'      => '#',
				'image_uri' => get_stylesheet_directory_uri() . "/images/woo.png"
			);
		} else {
			$ourfocus_content[ $zerif_lite_counter ] = array(
				'title'     => 'WOOCOMMERCE',
				'text'      => 'Build a front page for your WooCommerce store in a matter of minutes. The neat and clean presentation will help your sales and make your store accessible to everyone.',
				'link'      => '#',
				'image_uri' => get_template_directory_uri() . "/images/woo.png"
			);
		}

		update_option( 'widget_ctup-ads-widget', $ourfocus_content );

		$zerif_lite_counter ++;

		/* our focus widget #3 */

		$active_widgets['sidebar-ourfocus'][] = 'ctup-ads-widget-' . $zerif_lite_counter;

		if ( file_exists( get_stylesheet_directory() . '/images/ccc.png' ) ) {
			$ourfocus_content[ $zerif_lite_counter ] = array(
				'title'     => 'CUSTOM CONTENT BLOCKS',
				'text'      => 'Showcase your team, products, clients, about info, testimonials, latest posts from the blog, contact form, additional calls to action. Everything translation ready.',
				'link'      => '#',
				'image_uri' => get_stylesheet_directory_uri() . "/images/ccc.png"
			);
		} else {
			$ourfocus_content[ $zerif_lite_counter ] = array(
				'title'     => 'CUSTOM CONTENT BLOCKS',
				'text'      => 'Showcase your team, products, clients, about info, testimonials, latest posts from the blog, contact form, additional calls to action. Everything translation ready.',
				'link'      => '#',
				'image_uri' => get_template_directory_uri() . "/images/ccc.png"
			);
		}

		update_option( 'widget_ctup-ads-widget', $ourfocus_content );

		$zerif_lite_counter ++;

		/* our focus widget #4 */

		$active_widgets['sidebar-ourfocus'][] = 'ctup-ads-widget-' . $zerif_lite_counter;

		if ( file_exists( get_stylesheet_directory() . '/images/ti-logo.png' ) ) {
			$ourfocus_content[ $zerif_lite_counter ] = array(
				'title'     => 'GO PRO FOR MORE FEATURES',
				'text'      => 'Get new content blocks: pricing table, Google Maps, and more. Change the sections order, display each block exactly where you need it, customize the blocks with whatever colors you wish.',
				'link'      => '#',
				'image_uri' => get_stylesheet_directory_uri() . "/images/ti-logo.png"
			);
		} else {
			$ourfocus_content[ $zerif_lite_counter ] = array(
				'title'     => 'GO PRO FOR MORE FEATURES',
				'text'      => 'Get new content blocks: pricing table, Google Maps, and more. Change the sections order, display each block exactly where you need it, customize the blocks with whatever colors you wish.',
				'link'      => '#',
				'image_uri' => get_template_directory_uri() . "/images/ti-logo.png"
			);
		}

		update_option( 'widget_ctup-ads-widget', $ourfocus_content );

		$zerif_lite_counter ++;

		update_option( 'sidebars_widgets', $active_widgets );

	}

	/**
	 * Populate the Testimonials sidebar
	 */
	if ( empty ( $active_widgets[ $zerif_lite_sidebars['sidebar-testimonials'] ] ) ) {

		$zerif_lite_counter = 1;

		/* testimonial widget #1 */

		$active_widgets['sidebar-testimonials'][0] = 'zerif_testim-widget-' . $zerif_lite_counter;

		if ( file_exists( get_stylesheet_directory() . '/images/testimonial1.jpg' ) ) {
			$testimonial_content[ $zerif_lite_counter ] = array(
				'title'     => 'Dana Lorem',
				'text'      => 'Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Curabitur nec sem vel sapien venenatis mattis non vitae augue. Nullam congue commodo lorem vitae facilisis. Suspendisse malesuada id turpis interdum dictum.',
				'image_uri' => get_stylesheet_directory_uri() . "/images/testimonial1.jpg"
			);
		} else {
			$testimonial_content[ $zerif_lite_counter ] = array(
				'title'     => 'Dana Lorem',
				'text'      => 'Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Curabitur nec sem vel sapien venenatis mattis non vitae augue. Nullam congue commodo lorem vitae facilisis. Suspendisse malesuada id turpis interdum dictum.',
				'image_uri' => get_template_directory_uri() . "/images/testimonial1.jpg"
			);
		}

		update_option( 'widget_zerif_testim-widget', $testimonial_content );

		$zerif_lite_counter ++;

		/* testimonial widget #2 */

		$active_widgets['sidebar-testimonials'][] = 'zerif_testim-widget-' . $zerif_lite_counter;

		if ( file_exists( get_stylesheet_directory() . '/images/testimonial2.jpg' ) ) {
			$testimonial_content[ $zerif_lite_counter ] = array(
				'title'     => 'Linda Guthrie',
				'text'      => 'Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Curabitur nec sem vel sapien venenatis mattis non vitae augue. Nullam congue commodo lorem vitae facilisis. Suspendisse malesuada id turpis interdum dictum.',
				'image_uri' => get_stylesheet_directory_uri() . "/images/testimonial2.jpg"
			);
		} else {
			$testimonial_content[ $zerif_lite_counter ] = array(
				'title'     => 'Linda Guthrie',
				'text'      => 'Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Curabitur nec sem vel sapien venenatis mattis non vitae augue. Nullam congue commodo lorem vitae facilisis. Suspendisse malesuada id turpis interdum dictum.',
				'image_uri' => get_template_directory_uri() . "/images/testimonial2.jpg"
			);
		}

		update_option( 'widget_zerif_testim-widget', $testimonial_content );

		$zerif_lite_counter ++;

		/* testimonial widget #3 */

		$active_widgets['sidebar-testimonials'][] = 'zerif_testim-widget-' . $zerif_lite_counter;

		if ( file_exists( get_stylesheet_directory() . '/images/testimonial3.jpg' ) ) {
			$testimonial_content[ $zerif_lite_counter ] = array(
				'title'     => 'Cynthia Henry',
				'text'      => 'Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Curabitur nec sem vel sapien venenatis mattis non vitae augue. Nullam congue commodo lorem vitae facilisis. Suspendisse malesuada id turpis interdum dictum.',
				'image_uri' => get_stylesheet_directory_uri() . "/images/testimonial3.jpg"
			);
		} else {
			$testimonial_content[ $zerif_lite_counter ] = array(
				'title'     => 'Cynthia Henry',
				'text'      => 'Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Curabitur nec sem vel sapien venenatis mattis non vitae augue. Nullam congue commodo lorem vitae facilisis. Suspendisse malesuada id turpis interdum dictum.',
				'image_uri' => get_template_directory_uri() . "/images/testimonial3.jpg"
			);
		}

		update_option( 'widget_zerif_testim-widget', $testimonial_content );

		$zerif_lite_counter ++;

		update_option( 'sidebars_widgets', $active_widgets );
	}

	/**
	 * Populate the Our team sidebar
	 */
	if ( empty ( $active_widgets[ $zerif_lite_sidebars['sidebar-ourteam'] ] ) ) {

		$zerif_lite_counter = 1;

		/* our team widget #1 */

		$active_widgets['sidebar-ourteam'][0]   = 'zerif_team-widget-' . $zerif_lite_counter;

		$ourteam_content[ $zerif_lite_counter ] = array( 'name'        => 'ASHLEY SIMMONS',
		                                                 'position'    => 'Project Manager',
		                                                 'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc dapibus, eros at accumsan auctor, felis eros condimentum quam, non porttitor est urna vel neque',
		                                                 'fb_link'     => '#',
		                                                 'tw_link'     => '#',
		                                                 'bh_link'     => '#',
		                                                 'db_link'     => '#',
		                                                 'ln_link'     => '#',
		                                                 'image_uri'   => get_template_directory_uri() . "/images/team1.png"
		);

		update_option( 'widget_zerif_team-widget', $ourteam_content );

		$zerif_lite_counter ++;

		/* our team widget #2 */

		$active_widgets['sidebar-ourteam'][]    = 'zerif_team-widget-' . $zerif_lite_counter;

		$ourteam_content[ $zerif_lite_counter ] = array( 'name'        => 'TIMOTHY SPRAY',
		                                                 'position'    => 'Art Director',
		                                                 'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc dapibus, eros at accumsan auctor, felis eros condimentum quam, non porttitor est urna vel neque',
		                                                 'fb_link'     => '#',
		                                                 'tw_link'     => '#',
		                                                 'bh_link'     => '#',
		                                                 'db_link'     => '#',
		                                                 'ln_link'     => '#',
		                                                 'image_uri'   => get_template_directory_uri() . "/images/team2.png"
		);

		update_option( 'widget_zerif_team-widget', $ourteam_content );

		$zerif_lite_counter ++;

		/* our team widget #3 */

		$active_widgets['sidebar-ourteam'][]    = 'zerif_team-widget-' . $zerif_lite_counter;

		$ourteam_content[ $zerif_lite_counter ] = array( 'name'        => 'TONYA GARCIA',
		                                                 'position'    => 'Account Manager',
		                                                 'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc dapibus, eros at accumsan auctor, felis eros condimentum quam, non porttitor est urna vel neque',
		                                                 'fb_link'     => '#',
		                                                 'tw_link'     => '#',
		                                                 'bh_link'     => '#',
		                                                 'db_link'     => '#',
		                                                 'ln_link'     => '#',
		                                                 'image_uri'   => get_template_directory_uri() . "/images/team3.png"
		);

		update_option( 'widget_zerif_team-widget', $ourteam_content );

		$zerif_lite_counter ++;

		/* our team widget #4 */

		$active_widgets['sidebar-ourteam'][]    = 'zerif_team-widget-' . $zerif_lite_counter;

		$ourteam_content[ $zerif_lite_counter ] = array( 'name'        => 'JASON LANE',
		                                                 'position'    => 'Business Development',
		                                                 'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc dapibus, eros at accumsan auctor, felis eros condimentum quam, non porttitor est urna vel neque',
		                                                 'fb_link'     => '#',
		                                                 'tw_link'     => '#',
		                                                 'bh_link'     => '#',
		                                                 'db_link'     => '#',
		                                                 'ln_link'     => '#',
		                                                 'image_uri'   => get_template_directory_uri() . "/images/team4.png"
		);

		update_option( 'widget_zerif_team-widget', $ourteam_content );

		$zerif_lite_counter ++;

		update_option( 'sidebars_widgets', $active_widgets );
	}

	update_option( 'themeisle_companion_flag','installed' );

}

/**
 * Register Zerif Widgets
 */
function themeisle_register_widgets() {

	register_widget('zerif_ourfocus');
	register_widget('zerif_testimonial_widget');
	register_widget('zerif_clients_widget');
	register_widget('zerif_team_widget');

	$themeisle_companion_flag = get_option( 'themeisle_companion_flag' );
	if ( empty( $themeisle_companion_flag ) && function_exists( 'themeisle_populate_with_default_widgets' ) ) {
		themeisle_populate_with_default_widgets();
	}

}

add_action('widgets_init', 'themeisle_register_widgets');

require_once THEMEISLE_COMPANION_PATH . 'inc/widget-focus.php';
require_once THEMEISLE_COMPANION_PATH . 'inc/widget-testimonial.php';
require_once THEMEISLE_COMPANION_PATH . 'inc/widget-clients.php';
require_once THEMEISLE_COMPANION_PATH . 'inc/widget-team.php';
