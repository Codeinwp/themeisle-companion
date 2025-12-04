<?php

/**
 * Login Customizer Core Orbit Fox Module.
 *
 * @link       https://themeisle.com
 * @since      3.0.0
 *
 * @package    Login_Customizer_OBFX_Module
 */

/**
 * The class defines a new module to be used by Orbit Fox plugin.
 *
 * @package    Login_Customizer_OBFX_Module
 * @author     Themeisle <friends@themeisle.com>
 */
class Login_Customizer_OBFX_Module extends Orbit_Fox_Module_Abstract {

	const SECTION_ID = 'obfx_login_customizer';
	const OPTION_KEY = 'obfx_login_customizer_options';

	const ALLOWED_FORM_ACTIONS = array(
		'register',
		'lostpassword',
		'login',
	);

	/**
	 * Constructor.
	 * 
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
	
		require_once 'inc/class-obfx-login-values.php';
		require_once 'inc/class-obfx-login-security.php';
	}

	/**
	 * Setup module strings
	 *
	 * @access  public
	 */
	public function set_module_strings() {
		$this->name               = __( 'Login Page Customizer', 'themeisle-companion' );
		$this->description        = __( 'Customize your WordPress login page with a dedicated customizer interface. Change logos, colors, backgrounds, and branding to match your website design without needing separate plugins.', 'themeisle-companion' );
		$this->documentation_url  = 'https://docs.themeisle.com/article/951-orbit-fox-documentation#login-page-customizer';
		$this->module_main_action = array(
			'url'  => add_query_arg(
				array(
					'autofocus[section]' => self::SECTION_ID,
					'url'                => $this->get_customizer_login_url(),
				),
				admin_url( 'customize.php' )
			),
			'text' => __( 'Customize Login Page', 'themeisle-companion' ),
		);
	}

	/**
	 * Enable module by default
	 *
	 * @return bool
	 */
	public function enable_module() {
		return true;
	}

	/**
	 * Load module
	 */
	public function load() {
	}

	/**
	 * Define module options
	 *
	 * @return array
	 */
	public function options() {
		return array();
	}

	/**
	 * Admin enqueue scripts and styles
	 *
	 * @return array
	 */
	public function admin_enqueue() {
		return array();
	}

	/**
	 * Public enqueue scripts and styles
	 *
	 * @return array
	 */
	public function public_enqueue() {
		return array();
	}

	/**
	 * Register hooks
	 */
	public function hooks() {
		$this->loader->add_action( 'customize_register', $this, 'customize_register' );
		$this->loader->add_action( 'customize_controls_enqueue_scripts', $this, 'enqueue_customizer_scripts' );
		$this->loader->add_action( 'customize_preview_init', $this, 'enqueue_customizer_preview_script' );
		$this->loader->add_action( 'login_enqueue_scripts', $this, 'login_enqueue_scripts' );
		$this->loader->add_filter( 'login_headerurl', $this, 'change_login_url' );
		$this->loader->add_filter( 'login_headertext', $this, 'change_login_text' );
		$this->loader->add_action( 'init', $this, 'adjust_login_page' );
		$this->loader->add_action( 'template_redirect', $this, 'customizer_preview_template_redirect' );

		$settings = $this->get_settings();

		$login_security = new OBFX_Login_Security(
			array(
				'change_login_base'            => $settings['change_login_url'],
				'login_base'                   => $settings['login_url'],
				'redirect_admin_urls_to_login' => $settings['redirect_to_login'],
			)
		);

		$login_security->init();
	}

	/**
	 * Customizer preview template redirect.
	 * 
	 * Needed because the customizer preview doesn't work properly with the login page.
	 * 
	 * @return void
	 */
	public function customizer_preview_template_redirect() {
		if ( ! is_customize_preview() ) {
			return;
		}

		if ( ! isset( $_GET['obfx-login'] ) ) {
			return;
		}

		$this->enqueue_customizer_preview_script();

		if ( in_array( $_GET['obfx-login'], self::ALLOWED_FORM_ACTIONS, true ) ) {
			$_REQUEST['action'] = $_GET['obfx-login'];
		}

		// Used by wp-login.php - do not remove.
		$user_login = 'user@example.com';
		$error      = '';

		require_once 'wp-login.php';

		wp_footer();

		exit;
	}
  

	/**
	 * Adjust the login page.
	 * 
	 * Removes the shake error effect on the login page.
	 * Redirects the links in the preview to the customizer login page.
	 * 
	 * @return void
	 */
	public function adjust_login_page() {
		if ( ! is_customize_preview() ) {
			return;
		}

		add_filter( 'shake_error_codes', '__return_empty_array' );
		add_filter( 'enable_login_autofocus', '__return_false' );

		foreach ( self::ALLOWED_FORM_ACTIONS as $action ) {
			add_filter(
				$action . '_url',
				function ( $url ) use ( $action ) {
					return $this->get_customizer_login_url( $action );
				}
			);
		}
	}

	/**
	 * Register the customizer settings.
	 * 
	 * @param WP_Customize_Manager $wp_customize The customizer manager.
	 * 
	 * @return void
	 */
	public function customize_register( $wp_customize ) {

		require_once 'inc/class-obfx-login-control.php';

		add_action( 'wp_footer', array( $wp_customize, 'customize_preview_loading_style' ) );

		$wp_customize->add_section(
			self::SECTION_ID,
			array(
				'title'    => __( 'Login Customizer', 'themeisle-companion' ),
				'priority' => 10,
			)
		);

		$wp_customize->add_setting(
			self::OPTION_KEY,
			array(
				'default'           => OBFX_Login_Values::get_defaults(),
				'type'              => 'option',
				'sanitize_callback' => array( OBFX_Login_Values::class, 'sanitize_root_value' ),
				'validate_callback' => array( OBFX_Login_Values::class, 'validate_root_value' ),
				'transport'         => 'postMessage',
			)
		);

		$wp_customize->add_control(
			new OBFX_Login_Control(
				$wp_customize,
				self::OPTION_KEY,
				array(
					'id'      => self::OPTION_KEY,
					'section' => self::SECTION_ID,
					'setting' => self::OPTION_KEY,
				)
			)
		);
	}

	/**
	 * Enqueue the customizer scripts.
	 * 
	 * @return void
	 */
	public function enqueue_customizer_scripts() {
		$asset_path = OBX_PATH . '/obfx_modules/login-customizer/js/build/customizer.asset.php';

		if ( ! is_file( $asset_path ) ) {
			return;
		}

		$asset = include $asset_path;

		if ( ! is_array( $asset ) || ! isset( $asset['dependencies'] ) || ! isset( $asset['version'] ) ) {
			return;
		}

		wp_register_script( 'obfx-login-customizer', OBFX_URL . '/obfx_modules/login-customizer/js/build/customizer.js', $asset['dependencies'], $asset['version'], true );
		wp_localize_script( 'obfx-login-customizer', 'OBFXData', $this->get_localization_data() );
		wp_set_script_translations( 'obfx-login-customizer', 'themeisle-companion' );
		wp_enqueue_script( 'obfx-login-customizer' );

		wp_register_style( 'obfx-login-customizer-customizer', OBFX_URL . '/obfx_modules/login-customizer/css/customizer.css', array(), $this->version );
		wp_enqueue_style( 'obfx-login-customizer-customizer' );
	}

	/**
	 * Enqueue the customizer preview script.
	 * 
	 * @return void
	 */
	public function enqueue_customizer_preview_script() {
		wp_register_script( 'obfx-login-customizer-preview', OBFX_URL . '/obfx_modules/login-customizer/js/previewer.js', array( 'customize-preview' ), $this->version, true );
		wp_localize_script( 'obfx-login-customizer-preview', 'OBFXData', $this->get_localization_data() );
		wp_enqueue_script( 'obfx-login-customizer-preview' );
	}

	/**
	 * Enqueue the login scripts.
	 * 
	 * @return void
	 */
	public function login_enqueue_scripts() {
		wp_enqueue_style( 'obfx-login-customizer', OBFX_URL . '/obfx_modules/login-customizer/css/login.css', array(), $this->version );
		wp_add_inline_style( 'obfx-login-customizer', $this->get_css() );
	}


	/**
	 * Get localization data for the customizer.
	 * 
	 * @return array
	 */
	public function get_localization_data() {
		$languages      = get_available_languages();
		$policy_page_id = (int) get_option( 'wp_page_for_privacy_policy' );


		return array(
			'sectionID'           => self::SECTION_ID,
			'optionKey'           => self::OPTION_KEY,
			'loginUrl'            => $this->get_customizer_login_url(),
			'registerUrl'         => $this->get_customizer_login_url( 'register' ),
			'lostPasswordUrl'     => $this->get_customizer_login_url( 'lostpassword' ),
			'defaultValues'       => OBFX_Login_Values::get_defaults(),
			'hasLanguageSwitcher' => ! empty( $languages ),
			'registrationEnabled' => get_option( 'users_can_register' ),
			'hasPrivacyPolicy'    => get_post_status( $policy_page_id ) === 'publish',
		);
	}

	/**
	 * Get the settings.
	 * 
	 * @return array
	 */
	private function get_settings() {
		return wp_parse_args( get_option( self::OPTION_KEY, OBFX_Login_Values::get_defaults() ), OBFX_Login_Values::get_defaults() );
	}

	/**
	 * Get the css for the login page.
	 * 
	 * @return string
	 */
	private function get_css() {
		$options = $this->get_settings();


		$logo_selector = '.login h1 a, .login .wp-login-logo a';

		$css = array();

		$css[ $logo_selector ] = array();

		if ( $options['disable_logo'] === true ) {
			$css[ $logo_selector ]['display'] = 'none';
		} elseif ( $options['custom_logo_url'] ) {
			$css[ $logo_selector ]['background-image'] = 'url(' . $options['custom_logo_url'] . ')';
		}

		$css[':root'] = array();

		if ( $options['page_bg_color'] ) {
			$css[':root']['--obfx-login-bg-color'] = $options['page_bg_color'];
		}
		if ( $options['page_bg_image'] ) {
			$css[':root']['--obfx-login-bg-image']        = 'url(' . $options['page_bg_image'] . ')';
			$css[':root']['--obfx-login-bg-position']     = $options['page_bg_image_position'];
			$css[':root']['--obfx-login-bg-size']         = $options['page_bg_image_size'];
			$css[':root']['--obfx-login-bg-repeat']       = $options['page_bg_image_repeat'];
			$css[':root']['--obfx-login-bg-overlay-blur'] = $options['page_bg_overlay_blur'] . 'px';
		}

		$css[':root']['--obfx-login-logo-height']        = $options['logo_height'] . 'px';
		$css[':root']['--obfx-login-logo-width']         = $options['logo_width'] . 'px';
		$css[':root']['--obfx-login-logo-margin-bottom'] = $options['logo_bottom_margin'] . 'px';

		if ( $options['form_bg_color'] ) {
			$css[':root']['--obfx-login-form-bg-color'] = $options['form_bg_color'];
		}

		$css[':root']['--obfx-login-form-text-color']    = $options['form_text_color'];
		$css[':root']['--obfx-login-form-border']        = $options['form_border'];
		$css[':root']['--obfx-login-form-width']         = $options['form_width'] . 'px';
		$css[':root']['--obfx-login-form-border-radius'] = $options['form_border_radius'] . 'px';
		$css[':root']['--obfx-login-form-padding']       = $options['form_padding'];

		$css[':root']['--obfx-login-form-field-bg-color']      = $options['form_field_bg_color'];
		$css[':root']['--obfx-login-form-field-text-color']    = $options['form_field_text_color'];
		$css[':root']['--obfx-login-form-field-border']        = $options['form_field_border'];
		$css[':root']['--obfx-login-form-field-border-radius'] = $options['form_field_border_radius'] . 'px';
		$css[':root']['--obfx-login-form-field-margin-bottom'] = $options['form_field_margin_bottom'] . 'px';
		$css[':root']['--obfx-login-form-field-padding']       = $options['form_field_padding'];
		$css[':root']['--obfx-login-form-field-font-size']     = $options['form_field_font_size'] . 'px';

		$css[':root']['--obfx-login-form-label-font-size']     = $options['form_label_font_size'] . 'px';
		$css[':root']['--obfx-login-form-label-margin-bottom'] = $options['form_label_margin_bottom'] . 'px';
		$css[':root']['--obfx-login-form-checkbox-content']    = $this->get_checkbox_icon_content();

		$css[':root']['--obfx-login-button-padding']            = $options['button_padding'];
		$css[':root']['--obfx-login-button-font-size']          = $options['button_font_size'] . 'px';
		$css[':root']['--obfx-login-button-border-radius']      = $options['button_border_radius'] . 'px';
		$css[':root']['--obfx-login-button-border']             = $options['button_border'];
		$css[':root']['--obfx-login-button-background']         = $options['button_background'];
		$css[':root']['--obfx-login-button-text-color']         = $options['button_text_color'];
		$css[':root']['--obfx-login-button-hover-background']   = $options['button_hover_background'];
		$css[':root']['--obfx-login-button-hover-text-color']   = $options['button_hover_text_color'];
		$css[':root']['--obfx-login-button-hover-border-color'] = $options['button_hover_border_color'];

		if ( $options['button_display_below'] === true ) {
			$css[':root']['--obfx-login-button-width']      = $options['button_width'] . '%';
			$css[':root']['--obfx-login-button-alignment']  = $options['button_alignment'];
			$css[':root']['--obfx-login-button-margin-top'] = $options['button_margin_top'] . 'px';
		}


		if ( $options['show_remember_me'] === false ) {
			$css['.forgetmenot'] = array( 'display' => 'none' );
		}

		if ( $options['show_navigation_links'] === false ) {
			$css['#nav'] = array( 'display' => 'none' );
		} else {
			$css[':root']['--obfx-login-nav-color']       = $options['nav_color'];
			$css[':root']['--obfx-login-nav-hover-color'] = $options['nav_hover_color'];
			$css[':root']['--obfx-login-nav-font-size']   = $options['nav_font_size'] . 'px';
		}

		if ( $options['show_link_to_homepage'] === false ) {
			$css['.login #backtoblog'] = array( 'display' => 'none' );
		} else {
			$css[':root']['--obfx-login-homepage-link-color']       = $options['homepage_link_color'];
			$css[':root']['--obfx-login-homepage-link-hover-color'] = $options['homepage_link_hover_color'];
			$css[':root']['--obfx-login-homepage-link-font-size']   = $options['homepage_link_font_size'] . 'px';
		}

		if ( $options['show_privacy_policy'] === false ) {
			$css['.login .privacy-policy-page-link'] = array( 'display' => 'none' );
		} else {
			$css[':root']['--obfx-login-privacy-policy-link-color']       = $options['privacy_policy_link_color'];
			$css[':root']['--obfx-login-privacy-policy-link-hover-color'] = $options['privacy_policy_link_hover_color'];
			$css[':root']['--obfx-login-privacy-policy-link-font-size']   = $options['privacy_policy_link_font_size'] . 'px';
		}

		if ( $options['show_language_switcher'] === false ) {
			$css['.language-switcher'] = array( 'display' => 'none' );
		}

		if ( $options['button_display_below'] === true ) {
			$css['.login form .forgetmenot, p.submit'] = array( 'float' => 'none' );
		}


		if ( is_customize_preview() ) {
			$css['#login_error'] = array( 'display' => 'none' );
		}

		$css_string = '';


		foreach ( $css as $selector => $rules ) {
			if ( ! is_array( $rules ) || empty( $rules ) ) {
				continue;
			}

			$css_string .= $selector . '{';
			foreach ( $rules as $property => $value ) {
				$css_string .= $property . ':' . $value . ';';
			}
			$css_string .= '}';
		}

		return $css_string;
	}

	/**
	 * Get the checkbox icon content.
	 * 
	 * @return string
	 */
	private function get_checkbox_icon_content() {
		$options = $this->get_settings();

		$svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="%s" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-icon lucide-check"><path d="M20 6 9 17l-5-5"/></svg>';

		$svg = sprintf( $svg, $options['form_field_text_color'] );

		$svg_url = 'data:image/svg+xml;utf8,' . rawurlencode( $svg );
		$content = 'url("' . $svg_url . '")';

		return $content;
	}

	/**
	 * Change the login url.
	 * 
	 * @param string $url The url to change.
	 * 
	 * @return string
	 */
	public function change_login_url( $url ) {
		$settings = $this->get_settings();
		return esc_url( $settings['logo_url'] ? $settings['logo_url'] : $url );
	}

	/**
	 * Change the login text.
	 * 
	 * @param string $text The text to change.
	 * 
	 * @return string
	 */
	public function change_login_text( $text ) {
		$settings = $this->get_settings();
		return $settings['logo_title'] ? $settings['logo_title'] : $text;
	}

	/**
	 * Get the customizer login url.  
	 * 
	 * @param string $action The action to add to the url.
	 * 
	 * @return string
	 */
	private function get_customizer_login_url( $action = 'login' ) {
		if ( ! in_array( $action, self::ALLOWED_FORM_ACTIONS, true ) ) {
			$action = 'login';
		}

		return add_query_arg( 'obfx-login', $action, home_url() );
	}
}
