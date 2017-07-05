<?php
/**
 * The Mock-up to demonstrate and test module use.
 *
 * @link       https://themeisle.com
 * @since      1.0.0
 *
 * @package    Test_OBFX_Module
 */

/**
 * The class defines a new module to be used by Orbit Fox plugin.
 *
 * @package    Test_OBFX_Module
 * @author     Themeisle <friends@themeisle.com>
 */
class Test_OBFX_Module extends Orbit_Fox_Module_Abstract {

	/**
	 * Test_OBFX_Module constructor.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function __construct() {
	    parent::__construct();
		$this->name = __( 'Orbit Fox Test Module', 'obfx' );
		$this->description = __( 'A test module for Orbit Fox.', 'obfx' );
	}

	/**
	 * Determine if module should be loaded.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @return bool
	 */
	public function enable_module() {
		return true;
	}

	/**
	 * The loading logic for the module.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function load() {

	}

	/**
	 * Method that returns an array of scripts and styles to be loaded
	 * for the front end part.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @return array
	 */
	public function public_enqueue() {
	    return array();
	}

	/**
	 * Method that returns an array of scripts and styles to be loaded
	 * for the admin part.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @return array
	 */
	public function admin_enqueue() {
		return array(
		    'js' => array(
		        'test' => array( 'jquery' ),
			),
			'css' => array(
				'test' => false,
			),
		);
	}

	/**
	 * Method to define the options fields for the module
	 *
	 * @since   1.0.0
	 * @access  public
	 * @return array
	 */
	public function options() {
		return array(
		    array(
		        'id' => 'test_text_id',
		        'name' => 'test_text_name',
		        'label' => 'Module Text Label',
		        'title' => 'Module Text Title',
		        'description' => 'Module Text Description ...',
		        'type' => 'text',
		        'default' => '',
		        'placeholder' => 'Add some text',
            ),
            array(
                'id' => 'test_textarea_id',
                'name' => 'test_textarea_name',
                'label' => 'Module Textarea Label',
                'title' => 'Module Textarea Title',
                'description' => 'Module Textarea Description ...',
                'type' => 'textarea',
                'default' => '',
                'placeholder' => 'Add some text here ...',
            ),
            array(
                'id' => 'test_select_id',
                'name' => 'test_select_name',
                'label' => 'Module Select Label',
                'title' => 'Module Select Title',
                'description' => 'Module Select Description ...',
                'type' => 'select',
                'default' => '',
                'placeholder' => 'Select an option',
                'options' => array(
                    'opt_1' => 'I like cats!',
                    'opt_2' => 'I like dogs!',
                    'opt_3' => 'I like both! Like all the animals!!!',
                ),
            ),
            array(
                'id' => 'test_radio_id',
                'name' => 'test_radio_name',
                'label' => 'Module Radio Label',
                'title' => 'Module Radio Title',
                'description' => 'Module Radio Description ...',
                'type' => 'radio',
                'default' => '1',
                'options' => array(
                    '0' => 'Meh!',
                    '1' => 'Good!',
                    '2' => 'Great!',
                ),
            ),
            array(
                'id' => 'test_checkbox_id',
                'name' => 'test_checkbox_name',
                'label' => 'Module Checkbox Label',
                'title' => 'Module Checkbox Title',
                'description' => 'Module Checkbox Description ...',
                'type' => 'checkbox',
                'default' => '1'
            ),
            array(
                'id' => 'test_toggle_id',
                'name' => 'test_toggle_name',
                'label' => 'Module Toggle Label',
                'title' => 'Module Toggle Title',
                'description' => 'Module Toggle Description ...',
                'type' => 'toggle',
                'default' => '1',
            ),
        );
	}
}
