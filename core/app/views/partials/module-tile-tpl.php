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
	$name = __( 'Module Name', 'themeisle-companion' );
}

if ( ! isset( $description ) ) {
	$description = __( 'Module Description ...', 'themeisle-companion' );
}

if ( ! isset( $checked ) ) {
	$checked = '';
}

$noance = wp_create_nonce( 'obfx_activate_mod_' . $slug );

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
				<input class="obfx-mod-switch" type="checkbox" name="<?php echo $slug; ?>" value="<?php echo $noance; ?>" <?php echo $checked; ?> >
				<i class="form-icon"></i><?php echo  __( 'Activate', 'themeisle-companion' ); ?>
			</label>
		</div>
	</div>
</div>
