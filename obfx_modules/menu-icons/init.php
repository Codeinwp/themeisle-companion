<?php
/**
 * The module for menu icons.
 *
 * @link       https://themeisle.com
 * @since      1.0.0
 *
 * @package    Menu_Icons_OBFX_Module
 */

/**
 * The class for menu icons.
 *
 * @package    Menu_Icons_OBFX_Module
 * @author     Themeisle <friends@themeisle.com>
 * @codeCoverageIgnore
 */
class Menu_Icons_OBFX_Module extends Orbit_Fox_Module_Abstract {


	/**
	 * Menu_Icons_OBFX_Module constructor.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function __construct() {
		parent::__construct();
		$this->name        = __( 'Menu Icons', 'themeisle-companion' );
		$this->description = __( 'Module to define menu icons for navigation.', 'themeisle-companion' );
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
	 * Method to define hooks needed.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function hooks() {
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
		$current_screen = get_current_screen();

		if ( ! isset( $current_screen->id ) ) {
			return array();
		}
		if ( $current_screen->id != 'nav-menus' ) {
			return array();
		}

		return array(
			'css' => array(
				'admin' => false,
			),
			'js' => array(
				'admin' => array( 'jquery' ),
			),
		);
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