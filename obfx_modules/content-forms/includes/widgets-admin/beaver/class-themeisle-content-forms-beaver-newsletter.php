<?php
/**
 * Beaver Newsletter Widget main class.
 *
 * @package ContentForms
 */

namespace ThemeIsle\ContentForms\Includes\Widgets_Admin\Beaver;

require_once 'beaver_widget_base.php';

/**
 * Class Newsletter_Admin
 * @package ThemeIsle\ContentForms\Includes\Widgets_Admin\Beaver
 */
class Newsletter_Admin extends Beaver_Widget_Base {


	/**
	 * Widget name.
	 *
	 * @return string
	 */
	function get_widget_name() {
		return esc_html__( 'Newsletter Form', 'textdomain' );
	}

	/**
	 * Define the form type
	 * @return string
	 */
	public function get_type() {
		return 'newsletter';
	}

	/**
	 * Set default values for registration widget.
	 *
	 * @return array
	 */
	public function widget_default_values() {
		return array(
			'fields'          => array(
				array(
					'key'         => 'email',
					'label'       => esc_html__( 'Email', 'textdomain' ),
					'placeholder' => esc_html__( 'Email', 'textdomain' ),
					'type'        => 'email',
					'required'    => 'required',
					'field_map'   => 'email',
					'field_width' => '75',
				),
			),
			'submit_label'    => esc_html__( 'Join Newsletter', 'textdomain' ),
			'success_message' => esc_html__( 'Welcome to our newsletter!', 'textdomain' ),
			'error_message'   => esc_html__( 'Action failed!', 'textdomain' ),
		);
	}

	/**
	 * Newsletter_Admin constructor.
	 */
	public function __construct() {
		parent::__construct(
			array(
				'name'        => esc_html__( 'Newsletter', 'textdomain' ),
				'description' => esc_html__( 'A simple newsletter form.', 'textdomain' ),
				'category'    => esc_html__( 'Orbit Fox Modules', 'textdomain' ),
				'dir'         => dirname( __FILE__ ),
				'url'         => plugin_dir_url( __FILE__ ),
			)
		);
	}

	/**
	 * Add map field for Newsletter field
	 * @param array $fields Repeater fields.
	 * @return array
	 */
	public function add_widget_repeater_fields( $fields ) {

		$fields['field_map'] = array(
			'label' => __( 'Map field to', 'textdomain' ),
			'type'  => 'text',
		);
		return $fields;
	}

	/**
	 * Add specific controls for this type of widget.
	 *
	 * @param array $fields Fields config.
	 * @return array
	 */
	public function add_widget_specific_controls( $fields ) {
		$providers = array(
			'mailchimp'  => esc_html__( 'MailChimp', 'textdomain' ),
			'sendinblue' => esc_html__( 'Sendinblue ', 'textdomain' ),
		);
		if ( version_compare( '7.1', phpversion() ) !== 1 ) {
			$providers['mailerlite'] = esc_html__( 'MailerLite', 'textdomain' );
		}
		$fields['fields'] = array(
			'provider'        => array(
				'type'    => 'select',
				'label'   => esc_html__( 'Subscribe to', 'textdomain' ),
				'options' => $providers,
			),
			'access_key'      => array(
				'type'  => 'text',
				'label' => esc_html__( 'Access Key', 'textdomain' ),
			),
			'list_id'         => array(
				'type'  => 'text',
				'label' => esc_html__( 'List ID', 'textdomain' ),
			),
			'success_message' => array(
				'type'    => 'text',
				'label'   => esc_html__( 'Success message', 'textdomain' ),
				'default' => $this->get_default( 'success_message' ),
			),
			'error_message'   => array(
				'type'    => 'text',
				'label'   => esc_html__( 'Error message', 'textdomain' ),
				'default' => $this->get_default( 'error_message' ),
			),
		) + $fields['fields'];

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
