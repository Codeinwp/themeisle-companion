<?php

/**
 * OBFX Login Values Class
 *
 * @package OBFX
 */

class OBFX_Login_Values {
	const VALUE_TYPE_BOOLEAN = 'boolean';
	const VALUE_TYPE_INTEGER = 'int';
	const VALUE_TYPE_STRING  = 'string';
	const VALUE_TYPE_COLOR   = 'color';
	const VALUE_TYPE_BORDER  = 'border';
	const VALUE_TYPE_SIZING  = 'sizing';
	const VALUE_TYPE_URL     = 'url';
	const VALUE_TYPE_KEY     = 'key';
	const SCHEMA             = array(
		'disable_logo'                    => array(
			'type'    => self::VALUE_TYPE_BOOLEAN,
			'default' => false,
		),
		'custom_logo_url'                 => array(
			'type'    => self::VALUE_TYPE_URL,
			'default' => '',
		),
		'logo_width'                      => array(
			'type'    => self::VALUE_TYPE_INTEGER,
			'default' => 84,
		),
		'logo_height'                     => array(
			'type'    => self::VALUE_TYPE_INTEGER,
			'default' => 84,
		),
		'logo_bottom_margin'              => array(
			'type'    => self::VALUE_TYPE_INTEGER,
			'default' => 24,
		),
		'logo_url'                        => array(
			'type'    => self::VALUE_TYPE_URL,
			'default' => '',
		),
		'logo_title'                      => array(
			'type'    => self::VALUE_TYPE_STRING,
			'default' => '',
		),
		'page_bg_color'                   => array(
			'type'    => self::VALUE_TYPE_COLOR,
			'default' => '#f0f0f1',
		),
		'page_bg_image'                   => array(
			'type'    => self::VALUE_TYPE_URL,
			'default' => '',
		),
		'page_bg_overlay_blur'            => array(
			'type'    => self::VALUE_TYPE_INTEGER,
			'default' => 0,
		),
		'page_bg_image_position'          => array(
			'type'    => self::VALUE_TYPE_STRING,
			'default' => 'center',
		),
		'page_bg_image_size'              => array(
			'type'    => self::VALUE_TYPE_STRING,
			'default' => 'cover',
		),
		'page_bg_image_repeat'            => array(
			'type'    => self::VALUE_TYPE_STRING,
			'default' => 'no-repeat',
		),
		'form_bg_color'                   => array(
			'type'    => self::VALUE_TYPE_COLOR,
			'default' => '#ffffff',
		),
		'form_text_color'                 => array(
			'type'    => self::VALUE_TYPE_COLOR,
			'default' => '#3c434a',
		),
		'form_border'                     => array(
			'type'    => self::VALUE_TYPE_BORDER,
			'default' => '1px solid #c3c4c7',
		),
		'form_border_radius'              => array(
			'type'    => self::VALUE_TYPE_INTEGER,
			'default' => 0,
		),
		'form_width'                      => array(
			'type'    => self::VALUE_TYPE_INTEGER,
			'default' => 320,
		),
		'form_padding'                    => array(
			'type'    => self::VALUE_TYPE_SIZING,
			'default' => '26px 24px',
		),
		'form_field_bg_color'             => array(
			'type'    => self::VALUE_TYPE_COLOR,
			'default' => '#ffffff',
		),
		'form_field_text_color'           => array(
			'type'    => self::VALUE_TYPE_COLOR,
			'default' => '#2c3338',
		),
		'form_field_border'               => array(
			'type'    => self::VALUE_TYPE_BORDER,
			'default' => '1px solid #8c8f94',
		),
		'form_field_border_radius'        => array(
			'type'    => self::VALUE_TYPE_INTEGER,
			'default' => 4,
		),
		'form_field_margin_bottom'        => array(
			'type'    => self::VALUE_TYPE_INTEGER,
			'default' => 16,
		),
		'form_field_padding'              => array(
			'type'    => self::VALUE_TYPE_STRING,
			'default' => '3px 5px',
		),
		'form_field_font_size'            => array(
			'type'    => self::VALUE_TYPE_INTEGER,
			'default' => 24,
		),
		'form_label_font_size'            => array(
			'type'    => self::VALUE_TYPE_INTEGER,
			'default' => 14,
		),
		'form_label_margin_bottom'        => array(
			'type'    => self::VALUE_TYPE_INTEGER,
			'default' => 3,
		),
		'button_display_below'            => array(
			'type'    => self::VALUE_TYPE_BOOLEAN,
			'default' => false,
		),
		'button_width'                    => array(
			'type'    => self::VALUE_TYPE_INTEGER,
			'default' => 100,
		),
		'button_alignment'                => array(
			'type'    => self::VALUE_TYPE_STRING,
			'default' => 'center',
		),
		'button_margin_top'               => array(
			'type'    => self::VALUE_TYPE_INTEGER,
			'default' => 16,
		),
		'button_padding'                  => array(
			'type'    => self::VALUE_TYPE_SIZING,
			'default' => '9px 12px',
		),
		'button_font_size'                => array(
			'type'    => self::VALUE_TYPE_INTEGER,
			'default' => 13,
		),
		'button_border_radius'            => array(
			'type'    => self::VALUE_TYPE_INTEGER,
			'default' => 4,
		),
		'button_border'                   => array(
			'type'    => self::VALUE_TYPE_BORDER,
			'default' => '1px solid #2271b1',
		),
		'button_background'               => array(
			'type'    => self::VALUE_TYPE_COLOR,
			'default' => '#2271b1',
		),
		'button_text_color'               => array(
			'type'    => self::VALUE_TYPE_COLOR,
			'default' => '#ffffff',
		),
		'button_hover_background'         => array(
			'type'    => self::VALUE_TYPE_COLOR,
			'default' => '#135e96',
		),
		'button_hover_text_color'         => array(
			'type'    => self::VALUE_TYPE_COLOR,
			'default' => '#ffffff',
		),
		'button_hover_border_color'       => array(
			'type'    => self::VALUE_TYPE_COLOR,
			'default' => '#135e96',
		),
		'show_remember_me'                => array(
			'type'    => self::VALUE_TYPE_BOOLEAN,
			'default' => true,
		),
		'show_language_switcher'          => array(
			'type'    => self::VALUE_TYPE_BOOLEAN,
			'default' => true,
		),
		'show_navigation_links'           => array(
			'type'    => self::VALUE_TYPE_BOOLEAN,
			'default' => true,
		),
		'nav_font_size'                   => array(
			'type'    => self::VALUE_TYPE_INTEGER,
			'default' => 13,
		),
		'nav_color'                       => array(
			'type'    => self::VALUE_TYPE_COLOR,
			'default' => '#50575e',
		),
		'nav_hover_color'                 => array(
			'type'    => self::VALUE_TYPE_COLOR,
			'default' => '#50575e',
		),
		'show_link_to_homepage'           => array(
			'type'    => self::VALUE_TYPE_BOOLEAN,
			'default' => true,
		),
		'homepage_link_font_size'         => array(
			'type'    => self::VALUE_TYPE_INTEGER,
			'default' => 13,
		),
		'homepage_link_color'             => array(
			'type'    => self::VALUE_TYPE_COLOR,
			'default' => '#50575e',
		),
		'homepage_link_hover_color'       => array(
			'type'    => self::VALUE_TYPE_COLOR,
			'default' => '#50575e',
		),
		'show_privacy_policy'             => array(
			'type'    => self::VALUE_TYPE_BOOLEAN,
			'default' => true,
		),
		'privacy_policy_link_font_size'   => array(
			'type'    => self::VALUE_TYPE_INTEGER,
			'default' => 13,
		),
		'privacy_policy_link_color'       => array(
			'type'    => self::VALUE_TYPE_COLOR,
			'default' => '#2271b1',
		),
		'privacy_policy_link_hover_color' => array(
			'type'    => self::VALUE_TYPE_COLOR,
			'default' => '#135e96',
		),
		'change_login_url'                => array(
			'type'    => self::VALUE_TYPE_BOOLEAN,
			'default' => false,
		),
		'redirect_to_login'               => array(
			'type'    => self::VALUE_TYPE_BOOLEAN,
			'default' => false,
		),
		'login_url'                       => array(
			'type'    => self::VALUE_TYPE_KEY,
			'default' => '',
		),
	);

	/**
	 * Get the default values for the settings.
	 * 
	 * @return array
	 */
	public static function get_defaults() {
		return array_combine( array_keys( self::SCHEMA ), array_column( self::SCHEMA, 'default' ) );
	}

	/**
	 * Sanitize the root value.
	 * 
	 * @param array $value The value to sanitize.
	 * 
	 * @return array
	 */
	public static function sanitize_root_value( $value ) {
		if ( ! is_array( $value ) || empty( $value ) ) {
			return array();
		}

		foreach ( $value as $key => $val ) {
			if ( ! isset( self::SCHEMA[ $key ] ) ) {
				unset( $value[ $key ] );
				continue;
			}

			$value[ $key ] = self::sanitize_single_value( $key, $val );
		}

		return $value;
	}

	/**
	 * Validate the root value.
	 * 
	 * @param WP_Error $validity The validity object.
	 * @param array $value The value to validate.
	 * 
	 * @return WP_Error
	 */
	public static function validate_root_value( $validity, $value ) {
		if ( ! is_array( $value ) ) {
			$validity->add( 'invalid_value', __( 'The value must be an array.', 'themeisle-companion' ) );
		}

		return $validity;
	}

	/**
	 * Sanitize the value.
	 * 
	 * @param string $key The key of the value.
	 * @param mixed $value The value to sanitize.
	 * 
	 * @return mixed
	 */
	public static function sanitize_single_value( $key, $value ) {
		if ( ! isset( self::SCHEMA[ $key ] ) ) {
			return sanitize_text_field( $value );
		}

		$type = self::SCHEMA[ $key ]['type'];

		switch ( $type ) {
			case self::VALUE_TYPE_BOOLEAN:
				return (bool) $value;
			case self::VALUE_TYPE_INTEGER:
				return absint( $value );
			case self::VALUE_TYPE_COLOR:
				return self::sanitize_color( $value );
			case self::VALUE_TYPE_BORDER:
				return self::sanitize_border( $value );
			case self::VALUE_TYPE_URL:
				return esc_url_raw( $value );
			case self::VALUE_TYPE_SIZING:
				return self::sanitize_sizing( $value );
			case self::VALUE_TYPE_KEY:
				return sanitize_key( $value );
			case self::VALUE_TYPE_STRING:
			default:
				return (string) sanitize_text_field( $value );
		}
	}

	/**
	 * Sanitize the color.
	 * 
	 * @param string $value The value to sanitize.
	 * 
	 * @return string
	 */
	public static function sanitize_color( $value ) {
		if ( strpos( $value, '#' ) === 0 ) {
			return sanitize_hex_color( $value );
		}

		if ( strpos( $value, 'rgba(' ) === 0 ) {
			$value = str_replace( 'rgba(', '', $value );
			$value = str_replace( ')', '', $value );
			$value = explode( ',', $value );
			$value = array_map( 'trim', $value );

			foreach ( $value as $idx => $v ) {
				if ( $idx === 3 ) {
					$value[ $idx ] = floatval( $v );

					if ( $value[ $idx ] < 0 || $value[ $idx ] > 1 ) {
						$value[ $idx ] = 1;
					}

					continue;
				}

				$value[ $idx ] = absint( $v );

				if ( $value[ $idx ] < 0 ) {
					$value[ $idx ] = 0;

					continue;
				}

				if ( $value[ $idx ] > 255 ) {
					$value[ $idx ] = 255;
				}
			}

			return 'rgba(' . implode( ',', $value ) . ')';
		}

		return sanitize_text_field( $value );
	}

	/**
	 * Sanitize the border.
	 * 
	 * @param string $value The value to sanitize.
	 * 
	 * @return string
	 */
	public static function sanitize_border( $value ) {
		$value = explode( ' ', $value );

		$width = self::sanitize_single_px_value( $value[0] );
		$style = sanitize_text_field( $value[1] );
		$color = self::sanitize_color( $value[2] );

		return sprintf( '%s %s %s', $width, $style, $color );
	}

	/**
	 * Sanitize the single px value.
	 * 
	 * @param string $value The value to sanitize.
	 * 
	 * @return string
	 */
	public static function sanitize_single_px_value( $value ) {
		$value = explode( 'px', $value );
		$value = $value[0];
		return absint( $value ) . 'px';
	}

	/**
	 * Sanitize the sizing.
	 * 
	 * @param string $value The value to sanitize.
	 * 
	 * @return string
	 */
	public static function sanitize_sizing( $value ) {
		$value = explode( ' ', $value );

		if ( ! is_array( $value ) || empty( $value ) ) {
			return '0px 0px 0px 0px';
		}

		if ( count( $value ) === 1 ) {
			$first_val = self::sanitize_single_px_value( $value[0] );

			return sprintf( '%s %s %s %s', $first_val, $first_val, $first_val, $first_val );
		}

		if ( count( $value ) === 2 ) {
			$first_val  = self::sanitize_single_px_value( $value[0] );
			$second_val = self::sanitize_single_px_value( $value[1] );

			return sprintf( '%s %s %s %s', $first_val, $second_val, $first_val, $second_val );
		}

		if ( count( $value ) < 4 ) {
			return '0px 0px 0px 0px';
		}

		$value = array_map(
			function ( $v ) {
				return self::sanitize_single_px_value( $v );
			},
			$value
		);

		return implode( ' ', $value );
	}
}
