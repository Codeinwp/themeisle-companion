<?php

class Orbit_Fox_Connector {
	/**
	 * @var Connector
	 */
	protected static $instance = null;

	protected $oauth_details = null;

	protected $connect_url = 'https://connect.orbitfox.com';

	protected $oci = 'eB3pnaMWylkm';
	protected $ocs = '9xK8TGFWala9n3v3JzIzMOc2gHEHgsFxsF5nht0ENEix94EQ';

	function init(){
		$connect_data = get_option( 'obfx_connect_data' );

		$this->oauth_details = array(
			'oauth_consumer_key'     => $this->oci,
			'oauth_nonce'            => time(),
			'oauth_signature_method' => 'HMAC-SHA1',
			'oauth_timestamp'        => time()
		);

		if ( empty( $connect_data ) ) {
			add_action( 'admin_init', array( $this, 'catch_token_and_verifier' ), 10);
		} else {
			add_action( "admin_init", array( $this, 'catch_successful_connection'), 10 );
		}

		add_action( 'admin_footer', array( $this, 'admin_inline_js') );
		add_action( 'rest_api_init', array( $this, 'register_url_endpoints') );
	}

	public function register_url_endpoints(){
		register_rest_route( 'obfx-connector', '/connector-url', array(
			array(
				'methods'  => WP_REST_Server::READABLE,
				'callback' => array( $this, 'rest_handle_connector_url' )
			),
		) );
	}

	public function rest_handle_connector_url(){
		if ( ! current_user_can( 'manage_options' ) ) {
			return false;
		}

		if ( isset( $_GET['disconnect'] ) && $_GET['disconnect'] ) {
			delete_transient( 'obfx_connect_temp_creds' );
			delete_option( 'obfx_connect_data' );
			wp_send_json_success('disconnected');
		}

		$data = get_option( 'obfx_connect_data' );

		$response = $this->post_request( $this->connect_url . '/oauth1/request' );

		// in this case $response should hold a oauth_token and an oauth_verifier.
		// they are both enough to build an url for the connector button.

		wp_send_json( array(
			'data'=> $data,
			'res' => $response
		));
	}

	/**
	 * Print the inline script which get's the url for the Connector button.
	 */
	function admin_inline_js(){
		wp_enqueue_script('wp-api'); ?>
		<script type='text/javascript'>
			(function($){
				$( '#obfx_connect' ).on('click', function ( event ) {
					event.preventDefault();
					var self = this;
					wp.apiRequest({
						url: "<?php echo get_rest_url( null, 'obfx-connector/connector-url'); ?>",
						type: 'GET',
						dataType: 'json'
					}).done(function(response){
						console.log( response );
						// in case of success replace  self href attr
					}).fail(function(e){
						console.log(e);
					});
				});

				$( '#obfx_disconnect' ).on('click', function ( event ) {
					event.preventDefault();
					wp.apiRequest({
						url: "<?php echo get_rest_url( null, 'obfx-connector/connector-url'); ?>",
						type: 'GET',
						data:{
							disconnect: true
						},
						dataType: 'json'
					}).done(function(response){
						console.log( response );
						if ( response.success === true ) {
							console.log('disconnected');
						}
					}).fail(function(e){
						console.log(e);
					});
				});
			})(jQuery)
		</script>
		<?php
	}

	/**
	 * @TODO remove
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

		update_option( 'obfx_connect_data', $data );

		wp_safe_redirect( admin_url( 'admin.php?page=obfx_companion&action=successful_connection&nonce=' . wp_create_nonce( 'successful_connection' ) ) );
	}

	/**
	 * After a successful connection to our connector, let's try to get credentials for image cdn service.
	 * @throws Exception
	 * @throws \League\OAuth1\Client\Credentials\CredentialsException
	 */
	public function catch_successful_connection(){
		if ( empty( $_GET['action'] ) || empty( $_GET['nonce'] ) || $_GET['action'] !== 'successful_connection' ) {
			return;
		}

		$data = get_option( 'obfx_connect_data' );
		// ask credentials for the image cdn service

		//$imgcdn_creds = $this->server->requestCredentialsForService( 'https://i.orbitfox.com/', $data['user']['id'] );

		if ( ! empty( $imgcdn_creds ) ) {
			$data['imgcdn'] = $imgcdn_creds;
			update_option( 'obfx_connect_data', $data );
		}
	}

	/**
	 * Get the authorization urls from server. Usually these can be requested via a discovery but we can save some
	 * bandwith by caching them.
	 *
	 * @return stdClass
	 */
	protected function get_auth_urls( $type = null ) {

		if ( $type !== null ) {
			return $this->connect_url . '/oauth1/' . $type;
		}

		$object = new stdClass();

		$object->request = $this->connect_url . '/oauth1/request';
		$object->authorize = $this->connect_url . '/oauth1/authorize';
		$object->access = $this->connect_url . '/oauth1/access';

		return $object;
	}

	/**
	 * Create a signature base string from list of arguments
	 *
	 * @param string $request_url request url or endpoint
	 * @param string $method HTTP verb
	 * @param array $oauth_params Twitter's OAuth parameters
	 *
	 * @return string
	 */
	private function _build_signature_base_string( $request_url, $method, $oauth_params ) {
		// save the parameters as key value pair bounded together with '&'
		$string_params = array();
		ksort( $oauth_params );
		foreach ( $oauth_params as $key => $value ) {
			// convert oauth parameters to key-value pair
			$string_params[] = "$key=$value";
		}
		return "$method&" . rawurlencode( $request_url ) . '&' . rawurlencode( implode( '&', $string_params ) );
	}

	/**
	 * Create the signature.
	 *
	 * @param $data
	 *
	 * @return string
	 */
	private function _generate_oauth_signature( $data ) {
		// encode consumer and token secret keys and subsequently combine them using & to a query component
		$hash_hmac_key = rawurlencode( $this->oci ) . '&' . rawurlencode( $this->ocs );
		$oauth_signature = base64_encode( hash_hmac( 'sha1', $data, $hash_hmac_key, true ) );
		return $oauth_signature;
	}

	/**
	 * Generate the authorization HTTP header
	 * @return string
	 */
	public function authorization_header() {
		$header = 'OAuth ';
		$oauth_params = array();
		foreach ( $this->oauth_details as $key => $value ) {
			$oauth_params[] = "$key=\"" . rawurlencode( $value ) . '"';
		}
		$header .= implode( ', ', $oauth_params );
		return $header;
	}

	/**
	 * Process and return the JSON result.
	 *
	 * @return string
	 */
	public function post_request( $url ) {
		// @TODO let's see why this signature isn't working.
		$this->oauth_details['oauth_callback'] = rawurlencode(admin_url('admin.php?page=obfx_companion'));
		$signature = $this->_build_signature_base_string( $url, 'POST', $this->oauth_details );
		$this->oauth_details['oauth_signature'] = $this->_generate_oauth_signature( $signature );

		$header = $this->authorization_header();

		$args = array(
			'headers'   => array( 'Authorization' => $header ),
			'timeout'   => 45,
			'sslverify' => false
		);

		$response = wp_remote_post( $url, $args );

		if ( wp_remote_retrieve_response_code( $response ) === 200 ) {
			return wp_remote_retrieve_body( $response );
		}
		// otherwhize debug this
		return $response;
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