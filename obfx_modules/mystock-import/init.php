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
	const MAX_IMAGES	= 2;

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
        $this->loader->add_action( 'wp_ajax_' . $this->slug, $this, 'ajax' );
        $this->loader->add_action( 'wp_ajax_infinite-' . $this->slug, $this, 'infinite_scroll' );
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

    public function obfx_mystock_iframe( $post_id = false, $file_id = false ) {
//	    $this->obfx_mystock_handle_request();
		media_upload_header();



	    if ( false ===  ( $value = get_transient( 'mystock_photos' ) ) ) {
	        $url = $this->cache_images();
		    if( !empty( $urls ) ){
			    $urls['lastpage'] = 1;
			    set_transient( 'mystock_photos', $url, 60*60*24*7 );
		    }
	    }



        require $this->get_dir() . "/inc/photos.php";
    }

    private function obfx_mystock_handle_request(){
	    if( !isset( $_POST['imagesizes']) ){
		    return;
	    }
	    $image_url = $_POST['imagesizes'];
	    $filename = basename($image_url);

	    $uploaddir = wp_upload_dir();
	    $uploadfile = $uploaddir['path'] . '/' . $filename;
	    $contents= file_get_contents( $image_url );
	    $savefile = fopen($uploadfile, 'w');
	    fwrite($savefile, $contents);
	    fclose($savefile);


	    $wp_filetype = wp_check_filetype(basename($filename), null );

	    $attachment = array(
		    'post_mime_type' => $wp_filetype['type'],
		    'post_title' => $filename,
		    'post_content' => '',
		    'post_status' => 'inherit'
	    );

	    $attach_id = wp_insert_attachment( $attachment, $uploadfile );

	    $imagenew = get_post( $attach_id );
	    $fullsizepath = get_attached_file( $imagenew->ID );
	    $attach_data = wp_generate_attachment_metadata( $attach_id, $fullsizepath );
	    wp_update_attachment_metadata( $attach_id, $attach_data );
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

	private function cache_images( $page = 1 ) {

		require_once $this->get_dir() . '/vendor/phpflickr/phpflickr.php';
		$api	= new phpFlickr( self::API_KEY );
		$user	= $api->people_findByUsername( self::USER_NAME );
		$urls	= array();
		if ( $user && isset( $user['nsid'] ) ) {
			$photos	= $api->people_getPhotos($user['nsid'], array( 'per_page' => self::MAX_IMAGES, 'page' => $page ) );
			if ( $photos && $photos['photos']['photo'] ) {
				foreach ( $photos['photos']['photo'] as $photo ) {
					$photo_id =  $photo['id'];
					$photo_sizes = $api->photos_getSizes( $photo_id );
					$urls[$photo_id]	= $photo_sizes;
				}
			}
		}
		return $urls;
	}



	function infinite_scroll(){
		if( !isset( $_POST['page'] ) ){
			wp_die();
		}
		$oldphotos = get_transient( 'mystock_photos' );

		$req_page = (int)$_POST['page'] + 1;
		$urls = $this->cache_images($req_page);
		$urls['lastpage'] = $req_page;


		if( is_array($oldphotos) && is_array($urls) ){
			$data = array_merge($oldphotos,$urls);
			if( !empty( $data ) ){
				set_transient( 'mystock_photos', $data, 60*60*24*7 );
			}
		}

		require $this->get_dir() . "/inc/photos.php";
		wp_die();
	}


	function ajax(){

		$photo_id = $_POST['pid'];
		$data = get_transient('mystock_photos');
		if( empty( $photo_id ) || empty( $data[$photo_id] ) ){
			wp_die();
		}
		$photo_sizes = $data[$photo_id];

		/**
		 * Creating response for selected image
		 */
		$response = '<form id="importmsp" method="post">';
		$response .= '<select name="imagesizes">';
		foreach( $photo_sizes as $photo ){
			$label = $photo['label'] . ' ' . $photo['width'] . ' x ' . $photo['height'];
			$value = $photo['source'];
			$response .= '<option value="'. esc_url( $value ) .'">'. esc_html( $label ). '</option>';
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