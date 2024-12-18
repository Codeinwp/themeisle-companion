<?php

namespace ThemeIsle\ContentForms\Includes\Widgets_Admin\Beaver;

use ThemeIsle\ContentForms\Form_Manager;

require_once 'beaver_widget_base.php';


class Registration_Admin extends Beaver_Widget_Base {

	/**
	 * Widget name.
	 *
	 * @return string
	 */
	function get_widget_name() {
		return esc_html__( 'Registration Form', 'textdomain' );
	}

	/**
	 * Define the form type
	 * @return string
	 */
	public function get_type() {
		return 'registration';
	}

	/**
	 * Set default values for registration widget.
	 *
	 * @return array
	 */
	public function widget_default_values() {
		return array(
			'fields'       => array(
				array(
					'key'         => 'username',
					'label'       => esc_html__( 'User Name', 'textdomain' ),
					'placeholder' => esc_html__( 'User Name', 'textdomain' ),
					'type'        => 'text',
					'required'    => 'required',
					'field_map'   => 'user_login',
					'field_width' => '100',
				),
				array(
					'key'         => 'email',
					'label'       => esc_html__( 'Email', 'textdomain' ),
					'placeholder' => esc_html__( 'Email', 'textdomain' ),
					'type'        => 'email',
					'required'    => 'required',
					'field_map'   => 'user_email',
					'field_width' => '100',
				),
				array(
					'key'         => 'password',
					'label'       => esc_html__( 'Password', 'textdomain' ),
					'placeholder' => esc_html__( 'Password', 'textdomain' ),
					'type'        => 'password',
					'required'    => 'required',
					'field_map'   => 'user_pass',
					'field_width' => '100',
				),
			),
			'submit_label' => esc_html__( 'Register', 'textdomain' ),
		);
	}

	/**
	 * Registration_Admin constructor.
	 */
	public function __construct() {
		parent::__construct(
			array(
				'name'        => esc_html__( 'Registration', 'textdomain' ),
				'description' => esc_html__( 'A sign up form.', 'textdomain' ),
				'category'    => esc_html__( 'Orbit Fox Modules', 'textdomain' ),
				'dir'         => dirname( __FILE__ ),
				'url'         => plugin_dir_url( __FILE__ ),
			)
		);
	}

	/**
	 * Add widget repeater fields specific for contact widget.
	 *
	 * @param array $fields Widget fields.
	 *
	 * @return array
	 */
	function add_widget_repeater_fields( $fields ) {
		$field_types = array(
			'first_name'   => __( 'First Name', 'textdomain' ),
			'last_name'    => __( 'Last Name', 'textdomain' ),
			'user_pass'    => __( 'Password', 'textdomain' ),
			'user_login'   => __( 'Username', 'textdomain' ),
			'user_email'   => __( 'Email', 'textdomain' ),
			'display_name' => __( 'Display Name', 'textdomain' ),
		);

		$fields['field_map'] = array(
			'label'   => __( 'Map field to', 'textdomain' ),
			'type'    => 'select',
			'options' => $field_types,
		);
		return $fields;
	}

	/**
	 * Add specific controls for this type of widget.
	 *
	 * @param array $fields Fields config.
	 *
	 * @return array
	 */
	function add_widget_specific_controls( $fields ) {
		return $fields;
	}

	/**
	 * Get specific field types.
	 *
	 * @return array
	 */
	function get_specific_field_types() {
		return $this->field_types;
	}
}
