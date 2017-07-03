<?php
/**
 * The Helper Class for content rendering.
 *
 * @link       https://themeisle.com
 * @since      1.0.0
 *
 * @package    Orbit_Fox
 * @subpackage Orbit_Fox/app/helpers
 */

/**
 * The class that contains utility methods to render partials, views or elements.
 *
 * @package    Orbit_Fox
 * @subpackage Orbit_Fox/app/helpers
 * @author     Themeisle <friends@themeisle.com>
 */
class Orbit_Fox_Render_Helper {

	/**
	 * Get a partial template and return the output.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @param   string $name The name of the partial w/o '-tpl.php'.
	 * @param   array  $args Optional. An associative array with name and value to be
	 *                                 passed to the partial.
	 * @return string
	 */
	public function get_partial( $name = '', $args = array() ) {
		ob_start();
		$file = OBX_PATH . '/core/app/views/partials/' . $name . '-tpl.php';
		if ( ! empty( $args ) ) {
			foreach ( $args as $obfx_rh_name => $obfx_rh_value ) {
				$$obfx_rh_name = $obfx_rh_value;
			}
		}
		if ( file_exists( $file ) ) {
			include $file;
		}
		return ob_get_clean();
	}

	/**
	 * Get a view template and return the output.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @param   string $name The name of the partial w/o '-page.php'.
	 * @param   array  $args Optional. An associative array with name and value to be
	 *                                 passed to the view.
	 * @return string
	 */
	public function get_view( $name = '', $args = array() ) {
		ob_start();
		$file = OBX_PATH . '/core/app/views/' . $name . '-page.php';
		if ( ! empty( $args ) ) {
			foreach ( $args as $obfx_rh_name => $obfx_rh_value ) {
				$$obfx_rh_name = $obfx_rh_value;
			}
		}
		if ( file_exists( $file ) ) {
			include $file;
		}
		return ob_get_clean();
	}
}
