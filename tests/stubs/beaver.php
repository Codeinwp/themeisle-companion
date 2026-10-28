<?php
/**
 * Minimal stand-ins for the pieces of Beaver Builder that Content Forms markup touches,
 * so it can be rendered without the plugin.
 *
 * @package     Orbit_Fox
 * @subpackage  Orbit_Fox/tests
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

// The real plugin wins if it is loaded; the Beaver tests that need the model skip themselves in that case.
define( 'ORBIT_FOX_BEAVER_DOUBLE_ACTIVE', ! class_exists( 'FLBuilderModel' ) );

if ( ! class_exists( 'FLBuilderModule' ) ) {
	/**
	 * Stands in for Beaver Builder's module base.
	 */
	class FLBuilderModule {

		/**
		 * The module node id.
		 *
		 * @var string
		 */
		public $node;
	}
}

if ( ORBIT_FOX_BEAVER_DOUBLE_ACTIVE ) {
	/**
	 * Stands in for Beaver Builder's layout model.
	 */
	class FLBuilderModel {

		/**
		 * What get_layout_data() hands back: module nodes keyed by node id.
		 *
		 * @var array<string, object{settings: object}>
		 */
		public static $layout_data = array();

		/**
		 * @param string|null $status  Layout status.
		 * @param int|false   $post_id Post id.
		 *
		 * @return array<string, object{settings: object}>
		 */
		public static function get_layout_data( $status = null, $post_id = false ): array {
			return self::$layout_data;
		}

		public static function is_builder_active(): bool {
			return false;
		}
	}
}
