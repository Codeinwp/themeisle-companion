<?php

/**
 * The Orbit Fox Image CDN Module.
 *
 * @link       https://themeisle.com
 * @since      1.0.0
 *
 * @package    Image_CDN_OBFX_Module
 */

/**
 * The class defines a new module to be used by Orbit Fox plugin.
 *
 * @package    Image_CDN_OBFX_Module
 * @author     Themeisle <friends@themeisle.com>
 */
class Image_CDN_OBFX_Module extends Orbit_Fox_Module_Abstract {
	/**
	 * Dashboard related data.
	 *
	 * @var null|array Dashboard related data.
	 */
	protected $connect_data = null;

	/**
	 * Image_CDN_OBFX_Module constructor.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function __construct() {
		parent::__construct();
		if ( isset( $_GET['loggedin'] ) && $_GET['loggedin'] == 'true' ) {
			$this->show = true;
		}
		$this->name           = __( 'Image CDN Module', 'themeisle-companion' );
		$this->description    = __( 'Let us take care of you images sizes. With this feature we\'ll compress and resize every image on your website.', 'themeisle-companion' );
		$this->active_default = true;
	}

	/**
	 * Determine if module should be loaded.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @return bool
	 */
	public function enable_module() {
		$is_lucky = get_option('obfx_imgcdn_lucky');

		if (
		is_admin()
		//&& current_user_can( 'manage_options' )
		&& isset( $_GET['force-obfx-image-cdn'] ) ) {
			update_option('obfx_imgcdn_lucky', 'yes' );
			return true;
		}

		if ( empty( $is_lucky ) ) {
			$luck = rand ( 1 , 100 );
			if ( $luck < 11 ) {
				update_option('obfx_imgcdn_lucky', 'yes' );
				return true;
			} else {
				update_option('obfx_imgcdn_lucky', 'no' );
			}
		} elseif ( 'yes' === $is_lucky ) {
			return true;
		}

		return false;
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
	 * Method to define hooks needed.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function hooks() {
		/**
		 * Init the connector object and load deps.
		 */
		require_once __DIR__ . '/inc/class-request.php';
		require_once __DIR__ . '/inc/class-orbit-fox-connector.php';
		require_once __DIR__ . '/inc/class-orbit-fox-image-replacer.php';
		$this->loader->add_filter( 'obfx_custom_control_cdn_logged_in_data', $this, 'render_connect_data' );
		\OrbitFox\Connector::instance();

		if ( ! is_admin() ) {
			\OrbitFox\Image_CDN_Replacer::instance();
		}
	}

	/**
	 * Render data from dashboard of orbitfox.
	 */
	public function render_connect_data( $html ) {

		$obfx_user_data = get_option( \OrbitFox\Connector::API_DATA_KEY, false );

		if ( empty( $obfx_user_data ) ) {
			return '';
		}

		$html = '<h5>Logged in as : <b>' . $obfx_user_data['display_name'] . '</b></h5>';
		$html .= '<p>CDN url: <code>' . sprintf( '%s.i.orbitfox.com', $obfx_user_data['image_cdn']['key'] ) . '</code></p>';

		return $html;
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
		return array();
	}

	/**
	 * Options array for the Orbit Fox module.
	 *
	 * @return array
	 */
	public function options() {
		// let's check if this user needs to connect with orbitfox service
		$obfx_user_data     = get_option( \OrbitFox\Connector::API_DATA_KEY );
		$this->connect_data = $obfx_user_data;

		$fields = array(
			array(
				'id'          => 'obfx_connect_api_key',
				'name'        => 'obfx_connect_api_key',
				'type'        => 'password',
				'default'     => isset( $obfx_user_data['api_key'] ) ? $obfx_user_data['api_key'] : '',
				'placeholder' => __( 'Your OrbitFox api key', 'themeisle-companion' ),
				'text'        => '<span class="dashicons dashicons-share"></span>' . __( 'Connect with Orbitfox', 'themeisle-companion' ),
			),
		);
		if ( empty( $obfx_user_data ) ) {

			$fields[] = array(
				'id'         => 'obfx_connect',
				'name'       => 'obfx_connect',
				'type'       => 'link',
				'url'        => '#',
				'link-class' => 'btn btn-success',
				'text'       => '<span class="dashicons dashicons-share"></span>' . __( 'Connect to Orbitfox', 'themeisle-companion' ),
			);
		} else {
			$fields[] = array(
				'type' => 'custom',
				'id'   => 'cdn_logged_in_data',
				'name' => 'cdn_logged_in_data',
			);
			$fields[] = array(
				'id'         => 'obfx_disconnect',
				'name'       => 'obfx_disconnect',
				'type'       => 'link',
				'url'        => '#',
				'link-class' => 'btn btn-danger',
				'text'       => '<span class="dashicons dashicons-share"></span>' . __( 'Log-Out from Orbitfox', 'themeisle-companion' ),
			);

		}

		return $fields;
	}

}
