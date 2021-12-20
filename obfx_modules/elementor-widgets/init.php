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

use \ThemeIsle\ContentForms\Form_Manager;

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
		$this->name        = __( 'Page builder widgets', 'themeisle-companion' );
		$this->description = __( 'Adds widgets to the most popular builders: Elementor or Beaver. More to come!', 'themeisle-companion' );

		if ( wp_get_theme()->get( 'Name' ) === 'Neve' && ! is_plugin_active( 'neve-pro-addon/neve-pro-addon.php' ) ) {
			$this->description .=
				'<div class="neve-pro-notice">
					<p>' .
						sprintf( /* translators: %1$s is the features, %2$s is the plugin name  */
							__( 'You can get access to %1$s, including Instagram integration, display conditions and more in %2$s.', 'themeisle-companion' ) .
							sprintf(
								'<b>%1$s</b>',
								__( '10+ more Elementor and Gutenberg widgets', 'themeisle-companion' )
							),
							'<b>Neve PRO</b>'
						) .
					'</p>
					<a class="notice-cta" target="_blank" href="https://themeisle.com/themes/neve/upgrade/?utm_medium=dashboard&utm_source=pagebuildermodule&utm_campaign=orbitfox">
						<b>' . __( 'Learn more', 'themeisle-companion' ) . '</b>
					</a>
				</div>';
		}

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
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
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
		$this->loader->add_action( 'init', $this, 'load_content_forms' );
		$this->loader->add_action( 'plugins_loaded', $this, 'load_elementor_extra_widgets' );
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
		$options = array();

		return $options;
	}

	/**
	 * If the content-forms library is available we should make the forms available for elementor
	 */
	public function load_content_forms() {
		if ( ! class_exists( '\ThemeIsle\ContentForms\Form_Manager' ) ) {
			return false;
		}
		$content_forms = new Form_Manager();
		$content_forms->instance();

		return true;
	}

	/**
	 * Call the ElementorExtraWidgets Library which will register its own actions.
	 */
	public function load_elementor_extra_widgets() {
		if ( class_exists( '\ThemeIsle\ElementorExtraWidgets' ) ) {
			\ThemeIsle\ElementorExtraWidgets::instance();
		}
	}

}
