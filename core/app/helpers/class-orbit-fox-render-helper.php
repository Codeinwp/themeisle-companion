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

	/**
	 * Merges specific defaults with general ones.
	 *
	 * @since   1.0.0
	 * @access  private
	 * @param   array $option  The specific defaults array.
	 * @return array
	 */
	private function sanitize_option( $option ) {
		$general_defaults = array(
			'id' => null,
			'class' => null,
			'name' => null,
			'label' => 'Module Text Label',
			'description' => 'Module Text Description ...',
			'type' => null,
			'value' => '',
			'default' => '',
			'placeholder' => 'Add some text',
			'disabled' => false,
			'options' => array(),
		);

		return wp_parse_args( $option, $general_defaults );
	}

	/**
	 * Method to set field value.
	 *
	 * @since   1.0.0
	 * @access  private
	 * @param   array $option The option from the module.
	 * @return mixed
	 */
	private function set_field_value( $option = array() ) {
		$field_value = $option['default'];
		if ( isset( $option['value'] ) && $option['value'] != '' ) {
			$field_value = $option['value'];
		}
		return $field_value;
	}

	/**
	 * DRY method to generate checkbox or radio field types
	 *
	 * @since   1.0.0
	 * @access  private
	 * @param   string $type The field type ( checkbox | radio ).
	 * @param   string $field_value The field value.
	 * @param   string $checked The checked flag.
	 * @param   string $label The option label.
	 * @param   array  $option The option from the module.
	 * @return string
	 */
	private function generate_check_type( $type = 'radio', $field_value, $checked, $label, $option = array() ) {
	    return '
	    <label class="form-' . $type . ' ' . $option['class'] . '">
            <input type="' . $type . '" name="' . $option['name'] . '" value="' . $field_value . '" ' . $checked . ' />
            <i class="form-icon"></i> ' . $label . '
        </label>
	    ';
	}

	/**
	 * Render an input text field.
	 *
	 * @since   1.0.0
	 * @access  private
	 * @param   array $option The option from the module.
	 * @return mixed
	 */
	private function field_text( $option = array() ) {
		$field_value = $this->set_field_value( $option );
		$field = '
        <div class="form-group">
            <label class="form-label" for="' . $option['id'] . '">' . $option['label'] . '</label>
            <input class="form-input ' . $option['class'] . '" type="text" id="' . $option['id'] . '" name="' . $option['name'] . '" placeholder="' . $option['placeholder'] . '" value="' . $field_value . '">
        </div>
        ';

		return $field;
	}

	/**
	 * Render a textarea field.
	 *
	 * @since   1.0.0
	 * @access  private
	 * @param   array $option The option from the module.
	 * @return mixed
	 */
	private function field_textarea( $option = array() ) {
		$field_value = $this->set_field_value( $option );
		$field = '
        <div class="form-group">
            <label class="form-label" for="' . $option['id'] . '">' . $option['label'] . '</label>
            <textarea class="form-input ' . $option['class'] . '" id="' . $option['id'] . '" name="' . $option['name'] . '" placeholder="' . $option['placeholder'] . '" rows="3">' . $field_value . '</textarea>
        </div>
        ';

		return $field;
	}

	/**
	 * Render a select field.
	 *
	 * @since   1.0.0
	 * @access  private
	 * @param   array $option The option from the module.
	 * @return mixed
	 */
	private function field_select( $option = array() ) {
		$field_value = $this->set_field_value( $option );
		$select_options = '';
		foreach ( $option['options'] as $value => $label ) {
			$select_options .= '<option value="' . $value . '">' . $label . '</option>';
		}

		$field = '
        <div class="form-group">
            <label class="form-label" for="' . $option['id'] . '">' . $option['label'] . '</label>
            <select class="form-select ' . $option['class'] . '" id="' . $option['id'] . '" name="' . $option['name'] . '" placeholder="' . $option['placeholder'] . '">
                ' . $select_options . '
            </select>
        </div>
        ';

		return $field;
	}

	/**
	 * Render a radio field.
	 *
	 * @since   1.0.0
	 * @access  private
	 * @param   array $option The option from the module.
	 * @return mixed
	 */
	private function field_radio( $option = array() ) {
		$field_value = $this->set_field_value( $option );
		$select_options = '';
		foreach ( $option['options'] as $value => $label ) {
			$checked = '';
			if ( $value == $field_value ) {
				$checked = 'checked';
			}
			$select_options .= $this->generate_check_type( 'radio', $field_value, $checked, $label, $option );
		}

		$field = '
        <div class="form-group">
            <label class="form-label" for="' . $option['id'] . '">' . $option['label'] . '</label>
            ' . $select_options . '
        </div>
        ';

		return $field;
	}

	/**
	 * Render a checkbox field.
	 *
	 * @since   1.0.0
	 * @access  private
	 * @param   array $option The option from the module.
	 * @return mixed
	 */
	private function field_checkbox( $option = array() ) {
		$field_value = $this->set_field_value( $option );
		$select_options = '';
		foreach ( $option['options'] as $value => $label ) {
			$checked = '';
			if ( $value == $field_value ) {
				$checked = 'checked';
			}
			$select_options .= $this->generate_check_type( 'checkbox', $field_value, $checked, $label, $option );
		}

		$field = '
        <div class="form-group">
            <label class="form-label" for="' . $option['id'] . '">' . $option['label'] . '</label>
            ' . $select_options . '
        </div>
        ';

		return $field;
	}

	/**
	 * Render a toggle field.
	 *
	 * @since   1.0.0
	 * @access  private
	 * @param   array $option The option from the module.
	 * @return mixed
	 */
	private function field_toggle( $option = array() ) {
		$field_value = $this->set_field_value( $option );
		$field = '
        <div class="form-group">
            <label class="form-label" for="' . $option['id'] . '">' . $option['label'] . '</label>
            <label class="form-switch ' . $option['class'] . '">
                <input type="checkbox" name="' . $option['name'] . '" value="' . $field_value . '" />
                <i class="form-icon"></i> ' . $option['label'] . '
            </label>
        </div>
        ';

		return $field;
	}

	/**
	 * Method to render option to a field.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @param   array $option The option from the module.
	 * @return mixed
	 */
	public function render_option( $option = array() ) {
		$option = $this->sanitize_option( $option );
	    switch ( $option['type'] ) {
			case 'text':
				return $this->field_text( $option );
				break;
			case 'textarea':
				return $this->field_textarea( $option );
				break;
			case 'select':
				return $this->field_select( $option );
				break;
			case 'radio':
				return $this->field_radio( $option );
				break;
			case 'checkbox':
				return $this->field_checkbox( $option );
				break;
			case 'toggle':
				return $this->field_toggle( $option );
				break;
			default:
				return __( 'No option found for provided type', 'obfx' );
				break;
		}
	}


}
