<?php
/**
 * Main class for Elementor Contact Form Custom Widget
 *
 * @package ContentForms
 */

namespace ThemeIsle\ContentForms\Includes\Widgets_Admin\Elementor;

use Elementor\Controls_Manager;

require_once TI_CONTENT_FORMS_PATH . '/includes/widgets-admin/elementor/elementor_widget_base.php';
/**
 * Class Contact_Admin
 */
class Contact_Admin extends Elementor_Widget_Base {

	/**
	 * The type of current widget form.
	 *
	 * @return string
	 */
	public function get_widget_type() {
		return 'contact';
	}

	/**
	 * Elementor Widget Name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'content_form_contact';
	}

	/**
	 * Get Widget Title.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Contact Form', 'textdomain' );
	}

	/**
	 * The default values for current widget.
	 *
	 * @return array
	 */
	public function get_default_config() {
		return array(
			array(
				'key'         => 'name',
				'type'        => 'text',
				'label'       => esc_html__( 'Name', 'textdomain' ),
				'requirement' => 'required',
				'placeholder' => esc_html__( 'Name', 'textdomain' ),
				'field_width' => '100',
			),
			array(
				'key'         => 'email',
				'type'        => 'email',
				'label'       => esc_html__( 'Email', 'textdomain' ),
				'requirement' => 'required',
				'placeholder' => esc_html__( 'Email', 'textdomain' ),
				'field_width' => '100',
			),
			array(
				'key'         => 'phone',
				'type'        => 'number',
				'label'       => esc_html__( 'Phone', 'textdomain' ),
				'requirement' => 'optional',
				'placeholder' => esc_html__( 'Phone', 'textdomain' ),
				'field_width' => '100',
			),
			array(
				'key'         => 'message',
				'type'        => 'textarea',
				'label'       => esc_html__( 'Message', 'textdomain' ),
				'requirement' => 'required',
				'placeholder' => esc_html__( 'Message', 'textdomain' ),
				'field_width' => '100',
			),
		);
	}

	/**
	 * No other required fields for this widget.
	 *
	 * @return bool
	 */
	function add_specific_form_fields() {
		return false;
	}

	/**
	 * Add specific settings for Contact Widget.
	 */
	function add_specific_settings_controls() {

		$this->add_control(
			'success_message',
			array(
				'type'    => 'text',
				'label'   => esc_html__( 'Success message', 'textdomain' ),
				'default' => esc_html__( 'Your message has been sent!', 'textdomain' ),
			)
		);

		$this->add_control(
			'error_message',
			array(
				'type'    => 'text',
				'label'   => esc_html__( 'Error message', 'textdomain' ),
				'default' => esc_html__( 'Oops! I cannot send this email!', 'textdomain' ),
			)
		);

		$this->add_control(
			'to_send_email',
			array(
				'type'        => 'text',
				'label'       => esc_html__( 'Send to', 'textdomain' ),
				'default'     => get_bloginfo( 'admin_email' ),
				'description' => esc_html__( 'Where should we send the email?', 'textdomain' ),
			)
		);

		$this->add_control(
			'submit_label',
			array(
				'type'    => 'text',
				'label'   => esc_html__( 'Submit', 'textdomain' ),
				'default' => esc_html__( 'Submit', 'textdomain' ),
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
	 * Add repeater specific fields.
	 *
	 * @param Object $repeater Repeater instance.
	 * @return bool
	 */
	function add_repeater_specific_fields( $repeater ) {
		$repeater->add_control(
			'hidden_value',
			array(
				'label'       => __( 'Value', 'textdomain' ),
				'description' => __( 'You can use the following magic tags to get additional information: {current_url}, {username}, {user_nice_name}, {user_type}, {user_email}', 'textdomain' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => '',
				'condition'   => array(
					'type' => 'hidden',
				),
			)
		);
	}

	/**
	 * Specific field types for Contact form.
	 *
	 * @return array
	 */
	function get_specific_field_types() {
		$field_types             = $this->field_types;
		$field_types['checkbox'] = esc_html__( 'Checkbox', 'textdomain' );
		$field_types['hidden']   = esc_html__( 'Hidden', 'textdomain' );
		return $field_types;
	}
}
