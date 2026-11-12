<?php
/**
 * Module docs links shown on the dashboard.
 *
 * @package     Orbit_Fox
 * @subpackage  Orbit_Fox/tests
 */

/**
 * The "Docs" badge of each module opens its docs page directly (a redirect would drop the UTMs), with the UTMs.
 */
class Test_Module_Docs_Links extends WP_UnitTestCase {

	/**
	 * A module with its own docs page.
	 */
	public function test_a_module_page_link_has_the_utms() {
		$module = new Custom_Fonts_OBFX_Module();
		$module->set_module_strings();

		$this->assertSame(
			'https://docs.themeisle.com/orbit-fox/custom-fonts-orbit-fox-module?utm_source=wpadmin&utm_medium=dashboard&utm_campaign=custom-fonts&utm_content=docsorbit-foxcustom-fonts-orbit-fox-module',
			$module->get_documentation_link()
		);
	}

	/**
	 * A module documented in a section of the main page: the UTMs go before the anchor.
	 */
	public function test_a_section_link_keeps_its_anchor_after_the_utms() {
		$module = new Svg_Uploads_OBFX_Module();
		$module->set_module_strings();

		$this->assertSame(
			'https://docs.themeisle.com/orbit-fox/orbit-fox-documentation?utm_source=wpadmin&utm_medium=dashboard&utm_campaign=svg-uploads&utm_content=docsorbit-foxorbit-fox-documentation#9-svg-support',
			$module->get_documentation_link()
		);
	}

	/**
	 * No docs page, no link.
	 */
	public function test_a_module_without_docs_has_no_link() {
		$module                    = new Custom_Fonts_OBFX_Module();
		$module->documentation_url = '';

		$this->assertSame( '', $module->get_documentation_link() );
	}
}
