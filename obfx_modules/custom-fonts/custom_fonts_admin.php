<?php
/**
 *
 */

/**
 * Class Custom_Fonts_Admin
 */
class Custom_Fonts_Admin {
	
	/**
	 * Create custom fonts taxonomy.
	 *
	 * @since 2.10
	 * @return void
	 */
	public function create_taxonomy() {
		$labels = array(
			'name'              => __( 'Custom Fonts', 'themeisle-companion' ),
			'singular_name'     => __( 'Font', 'themeisle-companion' ),
			'menu_name'         => _x( 'Custom Fonts', 'Admin menu name', 'themeisle-companion' ),
			'search_items'      => __( 'Search Fonts', 'themeisle-companion' ),
			'all_items'         => __( 'All Fonts', 'themeisle-companion' ),
			'parent_item'       => __( 'Parent Font', 'themeisle-companion' ),
			'parent_item_colon' => __( 'Parent Font:', 'themeisle-companion' ),
			'edit_item'         => __( 'Edit Font', 'themeisle-companion' ),
			'update_item'       => __( 'Update Font', 'themeisle-companion' ),
			'add_new_item'      => __( 'Add New Font', 'themeisle-companion' ),
			'new_item_name'     => __( 'New Font Name', 'themeisle-companion' ),
			'not_found'         => __( 'No fonts found', 'themeisle-companion' ),
		);
		
		$args = array(
			'hierarchical'      => false,
			'labels'            => $labels,
			'public'            => false,
			'show_in_nav_menus' => false,
			'show_ui'           => true,
			'capabilities'      => array( 'edit_theme_options' ),
			'query_var'         => false,
			'rewrite'           => false,
		);
		
		register_taxonomy(
			'obfx_custom_fonts',
			array(),
			$args
		);
	}
	
	/**
	 * Add custom fonts taxonomy to menu.
	 *
	 * @since 2.10
	 * @return void
	 */
	public function add_to_menu() {
		add_submenu_page(
			'themes.php',
			__( 'Custom Fonts', 'themeisle-companion' ),
			__( 'Custom Fonts', 'themeisle-companion' ),
			'edit_theme_options',
			'edit-tags.php?taxonomy=obfx_custom_fonts'
		);
	}
	
	/**
	 * Edit custom font form fields.
	 * It hides the description and slug fields and it changes the description for the name field.
	 *
	 * @since 2.10
	 * @return void
	 */
	public function edit_custom_font_form() {
		global $parent_file, $submenu_file;
		
		if ( 'edit-tags.php?taxonomy=obfx_custom_fonts' === $submenu_file ) {
			$parent_file = 'themes.php'; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		}
		if ( get_current_screen()->id != 'edit-obfx_custom_fonts' ) {
			return;
		} ?>
		<style>#addtag div.form-field.term-slug-wrap, #edittag tr.form-field.term-slug-wrap { display: none; }
			#addtag div.form-field.term-description-wrap, #edittag tr.form-field.term-description-wrap { display: none; }</style><script>jQuery( document ).ready( function( $ ) {
				var $wrapper = $( '#addtag, #edittag' );
				$wrapper.find( 'tr.form-field.term-name-wrap p, div.form-field.term-name-wrap > p' ).text( '<?php esc_html_e( 'The name of the font as it appears in the customizer options.', 'themeisle-companion' ); ?>' );
			} );
		</script>
		<?php
	}
	
	/**
	 * Manage custom fonts taxonomy columns.
	 *
	 * @param array $columns Default columns.
	 *
	 * @since 2.10
	 * @return array
	 */
	public function manage_columns( $columns ) {
		unset( $columns['slug'] );
		unset( $columns['description'] );
		unset( $columns['posts'] );
		return $columns;
	}
	
	/**
	 * Add new Taxonomy data
	 *
	 * @since 2.10
	 * @return void
	 */
	public function add_new_taxonomy_data() {
		$this->font_file_new_field( 'font_woff_2', __( 'Font .woff2', 'themeisle-companion' ), __( 'Upload the font\'s woff2 file or enter the URL.', 'themeisle-companion' ) );
		$this->font_file_new_field( 'font_woff', __( 'Font .woff', 'themeisle-companion' ), __( 'Upload the font\'s woff file or enter the URL.', 'themeisle-companion' ) );
		$this->font_file_new_field( 'font_ttf', __( 'Font .ttf', 'themeisle-companion' ), __( 'Upload the font\'s ttf file or enter the URL.', 'themeisle-companion' ) );
		$this->font_file_new_field( 'font_eot', __( 'Font .eot', 'themeisle-companion' ), __( 'Upload the font\'s eot file or enter the URL.', 'themeisle-companion' ) );
		$this->font_file_new_field( 'font_svg', __( 'Font .svg', 'themeisle-companion' ), __( 'Upload the font\'s svg file or enter the URL.', 'themeisle-companion' ) );
		$this->font_file_new_field( 'font_otf', __( 'Font .otf', 'themeisle-companion' ), __( 'Upload the font\'s otf file or enter the URL.', 'themeisle-companion' ) );
		
		$this->select_new_field(
			'font-display',
			__( 'Font Display', 'themeisle-companion' ),
			__( 'Select font-display property for this font', 'themeisle-companion' ),
			array(
				'auto'     => 'auto',
				'block'    => 'block',
				'swap'     => 'swap',
				'fallback' => 'fallback',
				'optional' => 'optional',
			)
		);
	}
	
	/**
	 * Add Taxonomy data field
	 *
	 * @param int    $id current term id.
	 * @param string $title font type title.
	 * @param string $description title font type description.
	 * @param string $value title font type meta values.
	 *
	 * @since 2.10
	 * @return void
	 */
	protected function font_file_new_field( $id, $title, $description, $value = '' ) {
		echo '<div class="obfx-custom-fonts-file-wrap form-field term-' . esc_attr( $id ) . '-wrap" >';
		echo '<label for="font-' . esc_attr( $id ) . '">';
		echo esc_html( $title );
		echo '</label>';
		echo '<input type="text" id="font-' . esc_attr( $id ) . '" class="obfx-custom-fonts-link ' . esc_attr( $id ) . '" name=" obfx_custom_fonts[' . esc_attr( $id ) . ']" value="' . esc_attr( $value ) . '" />';
		echo '<a href="#" class="obfx-custom-fonts-upload button" data-upload-type="' . esc_attr( $id ) . '">';
		esc_html_e( 'Upload', 'themeisle-companion' );
		echo '</a>';
		echo '<p>' . esc_html( $description ) . '</p>';
		echo '</div>';
	}
	
	/**
	 * Render select field for the new font screen.
	 *
	 * @param string $id Field ID.
	 * @param string $title Field Title.
	 * @param string $description Field Description.
	 * @param array  $select_fields Select fields as Array.
	 *
	 * @since 2.10
	 * @return void
	 */
	protected function select_new_field( $id, $title, $description, $select_fields ) {
		echo '<div class="obfx-custom-fonts-file-wrap form-field term-' . esc_attr( $id ) . '-wrap" >';
		echo '<label for="font-' . esc_attr( $id ) . '">' . esc_html( $title ) . '</label>';
		echo '<select type="select" id="font-' . esc_attr( $id ) . '" class="obfx-custom-font-select-field ' . esc_attr( $id ) . '" name="obfx_custom_fonts[' . esc_attr( $id ) . ']" />';
		foreach ( $select_fields as $key => $value ) {
			echo '<option value="' . esc_attr( $key ) . '">' . esc_html( $value ) . '</option>';
		}
		echo '</select>';
		echo '</div>';
	}
	
	/**
	 * Edit Taxonomy data
	 *
	 * @param object $term taxonomy terms.
	 *
	 * @since 2.10
	 * @return void
	 */
	public function edit_taxonomy_data( $term ) {
		$data = self::get_font_links( $term->term_id );
		$this->font_file_edit_field( 'font_woff_2', __( 'Font .woff2', 'themeisle-companion' ), $data['font_woff_2'], __( 'Upload the font\'s woff2 file or enter the URL.', 'themeisle-companion' ) );
		$this->font_file_edit_field( 'font_woff', __( 'Font .woff', 'themeisle-companion' ), $data['font_woff'], __( 'Upload the font\'s woff file or enter the URL.', 'themeisle-companion' ) );
		$this->font_file_edit_field( 'font_ttf', __( 'Font .ttf', 'themeisle-companion' ), $data['font_ttf'], __( 'Upload the font\'s ttf file or enter the URL.', 'themeisle-companion' ) );
		$this->font_file_edit_field( 'font_eot', __( 'Font .eot', 'themeisle-companion' ), $data['font_eot'], __( 'Upload the font\'s eot file or enter the URL.', 'themeisle-companion' ) );
		$this->font_file_edit_field( 'font_svg', __( 'Font .svg', 'themeisle-companion' ), $data['font_svg'], __( 'Upload the font\'s svg file or enter the URL.', 'themeisle-companion' ) );
		$this->font_file_edit_field( 'font_otf', __( 'Font .otf', 'themeisle-companion' ), $data['font_otf'], __( 'Upload the font\'s otf file or enter the URL.', 'themeisle-companion' ) );
		
		$this->select_edit_field(
			'font-display',
			__( 'Font Display', 'themeisle-companion' ),
			$data['font-display'],
			__( 'Select font-display property for this font', 'themeisle-companion' ),
			array(
				'auto'     => 'Auto',
				'block'    => 'Block',
				'swap'     => 'Swap',
				'fallback' => 'Fallback',
				'optional' => 'Optional',
			)
		);
	}
	
	/**
	 * Add Taxonomy data field
	 *
	 * @param int    $id current term id.
	 * @param string $title font type title.
	 * @param string $value title font type meta values.
	 * @param string $description title font type description.
	 *
	 * @since 2.10
	 * @return void
	 */
	protected function font_file_edit_field( $id, $title, $value = '', $description ) {
		echo '<tr class="obfx-custom-fonts-file-wrap form-field term-' . esc_attr( $id ) . '-wrap ">';
		echo '<th scope="row">';
		echo '<label for="metadata-' . esc_attr( $id ) . '">';
		echo esc_html( $title );
		echo '</label>';
		echo '</th>';
		echo '<td>';
		echo '<input id="metadata-' . esc_attr( $id ) . '" type="text" class="obfx-custom-fonts-link ' . esc_attr( $id ) . '" name="obfx_custom_fonts[' . esc_attr( $id ) . ']" value="' . esc_attr( $value ) . '" />';
		echo '<a href="#" class="obfx-custom-fonts-upload button" data-upload-type="' . esc_attr( $id ) . '">';
		esc_html_e( 'Upload', 'themeisle-companion' );
		echo '</a>';
		echo '<p>';
		echo esc_html( $description );
		echo '</p>';
		echo '</td>';
		echo '</tr>';
	}
	
	/**
	 * Render select field for the edit font screen.
	 *
	 * @param string $id Field ID.
	 * @param string $title Field Title.
	 * @param string $saved_val Field Value.
	 * @param string $description Field Description.
	 * @param array  $select_fields Select fields as Array.
	 *
	 * @since 2.10
	 * @return void
	 */
	private function select_edit_field( $id, $title, $saved_val = '', $description, $select_fields ) {
		echo '<tr class="obfx-custom-fonts-file-wrap form-field term-' . esc_attr( $id ) . '-wrap">';
		echo '<th scope="row">';
		echo '<label for="metadata-' . esc_attr( $id ) . '">';
		echo esc_html( $title );
		echo '</label>';
		echo '</th>';
		echo '<td>';
		echo '<select type="select" id="font-' . esc_attr( $id ) . '" class="obfx-custom-font-select-field ' . esc_attr( $id ) . '" name="obfx_custom_fonts[' . esc_attr( $id ) . ']" />';
		foreach ( $select_fields as $key => $value ) {
			echo '<option value="' . esc_attr( $key ) . '" ' . selected( $key, $saved_val ) . '>';
			echo esc_html( $value );
			echo '</option>';
		}
		echo '</select>';
		echo '<p>';
		echo esc_html( $description );
		echo '</p>';
		echo '</td>';
		echo '</tr>';
	}
	
	/**
	 * Save Taxonomy meta data value
	 *
	 * @param int $term_id current term id.
	 *
	 * @since 2.10
	 * @return bool
	 */
	public function save_metadata( $term_id ) {
		
		if ( ! current_user_can( 'manage_options' ) ) {
			return false;
		}
		
		if ( isset( $_POST['obfx_custom_fonts'] ) ) {// phpcs:ignore WordPress.Security.NonceVerification.Missing
			$return_val = array();
			foreach ( $_POST['obfx_custom_fonts'] as $key => $value ) {
				if ( $key === 'font-display' ) {
					$return_val[ $key ] = esc_attr( $value );
					continue;
				}
				$return_val[ $key ] = esc_url( $value );
			}
			self::update_font_links( $return_val, $term_id );
		}
		
		return true;
	}
	
	/**
	 * Update font data from name
	 *
	 * @param array $posted custom font data.
	 * @param int   $term_id custom font term id.
	 *
	 * @since 2.10
	 * @return void
	 */
	public static function update_font_links( $posted, $term_id ) {
		
		$links = self::get_font_links( $term_id );
		foreach ( array_keys( $links ) as $key ) {
			if ( isset( $posted[ $key ] ) ) {
				$links[ $key ] = $posted[ $key ];
			} else {
				$links[ $key ] = '';
			}
		}
		update_option( "taxonomy_obfx_custom_fonts_{$term_id}", $links );
	}
	
	/**
	 * Get font links
	 *
	 * @param int $term_id custom font term id.
	 *
	 * @since 2.10
	 * @return array
	 */
	public static function get_font_links( $term_id ) {
		$links = get_option( "taxonomy_obfx_custom_fonts_{$term_id}", array() );
		return self::default_args( $links );
	}
	
	/**
	 * Default fonts
	 *
	 * @param array $fonts fonts array of fonts.
	 *
	 * @since 2.10
	 * @return array
	 */
	protected static function default_args( $fonts ) {
		return wp_parse_args(
			$fonts,
			array(
				'font_woff_2'  => '',
				'font_woff'    => '',
				'font_ttf'     => '',
				'font_svg'     => '',
				'font_eot'     => '',
				'font_otf'     => '',
				'font-display' => 'swap',
			)
		);
	}
	
	/**
	 * Allowed mime types and file extensions
	 *
	 * @param array $mimes Current array of mime types.
	 *
	 * @since 2.10
	 * @return array
	 */
	public function add_fonts_to_allowed_mimes( $mimes ) {
		$mimes['woff']  = 'application/x-font-woff';
		$mimes['woff2'] = 'application/x-font-woff2';
		$mimes['ttf']   = 'application/x-font-ttf';
		$mimes['svg']   = 'image/svg+xml';
		$mimes['eot']   = 'application/vnd.ms-fontobject';
		$mimes['otf']   = 'font/otf';
		
		return $mimes;
	}
	
	/**
	 * Correct the mome types and extension for the font types.
	 *
	 * @param array  $defaults File data array containing 'ext', 'type', and 'proper_filename' keys.
	 * @param string $file                      Full path to the file.
	 * @param string $filename                  The name of the file (may differ from $file due to
	 *                                          $file being in a tmp directory).
	 * @since 2.10
	 * @return array
	 */
	public function update_mime_types( $defaults, $file, $filename ) {
		if ( 'ttf' === pathinfo( $filename, PATHINFO_EXTENSION ) ) {
			$defaults['type'] = 'application/x-font-ttf';
			$defaults['ext']  = 'ttf';
		}
		
		if ( 'otf' === pathinfo( $filename, PATHINFO_EXTENSION ) ) {
			$defaults['type'] = 'application/x-font-otf';
			$defaults['ext']  = 'otf';
		}
		
		return $defaults;
	}
	
}
