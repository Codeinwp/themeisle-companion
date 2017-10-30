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
		$this->loader->add_action( 'customize_register', $this, 'remove_customizer' ,9999);
		//Add customizer section.
		$this->loader->add_action( 'customize_register', $this, 'add_customizer_section', 1000 );

	}

	/**
	 * Add customizer section to show templates.
	 *
	 * @param $wp_customize
	 */
	public function add_customizer_section( $wp_customize ) {

		$module_directory = $this->get_dir();
		require_once( $module_directory . '/inc/class-obfx-template-directory-customizer-section.php' );
		if ( class_exists( 'OBFX_Template_Directory_Customizer_Section' ) ) {
			$wp_customize->add_section(
				new OBFX_Template_Directory_Customizer_Section(
					$wp_customize, 'obfx-templates', array(
						'title'            => esc_html__( 'Mue', 'themeisle-companion' ),
						'priority'         => 0,
						'module_directory' => $this->get_dir(),
					)
				)
			);
		}
	}

	/**
	 * Remove the customizer controls.
	 */
	public function remove_customizer() {
		$current = urldecode( isset( $_GET['url'] ) ? $_GET['url'] : '' );
		$flag    = add_query_arg( 'obfx_themes', '', trailingslashit( home_url() ) );
		$current = str_replace( '/', '', $current );
		$flag    = str_replace( '/', '', $flag );
		if ( $flag !== $current ) {
			return;
		}
		global $wp_customize;
		foreach ($wp_customize->sections() as $section){
			if ( $section->id !== 'obfx-templates' ) {
				$wp_customize->remove_section( $section->id );
			}
		}
		foreach ($wp_customize->panels() as $panel){
			$wp_customize->remove_panel( $panel->id );
		}
	}

	/**
	 * Register endpoint for themes page.
	 */
	public function demo_listing_register() {
		add_rewrite_endpoint( 'obfx_themes', EP_ROOT );
	}

	/**
	 * Return template preview in customizer.
	 *
	 * @return bool|string
	 */
	public function demo_listing() {
		$flag = get_query_var( 'obfx_themes', false );

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

		if ( ! $current_screen->id == 'tools_page_obfx_template_dir' && ! $current_screen == 'customize' ) {
			return array();
		}

		return array(
			'css' => array(
				'admin' => array(),
			),
			'js'  => array(
				'customizer' => array('customize-preview')
			),
		);

	}

	/**
	 *
	 *
	 * @return array
	 */
	public function options() {
		return array();
	}
}