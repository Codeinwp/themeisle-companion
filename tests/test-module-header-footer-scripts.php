<?php
/**
 * Header/footer scripts module tests.
 *
 * @package     Orbit_Fox
 * @subpackage  Orbit_Fox/tests
 */
class Test_Module_Header_Footer_Scripts extends WP_UnitTestCase {

	/**
	 * Test that global script controls require permission to publish raw HTML.
	 */
	public function test_global_script_controls_require_unfiltered_html() {
		$manager = new Test_OBFX_Customize_Manager();
		$module  = new Header_Footer_Scripts_OBFX_Module();

		$module->register_customizer_controls( $manager );

		$this->assertSame( 'unfiltered_html', $manager->sections['obfx_header_footer_scripts']['capability'] );
		$this->assertSame( 'unfiltered_html', $manager->settings['obfx_header_scripts']['capability'] );
		$this->assertSame( 'unfiltered_html', $manager->settings['obfx_footer_scripts']['capability'] );
	}
}

/**
 * Minimal Customizer manager used to inspect registered controls.
 */
class Test_OBFX_Customize_Manager {

	/**
	 * Registered sections.
	 *
	 * @var array
	 */
	public $sections = array();

	/**
	 * Registered settings.
	 *
	 * @var array
	 */
	public $settings = array();

	/**
	 * Store a registered section.
	 *
	 * @param string $id   Section identifier.
	 * @param array  $args Section arguments.
	 */
	public function add_section( $id, $args ) {
		$this->sections[ $id ] = $args;
	}

	/**
	 * Store a registered setting.
	 *
	 * @param string $id   Setting identifier.
	 * @param array  $args Setting arguments.
	 */
	public function add_setting( $id, $args ) {
		$this->settings[ $id ] = $args;
	}

	/**
	 * Ignore controls for this test.
	 *
	 * @param string $id   Control identifier.
	 * @param array  $args Control arguments.
	 */
	public function add_control( $id, $args ) {
	}
}
