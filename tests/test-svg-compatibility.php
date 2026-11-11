<?php
/**
 * Legacy SVG compatibility tests.
 *
 * @package Orbit_Fox
 */

/**
 * Class Test_Svg_Compatibility
 */
class Test_Svg_Compatibility extends WP_UnitTestCase {

	/**
	 * @var string
	 */
	private $svg_file;

	public function setUp(): void {
		parent::setUp();

		$this->svg_file = sys_get_temp_dir() . '/orbit-fox-legacy-illustrator.svg';
	}

	public function tearDown(): void {
		if ( file_exists( $this->svg_file ) ) {
			unlink( $this->svg_file );
		}

		parent::tearDown();
	}

	/**
	 * Older Illustrator namespace entities are resolved before DTD removal.
	 */
	public function test_resolves_legacy_illustrator_namespace_entities(): void {
		$svg      = $this->get_legacy_illustrator_svg( true );
		$resolved = Orbit_Fox_Svg_Compatibility::resolve_legacy_namespace_entities( $svg );

		$this->assertStringContainsString( 'xmlns="http://www.w3.org/2000/svg"', $resolved );
		$this->assertStringContainsString( 'xmlns:xlink="http://www.w3.org/1999/xlink"', $resolved );
		$this->assertStringContainsString( 'xmlns:i="http://ns.adobe.com/AdobeIllustrator/10.0/"', $resolved );
		$this->assertStringContainsString( '<!ENTITY ns_svg "https://example.invalid/svg">', $resolved );
		$this->assertStringContainsString( '<!ENTITY payload', $resolved );
		$this->assertStringContainsString( '&payload;', $resolved );
	}

	/**
	 * Namespace-like text outside exact xmlns attributes remains untouched.
	 */
	public function test_does_not_rewrite_namespace_like_text_or_attributes(): void {
		$svg = '<!-- <svg xmlns="&ns_svg;"></svg> -->
<![CDATA[<svg xmlns="&ns_svg;"></svg>]]>
<svg xmlns="http://www.w3.org/2000/svg" data-xmlns="&ns_svg;" data-note=\' xmlns="&ns_svg;"\' data-é=\' xmlns="&ns_svg;"\' XMLNS="&ns_svg;">
	<text>xmlns="&ns_svg;"</text>
</svg>';

		$this->assertSame( $svg, Orbit_Fox_Svg_Compatibility::resolve_legacy_namespace_entities( $svg ) );
	}

	/**
	 * Document-defined entities outside the fixed namespace list remain rejected.
	 */
	public function test_does_not_resolve_other_document_entities(): void {
		$svg       = $this->get_legacy_illustrator_svg( true );
		$resolved  = Orbit_Fox_Svg_Compatibility::resolve_legacy_namespace_entities( $svg );
		$sanitizer = new enshrined\svgSanitize\Sanitizer();

		$this->assertFalse( $sanitizer->sanitize( $resolved ) );
	}

	/**
	 * The upload module accepts the normalized legacy export without loading its DTD.
	 */
	public function test_svg_upload_sanitizes_legacy_illustrator_export(): void {
		file_put_contents( $this->svg_file, $this->get_legacy_illustrator_svg() );

		$module     = new Svg_Uploads_OBFX_Module();
		$reflection = new ReflectionClass( $module );
		$load       = $reflection->getMethod( 'load_sanitizer' );
		$sanitize   = $reflection->getMethod( 'sanitize' );
		$load->setAccessible( true );
		$sanitize->setAccessible( true );
		$load->invoke( $module );

		$result             = $sanitize->invoke( $module, $this->svg_file );
		$sanitizer_property = $reflection->getProperty( 'sanitizer' );
		$sanitizer_property->setAccessible( true );
		$issues = $sanitizer_property->getValue( $module )->getXmlIssues();

		$this->assertTrue( $result, wp_json_encode( $issues ) );

		$clean = file_get_contents( $this->svg_file );
		$this->assertStringContainsString( 'xmlns="http://www.w3.org/2000/svg"', $clean );
		$this->assertStringNotContainsString( 'https://example.invalid/svg', $clean );
		$this->assertStringNotContainsString( '<!DOCTYPE', $clean );
	}

	/**
	 * The custom-font path uses the same normalization before sanitizing.
	 */
	public function test_custom_fonts_sanitizes_legacy_illustrator_export(): void {
		file_put_contents( $this->svg_file, $this->get_legacy_illustrator_svg() );

		$admin      = new Custom_Fonts_Admin();
		$reflection = new ReflectionClass( $admin );
		$sanitize   = $reflection->getMethod( 'sanitize_svg' );
		$sanitize->setAccessible( true );

		$this->assertTrue( $sanitize->invoke( $admin, $this->svg_file ) );

		$clean = file_get_contents( $this->svg_file );
		$this->assertStringContainsString( 'xmlns="http://www.w3.org/2000/svg"', $clean );
		$this->assertStringNotContainsString( 'https://example.invalid/svg', $clean );
		$this->assertStringNotContainsString( '<!DOCTYPE', $clean );
	}

	/**
	 * Build an older Illustrator-style export with fixed namespace entities and
	 * an unrelated entity that must never be expanded by the compatibility step.
	 *
	 * @return string
	 */
	private function get_legacy_illustrator_svg( $include_unrelated_entity = false ) {
		$unrelated_entity = $include_unrelated_entity ? "\n\t<!ENTITY payload \"not-expanded\">" : '';
		$title            = $include_unrelated_entity ? '<title>&payload;</title>' : '<title>Legacy export</title>';

		return '<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd" [
	<!ENTITY ns_svg "https://example.invalid/svg">
	<!ENTITY ns_xlink "https://example.invalid/xlink">
	<!ENTITY ns_ai "https://example.invalid/illustrator">
	<!-- ]> <svg xmlns="&ns_svg;"></svg> -->' . $unrelated_entity . '
]>
<svg xmlns="&ns_svg;" xmlns:xlink="&ns_xlink;" xmlns:i="&ns_ai;" width="100" height="100">
	' . $title . '
	<rect width="100" height="100" fill="red"/>
</svg>';
	}
}
