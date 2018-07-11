<?php

namespace OrbitFox\Gutenberg_Blocks;

/**
 * Class Plugin_Card_Server
 *
 */
class Plugin_Card_Server extends \WP_Rest_Controller {

	/**
	 * @var Plugin_Card_Server
	 */
	public static $instance = null;

	public $namespace = 'obfx-plugin-card/';
	public $version = 'v1';

	public function init() {
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	public function register_routes() {
		$namespace = $this->namespace . $this->version;

		register_rest_route( $namespace, '/search', array(
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => array( $this, 'search' ),
				'args'                => array(
					'search'      => array(
						'type'        => 'string',
						'required'    => true,
						'description' => __( 'The form must have data', 'textdomain' ),
					)
				),
			),
		) );
	}

	/**
	 * @param \WP_REST_Request $request
	 *
	 * @return mixed|\WP_REST_Response
	 */
	public function search( $request ) {
		$return = array(
			'success' => false,
			'data'     => esc_html__( 'Something went wrong', 'textdomain' )
		);

		$search   = $request->get_param( 'search' );

		require_once( ABSPATH . "wp-admin" . '/includes/plugin-install.php' );

		$request = array(
			'per_page' => 12,
			'search' => $search,
			'fields' => array(
				'short_description' => true,
				'sections' => false,
				'requires' => false,
				'rating' => false,
				'ratings' => false,
				'downloaded' => false,
				'last_updated' => false,
				'added' => false,
				'tags' => false,
				'compatibility' => false,
				'homepage' => true,
				'donate_link' => false,
			)
		);

		$results = plugins_api('query_plugins', $request);

		if ( is_wp_error( $request ) ) {
			$return['data'] = 'error';
			return $return;
		}

		$return['success'] = true;

		// Get data from API
		$return['data'] = $results;

		return rest_ensure_response( $return );
	}

	/**
	 * @static
	 * @since 1.0.0
	 * @access public
	 * @return Plugin_Card_Server
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