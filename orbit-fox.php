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
 * Plugin Name:       Orbit Fox
 * Plugin URI:        https://orbitfox.com
 * Description:       The Orbit Fox, greatest thing that happened to WordPress since automatic is spelled with two T's. ;)
 * Version:           1.0.0
 * Author:            Themeisle
 * Author URI:        https://themeisle.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       obfx
 * Domain Path:       /core/languages
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
	Orbit_Fox_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in core/includes/class-orbit-fox-deactivator.php
 */
function deactivate_orbit_fox() {
	Orbit_Fox_Deactivator::deactivate();
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
	$plugin = new Orbit_Fox();
	$plugin->run();

}

require_once( 'class-autoloader.php' );
Autoloader::set_plugins_path( plugin_dir_path( __DIR__ ) );
Autoloader::define_namespaces( array( 'Orbit_Fox', 'OBFX', 'OBFX_Module' ) );
/**
 * Invocation of the Autoloader::loader method.
 *
 * @since   1.0.0
 */
spl_autoload_register( 'Autoloader::loader' );

/**
 * The start of the app.
 *
 * @since   1.0.0
 */
run_orbit_fox();
