<?php
/**
 * Elementor Widgets Core Orbit Fox Module.
 *
 * @link       https://themeisle.com
 * @since      1.0.0
 *
 * @package    Elementor_Widgets_OBFX_Module
 */

/**
 * The class defines a new module to be used by Orbit Fox plugin.
 *
 * @package    Elementor_Widgets_OBFX_Module
 * @author     Themeisle <friends@themeisle.com>
 * @codeCoverageIgnore
 */
class Elementor_Widgets_OBFX_Module extends Orbit_Fox_Module_Abstract {

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

		if ( defined( 'ELEMENTOR_VERSION' ) ) {

		}
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
	 * @return mixed | array
	 */
	public function hooks() {

		$this->loader->add_action( 'elementor/init', $this, 'add_elementor_category' );
		$this->loader->add_action( 'elementor/widgets/widgets_registered', $this, 'add_elementor_widgets' );


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

	public function add_elementor_category() {
		\Elementor\Plugin::instance()->elements_manager->add_category(
			'obfx-elementor-widgets',
			array(
				'title' => __( 'Orbit Fox Addons', 'themeisle-companion' ),
				'icon'  => 'fa fa-plug',
			),
			1 );
	}

	public function add_elementor_widgets( $widgets_manager ) {

		require_once $this->get_dir() . '/widgets/widget-dummy.php';
		$widget = new Elementor\OBFX_Elementor_Widget_Dummy();
		$widgets_manager->register_widget_type( $widget );

		require_once $this->get_dir() . '/widgets/widget-pricing-table.php';
		$widget = new Elementor\OBFX_Elementor_Widget_Pricing_Table();
		$widgets_manager->register_widget_type( $widget );
	}
}