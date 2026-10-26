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

	/**
	 * Stands in for Elementor's editor, which reports whether the page is being edited.
	 */
	class Orbit_Fox_Elementor_Editor_Double {

		/**
		 * What is_edit_mode() hands back.
		 *
		 * @var bool
		 */
		public static $edit_mode = false;

		/**
		 * @return bool
		 */
		public function is_edit_mode(): bool {
			return self::$edit_mode;
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

			/**
			 * The editor.
			 *
			 * @var \Orbit_Fox_Elementor_Editor_Double
			 */
			public $editor;

			public function __construct() {
				$this->documents = new \Orbit_Fox_Elementor_Documents_Double();
				$this->editor    = new \Orbit_Fox_Elementor_Editor_Double();
			}
		}

		Plugin::$instance = new Plugin();

		/**
		 * Stands in for Elementor's widget base; the render attribute methods mirror Elementor 4.3.
		 */
		abstract class Widget_Base {

			/**
			 * Attributes per element, keyed by attribute name.
			 *
			 * @var array<string, array<string, list<string>>>
			 */
			protected $render_attributes = array();

			/**
			 * Widget settings.
			 *
			 * @var array<string, mixed>
			 */
			protected $settings;

			/**
			 * Widget data.
			 *
			 * @var array<string, mixed>
			 */
			protected $data;

			/**
			 * @param array<string, mixed> $settings Widget settings.
			 * @param array<string, mixed> $data     Widget data.
			 */
			public function __construct( array $settings = array(), array $data = array() ) {
				$this->settings = $settings;
				$this->data     = $data;
			}

			/**
			 * @param array<string, array<string, string|list<string>>>|string $element Element name, or attributes per element.
			 * @param array<string, string|list<string>>|string|null           $key     Attribute name, or attributes.
			 * @param string|list<string>|null                                 $value   Attribute value.
			 *
			 * @return $this
			 */
			public function add_render_attribute( $element, $key = null, $value = null ): self {
				if ( is_array( $element ) ) {
					foreach ( $element as $element_key => $attributes ) {
						$this->add_render_attribute( $element_key, $attributes );
					}

					return $this;
				}

				if ( is_array( $key ) ) {
					foreach ( $key as $attribute_key => $attributes ) {
						$this->add_render_attribute( $element, $attribute_key, $attributes );
					}

					return $this;
				}

				$current = isset( $this->render_attributes[ $element ][ $key ] ) ? $this->render_attributes[ $element ][ $key ] : array();

				$this->render_attributes[ $element ][ $key ] = array_merge( $current, (array) $value );

				return $this;
			}

			/**
			 * @param string $element Element name.
			 *
			 * @return string
			 */
			public function get_render_attribute_string( string $element ): string {
				if ( empty( $this->render_attributes[ $element ] ) ) {
					return '';
				}

				$rendered = array();
				foreach ( $this->render_attributes[ $element ] as $attribute_key => $attribute_values ) {
					$rendered[] = sprintf( '%1$s="%2$s"', $attribute_key, esc_attr( implode( ' ', $attribute_values ) ) );
				}

				return implode( ' ', $rendered );
			}

			/**
			 * @param string $element Element name.
			 */
			public function print_render_attribute_string( string $element ): void {
				echo $this->get_render_attribute_string( $element );
			}

			/**
			 * @param string $item Data key.
			 *
			 * @return mixed
			 */
			public function get_data( string $item ) {
				return isset( $this->data[ $item ] ) ? $this->data[ $item ] : null;
			}

			/**
			 * @return array<string, mixed>
			 */
			public function get_settings(): array {
				return $this->settings;
			}

			/**
			 * @return array<string, mixed>
			 */
			public function get_settings_for_display(): array {
				return $this->settings;
			}
		}
	}
}
