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
	 * @since  1.1.47
	 * @access public
	 * @var    string
	 */
	public $type = 'obfx-template-directory-section';


	public $module_dir = '';
	/**
	 * Hestia_Hiding_Section constructor.
	 *
	 * @param WP_Customize_Manager $manager Customizer Manager.
	 * @param string               $id Control id.
	 * @param array                $args Arguments.
	 */
	public function __construct( WP_Customize_Manager $manager, $id, array $args = array() ) {
		parent::__construct( $manager, $id, $args );

		$this->module_dir = $args['module_directory'];

        add_action( 'customize_controls_init', array( $this, 'enqueue' ) );
	}

	/**
	 * Enqueue function.
	 *
	 * @since  1.1.47
	 * @access public
	 * @return void
	 */
	public function enqueue() {
//		wp_enqueue_script( 'obfx-template-dir-customizer-script', $this->module_dir . '/js/customizer-script.js', array( 'jquery' ), '1.0.0', true );
		wp_enqueue_style( 'obfx-template-dir-style', $this->module_dir . '/css/admin.css', array(), '1.0.0' );
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
                        <img src="http://localhost/wordpress/wp-content/themes/hestia-pro/screenshot.png" alt="">
                    </div>
                    <div class="obfx-template-actions">
                        <a class="button"
                           href="<?php echo esc_url( admin_url() . 'customize.php?url=' . urlencode( $customizer ) ); ?>">Preview</a>
                        <a class="button button-primary" href="#">Import</a>
                    </div><!-- /.obfx-template-actions -->
                </div><!-- /.obfx-template -->

               </div> <!-- /.obfx-template-browser -->
		<?php
	}
}