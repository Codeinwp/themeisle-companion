<?php
define( 'BEAVER_WIDGETS_PATH', plugin_dir_path( __FILE__ ) );
define( 'BEAVER_WIDGETS_URL', plugins_url( '/', __FILE__ ) );

/**
 *
 */
class Beaver_Widgets_OBFX_Module extends Orbit_Fox_Module_Abstract {

	/**
	 * Beaver_Widgets_OBFX_Module constructor.
	 *
	 * @since   2.1.2
	 * @access  public
	 */
	public function __construct() {
		parent::__construct();
		$this->name        = __( 'Beaver Builder Widgets', 'themeisle-companion' );
		$this->description = __( 'Custom Beaver Builder Widgets.', 'themeisle-companion' );
	}

	/**
	 * Determine if module should be loaded.
	 *
	 * @since   2.1.2
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
		$this->loader->add_action( 'init', $this, 'load_widgets_modules' );
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

	public function load_widgets_modules(){
		if ( class_exists( 'FLBuilder' ) ) {
			require_once 'modules/pricing-table/pricing-table.php';
		}
	}
}