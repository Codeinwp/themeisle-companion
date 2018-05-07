<?php
namespace OrbitFox;

class Connector {
	/**
	 * @var Connector
	 */
	protected static $instance = null;

	protected $oauth_details = null;

	protected $connect_url = 'https://connect.orbitfox.com';

	protected $oci = 'eB3pnaMWylkm';

	protected $ocs = '9xK8TGFWala9n3v3JzIzMOc2gHEHgsFxsF5nht0ENEix94EQ';

	function init() {
		$connect_data = get_option( 'obfx_connect_data' );

		add_action( 'admin_init', array( $this, 'catch_token_and_verifier' ), 10 );
		if ( empty( $connect_data ) ) {
		} else {
			add_action( "admin_init", array( $this, 'catch_successful_connection' ), 10 );
		}

		add_action( 'admin_footer', array( $this, 'admin_inline_js' ) );
		add_action( 'rest_api_init', array( $this, 'register_url_endpoints' ) );
	}

	public function register_url_endpoints() {
		register_rest_route( 'obfx-connector', '/connector-url', array(
			array(
				'methods'  => WP_REST_Server::READABLE,
				'callback' => array( $this, 'rest_handle_connector_url' )
			),
		) );
	}

	/**
	 * When a user requests an url we request a set of temporary token credentials and build a link with them.
	 * We also save them because we'll need them with the verifier.
	 *
	 * @return bool
	 */
	public function rest_handle_connector_url() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return false;
		}

		if ( isset( $_GET['disconnect'] ) && $_GET['disconnect'] ) {
			delete_transient( 'obfx_connect_temp_creds' );
			delete_option( 'obfx_connect_data' );
			wp_send_json_success( 'disconnected' );
		}

		$request = new \OrbitFox\OAuth1_Request( $this->connect_url . '/oauth1/request', 'POST', array(), true );

		$response = $request->get_response();

		$args      = array();
		$response = str_replace( '\n', '', $response );
		parse_str( $response, $args );

		if ( isset( $args['oauth_token'] ) && isset( $args['oauth_token_secret'] ) ) {
			// cache temporary request
			set_transient( 'obfx_connect_temp_creds', array(
				'oauth_token'        => $args['oauth_token'],
				'oauth_token_secret' => $args['oauth_token_secret'],
			), 3600 );
			$url = $this->connect_url . '/oauth1/authorize';
			$url = add_query_arg( array( 'oauth_token' => $args['oauth_token'] ), $url );
			$url = add_query_arg( array( 'oauth_token_secret' => $args['oauth_token_secret'] ), $url );
			$url = add_query_arg( array( 'callback_url' => rawurlencode( admin_url( 'admin.php?page=obfx_companion' ) ) ), $url );

			wp_send_json_success( $url );
		}
		wp_send_json_error( $args );
	}

	/**
	 * Print the inline script which get's the url for the Connector button.
	 */
	function admin_inline_js() {
		wp_enqueue_script( 'wp-api' ); ?>
		<script type='text/javascript'>
			(function ($) {
				$('#obfx_connect').on('click', function (event) {
					event.preventDefault();

					$('#obfx_connect').parent().addClass('loading');

					wp.apiRequest({
						url: "<?php echo get_rest_url( null, 'obfx-connector/connector-url' ); ?>",
						type: 'GET',
						dataType: 'json'
					}).done(function (response) {
						if (response.success === true) {
							window.location.href = response.data;
						}
					}).fail(function (e) {
						$('#obfx_connect').parent().removeClass('loading');
					});
				});

				$('#obfx_disconnect').on('click', function (event) {
					event.preventDefault();
					$('#obfx_connect').parent().addClass('loading');
					wp.apiRequest({
						url: "<?php echo get_rest_url( null, 'obfx-connector/connector-url' ); ?>",
						type: 'GET',
						data: {disconnect: true},
						dataType: 'json'
					}).done(function (response) {
						if (response.success === true) {
							window.location.href = "<?php echo admin_url( 'admin.php?page=obfx_companion' ); ?>";
						}
					}).fail(function (e) {
						$('#obfx_disconnect').parent().removeClass('loading');
					});
				});
			})(jQuery)
		</script>
		<?php
	}

	/**
	 * In case there's a oauth verifier returned from the connect service we are ready to require the token credentials
	 * and save the user's data.
	 *
	 * @return null
	 */
	public function catch_token_and_verifier() {
		if ( ! isset( $_GET['oauth_token'] ) || ! isset( $_GET['oauth_verifier'] ) ) {
			return null;
		}

		$temporaryCredentials = get_transient( 'obfx_connect_temp_creds' );

		// meh, skip
		if ( empty( $temporaryCredentials ) ) {
			return null;
		}

		$request = new \OrbitFox\OAuth1_Request( $this->connect_url . '/oauth1/access', 'POST', array(
			'oauth_token'    => $_GET['oauth_token'],
			'oauth_verifier' => $_GET['oauth_verifier'],
		), true );

		$response = $request->get_response();

		$tokens = $data = array();

		parse_str( $response, $tokens );

		// get permanent credentials
		$data['connect'] = $tokens;

		update_option( 'obfx_connect_data', $data );

		// get user data
		$request = new \OrbitFox\OAuth1_Request( $this->connect_url . '/wp-json/wp/v2/users/me', 'GET', array(
			'_envelope' => '1',
			'context'   => 'edit',
		) );

		$user_details = $request->get_response();

		$user_details = json_decode( $user_details, true );
		$data['user'] = array_intersect_key( $user_details['body'], array_flip( array(
			'id',
			'username',
			'email',
			'name'
		) ) );

		if ( ! empty( $data['user'] ) ) {
			// cache the connection data
			update_option( 'obfx_connect_data', $data );
			wp_safe_redirect( admin_url( 'admin.php?page=obfx_companion&action=successful_connection&nonce=' . wp_create_nonce( 'successful_connection' ) ) );
		}
	}

	/**
	 * After a successful connection to our connector, let's try to get credentials for image cdn service.
	 */
	public function catch_successful_connection() {
		if ( empty( $_GET['action'] ) || empty( $_GET['nonce'] ) || $_GET['action'] !== 'successful_connection' ) {
			return;
		}

		$data = get_option( 'obfx_connect_data' );
		// ask credentials for the image cdn service

		$request = new OAuth1_Request( 'https://connect.orbitfox.com/broker/connect/', 'POST', array(
			'server_url' => 'https://i.orbitfox.com/',
			'wp_url'     => site_url(),
			'user_id'    => $data['user']['id'],
		) );

		$imgcdn_creds = $request->get_response();
		$imgcdn_creds = json_decode( $imgcdn_creds, true );

		if ( ! empty( $imgcdn_creds ) ) {
			// filter our keys
			$imgcdn_creds   = array_intersect_key( $imgcdn_creds, array_flip( array(
				'client_token',
				'client_secret',
				'api_root'
			) ) );
			$data['imgcdn'] = $imgcdn_creds;
			update_option( 'obfx_connect_data', $data );
		}
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