<?php
/**
 * ThemeIsle Companion Legacy Core Orbit Fox Module.
 *
 * @link       https://themeisle.com
 * @since      1.0.0
 *
 * @package    Companion_Legacy_OBFX_Module
 */

/**
 * The class defines a new module to be used by Orbit Fox plugin.
 *
 * @package    Companion_Legacy_OBFX_Module
 * @author     Themeisle <friends@themeisle.com>
 * @codeCoverageIgnore
 */
class Companion_Legacy_OBFX_Module extends Orbit_Fox_Module_Abstract {

	private $inc_dir;

	/**
	 * Companion_Legacy_OBFX_Module constructor.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function __construct() {
		parent::__construct();
		$this->name        = __( 'Companion Legacy', 'themeisle-companion' );
		$this->description = __( 'Module containing legacy functionality from ThemeIsle Companion.', 'themeisle-companion' );

		$this->auto = true;

		$this->inc_dir = $this->get_dir() . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR;
		if( ! defined('THEMEISLE_COMPANION_PATH') ) {
			define( 'THEMEISLE_COMPANION_PATH', $this->inc_dir );
		}
		if( ! defined('THEMEISLE_COMPANION_URL') ) {
			define( 'THEMEISLE_COMPANION_URL', plugin_dir_url( $this->inc_dir ) );
		}
		$theme_name = '';
		if ( $this->is_zerif() ) {
			$theme_name = 'Zerif';
			require_once $this->inc_dir . 'zerif-lite' . DIRECTORY_SEPARATOR . 'widgets' . DIRECTORY_SEPARATOR . 'widget-focus.php';
			require_once $this->inc_dir . 'zerif-lite' . DIRECTORY_SEPARATOR . 'widgets' . DIRECTORY_SEPARATOR . 'widget-testimonial.php';
			require_once $this->inc_dir . 'zerif-lite' . DIRECTORY_SEPARATOR . 'widgets' . DIRECTORY_SEPARATOR . 'widget-clients.php';
			require_once $this->inc_dir . 'zerif-lite' . DIRECTORY_SEPARATOR . 'widgets' . DIRECTORY_SEPARATOR . 'widget-team.php';
			require_once $this->inc_dir . 'zerif-lite' . DIRECTORY_SEPARATOR . 'functions.php';
		}

		if ( $this->is_rhea() ) {
			$theme_name = 'Rhea';
			require_once $this->inc_dir . 'rhea' . DIRECTORY_SEPARATOR . 'widgets' . DIRECTORY_SEPARATOR . 'features.widget.php';
			require_once $this->inc_dir . 'rhea' . DIRECTORY_SEPARATOR . 'widgets' . DIRECTORY_SEPARATOR . 'about.widget.php';
			require_once $this->inc_dir . 'rhea' . DIRECTORY_SEPARATOR . 'widgets' . DIRECTORY_SEPARATOR . 'hours.widget.php';
			require_once $this->inc_dir . 'rhea' . DIRECTORY_SEPARATOR . 'widgets' . DIRECTORY_SEPARATOR . 'contact.widget.php';
			require_once $this->inc_dir . 'rhea' . DIRECTORY_SEPARATOR . 'widgets' . DIRECTORY_SEPARATOR . 'progress-bar.widget.php';
			require_once $this->inc_dir . 'rhea' . DIRECTORY_SEPARATOR . 'widgets' . DIRECTORY_SEPARATOR . 'icon-box.widget.php';
		}

		if ( $this->is_hestia() ) {
			require_once $this->inc_dir . 'hestia' . DIRECTORY_SEPARATOR . 'functions.php';
			$theme_name = 'Hestia';

		}
		if ( ! empty( $theme_name ) ) {
			$this->notices = array(
				array(
					'type'           => 'primary',
					'title'          => 'How Orbit Fox helps you ?',
					'message'        => str_replace( '{theme}', $theme_name, 'Orbit Fox  enhances {theme} theme with extra functionalities like widgets and custom sections. Moreover we have a simple sharing and report module and added the ground for more useful ones for security, caching and analytics.  ' ),
					'display_always' => false
				)
			);
		}
	}

	private function is_zerif() {
		if ( $this->get_active_theme_dir() == 'zerif-lite' ) {
			return true;
		}

		return false;
	}

	private function is_rhea() {
		if ( $this->get_active_theme_dir( true ) == 'rhea' ) {
			return true;
		}

		return false;
	}

	private function is_hestia() {
		if ( $this->get_active_theme_dir() == 'hestia' ) {
			return true;
		}

		return false;
	}

	/**
	 * Determine if module should be loaded.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @return bool
	 */
	public function enable_module() {
		if ( $this->is_hestia() || $this->is_rhea() || $this->is_zerif() ) {
			return true;
		} else {
			return false;
		}
	}

	public function zerif_register_widgets() {
		register_widget( 'zerif_ourfocus' );
		register_widget( 'zerif_testimonial_widget' );
		register_widget( 'zerif_clients_widget' );
		register_widget( 'zerif_team_widget' );

		$themeisle_companion_flag = get_option( 'themeisle_companion_flag' );
		if ( empty( $themeisle_companion_flag ) && function_exists( 'themeisle_populate_with_default_widgets' ) ) {
			themeisle_populate_with_default_widgets();
		}
	}

	public function rhea_register_widgets() {
		register_widget( 'rhea_features_block' );
		register_widget( 'Rhea_Progress_Bar' );
		register_widget( 'Rhea_Icon_Box' );
		register_widget( 'Rhea_About_Company' );
		register_widget( 'Rhea_Hours' );
		register_widget( 'Rhea_Contact_Company' );
	}

	function rhea_load_custom_wp_admin_style() {
		wp_enqueue_style( 'fontawesome-style', get_template_directory_uri() . '/css/font-awesome.min.css' );
		wp_enqueue_style( 'rhea-admin-style', trailingslashit( THEMEISLE_COMPANION_URL ) . 'inc/rhea/assets/css/admin-style.css' );
		wp_enqueue_script( 'fontawesome-icons', trailingslashit( THEMEISLE_COMPANION_URL ) . 'inc/rhea/assets/js/icons.js', false, '1.0.0' );
		wp_enqueue_script( 'jquery-ui-dialog' );
		wp_enqueue_script( 'fontawesome-script', trailingslashit( THEMEISLE_COMPANION_URL ) . 'inc/rhea/assets/js/fontawesome.jquery.js', false, '1.0.0' );
	}

	public function rhea_add_html_to_admin_footer() {
		$output = '
        <div id="fontawesome-popup">
            <div class="left-side">
                <label for="fontawesome-live-search">' . esc_html_e( 'Search icon', 'themeisle-companion' ) . ':</label>
                <ul class="filter-icons">
                    <li data-filter="all" class="active">' . esc_html_e( 'All Icons', 'themeisle-companion' ) . '</li>
                </ul>
            </div>
            <div class="right-side">
            </div>
        </div>
        ';

		echo $output;
	}

	/**
	 * Wrapper method for themeisle_hestia_require function call.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function hestia_require() {
		themeisle_hestia_require();
	}

	/**
	 * Require files that needs to be in customizer.
	 *
	 * @since 2.0.4
	 */
	public function hestia_require_customizer() {
		themeisle_hestia_load_controls();
	}

	/**
	 * Wrapper method for themeisle_hestia_set_frontpage function call.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function hestia_set_front_page() {
		themeisle_hestia_set_frontpage();
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
	 * Method to define hooks needed.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function hooks() {
		if ( $this->is_zerif() ) {
			$this->loader->add_action( 'widgets_init', $this, 'zerif_register_widgets' );
		}

		if ( $this->is_rhea() ) {
			$this->loader->add_action( 'widgets_init', $this, 'rhea_register_widgets' );
			$this->loader->add_action( 'admin_enqueue_scripts', $this, 'rhea_load_custom_wp_admin_style' );
			$this->loader->add_action( 'admin_footer', $this, 'rhea_add_html_to_admin_footer' );
			$this->loader->add_action( 'customize_controls_print_footer_scripts', $this, 'rhea_add_html_to_admin_footer' );
		}

		if ( $this->is_hestia() ) {
		    define( 'THEMEISLE_COMPANION_VERSION', '2.0.0' );
			$this->loader->add_action( 'after_setup_theme', $this, 'hestia_require' );
			$this->loader->add_action( 'customize_register', $this, 'hestia_require_customizer', 0 );
			$this->loader->add_action( 'after_switch_theme', $this, 'hestia_set_front_page' );
		}
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
	}

	/**
	 * Method to define the options fields for the module
	 *
	 * @since   1.0.0
	 * @access  public
	 * @return array
	 */
	public function options() {
		return array();
	}
}