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

if ( ! isset( $name ) ) {
    $name = __( 'The Module Name', 'obfx' );
}

if ( ! isset( $description ) ) {
    $description = __( 'The Module Description ...', 'obfx' );
}

if ( ! isset( $options_fields ) ) {
    $options_fields = __( 'No options provided.', 'obfx' );
}

?>
<div class="panel">
    <div class="panel-header">
        <div class="panel-title"><?php echo $name; ?></div>
        <div class="panel-subtitle"><?php echo $description; ?></div>
    </div>
    <div class="panel-body">
        <?php echo $options_fields; ?>
    </div>
</div>
