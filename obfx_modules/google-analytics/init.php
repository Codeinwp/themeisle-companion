<?php
/**
 * The Mock-up to demonstrate and test module use.
 *
 * @link       https://themeisle.com
 * @since      1.0.0
 *
 * @package    Uptime_Monitor_OBFX_Module
 */

/**
 * The class defines a new module to be used by Orbit Fox plugin.
 *
 * @package    Uptime_Monitor_OBFX_Module
 * @author     Themeisle <friends@themeisle.com>
 */
class Google_Analytics_OBFX_Module extends Orbit_Fox_Module_Abstract {

	/**
	 * Test_OBFX_Module constructor.
	 *
	 * @since   4.0.3
	 * @access  public
	 */
	public function __construct() {
		parent::__construct();
		$this->name        = __( 'Analytics Integration', 'themeisle-companion' );
		$this->description = __( 'A module to integrate Google Analytics into your site easily.', 'themeisle-companion' );
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
	 * Method called on module activation.
	 * Calls the API to register an url to monitor.
	 *
	 * @since   2.3.3
	 * @access  public
	 */
	public function after_options_save() {
	}

	/**
	 * Method invoked after options save.
	 *
	 * @since   2.3.3
	 * @access  public
	 */
	public function activate() {
	}

	/**
	 * Method invoked before options save.
	 *
	 * @since   2.3.3
	 * @access  public
	 */
	public function before_options_save( $options ) {
		$this->deactivate();
	}

	/**
	 * Method called on module deactivation.
	 * Calls the API to unregister an url from the monitor.
	 *
	 * @since   2.3.3
	 * @access  public
	 */
	public function deactivate() {

		$monitor_url  = $this->monitor_url . '/api/monitor/remove';
		$url          = home_url();
		$args         = array(
			'body' => array( 'url' => $url )
		);
		$response     = wp_remote_post( $monitor_url, $args );
		$api_response = json_decode( $response['body'] );
	}

	/**
	 * Method to define hooks needed.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function hooks() {
		$this->loader->add_action( 'current_screen', $this, 'maybe_save_obfx_token' );
		$this->loader->add_filter( 'obfx_custom_control_google_signin', $this, 'render_custom_control' );
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
	 * @return array|boolean
	 */
	public function admin_enqueue() {
		$current_screen = get_current_screen();

		if ( ! isset( $current_screen->id ) ) {
			return array();
		}
		if ( $current_screen->id != 'dashboard' ) {
			return array();
		}

		return array(
			'js'  => array(
				'stats' => array( 'jquery' ),
			),
			'css' => array(
				'stats' => false,
			),
		);
	}

	/**
	 * Method to define the options fields for the module
	 *
	 * @since   1.0.0
	 * @access  public
	 * @return array
	 */
	public function options() {
		return array(
			array(
				'id'          => 'google_signin',
				'name'        => 'google_signin',
				'title'       => '',
				'description' => 'Sign in to use the Google Analytics integration.',
				'type'        => 'custom',
				'default'     => '',
			)
		);
	}

	/**
	 * Renders a custom control.
	 *
	 * @param array $option
	 *
	 * @return string
	 */
	public function render_custom_control( $option = array() ) {
		$obfx_token = get_option( 'obfx_token', '' );
		if ( empty( $obfx_token ) ) {
			return $this->generate_analytics_login();
		}
		return $this->get_tracking_codes( $obfx_token );
	}

	public function get_tracking_codes( $obfx_token = '' ) {
		if ( ! isset( $obfx_token ) ) {
			return false;
		}
		$response = wp_remote_post( 'http://redirecter2.local/api/pirate-bridge/v1/get_tracking_links',
			array(
				'headers' => array( 'x-obfx-auth' => $obfx_token ),
				'body' => array( 'site_url' => home_url(), 'site_hash' => $this->get_site_hash() ),
			) );
		print_r( $response['body'] );
//		wp_die();
	}

	/**
	 * Generates the analytics login control.
	 *
	 * @return string
	 */
	private final function generate_analytics_login() {
		$url = 'http://c3f82bad.ngrok.io/api/pirate-bridge/v1/auth';
		$url = add_query_arg( array(
			'site_hash'   => $this->get_site_hash(),
			'site_url'    => home_url(),
			'site_return' => admin_url( 'admin.php?page=obfx_companion' ),
		), $url );

		$template = '<a class="btn btn-lg" href="' . esc_url( $url ) . '">' . __( 'Login with Google', 'themeisle-companion' ) . '</a>';

		return $template;
	}

	/**
	 * Generate a website hash.
	 *
	 * @return string
	 */
	private final function get_site_hash() {
		return mb_strimwidth( rtrim( ltrim( sanitize_text_field( preg_replace( '/[^a-zA-Z0-9]/', '', AUTH_KEY . SECURE_AUTH_KEY . LOGGED_IN_KEY ) ) ) ), 0, 100 );
	}

	public final function maybe_save_obfx_token() {
		$obfx_token = isset( $_GET['obfx_token'] ) ? sanitize_text_field( $_GET['obfx_token'] ) : '';
		if ( empty( $obfx_token ) ) {
			return '';
		}
		if ( ! is_admin() ) {
			return '';
		}
		$current_screen = get_current_screen();
		if ( ! isset( $current_screen->id ) ) {
			return '';
		}
		if ( $current_screen->id !== 'toplevel_page_obfx_companion' ) {
			return '';
		}
		update_option( 'obfx_token', $obfx_token );
		wp_safe_redirect( admin_url( 'admin.php?page=obfx_companion' ) );
	}
}