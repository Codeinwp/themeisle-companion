<?php
/**
 * Toast modules template.
 * Imported via the Orbit_Fox_Render_Helper.
 *
 * @link       https://themeisle.com
 * @since      1.0.0
 *
 * @package    Orbit_Fox
 * @subpackage Orbit_Fox/app/views/partials
 */

?>
<div class="obfx-mod-toast toast toast-<?php echo esc_attr( $notice['type'] ); ?>">
	<button class="obfx-toast-dismiss btn btn-clear float-right"></button>
	<b><?php echo wp_kses_post( $notice['title'] ); ?></b><br/>
	<span><?php echo wp_kses_post( $notice['message'] ); ?></span>
</div>
