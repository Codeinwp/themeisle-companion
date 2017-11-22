<?php
/**
 * The View for Rendering the Plugin install modal.
 *
 * @link       https://themeisle.com
 * @since      2.0.0
 *
 * @package    Orbit_Fox_Modules
 * @subpackage Orbit_Fox_Modules/template-directory
 * @codeCoverageIgnore
 */

if ( ( $this->check_plugin_state( 'elementor' ) === 'activate' ) || ( $this->check_plugin_state( 'elementor' ) === 'install' ) ) {
	$html = '';
	$button = $this->get_button_html( 'elementor' );
	$html  .= '<div class="obfx-no-elementor-modal-wrapper"><div class="obfx-no-elementor-modal plugin-card-elementor">';
	$html  .= '<div class="modal-header"><span class="obfx-close-modal"><i class="dashicons dashicons-no"></i></span></div>';
	$html  .= '<p>' . __( 'In order to import this template, you must have Elementor Page Builder installed. Click the button below to install and activate now.', 'themeisle-companion' ) . '</p>';
	$html  .= $button;
	$html  .= '</div>';

	echo $html;
}
