<?php
/*
Plugin Name: ThemeIsle Companion
Plugin URI: https://github.com/Codeinwp/themeisle-companion
Description: Description goes here.
Version: 1.0.0
Author: Themeisle
Author URI: http://themeisle.com
Text Domain: themeisle-companion
Domain Path: /languages
License: GPLv2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

define( 'THEMEISLE_COMPANION_PATH',  plugin_dir_path( __FILE__ ) );
define( 'THEMEISLE_COMPANION_URL',  plugin_dir_url( __FILE__ ) );


if ( ! function_exists( 'add_action' ) ) {
	die('Nothing to do...');
}

/**
 * Register Zerif Widgets
 */
function themeisle_register_widgets() {

	register_widget('zerif_ourfocus');
	register_widget('zerif_testimonial_widget');
	register_widget('zerif_clients_widget');
	register_widget('zerif_team_widget');

}

add_action('widgets_init', 'themeisle_register_widgets');

require_once THEMEISLE_COMPANION_PATH . 'inc/widget-focus.php';
require_once THEMEISLE_COMPANION_PATH . 'inc/widget-testimonial.php';
require_once THEMEISLE_COMPANION_PATH . 'inc/widget-clients.php';
require_once THEMEISLE_COMPANION_PATH . 'inc/widget-team.php';
