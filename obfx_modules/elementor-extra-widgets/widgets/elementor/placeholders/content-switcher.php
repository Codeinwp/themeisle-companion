<?php

namespace ThemeIsle\ElementorExtraWidgets;

class Content_Switcher_Placeholder extends Premium_Placeholder {

	public function get_title() {
		return 'Content Switcher';
	}

	public function get_pro_element_name() {
		return 'content-switcher';
	}

	/**
	 * Widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'fas fa-toggle-on';
	}
}
