<?php

namespace ThemeIsle\ElementorExtraWidgets;

class Instagram_Feed_Placeholder extends Premium_Placeholder {

	public function get_title() {
		return 'Instagram Feed';
	}

	public function get_pro_element_name() {
		return 'instagram-feed';
	}

	/**
	 * Widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'fab fa-instagram-square';
	}
}
