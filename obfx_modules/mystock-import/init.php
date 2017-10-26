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

        wp_register_style( 'obfx_mystock', $this->get_url() . '/css/media.css' );
        wp_enqueue_style( 'obfx_mystock' );

        wp_iframe( array( $this, 'obfx_mystock_iframe' ), $post_id, $file_id );
    }

    public function obfx_mystock_iframe( $post_id = false, $file_id = false ) {
		list( $urls, $sizes)	= $this->get_images();
		media_upload_header();
        require $this->get_dir() . "/inc/photos.php";
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

	private function get_images() {
		require_once $this->get_dir() . '/vendor/phpflickr/phpflickr.php';
		$api	= new phpFlickr( self::API_KEY );
		$user	= $api->people_findByUsername( self::USER_NAME );
		$urls	= array();
		if ( $user && isset( $user['nsid'] ) ) {
			$photos	= $api->people_getPhotos($user['nsid'], array( 'per_page' => self::MAX_IMAGES ) );
			if ( $photos && $photos['photos']['photo'] ) {
				foreach ( $photos['photos']['photo'] as $photo ) {
					$urls[]	= $api->buildPhotoURL( $photo, 'square_150' );
				}
			}
		}
		return array(
			$urls,
			$api->get_sizes(),
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