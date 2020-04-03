<?php
/**
 * MyStock import module for Gutenberg
 *
 * @package Mystock_Wp_Editor
 */

/**
 * Class Mystock_Wp_Editor
 */
class Mystock_Wp_Editor_OBFX_Module extends Orbit_Fox_Module_Abstract {

	/**
	 * Mystock_Wp_Editor constructor.
	 */
	public function __construct() {
		parent::__construct();
		$this->name           = __( 'Mystock Import', 'themeisle-companion' );
		$this->description    = __( 'Module to import images directly from', 'themeisle-companion' ) . sprintf( ' <a href="%s" target="_blank">mystock.themeisle.com</a>', 'https://mystock.themeisle.com' );
		$this->active_default = true;
	}

	/**
	 * Determine if module should be loaded.
	 *
	 * @return bool
	 * @since   1.0.0
	 * @access  public
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
		$this->loader->add_action( 'init', $this, 'sidebar_register' );
	}

	/**
	 * Register sidebar in WP Editor
	 */
	public function sidebar_register(){
		wp_register_script(
			'mystock-sidebar',
			plugins_url( 'js/script.js', __FILE__ ),
			array( 'wp-plugins', 'wp-edit-post', 'wp-element' )
		);
	}

	/**
	 * Method to define the options fields for the module
	 *
	 * @return array
	 * @since   1.0.0
	 * @access  public
	 */
	public function options() {
		return array();
	}

	/**
	 * Method that returns an array of scripts and styles to be loaded
	 * for the admin part.
	 *
	 * @return array
	 * @since   1.0.0
	 * @access  public
	 */
	public function admin_enqueue() {
		wp_enqueue_script( 'mystock-sidebar' );
	}

	/**
	 * Method that returns an array of scripts and styles to be loaded
	 * for the front end part.
	 *
	 * @return array
	 * @since   1.0.0
	 * @access  public
	 */
	public function public_enqueue() {
		return array();
	}
}
