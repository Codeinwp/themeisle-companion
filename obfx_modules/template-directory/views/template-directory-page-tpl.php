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
		$html .= '<div class="obfx-template">';
		$html .= '<div class="more-details obfx-preview-template" data-demo-url="' . esc_url( $properties['demo_url'] ) . '" data-template-slug="' . esc_attr( $template ) . '" ><span>' . __( 'More Details', 'themeisle-companion' ) . '</span></div>';
		$html .= '<div class="obfx-template-screenshot">';
		$html .= '<img src="' . esc_url( $properties['screenshot'] ) . '" alt="' . esc_html( $properties['title'] ) . '" >';
		$html .= '</div>'; // .obfx-template-screenshot
		$html .= '<h2 class="template-name template-header">' . esc_html( $properties['title'] ) . '</h2>';
		$html .= '<div class="obfx-template-actions">';

		if ( ! empty( $properties['demo_url'] ) ) {
			$html .= '<a class="button obfx-preview-template" data-demo-url="' . esc_url( $properties['demo_url'] ) . '" data-template-slug="' . esc_attr( $template ) . '" >' . __( 'Preview', 'themeisle-companion' ) . '</a>';
		}
		$html .= '</div>'; // .obfx-template-actions
		$html .= '</div>'; // .obfx-template
	}
	$html .= '</div>'; // .obfx-template-browser
	$html .= '</div>'; // .obfx-template-dir
	$html .= '<div class="wp-clearfix clearfix"></div>';
}// End if().

echo $html;
?>

<div class="obfx-template-preview theme-install-overlay wp-full-overlay expanded" style="display: none;">
	<div class="wp-full-overlay-sidebar">
		<div class="wp-full-overlay-header">
			<button class="close-full-overlay"><span class="screen-reader-text">Close</span></button>
			<div class="obfx-next-prev">
				<button class="previous-theme"><span class="screen-reader-text">Previous</span></button>
				<button class="next-theme"><span class="screen-reader-text">Next</span></button>
			</div>
			<span class="obfx-import-template button button-primary">Import</span>
		</div>
		<div class="wp-full-overlay-sidebar-content">
			<?php
			foreach ( $templates_array as $template => $properties ) {
				?>
				<div class="install-theme-info obfx-theme-info <?php echo esc_attr( $template ); ?>"
					 data-demo-url="<?php echo esc_url( $properties['demo_url'] ); ?>"
					 data-template-file="<?php echo esc_url( $properties['import_file'] ); ?>"
					 data-template-title="<?php echo esc_html( $properties['title'] ); ?>">
					<h3 class="theme-name"><?php echo esc_html( $properties['title'] ); ?></h3>
					<img class="theme-screenshot" src="<?php echo esc_url( $properties['screenshot'] ); ?>"
						 alt="<?php echo esc_html( $properties['title'] ); ?>">
					<div class="theme-details">
						<?php echo esc_html( $properties['description'] ); ?>
					</div>
					<?php
					if ( ! empty( $properties['required_plugins'] ) && is_array( $properties['required_plugins'] ) ) {
					?>
					<div class="obfx-required-plugins">
						<p>Required Plugins</p>
						<?php
						foreach ( $properties['required_plugins'] as $plugin_slug => $details ) {
							if ( $this->check_plugin_state( $plugin_slug ) === 'install' ) {
								echo '<div class="obfx-installable plugin-card-' . esc_attr( $plugin_slug ) . '">';
								echo '<span class="dashicons dashicons-no-alt"></span>';
								echo $details['title'];
								echo $this->get_button_html( $plugin_slug );
								echo '</div>';
							} elseif ( $this->check_plugin_state( $plugin_slug ) === 'activate' ) {
								echo '<div class="obfx-activate plugin-card-' . esc_attr( $plugin_slug ) . '">';
								echo '<span class="dashicons dashicons-admin-plugins" style="color: #ffb227;"></span>';
								echo $details['title'];
								echo $this->get_button_html( $plugin_slug );
								echo '</div>';
							} else {
								echo '<div class="obfx-installed plugin-card-' . esc_attr( $plugin_slug ) . '">';
								echo '<span class="dashicons dashicons-yes" style="color: #34a85e"></span>';
								echo $details['title'];
								echo '</div>';
							}
						}
						?>
					</div>
					<?php
					}
					?>
				</div><!-- /.install-theme-info -->
			<?php } ?>
		</div>

		<div class="wp-full-overlay-footer">
			<button type="button" class="collapse-sidebar button" aria-expanded="true" aria-label="Collapse Sidebar">
				<span class="collapse-sidebar-arrow"></span>
				<span class="collapse-sidebar-label"><?php echo __( 'Collapse', 'themeisle-companion' ); ?></span>
			</button>
			<div class="devices-wrapper">
				<div class="devices obfx-responsive-preview">
					<button type="button" class="preview-desktop active" aria-pressed="true" data-device="desktop">
						<span class="screen-reader-text">Enter desktop preview mode</span>
					</button>
					<button type="button" class="preview-tablet" aria-pressed="false" data-device="tablet">
						<span class="screen-reader-text">Enter tablet preview mode</span>
					</button>
					<button type="button" class="preview-mobile" aria-pressed="false" data-device="mobile">
						<span class="screen-reader-text">Enter mobile preview mode</span>
					</button>
				</div>
			</div>

		</div>
	</div>
	<div class="wp-full-overlay-main obfx-main-preview">
		<iframe src="" title="Preview" class="obfx-template-frame"></iframe>
	</div>
</div>
