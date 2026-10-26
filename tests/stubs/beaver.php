<?php
/**
 * Minimal stand-in for Beaver Builder's module base, so Content Forms markup can be
 * rendered without the plugin.
 *
 * @package     Orbit_Fox
 * @subpackage  Orbit_Fox/tests
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

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
