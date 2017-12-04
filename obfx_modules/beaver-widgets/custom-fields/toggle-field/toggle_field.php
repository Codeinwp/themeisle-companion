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

	?>

	<div class="switch-field">

		<?php

		$buttoncount = count( $field['options'] );

		if ( array_key_exists( 'options', $field ) ) {

			$options = $field['options'];

			$keys = array_keys( $options );

			$leftKey = $keys[0];

			$rightKey = $keys[1];

			$leftValue = $options[ $leftKey ];

			$rightValue = $options[ $rightKey ];

			if ( $buttoncount == 3 ) {

				$lastKey = $keys[2];

				$lastValue = $options[ $lastKey ];

			}
		} else {

			$leftKey = 'yes';

			$rightKey = 'no';

			$leftValue = 'Yes';

			$rightValue = 'No';

		}
		?>
		<style>
			.switch-field .<?php echo $name; ?> {
			<?php
			if ( array_key_exists( 'offcolor', $field ) ) {
				$color = $field['offcolor'];
			} else {
				$color = '#e4e4e4';
			}
			?>
				background-color: <?php echo $color; ?>;
			}
			.switch-field  .<?php echo $name; ?>:checked + .<?php echo $name; ?> {
			<?php
			if ( array_key_exists( 'oncolor', $field ) ) {
				$color = $field['oncolor'];
			} else {
				$color = '#A5DC86';
			}
			?>
				background-color: <?php echo $color; ?>;
				color: #fff;
			}
		</style>

		<input type="radio" class="<?php echo $name; ?>" id="<?php echo $name; ?>_left" name="<?php echo $name; ?>" value="<?php echo $leftKey; ?>" 
												<?php
												if ( isset( $value ) && $value == $leftKey ) {
													echo 'checked';}
?>
 />
		<label class="<?php echo $name; ?>" for="<?php echo $name; ?>_left"><?php echo $leftValue; ?></label>

		<input type="radio" class="<?php echo $name; ?>" id="<?php echo $name; ?>_right" name="<?php echo $name; ?>" value="<?php echo $rightKey; ?>" 
												<?php
												if ( isset( $value ) && $value == $rightKey ) {
													echo 'checked';}
?>
 />
		<label class="<?php echo $name; ?>" for="<?php echo $name; ?>_right"><?php echo $rightValue; ?></label>

		<?php if ( $buttoncount == 3 ) { ?>
			<input type="radio" class="<?php echo $name; ?>" id="<?php echo $name; ?>_last" name="<?php echo $name; ?>" value="<?php echo $lastKey; ?>" 
													<?php
													if ( isset( $value ) && $value == $lastKey ) {
														echo 'checked';}
?>
 />
			<label class="<?php echo $name; ?>" for="<?php echo $name; ?>_last"><?php echo $lastValue; ?></label>
		<?php } ?>

	</div>
	<?php

}

add_action( 'fl_builder_control_toggle', 'fl_toggle_field_true', 1, 3 );

/**
 * Enqueue toggle field stylesheet
 *
 * @return void
 */
function sw_enqueue_toggle() {
	// var_dump(BEAVER_WIDGETS_URL);
	// die();
	if ( class_exists( 'FLBuilderModel' ) && FLBuilderModel::is_builder_active() ) {
		wp_enqueue_style( 'toggle-css', BEAVER_WIDGETS_URL . 'custom-fields/toggle-field/toggle.css', null, THEMEISLE_COMPANION_VERSION, 'all' );
	}
}
add_action( 'wp_enqueue_scripts', 'sw_enqueue_toggle' );
