<?php

// Get the module directory.
$module_directory = $this->get_dir();

// Include custom fields
require_once( $module_directory . '/custom-fields/toggle-field/toggle_field.php' );

/**
 * Class PostGridModule
 */
class PostGridModule extends FLBuilderModule {

	/**
	 * Constructor function for the module. You must pass the
	 * name, description, dir and url in an array to the parent class.
	 *
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct(
			array(
				'name'          => esc_html__( 'Post Grid', 'themeisle-companion' ),
				'description'   => esc_html__( 'A method to display your posts.', 'themeisle-companion' ),
				'category'      => esc_html__( 'Orbit Fox Modules', 'themeisle-companion' ),
				'dir'           => BEAVER_WIDGETS_PATH . 'modules/post-grid/',
				'url'           => BEAVER_WIDGETS_URL . 'modules/post-grid/',
			)
		);
	}
}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module( 'PostGridModule', array(
	'loop_settings' => array(
		'title'         => esc_html__( 'Loop Settings', 'themeisle-companion' ),
		'file'          => BEAVER_WIDGETS_PATH . 'modules/post-grid/includes/loop-settings.php',
	),
	'display' => array(
		'title' => esc_html__( 'Display options', 'themeisle-companion' ), // Tab title
		'sections' => array(
			'general' => array(
				'title' => '',
				'fields' => array(

				),
			),
		),
	),
));