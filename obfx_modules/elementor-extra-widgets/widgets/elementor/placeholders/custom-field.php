<?php

namespace ThemeIsle\ElementorExtraWidgets;

class Custom_Field_Placeholder extends Premium_Placeholder {

	public function get_title() {
		return 'Custom Field';
	}

	public function get_pro_element_name() {
		return 'custom-field';
	}

	/**
	 * Widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'fab fa-wpforms';
	}
}
