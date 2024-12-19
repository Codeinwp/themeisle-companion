<?php

namespace ThemeIsle\ElementorExtraWidgets;

/**
 * Create an abstract class as a base for all the Premium Widgets
 * This way, we'll configure the a Placeholder Widget in Lite plugins which will be overwritten in the Pro plugin.
 */
abstract class Premium_Placeholder extends \Elementor\Widget_Base {
	/**
	 * Each Placeholder must declare for which Premium Widget will keep the place warm
	 * @return mixed
	 */
	abstract public function get_pro_element_name();

	/**
	 * The widget's name will probably be the pro_element_name.
	 * @return mixed|string
	 */
	public function get_name() {
		return 'eaw-' . $this->get_pro_element_name();
	}

	/**
	 * Get widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-insert-image';
	}

	/**
	 * The Widget Category is filterable, so we need some extra logic to handle it better.
	 * Premium category is suffixed with `-pro` string.
	 *
	 * @return array
	 */
	public function get_categories() {
		$category_args = apply_filters( 'elementor_extra_widgets_category_args', array() );
		$slug          = isset( $category_args['slug'] ) ? $category_args['slug'] : 'obfx-elementor-widgets';

		return array( $slug . '-pro' );
	}

	/**
	 * Register Elementor Controls.
	 *
	 * Because this is just a placeholder widget, we need to output this to the Lite users.
	 */
	protected function register_controls() {
		// Empty controls section the because the widgets settings can't be open.
	}

	/**
	 * A placeholder should not output anything in front-end.
	 * Only on the editor side will output a message about it's type.
	 */
	public function render() {
		// Empty render function because the widgets can't be added.
	}
}
