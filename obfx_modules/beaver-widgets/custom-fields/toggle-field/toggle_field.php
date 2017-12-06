<?php
/**
 * This file is toggle_field.php
 *
 * @author Badabing
 * @package package name
 * @subpackage Customizations
 */

/**
 * Render the Toggle Field to the browser
 */
function fl_toggle_field_true( $name, $value, $field ) {
//	echo $value;
    ?>
    <p class="btn-switch">
        <input type="radio" <?php if( $value === 'yes'){ echo 'checked';} ?> value="yes" id="yes" name="<?php echo esc_attr( $name );?>" class="btn-switch__radio btn-switch__radio_yes" />
        <input type="radio" <?php if( $value !== 'yes'){ echo 'checked';} ?> value="no" id="no" name="<?php echo esc_attr( $name );?>" class="btn-switch__radio btn-switch__radio_no" />
        <label for="yes" class="btn-switch__label btn-switch__label_yes"><span class="btn-switch__txt"><?php echo esc_html__('Yes','themeisle-companion') ?></span></label>
        <label for="no" class="btn-switch__label btn-switch__label_no"><span class="btn-switch__txt"><?php echo esc_html__('No','themeisle-companion') ?></span></label>
    </p>

	<?php

}

add_action( 'fl_builder_control_toggle', 'fl_toggle_field_true', 1, 3 );

/**
 * Enqueue toggle field stylesheet
 *
 * @return void
 */
function sw_enqueue_toggle() {
	if ( class_exists( 'FLBuilderModel' ) && FLBuilderModel::is_builder_active() ) {
		wp_enqueue_style( 'toggle-css', BEAVER_WIDGETS_URL . 'custom-fields/toggle-field/toggle.css', null, THEMEISLE_COMPANION_VERSION, 'all' );
		wp_enqueue_script( 'toggle-js', BEAVER_WIDGETS_URL . 'custom-fields/toggle-field/toggle.js', array(), THEMEISLE_COMPANION_VERSION, true );
	}
}
add_action( 'wp_enqueue_scripts', 'sw_enqueue_toggle' );
