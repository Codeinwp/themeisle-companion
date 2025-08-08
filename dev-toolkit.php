<?php

/**
 * Plugin Name: Orbit Fox Dev Toolkit
 * Description: Developer toolkit for Orbit Fox.
 * Version: 99.99.99
 * Author: Andrei Baicus <andrei@themeisle.com>
 * Text Domain: obfx-dev-toolkit
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * OBFX_Dev_Toolkit Class.
 */
class OBFX_Dev_Toolkit {
	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'admin_bar_menu', array( $this, 'add_admin_bar_menu' ), 100 );
		add_action( 'admin_init', array( $this, 'handle_black_friday_toggle' ) );
		add_filter( 'themeisle_sdk_current_date', array( $this, 'filter_current_date' ) );
	}

	/**
	 * Add admin bar menu.
	 *
	 * @param \WP_Admin_Bar $wp_admin_bar
	 * @return void
	 */
	public function add_admin_bar_menu( $wp_admin_bar ) {
		$wp_admin_bar->add_node(
			array(
				'id'    => 'obfx-dev-toolkit',
				'title' => 'OrbitFox Dev Toolkit',
				'href'  => '#',
			)
		);

		// Add black friday banner toggle
		$wp_admin_bar->add_node(
			array(
				'parent' => 'obfx-dev-toolkit',
				'id'     => 'obfx-black-friday-toggle',
				'title'  => 'Black Friday: ' . $this->get_current_black_friday_state(),
				'href'   => wp_nonce_url( admin_url( '?obfx_black_friday=toggle' ), 'obfx_black_friday_toggle' ),
			)
		);
	}


	/**
	 * Handle black friday banner toggle.
	 *
	 * @return void
	 */
	public function handle_black_friday_toggle() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		if ( ! isset( $_GET['obfx_black_friday'] ) || ! isset( $_GET['_wpnonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_GET['_wpnonce'], 'obfx_black_friday_toggle' ) ) {
			return;
		}

		$current_state = get_option( 'obfx_black_friday_enabled', false );
		$new_state     = ! $current_state;

		update_option( 'obfx_black_friday_enabled', $new_state );

		wp_safe_redirect( remove_query_arg( array( 'obfx_black_friday', '_wpnonce' ), wp_get_referer() ) );
		exit;
	}

	/**
	 * Filter current date for black friday banner.
	 *
	 * @return \DateTime
	 */
	public function filter_current_date() {
		if ( get_option( 'obfx_black_friday_enabled', false ) ) {
			return new DateTime( '2025-11-26' );
		}
		return new DateTime();
	}

	/**
	 * Get current black friday state.
	 *
	 * @return string
	 */
	private function get_current_black_friday_state() {
		return get_option( 'obfx_black_friday_enabled', false ) ? 'Enabled' : 'Disabled';
	}
}

// Initialize the plugin
new OBFX_Dev_Toolkit();
