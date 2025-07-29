<?php

class OBFX_Social_Icons {
	/**
	 * Get the icon
	 *
	 * @param string $icon The icon name
	 * @return string The icon
	 */
	public function get_icon( $icon = '' ) {
		if ( empty( $icon ) ) {
			return '';
		}

		$path = $this->get_icon_path( $icon );

		if ( ! is_file( $path ) ) {
			return '';
		}

		return file_get_contents( $path );
	}

	/**
	 * Get the path to the icon
	 *
	 * @param string $icon The icon name
	 * @return string The path to the icon
	 */
	private function get_icon_path( $icon = '' ) {
		return OBX_PATH . '/obfx_modules/social-sharing/css/icons/' . $icon . '.svg';
	}

	/**
	 * Sanitize the icon SVG
	 *
	 * @param string $svg The SVG content
	 * @return string The sanitized SVG content
	 */
	public static function sanitize_icon_svg( $svg = '' ) {
		return wp_kses(
			$svg,
			array(
				'svg'  => array(
					'viewbox' => array(),
					'xmlns'   => array(),
				),
				'path' => array(
					'd'         => array(),
					'clip-rule' => array(),
					'fill-rule' => array(),
				),
			)
		);
	}
}
