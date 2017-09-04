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
	 *
	 * @since 1.1.47
	 */
	function hestia_ribbon() {

		$hestia_ribbon_hide = get_theme_mod( 'hestia_ribbon_hide', true );
		if ( (bool) $hestia_ribbon_hide === true ) {
			return;
		}
		$default            = ( current_user_can( 'edit_theme_options' ) ? esc_html__( 'Subscribe to our Newsletter', 'themeisle-companion', 'themeisle-companion' ) : false );
		$hestia_ribbon_text = get_theme_mod( 'hestia_ribbon_text', $default );

		$default                   = ( current_user_can( 'edit_theme_options' ) ? esc_html__( 'Subscribe', 'themeisle-companion' ) : false );
		$hestia_ribbon_button_text = get_theme_mod( 'hestia_ribbon_button_text', $default );

		$default                  = ( current_user_can( 'edit_theme_options' ) ? '#' : false );
		$hestia_ribbon_button_url = get_theme_mod( 'hestia_ribbon_button_url', $default );
		?>
		<section class="hestia-ribbon section section-image" >
			<?php hestia_ribbon_background(); ?>
			<div class="container">
				<div class="row xs-text-center like-table">
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
						<?php if ( ! empty( $hestia_ribbon_button_text ) && ! empty( $hestia_ribbon_button_url ) ) { ?>
							<a href="<?php esc_url( $hestia_ribbon_button_url ); ?>" class="btn btn-md btn-primary hestia-subscribe-button">
								<?php echo wp_kses_post( $hestia_ribbon_button_text ); ?>
							</a>
							<?php
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
