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
	protected $connect_data = null;

	// @TODO provide a filter for this
	protected static $extensions = array(
		'jpg',
		'webp',
		'png'
	);
	protected static $image_sizes;

	protected $max_width = 2000;
	protected $max_height = 2000;
	protected $img_real_sizes = null;

	function init() {
		$this->set_cdn_url();

		add_filter( 'image_downsize', array( $this, 'filter_image_downsize' ), 10, 3 );
		add_filter( 'the_content', array( $this, 'filter_the_content' ), 999999 );
		add_filter( 'wp_calculate_image_srcset', array( $this, 'filter_srcset_attr' ), 10, 5 );
		add_filter( 'init', array( $this, 'filter_options_and_mods' ) );
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
		// we don't run optimizations on dashboard side
		if ( is_admin() ) {
			return $image;
		}

		$image_url = wp_get_attachment_url( $attachment_id );

		if ( $image_url ) {
			//$image_meta = image_get_intermediate_size( $attachment_id, $size );
			$image_meta = wp_get_attachment_metadata( $attachment_id );
			$image_args = self::image_sizes();

			// default size
			$sizes = array(
				'width'  => $image_meta['width'],
				'height' => $image_meta['height'],
			);

			// in case there is a custom image size $size will be an array.
			if ( is_array( $size ) ) {
				$sizes = array(
					'width'  => $size[0],
					'height' => $size[1],
				);
			} elseif ( 'full' !== $size && isset( $image_args[ $size ] ) ) { // overwrite if there a size
				$sizes = array(
					'width'  => $image_args[ $size ]['width'],
					'height' => $image_args[ $size ]['height'],
				);
			}

			$new_sizes = $this->validate_image_sizes( $sizes['width'], $sizes['height'] );

			// try to get an optimized image url.
			$new_url = $this->get_imgcdn_url( $image_url, $new_sizes );

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

		if ( empty( $images ) ) {
			return $content; // simple. no images
		}

		$upload_dir  = wp_upload_dir();
		$upload_dir  = $upload_dir['baseurl'];
		$image_sizes = self::image_sizes();

		foreach ( $images[0] as $index => $tag ) {
			$width   = $height = false;
			$new_tag = $tag;
			$src     = $images['img_url'][ $index ];

			if ( apply_filters( 'obfx_imgcdn_disable_optimization_for_link', false, $src ) ) {
				continue;
			}

			if ( false !== strpos( $src, 'i.orbitfox.com' ) ) {
				continue; // we already have this
			}

			// we handle only images uploaded to this site.
			if ( false === strpos( $src, $upload_dir ) ) {
				continue;
			}

			// try to get the declared sizes from the img tag
			if ( preg_match( '#width=["|\']?([\d%]+)["|\']?#i', $images['img_tag'][ $index ], $width_string ) ) {
				$width = $width_string[1];
			}

			if ( preg_match( '#height=["|\']?([\d%]+)["|\']?#i', $images['img_tag'][ $index ], $height_string ) ) {
				$height = $height_string[1];
			}

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

			$new_sizes = $this->validate_image_sizes( $width, $height );
			$new_url = $this->get_imgcdn_url( $src, $new_sizes );

			// replace the url in hrefs or links
			if ( ! empty( $images['link_url'][ $index ] ) ) {
				$new_tag = preg_replace( '#(href=["|\'])' . $images['link_url'][ $index ] . '(["|\'])#i', '\1' . $new_url . '\2', $tag, 1 );
			}

			// replace the new sizes
			$new_tag = str_replace( 'width="' . $width . '"', 'width="' . $new_sizes['width'] . '"', $new_tag );
			$new_tag = str_replace( 'height="' . $height . '"', 'height="' . $new_sizes['height'] . '"', $new_tag );
			// replace the new url
			$new_tag = str_replace( 'src="' . $src . '"', 'src="' . $new_url . '"', $new_tag );

			$content = str_replace( $tag, $new_tag, $content );
		}

		return $content;
	}

	/**
	 * Replace image URLs in the srcset attributes and in case there is a resize in action, also replace the sizes.
	 *
	 * @param array $sources
	 * @param array $size_array
	 * @param array $image_src
	 * @param array $image_meta
	 * @param int $attachment_id
	 *
	 * @return array
	 */
	public function filter_srcset_attr( $sources = array(), $size_array = array(), $image_src = array(), $image_meta = array(), $attachment_id = 0 ) {
		if ( ! is_array( $sources ) ) {
			return $sources;
		}

		foreach ( $sources as $i => $source ) {
			list( $width, $height ) = self::parse_dimensions_from_filename( $source['url'] );

			if ( empty( $width ) ) {
				$width = $image_meta['width'];
			}

			if ( empty( $height ) ) {
				$height = $image_meta['height'];
			}

			$new_sizes = $this->validate_image_sizes( $width, $height );
			$new_url = $this->get_imgcdn_url( $source['url'], $new_sizes );

			$sources[ $i ]['url'] = $new_url;
			if ( $sources[ $i ]['descriptor'] ) {
				$sources[ $i ]['value'] = $new_sizes['width'];
			} else {
				$sources[ $i ]['value'] = $new_sizes['height'];
			}
		}

		return $sources;
	}

	/**
	 * Handles the url replacement in options and theme mods.
	 */
	public function filter_options_and_mods() {
		/**
		 * `obfx_imgcdn_options_with_url` is a filter that allows themes or plugins to select which option
		 * holds an url and needs an optimization.
		 */
		$theme_slug = get_option( 'stylesheet' );

		$options_list = apply_filters( 'obfx_imgcdn_options_with_url', array(
			"theme_mods_$theme_slug",
		) );

		foreach ( $options_list as $option ) {
			add_filter( "option_$option", array( $this, 'replace_option_url' ) );
		}

	}

	/**
	 * A filter which turns a local url into an optimized CDN image url or an array of image urls.
	 *
	 * @param $url string|array
	 *
	 * @return string|array
	 */
	public function replace_option_url( $url ) {
		if ( empty( $url ) ) {
			return $url;
		}

		// $url might be an array or an json encoded array with urls.
		if ( is_array( $url ) || filter_var($url, FILTER_VALIDATE_URL) === false ) {
			$array = $url;
			$encoded = false;

			// it might a json encoded array
			if ( ! is_array( $url ) ) {
				$array = json_decode( $url, true );
				$encoded = true;
			}

			// in case there is an array, apply it recursively.
			if ( is_array( $array ) ) {
				foreach ( $array as $index => $value ) {
					$array[$index] = $this->replace_option_url( $value );
				}

				if ( $encoded ) {
					return json_encode($array);
				} else {
					return $array;
				}
			}

			if ( filter_var($url, FILTER_VALIDATE_URL) === false ) {
				return $url;
			}
		}

		// get the optimized url.
		$new_url = $this->get_imgcdn_url( $url );

		return $new_url;
	}

	/**
	 * Keep the image sizes under a sane limit.
	 *
	 * @param $width
	 * @param $height
	 *
	 * @return array
	 */
	protected function validate_image_sizes( $width, $height ) {
		global $content_width;

		if ( doing_filter( 'the_content' ) && isset( $GLOBALS['content_width'] ) ) {
			$content_width = (int)$GLOBALS['content_width'];
			
			if ( $this->max_width > $content_width ) {
				$this->max_width = $content_width;
			}
		}

		if ( $this->max_width < $width ) {
			$resized = ( $this->max_width / $width ) * 100;
			$width   = $this->max_width;
			$height  = $height * ( ( 100 - $resized ) / 100 );
		}

		if ( $this->max_height < $height ) {
			$resized = ( $this->max_height / $height ) * 100;
			$width   = $width * ( ( 100 - $resized ) / 100 );
		}

		return array(
			'width'  => $width,
			'height' => $height
		);
	}

	/**
	 * Returns a signed image url authorized to be used in our CDN.
	 *
	 * @param $url
	 * @param array $args
	 *
	 * @return string
	 */
	protected function get_imgcdn_url( $url, $args = array( 'width' => 'auto', 'height' => 'auto' ) ) {
		// not used yet.
		$compress_level = 48;
		// this will authorize the image
		$hash = md5( json_encode( array(
			'url'      => $this->urlception_encode( $url ),
			'width'    => (string)$args['width'],
			'height'   => (string)$args['height'],
			'compress' => $compress_level,
			'secret'   => $this->connect_data['imgcdn']['client_secret']
		) ) );

		$new_url = sprintf( '%s/%s/%s/%s/%s/?url=%s',
			$this->cdn_url,
			$hash,
			(string)$args['width'],
			(string)$args['height'],
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
			'i' // api root; almost like /wp-json/
		);
	}

	/**
	 * Try to determine height and width from strings WP appends to resized image filenames.
	 *
	 * @param string $src The image URL.
	 *
	 * @return array An array consisting of width and height.
	 */
	public static function parse_dimensions_from_filename( $src ) {
		$width_height_string = array();

		if ( preg_match( '#-(\d+)x(\d+)\.(?:' . implode( '|', self::$extensions ) . '){1}$#i', $src, $width_height_string ) ) {
			$width  = (int) $width_height_string[1];
			$height = (int) $width_height_string[2];

			if ( $width && $height ) {
				return array( $width, $height );
			}
		}

		return array( false, false );
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