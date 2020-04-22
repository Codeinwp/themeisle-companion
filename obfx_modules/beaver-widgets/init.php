<?php
/**
 * Beaver Builder modules Orbit Fox Module.
 *
 * @link       https://themeisle.com
 * @since      2.2.5
 */

use ThemeIsle\ContentForms\Form_Manager;

define( 'BEAVER_WIDGETS_PATH', plugin_dir_path( __FILE__ ) );
define( 'BEAVER_WIDGETS_URL', plugins_url( '/', __FILE__ ) );

/**
 * Class Beaver_Widgets_OBFX_Module
 */
class Beaver_Widgets_OBFX_Module extends Orbit_Fox_Module_Abstract {

	/**
	 * Beaver_Widgets_OBFX_Module constructor.
	 *
	 * @since   2.2.5
	 * @access  public
	 */
	public function __construct() {
		parent::__construct();
		$this->name           = __( 'Page builder widgets', 'themeisle-companion' );
		$this->description    = __( 'Adds widgets to the most popular builders: Elementor or Beaver. More to come!', 'themeisle-companion' );
		$this->active_default = true;
	}

	/**
	 * Determine if module should be loaded.
	 *
	 * @since   2.2.5
	 * @access  public
	 * @return bool
	 */
	public function enable_module() {
		$this->check_new_user();
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
		return is_plugin_active( 'beaver-builder-lite-version/fl-builder.php' ) || is_plugin_active( 'bb-plugin/fl-builder.php' );
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
		$this->loader->add_action( 'init', $this, 'load_content_forms' );
		$this->loader->add_action( 'init', $this, 'load_widgets_modules' );
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
	 * Check if it's a new user for orbit fox.
	 *
	 * @return bool
	 */
	private function check_new_user() {
		$is_new_user = get_option( 'obfx_new_user' );
		if ( $is_new_user === 'yes' ) {
			return true;
		}

		$install_time = get_option( 'themeisle_companion_install' );
		$current_time = get_option( 'module_check_time' );
		if ( empty( $current_time ) ) {
			$current_time = time();
			update_option( 'module_check_time', $current_time );
		}
		if ( empty( $install_time ) || empty( $current_time ) ) {
			return false;
		}

		if ( ( $current_time - $install_time ) <= 60 ) {
			update_option( 'obfx_new_user', 'yes' );
			return true;
		}

		update_option( 'obfx_new_user', 'no' );
		return false;
	}

	/**
	 * Require Beaver Builder modules
	 *
	 * @since   2.2.5
	 * @access  public
	 * @return bool
	 */
	public function load_widgets_modules() {
		if ( ! class_exists( 'FLBuilderModel' ) || ! class_exists( 'FLBuilder' ) ) {
			return false;
		}
		$is_new_user  = get_option( 'obfx_new_user' );
		$modules_list = FLBuilderModel::$modules;

		$modules_to_load = array(
			'pricing-table',
			'services',
			'post-grid',
		);

		$new_user_prefix = '';
		if ( $is_new_user === 'yes' ) {
			$new_user_prefix = 'obfx-';
		}

		foreach ( $modules_to_load as $module ) {
			$prefix = $new_user_prefix;
			if ( empty( $prefix ) && array_key_exists( $module, $modules_list ) ) {
				$prefix = 'obfx-';
			}
			require_once 'modules/' . $module . '/' . $prefix . $module . '.php';
		}
		return true;
	}

}
