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

		$this->active_default = true;

		$this->inc_dir = $this->get_dir() . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR;
		if ( ! defined( 'THEMEISLE_COMPANION_PATH' ) ) {
			define( 'THEMEISLE_COMPANION_PATH', $this->inc_dir );
		}
		if ( ! defined( 'THEMEISLE_COMPANION_URL' ) ) {
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


		if ( $this->is_hestia() ) {
			require_once $this->inc_dir . 'hestia' . DIRECTORY_SEPARATOR . 'functions.php';
			require_once $this->inc_dir . 'hestia' . DIRECTORY_SEPARATOR . 'common-functions.php';
			$theme_name = 'Hestia';

		}

		if ( $this->is_hestia_pro() ) {
			require_once $this->inc_dir . 'hestia' . DIRECTORY_SEPARATOR . 'common-functions.php';
			$theme_name = 'Hestia Pro';

		}
		if ( $this->is_shop_isle() ) {
			$theme_name = 'Shop Isle';
		}

		$this->name        = sprintf( __( '%s enhancements ', 'themeisle-companion' ), $theme_name );
		$this->description = sprintf( __( 'Module containing frontpage improvements for %s theme.', 'themeisle-companion' ), $theme_name );
	}

	private function is_zerif() {
		if ( $this->get_active_theme_dir() == 'zerif-lite' ) {
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

	private function is_hestia_pro() {
		if ( $this->get_active_theme_dir() == 'hestia-pro' ) {
			return true;
		}

		return false;
	}

	private function is_shop_isle() {
		include_once ABSPATH . 'wp-admin/includes/plugin.php';
		if ( is_plugin_active( 'shop-isle-companion/shop-isle-companion.php' ) ) {
			return false;
		}
		if ( $this->get_active_theme_dir() == 'shop-isle' ) {
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
		if ( $this->is_hestia() || $this->is_zerif() || $this->is_hestia_pro() || $this->is_shop_isle() ) {
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


	/**
	 * Function to import customizer big title settings into first slide.
	 */
	public function shop_isle_get_wporg_options() {
		/* import shop isle options */
		$shop_isle_mods = get_option( 'theme_mods_shop-isle' );

		if ( ! empty( $shop_isle_mods ) ) {

			$new_slider = new stdClass();

			foreach ( $shop_isle_mods as $shop_isle_mod_k => $shop_isle_mod_v ) {

				/* migrate Big title section to Slider section */
				if ( ( $shop_isle_mod_k == 'shop_isle_big_title_image' ) || ( $shop_isle_mod_k == 'shop_isle_big_title_title' ) || ( $shop_isle_mod_k == 'shop_isle_big_title_subtitle' ) || ( $shop_isle_mod_k == 'shop_isle_big_title_button_label' ) || ( $shop_isle_mod_k == 'shop_isle_big_title_button_link' ) ) {

					if ( $shop_isle_mod_k == 'shop_isle_big_title_image' ) {
						if ( ! empty( $shop_isle_mod_v ) ) {
							$new_slider->image_url = $shop_isle_mod_v;
						} else {
							$new_slider->image_url = '';
						}
					}

					if ( $shop_isle_mod_k == 'shop_isle_big_title_title' ) {
						if ( ! empty( $shop_isle_mod_v ) ) {
							$new_slider->text = $shop_isle_mod_v;
						} else {
							$new_slider->text = '';
						}
					}

					if ( $shop_isle_mod_k == 'shop_isle_big_title_subtitle' ) {
						if ( ! empty( $shop_isle_mod_v ) ) {
							$new_slider->subtext = $shop_isle_mod_v;
						} else {
							$new_slider->subtext = '';
						}
					}

					if ( $shop_isle_mod_k == 'shop_isle_big_title_button_label' ) {
						if ( ! empty( $shop_isle_mod_v ) ) {
							$new_slider->label = $shop_isle_mod_v;
						} else {
							$new_slider->label = '';
						}
					}

					if ( $shop_isle_mod_k == 'shop_isle_big_title_button_link' ) {
						if ( ! empty( $shop_isle_mod_v ) ) {
							$new_slider->link = $shop_isle_mod_v;
						} else {
							$new_slider->link = '';
						}
					}

					if ( ! empty( $new_slider->image_url ) || ! empty( $new_slider->text ) || ! empty( $new_slider->subtext ) || ! empty( $new_slider->link ) ) {
						$new_slider_encode = json_encode( array( $new_slider ) );
						set_theme_mod( 'shop_isle_slider', $new_slider_encode );
					}               
				} else {

					set_theme_mod( $shop_isle_mod_k, $shop_isle_mod_v );
				}
			}
		}

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
	 * Wrapper method for themeisle_hestia_fix_duplicate_widgets function call.
	 *
	 * @since 2.4.5
	 * @access  public
	 */
	public function hestia_fix_duplicate_widgets() {
		themeisle_hestia_fix_duplicate_widgets();
	}

	/**
	 * Wrapper method for themeisle_hestia_clients_default_content function call.
	 *
	 * @since   2.1.1
	 * @access  public
	 */
	public function hestia_load_clients_default_content() {
		return themeisle_hestia_clients_default_content();
	}

	/**
	 * Wrapper method for themeisle_hestia_enqueue_clients_style function call.
	 *
	 * @access  public
	 */
	public function hestia_enqueue_clients_style() {
		themeisle_hestia_enqueue_clients_style();
	}

	/**
	 * Wrapper method for themeisle_hestia_top_bar_default_alignment function call.
	 * 
	 * @since   2.1.1
	 * @access  public
	 */
	public function hestia_top_bar_default_alignment() {
		return themeisle_hestia_top_bar_default_alignment();
	}

	/**
	 * Wrapper method for themeisle_hestia_load_controls function call.
	 *
	 * @since   2.0.4
	 * @access  public
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
		define( 'THEMEISLE_COMPANION_VERSION', '2.0.0' );
		if ( $this->is_zerif() ) {
			$this->loader->add_action( 'widgets_init', $this, 'zerif_register_widgets' );
		}



		if ( $this->is_hestia() ) {
			$this->loader->add_action( 'after_setup_theme', $this, 'hestia_require' );
			$this->loader->add_action( 'after_setup_theme', $this, 'hestia_fix_duplicate_widgets' );
			$this->loader->add_action( 'wp_enqueue_scripts', $this, 'hestia_enqueue_clients_style' );
			$this->loader->add_filter( 'hestia_clients_bar_default_content', $this, 'hestia_load_clients_default_content' );
			$this->loader->add_filter( 'hestia_top_bar_alignment_default', $this, 'hestia_top_bar_default_alignment' );
			$this->loader->add_action( 'customize_register', $this, 'hestia_require_customizer', 0 );
			$this->loader->add_action( 'after_switch_theme', $this, 'hestia_set_front_page' );
		}

		if ( $this->is_hestia_pro() ) {
			$this->loader->add_action( 'after_setup_theme', $this, 'hestia_fix_duplicate_widgets' );
			$this->loader->add_filter( 'hestia_clients_bar_default_content', $this, 'hestia_load_clients_default_content' );
			$this->loader->add_filter( 'hestia_top_bar_alignment_default', $this, 'hestia_top_bar_default_alignment' );
		}

		if ( $this->is_shop_isle() ) {
			require_once $this->inc_dir . 'shop-isle' . DIRECTORY_SEPARATOR . 'functions.php';
		}
	}

	/**
	 * Import mods if is shop isle.
	 */
	public function activate() {
		if ( $this->is_shop_isle() ) {
			$this->shop_isle_get_wporg_options();
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
