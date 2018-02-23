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
		$this->activate();
	}

	/**
	 * Method invoked after options save.
	 *
	 * @since   2.3.3
	 * @access  public
	 */
	public function activate() {
		$email = sanitize_email( $this->get_option( 'monitor_email' ) );
		if ( ! is_email( $email ) ) {
			return;
		}

		$monitor_url = $this->monitor_url . '/api/monitor/create';
		$url         = home_url();
		$args        = array(
			'body' => array( 'url' => $url, 'email' => $email )
		);
		$response    = wp_remote_post( $monitor_url, $args );
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
		$this->loader->add_action( $this->get_slug() . '_before_options_save', $this, 'before_options_save', 10, 1 );
		$this->loader->add_action( $this->get_slug() . '_after_options_save', $this, 'after_options_save' );
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
	 * @param array $option
	 *
	 * @return string
	 */
	public function render_custom_control( $option = array() ) {
		return $this->generate_analytics_login();
	}

	private final function generate_analytics_login() {
		$url      = 'http://ea435eb1.ngrok.io/api/pirate-bridge/v1/auth?';
		$url      .= http_build_query( array(
				'site_hash' => $this->get_site_hash(),
				'site_url' => home_url(),
				'site_return' => admin_url( 'admin.php?page=obfx_companion' ),
			)
		);
		$template = '<a class="btn btn-lg" href="' . esc_url( $url ) . '">' . __( 'Login with Google', 'themeisle-companion' ) .'</a>';
		return $template;
	}

	private final function get_site_hash() {
		return  mb_strimwidth( rtrim( ltrim( sanitize_text_field( preg_replace('/[^a-zA-Z0-9]/', '', AUTH_KEY . SECURE_AUTH_KEY . LOGGED_IN_KEY )  ) ) ), 0, 100 );
	}
}