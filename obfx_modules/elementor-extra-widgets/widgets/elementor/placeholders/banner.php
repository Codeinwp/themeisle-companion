<?php

namespace ThemeIsle\ElementorExtraWidgets;

class Banner_Placeholder extends Premium_Placeholder {

	public function get_title() {
		return 'Banner';
	}

	public function get_pro_element_name() {
		return 'banner';
	}

	/**
	 * Widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'far fa-image';
	}
}
