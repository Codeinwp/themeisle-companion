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
			'mystock-main',
			plugins_url( 'js/script.js', __FILE__ ),
			array( 'wp-plugins', 'wp-edit-post', 'wp-element' )
		);
		wp_localize_script(
			'mystock-main',
			'mystock_localize',
			array(
				'ajaxurl'          => admin_url( 'admin-ajax.php' ),
				'nonce'            => wp_create_nonce( 'mystock-import' . filter_input( INPUT_SERVER, 'REMOTE_ADDR', FILTER_VALIDATE_IP ) ),
				'slug'             => 'mystock-import',
				'api_key'          => '97d007cf8f44203a2e578841a2c0f9ac',
				'user_id'          => '136375272@N05',
				'per_page'         => 10,
				'error_restapi'    => __('There was an error accessing the server. Please try again later. If you still receive this error, contact the support team.', 'themeisle-companion' ),
				'insert_into_post' => __('Insert into post', 'themeisle-companion'),
				'set_as_featured'  => __('Set as featured image', 'themeisle-companion'),
				'saving'           => __('Downloading Image...', 'themeisle-companion'),
			)
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
		wp_enqueue_script( 'mystock-main' );
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
