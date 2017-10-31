<?php

/**
 * The Orbit Fox Template Directory Customizer Section.
 *
 * @link       https://themeisle.com
 * @since      1.0.0
 *
 * @package    Template_Directory_OBFX_Module
 */
class OBFX_Template_Directory_Customizer_Section extends WP_Customize_Section {

	/**
	 * The type of customize section being rendered.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $type = 'obfx-template-directory-section';

	/**
	 * OBFX Module Directory
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @var string
	 */
	private $module_dir = '';

	/**
	 * Hestia_Hiding_Section constructor.
	 *
	 * @param WP_Customize_Manager $manager Customizer Manager.
	 * @param string $id Control id.
	 * @param array $args Arguments.
	 */
	public function __construct( WP_Customize_Manager $manager, $id, array $args = array() ) {
		parent::__construct( $manager, $id, $args );

		$this->module_dir = $args['module_directory'];
	}

	public function enqueue() {

	}

	/**
	 * Outputs the Underscore.js template.
	 *
	 * @since  1.1.47
	 * @access public
	 * @return void
	 */
	protected function render() {
		?>
        <div class="obfx-template-browser customizer">
            <div class="obfx-template">
                <h2 class="template-name template-header">Hestia About Page</h2>
                <div class="obfx-template-screenshot">
                    <img src="https://i0.wp.com/themes.svn.wordpress.org/hestia/1.1.52/screenshot.png" alt="">
                </div>
                <div class="obfx-template-details">
                    <p>This should be the template description.</p>
                </div><!-- /.obfx-template-details -->
            </div><!-- /.obfx-template -->
        </div> <!-- /.obfx-template-browser -->
		<?php
	}
}