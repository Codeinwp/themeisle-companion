<?php
/**
 * Ribbon section for the homepage.
 *
 * @package Hestia
 * @since Hestia 1.1.47
 */

if ( ! function_exists( 'hestia_ribbon' ) ) :

	/**
	 * Ribbon section content.
	 * This function can be called from a shortcode too.
	 * When it's called as shortcode, the title and the subtitle shouldn't appear and it should be visible all the time,
	 * it shouldn't matter if is disable on front page.
	 *
	 * @since 1.1.47
	 * @modified 1.1.51
	 */
	function hestia_ribbon( $is_shortcode = false ) {

		// When this function is called from selective refresh, $is_shortcode gets the value of WP_Customize_Selective_Refresh object. We don't need that.
		if ( ! is_bool( $is_shortcode ) ) {
			$is_shortcode = false;
		}

		/* Don't show section if Disable section is checked or it doesn't have any content. Show it if it's called as a shortcode */
		$hestia_ribbon_hide = get_theme_mod( 'hestia_ribbon_hide', true );
		if ( $is_shortcode === false && (bool) $hestia_ribbon_hide === true ) {
			if ( is_customize_preview() ) {
				echo '<section class="hestia-ribbon section section-image" data-sorder="hestia_ribbon" style="display: none"></section>';
			}
			return;
		}
		$default            = ( current_user_can( 'edit_theme_options' ) ? esc_html__( 'Subscribe to our Newsletter', 'themeisle-companion' ) : false );
		$hestia_ribbon_text = get_theme_mod( 'hestia_ribbon_text', $default );

		$default                   = ( current_user_can( 'edit_theme_options' ) ? esc_html__( 'Subscribe', 'themeisle-companion' ) : false );
		$hestia_ribbon_button_text = get_theme_mod( 'hestia_ribbon_button_text', $default );

		$default                  = ( current_user_can( 'edit_theme_options' ) ? '#' : false );
		$hestia_ribbon_button_url = get_theme_mod( 'hestia_ribbon_button_url', $default );

		$wrapper_class = $is_shortcode === true ? 'is-shortcode' : '';
		?>
		<section class="hestia-ribbon section section-image <?php echo esc_attr( $wrapper_class ); ?>" data-sorder="hestia_ribbon">
			<?php hestia_ribbon_background(); ?>
			<div class="container">
				<div class="row hestia-xs-text-center hestia-like-table">
					<div class="col-md-8">
						<?php if ( ! empty( $hestia_ribbon_text ) ) { ?>
							<h2 class="hestia-title" style="margin:0;">
								<?php echo wp_kses_post( $hestia_ribbon_text ); ?>
							</h2>
							<?php
}
?>
					</div>
					<div class="col-md-4 text-center">
						<?php

						if ( ! empty( $hestia_ribbon_button_text ) && ! empty( $hestia_ribbon_button_url ) ) {

							$link_html = '<a href="' . esc_url( $hestia_ribbon_button_url ) . '"';
							if ( function_exists( 'hestia_is_external_url' ) ) {
								$link_html .= hestia_is_external_url( $hestia_ribbon_button_url );
							}
							$link_html .= ' class="btn btn-md btn-primary hestia-subscribe-button">';
							$link_html .= wp_kses_post( $hestia_ribbon_button_text );
							$link_html .= '</a>';
							echo wp_kses_post( $link_html );
						}
						?>
					</div>
				</div>
			</div>
		</section>
		<?php
	}
endif;

/**
 * Callback function for ribbon background selective refresh.
 *
 * @since 1.1.47
 */
function hestia_ribbon_background() {
	$default                  = ( current_user_can( 'edit_theme_options' ) ? get_template_directory_uri() . '/assets/img/contact.jpg' : false );
	$hestia_ribbon_background = get_theme_mod( 'hestia_ribbon_background', $default );
	?>
	<div class="hestia-ribbon-style">
		<style>
			<?php
			if ( ! empty( $hestia_ribbon_background ) ) {
				echo '.hestia-ribbon{ background-image: url(\'' . esc_url( $hestia_ribbon_background ) . '\'); }';
			}
			?>
		</style>
	</div>
	<?php
}

if ( function_exists( 'hestia_ribbon' ) ) {
	$section_priority = apply_filters( 'hestia_section_priority', 35, 'hestia_ribbon' );
	add_action( 'hestia_sections', 'hestia_ribbon', absint( $section_priority ) );
}
