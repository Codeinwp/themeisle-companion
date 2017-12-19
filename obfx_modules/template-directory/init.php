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
		$this->name           = __( 'Template Directory Module', 'themeisle-companion' );
		$this->description    = __( 'The awesome template directory is aiming to provide a wide range of templates that you can import straight into your website.', 'themeisle-companion' );
		$this->active_default = true;
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
		$this->loader->add_action( 'rest_api_init', $this, 'register_endpoints' );
		//Add dashboard menu page.
		$this->loader->add_action( 'admin_menu', $this, 'add_menu_page', 100 );
		//Add rewrite endpoint.
		$this->loader->add_action( 'init', $this, 'demo_listing_register' );
		//Add template redirect.
		$this->loader->add_action( 'template_redirect', $this, 'demo_listing' );
		//Enqueue admin scripts.
		$this->loader->add_action( 'admin_enqueue_scripts', $this, 'enqueue_template_dir_scripts' );
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
			wp_register_script( $script_handle, plugin_dir_url( $this->get_dir() ) . $this->slug . '/js/script.js', array( 'jquery' ), $this->version );
			wp_localize_script( $script_handle, 'importer_endpoint',
				array(
					'url'   => $this->get_endpoint_url( '/import_elementor' ),
					'nonce' => wp_create_nonce( 'wp_rest' ),
				) );
			wp_enqueue_script( $script_handle );
		}
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

		if ( ! ( $current_screen->id == 'orbit-fox_page_obfx_template_dir' ) && ( ! $current_screen == 'customize' ) ) {
			return array();
		}

		$enqueue = array(
			'css' => array(
				'admin' => array(),
			),
		);
		return $enqueue;
	}

	/**
	 * Register Rest endpoint for requests.
	 */
	public function register_endpoints() {
		register_rest_route( $this->slug, '/import_elementor', array(
			'methods'  => 'POST',
			'callback' => array( $this, 'import_elementor' ),
		) );
	}

	/**
	 * The templates list.
	 *
	 * @return array
	 */
	public function templates_list() {
		$repository_raw_url = 'https://raw.githubusercontent.com/Codeinwp/obfx-templates/master/';
		$defaults_if_empty  = array(
			'title'            => __( 'A new Orbit Fox Template', 'themeisle-companion' ),
			'screenshot'       => esc_url( 'https://raw.githubusercontent.com/Codeinwp/obfx-templates/master/placeholder.png' ),
			'description'      => __( 'This is an awesome Orbit Fox Template.', 'themeisle-companion' ),
			'demo_url'         => esc_url( 'https://demo.themeisle.com/hestia-pro-demo-content/demo-placeholder/' ),
			'import_file'      => '',
			'required_plugins' => array( 'elementor' => array( 'title' => __( 'Elementor Page Builder', 'themeisle-companion' ) ) ),
		);

		$templates_list = array(
			'about-our-business-elementor' => array(
				'title'            => __( 'About Our Business', 'themeisle-companion' ),
				'description'      => __( 'Use this layout to present your business in a fancy way. Add an interactive header, shwocase your services via progress bars, introduce your team members, and locate your headquarters on Google Maps. Last but not least, beautify the design by adding catchy images.', 'themeisle-companion' ),
				'demo_url'         => 'https://demo.themeisle.com/hestia-pro-demo-content/about-our-business-elementor/',
				'screenshot'       => esc_url( $repository_raw_url . 'about-our-business-elementor/screenshot.png' ),
				'import_file'      => esc_url( $repository_raw_url . 'about-our-business-elementor/template.json' ),
				),
			'contact-us-elementor'         => array(
				'title'            => __( 'Contact Us', 'themeisle-companion' ),
				'description'      => __( 'A clean and simple template for your Contact page, where we integrated our Pirate Forms plugin. It will let your customers send you a message using an intuitive form. A Google map, together with a few other details about your business, completes the section.', 'themeisle-companion' ),
				'demo_url'         => 'https://demo.themeisle.com/hestia-pro-demo-content/contact-us-elementor/',
				'screenshot'       => esc_url( $repository_raw_url . 'contact-us-elementor/screenshot.png' ),
				'import_file'      => esc_url( $repository_raw_url . 'contact-us-elementor/template.json' ),
			),
			'pricing-elementor'            => array(
				'title'            => __( 'Pricing', 'themeisle-companion' ),
				'description'      => __( 'If you plan to sell your products online, this layout offers you elegant pricing tables so you can differentiate the features and services for your clients. Also, for a better clarification, the template provides a FAQ area where you can answer people\'s questions.', 'themeisle-companion' ),
				'demo_url'         => 'https://demo.themeisle.com/hestia-pro-demo-content/pricing-elementor/',
				'screenshot'       => esc_url( $repository_raw_url . 'pricing-elementor/screenshot.png' ),
				'import_file'      => esc_url( $repository_raw_url . 'pricing-elementor/template.json' ),
			),
			'material-homepage-elementor'  => array(
				'title'            => __( 'Material Homepage', 'themeisle-companion' ),
				'description'      => __( 'This layout could be your main website homepage (or you can use it as an alternative homepage, if you wish). It was built on material design and comes with call to action, catchy icons, testimonials, blog posts, pricing plans, and other sections that you can add yourself by customizing it.', 'themeisle-companion' ),
				'demo_url'         => 'https://demo.themeisle.com/hestia-pro-demo-content/material-homepage-elementor/',
				'screenshot'       => esc_url( $repository_raw_url . 'material-homepage-elementor/screenshot.png' ),
				'import_file'      => esc_url( $repository_raw_url . 'material-homepage-elementor/template.json' ),
			),
			'ether-elementor'              => array(
				'title'            => __( 'Ether - Landing Page', 'themeisle-companion' ),
				'description'      => __( 'An elegant and modern landing page for e-commerce, coming with a clean interface, beautiful typography, photo galleries, and call to action. If you have an online shop and want to promote a certain product, use this layout to tell people why they should buy it.', 'themeisle-companion' ),
				'demo_url'         => 'https://demo.themeisle.com/hestia-pro-demo-content/ether-elementor/',
				'screenshot'       => esc_url( $repository_raw_url . 'ether-elementor/screenshot.png' ),
				'import_file'      => esc_url( $repository_raw_url . 'ether-elementor/template.json' ),
			),
			'jason-elementor'              => array(
				'title'            => __( 'Jason - Landing Page', 'themeisle-companion' ),
				'description'      => __( 'A classy template for freelancers, where you can put your skills and knowldge in the spotlight for potential clients. Talk about yourself, your projects, awards, and let people contact you easily. The template is designed to feature one-page scrolling.', 'themeisle-companion' ),
				'demo_url'         => 'https://demo.themeisle.com/hestia-pro-demo-content/jason-elementor/',
				'screenshot'       => esc_url( $repository_raw_url . 'jason-elementor/screenshot.png' ),
				'import_file'      => esc_url( $repository_raw_url . 'jason-elementor/template.json' ),
			),
			'pulse-elementor'              => array(
				'title'            => __( 'Pulse - Landing Page', 'themeisle-companion' ),
				'description'      => __( 'A good-looking landing page for products and apps, built to mark the features and services that they offer. The layout provides customer reviews, call to action, beautiful pricing tables, an About section, and a creative design. If you want to promote and sell your brand product, this template might help.', 'themeisle-companion' ),
				'demo_url'         => 'https://demo.themeisle.com/hestia-pro-demo-content/pulse-elementor/',
				'screenshot'       => esc_url( $repository_raw_url . 'pulse-elementor/screenshot.png' ),
				'import_file'      => esc_url( $repository_raw_url . 'pulse-elementor/template.json' ),
			),
			'ascend-elementor'             => array(
				'title'            => __( 'Ascend - Landing Page', 'themeisle-companion' ),
				'description'      => __( 'A resume-like template, built for outdoor enthusiasts and nature lovers. Its design and layout make it flexible for any other purpose too, so do not hesitate to showcase any kind of skills and activities, even business-oriented.', 'themeisle-companion' ),
				'demo_url'         => 'https://demo.themeisle.com/hestia-pro-demo-content/ascend-elementor/',
				'screenshot'       => esc_url( $repository_raw_url . 'ascend-elementor/screenshot.png' ),
				'import_file'      => esc_url( $repository_raw_url . 'ascend-elementor/template.json' ),
			),
			'path-elementor'               => array(
				'title'            => __( 'Path - Landing Page', 'themeisle-companion' ),
				'description'      => __( 'If you are a business consultant - agency or working on your own - have a look at this template! It comes with a clean design, call to action, statistics, and sections that put your services first.', 'themeisle-companion' ),
				'demo_url'         => 'https://demo.themeisle.com/hestia-pro-demo-content/path-elementor/',
				'screenshot'       => esc_url( $repository_raw_url . 'path-elementor/screenshot.png' ),
				'import_file'      => esc_url( $repository_raw_url . 'path-elementor/template.json' ),
			),
			'mocha-elementor'              => array(
				'title'            => __( 'Mocha - Landing Page', 'themeisle-companion' ),
				'description'      => __( 'An elegant and modern template for cafes and pubs, where you can display your menu in a mouth-watering way. Call to action, blog posts, attractive images, tabbed menus, and a catchy design will help you convince more people to stop by.', 'themeisle-companion' ),
				'demo_url'         => 'https://demo.themeisle.com/hestia-pro-demo-content/mocha-elementor/',
				'screenshot'       => esc_url( $repository_raw_url . 'mocha-elementor/screenshot.png' ),
				'import_file'      => esc_url( $repository_raw_url . 'mocha-elementor/template.json' ),
			),
		);

		foreach ( $templates_list as $template => $properties ) {
			$templates_list[ $template ] = wp_parse_args( $properties, $defaults_if_empty );
		}

		return $templates_list;
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
		add_submenu_page(
			'obfx_companion', __( 'Orbit Fox Template Directory', 'themeisle-companion' ), __( 'Template Directory', 'themeisle-companion' ), 'manage_options', 'obfx_template_dir',
			array( $this, 'render_admin_page' )
		);
	}

	/**
	 * Render the template directory admin page.
	 */
	public function render_admin_page() {
		$data = array(
			'templates_array'  => $this->templates_list(),
		);
		echo $this->render_view( 'template-directory-page', $data );
	}

	/**
	 * Utility method to call Elementor import routine.
	 */
	public function import_elementor() {
		if ( ! defined( 'ELEMENTOR_VERSION' ) ) {
			return 'no-elementor';
		}

		require_once( ABSPATH . 'wp-admin' . '/includes/file.php' );
		require_once( ABSPATH . 'wp-admin' . '/includes/image.php' );

		$template                   = download_url( esc_url( $_POST['template_url'] ) );
		$_FILES['file']['tmp_name'] = $template;
		$elementor                  = new Elementor\TemplateLibrary\Source_Local;
		$elementor->import_template();
		unlink( $template );

		$args = array(
			'post_type'        => 'elementor_library',
			'nopaging'         => true,
			'posts_per_page'   => '1',
			'orderby'          => 'date',
			'order'            => 'DESC',
			'suppress_filters' => true,
		);

		$query = new WP_Query( $args );

		$last_template_added = $query->posts[0];
		//get template id
		$template_id = $last_template_added->ID;

		wp_reset_query();
		wp_reset_postdata();

		//page content
		$page_content = $last_template_added->post_content;
		//meta fields
		$elementor_data_meta      = get_post_meta( $template_id, '_elementor_data' );
		$elementor_ver_meta       = get_post_meta( $template_id, '_elementor_version' );
		$elementor_edit_mode_meta = get_post_meta( $template_id, '_elementor_edit_mode' );
		$elementor_css_meta       = get_post_meta( $template_id, '_elementor_css' );

		$elementor_metas = array(
			'_elementor_data'      => ! empty( $elementor_data_meta[0] ) ? wp_slash( $elementor_data_meta[0] ) : '',
			'_elementor_version'   => ! empty( $elementor_ver_meta[0] ) ? $elementor_ver_meta[0] : '',
			'_elementor_edit_mode' => ! empty( $elementor_edit_mode_meta[0] ) ? $elementor_edit_mode_meta[0] : '',
			'_elementor_css'       => $elementor_css_meta,
		);

		// Create post object
		$new_template_page = array(
			'post_type'    => 'page',
			'post_title'   => $_POST['template_name'],
			'post_status'  => 'publish',
			'post_content' => $page_content,
			'meta_input'   => $elementor_metas,
		);

		$current_theme = wp_get_theme();
		switch ( $current_theme->get_template() ) {
			case 'hestia-pro':
			case 'hestia':
				$new_template_page['page_template'] = 'page-templates/template-pagebuilder-full-width.php';
				break;
			case 'zerif-lite':
			case 'zerif-pro':
				$new_template_page['page_template'] = 'template-fullwidth-no-title.php';
				break;
		}

		$post_id = wp_insert_post( $new_template_page );

		$redirect_url = add_query_arg( array(
			'post'   => $post_id,
			'action' => 'elementor',
		), admin_url( 'post.php' ) );

		return ( $redirect_url );

		die();
	}

	/**
	 * Options array for the Orbit Fox module.
	 *
	 * @return array
	 */
	public function options() {
		return array();
	}

	/**
	 * Generate action button html.
	 *
	 * @param string $slug plugin slug.
	 *
	 * @return string
	 */
	public function get_button_html( $slug ) {
		$button = '';
		$state  = $this->check_plugin_state( $slug );
		if ( ! empty( $slug ) ) {
			switch ( $state ) {
				case 'install':
					$nonce  = wp_nonce_url(
						add_query_arg(
							array(
								'action' => 'install-plugin',
								'from'   => 'import',
								'plugin' => $slug,
							),
							network_admin_url( 'update.php' )
						),
						'install-plugin_' . $slug
					);
					$button .= '<a data-slug="' . $slug . '" class="install-now obfx-install-plugin button button-primary" href="' . esc_url( $nonce ) . '" data-name="' . $slug . '" aria-label="Install ' . $slug . '">' . __( 'Install and activate', 'themeisle-companion' ) . '</a>';
					break;
				case 'activate':
					$plugin_link_suffix = $slug . '/' . $slug . '.php';
					$nonce              = add_query_arg(
						array(
							'action'        => 'activate',
							'plugin'        => rawurlencode( $plugin_link_suffix ),
							'_wpnonce'      => wp_create_nonce( 'activate-plugin_' . $plugin_link_suffix ),
						), network_admin_url( 'plugins.php' )
					);
					$button             .= '<a data-slug="' . $slug . '" class="activate-now button button-primary" href="' . esc_url( $nonce ) . '" aria-label="Activate ' . $slug . '">' . __( 'Activate', 'themeisle-companion' ) . '</a>';
					break;
			}// End switch().
		}// End if().
		return $button;
	}

	/**
	 * Check plugin state.
	 *
	 * @param string $slug plugin slug.
	 *
	 * @return bool
	 */
	public function check_plugin_state( $slug ) {
		if ( file_exists( WP_CONTENT_DIR . '/plugins/' . $slug . '/' . $slug . '.php' ) || file_exists( WP_CONTENT_DIR . '/plugins/' . $slug . '/index.php' ) ) {
			require_once( ABSPATH . 'wp-admin' . '/includes/plugin.php' );
			$needs = ( is_plugin_active( $slug . '/' . $slug . '.php' ) ||
			           is_plugin_active( $slug . '/index.php' ) ) ?
				'deactivate' : 'activate';

			return $needs;
		} else {
			return 'install';
		}
	}

}
