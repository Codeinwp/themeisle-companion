<?php
/**
 * Services section for the homepage.
 *
 * @package Hestia
 * @since Hestia 1.0
 */

if ( ! function_exists( 'hestia_features' ) ) :
	/**
	 * Features section content.
	 *
	 * @since Hestia 1.0
	 * @modified 1.1.34
	 */
	function hestia_features( $is_shortcode = false ) {
		$hide_section                 = get_theme_mod( 'hestia_features_hide', false );

		$default_title = false;
		$default_subtitle = false;
		$default_content = false;

		if ( current_user_can( 'edit_theme_options' ) ) {
			$default_title = esc_html__( 'Why our product is the best', 'themeisle-companion' );
			$default_subtitle = esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'themeisle-companion' );
			$default_content = hestia_get_features_default();
		}

		$hestia_features_title    = get_theme_mod( 'hestia_features_title', $default_title );
		$hestia_features_subtitle = get_theme_mod( 'hestia_features_subtitle', $default_subtitle );
		if ( $is_shortcode ) {
			$hestia_features_title = '';
			$hestia_features_subtitle = '';
		}
		$hestia_features_content  = get_theme_mod( 'hestia_features_content', $default_content );

		if ( ! $is_shortcode && ( is_front_page() && ( (bool) $hide_section === true ) ) || ((empty( $hestia_features_title )) && (empty( $hestia_features_subtitle )) && (empty( $hestia_features_content ))) ) {
			return;
		}

		$class_to_add = 'container';
		if ( $is_shortcode ) {
			$class_to_add = '';
		}

		hestia_before_features_section_trigger();
		?>
        <section class="features hestia-features" id="features" data-sorder="hestia_features">
			<?php hestia_before_features_section_content_trigger(); ?>
            <div class="<?php echo esc_attr( $class_to_add ); ?>">
				<?php hestia_top_features_section_content_trigger(); ?>
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
						<?php if ( ! empty( $hestia_features_title ) || is_customize_preview() ) : ?>
                            <h2 class="hestia-title"><?php echo esc_html( $hestia_features_title ); ?></h2>
						<?php endif; ?>
						<?php if ( ! empty( $hestia_features_subtitle ) || is_customize_preview() ) : ?>
                            <h5 class="description"><?php echo esc_html( $hestia_features_subtitle ); ?></h5>
						<?php endif; ?>
                    </div>
                </div>
				<?php hestia_features_content( $hestia_features_content ); ?>
				<?php hestia_bottom_features_section_content_trigger(); ?>
            </div>
			<?php hestia_after_features_section_content_trigger(); ?>
        </section>
		<?php
		hestia_after_features_section_trigger();
	}

endif;

/**
 * Get content for features section.
 *
 * @since 1.1.31
 * @access public
 * @param string $hestia_features_content Section content in json format.
 * @param bool   $is_callback Flag to check if it's callback or not.
 */
function hestia_features_content( $hestia_features_content, $is_callback = false ) {
	if ( ! $is_callback ) {
		?>
        <div class="hestia-features-content">
		<?php
	}
	if ( ! empty( $hestia_features_content ) ) :

		$hestia_features_content = json_decode( $hestia_features_content );
		if ( ! empty( $hestia_features_content ) ) {
			$i = 1;
			echo '<div class="row">';
			foreach ( $hestia_features_content as $features_item ) :
				$icon = ! empty( $features_item->icon_value ) ? apply_filters( 'hestia_translate_single_string', $features_item->icon_value, 'Features section' ) : '';
				$image = ! empty( $features_item->image_url ) ? apply_filters( 'hestia_translate_single_string', $features_item->image_url, 'Features section' ) : '';
				$title = ! empty( $features_item->title ) ? apply_filters( 'hestia_translate_single_string', $features_item->title, 'Features section' ) : '';
				$text = ! empty( $features_item->text ) ? apply_filters( 'hestia_translate_single_string', $features_item->text, 'Features section' ) : '';
				$link = ! empty( $features_item->link ) ? apply_filters( 'hestia_translate_single_string', $features_item->link, 'Features section' ) : '';
				$color = ! empty( $features_item->color ) ? $features_item->color : '';
				$choice = ! empty( $features_item->choice ) ? $features_item->choice : 'customizer_repeater_icon';
				?>
                <div class="col-md-4 feature-box">
                    <div class="info hestia-info">
						<?php if ( ! empty( $link ) ) : ?>
                        <a href="<?php echo esc_url( $link ); ?>">
							<?php
							endif;

							switch ( $choice ) {
								case 'customizer_repeater_image':
									if ( ! empty( $image ) ) {
										?>
                                        <div class="card card-plain">
                                            <img src="<?php echo esc_url( $image ); ?>"/>
                                        </div>
										<?php
									}
									break;
								case 'customizer_repeater_icon':
									if ( ! empty( $icon ) ) {
										?>
                                        <div class="icon" <?php echo ( ! empty( $color ) ? 'style="color:' . $color . '"' : '' ); ?>>
                                            <i class="fa <?php echo esc_html( $icon ); ?>"></i>
                                        </div>
										<?php
									}
									break;
							}
							?>
							<?php if ( ! empty( $title ) ) : ?>
                                <h4 class="info-title"><?php echo esc_html( $title ); ?></h4>
							<?php endif; ?>
							<?php if ( ! empty( $link ) ) : ?>
                        </a>
					<?php endif; ?>
						<?php if ( ! empty( $text ) ) : ?>
                            <p><?php echo wp_kses_post( html_entity_decode( $text ) ); ?></p>
						<?php endif; ?>
                    </div>
                </div>
				<?php
				if ( $i % 3 == 0 ) {
					echo '</div><!-- /.row -->';
					echo '<div class="row">';
				}
				$i++;

			endforeach;
			echo '</div>';
		}// End if().
	endif;
	if ( ! $is_callback ) {
		?>
        </div>
		<?php
	}
}

/**
 * Get default values for features section.
 *
 * @since 1.1.31
 * @access public
 */
function hestia_get_features_default() {
	return apply_filters(
		'hestia_features_default_content', json_encode(
			array(
				array(
					'icon_value' => 'fa-wechat',
					'title'      => esc_html__( 'Responsive', 'themeisle-companion' ),
					'text'       => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'themeisle-companion' ),
					'link'       => '#',
					'color'      => '#e91e63',
				),
				array(
					'icon_value' => 'fa-check',
					'title'      => esc_html__( 'Quality', 'themeisle-companion' ),
					'text'       => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'themeisle-companion' ),
					'link'       => '#',
					'color'      => '#00bcd4',
				),
				array(
					'icon_value' => 'fa-support',
					'title'      => esc_html__( 'Support', 'themeisle-companion' ),
					'text'       => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'themeisle-companion' ),
					'link'       => '#',
					'color'      => '#4caf50',
				),
			)
		)
	);
}

if ( function_exists( 'hestia_features' ) ) {
	$section_priority = apply_filters( 'hestia_section_priority', 10, 'hestia_features' );
	add_action( 'hestia_sections', 'hestia_features', absint( $section_priority ) );
	if ( function_exists( 'hestia_features_register_strings' ) ) {
		add_action( 'after_setup_theme', 'hestia_features_register_strings', 11 );
	}
}
