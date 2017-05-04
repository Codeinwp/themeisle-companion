<?php
/**
 * Customizer functionality for the Features section.
 *
 * @package WordPress
 * @subpackage Hestia
 * @since Hestia 1.0
 */

// Load Customizer repeater control.
$repeater_path = trailingslashit( get_template_directory() ) . '/inc/customizer-repeater/functions.php';
if ( file_exists( $repeater_path ) ) {
	require_once( $repeater_path );
}

if ( ! function_exists( 'hestia_features_customize_register' ) ) :
	/**
	 * Hook controls for Features section to Customizer.
	 *
	 * @since Hestia 1.0
	 */
	function hestia_features_customize_register( $wp_customize ) {

		$wp_customize->add_section( 'hestia_features', array(
			'title'    => esc_html__( 'Features', 'hestia-companion', 'themeisle-companion' ),
			'panel'    => 'hestia_frontpage_sections',
			'priority' => apply_filters( 'hestia_section_priority', 10, 'hestia_features' ),
		) );

		$wp_customize->add_setting( 'hestia_features_hide', array(
			'sanitize_callback' => 'hestia_sanitize_checkbox',
			'default'           => false,
		) );

		$wp_customize->add_control( 'hestia_features_hide', array(
			'type'     => 'checkbox',
			'label'    => esc_html__( 'Disable section', 'hestia-companion', 'themeisle-companion' ),
			'section'  => 'hestia_features',
			'priority' => 1,
		) );

		$wp_customize->add_setting( 'hestia_features_title', array(
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );

		$wp_customize->add_control( 'hestia_features_title', array(
			'label'    => esc_html__( 'Section Title', 'hestia-companion', 'themeisle-companion' ),
			'section'  => 'hestia_features',
			'priority' => 5,
		) );

		$wp_customize->add_setting( 'hestia_features_subtitle', array(
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );

		$wp_customize->add_control( 'hestia_features_subtitle', array(
			'label'    => esc_html__( 'Section Subtitle', 'hestia-companion', 'themeisle-companion' ),
			'section'  => 'hestia_features',
			'priority' => 10,
		) );

		if ( class_exists( 'Hestia_Repeater' ) ) {
			$wp_customize->add_setting( 'hestia_features_content', array(
				'sanitize_callback' => 'hestia_repeater_sanitize',
			) );

			$wp_customize->add_control( new Hestia_Repeater( $wp_customize, 'hestia_features_content', array(
				'label'                             => esc_html__( 'Features Content', 'hestia-companion', 'themeisle-companion' ),
				'section'                           => 'hestia_features',
				'priority'                          => 15,
				'add_field_label'                   => esc_html__( 'Add new Feature', 'hestia-companion', 'themeisle-companion' ),
				'item_name'                         => esc_html__( 'Feature', 'hestia-companion', 'themeisle-companion' ),
				'customizer_repeater_icon_control'  => true,
				'customizer_repeater_title_control' => true,
				'customizer_repeater_text_control'  => true,
				'customizer_repeater_link_control'  => true,
				'customizer_repeater_color_control' => true,
			) ) );
		}

	}

	add_action( 'customize_register', 'hestia_features_customize_register' );

endif;
