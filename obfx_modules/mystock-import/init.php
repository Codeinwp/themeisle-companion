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
	const API_KEY		= '97d007cf8f44203a2e578841a2c0f9ac';

	/**
	 * The number of images to fetch. Only the first page will be fetched.
	 */
	const MAX_IMAGES	= 14;

	/**
	 * The username of the flickr account.
	 */
	const USER_NAME		= 'themeisle';


	/**
	 * Mystock_Import_OBFX_Module constructor.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function __construct() {
		parent::__construct();
		$this->name        = __( 'Mystock Import', 'themeisle-companion' );
		$this->description = __( 'Module to import images from mystock.', 'themeisle-companion' );
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
        $this->loader->add_action( 'media_upload_obfx_mystock', $this, 'media_upload_picker' );
        $this->loader->add_action( 'wp_ajax_' . $this->slug, $this, 'display_photo_sizes' );
        $this->loader->add_action( 'wp_ajax_infinite-' . $this->slug, $this, 'infinite_scroll' );
        $this->loader->add_action( 'wp_ajax_handle-request-' . $this->slug, $this, 'handle_request' );
        $this->loader->add_action( 'media_buttons', $this, 'media_buttons', 15 );
        //$this->loader->add_action( 'wp_enqueue_media', $this, 'enqueue_media' );

        $this->loader->add_filter( 'media_upload_tabs', $this, 'upload_tabs' );
        $this->loader->add_filter(' image_send_to_editor', $this, 'image_send_to_editor', 9, 8 );
	}


    function upload_tabs( $tabs ) {
	    $tabs['obfx_mystock']    = __( 'Add from Mystock', 'themeisle-companion' );
	    return $tabs;
    }

    function media_buttons() {
        global $post;
        add_thickbox();
        echo '<button type="button" id="obfx_mystock-picker" class="button insert-media-obfx_mystock add_media" data-editor="content" data-url="' . add_query_arg( array( 'chromeless' => true, "tab" => "obfx_mystock", "TB_iframe" => true), admin_url('media-upload.php')) . '"><span class="wp-media-buttons-icon"></span>' . __("Add Media from Mystock", 'themeisle-companion' ) . '</button>';
        echo '<a href="" id="obfx_mystock_tb" class="thickbox" data-post="' . $post->ID . '" data-active="0"></a>';
    }

    function media_upload_picker() {
        $post_id = filter_input( INPUT_GET, 'post_id', FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 1 ) ) );
        $file_id  = filter_input( INPUT_GET, 'file' );
        $send_to_editor = false;
        
        wp_enqueue_media();

        wp_enqueue_script( 'obfx_mystock', $this->get_url() . '/js/admin.js' );
        wp_localize_script( 'obfx_mystock', 'obfx_mystock', array(
            'send_to_editor' => $send_to_editor,
            'ajaxurl'        => admin_url( 'admin-ajax.php' ),
            'nonce'          => wp_create_nonce( $this->slug . filter_input( INPUT_SERVER, 'REMOTE_ADDR', FILTER_VALIDATE_IP ) ),
            'l10n'           => array(
            ),
            'slug'          => $this->slug,
        ) );

        wp_register_style( 'obfx_mystock', $this->get_url() . '/css/media.css', array(),'1.0.0' );
        wp_enqueue_style( 'obfx_mystock' );

        wp_iframe( array( $this, 'obfx_mystock_iframe' ), $post_id, $file_id );
    }

	/**
	 * Display iframe of photos.
	 *
	 * @param bool $post_id
	 * @param bool $file_id
	 */
    public function obfx_mystock_iframe( $post_id = false, $file_id = false ) {

		media_upload_header();

	    if ( false ===  ( $value = get_transient( 'mystock_photos' ) ) ) {
            $urls = $this->get_images();
		    if( !empty( $urls ) ){
			    $urls['lastpage'] = 1;
		    	set_transient( 'mystock_photos', $urls, 60*60*24*7 );
		    }
	    } else {
		    $urls = get_transient( 'mystock_photos' );
	    }
        require $this->get_dir() . "/inc/photos.php";
    }


	/**
	 *
	 */
    function handle_request(){
	    if( !isset( $_POST['formdata']) ){
		    echo esc_html__( 'Image failed to upload', 'themeisle-companion');
		    wp_die();
	    }

	    $data = array();
	    parse_str($_POST['formdata'], $data);
	    if( empty( $data['imagesizes'] ) ){
		    echo esc_html__( 'Image failed to upload', 'themeisle-companion');
	    	wp_die();
	    }
	    $url = $data['imagesizes'];
	    $name = basename($url);
	    $tmp_file = download_url( $url );
	    if ( is_wp_error( $tmp_file ) ) {
		    echo esc_html__( 'Image failed to upload', 'themeisle-companion');
			wp_die();
	    }
	    $file             = array();
	    $file['name']     = $name;
	    $file['tmp_name'] = $tmp_file;
	    $image_id = media_handle_sideload( $file, 0 );
	    if ( is_wp_error( $image_id ) ) {
		    echo esc_html__( 'Image failed to upload', 'themeisle-companion');
	    	wp_die();
	    }
	    $attach_data = wp_generate_attachment_metadata( $image_id, get_attached_file( $image_id ) );
	    if ( is_wp_error( $attach_data ) ) {
	    	echo esc_html__( 'Image failed to upload', 'themeisle-companion');
		    wp_die();
	    }
	    wp_update_attachment_metadata( $image_id, $attach_data );
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
		if ( ! in_array( $current_screen->id, array( 'post', 'post-new', 'upload' ) ) ) {
			return array();
		}

		return array(
			'js'	=> array(
				'admin'	=> array( 'media-views' ),
			),
			'css'	=> array(
			),
		);
	}

	/**
	 * Request images from flickr.
	 *
	 * @param int $page Page to load.
	 *
	 * @return array
	 */
	private function get_images( $page = 1 ) {
		require_once $this->get_dir() . '/vendor/phpflickr/phpflickr.php';
		$api	= new phpFlickr( self::API_KEY );
		$user	= $api->people_findByUsername( self::USER_NAME );
		$photos	= array();
		if ( $user && isset( $user['nsid'] ) ) {
			$photos	= $api->people_getPublicPhotos($user['nsid'], NULL, 'url_sq, url_t, url_s, url_q, url_m, url_n, url_z, url_c, url_l, url_o', self::MAX_IMAGES, $page );
			if( !empty( $photos ) ){
				return $photos['photos']['photo'];
			}
		}
		return $photos;
	}

	/**
	 * Ajax function to load new images.
	 */
	function infinite_scroll(){
		if( !isset( $_POST['page'] ) ){
			wp_die();
		}

		//Get cached photos
		$cached_photos = get_transient( 'mystock_photos' );

		//Update last page that was loaded
		$req_page = (int)$_POST['page'] + 1;


		//Request new page
		$response = '';
		$new_request = $this->get_images($req_page);
		if( !empty( $new_request ) ){
			foreach ( $new_request as $photo ){
				$response .= '<li class="attachment obfx_mystock_photo" data-pid="'. esc_attr( $photo['id'] ) .'">';
				$response .= '<div class="attachment-preview"><div class="thumbnail"><div class="centered">';
				$response .= '<img src="'. esc_url( $photo['url_m'] ) .'">';
				$response .= '</div></div></div>';
				$response .= '<button type="button" class="check obfx_check" tabindex="0"><span class="media-modal-icon"></span><span class="screen-reader-text">'.esc_html__('Deselect','themeisle-companion') .'</span></button>';
				$response .= '</li>';
			}
		}

		// Update transient
		$new_data = array_merge( $cached_photos, $new_request);
		$new_data['lastpage'] = $req_page;
		if( !empty( $new_data ) ) {
			set_transient( 'mystock_photos', $new_data, 60 * 60 * 24 * 7 );
		}

		echo $response;
		wp_die();
	}

	/**
	 * Ajax function to display image sizes.
	 */
	function display_photo_sizes(){
		$photo_id = $_POST['pid'];
		$data = get_transient('mystock_photos');
		if( empty( $photo_id ) ){
			wp_die();
		}
		$photo = array_filter( $data, function ($e) use ( $photo_id )  { return $e['id'] === $photo_id; }, true );
		$photo = array_pop(array_reverse($photo));
		if( empty( $photo ) ){
			wp_die();
		}
		$photo_sizes = array(
			'url_sq' => __('Square','themeisle-companion'),
			'url_q' => __('Large Square','themeisle-companion'),
			'url_t' => __('Thumbnail','themeisle-companion'),
			'url_s' => __('Small','themeisle-companion'),
			'url_n' => __('Small 320','themeisle-companion'),
			'url_m' => __('Medium','themeisle-companion'),
			'url_z' => __('Medium 640','themeisle-companion'),
			'url_c' => __('Medium 800','themeisle-companion'),
			'url_l' => __('Large','themeisle-companion'),
			'url_o' => __('Original','themeisle-companion'),
		);


		/**
		 * Creating response for selected image
		 */
		$response = '<form id="importmsp" method="post">';
		$response .= '<select name="imagesizes">';
		foreach( $photo_sizes as $key => $label ){
			$response .= '<option value="'. esc_url( $photo[$key] ) .'">'. esc_html( $label ). '</option>';
		}
		$response .= '</select>';
		$response .= '<input type="submit" class="button obfx-import-media" value="Import media"/>';
		$response .= '</form>';
		echo $response;
		wp_die();
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