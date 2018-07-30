<?php

namespace OrbitFox\Gutenberg_Blocks;

/**
 * Class Font_Awesome_Icons_Server
 *
 */
class Font_Awesome_Icons_Server extends \WP_Rest_Controller {

	/**
	 * @var Font_Awesome_Icons_Server
	 */
	public static $instance = null;

	public $namespace = 'obfx-font-awesome-icons/';
	public $version = 'v1';

	public function init() {
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	public function register_routes() {
		$namespace = $this->namespace . $this->version;

		register_rest_route( $namespace, '/get_icons_list', array(
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_icons_list' ),
			),
		) );
	}

	public function get_icons_list( $request ){
		$content = file_get_contents( 'fontawesome-5.2.0/metadata/icons.json', FILE_USE_INCLUDE_PATH );

		$parsed_content =json_decode ($content, true);

		$icons = array();

		foreach( $parsed_content as $icon_key => $icon_args ) {
			$icons[$icon_key] = array(
				'name' => $icon_key,
				'unicode' => $icon_args['unicode'],
			);

			switch ( $icon_args['styles'][0] ) {
				case 'brands': {
					$icons[$icon_key]['prefix'] = 'fab';
					break;
				}
				case 'solid':
				case 'regular':
				default: {
					$icons[$icon_key]['prefix'] = 'fas';
				}
			}
			$icons[$icon_key]['styles'] = $icon_args['styles'];
		}
		return rest_ensure_response( $icons );
	}

	/**
	 * @param \WP_REST_Request $request
	 *
	 * @return mixed|\WP_REST_Response
	 */
	public function submit_form( $request ) {
		$return = array(
			'success' => false,
			'msg'     => esc_html__( 'Something went wrong', 'textdomain' )
		);

		$nonce   = $request->get_param( 'nonce' );
		$form_id = $request->get_param( 'form_id' );
//		$post_id = $request->get_param( 'post_id' );

		if ( ! wp_verify_nonce( $nonce, 'wp_rest' ) ) {
			$return['msg'] = 'Invalid nonce';
			return rest_ensure_response( $return );
		}

		$data         = $request->get_param( 'data' );

		if ( empty( $data[ $form_id ] ) ) {
			$return['msg'] = esc_html__( 'Invalid Data ', 'textdomain' ) . $form_id;
			return $return;
		}

		$storedPost = get_post_meta( $form_id, 'form_data', true );

		if ( empty( $data['email'] ) || ! is_email( $data['email'] ) ) {
			$return['msg'] = esc_html__( 'Invalid email.', 'textdomain' );

			return $return;
		}

		$from = $data['email'];

		if ( empty( $data['name'] ) ) {
			$return['msg'] = esc_html__( 'Missing name.', 'textdomain' );

			return $return;
		}

		$name = $data['name'];

		if ( empty( $data['message'] ) ) {
			$return['msg'] = esc_html__( 'Missing message.', 'textdomain' );

			return $return;
		}

		$msg = $data['message'];

		if ( ! isset( $storedPost['to_send_email'] ) || ! is_email( $storedPost['to_send_email'] ) ) {
			$return['msg'] = esc_html__( 'Wrong email configuration! Please contact administration!', 'textdomain' );

			return $return;
		}

		$result = $this->_send_mail( $storedPost['to_send_email'], $from, $name, $msg, $data );

		if ( $result ) {
			$return['success'] = true;
			$return['msg']     = $this->notices['success'];
		} else {
			$return['msg'] = esc_html__( 'Ops! I cannot send this email!', 'textdomain' );
		}


		return rest_ensure_response( $return );
	}


	/**
	 * @static
	 * @since 1.0.0
	 * @access public
	 * @return Font_Awesome_Icons_Server
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