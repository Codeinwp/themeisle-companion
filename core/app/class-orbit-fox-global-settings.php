<?php
/**
 * The global settings of the plugin.
 *
 * @link       https://themeisle.com
 * @since      1.0.0
 *
 * @package    Orbit_Fox
 * @subpackage Orbit_Fox/app
 */

/**
 * The global settings of the plugin.
 *
 * Defines the plugin global settings instance and modules.
 *
 * @package    Orbit_Fox
 * @subpackage Orbit_Fox/app
 * @author     Themeisle <friends@themeisle.com>
 */
class Orbit_Fox_Global_Settings {

	/**
	 * The main instance var.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @var     Orbit_Fox_Global_Settings $instance The istance of this class.
	 */
	public static $instance;

	/**
	 * Stores the default modules data.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @var     array $modules Modules List.
	 */
	public $modules = array();

	/**
	 * The instance method for the static class.
	 * Defines and returns the instance of the static class.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @return Orbit_Fox_Global_Settings
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Orbit_Fox_Global_Settings ) ) {
			self::$instance = new Orbit_Fox_Global_Settings;
			self::$instance->modules = apply_filters( 'obfx_modules',
				array(
				    'test',
				)
			);
		}// End if().

		return self::$instance;
	}

	/**
	 * Method to retrieve instance of modules.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @return array
	 */
	public function get_modules() {
		return self::instance()->modules;
	}
}
