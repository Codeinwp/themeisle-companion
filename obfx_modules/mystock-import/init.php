<?php
/**
 * The module for mystock import.
 *
 * @link       https://themeisle.com
 * @since      1.0.0
 *
 * @package    Mystock_Import_OBFX_Module
 */

/**
 * The class for mystock import.
 *
 * @package    Mystock_Import_OBFX_Module
 * @author     Themeisle <friends@themeisle.com>
 * @codeCoverageIgnore
 */
class Mystock_Import_OBFX_Module extends Orbit_Fox_Module_Abstract {

	/**
	 * The api key.
	 */
	const API_KEY = '97d007cf8f44203a2e578841a2c0f9ac';

	/**
	 * Flickr user id.
	 */
	const USER_ID = '136375272@N05';

	/**
	 * The number of images to fetch. Only the first page will be fetched.
	 */
	const MAX_IMAGES = 40;

	/**
	 * The cache time.
	 */
	const CACHE_DAYS = 7;

	/**
	 * Media strings ( used when the gutenberg editor is disabled ).
	 *
	 * @var string
	 */
	private $strings = array();


	/**
	 * Mystock_Import_OBFX_Module constructor.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function __construct() {
		parent::__construct();
		$this->name           = __( 'Mystock Import', 'themeisle-companion' );
		$this->description    = __( 'Module to import images directly from', 'themeisle-companion' ) . sprintf( ' <a href="%s" target="_blank">mystock.photos</a>', 'https://mystock.photos' );
		$this->active_default = true;
	}


	/**
	 * Determine if module should be loaded.
	 *
	 * @return bool
	 * @since   1.0.0
	 * @access  public
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
	public function load() {
	}

	/**
	 * Method to define hooks needed.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function hooks() {
		$this->loader->add_action( 'wp_ajax_handle-request-' . $this->slug, $this, 'handle_request' );
		$this->loader->add_action( 'wp_ajax_get-tab-' . $this->slug, $this, 'get_tab_content' );
		$this->loader->add_action( 'wp_ajax_infinite-' . $this->slug, $this, 'infinite_scroll' );
		$this->loader->add_filter( 'media_view_strings', $this, 'media_view_strings' );
	}

	/**
	 * Display tab content.
	 */
	public function get_tab_content() {
		$urls = $this->get_images();
		$page = 1;
		require $this->get_dir() . '/inc/photos.php';
		wp_die();
	}

	/**
	 * Request images from flickr.
	 *
	 * @param int $page Page to load.
	 *
	 * @return array
	 */
	private function get_images( $page = 1 ) {
		$photos = get_transient( $this->slug . 'photos_' . self::MAX_IMAGES . '_' . $page );
		if ( ! $photos ) {
			require_once $this->get_dir() . '/vendor/phpflickr/phpflickr.php';
			$api    = new phpFlickr( self::API_KEY );
			$photos = $api->people_getPublicPhotos( self::USER_ID, null, 'url_sq, url_t, url_s, url_q, url_m, url_n, url_z, url_c, url_l, url_o', self::MAX_IMAGES, $page );
			if ( ! empty( $photos ) ) {
				$pages = get_transient( $this->slug . 'photos_' . self::MAX_IMAGES . '_pages' );
				if ( false === $pages ) {
					set_transient( $this->slug . 'photos_' . self::MAX_IMAGES . '_pages', $photos['photos']['pages'], self::CACHE_DAYS * DAY_IN_SECONDS );
				}
				$photos = $photos['photos']['photo'];
			}
			set_transient( $this->slug . 'photos_' . self::MAX_IMAGES . '_' . $page, $photos, self::CACHE_DAYS * DAY_IN_SECONDS );
		}

		return $photos;
	}

	/**
	 * Upload image.
	 */
	public function handle_request() {
		$response = array(
			'success'    => false,
			'msg'        => __( 'There was an error getting image details from the request, please try again.', 'themeisle-companion' ),
			'attachment' => '',
		);

		if ( ! current_user_can( 'upload_files' ) ) {
			$response['msg'] = __( 'The current user does not have permission to upload files.', 'themeisle-companion' );
			wp_send_json_error( $response );
		}

		$check_referer = check_ajax_referer( $this->slug . filter_input( INPUT_SERVER, 'REMOTE_ADDR', FILTER_VALIDATE_IP ), 'security', false );
		if ( $check_referer === false ) {
			$response['msg'] = __( 'Invalid nonce.', 'themeisle-companion' );
			wp_send_json_error( $response );
		}

		if ( ! isset( $_POST['url'] ) ) {
			$response['msg'] = __( 'The URL of the image does not exist.', 'themeisle-companion' );
			wp_send_json_error( $response );
		}

		// Send request to `wp_remote_get`
		$url = esc_url_raw( wp_unslash( $_POST['url'] ) );

		require_once ABSPATH . 'wp-admin/includes/image.php';
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/media.php';

		$file_array = array();
		$local_file = download_url( $url );
		if ( is_wp_error( $local_file ) ) {
			$response['msg'] = __( 'Image download failed, please try again.', 'themeisle-companion' );
			wp_send_json_error( $response );
		}

		// Get Headers
		$type = mime_content_type( $local_file );
		if ( ! in_array( $type, array_values( get_allowed_mime_types() ), true ) ) {
			$response['msg'] = __( 'Image type could not be determined', 'themeisle-companion' );
			wp_send_json_error( $response );
		}

		// Upload remote file
		$file_array['name']     = basename( $url );
		$file_array['tmp_name'] = $local_file;

		$image_id = media_handle_sideload( $file_array );

		$response['success']    = true;
		$response['msg']        = __( 'Image successfully uploaded to the media library!', 'themeisle-companion' );
		$response['attachment'] = array(
			'id'  => $image_id,
			'url' => wp_get_attachment_url( $image_id ),
		);

		wp_send_json_success( $response );
	}

	/**
	 * Ajax function to load new images.
	 */
	public function infinite_scroll() {
		check_ajax_referer( $this->slug . filter_input( INPUT_SERVER, 'REMOTE_ADDR', FILTER_VALIDATE_IP ), 'security' );

		if ( ! isset( $_POST['page'] ) ) {
			wp_die();
		}

		// Update last page that was loaded
		$page = (int) $_POST['page'] + 1;

		// Request new page
		$urls = $this->get_images( $page );
		if ( ! empty( $urls ) ) {
			foreach ( $urls as $photo ) {
				include $this->get_dir() . '/inc/photo.php';
			}
		}
		wp_die();
	}

	/**
	 * Method that returns an array of scripts and styles to be loaded
	 * for the front end part.
	 *
	 * @return array
	 * @since   1.0.0
	 * @access  public
	 */
	public function public_enqueue() {
		return array();
	}

	/**
	 * Method that returns an array of scripts and styles to be loaded
	 * for the admin part.
	 *
	 * @return array
	 * @since   1.0.0
	 * @access  public
	 */
	public function admin_enqueue() {
		$current_screen = get_current_screen();
		if ( ! isset( $current_screen->id ) ) {
			return array();
		}
		if ( ! in_array( $current_screen->id, array( 'post', 'page', 'post-new', 'upload' ) ) ) {
			return array();
		}


		if ( method_exists( $current_screen, 'is_block_editor' ) && $current_screen->is_block_editor() ) {

			$this->localized = array(
				'script' => array(
					'ajaxurl'  => admin_url( 'admin-ajax.php' ),
					'nonce'    => wp_create_nonce( $this->slug . filter_input( INPUT_SERVER, 'REMOTE_ADDR', FILTER_VALIDATE_IP ) ),
					'slug'     => $this->slug,
					'api_key'  => self::API_KEY,
					'user_id'  => self::USER_ID,
					'per_page' => 20,
				),
			);

			return array(
				'css' => array(
					'editor-style' => array(),
				),
				'js'  => array(
					'script' => array( 'wp-plugins', 'wp-edit-post', 'wp-element', 'wp-api-fetch', 'wp-blocks' ),
				),
			);
		}

		$this->localized = array(
			'admin' => array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( $this->slug . filter_input( INPUT_SERVER, 'REMOTE_ADDR', FILTER_VALIDATE_IP ) ),
				'l10n'    => array(
					'fetch_image_sizes'     => esc_html__( 'Fetching data', 'themeisle-companion' ),
					'upload_image'          => esc_html__( 'Downloading image. Please wait...', 'themeisle-companion' ),
					'upload_image_complete' => esc_html__( 'Your image was imported. Go to Media Library tab to use it.', 'themeisle-companion' ),
					'load_more'             => esc_html__( 'Loading more photos...', 'themeisle-companion' ),
					'tab_name'              => esc_html__( 'MyStock Library', 'themeisle-companion' ),
					'featured_image_new'    => esc_html__( 'Import & set featured image', 'themeisle-companion' ),
					'insert_image_new'      => esc_html__( 'Import & insert image', 'themeisle-companion' ),
					'featured_image'        => isset( $this->strings['setFeaturedImage'] ) ? $this->strings['setFeaturedImage'] : '',
					'insert_image'          => isset( $this->strings['insertIntoPost'] ) ? $this->strings['insertIntoPost'] : '',
				),
				'slug'    => $this->slug,
				'pages'   => get_transient( $this->slug . 'photos_' . self::MAX_IMAGES . '_pages' ),
			),
		);

		return array(
			'js'  => array(
				'admin' => array( 'media-views' ),
			),
			'css' => array(
				'media' => array(),
			),
		);

	}

	/**
	 * Method to define the options fields for the module
	 *
	 * @return array
	 * @since   1.0.0
	 * @access  public
	 */
	public function options() {
		return array();
	}

	/**
	 * Media view strings
	 * @param array $strings Strings
	 *
	 * @return array
	 */
	public function media_view_strings( $strings ) {
		$this->strings = $strings;

		return $strings;
	}

}
