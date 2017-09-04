<?php
/**
 * Team section for the homepage.
 *
 * @package Hestia
 * @since Hestia 1.0
 */

if ( ! function_exists( 'hestia_team' ) ) :
	/**
	 * Team section content.
	 *
	 * @since Hestia 1.0
	 * @modified 1.1.34
	 */
	function hestia_team( $is_shortcode = false ) {

		$default_title = false;
		$default_subtitle = false;
		$default_content = false;

		if ( current_user_can( 'edit_theme_options' ) ) {

			$default_title = esc_html__( 'Meet our team', 'themeisle-companion' );
			$default_subtitle = esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'themeisle-companion' );
			$default_content = hestia_get_team_default();
		}

		$hestia_team_title    = get_theme_mod( 'hestia_team_title', $default_title );
		$hestia_team_subtitle = get_theme_mod( 'hestia_team_subtitle', $default_subtitle );
		if ( $is_shortcode ) {
			$hestia_team_title = '';
			$hestia_team_subtitle = '';
		}
		$hestia_team_content  = get_theme_mod( 'hestia_team_content', $default_content );

		$hide_section = get_theme_mod( 'hestia_team_hide', false );
		if ( ! $is_shortcode && ( (bool) $hide_section === true ) || ( empty( $hestia_team_title ) && empty( $hestia_team_subtitle ) && empty( $hestia_team_content ) ) ) {
			return;
		}

		$class_to_add = 'container';
		if ( $is_shortcode ) {
			$class_to_add = '';
		}
		hestia_before_team_section_trigger();
		?>
        <section class="team hestia-team" id="team" data-sorder="hestia_team">
			<?php hestia_before_team_section_content_trigger(); ?>
            <div class="<?php echo esc_attr( $class_to_add ); ?>">
				<?php hestia_top_team_section_content_trigger(); ?>
                <div class="row">
                    <div class="col-md-8 col-md-offset-2 text-center">
						<?php if ( ! empty( $hestia_team_title ) || is_customize_preview() ) : ?>
                            <h2 class="hestia-title"><?php echo esc_html( $hestia_team_title ); ?></h2>
						<?php endif; ?>
						<?php if ( ! empty( $hestia_team_subtitle ) || is_customize_preview() ) : ?>
                            <h5 class="description"><?php echo esc_html( $hestia_team_subtitle ); ?></h5>
						<?php endif; ?>
                    </div>
                </div>
				<?php hestia_team_content( $hestia_team_content ); ?>
				<?php hestia_bottom_team_section_content_trigger(); ?>
            </div>
			<?php hestia_after_team_section_content_trigger(); ?>
        </section>
		<?php
		hestia_after_team_section_trigger();
	}
endif;


/**
 * Get content for team section.
 *
 * @since 1.1.31
 * @access public
 * @param string $hestia_team_content Section content in json format.
 * @param bool   $is_callback Flag to check if it's callback or not.
 */
function hestia_team_content( $hestia_team_content, $is_callback = false ) {
	if ( ! $is_callback ) {
		?>
        <div class="hestia-team-content">
		<?php
	}
	?>
	<?php
	if ( ! empty( $hestia_team_content ) ) :
		$hestia_team_content = json_decode( $hestia_team_content );

		if ( ! empty( $hestia_team_content ) ) {

			$i = 1;
			echo '<div class="row">';
			foreach ( $hestia_team_content as $team_item ) :
				$image = ! empty( $team_item->image_url ) ? apply_filters( 'hestia_translate_single_string', $team_item->image_url, 'Team section' ) : '';
				$title = ! empty( $team_item->title ) ? apply_filters( 'hestia_translate_single_string', $team_item->title, 'Team section' ) : '';
				$subtitle = ! empty( $team_item->subtitle ) ? apply_filters( 'hestia_translate_single_string', $team_item->subtitle, 'Team section' ) : '';
				$text = ! empty( $team_item->text ) ? apply_filters( 'hestia_translate_single_string', $team_item->text, 'Team section' ) : '';
				$link = ! empty( $team_item->link ) ? apply_filters( 'hestia_translate_single_string', $team_item->link, 'Team section' ) : '';
				?>
                <div class="col-md-6">
                    <div class="card card-profile card-plain">
                        <div class="col-md-5">
                            <div class="card-image">
								<?php if ( ! empty( $image ) ) : ?>
									<?php if ( ! empty( $link ) ) : ?>
                                        <a href="<?php echo esc_url( $link ); ?>">
									<?php endif; ?>
                                    <img class="img"
                                         src="<?php echo esc_url( $image ); ?>"
										<?php
										if ( ! empty( $title ) ) :
											?>
                                            alt="<?php echo esc_attr( $title ); ?>" title="<?php echo esc_attr( $title ); ?>" <?php endif; ?> />
									<?php if ( ! empty( $link ) ) : ?>
                                        </a>
									<?php endif; ?>
								<?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="content">
								<?php if ( ! empty( $title ) ) : ?>
                                    <h4 class="card-title"><?php echo esc_html( $title ); ?></h4>
								<?php endif; ?>
								<?php if ( ! empty( $subtitle ) ) : ?>
                                    <h6 class="category text-muted"><?php echo esc_html( $subtitle ); ?></h6>
								<?php endif; ?>
								<?php if ( ! empty( $text ) ) : ?>
                                    <p class="card-description"><?php echo wp_kses_post( html_entity_decode( $text ) ); ?></p>
								<?php endif; ?>
								<?php
								if ( ! empty( $team_item->social_repeater ) ) :
									$icons = html_entity_decode( $team_item->social_repeater );
									$icons_decoded = json_decode( $icons, true );
									if ( ! empty( $icons_decoded ) ) :
										?>
                                        <div class="footer">
											<?php
											foreach ( $icons_decoded as $value ) :
												$social_icon = ! empty( $value['icon'] ) ? apply_filters( 'hestia_translate_single_string', $value['icon'], 'Team section' ) : '';
												$social_link = ! empty( $value['link'] ) ? apply_filters( 'hestia_translate_single_string', $value['link'], 'Team section' ) : '';
												?>
												<?php if ( ! empty( $social_icon ) ) : ?>
                                                <a href="<?php echo esc_url( $social_link ); ?>"
                                                   class="btn btn-just-icon btn-simple">
                                                    <i class="fa <?php echo esc_attr( $social_icon ); ?>"></i>
                                                </a>
											<?php endif; ?>
											<?php endforeach; ?>
                                        </div>
										<?php
									endif;
								endif;
								?>
                            </div>
                        </div>
                    </div>
                </div>
				<?php
				if ( $i % 2 == 0 ) {
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
function hestia_get_team_default() {
	return apply_filters(
		'hestia_team_default_content', json_encode(
			array(
				array(
					'image_url'       => get_template_directory_uri() . '/assets/img/1.jpg',
					'title'           => esc_html__( 'Desmond Purpleson', 'themeisle-companion' ),
					'subtitle'        => esc_html__( 'CEO', 'themeisle-companion' ),
					'text'            => esc_html__( 'Locavore pinterest chambray affogato art party, forage coloring book typewriter. Bitters cold selfies, retro celiac sartorial mustache.', 'themeisle-companion' ),
					'id'              => 'customizer_repeater_56d7ea7f40c56',
					'social_repeater' => json_encode(
						array(
							array(
								'id'   => 'customizer-repeater-social-repeater-57fb908674e06',
								'link' => 'facebook.com',
								'icon' => 'fa-facebook',
							),
							array(
								'id'   => 'customizer-repeater-social-repeater-57fb9148530ft',
								'link' => 'plus.google.com',
								'icon' => 'fa-google-plus',
							),
							array(
								'id'   => 'customizer-repeater-social-repeater-57fb9148530fc',
								'link' => 'twitter.com',
								'icon' => 'fa-twitter',
							),
							array(
								'id'   => 'customizer-repeater-social-repeater-57fb9150e1e89',
								'link' => 'linkedin.com',
								'icon' => 'fa-linkedin',
							),
						)
					),
				),
				array(
					'image_url'       => get_template_directory_uri() . '/assets/img/2.jpg',
					'title'           => esc_html__( 'Parsley Pepperspray', 'themeisle-companion' ),
					'subtitle'        => esc_html__( 'Marketing Specialist', 'themeisle-companion' ),
					'text'            => esc_html__( 'Craft beer salvia celiac mlkshk. Pinterest celiac tumblr, portland salvia skateboard cliche thundercats. Tattooed chia austin hell.', 'themeisle-companion' ),
					'id'              => 'customizer_repeater_56d7ea7f40c66',
					'social_repeater' => json_encode(
						array(
							array(
								'id'   => 'customizer-repeater-social-repeater-57fb9155a1072',
								'link' => 'facebook.com',
								'icon' => 'fa-facebook',
							),
							array(
								'id'   => 'customizer-repeater-social-repeater-57fb9160ab683',
								'link' => 'twitter.com',
								'icon' => 'fa-twitter',
							),
							array(
								'id'   => 'customizer-repeater-social-repeater-57fb9160ab484',
								'link' => 'pinterest.com',
								'icon' => 'fa-pinterest',
							),
							array(
								'id'   => 'customizer-repeater-social-repeater-57fb916ddffc9',
								'link' => 'linkedin.com',
								'icon' => 'fa-linkedin',
							),
						)
					),
				),
				array(
					'image_url'       => get_template_directory_uri() . '/assets/img/3.jpg',
					'title'           => esc_html__( 'Desmond Eagle', 'themeisle-companion' ),
					'subtitle'        => esc_html__( 'Graphic Designer', 'themeisle-companion' ),
					'text'            => esc_html__( 'Pok pok direct trade godard street art, poutine fam typewriter food truck narwhal kombucha wolf cardigan butcher whatever pickled you.', 'themeisle-companion' ),
					'id'              => 'customizer_repeater_56d7ea7f40c76',
					'social_repeater' => json_encode(
						array(
							array(
								'id'   => 'customizer-repeater-social-repeater-57fb917e4c69e',
								'link' => 'facebook.com',
								'icon' => 'fa-facebook',
							),
							array(
								'id'   => 'customizer-repeater-social-repeater-57fb91830825c',
								'link' => 'twitter.com',
								'icon' => 'fa-twitter',
							),
							array(
								'id'   => 'customizer-repeater-social-repeater-57fb918d65f2e',
								'link' => 'linkedin.com',
								'icon' => 'fa-linkedin',
							),
							array(
								'id'   => 'customizer-repeater-social-repeater-57fb918d65f2x',
								'link' => 'dribbble.com',
								'icon' => 'fa-dribbble',
							),
						)
					),
				),
				array(
					'image_url'       => get_template_directory_uri() . '/assets/img/4.jpg',
					'title'           => esc_html__( 'Ruby Von Rails', 'themeisle-companion' ),
					'subtitle'        => esc_html__( 'Lead Developer', 'themeisle-companion' ),
					'text'            => esc_html__( 'Small batch vexillologist 90\'s blue bottle stumptown bespoke. Pok pok tilde fixie chartreuse, VHS gluten-free selfies wolf hot.', 'themeisle-companion' ),
					'id'              => 'customizer_repeater_56d7ea7f40c86',
					'social_repeater' => json_encode(
						array(
							array(
								'id'   => 'customizer-repeater-social-repeater-57fb925cedcg5',
								'link' => 'github.com',
								'icon' => 'fa-github-square',
							),
							array(
								'id'   => 'customizer-repeater-social-repeater-57fb925cedcb2',
								'link' => 'facebook.com',
								'icon' => 'fa-facebook',
							),
							array(
								'id'   => 'customizer-repeater-social-repeater-57fb92615f030',
								'link' => 'twitter.com',
								'icon' => 'fa-twitter',
							),
							array(
								'id'   => 'customizer-repeater-social-repeater-57fb9266c223a',
								'link' => 'linkedin.com',
								'icon' => 'fa-linkedin',
							),
						)
					),
				),
			)
		)
	);
}


if ( function_exists( 'hestia_team' ) ) {
	$section_priority = apply_filters( 'hestia_section_priority', 30, 'hestia_team' );
	add_action( 'hestia_sections', 'hestia_team', absint( $section_priority ) );
	if ( function_exists( 'hestia_team_register_strings' ) ) {
		add_action( 'after_setup_theme', 'hestia_team_register_strings', 11 );
	}
}
