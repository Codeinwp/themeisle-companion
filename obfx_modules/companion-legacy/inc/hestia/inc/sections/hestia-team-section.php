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
	 * This function can be called from a shortcode too.
	 * When it's called as shortcode, the title and the subtitle shouldn't appear and it should be visible all the time,
	 * it shouldn't matter if is disable on front page.
	 *
	 * @since Hestia 1.0
	 * @modified 1.1.51
	 */
	function hestia_team( $is_shortcode = false ) {

		// When this function is called from selective refresh, $is_shortcode gets the value of WP_Customize_Selective_Refresh object. We don't need that.
		if ( ! is_bool( $is_shortcode ) ) {
			$is_shortcode = false;
		}

		$default_title    = false;
		$default_subtitle = false;
		$default_content  = false;

		if ( current_user_can( 'edit_theme_options' ) ) {
			$default_title    = esc_html__( 'Meet our team', 'themeisle-companion' );
			$default_subtitle = esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'themeisle-companion' );
			$default_content  = hestia_get_team_default();
		}

		$hestia_team_title    = get_theme_mod( 'hestia_team_title', $default_title );
		$hestia_team_subtitle = get_theme_mod( 'hestia_team_subtitle', $default_subtitle );
		$hestia_team_content  = get_theme_mod( 'hestia_team_content', $default_content );
		$section_is_empty     = empty( $hestia_team_title ) && empty( $hestia_team_subtitle ) && empty( $hestia_team_content );

		$hide_section = get_theme_mod( 'hestia_team_hide', false );
		$section_style        = '';
		/**
		 * Don't show section if Disable section is checked or it doesn't have any content.
		 * Show it if it's called as a shortcode.
		 */
		if ( ( $is_shortcode === false ) && ( $section_is_empty || (bool) $hide_section === true ) ) {
			if ( is_customize_preview() ) {
				$section_style = 'style="display: none"';
			} else {
				return;
			}
		}

		$wrapper_class   = $is_shortcode === true ? 'is-shortcode' : '';
		$container_class = $is_shortcode === true ? '' : 'container';

		if( function_exists( 'maybe_trigger_fa_loading' ) ){
			$html_allowed_strings = array(
				$hestia_team_title,
				$hestia_team_subtitle,
			);
			maybe_trigger_fa_loading( $html_allowed_strings );
		}

		hestia_before_team_section_trigger();
		?>
		<section class="hestia-team <?php echo esc_attr( $wrapper_class ); ?>" id="team" data-sorder="hestia_team" <?php echo wp_kses_post( $section_style ); ?>>
			<?php
			hestia_before_team_section_content_trigger();
			if ( function_exists( 'hestia_display_customizer_shortcut' ) && $is_shortcode === false ) {
				hestia_display_customizer_shortcut( 'hestia_team_hide', true );
			}
			?>
			<div class="<?php echo esc_attr( $container_class ); ?>">
				<?php
				hestia_top_team_section_content_trigger();
				if ( $is_shortcode === false ) {
					?>
					<div class="row">
						<div class="col-md-8 col-md-offset-2 text-center hestia-team-title-area">
							<?php
							if ( function_exists( 'hestia_display_customizer_shortcut' ) ) {
								hestia_display_customizer_shortcut( 'hestia_team_title' );
							}
							if ( ! empty( $hestia_team_title ) || is_customize_preview() ) {
								echo '<h2 class="hestia-title">' . wp_kses_post( $hestia_team_title ) . '</h2>';
							}
							if ( ! empty( $hestia_team_subtitle ) || is_customize_preview() ) {
								echo '<h5 class="description">' . wp_kses_post( $hestia_team_subtitle ) . '</h5>';
							}
							?>
						</div>
					</div>
					<?php
				}
				hestia_team_content( $hestia_team_content );
				hestia_bottom_team_section_content_trigger();
				?>
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
 *
 * @return bool
 */
function hestia_team_content( $hestia_team_content, $is_callback = false ) {
	
	if ( empty( $hestia_team_content ) ) {
		return false;
	}
	
	$hestia_team_content = json_decode( $hestia_team_content );
	if ( empty( $hestia_team_content ) ) {
		return false;
	}
	
	if ( ! $is_callback ) {
		echo '<div class="hestia-team-content">';
	}
	echo '<div class="row">';
	
	foreach ( $hestia_team_content as $team_item ) {
		$image    = ! empty( $team_item->image_url ) ? apply_filters( 'hestia_translate_single_string', $team_item->image_url, 'Team section' ) : '';
		$title    = ! empty( $team_item->title ) ? apply_filters( 'hestia_translate_single_string', $team_item->title, 'Team section' ) : '';
		$subtitle = ! empty( $team_item->subtitle ) ? apply_filters( 'hestia_translate_single_string', $team_item->subtitle, 'Team section' ) : '';
		$text     = ! empty( $team_item->text ) ? apply_filters( 'hestia_translate_single_string', $team_item->text, 'Team section' ) : '';
		$link     = ! empty( $team_item->link ) ? apply_filters( 'hestia_translate_single_string', $team_item->link, 'Team section' ) : '';
		
		$data = array( $image, $title, $subtitle, $text, $link );
		if ( ! array_filter( $data ) ) {
			continue;
		}
		
		$link_markup_open  = '';
		$link_markup_close = '';
		if ( ! empty( $link ) ) {
			$link_markup_open = '<a href="' . esc_url( $link ) . '"';
			if ( function_exists( 'hestia_is_external_url' ) ) {
				$link_markup_open .= hestia_is_external_url( $link );
			}
			$link_markup_open .= '>';
			
			$link_markup_close = '</a>';
		}
		
		if ( function_exists( 'maybe_trigger_fa_loading' ) ) {
			maybe_trigger_fa_loading( $text );
		}
		
		echo '<div class="col-xs-12 col-ms-6 col-sm-6">';
		echo '<div class="card card-profile card-plain">';
		echo '<div class="col-md-5">';
		echo '<div class="card-image">';
		
		if ( ! empty( $image ) ) {
			/**
			 * Alternative text for the Team box image
			 * It first checks for the Alt Text option of the attachment
			 * If that field is empty, uses the Title of the Testimonial box as alt text
			 */
			$alt_image = $title;
            $srcset    = '';
			$image_id  = function_exists( 'attachment_url_to_postid' ) ? attachment_url_to_postid( preg_replace( '/-\d{1,4}x\d{1,4}/i', '', $image ) ) : '';
			if ( ! empty( $image_id ) && $image_id !== 0 ) {
				$alt_image = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
                $srcset    = wp_get_attachment_image_srcset( $image_id, 'full' );
			}
			
			echo $link_markup_open;
			
			echo '<img class="img" src="' . esc_url( $image ) . '" ';
			if ( ! empty( $alt_image ) ) {
				echo ' alt="' . esc_attr( $alt_image ) . '" ';
			}
            if ( ! empty( $srcset ) ) {
                echo ' srcset="' . esc_attr( $srcset ) . '" ';
            }
			if ( ! empty( $title ) ) {
				echo ' title="' . esc_attr( $title ) . '" ';
			}
			echo '/>';
			
			echo $link_markup_close;
		}
		echo '</div>';
		echo '</div>';
		
		echo '<div class="col-md-7">';
		echo '<div class="content">';
		
		echo $link_markup_open;
		if ( ! empty( $title ) ) {
			echo '<h4 class="card-title">' . esc_html( $title ) . '</h4>';
		}
		
		if ( ! empty( $subtitle ) ) {
			echo '<h6 class="category text-muted">' . esc_html( $subtitle ) . '</h6>';
		}
		
		if ( ! empty( $text ) ) {
			echo '<p class="card-description">' . wp_kses_post( html_entity_decode( $text ) ) . '</p>';
		}
		echo $link_markup_close;
		
		if ( ! empty( $team_item->social_repeater ) ) {
			$icons         = html_entity_decode( $team_item->social_repeater );
			$icons_decoded = json_decode( $icons, true );
			if ( ! empty( $icons_decoded ) ) {
				echo '<div class="footer">';
				foreach ( $icons_decoded as $value ) {
					$social_icon = ! empty( $value['icon'] ) ? apply_filters( 'hestia_translate_single_string', $value['icon'], 'Team section' ) : '';
					$social_link = ! empty( $value['link'] ) ? apply_filters( 'hestia_translate_single_string', $value['link'], 'Team section' ) : '';
					if ( ! empty( $social_icon ) ) {
						$aria_label = '';
						$path       = wp_parse_url( $social_link, PHP_URL_PATH );
						if ( $path ) {
							$path       = strstr( $path, '.', true );
							$aria_label = sprintf( 'View the %s profile of %s', $path, $title );
							$aria_label = apply_filters( 'hestia_team_social_media_aria_text', $aria_label, $team_item );
						}
						$link = '<a href="' . esc_url( $social_link ) . '"';
						if ( function_exists( 'hestia_is_external_url' ) ) {
							$link .= hestia_is_external_url( $social_link );
						}
						$link .= ' class="btn btn-just-icon btn-simple" aria-label="' . esc_attr( $aria_label ) . '"><i class="' . esc_attr( hestia_display_fa_icon( $social_icon ) ) . '"></i></a>';
						echo $link;
					}
				}
				echo '</div>';
			}
		}
		
		echo '</div>';
		echo '</div>';
		echo '</div>';
		echo '</div>';
	};
	echo '</div>';
	
	if ( ! $is_callback ) {
		echo '</div>';
	}
	return true;
}


/**
 * Get default values for features section.
 *
 * @since 1.1.31
 * @access public
 */
function hestia_get_team_default() {
	return apply_filters(
		'hestia_team_default_content',
		json_encode(
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
								'icon' => 'fab fa-facebook-f',
							),
							array(
								'id'   => 'customizer-repeater-social-repeater-57fb9148530ft',
								'link' => 'plus.google.com',
								'icon' => 'fab fa-google-plus-g',
							),
							array(
								'id'   => 'customizer-repeater-social-repeater-57fb9148530fc',
								'link' => 'twitter.com',
								'icon' => 'fab fa-twitter',
							),
							array(
								'id'   => 'customizer-repeater-social-repeater-57fb9150e1e89',
								'link' => 'linkedin.com',
								'icon' => 'fab fa-linkedin-in',
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
								'icon' => 'fab fa-facebook-f',
							),
							array(
								'id'   => 'customizer-repeater-social-repeater-57fb9160ab683',
								'link' => 'twitter.com',
								'icon' => 'fab fa-google-plus-g',
							),
							array(
								'id'   => 'customizer-repeater-social-repeater-57fb9160ab484',
								'link' => 'pinterest.com',
								'icon' => 'fab fa-twitter',
							),
							array(
								'id'   => 'customizer-repeater-social-repeater-57fb916ddffc9',
								'link' => 'linkedin.com',
								'icon' => 'fab fa-linkedin-in',
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
								'icon' => 'fab fa-facebook-f',
							),
							array(
								'id'   => 'customizer-repeater-social-repeater-57fb91830825c',
								'link' => 'twitter.com',
								'icon' => 'fab fa-google-plus-g',
							),
							array(
								'id'   => 'customizer-repeater-social-repeater-57fb918d65f2e',
								'link' => 'linkedin.com',
								'icon' => 'fab fa-twitter',
							),
							array(
								'id'   => 'customizer-repeater-social-repeater-57fb918d65f2x',
								'link' => 'dribbble.com',
								'icon' => 'fab fa-linkedin-in',
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
								'icon' => 'fab fa-facebook-f',
							),
							array(
								'id'   => 'customizer-repeater-social-repeater-57fb925cedcb2',
								'link' => 'facebook.com',
								'icon' => 'fab fa-google-plus-g',
							),
							array(
								'id'   => 'customizer-repeater-social-repeater-57fb92615f030',
								'link' => 'twitter.com',
								'icon' => 'fab fa-twitter',
							),
							array(
								'id'   => 'customizer-repeater-social-repeater-57fb9266c223a',
								'link' => 'linkedin.com',
								'icon' => 'fab fa-linkedin-in',
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
