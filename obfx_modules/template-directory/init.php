<?php

/**
 * The Orbit Fox Template Directory Module.
 *
 * @link       https://themeisle.com
 * @since      1.0.0
 *
 * @package    Template_Directory_OBFX_Module
 */

use  Elementor\TemplateLibrary\Classes;

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
		$this->name                  = __( 'Template Directory Module', 'themeisle-companion' );
		$this->description           = __( 'The awesome template directory is aiming to provide a wide range of templates that you can import straight into your website.', 'themeisle-companion' );
		$this->active_default        = true;
		$this->refresh_after_enabled = true;
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
		add_action( 'admin_menu', [ $this, 'add_template_directory_submenu' ], 11 );
	}

	/**
	 * Add the Template Directory submenu
	 */
	public function add_template_directory_submenu() {
		add_submenu_page(
			'obfx_companion',
			esc_html__( 'Orbit Fox Template Directory', 'themeisle-companion' ),
			esc_html__( 'Template Directory', 'themeisle-companion' ),
			'manage_options',
			'obfx_template_dir',
			[ $this, 'render_template_directory' ]
		);
	}

	/**
	 * Renders the root element for the Template Directory page
	 */
	public function render_template_directory() {
		echo '<div id="obfx-template-directory"></div>';
	}

	/**
	 * Enqueue the scripts for the dashboard page of the
	 */
	public function enqueue_template_dir_scripts() {
		$current_screen = get_current_screen();
		if ( $current_screen->id == 'orbit-fox_page_obfx_template_dir' ) {
			$script_handle = $this->slug . '-script';
			wp_enqueue_script( 'plugin-install' );
			wp_enqueue_script( 'updates' );
			wp_register_script( $script_handle, plugin_dir_url( $this->get_dir() ) . $this->slug . '/js/script.js', array( 'jquery' ), $this->version, false );
			wp_localize_script(
				$script_handle,
				'importer_endpoint',
				array(
					'url'   => $this->get_endpoint_url( '/import_elementor' ),
					'nonce' => wp_create_nonce( 'wp_rest' ),
				)
			);
			wp_enqueue_script( $script_handle );
		}
	}

	/**
	 * Add the menu page.
	 *
	 * @param $products
	 *
	 * @return array
	 */
	public function add_page( $products ) {
		$sizzify = array(
			'obfx' => array(
				'directory_page_title' => __( 'Orbit Fox Template Directory', 'themeisle-companion' ),
				'parent_page_slug'     => 'obfx_companion',
				'page_slug'            => 'obfx_template_dir',
			),
		);
		return array_merge( $products, $sizzify );
	}

	/**
	 *
	 *
	 * @param string $path
	 *
	 * @return string
	 */
	public function get_endpoint_url( $path = '' ) {
		return rest_url( $this->slug . $path );
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
	 * Returns the link to activate the Templates Patterns Collection plugin.
	 *
	 * @return string
	 */
	public function get_tcp_activation_link() {
		return add_query_arg(
			array(
				'action'        => 'activate',
				'plugin'        => rawurlencode( 'templates-patterns-collection/templates-patterns-collection.php' ),
				'plugin_status' => 'all',
				'paged'         => '1',
				'_wpnonce'      => wp_create_nonce( 'activate-plugin_templates-patterns-collection/templates-patterns-collection.php' ),
			),
			esc_url( network_admin_url( 'plugins.php' ) )
		);
	}

	/**
	 * Returns the link to activate the Neve theme.
	 *
	 * @return string
	 */
	public function get_neve_activation_link() {
		return add_query_arg(
			array(
				'action'     => 'activate',
				'stylesheet' => 'neve',
				'_wpnonce'   => wp_create_nonce( 'switch-theme_neve' ),
			),
			esc_url( network_admin_url( 'themes.php' ) )
		);
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
		if ( ! isset( $current_screen->id ) || $current_screen->id !== 'orbit-fox_page_obfx_template_dir' ) {
			return array();
		}

		$dependencies_file = include OBX_PATH . '/obfx_modules/template-directory/js/template-directory.asset.php';

		$this->localized = array(
			'template-directory' => array(
				'slug'        => $this->slug,
				'assets'      => OBFX_URL . 'obfx_modules/template-directory/assets',
				'neveData'    => [
					'cta'      => $this->get_state( 'neve' ),
					'activate' => $this->get_neve_activation_link(),
				],
				'tpcData'     => [
					'cta'      => $this->get_state( 'tpc' ),
					'activate' => $this->get_tcp_activation_link(),
				],
				'tpcAdminURL' => admin_url( 'themes.php?page=tiob-starter-sites' ),
				'nonce'       => wp_create_nonce( 'wp_rest' ),
				'strings'     => [
					'themeNotInstalled' => __( 'In order to import any starter sites, Neve theme & Templates Cloud plugin need to be installed and activated. Click the button below to install and activate Neve.', 'themeisle-companion' ),
					'themeNotActive'    => __( 'In order to import any starter sites, Neve theme & Templates Cloud plugin need to be installed and activated. Click the button below to activate Neve.', 'themeisle-companion' ),
					'tpcNotInstalled'   => __( 'In order to import any starter sites, Neve theme & Templates Cloud plugin need to be installed and activated. Click the button below to install and activate Templates Cloud.', 'themeisle-companion' ),
					'tpcNotActive'      => __( 'In order to import any starter sites, Neve theme & Templates Cloud plugin need to be installed and activated. Click the button below to activate Templates Cloud.', 'themeisle-companion' ),
				],
			),
		);

		return array(
			'js'  => [
				'template-directory' => array_merge( $dependencies_file['dependencies'], [ 'updates' ] ),
			],
			'css' => [
				'admin' => [ 'wp-components' ],
			],
		);
	}

	/**
	 * Gets the state (the next action) of a plugin or theme. Can return
	 * 'install' if it's not installed, 'activate' if it's installed and not activates,
	 * and 'deactivate' if it's activated.
	 *
	 * @param $slug string The slug of the plugin or theme.
	 *
	 * @return string
	 */
	private function get_state( $slug ) {
		$state = 'install';
		switch ( $slug ) {
			case 'neve':
				if ( $this->check_theme_active( 'Neve' ) ) {
					$state = 'deactivate';
				} elseif ( $this->check_theme_installed( 'neve' ) ) {
					$state = 'activate';
				}
				break;
			case 'tpc':
				if ( $this->check_plugin_active( 'templates-patterns-collection/templates-patterns-collection.php' ) ) {
					$state = 'deactivate';
				} elseif ( $this->check_plugin_installed( 'templates-patterns-collection/templates-patterns-collection.php' ) ) {
					$state = 'activate';
				}
				break;
			default:
				break;
		}

		return $state;
	}

	/**
	 * Return true if the theme is installed
	 *
	 * @param $theme_slug string The theme slug
	 *
	 * @return bool
	 */
	private function check_theme_installed( $theme_slug ) {
		$installed_themes = wp_get_themes();
		return array_key_exists( $theme_slug, $installed_themes );
	}

	/**
	 * Returns true if the theme is active
	 *
	 * @param $theme_name string The theme slug
	 *
	 * @return bool
	 */
	private function check_theme_active( $theme_name ) {
		$theme = wp_get_theme();
		return $theme_name === $theme->name || $theme_name === $theme->parent_theme;
	}

	/**
	 * Check if plugin is installed by getting all plugins from the plugins dir.
	 *
	 * @param $plugin_slug
	 *
	 * @return bool
	 */
	private function check_plugin_installed( $plugin_slug ) {
		$installed_plugins = get_plugins();
		return array_key_exists( $plugin_slug, $installed_plugins ) || in_array( $plugin_slug, $installed_plugins, true );
	}

	/**
	 * Check if plugin is active.
	 *
	 * @param string $plugin_slug
	 *
	 * @return bool
	 */
	private function check_plugin_active( $plugin_slug ) {
		if ( is_plugin_active( $plugin_slug ) ) {
			return true;
		}

		return false;
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
