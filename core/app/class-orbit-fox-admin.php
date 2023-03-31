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
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		add_action( 'rest_api_init', array( $this, 'init_dashboard_routes' ) );

	}

	/**
	 * Registers the REST routes used for updating the modules state and settings
	 */
	public function init_dashboard_routes() {
		register_rest_route(
			'obfx',
			'/toggle-module-state',
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'update_module_callback' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			)
		);
		register_rest_route(
			'obfx',
			'/set-module-settings',
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'update_module_callback' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			)
		);
	}

	/**
	 * Callback function for the REST requests.
	 * Updates the module data corresponding to the request and returns a response.
	 *
	 * @param WP_REST_Request $request The request
	 *
	 * @return WP_REST_Response The response to the request
	 */
	public function update_module_callback( WP_REST_Request $request ) {
		$data     = json_decode( $request->get_body(), true );
		$settings = new Orbit_Fox_Global_Settings();
		$modules  = $settings::$instance->module_objects;

		if ( ! isset( $data['slug'] ) || ! isset( $data['value'] ) ) {
			return new WP_REST_Response(
				array(
					'type'    => 'error',
					'message' => __( 'Bad request!', 'themeisle-companion' ),
				)
			);
		}

		if ( ! isset( $modules[ $data['slug'] ] ) ) {
			return new WP_REST_Response(
				array(
					'type'    => 'error',
					'message' => __( 'Module not found!', 'themeisle-companion' ),
				)
			);
		}

		$response = false;

		if ( $request->get_route() === '/obfx/toggle-module-state' ) {
			$response = $modules[ $data['slug'] ]->set_status( 'active', $data['value'] );
			$this->trigger_activate_deactivate( $data['value'], $modules[ $data['slug'] ] );
		}

		if ( $request->get_route() === '/obfx/set-module-settings' ) {
			unset( $data->slug );
			$response = $modules[ $data['slug'] ]->set_options( $data['value'] );
		}

		if ( ! $response ) {
			return new WP_REST_Response(
				array(
					'type'    => 'warning',
					'message' => __( 'Data unchanged!', 'themeisle-companion' ),
				)
			);
		}

		return new WP_REST_Response(
			array(
				'type' => 'success',
				'data' => $data,
			)
		);
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
		if ( in_array( $screen->id, array( 'toplevel_page_obfx_companion' ), true ) ) {
			$dependencies = include OBX_PATH . '/dashboard/build/dashboard.asset.php';
			wp_register_style( 'obfx-dashboard-style', OBFX_URL . 'dashboard/build/style-dashboard.css', array( 'wp-components' ), $dependencies['version'] );
			wp_enqueue_style( 'obfx-dashboard-style' );
			wp_register_style( 'obfx-dashboard-colors', OBFX_URL . 'obfx_modules/social-sharing/css/admin.css', array( 'wp-components' ), $dependencies['version'] );
			wp_enqueue_style( 'obfx-dashboard-colors' );
			wp_register_style( 'obfx-dashboard-social', OBFX_URL . 'obfx_modules/social-sharing/css/vendor/socicon/socicon.css', array( 'wp-components' ), $dependencies['version'] );
			wp_enqueue_style( 'obfx-dashboard-social' );
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
		if ( in_array( $screen->id, array( 'toplevel_page_obfx_companion' ), true ) ) {
			wp_enqueue_script( 'plugin-install' );
			wp_enqueue_script( 'updates' );

			$dependencies    = include OBX_PATH . '/dashboard/build/dashboard.asset.php';
			$global_settings = new Orbit_Fox_Global_Settings();
			$modules         = array_filter(
				$global_settings::$instance->module_objects,
				function ( $module ) {
					return $module->enable_module();
				}
			);
			$modules_options = array_map(
				function ( $module ) {
					return $module->options();
				},
				$modules
			);

			wp_register_script( 'obfx-dashboard', OBFX_URL . '/dashboard/build/dashboard.js', $dependencies['dependencies'], $this->version, true );
			wp_enqueue_script( 'obfx-dashboard' );
			wp_localize_script(
				'obfx-dashboard',
				'obfxDash',
				array(
					'path'             => OBFX_URL . '/dashboard/',
					'root'             => esc_url_raw( get_rest_url() ) . 'obfx/',
					'toggleStateRoute' => 'toggle-module-state',
					'setSettingsRoute' => 'set-module-settings',
					'nonce'            => wp_create_nonce( 'wp_rest' ),
					'modules'          => $modules,
					'data'             => get_option( 'obfx_data' ),
					'options'          => $modules_options,
					'plugins'          => $this->get_recommended_plugins(),
				)
			);
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
		add_menu_page(
			__( 'Orbit Fox', 'themeisle-companion' ),
			__( 'Orbit Fox', 'themeisle-companion' ),
			'manage_options',
			'obfx_companion',
			array(
				$this,
				'page_modules_render',
			),
			'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0MzQuNjIiIGhlaWdodD0iMzkxLjMzIiB2aWV3Qm94PSIwIDAgNDM0LjYyIDM5MS4zMyI+PGRlZnM+PHN0eWxlPi5he2ZpbGw6I2ZmZjt9PC9zdHlsZT48L2RlZnM+PHRpdGxlPmxvZ28tb3JiaXQtZm94LTI8L3RpdGxlPjxwYXRoIGNsYXNzPSJhIiBkPSJNNzA4LjE1LDM5NS4yM2gwYy0xLjQ5LTEzLjc2LTcuNjEtMjkuMjEtMTUuOTQtNDQuNzZsLTE0LjQ2LDguMzVhNzYsNzYsMCwxLDEtMTQ1LDQyLjd2MUg0OTEuMWE3Niw3NiwwLDEsMS0xNDQuODYtNDMuNjVsLTE0LjQyLTguMzNjLTguMTcsMTUuMjgtMTQuMjIsMzAuNDctMTUuODMsNDQtLjA2LjM3LS4xMS43NS0uMTQsMS4xMnMtLjA2LjQ2LS4wOC42OGguMDVBMTUuNTcsMTUuNTcsMCwwLDAsMzIwLjM1LDQwOEw1MDEsNTU1LjExYTE1LjU0LDE1LjU0LDAsMCwwLDExLDQuNTVoMGExNS41NCwxNS41NCwwLDAsMCwxMS00LjU1TDcwMy42OSw0MDhBMTUuNjMsMTUuNjMsMCwwLDAsNzA4LjE1LDM5NS4yM1pNNDc5LjU5LDQ0MC41MWwyMi4wNSw1LjkxLTIuMDcsNy43My0yMi4wNS01LjkxWm0zLDE4Ljc1LDIyLjA1LDUuOTEtMi4wNyw3LjczTDQ4MC41Miw0NjdabTEsMTguNzUsMjIuMDUsNS45MS0yLjA3LDcuNzMtMjIuMDUtNS45MVptMzEsNjMuMzhhMTIuMzgsMTIuMzgsMCwwLDAtMSwuOTEsMi4yMSwyLjIxLDAsMCwxLTEuNTguNjNoMGEyLjIxLDIuMjEsMCwwLDEtMS41OC0uNjMsMTIuMzgsMTIuMzgsMCwwLDAtMS0uOTFMNDg2Ljg5LDUyM2M4LjItLjUzLDE2LjYzLS44MSwyNS4xMS0uODFzMTYuOTMuMjgsMjUuMTUuODFabTUuODktNDkuNzQtMi4wNy03LjczTDU0MC40OSw0NzhsMi4wNyw3LjczWm0xLTE4Ljc1LTIuMDctNy43MywyMi4wNi01LjkxLDIuMDcsNy43M1ptMy0xOC43NS0yLjA3LTcuNzMsMjIuMDYtNS45MSwyLjA3LDcuNzNaIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgtMjk0LjY5IC0xNjguMzQpIi8+PHBhdGggY2xhc3M9ImEiIGQ9Ik03MjkuMjYsMjA5YTEyMC4xOCwxMjAuMTgsMCwwLDAtMS4xOC0xNC43OGMtLjEzLS44OC0uMjctMS43Mi0uNDItMi41Ni0uMjItMS4yLS40Ni0yLjM1LS43Mi0zLjQ3LS4xOC0uNzktLjM4LTEuNTctLjU4LTIuMzItLjM2LTEuMjgtLjc0LTIuNDgtMS4xNi0zLjYyLS4zNi0uOTUtLjc0LTEuODUtMS4xNS0yLjctLjItLjQzLS40MS0uODQtLjYzLTEuMjRzLS40My0uNzktLjY1LTEuMTVhMTkuNzYsMTkuNzYsMCwwLDAtMS4xNS0xLjY4LDE0LjE5LDE0LjE5LDAsMCwwLTEuMTUtMS4zNiwxMS44NywxMS44NywwLDAsMC0xLS45MWMtLjExLS4xLS4yNS0uMTgtLjM3LS4yN2ExNS4yMSwxNS4yMSwwLDAsMC0yLjU0LTEuNTlsLTEuMDYtLjQ5YTI1LjU3LDI1LjU3LDAsMCwwLTMuODUtMS4yNWMtLjc0LS4xOC0xLjUyLS4zNS0yLjMzLS40OS0xLjExLS4xOS0yLjI4LS4zNS0zLjUtLjQ3cy0yLjY5LS4yMS00LjExLS4yNWMtMi4xNC0uMDctNC4zOSwwLTYuNzMuMDktMS41Ny4wOC0zLjE4LjItNC44Mi4zNmwtMi44MS4zYTE3MSwxNzEsMCwwLDAtMTgsMy4xN2wtMy4xMi43NHEtNC44NywxLjItOS43OSwyLjY0Yy0zLjI3LDEtNi41NCwyLTkuNzcsMy4xMXEtNS4yNCwxLjc4LTEwLjMsNGMtLjg1LjM3LTEuNjkuNzUtMi41MywxLjE0cS0zLjc4LDEuNzYtNy40OCwzLjc4YTE0Mi4zNywxNDIuMzcsMCwwLDAtMTIuOCw3Ljg4Yy0xLjQsMS0yLjgxLDItNC4yLDNhMjAxLjUzLDIwMS41MywwLDAsMC0yMy43LDIwLjc3Yy0yMC4zNy0xNC00Mi4zLTIwLTczLjctMjAuNDZ2MS43N2gwdi0xLjc3Yy0zMS40MS41LTUzLjM1LDYuNDQtNzMuNzIsMjAuNDctMTkuODQtMjAuMS0zOS4yNi0zMy4xNi02MS00MC42LTI5LjU2LTEwLjExLTYyLTE0LjU5LTcyLjc2LTUuNnMtMTEuOTUsNDEuNzYtNy4xMyw3Mi42M2M0LjU1LDI5LjEsMTguODMsNTYsNDQuNzgsODdsMCwuMDYsMTQuNDgsOC4zNkE3Niw3NiwwLDAsMSw0OTIuMjIsMzgyaDM5LjU2QTc2LDc2LDAsMCwxLDY2Ny40LDM0MS4xOWwxNC41Mi04LjM5LDAtLjA3cTMuNTctNC4yNiw2Ljg0LTguNDNjMS41LTEuODksMi45NC0zLjc3LDQuMzQtNS42NHMyLjc2LTMuNzMsNC4wNy01LjU3Yy42Ni0uOTIsMS4zLTEuODQsMS45NC0yLjc2cTEuOS0yLjc2LDMuNjctNS40OCwyLjY3LTQuMSw1LTguMTN0NC40NS04LjA1Yy45Mi0xLjc4LDEuODEtMy41NiwyLjY1LTUuMzRxMS44OS00LDMuNTEtOGMuNzItMS43OCwxLjM5LTMuNTUsMi01LjMzLjMyLS44OC42My0xLjc3LjkzLTIuNjYuNi0xLjc4LDEuMTUtMy41NiwxLjY3LTUuMzRhMTMxLjU0LDEzMS41NCwwLDAsMCwzLjYxLTE2LjIxLDIyMS4yNCwyMjEuMjQsMCwwLDAsMi42OC0zMS40NkM3MjkuMzIsMjEyLjUyLDcyOS4zMSwyMTAuNzMsNzI5LjI2LDIwOVpNMzg5LjMxLDI2OC43OWMtOS4yOSwxMS41OC0yMi4zNywyNy43Ni0zNC45NCw0NS42Ni0xMS42NC0xNi45Mi0yNC43Ni0zOC42MS0yNy40OS01Ny42NS0zLjEzLTIxLjg2LTEuOTQtMzcuNTktLjA3LTQzLjQ4YTMyLjY1LDMyLjY1LDAsMCwxLDQuMjktLjI1YzkuODYsMCwyNC4yOCwyLjkyLDM4LjU5LDcuODEsMTMuNTMsNC42MywyNi4xNSwxMi41NiwzOS4yNiwyNC44NUM0MDIuNjgsMjUyLjU0LDM5Ni4yMSwyNjAuMTksMzg5LjMxLDI2OC43OVptMzA3LjgxLTEyYy0yLjczLDE5LTE1LjgzLDQwLjctMjcuNDYsNTcuNjEtMTIuNTctMTcuODgtMjUuNjQtMzQuMDUtMzQuOTMtNDUuNjItNi45MS04LjYxLTEzLjM4LTE2LjI2LTE5LjY2LTIzLjA4LDEzLjExLTEyLjI4LDI1LjcyLTIwLjIsMzkuMjQtMjQuODMsMTQuMzEtNC44OSwyOC43My03LjgxLDM4LjU5LTcuODFhMzIuNjUsMzIuNjUsMCwwLDEsNC4yOS4yNUM2OTkuMDYsMjE5LjIxLDcwMC4yNSwyMzQuOTQsNjk3LjEyLDI1Ni44WiIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoLTI5NC42OSAtMTY4LjM0KSIvPjxwYXRoIGNsYXNzPSJhIiBkPSJNNDE2LjQ0LDMzMS41N0E1Ni41MSw1Ni41MSwwLDEsMCw0NzMsMzg4LjA4LDU2LjU3LDU2LjU3LDAsMCwwLDQxNi40NCwzMzEuNTdabTMxLjYyLDg2LjM2YTIzLjQ0LDIzLjQ0LDAsMSwxLDUtNy4zOCw5LjI1LDkuMjUsMCwwLDEtMS43OSwzLjM5QTIyLjcxLDIyLjcxLDAsMCwxLDQ0OC4wNiw0MTcuOTNaIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgtMjk0LjY5IC0xNjguMzQpIi8+PHBhdGggY2xhc3M9ImEiIGQ9Ik02MDcuNTYsMzMxLjU3YTU2LjUxLDU2LjUxLDAsMSwwLDU2LjUxLDU2LjUxQTU2LjU3LDU2LjU3LDAsMCwwLDYwNy41NiwzMzEuNTdabTEuNTMsODYuMzZhMjMuNDIsMjMuNDIsMCwwLDEtMzMuMTMsMCwyMy4xOCwyMy4xOCwwLDAsMS0zLjE5LTQsOS4wOCw5LjA4LDAsMCwxLTEuNzgtMy4zOSwyMy40MiwyMy40MiwwLDEsMSwzOC4xLDcuMzhaIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgtMjk0LjY5IC0xNjguMzQpIi8+PC9zdmc+',
			'75'
		);
		add_submenu_page( 'obfx_companion', __( 'Orbit Fox General Options', 'themeisle-companion' ), __( 'General Settings', 'themeisle-companion' ), 'manage_options', 'obfx_companion' );
	}

	/**
	 * Add the initial dashboard notice to guide the user to the OrbitFox admin page.
	 *
	 * @since   2.3.4
	 * @access  public
	 */
	public function visit_dashboard_notice() {
		global $current_user;
		$user_id = $current_user->ID;
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		$screen = get_current_screen();
		if ( empty( $screen ) ) {
			return;
		}

		if ( in_array( $screen->id, array( 'toplevel_page_obfx_companion' ), true ) ) {
			add_user_meta( $user_id, 'obfx_ignore_visit_dashboard_notice', 'true', true );
			return;
		}
		if ( ! get_user_meta( $user_id, 'obfx_ignore_visit_dashboard_notice' ) ) { ?>
			<div class="notice notice-info" style="position:relative;">
				<p>
					<?php
					/*
					 * translators: Go to url.
					 */
					echo sprintf( esc_attr__( 'You have activated Orbit Fox plugin! Go to the %s to get started with the extra features.', 'themeisle-companion' ), sprintf( '<a href="%s">%s</a>', esc_url( add_query_arg( 'obfx_ignore_visit_dashboard_notice', '0', admin_url( 'admin.php?page=obfx_companion' ) ) ), esc_attr__( 'Dashboard Page', 'themeisle-companion' ) ) );
					?>
				</p>
				<a href="<?php echo esc_url( add_query_arg( 'obfx_ignore_visit_dashboard_notice', '0', admin_url( 'admin.php?page=obfx_companion' ) ) ); ?>"
				   class="notice-dismiss" style="text-decoration: none;">
					<span class="screen-reader-text">Dismiss this notice.</span>
				</a>
			</div>
			<?php
		}
	}

	/**
	 * Dismiss the initial dashboard notice.
	 *
	 * @since   2.3.4
	 * @access  public
	 */
	public function visit_dashboard_notice_dismiss() {
		global $current_user;
		$user_id = $current_user->ID;
		// phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison
		if ( isset( $_GET['obfx_ignore_visit_dashboard_notice'] ) && '0' == $_GET['obfx_ignore_visit_dashboard_notice'] ) {
			add_user_meta( $user_id, 'obfx_ignore_visit_dashboard_notice', 'true', true );
			wp_safe_redirect( admin_url( 'admin.php?page=obfx_companion' ) );
			exit;
		}
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
	 * @param array $data The options data to try and save via the module model.
	 *
	 * @return array
	 * @since   1.0.0
	 * @access  public
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
	 * Define render function for recommended tab.
	 */
	public function get_recommended_plugins() {
		$plugins = array(
			'optimole-wp',
			'wp-cloudflare-page-cache',
			'feedzy-rss-feeds',
			'translatepress-multilingual',
			'otter-blocks',
			'wp-maintenance-mode',
			'multiple-pages-generator-by-porthas',
		);

		$th_plugins = array(
			'wp-landing-kit' => array(
				'banner'      => esc_url( OBFX_URL ) . '/dashboard/assets/wp-landing.jpg',
				'name'        => 'WP Landing Kit',
				'description' => __( 'Turn WordPress into a landing page powerhouse with Landing Kit. Map domains to pages or any other published resource.', 'themeisle-companion' ),
				'author'      => 'Themeisle',
				'action'      => 'external',
				'url'         => tsdk_utmify( 'https://wplandingkit.com/', 'recommendedplugins', 'orbitfox' ),
				'premium'     => true,
			),
		);

		if ( class_exists( 'woocommerce' ) ) {
			$th_plugins['sparks'] = array(
				'banner'      => esc_url( OBFX_URL ) . '/dashboard/assets/sparks.jpeg',
				'name'        => 'Sparks for WooCommerce',
				'description' => __( 'Conversion-focused features for your online store.', 'themeisle-companion' ),
				'author'      => 'Themeisle',
				'action'      => 'external',
				'url'         => tsdk_utmify( 'https://themeisle.com/plugins/sparks-for-woocommerce/', 'recommendedplugins', 'orbitfox' ),
				'premium'     => true,
			);
		}
		$install_instance = new Orbit_Fox_Plugin_Install();

		shuffle( $plugins );

		$data = array();
		foreach ( $plugins as $plugin ) {
			$current_plugin = $install_instance->call_plugin_api( $plugin );
			if ( ! isset( $current_plugin->name ) ) {
				continue;
			}

			$data[ $plugin ] = array(
				'banner'      => $current_plugin->banners['low'],
				'name'        => html_entity_decode( $current_plugin->name ),
				'description' => html_entity_decode( $current_plugin->short_description, ENT_QUOTES ),
				'version'     => $current_plugin->version,
				'author'      => html_entity_decode( wp_strip_all_tags( $current_plugin->author ) ),
				'action'      => $install_instance->check_plugin_state( $plugin ),
				'path'        => $install_instance->get_plugin_path( $plugin ),
				'activate'    => $install_instance->get_plugin_action_link( $plugin ),
				'deactivate'  => $install_instance->get_plugin_action_link( $plugin, 'deactivate' ),
			);
		}

		foreach ( $th_plugins as $plugin_slug => $plugin_data ) {
			$data[ $plugin_slug ] = $plugin_data;
		}

		return $data;
	}

	/**
	 * A method to trigger module activation or deavtivation hook
	 * based in active status.
	 *
	 * @codeCoverageIgnore
	 *
	 * @param boolean $active_status The active status.
	 * @param Orbit_Fox_Module_Abstract $module The module referenced.
	 *
	 * @since   2.3.3
	 * @access  public
	 */
	public function trigger_activate_deactivate( $active_status, Orbit_Fox_Module_Abstract $module ) {
		if ( $active_status === true ) {
			do_action( $module->get_slug() . '_activate' );
		} else {
			do_action( $module->get_slug() . '_deactivate' );
		}
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
		echo '<div id="obfx-dash"></div>'; // entry point for the React dashboard
	}

}
