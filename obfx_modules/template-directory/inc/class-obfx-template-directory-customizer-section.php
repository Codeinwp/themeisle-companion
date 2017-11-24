<?php
/**
 * Orbit fox template directory customizer section.
 *
 * @package    Orbit_Fox_Modules
 * @subpackage Orbit_Fox_Modules/template-directory
 */

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
	 * Templates list.
	 *
	 * @var array
	 */
	private $templates = array();

	/**
	 * The previewed template slug.
	 *
	 * @var string
	 */
	private $called_template = '';

	/**
	 * The tempalte to render required plugins install button.
	 *
	 * @var string
	 */
	private $required_plugins = '';

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

		if ( ! empty( $args['templates'] ) ) {
			$this->templates = $args['templates'];
		}
		if ( ! empty( $args['requires_plugins'] ) ) {
			$this->required_plugins = $args['requires_plugins'];
		}

		$this->called_template = isset( $_GET['obfx_template_id'] ) ? $_GET['obfx_template_id'] : '';
	}

	/**
	 * Enqueue scripts designated for this control.
	 */
	public function enqueue() {
		wp_enqueue_script( 'obfx-template-dir-script', $this->module_dir . 'template-directory/js/customizer.js', array( 'jquery', 'customize-preview' ), '1.0.0', true );
	}

	/**
	 * Outputs the Underscore.js template.
	 *
	 * @since  1.1.47
	 * @access public
	 * @return void
	 */
	protected function render() {
		$html = '';
		if ( ! empty( $this->templates ) ) {
			$html .= '<div class="obfx-template-browser customizer">';
			foreach ( $this->templates as $template => $properties ) {
				$active = '';
				if ( $template === $this->called_template ) {
					$active = ' active';
				}
				$html .= '<div class="obfx-template' . esc_attr( $active ) . '" data-demo-url="' . esc_url( $properties['demo_url'] ) . '" data-slug="' . $template . '" data-template-file="' . esc_url( $properties['import_file'] ) . '">';
				$html .= '<h2 class="template-name template-header">' . esc_html( $properties['title'] ) . '</h2>';
				$html .= '<div class="obfx-template-screenshot">';
				$html .= '<img src="' . esc_url( $properties['screenshot'] ) . '" alt="' . esc_html( $properties['title'] ) . '">';
				$html .= '</div>';
				$html .= '<div class="obfx-template-details">';
				$html .= '<p>' . esc_html( $properties['description'] ) . '</p>';
				$html .= '</div>'; // .obfx-template-details
				$html .= '</div>'; // .obfx-template
			}
			$html .= '</div>'; // .obfx-template-browser
			if ( ! empty( $this->required_plugins ) ) {
				$html .= $this->required_plugins;
			}
		}
		echo $html;
	}
}
