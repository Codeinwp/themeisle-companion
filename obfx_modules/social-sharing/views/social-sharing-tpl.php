<?php
/**
 * The Dashboard Widget View for Stats Module of Orbit Fox.
 *
 * @link       https://themeisle.com
 * @since      1.0.0
 *
 * @package    Orbit_Fox_Modules
 * @subpackage Orbit_Fox_Modules/stats/views
 * @codeCoverageIgnore
 */

if ( ! empty( $social_links ) ) { ?>
	<ul class="obfx-core-social-sharing-icons
	<?php if ( ! empty( $position_class ) ) {
		echo esc_attr( $position_class );
} ?>">
	<?php foreach ( $social_links as $network => $link ) { ?>
		<li>
			<a target= "_blank"
			   href="<?php echo esc_url( $link['link'] ); ?>">
				<i class="socicon- fa fa-<?php echo esc_attr( $link['icon_class'] ); ?>"></i>
				<?php if ( $show_name ) {
					echo '<span>' . esc_html( $network ) . '</span>';
} ?>
			</a>
		</li>
	<?php }?>
	</ul>
<?php }
