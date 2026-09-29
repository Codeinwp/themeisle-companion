<?php
/**
 * Minimal stand-ins for the pieces of Elementor that the Content Forms settings
 * lookup touches, so its failure paths can be exercised without the plugin.
 *
 * @package     Orbit_Fox
 * @subpackage  Orbit_Fox/tests
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

namespace {
	// The real plugin wins if it is loaded; the Elementor tests skip themselves in that case.
	define( 'ORBIT_FOX_ELEMENTOR_DOUBLE_ACTIVE', ! class_exists( '\Elementor\Plugin' ) );

	/**
	 * Stands in for an Elementor document.
	 */
	class Orbit_Fox_Elementor_Document_Double {

		/**
		 * The element tree this document reports.
		 *
		 * @var array
		 */
		protected $elements_data;

		/**
		 * @param array $elements_data The element tree this document reports.
		 */
		public function __construct( $elements_data ) {
			$this->elements_data = $elements_data;
		}

		/**
		 * @return array
		 */
		public function get_elements_data() {
			return $this->elements_data;
		}
	}

	/**
	 * Stands in for Elementor's documents manager, which returns false for a post it cannot open.
	 */
	class Orbit_Fox_Elementor_Documents_Double {

		/**
		 * What get() hands back. False models a post Elementor cannot open.
		 *
		 * @var Orbit_Fox_Elementor_Document_Double|false
		 */
		public static $document = false;

		/**
		 * @param int $post_id Post id.
		 *
		 * @return Orbit_Fox_Elementor_Document_Double|false
		 */
		public function get( $post_id ) {
			return self::$document;
		}
	}
}

namespace Elementor {
	if ( ORBIT_FOX_ELEMENTOR_DOUBLE_ACTIVE ) {
		/**
		 * Stands in for Elementor's plugin singleton.
		 */
		class Plugin {

			/**
			 * The singleton the lookup reads.
			 *
			 * @var Plugin
			 */
			public static $instance;

			/**
			 * The documents manager.
			 *
			 * @var \Orbit_Fox_Elementor_Documents_Double
			 */
			public $documents;

			public function __construct() {
				$this->documents = new \Orbit_Fox_Elementor_Documents_Double();
			}
		}

		Plugin::$instance = new Plugin();
	}
}
