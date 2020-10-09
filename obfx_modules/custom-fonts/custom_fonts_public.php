<?php
/**
 *
 */

/**
 * Class Custom_Fonts_Public
 */
class Custom_Fonts_Public {
	
	/**
	 * Member Varible
	 *
	 * @since  2.10
	 * @var string
	 */
	protected $font_css = '';
	
	/**
	 * Fonts
	 *
	 * @since  2.10
	 * @var array
	 */
	private $fonts = array();
	
	/**
	 * Filter NeveReactCustomize object to add custom fonts.
	 *
	 * @param $localized_data
	 *
	 * @since 2.10
	 * @return array
	 */
	public function add_custom_fonts( $localized_data ) {
		$localized_data['fonts']['Custom'] = $this->get_fonts();
		return $localized_data;
	}
	
	/**
	 * Get fonts
	 *
	 * @since 2.10
	 * @return array
	 */
	public function get_fonts() {
		if ( ! empty( $this->fonts ) ) {
			return $this->fonts;
		}
		
		$terms = get_terms(
			'obfx_custom_fonts',
			array(
				'hide_empty' => false,
			)
		);
		
		if ( ! empty( $terms ) ) {
			foreach ( $terms as $term ) {
				if ( ! is_object( $term ) || ! property_exists( $term, 'name' ) ) {
					return $this->fonts;
				}
				$this->fonts[] = $term->name;
			}
		}
		
		return $this->fonts;
	}
	
	/**
	 * Enqueue Scripts
	 *
	 * @since 2.10
	 * @return void
	 */
	public function add_style() {
		$fonts = $this->get_fonts();
		if ( ! empty( $fonts ) ) {
			foreach ( $fonts  as $load_font_name ) {
				$this->render_font_css( $load_font_name );
			}
			?>
			<style type="text/css">
				<?php echo wp_strip_all_tags( $this->font_css ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</style>
			<?php
		}
	}
	
	/**
	 * Get font data from name
	 *
	 * @param string $name custom font name.
	 *
	 * @since 2.10
	 * @return array
	 */
	public static function get_links_by_name( $name ) {
		
		$terms      = get_terms(
			'obfx_custom_fonts',
			array(
				'hide_empty' => false,
			)
		);
		$font_links = array();
		if ( ! empty( $terms ) ) {
			
			foreach ( $terms as $term ) {
				if ( $term->name == $name ) {
					$font_links[ $term->name ] = Custom_Fonts_Admin::get_font_links( $term->term_id );
				}
			}
		}
		
		return $font_links;
		
	}
	
	/**
	 * Create css for font-face
	 *
	 * @param array $font selected font from custom font list.
	 *
	 * @since 2.10
	 * @return void
	 */
	private function render_font_css( $font ) {
		$fonts = $this->get_links_by_name( $font );
		
		foreach ( $fonts as $font => $links ) :
			$css  = '@font-face { font-family:' . esc_attr( $font ) . ';';
			$css .= 'src:';
			$arr  = array();
			if ( $links['font_woff_2'] ) {
				$arr[] = 'url(' . esc_url( $links['font_woff_2'] ) . ") format('woff2')";
			}
			if ( $links['font_woff'] ) {
				$arr[] = 'url(' . esc_url( $links['font_woff'] ) . ") format('woff')";
			}
			if ( $links['font_ttf'] ) {
				$arr[] = 'url(' . esc_url( $links['font_ttf'] ) . ") format('truetype')";
			}
			if ( $links['font_otf'] ) {
				$arr[] = 'url(' . esc_url( $links['font_otf'] ) . ") format('opentype')";
			}
			if ( $links['font_svg'] ) {
				$arr[] = 'url(' . esc_url( $links['font_svg'] ) . '#' . esc_attr( strtolower( str_replace( ' ', '_', $font ) ) ) . ") format('svg')";
			}
			$css .= join( ', ', $arr );
			$css .= ';';
			$css .= 'font-display: ' . esc_attr( $links['font-display'] ) . ';';
			$css .= '}';
		endforeach;
		
		$this->font_css .= $css;
	}
	
	/**
	 * Set default 'inherit' if custom font is selected in customizer if this is deleted.
	 *
	 * @param int $term Term ID.
	 * @param int $tt_id Term taxonomy ID.
	 * @param string $taxonomy Taxonomy slug.
	 * @param mixed $deleted_term deleted term.
	 * @param object $object_ids objects ids.
	 *
	 * @since 2.10
	 * @return bool
	 */
	public function delete_custom_fonts_fallback( $term, $tt_id, $taxonomy, $deleted_term, $object_ids ) {
		if ( $taxonomy !== 'obfx_custom_fonts' ) {
			return false;
		}
		if ( ! defined( 'NEVE_VERSION' ) ) {
			return false;
		}
		$theme_mods = array( 'neve_body_font_family', 'neve_headings_font_family', 'primary-menu_component_font_family', 'footer_copyright_component_font_family' );
		foreach ( $theme_mods as $theme_mod ) {
			$value = get_theme_mod( $theme_mod );
			if ( $value === $deleted_term->name ) {
				set_theme_mod( $theme_mod, '' );
			}
		}
		return true;
	}
	
	/**
	 * Add Custom Font list to BB theme and BB Page Builder
	 *
	 * @param array $bb_fonts font families added by bb.
	 *
	 * @since 2.10
	 * @return array
	 */
	public function bb_custom_fonts( $bb_fonts ) {
		
		$fonts        = $this->get_fonts();
		$custom_fonts = array();
		if ( ! empty( $fonts ) ) {
			foreach ( $fonts as $font_family_name ) {
				$custom_fonts[ $font_family_name ] = array(
					'fallback' => 'Verdana, Arial, sans-serif',
					'weights'  => array( '100', '200', '300', '400', '500', '600', '700', '800', '900' ),
				);
			}
		}
		
		return array_merge( $bb_fonts, $custom_fonts );
	}
	
	/**
	 * Add Custom Font group to elementor font list.
	 * Group name "Custom" is added as the first element in the array.
	 *
	 * @param  array $font_groups default font groups in elementor.
	 *
	 * @since 2.10
	 * @return array
	 */
	public function elementor_group( $font_groups ) {
		$new_group['obfx-custom-fonts'] = __( 'Custom', 'themeisle-companion' );
		$font_groups                    = $new_group + $font_groups;
		
		return $font_groups;
	}
	
	/**
	 * Add Custom Fonts to the Elementor Page builder's font param.
	 *
	 * @param array
	 *
	 * @since 2.10
	 * @return array
	 */
	public function add_elementor_fonts( $fonts ) {
		
		$all_fonts = $this->get_fonts();
		if ( empty( $all_fonts ) ) {
			return $fonts;
		}
		
		foreach ( $all_fonts as $font_family_name ) {
			$fonts[ $font_family_name ] = 'obfx-custom-fonts';
		}
		return $fonts;
	}
	
}
