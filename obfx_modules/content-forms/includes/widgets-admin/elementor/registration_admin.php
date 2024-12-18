<?php
/**
 * Main class for Elementor Registration Form Custom Widget
 *
 * @package ContentForms
 */

namespace ThemeIsle\ContentForms\Includes\Widgets_Admin\Elementor;

use Elementor\Controls_Manager;
use ThemeIsle\ContentForms\Form_Manager;

require_once TI_CONTENT_FORMS_PATH . '/includes/widgets-admin/elementor/elementor_widget_base.php';

/**
 * Class Registration_Admin
 *
 * @package ThemeIsle\ContentForms\Includes\Widgets\Elementor\Registration
 */
class Registration_Admin extends Elementor_Widget_Base {


	/**
	 * The type of current widget form.
	 *
	 * @return string
	 */
	public function get_widget_type() {
		return 'registration';
	}

	/**
	 * @var array
	 */
	public $strings = array();

	/**
	 * Elementor Widget Name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'content_form_registration';
	}

	/**
	 * Get Widget Title.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Registration Form', 'textdomain' );
	}

	/**
	 * The default values for current widget.
	 *
	 * @return array
	 */
	function get_default_config() {
		return array(
			array(
				'key'         => 'username',
				'type'        => 'text',
				'label'       => esc_html__( 'User Name', 'textdomain' ),
				'require'     => 'required',
				'placeholder' => esc_html__( 'User Name', 'textdomain' ),
				'field_width' => '100',
				'field_map'   => 'user_login',
			),
			array(
				'key'         => 'email',
				'type'        => 'email',
				'label'       => esc_html__( 'Email', 'textdomain' ),
				'require'     => 'required',
				'placeholder' => esc_html__( 'Email', 'textdomain' ),
				'field_width' => '100',
				'field_map'   => 'user_email',
			),
			array(
				'key'         => 'password',
				'type'        => 'password',
				'label'       => esc_html__( 'Password', 'textdomain' ),
				'require'     => 'required',
				'placeholder' => esc_html__( 'Password', 'textdomain' ),
				'field_width' => '100',
				'field_map'   => 'user_pass',
			),
		);
	}

	/**
	 * Add specific form fields for Registration Widget.
	 */
	function add_specific_form_fields() {
		return false;
	}

	/**
	 * Add specific settings for Newsletter widget.
	 */
	function add_specific_settings_controls() {
		$this->add_control(
			'submit_label',
			array(
				'type'    => 'text',
				'label'   => esc_html__( 'Submit', 'textdomain' ),
				'default' => esc_html__( 'Register', 'textdomain' ),
			)
		);

		$this->add_responsive_control(
			'align_submit',
			array(
				'label'     => __( 'Alignment', 'textdomain' ),
				'type'      => Controls_Manager::CHOOSE,
				'toggle'    => false,
				'default'   => 'left',
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'textdomain' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'textdomain' ),
						'icon'  => 'fa fa-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'textdomain' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .content-form .submit-form' => 'text-align: {{VALUE}};',
				),
			)
		);
	}

	/**
	 * Add specific widget settings.
	 */
	function add_widget_specific_settings() {
		return false;
	}

	/**
	 * Add repeater registration specific field.
	 *
	 * @param Object $repeater Repeater instance.
	 */
	function add_repeater_specific_fields( $repeater ) {
		$field_types = array(
			'first_name'   => __( 'First Name', 'textdomain' ),
			'last_name'    => __( 'Last Name', 'textdomain' ),
			'user_pass'    => __( 'Password', 'textdomain' ),
			'user_login'   => __( 'Username', 'textdomain' ),
			'user_email'   => __( 'Email', 'textdomain' ),
			'display_name' => __( 'Display Name', 'textdomain' ),
		);
		$repeater->add_control(
			'field_map',
			array(
				'label'   => __( 'Map field to', 'textdomain' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $field_types,
				'default' => 'text',
			)
		);
	}

	/**
	 * Specific field types for Registration form.
	 *
	 * @return array
	 */
	function get_specific_field_types() {
		return $this->field_types;
	}
}
