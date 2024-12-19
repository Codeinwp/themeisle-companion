<?php

namespace ThemeIsle\ElementorExtraWidgets;

class Custom_Layout_Placeholder extends Premium_Placeholder {

	public function get_title() {
		return 'Custom Layout';
	}

	public function get_pro_element_name() {
		return 'custom-layout';
	}

	/**
	 * Widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'fa fa-edit';
	}
}
