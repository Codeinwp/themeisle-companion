<?php

namespace OrbitFox;

class Connector {
	/**
	 * Option key name for Obfx site account.
	 */
	const API_DATA_KEY = 'obfx_connect_data';
	/**
	 * @var Connector
	 */
	protected static $instance = null;
	/**
	 * @var string Root of the obfx dashboard.
	 */
	protected $connect_url = 'http://dashboardobfx.local/api/obfxhq/v1';
	/**
	 * @var string CDN details path.
	 */
	protected $cdn_path = '/image/details';

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
	 * Init hooks.
	 */
	function init() {
		add_action( 'admin_footer', array( $this, 'admin_inline_js' ) );
		add_action( 'rest_api_init', array( $this, 'register_url_endpoints' ) );
	}

	/**
	 * Register REST endpoints.
	 */
	public function register_url_endpoints() {
		register_rest_route( 'obfx-connector', '/connector-url', array(
			array(
				'methods'             => \WP_REST_Server::CREATABLE,
				'permission_callback' => function ( \WP_REST_Request $request ) {
					return current_user_can( 'manage_options' );
				},
				'callback'            => array( $this, 'rest_handle_connector_url' )
			),
		) );
	}

	/**
	 * When a user requests an url we request a set of temporary token credentials and build a link with them.
	 * We also save them because we'll need them with the verifier.
	 *
	 * @return \WP_REST_Response|\WP_Error The connection handshake.
	 */
	public function rest_handle_connector_url( \WP_REST_Request $request ) {

		$disconnect_flag = $request->get_param( 'disconnect' );
		if ( ! empty( $disconnect_flag ) ) {
			delete_option( 'obfx_connect_data' );

			return new \WP_REST_Response( array( 'code' => 200, 'data' => 'Disconnected' ), 200 );
		}
		$api_key = $request->get_param( 'api_key' );
		if ( empty( $api_key ) ) {
			return new \WP_Error( '404', 'Invalid api key' );
		}
		$request = new \OrbitFox\Request( $this->connect_url . $this->cdn_path, 'POST', $api_key );

		$response = $request->get_response();
		if ( $response === false ) {
			return new \WP_Error( '500', 'Error connecting to the OrbitFox api' );
		}
		$response['api_key'] = $api_key;
		update_option( self::API_DATA_KEY, $response );

		return new \WP_REST_Response( $response, 200 );
	}

	/**
	 * Print the inline script which get's the url for the Connector button.
	 */
	function admin_inline_js() {
		$connect_endpoint = get_rest_url( null, 'obfx-connector/connector-url' );
		$confirm_connect  =  add_query_arg(array( 'loggedin' => 'true' ), admin_url( 'admin.php?page=obfx_companion' ) );

		wp_enqueue_script( 'wp-api' ); ?>
		<script type='text/javascript'>
			(function ($) {
				$('#obfx_connect').on('click', function (event) {
					event.preventDefault();

					$('#obfx_connect').parent().addClass('loading');
					var api_key = $('#obfx_connect_api_key').val();

					wp.apiRequest({
						url: "<?php echo $connect_endpoint ?>",
						type: 'POST',
						data: {api_key: api_key},
						dataType: 'json'
					}).done(function (response) {
						if (response.id) {
							location.href = '<?php echo esc_url_raw( $confirm_connect ); ?>';
						}

					}).fail(function (e) {
						$('#obfx_connect').parent().removeClass('loading');
					});
				});

				$('#obfx_disconnect').on('click', function (event) {
					event.preventDefault();
					$('#obfx_connect').parent().addClass('loading');
					wp.apiRequest({
						url: "<?php echo $connect_endpoint ?>",
						type: 'POST',
						data: {disconnect: true},
						dataType: 'json'
					}).done(function (response) {
						location.reload();
					}).fail(function (e) {
						$('#obfx_disconnect').parent().removeClass('loading');
					});
				});
			})(jQuery)
		</script>
		<?php
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