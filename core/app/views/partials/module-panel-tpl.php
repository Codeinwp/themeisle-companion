<?php
/**
 * Panel modules template.
 * Imported via the Orbit_Fox_Render_Helper.
 *
 * @link       https://themeisle.com
 * @since      1.0.0
 *
 * @package    Orbit_Fox
 * @subpackage Orbit_Fox/app/views/partials
 */

if ( ! isset( $slug ) ) {
	$slug = '';
}
$noance = wp_create_nonce( 'obfx_update_module_options_' . $slug );

if ( ! isset( $active ) ) {
	$active = false;
}

if ( ! isset( $name ) ) {
	$name = __( 'The Module Name', 'themeisle-companion' );
}

if ( ! isset( $description ) ) {
	$description = __( 'The Module Description ...', 'themeisle-companion' );
}

if ( ! isset( $options_fields ) ) {
	$options_fields = __( 'No options provided.', 'themeisle-companion' );
}

$display         = '';
$disabled_fields = '';
if ( ! $active ) {
	$display         = 'style="display: none;"';
	$disabled_fields = 'disabled';
}
?>
<div id="obfx-mod-<?php echo $slug; ?>" class="panel options" <?php echo $display; ?>>
	<div class="panel-header">
		<button class="btn btn-action circle btn-expand" style="float: right; margin-right: 10px;">
			<i class="dashicons dashicons-arrow-down-alt2"></i>
		</button>
		<div class="panel-title"><?php echo $name; ?></div>
		<div class="panel-subtitle"><?php echo $description; ?></div>
		<div class="obfx-mod-toast toast" style="display: none;">
			<button class="obfx-toast-dismiss btn btn-clear float-right"></button>
			<span>Mock text for Toast Element</span>
		</div>
	</div>
	<form id="obfx-module-form-<?php echo $slug; ?>" class="obfx-module-form">
		<fieldset <?php echo $disabled_fields; ?> >
			<input type="hidden" name="module-slug" value="<?php echo $slug; ?>">
			<input type="hidden" name="noance" value="<?php echo $noance; ?>">
			<div class="panel-body">
				<?php echo $options_fields; ?>
				<div class="divider"></div>
			</div>
			<div class="panel-footer text-right">
				<button class="btn obfx-mod-btn-cancel" disabled>Cancel</button>
				<button class="btn btn-primary obfx-mod-btn-save" disabled>Save</button>
			</div>
		</fieldset>
	</form>
</div>
