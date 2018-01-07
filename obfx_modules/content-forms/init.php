<?php
/**
 * The module for content forms.
 *
 * @link       https://themeisle.com
 * @since      1.0.0
 *
 * @package    Content_Forms_OBFX_Module
 */

/**
 * The class for content forms.
 *
 * @package    Content_Forms_OBFX_Module
 * @author     Themeisle <friends@themeisle.com>
 * @codeCoverageIgnore
 */
class Content_Forms_OBFX_Module extends Orbit_Fox_Module_Abstract {

	/**
	 * A list of available content forms
	 * @var array
	 */
	private $content_forms = array(
		'contact-form',
		'newsletter',
		'registration'
	);

	/**
	 * OBFX_Module_Content_Forms  constructor.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function __construct() {
		parent::__construct();
		$this->name           = __( 'Content Forms', 'themeisle-companion' );
		$this->description    = __( 'Adds Content Forms to the most popular builders: Elementor or Beaver. More to come!', 'themeisle-companion' );
		$this->active_default = true;
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

		return array();
	}

	/**
	 * Method to define the options fields for the module
	 *
	 * @since   1.0.0
	 * @access  public
	 * @return array
	 */
	public function options() {
		$options = array(
			array(
				'id'      => 'contact',
				'name'    => 'contact',
				'title'   => 'Contact',
				'type'    => 'toggle',
				'label'   => 'Enable the Contact form',
				'default' => '1',
			),
			array(
				'id'      => 'newsletter',
				'name'    => 'newsletter',
				'title'   => 'Newsletter',
				'type'    => 'toggle',
				'label'   => 'Enable the Newsletter form',
				'default' => '1',
			),
			array(
				'id'      => 'registration',
				'name'    => 'registration',
				'title'   => 'Registration',
				'type'    => 'toggle',
				'label'   => 'Enable the Registration form',
				'default' => '1',
			),
//			array(
//				'id'      => 'elementor',
//				'name'    => 'elementor',
//				'title'   => 'Elementor',
//				'type'    => 'toggle',
//				'label'   => 'Enable content forms Elementor widget',
//				'default' => '1',
//			),
//			array(
//				'id'      => 'gutenberg',
//				'name'    => 'gutenberg',
//				'title'   => 'Gutenberg',
//				'type'    => 'toggle',
//				'label'   => 'Enable content forms Gutenberg block',
//				'default' => '1',
//			),
//			array(
//				'id'      => 'beaver',
//				'name'    => 'beaver',
//				'title'   => 'Beaver',
//				'type'    => 'toggle',
//				'label'   => 'Enable content forms Beaver module',
//				'default' => '1',
//			),
		);

		return $options;
	}

	/**
	 * Method to define hooks needed.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function hooks() {
		add_action( 'init_themeisle_content_forms', array( $this, 'load_forms' ) );
	}

	public function load_forms() {

		if ( $this->get_option('contact') ) {
			new \ThemeIsle\ContentForms\ContactForm();
		}

		if ( $this->get_option('newsletter') ) {
			new \ThemeIsle\ContentForms\NewsletterForm();
		}

		if ( $this->get_option('registration') ) {
			new \ThemeIsle\ContentForms\RegistrationForm();
		}

	}
}