<?php
/**
 * Gutenberg Blocks modules Orbit Fox Module.
 *
 * @link       https://themeisle.com
 * @since      2.2.5
 */

/**
 * Class Gutenberg_Blocks_OBFX_Module
 */
class Gutenberg_Blocks_OBFX_Module extends Orbit_Fox_Module_Abstract {

	/**
	 * Gutenberg_Blocks_OBFX_Module constructor.
	 *
	 * @since   2.2.5
	 * @access  public
	 */
	public function __construct() {
		parent::__construct();
		$this->name        = __( 'Gutenberg Blocks', 'themeisle-companion' );
		$this->description = __( 'A set of awesome Gutenberg Blocks!', 'themeisle-companion' );
		$this->active_default = false;
	}

	/**
	 * Determine if module should be loaded.
	 *
	 * @since   2.2.5
	 * @access  public
	 * @return bool
	 */
	public function enable_module() {
		require_once( ABSPATH . 'wp-admin' . '/includes/plugin.php' );
		return is_plugin_active( 'gutenberg/gutenberg.php' );
	}

	/**
	 * The loading logic for the module.
	 *
	 * @since   2.2.5
	 * @access  public
	 */
	public function load() {
	}

	/**
	 * Method to define hooks needed.
	 *
	 * @since   2.2.5
	 * @access  public
	 */
	public function hooks() {
		$this->loader->add_action( 'init', $this, 'load_blocks', 11 );

	}

	/**
	 * Method that returns an array of scripts and styles to be loaded
	 * for the front end part.
	 *
	 * @since   2.2.5
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
	 * @since   2.2.5
	 * @access  public
	 * @return array
	 */
	public function admin_enqueue() {
		return array();
	}

	/**
	 * Method to define the options fields for the module
	 *
	 * @since   2.2.5
	 * @access  public
	 * @return array
	 */
	public function options() {
		return array();
	}

	/**
	 * Load Gutenberg blocks
	 *
	 * @since   2.2.5
	 * @access  public
	 */
	public function load_blocks(){

		if ( ! is_admin() ) {
			return;
		}

		// @TODO for the moment load one js file with all the blocks. Maybe in future we'll group and enable them selectively
		wp_enqueue_script(
			'obfx-gutenberg-blocks',
			plugins_url( '/build/block.js', __FILE__ ),
			array( ),
			filemtime( plugin_dir_path( __FILE__ ) . '/build/block.js' ),
			true
		);
	}

}