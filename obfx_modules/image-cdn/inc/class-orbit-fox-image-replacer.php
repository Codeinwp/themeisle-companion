<?php

namespace OrbitFox;

class Image_CDN_Replacer {
	/**
	 * @var Image_CDN_Replacer
	 */
	protected static $instance = null;

	/**
	 * This property will be build on the run
	 * @var null
	 */
	protected $cdn_url = null;

	protected static $image_sizes;

	/**
	 * Property used in case there's a resize in action
	 * @var null
	 */
	protected $resize_percent = array( 'w' => null, 'h' => null );
	protected $max_width = 2000;
	protected $max_height = 2000;
	protected $img_real_sizes = null;

	function init() {
		$this->set_cdn_url();

		add_filter( 'image_downsize', array( $this, 'filter_image_downsize' ), 10, 3 );
		add_filter( 'the_content', array( $this, 'filter_the_content' ), 999999 );
		// @TODO We already replace the url  via `image_downsize` but we need a hook to replace new sizes
		//$this->loader->add_filter( 'get_post_galleries', $this, 'filter_the_galleries', 999999 );

		// @TODO also think about the images from widgets.

		// @TODO Create a really generic hook which should allow optimization for images in meta data and options.
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
	public function filter_image_downsize( $image, $attachment_id, $size ) {

		if ( is_admin() ) {
			return $image;
		}

		$image_url = wp_get_attachment_url( $attachment_id );

		if ( $image_url ) {
			//$image_meta = image_get_intermediate_size( $attachment_id, $size );

			$image_meta = wp_get_attachment_metadata( $attachment_id );

			$sizes = array(
				'width'  => $image_meta['width'],
				'height' => $image_meta['height'],
			);

			$image_args = self::image_sizes();

			if ( isset( $image_args[ $size ] ) ) {

				$sizes = array(
					'width'  => $image_args[ $size ]['width'],
					'height' => $image_args[ $size ]['height'],
				);
			}

			$new_url = $this->get_imgcdn_url( $image_url, array(
				'width'  => $sizes['width'],
				'height' => $sizes['height'],
			) );

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
	 * Identify images in post content, and if images are local (uploaded to the current site), pass through Photon.
	 *
	 * @param string $content
	 *
	 * @uses self::validate_image_url, apply_filters, jetpack_photon_url, esc_url
	 * @filter the_content
	 * @return string
	 */
	public function filter_the_content( $content ) {
		$images = self::parse_images_from_html( $content );

//		echo '<pre>';
//		print_r( esc_html( $content ) );
//		echo '</pre>';

		if ( empty( $images ) ) {
			return $content; // simple. no images
		}

		$image_sizes = self::image_sizes();

		foreach ( $images[0] as $index => $tag ) {
			$width   = $height = false;
			$new_tag = $tag;
			$src     = $images['img_url'][ $index ];

			// @TODO add a filter for this value.
			if ( false !== strpos( $src, 'i.orbitfox.com' ) ) {
				continue; // we already have this
			}
			// @TODO we should check if it is a valid url

			// @TODO We should add a filter to allow a possible exclusion of this link

//			var_dump( $images );

			// try to get the declared sizes from the img tag
			if ( preg_match( '#width=["|\']?([\d%]+)["|\']?#i', $images['img_tag'][ $index ], $width_string ) ) {
				$width = $width_string[1];
			}

			if ( preg_match( '#height=["|\']?([\d%]+)["|\']?#i', $images['img_tag'][ $index ], $height_string ) ) {
				$height = $height_string[1];
			}

			// @TODO full size should get some love from validate_* functions.

			// Detect WP registered image size from HTML class
			if ( preg_match( '#class=["|\']?[^"\']*size-([^"\'\s]+)[^"\']*["|\']?#i', $images['img_tag'][ $index ], $size ) ) {
				$size = array_pop( $size );

				if ( false === $width && false === $height && 'full' != $size && array_key_exists( $size, $image_sizes ) ) {
					$width  = (int) $image_sizes[ $size ]['width'];
					$height = (int) $image_sizes[ $size ]['height'];
				}
			} else {
				unset( $size );
			}

			$new_url = $this->get_imgcdn_url( $src, array(
				'width'  => $width,
				'height' => $height,
			) );

			// replace the url in hrefs or links
			if ( ! empty( $images['link_url'][ $index ] ) ) {
				$new_tag = preg_replace( '#(href=["|\'])' . $images['link_url'][ $index ] . '(["|\'])#i', '\1' . $new_url . '\2', $tag, 1 );
			}

			// @TODO replace the url in srcs or srcs sets

			// @TODO if the url is ok, replace the new sizes

			$content = str_replace( $tag, $new_tag, $content );
		}

		return $content;
	}

	/**
	 * @TODO MAke it work
	 *
	 * @param $width
	 *
	 * @return int
	 */
	function validate_width( $width ) {

		if ( empty( $width ) && ! empty( $this->img_real_sizes[0] ) ) {
			$width = $this->img_real_sizes[0];
		}

		if ( $this->max_width >= $width ) {
			return (int)$width;
		}

		//if our image is way bigger we'll try to reduce it at max width. save the percentage
		$this->resize_percent['w'] = ( $this->max_width / $width ) * 100;

		return $this->max_width;

	}

	/**
	 * @TODO make this work
	 *
	 * @param $height
	 *
	 * @return int
	 */
	function validate_height( $height ) {
		$original_height = $height;

		// in case we miss this param and we have a real height.
		if ( empty( $height ) && ! empty( $this->img_real_sizes[1] ) ) {
			$height = $this->img_real_sizes[1];
		}

		// if the width was reduced, we also need to reduce the height by the exact same percentage
		if ( ! empty( $this->resize_percent['w'] ) ) {
			$height = $height * ( ( 100 - $this->resize_percent['w'] ) / 100 );
		}

		if ( $this->max_height >= $height ) {
			return (int)$height;
		}

		//if it's still high let's take it lower and save the percentage.
		$this->resize_percent['h'] = ( $this->max_height / $height ) * 100;

		return $this->max_height;
	}

	/**
	 * Returns a signed image url authorized to be used in our CDN.
	 *
	 * @param $url
	 * @param array $args
	 *
	 * @return string
	 */
	public function get_imgcdn_url( $url, $args = array( 'width' => 100, 'height' => 100 ) ) {
		$compress_level = 35;
		// this will authorize the image
		$hash = md5( json_encode( array(
			'url'      => $this->urlception_encode( $url ),
			'width'    => $args['width'],
			'height'   => $args['height'],
			'compress' => $compress_level,
			'secret'   => $this->connect_data['imgcdn']['client_secret']
		) ) );

		$new_url = sprintf( '%s/%s/%s/%s/%s/?url=%s',
			$this->cdn_url,
			$hash,
			$args['width'],
			$args['height'],
			$compress_level,
			$this->urlception_encode( $url )
		);

		return $new_url;
	}

	/**
	 * Ensures that an url parameter can stand inside an url.
	 *
	 * @param $url
	 *
	 * @return string
	 */
	protected function urlception_encode( $url ) {
		$new_url = rtrim( $url, '/' );

		return urlencode( $new_url );
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
			if ( is_array( $_wp_additional_image_sizes ) && ! empty( $_wp_additional_image_sizes ) ) {
				self::$image_sizes = array_merge( $images, $_wp_additional_image_sizes );
			} else {
				self::$image_sizes = $images;
			}
		}

		return is_array( self::$image_sizes ) ? self::$image_sizes : array();
	}

	/**
	 * Match all images and any relevant <a> tags in a block of HTML.
	 *
	 * @param string $content Some HTML.
	 *
	 * @return array An array of $images matches, where $images[0] is
	 *         an array of full matches, and the link_url, img_tag,
	 *         and img_url keys are arrays of those matches.
	 */
	protected static function parse_images_from_html( $content ) {
		$images = array();

		if ( preg_match_all( '#(?:<a[^>]+?href=["|\'](?P<link_url>[^\s]+?)["|\'][^>]*?>\s*)?(?P<img_tag><img[^>]*?\s+?src=["|\'](?P<img_url>[^\s]+?)["|\'].*?>){1}(?:\s*</a>)?#is', $content, $images ) ) {
			foreach ( $images as $key => $unused ) {
				// Simplify the output as much as possible, mostly for confirming test results.
				if ( is_numeric( $key ) && $key > 0 ) {
					unset( $images[ $key ] );
				}
			}

			return $images;
		}

		return array();
	}

	/**
	 * Set the cdn url based on the current connected user.
	 */
	protected function set_cdn_url() {
		$this->connect_data = get_option( 'obfx_connect_data' );

		if ( empty( $this->connect_data ) ) {
			return;
		}

		if ( empty( $this->connect_data['imgcdn'] ) ) {
			return;
		}

		$this->cdn_url = sprintf( 'https://%s.%s/%s',
			$this->connect_data['imgcdn']['client_token'],
			'i.orbitfox.com',
			$this->connect_data['imgcdn']['client_token']
		);
	}

	/**
	 * @static
	 * @since 1.0.0
	 * @access public
	 * @return Image_CDN_Replacer
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