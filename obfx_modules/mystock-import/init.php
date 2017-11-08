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
	 * The number of images to fetch. Only the first page will be fetched.
	 */
	const MAX_IMAGES = 40;

	/**
	 * The username of the flickr account.
	 */
	const USER_NAME = 'themeisle';

	/**
	 * The cache time.
	 */
	const CACHE_DAYS = 7;


	/**
	 * Mystock_Import_OBFX_Module constructor.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function __construct() {
		parent::__construct();
		$this->name        = __( 'Mystock Import', 'themeisle-companion' );
		$this->description = __( 'Module to import images directly from', 'themeisle-companion' ) . sprintf( ' <a href="%s" target="_blank">mystock.photos</a>', 'https://mystock.photos' );
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
	public function load() {
	}

	/**
	 * Method to define hooks needed.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function hooks() {

		/*Get tab content*/
		$this->loader->add_action( 'wp_ajax_get-tab-' . $this->slug, $this, 'get_tab_content' );
		$this->loader->add_action( 'wp_ajax_' . $this->slug, $this, 'display_photo_sizes' );
		$this->loader->add_action( 'wp_ajax_infinite-' . $this->slug, $this, 'infinite_scroll' );
		$this->loader->add_action( 'wp_ajax_handle-request-' . $this->slug, $this, 'handle_request' );
	}

	/**
	 * Display tab content.
	 */
	public function get_tab_content() {
		$urls = $this->get_images();
		require $this->get_dir() . "/inc/photos.php";
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
		$photos	= get_transient( $this->slug . 'photos_' . self::MAX_IMAGES . '_' . $page );
		if ( ! $photos ) {
			require_once $this->get_dir() . '/vendor/phpflickr/phpflickr.php';
			$api    = new phpFlickr( self::API_KEY );
			$user   = $api->people_findByUsername( self::USER_NAME );
			$photos = array();
			if ( $user && isset( $user['nsid'] ) ) {
				$photos = $api->people_getPublicPhotos( $user['nsid'], null, 'url_sq, url_t, url_s, url_q, url_m, url_n, url_z, url_c, url_l, url_o', self::MAX_IMAGES, $page );
				if ( ! empty( $photos ) ) {
					$photos	= $photos['photos']['photo'];
				}
			}
			set_transient( $this->slug . 'photos_' . self::MAX_IMAGES . '_' . $page, $photos, self::CACHE_DAYS * DAY_IN_SECONDS );
		}

		return $photos;
	}

	/**
	 * Upload image.
	 */
	function handle_request() {
		check_ajax_referer( $this->slug . filter_input( INPUT_SERVER, 'REMOTE_ADDR', FILTER_VALIDATE_IP ), 'security' );

		if ( ! isset( $_POST['formdata'] ) ) {
			echo esc_html__( 'Image failed to upload', 'themeisle-companion' );
			wp_die();
		}

		$data = array();
		parse_str( $_POST['formdata'], $data );
		if ( empty( $data['imagesizes'] ) ) {
			echo esc_html__( 'Image failed to upload', 'themeisle-companion' );
			wp_die();
		}
		$url      = $data['imagesizes'];
		$name     = basename( $url );
		$tmp_file = download_url( $url );
		if ( is_wp_error( $tmp_file ) ) {
			echo esc_html__( 'Image failed to upload', 'themeisle-companion' );
			wp_die();
		}
		$file             = array();
		$file['name']     = $name;
		$file['tmp_name'] = $tmp_file;
		$image_id         = media_handle_sideload( $file, 0 );
		if ( is_wp_error( $image_id ) ) {
			echo esc_html__( 'Image failed to upload', 'themeisle-companion' );
			wp_die();
		}
		$attach_data = wp_generate_attachment_metadata( $image_id, get_attached_file( $image_id ) );
		if ( is_wp_error( $attach_data ) ) {
			echo esc_html__( 'Image failed to upload', 'themeisle-companion' );
			wp_die();
		}
		wp_update_attachment_metadata( $image_id, $attach_data );
	}

	/**
	 * Ajax function to load new images.
	 */
	function infinite_scroll() {
		check_ajax_referer( $this->slug . filter_input( INPUT_SERVER, 'REMOTE_ADDR', FILTER_VALIDATE_IP ), 'security' );

		if ( ! isset( $_POST['page'] ) ) {
			wp_die();
		}

		//Update last page that was loaded
		$req_page = (int) $_POST['page'] + 1;

		//Request new page
		$response    = '';
		$new_request = $this->get_images( $req_page );
		if ( ! empty( $new_request ) ) {
			foreach ( $new_request as $photo ) {
				$response .= '<li class="obfx-image" data-page="' . esc_attr( $req_page ) . '" data-pid="' . esc_attr( $photo['id'] ) . '">';
				$response .= '<div class="obfx-preview"><div class="thumbnail"><div class="centered">';
				$response .= '<img src="' . esc_url( $photo['url_m'] ) . '">';
				$response .= '</div></div></div>';
				$response .= '<button type="button" class="check obfx-image-check" tabindex="0"><span class="media-modal-icon"></span><span class="screen-reader-text">' . esc_html__( 'Deselect', 'themeisle-companion' ) . '</span></button>';
				$response .= '</li>';
			}
		}

		echo $response;
		wp_die();
	}

	/**
	 * Ajax function to display image sizes.
	 */
	public function display_photo_sizes() {

		check_ajax_referer( $this->slug . filter_input( INPUT_SERVER, 'REMOTE_ADDR', FILTER_VALIDATE_IP ), 'security' );

		$photo_id = $_POST['pid'];
		$page	  = $_POST['page'];
		if ( empty( $photo_id ) || empty( $page ) ) {
			wp_die();
		}
		$data     = $this->get_images( $page );
		$photo = array_filter( $data, function ( $e ) use ( $photo_id ) {
			return $e['id'] === $photo_id;
		}, true );
		$photo = array_pop( array_reverse( $photo ) );
		if ( empty( $photo ) ) {
			wp_die();
		}
		$photo_sizes = array(
			'url_sq' => __( 'Square', 'themeisle-companion' ),
			'url_q'  => __( 'Large Square', 'themeisle-companion' ),
			'url_t'  => __( 'Thumbnail', 'themeisle-companion' ),
			'url_s'  => __( 'Small', 'themeisle-companion' ),
			'url_n'  => __( 'Small 320', 'themeisle-companion' ),
			'url_m'  => __( 'Medium', 'themeisle-companion' ),
			'url_z'  => __( 'Medium 640', 'themeisle-companion' ),
			'url_c'  => __( 'Medium 800', 'themeisle-companion' ),
			'url_l'  => __( 'Large', 'themeisle-companion' ),
			'url_o'  => __( 'Original', 'themeisle-companion' ),
		);

		/**
		 * Creating response for selected image
		 */
		$response = '<div class="attachment-details">';
		$response .= '<form id="importmsp" method="post">';
		$response .= '<h2>' . esc_html__( 'Attachement display settings', 'themeisle-companion' ) . '</h2><hr/>';
		$response .= '<label class="attachement-settings">';
		$response .= '<span class="name">' . esc_html__( 'Size', 'themeisle-companion' ) . '</span>';
		$response .= '<select name="imagesizes">';
		foreach ( $photo_sizes as $key => $label ) {
			$response .= '<option value="' . esc_url( $photo[ $key ] ) . '">' . esc_html( $label ) . '</option>';
		}
		$response .= '</select>';
		$response .= '</label>';

		$response .= '<input type="submit" class="button obfx-import-media" value="Import media"/>';
		$response .= '</form>';
		$response .= '</div>';
		echo $response;
		wp_die();
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
	 * @return array
	 */
	public function admin_enqueue() {
		$current_screen = get_current_screen();

		if ( ! isset( $current_screen->id ) ) {
			return array();
		}
		if ( ! in_array( $current_screen->id, array( 'post', 'page', 'post-new', 'upload' ) ) ) {
			return array();
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
				),
				'slug'    => $this->slug,
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
	 * @since   1.0.0
	 * @access  public
	 * @return array
	 */
	public function options() {
		return array();
	}
}