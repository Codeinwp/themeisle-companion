<?php

namespace ThemeIsle\ElementorExtraWidgets;

class Team_Member_Placeholder extends Premium_Placeholder {

	public function get_title() {
		return 'Team Member';
	}

	public function get_pro_element_name() {
		return 'team-member';
	}

	/**
	 * Widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'fas fa-users';
	}
}
