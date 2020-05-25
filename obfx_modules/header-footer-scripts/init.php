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


	/**
	 * Meta controls.
	 *
	 * @var array
	 */
	private $meta_controls = array();

	/**
	 * Allowed html.
	 *
	 * @var array
	 */
	private $allowed_html = array();

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
			__( 'Header Footer Scripts %s', 'themeisle-companion' ),
			sprintf(
			/* translators: %s is New tag text */
				'<sup class="obfx-title-new">%s</sup>',
				__( 'NEW', 'themeisle-companion' )
			)
		);

		$this->description    = __( 'An easy way to add scripts, such as tracking and analytics scripts, to the header and footer of your website, as well as in the body of your posts and pages.', 'themeisle-companion' );
		$this->active_default = true;
		$this->meta_controls  = array(
			'obfx-header-scripts' => array(
				'type'        => 'textarea',
				'label'       => __( 'Header scripts', 'themeisle-companion' ),
				'description' => sprintf(
					/* translators: %s is head tag */
					__( 'Output before the closing %s tag, after sitewide header scripts.', 'themeisle-companion' ),
					'<code>&lt;/head&gt;</code>'
				),
			),
			'obfx-footer-scripts' => array(
				'type'        => 'textarea',
				'label'       => __( 'Footer scripts', 'themeisle-companion' ),
				'description' => sprintf(
					/* translators: %s is body tag */
					__( 'Output before the closing %s tag, after sitewide footer scripts.', 'themeisle-companion' ),
					'<code>&lt;/body&gt;</code>'
				),
			),
		);
		$this->allowed_html           = wp_kses_allowed_html( 'post' );
		$this->allowed_html['script'] = array(
			'async' => array(),
			'src'   => array(),
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

		$wp_customize->add_section(
			'obfx_header_footer_scripts',
			array(
				'title'      => __( 'Header/Footer scripts', 'themeisle-companion' ),
				'priority'   => 1000,
				'capability' => 'edit_theme_options',
			)
		);

		$wp_customize->add_setting(
			'obfx_header_scripts',
			array(
				'default'           => sprintf(
					/* translators: %s is placeholder value */
					'<!-- %s -->',
					__(
						'Enter your scripts here',
						'themeisle-companion'
					)
				),
				'sanitize_callback' => array( $this, 'sanitize_script' ),
				'capability'        => 'edit_theme_options',
			)
		);

		$wp_customize->add_control(
			'obfx_header_scripts',
			array(
				'section'     => 'obfx_header_footer_scripts',
				'type'        => 'textarea',
				'priority'    => 10,
				'label'       => __( 'Header scripts', 'themeisle-companion' ),
				'description' => sprintf(
					/* translators: %s is head tag */
					__( 'This code will output immediately before the closing %s tag in document source.', 'themeisle-companion' ),
					'<code>&lt;/head&gt;</code>'
				),
			)
		);

		$wp_customize->add_setting(
			'obfx_footer_scripts',
			array(
				'default'           => sprintf(
					/* translators: %s is placeholder value */
					'<!-- %s -->',
					__(
						'Enter your scripts here',
						'themeisle-companion'
					)
				),
				'sanitize_callback' => array( $this, 'sanitize_script' ),
				'capability'        => 'edit_theme_options',
			)
		);

		$wp_customize->add_control(
			'obfx_footer_scripts',
			array(
				'section'     => 'obfx_header_footer_scripts',
				'type'        => 'textarea',
				'priority'    => 10,
				'label'       => __( 'Footer scripts', 'themeisle-companion' ),
				'description' => sprintf(
					/* translators: %s is body tag */
					__( 'This code will output immediately before the closing %s tag in document source.', 'themeisle-companion' ),
					'<code>&lt;/body&gt;</code>'
				),
			)
		);
	}

	/**
	 * Sanitize customizer header footer scripts.
	 *
	 * @param string $script Input value
	 *
	 * @return mixed
	 */
	public function sanitize_script( $script ) {
		return wp_kses( $script, $this->allowed_html );
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
		$post_meta = get_post_meta( $post->ID, 'obfx_scripts', true );
		if ( ! empty( $post_meta ) ) {
			$post_meta = json_decode( $post_meta, true );
		}
		if ( $control_settings['type'] === 'textarea' ) {
			echo '<textarea class="widefat" rows="4" name="obfx_scripts[' . esc_attr( $control_id ) . ']">';
			if ( ! empty( $post_meta ) && array_key_exists( $control_id, $post_meta ) ) {
				$input_val = html_entity_decode( $post_meta[ $control_id ], ENT_QUOTES );
				echo wp_kses( $input_val, $this->allowed_html );
			}
			echo '</textarea>';
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
	 * @return  bool
	 */
	public function save_meta( $post_id ) {
		if ( ! isset( $_POST['obfx_scripts'] ) ) {
			return false;
		}
		if ( array_key_exists( 'obfx_scripts', $_POST ) ) {
			foreach ( $_POST['obfx_scripts'] as $script_position => $script_value ) {
				$_POST['obfx_scripts'][ $script_position ] = htmlentities( stripslashes( utf8_encode( $script_value ) ), ENT_QUOTES );
			}
			$meta_value = json_encode( $_POST['obfx_scripts'], true );
			update_post_meta(
				$post_id,
				'obfx_scripts',
				$meta_value
			);
		}
		return true;
	}

	/**
	 * Add content form Header scripts in wp_head hook.
	 *
	 * @since   2.9.6
	 * @access  public
	 */
	public function do_header_scripts() {
		$default = sprintf(
			/* translators: %s is placeholder value */
			'<!-- %s -->',
			__(
				'Enter your scripts here',
				'themeisle-companion'
			)
		);
		$header_scripts = get_theme_mod( 'obfx_header_scripts', $default );
		echo wp_kses( $header_scripts, $this->allowed_html );

		if ( is_singular() ) {
			global $post;
			$post_meta = get_post_meta( $post->ID, 'obfx_scripts', true );
			$post_meta = json_decode( $post_meta, true );
			$value = html_entity_decode( $post_meta['obfx-header-scripts'], ENT_QUOTES );
			echo wp_kses( $value, $this->allowed_html );
		}
	}

	/**
	 * Add content form Footer scripts in wp_footer hook.
	 *
	 * @since   2.9.6
	 * @access  public
	 */
	public function do_footer_scripts() {
		$default = sprintf(
			/* translators: %s is placeholder value */
			'<!-- %s -->',
			__(
				'Enter your scripts here',
				'themeisle-companion'
			)
		);
		$footer_scripts = get_theme_mod( 'obfx_footer_scripts', $default );
		echo wp_kses( $footer_scripts, $this->allowed_html );

		if ( is_singular() ) {
			global $post;
			$post_meta = get_post_meta( $post->ID, 'obfx_scripts', true );
			$post_meta = json_decode( $post_meta, true );
			$value = html_entity_decode( $post_meta['obfx-footer-scripts'], ENT_QUOTES );
			echo wp_kses( $value, $this->allowed_html );
		}
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
