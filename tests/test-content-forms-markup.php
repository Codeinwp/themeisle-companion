<?php
/**
 * Content Forms front-end markup tests.
 *
 * @package     Orbit_Fox
 * @subpackage  Orbit_Fox/tests
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

use ThemeIsle\ContentForms\Includes\Widgets_Admin\Beaver\Beaver_Widget_Base;
use ThemeIsle\ContentForms\Includes\Widgets_Admin\Elementor\Elementor_Widget_Base;

require_once __DIR__ . '/stubs/elementor.php';
require_once __DIR__ . '/stubs/beaver.php';
require_once TI_CONTENT_FORMS_PATH . '/includes/widgets-admin/beaver/beaver_widget_base.php';

if ( ORBIT_FOX_ELEMENTOR_DOUBLE_ACTIVE ) {
	require_once TI_CONTENT_FORMS_PATH . '/includes/widgets-admin/elementor/elementor_widget_base.php';

	/**
	 * Concrete Elementor contact form over the widget base double.
	 */
	class Orbit_Fox_Elementor_Form_Double extends Elementor_Widget_Base {

		public function get_widget_type(): string {
			return 'contact';
		}

		public function get_name(): string {
			return 'content_form_contact';
		}

		public function add_repeater_specific_fields( $repeater ): void {}

		public function add_widget_specific_settings(): void {}

		/**
		 * @return array{}
		 */
		public function get_default_config(): array {
			return array();
		}

		public function add_specific_form_fields(): void {}

		public function add_specific_settings_controls(): void {}

		/**
		 * @return array{}
		 */
		public function get_specific_field_types(): array {
			return array();
		}
	}
}

/**
 * Concrete Beaver contact form over the module base double.
 */
class Orbit_Fox_Beaver_Form_Double extends Beaver_Widget_Base {

	public function get_type(): string {
		return 'contact';
	}

	public function get_widget_name(): string {
		return 'Contact';
	}

	/**
	 * @param array<string, mixed> $fields Repeater fields.
	 *
	 * @return array<string, mixed>
	 */
	public function add_widget_repeater_fields( $fields ): array {
		return $fields;
	}

	/**
	 * @param array<string, mixed> $fields Widget controls.
	 *
	 * @return array<string, mixed>
	 */
	public function add_widget_specific_controls( $fields ): array {
		return $fields;
	}

	/**
	 * @return array{}
	 */
	public function widget_default_values(): array {
		return array();
	}

	/**
	 * @return array{}
	 */
	public function get_specific_field_types(): array {
		return array();
	}
}

/**
 * Stands in for the Beaver module the submit template renders against.
 */
class Orbit_Fox_Beaver_Template_Module_Double {

	/**
	 * The module node id.
	 *
	 * @var string
	 */
	public $node = 'node1';

	public function get_type(): string {
		return 'contact';
	}

	public function render_form_header( string $id ): void {}

	public function maybe_render_form_errors( string $id ): bool {
		return false;
	}

	public function render_form_footer(): void {}
}

/**
 * Class Test_Content_Forms_Markup
 */
class Test_Content_Forms_Markup extends WP_UnitTestCase {

	public function tearDown(): void {
		Orbit_Fox_Elementor_Editor_Double::$edit_mode = false;
		if ( ORBIT_FOX_BEAVER_DOUBLE_ACTIVE ) {
			FLBuilderModel::$layout_data = array();
		}
		wp_dequeue_script( 'content-forms' );

		parent::tearDown();
	}

	/**
	 * The Elementor cases drive a stand-in for \Elementor\Widget_Base, so they are meaningless
	 * on an environment where the real plugin defined the class first.
	 */
	protected function require_elementor_double(): void {
		if ( ! ORBIT_FOX_ELEMENTOR_DOUBLE_ACTIVE ) {
			$this->markTestSkipped( 'The real Elementor plugin is loaded; the test double is not in use.' );
		}
	}

	/**
	 * @param array<string, mixed> $settings Widget settings.
	 */
	protected function elementor_form( array $settings ): Orbit_Fox_Elementor_Form_Double {
		return new Orbit_Fox_Elementor_Form_Double( $settings, array( 'id' => 'form1' ) );
	}

	/**
	 * @param Orbit_Fox_Elementor_Form_Double|Orbit_Fox_Beaver_Form_Double $form   Form to render with.
	 * @param string                                                        $method Method that prints the markup.
	 * @param list<mixed>                                                   $args   Method arguments.
	 */
	protected function capture( $form, string $method, array $args = array() ): string {
		$reflection = new ReflectionMethod( $form, $method );
		$reflection->setAccessible( true );

		ob_start();
		$reflection->invokeArgs( $form, $args );

		return (string) ob_get_clean();
	}

	protected function beaver_form(): Orbit_Fox_Beaver_Form_Double {
		$form       = ( new ReflectionClass( Orbit_Fox_Beaver_Form_Double::class ) )->newInstanceWithoutConstructor();
		$form->node = 'node1';

		return $form;
	}

	/**
	 * @param array<string, string> $settings Module settings.
	 */
	protected function render_beaver_template( array $settings ): string {
		$module   = new Orbit_Fox_Beaver_Template_Module_Double();
		$settings = (object) array_merge( array( 'fields' => array() ), $settings );

		ob_start();
		include TI_CONTENT_FORMS_PATH . '/includes/widgets-admin/beaver/includes/frontend.php';

		return (string) ob_get_clean();
	}

	/**
	 * Field width classes must reach the browser as a quoted class attribute.
	 */
	public function test_elementor_field_keeps_its_width_classes(): void {
		$this->require_elementor_double();

		$field = array(
			'_id'         => 'name1',
			'type'        => 'text',
			'key'         => 'name',
			'label'       => 'Name',
			'requirement' => 'optional',
			'placeholder' => '',
			'field_width' => '33',
		);

		$html = $this->capture( $this->elementor_form( array( 'hide_label' => 'show' ) ), 'render_form_field', array( $field ) );

		$this->assertStringContainsString( '<fieldset class="content-form-field-text elementor-column elementor-col-33">', $html );
		$this->assertStringNotContainsString( '&quot;', $html );
	}

	/**
	 * The editor's notification previews keep their alignment style.
	 */
	public function test_elementor_notification_preview_keeps_its_alignment(): void {
		$this->require_elementor_double();
		Orbit_Fox_Elementor_Editor_Double::$edit_mode = true;

		$html = $this->capture( $this->elementor_form( array( 'notification_alignment' => 'right' ) ), 'maybe_render_form_notification' );

		foreach ( array( 'success', 'error' ) as $status ) {
			$this->assertSame(
				1,
				preg_match( '/<h3 style="margin-right:\s*0;\s*margin-left:\s*auto;\s*text-align:\s*right" class="content-form-notice content-form-' . $status . '">/', $html ),
				$html
			);
		}
	}

	/**
	 * The submit button's icon span keeps its icon class.
	 */
	public function test_elementor_submit_icon_keeps_its_class(): void {
		$this->require_elementor_double();

		$settings = array(
			'form_fields'            => array(
				array(
					'_id'         => 'name1',
					'type'        => 'text',
					'key'         => 'name',
					'label'       => 'Name',
					'requirement' => 'optional',
					'placeholder' => '',
					'field_width' => '100',
				),
			),
			'submit_label'           => 'Send',
			'button_icon_new'        => array(
				'value'   => 'fas fa-paper-plane',
				'library' => 'fa-solid',
			),
			'hide_label'             => 'show',
			'notification_alignment' => 'left',
		);

		$html = $this->capture( $this->elementor_form( $settings ), 'render' );

		$this->assertSame( 1, preg_match( '/<span class="[^"]*\belementor-button-icon\b[^"]*">/', $html ), $html );
		$this->assertStringNotContainsString( '&quot;', $html );
	}

	/**
	 * A placeholder with spaces and markup characters is printed whole and escaped once.
	 */
	public function test_beaver_placeholder_is_one_quoted_attribute(): void {
		$placeholder = 'Name & "nickname"';
		$expected    = 'placeholder="Name &amp; &quot;nickname&quot;"';

		foreach ( array( 'text', 'textarea' ) as $type ) {
			$field = array(
				'type'        => $type,
				'key'         => 'name',
				'label'       => '',
				'required'    => '',
				'placeholder' => $placeholder,
			);

			$html = $this->capture( $this->beaver_form(), 'render_form_field', array( $field, 'hide' ) );

			$this->assertStringContainsString( $expected, $html, $type );
			$this->assertStringNotContainsString( '&amp;amp;', $html, $type );
		}
	}

	/**
	 * The MailChimp address inputs print their multi-word placeholders whole.
	 */
	public function test_beaver_address_placeholders_are_quoted(): void {
		if ( ! ORBIT_FOX_BEAVER_DOUBLE_ACTIVE ) {
			$this->markTestSkipped( 'The real Beaver Builder plugin is loaded; the test double is not in use.' );
		}

		FLBuilderModel::$layout_data = array(
			'node1' => (object) array(
				'settings' => (object) array(
					'provider'   => 'mailchimp',
					'hide_label' => 'show',
				),
			),
		);

		$field = array(
			'type'        => 'text',
			'field_map'   => 'address',
			'required'    => 'required',
			'placeholder' => 'Street address',
		);

		$html = $this->capture( $this->beaver_form(), 'maybe_render_newsletter_address', array( $field, 'node1' ) );

		$this->assertStringContainsString( 'placeholder="Address Line 2"', $html );
		$this->assertStringContainsString( 'placeholder="City"', $html );
	}

	/**
	 * A text input closes cleanly after its placeholder.
	 */
	public function test_beaver_text_input_has_no_stray_quote(): void {
		$field = array(
			'type'        => 'text',
			'key'         => 'name',
			'label'       => '',
			'required'    => '',
			'placeholder' => 'Name',
		);

		$html = $this->capture( $this->beaver_form(), 'render_form_field', array( $field, 'hide' ) );

		$this->assertStringContainsString( 'placeholder="Name">', $html );
	}

	/**
	 * The submit fieldset carries the classes its styles target.
	 */
	public function test_beaver_submit_fieldset_keeps_its_classes(): void {
		$this->assertStringContainsString( '<fieldset class="submit-field">', $this->render_beaver_template( array() ) );
		$this->assertStringContainsString(
			'<fieldset class="submit-field submit-form contact">',
			$this->render_beaver_template( array( 'submit_display' => 'block' ) )
		);
	}
}
