<?php
/**
 * Hestia Clients Bar section markup tests.
 *
 * @package     Orbit_Fox
 * @subpackage  Orbit_Fox/tests
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

require_once __DIR__ . '/stubs/hestia.php';
require_once dirname( __DIR__ ) . '/obfx_modules/companion-legacy/inc/hestia/inc/sections/hestia-clients-bar-section.php';

/**
 * Class Test_Hestia_Clients_Bar
 */
class Test_Hestia_Clients_Bar extends WP_UnitTestCase {

	public function tearDown(): void {
		remove_theme_mod( 'hestia_clients_bar_content' );
		parent::tearDown();
	}

	public function test_attachment_alt_cannot_break_out_of_img_alt_attribute() {
		$image_id = self::factory()->attachment->create_object( 'client-logo.jpg', 0, array( 'post_mime_type' => 'image/jpeg' ) );
		update_post_meta( $image_id, '_wp_attachment_image_alt', 'Logo" onerror="alert(1)' );

		set_theme_mod(
			'hestia_clients_bar_content',
			wp_json_encode( array( array( 'image_url' => wp_get_attachment_url( $image_id ) ) ) )
		);

		ob_start();
		hestia_clients_bar( true );
		$markup = (string) ob_get_clean();

		$document = new DOMDocument();
		libxml_use_internal_errors( true );
		$document->loadHTML( $markup );
		libxml_clear_errors();

		$image = $document->getElementsByTagName( 'img' )->item( 0 );

		$this->assertInstanceOf( DOMElement::class, $image );
		$this->assertFalse( $image->hasAttribute( 'onerror' ) );
		$this->assertSame( 'Logo" onerror="alert(1)', $image->getAttribute( 'alt' ) );
	}
}
