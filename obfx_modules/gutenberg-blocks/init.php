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

	protected $blocks_classes = array();

	/**
	 * Gutenberg_Blocks_OBFX_Module constructor.
	 *
	 * @since   2.2.5
	 * @access  public
	 */
	public function __construct() {
		parent::__construct();
		$this->name           = __( 'Gutenberg Blocks', 'themeisle-companion' );
		$this->description    = __( 'A set of awesome Gutenberg Blocks!', 'themeisle-companion' );
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
		// TODO: Fix this check before gutenberg is merged into WordPress core.
		// checking function_exists( 'register_block_type' ) returns false at first and true later in the code.
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
		$this->loader->add_action( 'enqueue_block_assets', $this, 'enqueue_block_assets' );
		$this->loader->add_action( 'init', $this, 'load_gutenberg_blocks' );
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
	 * Load assets for our blocks.
	 */
	function enqueue_block_assets() {
		wp_enqueue_style( 'font-awesome-5', plugins_url( 'assets/fontawesome/css/all.min.css', __FILE__ ) );
		wp_enqueue_style( 'font-awesome-4-shims', plugins_url( 'assets/fontawesome/css/v4-shims.min.css', __FILE__ ) );
	}

	/**
	 * If the composer library is present let's try to init.
	 */
	function load_gutenberg_blocks() {
		if ( class_exists( '\ThemeIsle\GutenbergBlocks' ) ) {
			\ThemeIsle\GutenbergBlocks::instance( __( 'Orbit Fox', 'themeisle-companion' ) );
		}
	}

}