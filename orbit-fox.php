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
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Themeisle
 * Author URI:        https://themeisle.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       orbit-fox
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-orbit-fox-activator.php
 */
function activate_orbit_fox() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-orbit-fox-activator.php';
	Orbit_Fox_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-orbit-fox-deactivator.php
 */
function deactivate_orbit_fox() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-orbit-fox-deactivator.php';
	Orbit_Fox_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_orbit_fox' );
register_deactivation_hook( __FILE__, 'deactivate_orbit_fox' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-orbit-fox.php';

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

	$plugin = new Orbit_Fox();
	$plugin->run();

}
run_orbit_fox();
