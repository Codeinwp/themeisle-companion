<?php
/**
 * Header Footer scripts module Orbit Fox Module.
 *
 * @link       https://themeisle.com
 * @since      2.9.6
 */

/**
 * Class Header_Footer_Scripts_OBFX_Module
 */
class Header_Footer_Scripts_OBFX_Module extends Orbit_Fox_Module_Abstract {


	private $meta_controls = array();

	/**
	 * Header_Footer_Scripts_OBFX_Module constructor.
	 *
	 * @since   2.9.6
	 * @access  public
	 */
	public function __construct() {
		parent::__construct();
		$this->name = sprintf(
		/* translators: %s is New tag */
			__( 'Header Footer Scripts %s', 'neve' ),

			sprintf(
			/* translators: %s is New tag text */
				'<sup class="obfx-title-new">%s</sup>',
				__( 'NEW', 'themeisle-companion' )
			)
		);

		$this->description    = __( 'An easy way to add scripts, such as tracking and analytics scripts, to the header and footer of your website, as well as in the body of your posts and pages.', 'themeisle-companion' );
		$this->active_default = true;
		$this->meta_controls  = array(
			'obfx-header-scripts'        => array(
				'type'        => 'textarea',
				'label'       => __( 'Header scripts', 'themeisle-companion' ),
				'description' => sprintf(
					__( 'Output before the closing %s tag, after sitewide header scripts.', 'neve' ),
					'<code>&lt;/head&gt;</code>'
				)
			),
			'obfx-body-scripts'          => array(
				'type'  => 'textarea',
				'label' => __( 'Body scripts', 'themeisle-companion' ),
			),
			'obfx-body-scripts-position' => array(
				'type'    => 'select',
				'label'   => __( 'Body scripts position', 'themeisle-companion' ),
				'options' => array(
					'top'    => __( 'Top: after opening body tag', 'themeisle-companion' ),
					'bottom' => __( 'Bottom: before closing body tag', 'themeisle-companion' ),
				)
			)
		);
	}

	/**
	 * Determine if module should be loaded.
	 *
	 * @return bool
	 * @since   2.9.6
	 * @access  public
	 */
	public function enable_module() {
		return true;
	}

	/**
	 * The loading logic for the module.
	 *
	 * @since   2.9.6
	 * @access  public
	 */
	public function load() {
	}

	/**
	 * Method to define hooks needed.
	 *
	 * @since   2.9.6
	 * @access  public
	 */
	public function hooks() {
		$this->loader->add_action( 'customize_register', $this, 'register_customizer_controls' );
		$this->loader->add_action( 'add_meta_boxes', $this, 'add_meta' );
		$this->loader->add_action( 'save_post', $this, 'save_meta' );

		$this->loader->add_action( 'wp_head', $this, 'do_header_scripts' );
		$this->loader->add_action( 'wp_footer', $this, 'do_footer_scripts' );
		$this->loader->add_action( 'wp_body_open', $this, 'do_body_scripts' );
	}

	/**
	 * Register customizer controls.
	 *
	 * @param object $wp_customize the customizer manager.
	 *
	 * @since   2.9.6
	 * @access  public
	 */
	public function register_customizer_controls( $wp_customize ) {

		$wp_customize->add_section( 'obfx_header_footer_scripts', array(
			'title'      => __( 'Header/Footer scripts', 'themeisle-companion' ),
			'priority'   => 1000,
			'capability' => 'edit_theme_options',
		) );

		$wp_customize->add_setting( 'obfx_header_scripts', array(
			'default'           => sprintf( '<!-- %s -->',
				__( 'Enter your scripts here', 'themeisle-companion'
				)
			),
			'sanitize_callback' => 'sanitize_text_field',
			'capability'        => 'edit_theme_options',
		) );

		$wp_customize->add_control( 'obfx_header_scripts', array(
			'section'     => 'obfx_header_footer_scripts',
			'type'        => 'textarea',
			'priority'    => 10,
			'label'       => __( 'Header scripts', 'themeisle-companion' ),
			'description' => sprintf(
				__( 'This code will output immediately before the closing %s tag in document source.', 'themeisle-companion' ),
				'<code>&lt;/head&gt;</code>'
			)
		) );

		$wp_customize->add_setting( 'obfx_footer_scripts', array(
			'default'           => sprintf( '<!-- %s -->',
				__( 'Enter your scripts here', 'themeisle-companion'
				)
			),
			'sanitize_callback' => 'sanitize_text_field',
			'capability'        => 'edit_theme_options',
		) );

		$wp_customize->add_control( 'obfx_footer_scripts', array(
			'section'     => 'obfx_header_footer_scripts',
			'type'        => 'textarea',
			'priority'    => 10,
			'label'       => __( 'Footer scripts', 'themeisle-companion' ),
			'description' => sprintf(
				__( 'This code will output immediately before the closing %s tag in document source.', 'themeisle-companion' ),
				'<code>&lt;/body&gt;</code>'
			)
		) );
	}

	/**
	 * Add Header / Body scripts meta boxes.
	 *
	 * @since   2.9.6
	 * @access  public
	 */
	public function add_meta() {

		add_meta_box(
			'obfx-scripts',
			__( 'Scripts', 'themeisle-companion' ),
			array( $this, 'render_scripts_metabox' ),
			array( 'post', 'page' ),
			'normal',
			'default'
		);
	}

	/**
	 * Render meta box field.
	 *
	 * @param object $post Current post object.
	 *
	 * @since   2.9.6
	 * @access  public
	 */
	public function render_scripts_metabox( $post ) {
		echo '<table class="form-table">';
		echo '<tbody>';
		foreach ( $this->meta_controls as $control_id => $control_settings ) {
			echo '<tr>';
			echo '<th scope="row">';
			echo '<label for="' . esc_attr( $control_id ) . '"><strong>' . esc_html( $control_settings['label'] ) . '</strong></label>';
			echo '</th>';
			echo '<td>';
			echo '<p>';
			$this->render_control( $post, $control_id, $control_settings );
			echo '</p>';
			echo '</td>';
			echo '</tr>';
		}
		echo '</tbody>';
		echo '</table>';
	}

	/**
	 * Render meta control.
	 *
	 * @param object $post Current post object.
	 * @param string $control_id Meta control id.
	 * @param array $control_settings Meta control settings.
	 *
	 * @since   2.9.6
	 * @access  private
	 */
	private function render_control( $post, $control_id, $control_settings ) {
		var_dump( $post->ID );
		$post_meta = get_post_meta( $post->ID, 'obfx_scripts', true );
		if ( ! empty( $post_meta ) ){
			$post_meta = json_decode( $post_meta, true );
		}
		var_dump( $post_meta );
		if ( $control_settings['type'] === 'textarea' ) {
			echo '<textarea class="widefat" rows="4" name="obfx_scripts[' . esc_attr( $control_id ) . ']">';
			if ( ! empty( $post_meta) && array_key_exists( $control_id, $post_meta ) ) {
				echo htmlspecialchars( $post_meta[ $control_id ] );
			}
			echo '</textarea>';
		}
		if ( $control_settings['type'] === 'select' ) {
			echo '<select name="obfx_scripts[' . esc_attr( $control_id ) . ']">';
			foreach ( $control_settings['options'] as $option_value => $option_label ) {
				$selected = '';
				if ( ! empty( $post_meta) && array_key_exists( $control_id, $post_meta ) ) {
					$selected = selected( $post_meta[ $control_id ], $option_value );
				}
				echo '<option ' . $selected . ' value="' . esc_attr( $option_value ) . '">' . esc_html( $option_label ) . '</option>';
			}
			echo '</select>';
		}
		if ( array_key_exists( 'description', $control_settings ) ) {
			echo '<p>';
			echo wp_kses_post( $control_settings['description'] );
			echo '</p>';
		}
	}

	/**
	 * Save header/body scripts meta.
	 *
	 * @param int $post_id Current post id.
	 *
	 * @since   2.9.6
	 * @access  public
	 */
	public function save_meta( $post_id ) {
		if ( array_key_exists( 'obfx_scripts', $_POST ) ) {
			$meta_value = json_encode( $_POST['obfx_scripts'] );
			update_post_meta(
				$post_id,
				'obfx_scripts',
				$meta_value
			);
		}
	}

	/**
	 * Add content form Header scripts in wp_head hook.
	 *
	 * @since   2.9.6
	 * @access  public
	 */
	public function do_header_scripts() {
		$header_default = sprintf( '<!-- %s -->',
			__( 'Enter your scripts here', 'themeisle-companion'
			)
		);
		echo get_theme_mod( 'obfx_header_scripts', $header_default );

		if ( is_singular() ){
			global $post;
			$post_meta = get_post_meta( $post->ID, 'obfx_scripts', true );
			$post_meta = json_decode( $post_meta, true );
			echo $post_meta['obfx-header-scripts'];
		}
	}

	/**
	 * Add content form Footer scripts in wp_footer hook.
	 *
	 * @since   2.9.6
	 * @access  public
	 */
	public function do_footer_scripts() {
		$header_default = sprintf( '<!-- %s -->',
			__( 'Enter your scripts here', 'themeisle-companion'
			)
		);
		echo get_theme_mod( 'obfx_footer_scripts', $header_default );
	}

	/**
	 * Add content form Body scripts in wp_body_open hook.
	 *
	 * @since   2.9.6
	 * @access  public
	 */
	public function do_body_scripts() {
		if( ! is_singular() ){
			return false;
		}
		global $post;
		$post_meta = get_post_meta( $post->ID, 'obfx_scripts', true );
		$post_meta = json_decode( $post_meta, true );
		echo $post_meta['obfx-body-scripts'];
	}

	/**
	 * Method to define the options fields for the module
	 *
	 * @return array
	 * @since   2.9.6
	 * @access  public
	 */
	public function options() {
		return array();
	}

	/**
	 * Method that returns an array of scripts and styles to be loaded
	 * for the admin part.
	 *
	 * @return array
	 * @since   2.9.6
	 * @access  public
	 */
	public function admin_enqueue() {
		return array();
	}

	/**
	 * Method that returns an array of scripts and styles to be loaded
	 * for the front end part.
	 *
	 * @return array
	 * @since   2.9.6
	 * @access  public
	 */
	public function public_enqueue() {
		return array();
	}
}
