<?php
/**
 * The View for Rendering the Template Directory Main Dashboard Page.
 *
 * @link       https://themeisle.com
 * @since      2.0.0
 *
 * @package    Orbit_Fox_Modules
 * @subpackage Orbit_Fox_Modules/template-directory
 * @codeCoverageIgnore
 */

$preview_url = add_query_arg( 'obfx_templates', '', home_url() ); // Define query arg for custom endpoint.

$html = '';

if ( is_array( $templates_array ) ) {
	$html .= '<div class="obfx-template-dir wrap">';
	$html .= '<h1 class="wp-heading-inline">' . __( 'Orbit Fox Template Directory', 'themeisle-companion' ) . '</h1>';
	$html .= '<div class="obfx-template-browser">';

	foreach ( $templates_array as $template => $properties ) {
		$admin_url      = admin_url() . 'customize.php';
		$customizer_url = add_query_arg(
			array(
				'url'              => urlencode( $preview_url ),
				'return'           => admin_url() . 'admin.php?page=obfx_template_dir',
				'obfx_template_id' => esc_html( $template ),
			), $admin_url
		);

		$html .= '<div class="obfx-template">';
		$html .= '<h2 class="template-name template-header">' . esc_html( $properties['title'] ) . '</h2>';
		$html .= '<div class="obfx-template-screenshot">';
		$html .= '<img src="' . esc_url( $properties['screenshot'] ) . '" alt="' . esc_html( $properties['title'] ) . '" >';
		$html .= '</div>'; // .obfx-template-screenshot

		$html .= '<div class="obfx-template-actions">';
		if ( ! empty( $properties['demo_url'] ) ) {
			$html .= '<a class="button obfx-preview-template" href="' . esc_url( $customizer_url ) . '" >' . __( 'Preview', 'themeisle-companion' ) . '</a>';
		}

		if ( ! empty( $properties['import_file'] ) ) {
			$html .= '<a class="button button-primary obfx-import-template" data-template-file="' . esc_url( $properties['import_file'] ) . '"> ' . __( 'Import', 'themeisle-companion' ) . '</a>';
		}
		$html .= '</div>'; // .obfx-template-actions
		$html .= '</div>'; // .obfx-template
	}
	$html .= '</div>'; // .obfx-template-browser
	$html .= '</div>'; // .obfx-template-dir
	$html .= '<div class="wp-clearfix clearfix"></div>';
	if ( ! defined( 'ELEMENTOR_VERSION' ) ) {
		$html .= $requires_plugins;
	}
}// End if().

echo $html;
