<?php
/**
 * The Orbit Fox Template Directory Module.
 *
 * @link       https://themeisle.com
 * @since      1.0.0
 *
 * @package    Template_Directory_OBFX_Module
 */

/**
 * The class defines a new module to be used by Orbit Fox plugin.
 *
 * @package    Template_Directory_OBFX_Module
 * @author     Themeisle <friends@themeisle.com>
 */
class Template_Directory_OBFX_Module extends Orbit_Fox_Module_Abstract {

	/**
	 * Template_Directory_OBFX_Module constructor.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function __construct() {
		parent::__construct();
		$this->name        = __( 'Template Directory Module', 'themeisle-companion' );
		$this->description = __( 'The awesome template directory is aiming to provide a wide range of templates that you can import straight into your website.', 'themeisle-companion' );

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
		return true;
	}

	/**
	 * Method to define hooks needed.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function hooks() {
		$this->loader->add_action( 'admin_menu', $this, 'add_menu_page' );
	}

	public function add_menu_page() {
		add_management_page(
			__( 'Orbit Fox Template Directory', 'themeisle-companion' ), __( 'Template Directory', 'themeisle-companion' ), 'manage_options', 'obfx_template_dir',
			array( $this, 'render_admin_page' )
		);
	}

	public function render_admin_page() {
		echo $this->render_view( 'template-directory-page' );
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
		$current_screen = get_current_screen();
		if ( ! isset( $current_screen->id ) ) {
			return array();
		}

		if ( $current_screen->id != 'tools_page_obfx_template_dir' ) {
			return array();
		}

		return array(
			'css' => array(
				'admin' => array(),
			),
			'js'  => array(),
		);
	}


	public function options() {
		return array();
	}
}