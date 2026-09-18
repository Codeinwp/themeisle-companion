<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://themeisle.com
 * @since             1.0.0
 * @package           Orbit_Fox
 *
 * @wordpress-plugin
 * Plugin Name:       Orbit Fox Companion
 * Plugin URI:        https://orbitfox.com/
 * Description:       This swiss-knife plugin comes with a quality template library, menu/sharing icons modules, Gutenberg blocks, and newly added Elementor/BeaverBuilder page builder widgets on each release.
 * Version:           3.0.9
 * Author:            Themeisle
 * Author URI:        https://orbitfox.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       themeisle-companion
 * Domain Path:       /languages
 * WordPress Available:  yes
 * Requires License:    no
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in core/includes/class-orbit-fox-activator.php
 */
function activate_orbit_fox() {
	$obfx_activator = new Orbit_Fox_Activator();
	$obfx_activator->activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in core/includes/class-orbit-fox-deactivator.php
 */
function deactivate_orbit_fox() {
	$obfx_deactivator = new Orbit_Fox_Deactivator();
	$obfx_deactivator->deactivate();
}

register_activation_hook( __FILE__, 'activate_orbit_fox' );
register_deactivation_hook( __FILE__, 'deactivate_orbit_fox' );

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_orbit_fox() {
	define( 'OBFX_URL', plugins_url( '/', __FILE__ ) );
	define( 'OBX_PATH', dirname( __FILE__ ) );
	define( 'OBX_PRODUCT_SLUG', basename( OBX_PATH ) );

	// Load Composer autoloader before plugin initialization
	$vendor_file = OBX_PATH . '/vendor/autoload.php';
	if ( is_readable( $vendor_file ) ) {
		require_once $vendor_file;
	}

	$plugin = new Orbit_Fox();
	$plugin->run();
	add_filter(
		'themeisle_sdk_products',
		function ( $products ) {
			$products[] = __FILE__;

			return $products;
		}
	);
	add_filter(
		'themeisle_companion_friendly_name',
		function( $name ) {
			return 'Orbit Fox';
		}
	);
	add_filter(
		'themeisle_companion_load_promotions',
		function() {
			return array( 'otter' );
		}
	);
	add_filter(
		'themeisle_companion_about_us_metadata',
		function() {
			return array(
				'logo'     => esc_url( OBFX_URL . 'images/orbit-fox.png' ),
				'location' => 'obfx_companion',
			);
		}
	);
	add_filter(
		'themeisle_companion_ai_connect_metadata',
		function() {
			return array(
				'name'         => 'Orbit Fox',
				'notice_cases' => array(
					__( 'review your active modules', 'themeisle-companion' ),
					__( 'switch modules on or off', 'themeisle-companion' ),
					__( 'adjust your social sharing buttons', 'themeisle-companion' ),
				),
				'prompts'      => array(
					__( 'List my Orbit Fox modules, tell me which ones are active and what each active one is set to.', 'themeisle-companion' ),
					__( 'Turn on the social sharing module and show the share buttons on posts only, not on pages.', 'themeisle-companion' ),
					__( 'Go through every Orbit Fox module, deactivate the ones I am not using and tell me what you changed.', 'themeisle-companion' ),
				),
				'abilities'    => array(
					'orbit-fox/list-modules',
					'orbit-fox/configure-module',
				),
			);
		}
	);
}

require 'class-autoloader.php';
Autoloader::set_plugins_path( plugin_dir_path( __DIR__ ) );
Autoloader::define_namespaces( array( 'Orbit_Fox', 'OBFX', 'OBFX_Module' ) );
/**
 * Invocation of the Autoloader::loader method.
 *
 * @since   1.0.0
 */
spl_autoload_register( array( 'Autoloader', 'loader' ) );

run_orbit_fox();
