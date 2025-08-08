<?php

/**
 * OBFX Login Security Class
 *
 * @package OBFX
 */

class OBFX_Login_Security {
	const DEFAULT_CONFIG = array(
		'change_login_base'            => false,
		'redirect_admin_urls_to_login' => false,
		'login_base'                   => '',
	);

	private $config = array();

	/**
	 * Constructor.
	 * 
	 * @param array $config The configuration.
	 */
	public function __construct( $config ) {
		$this->config = wp_parse_args( $config, self::DEFAULT_CONFIG );
	}

	/**
	 * Initialize the login security features.
	 */
	public function init() {
		// Initialize custom login URL if enabled
		if ( $this->config['change_login_base'] && ! empty( $this->config['login_base'] ) ) {
			$this->init_custom_login_url();
		}
	}

	/**
	 * Initialize custom login URL functionality.
	 */
	private function init_custom_login_url() {
		add_action( 'init', array( $this, 'handle_custom_login_request' ) );
		add_filter( 'site_url', array( $this, 'filter_login_urls' ), 10, 4 );
		add_filter( 'wp_redirect', array( $this, 'filter_login_urls' ), 10, 2 );
		add_action( 'wp_loaded', array( $this, 'set_pagenow_for_custom_login' ) );
		add_action( 'login_init', array( $this, 'block_default_login_access' ) );

		// Handle admin URL redirection if enabled
		if ( $this->config['redirect_admin_urls_to_login'] ) {
			add_action( 'init', array( $this, 'handle_admin_url_redirection' ) );
		}
	}

	/**
	 * Handle admin URL redirection to custom login URL.
	 */
	public function handle_admin_url_redirection() {
		if ( is_user_logged_in() ) {
			return;
		}

		if ( ! isset( $_SERVER['REQUEST_URI'] ) ) {
			return;
		}

		if ( strpos( $_SERVER['REQUEST_URI'], 'wp-admin' ) !== false && ! $this->is_custom_login_request() ) {
			wp_safe_redirect( $this->get_custom_login_url() );
			exit;
		}
	}

	/**
	 * Handle custom login URL requests.
	 */
	public function handle_custom_login_request() {
		// Check if this is a request to our custom login URL
		if ( $this->is_custom_login_request() ) {
			$this->serve_login_page();
		}

		// Block access to default wp-login.php if not coming from custom URL
		if ( $this->is_default_login_request() && ! $this->is_custom_login_request() ) {
			$this->block_default_login();
		}
	}

	/**
	 * Check if the current request is for our custom login URL.
	 * 
	 * @return bool True if this is a custom login request.
	 */
	private function is_custom_login_request() {
		if ( ! isset( $_SERVER['REQUEST_URI'] ) ) {
			return false;
		}

		$custom_slug = $this->get_custom_login_slug();

		return ( strpos( $_SERVER['REQUEST_URI'], '/' . $custom_slug ) !== false );
	}

	/**
	 * Check if the current request is for the default login page.
	 * 
	 * @return bool True if this is a default login request.
	 */
	private function is_default_login_request() {
		if ( ! isset( $_SERVER['REQUEST_URI'] ) ) {
			return false;
		}

		return ( strpos( $_SERVER['REQUEST_URI'], 'wp-login.php' ) !== false ||
		( strpos( $_SERVER['REQUEST_URI'], 'wp-admin' ) !== false && ! is_user_logged_in() ) );
	}

	/**
	 * Get the custom login slug from config.
	 * 
	 * @return string The custom login slug.
	 */
	private function get_custom_login_slug() {
		return sanitize_title( $this->config['login_base'] );
	}

	/**
	 * Serve the WordPress login page for custom URL.
	 */
	private function serve_login_page() {
		if ( ! defined( 'WP_USE_THEMES' ) ) {
			define( 'WP_USE_THEMES', false );
		}

		global $pagenow;
		$pagenow = 'wp-login.php';

		require_once ABSPATH . 'wp-login.php';
		exit;
	}

	/**
	 * Block access to default login page.
	 */
	private function block_default_login() {
		if ( $this->config['redirect_admin_urls_to_login'] ) {
			wp_safe_redirect( $this->get_custom_login_url() );
			exit;
		}

		wp_safe_redirect( home_url( '/404' ) );
		exit;
	}

	/**
	 * Filter login URLs to use custom URL.
	 * 
	 * @param string $url The URL.
	 * @param string $path The path.
	 * @param string $scheme The scheme.
	 * @param int $blog_id The blog ID.
	 * @return string The filtered URL.
	 */
	public function filter_login_urls( $url, $path = '', $scheme = null, $blog_id = null ) {
		if ( strpos( $url, 'wp-login.php' ) !== false ) {
			if ( is_ssl() ) {
				$scheme = 'https';
			}

			$args     = explode( '?', $url );
			$base_url = home_url( $this->get_custom_login_slug(), $scheme );

			if ( isset( $args[1] ) ) {
				parse_str( $args[1], $query_args );
				$url = add_query_arg( $query_args, $base_url );
			} else {
				$url = $base_url;
			}
		}

		return $url;
	}

	/**
	 * Set the global $pagenow for custom login requests.
	 */
	public function set_pagenow_for_custom_login() {
		global $pagenow;

		if ( $this->is_custom_login_request() ) {
			$pagenow = 'wp-login.php';
		}
	}

	/**
	 * Block default login access during login_init.
	 */
	public function block_default_login_access() {
		if ( ! $this->is_custom_login_request() && $this->is_default_login_request() ) {
			wp_safe_redirect( home_url( '/404' ) );
			exit;
		}
	}

	/**
	 * Get the custom login URL.
	 * 
	 * @return string|false The custom login URL or false if not configured.
	 */
	public function get_custom_login_url() {
		if ( $this->config['change_login_base'] && ! empty( $this->config['login_base'] ) ) {
			return home_url( $this->get_custom_login_slug() );
		}

		return false;
	}
}
