<?php

/**
 * OBFX Login Control Class
 *
 * @package OBFX
 */

class OBFX_Login_Control extends WP_Customize_Control {
	/**
	 * The settings array for React
	 *
	 * @var array
	 */
	public $controls = array();

	public $type = 'obfx_login_control';


	public function render_content() {
		echo '<div id="obfx-login-control"></div>';
	}
}
