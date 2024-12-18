<?php
/**
 * This class handel the Elementor Widgets registration, category registration and all Elementor related actions.
 *
 * @package ContentForms
 */

namespace ThemeIsle\ContentForms\Includes\Widgets_Admin\Elementor;

use Elementor\Plugin;

/**
 * Class Elementor_Widget_Manager
 */
class Elementor_Widget_Manager {

	/**
	 * Type of Widget Forms.
	 *
	 * @var $forms
	 */
	public static $forms = array( 'contact', 'newsletter', 'registration' );

	/**
	 * Initialization Function
	 */
	public function init() {
		// Register Orbit Fox Category in Elementor Dashboard
		add_action( 'elementor/elements/categories_registered', array( $this, 'add_elementor_widget_categories' ) );

		// Register Orbit Fox Elementor Widgets
		add_action( 'elementor/widgets/widgets_registered', array( $this, 'register_elementor_widget' ) );

		// Before Elementor Widget Settings Save
		add_filter( 'elementor/document/save/data', array( $this, 'before_settings_save' ), 10, 2 );

	}

	/**
	 * Sanitize the field attributes for whe width attribute.
	 *
	 * @param array $field The field attributes.
	 *
	 * @return array
	 */
	private function sanitize_field_attributes( $field ) {
		$address_fields = array( 'addr2', 'city', 'state', 'zip', 'country' );
		foreach ( $address_fields as $attribute ) {
			if ( isset( $field[ $attribute . '_width' ] ) ) {
				$field[ $attribute . '_width' ] = is_numeric( $field[ $attribute . '_width' ] ) ? $field[ $attribute . '_width' ] : '100';
			}
		}
		return $field;
	}

	/**
	 * Sanitize the alignment attribute.
	 *
	 * @param string $alignment The alignment attribute.
	 *
	 * @return string
	 */
	private function sanitize_alignment( $alignment ) {
		$allowed_alignments = array( 'left', 'center', 'right' );
		return in_array( $alignment, $allowed_alignments ) ? $alignment : 'left';
	}

	/**
	 * Search and modify the widget settings.
	 *
	 * @param array $elements_data The elements data.
	 */
	private function search_and_modify_widget_settings( &$elements_data ) {
		foreach ( $elements_data as &$element ) {
			if ( isset( $element['elType'] ) && $element['elType'] === 'widget' ) {
				// Check if the widget is of the desired type
				if ( isset( $element['widgetType'] ) && in_array( $element['widgetType'], array( 'content_form_registration', 'content_form_newsletter', 'content_form_contact' ) ) ) {
					// Modify the settings of the widget
					$settings = $element['settings'];
					if ( isset( $settings['notification_alignment'] ) ) {
						$settings['notification_alignment'] = $this->sanitize_alignment( $settings['notification_alignment'] );
					}
					if ( isset( $settings['form_fields'] ) ) {
						$form_fields = $settings['form_fields'];
						foreach ( $form_fields as &$field ) {
							$field = $this->sanitize_field_attributes( $field );
						}
						$settings['form_fields'] = $form_fields;
					}
					//$settings[$control_name] = $desired_value;
					$element['settings'] = $settings;
				}
			}

			if ( isset( $element['elements'] ) && is_array( $element['elements'] ) ) {
				// If the element has nested elements (e.g., section or column), recursively call the function
				$this->search_and_modify_widget_settings( $element['elements'] );
			}
		}
	}

	/**
	 * Filter the document data and sanitize the form parameters.
	 *
	 * @param array $data The document data.
	 * @param @param \Elementor\Core\Base\Document $document The document instance.
	 *
	 * @return mixed
	 */
	public function before_settings_save( $data, $document ) {
		if ( ! isset( $data['elements'] ) ) {
			return;
		}
		$this->search_and_modify_widget_settings( $data['elements'] );
		return $data;
	}

	/**
	 * Register the category for widgets.
	 *
	 * @param \Elementor\Elements_Manager $elements_manager Elements manager.
	 */
	public function add_elementor_widget_categories( $elements_manager ) {
		$elements_manager->add_category(
			'obfx-elementor-widgets',
			array(
				'title' => __( 'Orbit Fox Addons', 'themeisle-companion' ),
				'icon'  => 'fa fa-plug',
			)
		);
	}

	/**
	 * Register Elementor Widgets that are added in Orbit Fox.
	 */
	public function register_elementor_widget() {
		foreach ( self::$forms as $form ) {
			require_once $form . '_admin.php';
			$widget = '\ThemeIsle\ContentForms\Includes\Widgets_Admin\Elementor\\' . ucwords( $form ) . '_Admin';
			Plugin::instance()->widgets_manager->register_widget_type( new $widget() );
		}
	}
}
