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

	protected $connect_data = null;

	/**
	 * Image_CDN_OBFX_Module constructor.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function __construct() {
		parent::__construct();
		$this->name           = __( 'Image CDN Module', 'themeisle-companion' );
		$this->description    = __( 'Let us take care of you images sizes. With this feature we\'ll compress and resize every image on your website.', 'themeisle-companion' );
		$this->active_default = false;
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

		\OrbitFox\Connector::instance();

		if ( ! is_admin() ) {
			\OrbitFox\Image_CDN_Replacer::instance();
		}
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
		$obfx_user_data = get_option( 'obfx_connect_data' );

		if ( empty( $obfx_user_data ) ) {

			return array(
				array(
					'id'         => 'obfx_connect',
					'name'       => 'obfx_connect',
					'type'       => 'link',
					'url'        => '#',
					'link-class' => 'btn btn-success',
					'text'       => '<span class="dashicons dashicons-share"></span>' . __( 'Connect with Orbitfox', 'themeisle-companion' ),
				),
			);
		}

		return array(
			array(
				'id'         => 'obfx_disconnect',
				'name'       => 'obfx_disconnect',
				'type'       => 'link',
				'url'        => admin_url('admin.php?page=obfx_companion&action=disconnect_obfx' ),
				'link-class' => 'btn btn-success',
				'text'       => '<span class="dashicons dashicons-share"></span>' . __( 'Disconnect from Orbitfox', 'themeisle-companion' ),
			),
		);
	}

}
