<?php
/**
 * The Mock-up to demonstrate and test module use.
 *
 * @link       https://themeisle.com
 * @since      1.0.0
 *
 * @package    Test_OBFX_Module
 */

/**
 * The class defines a new module to be used by Orbit Fox plugin.
 *
 * @package    Test_OBFX_Module
 * @author     Themeisle <friends@themeisle.com>
 */
class Test_OBFX_Module extends Orbit_Fox_Module_Abstract {

	/**
	 * Test_OBFX_Module constructor.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function __construct() {
		$this->name = __( 'Orbit Fox Test Module', 'obfx' );
		$this->description = __( 'A test module for Orbit Fox.', 'obfx' );
	}

	/**
	 * Determine if module should be loaded.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @return bool
	 */
	public function enable_module() {
		return true;
	}

	/**
	 * The loading logic for the module.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function load() {

	}

	/**
	 * Method that returns an array of scripts and styles to be loaded
	 * for the front end part.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @return array
	 */
	public function public_enqueue() {
	    return array();
	}

	/**
	 * Method that returns an array of scripts and styles to be loaded
	 * for the admin part.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @return array
	 */
	public function admin_enqueue() {
		return array();
	}

	/**
	 * Method to define the options fields for the module
	 *
	 * @since   1.0.0
	 * @access  public
	 * @return array
	 */
	public function options() {
		return array();
	}
}
