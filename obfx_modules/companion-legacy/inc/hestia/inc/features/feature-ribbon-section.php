<?php
/**
 * Customizer functionality for the Ribbon section.
 *
 * @package Hestia
 * @since 1.1.47
 */


if ( ! function_exists( 'hestia_ribbon_customize_register' ) ) :

	/**
	 * Hook controls for Ribbon section to Customizer.
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager.
	 * @since 1.1.47
	 * @modified 1.1.49
	 */
	function hestia_ribbon_customize_register( $wp_customize ) {

		$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';

		if ( class_exists( 'Hestia_Hiding_Section' ) ) {
			$wp_customize->add_section(
				new Hestia_Hiding_Section(
					$wp_customize, 'hestia_ribbon', array(
						'title'          => esc_html__( 'Ribbon', 'themeisle-companion' ),
						'panel'          => 'hestia_frontpage_sections',
						'priority'       => apply_filters( 'hestia_section_priority', 35, 'hestia_ribbon' ),
						'hiding_control' => 'hestia_ribbon_hide',
					)
				)
			);
		} else {
			$wp_customize->add_section(
				'hestia_ribbon', array(
					'title'    => esc_html__( 'Ribbon', 'themeisle-companion' ),
					'panel'    => 'hestia_frontpage_sections',
					'priority' => apply_filters( 'hestia_section_priority', 35, 'hestia_ribbon' ),
				)
			);
		}

		$wp_customize->add_setting(
			'hestia_ribbon_hide', array(
				'sanitize_callback' => 'hestia_sanitize_checkbox',
				'default'           => true,
				'transport'         => $selective_refresh,
			)
		);

		$wp_customize->add_control(
			'hestia_ribbon_hide', array(
				'type'     => 'checkbox',
				'label'    => esc_html__( 'Disable section', 'themeisle-companion' ),
				'section'  => 'hestia_ribbon',
				'priority' => 1,
			)
		);

		$default = ( current_user_can( 'edit_theme_options' ) ? get_template_directory_uri() . '/assets/img/contact.jpg' : '' );
		$wp_customize->add_setting(
			'hestia_ribbon_background', array(
				'sanitize_callback' => 'esc_url_raw',
				'default'           => $default,
				'transport'         => $selective_refresh,
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize, 'hestia_ribbon_background', array(
					'label'           => esc_html__( 'Background Image', 'themeisle-companion' ),
					'section'         => 'hestia_ribbon',
					'priority'        => 5,
				)
			)
		);

		$default = ( current_user_can( 'edit_theme_options' ) ? esc_html__( 'Subscribe to our Newsletter', 'themeisle-companion' ) : false );
		$wp_customize->add_setting(
			'hestia_ribbon_text', array(
				'sanitize_callback' => 'wp_kses_post',
				'default'           => $default,
				'transport'         => $selective_refresh,
			)
		);

		$wp_customize->add_control(
			'hestia_ribbon_text', array(
				'type'  => 'textarea',
				'label'    => esc_html__( 'Text', 'themeisle-companion' ),
				'section'  => 'hestia_ribbon',
				'priority' => 10,
			)
		);

		$default = ( current_user_can( 'edit_theme_options' ) ? esc_html__( 'Subscribe', 'themeisle-companion' ) : false );
		$wp_customize->add_setting(
			'hestia_ribbon_button_text', array(
				'sanitize_callback' => 'sanitize_text_field',
				'default' => $default,
				'transport' => $selective_refresh,
			)
		);

		$wp_customize->add_control(
			'hestia_ribbon_button_text', array(
				'label'    => esc_html__( 'Button Text', 'themeisle-companion' ),
				'section'  => 'hestia_ribbon',
				'priority' => 15,
			)
		);

		$default = ( current_user_can( 'edit_theme_options' ) ? '#' : false );
		$wp_customize->add_setting(
			'hestia_ribbon_button_url', array(
				'sanitize_callback' => 'esc_url_raw',
				'transport'         => $selective_refresh,
				'default' => $default,
			)
		);

		$wp_customize->add_control(
			'hestia_ribbon_button_url', array(
				'label'    => esc_html__( 'Link', 'themeisle-companion' ),
				'section'  => 'hestia_ribbon',
				'priority' => 20,
			)
		);
	}
	add_action( 'customize_register', 'hestia_ribbon_customize_register' );

endif;


if ( ! function_exists( 'hestia_register_ribbon_partials' ) ) :

	/**
	 * Add selective refresh for ribbon section controls.
	 *
	 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
	 * @since 1.1.47
	 * @access public
	 */
	function hestia_register_ribbon_partials( $wp_customize ) {
		// Abort if selective refresh is not available.
		if ( ! isset( $wp_customize->selective_refresh ) ) {
			return;
		}

		$wp_customize->selective_refresh->add_partial(
			'hestia_ribbon_hide', array(
				'selector' => '.hestia-ribbon:not(.is-shortcode)',
				'container_inclusive' => true,
				'render_callback' => 'hestia_ribbon',
				'fallback_refresh' => false,
			)
		);

		$wp_customize->selective_refresh->add_partial(
			'hestia_ribbon_background', array(
				'selector' => '.hestia-ribbon-style',
				'container_inclusive' => true,
				'render_callback' => 'hestia_ribbon_background',
			)
		);

		$wp_customize->selective_refresh->add_partial(
			'hestia_ribbon_text', array(
				'selector' => '.hestia-ribbon .hestia-title',
				'render_callback' => 'hestia_ribbon_text_callback',
			)
		);

		$wp_customize->selective_refresh->add_partial(
			'hestia_ribbon_button_text', array(
				'selector' => '.hestia-subscribe-button',
				'render_callback' => 'hestia_ribbon_button_text_callback',
			)
		);
	}

	add_action( 'customize_register', 'hestia_register_ribbon_partials' );
endif;

/**
 * Callback function for ribbon text selective refresh.
 *
 * @since 1.1.47
 * @return string
 */
function hestia_ribbon_text_callback() {
	return get_theme_mod( 'hestia_ribbon_text' );
}

/**
 * Callback function for ribbon button text selective refresh.
 *
 * @since 1.1.47
 * @return string
 */
function hestia_ribbon_button_text_callback() {
	return get_theme_mod( 'hestia_ribbon_button_text' );
}
