<?php
/**
 * Clients bar section for the homepage.
 *
 * @package Hestia
 * @since Hestia 1.1.47
 */

if ( ! function_exists( 'hestia_clients_bar' ) ) :

	/**
	 * Clients bar section content.
	 *
	 * @since Hestia 1.1.47
	 */
	function hestia_clients_bar() {
		$hestia_clients_bar_hide = get_theme_mod( 'hestia_clients_bar_hide', true );
		$hestia_clients_bar_content = get_theme_mod( 'hestia_clients_bar_content' );
		if ( (bool) $hestia_clients_bar_hide === true || empty( $hestia_clients_bar_content ) ) {
			return;
		}

		$hestia_clients_bar_content_decoded = json_decode( $hestia_clients_bar_content );
		if ( empty( $hestia_clients_bar_content_decoded ) ) {
			return;
		}
		?>
		<section class="hestia-clients-bar text-center">
			<div class="container">
				<div class="row">
					<?php
					$i = 1;
					$array_length = sizeof( $hestia_clients_bar_content_decoded );
					foreach ( $hestia_clients_bar_content_decoded as $client ) {
						$image = ! empty( $client->image_url ) ? apply_filters( 'hestia_translate_single_string', $client->image_url, 'Clients bar section' ) : '';
						$link = ! empty( $client->link ) ? apply_filters( 'hestia_translate_single_string', $client->link, 'Clients bar section' ) : '';

						$image_id = function_exists( 'attachment_url_to_postid' ) ? attachment_url_to_postid( preg_replace( '/-\d{1,4}x\d{1,4}/i', '', $image ) ) : '';
						$alt_text = '';
						if ( ! empty( $image_id ) ) {
							$alt_text  = 'alt="' . get_post_meta( $image_id, '_wp_attachment_image_alt', true ) . '"';
						}

						if ( ! empty( $image ) ) {
							echo '<div class="col-md-3">';
							if ( ! empty( $link ) ) {
								echo '<a href="' . esc_url( $link ) . '">';
							}
							echo '<img src="' . esc_url( $image ) . '" ' . wp_kses_post( $alt_text ) . '>';
							if ( ! empty( $link ) ) {
								echo '</a>';
							}
							echo '</div>';
						}

						if ( $i % 4 == 0 && $i !== $array_length ) {
							echo '</div><!-- /.row -->';
							echo '<div class="row">';
						}
						$i++;
					}
					?>
				</div>
			</div>
		</section>
		<?php
	}

endif;
if ( function_exists( 'hestia_clients_bar' ) ) {
	$section_priority = apply_filters( 'hestia_section_priority', 50, 'hestia_clients_bar' );
	add_action( 'hestia_sections', 'hestia_clients_bar', absint( $section_priority ) );
	if ( function_exists( 'hestia_features_register_strings' ) ) {
		add_action( 'after_setup_theme', 'hestia_features_register_strings', 11 );
	}
}
