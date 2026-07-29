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

		// The common functions file resolves its own path through the module instance,
		// so it has to be loaded with the module as $this. Beaver Builder is not needed
		// for the helpers themselves.
		$module      = new Beaver_Widgets_OBFX_Module();
		$load_common = Closure::bind(
			function () {
				require_once $this->get_dir() . '/inc/common-functions.php';
			},
			$module,
			'Beaver_Widgets_OBFX_Module'
		);
		$load_common();

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
	 * @covers ::themeisle_sanitize_tag
	 */
	public function test_sanitize_tag_keeps_supported_tags() {
		foreach ( array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p' ) as $tag ) {
			$this->assertEquals( $tag, themeisle_sanitize_tag( $tag ) );
		}
	}

	/**
	 * Anything that is not a supported tag should fall back to a safe default.
	 *
	 * @covers ::themeisle_sanitize_tag
	 */
	public function test_sanitize_tag_falls_back_for_unsupported_tags() {
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
			$this->assertEquals( 'h1', themeisle_sanitize_tag( $tag ), 'Unsupported tag: ' . $tag );
		}
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
}
