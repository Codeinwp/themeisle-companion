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
	 * This property will be build on the run
	 * @var null
	 */
	protected $cdn_url = null;

	protected $connect_data = null;

	protected static $image_sizes;

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

		$this->set_cdn_url();
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

		Orbit_Fox_Connector::instance();

		$this->loader->add_filter( 'image_downsize', $this, 'filter_image_downsize', 10, 3 );
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

	/**
	 * This filter will replace all the images retrieved via "wp_get_image" type of functions.
	 *
	 * @param $image
	 * @param $attachment_id
	 * @param $size
	 *
	 * @return array
	 */
	public function filter_image_downsize( $image, $attachment_id, $size ){

		if ( is_admin() )  {
			return $image;
		}

		$image_url = wp_get_attachment_url( $attachment_id );

		if ( $image_url ) {
			//$image_meta = image_get_intermediate_size( $attachment_id, $size );

			$image_meta = wp_get_attachment_metadata( $attachment_id );

			$sizes = array(
				'width' => $image_meta['width'],
				'height' => $image_meta['height'],
			);

			$image_args = self::image_sizes();

			if ( isset( $image_args[$size] ) ) {

				$sizes = array(
					'width' => $image_args[$size]['width'],
					'height' => $image_args[$size]['height'],
				);
			}

			$new_url = $this->get_imgcdn_url( $image_url, array(
				'width' => $sizes['width'],
				'height' => $sizes['height'],
			));

			$return = array(
				$new_url,
				$sizes['width'],
				$sizes['height'],
				false
			);

			return $return;
		}

		// in case something wrong comes, well return the default.
		return $image;
	}

	/**
	 * Returns a signed image url authorized to be used in our CDN.
	 *
	 * @param $url
	 * @param array $args
	 *
	 * @return string
	 */
	public function get_imgcdn_url( $url, $args = array( 'width' => 100, 'height' => 100 ) ){
		$compress_level = 30;

		// this will authorize the image
		$hash = md5( json_encode( array(
			'url' => $url,
			'width' => $args['width'],
			'height' => $args['height'],
			'compress' => $compress_level,
			'secret' => $this->connect_data['imgcdn']['client_secret']
		) ) );

		$new_url = sprintf( '%s/%s/%s/%s/%s/%s',
			$this->cdn_url,
			$hash,
			$args['width'],
			$args['height'],
			$compress_level,
			urlencode($url)
		);

		return $new_url;
	}

	/**
	 * Returns the array of image sizes since `get_intermediate_image_sizes` and image metadata  doesn't include the
	 * custom image sizes in a reliable way.
	 *
	 * Inspired from jetpack/photon.
	 *
	 * @global $wp_additional_image_sizes
	 *
	 * @return array
	 */
	protected static function image_sizes() {
		if ( null == self::$image_sizes ) {
			global $_wp_additional_image_sizes;

			// Populate an array matching the data structure of $_wp_additional_image_sizes so we have a consistent structure for image sizes
			$images = array(
				'thumb'  => array(
					'width'  => intval( get_option( 'thumbnail_size_w' ) ),
					'height' => intval( get_option( 'thumbnail_size_h' ) ),
					'crop'   => (bool) get_option( 'thumbnail_crop' )
				),
				'medium' => array(
					'width'  => intval( get_option( 'medium_size_w' ) ),
					'height' => intval( get_option( 'medium_size_h' ) ),
					'crop'   => false
				),
				'large'  => array(
					'width'  => intval( get_option( 'large_size_w' ) ),
					'height' => intval( get_option( 'large_size_h' ) ),
					'crop'   => false
				),
				'full'   => array(
					'width'  => null,
					'height' => null,
					'crop'   => false
				)
			);

			// Compatibility mapping as found in wp-includes/media.php
			$images['thumbnail'] = $images['thumb'];

			// Update class variable, merging in $_wp_additional_image_sizes if any are set
			if ( is_array( $_wp_additional_image_sizes ) && ! empty( $_wp_additional_image_sizes ) )
				self::$image_sizes = array_merge( $images, $_wp_additional_image_sizes );
			else
				self::$image_sizes = $images;
		}

		return is_array( self::$image_sizes ) ? self::$image_sizes : array();
	}

	/**
	 * Set the cdn url based on the current connected user.
	 */
	protected function set_cdn_url(){
		$this->connect_data = get_option('obfx_connect_data');

		if ( empty( $this->connect_data ) ) {
			return;
		}

		if ( empty( $this->connect_data['imgcdn'] ) ) {
			return;
		}

		$this->cdn_url = sprintf( 'https://%s.%s/%s',
			$this->connect_data['imgcdn']['client_token'],
			'i.orbitfox.com',
			'resize'
		);
	}
}
