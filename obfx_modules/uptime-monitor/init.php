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
class Uptime_Monitor_OBFX_Module extends Orbit_Fox_Module_Abstract {

	/**
	 * The API endpoint for monitor.
	 *
	 * @since   1.0.0
	 * @access  private
	 * @var     string $monitor_url The Monitor API url base.
	 */
	private $monitor_url = 'https://monitor.orbitfox.com/api/';

	/**
	 * Test_OBFX_Module constructor.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function __construct() {
		parent::__construct();
		$this->name        = __( 'Uptime Monitor', 'themeisle-companion' );
		$this->description = __( 'A simple module to notify you if your webpage goes down.', 'themeisle-companion' );

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
	public function load() {}

	/**
	 * Method called on module activation.
	 * Calls the API to register an url to monitor.
	 *
	 * @since   2.3.3
	 * @access  public
	 */
	public function activate() {
		$monitor_url = $this->monitor_url . 'monitor/create';
		$url = $this->get_option( 'monitor_url' );
		$email = $this->get_option( 'monitor_email' );
		$args = array(
			'body' => array( 'url' => $url, 'email' => $email )
		);
		$response = wp_remote_post( $monitor_url, $args );
		$api_response = json_decode( $response['body'] );

		if( $api_response->status != '200' ) {
			$response['type']    = 'error';
			$response['message'] = $api_response->message;
			echo json_encode( $response ); wp_die();

		}
	}

	/**
	 * Method called on module deactivation.
	 * Calls the API to unregister an url from the monitor.
	 *
	 * @since   2.3.3
	 * @access  public
	 */
	public function deactivate() {
		$monitor_url = $this->monitor_url . 'monitor/remove';
		$url = $this->get_option( 'monitor_url' );
		$args = array(
			'body' => array( 'url' => $url )
		);
		$response = wp_remote_post( $monitor_url, $args );
		$api_response = json_decode( $response['body'] );
	}

	/**
	 * Method invoked after options save.
	 *
	 * @since   2.3.3
	 * @access  public
	 */
	public function after_options_save() {
		$this->activate();
	}

	/**
	 * Method invoked before options save.
	 *
	 * @since   2.3.3
	 * @access  public
	 */
	public function before_options_save( $options ) {
		$old_url = $this->get_option( 'monitor_url' );
		$old_email = $this->get_option( 'monitor_email' );

		if( $options['monitor_url'] != $old_url ) {
			$this->deactivate();
		}
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
				'stats'            => array( 'jquery' ),
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
				'id'          => 'monitor_email',
				'name'        => 'monitor_email',
				'title'       => 'Email to notify',
				'description' => 'This email will be used to notify you when the webpage goes down.',
				'type'        => 'text',
				'default'     => get_option( 'admin_email', '' ),
				'placeholder' => 'Fill an email to use',
			),
			array(
				'id'          => 'monitor_url',
				'name'        => 'monitor_url',
				'title'       => 'The URL to monitor',
				'description' => 'This url will be monitored to check when the webpage goes down.',
				'type'        => 'text',
				'default'     => get_home_url(),
				'placeholder' => 'Fill an URL to monitor',
			),
		);
	}
}