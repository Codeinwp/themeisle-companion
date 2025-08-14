<?php

/**
 * The Front End View for Social Sharing Module of Orbit Fox.
 *
 * @link       https://themeisle.com
 * @since      1.0.0
 *
 * @package    Orbit_Fox_Modules
 * @subpackage Orbit_Fox_Modules/stats/social-sharing
 * @codeCoverageIgnore
 */

if ( ! empty( $social_links_array ) ) { ?>
	<ul class="obfx-sharing
	<?php

	if ( ! empty( $desktop_class ) ) {
		echo esc_attr( $desktop_class );
	}
	if ( ! empty( $mobile_class ) ) {
		echo esc_attr( $mobile_class );
	}

	$icons = new OBFX_Social_Icons();


	?>
	">
		<?php
		foreach ( $social_links_array as $slug => $network_data ) {
			$icon = $icons->get_icon( $network_data['icon'] );

			if ( empty( $icon ) ) {
				continue;
			}

			$class = '';
			// phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison
			if ( $network_data['show_desktop'] == '0' ) {
				$class .= 'obfx-hide-desktop-socials ';
			}
			// phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison
			if ( $network_data['show_mobile'] == '0' ) {
				$class .= 'obfx-hide-mobile-socials ';
			}
			?>
			<li class="<?php echo esc_attr( $class ); ?>">
				<a class="<?php echo esc_attr( $network_data['icon'] ); ?>"
					aria-label="<?php echo esc_attr( $network_data['nicename'] ); ?>"
					<?php
					// phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison
					echo ( isset( $network_data['target'] ) && $network_data['target'] != '0' ) ? 'target="_blank"' : '';
					?>
					href="<?php echo esc_url( $network_data['link'] ); ?>">

					<?php

					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo OBFX_Social_Icons::sanitize_icon_svg( $icon );

					if ( $show_name ) {
						echo '<span>' . esc_html( $network_data['nicename'] ) . '</span>';
					}
					?>
				</a>
			</li>
		<?php } ?>
	</ul>
	<?php
}// End if().
