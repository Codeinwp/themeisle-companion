<?php
/**
 * Minimal stand-ins for the Hestia theme helpers that the companion-legacy
 * front page sections call without a function_exists() guard.
 *
 * @package     Orbit_Fox
 * @subpackage  Orbit_Fox/tests
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

if ( ! function_exists( 'hestia_before_clients_bar_section_trigger' ) ) {
	function hestia_before_clients_bar_section_trigger(): void {}
}

if ( ! function_exists( 'hestia_after_clients_bar_section_trigger' ) ) {
	function hestia_after_clients_bar_section_trigger(): void {}
}

if ( ! function_exists( 'hestia_add_animationation' ) ) {
	/**
	 * @param string $animation Animation name.
	 */
	function hestia_add_animationation( string $animation ): string {
		return '';
	}
}
