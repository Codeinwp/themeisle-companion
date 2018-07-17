<?php

namespace OrbitFox\Gutenberg_Blocks;

/**
 * Class Contact_Form_Server
 *
 */
class Contact_Form_Server extends \WP_Rest_Controller {

	/**
	 * @var Contact_Form_Server
	 */
	public static $instance = null;

	protected $notices = null;

	public $namespace = 'obfx-contact-form/';
	public $version = 'v1';

	public function init() {
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );

		$this->notices = array(
			'success' => esc_html__( 'Your message has been sent!', 'textdomain' ),
			'error'   => esc_html__( 'We failed to send your message!', 'textdomain' ),
		);
	}

	public function register_routes() {
		$namespace = $this->namespace . $this->version;

		register_rest_route( $namespace, '/submit', array(
			array(
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'submit_form' ),
				'permission_callback' => array( $this, 'submit_forms_permissions_check' ),
				'args'                => array(
					'nonce'     => array(
						'type'        => 'string',
						'required'    => true,
						'description' => __( 'The security key', 'textdomain' ),
					),
					'data'      => array(
						'type'        => 'json',
						'required'    => true,
						'description' => __( 'The form must have data', 'textdomain' ),
					),
					'form_id'   => array(
						'type'        => 'string',
						'required'    => true,
						'description' => __( 'The form identifier.', 'textdomain' ),
					),
					'post_id'   => array(
						'type'        => 'string',
						'required'    => true,
						'description' => __( 'The form\'s post.', 'textdomain' ),
					)
				),
			),
		) );
	}

	public function rest_check( \WP_REST_Request $request ) {
			return rest_ensure_response( 'success' );
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
		$post_id = $request->get_param( 'post_id' );

		if ( ! wp_verify_nonce( $nonce, 'obfx-contact-form' ) ) {
			$return['msg'] = 'Invalid nonce';
			return rest_ensure_response( $return );
		}

		$form_builder = $request->get_param( 'form_builder' );
		$data         = $request->get_param( 'data' );

		if ( empty( $data[ $form_id ] ) ) {
			$return['msg'] = esc_html__( 'Invalid Data ', 'textdomain' ) . $form_id;
			return $return;
		}

		$data = $data[ $form_id ];
		// @TODO at this moment there isn't a way to retreat block attributes via PHP without a custom way.

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

		// prepare settings for submit
		$settings = $this->get_widget_settings( $form_id, $post_id, $form_builder );

		if ( ! isset( $settings['to_send_email'] ) || ! is_email( $settings['to_send_email'] ) ) {
			$return['msg'] = esc_html__( 'Wrong email configuration! Please contact administration!', 'textdomain' );

			return $return;
		}

		$result = $this->_send_mail( $settings['to_send_email'], $from, $name, $msg, $data );

		if ( $result ) {
			$return['success'] = true;
			$return['msg']     = $this->notices['success'];
		} else {
			$return['msg'] = esc_html__( 'Ops! I cannot send this email!', 'textdomain' );
		}


		return rest_ensure_response( $return );
	}

	public function submit_forms_permissions_check() {
		return 1;
	}



	/**
	 * Mail sender method
	 *
	 * @param $mailto
	 * @param $mailfrom
	 * @param $body
	 * @param array $extra_data
	 *
	 * @return bool
	 */
	private function _send_mail( $mailto, $mailfrom, $name, $body, $extra_data = array() ) {
		$success = false;

		$subject  = sanitize_text_field( $name );
		$mailto   = sanitize_email( $mailto );
		$mailfrom = sanitize_email( $mailfrom );

		$headers   = array();
		$headers[] = 'From: ' . $subject . ' <' . $mailfrom . '>';
		$headers[] = 'Content-Type: text/html; charset=UTF-8';

		$body = $this->prepare_body( $body, $extra_data );

		ob_start();

		$success = wp_mail( $mailto, $subject, $body, $headers );

		if ( ! $success ) {
			return ob_get_clean();
		}

		return $success;
	}

	/**
	 * Body template preparation
	 *
	 * @param string $body
	 * @param array $data
	 *
	 * @return string
	 */
	private function prepare_body( $body, $data ) {
		$tmpl = "";

		ob_start(); ?>
		<!doctype html>
		<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
			<meta http-equiv="Content-Type" content="text/html;" charset="utf-8"/>
			<!-- view port meta tag -->
			<meta name="viewport" content="width=device-width, initial-scale=1">
			<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
			<title><?php echo esc_html__( 'Mail From: ', 'textdomain' ) . esc_html( $data['name'] ); ?></title>
		</head>
		<body>
		<table>
			<thead>
			<tr>
				<th>
					<h3>
						<?php esc_html_e( 'Contact Form submission from ', 'textdomain' ); ?>
						<a href="<?php echo esc_url( get_site_url() ); ?>"><?php bloginfo( 'name' ); ?></a>
					</h3>
					<hr/>
				</th>
			</tr>
			</thead>
			<tbody>
			<?php
			foreach ( $data as $key => $value ) { ?>
				<tr>
					<td>
						<strong><?php echo esc_html( $key ) ?> : </strong>
						<p><?php echo esc_html( $value ); ?></p>
					</td>
				</tr>
			<?php } ?>
			</tbody>
			<tfoot>
			<tr>
				<td>
					<hr/>
					<?php esc_html_e( 'You received this email because your email address is set in the content form settings on ' ) ?>
					<a href="<?php echo esc_url( get_site_url() ); ?>"><?php bloginfo( 'name' ); ?></a>
				</td>
			</tr>
			</tfoot>
		</table>
		</body>
		</html>
		<?php
		return ob_get_clean();
	}

	/**
	 * @static
	 * @since 1.0.0
	 * @access public
	 * @return Contact_Form_Server
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