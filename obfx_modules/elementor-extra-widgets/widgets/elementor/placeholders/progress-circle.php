<?php

namespace ThemeIsle\ElementorExtraWidgets;

class Progress_Circle_Placeholder extends Premium_Placeholder {

	public function get_title() {
		return 'Progress Circle';
	}

	public function get_pro_element_name() {
		return 'progress-circle';
	}

	/**
	 * Widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'fas fa-circle-notch';
	}
}
