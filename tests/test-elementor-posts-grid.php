<?php
/**
 * Post Type Grid Elementor widget markup tests.
 *
 * @package     Orbit_Fox
 * @subpackage  Orbit_Fox/tests
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

use ThemeIsle\ElementorExtraWidgets\Posts_Grid;

require_once __DIR__ . '/stubs/elementor.php';

if ( ORBIT_FOX_ELEMENTOR_DOUBLE_ACTIVE ) {
	require_once dirname( __DIR__ ) . '/obfx_modules/elementor-extra-widgets/widgets/elementor/posts-grid.php';
}

/**
 * Class Test_Elementor_Posts_Grid
 */
class Test_Elementor_Posts_Grid extends WP_UnitTestCase {

	public function tearDown(): void {
		wp_reset_postdata();
		parent::tearDown();
	}

	/**
	 * Saves the title through an Author so kses runs as it does for users without unfiltered_html.
	 */
	protected function author_post_with_title( string $title ): int {
		$author_id = self::factory()->user->create( array( 'role' => 'author' ) );
		wp_set_current_user( $author_id );

		return (int) wp_insert_post(
			array(
				'post_title'  => $title,
				'post_status' => 'publish',
				'post_author' => $author_id,
			)
		);
	}

	protected function render_title( int $post_id ): string {
		$GLOBALS['post'] = get_post( $post_id );
		setup_postdata( $GLOBALS['post'] );

		$widget = new Posts_Grid(
			array(
				'grid_title_hide' => '',
				'grid_title_tag'  => 'h2',
				'grid_title_link' => 'yes',
			)
		);

		$reflection = new ReflectionMethod( $widget, 'renderTitle' );
		$reflection->setAccessible( true );

		ob_start();
		$reflection->invoke( $widget );

		return (string) ob_get_clean();
	}

	public function test_issue_2104_title_cannot_break_out_of_link_title_attribute() {
		if ( ! ORBIT_FOX_ELEMENTOR_DOUBLE_ACTIVE ) {
			$this->markTestSkipped( 'The real Elementor plugin is loaded; the test double is not in use.' );
		}

		$post_id = $this->author_post_with_title( '</code><code>" onfocus="alert(1)" autofocus="' );
		$markup  = $this->render_title( $post_id );

		$document = new DOMDocument();
		libxml_use_internal_errors( true );
		$document->loadHTML( $markup );
		libxml_clear_errors();

		$link = $document->getElementsByTagName( 'a' )->item( 0 );

		$this->assertInstanceOf( DOMElement::class, $link );
		$this->assertFalse( $link->hasAttribute( 'onfocus' ) );
		$this->assertFalse( $link->hasAttribute( 'autofocus' ) );
		$this->assertSame( '" onfocus="alert(1)" autofocus="', $link->getAttribute( 'title' ) );
	}
}
