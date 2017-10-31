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
		//Add dashboard menu page.
		$this->loader->add_action( 'admin_menu', $this, 'add_menu_page' );
		//Add rewrite endpoint.
		$this->loader->add_action( 'init', $this, 'demo_listing_register' );
		//Add template redirect.
		$this->loader->add_action( 'template_redirect', $this, 'demo_listing' );
		//Remove customizer controls.
		$this->loader->add_action( 'customize_register', $this, 'adjust_customizer' ,1000 );
	}


	/**
	 * Remove the customizer controls and add the template listing control.
	 */
	public function adjust_customizer( $wp_customize ) {
		//Check the URL parameter and bail if not on 'obfx_templates'.
		$current = urldecode( isset( $_GET['url'] ) ? $_GET['url'] : '' );
		$flag    = add_query_arg( 'obfx_templates', '', trailingslashit( home_url() ) );
		$current = str_replace( '/', '', $current );
		$flag    = str_replace( '/', '', $flag );
		if ( $flag !== $current ) {
			return;
		}

		//Remove all customizer sections and panels except 'obfx-templates'.
		foreach ($wp_customize->sections() as $section){
			if ( $section->id !== 'obfx-templates' ) {
				$wp_customize->remove_section( $section->id );
			}
		}
		foreach ($wp_customize->panels() as $panel){
			$wp_customize->remove_panel( $panel->id );
		}

		//Get the module directory to later pass it for scripts enqueueing in the Orbit Fox customizer section.
		$module_directory = $this->get_dir();

		//Include the customizer section custom class and add the section.
		require_once( $module_directory . '/inc/class-obfx-template-directory-customizer-section.php' );
		if ( class_exists( 'OBFX_Template_Directory_Customizer_Section' ) ) {
			$wp_customize->add_section(
				new OBFX_Template_Directory_Customizer_Section(
					$wp_customize, 'obfx-templates', array(
						'priority'         => 0,
						'module_directory' => $this->get_dir(),
					)
				)
			);
		}

	}

	/**
	 * Register endpoint for themes page.
	 */
	public function demo_listing_register() {
		add_rewrite_endpoint( 'obfx_templates', EP_ROOT );
	}

	/**
	 * Return template preview in customizer.
	 *
	 * @return bool|string
	 */
	public function demo_listing() {
		$flag = get_query_var( 'obfx_templates', false );

		if ( $flag !== '' ) {
			return false;
		}
		if ( ! current_user_can( 'customize' ) ) {
			return false;
		}
		if ( ! is_customize_preview() ) {
			return false;
		}

		return $this->render_view( 'template-directory-render-template' );
	}

	/**
	 * Add the 'Template Directory' page to the dashboard menu.
	 */
	public function add_menu_page() {
		add_management_page(
			__( 'Orbit Fox Template Directory', 'themeisle-companion' ), __( 'Template Directory', 'themeisle-companion' ), 'manage_options', 'obfx_template_dir',
			array( $this, 'render_admin_page' )
		);
	}

	/**
	 * Render the template directory admin page.
	 */
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

		if ( ! ( $current_screen->id == 'tools_page_obfx_template_dir' ) && ( ! $current_screen == 'customize' ) ) {
			return array();
		}

		return array(
			'css' => array(
				'admin' => array(),
			),
			'js'  => array(
				'customizer' => array('customize-preview'),
			),
		);

	}

	/**
	 * Options array for the Orbit Fox module.
	 *
	 * @return array
	 */
	public function options() {
		return array();
	}
}