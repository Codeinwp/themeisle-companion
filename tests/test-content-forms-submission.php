<?php
/**
 * Content Forms REST submission tests.
 *
 * @package     Orbit_Fox
 * @subpackage  Orbit_Fox/tests
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

use ThemeIsle\ContentForms\Includes\Admin\Widget_Actions_Base;
use ThemeIsle\ContentForms\Includes\Widgets_Public\Contact_Public;

/**
 * Class Test_Content_Forms_Submission
 */
class Test_Content_Forms_Submission extends WP_UnitTestCase {

	/**
	 * The shape the REST controller hands to the submission filter.
	 *
	 * @var array
	 */
	protected $return;

	public function setUp(): void {
		parent::setUp();

		require_once TI_CONTENT_FORMS_PATH . '/includes/widgets-public/contact_public.php';

		$this->return = array(
			'success' => false,
			'message' => 'Something went wrong',
		);
	}

	/**
	 * An unrecognized builder has no settings to look up.
	 */
	public function test_widget_settings_are_false_for_an_unsupported_builder() {
		$this->assertFalse( Widget_Actions_Base::get_widget_settings( 'abc123', 1, 'gutenberg' ) );
	}

	/**
	 * The contact handler used to call array_key_exists() on the settings lookup
	 * before checking it, which throws a TypeError on PHP 8 when the lookup returns false.
	 */
	public function test_contact_submission_returns_response_when_settings_are_unavailable() {
		$contact = new Contact_Public();

		foreach ( array( 'gutenberg', '', 'divi' ) as $builder ) {
			$result = $contact->rest_submit_form(
				$this->return,
				array( 'NAME' => 'Jane', 'EMAIL' => 'jane@example.com' ),
				'abc123',
				1,
				$builder
			);

			$this->assertIsArray( $result, 'Unsupported builder "' . $builder . '" must return the response array.' );
			$this->assertFalse( $result['success'] );
			$this->assertSame( $this->return, $result );
		}
	}

	/**
	 * The same crash reached through the registered submission filter,
	 * which is how the REST controller invokes the handler.
	 */
	public function test_contact_submission_filter_survives_an_unsupported_builder() {
		$contact = new Contact_Public();
		$contact->init();

		$this->assertNotFalse( has_filter( 'content_forms_submit_contact' ), 'The contact handler must be hooked, or this test proves nothing.' );

		$result = apply_filters(
			'content_forms_submit_contact',
			$this->return,
			array( 'NAME' => 'Jane' ),
			'abc123',
			1,
			'gutenberg'
		);

		$this->assertIsArray( $result );
		$this->assertSame( $this->return, $result );

		remove_filter( 'content_forms_submit_contact', array( $contact, 'rest_submit_form' ), 10 );
	}

	/**
	 * A resolvable form still reaches its own configuration check rather than the generic response.
	 */
	public function test_contact_submission_reports_wrong_email_configuration() {
		$result = $this->invoke_with_settings(
			array(
				'to_send_email' => 'not-an-email',
				'form_fields'   => array(),
			)
		);

		$this->assertStringContainsString( 'Wrong email configuration', $result['message'] );
	}

	/**
	 * Run rest_submit_form against a fixed settings array, bypassing the builder lookup.
	 *
	 * @param array $settings Settings the lookup should resolve to.
	 *
	 * @return array
	 */
	protected function invoke_with_settings( $settings ) {
		$stub = new class() extends Contact_Public {
			/**
			 * Settings the faked lookup returns.
			 *
			 * @var array
			 */
			public static $stub_settings = array();

			public static function get_widget_settings( $widget_id, $post_id, $builder ) {
				return self::$stub_settings;
			}
		};

		$stub::$stub_settings = $settings;

		return $stub->rest_submit_form( $this->return, array(), 'abc123', 1, 'beaver' );
	}
}
