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
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . '../assets/css/orbit-fox-admin.css', array(), $this->version, 'all' );
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . '../assets/js/orbit-fox-admin.js', array( 'jquery' ), $this->version, false );
		do_action( 'obfx_admin_enqueue_scripts' );
	}

	/**
	 * Add admin menu items for orbit-fox.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function menu_pages() {
		add_menu_page( __( 'Orbit Fox', 'themeisle-companion' ), __( 'Orbit Fox Modules', 'obfx' ), 'manage_options', 'obfx_menu',
			array(
				$this,
				'page_modules_render',
			),
			'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCA0MDYgNDI0Ljg5Ij48ZGVmcz48c3R5bGU+LmNscy0xe2ZpbGw6I2ZmZjt9PC9zdHlsZT48L2RlZnM+PHRpdGxlPkFzc2V0IDI8L3RpdGxlPjxnIGlkPSJMYXllcl8yIiBkYXRhLW5hbWU9IkxheWVyIDIiPjxnIGlkPSJMYXllcl8xLTIiIGRhdGEtbmFtZT0iTGF5ZXIgMSI+PHBhdGggY2xhc3M9ImNscy0xIiBkPSJNMzI0LjcyLDE4NC4zNWM3LTEzLjMyLTIuNzktODMuMzItMy04My44N2wtMy4zLTguNzEtOS4yOS44MWMtLjU5LDAtNTkuNjQsMTcuNC02OC43NywyNS41OWExMTUuMTUsMTE1LjE1LDAsMCwwLTc0LjcyLDBDMTU2LjUxLDExMCw5Ny40Niw5Mi42Miw5Ni44Nyw5Mi41NmwtOS4yOS0uODEtMy4zLDguNzJjLS4yMS41NS0xMCw3MC41NS0zLDgzLjg3LTE5LjIyLDI3LjgxLTMwLjI4LDk2LTMwLjI4LDk2TDIwMywzNDNsMTUyLTYyLjU5UzM0My45MywyMTIuMTYsMzI0LjcyLDE4NC4zNVptLTIzLjA3LTY1LjljMy4yOSwxMS42Nyw2LjczLDI5LjIyLDQuNjksNDMuMTdhMTczLDE3MywwLDAsMC0zOS45LTMxLjc4QzI3Ny40OSwxMjMuODMsMjkxLjM5LDEyMC4yNSwzMDEuNjUsMTE4LjQ1Wk0yMDMsMTM2LjU3YzI2LjI5LDAsNTMuODMsMTIuMjIsNzcuMzQsMzQtMjAuMjgsMS44My00Ny42MywzLjc4LTUzLjY5LDIyLjA5LTI3LjE2LDgyLjE2LTE4LjE2LDgyLjE2LTUxLjQ1Ljg3LTcuMTMtMTcuNDItMzMuNTctMTguMTUtNTMuNTUtMTkuMTRDMTQ2LDE1MC4yMywxNzUuMiwxMzYuNTcsMjAzLDEzNi41N1ptLTk4LjY4LTE4LjA5YTE1MC45MiwxNTAuOTIsMCwwLDEsMTYuNzQsMy45Myw5Nyw5NywwLDAsMSwxOC40OCw3LjQ0LDE3MywxNzMsMCwwLDAtMzkuOTEsMzEuOEM5Ny41MywxNDcuNzUsMTAxLDEzMC4zMiwxMDQuMzIsMTE4LjQ5Wk0yMDMsMzE2LjMzLDc2LDI2NGMxLjgtMjAuNiwxMC4yNy00Mi44NiwyMy44Ni02My4yM2E3NCw3NCwwLDAsMSw4OSw1NS41OGgyNy41MkE3NCw3NCwwLDAsMSwzMDIuODEsMTk2YzE1LjU0LDIxLjYzLDI1LjI1LDQ1LjgsMjcuMTksNjhaIi8+PGNpcmNsZSBjbGFzcz0iY2xzLTEiIGN4PSIyNjQuNjIiIGN5PSIyMjkuNjkiIHI9IjEyLjMyIi8+PGNpcmNsZSBjbGFzcz0iY2xzLTEiIGN4PSIxNDEuMzgiIGN5PSIyMjkuNjkiIHI9IjEyLjMyIi8+PHBhdGggY2xhc3M9ImNscy0xIiBkPSJNMjExLjIyLDI2Mi40NUgxOTQuNzhhOC4yMiw4LjIyLDAsMCwwLTguMjIsOC4yMmMwLDguMjIsMTYuNDMsMTYuNDMsMTYuNDMsMTYuNDNzMTYuNDMtOC4yMiwxNi40My0xNi40M0E4LjIyLDguMjIsMCwwLDAsMjExLjIyLDI2Mi40NVoiLz48cGF0aCBjbGFzcz0iY2xzLTEiIGQ9Ik0yMDMsNDA2QzkxLjA3LDQwNiwwLDMxNC45MywwLDIwM1M5MS4wNywwLDIwMywwLDQwNiw5MS4wNyw0MDYsMjAzLDMxNC45Myw0MDYsMjAzLDQwNlptMC0zODJDMTA0LjMsMjQsMjQsMTA0LjMsMjQsMjAzczgwLjMsMTc5LDE3OSwxNzksMTc5LTgwLjMsMTc5LTE3OVMzMDEuNywyNCwyMDMsMjRaIi8+PGVsbGlwc2UgY2xhc3M9ImNscy0xIiBjeD0iMjM4LjI5IiBjeT0iNTguMzEiIHJ4PSIxMC40NSIgcnk9IjQ1LjUiIHRyYW5zZm9ybT0ibWF0cml4KDAuNTQsIC0wLjg0LCAwLjg0LCAwLjU0LCA2MC4xNCwgMjI2Ljk3KSIvPjxyZWN0IGNsYXNzPSJjbHMtMSIgeD0iNjkuNyIgeT0iMzUyLjg5IiB3aWR0aD0iMjY5LjMiIGhlaWdodD0iMjQiIHJ4PSIxMiIgcnk9IjEyIi8+PHJlY3QgY2xhc3M9ImNscy0xIiB4PSI4Mi43IiB5PSIzNzYuODkiIHdpZHRoPSIyNDMuMyIgaGVpZ2h0PSIyNCIgcng9IjEyIiByeT0iMTIiLz48cmVjdCBjbGFzcz0iY2xzLTEiIHg9Ijg5LjciIHk9IjQwMC44OSIgd2lkdGg9IjIyOS4zIiBoZWlnaHQ9IjI0IiByeD0iMTIiIHJ5PSIxMiIvPjwvZz48L2c+PC9zdmc+',
		'2.0' );
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
	 * A method used for saving module options data
	 * and returning a well formatted response as an array.
	 *
	 * @codeCoverageIgnore
	 *
	 * @since   1.0.0
	 * @access  public
	 * @param   array $data The options data to try and save via the module model.
	 * @return array
	 */
	public function try_module_save( $data ) {
		$response = array();
		$global_settings = new Orbit_Fox_Global_Settings();
		$modules = $global_settings::$instance->module_objects;
		$response['type'] = 'error';
		$response['message'] = __( 'No module found! No data was updated.', 'themeisle-companion' );
		if ( isset( $modules[ $data['module-slug'] ] ) ) {
			$module = $modules[ $data['module-slug'] ];
			unset( $data['noance'] );
			unset( $data['module-slug'] );
			$response['type'] = 'warning';
			$response['message'] = __( 'Something went wrong, data might not be saved!', 'themeisle-companion' );
			$result = $module->set_options( $data );
			if ( $result ) {
				$response['type'] = 'success';
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
	public function obfx_update_module_options() {
		$json = stripslashes( str_replace( '&quot;', '"', $_POST['data'] ) );
	    $data = json_decode( $json, true );
		$response['type'] = 'error';
		$response['message'] = __( 'Could not process the request!', 'themeisle-companion' );
	    if ( isset( $data['noance'] ) && wp_verify_nonce( $data['noance'], 'obfx_update_module_options_' . $data['module-slug'] ) ) {
			$response = $this->try_module_save( $data );
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
	 * @param   array $data The data to try and update status via the module model.
	 * @return array
	 */
	public function try_module_activate( $data ) {
		$response = array();
		$global_settings = new Orbit_Fox_Global_Settings();
		$modules = $global_settings::$instance->module_objects;
		$response['type'] = 'error';
		$response['message'] = __( 'No module found!', 'themeisle-companion' );
		if ( isset( $modules[ $data['name'] ] ) ) {
			$module = $modules[ $data['name'] ];
			$response['type'] = 'warning';
			$response['message'] = __( 'Something went wrong, can not change module status!', 'themeisle-companion' );
			$result = $module->set_status( 'active', $data['checked'] );
			if ( $result ) {
				$response['type'] = 'success';
				$response['message'] = __( 'Module status changed!', 'themeisle-companion' );
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
		$json = stripslashes( str_replace( '&quot;', '"', $_POST['data'] ) );
		$data = json_decode( $json, true );
		$response['type'] = 'error';
		$response['message'] = __( 'Could not process the request!', 'themeisle-companion' );
		if ( isset( $data['noance'] ) && wp_verify_nonce( $data['noance'], 'obfx_activate_mod_' . $data['name'] ) ) {
			$response = $this->try_module_activate( $data );
		}
		echo json_encode( $response );
		wp_die();
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

		$rdh = new Orbit_Fox_Render_Helper();
		$tiles = '';
		$panels = '';
		$count_modules = 0;
		foreach ( $modules as $slug => $module ) {
			$count_modules++;
			$checked = '';
			if ( $module->is_active() ) {
			    $checked = 'checked';
			}
			$data = array(
			    'slug' => $slug,
				'name' => $module->name,
				'description' => $module->description,
				'checked' => $checked,
			);
			$tiles .= $rdh->get_partial( 'module-tile', $data );
			$tiles .= '<div class="divider"></div>';

			$module_options = $module->get_options();
			$options_fields = '';
			foreach ( $module_options as $option ) {
				$options_fields .= $rdh->render_option( $option );
			}

			$panels .= $rdh->get_partial(
				'module-panel',
				array(
				        'slug' => $slug,
						'name' => $module->name,
						'active' => $module->is_active(),
						'description' => $module->description,
						'options_fields' => $options_fields,
					)
			);
		}

		$no_modules = false;
		$empty_tpl = '';
		if ( $count_modules == 0 ) {
			$no_modules = true;
			$empty_tpl = $rdh->get_partial(
				'empty',
				array(
						'title' => __( 'No modules found.', 'themeisle-companion' ),
						'sub_title' => __( 'Please contact support for more help.', 'themeisle-companion' ),
						'show_btn' => true,
					)
			);
			$panels = $rdh->get_partial(
				'empty',
				array(
					'title' => __( 'No active modules.', 'themeisle-companion' ),
					'sub_title' => __( 'Activate a module using the toggles above.', 'themeisle-companion' ),
					'show_btn' => false,
				)
			);
		}

		$data = array(
			'no_modules' => $no_modules,
			'empty_tpl' => $empty_tpl,
			'count_modules' => $count_modules,
			'tiles' => $tiles,
			'panels' => $panels,
		);
	    $output = $rdh->get_view( 'modules', $data );
	    echo $output;
	}

}
