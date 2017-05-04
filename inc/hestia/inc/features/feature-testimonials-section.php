<?php
/**
 * Customizer functionality for the Testimonials section.
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

if ( ! function_exists( 'hestia_testimonials_customize_register' ) ) :
	/**
	 * Hook controls for Testimonials section to Customizer.
	 *
	 * @since Hestia 1.0
	 */
	function hestia_testimonials_customize_register( $wp_customize ) {

		$wp_customize->add_section( 'hestia_testimonials', array(
			'title'    => esc_html__( 'Testimonials', 'hestia-companion', 'themeisle-companion' ),
			'panel'    => 'hestia_frontpage_sections',
			'priority' => apply_filters( 'hestia_section_priority', 40, 'hestia_testimonials' ),
		) );

		$wp_customize->add_setting( 'hestia_testimonials_hide', array(
			'sanitize_callback' => 'hestia_sanitize_checkbox',
			'default'           => false,
		) );

		$wp_customize->add_control( 'hestia_testimonials_hide', array(
			'type'     => 'checkbox',
			'label'    => esc_html__( 'Disable section', 'hestia-companion', 'themeisle-companion' ),
			'section'  => 'hestia_testimonials',
			'priority' => 1,
		) );

		$wp_customize->add_setting( 'hestia_testimonials_title', array(
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );

		$wp_customize->add_control( 'hestia_testimonials_title', array(
			'label'    => esc_html__( 'Section Title', 'hestia-companion', 'themeisle-companion' ),
			'section'  => 'hestia_testimonials',
			'priority' => 5,
		) );

		$wp_customize->add_setting( 'hestia_testimonials_subtitle', array(
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );

		$wp_customize->add_control( 'hestia_testimonials_subtitle', array(
			'label'    => esc_html__( 'Section Subtitle', 'hestia-companion', 'themeisle-companion' ),
			'section'  => 'hestia_testimonials',
			'priority' => 10,
		) );

		if ( class_exists( 'Hestia_Repeater' ) ) {
			$wp_customize->add_setting( 'hestia_testimonials_content', array(
				'sanitize_callback' => 'hestia_repeater_sanitize',
			) );

			$wp_customize->add_control( new Hestia_Repeater( $wp_customize, 'hestia_testimonials_content', array(
				'label'                                => esc_html__( 'Testimonials Content', 'hestia-companion', 'themeisle-companion' ),
				'section'                              => 'hestia_testimonials',
				'priority'                             => 15,
				'add_field_label'                      => esc_html__( 'Add new Testimonial', 'hestia-companion', 'themeisle-companion' ),
				'item_name'                            => esc_html__( 'Testimonial', 'hestia-companion', 'themeisle-companion' ),
				'customizer_repeater_image_control'    => true,
				'customizer_repeater_title_control'    => true,
				'customizer_repeater_subtitle_control' => true,
				'customizer_repeater_text_control'     => true,
				'customizer_repeater_link_control'     => true,
			) ) );
		}
	}

	add_action( 'customize_register', 'hestia_testimonials_customize_register' );

endif;
