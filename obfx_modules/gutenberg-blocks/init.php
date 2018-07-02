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
		return is_plugin_active( 'gutenberg/gutenberg.php' ) && function_exists( 'register_block_type');
	}

	/**
	 * The loading logic for the module.
	 *
	 * @since   2.2.5
	 * @access  public
	 */
	public function load() {}

	/**
	 * Method to define hooks needed.
	 *
	 * @since   2.2.5
	 * @access  public
	 */
	public function hooks() {
		$this->loader->add_action( 'init', $this, 'load_js_blocks' );
		$this->loader->add_action( 'init', $this, 'load_server_side_blocks', 11 );

		//add_action( 'enqueue_block_editor_assets', 'gutenberg_examples_02_enqueue_block_editor_assets' );
		add_action( 'enqueue_block_assets', array( $this, 'enqueue_block_assets' ) );
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
	public function load_js_blocks(){
		if ( ! is_admin() ) {
			return;
		}
		wp_enqueue_script('lodash');
		// @TODO for the moment load one js file with all the blocks. Maybe in future we'll group and enable them selectively
		wp_enqueue_script(
			'obfx-gutenberg-blocks',
			plugins_url( '/build/block.js', __FILE__ ),
			array(),
			filemtime( plugin_dir_path( __FILE__ ) . '/build/block.js' ),
			true
		);

		wp_enqueue_style(
			'obfx-gutenberg-blocks-editor',
			plugins_url( 'build/edit-blocks.css', __FILE__ ),
			array( 'wp-edit-blocks' ),
			filemtime( plugin_dir_path( __FILE__ ) . 'build/edit-blocks.css' )
		);
	}

	public function load_server_side_blocks() {
		// load the base class
		require_once plugin_dir_path( __FILE__ ) . 'class-gutenberg-block.php';

		$ss_blocks = glob( __DIR__ . '/blocks/*/*.php');

		foreach ( $ss_blocks as $block ) {
			require_once $block;

			// remove the class prefix and the extension
			$classname = str_replace( array( 'class-' , '.php' ), '', basename( $block ) );
			// get an array of words from class names and we'll make them capitalized.
			$classname = explode( '-', $classname );
			$classname = array_map( 'ucfirst', $classname );
			// rebuild the classname string as capitalized and separated by underscores.
			$classname = 'OrbitFox\Gutenberg_Blocks\\' . implode( '_', $classname );

			if ( ! class_exists ( $classname ) ) {
				continue;
			}

			$block = new $classname();
			$block->register_block();
		}

	}

	function enqueue_block_assets() {
		wp_enqueue_style(
			'obfx-block_styles',
			plugins_url( 'build/style.css', __FILE__ ),
			array( 'wp-blocks' ),
			filemtime( plugin_dir_path( __FILE__ ) . 'build/style.css' )
		);
	}

}