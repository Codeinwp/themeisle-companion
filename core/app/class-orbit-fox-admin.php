<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://themeisle.com
 * @since      1.0.0
 *
 * @package    Orbit_Fox
 * @subpackage Orbit_Fox/app
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Orbit_Fox
 * @subpackage Orbit_Fox/app
 * @author     Themeisle <friends@themeisle.com>
 */
class Orbit_Fox_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param      string $plugin_name The name of this plugin.
	 * @param      string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Orbit_Fox_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Orbit_Fox_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		$screen = get_current_screen();
		if ( empty( $screen ) ) {
			return;
		}
		if ( in_array( $screen->id, array( 'tools_page_obfx_companion' ) ) ) {
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . '../assets/css/orbit-fox-admin.css', array(), $this->version, 'all' );
		}
		do_action( 'obfx_admin_enqueue_styles' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Orbit_Fox_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Orbit_Fox_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		$screen = get_current_screen();
		if ( empty( $screen ) ) {
			return;
		}
		if ( in_array( $screen->id, array( 'tools_page_obfx_companion' ) ) ) {
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . '../assets/js/orbit-fox-admin.js', array( 'jquery' ), $this->version, false );
		}
		do_action( 'obfx_admin_enqueue_scripts' );
	}

	/**
	 * Add admin menu items for orbit-fox.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function menu_pages() {
		add_management_page(
			__( 'Orbit Fox', 'themeisle-companion' ), __( 'Orbit Fox Companion', 'themeisle-companion' ), 'manage_options', 'obfx_companion',
			array(
				$this,
				'page_modules_render',
			)
		);
	}

	/**
	 * Calls the orbit_fox_modules hook.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function load_modules() {
		do_action( 'orbit_fox_modules' );
	}

	/**
	 * This method is called via AJAX and processes the
	 * request for updating module options.
	 *
	 * @codeCoverageIgnore
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function obfx_update_module_options() {
		$json                = stripslashes( str_replace( '&quot;', '"', $_POST['data'] ) );
		$data                = json_decode( $json, true );
		$response['type']    = 'error';
		$response['message'] = __( 'Could not process the request!', 'themeisle-companion' );
		if ( isset( $data['noance'] ) && wp_verify_nonce( $data['noance'], 'obfx_update_module_options_' . $data['module-slug'] ) ) {
			$response = $this->try_module_save( $data );
		}
		echo json_encode( $response );
		wp_die();
	}

	/**
	 * A method used for saving module options data
	 * and returning a well formatted response as an array.
	 *
	 * @codeCoverageIgnore
	 *
	 * @since   1.0.0
	 * @access  public
	 *
	 * @param   array $data The options data to try and save via the module model.
	 *
	 * @return array
	 */
	public function try_module_save( $data ) {
		$response            = array();
		$global_settings     = new Orbit_Fox_Global_Settings();
		$modules             = $global_settings::$instance->module_objects;
		$response['type']    = 'error';
		$response['message'] = __( 'No module found! No data was updated.', 'themeisle-companion' );
		if ( isset( $modules[ $data['module-slug'] ] ) ) {
			$module = $modules[ $data['module-slug'] ];
			unset( $data['noance'] );
			unset( $data['module-slug'] );
			$response['type']    = 'warning';
			$response['message'] = __( 'Something went wrong, data might not be saved!', 'themeisle-companion' );
			$result              = $module->set_options( $data );
			if ( $result ) {
				$response['type']    = 'success';
				$response['message'] = __( 'Options updated, successfully!', 'themeisle-companion' );
			}
		}

		return $response;
	}

	/**
	 * This method is called via AJAX and processes the
	 * request for updating module options.
	 *
	 * @codeCoverageIgnore
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function obfx_update_module_active_status() {
		$json                = stripslashes( str_replace( '&quot;', '"', $_POST['data'] ) );
		$data                = json_decode( $json, true );
		$response['type']    = 'error';
		$response['message'] = __( 'Could not process the request!', 'themeisle-companion' );
		if ( isset( $data['noance'] ) && wp_verify_nonce( $data['noance'], 'obfx_activate_mod_' . $data['name'] ) ) {
			$response = $this->try_module_activate( $data );
		}
		echo json_encode( $response );
		wp_die();
	}

	/**
	 * A method used for saving module status data
	 * and returning a well formatted response as an array.
	 *
	 * @codeCoverageIgnore
	 *
	 * @since   1.0.0
	 * @access  public
	 *
	 * @param   array $data The data to try and update status via the module model.
	 *
	 * @return array
	 */
	public function try_module_activate( $data ) {
		$response            = array();
		$global_settings     = new Orbit_Fox_Global_Settings();
		$modules             = $global_settings::$instance->module_objects;
		$response['type']    = 'error';
		$response['message'] = __( 'No module found!', 'themeisle-companion' );
		if ( isset( $modules[ $data['name'] ] ) ) {
			$module              = $modules[ $data['name'] ];
			$response['type']    = 'warning';
			$response['message'] = __( 'Something went wrong, can not change module status!', 'themeisle-companion' );
			$result              = $module->set_status( 'active', $data['checked'] );
			if ( $result ) {
				$response['type']    = 'success';
				$response['message'] = __( 'Module status changed!', 'themeisle-companion' );
			}
		}

		return $response;
	}

	/**
	 * Method to display modules page.
	 *
	 * @codeCoverageIgnore
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function page_modules_render() {
		$global_settings = new Orbit_Fox_Global_Settings();

		$modules = $global_settings::$instance->module_objects;

		$rdh           = new Orbit_Fox_Render_Helper();
		$tiles         = '';
		$panels        = '';
		$toasts        = '';
		$count_modules = 0;
		foreach ( $modules as $slug => $module ) {
			if ( $module->enable_module() ) {
				$notices        = $module->get_notices();
				$showed_notices = $module->get_status( 'showed_notices' );
				if ( ! is_array( $showed_notices ) ) {
					$showed_notices = array();
				}
				if ( isset( $showed_notices ) && is_array( $showed_notices ) ) {
					foreach ( $notices as $notice ) {
						$hash = md5( serialize( $notice ) );
						$data = array(
							'notice' => $notice,
						);
						if ( $notice['display_always'] == false && ! in_array( $hash, $showed_notices ) ) {
							$toasts .= $rdh->get_partial( 'module-toast', $data );
						} elseif ( $notice['display_always'] == true ) {
							$toasts .= $rdh->get_partial( 'module-toast', $data );
						}
					}
				}

				$module->update_showed_notices();
				if ( $module->auto == false ) {
					$count_modules ++;
					$checked = '';
					if ( $module->get_is_active() ) {
						$checked = 'checked';
					}

					$data   = array(
						'slug'        => $slug,
						'name'        => $module->name,
						'description' => $module->description,
						'checked'     => $checked,
					);
					$tiles .= $rdh->get_partial( 'module-tile', $data );
					$tiles .= '<div class="divider"></div>';
				}

				$module_options = $module->get_options();
				$options_fields = '';
				if ( ! empty( $module_options ) ) {
					foreach ( $module_options as $option ) {
						$options_fields .= $rdh->render_option( $option );
					}

					$panels .= $rdh->get_partial(
						'module-panel',
						array(
							'slug'           => $slug,
							'name'           => $module->name,
							'active'         => $module->get_is_active(),
							'description'    => $module->description,
							'options_fields' => $options_fields,
						)
					);
				}
			}// End if().
		}// End foreach().

		$no_modules = false;
		$empty_tpl  = '';
		if ( $count_modules == 0 ) {
			$no_modules = true;
			$empty_tpl  = $rdh->get_partial(
				'empty',
				array(
					'title'     => __( 'No modules found.', 'themeisle-companion' ),
					'sub_title' => __( 'Please contact support for more help.', 'themeisle-companion' ),
					'show_btn'  => true,
				)
			);
			$panels     = $rdh->get_partial(
				'empty',
				array(
					'title'     => __( 'No active modules.', 'themeisle-companion' ),
					'sub_title' => __( 'Activate a module using the toggles above.', 'themeisle-companion' ),
					'show_btn'  => false,
				)
			);
		}

		$data   = array(
			'no_modules'    => $no_modules,
			'empty_tpl'     => $empty_tpl,
			'count_modules' => $count_modules,
			'tiles'         => $tiles,
			'toasts'        => $toasts,
			'panels'        => $panels,
		);
		$output = $rdh->get_view( 'modules', $data );
		echo $output;
	}

}
