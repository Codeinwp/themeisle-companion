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
		$this->loader->add_action( 'enqueue_block_editor_assets', $this, 'load_js_blocks' );
		$this->loader->add_action( 'init', $this, 'autoload_block_classes', 11 );
		$this->loader->add_action( 'wp', $this, 'load_server_side_blocks', 11 );
		$this->loader->add_action( 'init', $this, 'registerSettings' );
		$this->loader->add_action( 'enqueue_block_assets', $this, 'enqueue_block_assets' );
		$this->loader->add_action( 'block_categories', $this, 'block_categories' );
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
	public function load_js_blocks() {
		wp_enqueue_script(
			'obfx-gutenberg-blocks',
			plugins_url( '/build/block.js', __FILE__ ),
			array( 'wp-api', 'jquery' ),
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

		foreach ( $this->blocks_classes as $classname ) {

			if ( ! class_exists( $classname ) ) {
				continue;
			}

			$block = new $classname();

			if ( method_exists( $block, 'register_block' ) ) {
				$block->register_block();
			}
		}
	}

	/**
	 * Autoload classes for each block.
	 */
	function autoload_block_classes() {
		// load the base class
		require_once plugin_dir_path( __FILE__ ) . 'class-gutenberg-block.php';
		$ss_blocks = glob( __DIR__ . '/blocks/*/*.php' );

		foreach ( $ss_blocks as $block ) {
			require_once $block;

			// remove the class prefix and the extension
			$classname = str_replace( array( 'class-', '.php' ), '', basename( $block ) );
			// get an array of words from class names and we'll make them capitalized.
			$classname = explode( '-', $classname );
			$classname = array_map( 'ucfirst', $classname );
			// rebuild the classname string as capitalized and separated by underscores.
			$classname = 'OrbitFox\Gutenberg_Blocks\\' . implode( '_', $classname );

			if ( ! class_exists( $classname ) ) {
				continue;
			}

			if ( strpos( $block, '-block.php' ) ) {
				// we need to init these blocks on a hook later than "init". See `load_server_side_blocks`
				$this->blocks_classes[] = $classname;
				continue;
			}

			$block = new $classname();

			if ( method_exists( $block, 'instance' ) ) {
				$block->instance();
			}
		}
	}

	/**
	 * Load assets for our blocks.
	 */
	function enqueue_block_assets() {
		wp_enqueue_style( 'font-awesome-5', plugins_url( 'assets/fontawesome/css/all.min.css', __FILE__ ) );
		wp_enqueue_style( 'font-awesome-4-shims', plugins_url( 'assets/fontawesome/css/v4-shims.min.css', __FILE__ ) );

		if ( is_admin() ) {
			return;
		}

		wp_enqueue_style(
			'obfx-block_styles',
			plugins_url( 'build/style.css', __FILE__ ),
			array( 'wp-blocks' ),
			filemtime( plugin_dir_path( __FILE__ ) . 'build/style.css' )
		);

		if ( has_block( 'orbitfox/chart-pie' ) ) {
			wp_enqueue_script( 'google-charts', 'https://www.gstatic.com/charts/loader.js' );
		}
	}

	/**
	 * Register our custom block category.
	 *
	 * @access public
	 *
	 * @param array $categories All categories.
	 *
	 * @link   https://wordpress.org/gutenberg/handbook/extensibility/extending-blocks/#managing-block-categories
	 */
	public function block_categories( $categories ) {
		return array_merge(
			$categories,
			array(
				array(
					'slug'  => 'orbitfox',
					'title' => __( 'Orbit Fox Blocks', 'themeisle-companion' ),
				),
			)
		);
	}

	/**
	 * Register Settings for Google Maps Block
	 */
	public function registerSettings() {
		register_setting(
			'orbitfox_google_map_block_api_key',
			'orbitfox_google_map_block_api_key',
			array(
				'type'              => 'string',
				'description'       => __( 'Google Map API key for the Gutenberg block plugin.', 'themeisle-companion' ),
				'sanitize_callback' => 'sanitize_text_field',
				'show_in_rest'      => true,
				'default'           => ''
			)
		);
	}

}