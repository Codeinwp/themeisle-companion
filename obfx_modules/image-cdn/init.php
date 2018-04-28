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

	protected $base_cdn_url = 'https://i.orbitfox.com/';
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
		// this module is dependent on the connect data.
		if ( empty ( get_option( 'obfx_connect_data') ) ) {
			return false;
		}
		return true;
	}

	/**
	 * The loading logic for the module.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function load() {
		// this module is dependent on the connect data.
		if ( empty ( get_option( 'obfx_connect_data') ) ) {
			return false;
		}
		return true;
	}

	/**
	 * Method to define hooks needed.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function hooks() {
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
		return array();
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

	public function get_imgcdn_url( $url, $args = array( 'width' => 100, 'height' => 100 ) ){
		$compress_level = 30;

		$new_url = sprintf( '%s?url=%s&w=%s&h=%s&q=%s',
			$this->base_cdn_url,
			urlencode($url),
			$args['width'],
			$args['height'],
			$compress_level
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
}
