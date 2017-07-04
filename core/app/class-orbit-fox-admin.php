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
		add_menu_page( __( 'Orbit Fox', 'obfx' ), __( 'Orbit Fox Modules', 'obfx' ), 'manage_options', 'obfx_menu',
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
		foreach ( $modules as $module ) {
			$count_modules++;
			$data = array(
				'name' => $module->name,
				'description' => $module->description,
			);
			$tiles .= $rdh->get_partial( 'module-tile', $data );
			$tiles .= '<div class="divider"></div>';

			$module_options = $module->options();
			$options_fields = '';
			foreach ( $module_options as $option ) {
				$options_fields .= $rdh->render_option( $option );
			}

			$panels .= $rdh->get_partial(
				'module-panel',
				array(
						'name' => $module->name,
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
						'title' => __( 'No modules found.', 'obfx' ),
						'sub_title' => __( 'Please contact support for more help.', 'obfx' ),
						'show_btn' => true,
					)
			);
			$panels = $rdh->get_partial(
				'empty',
				array(
					'title' => __( 'No active modules.', 'obfx' ),
					'sub_title' => __( 'Activate a module using the toggles above.', 'obfx' ),
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
