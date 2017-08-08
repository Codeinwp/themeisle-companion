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
	 * @modified 1.1.30
	 */
	function hestia_features() {

		$show_features_single_product = get_theme_mod( 'hestia_features_show_on_single_product', false );
		$hide_section                 = get_theme_mod( 'hestia_features_hide', false );

		$default_title = false;
		$default_subtitle = false;
		$default_content = false;

		if ( current_user_can( 'edit_theme_options' ) ) {
			$default_title = __( 'Why our product is the best', 'themeisle-companion' );
			$default_subtitle = __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'themeisle-companion' );
			$default_content = hestia_get_features_default();
		}

		$hestia_features_title    = get_theme_mod( 'hestia_features_title', $default_title );
		$hestia_features_subtitle = get_theme_mod( 'hestia_features_subtitle', $default_subtitle );
		$hestia_features_content  = get_theme_mod( 'hestia_features_content', $default_content );

		if ( ( ( is_single() && ( (bool) $show_features_single_product === false ) ) || ( is_front_page() && ( (bool) $hide_section === true ) ) ) || ( ( empty( $hestia_features_title ) ) && ( empty( $hestia_features_subtitle ) ) && ( empty( $hestia_features_content ) ) ) ) {
			return;
		}
		?>
		<section class="features hestia-features" id="features" data-sorder="hestia_features">
			<div class="container">
				<div class="row">
					<div class="col-md-8 col-md-offset-2">
						<?php if ( ! empty( $hestia_features_title ) || is_customize_preview() ) : ?>
							<h2 class="title"><?php echo esc_html( $hestia_features_title ); ?></h2>
						<?php endif; ?>
						<?php if ( ! empty( $hestia_features_subtitle ) || is_customize_preview() ) : ?>
							<h5 class="description"><?php echo esc_html( $hestia_features_subtitle ); ?></h5>
						<?php endif; ?>
					</div>
				</div>
				<?php hestia_features_content( $hestia_features_content ); ?>
			</div>
		</section>
		<?php
	}

endif;

if ( ! function_exists( 'hestia_features_register_strings' ) ) {
	/**
	 * Register polylang strings
	 *
	 * @modified 1.1.30
	 * @access public
	 */
	function hestia_features_register_strings() {
		$default = hestia_get_features_default();
		hestia_pll_string_register_helper( 'hestia_features_content', $default, 'Features section' );
	}
}

/**
 * Get content for features section.
 *
 * @since 1.1.30
 * @access public
 * @param string $hestia_features_content Section content in json format.
 * @param bool   $is_callback Flag to check if it's callback or not.
 */
function hestia_features_content( $hestia_features_content, $is_callback = false ) {
	if ( ! $is_callback ) { ?>
		<div class="row hestia-features-content">
	    <?php
	}
	if ( ! empty( $hestia_features_content ) ) :
		$hestia_features_content = json_decode( $hestia_features_content );
		foreach ( $hestia_features_content as $features_item ) :
			$icon = ! empty( $features_item->icon_value ) ? apply_filters( 'hestia_translate_single_string', $features_item->icon_value, 'Features section' ) : '';
			$title = ! empty( $features_item->title ) ? apply_filters( 'hestia_translate_single_string', $features_item->title, 'Features section' ) : '';
			$text = ! empty( $features_item->text ) ? apply_filters( 'hestia_translate_single_string', $features_item->text, 'Features section' ) : '';
			$link = ! empty( $features_item->link ) ? apply_filters( 'hestia_translate_single_string', $features_item->link, 'Features section' ) : '';
			$color = ! empty( $features_item->color ) ? $features_item->color : '';
			
			?>
			<div class="col-md-4 feature-box">
			<div class="info hestia-info">
			<?php if ( ! empty( $link ) ) : ?>
						<a href="<?php echo esc_url( $link ); ?>">
							<?php endif; ?>
				<?php if ( ! empty( $icon ) ) : ?>
								<div class="icon" <?php if ( ! empty( $color ) ) { echo 'style="color:' . $color . '"'; } ?>>
									<i class="fa <?php echo esc_html( $icon ); ?>"></i>
								</div>
							<?php endif; ?>
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
			endforeach;
		endif;
	if ( ! $is_callback ) { ?>
		</div>
		<?php
	}
}

/**
 * Get default values for features section.
 *
 * @since 1.1.30
 * @access public
 */
function hestia_get_features_default() {
	return apply_filters( 'hestia_features_default_content', json_encode( array(
		array(
			'icon_value' => 'fa-wechat',
			'title'      => __( 'Responsive', 'themeisle-companion' ),
			'text'       => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'themeisle-companion' ),
			'link'       => '#',
			'color'      => '#e91e63',
		),
		array(
			'icon_value' => 'fa-check',
			'title'      => __( 'Quality', 'themeisle-companion' ),
			'text'       => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'themeisle-companion' ),
			'link'       => '#',
			'color'      => '#00bcd4',
		),
		array(
			'icon_value' => 'fa-support',
			'title'      => __( 'Support', 'themeisle-companion' ),
			'text'       => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'themeisle-companion' ),
			'link'       => '#',
			'color'      => '#4caf50',
		),
	) ) );
}

if ( function_exists( 'hestia_features' ) ) {
	$section_priority = apply_filters( 'hestia_section_priority', 10, 'hestia_features' );
	add_action( 'hestia_sections', 'hestia_features', absint( $section_priority ) );
	add_action( 'after_setup_theme', 'hestia_features_register_strings', 11 );
}
