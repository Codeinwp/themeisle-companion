<?php

require_once __DIR__ . '/helpers/class-orbit-fox-wordpress-oauth1-client.php';

class Orbit_Fox_Connector {
	/**
	 * @var Connector
	 */
	protected static $instance = null;
	/**
	 * @var Orbitfox\Connector\OauthClient
	 */
	public $server = null;

	protected $connect_url = 'https://connect.orbitfox.com';
	protected $oci = 'eB3pnaMWylkm';
	protected $ocs = '9xK8TGFWala9n3v3JzIzMOc2gHEHgsFxsF5nht0ENEix94EQ';

	function init(){
		$connect_data = get_option( 'obfx_connect_data' );
//		delete_transient( 'obfx_connect_temp_creds' );
//		delete_option( 'obfx_connect_data' );
		if ( empty( $connect_data ) ) {
			add_action( 'admin_notices', array( $this, 'catch_token_and_verifier' ), 10);
		} else {
			add_action( "admin_notices", array( $this, 'catch_disconnect_params'), 9 );
		}

		add_filter( "obfx_connector_url", array( $this, 'get_connector_url') );

		$this->init_server();
	}

	/**
	 * Make an instance of our oauth client.
	 *
	 * @return \Orbitfox\Connector\OAuthClient
	 */
	protected function init_server() {
		if ( ! empty( $this->server ) ) {
			return $this->server;
		}

		date_default_timezone_set('UTC');

		$this->server = new Orbitfox\Connector\OAuthClient( array(
			'identifier'   => $this->oci,
			'secret'       => $this->ocs,
			'api_root'     => $this->connect_url,
			'auth_urls'    => $this->get_auth_urls(),
			'callback_uri' => admin_url('admin.php?page=obfx_companion')
		));

		return $this->server;
	}

	/**
	 * Returns the url for the Connect button but not before trying to get temporary credentials for our connect service.
	 *
	 * @return string
	 */
	public function get_connector_url(){
		$temporaryCredentials = get_transient( 'obfx_connect_temp_creds' );

		if ( empty( $temporaryCredentials ) ) {
			$temporaryCredentials = $this->server->getTemporaryCredentials();
			set_transient( 'obfx_connect_temp_creds', $temporaryCredentials, 24 * HOUR_IN_SECONDS );
		}

		$url = $this->server->getAuthorizationUrl($temporaryCredentials);
		$url = $url . '&callback=' . urlencode( admin_url('admin.php?page=obfx_companion') );

		return $url;
	}

	/**
	 * In case there's a oauth verifier returned from the connect service we are ready to require the token credentials
	 * and save the user's data.
	 */
	public function catch_token_and_verifier(){
		if ( ! isset( $_GET['oauth_token'] ) || ! isset( $_GET['oauth_verifier'] ) ) {
			return null;
		}

		$temporaryCredentials = get_transient( 'obfx_connect_temp_creds' );
		// meh, skip
		if ( empty( $temporaryCredentials ) ) {
			return null;
		}

		// get permanent credentials
		$data['connect'] = $this->server->getTokenCredentials($temporaryCredentials, $_GET['oauth_token'], $_GET['oauth_verifier']);
		// get user data
		$data['user'] = $this->server->get_user_data( $data['connect'] );
		// ask credentials for the image cdn service
		//$data['imgcdn'] = $this->server->requestCredentialsForService( 'https://i.orbitfox.com/' );
		update_option( 'obfx_connect_data', $data );

		wp_redirect( admin_url( 'admin.php?page=obfx_companion') );
	}

	/**
	 * Try to catch a disconnect action.
	 * When there is one, remove all the connect data and redirect the user back to the dashboard.
	 */
	public function catch_disconnect_params() {
		if ( empty( $_GET['action'] ) || empty( $_GET['nonce'] ) || $_GET['action'] !== 'disconnect_obfx' ) {
			return;
		}

		if ( wp_verify_nonce( $_GET['nonce'], 'disconnect_obfx' ) ) {
			delete_transient( 'obfx_connect_temp_creds' );
			delete_option( 'obfx_connect_data' );
			wp_redirect( admin_url('admin.php?page=obfx_companion') );
		}
	}

	/**
	 * Returns the current url.
	 * But better use admin_url
	 *
	 * @return mixed|string
	 */
	protected function get_local_url() {
		$scheme = ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] ) ? 'https' : 'http';
		$here = $scheme . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		if ( ! empty( $_SERVER['QUERY_STRING'] ) ) {
			// Strip the query string
			$here = str_replace( '?' . $_SERVER['QUERY_STRING'], '', $here );
		}

		return $here;
	}

	/**
	 * Get the authorization urls from server. Usually these can be requested via a discovery but we can save some
	 * bandwith by caching them.
	 *
	 * @return stdClass
	 */
	protected function get_auth_urls() {
		$object = new stdClass();

		$object->request = $this->connect_url . '/oauth1/request';
		$object->authorize = $this->connect_url . '/oauth1/authorize';
		$object->access = $this->connect_url . '/oauth1/access';

		return $object;
	}

	/**
	 * @static
	 * @since 1.0.0
	 * @access public
	 * @return Connector
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
			self::$instance->init();
		}

		return self::$instance;
	}

	/**
	 * Throw error on object clone
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object therefore, we don't want the object to be cloned.
	 *
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'textdomain' ), '1.0.0' );
	}

	/**
	 * Disable unserializing of the class
	 *
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'textdomain' ), '1.0.0' );
	}
}