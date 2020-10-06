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
	 * @var string $font_css
	 */
	protected $font_css = '';
	
	/**
	 * Fonts
	 *
	 * @since  1.0.0
	 * @var (string) $fonts
	 */
	public static $fonts = null;
	
	/**
	 * Filter NeveReactCustomize object to add custom fonts.
	 *
	 * @param $localized_data
	 */
	public function add_custom_fonts( $localized_data ) {
		$localized_data['fonts']['Custom'] = $this->get_fonts();
		return $localized_data;
	}
	
	/**
	 * Get fonts
	 *
	 * @since 1.0.0
	 * @return array $fonts fonts array of fonts.
	 */
	private function get_fonts() {
		
		if ( is_null( self::$fonts ) ) {
			self::$fonts = array();
			
			$terms = get_terms(
				'obfx_custom_fonts',
				array(
					'hide_empty' => false,
				)
			);
			
			if ( ! empty( $terms ) ) {
				foreach ( $terms as $term ) {
					self::$fonts[] = $term->name;
				}
			}
		}
		return self::$fonts;
	}
	
	/**
	 * Enqueue Scripts
	 *
	 * @since 1.0.4
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
	 * @since 1.0.0
	 * @param string $name custom font name.
	 * @return array $font_links custom font data.
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
	 * @since 1.0.0
	 * @param array $font selected font from custom font list.
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
	
}
