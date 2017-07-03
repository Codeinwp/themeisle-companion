<?php
/**
 * Tile modules template.
 * Imported via the Orbit_Fox_Render_Helper.
 *
 * @link       https://themeisle.com
 * @since      1.0.0
 *
 * @package    Orbit_Fox
 * @subpackage Orbit_Fox/app/views/partials
 */

if ( ! isset( $name ) ) {
	$name = __( 'Module Name', 'obfx' );
}

if ( ! isset( $description ) ) {
	$description = __( 'Module Description ...', 'obfx' );
}

?>
<div class="tile">
	<div class="tile-icon">
		<div class="example-tile-icon">
			<i class="dashicons dashicons-admin-plugins centered"></i>
		</div>
	</div>
	<div class="tile-content">
		<p class="tile-title"><?php echo $name; ?></p>
		<p class="tile-subtitle"><?php echo $description; ?></p>
	</div>
	<div class="tile-action">
		<div class="form-group">
			<label class="form-switch">
				<input type="checkbox">
				<i class="form-icon"></i><?php echo  __( 'Activate', 'obfx' ); ?>
			</label>
		</div>
	</div>
</div>
