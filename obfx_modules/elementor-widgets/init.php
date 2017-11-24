<?php
/**
 * Elementor Widgets Core Orbit Fox Module.
 *
 * @link       https://themeisle.com
 * @since      1.0.0
 *
 * @package    Elementor_Widgets_OBFX_Module
 */

define( 'OBFX_MODULE_URL', __FILE__ );

/**
 * The class defines a new module to be used by Orbit Fox plugin.
 *
 * @package    Elementor_Widgets_OBFX_Module
 * @author     Themeisle <friends@themeisle.com>
 * @codeCoverageIgnore
 */
class Elementor_Widgets_OBFX_Module extends Orbit_Fox_Module_Abstract {

	/**
	 * Elementor Widgets File Names
	 *
	 * @var array
	 */
	private $elementor_widgets = array(
		'class-obfx-elementor-widget-pricing-table',
		'class-obfx-elementor-widget-services',
		'class-obfx-elementor-widget-posts-grid',
	);

	/**
	 * Elementor_Widgets_OBFX_Module  constructor.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function __construct() {
		parent::__construct();
		$this->name        = __( 'Elementor Modules', 'themeisle-companion' );
		$this->description = __( 'Adds new Elementor Widgets.', 'themeisle-companion' );
		$this->active_default = true;
	}

	/**
	 * Determine if module should be loaded.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @return bool
	 */
	public function enable_module() {
		require_once( ABSPATH . 'wp-admin' . '/includes/plugin.php' );
		return is_plugin_active( 'elementor/elementor.php' );
	}

	/**
	 * The loading logic for the module.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function load() {}

	/**
	 * Method to define hooks needed.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @return mixed | array
	 */
	public function hooks() {

		$this->loader->add_action( 'elementor/init', $this, 'add_elementor_category' );
		$this->loader->add_action( 'elementor/widgets/widgets_registered', $this, 'add_elementor_widgets' );
		$this->loader->add_action( 'elementor/frontend/after_register_scripts', $this, 'enqueue_scripts' );

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
		return array(
			'css' => array(
				'public' => false,
			),
		);
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
		$options = array();

		return $options;
	}

	/**
	 * Add the Category for Orbit Fox Widgets.
	 */
	public function add_elementor_category() {
		\Elementor\Plugin::instance()->elements_manager->add_category(
			'obfx-elementor-widgets',
			array(
				'title' => __( 'Orbit Fox Addons', 'themeisle-companion' ),
				'icon'  => 'fa fa-plug',
			),
			1 );
	}

	/**
	 * Require and instantiate Elementor Widgets.
	 *
	 * @param $widgets_manager
	 */
	public function add_elementor_widgets( $widgets_manager ) {
		foreach ( $this->elementor_widgets as $widget ) {
			require_once $this->get_dir() . '/widgets/' . $widget . '.php';
		}

		// Pricing table
		$widget = new Elementor\OBFX_Elementor_Widget_Pricing_Table();
		$widgets_manager->register_widget_type( $widget );
        // Services
		$widget = new Elementor\OBFX_Elementor_Widget_Services();
		$widgets_manager->register_widget_type( $widget );
		// Posts grid
		$widget = new Elementor\OBFX_Elementor_Widget_Posts_Grid();
		$widgets_manager->register_widget_type( $widget );
	}

	public function enqueue_scripts() {
		// Add custom JS for grid.
		wp_enqueue_script( 'obfx-grid-js', plugins_url ( 'js/obfx-grid.js', OBFX_MODULE_URL ), array(), '1.0', true );
	}
}