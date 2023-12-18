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
	 * Header_Footer_Scripts_OBFX_Module constructor.
	 *
	 * @since   2.9.6
	 * @access  public
	 */
	public function __construct() {
		parent::__construct();
		$this->name = __( 'Header Footer Scripts', 'themeisle-companion' );

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

		/**
		 * Since we allow for the script meta to be unfiltered, we need to make sure that
		 * the current user is allowed to add unfiltered html. If not we prevent the meta from being saved or listed.
		 */
		$this->loader->add_filter( 'add_post_metadata', $this, 'check_post_metadata', 10, 5 );
		$this->loader->add_filter( 'update_post_metadata', $this, 'check_post_metadata', 10, 5 );
		$this->loader->add_filter( 'is_protected_meta', $this, 'is_meta_protected', 10, 3 );
	}

	/**
	 * Check if meta is protected.
	 *
	 * @param bool $protected Whether the key is considered protected.
	 * @param string $meta_key Metadata key.
	 * @param string $meta_type Type of object metadata is for. Accepts 'post', 'comment', 'term', 'user', or any other object type with an associated meta table.
	 *
	 * @return bool
	 */
	final public function is_meta_protected( $protected, $meta_key, $meta_type ) {
		if ( ! in_array( $meta_key, array( 'obfx-header-scripts', 'obfx-footer-scripts' ), true ) ) {
			return $protected;
		}

		if ( current_user_can( 'unfiltered_html' ) ) {
			return $protected;
		}

		return true;
	}

	/**
	 * @param null | bool $check Whether the meta key is allowed for update or add actions.
	 * @param int $object_id Object ID.
	 * @param string $meta_key Metadata key.
	 * @param mixed $meta_value Metadata value.
	 * @param mixed $prev_value Previous value of metadata.
	 *
	 * @return null | bool
	 */
	final public function check_post_metadata( $check, $object_id, $meta_key, $meta_value, $prev_value ) {
		if ( ! in_array( $meta_key, array( 'obfx-header-scripts', 'obfx-footer-scripts' ), true ) ) {
			return $check;
		}

		if ( current_user_can( 'unfiltered_html' ) ) {
			return $check;
		}

		return false;
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
				'default'    => sprintf(
				/* translators: %s is placeholder value */
					'<!-- %s -->',
					__(
						'Enter your scripts here',
						'themeisle-companion'
					)
				),
				'capability' => 'edit_theme_options',
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
				'default'    => sprintf(
				/* translators: %s is placeholder value */
					'<!-- %s -->',
					__(
						'Enter your scripts here',
						'themeisle-companion'
					)
				),
				'capability' => 'edit_theme_options',
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
			wp_nonce_field( 'obfx_hfs_metabox_nonce', $control_id . '_meta_nonce' );
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
		$post_meta = get_post_meta( $post->ID, $control_id, true );

		if ( $control_settings['type'] === 'textarea' ) {
			echo '<textarea class="widefat" rows="4" name="' . esc_attr( $control_id ) . '">';
			if ( ! empty( $post_meta ) ) {
				// phpcs:ignore
				echo $post_meta;
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
	 * @return  bool
	 * @since   2.9.6
	 * @access  public
	 */
	public function save_meta( $post_id ) {
		if ( ! current_user_can( 'unfiltered_html' ) ) {
			return false;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return false;
		}

		if ( ! isset( $_POST['post_type'] ) ) {
			return false;
		}
		if ( 'page' == $_POST['post_type'] && ! current_user_can( 'edit_page', $post_id ) ) {
			return false;
		}

		if ( 'post' == $_POST['post_type'] && ! current_user_can( 'edit_post', $post_id ) ) {
			return false;
		}

		foreach ( $this->meta_controls as $control_id => $control_settings ) {
			if ( ! isset( $_POST[ $control_id . '_meta_nonce' ] ) || ! wp_verify_nonce( $_POST[ $control_id . '_meta_nonce' ], 'obfx_hfs_metabox_nonce' ) ) {
				return false;
			}
			if ( ! isset( $_POST[ $control_id ] ) ) {
				continue;
			}
			update_post_meta(
				$post_id,
				$control_id,
				$_POST[ $control_id ]
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

		// phpcs:ignore
		echo get_theme_mod( 'obfx_header_scripts', $default );

		if ( is_singular() ) {
			global $post;

			// phpcs:ignore
			echo get_post_meta( $post->ID, 'obfx-header-scripts', true );
		}

		if ( class_exists( 'WooCommerce', false ) && is_shop() ) {
			$shop_id = get_option( 'woocommerce_shop_page_id' );
			// phpcs:ignore
			echo get_post_meta( $shop_id, 'obfx-header-scripts', true );
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

		// phpcs:ignore
		echo get_theme_mod( 'obfx_footer_scripts', $default );

		if ( is_singular() ) {
			global $post;

			// phpcs:ignore
			echo get_post_meta( $post->ID, 'obfx-footer-scripts', true );
		}

		if ( class_exists( 'WooCommerce', false ) && is_shop() ) {
			$shop_id = get_option( 'woocommerce_shop_page_id' );

			// phpcs:ignore
			echo get_post_meta( $shop_id, 'obfx-footer-scripts', true );
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
