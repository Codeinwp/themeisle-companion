<?php
/**
 * WordPress unit test plugin.
 *
 * @package     Orbit_Fox
 * @subpackage  Orbit_Fox/tests
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */
class Test_Module_Beaver_Widgets extends WP_UnitTestCase {

	/**
	 * Path to the pricing table frontend template.
	 *
	 * @var string
	 */
	protected $pricing_table_template;

	public function setUp(): void {
		parent::setUp();

		$this->pricing_table_template = dirname( dirname( __FILE__ ) ) . '/obfx_modules/beaver-widgets/modules/pricing-table/includes/frontend.php';
	}

	/**
	 * Render the pricing table frontend template.
	 *
	 * @param array $args Settings to overwrite the defaults with.
	 *
	 * @return string
	 */
	protected function render_pricing_table( $args = array() ) {
		$settings = (object) array_merge(
			array(
				'card_layout'       => 'yes',
				'plan_title'        => 'Plan title',
				'plan_title_tag'    => 'h2',
				'plan_subtitle'     => 'Plan subtitle',
				'plan_subtitle_tag' => 'p',
				'price'             => '50',
				'currency'          => '$',
				'currency_position' => 'after',
				'period'            => '/mo',
				'features'          => array(),
				'text'              => 'Buy now',
				'link'              => 'https://example.com',
			),
			$args
		);

		ob_start();
		include $this->pricing_table_template;

		return ob_get_clean();
	}

	/**
	 * Supported tags should be returned unchanged.
	 *
	 * @covers Orbit_Fox::sanitize_html_tag
	 */
	public function test_sanitize_html_tag_keeps_supported_tags() {
		foreach ( array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p' ) as $tag ) {
			$this->assertEquals( $tag, apply_filters( 'obfx_sanitize_html_tag', $tag ) );
		}
	}

	/**
	 * Anything that is not a supported tag should fall back to a safe default.
	 *
	 * @covers Orbit_Fox::sanitize_html_tag
	 */
	public function test_sanitize_html_tag_falls_back_for_unsupported_tags() {
		$unsupported = array(
			'h1 onmouseover=alert(document.cookie)',
			'p onclick=alert(1)',
			'h2 class="evil"',
			'script',
			'div',
			'H1',
			'',
		);

		foreach ( $unsupported as $tag ) {
			$this->assertEquals( 'h2', apply_filters( 'obfx_sanitize_html_tag', $tag ), 'Unsupported tag: ' . $tag );
		}
	}

	/**
	 * Callers can narrow the allowed tags and pick their own fallback.
	 *
	 * @covers Orbit_Fox::sanitize_html_tag
	 */
	public function test_sanitize_html_tag_honours_custom_allowed_list_and_default() {
		$allowed = array( 'h3', 'h4' );

		$this->assertEquals( 'h3', apply_filters( 'obfx_sanitize_html_tag', 'h3', $allowed, 'p' ) );
		$this->assertEquals( 'p', apply_filters( 'obfx_sanitize_html_tag', 'h1', $allowed, 'p' ) );
		$this->assertEquals( 'p', apply_filters( 'obfx_sanitize_html_tag', 'script', $allowed, 'p' ) );
	}

	/**
	 * The pricing table should render the configured heading tags.
	 */
	public function test_pricing_table_renders_configured_tags() {
		$output = $this->render_pricing_table(
			array(
				'plan_title_tag'    => 'h3',
				'plan_subtitle_tag' => 'h4',
			)
		);

		$this->assertStringContainsString( '<h3 class="obfx-plan-title text-center">Plan title</h3>', $output );
		$this->assertStringContainsString( '<h4 class="obfx-plan-subtitle text-center">Plan subtitle</h4>', $output );
	}

	/**
	 * The pricing table should reject unsafe heading tags and fall back to its defaults.
	 */
	public function test_pricing_table_sanitizes_unsupported_tags() {
		$output = $this->render_pricing_table(
			array(
				'plan_title_tag'    => 'h3 onmouseover=alert(1)',
				'plan_subtitle_tag' => 'script',
			)
		);

		$this->assertStringContainsString( '<h2 class="obfx-plan-title text-center">Plan title</h2>', $output );
		$this->assertStringContainsString( '<p class="obfx-plan-subtitle text-center">Plan subtitle</p>', $output );
		$this->assertStringNotContainsString( 'onmouseover', $output );
		$this->assertStringNotContainsString( '<script', $output );
	}
}
